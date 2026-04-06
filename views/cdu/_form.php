<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\TitoloEdilizio;
use app\models\Committenti;
use kartik\select2\Select2;
use kartik\date\DatePicker;


/* @var $this yii\web\View */
/* @var $model app\models\Edilizia */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cdu-form">

    <?php $form = ActiveForm::begin(); ?>
<div class="row">
        <div class="col-sm-2" >
        <?= $form->field($model, 'NumeroProtocollo')->textInput() ?>    
        </div>
        <div class="col-sm-2">
        <?= $form->field($model, 'DataProtocollo')->widget(DatePicker::classname(), [
                'language' => 'it',
                'name' => 'dp_3',
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                'value'=>date("d-m-yyyy"),
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'd-m-yyyy',
                    'todayHighlight'=>true,
                    
        ]])
        ?>
        </div>
</div>

<div class="row">
    <div class="col-sm-7" id='divRich1'>    
     <?php
     $data = ArrayHelper::map(Committenti::find()->all(), 'committenti_id', 'nomeCompleto');
     echo $form->field($model, 'idrich')->widget(Select2::classname(), [
    'data' => $data,
    'options' => ['placeholder' => 'Richiedente ...'],
    'pluginOptions' => [
        'allowClear' => true
    ],])->label('Richiedente'); ?>
    </div>
</div> 
 <div class="row">
     
     <div class="col-sm-1" >
         <?= $form->field($model, 'sez1')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-1" >
    <?= $form->field($model, 'foglio1')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-5" >
    <?= $form->field($model, 'particelle1')->textInput(['maxlength' => true]) ?>
    </div>
 </div> 
 <div class="row">
     
     <div class="col-sm-1" >
         <?= 
         $form->field($model, 'sez2')->textInput(['maxlength' => true])
         ?>
    </div>
    <div class="col-sm-1" >
    <?= $form->field($model, 'foglio2')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-5" >
    <?= $form->field($model, 'particelle2')->textInput(['maxlength' => true]) ?>
    </div>
 </div> 
 <div class="row">
     
     <div class="col-sm-1" >
         <?= 
         $form->field($model, 'sez3')->textInput(['maxlength' => true])
         ?>
    </div>
    <div class="col-sm-1" >
    <?= $form->field($model, 'foglio3')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-5" >
    <?= $form->field($model, 'particelle3')->textInput(['maxlength' => true]) ?>
    </div>
 </div> 
 <div class="row">
     
     <div class="col-sm-1" >
         <?= 
         $form->field($model, 'sez4')->textInput(['maxlength' => true])
         ?>
    </div>
    <div class="col-sm-1" >
    <?= $form->field($model, 'foglio4')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-5" >
    <?= $form->field($model, 'particelle4')->textInput(['maxlength' => true]) ?>
    </div>
 </div> 
<div class="row">
    <div class="col-sm-2" >
    <?php 
     $tipo=[0=>'Privato',1=>'Ente Pubblico'];
         echo $form->field($model, 'tipodestinatario')->widget(Select2::classname(), [
        'data' => $tipo,
        'options' => [
            'placeholder' => 'Destinatario ...',
            'value' => 0,],
        'pluginOptions' => [
        'allowClear' => true
    ],])->label('Tipo Destinatario');       
    ?>
    </div>
    <div class="col-sm-5" >
    <?php 
            $esz=[0=>'Nessuna',1=>'uso Piccola  Proprietà  Contadina art. 21 del D.P.R. n. 642/72'];
         echo $form->field($model, 'esenzione')->widget(Select2::classname(), [
        'data' => $esz,
        'options' => [
            'placeholder' => 'Esenzione ...',
            'value' => 0,],
        'pluginOptions' => [
        'allowClear' => true
    ],])->label('Esenzione Bollo');
    ?>
    </div>
</div>
    <div class="form-group">
        <?= Html::submitButton('Salva', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
