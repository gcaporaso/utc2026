<?php
/** @var yii\web\View $this */
/** @var int $anno */
/** @var app\models\ImuF24Fornitura[] $forniture */

$this->title = 'Forniture F24 SOGEI — IMU';
?>

<div class="container-fluid py-3">

  <div class="d-flex align-items-center mb-3 gap-3">
    <h4 class="mb-0"><i class="fas fa-file-import text-primary me-2"></i>Forniture F24 SOGEI</h4>
    <div class="ms-auto d-flex align-items-center gap-2">
      <label class="mb-0 fw-semibold">Anno:</label>
      <select id="selAnno" class="form-select form-select-sm" style="width:90px">
        <?php for ($y = date('Y'); $y >= 2019; $y--): ?>
          <option value="<?= $y ?>" <?= $y == $anno ? 'selected' : '' ?>><?= $y ?></option>
        <?php endfor; ?>
      </select>
      <a href="<?= \yii\helpers\Url::to(['/imu/calcolo']) ?>" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-calculator me-1"></i>Calcolo IMU
      </a>
    </div>
  </div>

  <!-- Upload form -->
  <div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">
      <i class="fas fa-upload me-2"></i>Importa fornitura
    </div>
    <div class="card-body">
      <p class="text-muted small mb-3">
        Caricare il file <strong>.RUN</strong> estratto dal pacchetto ZIP fornito dall'Agenzia delle Entrate,
        oppure il file <strong>.ZIP</strong> completo. Il sistema importa i record G1 (versamenti IMU, tipo imposta "I").
        Dimensione massima: 2 MB. In caso di file più grandi, estrarre il .RUN dallo ZIP prima del caricamento.
      </p>
      <div class="row g-3 align-items-end">
        <div class="col-md-5">
          <label class="form-label fw-semibold">File fornitura (.RUN o .ZIP)</label>
          <input type="file" id="f24file" class="form-control" accept=".run,.zip,.txt">
        </div>
        <div class="col-md-2">
          <label class="form-label fw-semibold">Anno riferimento</label>
          <input type="number" id="annoImport" class="form-control" value="<?= $anno ?>" min="2019" max="<?= date('Y') ?>">
        </div>
        <div class="col-md-3">
          <button id="btnImporta" class="btn btn-primary">
            <i class="fas fa-upload me-1"></i>Importa
          </button>
          <span id="spinImport" class="d-none ms-2">
            <i class="fas fa-spinner fa-spin text-muted"></i> Importazione in corso…
          </span>
        </div>
      </div>
      <div id="msgImport" class="mt-3 d-none"></div>
    </div>
  </div>

  <!-- Storico forniture -->
  <div class="card shadow-sm mb-4">
    <div class="card-header">
      <i class="fas fa-history me-2"></i>Forniture importate
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-sm table-hover mb-0" id="tblForniture">
          <thead class="table-light">
            <tr>
              <th>File</th>
              <th class="text-center">Anno rif.</th>
              <th class="text-center">Data fornitura</th>
              <th class="text-center">Vers. totali</th>
              <th class="text-center">Record IMU</th>
              <th class="text-center">Importato il</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($forniture)): ?>
              <tr><td colspan="7" class="text-center text-muted py-4">Nessuna fornitura importata.</td></tr>
            <?php else: ?>
              <?php foreach ($forniture as $f): ?>
                <tr data-id="<?= $f->id ?>">
                  <td class="small"><code><?= \yii\helpers\Html::encode($f->nome_file) ?></code></td>
                  <td class="text-center"><?= $f->anno_riferimento ?: '—' ?></td>
                  <td class="text-center"><?= $f->data_fornitura ?: '—' ?></td>
                  <td class="text-center"><?= $f->num_record ?></td>
                  <td class="text-center"><span class="badge bg-info"><?= $f->num_imu ?></span></td>
                  <td class="text-center small"><?= $f->importato_il ?></td>
                  <td class="text-center">
                    <button class="btn btn-sm btn-outline-danger btn-del" data-id="<?= $f->id ?>"
                            data-file="<?= \yii\helpers\Html::encode($f->nome_file) ?>">
                      <i class="fas fa-trash"></i>
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Esplora pagamenti per anno/CF -->
  <div class="card shadow-sm">
    <div class="card-header d-flex align-items-center gap-3 flex-wrap">
      <span><i class="fas fa-search me-2"></i>Esplora pagamenti</span>
      <small class="text-muted">
        Se inserisci il CF vengono mostrati tutti gli anni; l'anno filtra solo se il CF è vuoto.
      </small>
      <div class="ms-auto d-flex align-items-center gap-2">
        <input type="text" id="cfFiltro" class="form-control form-control-sm" style="width:175px"
               placeholder="CF (facoltativo)" maxlength="16">
        <select id="annoFiltro" class="form-select form-select-sm" style="width:90px">
          <option value="0">Tutti</option>
          <?php for ($y = date('Y'); $y >= 2019; $y--): ?>
            <option value="<?= $y ?>"><?= $y ?></option>
          <?php endfor; ?>
        </select>
        <button id="btnCerca" class="btn btn-sm btn-outline-primary">
          <i class="fas fa-search"></i> Cerca
        </button>
      </div>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-sm table-hover mb-0 small" id="tblPagamenti">
          <thead class="table-light">
            <tr>
              <th class="text-center">Anno IMU</th>
              <th>Codice Fiscale</th>
              <th>Contribuente</th>
              <th class="text-center">Cod. Tributo</th>
              <th>Descrizione</th>
              <th class="text-center">Data Risc.</th>
              <th class="text-end">Importo (€)</th>
              <th class="text-center">Acc.</th>
              <th class="text-center">Saldo</th>
              <th class="text-center">Ravv.</th>
            </tr>
          </thead>
          <tbody id="bodyPagamenti">
            <tr><td colspan="10" class="text-center text-muted py-3">Inserire un CF o selezionare un anno e premere Cerca.</td></tr>
          </tbody>
        </table>
      </div>
      <div id="countPag" class="text-muted small px-3 py-2"></div>
    </div>
  </div>

