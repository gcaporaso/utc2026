<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Tecnici */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="card card-secondary col-md-12"  style="margin-top: 5px" >
       <!-- INTESTAZIONE -->
        <div class="card-header" >
                <h3 class="card-title">Dati del tecnico</h3>
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
    <div class="col-md-2">
        <?= $form->field($model, 'COGNOME')->textInput(['maxlength' => true]) ?>
    </div>    
    <div class="col-md-2">
        <?= $form->field($model, 'NOME')->textInput(['maxlength' => true]) ?>
    </div>    
    <div class="col-md-2">
        <?= $form->field($model, 'COMUNE_NASCITA')->textInput(['maxlength' => true]) ?>
    </div>    
    <div class="col-md-2">
        <?= $form->field($model, 'PROVINCIA_NASCITA')->textInput(['maxlength' => true]) ?>
    </div>    
    <div class="col-md-2">
        <?=
            $form->field($model, 'DATA_NASCITA')->widget(DateControl::classname(), [
                    //'type'=>DateControl::FORMAT_DATE,
                    'ajaxConversion' => false,
                    'autoWidget' => true,
                    'widgetClass' => '',
                    'displayFormat' => 'php:d-m-Y',
                    'saveFormat' => 'php:Y-m-d',
                    'saveTimezone' => 'UTC',
                    'displayTimezone' => 'Europe/Amsterdam',
                    'language' => 'it',
                    'name' => 'dp_134',
                    'convertFormat'=>true,
                    'options'=>[
                         
                        
                    ],
                    'widgetOptions' => [
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'php:d-m-Y',
                            'todayHighlight'=>true,
//                            'clearButton' => false,
                            //'language' => 'it'
                            //'template' => '{remove}{input}{error}',
                            ],
                    ]])->label('Data Pagamento:');
        ?>        
        
    </div>    
</div>    
<div class="row">   
    <div class="col-md-2">
    <?php $listatitoli = ArrayHelper::map(\app\models\TitoloComponente::find()->all(), 'idtitolo_componente', 'titolo'); 
    echo $form->field($model, 'idtitolo')->widget(Select2::classname(), [
                'name' => 'SelTitolo',
                'size' => Select2::MEDIUM,
                'data' => $listatitoli,
                'options' => ['id' => 'lstitolo', 'multiple' => false, 'value' => 4,
                    'placeholder' => 'Seleziona titolo ...',
                ],
                'pluginOptions' => ['allowClear' => false],
            ])->label('Titolo Professionale');
    ?>
    
        
    </div>    
    <div class="col-md-2">
        <?= $form->field($model, 'ALBO')->textInput(['maxlength' => true]) ?>
    </div>    
    <div class="col-md-2">
        <?= $form->field($model, 'PROVINCIA_ALBO')->textInput(['maxlength' => true]) ?>
    </div>    
    <div class="col-md-1">
        <?= $form->field($model, 'NUMERO_ISCRIZIONE')->textInput(['maxlength' => true]) ?>
    </div>    
</div>    
<div class="row">     
    
<!--<div class="col-md-1">
    <?php // $form->field($model, 'NOME_COMPOSTO')->textInput(['maxlength' => true]) ?>
</div>    -->
    <div class="col-md-2">
        <?= $form->field($model, 'INDIRIZZO')->textInput(['maxlength' => true]) ?>
    </div>    
    <div class="col-md-2">
        <?= $form->field($model, 'COMUNE_RESIDENZA')->textInput(['maxlength' => true]) ?>
    </div>    
    <div class="col-md-2">
        <?= $form->field($model, 'PROVINCIA_RESIDENZA')->textInput(['maxlength' => true]) ?>
    </div> 
</div>    
<div class="row"> 
    <div class="col-md-2">
        <?= $form->field($model, 'CODICE_FISCALE')->textInput(['maxlength' => true]) ?>
    </div>    
    <div class="col-md-2">
        <?= $form->field($model, 'P_IVA')->textInput(['maxlength' => true])->label('Partita IVA') ?>
    </div>  
</div>    
<div class="row">    
    <div class="col-md-2">
        <?= $form->field($model, 'TELEFONO')->textInput(['maxlength' => true]) ?>
    </div>    
    <div class="col-md-2">
        <?= $form->field($model, 'FAX')->textInput(['maxlength' => true]) ?>
    </div>    
    <div class="col-md-2">
        <?= $form->field($model, 'CELLULARE')->textInput(['maxlength' => true]) ?>
    </div> 
</div>    
<div class="row">    
    <div class="col-md-3">
        <?= $form->field($model, 'EMAIL')->textInput(['maxlength' => true]) ?>
    </div>    
    <div class="col-md-3">
        <?= $form->field($model, 'PEC')->textInput(['maxlength' => true]) ?>
    </div>    
    <div class="col-md-3">
        <?= $form->field($model, 'Denominazione')->textInput(['maxlength' => true]) ?>
    </div>    
</div>    

   
    
</div>
       
       <div class="card-footer">
           <?= Html::submitButton(Yii::t('app', 'Salva'), ['class' => 'btn btn-success']) ?>
           <?= Html::a('Annulla', ['tecnici/index'], ['class'=>'btn btn-primary', 'style'=>'margin-left:20px']) ?>
           <?php ActiveForm::end(); ?>
       </div>

</div>       
