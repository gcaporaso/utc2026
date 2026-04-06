<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ComponentiSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="componenti-commissioni-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'idcomponenti_commissioni') ?>

    <?= $form->field($model, 'Cognome') ?>

    <?= $form->field($model, 'Nome') ?>

    <?= $form->field($model, 'Tipologia') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
