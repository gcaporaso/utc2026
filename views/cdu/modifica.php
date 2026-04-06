<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Edilizia */

$this->title = Yii::t('app', 'Modifica Richiesta CDU: {name}', [
    'name' => $model->idcdu,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'CDU'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Modifica');
?>
<div class="cdu-update">

<!--    <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>