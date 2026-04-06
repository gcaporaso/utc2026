<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use yii\helpers\ArrayHelper;
use app\models\StatoSedute;
use kartik\select2\Select2;
use kartik\widgets\TimePicker;
use app\models\Commissioni;

/* @var $this yii\web\View */
/* @var $model app\models\SeduteCommissioni */
/* @var $form yii\widgets\ActiveForm */

?>
<div class="card card-secondary col-md-12"  style="margin-top: 2px" >
    <div class="card-header" >
                <h3 class="card-title">INSERIMENTO DATI SEDUTA COMMISSIONE</h3>
    </div>
    <div class="card-body">
        <div class="sedute-commissioni-form">
            <?php $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class="col-sm-2">
                    <?= $form->field($model, 'numero')->textInput() ?>
                </div> 
                <div class="col-sm-4">
                    <?php
                        $listacommissioni = ArrayHelper::map(Commissioni::find()->where(['Tipo'=>$model->commissione->Tipo])->all(), 'idcommissioni', 'Descrizione');
                        echo $form->field($model, 'commissione_id')->widget(Select2::classname(), [
                        'name' => 'SelTipoComm',
                        'size' => Select2::MEDIUM,
                        'data' => $listacommissioni,
                        'options' => ['id' => 'lstcom', 'multiple' => false, 
                            'placeholder' => 'Seleziona commissione ...',
                        ],
                        'pluginOptions' => ['allowClear' => false],
                    ])->label('Commissione');

        //             $form->field($model, 'commissione_id')->textInput(['readonly'=> true])
                    ?>
                </div>    

            </div>
            <div class="row">
                <div class="col-sm-2">
                <?= $form->field($model, 'dataseduta')->widget(DateControl::classname(), [
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
                                     'removeButton' => false,
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'php:d-m-Y',
                                    'todayHighlight'=>true,
                                    ],
                    ]])
                    ?>
                </div>
                <div class="col-sm-2">

                    <?php
                $listatipi = ArrayHelper::map(StatoSedute::find()->all(), 'idstato_sedute', 'descrizione');
                    echo $form->field($model, 'statoseduta')->widget(Select2::classname(), [
                        'name' => 'SelStatoSeduta',
                        'size' => Select2::MEDIUM,
                        'data' => $listatipi,
                        'options' => ['id' => 'lstitip', 'multiple' => false, 
                            'placeholder' => 'Seleziona stato ...',
                        ],
                        'pluginOptions' => ['allowClear' => false],
                    ])->label('Stato Seduta');
                    ?>



                </div>
                <div class="col-sm-2">
                    <?= $form->field($model, 'presenze')->textInput(['readonly'=> true])->Label('ID Presenze') ?>
                </div>
            </div>    
            <div class="row">

                <div class="col-sm-2">
                    <?php // $form->field($model,'orarioconvocazione')->widget(DateControl::classname(),[
        //                'type'=>DateControl::FORMAT_TIME,
        //                'displayFormat' => 'php:H:i',
        //                'options' => [
        //                    'pluginOptions' => [
        //                    'autoclose' => true
        //                    ],
        //                'options' => ['placeholder' => 'time']
        //                ]
        //
        //            ]) 
                    ?> 
                    <?= $form->field($model, 'orarioconvocazione')->textInput() ?>
                </div>
                <div class="col-sm-2">
                    <?= $form->field($model, 'orarioinizio')->textInput() ?>
                </div>
                <div class="col-sm-2">
                    <?= $form->field($model, 'orariofine')->textInput() ?>
                </div>
            </div>

        </div>
    </div>
    <div class="card-footer">
    
        <?= Html::submitButton('Salva', ['class' => 'btn btn-success']) ?>
        <?php if ($model->isNewRecord) {
        echo Html::a('Annulla', ['commissioni/sedute','idtipocommissione'=>$idtipocommissione],
                ['class'=>'btn btn-primary', 'style'=>'margin-left:20px']);    
        } else {
        echo Html::a('Annulla', ['commissioni/sedute','idtipocommissione'=>$model->commissione->Tipo],
                ['class'=>'btn btn-primary', 'style'=>'margin-left:20px']); 
                
        }
        ?>    
        
        

    <?php ActiveForm::end(); ?>
    </div>
</div>
