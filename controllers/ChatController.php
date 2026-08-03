<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\DatiCensuari;

/**
 * Chatbot AI locale via Ollama.
 * Pipeline: testo utente → Ollama (JSON: sql|map|clarify) → esecuzione → Ollama (interpretazione) → risposta.
 */
class ChatController extends Controller
{
    /** URL endpoint Ollama (OpenAI-compatible) */
    private string $ollamaUrl = 'http://127.0.0.1:11434/v1/chat/completions';

    /** Nome del modello Ollama da usare */
    private string $ollamaModel = 'utcbim-assistant';

    /** Schema DB compatto (notazione: tabella(col:tipo→FK_tabella)) */
    private string $databaseSchema =
        "edilizia(edilizia_id PK, DataProtocollo date, NumeroProtocollo int, id_committente→committenti, id_titolo→titoli_edilizia, DescrizioneIntervento text, PROGETTISTA_ARC_ID→tecnici, DIR_LAV_ARCH_ID→tecnici, IMPRESA_ID→imprese, CatastoFoglio int, CatastoParticella, Stato_Pratica_id→stato_edilizia, Latitudine, Longitudine, Oneri_Costruzione dec, Oneri_Urbanizzazione dec, Oneri_Pagati dec, Data_Inizio_Lavori date, Data_Fine_Lavori date, Sanatoria tinyint, tipologia_id→tipologia)\n" .
        "sismica(sismica_id PK, Protocollo int, DataProtocollo date, committenti_id→committenti, DescrizioneLavori, IMPRESA_ID→imprese, CatastoFoglio, CatastoParticelle, NumeroAUTORIZZAZIONE int, DataAUTORIZZAZIONE date, ImportoContributo dec, StatoPratica int, Inizio_Lavori date, Fine_Lavori date, Data_Collaudo date, pratica_id→edilizia)\n" .
        "paesistica(idpaesistica PK, NumeroProtocollo int, DataProtocollo date, idcommittente→committenti, DescrizioneIntervento text, StatoPratica int, NumeroAutorizzazione, DataAutorizzazione date, CatastoFoglio, CatastoParticella, Progettista_ID→tecnici, Impresa_ID→imprese, Indennita dec, InviatoSoprintendenza tinyint, InviatoRegione tinyint, Edilizia_ID→edilizia)\n" .
        "cdu(idcdu PK, idrich→committenti, foglio1 foglio2 foglio3 foglio4, particelle1 particelle2 particelle3 particelle4, esenzione int, NumeroProtocollo, DataProtocollo date)\n" .
        "committenti(committenti_id PK, Cognome, Nome, CodiceFiscale, DataNascita date, ComuneNascita, Email, PEC, Denominazione)\n" .
        "tecnici(tecnici_id PK, COGNOME, NOME, CODICE_FISCALE, ALBO, PROVINCIA_ALBO, EMAIL)\n" .
        "imprese(imprese_id PK, RAGIONE_SOCIALE, CODICE_FISCALE, PartitaIVA, EMAIL)\n" .
        "commissioni(idcommissioni PK, Descrizione, Tipo→tipo_commissioni, NumeroDelibera, DataDelibera date)\n" .
        "sedute_commissioni(idsedute_commissioni PK, commissione_id→commissioni, dataseduta date, numero int, statoseduta int, presenze int, orarioconvocazione time)\n" .
        "pareri_commissioni(idpareri_commissioni PK, commissioni_id→commissioni, pratica_id→edilizia, seduta_id→sedute_commissioni, tipoparere_id→tipoparere, testoparere text)\n" .
        "stato_edilizia(idstato_edilizia PK, descrizione)\n" .
        "titoli_edilizia(titoli_id PK, TITOLO, DESCRIZIONE)\n" .
        "tipologia(idtipologia PK, Categoria, DESCRIZIONE)\n" .
        "tipoparere(idtipoparere PK, esitoparere)\n" .
        "oneri_concessori(idoneri PK, edilizia_id→edilizia, tiporata int, importodovutorata dec, datascadenza date, importopagatorata dec, datapagamento date, pagata tinyint)\n" .
        "dati_mappe(iddati_mappe PK, dataMappe date, folder_path varchar — percorso cartella mappe catastali vettoriali GeoJSON, es: mappe/b542)";

