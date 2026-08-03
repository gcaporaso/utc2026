<?php
/** @var yii\web\View $this */
/** @var int $anno */
/** @var app\models\ImuAreaEdificabile[] $aree */
/** @var int[] $anniList */
/** @var array<string,string> $zoneList */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Aree Edificabili — Valori Unitari ' . $anno;

$saveUrl   = Url::to(['/imu/save-area']);
$deleteUrl = Url::to(['/imu/delete-area']);
$copiaUrl  = Url::to(['/imu/copia-area-anno']);
$csrf      = Yii::$app->request->csrfToken;
?>

<div class="content-wrapper" style="margin-left:10px!important">
  <section class="content-header">
    <h1>Aree Edificabili <small>Valori unitari €/mq per zona PRG</small></h1>
  </section>

  <section class="content">

    <!-- Selezione anno -->
    <div class="card card-outline card-secondary mb-3">
      <div class="card-body py-2">
        <form method="GET" class="form-inline">
          <input type="hidden" name="r" value="imu/aree-edificabili">
          <label class="mr-2">Anno di riferimento:</label>
          <select name="anno" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
            <?php foreach ($anniList as $a): ?>
              <option value="<?= $a ?>" <?= $a == $anno ? 'selected' : '' ?>><?= $a ?></option>
            <?php endforeach; ?>
            <?php if (!in_array($anno, $anniList)): ?>
              <option value="<?= $anno ?>" selected><?= $anno ?></option>
            <?php endif; ?>
          </select>
          <button type="button" class="btn btn-sm btn-outline-primary ml-3"
                  data-toggle="modal" data-target="#modalCopiaAnno">
            <i class="fas fa-copy"></i> Copia anno…
          </button>
        </form>
      </div>
    </div>

    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-map-marked-alt"></i> Valori unitari €/mq — <?= $anno ?></h3>
        <div class="card-tools">
          <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#modalArea" data-id="0">
            <i class="fas fa-plus"></i> Nuova zona
          </button>
        </div>
      </div>
      <div class="card-body p-0">
        <table class="table table-sm table-hover table-striped mb-0" id="tbl-aree">
          <thead class="thead-light">
            <tr>
              <th style="width:120px">Zona PRG</th>
              <th>Descrizione</th>
              <th class="text-right" style="width:140px">Valore unitario €/mq</th>
              <th style="width:60px">Attiva</th>
              <th style="width:60px"></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($aree as $ar): ?>
            <tr id="area-row-<?= $ar->id ?>">
              <td><strong><?= Html::encode($ar->zona) ?></strong></td>
              <td>
                <?= Html::encode($ar->descrizione) ?>
                <?php if ($ar->note): ?>
                  <small class="text-muted ml-1"><?= Html::encode($ar->note) ?></small>
                <?php endif; ?>
              </td>
              <td class="text-right">
                <strong>€ <?= number_format($ar->valore_mq, 2, ',', '.') ?></strong>
              </td>
              <td>
                <?= $ar->attiva
                    ? '<span class="badge badge-success">Sì</span>'
                    : '<span class="badge badge-secondary">No</span>' ?>
              </td>
              <td style="white-space:nowrap">
                <button class="btn btn-xs btn-outline-primary btn-edit-area"
                        data-id="<?= $ar->id ?>"
                        data-anno="<?= $ar->anno ?>"
                        data-zona="<?= Html::encode($ar->zona) ?>"
                        data-descrizione="<?= Html::encode($ar->descrizione) ?>"
                        data-valore="<?= $ar->valore_mq ?>"
                        data-attiva="<?= $ar->attiva ?>"
                        data-note="<?= Html::encode($ar->note ?? '') ?>">
                  <i class="fas fa-pencil-alt"></i>
                </button>
                <button class="btn btn-xs btn-outline-danger btn-del-area" data-id="<?= $ar->id ?>">
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($aree)): ?>
            <tr>
              <td colspan="5" class="text-center text-muted py-4">
                Nessun valore per l'anno <?= $anno ?>.
                Inserire le zone o usare "Copia anno" per importare da un anno precedente.
              </td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Info formula -->
    <div class="card card-outline card-secondary">
      <div class="card-body py-2">
        <i class="fas fa-info-circle text-info mr-1"></i>
        <strong>Come funziona:</strong>
        Il valore unitario €/mq viene moltiplicato per la consistenza (mq edificabili) dell'area
        ricadente nella zona per ottenere il <em>valore venale in commercio</em>,
        base imponibile IMU per le aree fabbricabili (art. 5 c.5 D.Lgs. 504/1992).
        <br>
        <small class="text-muted">
          Il sistema utilizza automaticamente questo valore nella pagina Calcolo IMU quando
          individua terreni edificabili intestati al contribuente.
        </small>
      </div>
    </div>

  </section>
</div>

