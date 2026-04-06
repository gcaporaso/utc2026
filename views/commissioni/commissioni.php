<?php
/* @var $this yii\web\View */
use kartik\dynagrid\DynaGrid;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\Html;
use mdm\admin\components\Helper;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/// Definisco i pulsanti da visualizzare sulla Toolbar
$plus= Html::a('<i class="fas fa-plus"></i>', 
			['commissioni/nuovacommissione'], ['title'=>'Aggiungi Commissione', 'class'=>'btn btn-success']);
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
//                         'data-confirm' => "Sei sicuro di volere eliminare questa Commissione?",
//                         'data-method' => 'post',
//                         //'data-pjax' => 0
//                      ]);
//             }],
//        'urlCreator' => function ($action, $model, $key, $index) {
//            if ($action === 'delete') {
//            $url = Url::toRoute(['commissioni/deletecommissione','idcommissione'=>$model->idcommissioni]); // your own url generation logic
//            return $url;
//            }
//            }
//        ],
        ['attribute'=>'idcommissioni',
         'hAlign'=>'center',
        'width'=>'5%'],
        ['attribute'=>'Descrizione',
         'hAlign'=>'left',
        ],
        ['attribute'=>'tipologia',
         'value'=>'tipologia.descrizione',
         'hAlign'=>'left',
         'filter' => ArrayHelper::map(app\models\TipoCommissioni::find()->asArray()->all(), 'idtipo_commissioni', 'descrizione'),
        ],
                
        ['attribute'=>'NumeroDelibera',
            'hAlign'=>'center',
        'width'=>'15%',
        ],
        ['attribute'=>'DataDelibera',
        'hAlign'=>'center',
        'format' =>  ['date', 'php:d-m-Y'],
        'width'=>'10%',
        ],
        [
        'class'=>'kartik\grid\ActionColumn',
        'order'=>DynaGrid::ORDER_FIX_RIGHT,
	'template' => '{composizione}',
        'header'=>'',
        'width'=>'10%',
        'buttons' => [
            'composizione' => function ($url, $model) {
                return Html::a('<span class="fas fa-user"></span>', $url, [
                'title' => 'composizione',
                ]);}
            ],
        'urlCreator' => function ($action, $model, $key, $index) {
            if ($action === 'composizione') {
            $url = Url::toRoute(['commissioni/composizione','idcommissione'=>$model->idcommissioni]); // your own url generation logic
            return $url;
            }
        }
        ],
        [
        'class'=>'kartik\grid\ActionColumn',
        'order'=>DynaGrid::ORDER_FIX_RIGHT,
	'template' => '{update}',
        'header'=>'',
        'width'=>'5%',
//        'buttons' => [
//            'update' => function ($url, $model) {
//                return Html::a('<span class="fas fa-edit"></span>', $url, [
//                'title' => Yii::t('app', 'update'),
//                ]);}
//            ],
        'urlCreator' => function ($action, $model, $key, $index) {
            if ($action === 'update') {
            $url = Url::toRoute(['commissioni/updatecommissione','idcommissione'=>$model->idcommissioni]); // your own url generation logic
            return $url;
            }
            if ($action === 'view') {
            $url = Url::toRoute(['commissioni/viewcommissione','idcommissione'=>$model->idcommissioni]); // your own url generation logic
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
		'heading'=>'<h3 class="panel-title"><b>ELENCO COMMISSIONI COMUNALI<b></h3>',
		'before' =>  '<div style="padding-top: 7px;"><em>Elenco Commmissioni</em></div>',
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
    'options'=>['id'=>'dynagrid-cm8'] // a unique identifier is important
]);
 
 
