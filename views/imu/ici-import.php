<?php
/** @var yii\web\View $this */
/** @var int $anno */
/** @var app\models\IciFornitura[] $forniture */

$this->title = 'Variazioni Catastali ICI/IMU';
?>

<div class="container-fluid py-3">

  <div class="d-flex align-items-center mb-3 gap-3 flex-wrap">
    <h4 class="mb-0"><i class="fas fa-exchange-alt text-primary me-2"></i>Variazioni Catastali ICI/IMU</h4>
    <small class="text-muted">Forniture mensili dal Portale dei Comuni — mutazioni di titolarità immobiliare</small>
    <div class="ms-auto d-flex align-items-center gap-2">
      <a href="<?= \yii\helpers\Url::to(['/imu/calcolo']) ?>" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-calculator me-1"></i>Calcolo IMU
      </a>
    </div>
  </div>

  <!-- Upload singolo file -->
  <div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">
      <i class="fas fa-upload me-2"></i>Importa fornitura mensile
    </div>
    <div class="card-body">
      <p class="text-muted small mb-3">
        Caricare il file <strong>.ZIP</strong> scaricato dal Portale dei Comuni (voce "Dati per la gestione dell'ICI")
        oppure il file <strong>.XML</strong> estratto. Ogni fornitura copre un mese di variazioni.
      </p>
      <div class="row g-3 align-items-end">
        <div class="col-md-6">
          <label class="form-label fw-semibold">File fornitura (.ZIP o .XML)</label>
          <input type="file" id="icifile" class="form-control" accept=".zip,.xml">
        </div>
        <div class="col-md-4 d-flex gap-2">
          <button id="btnImporta" class="btn btn-primary">
            <i class="fas fa-upload me-1"></i>Importa
          </button>
          <button id="btnStorici" class="btn btn-outline-secondary" title="Importa tutti i file ZIP in runtime/dati-ici/">
            <i class="fas fa-history me-1"></i>Importa storici
          </button>
          <span id="spinImport" class="d-none ms-1 align-self-center">
            <i class="fas fa-spinner fa-spin text-muted"></i>
          </span>
        </div>
      </div>
      <div id="msgImport" class="mt-3 d-none"></div>
    </div>
  </div>

  <!-- Storico forniture importate -->
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
              <th class="text-center">Periodo</th>
              <th class="text-center">Variazioni</th>
              <th class="text-center">Soggetti</th>
              <th class="text-center">Importato il</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($forniture)): ?>
              <tr><td colspan="6" class="text-center text-muted py-4">Nessuna fornitura importata.</td></tr>
            <?php else: ?>
              <?php foreach ($forniture as $f): ?>
                <tr data-id="<?= $f->id ?>">
                  <td class="small"><code><?= \yii\helpers\Html::encode($f->nome_file) ?></code></td>
                  <td class="text-center">
                    <?php
                      $am = $f->anno_mese ?: '';
                      echo $am && preg_match('/^(\d{4})-(\d{2})$/', $am, $mp)
                          ? $mp[2] . '-' . $mp[1]
                          : ($am ?: '—');
                    ?>
                  </td>
                  <td class="text-center"><?= $f->num_variazioni ?></td>
                  <td class="text-center"><span class="badge bg-info"><?= $f->num_soggetti ?></span></td>
                  <td class="text-center small"><?= $f->importato_il ?></td>
                  <td class="text-center">
                    <button class="btn btn-sm btn-outline-danger btn-del"
                            data-id="<?= $f->id ?>"
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

  <!-- Esplora variazioni -->
  <div class="card shadow-sm">
    <div class="card-header d-flex align-items-center gap-3 flex-wrap">
      <span><i class="fas fa-search me-2"></i>Esplora variazioni</span>
      <small class="text-muted">Ricerca per CF e/o anno per verificare mutazioni di titolarità</small>
      <div class="ms-auto d-flex align-items-center gap-2">
        <input type="text" id="cfFiltro" class="form-control form-control-sm" style="width:175px"
               placeholder="CF (facoltativo)" maxlength="16">
        <select id="annoFiltro" class="form-select form-select-sm" style="width:90px">
          <option value="0">Tutti</option>
          <?php for ($y = date('Y'); $y >= 2021; $y--): ?>
            <option value="<?= $y ?>" <?= $y == $anno ? 'selected' : '' ?>><?= $y ?></option>
          <?php endfor; ?>
        </select>
        <button id="btnCerca" class="btn btn-sm btn-outline-primary">
          <i class="fas fa-search"></i> Cerca
        </button>
      </div>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-sm table-hover mb-0 small" id="tblVariazioni">
          <thead class="table-light">
            <tr>
              <th class="text-center">Data pres.</th>
              <th>Codice Fiscale</th>
              <th>Nominativo</th>
              <th class="text-center">Tipo</th>
              <th>Diritto</th>
              <th class="text-center">Quota</th>
              <th class="text-center">Tip.</th>
              <th class="text-center">Foglio</th>
              <th class="text-center">Numero</th>
              <th class="text-center">Sub</th>
              <th class="text-center">Cat.</th>
              <th class="text-end">Rendita €</th>
              <th class="text-center">Mesi sug.</th>
            </tr>
          </thead>
          <tbody id="bodyVariazioni">
            <tr><td colspan="13" class="text-center text-muted py-3">Inserire un CF o selezionare un anno e premere Cerca.</td></tr>
          </tbody>
        </table>
      </div>
      <div id="countVar" class="text-muted small px-3 py-2"></div>
    </div>
  </div>

