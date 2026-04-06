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
<div class="card card-secondary col-md-12"  style="margin-top: 5px" >
       <!-- INTESTAZIONE -->
        <div class="card-header" >
                <h3 class="card-title">INSERIMENTO DATI NUOVA PRATICA EDILIZIA</h3>
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
        <div class="col-sm-2">
        <?php
        $listatitoli = ArrayHelper::map(TitoloEdilizio::find()->all(), 'titoli_id', 'TITOLO');
            echo $form->field($model, 'id_titolo')->widget(Select2::classname(), [
                'name' => 'SelTitolo',
                'size' => Select2::MEDIUM,
                'data' => $listatitoli,
                'options' => ['id' => 'lstitolo', 'multiple' => false, 'value' => 4,
                    'placeholder' => 'Seleziona titolo ...',
                ],
                'pluginOptions' => ['allowClear' => false],
            ])->label('Titolo Edilizio Richiesto');
            ?>
        </div>
        <div class="col-sm-2"  style="margin-top:34px;margin-left:32px;">
        <?php echo $form->field($model, 'Sanatoria')->checkbox()->label(false); ?>
        </div>
</div>

<div class="row">
    <div class="col-sm-7" id='divRich1'>    
     <?php
     $data = ArrayHelper::map(Committenti::find()->all(), 'committenti_id', 'nomeCompleto');
     echo $form->field($model, 'id_committente')->widget(Select2::classname(), [
    'data' => $data,
    'options' => ['placeholder' => 'Richiedente ...'],
    'pluginOptions' => [
        'allowClear' => true
    ],])->label('Richiedente'); ?>
    </div>
</div> 
<div class="row"> 
    <div class="col-sm-7" >
        <?= $form->field($model, 'DescrizioneIntervento')->textarea(['row' => '6']) ?>
    </div>
</div> 
 <div class="row">
     
     <div class="col-sm-2" >
         <?php 
         $cattipo=[0=>'Fabbricati',1=>'Terreni'];
         echo $form->field($model, 'CatastoTipo')->widget(Select2::classname(), [
        'data' => $cattipo,
        'options' => [
            'placeholder' => 'Richiedente ...',
            'value' => 0,],
        'pluginOptions' => [
        'allowClear' => true
    ],])->label('Tipo Catasto');
         ?>
     </div>
     <div class="col-sm-1" >
    <?= $form->field($model, 'CatastoFoglio')->textInput() ?>
    </div>
    <div class="col-sm-1" >
    <?= $form->field($model, 'CatastoParticella')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-1" >
    <?= $form->field($model, 'CatastoSub')->textInput(['maxlength' => true]) ?>
    </div>
      <div class="col-sm-1" >
    <?= $form->field($model, 'Latitudine')->textInput(['maxlength' => true]) ?>
    </div>
      <div class="col-sm-1" >
    <?= $form->field($model, 'Longitudine')->textInput(['maxlength' => true]) ?>
    </div>
 </div> 
<div class="row">
    <div class="col-sm-5" >
    <?= $form->field($model, 'IndirizzoImmobile')->textInput(['maxlength' => true]) ?>
    </div>
</div>
    </div>       
       <div class="card-footer">
           <?= Html::submitButton(Yii::t('app', 'Salva'), ['class' => 'btn btn-success']) ?>
           <?= Html::a('Annulla', ['edilizia/index'], ['class'=>'btn btn-primary', 'style'=>'margin-left:20px']) ?>
           <?php ActiveForm::end(); ?>
       </div>

   
</div>
