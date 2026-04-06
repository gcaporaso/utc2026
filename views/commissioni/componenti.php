<?php
/* @var $this yii\web\View */
use kartik\dynagrid\DynaGrid;
use kartik\grid\GridView;
//use kartik\select2\Select2;
use yii\helpers\Html;
//use mdm\admin\components\Helper;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/// Definisco i pulsanti da visualizzare sulla Toolbar
$plus= Html::a('<i class="fas fa-plus"></i>', 
			['commissioni/nuovocomponente'], ['title'=>'Aggiungi Componente Commissione', 'class'=>'btn btn-success']);
$reset = Html::a('<i class="fas fa-undo"></i>', 
			['index'], ['data-pjax'=>0, 'class' => 'btn btn-default', 'title'=>'Resetta Impostazioni']);
$filter ='{dynagridFilter}';
$sort ='{dynagridSort}';
$setting ='{dynagrid}';
$toolbar=$plus .' '.$reset;
$toolbar2=$filter.$sort.$setting;
//if (isset(Yii::$app->User)) {
//    $toolbar=$toolbar .Yii::$app->user->can('Inserimento Ufficio Pratica')? $plus .' '.$reset:'';
//    //$toolbar=$toolbar+Yii::$app->user->can('Inserimento Ufficio Pratica')? $plus .' ':'';
//    $toolbar2=$toolbar .Yii::$app->user->can('Inserimento Ufficio Pratica')? $filter.$sort.$setting:'';
//} 


echo DynaGrid::widget([
   	'columns'=>[
//        [
//        'class'=>'kartik\grid\ActionColumn',
//        'order'=>DynaGrid::ORDER_FIX_LEFT,
//		'template' => '{delete}',
//                'header'=>'',
//                'width'=>'3%',
//        'buttons'=>['delete' => function ($url) {
//                  return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url,
//                      [  
//                         'title' => 'Elimina',
//                         'data-confirm' => "Sei sicuro di volere eliminare questa istanza?",
//                         'data-method' => 'post',
//                         //'data-pjax' => 0
//                      ]);
//             }],
//        'urlCreator' => function ($action, $model, $key, $index) {
//            if ($action === 'delete') {
//            $url = Url::toRoute(['commissioni/deleteComponente','idcomponente'=>$model->idcomponenti_commissioni]); // your own url generation logic
//            return $url;
//            }
//            }
//        ],
        ['attribute'=>'Cognome',
         'hAlign'=>'center',
        'width'=>'15%'],
        ['attribute'=>'Nome',
            'hAlign'=>'center',
        'width'=>'15%',
        ],
        ['attribute'=>'DataNascita',
         'hAlign'=>'center',
        'width'=>'10%',
        ],
        ['attribute'=>'IndirizzoResidenza',
        'width'=>'13%',
        ],
        ['attribute'=>'ComuneResidenza',
        'width'=>'13%',
        ],
        ['attribute'=>'ProvinciaResidenza',
         'hAlign'=>'center',
        'width'=>'6%',
        ],
        ['attribute'=>'telefono',
        'width'=>'10%'
        ],
        ['attribute'=>'cellulare',
        'width'=>'10%'
        ],
        ['attribute'=>'email',
        'width'=>'10%'
        ],
        ['attribute'=>'pec',
        'width'=>'10%'
        ],
        ['attribute'=>'competenze',
         'value'=>'competenze.titolo',   
         'hAlign'=>'center',
        'width'=>'11%',
        'filter' => ArrayHelper::map(app\models\TitoloComponente::find()->asArray()->all(), 'idtitolo_componente', 'titolo'),
        ],
        [
        'class'=>'kartik\grid\ActionColumn',
        'order'=>DynaGrid::ORDER_FIX_RIGHT,
	'template' => '{update}',
        'header'=>'',
        'width'=>'5%',
//        'buttons' => [
//            'update' => function ($url, $model) {
//                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
//                'title' => Yii::t('app', 'update'),
//                ]);}
//            ],
        'urlCreator' => function ($action, $model, $key, $index) {
            if ($action === 'update') {
            $url = Url::toRoute(['commissioni/updatecomponente','idcomponente'=>$model->idcomponenti_commissioni]); // your own url generation logic
            return $url;
            }
            if ($action === 'view') {
            $url = Url::toRoute(['commissioni/viewcomponente','idcomponente'=>$model->idcomponenti_commissioni]); // your own url generation logic
            return $url;
            }
        }
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
		'heading'=>'<h3 class="panel-title"><b>ELENCO COMPONENTI COMMISSIONI<b></h3>',
		'before' =>  '<div style="padding-top: 7px;"><em>Elenco Componenti</em></div>',
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
        //'exportConfig' => $ExportConfig,
        //'fontAwesome'=>true,
	'toolbar' =>  [
	    ['content'=>$toolbar
                
            ],
            ['content'=>Html::tag('div','',['style' => ['margin-left'=>'5px']])],
            ['content'=>$toolbar2],
            '{export}',
        ]
        ], // gridOption
    'options'=>['id'=>'dynagrid-co9'] // a unique identifier is important
]);
 
 
