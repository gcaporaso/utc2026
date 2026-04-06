<!--     bootstrap 4.x is supported. You can also use the bootstrap css 3.3.x versions 
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.0.1/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
     if using RTL (Right-To-Left) orientation, load the RTL CSS file after fileinput.css by uncommenting below 
     link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.0.1/css/fileinput-rtl.min.css" media="all" rel="stylesheet" type="text/css" /
     the font awesome icon library if using with `fas` theme (or Bootstrap 4.x). Note that default icons used in the plugin are glyphicons that are bundled only with Bootstrap 3.x. 
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" crossorigin="anonymous">-->
<!--    <script src="https://code.jquery.com/jquery-3.3.1.min.js" crossorigin="anonymous"></script>-->
    <!-- piexif.min.js is needed for auto orienting image files OR when restoring exif data in resized images and when you
        wish to resize images before upload. This must be loaded before fileinput.min.js -->
<!--    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.0.1/js/plugins/piexif.min.js" type="text/javascript"></script>-->
    <!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview. 
        This must be loaded before fileinput.min.js -->
<!--    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.0.1/js/plugins/sortable.min.js" type="text/javascript"></script>-->
    <!-- purify.min.js is only needed if you wish to purify HTML content in your preview for 
        HTML files. This must be loaded before fileinput.min.js -->
<!--    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.0.1/js/plugins/purify.min.js" type="text/javascript"></script>-->
    <!-- popper.min.js below is needed if you use bootstrap 4.x (for popover and tooltips). You can also use the bootstrap js 
       3.3.x versions without popper.min.js. -->
<!--    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>-->
<!--     bootstrap.min.js below is needed if you wish to zoom and preview file content in a detail modal
        dialog. bootstrap 4.x is supported. You can also use the bootstrap js 3.3.x versions. 
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>-->
    <!-- the main fileinput plugin file -->
<!--    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.0.1/js/fileinput.min.js"></script>-->
    <!-- following theme script is needed to use the Font Awesome 5.x theme (`fas`) -->
<!--    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.0.1/themes/fas/theme.min.js"></script -->
    <!-- optionally if you need translation for your language then include the locale file as mentioned below (replace LANG.js with your language locale) -->
<!--    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.0.1/js/locales/LANG.js"></script>-->
<!--    <script>
    $("document").ready(function(){ 
		$("#new_country").on("pjax:end", function() {
			$.pjax.reload({container:"#countries"});  //Reload GridView
		});
    });    
    </script>-->

<?php

use yii\helpers\Html;
use yii\helpers\Url;
//use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
//use app\models\TitoloEdilizio;
//use app\models\Committenti;
//use app\models\Tecnici;
//use app\models\Imprese;
//use app\models\StatoEdilizia;
//use kartik\select2\Select2;
//use kartik\date\DatePicker;
//use kartik\number\NumberControl;
//use kartik\datecontrol\DateControl;
use kartik\dynagrid\DynaGrid;
use kartik\file\FileInput

/* @var $this yii\web\View */
/* @var $model app\models\Edilizia */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-sm-5" >
        
        <div class="file">

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
    <?= $form->field($modtec, 'imageFile')->fileInput() ?>
    <button>Submit</button>
<?php ActiveForm::end() ?>
        </div>
    
            
            
    
        
    
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
                  return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url,
                      [  
                         'title' => 'Elimina',
                         'data-confirm' => "Sei sicuro di volere eliminare questa istanza?",
                         //'data-method' => 'post',
                         //'data-pjax' => 0
                      ]);
             }]
    ],
	[   'class'=>'\kartik\grid\SerialColumn', 
		'order'=>DynaGrid::ORDER_FIX_LEFT],
	['attribute'=>'descrizione',
         //'label'=>'Protocollo',
        // 'width'=>'10%',
 	'hAlign'=>'left', 
	],
        ['attribute'=>'nome',
         //'format' =>  ['date', 'php:d-m-Y'],
         'hAlign'=>'left',
        'width'=>'33%'],
        ['attribute'=>'size', //committente.nomeCompleto',
        'width'=>'9%',
	'label'=>'byte'
        ],     
	['attribute'=>'type',
         'width'=>'6%',
         'label'=>'tipo'
        ]],
    ////////////////////////////////
    ///// OPZIONI DELLA TABELLA ////
    ///////////////////////////////
    'storage'=>'db', //DynaGrid::TYPE_COOKIE,
    'dbUpdateNameOnly'=>true,
    'theme'=>'panel-info',
    //'emptyCell'=>' ',
    'showPersonalize'=>false,
    //'pjax'=>true,
    'gridOptions'=>[
        'dataProvider'=>$dpv1,
        //'filterModel'=>$Search,
        'showPageSummary'=>false,
        
        'panel'=>[
		'heading'=>'<h3 class="panel-title"><b>ELENCO ALLEGATI TECNICI<b></h3>',
		//'before' =>  '<div style="padding-top: 7px;"><em>Elenco Documenti</em></div>',
                'before' =>  '',
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
            ['content'=>''
//                Html::a('<i class="glyphicon glyphicon-plus"></i>', 
//			['edilizia/addfiletecnico'], ['title'=>'Aggiungi Istanza', 'class'=>'btn btn-success']) 
                
            ],
        ]
        ], // gridOption
    'options'=>['id'=>'dynagrid-a'] // a unique identifier is important
]);
?> 

</div>           
<div class="col-sm-5" >
        <div class="file">
            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
            <?= $form->field($modamm, 'nome')->fileInput() ?>
            <button>Submit</button>
            <?php ActiveForm::end() ?>
        </div>
       

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
                  return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url,
                      [  
                         'title' => 'Elimina',
                         'data-confirm' => "Sei sicuro di volere eliminare questa istanza?",
                         //'data-method' => 'post',
                         //'data-pjax' => 0
                      ]);
             }]
    ],
	[   'class'=>'\kartik\grid\SerialColumn', 
		'order'=>DynaGrid::ORDER_FIX_LEFT],
	['attribute'=>'descrizione',
         //'label'=>'Protocollo',
         //'width'=>'20%',
 	'hAlign'=>'left', 
	],
        ['attribute'=>'nome',
         //'format' =>  ['date', 'php:d-m-Y'],
         'hAlign'=>'left',
        'width'=>'33%'],
        ['attribute'=>'size', //committente.nomeCompleto',
        'width'=>'9%',
	'label'=>'byte'
        ],     
	['attribute'=>'type',
         'width'=>'6%',
         'label'=>'tipo'
        ]],
    ////////////////////////////////
    ///// OPZIONI DELLA TABELLA ////
    ///////////////////////////////
    'storage'=>'db', //DynaGrid::TYPE_COOKIE,
    'dbUpdateNameOnly'=>true,
    'theme'=>'panel-info',
    //'emptyCell'=>' ',
    'showPersonalize'=>false,
    //'pjax'=>true,
    'gridOptions'=>[
        'dataProvider'=>$dpv2,
        //'filterModel'=>$Search,
        'showPageSummary'=>false,
        
        'panel'=>[
		'heading'=>'<h3 class="panel-title"><b>ELENCO ALLEGATI AMMINISTRATIVI<b></h3>',
		'before' =>  '<div style="padding-top: 7px;"><em>Elenco Documenti</em></div>',
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
            ['content'=>
                Html::a('<i class="glyphicon glyphicon-plus"></i>', 
			['edilizia/addfileamm'], ['title'=>'Aggiungi Istanza', 'class'=>'btn btn-success']) 
            ],
        ]
        ], // gridOption
    'options'=>['id'=>'dynagrid-b'] // a unique identifier is important
]);


?>
</div>