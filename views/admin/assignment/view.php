<?php
/** @var yii\web\View $this */
/** @var mdm\admin\models\Assignment $model */
/** @var string $usernameField */
/** @var string $fullnameField */

use app\models\Profilo;
use mdm\admin\AnimateAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\YiiAsset;

$userName = $model->{$usernameField};
if (!empty($fullnameField)) {
    $userName .= ' (' . ArrayHelper::getValue($model, $fullnameField) . ')';
}
$userName = Html::encode($userName);
$this->title = 'Ruoli: ' . $userName;

$profilo  = Profilo::findOne(['user_id' => $model->id]);
$avatarUrl = Url::to(['/profilo/avatar', 'userId' => $model->id]);

AnimateAsset::register($this);
YiiAsset::register($this);
$opts = Json::htmlEncode(['items' => $model->getItems()]);
$this->registerJs("var _opts = {$opts};");
$this->registerJs($this->render('@mdm/admin/views/assignment/_script.js'));
$spin = ' <i class="fas fa-spinner fa-spin" style="display:none"></i>';
?>
<div class="content-wrapper" style="margin-left:10px!important">
  <section class="content-header">
    <h1>Assegnazione Ruoli <small><?= $userName ?></small></h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= Url::to(['/admin/assignment']) ?>">Assegnazioni</a></li>
      <li class="breadcrumb-item active"><?= $userName ?></li>
    </ol>
  </section>

  <section class="content">
    <div class="row mb-3">
      <div class="col-md-2 text-center">
        <img src="<?= $avatarUrl ?>" class="img-circle elevation-1"
             style="width:70px;height:70px;object-fit:cover;" alt="">
        <div class="mt-1" style="font-size:13px;">
          <strong><?= Html::encode($profilo ? $profilo->getDisplayName() : $model->{$usernameField}) ?></strong>
        </div>
        <?php if ($profilo && $profilo->ruolo): ?>
        <div class="text-muted" style="font-size:11px;"><?= Html::encode($profilo->ruolo) ?></div>
        <?php endif; ?>
      </div>
      <div class="col-md-10 d-flex align-items-center">
        <div>
          <a href="<?= Url::to(['/admin/assignment']) ?>" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Torna all'elenco
          </a>
          <a href="<?= Url::to(['/admin/user/view', 'id' => $model->id]) ?>" class="btn btn-sm btn-outline-primary ml-1">
            <i class="fas fa-user mr-1"></i> Scheda utente
          </a>
        </div>
      </div>
    </div>

    <div class="card card-outline card-warning">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-exchange-alt mr-1"></i> Trascina ruoli/permessi tra le colonne</h3>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-5">
            <label class="text-muted" style="font-size:12px;">DISPONIBILI</label>
            <input class="form-control form-control-sm mb-1 search" data-target="available"
                   placeholder="Cerca…">
            <select multiple size="14" class="form-control list" data-target="available"
                    style="font-size:13px;"></select>
          </div>
          <div class="col-md-2 d-flex flex-column justify-content-center align-items-center" style="gap:8px;">
            <?= Html::a('<i class="fas fa-angle-double-right"></i>' . $spin,
                ['assign', 'id' => (string)$model->id],
                ['class' => 'btn btn-success btn-assign', 'data-target' => 'available',
                 'title' => 'Assegna selezionati']) ?>
            <?= Html::a('<i class="fas fa-angle-double-left"></i>' . $spin,
                ['revoke', 'id' => (string)$model->id],
                ['class' => 'btn btn-danger btn-assign', 'data-target' => 'assigned',
                 'title' => 'Rimuovi selezionati']) ?>
          </div>
          <div class="col-md-5">
            <label class="text-muted" style="font-size:12px;">ASSEGNATI</label>
            <input class="form-control form-control-sm mb-1 search" data-target="assigned"
                   placeholder="Cerca…">
            <select multiple size="14" class="form-control list" data-target="assigned"
                    style="font-size:13px;"></select>
          </div>
        </div>
        <p class="text-muted mt-2 mb-0" style="font-size:12px;">
          <i class="fas fa-info-circle mr-1"></i>
          Seleziona uno o più elementi e usa i pulsanti &rsaquo;&rsaquo; / &lsaquo;&lsaquo; per assegnare o revocare.
        </p>
      </div>
    </div>

  </section>
</div>
<?php
// Override glyphicon references in _script.js to use Font Awesome
$this->registerCss('
.btn-assign i.glyphicon-refresh-animate { display:none; }
.btn-assign i.fa-spinner { display:inline-block!important; }
');
?>
