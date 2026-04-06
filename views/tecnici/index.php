<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\dynagrid\DynaGrid;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TecniciSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tecnici');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tecnici-index">

<!--    <h1><?= Html::encode($this->title) ?></h1>-->

       <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<!--    <?php
//        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
//        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
//
//            'tecnici_id',
//            'COGNOME',
//            'NOME',
//            'COMUNE_NASCITA',
//            'PROVINCIA_NASCITA',
//            //'DATA_NASCITA',
//            //'ALBO',
//            //'PROVINCIA_ALBO',
//            //'NUMERO_ISCRIZIONE',
//            //'NOME_COMPOSTO',
//            //'INDIRIZZO',
//            //'COMUNE_RESIDENZA',
//            //'PROVINCIA_RESIDENZA',
//            //'CODICE_FISCALE',
//            //'P_IVA',
//            //'TELEFONO',
//            //'FAX',
//            //'CELLULARE',
//            //'EMAIL:email',
//            //'PEC',
//            //'Denominazione',
//
//            ['class' => 'yii\grid\ActionColumn'],
//        ],
//    ]); 
    ?>
    -->
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
    ['attribute'=>'tecnici_id',
     'label'=>'id',
     'width'=>'2%',
    ],
    //'Cognome',
    //'Nome',
    //'DataNascita',
//            'RegimeGiuridico_id',
    ['attribute'=>'NOME_COMPOSTO',
     'label'=>'Cognome,Nome o Denominazione',
     'width'=>'18%',
    ],
    ['attribute'=>'INDIRIZZO',
    ],
    ['attribute'=>'COMUNE_RESIDENZA',
    ],
    ['attribute'=>'PROVINCIA_RESIDENZA',
    ],
//    ['attribute'=>'CapResidenza',
//    'label'=>'cap',
//    ],
    //'ProvinciaResidenza',
    ['attribute'=>'CODICE_FISCALE',
     'visible'=>false
        ],
    ['attribute'=>'P_IVA',
     'visible'=>false
    ],
    ['attribute'=>'EMAIL',
     'visible'=>false
    ],
    ['attribute'=>'PEC',
     'visible'=>false
    ],
    ['attribute'=>'TELEFONO',
     'visible'=>false
    ],
    ['attribute'=>'CELLULARE',
     
    ],
    ['attribute'=>'Denominazione',
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
    ['attribute'=>'ALBO',
        
    ],
    ['attribute'=>'PROVINCIA_ALBO',
        'visible'=>false
    ],
    ['attribute'=>'NUMERO_ISCRIZIONE',
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
		'heading'=>'<h3 class="panel-title"><b>ARCHIVIO TECNICI<b></h3>',
		'before' =>  '<div style="padding-top: 7px;"><em>Archivio dati Tecnici</em></div>',
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
			['tecnici/create'], ['title'=>'Aggiungi Tecnico', 'class'=>'btn btn-success']) . ' '.
                Html::a('<i class="fas fa-undo"></i>', 
			['index'], ['data-pjax'=>0, 'class' => 'btn btn-default', 'title'=>'Resetta Impostazioni'])
            ],
            ['content'=>'{dynagridFilter}{dynagridSort}{dynagrid}'],
            '{export}',
        ]
        ], // gridOption
    'options'=>['id'=>'dynagrid-t1'] // a unique identifier is important
]);
?>
    
    
    
    
    
    


</div>
