<?php
/** @var yii\web\View $this */
/** @var app\models\Profilo $profilo */
/** @var mdm\admin\models\User $user */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Il mio profilo';
$saveUrl      = Url::to(['/profilo/save']);
$uploadUrl    = Url::to(['/profilo/upload-avatar']);
$avatarUrl    = Url::to(['/profilo/avatar']);
$csrf         = Yii::$app->request->csrfToken;
?>

<div class="content-wrapper" style="margin-left:10px!important">
  <section class="content-header">
    <h1>Profilo utente <small><?= Html::encode($user->username) ?></small></h1>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="row">

        <!-- ── Colonna sinistra: avatar ─────────────────────────────────── -->
        <div class="col-md-4">
          <div class="card card-outline card-primary">
            <div class="card-body text-center pt-4 pb-4">

              <!-- Avatar corrente -->
              <div id="avatar-wrapper" style="display:inline-block;position:relative;margin-bottom:16px;">
                <img id="avatar-img"
                     src="<?= $avatarUrl ?>"
                     class="img-circle elevation-2"
                     style="width:140px;height:140px;object-fit:cover;"
                     alt="Avatar">
                <button type="button" id="btn-change-avatar"
                        class="btn btn-sm btn-outline-secondary"
                        style="position:absolute;bottom:0;right:0;border-radius:50%;width:32px;height:32px;padding:0;"
                        title="Cambia foto">
                  <i class="fas fa-camera" style="font-size:13px;"></i>
                </button>
              </div>
              <input type="file" id="avatar-file-input" accept="image/*" style="display:none">

              <h5 class="mt-1 mb-0" id="sidebar-display-name">
                <?= Html::encode($profilo->getDisplayName() ?: $user->username) ?>
              </h5>
              <p class="text-muted mt-1" id="sidebar-ruolo" style="font-size:13px;">
                <?= Html::encode($profilo->ruolo ?? '') ?>
              </p>

              <div id="avatar-alert" class="alert d-none mt-2 mb-0 py-2" style="font-size:13px;"></div>
            </div>
            <div class="card-footer text-muted" style="font-size:12px;">
              <i class="fas fa-info-circle mr-1"></i>
              Formati ammessi: JPG, PNG, GIF, WEBP — max 2 MB
            </div>
          </div>

          <!-- Info account (sola lettura) -->
          <div class="card card-outline card-secondary">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-user-circle mr-1"></i> Account</h3>
            </div>
            <div class="card-body p-0">
              <table class="table table-sm mb-0">
                <tr>
                  <td class="text-muted" style="width:40%">Username</td>
                  <td><strong><?= Html::encode($user->username) ?></strong></td>
                </tr>
                <tr>
                  <td class="text-muted">Email</td>
                  <td><?= Html::encode($user->email) ?></td>
                </tr>
                <tr>
                  <td class="text-muted">Registrato il</td>
                  <td><?= date('d/m/Y', $user->created_at) ?></td>
                </tr>
                <tr>
                  <td class="text-muted">Stato</td>
                  <td>
                    <?= $user->status == 10
                        ? '<span class="badge badge-success">Attivo</span>'
                        : '<span class="badge badge-secondary">Inattivo</span>' ?>
                  </td>
                </tr>
              </table>
            </div>
          </div>
        </div>

        <!-- ── Colonna destra: form dati personali ──────────────────────── -->
        <div class="col-md-8">
          <div class="card card-outline card-warning">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-id-card mr-1"></i> Dati personali</h3>
            </div>
            <div class="card-body">

              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Nome</label>
                    <input type="text" id="prof-nome" class="form-control"
                           value="<?= Html::encode($profilo->nome ?? '') ?>"
                           placeholder="es. Giuseppe">
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Cognome</label>
                    <input type="text" id="prof-cognome" class="form-control"
                           value="<?= Html::encode($profilo->cognome ?? '') ?>"
                           placeholder="es. Rossi">
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label>Ruolo / Qualifica</label>
                <input type="text" id="prof-ruolo" class="form-control"
                       value="<?= Html::encode($profilo->ruolo ?? '') ?>"
                       placeholder="es. Responsabile Ufficio Tecnico">
              </div>

              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Telefono</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                      </div>
                      <input type="tel" id="prof-telefono" class="form-control"
                             value="<?= Html::encode($profilo->telefono ?? '') ?>"
                             placeholder="0824 XXXXXX">
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Cellulare</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-mobile-alt"></i></span>
                      </div>
                      <input type="tel" id="prof-cellulare" class="form-control"
                             value="<?= Html::encode($profilo->cellulare ?? '') ?>"
                             placeholder="3XX XXXXXXX">
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label>Note / Bio</label>
                <textarea id="prof-bio" class="form-control" rows="3"
                          placeholder="Breve descrizione, specializzazioni, ecc."><?= Html::encode($profilo->bio ?? '') ?></textarea>
              </div>

              <div id="save-alert" class="alert d-none py-2" style="font-size:13px;"></div>

            </div>
            <div class="card-footer">
              <button type="button" class="btn btn-warning" id="btn-save-profilo">
                <i class="fas fa-save mr-1"></i> Salva modifiche
              </button>
            </div>
          </div>
        </div>

      </div><!-- /.row -->
    </div>
  </section>