</div><!-- /container -->

<script>
(function () {
    'use strict';

    var annoSel   = document.getElementById('selAnno');
    var btnImp    = document.getElementById('btnImporta');
    var fileInput = document.getElementById('f24file');
    var annoInp   = document.getElementById('annoImport');
    var msgDiv    = document.getElementById('msgImport');
    var spin      = document.getElementById('spinImport');
    var btnCerca   = document.getElementById('btnCerca');
    var cfFiltro   = document.getElementById('cfFiltro');
    var annoFiltro = document.getElementById('annoFiltro');
    var bodyPag    = document.getElementById('bodyPagamenti');
    var countPag   = document.getElementById('countPag');

    // Sync selAnno ↔ annoImport
    annoSel.addEventListener('change', function () { annoInp.value = annoSel.value; });
    annoInp.addEventListener('change', function () { annoSel.value = annoInp.value; });

    // ── Import ──
    btnImp.addEventListener('click', function () {
        if (!fileInput.files.length) {
            alert('Selezionare un file .RUN o .ZIP.');
            return;
        }
        var fd = new FormData();
        fd.append('f24file', fileInput.files[0]);
        fd.append('anno', annoInp.value);
        fd.append(document.querySelector('meta[name="csrf-param"]').content,
                  document.querySelector('meta[name="csrf-token"]').content);

        spin.classList.remove('d-none');
        btnImp.disabled = true;
        msgDiv.classList.add('d-none');

        fetch('<?= \yii\helpers\Url::to(['/imu/f24-upload']) ?>', { method: 'POST', body: fd })
            .then(function (r) { return r.json(); })
            .then(function (d) {
                spin.classList.add('d-none');
                btnImp.disabled = false;
                if (d.ok) {
                    msgDiv.className = 'mt-3 alert alert-success';
                    msgDiv.innerHTML = '<i class="fas fa-check-circle me-1"></i>' + d.msg +
                        (d.data_fornitura ? ' &mdash; Data fornitura: <strong>' + d.data_fornitura + '</strong>' : '') +
                        (d.errori && d.errori.length ? '<br><small class="text-warning">Avvisi: ' + d.errori.join('; ') + '</small>' : '');
                    // Ricarica la pagina per aggiornare la tabella forniture
                    setTimeout(function () { location.reload(); }, 1800);
                } else {
                    msgDiv.className = 'mt-3 alert alert-danger';
                    msgDiv.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i>' + (d.error || 'Errore sconosciuto.');
                }
                msgDiv.classList.remove('d-none');
            })
            .catch(function (e) {
                spin.classList.add('d-none');
                btnImp.disabled = false;
                msgDiv.className = 'mt-3 alert alert-danger';
                msgDiv.textContent = 'Errore di comunicazione: ' + e.message;
                msgDiv.classList.remove('d-none');
            });
    });

    // ── Elimina fornitura ──
    document.getElementById('tblForniture').addEventListener('click', function (e) {
        var btn = e.target.closest('.btn-del');
        if (!btn) return;
        var nome = btn.dataset.file;
        if (!confirm('Eliminare la fornitura "' + nome + '" e tutti i relativi pagamenti?')) return;

        var fd = new FormData();
        fd.append('id', btn.dataset.id);
        fd.append(document.querySelector('meta[name="csrf-param"]').content,
                  document.querySelector('meta[name="csrf-token"]').content);

        fetch('<?= \yii\helpers\Url::to(['/imu/f24-delete']) ?>', { method: 'POST', body: fd })
            .then(function (r) { return r.json(); })
            .then(function (d) {
                if (d.ok) {
                    btn.closest('tr').remove();
                } else {
                    alert('Errore: ' + (d.error || 'sconosciuto'));
                }
            });
    });

    // ── Cerca pagamenti ──
    function cercaPagamenti() {
        var cf      = cfFiltro.value.trim().toUpperCase();
        var anno    = annoFiltro.value;
        var baseUrl = '<?= \yii\helpers\Url::to(['/imu/f24-lista']) ?>';
        var sep     = baseUrl.indexOf('?') !== -1 ? '&' : '?';
        var url     = baseUrl + sep + 'anno=' + anno + (cf ? '&cf=' + encodeURIComponent(cf) : '');

        if (!cf && anno === '0') {
            alert('Inserire un Codice Fiscale oppure selezionare un anno.');
            return;
        }

        bodyPag.innerHTML = '<tr><td colspan="10" class="text-center"><i class="fas fa-spinner fa-spin"></i></td></tr>';

        fetch(url)
            .then(function (r) { return r.json(); })
            .then(function (d) {
                if (!d.ok || !d.rows.length) {
                    bodyPag.innerHTML = '<tr><td colspan="10" class="text-center text-muted py-3">Nessun pagamento trovato.</td></tr>';
                    countPag.textContent = '';
                    return;
                }
                var fmt = function (v) { return Number(v).toLocaleString('it-IT', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); };
                bodyPag.innerHTML = d.rows.map(function (r) {
                    return '<tr>' +
                        '<td class="text-center fw-semibold">' + (r.anno_riferimento || '—') + '</td>' +
                        '<td><code>' + r.codice_fiscale + '</code></td>' +
                        '<td>' + (r.denominazione || '') + '</td>' +
                        '<td class="text-center"><span class="badge bg-secondary">' + r.codice_tributo + '</span></td>' +
                        '<td class="small">' + r.desc_tributo + '</td>' +
                        '<td class="text-center">' + (r.data_riscossione || '—') + '</td>' +
                        '<td class="text-end fw-semibold">' + fmt(r.importo_debito) + '</td>' +
                        '<td class="text-center">' + (r.acconto ? '<i class="fas fa-check text-primary"></i>' : '') + '</td>' +
                        '<td class="text-center">' + (r.saldo   ? '<i class="fas fa-check text-success"></i>' : '') + '</td>' +
                        '<td class="text-center">' + (r.ravvedimento ? '<i class="fas fa-exclamation-circle text-warning"></i>' : '') + '</td>' +
                        '</tr>';
                }).join('');
                countPag.textContent = d.count + ' pagamenti trovati.';
            })
            .catch(function (e) {
                bodyPag.innerHTML = '<tr><td colspan="10" class="text-danger text-center">' + e.message + '</td></tr>';
            });
    }

    btnCerca.addEventListener('click', cercaPagamenti);
    cfFiltro.addEventListener('keydown', function (e) { if (e.key === 'Enter') cercaPagamenti(); });

})();
</script>
