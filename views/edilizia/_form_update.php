
<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\TitoloEdilizio;
use app\models\Committenti;
use app\models\Tecnici;
use app\models\Imprese;
use app\models\StatoEdilizia;
use app\models\TipologiaEdilizia;
use kartik\select2\Select2;
//use yii\bootstrap\Button;
//use kartik\date\DatePicker;
use kartik\number\NumberControl;
use kartik\datecontrol\DateControl;
//use yii\web\UrlManager;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model app\models\Edilizia */
/* @var $form yii\widgets\ActiveForm */
//$this->registerJsFile(Yii::$app->request->baseUrl . '/js/jquery-3.5.0.min.js', array('position' => $this::POS_HEAD), 'jquery');
//$this->registerJsFile('@web/js/jquery-3.5.0.min.js', ['position' => \yii\web\View::POS_READY]);
//$this->registerJsFile('@web/js/miyjscript.js', ['position' => $this::POS_HEAD]);

//  
//JqueryAsset::register($this); 
$this->registerJs(
    '$(document).ready(function() {

        // assegna nuovo numero al permesso di costruire
        $("#numpermesso").click(function(ev){
             
            $.ajax({
                url : "' . Url::toRoute("edilizia/numerapermesso") . '",
                type : "POST",
                data : {"id":10,
                        _csrf : "' . Yii::$app->request->getCsrfToken() . '"
                },
                dataType:"json",
                success : function(data) {      
                    $("#edilizia-numerotitolo").val(data.numero);
                    $("#edilizia-datatitolo-disp").val("' . date("d-m-Y") . '");
                },
                error : function(xhr, status, error){
                    var errorMessage = xhr.status + " : " + xhr.statusText
                    alert("Error - " + errorMessage);
                }
            });
        });



 // assegna nuovo numero al permesso di costruire
        $("#numpaesistica").click(function(){
        alert("Numero Paesistica");
            $.ajax({
                url : "' . Url::toRoute("edilizia/numerapaesistica") . '",
                type : "POST",
                data : {"id":10,
                        _csrf : "' . Yii::$app->request->getCsrfToken() . '"
                },
                dataType:"json",
                success : function(data) {      
                    $("#edilizia-numeroautorizzazionepaesaggistica").val(data.numero);
                    $("#edilizia-dataautorizzazionepaesaggistica-disp").val("' . date("d-m-Y") . '");
                },
                error : function(xhr, status, error){
                    var errorMessage = xhr.status + " : " + xhr.statusText
                    alert("Error - " + errorMessage);
                }
            });
        });






    });'
);

?>

          
<div class="card card-secondary col-md-12"  style="margin-top: 5px" >
       <!-- INTESTAZIONE -->
        <div class="card-header" >
                <h3 class="card-title">DATI PRATICA EDILIZIA</h3>
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

    <?php $form = ActiveForm::begin(); 
    $etecnici = ArrayHelper::map(Tecnici::find()->all(), 'tecnici_id', 'nomeCompleto');
    ?>
