<?php
/** @var yii\web\View $this */
/** @var mdm\admin\models\User $model */

use app\models\Profilo;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Utente: ' . $model->username;
$profilo = Profilo::findOne(['user_id' => $model->id]);
$avatarUrl = Url::to(['/profilo/avatar', 'userId' => $model->id]);
?>
<div class="content-wrapper" style="margin-left:10px!important">
  <section class="content-header">
    <h1><?= Html::encode($model->username) ?> <small>Dettaglio utente</small></h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= Url::to(['/admin/user']) ?>">Utenti</a></li>
      <li class="breadcrumb-item active"><?= Html::encode($model->username) ?></li>
    </ol>
  </section>

  <section class="content">

    <?php foreach (['success' => 'success', 'warning' => 'warning', 'error' => 'danger'] as $type => $cls): ?>
    <?php if (Yii::$app->session->hasFlash($type)): ?>
    <div class="alert alert-<?= $cls ?> alert-dismissible">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      <?= Yii::$app->session->getFlash($type) ?>
    </div>
    <?php endif; ?>
    <?php endforeach; ?>

    <div class="row">

      <!-- Avatar + azioni -->
      <div class="col-md-3">
        <div class="card card-outline card-primary text-center pt-4 pb-3">
          <img src="<?= $avatarUrl ?>" class="img-circle elevation-2 mx-auto"
               style="width:100px;height:100px;object-fit:cover;" alt="Avatar">
          <h5 class="mt-3 mb-0"><?= Html::encode($profilo ? $profilo->getDisplayName() : $model->username) ?></h5>
          <?php if ($profilo && $profilo->ruolo): ?>
          <p class="text-muted" style="font-size:12px;"><?= Html::encode($profilo->ruolo) ?></p>
          <?php endif; ?>
          <div class="mt-2">
            <?= $model->status == 10
                ? '<span class="badge badge-success">Attivo</span>'
                : '<span class="badge badge-secondary">Inattivo</span>' ?>
          </div>
          <div class="mt-3 px-3">
            <a href="<?= Url::to(['/admin/assignment/view', 'id' => $model->id]) ?>"
               class="btn btn-sm btn-warning btn-block">
              <i class="fas fa-user-tag mr-1"></i> Assegna ruoli
            </a>
            <?php if ($model->status != 10): ?>
            <a href="<?= Url::to(['/admin/user/activate', 'id' => $model->id]) ?>"
               class="btn btn-sm btn-success btn-block mt-1"
               data-method="post"
               data-confirm="Attivare l'utente <?= Html::encode($model->username) ?>?">
              <i class="fas fa-check mr-1"></i> Attiva
            </a>
            <?php elseif ($model->id != Yii::$app->user->id): ?>
            <a href="<?= Url::to(['/admin/user/deactivate', 'id' => $model->id]) ?>"
               class="btn btn-sm btn-warning btn-block mt-1"
               data-method="post"
               data-confirm="Disattivare l'utente <?= Html::encode($model->username) ?>? Non potrà più accedere.">
              <i class="fas fa-ban mr-1"></i> Disattiva
            </a>
            <?php endif; ?>
            <?php if ($model->id != Yii::$app->user->id): ?>
            <a href="<?= Url::to(['/admin/user/delete', 'id' => $model->id]) ?>"
               class="btn btn-sm btn-danger btn-block mt-1"
               data-method="post"
               data-confirm="Eliminare l'utente <?= Html::encode($model->username) ?>?">
              <i class="fas fa-trash mr-1"></i> Elimina
            </a>
            <?php endif; ?>
          </div>
        </div>

        <a href="<?= Url::to(['/admin/user']) ?>" class="btn btn-sm btn-outline-secondary btn-block">
          <i class="fas fa-arrow-left mr-1"></i> Torna all'elenco
        </a>
      </div>

      <!-- Dati utente -->
      <div class="col-md-9">
        <div class="card card-outline card-secondary">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-id-card mr-1"></i> Dati account</h3>
          </div>
          <div class="card-body p-0">
            <table class="table table-sm mb-0">
              <tr>
                <td class="text-muted" style="width:30%">Username</td>
                <td><strong><?= Html::encode($model->username) ?></strong></td>
              </tr>
              <tr>
                <td class="text-muted">Email</td>
                <td><?= Html::encode($model->email) ?></td>
              </tr>
              <tr>
                <td class="text-muted">Registrato il</td>
                <td><?= date('d/m/Y H:i', $model->created_at) ?></td>
              </tr>
              <tr>
                <td class="text-muted">Ultimo aggiornamento</td>
                <td><?= date('d/m/Y H:i', $model->updated_at) ?></td>
              </tr>
              <?php if ($profilo): ?>
              <tr><td colspan="2" class="bg-light text-muted" style="font-size:11px;padding:4px 8px;">PROFILO</td></tr>
              <?php if ($profilo->telefono): ?>
              <tr>
                <td class="text-muted">Telefono</td>
                <td><?= Html::encode($profilo->telefono) ?></td>
              </tr>
              <?php endif; ?>
              <?php if ($profilo->cellulare): ?>
              <tr>
                <td class="text-muted">Cellulare</td>
                <td><?= Html::encode($profilo->cellulare) ?></td>
              </tr>
              <?php endif; ?>
              <?php if ($profilo->bio): ?>
              <tr>
                <td class="text-muted">Note</td>
                <td><?= Html::encode($profilo->bio) ?></td>
              </tr>
              <?php endif; ?>
              <?php endif; ?>
            </table>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>
