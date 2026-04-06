

<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
//use vkabachenko\filepond\widget\FilepondWidget;
//use kartik\file\FileInput;
use yii\helpers\Url;
use kartik\dynagrid\DynaGrid;
//use kartik\detail\DetailView;
//use yii\widgets\Pjax;
?>
    
<?php
/* @var $this yii\web\View */
/* @var $model app\models\Edilizia */

$this->title = Yii::t('app', 'Allegati Pratica Edilizia {Pratica}',['Pratica' => $idpratica]);
$this->params['breadcrumbs'][] = ['label' => 'Pratica', 'url' => ['allegati']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
<div class="container-fluid" style="padding:10px;">

<!--    <h1><?= Html::encode($this->title) ?></h1>-->

    <?php // $this->render('_form_allegati', [
      //  'modtec' => $modtec,'modamm' => $modamm,'dpv1'=>$dprov1,'dpv2'=>$dprov2, 'id'=>$id
    // ]) ?>
    
    <?php $form = ActiveForm::begin([
        'action'=>['edilizia/allegati', 'idpratica'=>$idpratica],
        'id' => 'allegati-tecnici-form',
        'options' => ['enctype' => 'multipart/form-data'],
    ]) ?>
    <table style="border: 1px solid grey;">
        <thead><th style="border:1px solid grey;">Descrizione Allegato:</th><th  style="border:1px solid grey;">File allegato:</th><th></th></thead>
    <tbody>
        <tr style="border: 1px solid grey;">
        <td style="width:10%;padding-left:5px;padding-top:10px;padding-right:5px;">
        <?= $form->field($model, 'descrizione')->label(false)->textInput(['maxlength' => true]) ?>
        </td>
    <td class="file-input" style="width:15%; padding-left:10px;border: 1px solid grey;">
        <div style="position:relative;">
           <?php echo $form->field($model, 'nome')->label(false)->fileInput() ?>
        </div>
    </td>  
    <td style="width:6%; padding-left:10px;padding-right:10px;border: 1px solid grey;">
        <div>
       <?= $form->field($model, 'tipologia')->dropDownList([0 => 'Elaborato Tecnico/Grafico', 1 => 'Atto Amministrativo'])->label('tipologia allegato'); ?>
        </div>
    </td>
        <td style="width:3%;text-align: center">
        <?= Html::submitButton($model->isNewRecord ? 'Aggiungi' : 'Aggiorna', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </td>
        </tr>
    </tbody>
    </table>
    <?php ActiveForm::end() ?>
    
</div>
</div>
<!-- display success message -->
<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success alert-dismissable">
         <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
         <h4><i class="icon fa fa-check"></i>Salvato!</h4>
         <?= Yii::$app->session->getFlash('success') ?>
    </div>
<?php endif; ?>

<!-- // display error message -->
<?php if (Yii::$app->session->hasFlash('error')): ?>
    <div class="alert alert-danger alert-dismissable">
         <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
         <h4><i class="icon fa fa-check"></i>Errore!</h4>
         <?= Yii::$app->session->getFlash('error') ?>
    </div>
<?php endif; ?>


<div class="row">
  <div class="col-xs-6" style="padding-left:10px;padding-top:5px;padding-right:5px;">  
<?php
//Pjax::begin(['id' => 'alltec']);
echo DynaGrid::widget([
   	'columns'=>[
    [
        'class'=>'kartik\grid\ActionColumn',
        'order'=>DynaGrid::ORDER_FIX_LEFT,
		'template' => '{delete}',
                'header'=>'',
                'width'=>'2%',
        'buttons'=>['delete' => function ($url,$model1) use ($idpratica) {
                       //$myurl = $url . '&idpratica='.$id;
                       $url = Url::toRoute(['edilizia/cancellafile','id'=>$model1->idallegati,'idpratica'=>$idpratica]);
                      return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url,
                      [  
                         'title' => 'Elimina',
                         'data-confirm' => "Sei sicuro di volere eliminare questo allegato?",
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
        [//'attribute'=>'nomefile',
        //'label' => 'bla',
        'format' => 'raw',
        'value' => function ($data) {
            //return Html::a($data['nomefile'],$data['path'] . $data['nomefile'],['target' => '_blank', 'class' => 'box_button fl download_link']);
            return Html::a($data['nomefile'],['edilizia/download','filename'=> $data['path'] . $data['nomefile']]);
       },
         //'format' =>  ['date', 'php:d-m-Y'],
         'hAlign'=>'left',
        'width'=>'45%'],
        ['attribute'=>'byte', //committente.nomeCompleto',
        'width'=>'9%',
	'label'=>'byte'
        ],     
	['attribute'=>'data_update',
         'label'=>'data',
         'format' =>  ['date', 'php:d-m-Y'],
         'hAlign'=>'center',
         'width'=>'11%'
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
//Pjax::end();
?> 

</div>           
    
<div class="col-xs-6" style="padding-left:5px;padding-top:5px;padding-right:10px;">  

    <?php

    //Pjax::begin(['id' => 'allamm']);
    echo DynaGrid::widget([
   	'columns'=>[
    [
        'class'=>'kartik\grid\ActionColumn',
        'order'=>DynaGrid::ORDER_FIX_LEFT,
		'template' => '{delete}',
                'header'=>'',
                'width'=>'2%',
        'buttons'=>['delete' => function ($url,$model2) use ($idpratica) {
                    $url = Url::toRoute(['edilizia/cancellafile','id'=>$model2->idallegati,'idpratica'=>$idpratica]);
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
        [//'attribute'=>'nome',
        'format' => 'raw',
        'value' => function ($data) {
            //return Html::a($data['nomefile'],$data['path'] . $data['nomefile'],['target' => '_blank', 'class' => 'box_button fl download_link']);
            return Html::a($data['nomefile'],['edilizia/download','filename'=> $data['path'] . $data['nomefile']]);
                },
         //'format' =>  ['date', 'php:d-m-Y'],
        'hAlign'=>'left',
        'width'=>'45%'],
        ['attribute'=>'byte', //committente.nomeCompleto',
        'width'=>'9%',
	'label'=>'byte'
        ],     
	['attribute'=>'data_update',
         'label'=>'data',
         'format' =>  ['date', 'php:d-m-Y'],
         'hAlign'=>'center',
         'width'=>'11%'
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
                'before'=>'',
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
            ['content'=>''],
        ]
        ], // gridOption
    'options'=>['id'=>'dynagrid-b'] // a unique identifier is important
]);
//Pjax::end();

?>
</div>
</div>