<?php
/** @var yii\web\View $this */
/** @var int $anno */
/** @var app\models\ImuAliquota[] $aliquote */
/** @var app\models\ImuCoefficiente[] $coeffs */
/** @var int[] $anniList */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'IMU — Parametri ' . $anno;

$tipiLabels = app\models\ImuAliquota::tipiLabels();
?>

<div class="content-wrapper" style="margin-left:10px!important">
  <section class="content-header">
    <h1>Parametri IMU <small>Imposta Municipale Propria</small></h1>
  </section>

  <section class="content">

    <!-- Selezione anno -->
    <div class="card card-outline card-secondary mb-3">
      <div class="card-body py-2">
        <form method="GET" class="form-inline">
          <input type="hidden" name="r" value="imu/index">
          <label class="mr-2">Anno di riferimento:</label>
          <select name="anno" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
            <?php foreach ($anniList as $a): ?>
              <option value="<?= $a ?>" <?= $a == $anno ? 'selected' : '' ?>><?= $a ?></option>
            <?php endforeach; ?>
          </select>
          <button type="button" class="btn btn-sm btn-outline-primary ml-3" data-toggle="modal" data-target="#modalCopiaAnno">
            <i class="fas fa-copy"></i> Copia anno…
          </button>
        </form>
      </div>
    </div>

    <div class="row">

      <!-- ===== ALIQUOTE ===== -->
      <div class="col-lg-7">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-percent"></i> Aliquote <?= $anno ?></h3>
            <div class="card-tools">
              <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#modalAliquota" data-id="0">
                <i class="fas fa-plus"></i> Nuova aliquota
              </button>
            </div>
          </div>
          <div class="card-body p-0">
            <table class="table table-sm table-hover table-striped mb-0" id="tbl-aliquote">
              <thead class="thead-light">
                <tr>
                  <th>Tipo immobile</th>
                  <th class="text-right">Aliquota ‰</th>
                  <th class="text-right">Detrazione €</th>
                  <th>Attiva</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($aliquote as $al): ?>
                <tr id="alq-row-<?= $al->id ?>">
                  <td>
                    <strong><?= Html::encode($tipiLabels[$al->tipo] ?? $al->tipo) ?></strong>
                    <?php if ($al->descrizione): ?>
                      <br><small class="text-muted"><?= Html::encode($al->descrizione) ?></small>
                    <?php endif; ?>
                  </td>
                  <td class="text-right"><?= number_format($al->aliquota * 1000, 2) ?></td>
                  <td class="text-right"><?= number_format($al->detrazione, 2) ?></td>
                  <td><?= $al->attiva ? '<span class="badge badge-success">Sì</span>' : '<span class="badge badge-secondary">No</span>' ?></td>
                  <td style="white-space:nowrap">
                    <button class="btn btn-xs btn-outline-primary btn-edit-alq"
                            data-id="<?= $al->id ?>"
                            data-anno="<?= $al->anno ?>"
                            data-tipo="<?= Html::encode($al->tipo) ?>"
                            data-descrizione="<?= Html::encode($al->descrizione) ?>"
                            data-aliquota="<?= $al->aliquota ?>"
                            data-detrazione="<?= $al->detrazione ?>"
                            data-attiva="<?= $al->attiva ?>"
                            data-note="<?= Html::encode($al->note ?? '') ?>">
                      <i class="fas fa-pencil-alt"></i>
                    </button>
                    <button class="btn btn-xs btn-outline-danger btn-del-alq" data-id="<?= $al->id ?>">
                      <i class="fas fa-trash"></i>
                    </button>
                  </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($aliquote)): ?>
                <tr><td colspan="5" class="text-center text-muted py-3">Nessuna aliquota per l'anno <?= $anno ?>. Usa "Copia anno" per importare da un anno precedente.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- ===== COEFFICIENTI ===== -->
      <div class="col-lg-5">
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-calculator"></i> Coefficienti catastali</h3>
            <small class="ml-2 text-light">(art. 13, DL 201/2011)</small>
          </div>
          <div class="card-body p-0">
            <table class="table table-sm table-hover table-striped mb-0">
              <thead class="thead-light">
                <tr><th>Categoria</th><th class="text-right">Coefficiente</th><th style="max-width:220px">Descrizione</th><th></th></tr>
              </thead>
              <tbody>
                <?php foreach ($coeffs as $c): ?>
                <tr id="coeff-row-<?= $c->id ?>">
                  <td><strong><?= Html::encode($c->categoria) ?></strong></td>
                  <td class="text-right coeff-val-<?= $c->id ?>"><?= $c->coefficiente ?></td>
                  <td><small><?= Html::encode($c->descrizione ?? '') ?></small></td>
                  <td>
                    <button class="btn btn-xs btn-outline-primary btn-edit-coeff"
                            data-id="<?= $c->id ?>"
                            data-cat="<?= Html::encode($c->categoria) ?>"
                            data-val="<?= $c->coefficiente ?>">
                      <i class="fas fa-pencil-alt"></i>
                    </button>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Formula riepilogativa -->
        <div class="card card-outline card-secondary">
          <div class="card-header"><h3 class="card-title"><i class="fas fa-info-circle"></i> Formula IMU</h3></div>
          <div class="card-body">
            <code style="font-size:12px;line-height:1.8">
              Rendita rivalutata = Rendita × 1,05<br>
              Base imponibile = Rendita rivalutata × Coefficiente<br>
              IMU annuale = Base imponibile × Aliquota<br>
              IMU acconto (giugno) = IMU annuale × 50%<br>
              IMU saldo (dicembre) = IMU annuale − acconto
            </code>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>

