<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TipologiaEdilizia */

$this->title = Yii::t('app', 'Update Tipologia Edilizia: {name}', [
    'name' => $model->tipologia_id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tipologia Edilizias'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->tipologia_id, 'url' => ['view', 'id' => $model->tipologia_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tipologia-edilizia-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
