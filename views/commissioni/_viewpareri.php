
<script src="js/jquery-3.3.1.min.js" type="text/javascript">
$('#w0').yiiGridView('applyFilter');
</script>
<?php  
//use yii\grid\GridView;
use kartik\dynagrid\DynaGrid;
use kartik\grid\GridView;
use kartik\select2\Select2;
use kartik\mpdf\Pdf;
use yii\helpers\ArrayHelper;
//use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
//use yii\widgets\LinkPager;
//use mdm\admin\components\Configs; 
use app\models\Edilizia;
use app\models\Committente;
use app\models\TitoloEdilizio;
//use kartik\daterange\DateRangePicker;
use yii\web\View;
use mdm\admin\components\Helper;
use app\models\SeduteCommissioni;
use app\models\Commissioni;
//use app\models\PareriCommissioni;


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



//$scommissione=strtoupper(Commissioni::findOne($idcommissione)->tipologia->descrizione);
//$sdata=date('d-m-Y',strtotime(SeduteCommissioni::findOne($idseduta)->dataseduta));
?>

<?php
echo DynaGrid::widget([
   	'columns'=>[
	[   'class'=>'\kartik\grid\SerialColumn', 
	'order'=>DynaGrid::ORDER_FIX_LEFT],
        [
        'class'=>'kartik\grid\ActionColumn',
        'order'=>DynaGrid::ORDER_FIX_LEFT,
		'template' => '{delete}',
                'header'=>'',
                'width'=>'2%',
                 'buttons' => [
            'delete' => function ($url, $model) {
                return Html::a('<span class="fas fa-trash"></span>', $url, [
                'title' => 'cancella',
                 'data-confirm' => "Sei sicuro di volere eliminare questa pratica dalla commissione?",
                ]);}
            ],
        'urlCreator' => function ($action, $model, $key, $index) use($idcommissione,$idseduta) {
            if ($action === 'delete') {
            $url = Url::toRoute(['commissioni/cancellapraticaseduta','idcommissione'=>$idcommissione,
                'idseduta'=>$idseduta,'pratica'=>$model->idpareri_commissioni]); // your own url generation logic
            return $url;
            }
        }
        ],
        [
        'class' => 'kartik\grid\ExpandRowColumn',
        'width' => '50px',
        'value' => function ($model, $key, $index, $column) {
        return GridView::ROW_COLLAPSED;
        },
        // uncomment below and comment detail if you need to render via ajax
        // 'detailUrl' => Url::to(['/site/book-details']),
        'detail' => function ($model, $key, $index, $column) {
        return Yii::$app->controller->renderPartial('_expand-parere-detail', ['model' => $model]);
        },
        'headerOptions' => ['class' => 'kartik-sheet-style'], 
        'expandOneOnly' => true
        ],    
	['attribute'=>'pratica',
         'value'=>'pratica.NumeroProtocollo',
         'label'=>'Protocollo',
         'hAlign'=>'center',
         //'contentOptions'=>['style'=>'word-wrap: break-word;white-space:pre-line;'],
         'width'=>'5%',
        ],
        ['attribute'=>'pratica',
         'value'=>'pratica.DataProtocollo',
         'format' =>  ['date', 'php:d-m-Y'],
         'label'=>'Data Protocollo',
         'hAlign'=>'center',
        'filterType' => GridView::FILTER_DATE_RANGE,
        'filterWidgetOptions' => [
                'startAttribute' => 'data_inizio_protocollo', //Attribute of start time
                'endAttribute' => 'data_fine_protocollo',   //The attributes of the end time
                'convertFormat'=>true, // Importantly, true uses the local - > format time format to convert PHP time format to js time format.
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',//Date format
                    'locale'=>['format' => 'd-m-Y'], //php formatting time
                ]
            ],
        'width'=>'11%'],
                
                
                
        ['attribute'=>'pratica',
         'value'=>'pratica.richiedente.nomeCompleto',
         'label'=>'Richiedente',
         'width'=>'20%',
        ],
        ['attribute'=>'pratica',
         'value'=>'pratica.DescrizioneIntervento',
         'label'=>'Descrizione Intervento',
        ],
        ['attribute'=>'tipoParere',
         'value'=>'tipoParere.esitoparere',
         'label'=>'Parere',
         'width'=>'15%',
        ],
        [
        'class'=>'kartik\grid\ActionColumn',
        'order'=>DynaGrid::ORDER_FIX_RIGHT,
		'template' => '{modifica}',
                'header'=>'',
                'width'=>'2%',
        'buttons'=>['modifica' => function ($url) {
                  return Html::a('<span class="fas fa-pencil-alt"></span>', $url,
                      [  
                         'title' => 'Modifica',
                         //'data-confirm' => "Sei sicuro di volere eliminare questa seduta?",
                         'data-method' => 'post',
                         //'data-pjax' => 0
                      ]);
             }],
        'urlCreator' => function ($action, $model) use($idcommissione,$idseduta) {
            if ($action === 'modifica') {
            $url = Url::toRoute(['commissioni/modificaparere','idparere'=>$model->idpareri_commissioni]); // your own url generation logic
            return $url;
            }
        }
        ],
    ],
    ////////////////////////////////
    ///// OPZIONI DELLA TABELLA ////
    ///////////////////////////////
    'storage'=>'db', //DynaGrid::TYPE_COOKIE,
    'dbUpdateNameOnly'=>true,
    'theme'=>'panel-info',
    'showPersonalize'=>false,
    'gridOptions'=>[
        'dataProvider'=>$ProviderPareri,
        //'filterModel'=>$Search,
        'showPageSummary'=>false,
        'toolbar' => [
        ['content'=>''],
        ],
        'panel'=>[
		'heading'=>false, //'<h3 class="panel-title"><b>ELENCO PRATICHE DELLA '. $scommissione . ' DEL '. $sdata .'<b></h3>',
		'before' => false, // '<div style="padding-top: 7px;"><em>Elenco Pratiche</em></div>',
                'after'=>false,
                'footer'=>false
                //'after'=>Html::a('<i class="fas fa-redo"></i> Compila Verbale Commissione', ['commissioni/compilaverbale','idseduta'=>$idseduta], ['class' => 'btn btn-success']),
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
    'options'=>['id'=>'dynagrid-par1'] // a unique identifier is important
]);
 
 