<!-- Modal aliquota -->
<div class="modal fade" id="modalAliquota" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><i class="fas fa-percent"></i> <span id="modal-alq-title">Nuova aliquota</span></h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="alq-id">
        <div class="form-group">
          <label>Anno</label>
          <input type="number" id="alq-anno" class="form-control" value="<?= $anno ?>" min="2000" max="2100">
        </div>
        <div class="form-group">
          <label>Tipo immobile</label>
          <select id="alq-tipo" class="form-control">
            <?php foreach ($tipiLabels as $key => $lbl): ?>
              <option value="<?= $key ?>"><?= $lbl ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Descrizione estesa</label>
          <input type="text" id="alq-descrizione" class="form-control" placeholder="Es. Abitazioni locate a canone concordato">
        </div>
        <div class="form-row">
          <div class="form-group col-6">
            <label>Aliquota ‰ (per mille)</label>
            <input type="number" id="alq-aliquota-permille" class="form-control" step="0.1" min="0" max="10" placeholder="es. 7.6">
            <small class="text-muted">Inserire in ‰ — verrà convertita automaticamente</small>
          </div>
          <div class="form-group col-6">
            <label>Detrazione annuale (€)</label>
            <input type="number" id="alq-detrazione" class="form-control" step="0.01" min="0" value="0">
          </div>
        </div>
        <div class="form-group">
          <label>Note</label>
          <textarea id="alq-note" class="form-control" rows="2"></textarea>
        </div>
        <div class="form-check">
          <input type="checkbox" id="alq-attiva" class="form-check-input" checked>
          <label for="alq-attiva" class="form-check-label">Aliquota attiva</label>
        </div>
        <div id="alq-alert" class="alert d-none mt-2"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
        <button type="button" class="btn btn-primary" id="btn-save-alq"><i class="fas fa-save"></i> Salva</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal copia anno -->
<div class="modal fade" id="modalCopiaAnno" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header bg-secondary text-white">
        <h5 class="modal-title">Copia aliquote da anno</h5>
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
        <button type="button" class="btn btn-primary" id="btn-copia-anno"><i class="fas fa-copy"></i> Copia</button>
      </div>
    </div>
  </div>
</div>

<?php
$saveAlqUrl   = \yii\helpers\Url::to(['/imu/save-aliquota']);
$delAlqUrl    = \yii\helpers\Url::to(['/imu/delete-aliquota']);
$saveCoeffUrl = \yii\helpers\Url::to(['/imu/save-coeff']);
$copiaUrl     = \yii\helpers\Url::to(['/imu/copia-anno']);
$csrfToken    = Yii::$app->request->csrfToken;

