
<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\TitoloEdilizio;
use app\models\Committenti;
use app\models\Tecnici;
use app\models\Imprese;
use app\models\StatoEdilizia;
use app\models\TitoliPaesistica;
use kartik\select2\Select2;
//use yii\bootstrap\Button;
//use kartik\date\DatePicker;
//use kartik\number\NumberControl;
use kartik\datecontrol\DateControl;
//use yii\web\UrlManager;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model app\models\Edilizia */
/* @var $form yii\widgets\ActiveForm */
//$this->registerJsFile(Yii::$app->request->baseUrl . '/js/jquery-3.5.0.min.js', array('position' => $this::POS_HEAD), 'jquery');
//$this->registerJsFile('@web/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js', ['position' => \yii\web\View::POS_READY]);
//$this->registerCssFile('@web/plugins/tempusdominus-bootstrap-4/tempusdominus-bootstrap-4.min.css');
//$this->registerJsFile('@web/js/miyjscript.js', ['position' => $this::POS_HEAD]);
\hail812\adminlte3\assets\PluginAsset::register($this)->add('sweetalert2','tempusdominus-bootstrap-4');
//  
//JqueryAsset::register($this); 
$this->registerJs(
    '$(document).ready(function() {
        
//        var Toast = Swal.mixin({
//        toast: true,
//        position: "top-end",
//        showConfirmButton: false,
//        timer: 3000
//        });    
//        
//        Toast.fire({
//        icon: "success",
//        title: "Lorem ipsum dolor sit amet, consetetur sadipscing elitr."
//        });


        
       
 // assegna nuovo numero al permesso di costruire
        $("#numpaesistica").click(function(){
        //alert("Numero Paesistica");
            $.ajax({
                url : "' . Url::toRoute("paesistica/numerapaesistica") . '",
                type : "POST",
                data : {"id":10,
                        _csrf : "' . Yii::$app->request->getCsrfToken() . '"
                },
                dataType:"json",
                success : function(data) {      
                    $("#paesistica-numeroautorizzazionepaesaggistica").val(data.numero);
                    $("#paesistica-dataautorizzazionepaesaggistica-disp").val("' . date("d-m-Y") . '");
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
                <h3 class="card-title">DATI PRATICA PAESAGGISTICA</h3>
<!--                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                    </button>
                </div>-->
        </div>

    <?php $form = ActiveForm::begin(); 
    $etecnici = ArrayHelper::map(Tecnici::find()->all(), 'tecnici_id', 'nomeCompleto');
    ?>
<div class="card-body">       
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
        $listatitoli = ArrayHelper::map(TitoliPaesistica::find()->all(), 'idtitoli_paesistica', 'descrizione');
            echo $form->field($model, 'idtipo')->widget(Select2::classname(), [
                'name' => 'SelTitolo',
                'size' => Select2::MEDIUM,
                'data' => $listatitoli,
                'options' => ['id' => 'lstitolo', 'multiple' => false, 
                    'placeholder' => 'Seleziona titolo ...',
                ],
                'pluginOptions' => ['allowClear' => false],
            ])->label('Titolo Paesistico Richiesto');
            ?>
        </div>

    
    
        <div class="col-sm-2"  style="margin-top:30px">
        <?php echo $form->field($model, 'Compatibilita')->checkbox()->label(false); ?>
        </div>
</div>

<div class="row"> 
    <div class="col-sm-5" >
        <?= $form->field($model, 'DescrizioneIntervento')->textarea(['row' => '6']) ?>
    </div>
    <div class="col-sm-3" id='divRich1'>    
     <?php
     $data = ArrayHelper::map(Committenti::find()->all(), 'committenti_id', 'nomeCompleto');
     echo $form->field($model, 'idcommittente')->widget(Select2::classname(), [
    'data' => $data,
    'options' => ['placeholder' => 'Richiedente ...'],
    'pluginOptions' => [
        'allowClear' => true
    ],])->label('Richiedente'); ?>
    </div>

    
</div> 

    <hr style='padding-left:10px;padding-left:10px;border-style:groove;border-width: 1px;'>    
  
<div class="row">
    <div class="col-sm-2">    
     <?php
     $stato = ArrayHelper::map(StatoEdilizia::find()->all(), 'idstato_edilizia', 'descrizione');
     echo $form->field($model, 'StatoPratica')->widget(Select2::classname(), [
    'data' => $stato,
    'options' => ['placeholder' => 'Stato Pratica ...'],
    'pluginOptions' => [
        'allowClear' => true
    ],])->label('Stato Pratica'); ?>
    </div>
    
    
   
        <div class="col-sm-2" >
       
       <?php echo $form->field($model, 'NumeroAutorizzazione')->textInput();     ?>
        
        </div>
    <div class='col-sm-1' style='padding-top:25px;'>
      <?php echo Html::button('Numera', ['class'=>'btn btn-primary', 'id' => 'numpaesistica',
          'disabled' => isset($model->DataAutorizzazione) ? true:false
          ]);  ?>
    </div>

    <div class='col-sm-2'>
        <?php
        echo $form->field($model, 'DataAutorizzazione')->widget(DateControl::classname(), [
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
    
       
</div> 
    
   
<hr style='padding-left:10px;padding-left:10px;border-style:groove;border-width: 1px;'>    
   
<div class="row">
     
     <div class="col-sm-2" >
         <?php 
         $cattipo=[0=>'Fabbricati',1=>'Terreni'];
         echo $form->field($model, 'TipoCatasto')->widget(Select2::classname(), [
        'data' => $cattipo,
        'options' => [
            'placeholder' => 'Tipo Catasto ...',
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
    <div class="col-sm-2">
        <?php echo $form->field($model, 'Indennita', [
                    'template' => '{label}<div class="input-group"><span class="input-group-addon">€</span>{input}{error}</div>',
                ])->textInput(['type' => 'number', 'step' => '0.01', 'min' => '0', 'placeholder' => '0,00'])->label('Indennità Risarcitoria'); ?>
    </div>
     <div class="col-sm-2">
        <?php echo $form->field($model, 'IndPagata', [
                    'template' => '{label}<div class="input-group"><span class="input-group-addon">€</span>{input}{error}</div>',
                ])->textInput(['type' => 'number', 'step' => '0.01', 'min' => '0', 'placeholder' => '0,00'])->label('Indennità Pagata'); ?>
    </div>
    

</div>

<hr style='padding-left:10px;padding-left:10px;border-style:groove;border-width: 1px;'>   
<div class="row">
        <div class="col-sm-2">
        <?php echo $form->field($model, 'InviatoSoprintendenza')->checkbox(); ?>
        </div>
        <div class="col-sm-2"  >
        <?php echo $form->field($model, 'InviatoRegione')->checkbox(); ?>
        </div>
    
</div>
<hr style='padding-left:10px;padding-left:10px;border-style:groove;border-width: 1px;'>
<div class="row">
    <div class="col-sm-2" id='divRich1'>    
     <?php
     //$etecnici = ArrayHelper::map(Tecnici::find()->all(), 'tecnici_id', 'nomeCompleto');
     echo $form->field($model, 'Progettista_ID')->widget(Select2::classname(), [
    'data' => $etecnici,
    'options' => ['placeholder' => 'Progettista ...'],
    'pluginOptions' => [
        'allowClear' => true
    ],])->label('Progettista'); ?>
    </div>
    <div class="col-sm-2">    
     <?php
     echo $form->field($model, 'Direttore_Lavori_ID')->widget(Select2::classname(), [
    'data' => $etecnici,
    'options' => ['placeholder' => 'Direttore Lavori ....'],
    'pluginOptions' => [
        'allowClear' => true
    ],])->label('Direttore Lavori'); ?>
    </div>
    <div class="col-sm-2">    
     <?php
     $imprese = ArrayHelper::map(Imprese::find()->all(), 'imprese_id', 'nomeCompleto');
     echo $form->field($model, 'Impresa_ID')->widget(Select2::classname(), [
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
           <?= Html::a('Annulla', ['paesistica/index'], ['class'=>'btn btn-primary', 'style'=>'margin-left:20px']) ?>
           <?php ActiveForm::end(); ?>
       </div>

 

</div>
 