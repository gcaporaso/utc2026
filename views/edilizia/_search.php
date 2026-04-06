<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EdiliziaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="edilizia-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'edilizia_id') ?>

    <?= $form->field($model, 'idpratica') ?>

    <?= $form->field($model, 'DataProtocollo') ?>

    <?= $form->field($model, 'NumeroProtocollo') ?>

    <?= $form->field($model, 'id_committente') ?>

    <?php // echo $form->field($model, 'id_titolo') ?>

    <?php // echo $form->field($model, 'DescrizioneIntervento') ?>

    <?php // echo $form->field($model, 'PROGETTISTA_ARC_ID') ?>

    <?php // echo $form->field($model, 'PROGETTISTA_ARCHITETTONICO') ?>

    <?php // echo $form->field($model, 'DIR_LAV_ARCH_ID') ?>

    <?php // echo $form->field($model, 'DIRETTORE_LAVORI_ARCHITETTONICO') ?>

    <?php // echo $form->field($model, 'PROGETTISTA_STR_ID') ?>

    <?php // echo $form->field($model, 'PROGETTISTA_STRUTTURE') ?>

    <?php // echo $form->field($model, 'DIR_LAV_STR_ID') ?>

    <?php // echo $form->field($model, 'DIRETTORE_LAVORI_STRUTTURE') ?>

    <?php // echo $form->field($model, 'IMPRESA_ID') ?>

    <?php // echo $form->field($model, 'IMPRESA') ?>

    <?php // echo $form->field($model, 'CatastoFoglio') ?>

    <?php // echo $form->field($model, 'CatastoParticella') ?>

    <?php // echo $form->field($model, 'CatastoSub') ?>

    <?php // echo $form->field($model, 'ESITO-UTC') ?>

    <?php // echo $form->field($model, 'Data_OK') ?>

    <?php // echo $form->field($model, 'Stato_Pratica_id') ?>

    <?php // echo $form->field($model, 'Latitudine') ?>

    <?php // echo $form->field($model, 'Longitudine') ?>

    <?php // echo $form->field($model, 'AutPaesistica') ?>

    <?php // echo $form->field($model, 'Diritti_AP') ?>

    <?php // echo $form->field($model, 'NumeroTitolo') ?>

    <?php // echo $form->field($model, 'DataTitolo') ?>

    <?php // echo $form->field($model, 'Sanatoria') ?>

    <?php // echo $form->field($model, 'NumeroAutorizzazionePaesaggistica') ?>

    <?php // echo $form->field($model, 'DataAutorizzazionePaesaggistica') ?>

    <?php // echo $form->field($model, 'COMPATIBILITA_PAESISTICA') ?>

    <?php // echo $form->field($model, 'TitoloOneroso') ?>

    <?php // echo $form->field($model, 'ONERI') ?>

    <?php // echo $form->field($model, 'Pagato') ?>

    <?php // echo $form->field($model, 'Data_Inizio_Lavori') ?>

    <?php // echo $form->field($model, 'Data_Fine_Lavori') ?>

    <?php // echo $form->field($model, 'CatastoTipo') ?>

    <?php // echo $form->field($model, 'IndirizzoImmobile') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
