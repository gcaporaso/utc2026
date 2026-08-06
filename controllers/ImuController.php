<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\ImuAliquota;
use app\models\ImuCoefficiente;
use app\models\ImuAreaEdificabile;
use app\models\ImuF24Pagamento;
use app\models\ImuF24Fornitura;
use app\models\IciFornitura;
use app\models\IciVariazione;
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
        // Zone speciali non sempre presenti nel GeoJSON PRG ma ammesse come aree edificabili
        if (!isset($zones['PEEP'])) {
            $zones['PEEP'] = 'PEEP — Piano di Edilizia Economica e Popolare';
        }
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

        // Aggiungi fabbricati acquisiti nell'anno di calcolo da variazioni ICI
        // che non sono ancora presenti nel catasto (aggiornamento catastale non ancora recepito).
        if ($codFisc && $anno) {
            $acqIci = IciVariazione::find()
                ->where(['codice_fiscale' => $codFisc, 'tipo_variazione' => 'A', 'tipologia_immobile' => 'F'])
                ->andWhere(['or',
                    ['YEAR(data_presentazione)' => $anno],
                    ['and',
                        ['YEAR(data_validita_atto)' => $anno],
                        new \yii\db\Expression('YEAR(data_presentazione) >= :ay2', [':ay2' => $anno]),
                    ],
                ])
                ->andWhere(['>', 'rendita', 0])
                ->all();

            if ($acqIci) {
                $catastoKeys = [];
                foreach ($immobili as $imm) {
                    $catastoKeys[intval($imm['foglio']) . '|' . intval($imm['numero']) . '|' . ltrim((string)($imm['subalterno'] ?? ''), '0')] = true;
                }
                foreach ($acqIci as $v) {
                    $fInt = (int)$v->foglio;
                    $nInt = (int)$v->numero;
                    $sStr = ltrim((string)($v->subalterno ?? ''), '0');
                    $key  = $fInt . '|' . $nInt . '|' . $sStr;
                    if (isset($catastoKeys[$key])) { continue; }
                    $catastoKeys[$key] = true;

                    // Quota ICI: il ratio quotaNum/quotaDen è in millesimi (es. 500 = 50%).
                    // Normalizza a formato catasto: quotaNum/1000 dove risultato ∈ [0,1].
                    $qn    = (int)$v->quota_numeratore;
                    $qd    = (int)$v->quota_denominatore;
                    $ratio = $qd > 0 ? $qn / $qd : 1000;
                    $quotaNum = $ratio > 1 ? (int)round($ratio) : (int)round($ratio * 1000);
                    if ($quotaNum <= 0 || $quotaNum > 1000) { $quotaNum = 1000; }

                    $immobili[] = [
                        'foglio'     => $fInt,
                        'numero'     => (string)$nInt,
                        'subalterno' => $sStr ?: '0',
                        'categoria'  => $v->categoria ?? '',
                        'rendita'    => (float)$v->rendita,
                        'quotaNum'   => $quotaNum,
                        'quotaDen'   => 1000,
                        'indirizzo'  => $v->indirizzo ?? '',
                        '_fromIci'   => true,
                        '_mesi'      => $v->mesiSuggeriti($anno),
                    ];
                }
            }
        }

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

        // Pagamenti F24 SOGEI per il contribuente — usati nel calcolo saldo
        $pagamentiF24 = [];
        if ($codFisc) {
            $righeF24 = ImuF24Pagamento::find()
                ->where(['codice_fiscale' => $codFisc, 'anno_riferimento' => $anno, 'tipo_imposta' => 'I'])
                ->orderBy('data_riscossione')
                ->all();
            foreach ($righeF24 as $pf) {
                $pagamentiF24[] = [
                    'id'              => $pf->id,
                    'codice_tributo'  => $pf->codice_tributo,
                    'data_riscossione'=> $pf->data_riscossione,
                    'importo_debito'  => (float)$pf->importo_debito,
                    'importo_credito' => (float)$pf->importo_credito,
                    'detrazione'      => (float)$pf->detrazione,
                    'acconto'         => (int)$pf->acconto,
                    'saldo'           => (int)$pf->saldo,
                    'ravvedimento'    => (int)$pf->ravvedimento,
                    'desc_tributo'    => ImuF24Pagamento::codiciTributo()[$pf->codice_tributo] ?? $pf->codice_tributo,
                ];
            }
        }

        // Variazioni ICI/IMU per il contribuente nell'anno di calcolo
        $variazioniIci = [];
        if ($codFisc) {
            // Variazioni rilevanti per l'anno di calcolo:
            // - trascritte nell'anno (data_presentazione), oppure
            // - atto dell'anno di calcolo ma trascritto successivamente (es. successioni dichiarate dopo)
            $righeIci = IciVariazione::find()
                ->where(['codice_fiscale' => $codFisc])
                ->andWhere(['or',
                    ['YEAR(data_presentazione)' => $anno],
                    ['and',
                        ['YEAR(data_validita_atto)' => $anno],
                        new \yii\db\Expression('YEAR(data_presentazione) >= :ay', [':ay' => $anno]),
                    ],
                ])
                ->orderBy('data_presentazione')
                ->all();
            foreach ($righeIci as $v) {
                $variazioniIci[] = [
                    'id'                => $v->id,
                    'tipo'              => $v->tipo_variazione,
                    'data_pres'         => $v->data_presentazione,
                    'data_atto'         => $v->data_validita_atto,
                    'codice_diritto'    => $v->codice_diritto,
                    'desc_diritto'      => IciVariazione::descrizioniDiritto()[$v->codice_diritto] ?? $v->codice_diritto,
                    'quota_num'         => $v->quota_numeratore,
                    'quota_den'         => $v->quota_denominatore,
                    'quota_fraz'        => IciVariazione::quotaFrazione((int)$v->quota_numeratore, (int)$v->quota_denominatore),
                    'tipologia'         => $v->tipologia_immobile,
                    'foglio'            => $v->foglio,
                    'numero'            => $v->numero,
                    'subalterno'        => $v->subalterno,
                    'categoria'         => $v->categoria,
                    'rendita'           => (float)$v->rendita,
                    'indirizzo'         => $v->indirizzo,
                    'mesi_suggeriti'    => $v->mesiSuggeriti($anno),
                ];
            }
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
            'pagamentiF24'  => $pagamentiF24,
            'variazioniIci' => $variazioniIci,
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

    // ─────────────────────────────────────────────────────────────────────────
    // Gestione forniture F24 SOGEI
    // ─────────────────────────────────────────────────────────────────────────

    /** Pagina import forniture F24. */
    public function actionF24Import(): string
    {
        $this->layout = 'main';
        $anno = (int)Yii::$app->request->get('anno', date('Y'));
        $forniture = ImuF24Fornitura::find()->orderBy(['importato_il' => SORT_DESC])->limit(50)->all();
        return $this->render('f24-import', compact('anno', 'forniture'));
    }

    /**
     * AJAX POST: riceve file .RUN o .ZIP, lo analizza e salva i record G1 in imu_f24_pagamenti.
     * Supporta upload e path locale (utile da CLI/test).
     */
    public function actionF24Upload(): Response
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $anno = (int)Yii::$app->request->post('anno', date('Y'));

        $file = \yii\web\UploadedFile::getInstanceByName('f24file');
        if (!$file) {
            return $this->asJson(['ok' => false, 'error' => 'Nessun file ricevuto.']);
        }

        $ext      = strtolower($file->extension);
        $tmpPath  = $file->tempName;
        $nomeFile = $file->baseName . '.' . $ext;

        if (!in_array($ext, ['run', 'zip', 'txt'], true)) {
            return $this->asJson(['ok' => false, 'error' => 'Formato file non supportato. Caricare un file .RUN o .ZIP.']);
        }

        try {
            $contenuto = $this->leggiContenutoF24($tmpPath, $ext);
        } catch (\Exception $e) {
            return $this->asJson(['ok' => false, 'error' => $e->getMessage()]);
        }

        [$numTot, $numImu, $dataForn, $errori] = $this->importaRecordG1($contenuto, $nomeFile, $anno);

        return $this->asJson([
            'ok'          => true,
            'num_record'  => $numTot,
            'num_imu'     => $numImu,
            'data_fornitura' => $dataForn,
            'errori'      => $errori,
            'msg'         => "Importati {$numImu} record IMU su {$numTot} versamenti totali.",
        ]);
    }

    /** AJAX: elenco pagamenti F24 per anno (eventualmente filtrato per CF). */
    public function actionF24Lista(): Response
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $anno = (int)Yii::$app->request->get('anno', 0);
        $cf   = strtoupper(trim(Yii::$app->request->get('cf', '')));

        $q = ImuF24Pagamento::find()
            ->where(['tipo_imposta' => 'I'])
            ->orderBy('anno_riferimento DESC, data_riscossione, codice_fiscale');

        // Filtra per anno solo se specificato E nessun CF (ricerca per CF mostra tutti gli anni)
        if ($anno > 0 && !$cf) {
            $q->andWhere(['anno_riferimento' => $anno]);
        }
        if ($cf) {
            $q->andWhere(['codice_fiscale' => $cf]);
        }
        $rows = $q->limit(500)->all();

        $out = [];
        foreach ($rows as $r) {
            $out[] = [
                'id'               => $r->id,
                'anno_riferimento' => (int)$r->anno_riferimento,
                'codice_fiscale'   => $r->codice_fiscale,
                'denominazione'    => trim($r->denominazione . ' ' . $r->nome_contribuente),
                'codice_tributo'   => $r->codice_tributo,
                'desc_tributo'     => ImuF24Pagamento::codiciTributo()[$r->codice_tributo] ?? $r->codice_tributo,
                'data_riscossione' => $r->data_riscossione,
                'importo_debito'   => (float)$r->importo_debito,
                'importo_credito'  => (float)$r->importo_credito,
                'acconto'          => (int)$r->acconto,
                'saldo'            => (int)$r->saldo,
                'ravvedimento'     => (int)$r->ravvedimento,
            ];
        }
        return $this->asJson(['ok' => true, 'rows' => $out, 'count' => count($out)]);
    }

    /** AJAX: elimina una fornitura e tutti i suoi pagamenti. */
    public function actionF24Delete(): Response
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $id = (int)Yii::$app->request->post('id', 0);
        $forn = ImuF24Fornitura::findOne($id);
        if (!$forn) {
            return $this->asJson(['ok' => false, 'error' => 'Fornitura non trovata.']);
        }
        ImuF24Pagamento::deleteAll(['file_origine' => $forn->nome_file]);
        $forn->delete();
        return $this->asJson(['ok' => true]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Helper privati F24
    // ─────────────────────────────────────────────────────────────────────────

    /** Legge il contenuto grezzo del file, estraendo dallo ZIP se necessario.
     *  Supporta ZIP con più file .RUN (concatena tutto il contenuto). */
    private function leggiContenutoF24(string $tmpPath, string $ext): string
    {
        if ($ext === 'zip') {
            $zip = new \ZipArchive();
            if ($zip->open($tmpPath) !== true) {
                throw new \Exception('Impossibile aprire il file ZIP.');
            }
            $contenuto = '';
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $name = $zip->getNameIndex($i);
                if (preg_match('/\.(run|txt)$/i', $name)) {
                    $blocco = $zip->getFromIndex($i);
                    // Assicura che ogni blocco termini con newline prima di concatenare
                    $contenuto .= rtrim($blocco) . "\n";
                }
            }
            $zip->close();
            if ($contenuto === '') {
                throw new \Exception('Nessun file .RUN trovato nello ZIP.');
            }
            return $contenuto;
        }
        return file_get_contents($tmpPath);
    }

    /**
     * Analizza il contenuto testuale della fornitura SOGEI e salva i record G1 IMU.
     * Restituisce [numTotVersamenti, numImuSalvati, dataFornitura, errori[]].
     */
    private function importaRecordG1(string $contenuto, string $nomeFile, int $anno): array
    {
        // Normalizza fine riga
        $contenuto = str_replace(["\r\n", "\r"], "\n", $contenuto);
        $righe     = explode("\n", $contenuto);

        $numTot     = 0;
        $numImu     = 0;
        $dataForn   = null;
        $errori     = [];
        $savedCf    = [];

        // Elimina eventuali pagamenti già importati da questo file
        ImuF24Pagamento::deleteAll(['file_origine' => $nomeFile]);

        $db = Yii::$app->db;

        foreach ($righe as $idx => $riga) {
            // Ogni record è esattamente 300 caratteri (più eventuale newline già rimossa)
            if (strlen($riga) < 300) {
                continue;
            }

            $tipoRecord = substr($riga, 0, 2);
            if ($tipoRecord !== 'G1') {
                continue;
            }

            $numTot++;

            // Estrae i campi dal record G1 (posizioni 1-based → substr offset 0-based)
            $rawDataForn   = substr($riga,  2,  8); // pos  3-10
            $progDelega    = substr($riga, 30,  6); // pos 31-36
            $progRiga      = (int)substr($riga, 36,  2); // pos 37-38
            $cf            = trim(substr($riga, 49, 16)); // pos 50-65
            $dataRisc      = substr($riga, 66,  8); // pos 67-74
            $codEnteComune = trim(substr($riga, 74,  4)); // pos 75-78
            $codTributo    = trim(substr($riga, 78,  4)); // pos 79-82
            $rateazione    = trim(substr($riga, 83,  4)); // pos 84-87
            $annoRif       = (int)substr($riga, 87,  4); // pos 88-91
            $importoDeb    = (int)substr($riga, 95, 15); // pos 96-110 (centesimi)
            $importoCred   = (int)substr($riga, 110, 15); // pos 111-125 (centesimi)
            $ravv          = (int)substr($riga, 125, 1);  // pos 126
            $immVar        = (int)substr($riga, 126, 1);  // pos 127
            $flagAcconto   = (int)substr($riga, 127, 1);  // pos 128
            $flagSaldo     = (int)substr($riga, 128, 1);  // pos 129
            $numFabb       = (int)substr($riga, 129, 3);  // pos 130-132
            $detrazioneRaw = (int)substr($riga, 133, 15); // pos 134-148
            $denominazione = trim(substr($riga, 148, 39)); // pos 149-187
            $cfOrig        = trim(substr($riga, 187, 16)); // pos 188-203
            $nomeContr     = trim(substr($riga, 203, 20)); // pos 204-223
            $sesso         = trim(substr($riga, 223, 1));  // pos 224
            $dataNasc      = substr($riga, 224,  8);       // pos 225-232
            $comuneNasc    = trim(substr($riga, 232, 25)); // pos 233-257
            $provNasc      = trim(substr($riga, 257, 2));  // pos 258-259
            $tipoImposta   = trim(substr($riga, 259, 1));  // pos 260
            $dataRip       = substr($riga, 12, 8);         // pos 13-20

            // Filtra solo IMU (tipo imposta 'I')
            if ($tipoImposta !== 'I') {
                continue;
            }

            // Valida CF
            if (!preg_match('/^[A-Z0-9]{16}$/i', $cf)) {
                $errori[] = "Riga " . ($idx + 1) . ": CF non valido '$cf'";
                continue;
            }

            // Converti date yyyymmdd → yyyy-mm-dd (NULL se non valida)
            $toDate = static function (string $d): ?string {
                if (!preg_match('/^\d{8}$/', $d) || $d === '00000000') {
                    return null;
                }
                return substr($d, 0, 4) . '-' . substr($d, 4, 2) . '-' . substr($d, 6, 2);
            };

            if (!$dataForn) {
                $dataForn = $toDate($rawDataForn);
            }

            // Anno di riferimento: se 0 o non valido, usa $anno (anno di lavoro)
            if ($annoRif < 2010 || $annoRif > 2100) {
                $annoRif = $anno;
            }

            $pag = new ImuF24Pagamento();
            $pag->anno_riferimento   = $annoRif;
            $pag->codice_fiscale     = strtoupper($cf);
            $pag->codice_fiscale_orig = $cfOrig ?: null;
            $pag->codice_tributo     = $codTributo;
            $pag->tipo_imposta       = 'I';
            $pag->data_riscossione   = $toDate($dataRisc);
            $pag->data_fornitura     = $toDate($rawDataForn);
            $pag->data_ripartizione  = $toDate($dataRip);
            $pag->importo_debito     = round($importoDeb / 100, 2);
            $pag->importo_credito    = round($importoCred / 100, 2);
            $pag->detrazione         = round($detrazioneRaw / 100, 2);
            $pag->acconto            = $flagAcconto;
            $pag->saldo              = $flagSaldo;
            $pag->ravvedimento       = $ravv;
            $pag->immobili_variati   = $immVar;
            $pag->num_fabbricati     = $numFabb;
            $pag->progressivo_delega = $progDelega ?: null;
            $pag->progressivo_riga   = $progRiga;
            $pag->codice_ente_comunale = $codEnteComune ?: null;
            $pag->denominazione      = $denominazione ?: null;
            $pag->nome_contribuente  = $nomeContr ?: null;
            $pag->sesso              = $sesso ?: null;
            $pag->data_nascita       = $toDate($dataNasc);
            $pag->comune_nascita     = $comuneNasc ?: null;
            $pag->provincia_nascita  = $provNasc ?: null;
            $pag->file_origine       = $nomeFile;

            try {
                if ($pag->save()) {
                    $numImu++;
                    $savedCf[$annoRif] = true;
                } else {
                    $errori[] = "Riga " . ($idx + 1) . " (CF $cf): " . implode(', ', $pag->getFirstErrors());
                }
            } catch (\yii\db\IntegrityException $e) {
                // Chiave univoca violata: record già presente (fornitura cumulativa) — ignorato
            }
        }

        // Salva log fornitura
        $forn = ImuF24Fornitura::findOne(['nome_file' => $nomeFile]) ?? new ImuF24Fornitura();
        $forn->nome_file        = $nomeFile;
        $forn->data_fornitura   = $dataForn;
        $forn->anno_riferimento = $anno;
        $forn->num_record       = $numTot;
        $forn->num_imu          = $numImu;
        $forn->save();

        return [$numTot, $numImu, $dataForn, $errori];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Forniture ICI/IMU — variazioni catastali mensili portale ANCI
    // ─────────────────────────────────────────────────────────────────────────

    public function actionIciImport(): string
    {
        $this->layout = 'main';
        $anno      = (int)Yii::$app->request->get('anno', date('Y'));
        $forniture = IciFornitura::find()->orderBy('anno_mese DESC, importato_il DESC')->all();
        return $this->render('ici-import', compact('anno', 'forniture'));
    }

    /** AJAX POST: importa un file ZIP o XML dal portale ANCI. */
    public function actionIciUpload(): Response
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $file = \yii\web\UploadedFile::getInstanceByName('icifile');
        if (!$file) {
            return $this->asJson(['ok' => false, 'error' => 'Nessun file ricevuto.']);
        }

        $ext = strtolower($file->extension);
        if (!in_array($ext, ['zip', 'xml'])) {
            return $this->asJson(['ok' => false, 'error' => 'Formato non supportato. Usare .zip o .xml.']);
        }

        $tmpPath = $file->tempName;
        $xmlContent = '';

        if ($ext === 'zip') {
            $zip = new \ZipArchive();
            if ($zip->open($tmpPath) !== true) {
                return $this->asJson(['ok' => false, 'error' => 'Impossibile aprire il file ZIP.']);
            }
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $name = $zip->getNameIndex($i);
                if (preg_match('/\.(xml)$/i', $name)) {
                    $xmlContent = $zip->getFromIndex($i);
                    break;
                }
            }
            $zip->close();
            if (!$xmlContent) {
                return $this->asJson(['ok' => false, 'error' => 'Nessun file XML trovato nello ZIP.']);
            }
        } else {
            $xmlContent = file_get_contents($tmpPath);
        }

        $nomeFile = $file->name;
        $result   = $this->importaIciXml($xmlContent, $nomeFile);

        if (isset($result['error'])) {
            return $this->asJson(['ok' => false, 'error' => $result['error']]);
        }

        return $this->asJson([
            'ok'             => true,
            'msg'            => "Importati {$result['num_soggetti']} soggetti ({$result['num_variazioni']} variazioni) da {$result['anno_mese']}.",
            'anno_mese'      => $result['anno_mese'],
            'num_variazioni' => $result['num_variazioni'],
        ]);
    }

    /** AJAX POST: importa tutti i file ZIP storici da runtime/dati-ici/. */
    public function actionIciImportStorici(): Response
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $dir = \Yii::getAlias('@runtime') . '/dati-ici/';
        if (!is_dir($dir)) {
            return $this->asJson(['ok' => false, 'error' => 'Cartella storici non trovata: ' . $dir]);
        }

        $zips = glob($dir . 'ICI_*.zip');
        if (!$zips) {
            return $this->asJson(['ok' => false, 'error' => 'Nessun file ZIP trovato in ' . $dir]);
        }

        sort($zips);
        $totVariazioni = 0;
        $totSoggetti   = 0;
        $elaborati     = 0;
        $errori        = [];

        foreach ($zips as $zipPath) {
            $nomeFile = basename($zipPath);
            // Salta se già importato
            if (IciFornitura::find()->where(['nome_file' => $nomeFile])->exists()) {
                continue;
            }

            $zip = new \ZipArchive();
            if ($zip->open($zipPath) !== true) {
                $errori[] = "$nomeFile: impossibile aprire lo ZIP";
                continue;
            }
            $xmlContent = '';
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $name = $zip->getNameIndex($i);
                if (preg_match('/\.xml$/i', $name)) {
                    $xmlContent = $zip->getFromIndex($i);
                    break;
                }
            }
            $zip->close();

            if (!$xmlContent) {
                $errori[] = "$nomeFile: nessun XML trovato";
                continue;
            }

            $result = $this->importaIciXml($xmlContent, $nomeFile);
            if (isset($result['error'])) {
                $errori[] = "$nomeFile: " . $result['error'];
                continue;
            }

            $totVariazioni += $result['num_variazioni'];
            $totSoggetti   += $result['num_soggetti'];
            $elaborati++;
        }

        return $this->asJson([
            'ok'      => true,
            'msg'     => "Elaborati $elaborati file ZIP. Variazioni: $totVariazioni, Soggetti: $totSoggetti.",
            'errori'  => $errori,
        ]);
    }

    /** AJAX POST: elimina una fornitura e tutte le sue variazioni. */
    public function actionIciDelete(): Response
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $id   = (int)Yii::$app->request->post('id', 0);
        $forn = IciFornitura::findOne($id);
        if (!$forn) { return $this->asJson(['ok' => false, 'error' => 'Fornitura non trovata.']); }

        Yii::$app->db->createCommand()
            ->delete('ici_variazioni', ['fornitura_id' => $id])
            ->execute();
        $forn->delete();

        return $this->asJson(['ok' => true]);
    }

    /** AJAX GET: ricerca variazioni per CF e/o anno. */
    public function actionIciLista(): Response
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $cf   = strtoupper(trim(Yii::$app->request->get('cf',   '')));
        $anno = (int)Yii::$app->request->get('anno', 0);

        $q = IciVariazione::find()->orderBy('data_presentazione DESC, codice_fiscale');
        if ($cf) {
            $q->andWhere(['codice_fiscale' => $cf]);
        }
        if ($anno > 0 && !$cf) {
            $q->andWhere(['YEAR(data_presentazione)' => $anno]);
        } elseif ($anno > 0 && $cf) {
            $q->andWhere(['YEAR(data_presentazione)' => $anno]);
        }

        $rows = [];
        foreach ($q->limit(500)->all() as $v) {
            $rows[] = [
                'id'          => $v->id,
                'anno_mese'   => $v->anno_mese,
                'data_pres'   => $v->data_presentazione,
                'tipo'        => $v->tipo_variazione,
                'cf'          => $v->codice_fiscale,
                'nominativo'  => trim($v->cognome . ' ' . $v->nome),
                'tipologia'   => $v->tipologia_immobile,
                'foglio'      => $v->foglio,
                'numero'      => $v->numero,
                'subalterno'  => $v->subalterno,
                'categoria'   => $v->categoria,
                'rendita'     => (float)$v->rendita,
                'diritto'     => IciVariazione::descrizioniDiritto()[$v->codice_diritto] ?? $v->codice_diritto,
                'quota'       => IciVariazione::quotaFrazione((int)$v->quota_numeratore, (int)$v->quota_denominatore),
                'mesi_sug'    => $v->mesiSuggeriti($anno > 0 ? $anno : (int)substr((string)$v->anno_mese, 0, 4)),
            ];
        }

        return $this->asJson(['ok' => true, 'rows' => $rows, 'count' => count($rows)]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Metodi privati — parsing XML forniture ICI
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Parsa l'XML di una fornitura ICI e importa le variazioni nel DB.
     * @return array ['anno_mese','num_variazioni','num_soggetti'] oppure ['error' => '...']
     */
    private function importaIciXml(string $xmlContent, string $nomeFile): array
    {
        // Rimuove la dichiarazione di namespace default per semplificare SimpleXML
        $xmlContent = preg_replace('/\sxmlns(?::\w+)?="[^"]*"/', '', $xmlContent);
        // Fix encoding dichiarato in ISO-8859-1 ma letto come UTF-8
        if (str_contains($xmlContent, 'encoding="ISO-8859-1"')) {
            $xmlContent = mb_convert_encoding($xmlContent, 'UTF-8', 'ISO-8859-1');
            $xmlContent = str_replace('encoding="ISO-8859-1"', 'encoding="UTF-8"', $xmlContent);
        }

        $xml = @simplexml_load_string($xmlContent);
        if (!$xml) {
            return ['error' => 'XML non valido o non leggibile.'];
        }

        // Dati generali del periodo
        $dg        = $xml->DatiPresenti->DatiGenerali ?? null;
        $dataIniz  = $dg ? $this->parseDataIci((string)$dg->DataIniziale) : null;
        $dataFine  = $dg ? $this->parseDataIci((string)$dg->DataFinale)   : null;
        $annoMese  = $dataIniz ? substr($dataIniz, 0, 7) : date('Y-m');

        // Controlla se già importato
        if (IciFornitura::find()->where(['nome_file' => $nomeFile])->exists()) {
            return ['error' => "File già importato: $nomeFile"];
        }

        $variazioni   = $xml->DatiPresenti->Variazioni->Variazione ?? [];
        $numVariazioni = 0;
        $numSoggetti   = 0;
        $fornId        = 0;

        // Crea prima la fornitura
        $forn = new IciFornitura();
        $forn->nome_file      = $nomeFile;
        $forn->anno_mese      = $annoMese;
        $forn->data_inizio    = $dataIniz;
        $forn->data_fine      = $dataFine;
        $forn->num_variazioni = 0;
        $forn->num_soggetti   = 0;
        if (!$forn->save()) {
            return ['error' => 'Impossibile salvare la fornitura: ' . implode('; ', $forn->getFirstErrors())];
        }
        $fornId = $forn->id;

        foreach ($variazioni as $var) {
            $nota       = $var->Trascrizione->Nota ?? null;
            if (!$nota) continue;

            $numNota    = (int)(string)($nota->NumeroNota ?? 0);
            $annoNota   = (int)(string)($nota->Anno ?? 0);
            $esitoNota  = (int)(string)($nota->EsitoNota ?? 2);
            $dataPres   = $this->parseDataIci((string)($nota->DataPresentazioneAtto ?? ''));
            $dataVal    = $this->parseDataIci((string)($nota->DataValiditaAtto ?? ''));
            $codiceAtto = (string)($nota->CodiceAtto ?? '');

            // Salta note non registrate
            if ($esitoNota == 2) continue;

            // Mappa immobili per Ref_Immobile
            $immMap = $this->buildImmobiliMap($var->Immobili ?? null);

            foreach ($var->Soggetti->Soggetto ?? [] as $sogg) {
                $cf = $cognome = $nome = '';
                if ($sogg->PersonaFisica) {
                    $pf      = $sogg->PersonaFisica;
                    $cf      = strtoupper(trim((string)($pf->CodiceFiscale ?? '')));
                    $cognome = trim((string)($pf->Cognome ?? ''));
                    $nome    = trim((string)($pf->Nome ?? ''));
                } elseif ($sogg->PersonaGiuridica) {
                    $pg      = $sogg->PersonaGiuridica;
                    $cf      = strtoupper(trim((string)($pg->CodiceFiscale ?? '')));
                    $cognome = trim((string)($pg->Denominazione ?? ''));
                }
                if (!$cf) continue;

                foreach ($sogg->DatiTitolarita->Titolarita ?? [] as $tit) {
                    $refImm = (string)($tit->attributes()['Ref_Immobile'] ?? '');
                    $imm    = $immMap[$refImm] ?? null;
                    if (!$imm) continue;

                    $tipoVar = null;
                    $codDir  = $quotaN = $quotaD = null;

                    if ($tit->Acquisizione) {
                        $tipoVar = 'A';
                        $acq     = $tit->Acquisizione;
                        $codDir  = (string)($acq->CodiceDiritto    ?? '');
                        $quotaN  = (string)($acq->QuotaNumeratore  ?? '');
                        $quotaD  = (string)($acq->QuotaDenominatore ?? '');
                    } elseif ($tit->Cessione) {
                        $tipoVar = 'C';
                        $ces     = $tit->Cessione;
                        $codDir  = (string)($ces->CodiceDiritto    ?? '');
                        $quotaN  = (string)($ces->QuotaNumeratore  ?? '');
                        $quotaD  = (string)($ces->QuotaDenominatore ?? '');
                    }
                    if (!$tipoVar) continue;

                    $v = new IciVariazione();
                    $v->fornitura_id          = $fornId;
                    $v->anno_mese             = $annoMese;
                    $v->numero_nota           = $numNota ?: null;
                    $v->anno_nota             = $annoNota ?: null;
                    $v->data_presentazione    = $dataPres;
                    $v->data_validita_atto    = $dataVal;
                    $v->esito_nota            = $esitoNota;
                    $v->codice_fiscale        = $cf;
                    $v->cognome               = $cognome;
                    $v->nome                  = $nome;
                    $v->tipo_variazione       = $tipoVar;
                    $v->codice_diritto        = $codDir;
                    $v->quota_numeratore      = $quotaN !== '' ? (int)$quotaN : null;
                    $v->quota_denominatore    = $quotaD !== '' ? (int)$quotaD : null;
                    $v->tipologia_immobile    = $imm['tipologia'];
                    $v->foglio                = $imm['foglio'];
                    $v->numero                = $imm['numero'];
                    $v->subalterno            = $imm['subalterno'] ?? '';
                    $v->id_catastale_immobile = $imm['id_catastale'] ? (int)$imm['id_catastale'] : null;
                    $v->categoria             = $imm['categoria'] ?? null;
                    $v->classe                = $imm['classe'] ?? null;
                    $v->superficie            = isset($imm['superficie']) ? (int)$imm['superficie'] : null;
                    $v->rendita               = isset($imm['rendita_c']) ? round($imm['rendita_c'] / 100, 2) : null;
                    $v->indirizzo             = $imm['indirizzo'] ?? null;
                    $v->dominicale            = isset($imm['dominicale_c']) ? round($imm['dominicale_c'] / 100, 2) : null;
                    $v->agrario               = isset($imm['agrario_c'])    ? round($imm['agrario_c']    / 100, 2) : null;

                    try {
                        if ($v->save()) {
                            $numSoggetti++;
                        }
                    } catch (\yii\db\IntegrityException $e) {
                        // Duplicato (stessa variazione già importata) — ignora
                    }
                }
                $numVariazioni++;
            }
        }

        // Aggiorna contatori
        $forn->num_variazioni = $numVariazioni;
        $forn->num_soggetti   = $numSoggetti;
        $forn->save();

        return [
            'anno_mese'      => $annoMese,
            'num_variazioni' => $numVariazioni,
            'num_soggetti'   => $numSoggetti,
        ];
    }

    /** Costruisce la mappa Ref_Immobile → dati catastali dall'elemento Immobili. */
    private function buildImmobiliMap(?\SimpleXMLElement $immobili): array
    {
        if (!$immobili) return [];
        $map = [];

        foreach ($immobili->children() as $immEl) {
            $ref      = (string)($immEl->attributes()['Ref_Immobile'] ?? '');
            $tipologia = (string)($immEl->TipologiaImmobile ?? '');
            $idCat    = (string)($immEl->IdCatastaleImmobile ?? '');

            $data = ['tipologia' => $tipologia, 'id_catastale' => $idCat];

            if ($tipologia === 'F') {
                $idDef = $immEl->Identificativi->IdentificativoDefinitivo ?? $immEl->Identificativo ?? null;
                $data['foglio']     = ltrim((string)($idDef->Foglio     ?? ''), '0') ?: '0';
                $data['numero']     = ltrim((string)($idDef->Numero     ?? ''), '0') ?: '0';
                $data['subalterno'] = ltrim((string)($idDef->Subalterno ?? ''), '0');
                $cl = $immEl->Classamento ?? null;
                if ($cl) {
                    // Categoria: 'A02' → normalizza a 'A/2' per coerenza col catasto
                    $catRaw = strtoupper(trim((string)($cl->Categoria ?? ($cl->Natura ?? ''))));
                    $catNorm = preg_replace('/^([A-Z]+)0*(\d+)$/', '$1/$2', $catRaw);
                    $data['categoria']   = $catNorm ?: $catRaw;
                    $data['classe']      = (string)($cl->Classe     ?? '');
                    $data['superficie']  = (int)(string)($cl->Superficie ?? 0);
                    $rendC               = trim((string)($cl->RenditaEuro ?? ''));
                    $data['rendita_c']   = $rendC !== '' ? (int)$rendC : null; // centesimi
                }
                $ub = $immEl->UbicazioneCatasto ?? null;
                if ($ub) {
                    $data['indirizzo'] = trim((string)($ub->Indirizzo ?? '') . ' ' . (string)($ub->Civico1 ?? ''));
                }
            } elseif ($tipologia === 'T') {
                $idDef = $immEl->Identificativo ?? null;
                $data['foglio']     = ltrim((string)($idDef->Foglio  ?? ''), '0') ?: '0';
                $data['numero']     = ltrim((string)($idDef->Numero  ?? ''), '0') ?: '0';
                $data['subalterno'] = '';
                $cl = $immEl->Classamento ?? null;
                if ($cl) {
                    $domC = trim((string)($cl->DominicaleEuro ?? ''));
                    $agrC = trim((string)($cl->AgrarioEuro   ?? ''));
                    $data['dominicale_c'] = $domC !== '' ? (int)$domC : null;
                    $data['agrario_c']    = $agrC !== '' ? (int)$agrC : null;
                }
            }

            if ($ref) $map[$ref] = $data;
        }
        return $map;
    }

    /**
     * Converte data dal formato ddmmyyyy (usato nelle forniture ICI) o yyyy-mm-dd in yyyy-mm-dd.
     * Restituisce null se vuota o non valida.
     */
    private function parseDataIci(string $s): ?string
    {
        $s = trim($s);
        if (!$s) return null;
        // ISO: yyyy-mm-dd
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $s)) { return $s; }
        // ddmmyyyy (formato usato nelle forniture ICI)
        if (preg_match('/^(\d{2})(\d{2})(\d{4})$/', $s, $m)) {
            return $m[3] . '-' . $m[2] . '-' . $m[1];
        }
        return null;
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
