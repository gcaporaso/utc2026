<?php
/** @var yii\web\View $this */
/** @var int $anno */
use yii\helpers\Url;

$this->title = 'Calcolo IMU ' . $anno;

$ricercaUrl   = Url::to(['/imu/ricerca']);
$generaPdfUrl = Url::to(['/imu/genera-pdf']);
$generaF24Url = Url::to(['/imu/genera-f24']);
$csrfToken    = Yii::$app->request->csrfToken;

// Variabili PHP → JS (nel <head> così disponibili prima del DOMContentLoaded)
$this->registerJs(
    'var RICERCA_URL    = ' . json_encode($ricercaUrl)   . ';' .
    'var GENERA_PDF_URL = ' . json_encode($generaPdfUrl) . ';' .
    'var GENERA_F24_URL = ' . json_encode($generaF24Url) . ';' .
    'var CSRF_TOKEN     = ' . json_encode($csrfToken)    . ';',
    \yii\web\View::POS_HEAD
);
?>

<div class="content-wrapper" style="margin-left:10px!important">
  <section class="content-header">
    <h1>Calcolo IMU <small>Imposta Municipale Propria</small></h1>
  </section>

  <section class="content">

    <!-- ══ Ricerca contribuente ══════════════════════════════════════════════ -->
    <div class="card card-primary card-outline">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-search"></i> Ricerca contribuente</h3>
        <div class="card-tools">
          <label class="mb-0 mr-1 text-light" style="font-size:12px">Anno:</label>
          <select id="sel-anno" class="form-control form-control-sm" style="width:75px;display:inline-block">
            <?php for ($a = (int)date('Y'); $a >= 2020; $a--): ?>
              <option value="<?= $a ?>" <?= $a == $anno ? 'selected' : '' ?>><?= $a ?></option>
            <?php endfor; ?>
          </select>
        </div>
      </div>
      <div class="card-body pb-2">
        <div class="form-row align-items-end">
          <div class="form-group col-md-3 mb-1">
            <label class="mb-0">Cognome</label>
            <input type="text" id="in-cognome" class="form-control form-control-sm"
                   placeholder="es. ROSSI" style="text-transform:uppercase">
          </div>
          <div class="form-group col-md-3 mb-1">
            <label class="mb-0">Nome</label>
            <input type="text" id="in-nome" class="form-control form-control-sm"
                   placeholder="es. MARIO" style="text-transform:uppercase">
          </div>
          <div class="form-group col-md-2 mb-1">
            <label class="mb-0">Codice Fiscale</label>
            <input type="text" id="in-cf" class="form-control form-control-sm"
                   placeholder="16 car." maxlength="16" style="text-transform:uppercase">
          </div>
          <div class="form-group col-md-2 mb-1">
            <label class="mb-0">Data nascita</label>
            <input type="text" id="in-nasc" class="form-control form-control-sm" placeholder="GG/MM/AAAA">
          </div>
          <div class="form-group col-md-2 mb-1">
            <button id="btn-cerca" class="btn btn-primary btn-sm btn-block">
              <i class="fas fa-search"></i> Cerca
            </button>
          </div>
        </div>
        <div id="alert-ricerca" class="alert d-none mt-1 mb-0 py-1 px-2" style="font-size:13px"></div>
      </div>
    </div>

    <!-- ══ Risultati (nascosto fino alla ricerca) ════════════════════════════ -->
    <div id="sezione-risultati" class="d-none">

      <!-- Dati persona -->
      <div class="card card-outline card-info mb-2">
        <div class="card-body py-2">
          <div class="row">
            <div class="col-md-4">
              <i class="fas fa-user text-info mr-1"></i>
              <strong id="res-intestatario"></strong>
              <span class="text-muted ml-1" id="res-cf" style="font-size:12px"></span>
            </div>
            <div class="col-md-3">
              <small class="text-muted">Nato/a il </small>
              <strong id="res-nasc"></strong>
              <span class="text-muted" id="res-sesso"></span>
            </div>
            <div class="col-md-5">
              <small class="text-muted">Comune nascita: </small>
              <strong id="res-luogo"></strong>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabella immobili -->
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-building"></i> Immobili censiti in catasto</h3>
          <div class="card-tools">
            <small class="text-light">Imposta tipo utilizzo, mesi e condizioni per ogni unità</small>
          </div>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-sm table-hover table-striped mb-0" id="tbl-immobili"
                   style="font-size:12px;min-width:1320px">
              <thead class="thead-dark">
                <tr>
                  <th>Fg.</th>
                  <th>Part.</th>
                  <th>Sub</th>
                  <th>Cat.</th>
                  <th style="min-width:120px">Rendita €<br><small class="font-weight-normal">/ Zona PRG (aree)</small></th>
                  <th class="text-right" style="min-width:80px">Consist.<br>(mq)</th>
                  <th class="text-center">Quota<br>possesso</th>
                  <th style="min-width:280px">Tipo utilizzo</th>
                  <th class="text-center" style="min-width:68px">Mesi<br>poss.</th>
                  <th class="text-center" style="min-width:100px">Inag./Storico</th>
                  <th class="text-right">Coeff.</th>
                  <th class="text-right">Base<br>imponibile €</th>
                  <th class="text-right">Aliq.‰</th>
                  <th class="text-right">IMU<br>proporzionale €</th>
                </tr>
              </thead>
              <tbody id="tbody-immobili"></tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Periodo + Riepilogo -->
      <div class="row">
        <div class="col-md-4">
          <div class="card card-outline card-secondary">
            <div class="card-header py-2"><h3 class="card-title">Periodo versamento</h3></div>
            <div class="card-body py-2">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="periodo" id="p-acconto" value="acconto" checked>
                <label class="form-check-label" for="p-acconto">
                  <strong>Acconto (giugno)</strong> — 50%
                </label>
              </div>
              <div class="form-check mt-1">
                <input class="form-check-input" type="radio" name="periodo" id="p-saldo" value="saldo">
                <label class="form-check-label" for="p-saldo">
                  <strong>Saldo (dicembre)</strong> — 50%
                </label>
              </div>
              <div class="form-check mt-1">
                <input class="form-check-input" type="radio" name="periodo" id="p-annuale" value="annuale">
                <label class="form-check-label" for="p-annuale">
                  <strong>Annuale</strong> — 100%
                </label>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-8">
          <div class="card card-outline card-success">
            <div class="card-header py-2">
              <h3 class="card-title"><i class="fas fa-calculator"></i> Riepilogo</h3>
            </div>
            <div class="card-body py-2">
              <table class="table table-sm table-borderless mb-0">
                <tbody>
                  <tr>
                    <td>Fabbricati esenti (ab. princ., pertinenze, rurali, terreni):</td>
                    <td class="text-right text-muted">ESENTE</td>
                  </tr>
                  <tr>
                    <td>IMU proporzionale annuale (quota × mesi / 12):</td>
                    <td class="text-right font-weight-bold" id="tot-annuale">€ 0,00</td>
                  </tr>
                  <tr class="border-top">
                    <td id="lbl-dovuta"><strong>IMU da versare (acconto 50%):</strong></td>
                    <td class="text-right" id="tot-dovuta"
                        style="font-size:1.4em;color:#c0392b;font-weight:bold">€ 0,00</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Bottoni -->
      <div class="row mt-1 mb-3">
        <div class="col-12 text-right">
          <button id="btn-pdf" class="btn btn-info mr-2">
            <i class="fas fa-file-pdf"></i> Stampa Calcolo
          </button>
          <button id="btn-f24" class="btn btn-danger">
            <i class="fas fa-file-invoice"></i> Genera Modello F24
          </button>
        </div>
      </div>
      <div id="alert-docs" class="alert d-none mb-3"></div>

      <!-- ── Calcolo Tardivo ── -->
      <div class="card card-outline card-warning mt-2">
        <div class="card-header py-2">
          <h3 class="card-title">
            <i class="fas fa-clock text-warning"></i> Calcolo Tardivo — Interessi e Sanzioni
          </h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body py-2">
          <div class="row align-items-end mb-2">
            <div class="col-auto">
              <label class="mb-0" style="font-size:13px"><strong>Data di pagamento</strong></label>
              <input type="date" id="inp-data-pagamento" class="form-control form-control-sm mt-1"
                     style="width:160px">
            </div>
            <div class="col-auto">
              <button class="btn btn-sm btn-warning" id="btn-calcola-tardivo">
                <i class="fas fa-calculator"></i> Calcola
              </button>
            </div>
            <div class="col text-muted pt-3" style="font-size:12px" id="lbl-tasso-legale"></div>
          </div>

          <div id="tbl-tardivo-wrap" class="d-none">
            <table class="table table-sm table-bordered mb-1" style="font-size:13px">
              <thead class="thead-light">
                <tr>
                  <th>Rata</th>
                  <th>Scadenza</th>
                  <th class="text-center">Giorni ritardo</th>
                  <th class="text-right">IMU dovuta</th>
                  <th class="text-right">Interessi</th>
                  <th class="text-right">Sanzione</th>
                  <th class="text-right"><strong>Totale</strong></th>
                </tr>
              </thead>
              <tbody id="tbody-tardivo"></tbody>
              <tfoot id="tfoot-tardivo"></tfoot>
            </table>
            <p id="note-tardivo" class="text-muted mb-1" style="font-size:11px"></p>
            <div class="text-right mt-1" id="btnbar-tardivo">
              <button id="btn-pdf-tardivo" class="btn btn-sm btn-info mr-2">
                <i class="fas fa-file-pdf"></i> Stampa Calcolo Tardivo
              </button>
              <button id="btn-f24-tardivo" class="btn btn-sm btn-danger">
                <i class="fas fa-file-invoice"></i> Genera F24 Tardivo
              </button>
            </div>
            <div id="alert-docs-tardivo" class="alert d-none mt-2 mb-0"></div>
          </div>
        </div>
      </div>

      <!-- ── Pagamenti F24 SOGEI ── -->
      <div class="card card-outline card-primary mt-2" id="pannello-f24" style="display:none!important">
        <div class="card-header py-2">
          <h3 class="card-title">
            <i class="fas fa-receipt text-primary"></i> Pagamenti F24 rilevati (SOGEI)
          </h3>
          <div class="card-tools">
            <a href="<?= \yii\helpers\Url::to(['/imu/f24-import']) ?>" class="btn btn-tool btn-sm text-muted"
               title="Gestisci forniture F24" target="_blank">
              <i class="fas fa-cog"></i>
            </a>
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body py-2">
          <p class="text-muted small mb-2" id="f24-nessun-dato" style="display:none">
            <i class="fas fa-info-circle"></i>
            Nessun pagamento F24 trovato per questo contribuente per l'anno selezionato.
            <a href="<?= \yii\helpers\Url::to(['/imu/f24-import']) ?>" target="_blank">Importa fornitura</a>.
          </p>
          <div id="f24-dati-wrap" style="display:none">
            <!-- Dettaglio righe F24 -->
            <table class="table table-sm table-bordered mb-3" style="font-size:12px">
              <thead class="thead-light">
                <tr>
                  <th>Cod. Tributo</th>
                  <th>Descrizione</th>
                  <th class="text-center">Data Risc.</th>
                  <th class="text-center">Acconto</th>
                  <th class="text-center">Saldo</th>
                  <th class="text-right">Importo (€)</th>
                </tr>
              </thead>
              <tbody id="tbody-f24"></tbody>
            </table>
            <!-- Riepilogo dovuto / pagato / differenza -->
            <div class="row">
              <div class="col-md-7">
                <table class="table table-sm table-bordered mb-0" style="font-size:13px" id="tbl-f24-riepilogo">
                  <thead class="thead-light">
                    <tr>
                      <th></th>
                      <th class="text-right">Dovuto</th>
                      <th class="text-right">Pagato (F24)</th>
                      <th class="text-right">Differenza</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Acconto (giugno)</td>
                      <td class="text-right" id="f24-r-acc-dov">—</td>
                      <td class="text-right text-primary font-weight-bold" id="f24-r-acc-pag">—</td>
                      <td class="text-right" id="f24-r-acc-diff">—</td>
                    </tr>
                    <tr id="f24-row-saldo">
                      <td>Saldo (dicembre)</td>
                      <td class="text-right" id="f24-r-sal-dov">—</td>
                      <td class="text-right text-primary font-weight-bold" id="f24-r-sal-pag">—</td>
                      <td class="text-right" id="f24-r-sal-diff">—</td>
                    </tr>
                    <tr class="table-secondary font-weight-bold">
                      <td><strong>Totale annuale</strong></td>
                      <td class="text-right" id="f24-r-tot-dov">—</td>
                      <td class="text-right text-primary" id="f24-r-tot-pag">—</td>
                      <td class="text-right" id="f24-r-tot-diff">—</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="col-md-5 pt-1" id="f24-note" style="font-size:12px"></div>
            </div>
          </div>
        </div>
      </div>

    </div><!-- /sezione-risultati -->
  </section>
