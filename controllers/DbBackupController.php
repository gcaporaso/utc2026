<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;

/**
 * DbBackupController — backup del database MySQL tramite mysqldump.
 * Accessibile solo agli utenti autenticati con ruolo admin.
 */
class DbBackupController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],   // solo utenti autenticati; ruoli specifici gestiti da mdm/admin RBAC
                    ],
                ],
            ],
        ];
    }

    /**
     * Lista i backup esistenti e offre il form per crearne uno nuovo.
     */
    public function actionIndex()
    {
        $backupDir = Yii::getAlias('@app/runtime/backups');
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0750, true);
        }

        $files = glob($backupDir . '/*.sql.gz');
        if ($files === false) $files = [];

        // Ordina per data decrescente
        usort($files, function ($a, $b) {
            return filemtime($b) - filemtime($a);
        });

        $backups = array_map(function ($path) {
            return [
                'name'    => basename($path),
                'size'    => filesize($path),
                'date'    => date('d/m/Y H:i:s', filemtime($path)),
                'path'    => $path,
            ];
        }, $files);

        $restoreLog = $this->_readRestoreLog();
        return $this->render('index', compact('backups', 'restoreLog'));
    }

    /**
     * Esegue mysqldump e salva il file compresso in runtime/backups/.
     * Redirige all'index con un messaggio flash.
     */
    public function actionCreate()
    {
        if (!Yii::$app->request->isPost) {
            return $this->redirect(['index']);
        }

        $backupDir = Yii::getAlias('@app/runtime/backups');
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0750, true);
        }

        [$host, $port, $dbname, $user, $password] = $this->_dbParams();

        if (!$dbname) {
            Yii::$app->session->addFlash('error', 'Impossibile determinare il nome del database dal DSN.');
            return $this->redirect(['index']);
        }

        $filename = $dbname . '_' . date('Ymd_His') . '.sql.gz';
        $filepath = $backupDir . '/' . $filename;
        $optFile  = $this->_writeOptFile($password);

        $cmd = sprintf(
            'mysqldump --defaults-extra-file=%s -h %s -P %s -u %s --single-transaction --routines --triggers %s | gzip > %s 2>&1',
            escapeshellarg($optFile),
            escapeshellarg($host),
            escapeshellarg($port),
            escapeshellarg($user),
            escapeshellarg($dbname),
            escapeshellarg($filepath)
        );

        exec($cmd, $output, $returnCode);
        @unlink($optFile);

        if ($returnCode !== 0 || !file_exists($filepath) || filesize($filepath) === 0) {
            @unlink($filepath);
            Yii::$app->session->addFlash('error', 'Backup fallito. Codice: ' . $returnCode
                . (count($output) ? ' — ' . implode(' ', $output) : ''));
        } else {
            Yii::$app->session->addFlash('success', 'Backup creato: ' . $filename
                . ' (' . $this->formatSize(filesize($filepath)) . ')');
        }

        return $this->redirect(['index']);
    }

    /**
     * Ripristina il database da un backup selezionato.
     *
     * Prima del ripristino crea automaticamente un backup di sicurezza
     * del database corrente, poi esegue il restore e registra l'operazione
     * nel log di versione (runtime/backups/restore_log.json).
     */
    public function actionRestore()
    {
        if (!Yii::$app->request->isPost) {
            return $this->redirect(['index']);
        }

        $file      = Yii::$app->request->post('file', '');
        $filename  = basename($file);
        $backupDir = Yii::getAlias('@app/runtime/backups');
        $filepath  = $backupDir . '/' . $filename;

        if (!preg_match('/^[\w\-]+\.sql\.gz$/', $filename) || !is_file($filepath)) {
            throw new \yii\web\NotFoundHttpException('File non trovato.');
        }

        [$host, $port, $dbname, $user, $password] = $this->_dbParams();

        // --- 1. Backup automatico di sicurezza prima del ripristino ---
        $safeFile = $backupDir . '/' . $dbname . '_pre_restore_' . date('Ymd_His') . '.sql.gz';
        $optFile  = $this->_writeOptFile($password);

        $cmdBackup = sprintf(
            'mysqldump --defaults-extra-file=%s -h %s -P %s -u %s --single-transaction --routines --triggers %s | gzip > %s 2>&1',
            escapeshellarg($optFile),
            escapeshellarg($host),
            escapeshellarg($port),
            escapeshellarg($user),
            escapeshellarg($dbname),
            escapeshellarg($safeFile)
        );
        exec($cmdBackup, $outBackup, $rcBackup);

        if ($rcBackup !== 0 || !file_exists($safeFile) || filesize($safeFile) === 0) {
            @unlink($safeFile);
            @unlink($optFile);
            Yii::$app->session->addFlash('error',
                'Impossibile creare il backup di sicurezza pre-ripristino. Operazione annullata.');
            return $this->redirect(['index']);
        }

        // --- 2. Ripristino ---
        $cmdRestore = sprintf(
            'gunzip -c %s | mysql --defaults-extra-file=%s -h %s -P %s -u %s %s 2>&1',
            escapeshellarg($filepath),
            escapeshellarg($optFile),
            escapeshellarg($host),
            escapeshellarg($port),
            escapeshellarg($user),
            escapeshellarg($dbname)
        );
        exec($cmdRestore, $outRestore, $rcRestore);
        @unlink($optFile);

        if ($rcRestore !== 0) {
            Yii::$app->session->addFlash('error',
                'Ripristino fallito (codice ' . $rcRestore . ')'
                . (count($outRestore) ? ': ' . implode(' ', array_slice($outRestore, 0, 3)) : '')
                . '. Il backup di sicurezza è stato salvato come: ' . basename($safeFile));
            return $this->redirect(['index']);
        }

        // --- 3. Registra l'operazione nel log di versione ---
        $this->_appendRestoreLog([
            'timestamp'   => date('Y-m-d H:i:s'),
            'restored_from' => $filename,
            'safety_backup' => basename($safeFile),
            'user'        => Yii::$app->user->identity->username ?? Yii::$app->user->id,
        ]);

        Yii::$app->session->addFlash('success',
            'Database ripristinato da <strong>' . htmlspecialchars($filename) . '</strong>. '
            . 'Backup di sicurezza salvato: ' . basename($safeFile));

        return $this->redirect(['index']);
    }

    /**
     * Scarica un file di backup.
     */
    public function actionDownload(string $file)
    {
        $backupDir = Yii::getAlias('@app/runtime/backups');
        // Sicurezza: impedisce path traversal
        $filename = basename($file);
        $filepath = $backupDir . '/' . $filename;

        if (!preg_match('/^[\w\-]+\.sql\.gz$/', $filename) || !is_file($filepath)) {
            throw new \yii\web\NotFoundHttpException('File non trovato.');
        }

        return Yii::$app->response->sendFile($filepath, $filename, [
            'mimeType' => 'application/gzip',
        ]);
    }

    /**
     * Elimina un file di backup.
     */
    public function actionDelete(string $file)
    {
        $backupDir = Yii::getAlias('@app/runtime/backups');
        $filename  = basename($file);
        $filepath  = $backupDir . '/' . $filename;

        if (!preg_match('/^[\w\-]+\.sql\.gz$/', $filename) || !is_file($filepath)) {
            throw new \yii\web\NotFoundHttpException('File non trovato.');
        }

        @unlink($filepath);
        Yii::$app->session->addFlash('success', 'Backup eliminato: ' . $filename);
        return $this->redirect(['index']);
    }

    // -----------------------------------------------------------------------
    // Helper privati
    // -----------------------------------------------------------------------

    /** Restituisce [host, port, dbname, user, password] dalla configurazione Yii. */
    private function _dbParams(): array
    {
        $db   = Yii::$app->db;
        $dsn  = $db->dsn;
        preg_match('/host=([^;]+)/', $dsn, $mHost);
        preg_match('/dbname=([^;]+)/', $dsn, $mDb);
        preg_match('/port=(\d+)/', $dsn, $mPort);
        return [
            $mHost[1]  ?? '127.0.0.1',
            $mPort[1]  ?? '3306',
            $mDb[1]    ?? '',
            $db->username,
            $db->password,
        ];
    }

    /** Scrive un file temporaneo di opzioni MySQL con la password e restituisce il path. */
    private function _writeOptFile(string $password): string
    {
        $f = tempnam(sys_get_temp_dir(), 'myopt_');
        file_put_contents($f, "[client]\npassword=" . addslashes($password) . "\n");
        chmod($f, 0600);
        return $f;
    }

    /** Aggiunge una voce al log dei ripristini (runtime/backups/restore_log.json). */
    private function _appendRestoreLog(array $entry): void
    {
        $logFile = Yii::getAlias('@app/runtime/backups/restore_log.json');
        $log     = [];
        if (is_file($logFile)) {
            $log = json_decode(file_get_contents($logFile), true) ?: [];
        }
        array_unshift($log, $entry);          // più recente in cima
        file_put_contents($logFile, json_encode($log, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /** Legge il log dei ripristini. */
    public function _readRestoreLog(): array
    {
        $logFile = Yii::getAlias('@app/runtime/backups/restore_log.json');
        if (!is_file($logFile)) return [];
        return json_decode(file_get_contents($logFile), true) ?: [];
    }

    private function formatSize(int $bytes): string
    {
        if ($bytes >= 1048576) return round($bytes / 1048576, 1) . ' MB';
        if ($bytes >= 1024)    return round($bytes / 1024, 1) . ' KB';
        return $bytes . ' B';
    }
}
