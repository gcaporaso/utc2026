<?php
/** @var yii\web\View $this */
/** @var mdm\admin\models\AuthItem $model */
/** @var mdm\admin\components\ItemController $context */

use mdm\admin\AnimateAsset;
use mdm\admin\models\AuthItem;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\YiiAsset;

$context = $this->context;
$labels  = $context->labels();
$isRole  = ($labels['Item'] === 'Role');
$this->title = $model->name;

AnimateAsset::register($this);
YiiAsset::register($this);
$opts = Json::htmlEncode([
    'items'      => $model->getItems(),
    'users'      => $model->getUsers(),
    'getUserUrl' => Url::to(['get-users', 'id' => $model->name]),
]);
$this->registerJs("var _opts = {$opts};");
$this->registerJs($this->render('@mdm/admin/views/item/_script.js'));
$spin = ' <i class="fas fa-spinner fa-spin" style="display:none"></i>';
?>
<div class="content-wrapper" style="margin-left:10px!important">
  <section class="content-header">
    <h1><?= Html::encode($this->title) ?>
      <small><?= $isRole ? 'Ruolo' : 'Permesso' ?></small>
    </h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="<?= Url::to(['index']) ?>"><?= $isRole ? 'Ruoli' : 'Permessi' ?></a>
      </li>
      <li class="breadcrumb-item active"><?= Html::encode($model->name) ?></li>
    </ol>
  </section>

  <section class="content">
    <div class="row">

      <!-- Info -->
      <div class="col-md-4">
        <div class="card card-outline card-secondary">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-<?= $isRole ? 'shield-alt' : 'key' ?> mr-1"></i>
              Dettagli
            </h3>
          </div>
          <div class="card-body p-0">
            <table class="table table-sm mb-0">
              <tr>
                <td class="text-muted">Nome</td>
                <td><strong><?= Html::encode($model->name) ?></strong></td>
              </tr>
              <tr>
                <td class="text-muted">Descrizione</td>
                <td><?= Html::encode($model->description) ?></td>
              </tr>
            </table>
          </div>
          <div class="card-footer">
            <a href="<?= Url::to(['update', 'id' => $model->name]) ?>"
               class="btn btn-sm btn-primary">
              <i class="fas fa-pencil-alt mr-1"></i> Modifica
            </a>
            <a href="<?= Url::to(['delete', 'id' => $model->name]) ?>"
               class="btn btn-sm btn-danger ml-1"
               data-method="post"
               data-confirm="Eliminare '<?= Html::encode($model->name) ?>'?">
              <i class="fas fa-trash mr-1"></i> Elimina
            </a>
          </div>
        </div>

        <!-- Utenti assegnati -->
        <div class="card card-outline card-info">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-users mr-1"></i> Utenti assegnati</h3>
          </div>
          <div class="card-body p-1">
            <div id="list-users" style="font-size:13px;min-height:30px;"></div>
          </div>
        </div>

        <a href="<?= Url::to(['index']) ?>" class="btn btn-sm btn-outline-secondary btn-block">
          <i class="fas fa-arrow-left mr-1"></i> Torna all'elenco
        </a>
      </div>

      <!-- Figli (ruoli/permessi ereditati) -->
      <div class="col-md-8">
        <div class="card card-outline card-warning">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-exchange-alt mr-1"></i>
              <?= $isRole ? 'Permessi inclusi in questo ruolo' : 'Permessi figli' ?>
            </h3>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-5">
                <label class="text-muted" style="font-size:12px;">DISPONIBILI</label>
                <input class="form-control form-control-sm mb-1 search" data-target="available"
                       placeholder="Cerca…">
                <select multiple size="12" class="form-control list" data-target="available"
                        style="font-size:13px;"></select>
              </div>
              <div class="col-md-2 d-flex flex-column justify-content-center align-items-center" style="gap:8px;">
                <?= Html::a('<i class="fas fa-angle-double-right"></i>' . $spin,
                    ['assign', 'id' => $model->name],
                    ['class' => 'btn btn-success btn-assign', 'data-target' => 'available',
                     'title' => 'Aggiungi']) ?>
                <?= Html::a('<i class="fas fa-angle-double-left"></i>' . $spin,
                    ['remove', 'id' => $model->name],
                    ['class' => 'btn btn-danger btn-assign', 'data-target' => 'assigned',
                     'title' => 'Rimuovi']) ?>
              </div>
              <div class="col-md-5">
                <label class="text-muted" style="font-size:12px;">ASSEGNATI</label>
                <input class="form-control form-control-sm mb-1 search" data-target="assigned"
                       placeholder="Cerca…">
                <select multiple size="12" class="form-control list" data-target="assigned"
                        style="font-size:13px;"></select>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>
