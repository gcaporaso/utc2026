
<?php  
//use yii\grid\GridView;
use kartik\dynagrid\DynaGrid;
//use kartik\grid\GridView;
//use kartik\select2\Select2;
//use kartik\mpdf\Pdf;
//use yii\helpers\ArrayHelper;
////use yii\data\ActiveDataProvider;
//use yii\helpers\Html;
//use yii\helpers\Url;
////use yii\widgets\LinkPager;
////use mdm\admin\components\Configs; 
//use app\models\Edilizia;
//use app\models\Committente;
//use app\models\TitoloEdilizio;
//use kartik\daterange\DateRangePicker;
use yii\web\View;
//use mdm\admin\components\Helper;
//use app\models\SeduteCommissioni;
//use app\models\Commissioni;


$script = <<< JS
 $('input[name="daterg1"]').on('cancel.daterangepicker', function(ev, picker) {
        alert('pulisco');
      $(this).val('');
});
 $('#daterg1').on('click', function(ev, picker) {
        alert('click');
       $(this).val('');
});


JS;
$this->registerJs($script, View::POS_READY);


$title='Selezione Pratiche Edilizie';


echo DynaGrid::widget([
   	'columns'=>[
	[   'class'=>'\kartik\grid\SerialColumn', 
		'order'=>DynaGrid::ORDER_FIX_LEFT],
	['attribute'=>'NumeroProtocollo',
         'label'=>'Protocollo',
         'width'=>'5%',
 	'hAlign'=>'center', 
	],
        ['attribute'=>'DataProtocollo',
         'format' =>  ['date', 'php:d-m-Y'],
         'hAlign'=>'center',
        'width'=>'11%'],
        ['attribute'=>'richiedente', //committente.nomeCompleto',
        'value'=>'richiedente.nomeCompleto',
        'width'=>'10%',
	'label'=>'Richiedente'],     
	['attribute'=>'DescrizioneIntervento',
         //'noWrap'=>false,
         'contentOptions'=>['style'=>'word-wrap: break-word;white-space:pre-line;'],
         'width'=>'20%',
        ],
        ['attribute'=>'IndirizzoImmobile',
         'width'=>'10%',
        ],
        ['attribute'=>'titolo',
        'value'=>'titolo.TITOLO',
        'hAlign'=>'center',
        'label'=>'Titolo',
        'width'=>'8%',
        ],
        ['attribute'=>'CatastoFoglio',
        'hAlign'=>'center'],
        ['attribute'=>'CatastoParticella',
        'hAlign'=>'center',
        'value' =>  function($model)
               {if ($model->CatastoParticella===null) {
                     return '';
                 } else {
                     return $model->CatastoParticella;
                 }
               },
        ],
        ['attribute'=>'CatastoSub',
        'value' =>  function($model)
               {
                 if ($model->CatastoSub===null) {
                     return '';
                 } else {
                     return $model->CatastoSub;
                     
                 }
               },
        'hAlign'=>'center',
        ],
        ['class'=>'kartik\grid\CheckboxColumn', 
	'noWrap'=>true,
	 //'filterType'=>GridView::FILTER_CHECKBOX,
	'order'=>DynaGrid::ORDER_FIX_RIGHT,
        'width'=>'5%',
        ]
    ],
    ////////////////////////////////
    ///// OPZIONI DELLA TABELLA ////
    ///////////////////////////////
    'storage'=>'db', //DynaGrid::TYPE_COOKIE,
    'dbUpdateNameOnly'=>true,
    'theme'=>'panel-info',
    'showPersonalize'=>false,
    'gridOptions'=>[
        'dataProvider'=>$Provider,
        //'filterModel'=>$Search,
        'showPageSummary'=>false,
        'toolbar' => [
        ['content'=>''],
        ],
        'panel'=>[
		'heading'=>false, //'<h3 class="panel-title"><b>SELEZIONE PRATICHE DA SOTTOPORRE AD ESAME DA PARTE DELLA '. $scommissione . ' DEL '. $sdata .'<b></h3>',
		'before' => false, // '<div style="padding-top: 7px;"><em>Elenco Pratiche</em></div>',
                'after' => false, // Html::button('Aggiungi Pratiche Selezionate', ['class' => 'btn btn-primary','style'=>'float:right;margin-top:20px','id'=>'selButton']),
                'footer'=> true 
		],
	'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
	//'headerRowOptions' => ['class' => 'kartik-sheet-style'],
    	'filterRowOptions' => ['class' => 'kartik-sheet-style'],
	'responsive'=>true,
    	'hover'=>true,
    	'bordered' => true,
    	'striped' => true,
    	'condensed' => true,
    	'persistResize' => false,
    	//'toggleDataContainer' => ['class' => 'btn-group-sm'],
        ], // gridOption
    'options'=>['id'=>'dynagrid-sel2'] // a unique identifier is important
]);
 
 
