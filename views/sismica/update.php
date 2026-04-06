<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Sismica */

$this->title = 'Modifica Pratica Sismica: ' . $model->sismica_id;
$this->params['breadcrumbs'][] = ['label' => 'Sismica', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->sismica_id, 'url' => ['view', 'id' => $model->sismica_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sismica-update">

    

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
