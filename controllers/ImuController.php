<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\ImuAliquota;
use app\models\ImuCoefficiente;
use app\models\ImuAreaEdificabile;
use app\models\DatiCensuari;
use app\models\DatiMappe;
use app\helpers\BelfioreHelper;

class ImuController extends Controller
{
    // ─────────────────────────────────────────────────────────────────────────
    // Pagina parametri (aliquote + coefficienti)
    // ─────────────────────────────────────────────────────────────────────────

    public function actionIndex(): string
    {
        $this->layout = 'main';

        $anno      = (int)Yii::$app->request->get('anno', date('Y'));
        $aliquote  = ImuAliquota::find()->where(['anno' => $anno])->orderBy('tipo')->all();
        $coeffs    = ImuCoefficiente::find()->orderBy('categoria')->all();
        $anniList  = Yii::$app->db->createCommand(
            'SELECT DISTINCT anno FROM imu_aliquote ORDER BY anno DESC'
        )->queryColumn();

        return $this->render('index', compact('anno', 'aliquote', 'coeffs', 'anniList'));
    }

    /** AJAX: salva/aggiorna un'aliquota */
    public function actionSaveAliquota(): Response
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data = Yii::$app->request->post();

        $id    = (int)($data['id'] ?? 0);
        $model = $id ? ImuAliquota::findOne($id) : new ImuAliquota();
        if (!$model) { return $this->asJson(['ok' => false, 'error' => 'Record non trovato.']); }

        $model->anno        = (int)($data['anno']        ?? date('Y'));
        $model->tipo        = trim($data['tipo']         ?? '');
        $model->descrizione = trim($data['descrizione']  ?? '');
        $model->aliquota    = (float)str_replace(',', '.', $data['aliquota'] ?? '0');
        $model->detrazione  = (float)str_replace(',', '.', $data['detrazione'] ?? '0');
        $model->attiva      = isset($data['attiva']) ? 1 : 0;
        $model->note        = trim($data['note'] ?? '');

