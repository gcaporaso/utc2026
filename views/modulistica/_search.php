<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SearchModulistica */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="modulistica-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'idmodulistica') ?>

    <?= $form->field($model, 'nomefile') ?>

    <?= $form->field($model, 'path') ?>

    <?= $form->field($model, 'descrizione') ?>

    <?= $form->field($model, 'numerorevisione') ?>

    <?php // echo $form->field($model, 'datarevisione') ?>

    <?php // echo $form->field($model, 'tipo') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
