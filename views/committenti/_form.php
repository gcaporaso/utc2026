<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\FormaGiuridica;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use kartik\datecontrol\Module;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model app\models\Committenti */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card card-secondary col-md-12"  style="margin-top: 5px" >
       <!-- INTESTAZIONE -->
        <div class="card-header" >
                <h3 class="card-title">DATI DEL RICHIEDENTE</h3>
<!--                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                    </button>
                </div>-->
        </div>

    <?php $form = ActiveForm::begin(); ?>
<div class="card-body">    
<div class="row">
    <div class="col-sm-4">
        <?php
        $listaformegiuridiche = ArrayHelper::map(FormaGiuridica::find()->all(), 'idformagiuridica', 'descrizione');
        echo $form->field($model, 'RegimeGiuridico_id')->widget(Select2::classname(), [
                'name' => 'SelFormaGiuridica',
                //'size' => Select2::MEDIUM,
                'data' => $listaformegiuridiche,
                'options' => ['id' => 'lstforme', 'multiple' => false,
                    'placeholder' => 'Seleziona forma giuridica ...',
                ],
                'pluginOptions' => ['allowClear' => false],
            ])->label('Forma Giuridica');
        ?>    
    </div>
</div>
<div class="row">
    
    <div class="col-sm-2" >
        <?= $form->field($model, 'Denominazione')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-2" >
        <?= $form->field($model, 'Cognome')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-2" >
    <?= $form->field($model, 'Nome')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-2">
    <?= $form->field($model, 'DataNascita')->widget(DatePicker::classname(), [
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
<?php // $form->field($model, 'DataNascita')->widget(DateControl::classname(), [
//            'type' => 'date',
//            'ajaxConversion' => true,
//            'autoWidget' => false,
//            //'widgetClass' => 'kartik\date\DatePicker',
//            'displayFormat' => 'php:d-m-Y',
//            'saveFormat' => 'php:Y-m-d',
//            'saveTimezone' => 'UTC',
//            'displayTimezone' => 'Europe/Amsterdam',
////            'saveOptions' => [
////                'label' => 'Input saved as: ',
////                'type' => 'text',
////                'readonly' => true,
////                'class' => 'hint-input text-muted'
////                ],
//            'widgetOptions' => [
//                'options' => [
//                    'class' => 'form-control'
//                ],
//                'clientOptions' => [
//                    'dateFormat' => 'php:d-m-Y'
//                ],
//                'dateFormat' => 'php:d-m-Y'
//            ],
//    'language' => 'it'
//]); ?>    
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'ComuneNascita')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-1">
        <?= $form->field($model, 'ProvinciaNascita')->textInput(['maxlength' => true]) ?>
    </div>
   
</div>    

<div class="row">
        <?php // $form->field($model, 'NOME_COMPOSTO')->textInput(['maxlength' => true]) ?> 
    <div class="col-sm-2" >
        <?= $form->field($model, 'IndirizzoResidenza')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-2" >
        <?= $form->field($model, 'ComuneResidenza')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-2" >
        <?= $form->field($model, 'ProvinciaResidenza')->textInput(['maxlength' => true]) ?>
    </div>    
    <div class="col-sm-1" >
        <?= $form->field($model, 'NumeroCivicoResidenza')->textInput(['maxlength' => true])->label('Numero Civico') ?>
    </div>
    <div class="col-sm-1" >
        <?= $form->field($model, 'CapResidenza')->textInput(['maxlength' => true]) ?>
    </div>
</div>    
    
<div class="row">
    <div class="col-sm-2" >
        <?= $form->field($model, 'CodiceFiscale')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-2" >
        <?= $form->field($model, 'PartitaIVA')->textInput(['maxlength' => true]) ?>
    </div>
</div>
    
<div class="row">
    <div class="col-sm-4" >
    <?= $form->field($model, 'Email')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-4" >
    <?= $form->field($model, 'PEC')->textInput(['maxlength' => true]) ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-2" >
        <?= $form->field($model, 'Telefono')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-2" >
        <?= $form->field($model, 'Cellulare')->textInput() ?>
    </div>
</div>
    
</div>
<div class="card-footer">
           <?= Html::submitButton(Yii::t('app', 'Salva'), ['class' => 'btn btn-success']) ?>
           <?= Html::a('Annulla', ['committenti/index'], ['class'=>'btn btn-primary', 'style'=>'margin-left:20px']) ?>
           <?php ActiveForm::end(); ?>
</div>

    

   

</div>
