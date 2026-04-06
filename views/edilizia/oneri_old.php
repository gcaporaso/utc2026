
<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
//use vkabachenko\filepond\widget\FilepondWidget;
use yii\widgets\Pjax;
use yii\helpers\Url;
use kartik\dynagrid\DynaGrid;
use kartik\grid\GridView;
use kartik\datecontrol\DateControl;
use kartik\editable\Editable;
use yii\bootstrap\Modal;
use yii\web\View;
//use kartik\dialog\Dialog;
//use yii\web\JsExpression;
//use kartik\dialog\DialogAsset;


//$this->registerJsFile('@web/js/oneri.js');

$this->registerJs("
$('#modalButton').on('click',function (event) {
//alert('OK');
    $('#deto').removeClass('has-error');
    $('#deto').removeClass('has-error');
    $.ajax({
        url : '/index.php?r=edilizia/oneriajax',
        type:'post',
        data: { 'idpratica':" . $idpratica . ", 
                'tipo':'email',
              ' _csrf' : '" . Yii::$app->request->getCsrfToken() . "'
               },
        dataType:'json',
        success : function(data) {      
                    $('#efrom').val('ing@comune.campolidelmontetaburno.bn.it');
                    $('#eto').val(data.eto);
                    $('#esubject').val(data.esubject);
                    $('#ebody').val(data.ebody);
                },
        error : function(xhr, status, error){
                    var errorMessage = xhr.status + ' : ' + xhr.statusText
                    alert('Error - ' + errorMessage);
                }
     });
event.preventDefault();        

});



$('#pecButton').on('click',function (event) {
//alert('OK');
    $('#deto').removeClass('has-error');
    $('#deto').removeClass('has-error');
    $.ajax({
        url : '/index.php?r=edilizia/oneriajax',
        type:'post',
        data: { 'idpratica':" . $idpratica . ",
                'tipo':'pec',
              ' _csrf' : '" . Yii::$app->request->getCsrfToken() . "'
               },
        dataType:'json',
        success : function(data) {      
                    $('#efrom').val('ingcampoli@pec.it');
                    $('#eto').val(data.eto);
                    $('#esubject').val(data.esubject);
                    $('#ebody').val(data.ebody);
                },
        error : function(xhr, status, error){
                    var errorMessage = xhr.status + ' : ' + xhr.statusText
                    alert('Error - ' + errorMessage);
                }
     });
event.preventDefault();        

});



function email_valida(email) {
  var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
  if (!reg.test(email)) {return false;} else { return true;}
}



$('#subButton').on('click',function (event) {
    // VALIDAZIONE INPUT
     if (!email_valida($('#efrom').val())) {
        alert('Email non valida!');
        $('#defrom').addClass('has-error');
        return;
        }  else {
        $('#deto').removeClass('has-error');
    }
    if (!email_valida($('#eto').val())) {
        alert('Email non valida!');
        $('#deto').addClass('has-error');
        return;
    } else {
        $('#deto').removeClass('has-error');
    }


    var url = '/index.php?r=edilizia/inviaemail';
    if ($('#efrom').val()=='ingcampoli@pec.it') {
        url = '/index.php?r=edilizia/inviapec';
    };
            $.ajax({
            url: url,
            type: 'post',
            data: {'efrom':$('#efrom').val(),
                   'eto':$('#eto').val(),
                   'esubject':$('#esubject').val(),
                   'ebody':$('#ebody').val(),
                },
            success: function(){
                alert('Email Inviata!');
            },
             error : function(xhr, status, error){
                    
                    var errorMessage = xhr.status + ' : ' + xhr.statusText;
                    alert('Si è verificato un errore!'+' Email non Inviata!');
                }
        });  
        $('.modal').modal('hide'); //hide popup  
        event.preventDefault();      
        return false;
    });
   

$('#pianoButton').on('click',function (event) {
//alert('OK');

    $.ajax({
        url : '/index.php?r=edilizia/pianorateizzazioneajax',
        type:'post',
        data: { 'idpratica':" . $idpratica . ", 
                'importo':$('#pimporto').val(),
                'numrate':$('#pnumero').val(),
                'intervallo':$('#pintervallo').val(),
                'primadata':$('#pdata').val(),
              ' _csrf' : '" . Yii::$app->request->getCsrfToken() . "'
               },
        dataType:'json',
        success : function(data) {      
                },
        error : function(xhr, status, error){
                    var errorMessage = xhr.status + ' : ' + xhr.statusText
                    //alert('Error - ' + errorMessage);
                }
     });
$('.modal').modal('hide'); //hide popup 
//event.preventDefault();        
location.reload(); 
});


", yii\web\View::POS_READY);



?>

<!-- Modal -->
<div class="modal" id="myModal" tabindex="-1" role="dialog" >
  <div class="modal-dialog">
    <div class="modal-content" style="height:700px; max-height:800px; width:800px; max-width:800px;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Invio Email Sollecito Pagamento</h4>
      </div>
      <div class="modal-body" >
        <form action="#" id ="formemail" method="post">
          <div class="form-group" id="defrom">
              <label for="efrom" class="col-form-label">Da:</label>
              <input type="text" class="form-control" id="efrom"><h2 id='result1'></h2>
          </div>
          <div class="form-group" id="deto">
              <label for="eto" class="col-form-label">a:</label>
              <input type="text" class="form-control" id="eto"><h2 id='result2'></h2>
          </div>
          <div class="form-group">
            <label for="esubject" class="col-form-label">Oggetto:</label>
            <input type="text" class="form-control" id="esubject">
          </div>
          <div class="form-group">
            <label for="ebody" class="col-form-label">Messaggio:</label>
            <textarea class="form-control" id="ebody" rows="12"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
        <button class="btn btn-primary" id="subButton">Invia</button>
      </div>
    </div>
  </div>
</div>



<!-- Modal -->
<div class="modal" id="modalPiano" tabindex="-2" role="dialog" >
  <div class="modal-dialog">
    <div class="modal-content" >
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Piano di Rateizzazione Oneri Concessori</h4>
      </div>
      <div class="modal-body" >
        <form action="index.php?r=edilizia/pianooneri" id ="formpiano" method="post">
          <div class="form-group">
            <div class="col-sm-6">
              <label for="pimporto" class="col-form-label">Importo da Rateizzare:</label>
              <input type="text" class="form-control" id="pimporto">
            </div>
            <div class="col-sm-6">
              <label for="pnumero" class="col-form-label">Numero di Rate:</label>
              <input type="text" class="form-control" id="pnumero">
          </div>
          </div>
          <div class="form-group">
            <div class="col-sm-6">
                <label for="pintervallo" class="col-form-label">Intervallo tra le Rate:</label>
                <select class="form-control" id="pintervallo">
                        <option value="1">Un mese</option>
                        <option value="2">Due Mesi</option>
                        <option value="3">Tre Mesi</option>
                        <option value="6">Sei Mesi</option>
                        <option value="12">Un Anno</option>
                </select>
            </div>
            <div class="col-sm-6">
                <label for="pdata" class="col-form-label">Data Scadenza Prima Rata:</label>
                <input type="date" class="form-control" id="pdata">
            </div>
          </div>
        </form>
      </div>
        <div class="modal-footer" >
        <button type="button" class="btn btn-default" data-dismiss="modal" style="margin-top: 25px">Chiudi</button>
        <button class="btn btn-primary" id="pianoButton" style="margin-top: 25px">Genera</button>
      </div>
    </div>
  </div>
</div>
<?php


//$this->registerJs(

//       ' $("#emailsoll").on("click", function() {
//            //alert("Button Click!");
//            $.ajax({
//                url : "/index.php?r=edilizia/emailsollecito&idpratica='. $id .'",
//                type : "POST",
//                data : {"id":' . $id . ',
//                        _csrf : "' . Yii::$app->request->getCsrfToken() . '"
//                },
//                dataType:"json",
//                success : function(data) {      
//                    alert("Email Inviata!");
//                },
//                error : function(xhr, status, error){
//                    var errorMessage = xhr.status + " : " + xhr.statusText
//                    alert("Error - " + errorMessage);
//                }
//            });
//      });
        
        // listen click, open modal and .load content
/*
 * Quando viene cliccato il pulsante "modalButton"
 * mostra il "modal" con il contenuto "modalContent"
 * preso dal rendering di modal.value
 */
//'$("#emailsoll").click(function (data){
//    $("#modal").modal("show")
//        .find("#modalContent")
//        .load($(this).attr("value"))
//
//});
        
// ', yii\web\View::POS_READY);



/* @var $this yii\web\View */
/* @var $model app\models\Edilizia */

$this->title = Yii::t('app', 'Oneri Concessori Pratica Edilizia {Pratica}',['Pratica' => $idpratica]);
$this->params['breadcrumbs'][] = ['label' => 'Pratica', 'url' => ['allegati']];
$this->params['breadcrumbs'][] = $this->title;
?>


     


<div class="row">
<div class="container-fluid" style="padding:10px;">

<!--    <h1><?= Html::encode($this->title) ?></h1>-->

    <?php // $this->render('_form_allegati', [
      //  'modtec' => $modtec,'modamm' => $modamm,'dpv1'=>$dprov1,'dpv2'=>$dprov2, 'id'=>$id
    // ]) ?>
    <?php $form = ActiveForm::begin([
        'action'=>['edilizia/oneri', 'id'=>$idpratica],
        'id' => 'oneri-form',
    ]) ?>
    <table style="border: 1px solid grey;">
        <thead><th style="border:1px solid grey;">Tipo Rateizzazione:</th><th>Rata Numero:</th><th  style="border:1px solid grey;">Importo dovuto:</th><th>Data Scadenza:</th>
    <th  style="border:1px solid grey;">Importo pagato:</th><th>Data Pagamento:</th></thead>
    <tbody>
        <tr style="border: 1px solid grey;">
        <td style="width:8%;padding-left:5px;padding-top:10px;padding-right:5px;">
        <?= $form->field($model, 'tiporata')->dropDownList([0 => 'RATE', 1 => 'UNICA'])->label(false) ?>
        </td>
    <td style="width:8%; padding-left:10px;padding-top:10px;padding-right:10px;border: 1px solid grey;">
        <div style="position:relative;">
           <?php echo $form->field($model, 'ratanumero')->label(false)->textInput() ?>
        </div>
    </td>  
    <td style="width:10%; padding-left:10px;padding-top:10px;padding-right:10px;border: 1px solid grey;">
        <div style="position:relative;">
           <?php echo $form->field($model, 'importodovutorata')->label(false)->textInput() ?>
        </div>
    </td> 
    
    <td style="width:15%; padding-left:10px;padding-top:10px;padding-right:10px;border: 1px solid grey;">
        <div>
       <?php
        echo $form->field($model, 'datascadenza')->widget(DateControl::classname(), [
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
                    
                ]])->label(false); ?>
        </div>
    </td>
    
        <td style="width:10%; padding-left:10px;padding-top:10px;padding-right:10px;border: 1px solid grey;">
        <div style="position:relative;">
           <?php echo $form->field($model, 'importopagatorata')->label(false)->textInput() ?>
        </div>
    </td> 

    
    <td style="width:15%; padding-left:10px;padding-top:10px;padding-right:10px;border: 1px solid grey;">
        <div>
       <?php
        echo $form->field($model, 'datapagamento')->widget(DateControl::classname(), [
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
                    
                ]])->label(false); ?>
        </div>
    </td>
    
    
    
    
        <td style="width:3%;text-align: center">
        <?= Html::submitButton($model->isNewRecord ? 'Aggiungi' : 'Aggiorna', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </td>
        </tr>
    </tbody>
    </table>
    <?php ActiveForm::end() ?>
    
</div>
</div>

<!-- display success message -->
<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success alert-dismissable">
         <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
         <h4><i class="icon fa fa-check"></i>Salvato!</h4>
         <?= Yii::$app->session->getFlash('success') ?>
    </div>
<?php endif; ?>

<!-- // display error message -->
<?php if (Yii::$app->session->hasFlash('error')): ?>
    <div class="alert alert-danger alert-dismissable">
         <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
         <h4><i class="icon fa fa-check"></i>Errore!</h4>
         <?= Yii::$app->session->getFlash('error') ?>
    </div>
<?php endif; ?>


<div class="row" style="padding-top:10px;">
  
<?php
echo DynaGrid::widget([
   	'columns'=>[
        [
        'class'=>'kartik\grid\ActionColumn',
        'order'=>DynaGrid::ORDER_FIX_LEFT,
		'template' => '{delete}',
                'header'=>'',
                'width'=>'2%',
        'buttons'=>['delete' => function ($url,$model1) use ($idpratica) {
                       //$myurl = $url . '&idpratica='.$id;
                       $url = Url::toRoute(['edilizia/cancellarata','id'=>$model1->idoneri,'idpratica'=>$idpratica]);
                      return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url,
                      [  
                         'title' => 'Elimina',
                         'data-confirm' => "Sei sicuro di volere eliminare questo allegato?",
                         //'data-method' => 'post',
                         //'data-pjax' => 0
                      ]);
             }]
        ],
	['class'=>'\kartik\grid\SerialColumn', 
	'order'=>DynaGrid::ORDER_FIX_LEFT],
	['class'=>'kartik\grid\EditableColumn',
         'attribute'=>'tiporata',
         'label'=>'Tipo Rata',
         //'label'=>'Protocollo',
        'hAlign'=>'center',
        'value'=> function($model)
               {if ($model->tiporata==0) {
                     return 'RATE';
                 } else {
                     return 'UNICA';
                 }
               },
            'editableOptions' => [
            //'label'=>'xxx',
            'header' => 'Tipo Pagamento',
            'formOptions' => ['action' => ['/edilizia/oneriup']],
            'inputType' => Editable::INPUT_DROPDOWN_LIST,
            'data' => [0 => 'RATE', 1 => 'UNICA'],
            'options' => ['class'=>'form-control', 'prompt'=>'Seleziona...'],
            'options' => [
                'pluginOptions' => [
                    //'label'=>'pagata?',
                ]
            ],
            
            //'label'=>'pagato?',
            'displayValueConfig'=> [
                '0' => '<i>Rate</i>',
                '1' => '<i>Unica</i>',
            ],
        ],    
        'width'=>'6%',
 	//'hAlign'=>'left', 
	],
        ['class'=>'kartik\grid\EditableColumn',
        'attribute'=>'ratanumero',
        'label'=>'Rata Numero',
         //'format' =>  ['date', 'php:d-m-Y'],
         'value' =>  function($model)
               {if ($model->ratanumero===null) {
                     return '';
                 } else {
                     return $model->ratanumero;
                 }
               },
        'editableOptions' => [
            'formOptions' => ['action' => ['/edilizia/oneriup']],
            'inputType' => Editable::INPUT_TEXT,
        ],
        'hAlign'=>'center',
        'width'=>'8%'],
        ['attribute'=>'importodovutorata', //committente.nomeCompleto',
        'class'=>'kartik\grid\EditableColumn',
        'header' => 'Importo Dovuto',
        'pageSummary' => true,
        'format'=>['decimal', 2],
        'editableOptions' => [
            'formOptions' => ['action' => ['/edilizia/oneriup']],
            'inputType' => Editable::INPUT_TEXT,
        ],
         // $this->price = Yii::app()->numberFormatter->formatCurrency(strtr($this->price,array(','=>'.')), 'EUR');
        'width'=>'18%',
        'hAlign'=>'center',
        ],  
        ['class'=>'kartik\grid\EditableColumn',
        'attribute'=>'datascadenza',
        'format' =>  ['date', 'php:d-m-Y'],
        'hAlign'=>'left',
        'editableOptions'=>[
            'header'=>' Data Scadenza',
            'formOptions' => ['action' => ['/edilizia/oneriup']],
            'inputType'=>Editable::INPUT_WIDGET,
            'widgetClass'=>'kartik\datecontrol\DateControl',
            'options'=>[
                  'class'=>'kartik\datecontrol\DateControl',  
                      'type'=>DateControl::FORMAT_DATE,
                'pluginOptions'=>[
                ]],
        ],
        'width'=>'12%'],
        [
        'class'=>'kartik\grid\EditableColumn',
        //'class'=>'kartik\grid\BooleanColumn',
        
        'attribute'=>'pagata', 
        'hAlign'=>'center',
        'editableOptions' => [
            //'label'=>'xxx',
            'header' => 'Pagato',
            'formOptions' => ['action' => ['/edilizia/oneriup']],
            'inputType' => Editable::INPUT_DROPDOWN_LIST,
            'data' => [0 => 'No', 1 => 'Si'],
            'options' => ['class'=>'form-control', 'prompt'=>'Seleziona...'],
            'options' => [
                'pluginOptions' => [
                    //'label'=>'pagata?',
                ]
            ],
            
            //'label'=>'pagato?',
            'displayValueConfig'=> [
                '0' => '<i class="glyphicon glyphicon-remove"></i>',
                '1' => '<i class="glyphicon glyphicon-ok"></i>',
            ],
        ],    
        'vAlign'=>'middle',
        //'trueLabel' => 'Si',
        //'falseLabel' => 'No',
        //'trueIcon' => '<span class="glyphicon glyphicon-ok text-success"></span>',
        //'falseIcon'=>' ',
        'width'=>'7%',
        ],
        ['attribute'=>'importopagatorata', //committente.nomeCompleto',
        'class'=>'kartik\grid\EditableColumn',
        'format'=>['decimal', 2],
        'pageSummary' => true,
        //'value'=>isset($model->importopagatorata)?Yii::$app->formatter->asCurrency($model->importopagatorata, 'EUR'):'=======',
        'header' => 'Importo Pagato',
        'editableOptions' => [
            'formOptions' => ['action' => ['/edilizia/oneriup']],
            'inputType' => Editable::INPUT_TEXT,
//            'afterInput'=>    function ($form, $widget) {
//                   if (isset($form->field($widget->model, 'importopagatorata'))) {
//                echo Yii::$app->formatter->asDecimal($form->field($widget->model, 'importopagatorata'));
//                   }
//            },
            'pluginEvents' => [
                //"editableChange"=>"function(event, val) { log('Changed Value ' + val); }",
                //"editableSubmit"=>"function(event, val, form) { log('Submitted Value ' + val); }",
                //"editableBeforeSubmit"=>"function(event, jqXHR) { log('Before submit triggered'); }",
                //"editableSubmit"=>"function(event, val, form, jqXHR) { alert('Submitted Value ' + val); }",
                //"editableReset"=>"function(event) { log('Reset editable form'); }",
                //"editableSuccess"=>"function(event, val, form, data) {alert('Successful submission of value ' + val);}",
                //"editableError"=>"function(event, val, form, data) { log('Error while submission of value ' + val); }",
                //"editableAjaxError"=>"function(event, jqXHR, status, message) { log(message); }",
            ],    
        ],

        'width'=>'15%',
	//'label'=>'Importo Pagato',
        'hAlign'=>'center',
        
        ],     
	['class'=>'kartik\grid\EditableColumn',
        'attribute'=>'datapagamento',
        'label'=>'Data Pagamento',
        'format' =>  ['date', 'php:d-m-Y'],
        'hAlign'=>'left',
        'editableOptions'=>[
            'header'=>' Data Pagamento',
            'formOptions' => ['action' => ['/edilizia/oneriup']],
            'inputType'=>Editable::INPUT_WIDGET,
            'widgetClass'=>'kartik\datecontrol\DateControl',
            
            'options'=>[
                  'class'=>'kartik\datecontrol\DateControl',  
                      'type'=>DateControl::FORMAT_DATE,
                'pluginOptions'=>[
                ]],
            
        ],
           
        
         'width'=>'25%',
        ]],
    ////////////////////////////////
    ///// OPZIONI DELLA TABELLA ////
    ///////////////////////////////
    'storage'=>'db', //DynaGrid::TYPE_COOKIE,
    'dbUpdateNameOnly'=>true,
    'theme'=>'panel-info',
    //'emptyCell'=>' ',
    'showPersonalize'=>false,
    //'pjax'=>true,
    'gridOptions'=>[
        'dataProvider'=>$dprovider,
        //'filterModel'=>$Search,
        'showPageSummary'=>true,
        
        'panel'=>[
		'heading'=>'<h3 class="panel-title"><b>ELENCO RATE ONERI CONCESSORI<b></h3>',
		//'before' =>  '<div style="padding-top: 7px;"><em>Elenco Documenti</em></div>',
                'before' =>  '',
                'after' => false
		],
	'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
	'headerRowOptions' => ['class' => 'kartik-sheet-style'],
    	'filterRowOptions' => ['class' => 'kartik-sheet-style'],
	'responsive'=>true,
    	'hover'=>true,
    	'bordered' => true,
    	'striped' => true,
    	'condensed' => true,
    	'persistResize' => false,
        //'submitButtonOptions'=>true,
    	'toggleDataContainer' => ['class' => 'btn-group-sm'],
    	'exportContainer' => ['class' => 'btn-group-sm'],
        'panel' => [
        'type' => GridView::TYPE_PRIMARY,
        //'heading' => $heading,
        'after'=>Html::button('Genera Piano Rateizzazione', ['class' => 'btn btn-success', 
                            'data-toggle' => 'modal', // outputs the result to the modal window
                            'data-target' => '#modalPiano', // ID modal
                            'data-title' => 'Genera Piano Rateizzazione', // custom modal title
                            'id'=>'generaButton'
                            ]) .' '.
                Html::a('<i class="fas fa-redo"></i> Stampa Prospetto', ['edilizia/pdfoneri','idpratica'=>$idpratica], ['class' => 'btn btn-info']). ' ' .
                Html::a('<i class="fas fa-redo"></i> Genera Autorizzazione Piano Word', ['edilizia/autorizzapianoword','idpratica'=>$idpratica], ['class' => 'btn btn-success']) . ' ' .
                //Html::a('<i class="fas fa-redo" name="emailsoll"></i> Invia Email Sollecito Pagamento',['class' => 'btn btn-danger']),
                //Html::button('Invia Email Sollecito Pagamento', ['id'=>'emailsoll','class' => 'btn btn-danger']), 
                //Html::button('Invia Email Sollecito Pagamento', ['value'=>Url::to(['edilizia/formemail','id'=>$id]), 'class' => 'btn btn-success','id'=>'emailsoll']) 
                 Html::button('Invia Email Sollecito Pagamento', ['class' => 'btn btn-success', 
                            'data-toggle' => 'modal', // outputs the result to the modal window
                            'data-target' => '#myModal', // ID modal
                            'data-title' => 'Invia Email Sollecito', // custom modal title
                            'id'=>'modalButton'
                            ]) . ' ' .
 //               Html::button('Invia Email Sollecito Pagamento',['class' => 'btn btn-success','id'=>'btn-custom-2']) 
                
                
                 Html::button('Invia PEC Sollecito Pagamento', ['class' => 'btn btn-success', 
                            'data-toggle' => 'modal', // outputs the result to the modal window
                            'data-target' => '#myModal', // ID modal
                            'data-title' => 'Invia PEC Sollecito', // custom modal title
                            'id'=>'pecButton'
                            ]),
 //               Html::button('Invia Email Sollecito Pagamento',['class' => 'btn btn-success','id'=>'btn-custom-2']) 
                
                ],
	'toolbar' =>  [
            ['content'=>''
//                Html::a('<i class="glyphicon glyphicon-plus"></i>', 
//			['edilizia/addfiletecnico'], ['title'=>'Aggiungi Istanza', 'class'=>'btn btn-success']) 
                
            ],
        ]
        ], // gridOption
           
    'options'=>['id'=>'dynagrid-o'] // a unique identifier is important
]);
?> 



</div>

