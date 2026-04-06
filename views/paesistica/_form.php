<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\StatoEdilizia;
use app\models\Committenti;
use app\models\TitoliPaesistica;
use kartik\select2\Select2;
use kartik\date\DatePicker;


/* @var $this yii\web\View */
/* @var $model app\models\Edilizia */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="card card-secondary col-md-12"  style="margin-top: 5px" >
       <!-- INTESTAZIONE -->
        <div class="card-header" >
                <h3 class="card-title">INSERIMENTO DATI NUOVA PRATICA PAESISTICA</h3>
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
        <div class="col-sm-1" >
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
        <div class="col-sm-4">
        <?php
        $listatitoli = ArrayHelper::map(TitoliPaesistica::find()->all(), 'idtitoli_paesistica', 'descrizione');
            echo $form->field($model, 'idtipo')->widget(Select2::classname(), [
                'name' => 'SelPaes',
                'size' => Select2::MEDIUM,
                'data' => $listatitoli,
                'options' => ['id' => 'lstitolo', 'multiple' => false, 'value' => 1,
                    'placeholder' => 'Seleziona procedura ...',
                ],
                'pluginOptions' => ['allowClear' => false],
            ])->label('Titolo Paesistico Richiesto');
            ?>
        </div>
        <div class="col-sm-3">
            <?php
                $stato = ArrayHelper::map(StatoEdilizia::find()->all(), 'idstato_edilizia', 'descrizione');
                echo $form->field($model, 'StatoPratica')->widget(Select2::classname(), [
               'data' => $stato,
               'options' => ['placeholder' => 'Stato Pratica ...'],
               'pluginOptions' => [
                   'allowClear' => true
            ],])->label('Stato Pratica'); ?>
        </div>
        
</div>

<div class="row">
    <div class="col-sm-6" id='divRich1'>    
     <?php
     $data = ArrayHelper::map(Committenti::find()->all(), 'committenti_id', 'nomeCompleto');
     echo $form->field($model, 'idcommittente')->widget(Select2::classname(), [
    'data' => $data,
    'options' => ['placeholder' => 'Richiedente ...'],
    'pluginOptions' => [
        'allowClear' => true
    ],])->label('Richiedente'); ?>
    </div>
<!--</div> 
<div class="row"> -->
    <div class="col-sm-6" >
        <?= $form->field($model, 'DescrizioneIntervento')->textarea(['row' => '6']) ?>
    </div>
</div> 
 <div class="row">
     
<!--     <div class="col-sm-2" >
         <?php 
//         $cattipo=[0=>'Fabbricati',1=>'Terreni'];
//         echo $form->field($model, 'CatastoTipo')->widget(Select2::classname(), [
//        'data' => $cattipo,
//        'options' => [
//            'placeholder' => 'Richiedente ...',
//            'value' => 0,],
//        'pluginOptions' => [
//        'allowClear' => true
//    ],])->label('Tipo Catasto');
         ?>
     </div>-->
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
    <div class="col-sm-5" >
        <?= $form->field($model, 'IndirizzoImmobile')->textInput(['maxlength' => true]) ?>
    </div>
 </div> 
<div class="row">
    <div class="col-sm-10">
    <?php 
        $esommario = ArrayHelper::map(\app\models\Edilizia::find()
            ->where(['Stato_Pratica_id'=>[1,2,3,4,7,8]])    
            ->all(),'edilizia_id', 'sommario');
        echo $form->field($model, 'Edilizia_ID')->widget(Select2::classname(), [
        'data' => $esommario,
        'options' => ['placeholder' => 'Pratica ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],])->label('Riferita alla Pratica Edilizia:');
        ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-2"  style="margin-top:30px">
        <?php echo $form->field($model, 'Compatibilita')->checkbox()->label(false); ?>
    </div>
    <div class="col-sm-2">
        <?php echo $form->field($model, 'Indennita')->textInput()->label('Indennità ex art. 167'); ?>
    </div>    
    <div class="col-sm-2">
        <?php echo $form->field($model, 'IndPagata')->textInput()->label('Indennità Pagata'); ?>
    </div>    
</div>
        </div>       
       <div class="card-footer">
           <?= Html::submitButton(Yii::t('app', 'Salva'), ['class' => 'btn btn-success']) ?>
           <?= Html::a('Annulla', ['paesistica/index'], ['class'=>'btn btn-primary', 'style'=>'margin-left:20px']) ?>
           <?php ActiveForm::end(); ?>
       </div>


</div>