$this->registerJs(<<<JS
$(function () {

    // ---- Apri modal per NUOVA aliquota ----
    $('[data-target="#modalAliquota"][data-id="0"]').on('click', function () {
        $('#alq-id').val('');
        $('#modal-alq-title').text('Nuova aliquota');
        $('#alq-anno').val({$anno});
        $('#alq-tipo').val('altri_immobili');
        $('#alq-descrizione').val('');
        $('#alq-aliquota-permille').val('');
        $('#alq-detrazione').val('0');
        $('#alq-note').val('');
        $('#alq-attiva').prop('checked', true);
        $('#alq-alert').addClass('d-none').text('');
    });

    // ---- Apri modal per MODIFICA aliquota ----
    $(document).on('click', '.btn-edit-alq', function () {
        var b = $(this);
        $('#alq-id').val(b.data('id'));
        $('#modal-alq-title').text('Modifica aliquota #' + b.data('id'));
        $('#alq-anno').val(b.data('anno'));
        $('#alq-tipo').val(b.data('tipo'));
        $('#alq-descrizione').val(b.data('descrizione'));
        $('#alq-aliquota-permille').val((parseFloat(b.data('aliquota')) * 1000).toFixed(2));
        $('#alq-detrazione').val(b.data('detrazione'));
        $('#alq-note').val(b.data('note'));
        $('#alq-attiva').prop('checked', b.data('attiva') == 1);
        $('#alq-alert').addClass('d-none').text('');
        $('#modalAliquota').modal('show');
    });

    // ---- Salva aliquota ----
    $('#btn-save-alq').on('click', function () {
        var permille = parseFloat($('#alq-aliquota-permille').val()) || 0;
        $.ajax({
            url: '{$saveAlqUrl}',
            type: 'POST',
            dataType: 'json',
            data: {
                _csrf:        '{$csrfToken}',
                id:           $('#alq-id').val(),
                anno:         $('#alq-anno').val(),
                tipo:         $('#alq-tipo').val(),
                descrizione:  $('#alq-descrizione').val(),
                aliquota:     (permille / 1000).toFixed(6),
                detrazione:   $('#alq-detrazione').val(),
                note:         $('#alq-note').val(),
                attiva:       $('#alq-attiva').is(':checked') ? 1 : 0,
            },
        }).done(function (r) {
            if (r.ok) {
                location.reload();
            } else {
                $('#alq-alert').removeClass('d-none alert-success').addClass('alert-danger').text(r.error || 'Errore.');
            }
        });
    });

    // ---- Elimina aliquota ----
    $(document).on('click', '.btn-del-alq', function () {
        if (!confirm('Eliminare questa aliquota?')) return;
        var id = $(this).data('id');
        $.post('{$delAlqUrl}', { _csrf: '{$csrfToken}', id: id }, function (r) {
            if (r.ok) { $('#alq-row-' + id).remove(); }
        }, 'json');
    });

    // ---- Modifica coefficiente (inline) ----
    $(document).on('click', '.btn-edit-coeff', function () {
        var b = $(this);
        var id  = b.data('id');
        var old = parseInt($('.coeff-val-' + id).text());
        var nv  = prompt('Nuovo coefficiente per ' + b.data('cat') + ':', old);
        if (nv === null || isNaN(parseInt(nv))) return;
        $.post('{$saveCoeffUrl}', { _csrf: '{$csrfToken}', id: id, coefficiente: parseInt(nv) }, function (r) {
            if (r.ok) { $('.coeff-val-' + id).text(parseInt(nv)); }
        }, 'json');
    });

    // ---- Copia anno ----
    $('#btn-copia-anno').on('click', function () {
        $.post('{$copiaUrl}', {
            _csrf:     '{$csrfToken}',
            anno_src:  $('#copia-src').val(),
            anno_dst:  $('#copia-dst').val(),
        }, function (r) {
            if (r.ok) {
                $('#copia-alert').removeClass('d-none alert-danger').addClass('alert-success').text('Aliquote copiate. Ricarico…');
                setTimeout(function () { location.href = '?r=imu/index&anno=' + $('#copia-dst').val(); }, 1000);
            } else {
                $('#copia-alert').removeClass('d-none alert-success').addClass('alert-danger').text(r.error || 'Errore.');
            }
        }, 'json');
    });
});
JS
); ?>
