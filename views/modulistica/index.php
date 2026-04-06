<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\dynagrid\DynaGrid;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ImpreseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Modulistica');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid">
<!--    <div class="row" style="margin-top: 10px">-->
     <?php
    echo DynaGrid::widget([
    'columns'=>[
    [
        'class'=>'kartik\grid\ActionColumn',
        'order'=>DynaGrid::ORDER_FIX_LEFT,
	'template' => '{delete}',
        'header'=>'',
        'width'=>'2%',
        'buttons'=>['delete' => function ($url) {
                  return Html::a('<span class="fas fa-thin fa-trash"></span>', $url,
                      [  
                         'title' => 'Elimina',
                         'data-confirm' => "Sei sicuro di volere eliminare questo modello?",
                         'data-method' => 'post',
                         //'data-pjax' => 0
                      ]);
             }]
    ],
    ['attribute'=>'idmodulistica',
     'label'=>'id',
     'width'=>'2%',
    ],
    ['attribute'=>'descrizione',
     'width'=>'20%',
    ],
    [//'attribute'=>'nomefile',
     'label'=>'Nome File',
     'format' => 'raw',
     'value' => function ($data) {
            //return Html::a($data['nomefile'],$data['path'] . $data['nomefile'],['target' => '_blank', 'class' => 'box_button fl download_link']);
        return Html::a($data['nomefile'],['modulistica/download','filename'=> $data['path'] . $data['nomefile']]);
     },
     'width'=>'45%',   
    ],
    ['attribute'=>'codice',
     'hAlign'=>'center',
     'width'=>'6%',   
    ],
    ['attribute'=>'path',
     'visible'=>false,
    ],
    ['attribute'=>'numerorevisione',
     'label'=>'Versione',
     'hAlign'=>'center',
     'width'=>'5%',   
    ],
    ['attribute'=>'datarevisione',
     'format' =>  ['date', 'php:d-m-Y'],
     'hAlign'=>'center',
     'label'=>'Data Revisione',
     'width'=>'8%',   
    ],
    ['attribute'=>'settore',
     'value'=>'settore.descrizione',
     'label'=>'Categoria',
     'width'=>'10%',   
     'hAlign'=>'center',
     'filter' => ArrayHelper::map(app\models\Categorie::find()->asArray()->all(), 'idcategorie', 'descrizione'),
    ],
    [
        'class'=>'kartik\grid\ActionColumn',
        'order'=>DynaGrid::ORDER_FIX_RIGHT,
	'template' => '{update}',
        'header'=>'',
        'width'=>'2%',
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
		'heading'=>'<h3 class="panel-title"><b>ARCHIVIO MODULISTICA<b></h3>',
		'before' =>  '<div style="padding-top: 7px;"><em>Archivio modelli</em></div>',
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
	'toolbar' =>  [
        ['content'=>Select2::widget([
  		    'name' => 'ApplicaFiltro',
		    'size' => Select2::MEDIUM,
		    //'data' => $model->filterList, //[1 => "First", 2 => "Second", 3 => "Third", 4 => "Fourth", 5 => "Fifth"],
		    'options' => ['multiple' => false, 
                    'placeholder' => 'Seleziona un Filtro ...'],
		    'pluginOptions' => ['allowClear' => true],
				]),
        ],
	    ['content'=>
                Html::a('<i class="fas fa-solid fa-plus"></i>', 
			['modulistica/nuovo'], ['title'=>'Aggiungi modello', 'class'=>'btn btn-success']) . ' '.
                Html::a('<i class="fas fa-undo"></i>', 
			['index'], ['data-pjax'=>0, 'class' => 'btn btn-default', 'title'=>'Resetta Impostazioni'])
        ],
        ['content'=>'{dynagridFilter}{dynagridSort}{dynagrid}'],
        '{export}',
        ]
        ], // gridOption
    'options'=>['id'=>'dynagrid-mod1'] // a unique identifier is important
]);
?>
    
    

<!--    </div>    -->
    

</div>
