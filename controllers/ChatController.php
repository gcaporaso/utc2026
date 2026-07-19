<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;

/**
 * Chatbot AI locale via Ollama.
 * Pipeline: testo utente → Ollama (JSON: sql|map|clarify) → esecuzione → Ollama (interpretazione) → risposta.
 */
class ChatController extends Controller
{
    /** URL endpoint Ollama (OpenAI-compatible) */
    private string $ollamaUrl = 'http://localhost:11434/v1/chat/completions';

    /** Nome del modello Ollama da usare */
    private string $ollamaModel = 'utcbim-assistant';

    /** Schema DB da iniettare nel system prompt */
    private string $databaseSchema = "CREATE TABLE `edilizia` (
  `edilizia_id` int NOT NULL AUTO_INCREMENT,
  `DataProtocollo` date NOT NULL,
  `NumeroProtocollo` int NOT NULL,
  `id_committente` int NOT NULL,
  `id_titolo` int NOT NULL DEFAULT '4',
  `DescrizioneIntervento` mediumtext,
  `PROGETTISTA_ARC_ID` int DEFAULT NULL,
  `DIR_LAV_ARCH_ID` int DEFAULT NULL,
  `PROGETTISTA_STR_ID` int DEFAULT NULL,
  `DIR_LAV_STR_ID` int DEFAULT NULL,
  `IMPRESA_ID` int DEFAULT NULL,
  `CatastoFoglio` int DEFAULT NULL,
  `CatastoParticella` varchar(45) DEFAULT NULL,
  `CatastoSub` varchar(25) DEFAULT NULL,
  `Stato_Pratica_id` int NOT NULL DEFAULT '0',
  `Latitudine` double DEFAULT NULL,
  `Longitudine` double DEFAULT NULL,
  `Oneri_Costruzione` decimal(9,2) DEFAULT '0.00',
  `Oneri_Urbanizzazione` decimal(9,2) DEFAULT NULL,
  `Oneri_Pagati` decimal(9,2) DEFAULT '0.00',
  `Data_Inizio_Lavori` date DEFAULT NULL,
  `Data_Fine_Lavori` date DEFAULT NULL,
  `Sanatoria` tinyint DEFAULT '0',
  `tipologia_id` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`edilizia_id`)
) ENGINE=InnoDB;
CREATE TABLE `sismica` (
  `sismica_id` int NOT NULL AUTO_INCREMENT,
  `Protocollo` int NOT NULL,
  `DataProtocollo` date NOT NULL,
  `committenti_id` int NOT NULL,
  `DescrizioneLavori` varchar(255) NOT NULL,
  `TipoDenuncia` int NOT NULL DEFAULT '1',
  `TipoProcedimento` int NOT NULL DEFAULT '0',
  `PROG_ARCH_ID` int DEFAULT NULL,
  `PROG_STR_ID` int DEFAULT NULL,
  `DD_LL_ARCH_ID` int DEFAULT NULL,
  `DIR_LAV_STR_ID` int DEFAULT NULL,
  `IMPRESA_ID` int DEFAULT NULL,
  `CatastoFoglio` varchar(45) DEFAULT NULL,
  `CatastoParticelle` varchar(45) DEFAULT NULL,
  `NumeroAUTORIZZAZIONE` int DEFAULT NULL,
  `DataAUTORIZZAZIONE` date DEFAULT NULL,
  `ImportoContributo` double DEFAULT NULL,
  `StatoPratica` int NOT NULL DEFAULT '5',
  `Inizio_Lavori` date DEFAULT NULL,
  `Fine_Lavori` date DEFAULT NULL,
  `Data_Collaudo` date DEFAULT NULL,
  `pratica_id` int DEFAULT NULL,
  PRIMARY KEY (`sismica_id`)
) ENGINE=InnoDB;
CREATE TABLE `paesistica` (
  `idpaesistica` int NOT NULL AUTO_INCREMENT,
  `NumeroProtocollo` int NOT NULL,
  `DataProtocollo` date NOT NULL,
  `idcommittente` int NOT NULL,
  `DescrizioneIntervento` mediumtext NOT NULL,
  `StatoPratica` int NOT NULL DEFAULT '1',
  `NumeroAutorizzazione` int DEFAULT NULL,
  `DataAutorizzazione` date DEFAULT NULL,
  `CatastoFoglio` varchar(45) DEFAULT NULL,
  `CatastoParticella` varchar(45) DEFAULT NULL,
  `Progettista_ID` int DEFAULT NULL,
  `Impresa_ID` int DEFAULT NULL,
  `Indennita` double NOT NULL DEFAULT '0',
  `InviatoSoprintendenza` tinyint(1) DEFAULT '0',
  `InviatoRegione` tinyint(1) DEFAULT '0',
  `Edilizia_ID` int DEFAULT NULL,
  PRIMARY KEY (`idpaesistica`)
) ENGINE=InnoDB;
CREATE TABLE `cdu` (
  `idcdu` int NOT NULL AUTO_INCREMENT,
  `idrich` int NOT NULL,
  `foglio1` varchar(45) NOT NULL,
  `particelle1` varchar(255) NOT NULL,
  `foglio2` varchar(45) DEFAULT NULL,
  `particelle2` varchar(255) DEFAULT NULL,
  `foglio3` varchar(45) DEFAULT NULL,
  `particelle3` varchar(255) DEFAULT NULL,
  `foglio4` varchar(45) DEFAULT NULL,
  `particelle4` varchar(255) DEFAULT NULL,
  `esenzione` int NOT NULL DEFAULT '0',
  `NumeroProtocollo` varchar(45) NOT NULL,
  `DataProtocollo` date NOT NULL,
  PRIMARY KEY (`idcdu`)
) ENGINE=InnoDB;
CREATE TABLE `committenti` (
  `committenti_id` int NOT NULL AUTO_INCREMENT,
  `Cognome` varchar(45) DEFAULT NULL,
  `Nome` varchar(25) DEFAULT NULL,
  `CodiceFiscale` varchar(25) DEFAULT NULL,
  `DataNascita` date DEFAULT NULL,
  `ComuneNascita` varchar(45) DEFAULT NULL,
  `IndirizzoResidenza` varchar(45) DEFAULT NULL,
  `ComuneResidenza` varchar(25) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `PEC` varchar(255) DEFAULT NULL,
  `Telefono` varchar(12) DEFAULT NULL,
  `Cellulare` varchar(15) DEFAULT NULL,
  `Denominazione` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`committenti_id`)
) ENGINE=InnoDB;
CREATE TABLE `tecnici` (
  `tecnici_id` int NOT NULL AUTO_INCREMENT,
  `COGNOME` varchar(12) DEFAULT NULL,
  `NOME` varchar(25) DEFAULT NULL,
  `CODICE_FISCALE` varchar(25) DEFAULT NULL,
  `ALBO` varchar(25) DEFAULT NULL,
  `PROVINCIA_ALBO` varchar(12) DEFAULT NULL,
  `NUMERO_ISCRIZIONE` varchar(12) DEFAULT NULL,
  `EMAIL` varchar(255) DEFAULT NULL,
  `PEC` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`tecnici_id`)
) ENGINE=InnoDB;
CREATE TABLE `imprese` (
  `imprese_id` int NOT NULL AUTO_INCREMENT,
  `RAGIONE_SOCIALE` varchar(255) NOT NULL,
  `CODICE_FISCALE` varchar(16) DEFAULT NULL,
  `PartitaIVA` varchar(11) DEFAULT NULL,
  `EMAIL` varchar(45) DEFAULT NULL,
  `PEC` varchar(45) DEFAULT NULL,
  `Telefono` int DEFAULT NULL,
  `Cellulare` bigint DEFAULT NULL,
  PRIMARY KEY (`imprese_id`)
) ENGINE=InnoDB;
CREATE TABLE `commissioni` (
  `idcommissioni` int NOT NULL AUTO_INCREMENT,
  `Descrizione` varchar(65) NOT NULL,
  `Tipo` int NOT NULL DEFAULT '1',
  `NumeroDelibera` int DEFAULT NULL,
  `DataDelibera` date DEFAULT NULL,
  PRIMARY KEY (`idcommissioni`)
) ENGINE=InnoDB;
CREATE TABLE `sedute_commissioni` (
  `idsedute_commissioni` int NOT NULL AUTO_INCREMENT,
  `commissione_id` int NOT NULL DEFAULT '1',
  `dataseduta` date NOT NULL,
  `statoseduta` int NOT NULL DEFAULT '1',
  `presenze` int NOT NULL,
  `orarioconvocazione` time NOT NULL DEFAULT '18:00:00',
  `numero` int NOT NULL,
  PRIMARY KEY (`idsedute_commissioni`)
) ENGINE=InnoDB;
CREATE TABLE `pareri_commissioni` (
  `idpareri_commissioni` int NOT NULL AUTO_INCREMENT,
  `commissioni_id` int NOT NULL,
  `pratica_id` int NOT NULL,
  `seduta_id` int NOT NULL,
  `tipoparere_id` int NOT NULL,
  `testoparere` mediumtext NOT NULL,
  PRIMARY KEY (`idpareri_commissioni`)
) ENGINE=InnoDB;
CREATE TABLE `stato_edilizia` (`idstato_edilizia` int NOT NULL AUTO_INCREMENT, `descrizione` varchar(45) NOT NULL, PRIMARY KEY (`idstato_edilizia`)) ENGINE=InnoDB;
CREATE TABLE `titoli_edilizia` (`titoli_id` int NOT NULL AUTO_INCREMENT, `TITOLO` varchar(15) DEFAULT NULL, `DESCRIZIONE` varchar(255) DEFAULT NULL, PRIMARY KEY (`titoli_id`)) ENGINE=InnoDB;
CREATE TABLE `tipologia` (`idtipologia` int NOT NULL AUTO_INCREMENT, `Categoria` varchar(25) NOT NULL, `DESCRIZIONE` varchar(800) NOT NULL, PRIMARY KEY (`idtipologia`)) ENGINE=InnoDB;
CREATE TABLE `tipoparere` (`idtipoparere` int NOT NULL AUTO_INCREMENT, `esitoparere` varchar(45) NOT NULL, PRIMARY KEY (`idtipoparere`)) ENGINE=InnoDB;
CREATE TABLE `oneri_concessori` (`idoneri` int NOT NULL AUTO_INCREMENT, `edilizia_id` int NOT NULL, `tiporata` int NOT NULL DEFAULT '0', `importodovutorata` decimal(9,2) DEFAULT NULL, `datascadenza` date DEFAULT NULL, `importopagatorata` decimal(9,2) DEFAULT NULL, `datapagamento` date DEFAULT NULL, `pagata` tinyint NOT NULL DEFAULT '0', PRIMARY KEY (`idoneri`)) ENGINE=InnoDB;
RELAZIONI:
edilizia.id_committente => committenti.committenti_id
edilizia.id_titolo => titoli_edilizia.titoli_id
edilizia.tipologia_id => tipologia.idtipologia
edilizia.PROGETTISTA_ARC_ID => tecnici.tecnici_id
edilizia.DIR_LAV_ARCH_ID => tecnici.tecnici_id
edilizia.IMPRESA_ID => imprese.imprese_id
sismica.committenti_id => committenti.committenti_id
sismica.pratica_id => edilizia.edilizia_id
paesistica.idcommittente => committenti.committenti_id
paesistica.Edilizia_ID => edilizia.edilizia_id
cdu.idrich => committenti.committenti_id";

    /** System prompt inviato al modello a ogni chiamata */
    private function buildSystemPrompt(): string
    {
        return "Sei l'assistente AI per il sistema UTC-BIM del Comune di Campoli del Monte Taburno.
Interpreta le domande degli utenti e rispondi SOLO con JSON valido (nessun testo aggiuntivo).

SCHEMA DEL DATABASE MySQL:\n" . $this->databaseSchema . "

FORMATO RISPOSTA:
- Query SQL:      {\"type\":\"sql\",\"query\":\"SELECT ... LIMIT 100\"}
- Azione mappa:   {\"type\":\"map\",\"action\":\"highlight_particelle|zoom_foglio|highlight_edilizia|show_layer|reset_map\",\"params\":{}}
- Non comprendo:  {\"type\":\"clarify\",\"message\":\"Descrivi cosa non hai capito\"}

REGOLE FONDAMENTALI:
- Rispondi SOLO con JSON puro, mai testo prima o dopo
- Solo SELECT, mai INSERT/UPDATE/DELETE/DROP/ALTER/TRUNCATE
- Aggiungi LIMIT 100 se non diversamente specificato
- Per ricerche testuali usa LIKE '%...%'
- Per anni usa YEAR(campo), per mesi MONTH(campo)
- Azioni mappa quando si chiede di mostrare/evidenziare/zoomare elementi cartografici";
    }

    // -----------------------------------------------------------------------
    // VIEW principale + Log admin
    // -----------------------------------------------------------------------

    public function actionLog(): Response|string
    {
        $type     = Yii::$app->request->get('type');
        $feedback = Yii::$app->request->get('feedback');
        $q        = Yii::$app->request->get('q');
        $export   = Yii::$app->request->get('export');

        $where  = ['1=1'];
        $params = [];
        if ($type)          { $where[] = 'action_type = :type';          $params[':type']  = $type; }
        if ($q)             { $where[] = 'user_query LIKE :q';            $params[':q']     = '%' . $q . '%'; }
        if ($feedback === 'neg')  { $where[] = 'feedback = 0'; }
        if ($feedback === 'pos')  { $where[] = 'feedback = 1'; }
        if ($feedback === 'none') { $where[] = 'feedback IS NULL'; }

        $sql = 'SELECT * FROM ai_chat_log WHERE ' . implode(' AND ', $where) . ' ORDER BY id DESC LIMIT 500';

        try {
            $rows = Yii::$app->db->createCommand($sql, $params)->queryAll();
        } catch (\Exception) {
            $rows = [];
        }

        // Export JSONL per il fine-tuning (feedback negativi o errori)
        if ($export === 'jsonl') {
            Yii::$app->response->format = Response::FORMAT_RAW;
            Yii::$app->response->headers->set('Content-Type', 'application/x-ndjson');
            Yii::$app->response->headers->set('Content-Disposition', 'attachment; filename="ai_finetune_candidates.jsonl"');
            $lines = [];
            foreach ($rows as $r) {
                $lines[] = json_encode([
                    'id'         => $r['id'],
                    'query'      => $r['user_query'],
                    'type'       => $r['action_type'],
                    'sql'        => $r['sql_query'],
                    'feedback'   => $r['feedback'],
                    'note'       => $r['feedback_note'],
                    'response_ok'=> (bool)$r['response_ok'],
                ], JSON_UNESCAPED_UNICODE);
            }
            return Yii::$app->response->data = implode("\n", $lines);
        }

        try {
            $stats = Yii::$app->db->createCommand('
                SELECT
                    COUNT(*)                                                AS totale,
                    SUM(response_ok)                                        AS ok,
                    SUM(1 - response_ok)                                    AS errori,
                    SUM(CASE WHEN feedback = 1 THEN 1 ELSE 0 END)          AS feedback_pos,
                    SUM(CASE WHEN feedback = 0 THEN 1 ELSE 0 END)          AS feedback_neg,
                    ROUND(AVG(response_ms))                                 AS avg_ms
                FROM ai_chat_log
            ')->queryOne();
        } catch (\Exception) {
            $stats = ['totale' => 0, 'ok' => 0, 'errori' => 0, 'feedback_pos' => 0, 'feedback_neg' => 0, 'avg_ms' => 0];
        }

        $this->layout = 'main';
        return $this->render('log', ['rows' => $rows, 'stats' => $stats]);
    }

    public function actionStatus(): Response
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $ch = curl_init('http://localhost:11434/api/tags');
        curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 3]);
        $raw   = curl_exec($ch);
        $errno = curl_errno($ch);
        unset($ch);
        return $this->asJson(['ok' => $errno === 0 && $raw !== false]);
    }

    public function actionQchat(): string
    {
        $this->layout = 'main';
        return $this->render('qchat');
    }

    // -----------------------------------------------------------------------
    // ENDPOINT AJAX principale
    // -----------------------------------------------------------------------

    public function actionChat(): Response
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!Yii::$app->request->isAjax) {
            return $this->asJson(['error' => 'Richiesta non valida.']);
        }

        $userMessage = trim(Yii::$app->request->post('msguser', ''));
        if ($userMessage === '') {
            return $this->asJson(['error' => 'Messaggio vuoto.']);
        }

        $tStart = microtime(true);

        // Step 1: testo → JSON action (sql / map / clarify)
        $action = $this->callOllama([
            ['role' => 'system', 'content' => $this->buildSystemPrompt()],
            ['role' => 'user',   'content' => $userMessage],
        ]);

        if (isset($action['error'])) {
            $this->log($userMessage, 'error', null, null, null, false, (int)((microtime(true) - $tStart) * 1000));
            return $this->asJson(['response' => 'Errore AI: ' . $action['error']]);
        }

        $parsed = $this->parseJson($action['content'] ?? '');
        if ($parsed === null) {
            $this->log($userMessage, 'error', null, null, null, false, (int)((microtime(true) - $tStart) * 1000));
            return $this->asJson(['response' => $action['content'] ?? 'Risposta non valida dal modello.']);
        }

        $ms = (int)((microtime(true) - $tStart) * 1000);

        switch ($parsed['type'] ?? '') {
            case 'sql':
                $result = $this->handleSql($parsed['query'] ?? '', $userMessage);
                $ok     = !isset($result['error']);
                $logId  = $this->log($userMessage, 'sql', $parsed['query'] ?? null, null, $result['count'] ?? null, $ok, $ms);
                $result['log_id'] = $logId;
                return $this->asJson($result);

            case 'map':
                $logId = $this->log($userMessage, 'map', null, $parsed['action'] ?? null, null, true, $ms);
                return $this->asJson([
                    'response'   => 'Azione mappa eseguita.',
                    'map_action' => $parsed,
                    'log_id'     => $logId,
                ]);

            case 'clarify':
                $logId = $this->log($userMessage, 'clarify', null, null, null, true, $ms);
                return $this->asJson(['response' => $parsed['message'] ?? 'Domanda non chiara.', 'log_id' => $logId]);

            default:
                $logId = $this->log($userMessage, 'error', null, null, null, false, $ms);
                return $this->asJson(['response' => $action['content'], 'log_id' => $logId]);
        }
    }

    // -----------------------------------------------------------------------
    // ENDPOINT AJAX: feedback utente (👍 / 👎)
    // -----------------------------------------------------------------------

    public function actionFeedback(): Response
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $logId   = (int)Yii::$app->request->post('log_id', 0);
        $value   = (int)Yii::$app->request->post('value', -1);   // 1=positivo, 0=negativo
        $note    = trim(Yii::$app->request->post('note', ''));

        if ($logId <= 0 || !in_array($value, [0, 1], true)) {
            return $this->asJson(['ok' => false]);
        }

        try {
            Yii::$app->db->createCommand()->update('ai_chat_log', [
                'feedback'      => $value,
                'feedback_note' => $note ?: null,
            ], ['id' => $logId])->execute();
        } catch (\Exception) {
            return $this->asJson(['ok' => false]);
        }

        return $this->asJson(['ok' => true]);
    }

    // -----------------------------------------------------------------------
    // Esegue la query SQL e chiede al modello di interpretare i risultati
    // -----------------------------------------------------------------------

    private function handleSql(string $query, string $originalQuestion): array
    {
        if ($query === '') {
            return ['response' => 'Nessuna query generata.'];
        }

        // Sicurezza: blocca tutto ciò che non è SELECT
        if (!preg_match('/^\s*SELECT\s/i', $query)) {
            return ['response' => 'Operazione non consentita: sono ammesse solo interrogazioni di lettura.'];
        }

        $rows = $this->executeQuery($query);

        if (isset($rows['error'])) {
            return ['response' => 'Errore nell\'esecuzione della query: ' . $rows['error'], 'query' => $query];
        }

        if (empty($rows)) {
            return ['response' => 'Nessun risultato trovato per la tua ricerca.', 'query' => $query];
        }

        // Step 2: risultati → risposta in linguaggio naturale
        $interpretation = $this->callOllama([
            ['role' => 'system', 'content' => 'Sei un assistente che formatta dati del database comunale in italiano chiaro e strutturato. Ricevi una domanda originale e i risultati JSON di una query MySQL. Rispondi in italiano con un testo ben formattato, sintetico e informativo. Non mostrare JSON grezzo.'],
            ['role' => 'user',   'content' => "Domanda: {$originalQuestion}\n\nRisultati (max 50 righe mostrati):\n" . json_encode(array_slice($rows, 0, 50), JSON_UNESCAPED_UNICODE)],
        ]);

        $responseText = $interpretation['content'] ?? 'Risultati ottenuti ma impossibile formattarli.';

        return [
            'response' => $responseText,
            'query'    => $query,
            'count'    => count($rows),
        ];
    }

    // -----------------------------------------------------------------------
    // Chiama Ollama via REST
    // -----------------------------------------------------------------------

    private function callOllama(array $messages): array
    {
        $payload = json_encode([
            'model'       => $this->ollamaModel,
            'messages'    => $messages,
            'temperature' => 0.1,
            'stream'      => false,
        ]);

        $ch = curl_init($this->ollamaUrl);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 120,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($payload),
            ],
        ]);

        $raw   = curl_exec($ch);
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        unset($ch); // curl_close() è no-op in PHP 8+

        if ($errno) {
            return ['error' => "Connessione a Ollama fallita: {$error}. Verificare che Ollama sia in esecuzione su localhost:11434."];
        }

        $data = json_decode($raw, true);
        if (!$data) {
            return ['error' => 'Risposta non valida da Ollama.'];
        }

        $content = $data['choices'][0]['message']['content'] ?? null;
        if ($content === null) {
            return ['error' => 'Nessun contenuto nella risposta di Ollama.'];
        }

        return ['content' => trim($content)];
    }

    // -----------------------------------------------------------------------
    // Esegue una SELECT sul DB
    // -----------------------------------------------------------------------

    private function executeQuery(string $query): array
    {
        try {
            return Yii::$app->db->createCommand($query)->queryAll();
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // -----------------------------------------------------------------------
    // Logging: scrive un record in ai_chat_log e restituisce l'id
    // -----------------------------------------------------------------------

    private function log(
        string  $userQuery,
        string  $actionType,
        ?string $sqlQuery,
        ?string $mapAction,
        ?int    $rowCount,
        bool    $responseOk,
        int     $responseMs
    ): int {
        try {
            Yii::$app->db->createCommand()->insert('ai_chat_log', [
                'user_query'  => $userQuery,
                'action_type' => $actionType,
                'sql_query'   => $sqlQuery,
                'map_action'  => $mapAction,
                'row_count'   => $rowCount,
                'response_ok' => $responseOk ? 1 : 0,
                'model_name'  => $this->ollamaModel,
                'response_ms' => $responseMs,
            ])->execute();
            return (int)Yii::$app->db->getLastInsertID();
        } catch (\Exception) {
            return 0;
        }
    }

    // -----------------------------------------------------------------------
    // Estrae il JSON dall'output del modello (gestisce markdown code blocks)
    // -----------------------------------------------------------------------

    private function parseJson(string $text): ?array
    {
        // Rimuove eventuali ```json ... ``` che alcuni modelli aggiungono
        $text = preg_replace('/^```(?:json)?\s*/im', '', $text);
        $text = preg_replace('/```\s*$/im', '', $text);
        $text = trim($text);

        $decoded = json_decode($text, true);
        return is_array($decoded) ? $decoded : null;
    }

    // -----------------------------------------------------------------------
    // API GeoJSON: pratiche edilizie con coordinate per Leaflet
    // -----------------------------------------------------------------------

    public function actionEdiliziaGeojson(): Response
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::$app->response->headers->set('Content-Type', 'application/geo+json');

        $anno   = Yii::$app->request->get('anno');
        $foglio = Yii::$app->request->get('foglio');

        $where  = ['AND', 'e.Latitudine IS NOT NULL', 'e.Longitudine IS NOT NULL'];
        $params = [];

        if ($anno && is_numeric($anno)) {
            $where[]         = 'YEAR(e.DataProtocollo) = :anno';
            $params[':anno'] = (int)$anno;
        }
        if ($foglio && is_numeric($foglio)) {
            $where[]           = 'e.CatastoFoglio = :foglio';
            $params[':foglio'] = (int)$foglio;
        }

        $sql = 'SELECT e.edilizia_id, e.NumeroProtocollo, e.DataProtocollo,
                       e.DescrizioneIntervento, e.Latitudine, e.Longitudine
                FROM edilizia e
                WHERE ' . implode(' AND ', array_slice($where, 1));

        try {
            $rows = Yii::$app->db->createCommand($sql, $params)->queryAll();
        } catch (\Exception) {
            return $this->asJson(['type' => 'FeatureCollection', 'features' => []]);
        }

        $features = array_map(function ($r) {
            return [
                'type'       => 'Feature',
                'geometry'   => ['type' => 'Point', 'coordinates' => [(float)$r['Longitudine'], (float)$r['Latitudine']]],
                'properties' => [
                    'NumeroProtocollo'    => $r['NumeroProtocollo'],
                    'DataProtocollo'      => $r['DataProtocollo'],
                    'DescrizioneIntervento' => $r['DescrizioneIntervento'],
                ],
            ];
        }, $rows);

        return $this->asJson(['type' => 'FeatureCollection', 'features' => $features]);
    }
}
