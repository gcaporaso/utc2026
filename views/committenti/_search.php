<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CommittentiSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="committenti-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'committenti_id') ?>

    <?= $form->field($model, 'Cognome') ?>

    <?= $form->field($model, 'Nome') ?>

    <?= $form->field($model, 'DataNascita') ?>

    <?= $form->field($model, 'RegimeGiuridico_id') ?>

    <?php echo $form->field($model, 'NOME_COMPOSTO') ?>

    <?php echo $form->field($model, 'IndirizzoResidenza') ?>

    <?php echo $form->field($model, 'ComuneResidenza') ?>

    <?php // echo $form->field($model, 'ProvinciaResidenza') ?>

    <?php // echo $form->field($model, 'CodiceFiscale') ?>

    <?php // echo $form->field($model, 'PartitaIVA') ?>

    <?php // echo $form->field($model, 'EMAIL') ?>

    <?php // echo $form->field($model, 'PEC') ?>

    <?php // echo $form->field($model, 'Telefono') ?>

    <?php // echo $form->field($model, 'Cellulare') ?>

    <?php // echo $form->field($model, 'Denominazione') ?>

    <?php // echo $form->field($model, 'ComuneNascita') ?>

    <?php // echo $form->field($model, 'ProvinciaNascita') ?>

    <?php // echo $form->field($model, 'NumeroCivicoResidenza') ?>

    <?php // echo $form->field($model, 'CapResidenza') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
