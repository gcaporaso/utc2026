<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Committenti */

$this->title = Yii::t('app', 'Modifica Richiedente: {name}', [
    'name' => $model->committenti_id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Richiedenti'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->committenti_id, 'url' => ['view', 'id' => $model->committenti_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Modifica');
?>
<div class="committenti-update">

<!--    <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
