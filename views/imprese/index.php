<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\dynagrid\DynaGrid;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ImpreseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Imprese');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="imprese-index">

<!--    <h1><?= Html::encode($this->title) ?></h1>-->

<!--    <p>
        <?php // Html::a(Yii::t('app', 'Create Imprese'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>-->

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?Php // GridView::widget([
//        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
//        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
//
//            'imprese_id',
//            'RAGIONE_SOCIALE',
//            'COGNOME',
//            'NOME',
//            'DATA_NASCITA',
            //'PROVINCIA_NASCITA',
            //'NOME_COMPOSTO',
            //'INDIRIZZO',
            //'COMUNE_RESIDENZA',
            //'PROVINCIA_RESIDENZA',
            //'CODICE_FISCALE',
            //'P.IVA',
            //'EMAIL:email',
            //'PEC',
            //'Cassa_Edile',
            //'INPS',
            //'INAIL',
            //'Telefono',
            //'Cellulare',

//            ['class' => 'yii\grid\ActionColumn'],
//        ],
//    ]); ?>

    
    
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
                  return Html::a('<span class="fas fa-trash"></span>', $url,
                      [  
                         'title' => 'Elimina',
                         'data-confirm' => "Sei sicuro di volere eliminare questa istanza?",
                         'data-method' => 'post',
                         //'data-pjax' => 0
                      ]);
             }]
    ],
    ['attribute'=>'imprese_id',
     'label'=>'id',
     'width'=>'2%',
    ],
    //'Cognome',
    //'Nome',
    //'DataNascita',
//            'RegimeGiuridico_id',
    ['attribute'=>'RAGIONE_SOCIALE',
     'label'=>'Denominazione',
     'width'=>'18%',
    ],
    ['attribute'=>'INDIRIZZO',
    ],
    ['attribute'=>'COMUNE_RESIDENZA',
    ],
    ['attribute'=>'PROVINCIA_RESIDENZA',
    ],
    ['attribute'=>'PartitaIVA',
    ],
    ['attribute'=>'CODICE_FISCALE',
    ],
    
    ['attribute'=>'EMAIL',
     'visible'=>false
    ],
    ['attribute'=>'PEC',
     'visible'=>false
    ],
    ['attribute'=>'Telefono',
     'visible'=>false
    ],
    ['attribute'=>'Cellulare',
     'visible'=>false
    ],
    ['attribute'=>'NOME_COMPOSTO',
     'visible'=>false
    ],
    ['attribute'=>'COMUNE_NASCITA',
        'visible'=>false
    ],
    ['attribute'=>'PROVINCIA_NASCITA',
        'visible'=>false
    ],
    ['attribute'=>'COGNOME',
        'visible'=>false
    ],
    ['attribute'=>'NOME',
        'visible'=>false
    ],
    ['attribute'=>'Cassa_Edile',
        'visible'=>false
    ],
    ['attribute'=>'INPS',
        'visible'=>false
    ],
    ['attribute'=>'INAIL',
        'visible'=>false
    ],
//    [
//        'class'=>'kartik\grid\ActionColumn',
//        'order'=>DynaGrid::ORDER_FIX_RIGHT,
//	'template' => '{view}',
//        'header'=>'',
//        'width'=>'2%',
//    ],
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
        'dataProvider'=>$dataProvider,
        'filterModel'=>$searchModel,
        'showPageSummary'=>false,
        
        'panel'=>[
		'heading'=>'<h3 class="panel-title"><b>ARCHIVIO IMPRESE<b></h3>',
		'before' =>  '<div style="padding-top: 7px;"><em>Archivio dati Imprese</em></div>',
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
//            ['content'=>Select2::widget([
//  		'name' => 'ApplicaFiltro',
//		'size' => Select2::MEDIUM,
//		//'data' => $model->filterList, //[1 => "First", 2 => "Second", 3 => "Third", 4 => "Fourth", 5 => "Fifth"],
//		'options' => ['multiple' => false, 
//                    'placeholder' => 'Seleziona un Filtro ...'],
//		'pluginOptions' => ['allowClear' => true],
//				]),
//            ],
	    ['content'=>
                Html::a('<i class="fas fa-plus"></i>', 
			['imprese/create'], ['title'=>'Aggiungi Impresa', 'class'=>'btn btn-success']) . ' '.
                Html::a('<i class="fas fa-undo"></i>', 
			['index'], ['data-pjax'=>0, 'class' => 'btn btn-default', 'title'=>'Resetta Impostazioni'])
            ],
            ['content'=>'{dynagridFilter}{dynagridSort}{dynagrid}'],
            '{export}',
        ]
        ], // gridOption
    'options'=>['id'=>'dynagrid-i1'] // a unique identifier is important
]);
?>
    
    
    
    
    
    




    
    

</div>
