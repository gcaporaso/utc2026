
<script type="text/javascript">
$('#w0').yiiGridView('applyFilter');
</script>
<?php  
//use yii\grid\GridView;
use kartik\dynagrid\DynaGrid;
use kartik\grid\GridView;
use kartik\select2\Select2;
use Mpdf\Mpdf;
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

//use frontend\models\EdiliziaSearch;

/// Definisco i pulsanti da visualizzare sulla Toolbar
$plus= Html::a('<i class="fas fa-plus"></i>', 
			['edilizia/nuova'], ['title'=>'Aggiungi Istanza', 'class'=>'btn btn-success']);
$reset = Html::a('<i class="fas fa-undo"></i>', 
			['index'], ['data-pjax'=>0, 'class' => 'btn btn-default', 'title'=>'Resetta Impostazioni']);
$filter ='{dynagridFilter}';
$sort ='{dynagridSort}';
$setting ='{dynagrid}';
$toolbar='';
$toolbar2='';
if (isset(Yii::$app->User)) {
    $toolbar=$toolbar .Yii::$app->user->can('Inserimento Ufficio Pratica')? $plus :'';
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
$title='Tabella Pratiche Edilizie';

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
        'order'=>DynaGrid::ORDER_FIX_RIGHT,
		//'template' => Helper::filterActionColumn('{delete}'),
                'template' => '{gestione}',
                'header'=>'',
                'width'=>'1%',
                'contentOptions' => ['style' => 'font-size:16px;'],
        'buttons'=>['gestione' => function ($url,$model) {
                  return Html::a('<span class="fas fa-edit"></span>', Url::toRoute(['edilizia/allegati','idpratica'=>$model->edilizia_id]),
                      [  
                         'title' => 'Gestione',
                          
                         //'data-confirm' => "Sei sicuro di volere eliminare questa istanza?",
                         //'data-method' => 'post',
                         //'data-pjax' => 0
                      ]);
             }]
        
    ],  
//    ['class'=>'kartik\grid\ActionColumn', 
//	'noWrap'=>true,
//	'order'=>DynaGrid::ORDER_FIX_LEFT,
//        'dropdown'=>true,
//        'header'=>'',
//        'dropdownMenu'=>['class'=>'text-left'],
//        'dropdownOptions' => ['class' => 'float-right'],
//        'dropdownButton'=>['label'=>'', 'class'=>'btn btn-info'],
//        'template' => '{soggetti}{oneri}{allegati}{atti}', //{procedura}',
//        'buttons' => [
//               'soggetti' => function ($url, $model) {
//                    $title = 'Soggetti';
//                    $options = []; // you forgot to initialize this
//                    $icon = '<span class="glyphicon glyphicon-user"></span>';
//                    $label = $icon . ' ' . $title;
//                    $url = Url::toRoute(['edilizia/soggetti','idpratica'=>$model->edilizia_id]);
//                    $options['tabindex'] = '-1';
//                    return '<li>' . Html::a($label, $url, $options) . '</li>' . PHP_EOL;
//                },
//               'oneri' => function ($url, $model) {
//                    $title = 'Oneri';
//                    $options = []; // you forgot to initialize this
//                    $icon = '<span class="glyphicon glyphicon-eur"></span>';
//                    $label = $icon . ' ' . $title;
//                    $url = Url::toRoute(['edilizia/oneri','idpratica'=>$model->edilizia_id]);
//                    $options['tabindex'] = '-1';
//                    return '<li>' . Html::a($label, $url, $options) . '</li>' . PHP_EOL;
//                },
//                  'allegati' => function ($url, $model) {
//                    $title = 'Allegati';
//                    $options = []; // you forgot to initialize this
//                    $icon = '<span class="far fa-file"></span>';
//                    $label = $icon. ' ' . $title;
//                    $url = Url::toRoute(['edilizia/allegati','idpratica'=>$model->edilizia_id]);
//                    $options['tabindex'] = '-1';
//                    return '<li>' . Html::a($label, $url, $options) . '</li>' . PHP_EOL;
//                },   
//                    'atti' => function ($url, $model) {
//                    $title = 'atti';
//                    $options = []; // you forgot to initialize this
//                    $icon = '<span class="far fa-file-word"></span>';
//                    $label = $icon. ' ' . $title;
//                    $url = Url::toRoute(['edilizia/toword','idpratica'=>$model->edilizia_id]);
//                    $options['tabindex'] = '-1';
//                    return '<li>' . Html::a($label, $url, $options) . '</li>' . PHP_EOL;
//                },  
////                    'procedura' => function ($url, $model) {
////                    $title = 'procedura';
////                    $options = []; // you forgot to initialize this
////                    $icon = '<span class="fa fa-sort-amount-asc"></span>';
////                    $label = $icon. ' ' . $title;
////                    $url = Url::toRoute(['edilizia/procedura','idpratica'=>$model->edilizia_id]);
////                    $options['tabindex'] = '-1';
////                    return '<li>' . Html::a($label, $url, $options) . '</li>' . PHP_EOL;
////                },  
//                ],
//        //'deleteOptions' => ['label' => '<i class="glyphicon glyphicon-update">Soggetti Coinvolti</i>']
        
