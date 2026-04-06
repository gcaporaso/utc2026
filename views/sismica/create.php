<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Sismica */

$this->title = 'Nuova Pratica Sismica';
$this->params['breadcrumbs'][] = ['label' => 'Sismica', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sismica-create">

   

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