        if (!$model->save()) {
            return $this->asJson(['ok' => false, 'error' => implode('; ', $model->getFirstErrors())]);
        }
        return $this->asJson(['ok' => true, 'id' => $model->id]);
    }

    /** AJAX: elimina un'aliquota */
    public function actionDeleteAliquota(): Response
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $id    = (int)Yii::$app->request->post('id', 0);
        $model = ImuAliquota::findOne($id);
        if (!$model) { return $this->asJson(['ok' => false]); }
        $model->delete();
        return $this->asJson(['ok' => true]);
    }

    /** AJAX: salva coefficiente */
    public function actionSaveCoeff(): Response
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data = Yii::$app->request->post();
        $id   = (int)($data['id'] ?? 0);
        $model = $id ? ImuCoefficiente::findOne($id) : null;
        if (!$model) { return $this->asJson(['ok' => false, 'error' => 'Record non trovato.']); }

        $model->coefficiente = (int)($data['coefficiente'] ?? 160);
        if (!$model->save()) {
            return $this->asJson(['ok' => false, 'error' => implode('; ', $model->getFirstErrors())]);
        }
        return $this->asJson(['ok' => true]);
    }

    /** AJAX: aggiungi aliquote per nuovo anno (copia dall'anno precedente) */
    public function actionCopiaAnno(): Response
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $annoSrc = (int)Yii::$app->request->post('anno_src', 0);
        $annoDst = (int)Yii::$app->request->post('anno_dst', 0);
        if ($annoSrc < 2000 || $annoDst < 2000 || $annoSrc === $annoDst) {
            return $this->asJson(['ok' => false, 'error' => 'Anni non validi.']);
        }
        $src = ImuAliquota::find()->where(['anno' => $annoSrc])->all();
        foreach ($src as $row) {
            $nuovo              = new ImuAliquota();
            $nuovo->anno        = $annoDst;
            $nuovo->tipo        = $row->tipo;
            $nuovo->descrizione = $row->descrizione;
            $nuovo->aliquota    = $row->aliquota;
            $nuovo->detrazione  = $row->detrazione;
            $nuovo->attiva      = $row->attiva;
            $nuovo->note        = $row->note;
            $nuovo->save();
        }
        return $this->asJson(['ok' => true]);
    }

    /** Serve il PDF generato tramite route Yii */
    public function actionDownload(): Response
    {
        $filename = basename(Yii::$app->request->get('file', ''));
        if (!$filename || !preg_match('/^(?:imu|f24)_[\w_\-]+\.pdf$/i', $filename)) {
            throw new \yii\web\BadRequestHttpException('File non valido.');
        }
        $filePath = Yii::getAlias('@webroot') . '/imu/' . $filename;
        if (!file_exists($filePath)) {
            throw new \yii\web\NotFoundHttpException('File PDF non trovato.');
        }
        return Yii::$app->response->sendFile($filePath, $filename, ['mimeType' => 'application/pdf']);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Pagina Aree Edificabili (valori unitari €/mq per zona PRG)
    // ─────────────────────────────────────────────────────────────────────────

    public function actionAreeEdificabili(): string
    {
        $this->layout = 'main';
        $anno  = (int)Yii::$app->request->get('anno', date('Y'));
        $aree  = ImuAreaEdificabile::find()->where(['anno' => $anno])->orderBy('zona')->all();
        $anniList = Yii::$app->db->createCommand(
            'SELECT DISTINCT anno FROM imu_aree_edificabili ORDER BY anno DESC'
        )->queryColumn();
        $zoneList = $this->getZonePrg();
        return $this->render('aree-edificabili', compact('anno', 'aree', 'anniList', 'zoneList'));
    }

    /** AJAX: salva/aggiorna un valore area edificabile */
    public function actionSaveArea(): Response
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data = Yii::$app->request->post();
        $id   = (int)($data['id'] ?? 0);
        $model = $id ? ImuAreaEdificabile::findOne($id) : new ImuAreaEdificabile();
        if (!$model) { return $this->asJson(['ok' => false, 'error' => 'Record non trovato.']); }

        $model->anno        = (int)($data['anno']        ?? date('Y'));
        $model->zona        = trim($data['zona']         ?? '');
        $model->descrizione = trim($data['descrizione']  ?? '');
        $model->valore_mq   = (float)str_replace(',', '.', $data['valore_mq'] ?? '0');
        $model->attiva      = isset($data['attiva']) ? 1 : 0;
        $model->note        = trim($data['note'] ?? '');

        if (!$model->save()) {
            return $this->asJson(['ok' => false, 'error' => implode('; ', $model->getFirstErrors())]);
        }
        return $this->asJson(['ok' => true, 'id' => $model->id]);
    }

    /** AJAX: elimina un valore area edificabile */
    public function actionDeleteArea(): Response
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $id    = (int)Yii::$app->request->post('id', 0);
        $model = ImuAreaEdificabile::findOne($id);
        if (!$model) { return $this->asJson(['ok' => false]); }
        $model->delete();
        return $this->asJson(['ok' => true]);
    }

    /** AJAX: copia valori da un anno a un altro */
    public function actionCopiaAreaAnno(): Response
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $annoSrc = (int)Yii::$app->request->post('anno_src', 0);
        $annoDst = (int)Yii::$app->request->post('anno_dst', 0);
        if ($annoSrc < 2000 || $annoDst < 2000 || $annoSrc === $annoDst) {
            return $this->asJson(['ok' => false, 'error' => 'Anni non validi.']);
        }
        $src = ImuAreaEdificabile::find()->where(['anno' => $annoSrc])->all();
        foreach ($src as $row) {
            $nuovo              = new ImuAreaEdificabile();
            $nuovo->anno        = $annoDst;
            $nuovo->zona        = $row->zona;
            $nuovo->descrizione = $row->descrizione;
            $nuovo->valore_mq   = $row->valore_mq;
            $nuovo->attiva      = $row->attiva;
            $nuovo->note        = $row->note;
            $nuovo->save();
        }
        return $this->asJson(['ok' => true]);
    }

    /**
     * Carica dal PRG GeoJSON le zone edificabili (codice + descrizione).
     * @return array<string, string>  ['Ct' => 'Ct - Zone turistico-alberghiere', …]
     */
    private function getZonePrg(): array
    {
        $prgPath = Yii::getAlias('@webroot') . '/mappe/b542/prg_epsg7792.geojson';
        if (!file_exists($prgPath)) return [];

        $prg = json_decode(file_get_contents($prgPath));
        if (!$prg) return [];

        $zones = [];
        foreach ($prg->features as $ft) {
            $z    = trim($ft->properties->z    ?? '');
            $desc = trim($ft->properties->estes ?? '');
            if ($z === '') continue;
            if (!isset($zones[$z])) {
                $zones[$z] = $z . ($desc ? ' — ' . $desc : '');
            }
        }
        ksort($zones);
        return $zones;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Pagina calcolo IMU interattivo
    // ─────────────────────────────────────────────────────────────────────────

    public function actionCalcolo(): string
    {
        $this->layout = 'main';
        $anno = (int)Yii::$app->request->get('anno', date('Y'));
        return $this->render('calcolo', ['anno' => $anno]);
    }

    /**
     * AJAX: cerca immobili del contribuente nel catasto SQLite.
     * Restituisce persona + immobili + aliquote + coefficienti come JSON.
     */
    public function actionRicerca(): Response
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $cognome  = strtoupper(trim(Yii::$app->request->post('cognome',  '')));
        $nome     = strtoupper(trim(Yii::$app->request->post('nome',     '')));
        $codFisc  = strtoupper(trim(Yii::$app->request->post('cod_fisc', '')));
        $dataNasc = trim(Yii::$app->request->post('data_nasc', ''));
        $anno     = (int)Yii::$app->request->post('anno', date('Y'));

        if (!$cognome && !$codFisc) {
            return $this->asJson(['ok' => false, 'error' => 'Inserire almeno il Cognome o il Codice Fiscale.']);
        }

        // --- trova DB catasto ---
        [$dbPath, $codComune] = $this->getDbInfo();
        if (!$dbPath || !file_exists($dbPath)) {
            return $this->asJson(['ok' => false, 'error' => 'Database catasto non trovato. Aggiornare i dati censuari.']);
        }

        try {
            $db = new \SQLite3($dbPath, SQLITE3_OPEN_READONLY);
        } catch (\Exception $e) {
            return $this->asJson(['ok' => false, 'error' => 'Impossibile aprire il database: ' . $e->getMessage()]);
        }

        // Converte GG/MM/AAAA → AAAA-MM-GG per la query
        $dataNascDb = '';
        if ($dataNasc && preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $dataNasc, $m)) {
            $dataNascDb = $m[3] . '-' . str_pad($m[2], 2, '0', STR_PAD_LEFT) . '-' . str_pad($m[1], 2, '0', STR_PAD_LEFT);
        }

        $immobili   = $this->queryFabbricatiConRendita($db, $cognome, $nome, $dataNascDb, $codFisc);
        $personaRow = $this->queryPersonaFisicaData($db, $cognome, $nome, $dataNascDb, $codFisc);
        $terreniRaw = $this->queryTerreni($db, $cognome, $nome, $dataNascDb, $codFisc);
        $db->close();

        // Verifica edificabilità dei terreni (usa geoPHP + PRG GeoJSON)
        $areeEdificabili = $this->checkTerreniEdificabili($terreniRaw);
        $immobili        = array_merge($immobili, $areeEdificabili);

        if (empty($immobili)) {
            $sogg = $cognome . ($nome ? ' ' . $nome : '') . ($codFisc ? ' (CF: ' . $codFisc . ')' : '');
            return $this->asJson(['ok' => false, 'error' => 'Nessun immobile nel catasto intestato a ' . $sogg . '.']);
        }

        // Completa CF dal catasto se mancante
        if (!$codFisc && !empty($personaRow['codFiscale'])) {
            $codFisc = strtoupper(trim($personaRow['codFiscale']));
        }

        // Decodifica CF per data nascita / sesso
        $sessoStr    = 'M';
        $codBelfiore = strtoupper(trim($personaRow['luogoNascita'] ?? ''));
        $luogoNasc   = trim($personaRow['comuneNascitaNome'] ?? $personaRow['luogoNascita'] ?? '');
        // BelfioreHelper ha priorità sulla decodifica del DB catasto: il campo COD_COMUNE.decodifica
        // è CHAR(22) nella distribuzione AE, quindi nomi >22 car. vengono troncati.
        // BelfioreHelper contiene i nomi completi; se trova il codice, sovrascrive il valore troncato.
        if (preg_match('/^[A-Z]\d{3}$/i', $codBelfiore)) {
            $nomeDecodificato = BelfioreHelper::getNome($codBelfiore);
            if ($nomeDecodificato !== '') { $luogoNasc = $nomeDecodificato; }
        }
        $provNasc = BelfioreHelper::getProvincia($codBelfiore);
        if ($codFisc) {
            $cfDec    = $this->cfToDataNascita($codFisc);
            $sessoStr = $cfDec['sesso'];
            if (!$dataNasc && $cfDec['data']) { $dataNasc = $cfDec['data']; }
        }
        if (!$dataNasc && !empty($personaRow['dataNascita'])) {
            $pts = explode('-', $personaRow['dataNascita']);
            if (count($pts) === 3) { $dataNasc = $pts[2] . '/' . $pts[1] . '/' . $pts[0]; }
        }
        if (!$sessoStr && !empty($personaRow['sesso'])) {
            $sessoStr = in_array((string)$personaRow['sesso'], ['2', 'F', 'f'], true) ? 'F' : 'M';
        }

        // Aliquote e coefficienti da MySQL
        $aliquoteRaw = ImuAliquota::find()->where(['anno' => $anno, 'attiva' => 1])->indexBy('tipo')->all();
        if (empty($aliquoteRaw)) {
            $aliquoteRaw = ImuAliquota::find()->where(['attiva' => 1])->orderBy(['anno' => SORT_DESC])->indexBy('tipo')->all();
        }
        $aliquoteOut = [];
        foreach ($aliquoteRaw as $tipo => $rec) {
            $aliquoteOut[$tipo] = ['aliquota' => (float)$rec->aliquota, 'detrazione' => (float)$rec->detrazione];
        }

        $coeffsRaw = ImuCoefficiente::find()->all();
        $coeffsOut = [];
        foreach ($coeffsRaw as $c) {
            $coeffsOut[$c->categoria] = (int)$c->coefficiente;
        }

        // Valori unitari €/mq delle aree edificabili configurate per l'anno.
        // Chiave = 'id' . $ar->id per supportare più voci con lo stesso codice zona PRG.
        $areeRaw    = ImuAreaEdificabile::find()->where(['anno' => $anno, 'attiva' => 1])->orderBy('zona')->all();
        $valoriZone = [];
        foreach ($areeRaw as $ar) {
            $valoriZone['id' . $ar->id] = [
                'zona'     => $ar->zona,
                'label'    => $ar->zona . ($ar->descrizione ? ' — ' . $ar->descrizione : ''),
                'valore_mq' => (float)$ar->valore_mq,
            ];
        }

        return $this->asJson([
            'ok'          => true,
            'persona'     => [
                'cognome'    => $personaRow['cognome']   ?? $cognome,
                'nome'       => $personaRow['nome']      ?? $nome,
                'codFiscale' => $codFisc,
                'dataNasc'   => $dataNasc,
                'sesso'      => $sessoStr,
                'luogoNasc'  => $luogoNasc,
                'provNasc'   => $provNasc,
            ],
            'immobili'    => $immobili,
            'aliquote'    => $aliquoteOut,
            'coefficienti' => $coeffsOut,
            'valoriZone'  => $valoriZone,
            'anno'        => $anno,
            'codComune'   => $codComune,
            'tassoLegale' => self::getTassoLegale($anno),
        ]);
    }

    /** AJAX: genera PDF riepilogo calcolo IMU. */
    public function actionGeneraPdf(): Response
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        [$anno, $periodo, $immobili, $persona] = $this->estraiDatiPost($post);

        if (empty($immobili)) {
            return $this->asJson(['ok' => false, 'error' => 'Nessun dato immobili ricevuto.']);
        }

        [$righe, $totAnnuale, $imuDovuta] = $this->calcolaRighe($immobili, $anno, $periodo);

        $cognome      = strtoupper(trim($persona['cognome'] ?? ''));
        $nome         = strtoupper(trim($persona['nome']    ?? ''));
        $intestatario = $cognome . ($nome ? ' ' . $nome : '');
        if (!empty($persona['codFiscale'])) { $intestatario .= ' (CF: ' . $persona['codFiscale'] . ')'; }

        $tardivo = null;
        if (!empty($post['tardivo'])) {
            $td = json_decode($post['tardivo'], true);
            if (is_array($td) && ($td['totaleImu'] ?? 0) > 0) { $tardivo = $td; }
        }

        $result = $this->generaImuPdf($intestatario, $righe, $totAnnuale, $imuDovuta, $periodo, $anno, $tardivo);
        if (isset($result['error'])) {
            return $this->asJson(['ok' => false, 'error' => $result['error']]);
        }
        return $this->asJson(['ok' => true, 'url' => $result['url']]);
    }

    /** AJAX: genera PDF Modello F24 Semplificato. */
    public function actionGeneraF24(): Response
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        [$anno, $periodo, $immobili, $persona] = $this->estraiDatiPost($post);

        if (empty($immobili)) {
            return $this->asJson(['ok' => false, 'error' => 'Nessun dato immobili ricevuto.']);
        }

        [$righe, , $imuDovuta] = $this->calcolaRighe($immobili, $anno, $periodo);

        $codComune   = strtoupper(trim($post['codComune'] ?? ''));
        $fattore     = match($periodo) { 'acconto' => 0.5, 'saldo' => 0.5, default => 1.0 };
        $scaleFactor = 1.0;

        $tardivo = null;
        if (!empty($post['tardivo'])) {
            $td = json_decode($post['tardivo'], true);
            if (is_array($td) && ($td['totaleImu'] ?? 0) > 0) {
                $tardivo = $td;
                // scaleFactor e imuDovuta dipendono dal periodo selezionato
                if ($periodo === 'acconto') {
                    $base        = (float)($td['acconto']['importo'] ?? 0);
                    $totRata     = (float)($td['acconto']['totale']  ?? 0);
                    $scaleFactor = $base > 0 ? $totRata / $base : 1.0;
                    $imuDovuta   = $totRata;
                    // $fattore rimane 0.5
                } elseif ($periodo === 'saldo') {
                    $base        = (float)($td['saldo']['importo'] ?? 0);
                    $totRata     = (float)($td['saldo']['totale']  ?? 0);
                    $scaleFactor = $base > 0 ? $totRata / $base : 1.0;
                    $imuDovuta   = $totRata;
                    // $fattore rimane 0.5
                } else {
                    // annuale: entrambe le rate
                    $fattore     = 1.0;
                    $scaleFactor = $td['totaleComplessivo'] / $td['totaleImu'];
                    $imuDovuta   = (float)$td['totaleComplessivo'];
                }
            }
        }

        $result = $this->generaF24Pdf(
            strtoupper(trim($persona['cognome']    ?? '')),
            strtoupper(trim($persona['nome']       ?? '')),
            strtoupper(trim($persona['codFiscale'] ?? '')),
            trim($persona['dataNasc']  ?? ''),
            trim($persona['sesso']     ?? 'M'),
            trim($persona['luogoNasc'] ?? ''),
            trim($persona['provNasc']  ?? ''),
            $righe,
            $imuDovuta,
            $periodo,
            $anno,
            $fattore,
            $codComune,
            $scaleFactor,
            $tardivo
        );
        if (isset($result['error'])) {
            return $this->asJson(['ok' => false, 'error' => $result['error']]);
        }
        return $this->asJson(['ok' => true, 'url' => $result['url']]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Metodi privati — query catasto
    // ─────────────────────────────────────────────────────────────────────────

    /** Restituisce [percorso SQLite, codice Comune (es. 'B542')]. */
    private function getDbInfo(): array
    {
        $ultimoDb = DatiCensuari::find()->orderBy(['dataCensuari' => SORT_DESC])->one();
        if (!$ultimoDb) {
            return [Yii::getAlias('@webroot') . '/mappe/b542/catasto.db', 'B542'];
        }
        $dbPath    = Yii::getAlias('@webroot') . '/' . $ultimoDb->file_path_database;
        $codComune = '';
        if (preg_match('|/([a-zA-Z]\d{3})/|', $ultimoDb->file_path_database, $mc)) {
            $codComune = strtoupper($mc[1]);
        }
        return [$dbPath, $codComune];
    }

    /**
     * Tasso di interesse legale (art. 1284 c.c.) per anno.
     * Aggiornare annualmente con il DM del MEF.
     */
    private static function getTassoLegale(int $anno): float
    {
        $tassi = [
            2020 => 0.0005,  // 0,05%
            2021 => 0.0001,  // 0,01%
            2022 => 0.0125,  // 1,25%
            2023 => 0.0500,  // 5,00%
            2024 => 0.0250,  // 2,50%
            2025 => 0.0200,  // 2,00%
        ];
        return $tassi[$anno] ?? $tassi[max(array_keys($tassi))];
    }

    /** Cerca fabbricati con rendita per una persona fisica nel catasto SQLite. */
    private function queryFabbricatiConRendita(\SQLite3 $db, string $cognome, string $nome, string $dataNasc, string $codFisc = ''): array
    {
        if ($codFisc) {
            $cond = ['upper(trim(pf.codFiscale)) = upper(trim(:codFiscale))'];
        } else {
            $cond = ['upper(trim(pf.cognome)) = upper(trim(:cognome))'];
            if ($nome)     { $cond[] = 'upper(trim(pf.nome)) LIKE upper(trim(:nome))'; }
            if ($dataNasc) { $cond[] = 'pf.dataNascita = :dataNascita'; }
        }

        $sql = 'SELECT
                    ii.idImmobile,
                    ltrim(ii.foglio,\'0\')     AS foglio,
                    ltrim(ii.numero,\'0\')     AS numero,
                    ltrim(ii.subalterno,\'0\') AS subalterno,
                    ui.categoria,
                    COALESCE(CAST(REPLACE(COALESCE(ui.renditaEuro,\'0\'),\',\',\'.\') AS REAL), 0) AS rendita,
                    MAX(CASE WHEN t.quotaNum > 0 THEN t.quotaNum ELSE NULL END) AS quotaNum,
                    MAX(CASE WHEN t.quotaNum > 0 THEN t.quotaDen ELSE NULL END) AS quotaDen
                FROM PERSONA_FISICA pf
                JOIN TITOLARITA t  ON pf.idSoggetto = t.idSoggetto
                JOIN IDENTIFICATIVI_IMMOBILIARI ii ON t.idImmobile = ii.idImmobile
                LEFT JOIN UNITA_IMMOBILIARI ui ON ui.idImmobile = ii.idImmobile
                WHERE ' . implode(' AND ', $cond) . '
                  AND COALESCE(CAST(REPLACE(COALESCE(ui.renditaEuro,\'0\'),\',\',\'.\') AS REAL), 0) > 0
                  AND (ui.categoria IS NULL OR ui.categoria NOT LIKE \'F%\')
                GROUP BY ii.idImmobile
                ORDER BY foglio, numero, subalterno
                LIMIT 500';

        $stmt = $db->prepare($sql);
        if ($codFisc) {
            $stmt->bindValue(':codFiscale', $codFisc, SQLITE3_TEXT);
        } else {
            $stmt->bindValue(':cognome', $cognome, SQLITE3_TEXT);
            if ($nome)     { $stmt->bindValue(':nome', '%' . $nome . '%', SQLITE3_TEXT); }
            if ($dataNasc) { $stmt->bindValue(':dataNascita', $dataNasc, SQLITE3_TEXT); }
        }

        $res     = $stmt->execute();
        $seen    = [];
        $results = [];
        while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $key = $row['foglio'] . '|' . $row['numero'] . '|' . $row['subalterno'];
            if (isset($seen[$key])) { continue; }
            $seen[$key] = true;

            $quotaNum = (int)($row['quotaNum'] ?? 1);
            $quotaDen = (int)($row['quotaDen'] ?? 1);
            if ($quotaNum <= 0) { $quotaNum = 1; $quotaDen = 1; }

            // Normalizza categoria: "A10" → "A/10"
            $catRaw  = strtoupper(str_replace([' ', '.'], '', trim($row['categoria'] ?? '')));
            $catNorm = preg_replace('/^([A-Z]+)(\d+)$/', '$1/$2', $catRaw);
            $catNorm = preg_replace('/\/0+(\d)/', '/$1', $catNorm);

            $results[] = [
                'foglio'     => (int)($row['foglio'] ?? 0),
                'numero'     => (string)(int)($row['numero'] ?? 0),
                'subalterno' => ltrim($row['subalterno'] ?? '', '0') ?: '0',
                'categoria'  => $catNorm ?: $catRaw,
                'rendita'    => round((float)$row['rendita'], 2), // rendita intera, quota separata
                'quotaNum'   => $quotaNum,
                'quotaDen'   => $quotaDen > 0 ? $quotaDen : 1,
            ];
        }
        return $results;
    }

    /** Recupera i dati anagrafici del contribuente dal catasto SQLite (con nome Comune di nascita). */
    private function queryPersonaFisicaData(\SQLite3 $db, string $cognome, string $nome, string $dataNasc, string $codFisc): array
    {
        $cols = 'pf.codFiscale, pf.cognome, pf.nome, pf.sesso, pf.dataNascita,
                 pf.luogoNascita, coalesce(cc.decodifica, pf.luogoNascita) AS comuneNascitaNome';
        $join = 'LEFT JOIN COD_COMUNE cc ON cc.codice = pf.luogoNascita';
        if ($codFisc) {
            $stmt = $db->prepare(
                "SELECT $cols FROM PERSONA_FISICA pf $join
                  WHERE upper(trim(pf.codFiscale)) = upper(trim(:cf)) LIMIT 1"
            );
            $stmt->bindValue(':cf', $codFisc, SQLITE3_TEXT);
        } else {
            $cond = ['upper(trim(pf.cognome)) = upper(trim(:cognome))'];
            if ($nome)     { $cond[] = 'upper(trim(pf.nome)) LIKE upper(trim(:nome))'; }
            if ($dataNasc) { $cond[] = 'pf.dataNascita = :dataNascita'; }
            $stmt = $db->prepare(
                "SELECT $cols FROM PERSONA_FISICA pf $join
                  WHERE " . implode(' AND ', $cond) . ' LIMIT 1'
            );
            $stmt->bindValue(':cognome', $cognome, SQLITE3_TEXT);
            if ($nome)     { $stmt->bindValue(':nome', '%' . $nome . '%', SQLITE3_TEXT); }
            if ($dataNasc) { $stmt->bindValue(':dataNascita', $dataNasc, SQLITE3_TEXT); }
        }
        $res = $stmt->execute();
        return ($res && ($row = $res->fetchArray(SQLITE3_ASSOC))) ? $row : [];
    }

    /** Decodifica il Codice Fiscale italiano e restituisce data di nascita e sesso. */
    private function cfToDataNascita(string $cf): array
    {
        if (strlen($cf) !== 16) { return ['data' => '', 'sesso' => 'M']; }
        $cf   = strtoupper($cf);
        $yy   = substr($cf, 6, 2);
        $mc   = substr($cf, 8, 1);
        $dd   = (int)substr($cf, 9, 2);
        $map  = ['A'=>1,'B'=>2,'C'=>3,'D'=>4,'E'=>5,'H'=>6,'L'=>7,'M'=>8,'P'=>9,'R'=>10,'S'=>11,'T'=>12];
        $mese = $map[$mc] ?? 0;
        $sesso = 'M';
        if ($dd > 40) { $dd -= 40; $sesso = 'F'; }
        $yInt = (int)$yy;
        $year = ($yInt > (int)date('y')) ? (1900 + $yInt) : (2000 + $yInt);
        if (!$mese || $dd < 1 || $dd > 31) { return ['data' => '', 'sesso' => 'M']; }
        return ['data' => sprintf('%02d/%02d/%04d', $dd, $mese, $year), 'sesso' => $sesso];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Metodi privati — calcolo IMU server-side
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Estrae e valida i campi comuni dalle POST per generaPdf e generaF24.
     * @return array [anno, periodo, immobili[], persona[]]
     */
    private function estraiDatiPost(array $post): array
    {
        $anno     = (int)($post['anno']    ?? date('Y'));
        $periodo  = trim($post['periodo']  ?? 'acconto');
        if (!in_array($periodo, ['acconto', 'saldo', 'annuale'], true)) { $periodo = 'acconto'; }
        $immobili = json_decode($post['immobili'] ?? '[]', true) ?: [];
        $persona  = json_decode($post['persona']  ?? '{}', true) ?: [];
        return [$anno, $periodo, $immobili, $persona];
    }

    /**
     * Ricalcola le righe IMU server-side a partire dai dati POST.
     * Ogni immobile deve avere: foglio, numero, subalterno, categoria, rendita, tipoUtilizzo.
     * @return array [righe[], totAnnuale, imuDovuta]
     */
    private function calcolaRighe(array $immobili, int $anno, string $periodo): array
    {
        $aliquote = ImuAliquota::find()->where(['anno' => $anno, 'attiva' => 1])->indexBy('tipo')->all();
        if (empty($aliquote)) {
            $aliquote = ImuAliquota::find()->where(['attiva' => 1])->orderBy(['anno' => SORT_DESC])->indexBy('tipo')->all();
        }

        $righe         = [];
        $totProporz    = 0.0;

        foreach ($immobili as $imm) {
            $rendita      = (float)($imm['rendita']      ?? 0);
            $catNorm      = (string)($imm['categoria']   ?? '');
            $tipoUtilizzo = (string)($imm['tipoUtilizzo'] ?? 'altra_abitazione');
            $mesi         = min(12, max(1, (int)($imm['mesi']     ?? 12)));
            $riduzione    = (string)($imm['riduzione']   ?? 'no');
            $quotaNum     = max(1, (int)($imm['quotaNum'] ?? 1));
            $quotaDen     = max(1, (int)($imm['quotaDen'] ?? 1));
            $quota        = $quotaNum / $quotaDen;

            $tipoRecord   = (string)($imm['tipoRecord']  ?? 'fabbricato');
            $valoreVenale = (float)($imm['valoreVenale'] ?? 0.0);
            $consistenza  = (float)($imm['consistenza']  ?? 0.0);
            $zona         = (string)($imm['zona']        ?? '');

            if ($tipoRecord === 'area') {
                // Area fabbricabile: base = valore venale in commercio (inserito dall'utente)
                $coeff = 1;
                $base  = $valoreVenale;
            } else {
                $coeff = ImuCoefficiente::getCoeffForCategoria($catNorm);
                $base  = $rendita * 1.05 * $coeff;
                // Riduzione 50% per condizione fisica (inagibile/storico)
                if ($riduzione === 'inagibile' || $riduzione === 'storico') { $base *= 0.5; }
                // Riduzione 50% incorporata nel tipo comodato con riduzione
                if ($tipoUtilizzo === 'comodato50') { $base *= 0.5; }
            }

            $esente     = false;
            $aliquota   = 0.0;
            $detrazione = 0.0;
            $note       = '';

            switch ($tipoUtilizzo) {
                // ── ESENTI ────────────────────────────────────────────────
                case 'abpr_std':
                    $esente = true; $note = 'Abitazione principale (ESENTE)'; break;
                case 'pertinenza_std':
                    $esente = true; $note = 'Pertinenza AB.PR. (ESENTE)'; break;
                case 'assimilata_anziani':
                    $esente = true; $note = 'Assimilata AB.PR. anziani/disabili (ESENTE)'; break;
                case 'terreno_agricolo':
                    $esente = true; $note = 'Terreno agricolo (ESENTE)'; break;

                // ── ABITAZIONE PRINCIPALE DI PREGIO (A/1, A/8, A/9) ─────
                case 'abpr_lusso':
                    $rec        = $aliquote['abitazione_principale'] ?? null;
                    $aliquota   = $rec ? (float)$rec->aliquota : 0.004;
                    $detrazione = $rec ? (float)$rec->detrazione : 200.0;
                    $note       = 'AB.PR. pregio A/1-A/8-A/9 (4‰ + detr.€200)';
                    break;
                case 'pertinenza_lusso':
                    $rec      = $aliquote['abitazione_principale'] ?? null;
                    $aliquota = $rec ? (float)$rec->aliquota : 0.004;
                    $note     = 'Pertinenza AB.PR. pregio (4‰ senza detr.)';
                    break;

                // ── RURALI STRUMENTALI ────────────────────────────────────
                case 'd10_rurale':
                case 'rurale_abc':
                    $rec      = $aliquote['rurali_strumentali'] ?? null;
                    $aliquota = $rec ? (float)$rec->aliquota : 0.001; // 1‰ di legge
                    $note     = 'Rurale strumentale (1‰)';
                    break;

                // ── AREE FABBRICABILI ─────────────────────────────────────
                case 'area_fabbricabile':
                    $rec      = $aliquote['aree_fabbricabili'] ?? $aliquote['altri_immobili'] ?? null;
                    $aliquota = $rec ? (float)$rec->aliquota : 0.0106;
                    $note     = 'Area fabbricabile';
                    break;

                // ── TUTTI GLI ALTRI (→ altri_immobili) ───────────────────
                default:
                    $rec        = $aliquote['altri_immobili'] ?? null;
                    $aliquota   = $rec ? (float)$rec->aliquota : 0.0106;
                    $detrazione = $rec ? (float)$rec->detrazione : 0.0;
                    $note       = match($tipoUtilizzo) {
                        'altra_abitazione'     => 'Altra abitazione',
                        'a10_uffici'           => 'Cat. A/10 - Uffici',
                        'c1_negozi'            => 'Cat. C/1 - Negozi',
                        'c2_magazzini'         => 'Cat. C/2 - Magazzini',
                        'c3_laboratori'        => 'Cat. C/3 - Laboratori',
                        'b_c4_c5'              => 'Cat. B, C/4, C/5',
                        'c6_c7_stalle'         => 'Cat. C/6, C/7',
                        'cat_d'                => 'Cat. D (ind./comm.)',
                        'd5_credito'           => 'Cat. D/5 - Credito',
                        'comodato50'           => 'Comodato gratuito -50% base',
                        'comodato_noriduziome' => 'Comodato gratuito',
                        'iacp'                 => 'IACP/ARES/ALER',
                        default                => 'Fabbricato',
                    };
                    break;
            }

            // IMU = max(0, base × aliquota - detrazione) × quota × mesi/12
            $imuAnnualeIntero = $esente ? 0.0 : max(0.0, $base * $aliquota - $detrazione);
            $imuProporzionale = $imuAnnualeIntero * $quota * ($mesi / 12.0);

            $totProporz += $imuProporzionale;

            $righe[] = [
                'foglio'           => $imm['foglio']     ?? '',
                'numero'           => $imm['numero']     ?? '',
                'sub'              => $imm['subalterno'] ?? '0',
                'categoria'        => $catNorm,
                'rendita'          => $rendita,
                'tipoRecord'       => $tipoRecord,
                'tipoUtilizzo'     => $tipoUtilizzo,
                'valoreVenale'     => $valoreVenale,
                'consistenza'      => $consistenza,
                'zona'             => $zona,
                'quotaNum'         => $quotaNum,
                'quotaDen'         => $quotaDen,
                'mesi'             => $mesi,
                'riduzione'        => $riduzione,
                'coeff'            => $coeff,
                'base'             => $base,
                'aliquota'         => $aliquota,
                'detrazione'       => $detrazione,
                'imu_annuale'      => $imuAnnualeIntero,
                'imu_proporzionale'=> $imuProporzionale,
                'note'             => $note,
                'esente'           => $esente,
            ];
        }

        $fattore   = match($periodo) { 'acconto' => 0.5, 'saldo' => 0.5, default => 1.0 };
        $imuDovuta = $totProporz * $fattore;

        return [$righe, $totProporz, $imuDovuta];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Metodi privati — generazione PDF
    // ─────────────────────────────────────────────────────────────────────────

    /** Genera il PDF del calcolo IMU (mPDF) e restituisce ['url' => …] o ['error' => …]. */
    private function generaImuPdf(
        string  $intestatario,
        array   $righe,
        float   $totAnnuale,
        float   $imuDovuta,
        string  $periodo,
        int     $anno,
        ?array  $tardivo = null
    ): array {
        $periodoLabel = match($periodo) { 'acconto' => 'ACCONTO (giugno)', 'saldo' => 'SALDO (dicembre)', default => 'ANNUALE' };
        $dataOra      = date('d/m/Y H:i');
        $slug         = preg_replace('/[^A-Za-z0-9]/', '_', preg_replace('/\s*\(.*\)/', '', $intestatario));
        $filename     = 'imu_' . substr($slug, 0, 40) . '_' . date('Ymd_His') . '.pdf';
        $filePath     = Yii::getAlias('@webroot') . '/imu/' . $filename;
        $totFmt       = number_format($totAnnuale, 2, ',', '.');
        $dovutaFmt    = number_format($imuDovuta,  2, ',', '.');

        $rows = '';
        foreach ($righe as $r) {
            $quota          = $r['quotaNum'] . '/' . $r['quotaDen'];
            $riduzioneLabel = match($r['riduzione'] ?? 'no') { 'inagibile' => 'Inag.', 'storico' => 'Stor.', default => '—' };
            $isArea         = ($r['tipoRecord'] ?? 'fabbricato') === 'area';
            $renditaLabel   = $isArea
                ? number_format($r['valoreVenale'] ?? 0, 2, ',', '.')
                : number_format($r['rendita'], 2, ',', '.');
            $consistLabel   = $isArea ? number_format($r['consistenza'] ?? 0, 0, ',', '.') . ' mq' : '—';

            if ($r['esente']) {
                $rows .= sprintf(
                    '<tr style="background:#e8f8e8"><td>%s</td><td>%s</td><td>%s</td><td>%s</td>' .
                    '<td class="num">%s</td><td class="ct">%s</td><td class="ct">%s</td><td class="ct">%s</td><td class="ct">%d</td><td class="ct">%s</td>' .
                    '<td colspan="3" style="color:#1a7a1a;font-style:italic">%s</td>' .
                    '<td class="num"><strong>0,00</strong></td></tr>',
                    $r['foglio'], $r['numero'], $r['sub'], htmlspecialchars($r['categoria']),
                    $renditaLabel, $consistLabel,
                    $quota, $riduzioneLabel, $r['mesi'], $riduzioneLabel,
                    htmlspecialchars($r['note'])
                );
            } else {
                $rows .= sprintf(
                    '<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td>' .
                    '<td class="num">%s</td><td class="ct">%s</td><td class="ct">%s</td><td class="ct">%d</td><td class="ct">%s</td>' .
                    '<td class="num">%d</td><td class="num">%s</td><td class="num">%s‰</td>' .
                    '<td class="num"><strong>%s</strong></td></tr>',
                    $r['foglio'], $r['numero'], $r['sub'], htmlspecialchars($r['categoria']),
                    $renditaLabel, $consistLabel,
                    $quota, $r['mesi'], $riduzioneLabel,
                    $r['coeff'],
                    number_format($r['base'],              2, ',', '.'),
                    number_format($r['aliquota'] * 1000,   2, ',', '.'),
                    number_format($r['imu_proporzionale'], 2, ',', '.')
                );
            }
        }

        // Sezione tardivo (solo se presente)
        $tardivHtml = '';
        if ($tardivo !== null) {
            $fmtN = fn(float $v): string => '€ ' . number_format($v, 2, ',', '.');
            $fmtD = function(string $iso): string {
                $p = explode('-', $iso);
                return count($p) === 3 ? $p[2] . '/' . $p[1] . '/' . $p[0] : $iso;
            };
            $dataPag  = $fmtD($tardivo['dataPagamento'] ?? '');
            $tassoStr = number_format(($tardivo['tassoLegale'] ?? 0) * 100, 2, ',', '.') . '%';

            $accRow = $tardivo['acconto'] ?? [];
            $salRow = $tardivo['saldo']   ?? [];

            $makeRow = function(string $label, array $r) use ($fmtN, $fmtD): string {
                $giorni = (int)($r['giorni'] ?? 0);
                $color  = $giorni > 0 ? '#c0392b' : '#1a7a1a';
                $rate   = $giorni > 0 ? number_format((float)($r['rate'] ?? 0), 2, ',', '.') . '%' : '—';
                return sprintf(
                    '<tr><td>%s</td><td>%s</td>'
                    . '<td class="ct" style="color:%s">%s</td>'
                    . '<td class="num">%s</td>'
                    . '<td class="num" style="color:%s">%s</td>'
                    . '<td class="num" style="color:%s">%s<br><small>(%s)</small></td>'
                    . '<td class="num"><strong>%s</strong></td></tr>',
                    htmlspecialchars($label),
                    $fmtD($r['scad'] ?? ''),
                    $color, $giorni > 0 ? '+' . $giorni . ' gg' : '—',
                    $fmtN((float)($r['importo']   ?? 0)),
                    $color, $giorni > 0 ? $fmtN((float)($r['interessi'] ?? 0)) : '—',
                    $color, $giorni > 0 ? $fmtN((float)($r['sanzione']  ?? 0)) : '—', $rate,
                    $fmtN((float)($r['totale'] ?? 0))
                );
            };

            $totCompFmt = $fmtN((float)($tardivo['totaleComplessivo'] ?? 0));
            $totIntFmt  = $fmtN((float)($tardivo['totaleInteressi']   ?? 0));
            $totSanFmt  = $fmtN((float)($tardivo['totaleSanzione']    ?? 0));
            $totImuFmt  = $fmtN((float)($tardivo['totaleImu']         ?? 0));

            // Nota su aliquote sanzione
            $riformaDate = new \DateTime('2024-09-01');
            $accontoDate = new \DateTime($tardivo['acconto']['scad'] ?? '2000-01-01');
            $saldoDate   = new \DateTime($tardivo['saldo']['scad']   ?? '2000-01-01');
            $notaAliq = ($accontoDate >= $riformaDate || $saldoDate >= $riformaDate)
                ? 'Sanzione post-1/9/2024 (D.Lgs. 87/2024): ≤15 gg 1/15×12,5%/die; ≤90 gg 12,5%; >90 gg 25%.'
                : 'Sanzione pre-1/9/2024: ≤15 gg 1/15×15%/die; ≤90 gg 15%; >90 gg 30%.';

            $tardivHtml = <<<THTML

<h1 style="margin-top:18px;color:#7d6608;border-bottom-color:#7d6608">
  CALCOLO TARDIVO — pagamento al {$dataPag}
</h1>
<h2>Tasso interesse legale {$anno}: {$tassoStr} (art. 20 Regolamento IMU)</h2>
<table>
  <thead>
    <tr>
      <th>Rata</th><th>Scadenza</th><th>Giorni ritardo</th>
      <th>IMU dovuta</th><th>Interessi</th><th>Sanzione</th><th>Totale</th>
    </tr>
  </thead>
  <tbody>
    {$makeRow('Acconto (giugno)', $accRow)}
    {$makeRow('Saldo (dicembre)', $salRow)}
  </tbody>
  <tfoot>
    <tr style="background:#fef9e7;font-weight:bold">
      <td colspan="3">Totale complessivo da versare</td>
      <td class="num">{$totImuFmt}</td>
      <td class="num">{$totIntFmt}</td>
      <td class="num">{$totSanFmt}</td>
      <td class="num" style="font-size:11px;color:#c0392b">{$totCompFmt}</td>
    </tr>
  </tfoot>
</table>
<p style="font-size:7px;color:#888;margin-top:4px">
  {$notaAliq}
  Interessi: importo × tasso legale × giorni / 365. Scadenze: Acconto 16/6 — Saldo 16/12 (slittate al lunedì se festive).
</p>
THTML;
        }

        $html = <<<HTML
<!DOCTYPE html><html><head><meta charset="UTF-8">
<style>
  body{font-family:DejaVu Sans,sans-serif;font-size:8.5px;color:#222;margin:12px}
  h1{font-size:13px;color:#1a5276;border-bottom:2px solid #1a5276;padding-bottom:3px;margin-bottom:3px}
  h2{font-size:9px;color:#555;margin:2px 0 6px}
  table{width:100%;border-collapse:collapse;margin-top:6px}
  th{background:#1a5276;color:#fff;padding:3px 4px;text-align:center;font-size:7.5px}
  td{padding:2px 4px;border-bottom:1px solid #ddd}
  .num{text-align:right} .ct{text-align:center}
  .foot{margin-top:16px;font-size:7px;color:#999;border-top:1px solid #ccc;padding-top:3px}
</style></head><body>
<h1>CALCOLO IMU {$anno} — {$periodoLabel}</h1>
<h2>Intestatario: {$intestatario} &nbsp;|&nbsp; Data elaborazione: {$dataOra}</h2>
<table>
  <thead>
    <tr><th>Foglio</th><th>Part.</th><th>Sub</th><th>Cat.</th>
    <th>Rendita/Val.ven. €</th><th>Consist. mq</th><th>Quota</th><th>Mesi</th><th>Riduz.</th>
    <th>Coeff.</th><th>Base imp. €</th><th>Aliquota</th><th>IMU prop. €</th></tr>
  </thead>
  <tbody>{$rows}</tbody>
</table>
<table style="width:100%;border-collapse:collapse;margin-top:12px;border-top:2px solid #1a5276">
  <tr>
    <td style="border:none">&nbsp;</td>
    <td style="border:none;text-align:right;font-size:10px">IMU annuale totale:</td>
    <td style="border:none;text-align:right;font-size:10px;width:110px"><strong>€ {$totFmt}</strong></td>
  </tr>
  <tr>
    <td style="border:none">&nbsp;</td>
    <td style="border:none;text-align:right;font-size:12px">IMU dovuta ({$periodoLabel}):</td>
    <td style="border:none;text-align:right;font-size:13px;color:#c0392b;width:110px"><strong>€ {$dovutaFmt}</strong></td>
  </tr>
</table>
{$tardivHtml}
<div class="foot">
  Documento generato automaticamente da UTC-BIM — Sistema Informativo Territoriale.<br>
  Il calcolo è indicativo e basato sulle rendite catastali al momento dell'elaborazione.
</div>
</body></html>
HTML;

        try {
            $tmpDir = sys_get_temp_dir() . '/mpdf_imu';
            if (!is_dir($tmpDir)) { @mkdir($tmpDir, 0777, true); }
            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'UTF-8', 'format' => 'A4', 'orientation' => 'L',
                'margin_left' => 10, 'margin_right' => 10,
                'margin_top' => 12, 'margin_bottom' => 12,
                'margin_header' => 0, 'margin_footer' => 0,
                'tempDir' => $tmpDir,
            ]);
            $mpdf->SetTitle("Calcolo IMU {$anno} — {$intestatario}");
            $mpdf->WriteHTML($html);
            $mpdf->Output($filePath, \Mpdf\Output\Destination::FILE);
            if (!file_exists($filePath) || filesize($filePath) === 0) {
                return ['error' => 'Il file PDF è stato generato ma risulta vuoto.'];
            }
            @chmod($filePath, 0644);
            return ['url' => \yii\helpers\Url::to(['/imu/download', 'file' => $filename], true)];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /** Genera il PDF del Modello F24 Semplificato (FPDI overlay sul template ufficiale). */
    private function generaF24Pdf(
        string  $cognome,
        string  $nome,
        string  $codFisc,
        string  $dataNasc,
        string  $sesso,
        string  $luogoNasc,
        string  $provNasc,
        array   $righe,
        float   $imuDovuta,
        string  $periodo,
        int     $anno,
        float   $fattore,
        string  $codComune,
        float   $scaleFactor = 1.0,
        ?array  $tardivo = null
    ): array {
        // Aggregazione per codice tributo IMU (tabella F24 aggiornata)
        // 3912 – AB.PR. di pregio e pertinenze              → Comune
        // 3913 – Fabbricati rurali strumentali              → Comune
        // 3916 – Aree fabbricabili                          → Comune
        // 3918 – Altri fabbricati                           → Comune
        // 3925 – Cat. D immobili produttivi                 → Stato (quota fissa 7,6‰)
        // 3930 – Cat. D immobili produttivi - incremento    → Comune (eccedenza oltre 7,6‰)
        $bkt = [
            '3912' => ['tot' => 0.0, 'n' => 0],
            '3913' => ['tot' => 0.0, 'n' => 0],
            '3916' => ['tot' => 0.0, 'n' => 0],
            '3918' => ['tot' => 0.0, 'n' => 0],
            '3925' => ['tot' => 0.0, 'n' => 0],
            '3930' => ['tot' => 0.0, 'n' => 0],
        ];
        $nD = 0;

        foreach ($righe as $r) {
            if ($r['esente'] || $r['imu_annuale'] <= 0) { continue; }
            $imp        = round($r['imu_annuale'] * $fattore * $scaleFactor, 2);
            $cat        = strtoupper($r['categoria']    ?? '');
            $tipoUtil   = $r['tipoUtilizzo']             ?? '';
            $tipoRecord = $r['tipoRecord']               ?? 'fabbricato';

            if ($tipoRecord === 'area') {
                // Aree fabbricabili → 3916 Comune
                $bkt['3916']['tot'] += $imp;
                $bkt['3916']['n']++;
            } elseif ($tipoUtil === 'abpr_lusso' || $tipoUtil === 'pertinenza_lusso') {
                // Abitazione principale di pregio (A/1, A/8, A/9) → 3912 Comune
                $bkt['3912']['tot'] += $imp;
                $bkt['3912']['n']++;
            } elseif ($tipoUtil === 'd10_rurale' || $tipoUtil === 'rurale_abc') {
                // Fabbricati rurali strumentali → 3913 Comune
                $bkt['3913']['tot'] += $imp;
                $bkt['3913']['n']++;
            } elseif (str_starts_with($cat, 'D')) {
                // Cat. D produttivi: quota fissa 7,6‰ → Stato (3925); eccedenza → Comune (3930)
                $baseD = $r['rendita'] * 1.05 * $r['coeff'];
                $stato = round($baseD * 0.0076 * $fattore * $scaleFactor, 2);
                $com   = max(0.0, round($imp - $stato, 2));
                $bkt['3925']['tot'] += $stato;
                $bkt['3930']['tot'] += $com;
                $nD++;
            } else {
                // Tutti gli altri fabbricati → 3918 Comune
                $bkt['3918']['tot'] += $imp;
                $bkt['3918']['n']++;
            }
        }

        // Arrotondamento all'euro (≥ 50 cent → euro superiore, < 50 cent → euro inferiore)
        $lineeF24 = [];
        foreach (['3912', '3913', '3916', '3918'] as $cod) {
            if ($bkt[$cod]['tot'] > 0) {
                $lineeF24[] = [$codComune, $cod, $anno, $bkt[$cod]['n'], (int)round($bkt[$cod]['tot'])];
            }
        }
        if ($bkt['3925']['tot'] > 0) { $lineeF24[] = [$codComune, '3925', $anno, $nD, (int)round($bkt['3925']['tot'])]; }
        if ($bkt['3930']['tot'] > 0) { $lineeF24[] = [$codComune, '3930', $anno, $nD, (int)round($bkt['3930']['tot'])]; }

        if (empty($lineeF24)) {
            return ['error' => 'Nessun importo IMU da versare (tutti gli immobili risultano esenti).'];
        }

        // Il totale saldo è la somma delle righe già arrotondate (evita scostamenti)
        $imuDovuta = (float)array_sum(array_column($lineeF24, 4));

        $templatePath = Yii::getAlias('@webroot') . '/../assets/f24_template.pdf';
        if (!file_exists($templatePath)) {
            return ['error' => 'Template F24 non trovato: ' . $templatePath];
        }
        $slug     = preg_replace('/[^A-Za-z0-9]/', '_', $cognome . '_' . $nome);
        $filename = 'f24_' . substr($slug, 0, 40) . '_' . date('Ymd_His') . '.pdf';
        $imuDir   = Yii::getAlias('@webroot') . '/imu/';
        if (!is_dir($imuDir)) { @mkdir($imuDir, 0775, true); }
        $filePath = $imuDir . $filename;

        $dateParts = explode('/', $dataNasc ?: '  /  /    ');
        $gg   = str_pad($dateParts[0] ?? '', 2);
        $mm   = str_pad($dateParts[1] ?? '', 2);
        $aaaa = str_pad($dateParts[2] ?? '', 4);
        $isAcconto = ($periodo === 'acconto');
        $isTardivo = ($tardivo !== null);

        try {
            $pdf = new \setasign\Fpdi\Fpdi('P', 'mm', 'A4');
            $pdf->SetMargins(0, 0, 0);
            $pdf->SetAutoPageBreak(false);
            $pdf->AddPage();
            $pdf->setSourceFile($templatePath);
            $tpl = $pdf->importPage(1);
            $pdf->useTemplate($tpl, 0, 0, 210, 297);
            $pdf->SetTextColor(0, 0, 0);

            $writeAmt = function(float $amount, float $startX, float $cellW, float $decX, float $y) use ($pdf): void {
                $amtStr = number_format(round($amount, 2), 2, ',', '.');
                $parts  = explode(',', $amtStr);
                $pdf->SetXY($startX, $y);
                $pdf->Cell($cellW, 4, $parts[0], 0, 0, 'R');
                $pdf->SetXY($decX, $y);
                $pdf->Write(4, $parts[1] ?? '00');
            };

            $writeCopy = function(float $yOff) use (
                $pdf, $codFisc, $cognome, $nome,
                $gg, $mm, $aaaa, $sesso, $luogoNasc, $provNasc,
                $lineeF24, $isAcconto, $imuDovuta, $writeAmt,
                $isTardivo
            ): void {
                // CF (16 caselle)
                $pdf->SetFont('Helvetica', 'B', 9);
                $cfStr = str_pad(strtoupper($codFisc), 16);
                for ($i = 0; $i < 16; $i++) {
                    $ch = substr($cfStr, $i, 1);
                    if ($ch === ' ') { continue; }
                    $pdf->SetXY(40.0 + $i * 5.05 + 0.8, $yOff + 34.0);
                    $pdf->Write(4, $ch);
                }
                // Cognome
                $pdf->SetFont('Helvetica', 'B', 9.0);
                $pdf->SetXY(40.5, $yOff + 42.0);
                $pdf->Write(4, mb_strtoupper(mb_substr($cognome, 0, 30)));
                // Nome
                $pdf->SetXY(147.0, $yOff + 42.0);
                $pdf->Write(4, mb_strtoupper(mb_substr($nome, 0, 20)));
                // Data di nascita (GG MM AAAA a caselle)
                $pdf->SetFont('Helvetica', 'B', 9.0);
                for ($j = 0; $j < 2; $j++) {
                    $ch = substr($gg, $j, 1);
                    if ($ch !== ' ') { $pdf->SetXY(40.8 + $j * 5, $yOff + 51.2); $pdf->Write(4, $ch); }
                }
                for ($j = 0; $j < 2; $j++) {
                    $ch = substr($mm, $j, 1);
                    if ($ch !== ' ') { $pdf->SetXY(51.0 + $j * 5, $yOff + 51.2); $pdf->Write(4, $ch); }
                }
                for ($j = 0; $j < 4; $j++) {
                    $ch = substr($aaaa, $j, 1);
                    if ($ch !== ' ') { $pdf->SetXY(61.2 + $j * 5.0, $yOff + 51.2); $pdf->Write(4, $ch); }
                }
                // Sesso
                $pdf->SetFont('Helvetica', 'B', 9);
                $pdf->SetXY(85.8, $yOff + 51.2);
                $pdf->Write(4, strtoupper(substr($sesso, 0, 1)));
                // Comune di nascita — font ridotto per nomi lunghi (campo ≈86mm)
                $nomeNasc  = mb_strtoupper($luogoNasc);
                $nomeFont  = mb_strlen($nomeNasc) > 28 ? 7.0 : (mb_strlen($nomeNasc) > 22 ? 8.0 : 9.0);
                $pdf->SetFont('Helvetica', 'B', $nomeFont);
                $pdf->SetXY(97.0, $yOff + 51.2);
                $pdf->Write(4, $nomeNasc);
                // Provincia di nascita
                if ($provNasc !== '') {
                    $pdf->SetFont('Helvetica', 'B', 9.0);
                    $pdf->SetXY(190.7, $yOff + 51.0);
                    $pdf->Write(4, strtoupper(substr($provNasc, 0, 1)));
                    $pdf->SetXY(195.5, $yOff + 51.0);
                    $pdf->Write(4, strtoupper(substr($provNasc, 1, 1)));

                }
                // Righe di pagamento
                $pdf->SetFont('Helvetica', 'B', 9.0);
                foreach ($lineeF24 as $idx => $l) {
                    $rowY = $yOff + 72.5 + $idx * 4.3;
                    $pdf->SetXY(12.4, $rowY); $pdf->Write(4, 'E');
                    $pdf->SetXY(18.6, $rowY); $pdf->Write(4, 'L');
                    $pdf->SetXY(29.0, $rowY); $pdf->Write(4, $l[1]);
                    // codice ente
                    $ce = str_pad(strtoupper($l[0]), 4);
                    for ($i = 0; $i < 4; $i++) {
                        $cei = substr($ce, $i, 1);
                        if ($cei === ' ') { continue; }
                        $pdf->SetXY(41.5 + $i * 3.85, $rowY);
                        $pdf->Write(4, $cei);
                    }
                    //$pdf->SetXY(44.0, $rowY); $pdf->Write(4, $l[0]);
                    // ravvedimento operoso (solo per tardivo)
                    if ($isTardivo) { $pdf->SetXY(59.5, $rowY); $pdf->Write(4, 'X'); }
                    // acconto o saldo
                    if ($isAcconto) { $pdf->SetXY(73.5, $rowY); $pdf->Write(4, 'X'); }
                    else            { $pdf->SetXY(80.0, $rowY); $pdf->Write(4, 'X'); }
                    // numero immobili
                    $pdf->SetXY(87.5,  $rowY); $pdf->Write(4, (string)$l[3]);
                    // anno
                    $pdf->SetXY(110.0, $rowY); $pdf->Write(4, (string)$l[2]);
                    // importi a debito
                    $writeAmt((float)$l[4], 147.0, 19.2, 165.4, $rowY);
                }
                // Saldo finale
                $pdf->SetFont('Helvetica', 'B', 9.0);
                $writeAmt($imuDovuta, 171.0, 24.3, 194.8, $yOff + 115.0);
            };

            $writeCopy(0.0);
            $writeCopy(144.0);
            $pdf->Output($filePath, 'F');

            if (!file_exists($filePath) || filesize($filePath) === 0) {
                return ['error' => 'PDF F24 generato ma vuoto o assente.'];
            }
            @chmod($filePath, 0644);
            return ['url' => \yii\helpers\Url::to(['/imu/download', 'file' => $filename], true)];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Metodi privati — terreni edificabili
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Interroga i terreni catastali del contribuente.
     * I terreni sono in PARTICELLA (non TERRENI), collegati tramite TITOLARITA.idParticella.
     */
    private function queryTerreni(\SQLite3 $db, string $cognome, string $nome, string $dataNasc, string $codFisc): array
    {
        if ($codFisc) {
            $cond = ['upper(trim(pf.codFiscale)) = upper(trim(:codFiscale))'];
        } else {
            $cond = ['upper(trim(pf.cognome)) = upper(trim(:cognome))'];
            if ($nome)     { $cond[] = 'upper(trim(pf.nome)) LIKE upper(trim(:nome))'; }
            if ($dataNasc) { $cond[] = 'pf.dataNascita = :dataNascita'; }
        }

        // I terreni usano TITOLARITA.idParticella (i fabbricati usano idImmobile)
        $sql = 'SELECT
                    CAST(p.foglio AS INTEGER) AS foglio,
                    CAST(p.numero AS INTEGER) AS numero,
                    COALESCE(cp.ettari,   0) AS ettari,
                    COALESCE(cp.are,      0) AS are,
                    COALESCE(cp.centiare, 0) AS centiare,
                    MAX(CASE WHEN t.quotaNum > 0 THEN t.quotaNum ELSE NULL END) AS quotaNum,
                    MAX(CASE WHEN t.quotaNum > 0 THEN t.quotaDen ELSE NULL END) AS quotaDen
                FROM PERSONA_FISICA pf
                JOIN TITOLARITA t  ON pf.idSoggetto = t.idSoggetto
                                   AND t.idParticella IS NOT NULL
                JOIN PARTICELLA p  ON t.idParticella = p.idParticella
                LEFT JOIN CARATTERISTICHE_PARTICELLA cp
                       ON cp.idParticella = p.idParticella
                WHERE ' . implode(' AND ', $cond) . '
                GROUP BY p.foglio, p.numero
                ORDER BY foglio, numero
                LIMIT 300';

        $stmt = $db->prepare($sql);
        if (!$stmt) return [];

        if ($codFisc) {
            $stmt->bindValue(':codFiscale', $codFisc, SQLITE3_TEXT);
        } else {
            $stmt->bindValue(':cognome', $cognome, SQLITE3_TEXT);
            if ($nome)     { $stmt->bindValue(':nome', '%' . $nome . '%', SQLITE3_TEXT); }
            if ($dataNasc) { $stmt->bindValue(':dataNascita', $dataNasc, SQLITE3_TEXT); }
        }

        $res = $stmt->execute();
        if (!$res) return [];

        $seen = [];
        $results = [];
        while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $key = $row['foglio'] . '|' . $row['numero'];
            if (isset($seen[$key])) continue;
            $seen[$key] = true;

            $quotaNum = (int)($row['quotaNum'] ?? 1);
            $quotaDen = (int)($row['quotaDen'] ?? 1);
            if ($quotaNum <= 0) { $quotaNum = 1; $quotaDen = 1; }
            $mqTotali = (int)$row['ettari'] * 10000 + (int)$row['are'] * 100 + (int)$row['centiare'];

            $results[] = [
                'foglio'   => (string)$row['foglio'],
                'numero'   => (string)$row['numero'],
                'mqTotali' => $mqTotali,
                'quotaNum' => $quotaNum,
                'quotaDen' => $quotaDen > 0 ? $quotaDen : 1,
            ];
        }
        return $results;
    }

    /**
     * Per ogni terreno controlla, tramite geoPHP + PRG, se ricade (anche parzialmente)
     * in una zona edificabile (B, C, D, H, PEEP…).
     * Restituisce i terreni edificabili come array pronti per la lista IMU.
     */
    private function checkTerreniEdificabili(array $terreni): array
    {
        if (empty($terreni)) return [];

        $geophpPath = Yii::$app->basePath . '/vendor/phayes/geophp/geoPHP.inc';
        if (!file_exists($geophpPath)) return [];
        require_once $geophpPath;

        $prgPath = Yii::getAlias('@webroot') . '/mappe/b542/prg_epsg7792.geojson';
        if (!file_exists($prgPath)) return [];

        $prg = json_decode(file_get_contents($prgPath));
        if (!$prg || empty($prg->features)) return [];

        $ultimaMappa = DatiMappe::find()->orderBy(['dataMappe' => SORT_DESC])->one();
        $mappaBase   = $ultimaMappa
            ? Yii::getAlias('@webroot') . '/' . $ultimaMappa->folder_path
            : Yii::getAlias('@webroot') . '/mappe/b542/V2025-09-22';

        // Raggruppa per foglio per leggere ogni GeoJSON una sola volta
        $byFoglio = [];
        foreach ($terreni as $t) { $byFoglio[(int)$t['foglio']][] = $t; }

        $risultati = [];
        foreach ($byFoglio as $foglio => $tList) {
            $nomefile   = 'B542_00' . trim(sprintf('%02u', $foglio)) . '00.geojson';
            $geojsonPath = $mappaBase . '/' . $nomefile;
            if (!file_exists($geojsonPath)) continue;

            $jsone = json_decode(file_get_contents($geojsonPath), true);
            if (!$jsone) continue;

            // Indicizza le geometrie delle particelle per numero
            $geomByNum = [];
            foreach ($jsone['features'] as $prc) {
                if (($prc['properties']['LIVELLO'] ?? '') !== 'PARTICELLE') continue;
                $geomByNum[(string)(int)$prc['properties']['CODICE']] = $prc['geometry'];
            }

            foreach ($tList as $terreno) {
                $numero = $terreno['numero'];
                if (!isset($geomByNum[$numero])) continue;

                try {
                    $rpc = call_user_func(['geoPHP', 'load'], json_encode($geomByNum[$numero]), 'json');
                    if (!$rpc) continue;
                } catch (\Exception) { continue; }

                // Fattore di rettifica: rapporto tra superficie catastale (dati censuari)
                // e superficie calcolata dalla mappa vettoriale, per compensare le discrepanze
                // geometriche tra PRG e catasto.
                $mqMappa          = $rpc->getArea();
                $mqCensuari       = (float)$terreno['mqTotali'];
                $fattoreRettifica = ($mqMappa > 0 && $mqCensuari > 0)
                    ? ($mqCensuari / $mqMappa)
                    : 1.0;

                // Interseca con le zone edificabili del PRG
                $zoneTrovate = [];
                foreach ($prg->features as $zona) {
                    $z = trim($zona->properties->z ?? '');
                    if (!$this->isZonaEdificabile($z)) continue;
                    try {
                        $zo = call_user_func(['geoPHP', 'load'], json_encode($zona), 'json');
                        if (!$zo->intersects($rpc)) continue;
                        $in       = $zo->intersection($rpc);
                        $areaGeom = $in ? $in->getArea() : 0;
                        if ($areaGeom > 5) {
                            // Applica la rettifica: mq_PRG × (mq_censuari / mq_mappa)
                            $areaRett = round($areaGeom * $fattoreRettifica, 0);
                            $desc     = $z . ($zona->properties->estes ? ' - ' . $zona->properties->estes : '');
                            $zoneTrovate[] = ['codice' => $z, 'zona' => $desc, 'area' => (float)$areaRett];
                        }
                    } catch (\Exception) { continue; }
                }

                if (empty($zoneTrovate)) continue;

                // Ordina per area decrescente: la zona prevalente è la prima
                usort($zoneTrovate, fn($a, $b) => $b['area'] <=> $a['area']);

                $mqEd     = (int)array_sum(array_column($zoneTrovate, 'area'));
                $zonaStr  = implode('; ', array_column($zoneTrovate, 'zona'));
                $zonaCode = $zoneTrovate[0]['codice'];

                $risultati[] = [
                    'tipoRecord'   => 'area',
                    'foglio'       => $terreno['foglio'],
                    'numero'       => $terreno['numero'],
                    'subalterno'   => '0',
                    'categoria'    => 'AF',
                    'rendita'      => 0.0,
                    'quotaNum'     => $terreno['quotaNum'],
                    'quotaDen'     => $terreno['quotaDen'],
                    'mqTotali'     => $terreno['mqTotali'],
                    'consistenza'  => $mqEd,
                    'valoreVenale' => 0.0,
                    'zona'         => $zonaStr,
                    'zonaCode'     => $zonaCode,
                    'tipoUtilizzo' => 'area_fabbricabile',
                ];
            }
        }
        return $risultati;
    }

    /** Restituisce true se la sigla di zona PRG è edificabile (B, C1-C12, CI, Ct, D, H, PEEP…). */
    private function isZonaEdificabile(string $z): bool
    {
        $z = strtoupper(trim($z));
        return (bool)(
            preg_match('/^B\d*$/', $z)    // B, B1, B2 …
            || preg_match('/^C\d+[A-Z]?\d*$/', $z) // C1…C12, C1I, C2I, C3I, C4I …
            || preg_match('/^CI\d*$/', $z) // CI, CI1, CI2 …
            || preg_match('/^CT\d*$/', $z) // Ct, Ct1 …
            || preg_match('/^D\d*$/', $z) // D, D1 …
            || preg_match('/^H\d*$/', $z) // H
            || str_contains($z, 'PEEP')
        );
    }
}
