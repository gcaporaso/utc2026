<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TecniciSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tecnici-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'tecnici_id') ?>

    <?= $form->field($model, 'COGNOME') ?>

    <?= $form->field($model, 'NOME') ?>

    <?= $form->field($model, 'COMUNE_NASCITA') ?>

    <?= $form->field($model, 'PROVINCIA_NASCITA') ?>

    <?php // echo $form->field($model, 'DATA_NASCITA') ?>

    <?php // echo $form->field($model, 'ALBO') ?>

    <?php // echo $form->field($model, 'PROVINCIA_ALBO') ?>

    <?php // echo $form->field($model, 'NUMERO_ISCRIZIONE') ?>

    <?php // echo $form->field($model, 'NOME_COMPOSTO') ?>

    <?php // echo $form->field($model, 'INDIRIZZO') ?>

    <?php // echo $form->field($model, 'COMUNE_RESIDENZA') ?>

    <?php // echo $form->field($model, 'PROVINCIA_RESIDENZA') ?>

    <?php // echo $form->field($model, 'CODICE_FISCALE') ?>

    <?php // echo $form->field($model, 'PartitaIVA') ?>

    <?php // echo $form->field($model, 'TELEFONO') ?>

    <?php // echo $form->field($model, 'FAX') ?>

    <?php // echo $form->field($model, 'CELLULARE') ?>

    <?php // echo $form->field($model, 'EMAIL') ?>

    <?php // echo $form->field($model, 'PEC') ?>

    <?php // echo $form->field($model, 'Denominazione') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