</div>

<?php
$this->registerJs(<<<'JSINIT'
(function () {
'use strict';

// ── Stato globale ──────────────────────────────────────────────────────────
var state = { persona: {}, immobili: [], aliquote: {}, coefficienti: {}, valoriZone: {}, anno: new Date().getFullYear(), codComune: '', tassoLegale: 0, tardivo: null, pagamentiF24: [], variazioniIci: [] };

// ── Elenco tipi utilizzo — FABBRICATI ────────────────────────────────────
var TIPI_FAB = [
    { val: 'abpr_std',             lbl: 'Abitazione principale e assimilate (Cat. da A2 ad A7)',                                              esente: true  },
    { val: 'pertinenza_std',       lbl: 'Pertinenze Abitazione Principale (C/2, C/6, C/7)',                                                  esente: true  },
    { val: 'assimilata_anziani',   lbl: 'Assimilata ad Abitazione Principale - posseduta da anziani o disabili',                            esente: true  },
    { val: 'abpr_lusso',           lbl: 'Abitazione principale di pregio (Cat. A/1, A/8, A/9)',                                              esente: false },
    { val: 'pertinenza_lusso',     lbl: 'Pertinenze Abitazione principale di pregio (C/2, C/6, C/7)',                                        esente: false },
    { val: 'altra_abitazione',     lbl: 'Altre abitazioni - immobili Cat. A (tranne A/10)',                                                  esente: false },
    { val: 'a10_uffici',           lbl: 'Cat. A/10 - Uffici e studi privati',                                                               esente: false },
    { val: 'c1_negozi',            lbl: 'Cat. C/1 - Negozi e botteghe',                                                                     esente: false },
    { val: 'c2_magazzini',         lbl: 'Cat. C/2 - Magazzini e locali di deposito',                                                        esente: false },
    { val: 'c3_laboratori',        lbl: 'Cat. C/3 - Laboratori per arti e mestieri',                                                        esente: false },
    { val: 'b_c4_c5',              lbl: 'Cat. B, C/4, C/5 - Fabbricati comuni',                                                             esente: false },
    { val: 'c6_c7_stalle',         lbl: 'Cat. C/6, C/7 - Stalle, scuderie, rimesse, autorimesse - Tettoie',                                esente: false },
    { val: 'cat_d',                lbl: 'Cat. D, tranne D/5 e D/10 - Immobili industriali e commerciali',                                   esente: false },
    { val: 'd5_credito',           lbl: 'Cat. D/5 - Istituti di credito ed assicurazioni',                                                  esente: false },
    { val: 'd10_rurale',           lbl: 'Fabbricati rurali ad uso strumentale all\'attività agricola (D/10)',                               esente: false },
    { val: 'rurale_abc',           lbl: 'Fabbricati rurali ad uso strumentale all\'attività agricola (Cat. A, C/2, C/6, C/7)',             esente: false },
    { val: 'comodato50',           lbl: 'Abitazione concessa in comodato gratuito (tranne A/1, A/8, A/9) - riduzione 50% base imponibile', esente: false },
    { val: 'comodato_noriduziome', lbl: 'Abitazione in comodato gratuito senza riduzione imponibile',                                       esente: false },
    { val: 'iacp',                 lbl: 'Abitazioni assegnate dagli Istituti Autonomi Case Popolari (ex IACP/ARES/ALER)',                   esente: false },
];

// ── Elenco tipi utilizzo — AREE EDIFICABILI ───────────────────────────────
var TIPI_AREA = [
    { val: 'area_fabbricabile', lbl: 'Area fabbricabile',                              esente: false },
    { val: 'peep',              lbl: 'Area PEEP - Piano di Edilizia Economica e Pop.', esente: false },
    { val: 'terreno_agricolo',  lbl: 'Terreno agricolo',                               esente: true  },
];

var RIDUZIONI = [
    { val: 'no',        lbl: 'NO' },
    { val: 'inagibile', lbl: 'Inagibile (50% base)' },
    { val: 'storico',   lbl: 'Storico (50% base)' },
];

// ── Opzioni tipo rilevanti per categoria catastale ────────────────────────
function tipiPerCategoria(cat) {
    var c = (cat || '').toUpperCase().replace(/\s|\./g, '');

    if (/^D\/10$|^D10$/.test(c))
        return ['d10_rurale', 'cat_d'];
    if (/^D\/5$|^D5$/.test(c))
        return ['d5_credito', 'cat_d'];
    if (/^D/.test(c))
        return ['cat_d', 'assimilata_anziani'];

    if (/^A\/10$|^A10$/.test(c))
        return ['a10_uffici', 'assimilata_anziani', 'altra_abitazione'];

    if (/^A\/(1|8|9)$|^A[189]$/.test(c))
        return ['abpr_lusso', 'pertinenza_lusso', 'altra_abitazione',
                'assimilata_anziani', 'comodato_noriduziome', 'iacp'];

    if (/^A/.test(c))  // A/2–A/7
        return ['abpr_std', 'altra_abitazione', 'assimilata_anziani',
                'comodato50', 'comodato_noriduziome', 'iacp', 'rurale_abc'];

    if (/^B$|^C\/(4|5)$|^C[45]$/.test(c))
        return ['b_c4_c5', 'assimilata_anziani'];

    if (/^C\/1$|^C1$/.test(c))
        return ['c1_negozi'];

    if (/^C\/2$|^C2$/.test(c))
        return ['c2_magazzini', 'pertinenza_std', 'pertinenza_lusso', 'rurale_abc', 'assimilata_anziani'];

    if (/^C\/3$|^C3$/.test(c))
        return ['c3_laboratori', 'rurale_abc'];

    if (/^C\/(6|7)$|^C[67]$/.test(c))
        return ['c6_c7_stalle', 'pertinenza_std', 'pertinenza_lusso', 'rurale_abc', 'assimilata_anziani'];

    if (/^[EF]/.test(c))
        return ['assimilata_anziani', 'altra_abitazione'];

    return TIPI_FAB.map(function (t) { return t.val; }); // categoria sconosciuta: tutte
}

// ── Default tipo in base alla categoria ────────────────────────────────────
function defaultTipo(cat) {
    var c = (cat || '').toUpperCase().replace(/\s|\./g, '');
    if (/^D\/10$|^D10$/.test(c))          return 'd10_rurale';
    if (/^D\/5$|^D5$/.test(c))            return 'd5_credito';
    if (/^D/.test(c))                      return 'cat_d';
    if (/^A\/10$|^A10$/.test(c))          return 'a10_uffici';
    if (/^A\/(1|8|9)$|^A[189]$/.test(c)) return 'altra_abitazione'; // pregio, default non principale
    if (/^A/.test(c))                      return 'altra_abitazione';
    if (/^C\/1$|^C1$/.test(c))            return 'c1_negozi';
    if (/^C\/2$|^C2$/.test(c))            return 'c2_magazzini';
    if (/^C\/3$|^C3$/.test(c))            return 'c3_laboratori';
    if (/^C\/(6|7)$|^C[67]$/.test(c))    return 'c6_c7_stalle';
    if (/^B$|^C\/(4|5)$|^C[45]$/.test(c)) return 'b_c4_c5';
    if (/^[EF]/.test(c))                   return 'assimilata_anziani';
    return 'altra_abitazione';
}

// ── Coefficiente per categoria ─────────────────────────────────────────────
function getCoeff(cat) {
    var c = (cat || '').toUpperCase().replace(/\s|\./g, '');
    if (state.coefficienti[c])  return state.coefficienti[c];
    // prova la classe base (es. A/2 → A)
    var base = c.replace(/\/\d+$/, '');
    if (state.coefficienti[base]) return state.coefficienti[base];
    return 160;
}

// ── Calcola IMU per un immobile ────────────────────────────────────────────
function calcolaImm(rendita, cat, tipo, quotaNum, quotaDen, mesi, riduzione, tipoRecord, valoreVenale) {
    var quota  = quotaNum / (quotaDen > 0 ? quotaDen : 1);
    var alq    = state.aliquote;
    var coeff, base;

    if (tipoRecord === 'area') {
        if (tipo === 'terreno_agricolo') {
            return { coeff: 1, base: 0, aliquota: 0, detrazione: 0, imuProporzionale: 0, esente: true };
        }
        coeff = 1;
        base  = valoreVenale || 0;
        var aliquota = tipo === 'peep'
            ? (alq['peep'] || alq['area_fabbricabile'] || alq['aree_fabbricabili'] || alq['altri_immobili'] || {}).aliquota || 0.0106
            : (alq['area_fabbricabile'] || alq['aree_fabbricabili'] || alq['altri_immobili'] || {}).aliquota || 0.0106;
        var imu = Math.max(0, base * aliquota * quota * (mesi / 12));
        return { coeff: 1, base: base, aliquota: aliquota, detrazione: 0,
                 imuProporzionale: imu, esente: false };
    }

    coeff = getCoeff(cat);
    base  = rendita * 1.05 * coeff;

    // Riduzioni fisiche (inagibile/storico)
    if (riduzione === 'inagibile' || riduzione === 'storico') base *= 0.5;
    // Riduzione comodato gratuito con 50%
    if (tipo === 'comodato50') base *= 0.5;

    var aliquota = 0, detrazione = 0, imuAnnuale = 0, esente = false;

    switch (tipo) {
        case 'abpr_std': case 'pertinenza_std': case 'assimilata_anziani': case 'terreno_agricolo':
            esente = true; break;

        case 'abpr_lusso':
            aliquota   = (alq['abitazione_principale'] || {}).aliquota   || 0.004;
            detrazione = (alq['abitazione_principale'] || {}).detrazione || 200;
            imuAnnuale = Math.max(0, (base * aliquota - detrazione) * quota * (mesi / 12));
            break;

        case 'pertinenza_lusso':
            aliquota   = (alq['abitazione_principale'] || {}).aliquota || 0.004;
            imuAnnuale = Math.max(0, base * aliquota * quota * (mesi / 12));
            break;

        case 'd10_rurale': case 'rurale_abc':
            aliquota   = (alq['rurali_strumentali'] || {}).aliquota || 0.001;
            imuAnnuale = Math.max(0, base * aliquota * quota * (mesi / 12));
            break;

        case 'area_fabbricabile':
            aliquota   = (alq['aree_fabbricabili'] || alq['altri_immobili'] || {}).aliquota || 0.0106;
            imuAnnuale = Math.max(0, base * aliquota * quota * (mesi / 12));
            break;

        default: // tutti gli altri → altri_immobili (10,6‰)
            aliquota   = (alq['altri_immobili'] || {}).aliquota   || 0.0106;
            detrazione = (alq['altri_immobili'] || {}).detrazione || 0;
            imuAnnuale = Math.max(0, (base * aliquota - detrazione) * quota * (mesi / 12));
            break;
    }

    return { coeff: coeff, base: base, aliquota: aliquota, detrazione: detrazione,
             imuProporzionale: imuAnnuale, esente: esente };
}

// ── Formattazione numero italiano ─────────────────────────────────────────
function fmt(n, dec) {
    dec = dec === undefined ? 2 : dec;
    var s = n.toFixed(dec);
    var parts = s.split('.');
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    return parts.join(',');
}

// ── Costruisce il select Zona PRG (per aree edificabili) ─────────────────
function buildZonaSelect(zonaSel, idx) {
    var zones = state.valoriZone;
    var opts  = '<option value="">— seleziona zona —</option>';
    var keys  = Object.keys(zones).sort(function (a, b) {
        return (zones[a].label || a).localeCompare(zones[b].label || b);
    });
    keys.forEach(function (k) {
        var val = '€ ' + fmt(zones[k].valore_mq) + '/mq';
        opts += '<option value="' + k + '"' + (k === zonaSel ? ' selected' : '') + '>' + (zones[k].label || k) + ' (' + val + ')</option>';
    });
    if (zonaSel && !zones[zonaSel]) {
        opts += '<option value="' + zonaSel + '" selected>' + zonaSel + ' (n.d.)</option>';
    }
    return '<select class="form-control form-control-sm sel-zona" data-idx="' + idx + '" style="font-size:11px;min-width:160px">' + opts + '</select>';
}

// ── Costruisce il select Tipo utilizzo ────────────────────────────────────
function buildTipoSelect(tipoSel, idx, isArea, categoria) {
    var lista;
    if (isArea) {
        lista = TIPI_AREA;
    } else {
        var validi = tipiPerCategoria(categoria);
        // Garantisce che il valore attuale sia sempre presente anche se non nel filtro
        if (tipoSel && validi.indexOf(tipoSel) === -1) { validi = [tipoSel].concat(validi); }
        lista = validi.map(function (v) {
            return TIPI_FAB.find(function (t) { return t.val === v; });
        }).filter(Boolean);
    }
    return '<select class="form-control form-control-sm sel-tipo" data-idx="' + idx + '" ' +
           'style="font-size:11px;width:100%">' +
           lista.map(function (t) {
               return '<option value="' + t.val + '"' + (t.val === tipoSel ? ' selected' : '') + '>' + t.lbl + '</option>';
           }).join('') + '</select>';
}

// ── Costruisce il select Mesi ─────────────────────────────────────────────
function buildMesiSelect(mesiSel, idx) {
    var opts = '';
    for (var m = 1; m <= 12; m++) {
        opts += '<option value="' + m + '"' + (m === mesiSel ? ' selected' : '') + '>' + m + '</option>';
    }
    return '<select class="form-control form-control-sm sel-mesi" data-idx="' + idx + '" style="width:55px">' + opts + '</select>';
}

// ── Costruisce il select Riduzione ───────────────────────────────────────
function buildRiduzSelect(riduzSel, idx) {
    return '<select class="form-control form-control-sm sel-riduz" data-idx="' + idx + '" style="font-size:11px;width:96px">' +
           RIDUZIONI.map(function (r) {
               return '<option value="' + r.val + '"' + (r.val === riduzSel ? ' selected' : '') + '>' + r.lbl + '</option>';
           }).join('') + '</select>';
}

// ── Costruisce e inserisce le righe della tabella ─────────────────────────
function buildTable() {
    var tbody = document.getElementById('tbody-immobili');
    tbody.innerHTML = '';
    state.immobili.forEach(function (imm, idx) {
        var isArea   = imm.tipoRecord === 'area';
        var mesiSel  = imm._mesi  || 12;
        var riduzSel = imm._riduz || 'no';
        imm._mesi  = mesiSel;
        imm._riduz = riduzSel;

        var consistenza = imm._consistenza !== undefined ? imm._consistenza : (imm.consistenza || 0);
        imm._consistenza = consistenza;

        var tipoSel, valoreVenale;
        if (isArea) {
            tipoSel   = imm._tipo || 'area_fabbricabile';
            imm._tipo = tipoSel;
            // Primo caricamento: trova la chiave id-based che corrisponde al codice PRG
            var zonaSel0;
            if (imm._zona !== undefined) {
                zonaSel0 = imm._zona;
            } else {
                var zonaCode = imm.zonaCode || '';
                zonaSel0 = '';
                Object.keys(state.valoriZone).forEach(function (k) {
                    if (!zonaSel0 && state.valoriZone[k].zona === zonaCode) { zonaSel0 = k; }
                });
            }
            imm._zona    = zonaSel0;
            var vzData0  = state.valoriZone[zonaSel0];
            valoreVenale = consistenza * (vzData0 ? vzData0.valore_mq : 0);
        } else {
            tipoSel      = imm._tipo || defaultTipo(imm.categoria);
            imm._tipo    = tipoSel;
            valoreVenale = imm._valoreVenale !== undefined ? imm._valoreVenale : 0;
        }
        imm._valoreVenale = valoreVenale;

        var res = calcolaImm(imm.rendita, imm.categoria, tipoSel, imm.quotaNum, imm.quotaDen, mesiSel, riduzSel, imm.tipoRecord || 'fabbricato', valoreVenale);
        imm._res = res;

        var quotaStr = imm.quotaNum + '/' + imm.quotaDen;
        if (imm.quotaNum === imm.quotaDen) { quotaStr += ' (100%)'; }
        else { quotaStr += ' (' + Math.round(imm.quotaNum / imm.quotaDen * 100) + '%)'; }

        // Cella Rendita (fabbricati) o select Zona PRG (aree edificabili)
        var renditaCell = isArea
            ? buildZonaSelect(imm._zona || '', idx)
            : fmt(imm.rendita);

        // Cella Consistenza (mq) — editabile solo per le aree
        var consistCell = isArea
            ? '<input type="number" class="form-control form-control-sm inp-consist" data-idx="' + idx + '" value="' + consistenza + '" min="0" step="1" style="width:70px" title="mq edificabili">'
            : '<span class="text-muted">—</span>';

        var catLabel = isArea
            ? '<strong>AF</strong>'
            : '<strong>' + (imm.categoria || '?') + '</strong>';

        var fromIci  = !!imm._fromIci;
        var tr = document.createElement('tr');
        tr.className = res.esente ? 'table-success' : (fromIci ? 'table-info' : (isArea ? 'table-warning' : ''));
        tr.dataset.idx = idx;
        var foglioCell = fromIci
            ? imm.foglio + ' <span class="badge bg-primary" style="font-size:9px" title="Immobile da variazione ICI ' + state.anno + ', non ancora nel catasto">ICI</span>'
            : imm.foglio;
        tr.innerHTML =
            '<td>' + foglioCell + '</td>' +
            '<td>' + imm.numero + '</td>' +
            '<td>' + (imm.subalterno || '—') + '</td>' +
            '<td>' + catLabel + '</td>' +
            '<td class="text-right">' + renditaCell + '</td>' +
            '<td class="text-right">' + consistCell + '</td>' +
            '<td class="text-center" style="white-space:nowrap;font-size:11px">' + quotaStr + '</td>' +
            '<td>' + buildTipoSelect(tipoSel, idx, isArea, imm.categoria) + '</td>' +
            '<td class="text-center">' + buildMesiSelect(mesiSel, idx) + '</td>' +
            '<td class="text-center">' + buildRiduzSelect(riduzSel, idx) + '</td>' +
            '<td class="text-right td-coeff">' + res.coeff + '</td>' +
            '<td class="text-right td-base">'  + (res.esente ? '<em>—</em>' : fmt(res.base)) + '</td>' +
            '<td class="text-right td-alq">'   + (res.esente ? '<em>ESENTE</em>' : fmt(res.aliquota * 1000, 3) + '‰') + '</td>' +
            '<td class="text-right td-imu"><strong>' + (res.esente ? '0,00' : fmt(res.imuProporzionale)) + '</strong></td>';
        tbody.appendChild(tr);

        // Variazioni ICI: cerca corrispondenza foglio+numero+subalterno
        var fInt = parseInt(imm.foglio, 10);
        var nInt = parseInt(imm.numero, 10);
        var sStr = String(imm.subalterno || '').replace(/^0+/, '') || '';
        var varImm = (state.variazioniIci || []).filter(function (v) {
            return parseInt(v.foglio, 10) === fInt &&
                   parseInt(v.numero, 10) === nInt &&
                   (v.tipologia === 'T' || (String(v.subalterno || '').replace(/^0+/, '') || '') === sStr);
        });
        varImm.forEach(function (v) {
            var isAcq  = v.tipo === 'A';
            var bgCls  = isAcq ? 'table-info' : 'table-warning';
            var icon   = isAcq ? 'fas fa-arrow-circle-right text-primary' : 'fas fa-arrow-circle-left text-warning';
            var label  = isAcq ? 'ACQUISITO' : 'CEDUTO';
            function fmtData(s) {
                var p = (s || '').split('-');
                return p.length === 3 ? p[2] + '/' + p[1] + '/' + p[0] : (s || '—');
            }
            var dFmt   = fmtData(v.data_pres);
            var dAtto  = v.data_atto && v.data_atto !== v.data_pres ? fmtData(v.data_atto) : null;
            var mSug   = v.mesi_suggeriti || 0;
            var descDir = v.desc_diritto || v.codice_diritto || '';
            var trV = document.createElement('tr');
            trV.className = bgCls;
            trV.innerHTML =
                '<td colspan="14" class="py-1 px-3 small">' +
                '<i class="' + icon + ' me-1"></i>' +
                '<strong>' + label + '</strong>' +
                ' trascritto il <strong>' + dFmt + '</strong>' +
                (dAtto ? ' &mdash; <span class="text-muted">atto del</span> <strong>' + dAtto + '</strong>' : '') +
                (descDir ? ' &mdash; ' + descDir : '') +
                (v.quota_fraz && v.quota_fraz !== '—' ? ' quota <strong>' + v.quota_fraz + '</strong>' : '') +
                ' &nbsp;|&nbsp; <span class="text-muted">Mesi suggeriti: </span>' +
                '<strong class="' + (isAcq ? 'text-primary' : 'text-warning') + '">' + mSug + '</strong>' +
                (mSug > 0 && mSug < 12
                    ? ' <button class="btn btn-xs btn-outline-' + (isAcq ? 'primary' : 'warning') +
                      ' py-0 px-1 ms-1 btn-applica-mesi" data-idx="' + idx + '" data-mesi="' + mSug + '"' +
                      ' title="Applica ' + mSug + ' mesi di possesso" style="font-size:11px">Applica</button>'
                    : '') +
                '</td>';
            tbody.appendChild(trV);
        });
    });
    aggiornaTotali();
}

// ── Aggiorna una riga e i totali ──────────────────────────────────────────
function aggiornaRiga(tr, idx) {
    var imm      = state.immobili[idx];
    var tipoSel  = tr.querySelector('.sel-tipo').value;
    var mesiSel  = parseInt(tr.querySelector('.sel-mesi').value) || 12;
    var riduzSel = tr.querySelector('.sel-riduz').value;
    imm._tipo  = tipoSel;
    imm._mesi  = mesiSel;
    imm._riduz = riduzSel;

    var inpConsist = tr.querySelector('.inp-consist');
    if (inpConsist) { imm._consistenza = parseFloat(inpConsist.value) || 0; }

    var isArea = imm.tipoRecord === 'area';
    var valoreVenale;
    if (isArea) {
        var selZona = tr.querySelector('.sel-zona');
        if (selZona) { imm._zona = selZona.value; }
        var vzData  = state.valoriZone[imm._zona || ''];
        valoreVenale = (imm._consistenza || 0) * (vzData ? vzData.valore_mq : 0);
        imm._valoreVenale = valoreVenale;
    } else {
        valoreVenale = imm._valoreVenale || 0;
    }

    var res = calcolaImm(imm.rendita, imm.categoria, tipoSel, imm.quotaNum, imm.quotaDen, mesiSel, riduzSel,
                         imm.tipoRecord || 'fabbricato', valoreVenale);
    imm._res = res;

    tr.className = res.esente ? 'table-success' : (isArea ? 'table-warning' : '');
    tr.querySelector('.td-coeff').textContent = res.coeff;
    tr.querySelector('.td-base').innerHTML    = res.esente ? '<em>—</em>' : fmt(res.base);
    tr.querySelector('.td-alq').innerHTML     = res.esente ? '<em>ESENTE</em>' : fmt(res.aliquota * 1000, 3) + '‰';
    tr.querySelector('.td-imu').innerHTML     = '<strong>' + (res.esente ? '0,00' : fmt(res.imuProporzionale)) + '</strong>';
    aggiornaTotali();
}

// ── Ricalcola i totali ────────────────────────────────────────────────────
function aggiornaTotali() {
    var periodo  = document.querySelector('input[name="periodo"]:checked').value;
    var fattore  = (periodo === 'annuale') ? 1.0 : 0.5;
    var totProporz = 0;
    state.immobili.forEach(function (imm) {
        if (!imm._res || imm._res.esente) return;
        totProporz += imm._res.imuProporzionale;
    });
    var dovuta = totProporz * fattore;
    var labels = {
        acconto:  'IMU da versare — Acconto giugno (50%):',
        saldo:    'IMU da versare — Saldo dicembre (50%):',
        annuale:  'IMU da versare — Annuale (100%):'
    };
    document.getElementById('tot-annuale').textContent = '€ ' + fmt(totProporz);
    document.getElementById('tot-dovuta').textContent  = '€ ' + fmt(dovuta);
    document.getElementById('lbl-dovuta').innerHTML    = '<strong>' + (labels[periodo] || '') + '</strong>';

    // Aggiorna saldo nel pannello F24 (i totali IMU sono appena cambiati)
    if (state.pagamentiF24 && state.pagamentiF24.length) {
        aggiornaPannelloF24();
    }
}

// ── Pannello Pagamenti F24 SOGEI ─────────────────────────────────────────
function aggiornaPannelloF24() {
    var pannello  = document.getElementById('pannello-f24');
    var wrapDati  = document.getElementById('f24-dati-wrap');
    var elNessuno = document.getElementById('f24-nessun-dato');
    var tbody     = document.getElementById('tbody-f24');
    var elNote    = document.getElementById('f24-note');

    var pags       = state.pagamentiF24 || [];
    var annoCalc   = state.anno || new Date().getFullYear();
    var annoCorr   = new Date().getFullYear();
    var annoPrec   = annoCalc < annoCorr;   // anno precedente: entrambe le rate già scadute

    pannello.style.removeProperty('display');

    if (!pags.length) {
        wrapDati.style.display  = 'none';
        elNessuno.style.display = '';
        return;
    }

    elNessuno.style.display = 'none';
    wrapDati.style.display  = '';

    // IMU annuale calcolata
    var totAnn = 0;
    state.immobili.forEach(function (imm) {
        if (imm._res && !imm._res.esente) totAnn += imm._res.imuProporzionale;
    });
    totAnn = Math.round(totAnn * 100) / 100;

    // Somme dai pagamenti F24 per tipo
    var accPag = 0, salPag = 0;
    pags.forEach(function (p) {
        var netto = Math.round((p.importo_debito - p.importo_credito) * 100) / 100;
        if (p.acconto && !p.saldo)       { accPag += netto; }
        else if (p.saldo && !p.acconto)  { salPag += netto; }
        else if (p.acconto && p.saldo)   { salPag += netto; } // pagamento unico = trattato come saldo
    });
    accPag = Math.round(accPag * 100) / 100;
    salPag = Math.round(salPag * 100) / 100;

    var accDov  = Math.round(totAnn / 2 * 100) / 100;
    var salDov  = Math.max(0, Math.round((totAnn - accPag) * 100) / 100);
    var totPag  = Math.round((accPag + salPag) * 100) / 100;
    var totDov  = totAnn;

    var diffAcc = Math.round((accPag - accDov) * 100) / 100;
    var diffSal = Math.round((salPag - salDov) * 100) / 100;
    var diffTot = Math.round((totPag - totDov) * 100) / 100;

    // Colore differenza: verde se ≥ 0, rosso se < 0
    function fmtDiff(v) {
        var cls = v >= 0 ? 'text-success' : 'text-danger font-weight-bold';
        return '<span class="' + cls + '">' + (v >= 0 ? '+' : '') + fmt(v) + '</span>';
    }

    // Popola tabella righe
    tbody.innerHTML = pags.map(function (p) {
        var netto = p.importo_debito - p.importo_credito;
        var isAcc = p.acconto && !p.saldo;
        var isSal = !p.acconto;
        return '<tr>' +
            '<td><span class="badge badge-secondary">' + p.codice_tributo + '</span></td>' +
            '<td class="small">' + (p.desc_tributo || '') + '</td>' +
            '<td class="text-center small">' + (p.data_riscossione || '—') + '</td>' +
            '<td class="text-center">' + (isAcc ? '<i class="fas fa-check text-primary"></i>' : '') + '</td>' +
            '<td class="text-center">' + (isSal ? '<i class="fas fa-check text-success"></i>' : '') + '</td>' +
            '<td class="text-right font-weight-bold">€ ' + fmt(netto) + '</td>' +
            '</tr>';
    }).join('');

    // Popola riepilogo
    document.getElementById('f24-r-acc-dov').textContent  = '€ ' + fmt(accDov);
    document.getElementById('f24-r-acc-pag').textContent  = '€ ' + fmt(accPag);
    document.getElementById('f24-r-acc-diff').innerHTML   = fmtDiff(diffAcc);
    document.getElementById('f24-r-tot-dov').textContent  = '€ ' + fmt(totDov);
    document.getElementById('f24-r-tot-pag').textContent  = '€ ' + fmt(totPag);
    document.getElementById('f24-r-tot-diff').innerHTML   = fmtDiff(diffTot);

    var rowSaldo = document.getElementById('f24-row-saldo');
    if (annoPrec) {
        // Anno chiuso: mostra saldo pagato e differenza saldo
        rowSaldo.style.display = '';
        document.getElementById('f24-r-sal-dov').textContent = '€ ' + fmt(salDov);
        document.getElementById('f24-r-sal-pag').textContent = '€ ' + fmt(salPag);
        document.getElementById('f24-r-sal-diff').innerHTML  = fmtDiff(diffSal);
    } else {
        // Anno corrente: riga saldo mostra il dovuto residuo
        rowSaldo.style.display = '';
        document.getElementById('f24-r-sal-dov').innerHTML  = '<em>da versare:</em> <strong style="color:#c0392b">€ ' + fmt(salDov) + '</strong>';
        document.getElementById('f24-r-sal-pag').textContent = salPag > 0 ? '€ ' + fmt(salPag) : '—';
        document.getElementById('f24-r-sal-diff').textContent = '—';
    }

    // Note
    var noteHtml = '';
    if (annoPrec) {
        if (diffTot >= 0) {
            noteHtml = '<i class="fas fa-check-circle text-success"></i> Totale pagato <strong>in eccedenza di € ' + fmt(diffTot) + '</strong>.';
        } else {
            noteHtml = '<i class="fas fa-exclamation-triangle text-danger"></i> Totale non pagato: <strong>€ ' + fmt(-diffTot) + '</strong>.<br>' +
                       'Verificare eventuale ravvedimento operoso.';
        }
    } else {
        if (diffAcc >= 0) {
            noteHtml = '<i class="fas fa-check-circle text-success"></i> Acconto versato in eccedenza di € ' + fmt(diffAcc) + '.<br>' +
                       'Saldo residuo: <strong style="color:#c0392b">€ ' + fmt(salDov) + '</strong>.';
        } else if (diffAcc < 0) {
            noteHtml = '<i class="fas fa-exclamation-triangle text-warning"></i> Acconto inferiore al teorico di € ' + fmt(-diffAcc) + '.<br>' +
                       'Saldo residuo (IMU ann. &minus; acc. eff.): <strong style="color:#c0392b">€ ' + fmt(salDov) + '</strong>.';
        } else {
            noteHtml = '<i class="fas fa-check-circle text-success"></i> Acconto esatto.<br>' +
                       'Saldo da versare: <strong style="color:#c0392b">€ ' + fmt(salDov) + '</strong>.';
        }
    }
    elNote.innerHTML = noteHtml;
}

// ── Raccoglie i dati per il server ────────────────────────────────────────
function raccogliDati(includeTardivo) {
    var periodo  = document.querySelector('input[name="periodo"]:checked').value;
    var rows     = document.querySelectorAll('#tbody-immobili tr');
    var immoOut  = state.immobili.map(function (imm, idx) {
        var tr = rows[idx];
        var inpConsist = tr ? tr.querySelector('.inp-consist') : null;
        return {
            foglio:       imm.foglio,
            numero:       imm.numero,
            subalterno:   imm.subalterno || '0',
            categoria:    imm.categoria,
            rendita:      imm.rendita,
            tipoRecord:   imm.tipoRecord   || 'fabbricato',
            valoreVenale: imm._valoreVenale || 0,
            consistenza:  inpConsist ? (parseFloat(inpConsist.value) || 0) : (imm._consistenza  || 0),
            zona:         (function() {
                var vzd = state.valoriZone[imm._zona];
                return vzd ? vzd.label : (imm._zona || imm.zona || '');
            })(),
            quotaNum:     imm.quotaNum,
            quotaDen:     imm.quotaDen,
            tipoUtilizzo: tr ? tr.querySelector('.sel-tipo').value : (imm._tipo || 'altra_abitazione'),
            mesi:         tr ? parseInt(tr.querySelector('.sel-mesi').value) || 12 : (imm._mesi || 12),
            riduzione:    tr ? tr.querySelector('.sel-riduz').value : (imm._riduz || 'no'),
            coeff:        imm._res ? imm._res.coeff : 160,
        };
    });
    var fd = new FormData();
    fd.append('anno',      state.anno);
    fd.append('periodo',   periodo);
    fd.append('codComune', state.codComune);
    fd.append('persona',   JSON.stringify(state.persona));
    fd.append('immobili',  JSON.stringify(immoOut));
    fd.append('_csrf',     CSRF_TOKEN);
    if (includeTardivo && state.tardivo) {
        fd.append('tardivo', JSON.stringify(state.tardivo));
    }
    return fd;
}

// ── Genera documento (PDF o F24) ─────────────────────────────────────────
function generaDocumento(url, includeTardivo) {
    var isTardivo = includeTardivo === true;
    var errBox = document.getElementById(isTardivo ? 'alert-docs-tardivo' : 'alert-docs');
    errBox.className = 'alert d-none';
    fetch(url, { method: 'POST', body: raccogliDati(isTardivo) })
        .then(function (r) { return r.json(); })
        .then(function (r) {
            if (r.ok && r.url) {
                window.open(r.url, '_blank');
            } else {
                errBox.className = 'alert alert-danger';
                errBox.textContent = '❌ ' + (r.error || 'Errore sconosciuto.');
            }
        })
        .catch(function (e) {
            errBox.className = 'alert alert-danger';
            errBox.textContent = '❌ Errore di rete: ' + e.message;
        });
}

// ── Event: pulsante Cerca ─────────────────────────────────────────────────
document.getElementById('btn-cerca').addEventListener('click', function () {
    var box = document.getElementById('alert-ricerca');
    var btn = this;
    box.className = 'alert alert-info mt-1 mb-0 py-1 px-2';
    box.textContent = 'Ricerca in corso…';
    btn.disabled = true;

    var fd = new FormData();
    fd.append('cognome',   document.getElementById('in-cognome').value.trim().toUpperCase());
    fd.append('nome',      document.getElementById('in-nome').value.trim().toUpperCase());
    fd.append('cod_fisc',  document.getElementById('in-cf').value.trim().toUpperCase());
    fd.append('data_nasc', document.getElementById('in-nasc').value.trim());
    fd.append('anno',      document.getElementById('sel-anno').value);
    fd.append('_csrf',     CSRF_TOKEN);

    fetch(RICERCA_URL, { method: 'POST', body: fd })
        .then(function (r) { return r.json(); })
        .then(function (r) {
            btn.disabled = false;
            if (!r.ok) {
                box.className = 'alert alert-danger mt-1 mb-0 py-1 px-2';
                box.textContent = r.error || 'Nessun risultato.';
                document.getElementById('sezione-risultati').classList.add('d-none');
                return;
            }
            box.className = 'alert alert-success mt-1 mb-0 py-1 px-2';
            box.textContent = 'Trovati ' + r.immobili.length + ' fabbricato/i.';

            state.persona      = r.persona;
            state.immobili     = r.immobili;
            state.aliquote     = r.aliquote;
            state.coefficienti = r.coefficienti;
            state.valoriZone   = r.valoriZone   || {};
            state.anno         = r.anno;
            state.codComune    = r.codComune || '';
            state.tassoLegale  = r.tassoLegale  || 0;
            state.pagamentiF24  = r.pagamentiF24  || [];
            state.variazioniIci = r.variazioniIci || [];
            state.tardivo = null;
            // preset data pagamento = oggi
            var oggi = new Date().toISOString().split('T')[0];
            document.getElementById('inp-data-pagamento').value = oggi;
            document.getElementById('tbl-tardivo-wrap').classList.add('d-none');
            document.getElementById('alert-docs-tardivo').className = 'alert d-none';

            var p = r.persona;
            document.getElementById('res-intestatario').textContent = p.cognome + ' ' + p.nome;
            document.getElementById('res-cf').textContent = p.codFiscale ? '(CF: ' + p.codFiscale + ')' : '';
            document.getElementById('res-nasc').textContent = p.dataNasc || '—';
            document.getElementById('res-sesso').textContent = p.sesso ? ' (' + p.sesso + ')' : '';
            document.getElementById('res-luogo').textContent =
                (p.luogoNasc || '') + (p.provNasc ? ' (' + p.provNasc + ')' : '');

            document.getElementById('alert-docs').className = 'alert d-none';
            document.getElementById('sezione-risultati').classList.remove('d-none');
            buildTable();
            aggiornaPannelloF24();
        })
        .catch(function (e) {
            btn.disabled = false;
            box.className = 'alert alert-danger mt-1 mb-0 py-1 px-2';
            box.textContent = 'Errore di rete: ' + e.message;
        });
});

// ── Event: cambio dropdown e input numerici in tabella ────────────────────
document.getElementById('tbody-immobili').addEventListener('change', function (e) {
    var el = e.target;
    var cl = el.classList;
    if (!cl.contains('sel-tipo') && !cl.contains('sel-mesi') && !cl.contains('sel-riduz') &&
        !cl.contains('sel-zona') && !cl.contains('inp-consist')) return;
    var tr = el.closest('tr');
    var idx = parseInt(tr ? tr.dataset.idx : el.getAttribute('data-idx'));
    if (isNaN(idx)) return;
    aggiornaRiga(tr, idx);
});
document.getElementById('tbody-immobili').addEventListener('input', function (e) {
    var el = e.target;
    if (!el.classList.contains('inp-consist')) return;
    var tr = el.closest('tr');
    var idx = parseInt(tr ? tr.dataset.idx : el.getAttribute('data-idx'));
    if (isNaN(idx)) return;
    aggiornaRiga(tr, idx);
});
document.getElementById('tbody-immobili').addEventListener('click', function (e) {
    var btn = e.target.closest('.btn-applica-mesi');
    if (!btn) return;
    var idx  = parseInt(btn.dataset.idx);
    var mesi = parseInt(btn.dataset.mesi);
    if (isNaN(idx) || isNaN(mesi)) return;
    // Trova la TR dell'immobile (quella con data-idx)
    var tr = document.querySelector('#tbody-immobili tr[data-idx="' + idx + '"]');
    if (!tr) return;
    var selMesi = tr.querySelector('.sel-mesi');
    if (selMesi) {
        selMesi.value = mesi;
        state.immobili[idx]._mesi = mesi;
        aggiornaRiga(tr, idx);
    }
});

// ── Event: cambio periodo ─────────────────────────────────────────────────
document.querySelectorAll('input[name="periodo"]').forEach(function (r) {
    r.addEventListener('change', aggiornaTotali);
});

// ── Calcolo interessi e sanzioni per ritardato pagamento ─────────────────
function prossimoPD(d) {                               // slitta al lunedì se festivo
    var dow = d.getDay();
    if (dow === 6) d.setDate(d.getDate() + 2);
    if (dow === 0) d.setDate(d.getDate() + 1);
    return d;
}

function sanzioneRate(dueDate, giorni) {
    if (giorni <= 0) return 0;
    // D.Lgs. 87/2024 → nuove aliquote per violazioni dal 01/09/2024
    var riformaDate = new Date(2024, 8, 1);
    var isNuovo     = dueDate >= riformaDate;
    var ridP = isNuovo ? 0.125 : 0.15;   // aliquota ridotta (≤90 gg)
    var baseP = isNuovo ? 0.25  : 0.30;  // aliquota piena   (>90 gg)
    if (giorni <= 15) return (ridP / 15) * giorni;
    if (giorni <= 90) return ridP;
    return baseP;
}

function calcolaTardivo() {
    var dataPagStr = document.getElementById('inp-data-pagamento').value;
    if (!dataPagStr) { alert('Inserire la data di pagamento.'); return; }

    var anno  = state.anno;
    var tasso = state.tassoLegale || 0;

    // IMU annuale totale da stato corrente
    var totAnn = 0;
    state.immobili.forEach(function (imm) {
        if (imm._res && !imm._res.esente) totAnn += imm._res.imuProporzionale;
    });
    totAnn = Math.round(totAnn * 100) / 100;
    var acconto = Math.round(totAnn / 2 * 100) / 100;
    var saldo   = Math.round((totAnn - acconto) * 100) / 100;

    var dataPag     = new Date(dataPagStr);
    var scadAcconto = prossimoPD(new Date(anno, 5, 16));   // 16 giugno
    var scadSaldo   = prossimoPD(new Date(anno, 11, 16));  // 16 dicembre

    function calcolaRata(label, scad, importo) {
        var ms     = dataPag - scad;
        var giorni = ms > 0 ? Math.ceil(ms / 86400000) : 0;
        var int_   = giorni > 0 ? Math.round(importo * tasso * giorni / 365 * 100) / 100 : 0;
        var san    = giorni > 0 ? Math.round(importo * sanzioneRate(scad, giorni) * 100) / 100 : 0;
        var tot    = Math.round((importo + int_ + san) * 100) / 100;
        var rate   = giorni > 0 ? sanzioneRate(scad, giorni) * 100 : 0;
        return { label: label, scad: scad, giorni: giorni, importo: importo,
                 interessi: int_, sanzione: san, totale: tot, rate: rate };
    }

    var righe = [
        calcolaRata('Acconto (giugno)',   scadAcconto, acconto),
        calcolaRata('Saldo (dicembre)',   scadSaldo,   saldo),
    ];

    function fmtE(v)  { return '€ ' + fmt(v); }
    function fmtDay(d) { return d.toLocaleDateString('it-IT'); }
    function fmtPct(p) { return p.toFixed(2).replace('.', ',') + '%'; }

    var tbody = document.getElementById('tbody-tardivo');
    var tfoot = document.getElementById('tfoot-tardivo');
    tbody.innerHTML = '';
    righe.forEach(function (r) {
        var cls = r.giorni > 0 ? 'text-danger' : 'text-success';
        tbody.innerHTML +=
            '<tr>' +
            '<td>' + r.label + '</td>' +
            '<td>' + fmtDay(r.scad) + '</td>' +
            '<td class="text-center ' + cls + '">' + (r.giorni > 0 ? '+' + r.giorni : '<em>—</em>') + '</td>' +
            '<td class="text-right">' + fmtE(r.importo) + '</td>' +
            '<td class="text-right ' + cls + '">' + (r.giorni > 0 ? fmtE(r.interessi) : '<em>—</em>') + '</td>' +
            '<td class="text-right ' + cls + '">' + (r.giorni > 0 ? fmtE(r.sanzione) + '<br><small>(' + fmtPct(r.rate) + ')</small>' : '<em>—</em>') + '</td>' +
            '<td class="text-right font-weight-bold">' + fmtE(r.totale) + '</td>' +
            '</tr>';
    });

    var totI = Math.round(righe.reduce(function (s, r) { return s + r.interessi; }, 0) * 100) / 100;
    var totS = Math.round(righe.reduce(function (s, r) { return s + r.sanzione;  }, 0) * 100) / 100;
    var totT = Math.round(righe.reduce(function (s, r) { return s + r.totale;    }, 0) * 100) / 100;
    tfoot.innerHTML =
        '<tr class="table-warning">' +
        '<td colspan="3"><strong>Totale complessivo</strong></td>' +
        '<td class="text-right"><strong>' + fmtE(totAnn) + '</strong></td>' +
        '<td class="text-right"><strong>' + fmtE(totI) + '</strong></td>' +
        '<td class="text-right"><strong>' + fmtE(totS) + '</strong></td>' +
        '<td class="text-right" style="font-size:1.1em;color:#c0392b"><strong>' + fmtE(totT) + '</strong></td>' +
        '</tr>';

    // Salva dati tardivo in state per i documenti
    state.tardivo = {
        dataPagamento:     dataPagStr,
        anno:              anno,
        tassoLegale:       tasso,
        acconto: {
            scad:      scadAcconto.toISOString().split('T')[0],
            giorni:    righe[0].giorni,
            importo:   righe[0].importo,
            interessi: righe[0].interessi,
            sanzione:  righe[0].sanzione,
            totale:    righe[0].totale,
            rate:      righe[0].rate
        },
        saldo: {
            scad:      scadSaldo.toISOString().split('T')[0],
            giorni:    righe[1].giorni,
            importo:   righe[1].importo,
            interessi: righe[1].interessi,
            sanzione:  righe[1].sanzione,
            totale:    righe[1].totale,
            rate:      righe[1].rate
        },
        totaleImu:         totAnn,
        totaleInteressi:   totI,
        totaleSanzione:    totS,
        totaleComplessivo: totT
    };

    var tassoStr = (tasso * 100).toFixed(2).replace('.', ',');
    document.getElementById('lbl-tasso-legale').textContent =
        'Tasso legale ' + anno + ': ' + tassoStr + '%  •  Art. 13 D.Lgs. 471/1997 e s.m.i.';
    document.getElementById('note-tardivo').textContent =
        'Interessi a maturazione giornaliera (art. 20 Regolamento IMU comunale). ' +
        'Sanzione: entro 15 gg = quota giornaliera (1/15 dell\'aliquota ridotta); ' +
        'da 16 a 90 gg = aliquota ridotta; oltre 90 gg = aliquota piena. ' +
        'Aliquote pre-1/9/2024: 30% piena / 15% ridotta. ' +
        'Aliquote post-1/9/2024 (D.Lgs. 87/2024): 25% piena / 12,5% ridotta. ' +
        'Acconto: 16 giugno — Saldo: 16 dicembre (slittati al lunedì se festivi).';
    document.getElementById('tbl-tardivo-wrap').classList.remove('d-none');
}

document.getElementById('btn-calcola-tardivo').addEventListener('click', calcolaTardivo);

// ── Event: genera PDF / F24 ───────────────────────────────────────────────
document.getElementById('btn-pdf').addEventListener('click', function ()        { generaDocumento(GENERA_PDF_URL, false); });
document.getElementById('btn-f24').addEventListener('click', function ()        { generaDocumento(GENERA_F24_URL, false); });
document.getElementById('btn-pdf-tardivo').addEventListener('click', function () { generaDocumento(GENERA_PDF_URL, true);  });
document.getElementById('btn-f24-tardivo').addEventListener('click', function () { generaDocumento(GENERA_F24_URL, true);  });

// ── Enter nella ricerca ───────────────────────────────────────────────────
['in-cognome','in-nome','in-cf','in-nasc'].forEach(function (id) {
    document.getElementById(id).addEventListener('keydown', function (e) {
        if (e.key === 'Enter') document.getElementById('btn-cerca').click();
    });
});

})();
JSINIT, \yii\web\View::POS_END);
?>
