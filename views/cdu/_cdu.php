
<script src="js/jquery-3.5.0.min.js" type="text/javascript">
    $('#w0').yiiGridView('applyFilter');
</script>
<?php  
use kartik\dynagrid\DynaGrid;
use kartik\grid\GridView;
use kartik\select2\Select2;
use kartik\mpdf\Pdf;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Cdu;
use app\models\Committente;
use yii\web\View;
use mdm\admin\components\Helper;

//use frontend\models\EdiliziaSearch;

/// Definisco i pulsanti da visualizzare sulla Toolbar
$plus= Html::a('<i class="fas fa-plus"></i>', 
			['cdu/nuovo'], ['title'=>'Aggiungi CDU', 'class'=>'btn btn-success']);
$reset = Html::a('<i class="fas fa-undo"></i>', 
			['index'], ['data-pjax'=>0, 'class' => 'btn btn-default', 'title'=>'Resetta Impostazioni']);
$filter ='{dynagridFilter}';
$sort ='{dynagridSort}';
$setting ='{dynagrid}';
$toolbar='';
$toolbar2='';
if (isset(Yii::$app->User)) {
    $toolbar=$toolbar .Yii::$app->user->can('Inserimento Ufficio Pratica')? $plus:'';
    //$toolbar=$toolbar+Yii::$app->user->can('Inserimento Ufficio Pratica')? $plus .' ':'';
    $toolbar2=$toolbar .Yii::$app->user->can('Inserimento Ufficio Pratica')? $reset.$filter.$sort.$setting:'';
} 
            


//$tiporich=[0=>'Permesso Costruire',1=>'Scia',2=>'SuperScia',3=>'Cila',4=>'Cil',5=>'Sca',6=>'Condono edilizio Legge 47/85'];
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