</div>

<?php $this->registerJs(<<<JS
$(function () {

    // ── Salva dati profilo ───────────────────────────────────────────────
    $('#btn-save-profilo').on('click', function () {
        var btn = $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Salvataggio…');
        $.ajax({
            url: '{$saveUrl}',
            type: 'POST',
            dataType: 'json',
            data: {
                _csrf:      '{$csrf}',
                nome:       $('#prof-nome').val(),
                cognome:    $('#prof-cognome').val(),
                ruolo:      $('#prof-ruolo').val(),
                telefono:   $('#prof-telefono').val(),
                cellulare:  $('#prof-cellulare').val(),
                bio:        $('#prof-bio').val(),
            },
        }).done(function (r) {
            if (r.ok) {
                var nome    = $.trim($('#prof-nome').val() + ' ' + $('#prof-cognome').val());
                var ruolo   = $('#prof-ruolo').val();
                $('#sidebar-display-name').text(nome || '{$user->username}');
                $('#sidebar-ruolo').text(ruolo);
                // aggiorna anche la sidebar
                $('.user-panel .user-info-name').text(nome || '{$user->username}');
                showAlert('#save-alert', 'success', '<i class="fas fa-check mr-1"></i> Dati salvati.');
            } else {
                showAlert('#save-alert', 'danger', r.error || 'Errore durante il salvataggio.');
            }
        }).fail(function () {
            showAlert('#save-alert', 'danger', 'Errore di comunicazione con il server.');
        }).always(function () {
            btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Salva modifiche');
        });
    });

    // ── Upload avatar ────────────────────────────────────────────────────
    $('#btn-change-avatar').on('click', function () {
        $('#avatar-file-input').click();
    });

    $('#avatar-file-input').on('change', function () {
        var file = this.files[0];
        if (!file) return;

        var formData = new FormData();
        formData.append('_csrf', '{$csrf}');
        formData.append('avatar', file);

        showAlert('#avatar-alert', 'secondary', '<i class="fas fa-spinner fa-spin mr-1"></i> Caricamento…');

        $.ajax({
            url: '{$uploadUrl}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
        }).done(function (r) {
            if (r.ok) {
                // aggiorna anteprima con cache-busting
                var newSrc = r.url + '?t=' + Date.now();
                $('#avatar-img').attr('src', newSrc);
                // aggiorna avatar nella sidebar
                $('.user-panel .img-circle').attr('src', newSrc);
                showAlert('#avatar-alert', 'success', '<i class="fas fa-check mr-1"></i> Foto aggiornata.');
            } else {
                showAlert('#avatar-alert', 'danger', r.error || 'Errore caricamento.');
            }
        }).fail(function () {
            showAlert('#avatar-alert', 'danger', 'Errore di comunicazione con il server.');
        });

        // reset input so re-selecting the same file fires change again
        $(this).val('');
    });

    function showAlert(sel, type, msg) {
        $(sel)
            .removeClass('d-none alert-success alert-danger alert-secondary')
            .addClass('alert alert-' + type)
            .html(msg);
        setTimeout(function () {
            $(sel).addClass('d-none');
        }, 4000);
    }
});
JS
); ?>