    /** System prompt inviato al modello a ogni chiamata */
    private function buildSystemPrompt(): string
    {
        return "Assistente AI UTC-BIM. Rispondi SOLO con JSON valido, zero testo fuori dal JSON.\n" .
            "DB MySQL — schema (col→FK=chiave esterna):\n" . $this->databaseSchema . "\n" .
            "RISPOSTE:\n" .
            "{\"type\":\"sql\",\"query\":\"SELECT ... LIMIT 100\"}\n" .
            "{\"type\":\"map\",\"action\":\"highlight_particelle|zoom_foglio|zoom_particella|highlight_edilizia|show_layer|hide_layer|reset_map\",\"params\":{}}\n" .
            "{\"type\":\"clarify\",\"message\":\"...\"}\n" .
            "REGOLE: solo SELECT; LIMIT 100 default; LIKE '%x%' per testi; YEAR()/MONTH() per date; azioni mappa solo per richieste cartografiche.\n" .
            "ONERI NON PAGATI: usare edilizia JOIN committenti, calcolo debito = COALESCE(Oneri_Costruzione,0)+COALESCE(Oneri_Urbanizzazione,0)-COALESCE(Oneri_Pagati,0) WHERE debito>0; NON usare colonne inesistenti.\n" .
            "MAPPE CATASTALI: file GeoJSON versionati in web/{dati_mappe.folder_path}/; naming B542_{N*100 zero-pad 6}.geojson (es. foglio 1→B542_000100.geojson, foglio 12→B542_001200.geojson); layer Leaflet: foglio{NN}_Particelle / foglio{NN}_Fabbricati, NN=01..12.";
    }