// $isFa=true;
$pdfHeader='';
$pdfFooter='';
$title='Tabella Certificati di Destinazione Urbanistica';

    // $isFa below determines if export['fontAwesome'] property is set to true.
    $ExportConfig = [
        GridView::HTML => [
            'label' => Yii::t('kvgrid', 'HTML'),
            //'icon' =>  $isFa ? 'file-text' : 'floppy-saved',
            'iconOptions' => ['class' => 'text-info'],
            'showHeader' => true,
            'showPageSummary' => true,
            'showFooter' => true,
            'showCaption' => true,
            'filename' => Yii::t('kvgrid', 'grid-export'),
            'alertMsg' => Yii::t('kvgrid', 'Sara generato un file HTML per il download.'),
            'options' => ['title' => Yii::t('kvgrid', 'Hyper Text Markup Language')],
            'mime' => 'text/html',
            'config' => [
                'cssFile' => '' //https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css'
            ]
        ],
        GridView::TEXT => [
            'label' => Yii::t('kvgrid', 'Text'),
            //'icon' => $isFa ? 'file-text-o' : 'floppy-save',
            'iconOptions' => ['class' => 'text-muted'],
            'showHeader' => true,
            'showPageSummary' => true,
            'showFooter' => true,
            'showCaption' => true,
            'filename' => Yii::t('kvgrid', 'grid-export'),
            'alertMsg' => Yii::t('kvgrid', 'The TEXT export file will be generated for download.'),
            'options' => ['title' => Yii::t('kvgrid', 'Tab Delimited Text')],
            'mime' => 'text/plain',
            'config' => [
                'colDelimiter' => "\t",
                'rowDelimiter' => "\r\n",
            ]
        ],
        GridView::EXCEL => [
            'label' => Yii::t('kvgrid', 'Excel'),
            //'icon' => $isFa ? 'file-excel' : 'floppy-remove',
            //'iconOptions' => ['class' => 'text-success'],
            'showHeader' => true,
            'showPageSummary' => true,
            'showFooter' => true,
            'showCaption' => true,
            'filename' => Yii::t('kvgrid', 'tabellapratiche'),
            'alertMsg' => Yii::t('kvgrid', 'Conferma esportazione in formato EXCEL della tabella visualizzata.'),
            'options' => ['title' => Yii::t('kvgrid', 'Microsoft Excel 95+')],
            'mime' => 'application/vnd.ms-excel',
            'config' => [
                'worksheet' => Yii::t('kvgrid', 'TabellaPratiche'),
                'cssFile' => ''
            ]
        ],
//        GridView::PDF => [
//            'label' => Yii::t('kvgrid', 'PDF'),
//            //'icon' => $isFa ? 'file-pdf-o' : 'floppy-disk',
//            'iconOptions' => ['class' => 'text-danger'],
//            'showHeader' => true,
//            'showPageSummary' => true,
//            'showFooter' => true,
//            'showCaption' => true,
//            'filename' => Yii::t('kvgrid', 'grid-export'),
//            'alertMsg' => Yii::t('kvgrid', 'Conferma esportazione in formato PDF della tabella visualizzata.'),
//            'options' => ['title' => Yii::t('kvgrid', 'Portable Document Format')],
//            'mime' => 'application/pdf',
//            'config' => [
//                'mode' => 'c',
//                'format' => 'A4-L',
//                'destination' => 'D',
//                'marginTop' => 20,
//                'marginBottom' => 20,
//                'cssInline' => '.kv-wrap{padding:20px;}' .
//                    '.kv-align-center{text-align:center;}' .
//                    '.kv-align-left{text-align:left;}' .
//                    '.kv-align-right{text-align:right;}' .
//                    '.kv-align-top{vertical-align:top!important;}' .
//                    '.kv-align-bottom{vertical-align:bottom!important;}' .
//                    '.kv-align-middle{vertical-align:middle!important;}' .
//                    '.kv-page-summary{border-top:4px double #ddd;font-weight: bold;}' .
//                    '.kv-table-footer{border-top:4px double #ddd;font-weight: bold;}' .
//                    '.kv-table-caption{font-size:1.5em;padding:8px;border:1px solid #ddd;border-bottom:none;}',
//                'methods' => [
//                    'SetHeader' => [
//                        ['odd' => $pdfHeader, 'even' => $pdfHeader]
//                    ],
//                    'SetFooter' => [
//                        ['odd' => $pdfFooter, 'even' => $pdfFooter]
//                    ],
//                ],
//                'options' => [
//                    'title' => $title,
//                    'subject' => Yii::t('kvgrid', 'PDF export generated by kartik-v/yii2-grid extension'),
//                    'keywords' => Yii::t('kvgrid', 'krajee, grid, export, yii2-grid, pdf')
//                ],
//                'contentBefore'=>'',
//                'contentAfter'=>''
//            ]
//        ],
    ];



