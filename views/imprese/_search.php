<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ImpreseSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="imprese-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'imprese_id') ?>

    <?= $form->field($model, 'RAGIONE_SOCIALE') ?>

    <?= $form->field($model, 'COGNOME') ?>

    <?= $form->field($model, 'NOME') ?>

    <?= $form->field($model, 'DATA_NASCITA') ?>

    <?php // echo $form->field($model, 'PROVINCIA_NASCITA') ?>

    <?php // echo $form->field($model, 'NOME_COMPOSTO') ?>

    <?php // echo $form->field($model, 'INDIRIZZO') ?>

    <?php // echo $form->field($model, 'COMUNE_RESIDENZA') ?>

    <?php // echo $form->field($model, 'PROVINCIA_RESIDENZA') ?>

    <?php // echo $form->field($model, 'CODICE_FISCALE') ?>

    <?php // echo $form->field($model, 'P.IVA') ?>

    <?php // echo $form->field($model, 'EMAIL') ?>

    <?php // echo $form->field($model, 'PEC') ?>

    <?php // echo $form->field($model, 'Cassa_Edile') ?>

    <?php // echo $form->field($model, 'INPS') ?>

    <?php // echo $form->field($model, 'INAIL') ?>

    <?php // echo $form->field($model, 'Telefono') ?>

    <?php // echo $form->field($model, 'Cellulare') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
