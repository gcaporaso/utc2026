<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SearchSismica */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sismica-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'sismica_id') ?>

    <?= $form->field($model, 'pratica_id') ?>

    <?= $form->field($model, 'DataProtocollo') ?>

    <?= $form->field($model, 'Protocollo') ?>

    <?= $form->field($model, 'DescrizioneLavori') ?>

    <?php // echo $form->field($model, 'committenti_id') ?>

    <?php // echo $form->field($model, 'TipoDenuncia') ?>

    <?php // echo $form->field($model, 'StrutturaPortante') ?>

    <?php // echo $form->field($model, 'PROG_ARCH_ID') ?>

    <?php // echo $form->field($model, 'DD_LL_ARCH_ID') ?>

    <?php // echo $form->field($model, 'PROG_STR_ID') ?>

    <?php // echo $form->field($model, 'DIR_LAV_STR_ID') ?>

    <?php // echo $form->field($model, 'IMPRESA_ID') ?>

    <?php // echo $form->field($model, 'COLLAUDATORE_ID') ?>

    <?php // echo $form->field($model, 'IstruttoreID') ?>

    <?php // echo $form->field($model, 'ISTRUTTORE') ?>

    <?php // echo $form->field($model, 'NumeroAUTORIZZAZIONE') ?>

    <?php // echo $form->field($model, 'DataAUTORIZZAZIONE') ?>

    <?php // echo $form->field($model, 'ImportoContributo') ?>

    <?php // echo $form->field($model, 'DataVersamentoContributo') ?>

    <?php // echo $form->field($model, 'PAGATO_COMMISSIONE') ?>

    <?php // echo $form->field($model, 'TrasmissioneGenioCivile') ?>

    <?php // echo $form->field($model, 'PubblicazioneALBO') ?>

    <?php // echo $form->field($model, 'Ritiro') ?>

    <?php // echo $form->field($model, 'Inizio_Lavori') ?>

    <?php // echo $form->field($model, 'Fine_Lavori') ?>

    <?php // echo $form->field($model, 'Data_Strutture_Ultimate') ?>

    <?php // echo $form->field($model, 'Data_Collaudo') ?>

    <?php // echo $form->field($model, 'TipoTitolo') ?>

    <?php // echo $form->field($model, 'TipoCommittente') ?>

    <?php // echo $form->field($model, 'TipoProcedimento') ?>

    <?php // echo $form->field($model, 'Variante') ?>

    <?php // echo $form->field($model, 'Sopraelevazione') ?>

    <?php // echo $form->field($model, 'Ampliamento') ?>

    <?php // echo $form->field($model, 'Integrazione') ?>

    <?php // echo $form->field($model, 'CatastoTipo') ?>

    <?php // echo $form->field($model, 'CatastoFoglio') ?>

    <?php // echo $form->field($model, 'CatastoParticelle') ?>

    <?php // echo $form->field($model, 'CatastoSub') ?>

    <?php // echo $form->field($model, 'StrutturaAltro') ?>

    <?php // echo $form->field($model, 'ClassificazioneSismica') ?>

    <?php // echo $form->field($model, 'AccelerazioneAg') ?>

    <?php // echo $form->field($model, 'AbitatoConsolidare') ?>

    <?php // echo $form->field($model, 'InteresseStatale') ?>

    <?php // echo $form->field($model, 'InteresseRegionale') ?>

    <?php // echo $form->field($model, 'Articolo65') ?>

    <?php // echo $form->field($model, 'GeologoID') ?>

    <?php // echo $form->field($model, 'DestinazioneDuso') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