</div>

<script>
(function () {
    'use strict';

    var btnImp   = document.getElementById('btnImporta');
    var btnStor  = document.getElementById('btnStorici');
    var fileInp  = document.getElementById('icifile');
    var msgDiv   = document.getElementById('msgImport');
    var spin     = document.getElementById('spinImport');
    var btnCerca = document.getElementById('btnCerca');
    var cfFiltro = document.getElementById('cfFiltro');
    var annoFilt = document.getElementById('annoFiltro');
    var body     = document.getElementById('bodyVariazioni');
    var countDiv = document.getElementById('countVar');

    function csrf() {
        return {
            param: document.querySelector('meta[name="csrf-param"]').content,
            token: document.querySelector('meta[name="csrf-token"]').content,
        };
    }

    function showMsg(ok, html) {
        msgDiv.className = 'mt-3 alert ' + (ok ? 'alert-success' : 'alert-danger');
        msgDiv.innerHTML = '<i class="fas fa-' + (ok ? 'check-circle' : 'exclamation-triangle') + ' me-1"></i>' + html;
        msgDiv.classList.remove('d-none');
    }

    function setLoading(on) {
        spin.classList.toggle('d-none', !on);
        btnImp.disabled = on;
        btnStor.disabled = on;
    }

    // ── Importa singolo file ──
    btnImp.addEventListener('click', function () {
        if (!fileInp.files.length) { alert('Selezionare un file .ZIP o .XML.'); return; }
        var fd = new FormData();
        fd.append('icifile', fileInp.files[0]);
        var c = csrf();
        fd.append(c.param, c.token);
        setLoading(true);
        msgDiv.classList.add('d-none');

        fetch('<?= \yii\helpers\Url::to(['/imu/ici-upload']) ?>', { method: 'POST', body: fd })
            .then(function (r) { return r.json(); })
            .then(function (d) {
                setLoading(false);
                if (d.ok) {
                    showMsg(true, d.msg);
                    setTimeout(function () { location.reload(); }, 1800);
                } else {
                    showMsg(false, d.error || 'Errore sconosciuto.');
                }
            })
            .catch(function (e) {
                setLoading(false);
                showMsg(false, 'Errore di comunicazione: ' + e.message);
            });
    });

    // ── Importa storici ──
    btnStor.addEventListener('click', function () {
        if (!confirm('Importare tutti i file ZIP da runtime/dati-ici/ non ancora importati?\nPuò richiedere qualche minuto.')) return;
        var fd = new FormData();
        var c = csrf();
        fd.append(c.param, c.token);
        setLoading(true);
        msgDiv.classList.add('d-none');

        fetch('<?= \yii\helpers\Url::to(['/imu/ici-import-storici']) ?>', { method: 'POST', body: fd })
            .then(function (r) { return r.json(); })
            .then(function (d) {
                setLoading(false);
                var extra = d.errori && d.errori.length ? '<br><small class="text-warning">Errori: ' + d.errori.join('; ') + '</small>' : '';
                if (d.ok) {
                    showMsg(true, d.msg + extra);
                    setTimeout(function () { location.reload(); }, 2500);
                } else {
                    showMsg(false, (d.error || 'Errore') + extra);
                }
            })
            .catch(function (e) {
                setLoading(false);
                showMsg(false, 'Errore di comunicazione: ' + e.message);
            });
    });

    // ── Elimina fornitura ──
    document.getElementById('tblForniture').addEventListener('click', function (e) {
        var btn = e.target.closest('.btn-del');
        if (!btn) return;
        if (!confirm('Eliminare la fornitura "' + btn.dataset.file + '" e tutte le sue variazioni?')) return;
        var fd = new FormData();
        fd.append('id', btn.dataset.id);
        var c = csrf();
        fd.append(c.param, c.token);

        fetch('<?= \yii\helpers\Url::to(['/imu/ici-delete']) ?>', { method: 'POST', body: fd })
            .then(function (r) { return r.json(); })
            .then(function (d) {
                if (d.ok) { btn.closest('tr').remove(); }
                else { alert('Errore: ' + (d.error || 'sconosciuto')); }
            });
    });

    // ── Cerca variazioni ──
    function cerca() {
        var cf   = cfFiltro.value.trim().toUpperCase();
        var anno = annoFilt.value;
        if (!cf && anno === '0') { alert('Inserire un CF oppure selezionare un anno.'); return; }

        var baseUrl = '<?= \yii\helpers\Url::to(['/imu/ici-lista']) ?>';
        var sep     = baseUrl.indexOf('?') !== -1 ? '&' : '?';
        var url     = baseUrl + sep + 'anno=' + anno + (cf ? '&cf=' + encodeURIComponent(cf) : '');

        body.innerHTML = '<tr><td colspan="13" class="text-center"><i class="fas fa-spinner fa-spin"></i></td></tr>';

        fetch(url).then(function (r) { return r.json(); }).then(function (d) {
            if (!d.ok || !d.rows.length) {
                body.innerHTML = '<tr><td colspan="13" class="text-center text-muted py-3">Nessuna variazione trovata.</td></tr>';
                countDiv.textContent = '';
                return;
            }
            var tipoLabel = function (t) {
                return t === 'A'
                    ? '<span class="badge bg-success">ACQ</span>'
                    : '<span class="badge bg-warning text-dark">CED</span>';
            };
            var tipImm = function (t) {
                return t === 'F' ? '<i class="fas fa-building text-secondary" title="Fabbricato"></i>'
                                 : '<i class="fas fa-tree text-success" title="Terreno"></i>';
            };
            var fmtR = function (v) {
                return v ? Number(v).toLocaleString('it-IT', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '—';
            };
            body.innerHTML = d.rows.map(function (r) {
                return '<tr>' +
                    '<td class="text-center">' + (r.data_pres || '—') + '</td>' +
                    '<td><code>' + r.cf + '</code></td>' +
                    '<td>' + r.nominativo + '</td>' +
                    '<td class="text-center">' + tipoLabel(r.tipo) + '</td>' +
                    '<td class="small">' + (r.diritto || '—') + '</td>' +
                    '<td class="text-center">' + r.quota + '</td>' +
                    '<td class="text-center">' + tipImm(r.tipologia) + '</td>' +
                    '<td class="text-center">' + (r.foglio || '—') + '</td>' +
                    '<td class="text-center">' + (r.numero || '—') + '</td>' +
                    '<td class="text-center">' + (r.subalterno || '—') + '</td>' +
                    '<td class="text-center"><code>' + (r.categoria || '—') + '</code></td>' +
                    '<td class="text-end">' + fmtR(r.rendita) + '</td>' +
                    '<td class="text-center fw-semibold ' + (r.mesi_sug > 0 ? 'text-primary' : 'text-muted') + '">' +
                        r.mesi_sug + ' mesi</td>' +
                    '</tr>';
            }).join('');
            countDiv.textContent = d.count + ' variazioni trovate.';
        }).catch(function (e) {
            body.innerHTML = '<tr><td colspan="13" class="text-danger text-center">' + e.message + '</td></tr>';
        });
    }

    btnCerca.addEventListener('click', cerca);
    cfFiltro.addEventListener('keydown', function (e) { if (e.key === 'Enter') cerca(); });

})();
</script>
