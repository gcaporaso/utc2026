<?php
/** @var yii\web\View $this */
/** @var mdm\admin\models\searchs\Usersearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

use app\models\Profilo;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Gestione Utenti';
?>
<div class="content-wrapper" style="margin-left:10px!important">
  <section class="content-header">
    <h1>Gestione Utenti <small>Elenco degli utenti del sistema</small></h1>
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

    <!-- Barra azioni -->
    <div class="mb-3 d-flex justify-content-between align-items-center">
      <a href="<?= Url::to(['/admin/user/signup']) ?>" class="btn btn-success btn-sm">
        <i class="fas fa-user-plus mr-1"></i> Nuovo utente
      </a>
      <div class="d-flex gap-2">
        <a href="<?= Url::to(['/admin/assignment']) ?>" class="btn btn-outline-primary btn-sm ml-2">
          <i class="fas fa-user-tag mr-1"></i> Assegnazioni
        </a>
        <a href="<?= Url::to(['/admin/role']) ?>" class="btn btn-outline-secondary btn-sm ml-2">
          <i class="fas fa-shield-alt mr-1"></i> Ruoli
        </a>
        <a href="<?= Url::to(['/admin/permission']) ?>" class="btn btn-outline-secondary btn-sm ml-2">
          <i class="fas fa-key mr-1"></i> Permessi
        </a>
      </div>
    </div>

    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-users mr-1"></i> Utenti registrati</h3>
      </div>
      <div class="card-body p-0">
        <table class="table table-sm table-hover mb-0">
          <thead class="thead-light">
            <tr>
              <th style="width:50px">#</th>
              <th style="width:50px"></th>
              <th>Username</th>
              <th>Nome Completo</th>
              <th>Email</th>
              <th>Registrato</th>
              <th style="width:80px">Stato</th>
              <th style="width:100px"></th>
            </tr>
          </thead>
          <tbody>
            <?php
            $models = $dataProvider->getModels();
            $i = 0;
            foreach ($models as $user):
                $i++;
                $profilo = Profilo::findOne(['user_id' => $user->id]);
                $displayName = $profilo ? $profilo->getDisplayName() : '';
                $avatarUrl = Url::to(['/profilo/avatar', 'userId' => $user->id]);
            ?>
            <tr>
              <td class="text-muted"><?= $i ?></td>
              <td>
                <img src="<?= $avatarUrl ?>" class="img-circle"
                     style="width:32px;height:32px;object-fit:cover;" alt="">
              </td>
              <td>
                <strong><?= Html::encode($user->username) ?></strong>
              </td>
              <td><?= Html::encode($displayName) ?></td>
              <td>
                <a href="mailto:<?= Html::encode($user->email) ?>">
                  <?= Html::encode($user->email) ?>
                </a>
              </td>
              <td><?= date('d/m/Y', $user->created_at) ?></td>
              <td>
                <?= $user->status == 10
                    ? '<span class="badge badge-success">Attivo</span>'
                    : '<span class="badge badge-secondary">Inattivo</span>' ?>
              </td>
              <td style="white-space:nowrap">
                <a href="<?= Url::to(['/admin/user/view', 'id' => $user->id]) ?>"
                   class="btn btn-xs btn-outline-primary" title="Dettaglio">
                  <i class="fas fa-eye"></i>
                </a>
                <a href="<?= Url::to(['/admin/assignment/view', 'id' => $user->id]) ?>"
                   class="btn btn-xs btn-outline-warning" title="Assegna ruoli">
                  <i class="fas fa-user-tag"></i>
                </a>
                <?php if ($user->status != 10): ?>
                <a href="<?= Url::to(['/admin/user/activate', 'id' => $user->id]) ?>"
                   class="btn btn-xs btn-outline-success" title="Attiva"
                   data-method="post"
                   data-confirm="Attivare l'utente <?= Html::encode($user->username) ?>?">
                  <i class="fas fa-check"></i>
                </a>
                <?php elseif ($user->id != Yii::$app->user->id): ?>
                <a href="<?= Url::to(['/admin/user/deactivate', 'id' => $user->id]) ?>"
                   class="btn btn-xs btn-outline-warning" title="Disattiva"
                   data-method="post"
                   data-confirm="Disattivare l'utente <?= Html::encode($user->username) ?>? Non potrà più accedere.">
                  <i class="fas fa-ban"></i>
                </a>
                <?php endif; ?>
                <?php if ($user->id != Yii::$app->user->id): ?>
                <a href="<?= Url::to(['/admin/user/delete', 'id' => $user->id]) ?>"
                   class="btn btn-xs btn-outline-danger" title="Elimina"
                   data-method="post"
                   data-confirm="Eliminare l'utente <?= Html::encode($user->username) ?>?">
                  <i class="fas fa-trash"></i>
                </a>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($models)): ?>
            <tr><td colspan="8" class="text-center text-muted py-4">Nessun utente trovato.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </section>
</div>
