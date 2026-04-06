

<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use yii\web\View;
use kartik\dynagrid\DynaGrid;
use kartik\select2\Select2;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CommittentiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = Yii::t('app', 'Committentis');
$this->title = '';
$this->params['breadcrumbs'][] = $this->title;

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
?>

<div class="committenti-index">

    <h1><?= Html::encode($this->title) ?></h1>

<!--    <p>
        <?php // Html::a(Yii::t('app', 'Create Committenti'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>-->

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php // GridView::widget([
//        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
//        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
//
//            'committenti_id',
//            'Cognome',
//            'Nome',
//            'DataNascita',
//            'RegimeGiuridico_id',
//            //'NOME_COMPOSTO',
//            //'IndirizzoResidenza',
//            //'ComuneResidenza',
//            //'ProvinciaResidenza',
//            //'CodiceFiscale',
//            //'PartitaIVA',
//            //'EMAIL:email',
//            //'PEC',
//            //'Telefono',
//            //'Cellulare',
//            //'Denominazione',
//            //'ComuneNascita',
//            //'ProvinciaNascita',
//            //'NumeroCivicoResidenza',
//            //'CapResidenza',
//
//            ['class' => 'yii\grid\ActionColumn'],
//        ],
   // ]); ?>

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
    ['attribute'=>'committenti_id',
     'label'=>'id',
     'width'=>'2%',
    ],
    //'Cognome',
    //'Nome',
    //'DataNascita',
//            'RegimeGiuridico_id',
//    ['attribute'=>'NOME_COMPOSTO',
//     'label'=>'Cognome,Nome o Denominazione',
//     'width'=>'18%',
//    ],
      ['attribute'=>'nomeCompleto', //committente.nomeCompleto',
        //'value'=>'richiedente.nomeCompleto',
//        'filterType'=>GridView::FILTER_SELECT2,
//      
//        'filter'=>[1=>'Caporaso Giuseppe (16-09-1964)', 2=>'Barbato Francesco (02-11-1965)'], //ArrayHelper::map(Committente::find()->asArray()->all(), 'idcommittente', 'nomeCompleto'), 
//        'filterWidgetOptions'=>[
//            'pluginOptions'=>['allowClear'=>true],
//        ],
//        'filterInputOptions'=>['placeholder'=>'Filtra per Richiedente..'],
//        'format'=>'raw',
        'width'=>'18%',
	'label'=>'Cognome, Nome o Denominazione'],     
    ['attribute'=>'IndirizzoResidenza',
     'width'=>'14%'
    ],
    ['attribute'=>'NumeroCivicoResidenza',
    'visible'=>false
    ],
    ['attribute'=>'ComuneResidenza',
     'width'=>'15%'
    ],
   
    ['attribute'=>'CapResidenza',
    'label'=>'cap',
    'visible'=>false
    ],
    //'ProvinciaResidenza',
    ['attribute'=>'CodiceFiscale',
     'visible'=>false
        ],
    ['attribute'=>'PartitaIVA',
     'visible'=>false
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
     
    ],
    ['attribute'=>'Denominazione',
     'visible'=>false
    ],
    ['attribute'=>'ComuneNascita',
        'visible'=>false
    ],
    ['attribute'=>'ProvinciaNascita',
        'visible'=>false
    ],
    ['attribute'=>'CapResidenza',
        'visible'=>false
    ],
    ['attribute'=>'DataNascita',
         'format' =>  ['date', 'php:d-m-Y'],
         'hAlign'=>'center',
        'filterType' => GridView::FILTER_DATE_RANGE,
        'filterWidgetOptions' => [
                'startAttribute' => 'data_inizio_nascita', //Attribute of start time
                'endAttribute' => 'data_fine_nascita',   //The attributes of the end time
                'convertFormat'=>true, // Importantly, true uses the local - > format time format to convert PHP time format to js time format.
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',//Date format
                    'locale'=>['format' => 'd-m-Y'], //php formatting time
                ]
            ],
        
        //The use of single time selector
        'width'=>'9%'
    ],
    [
        'class'=>'kartik\grid\ActionColumn',
        'order'=>DynaGrid::ORDER_FIX_RIGHT,
	'template' => '{view}',
        'header'=>'',
        'width'=>'2%',
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
        'dataProvider'=>$dataProvider,
        'filterModel'=>$searchModel,
        'showPageSummary'=>false,
        
        'panel'=>[
		'heading'=>'<h3 class="panel-title"><b>ARCHIVIO RICHIEDENTI<b></h3>',
		'before' =>  '<div style="padding-top: 7px;"><em>Archivio dati Richiedenti</em></div>',
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
//		'data' => $model->filterList, //[1 => "First", 2 => "Second", 3 => "Third", 4 => "Fourth", 5 => "Fifth"],
//		'options' => ['multiple' => false, 
//                    'placeholder' => 'Seleziona un Filtro ...'],
//		'pluginOptions' => ['allowClear' => true],
//				]),
//            ],
	    ['content'=>
                Html::a('<i class="fas fa-plus"></i>', 
			['committenti/create'], ['title'=>'Aggiungi Istanza', 'class'=>'btn btn-success']) . ' '.
                Html::a('<i class="fas fa-undo"></i>', 
			['index'], ['data-pjax'=>0, 'class' => 'btn btn-default', 'title'=>'Resetta Impostazioni'])
            ],
            ['content'=>'{dynagridFilter}{dynagridSort}{dynagrid}'],
            '{export}',
        ]
        ], // gridOption
    'options'=>['id'=>'dynagrid-c1'] // a unique identifier is important
]);
?>
</div>