echo DynaGrid::widget([
   	'columns'=>[
            [
        'class'=>'kartik\grid\ActionColumn',
        'order'=>DynaGrid::ORDER_FIX_LEFT,
		'template' => '{delete}',
                'header'=>'',
                'width'=>'2%',
        'buttons'=>['delete' => function ($url) {
                  return Html::a('<span class="fas fa-trash"></span>', $url,
                      [  
                         'title' => 'Elimina',
                         'data-confirm' => "Sei sicuro di volere eliminare questa istanza?",
                         'data-method' => 'post',
                         //'data-pjax' => 0
                      ]);
             }]
    ],
	[   'class'=>'\kartik\grid\SerialColumn', 
	    'order'=>DynaGrid::ORDER_FIX_LEFT,
            'width'=>'3%',         
    ],
	['attribute'=>'NumeroProtocollo',
         'label'=>'Protocollo',
         'width'=>'4%',
 	'hAlign'=>'center', 
	],
    ['attribute'=>'DataProtocollo',
        'format' =>  ['date', 'php:d-m-Y'],
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
    
        //The use of single time selector
        'width'=>'9%'
    ],
                     
    ['attribute'=>'richiedente', //committente.nomeCompleto',
    'value'=>'richiedente.nomeCompleto',
    'width'=>'15%',
    'label'=>'Richiedente'
    ],     
    [
        'attribute'=>'foglio1',
        'width'=>'4%',
        'hAlign'=>'center',   
    ],
    [
        'attribute'=>'particelle1',
        //'width'=>'15%',
    //'hAlign'=>'center',    
    ],
	[
        'attribute'=>'foglio2',
        'width'=>'5%',
    ],
    [
        'attribute'=>'particelle2',
        'width'=>'15%',
    ],
	[
        'attribute'=>'foglio3',
        'width'=>'5%',
        'visible'=>false,
    ],
    [
        'attribute'=>'particelle3',
        'width'=>'10%',
        'visible'=>false,
    ],
	[
        'attribute'=>'foglio4',
        'width'=>'5%',
        'visible'=>false,
    ],
    [
       'attribute'=>'particelle4',
       'width'=>'10%',
       'visible'=>false,
    ],
    ['attribute'=>'tipodestinatario',
        //'label'=>'Longitudine',
        'visible'=>false],
    ['attribute'=>'esenzione',
        'visible'=>false,
        'width'=>'5%',
    ],
    [
        'class'=>'kartik\grid\ActionColumn',
        'order'=>DynaGrid::ORDER_FIX_RIGHT,
		//'template' => Helper::filterActionColumn('{delete}'),
            'template' => '{gestione}',
            'header'=>'',
            'width'=>'1%',
            'contentOptions' => ['style' => 'font-size:16px;'],
            'buttons'=>['gestione' => function ($url,$model) {
                  return Html::a('<span class="fas fa-edit"></span>', Url::toRoute(['cdu/atti','idcdu'=>$model->idcdu]),
                      [  
                         'title' => 'Gestione',
                      ]);
             }]
        
    ],  
    [
        'class'=>'kartik\grid\ActionColumn',
        'order'=>DynaGrid::ORDER_FIX_RIGHT,
	    'template' => '{update}', //Helper::filterActionColumn('{update}'),
        'header'=>'',
        'width'=>'5%',
    ],
    ['class'=>'kartik\grid\CheckboxColumn', 
	'noWrap'=>true,
	 //'filterType'=>GridView::FILTER_CHECKBOX,
	 'order'=>DynaGrid::ORDER_FIX_RIGHT,
     'width'=>'1%',
    ]],
    ////////////////////////////////
    ///// OPZIONI DELLA TABELLA ////
    ///////////////////////////////
    'storage'=>'db', //DynaGrid::TYPE_COOKIE,
    'dbUpdateNameOnly'=>true,
    'theme'=>'panel-info',
    //'emptyCell'=>' ',
    'showPersonalize'=>true,
    //'pjax'=>true,
    'gridOptions'=>[
        'dataProvider'=>$Provider,
        'filterModel'=>$Search,
        'showPageSummary'=>false,
        'panel'=>[
		    'heading'=>'<b>ELENCO RICHIESTE CERTIFICATI DESTINAZIONE URBANISTICA</b>',
		    'before' =>  '<div style="padding-top: 7px;"><em>Elenco CDU</em></div>',
            'after' => false
		],
	    'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
	    // 'headerRowOptions' => ['class' => 'kartik-sheet-style'],
    	// 'filterRowOptions' => ['class' => 'kartik-sheet-style'],
	    'responsive'=>true,
    	'hover'=>true,
    	'bordered' => true,
    	'striped' => true,
    	'condensed' => true,
    	'persistResize' => false,
        //'submitButtonOptions'=>true,
    	// 'toggleDataContainer' => ['class' => 'btn-group-sm'],
    	// 'exportContainer' => [['class' => 'btn-group-sm']],
        'export'=>['header'=>'<li role="presentation" class="dropdown-header">Esporta in formato:</li>'],
        'exportConversions'=>[
           ['from'=>GridView::ICON_ACTIVE, 'to'=>'Si'],
           ['from'=>GridView::ICON_INACTIVE, 'to'=>'No'],
        ],
        'exportConfig' => $ExportConfig,
        //'fontAwesome'=>true,
	    'toolbar' =>  [
                ['content'=>$toolbar
                    
                ],
                ['content'=>Html::tag('div','',['style' => ['margin-left'=>'5px']])],
                ['content'=>$toolbar2],
                '{export}',
            ]
        ], // gridOption
    'options'=>['id'=>'dynagrid-13'], // a unique identifier is important
    
]);
 
 
$this->registerJs("
    if (typeof bootstrap === 'undefined') {
        window.bootstrap = {};
        bootstrap.Modal = function(el) { return $(el).modal(); };
    }
", \yii\web\View::POS_HEAD);
?>
 
 