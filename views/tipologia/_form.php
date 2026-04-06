<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TipologiaEdilizia */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tipologia-edilizia-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'Categoria')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'DESCRIZIONE')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Normativa')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
