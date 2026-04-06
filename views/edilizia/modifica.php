<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Edilizia */

$this->title = Yii::t('app', 'Modifica Pratica: {name}', [
    'name' => $model->edilizia_id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pratiche'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->edilizia_id, 'url' => ['view', 'id' => $model->edilizia_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Modifica');
?>
<div class="edilizia-update">

<!--    <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form_update', [
        'model' => $model,
    ]) ?>

</div>