    private function buildMapSystemPrompt(): string
    {
        return "Sei un assistente GIS. Rispondi SOLO con JSON valido, zero testo fuori dal JSON.\n\n" .
            // CATASTO in cima — alta priorità per modelli piccoli
            "CATASTO: usa {\"type\":\"catasto\"} quando la domanda chiede di trovare/cercare/evidenziare\n" .
            "particelle in base al NOME di una persona o ente (intestatario, proprietario, titolare).\n" .
            "Parole chiave che attivano SEMPRE catasto: intestato, intestata, intestati, intestate,\n" .
            "proprietario, proprietari, titolare, titolari, denominazione, comune di, chiesa, ente, impresa, societa.\n\n" .
            "{\"type\":\"catasto\",\"params\":{\"cognome\":\"\",\"nome\":\"\",\"data_nascita\":\"\",\"denominazione\":\"\"}}\n" .
            "- Persona fisica (nome e cognome separati) → usa cognome + nome\n" .
            "- Ente, comune, societa, chiesa → usa SOLO denominazione (cognome vuoto)\n" .
            "Esempi CATASTO:\n" .
            "  'particelle di Rossi Mario' → {\"type\":\"catasto\",\"params\":{\"cognome\":\"ROSSI\",\"nome\":\"MARIO\",\"data_nascita\":\"\",\"denominazione\":\"\"}}\n" .
            "  'intestate a Grasso Emilia' → {\"type\":\"catasto\",\"params\":{\"cognome\":\"GRASSO\",\"nome\":\"EMILIA\",\"data_nascita\":\"\",\"denominazione\":\"\"}}\n" .
            "  'Caporaso Giuseppe nato 16/09/1964' → {\"type\":\"catasto\",\"params\":{\"cognome\":\"CAPORASO\",\"nome\":\"GIUSEPPE\",\"data_nascita\":\"16/09/1964\",\"denominazione\":\"\"}}\n" .
            "  'COMUNE DI CAMPOLI' → {\"type\":\"catasto\",\"params\":{\"cognome\":\"\",\"nome\":\"\",\"data_nascita\":\"\",\"denominazione\":\"COMUNE DI CAMPOLI\"}}\n" .
            "  'denominazione=COMUNE DI CAMPOLI' → {\"type\":\"catasto\",\"params\":{\"cognome\":\"\",\"nome\":\"\",\"data_nascita\":\"\",\"denominazione\":\"COMUNE DI CAMPOLI\"}}\n" .
            "  'ENEL DISTRIBUZIONE' → {\"type\":\"catasto\",\"params\":{\"cognome\":\"\",\"nome\":\"\",\"data_nascita\":\"\",\"denominazione\":\"ENEL DISTRIBUZIONE\"}}\n\n" .
            // MAPPA solo quando foglio/particella sono già noti
            "MAPPA: usa {\"type\":\"map\"} SOLO quando foglio e/o particella specifici sono GIA NOTI nella domanda.\n" .
            "{\"type\":\"map\",\"action\":\"zoom_foglio\",\"params\":{\"foglio\":\"N\"}}\n" .
            "{\"type\":\"map\",\"action\":\"zoom_particella\",\"params\":{\"foglio\":\"N\",\"particella\":\"N\"}}\n" .
            "{\"type\":\"map\",\"action\":\"highlight_particelle\",\"params\":{\"foglio\":\"N\",\"particelle\":[\"X\",\"Y\"]}}\n" .
            "{\"type\":\"map\",\"action\":\"highlight_edilizia\",\"params\":{\"anno\":\"2024\",\"foglio\":\"N\"}}\n" .
            "{\"type\":\"map\",\"action\":\"show_layer\",\"params\":{\"layer\":\"nome\"}}\n" .
            "{\"type\":\"map\",\"action\":\"hide_layer\",\"params\":{\"layer\":\"nome\"}}\n" .
            "{\"type\":\"map\",\"action\":\"reset_map\",\"params\":{}}\n\n" .
            "CHIARIMENTO: {\"type\":\"clarify\",\"message\":\"...\"}\n\n" .
            "Layer tematici: piano_regolatore_generale, vincoli_idrografici, piano_frane, piano_territoriale_paesistico, perimetro_comunale.\n" .
            "Foglio e particella sono numeri interi. MAI SQL. Rispondi SOLO con JSON.";
    }


