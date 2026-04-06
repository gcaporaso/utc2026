<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\ComponentiCommissioni */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card card-secondary col-md-12"  style="margin-top: 5px" >
       <!-- INTESTAZIONE -->
        <div class="card-header" >
                <h3 class="card-title">INSERIMENTO DATI NUOVO COMPONENTE</h3>
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

   

    
    
    
  <?php  $etipo = ArrayHelper::map(app\models\TitoloComponente::find()->all(), 'idtitolo_componente', 'titolo'); ?>
<div class="row">
        <div class="col-sm-3" >
            <?= $form->field($model, 'Cognome')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-3" >
            <?= $form->field($model, 'Nome')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-3" >
            <?= $form->field($model, 'DataNascita')->widget(DateControl::classname(), [
                    'type' => 'date',
                    'ajaxConversion' => false,
                    'autoWidget' => true,
                    'widgetClass' => '',
                    'displayFormat' => 'php:d-m-Y',
                    'saveFormat' => 'php:Y-m-d',
                    'saveTimezone' => 'UTC',
                    'displayTimezone' => 'Europe/Amsterdam',
                    'language' => 'it',
                    'name' => 'dp_3',
                    'convertFormat'=>true,
                //'type' => DatePicker::TYPE_COMPONENT_APPEND,
                //'value'=>date("d-m-yyyy"),
                //'dateFormat' => 'php:d-m-Y',
                'widgetOptions' => [
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'php:d-m-Y',
                        'todayHighlight'=>true,
                        ],
                    
        ]])
            ?>
        </div>
</div>
<div class="row">
    <div class="col-sm-3" >
    <?= $form->field($model, 'IndirizzoResidenza')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3" >
    <?= $form->field($model, 'ComuneResidenza')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-2" >
    <?= $form->field($model, 'ProvinciaResidenza')->textInput(['maxlength' => true]) ?>
    </div>
</div>
    
<div class="row">
    <div class="col-sm-3" >
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3" >
    <?= $form->field($model, 'pec')->textInput(['maxlength' => true]) ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-3" >
    <?= $form->field($model, 'telefono')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3" >
    <?= $form->field($model, 'cellulare')->textInput(['maxlength' => true]) ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-4" >
    <?= $form->field($model, 'titolo_id')->widget(Select2::classname(), [
    'data' => $etipo,
    'options' => ['placeholder' => 'Titolo Componente ....'],
    'pluginOptions' => [
        'allowClear' => true
    ],])->label('Titolo Componente'); ?>
    </div>
</div>
    <div class="form-group">
        <?= Html::submitButton('Salva', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>

       