<!-- Modal aggiungi/modifica zona -->
<div class="modal fade" id="modalArea" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title"><i class="fas fa-map-marked-alt"></i>
          <span id="modal-area-title">Nuova zona edificabile</span></h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="area-id">
        <div class="form-group">
          <label>Anno</label>
          <input type="number" id="area-anno" class="form-control" value="<?= $anno ?>" min="2000" max="2100">
        </div>
        <div class="form-group">
          <label>Zona PRG</label>
          <select id="area-zona" class="form-control">
            <?php foreach ($zoneList as $codice => $etichetta): ?>
              <option value="<?= Html::encode($codice) ?>"><?= Html::encode($etichetta) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Descrizione aggiuntiva</label>
          <input type="text" id="area-descrizione" class="form-control"
                 placeholder="Es. Zona residenziale di espansione">
        </div>
        <div class="form-group">
          <label>Valore unitario (€/mq)</label>
          <div class="input-group">
            <div class="input-group-prepend"><span class="input-group-text">€</span></div>
            <input type="number" id="area-valore" class="form-control"
                   step="0.01" min="0" placeholder="es. 50.00">
            <div class="input-group-append"><span class="input-group-text">/mq</span></div>
          </div>
          <small class="text-muted">Valore venale medio deliberato dal Comune per la zona</small>
        </div>
        <div class="form-group">
          <label>Note</label>
          <textarea id="area-note" class="form-control" rows="2"
                    placeholder="Es. Delibera di Giunta n. XX del GG/MM/AAAA"></textarea>
        </div>
        <div class="form-check">
          <input type="checkbox" id="area-attiva" class="form-check-input" checked>
          <label for="area-attiva" class="form-check-label">Valore attivo</label>
        </div>
        <div id="area-alert" class="alert d-none mt-2"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
        <button type="button" class="btn btn-warning" id="btn-save-area">
          <i class="fas fa-save"></i> Salva
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Modal copia anno -->
<div class="modal fade" id="modalCopiaAnno" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header bg-secondary text-white">
        <h5 class="modal-title">Copia valori da anno</h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Anno sorgente</label>
          <select id="copia-src" class="form-control">
            <?php foreach ($anniList as $a): ?>
              <option value="<?= $a ?>"><?= $a ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Anno destinazione</label>
          <input type="number" id="copia-dst" class="form-control" value="<?= $anno + 1 ?>">
        </div>
        <div id="copia-alert" class="alert d-none"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
        <button type="button" class="btn btn-primary" id="btn-copia-anno">
          <i class="fas fa-copy"></i> Copia
        </button>
      </div>
    </div>
  </div>
</div>

<?php
$this->registerJs(<<<JS
$(function () {

    // ── Nuova zona ──────────────────────────────────────────────────────────
    $('[data-target="#modalArea"][data-id="0"]').on('click', function () {
        $('#area-id').val('');
        $('#modal-area-title').text('Nuova zona edificabile');
        $('#area-anno').val({$anno});
        $('#area-zona').val($('#area-zona option:first').val());
        $('#area-descrizione').val('');
        $('#area-valore').val('');
        $('#area-note').val('');
        $('#area-attiva').prop('checked', true);
        $('#area-alert').addClass('d-none');
    });

    // ── Modifica zona ────────────────────────────────────────────────────────
    $(document).on('click', '.btn-edit-area', function () {
        var b = $(this);
        $('#area-id').val(b.data('id'));
        $('#modal-area-title').text('Modifica zona — ' + b.data('zona'));
        $('#area-anno').val(b.data('anno'));
        $('#area-zona').val(b.data('zona'));
        $('#area-descrizione').val(b.data('descrizione'));
        $('#area-valore').val(parseFloat(b.data('valore')).toFixed(2));
        $('#area-note').val(b.data('note'));
        $('#area-attiva').prop('checked', b.data('attiva') == 1);
        $('#area-alert').addClass('d-none');
        $('#modalArea').modal('show');
    });

    // ── Salva ────────────────────────────────────────────────────────────────
    $('#btn-save-area').on('click', function () {
        $.ajax({
            url: '{$saveUrl}',
            type: 'POST',
            dataType: 'json',
            data: {
                _csrf:       '{$csrf}',
                id:          $('#area-id').val(),
                anno:        $('#area-anno').val(),
                zona:        $('#area-zona').val(),
                descrizione: $('#area-descrizione').val(),
                valore_mq:   $('#area-valore').val(),
                note:        $('#area-note').val(),
                attiva:      $('#area-attiva').is(':checked') ? 1 : 0,
            },
        }).done(function (r) {
            if (r.ok) { location.reload(); }
            else { $('#area-alert').removeClass('d-none alert-success').addClass('alert alert-danger').text(r.error || 'Errore.'); }
        });
    });

    // ── Elimina ──────────────────────────────────────────────────────────────
    $(document).on('click', '.btn-del-area', function () {
        if (!confirm('Eliminare questo valore?')) return;
        var id = $(this).data('id');
        $.post('{$deleteUrl}', { _csrf: '{$csrf}', id: id }, function (r) {
            if (r.ok) { $('#area-row-' + id).remove(); }
        }, 'json');
    });

    // ── Copia anno ───────────────────────────────────────────────────────────
    $('#btn-copia-anno').on('click', function () {
        $.post('{$copiaUrl}', {
            _csrf:    '{$csrf}',
            anno_src: $('#copia-src').val(),
            anno_dst: $('#copia-dst').val(),
        }, function (r) {
            if (r.ok) {
                $('#copia-alert').removeClass('d-none alert-danger').addClass('alert alert-success').text('Valori copiati. Ricarico…');
                setTimeout(function () { location.href = '?r=imu/aree-edificabili&anno=' + $('#copia-dst').val(); }, 1000);
            } else {
                $('#copia-alert').removeClass('d-none alert-success').addClass('alert alert-danger').text(r.error || 'Errore.');
            }
        }, 'json');
    });
});
JS
); ?>
