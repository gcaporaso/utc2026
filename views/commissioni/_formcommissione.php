<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use app\models\TipoCommissioni;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\ComponentiCommissioni */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="commissioni-form">
    <div><p></p></div>
        
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-sm-10">
            <?= $form->field($model, 'Descrizione')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-2">
        <?php
        $listatipi = ArrayHelper::map(TipoCommissioni::find()->all(), 'idtipo_commissioni', 'descrizione');
            echo $form->field($model, 'Tipo')->widget(Select2::classname(), [
                'name' => 'SelTipoComm',
                'size' => Select2::MEDIUM,
                'data' => $listatipi,
                'options' => ['id' => 'lstitip', 'multiple' => false, 
                    'placeholder' => 'Seleziona tipo ...',
                ],
                'pluginOptions' => ['allowClear' => false],
            ])->label('Tipo Commissione');
            ?>
        </div>

    
    </div>
    <div class="row">
        <div class="col-sm-4">
        <?= $form->field($model, 'NumeroDelibera')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-8">
    <?= $form->field($model, 'DataDelibera')->widget(DateControl::classname(), [
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
    <div class="form-group">
        <?= Html::submitButton('Salva', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