<div class="row">
        <div class="col-sm-2" >
        <?= $form->field($model, 'NumeroProtocollo')->textInput() ?>    
        </div>
        <div class="col-sm-3">
        <?= $form->field($model, 'DataProtocollo')->widget(DateControl::classname(), [
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
        <div class="col-sm-2">
        <?php
        $listatitoli = ArrayHelper::map(TitoloEdilizio::find()->all(), 'titoli_id', 'TITOLO');
            echo $form->field($model, 'id_titolo')->widget(Select2::classname(), [
                'name' => 'SelTitolo',
                'size' => Select2::MEDIUM,
                'data' => $listatitoli,
                'options' => ['id' => 'lstitolo', 'multiple' => false, 
                    'placeholder' => 'Seleziona titolo ...',
                ],
                'pluginOptions' => ['allowClear' => false],
            ])->label('Titolo Edilizio Richiesto');
            ?>
        </div>
    <div class="col-sm-3" id='divRich1'>    
     <?php
     $data = ArrayHelper::map(Committenti::find()->all(), 'committenti_id', 'nomeCompleto');
     echo $form->field($model, 'id_committente')->widget(Select2::classname(), [
    'data' => $data,
    'options' => ['placeholder' => 'Richiedente ...'],
    'pluginOptions' => [
        'allowClear' => true
    ],])->label('Richiedente'); ?>
    </div>

    
    
        <div class="col-sm-2"  style="margin-top:30px">
        <?php echo $form->field($model, 'Sanatoria')->checkbox()->label(false); ?>
        </div>
</div>

    <div class="row"> 
    <div class="col-sm-5" >
        <?= $form->field($model, 'DescrizioneIntervento')->textarea(['row' => '6']) ?>
    </div>
    <div class="col-sm-7">    
     <?php
     $tipoed = ArrayHelper::map(TipologiaEdilizia::find()->all(), 'idtipologia', 'sommarioTipologia');
     echo $form->field($model, 'tipologia_id')->widget(Select2::classname(), [
    'data' => $tipoed,
    'id'=>'seltipo',
    //'height'=>'300px',
    'options' => ['placeholder' => 'tipologia ...'],
    'pluginOptions' => [
        'allowClear' => true
    ],])->label('Tipologia Edilizia'); ?>
    </div>
    
</div> 

    <hr style='padding-left:10px;padding-left:10px;border-style:groove;border-width: 1px;'>    
  
<div class="row">
    <div class="col-sm-2">    
     <?php
     $stato = ArrayHelper::map(StatoEdilizia::find()->all(), 'idstato_edilizia', 'descrizione');
     echo $form->field($model, 'Stato_Pratica_id')->widget(Select2::classname(), [
    'data' => $stato,
    'options' => ['placeholder' => 'Stato Pratica ...'],
    'pluginOptions' => [
        'allowClear' => true
    ],])->label('Stato Pratica'); ?>
    </div>
    <div class='col-sm-2'>
        <?php
        echo $form->field($model, 'Data_OK')->widget(DateControl::classname(), [
                    'type' => 'date',
                    'ajaxConversion' => false,
                    'autoWidget' => true,
                    'widgetClass' => '',
                    'displayFormat' => 'php:d-m-Y',
                    'saveFormat' => 'php:Y-m-d',
                    'saveTimezone' => 'UTC',
                    'displayTimezone' => 'Europe/Amsterdam',
                    'language' => 'it',
                    'name' => 'dp_4',
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
                    
                ]]); ?>
    </div>
    
    
    <?php if (($model->id_titolo ==4) OR ($model->id_titolo ==8) OR ($model->id_titolo ==9)) { ?>
        <div class="col-sm-1" >
       
       <?php echo $form->field($model, 'NumeroTitolo')->textInput();     ?>
        
        </div>

    <div class='col-sm-2'>
        <?php
        echo $form->field($model, 'DataTitolo')->widget(DateControl::classname(), [
                    'type' => 'date',
                    'ajaxConversion' => false,
                    'autoWidget' => true,
                    'widgetClass' => '',
                    'displayFormat' => 'php:d-m-Y',
                    'saveFormat' => 'php:Y-m-d',
                    'saveTimezone' => 'UTC',
                    'displayTimezone' => 'Europe/Amsterdam',
                    'language' => 'it',
                    'name' => 'dp_4',
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
                    
                ]]); ?>
    </div>
    <div class='col-sm-2' style='padding-top:25px;'>
      <?php echo Html::button('Numera', ['class'=>'btn btn-primary', 'id' => 'numpermesso',
          'disabled' => isset($model->DataTitolo) ? true:false
          ]);  ?>
    </div>
        <?php } ?>
</div> 
    
    <hr style='padding-left:10px;padding-left:10px;border-style:groove;border-width: 1px;'>
    
<div class="row">
    <div class="col-sm-2">
        <?php echo $form->field($model, 'AutPaesistica')->checkbox()->label('Soggetta ad Autorizzazione Paesistica?'); ?>
    </div>   
    <div class="col-sm-2">
        <?php echo $form->field($model, 'COMPATIBILITA_PAESISTICA')->checkbox()->label('Trattasi di  Compatibilità?'); ?>
    </div>   
    
    <div class="col-sm-1" >
        
        <?php echo $form->field($model, 'NumeroAutorizzazionePaesaggistica')->textInput()->label('Num. Aut. Paesistica');     ?>
        
    </div>
    <div class='col-sm-2'>
        <?php
        echo $form->field($model, 'DataAutorizzazionePaesaggistica')->widget(DateControl::classname(), [
                    'type' => 'date',
                    'ajaxConversion' => false,
                    'autoWidget' => true,
                    'widgetClass' => '',
                    'displayFormat' => 'php:d-m-Y',
                    'saveFormat' => 'php:Y-m-d',
                    'saveTimezone' => 'UTC',
                    'displayTimezone' => 'Europe/Amsterdam',
                    'language' => 'it',
                    'name' => 'dp_4',
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
                    
                ]]); ?>
    </div>
    <div class='col-sm-2' style='padding-top:25px;'>
      <?php echo Html::button('Numera', ['class'=>'btn btn-primary', 'id' => 'numpaesistica',
          'disabled' => isset($model->DataAutorizzazionePaesaggistica) ? true:false
          ]);  ?>
    </div>

</div>
   