    // Rileva parole-chiave catasto nel messaggio utente (safety-net)
    private function isCatastoQuery(string $msg): bool
    {
        $patterns = [
            '/intestat[oaei]/i',
            '/proprietar[ioa]/i',
            '/titolar[ei]/i',
            '/\bdenominazione\b/i',
        ];
        foreach ($patterns as $p) {
            if (preg_match($p, $msg)) { return true; }
        }
        return false;
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
        $mode   = Yii::$app->request->post('mode', 'db');

        // Step 1: testo → JSON action (sql / map / clarify)
        $systemPrompt = ($mode === 'map') ? $this->buildMapSystemPrompt() : $this->buildSystemPrompt();
        $action = $this->callOllama([
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user',   'content' => $userMessage],
        ]);

        if (isset($action['error'])) {
            $this->log($userMessage, 'error', null, null, null, false, (int)((microtime(true) - $tStart) * 1000));
            return $this->asJson(['response' => 'Errore AI: ' . $action['error']]);
        }

        $parsed = $this->parseJson($action['content'] ?? '');
        if ($parsed === null) {
            $logId = $this->log($userMessage, 'error', null, null, null, false, (int)((microtime(true) - $tStart) * 1000));
            return $this->asJson(['response' => $action['content'] ?? 'Risposta non valida dal modello.', 'log_id' => $logId]);
        }

        // Safety-net: il modello ha restituito "map" ma la query sembra catasto → ri-chiama con prompt focalizzato
        if ($mode === 'map' && ($parsed['type'] ?? '') === 'map' && $this->isCatastoQuery($userMessage)) {
            $retry = $this->callOllama([
                ['role' => 'system', 'content' =>
                    "Estrai i parametri per la ricerca catasto dalla richiesta utente.\n" .
                    "Rispondi SOLO con questo JSON (campi vuoti se non presenti):\n" .
                    "{\"type\":\"catasto\",\"params\":{\"cognome\":\"\",\"nome\":\"\",\"data_nascita\":\"\",\"denominazione\":\"\"}}\n" .
                    "- Persona fisica: compila cognome e nome\n" .
                    "- Ente/comune/societa: compila solo denominazione\n" .
                    "Nessun testo fuori dal JSON."
                ],
                ['role' => 'user', 'content' => $userMessage],
            ]);
            if (!isset($retry['error'])) {
                $retryParsed = $this->parseJson($retry['content'] ?? '');
                if ($retryParsed && ($retryParsed['type'] ?? '') === 'catasto') {
                    $parsed = $retryParsed;
                }
            }
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

            case 'catasto':
                $result = $this->handleCatasto($parsed['params'] ?? []);
                $logId  = $this->log($userMessage, 'map', null, 'catasto_search', $result['count'] ?? null, !isset($result['error']), $ms);
                $result['log_id'] = $logId;
                return $this->asJson($result);

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
    // ENDPOINT AJAX: modifica manuale di un record log (dalla vista Log)
    // -----------------------------------------------------------------------

    public function actionUpdateLog(): Response
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $id           = (int)Yii::$app->request->post('id', 0);
        $feedback     = Yii::$app->request->post('feedback', '');
        $feedbackNote = trim(Yii::$app->request->post('feedback_note', ''));
        $actionType   = trim(Yii::$app->request->post('action_type', ''));

        if ($id <= 0) {
            return $this->asJson(['ok' => false, 'error' => 'ID non valido.']);
        }

        $data = ['feedback_note' => $feedbackNote ?: null];

        if (in_array($feedback, ['0', '1'], true)) {
            $data['feedback'] = (int)$feedback;
        } elseif ($feedback === '') {
            $data['feedback'] = null;
        }

        if (in_array($actionType, ['sql', 'map', 'clarify', 'error'], true)) {
            $data['action_type'] = $actionType;
        }

        try {
            Yii::$app->db->createCommand()->update('ai_chat_log', $data, ['id' => $id])->execute();
        } catch (\Exception $e) {
            return $this->asJson(['ok' => false, 'error' => $e->getMessage()]);
        }

        return $this->asJson(['ok' => true]);
    }

    // -----------------------------------------------------------------------
    // Ricerca catasto censuario su SQLite (catasto.db)
    // -----------------------------------------------------------------------

    private function handleCatasto(array $params): array
    {
        $cognome       = strtoupper(trim($params['cognome']        ?? ''));
        $nome          = strtoupper(trim($params['nome']           ?? ''));
        $dataNasc      = trim($params['data_nascita']              ?? '');
        $codFisc       = strtoupper(trim($params['codice_fiscale'] ?? ''));
        $denominazione = strtoupper(trim($params['denominazione']  ?? ''));

        if (!$cognome && !$denominazione) {
            return ['response' => 'Specificare almeno il cognome o la denominazione per la ricerca nel catasto.'];
        }

        // Database censuario più recente dalla tabella dati_censuari
        $ultimoDb = DatiCensuari::find()->orderBy(['dataCensuari' => SORT_DESC])->one();
        $dbPath   = $ultimoDb
            ? Yii::getAlias('@webroot') . '/' . $ultimoDb->file_path_database
            : Yii::getAlias('@webroot') . '/mappe/b542/catasto.db';

        if (!file_exists($dbPath)) {
            return ['response' => 'Database catasto non trovato. Aggiornare i dati censuari.'];
        }

        try {
            $db = new \SQLite3($dbPath, SQLITE3_OPEN_READONLY);
        } catch (\Exception $e) {
            return ['response' => 'Impossibile aprire il database catasto: ' . $e->getMessage()];
        }

        // Converte dataNascita GG/MM/AAAA → AAAA-MM-GG (formato nel DB)
        if ($dataNasc && preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $dataNasc, $m)) {
            $dataNasc = $m[3] . '-' . str_pad($m[2], 2, '0', STR_PAD_LEFT) . '-' . str_pad($m[1], 2, '0', STR_PAD_LEFT);
        }

        $parcelle    = [];
        $dateIgnored = false;

        // --- Passo 1: cerca in PERSONA_FISICA se cognome fornito ---
        if ($cognome) {
            $parcelle = $this->queryPersonaFisica($db, $cognome, $nome, $codFisc, $dataNasc);
            // Fallback senza data se non trovato nulla
            if (empty($parcelle) && $dataNasc) {
                $parcelle    = $this->queryPersonaFisica($db, $cognome, $nome, $codFisc, '');
                $dateIgnored = !empty($parcelle);
            }
        }

        // --- Passo 2: cerca in PERSONA_GIURIDICA ---
        if ($denominazione) {
            // Ricerca per denominazione esplicita (enti, comuni, società, ecc.)
            $giuridica = $this->queryPersonaGiuridica($db, $denominazione);
            $parcelle  = $this->mergeParcelle($parcelle, $giuridica);
        } elseif ($cognome) {
            // Cerca in GIURIDICA con cognome+nome, TRANNE se la data ha già identificato
            // univocamente il soggetto in FISICA (evita di aggiungere altri "CAPORASO GIUSEPPE")
            $identificatoConData = !empty($parcelle) && $dataNasc && !$dateIgnored;
            if (!$identificatoConData) {
                $giuridica = $this->queryPersonaGiuridica($db, $cognome, $nome);
                $parcelle  = $this->mergeParcelle($parcelle, $giuridica);
            }
        }

        // --- Passo 3: cerca nel Catasto Fabbricati (IDENTIFICATIVI_IMMOBILIARI) ---
        $fabbricati = [];
        try {
            if ($cognome) {
                $fab = $this->queryFabbricatiFisica($db, $cognome, $nome, $codFisc, $dataNasc);
                if (empty($fab) && $dataNasc && !$dateIgnored) {
                    $fab = $this->queryFabbricatiFisica($db, $cognome, $nome, $codFisc, '');
                }
                $fabbricati = $fab;
            }
            if ($denominazione) {
                $fabG = $this->queryFabbricatiGiuridica($db, $denominazione);
                $fabbricati = $this->mergeFabbricati($fabbricati, $fabG);
            } elseif ($cognome) {
                $identificatoConData = !empty($parcelle) && $dataNasc && !$dateIgnored;
                if (!$identificatoConData) {
                    $fabG = $this->queryFabbricatiGiuridica($db, $cognome, $nome);
                    $fabbricati = $this->mergeFabbricati($fabbricati, $fabG);
                }
            }
        } catch (\Exception) {
            // IDENTIFICATIVI_IMMOBILIARI non presente nel DB → catasto fabbricati non disponibile
        }

        $db->close();

        if (empty($parcelle) && empty($fabbricati)) {
            $label = $denominazione ?: ($cognome . ($nome ? ' ' . $nome : ''));
            return ['response' => 'Nessuna unità catastale trovata intestata a ' . $label . '.'];
        }

        // Etichetta intestatario: preferisce data nascita se presente
        $dataNascDB = null;
        foreach (array_merge($parcelle, $fabbricati) as $p) {
            if (!empty($p['data_nascita'])) { $dataNascDB = $p['data_nascita']; break; }
        }
        $labelDB = $parcelle[0]['label'] ?? ($fabbricati[0]['label'] ?? ($denominazione ?: ($cognome . ($nome ? ' ' . $nome : ''))));
        if ($dataNascDB) { $labelDB .= ' (nato/a il ' . $dataNascDB . ')'; }

        $note       = $dateIgnored ? "\n(⚠️ Data di nascita non corrispondente nel DB — risultati per nome/cognome)" : '';
        $totalCount = count($parcelle) + count($fabbricati);
        $lines      = ['Trovate ' . $totalCount . ' unità catastali intestate a ' . $labelDB . ':' . $note];

        // Sezione Catasto Terreni
        if (!empty($parcelle)) {
            $byFoglio = [];
            foreach ($parcelle as $p) { $byFoglio[$p['foglio']][] = $p['numero']; }
            ksort($byFoglio);
            $lines[] = "\n📋 Catasto Terreni (" . count($parcelle) . ' particelle):';
            foreach ($byFoglio as $f => $numeri) {
                $lines[] = '  • Foglio ' . $f . ': ' . implode(', ', $numeri);
            }
        }

        // Sezione Catasto Fabbricati
        if (!empty($fabbricati)) {
            $byFoglio = [];
            foreach ($fabbricati as $p) { $byFoglio[$p['foglio']][$p['numero']][] = $p['subalterno']; }
            ksort($byFoglio);
            $lines[] = "\n🏠 Catasto Fabbricati (" . count($fabbricati) . ' unità):';
            foreach ($byFoglio as $f => $numeri) {
                ksort($numeri);
                foreach ($numeri as $num => $subs) {
                    $subs    = array_filter($subs, fn($s) => $s !== '' && $s !== '0');
                    $subStr  = !empty($subs) ? ' — sub. ' . implode(', ', $subs) : '';
                    $lines[] = '  • Foglio ' . $f . ', particella ' . $num . $subStr;
                }
            }
        }

        // Unifica foglio+numero per il map highlight (senza duplicati fra terreni e fabbricati)
        $parcelleMap = $this->mergeParcelle(
            $parcelle,
            array_map(fn($p) => [
                'foglio'       => $p['foglio'],
                'numero'       => $p['numero'],
                'label'        => $p['label'],
                'data_nascita' => $p['data_nascita'],
            ], $fabbricati)
        );

        return [
            'response'         => implode("\n", $lines),
            'catasto_parcelle' => $parcelleMap,
            'count'            => $totalCount,
        ];
    }

