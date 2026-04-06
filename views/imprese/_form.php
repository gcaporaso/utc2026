<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\FormaGiuridica;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Imprese */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card card-secondary col-md-12"  style="margin-top: 5px" >
       <!-- INTESTAZIONE -->
        <div class="card-header" >
                <h3 class="card-title">DATI IMPRESA</h3>
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
    <?php
        $listaformegiuridiche = ArrayHelper::map(FormaGiuridica::find()->all(), 'idformagiuridica', 'descrizione');
        echo $form->field($model, 'formaGiuridica')->widget(Select2::classname(), [
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
    <div class="col-md-2">
    <?= $form->field($model, 'RAGIONE_SOCIALE')->textInput(['maxlength' => true]) ?>
    </div>    
       
</div>    
<div class="row">
    <div class="col-md-2">    
    <?= $form->field($model, 'COGNOME')->textInput(['maxlength' => true]) ?>
    </div>    
    <div class="col-md-2">    
    <?= $form->field($model, 'NOME')->textInput(['maxlength' => true]) ?>
    </div> 
    <div class="col-md-2">        
    <?= $form->field($model, 'DATA_NASCITA')->textInput() ?>
    </div>    
    <div class="col-md-2"> 
    <?= $form->field($model, 'PROVINCIA_NASCITA')->textInput(['maxlength' => true]) ?>
    </div> 
</div>    
<div class="row">    
    
    <?php // $form->field($model, 'NOME_COMPOSTO')->textInput(['maxlength' => true]) ?>
    
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
    <?= $form->field($model, 'PartitaIVA')->textInput(['maxlength' => true]) ?>
    </div>    
</div>    
<div class="row">    
    <div class="col-md-2"> 
    <?= $form->field($model, 'EMAIL')->textInput(['maxlength' => true]) ?>
    </div>    
    <div class="col-md-2"> 
    <?= $form->field($model, 'PEC')->textInput(['maxlength' => true]) ?>
    </div>  
</div>    
<div class="row">    
    <div class="col-md-2"> 
    <?= $form->field($model, 'Cassa_Edile')->textInput() ?>
    </div>    
    <div class="col-md-2"> 
    <?= $form->field($model, 'INPS')->textInput(['maxlength' => true]) ?>
    </div>    
    <div class="col-md-2"> 
    <?= $form->field($model, 'INAIL')->textInput(['maxlength' => true]) ?>
    </div>    
</div>    
<div class="row">    
    <div class="col-md-2"> 
    <?= $form->field($model, 'Telefono')->textInput() ?>
    </div>    
    <div class="col-md-2"> 
    <?= $form->field($model, 'Cellulare')->textInput() ?>
    </div>    
    
</div>
</div>       
       <div class="card-footer">
           <?= Html::submitButton(Yii::t('app', 'Salva'), ['class' => 'btn btn-success']) ?>
           <?= Html::a('Annulla', ['imprese/index'], ['class'=>'btn btn-primary', 'style'=>'margin-left:20px']) ?>
           <?php ActiveForm::end(); ?>
       </div>

    

</div>
