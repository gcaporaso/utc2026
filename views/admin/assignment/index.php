<?php
/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var mdm\admin\models\searchs\Assignment $searchModel */
/** @var string $usernameField */

use app\models\Profilo;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = 'Assegnazioni Ruoli';
?>
<div class="content-wrapper" style="margin-left:10px!important">
  <section class="content-header">
    <h1>Assegnazioni <small>Ruoli e permessi per utente</small></h1>
  </section>

  <section class="content">
    <div class="mb-3">
      <a href="<?= Url::to(['/admin/user']) ?>" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left mr-1"></i> Utenti
      </a>
      <a href="<?= Url::to(['/admin/role']) ?>" class="btn btn-sm btn-outline-secondary ml-1">
        <i class="fas fa-shield-alt mr-1"></i> Ruoli
      </a>
    </div>

    <div class="card card-outline card-warning">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-user-tag mr-1"></i> Assegnazioni per utente</h3>
      </div>
      <div class="card-body p-0">
        <?php Pjax::begin(); ?>
        <table class="table table-sm table-hover mb-0">
          <thead class="thead-light">
            <tr>
              <th style="width:50px"></th>
              <th>Username</th>
              <th>Nome</th>
              <th>Email</th>
              <th style="width:80px"></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($dataProvider->getModels() as $model):
              $profilo = Profilo::findOne(['user_id' => $model->id]);
              $avatarUrl = Url::to(['/profilo/avatar', 'userId' => $model->id]);
            ?>
            <tr>
              <td>
                <img src="<?= $avatarUrl ?>" class="img-circle"
                     style="width:30px;height:30px;object-fit:cover;" alt="">
              </td>
              <td><strong><?= Html::encode($model->{$usernameField}) ?></strong></td>
              <td><?= Html::encode($profilo ? $profilo->getDisplayName() : '') ?></td>
              <td><?= Html::encode($model->email ?? '') ?></td>
              <td>
                <a href="<?= Url::to(['/admin/assignment/view', 'id' => $model->id]) ?>"
                   class="btn btn-xs btn-warning">
                  <i class="fas fa-user-tag"></i> Ruoli
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <?php Pjax::end(); ?>
      </div>
    </div>

  </section>
</div>