<hr style='padding-left:10px;padding-left:10px;border-style:groove;border-width: 1px;'>    
   
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
    <div class="col-sm-2" >
        <?= $form->field($model, 'IndirizzoImmobile')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-2" >
        <?php echo $form->field($model, 'TitoloOneroso')->checkbox()->label('Pratica Soggetta ad Oneri Concessori'); ?>
    </div>
    <div class="col-sm-2">
        <?php echo $form->field($model, 'Oneri_Costruzione')->widget(NumberControl::classname(),[
                    'name' => 'amount_oneri',
                    //'value' => 78263232.01,
                    'maskedInputOptions' => [
                        'prefix' => '€ ',
                        'groupSeparator' => '.',
                        'radixPoint' => ','
                        ],
                    //'options' => $saveOptions,
                    //'displayOptions' => $dispOptions,
                    //'saveInputContainer' => $saveCont
                    ])->label('Oneri Costruzione'); ?>
    </div>
     <div class="col-sm-2">
        <?php echo $form->field($model, 'Oneri_Urbanizzazione')->widget(NumberControl::classname(),[
                    'name' => 'amount_oneri2',
                    //'value' => 78263232.01,
                    'maskedInputOptions' => [
                        'prefix' => '€ ',
                        'groupSeparator' => '.',
                        'radixPoint' => ','
                        ],
                    //'options' => $saveOptions,
                    //'displayOptions' => $dispOptions,
                    //'saveInputContainer' => $saveCont
                    ])->label('Oneri Urbanizzazione'); ?>
    </div>
    <div class="col-sm-2">
        <?php echo $form->field($model, 'Oneri_Pagati')->widget(NumberControl::classname(),[
                    'name' => 'amount_pagato',
                    //'value' => 78263232.01,
                    'maskedInputOptions' => [
                        'prefix' => '€ ',
                        'groupSeparator' => '.',
                        'radixPoint' => ','
                        ],
                    //'options' => $saveOptions,
                    //'displayOptions' => $dispOptions,
                    //'saveInputContainer' => $saveCont
                    ])->label('Oneri Pagati'); ?>
    </div>

</div>
<hr style='padding-left:10px;padding-left:10px;border-style:groove;border-width: 1px;'>
<div class="row">
    <div class="col-sm-2" id='divRich1'>    
     <?php
     //$etecnici = ArrayHelper::map(Tecnici::find()->all(), 'tecnici_id', 'nomeCompleto');
     echo $form->field($model, 'PROGETTISTA_ARC_ID')->widget(Select2::classname(), [
    'data' => $etecnici,
    'options' => ['placeholder' => 'Progettista Arch. ...'],
    'pluginOptions' => [
        'allowClear' => true
    ],])->label('Progettista Architettonico'); ?>
    </div>
    <div class="col-sm-2">    
     <?php
     echo $form->field($model, 'DIR_LAV_ARCH_ID')->widget(Select2::classname(), [
    'data' => $etecnici,
    'options' => ['placeholder' => 'Direttore Lavori Architettonico ....'],
    'pluginOptions' => [
        'allowClear' => true
    ],])->label('Direttore Lavori Architettonico'); ?>
    </div>
    <div class="col-sm-2">    
     <?php
     //$etecnici = ArrayHelper::map(Tecnici::find()->all(), 'tecnici_id', 'nomeCompleto');
     echo $form->field($model, 'PROGETTISTA_STR_ID')->widget(Select2::classname(), [
    'data' => $etecnici,
    'options' => ['placeholder' => 'Progettista Strutturale ...'],
    'pluginOptions' => [
        'allowClear' => true
    ],])->label('Progettista Strutturale'); ?>
    </div>
    <div class="col-sm-2">    
     <?php
     //$etecnici = ArrayHelper::map(Tecnici::find()->all(), 'tecnici_id', 'nomeCompleto');
     echo $form->field($model, 'DIR_LAV_STR_ID')->widget(Select2::classname(), [
    'data' => $etecnici,
    'options' => ['placeholder' => 'Direttore Lavori Strutturale ...'],
    'pluginOptions' => [
        'allowClear' => true
    ],])->label('Direttore Lavori Strutturale'); ?>
    </div>
    <div class="col-sm-2">    
     <?php
     $imprese = ArrayHelper::map(Imprese::find()->all(), 'imprese_id', 'nomeCompleto');
     echo $form->field($model, 'IMPRESA_ID')->widget(Select2::classname(), [
    'data' => $imprese,
    'options' => ['placeholder' => 'Impresa Esecutrice ...'],
    'pluginOptions' => [
        'allowClear' => true
    ],])->label('Impresa Esecutrice'); ?>
    </div>
    
</div>
    
 
    
    </div>       
       <div class="card-footer">
           <?= Html::submitButton(Yii::t('app', 'Salva'), ['class' => 'btn btn-success']) ?>
           <?= Html::a('Annulla', ['edilizia/index'], ['class'=>'btn btn-primary', 'style'=>'margin-left:20px']) ?>
           <?php ActiveForm::end(); ?>
       </div>



</div>
 