//    ],                     

            [
        'class'=>'kartik\grid\ActionColumn',
        'order'=>DynaGrid::ORDER_FIX_LEFT,
		'template' => Helper::filterActionColumn('{delete}'),
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
		'order'=>DynaGrid::ORDER_FIX_LEFT],
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
        'width'=>'8%'],
                     
        ['attribute'=>'richiedente', //committente.nomeCompleto',
        'value'=>'richiedente.nomeCompleto',
//        'filterType'=>GridView::FILTER_SELECT2,
//      
//        'filter'=>[1=>'Caporaso Giuseppe (16-09-1964)', 2=>'Barbato Francesco (02-11-1965)'], //ArrayHelper::map(Committente::find()->asArray()->all(), 'idcommittente', 'nomeCompleto'), 
//        'filterWidgetOptions'=>[
//            'pluginOptions'=>['allowClear'=>true],
//        ],
//        'filterInputOptions'=>['placeholder'=>'Filtra per Richiedente..'],
//        'format'=>'raw',
        'width'=>'10%',
	'label'=>'Richiedente'],     
                     
	[//'class'=>'kartik\grid\EditableColumn',
         'attribute'=>'DescrizioneIntervento',
         //'noWrap'=>false,
         'contentOptions'=>['style'=>'word-wrap: break-word;white-space:pre-line;'],
         'width'=>'22%',
         'contentOptions' => []
//         'editableOptions'=>[
//             'header'=>'Descrizione ..',
//             //'formOptions' => ['action' => ['/edilizia/index']],
//             //'inputType'=>\kartik\editable\Editable::,
//             
//            ]
        ],
                     
        [//'class' => 'yii\grid\DataColumn',
        'attribute'=>'statoPratica',
        'value'=>'statoPratica.descrizione',
        //'value' => 'titolo.Descrizione',
        'hAlign'=>'center',
        'label'=>'Stato Pratica',
        'width'=>'7%',
        'filter' => ArrayHelper::map(app\models\StatoEdilizia::find()->asArray()->all(), 'idstato_edilizia', 'descrizione'),
        ],
                     
                     
        ['attribute'=>'NumeroTitolo',
         'label'=>'Numero Permesso',
         'hAlign'=>'center',
         'value' =>  function($model)
               {
                 if ($model->NumeroTitolo>0) {
                     return $model->NumeroTitolo;
                 } else {
                     return '';
                 }
               },
         'width'=>'4%',
            ],
        ['class'=>'kartik\grid\DataColumn',
         'attribute'=>'DataTitolo',
         'label'=>'Data Permesso', 
          'value' => function($model) {
                   if (!empty($model->DataTitolo)) {
                       return Yii::$app->formatter->asDate($model->DataTitolo, 'dd-MM-yyyy');
                      //return $model->DataTitolo;
                   } else {
                       return '';
                   }
            },
         //'format' =>  ['date', 'php:d-m-Y'],
         'filterType' => GridView::FILTER_DATE_RANGE,
         'filterWidgetOptions' => [
                'startAttribute' => 'data_inizio_titolo', //Attribute of start time
                'endAttribute' => 'data_fine_titolo',   //The attributes of the end time
                'convertFormat'=>true, // Importantly, true uses the local - > format time format to convert PHP time format to js time format.
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',//Date format
                    'locale'=>['format' => 'd-m-Y'], //php formatting time
                ]
            ],   
         'width'=>'8%',
         'hAlign'=>'center',
         //'nullDisplay'=>' '
         
            ],
                     
        ['attribute'=>'IndirizzoImmobile',
         'width'=>'10%',
            ],
                     
    [//'class' => 'yii\grid\DataColumn',
        'attribute'=>'titolo',
        'value'=>'titolo.TITOLO',
        //'value' => 'titolo.Descrizione',
        'hAlign'=>'center',
        'label'=>'Titolo',
        'width'=>'8%',
//        'value'=>function ($model) { 
//            return $model->titolo->TITOLO;
//        },
        'filter' => Arrayhelper::map(TitoloEdilizio::find()->orderBy('TITOLO')->asArray()->all(), 'titoli_id', 'TITOLO'),
//        'filter' => Html::activeDropDownList($Search, 'TipoRichiesta', ArrayHelper::map(Titoloedilizio::find()->asArray()->all(),
//                'titoli_id', 'TITOLO'),['class'=>'form-control','prompt' => 'Titolo ...']),
        //'filterType'=>GridView::FILTER_SELECT2,
                
//        'filter'=>ArrayHelper::map(Titoloedilizio::find()->orderBy('DESCRIZIONE')->asArray()->all(), 'titoli_id', 'DESCRIZIONE'), 
//        'filterWidgetOptions'=>[
//            'pluginOptions'=>['allowClear'=>true],
//        ],
//        'filterInputOptions'=>['placeholder'=>'Titolo ..'],
//        'format'=>'raw'
    ],
                
    
    [
        'class'=>'kartik\grid\BooleanColumn',
        //'label'=>'Sanatoria',
        'attribute'=>'Sanatoria', 
        'vAlign'=>'middle',
        'trueLabel' => 'Si',
        'falseLabel' => 'No',
        'trueIcon' => '<span class="glyphicon glyphicon-ok text-success"></span>',
        'falseIcon'=>' '
    ],
    [
        'class'=>'kartik\grid\BooleanColumn',
        'label'=>'Onerosa',
        'attribute'=>'TitoloOneroso', 
        'vAlign'=>'middle',
        'trueLabel' => 'Si',
        'falseLabel' => 'No',
        'showNullAsFalse'=>true,
        'falseIcon'=>' '
        //'trueIcon' => '<span class="glyphicon glyphicon-ok text-success"></span>',
        //'falseIcon' => '<span></span>'
    ],
    ['attribute'=>'CatastoFoglio',
        'visible'=>false,
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
        'visible'=>false],
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
        'visible'=>false],
    ['attribute'=>'DerogaUrbanistica',
        'visible'=>false],
    ['attribute'=>'Oneri_Costruzione',
        'label'=>'Oneri Costruzione',
        'visible'=>false],
    ['attribute'=>'Oneri_Urbanizzazione',
        'label'=>'Oneri Urbanizzazione',
        'visible'=>false],
    ['attribute'=>'Oneri_Pagati',
        'label'=>'Oneri Pagati',
        'visible'=>false],
    ['attribute'=>'Latitudine',
        'label'=>'Latitudine',
        'visible'=>false],
    ['attribute'=>'Longitudine',
        'label'=>'Longitudine',
        'visible'=>false],
    ['attribute'=>'residuo',
        'label'=>'Residuo Oneri',
        //'filter'=> array('0'=>'Tutti','1'=>'Maggiore di 0','2'=>'0'), 
        'visible'=>false,
        'width'=>'5%',
    ],
    
    [
        'class'=>'kartik\grid\ActionColumn',
        'order'=>DynaGrid::ORDER_FIX_RIGHT,
	'template' => Helper::filterActionColumn('{view} {update}'),
        'header'=>'',
        'width'=>'18%',
//                'buttons'=>[
//                    'allegati' => function ($url, $model) {
//                    $url = Url::to(['edilizia/allegati', 'id' => $model->edilizia_id]);
//                    return Html::a('<span class="fa fa-file"></span>', $url, ['title' => 'allegati']);
//                    },
//                    'toword' => function ($url, $model) {
//                    $url = Url::to(['edilizia/toword', 'id' => $model->edilizia_id]);
//                    return Html::a('<span class="fa fa-file-word-o"></span>', $url, ['title' => 'modulistica']);
//                    },
//                        ],

    ],
    ['class'=>'kartik\grid\CheckboxColumn', 
	'noWrap'=>true,
	 //'filterType'=>GridView::FILTER_CHECKBOX,
	 'order'=>DynaGrid::ORDER_FIX_RIGHT,
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
		'heading'=>'<h3 class="panel-title"><b>ELENCO PRATICHE EDILIZIE<b></h3>',
		'before' =>  '<div style="padding-top: 7px;"><em>Elenco Pratiche</em></div>',
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
    	'exportContainer' => [['class' => 'btn-group-sm']],
        'export'=>['header'=>'<li role="presentation" class="dropdown-header">Esporta in formato:</li>'],
        'exportConversions'=>[
           ['from'=>GridView::ICON_ACTIVE, 'to'=>'Si'],
           ['from'=>GridView::ICON_INACTIVE, 'to'=>'No'],
        ],
        'exportConfig' => $ExportConfig,
        //'fontAwesome'=>true,
	'toolbar' =>  [
//            ['content'=>Select2::widget([
//  		'name' => 'ApplicaFiltro',
//		'size' => Select2::MEDIUM,
//		'data' => $model->filterList, //[1 => "First", 2 => "Second", 3 => "Third", 4 => "Fourth", 5 => "Fifth"],
//		'options' => ['multiple' => false, 
//                    'placeholder' => 'Seleziona un Filtro ...'],
//		'pluginOptions' => ['allowClear' => true],
//				]),
//            ],
            ['content'=>Html::a('<i class="fas fa-list"></i>', 
			['edilizia/elencopermessi'], ['title'=>'Elenco Permessi Costruire Anno Corrente', 'class'=>'btn btn-primary'])
            ],
            ['content'=>Html::tag('div','',['style' => ['margin-left'=>'5px']])],
            ['content'=>Html::a('<i class="fas fa-list-ol"></i>', 
	       ['edilizia/elencopratiche'], ['title'=>'Elenco Numero Pratiche per Anno', 'class'=>'btn btn-secondary'])
            ],
            ['content'=>Html::tag('div','',['style' => ['margin-left'=>'20px']])],
	    ['content'=>$toolbar],
            ['content'=>Html::tag('div','',['style' => ['margin-left'=>'20px']])],
            ['content'=>$toolbar2],
            ['content'=>Html::tag('div','',['style' => ['margin-left'=>'20px']])],
            '{export}',
        ]
        ], // gridOption
    'options'=>['id'=>'dynagrid-2'] // a unique identifier is important
]);
 
 
