<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Imprese */

$this->title = Yii::t('app', 'Modifica dati impresa n. {name}', [
    'name' => $model->imprese_id,
]);
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Imprese'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->imprese_id, 'url' => ['view', 'id' => $model->imprese_id]];
//$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="imprese-update">

    

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