    // Cerca in PERSONA_FISICA nel catasto fabbricati (IDENTIFICATIVI_IMMOBILIARI)
    private function queryFabbricatiFisica(\SQLite3 $db, string $cognome, string $nome, string $codFisc, string $dataNasc): array
    {
        $conditions = ['upper(trim(pf.cognome)) = upper(trim(:cognome))'];
        if ($nome)     { $conditions[] = 'upper(trim(pf.nome)) LIKE upper(trim(:nome))'; }
        if ($codFisc)  { $conditions[] = 'upper(trim(pf.codFiscale)) = upper(trim(:codFisc))'; }
        if ($dataNasc) { $conditions[] = 'pf.dataNascita = :dataNascita'; }

        $sql = 'SELECT ltrim(ii.foglio,\'0\') AS foglio,
                       ltrim(ii.numero,\'0\') AS numero,
                       ltrim(ii.subalterno,\'0\') AS subalterno,
                       pf.cognome || \' \' || pf.nome AS label,
                       CASE WHEN pf.dataNascita != "" THEN
                           substr(pf.dataNascita,9,2)||\'/\'||substr(pf.dataNascita,6,2)||\'/\'||substr(pf.dataNascita,1,4)
                       ELSE NULL END AS data_nasc
                FROM PERSONA_FISICA pf
                JOIN TITOLARITA t ON pf.idSoggetto = t.idSoggetto
                JOIN IDENTIFICATIVI_IMMOBILIARI ii ON t.idImmobile = ii.idImmobile
                WHERE ' . implode(' AND ', $conditions) . '
                ORDER BY foglio, numero, subalterno LIMIT 200';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':cognome', $cognome, SQLITE3_TEXT);
        if ($nome)     { $stmt->bindValue(':nome', '%' . $nome . '%', SQLITE3_TEXT); }
        if ($codFisc)  { $stmt->bindValue(':codFisc', $codFisc, SQLITE3_TEXT); }
        if ($dataNasc) { $stmt->bindValue(':dataNascita', $dataNasc, SQLITE3_TEXT); }

        return $this->fetchImmobili($stmt);
    }

    // Cerca in PERSONA_GIURIDICA nel catasto fabbricati (IDENTIFICATIVI_IMMOBILIARI)
    private function queryFabbricatiGiuridica(\SQLite3 $db, string $termine, string $nome = ''): array
    {
        $conditions = ['upper(trim(pg.denominazione)) LIKE upper(trim(:termine))'];
        if ($nome) { $conditions[] = 'upper(trim(pg.denominazione)) LIKE upper(trim(:nome))'; }

        $sql = 'SELECT ltrim(ii.foglio,\'0\') AS foglio,
                       ltrim(ii.numero,\'0\') AS numero,
                       ltrim(ii.subalterno,\'0\') AS subalterno,
                       pg.denominazione AS label,
                       NULL AS data_nasc
                FROM PERSONA_GIURIDICA pg
                JOIN TITOLARITA t ON pg.idSoggetto = t.idSoggetto
                JOIN IDENTIFICATIVI_IMMOBILIARI ii ON t.idImmobile = ii.idImmobile
                WHERE ' . implode(' AND ', $conditions) . '
                ORDER BY foglio, numero, subalterno LIMIT 200';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':termine', '%' . $termine . '%', SQLITE3_TEXT);
        if ($nome) { $stmt->bindValue(':nome', '%' . $nome . '%', SQLITE3_TEXT); }

        return $this->fetchImmobili($stmt);
    }

    // Esegue uno statement preparato e ritorna array di unità immobiliari (fabbricati) deduplicate
    private function fetchImmobili(\SQLite3Stmt $stmt): array
    {
        $res     = $stmt->execute();
        $seen    = [];
        $results = [];
        while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $foglio = (int)($row['foglio'] ?? 0);
            $numero = (string)(int)($row['numero'] ?? 0);
            $sub    = ltrim($row['subalterno'] ?? '', '0') ?: '0';
            $key = $foglio . '|' . $numero . '|' . $sub;
            if (isset($seen[$key])) { continue; }
            $seen[$key] = true;
            $results[] = [
                'foglio'       => $foglio,
                'numero'       => $numero,
                'subalterno'   => $sub,
                'label'        => trim($row['label'] ?? ''),
                'data_nascita' => $row['data_nasc'] ?? null,
                'tipo'         => 'fabbricati',
            ];
        }
        return $results;
    }

    // Unisce due array di unità immobiliari deduplicando per foglio+numero+subalterno
    private function mergeFabbricati(array $a, array $b): array
    {
        $seen = [];
        $out  = [];
        foreach (array_merge($a, $b) as $p) {
            $key = $p['foglio'] . '|' . $p['numero'] . '|' . ($p['subalterno'] ?? '');
            if (!isset($seen[$key])) {
                $seen[$key] = true;
                $out[] = $p;
            }
        }
        return $out;
    }

    // Cerca in PERSONA_FISICA per cognome, nome, codice fiscale, data nascita
    private function queryPersonaFisica(\SQLite3 $db, string $cognome, string $nome, string $codFisc, string $dataNasc): array
    {
        $conditions = ['upper(trim(pf.cognome)) = upper(trim(:cognome))'];
        if ($nome)     { $conditions[] = 'upper(trim(pf.nome)) LIKE upper(trim(:nome))'; }
        if ($codFisc)  { $conditions[] = 'upper(trim(pf.codFiscale)) = upper(trim(:codFisc))'; }
        if ($dataNasc) { $conditions[] = 'pf.dataNascita = :dataNascita'; }

        $sql = 'SELECT p.foglio, p.numero,
                       pf.cognome || \' \' || pf.nome AS label,
                       CASE WHEN pf.dataNascita != "" THEN
                           substr(pf.dataNascita,9,2)||\'/\'||substr(pf.dataNascita,6,2)||\'/\'||substr(pf.dataNascita,1,4)
                       ELSE NULL END AS data_nasc
                FROM PERSONA_FISICA pf
                JOIN TITOLARITA t ON pf.idSoggetto = t.idSoggetto
                JOIN PARTICELLA p ON t.idParticella = p.idParticella
                WHERE ' . implode(' AND ', $conditions) . '
                ORDER BY p.foglio, p.numero LIMIT 200';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':cognome', $cognome, SQLITE3_TEXT);
        if ($nome)     { $stmt->bindValue(':nome', '%' . $nome . '%', SQLITE3_TEXT); }
        if ($codFisc)  { $stmt->bindValue(':codFisc', $codFisc, SQLITE3_TEXT); }
        if ($dataNasc) { $stmt->bindValue(':dataNascita', $dataNasc, SQLITE3_TEXT); }

        return $this->fetchParcelle($stmt);
    }

    // Cerca in PERSONA_GIURIDICA per denominazione (ente/società) o cognome+nome combinati
    private function queryPersonaGiuridica(\SQLite3 $db, string $termine, string $nome = ''): array
    {
        $conditions = ['upper(trim(pg.denominazione)) LIKE upper(trim(:termine))'];
        if ($nome) { $conditions[] = 'upper(trim(pg.denominazione)) LIKE upper(trim(:nome))'; }

        $sql = 'SELECT p.foglio, p.numero,
                       pg.denominazione AS label,
                       NULL AS data_nasc
                FROM PERSONA_GIURIDICA pg
                JOIN TITOLARITA t ON pg.idSoggetto = t.idSoggetto
                JOIN PARTICELLA p ON t.idParticella = p.idParticella
                WHERE ' . implode(' AND ', $conditions) . '
                ORDER BY p.foglio, p.numero LIMIT 200';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':termine', '%' . $termine . '%', SQLITE3_TEXT);
        if ($nome) { $stmt->bindValue(':nome', '%' . $nome . '%', SQLITE3_TEXT); }

        return $this->fetchParcelle($stmt);
    }

    // Esegue uno statement preparato e ritorna array di particelle deduplicate
    private function fetchParcelle(\SQLite3Stmt $stmt): array
    {
        $res     = $stmt->execute();
        $seen    = [];
        $results = [];
        while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $key = $row['foglio'] . '|' . $row['numero'];
            if (isset($seen[$key])) { continue; }
            $seen[$key] = true;
            $results[] = [
                'foglio'       => (int)$row['foglio'],
                'numero'       => (string)(int)$row['numero'],
                'label'        => trim($row['label'] ?? ''),
                'data_nascita' => $row['data_nasc'] ?? null,
            ];
        }
        return $results;
    }

    // Unisce due array di particelle rimuovendo duplicati foglio+numero
    private function mergeParcelle(array $a, array $b): array
    {
        $seen = [];
        $out  = [];
        foreach (array_merge($a, $b) as $p) {
            $key = $p['foglio'] . '|' . $p['numero'];
            if (!isset($seen[$key])) {
                $seen[$key] = true;
                $out[] = $p;
            }
        }
        return $out;
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
            CURLOPT_TIMEOUT        => 240,
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
        if (is_array($decoded)) return $decoded;

        // Alcuni modelli emettono newline letterali dentro i valori stringa del JSON,
        // che lo rendono invalido. Li sostituiamo con la sequenza di escape \n.
        $fixed = preg_replace_callback('/"(?:[^"\\\\]|\\\\.)*"/s', function ($m) {
            return str_replace(["\r\n", "\r", "\n"], ['\\n', '\\n', '\\n'], $m[0]);
        }, $text);
        $decoded = json_decode($fixed, true);
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
