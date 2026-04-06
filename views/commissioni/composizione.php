

<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
//use vkabachenko\filepond\widget\FilepondWidget;
//use kartik\file\FileInput;
use yii\helpers\Url;
use kartik\dynagrid\DynaGrid;
//use kartik\detail\DetailView;
use yii\helpers\ArrayHelper;
?>
    
<?php
/* @var $this yii\web\View */
/* @var $model app\models\Edilizia */

$this->title = Yii::t('app', 'Gestione Componenti Commissione {Commissione}',['Commissione' => $idcommissione]);
$this->params['breadcrumbs'][] = ['label' => 'Commissione', 'url' => ['composizione']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
<div class="container-fluid" style="padding:10px;">

<!--    <h1><?= Html::encode($this->title) ?></h1>-->

    <?php $form = ActiveForm::begin([
        'action'=>['commissioni/composizione', 'idcommissione'=>$idcommissione],
        'id' => 'composizione-form',
    ]) ?>
    <?php //$tutticomponenti = app\models\ComponentiCommissioni::find()->all(); //ArrayHelper::map(app\models\ComponentiCommissioni::find()->asArray()->all(), 'idcomponenti_commissioni', 'nomeCompleto') 
            $elcomponenti = ArrayHelper::map(app\models\ComponentiCommissioni::find()->all(), 'idcomponenti_commissioni', 'nomeCompleto');
            
            ?>
    <table style="border: 1px solid grey;">
        <thead><th style="border:1px solid grey;">Scegli componente da aggiungere:</th><th></th></thead>
    <tbody>
        <tr style="border: 1px solid grey;">
            <td style="width:6%; padding-left:10px;padding-right:10px;border: 1px solid grey;">
                <div>
                <?= $form->field($componente, 'idcomponenti_commissioni')->dropDownList($elcomponenti)->label('Componente:'); ?>
                </div>
            </td>
            <td style="width:3%;text-align: center">
            <?= Html::submitButton('Aggiungi', ['class' =>'btn btn-primary']) ?>
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

<?php echo print_r($dpv1->getModels()); ?>

<div class="row">
  <div class="col-xs col-sm" style="padding-left:10px;padding-top:5px;padding-right:5px;">  
<?php
echo DynaGrid::widget([
    'columns'=>[
        [
        'class'=>'kartik\grid\ActionColumn',
        'order'=>DynaGrid::ORDER_FIX_LEFT,
		'template' => '{delete}',
                'header'=>'',
                'width'=>'2%',
        'buttons'=>['delete' => function ($url,$model1) use ($idcommissione) {
                       //$myurl = $url . '&idpratica='.$id;
                       $url = Url::toRoute(['commissioni/cancellacomposizionecomponente','idcomponente'=>$model1->idcomposizioni,'idcommissione'=>$idcommissione]);
                      return Html::a('<span class="fas fa-trash"></span>', $url,
                      [  
                         'title' => 'Elimina',
                         'data-confirm' => "Sei sicuro di volere eliminare questo componente?",
                         //'data-method' => 'post',
                         //'data-pjax' => 0
                      ]);
             }]
        ],
	['class'=>'\kartik\grid\SerialColumn', 
	 'order'=>DynaGrid::ORDER_FIX_LEFT,
         'width'=>'2%'
        ],
	['attribute'=>'componenti_id',
         //'value'=>'componenti.nomeCompleto',
         'width'=>'10%'
        ],
        
    ],
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
		'heading'=>'<h3 class="panel-title"><b>ELENCO COMPONENTI COMMISSIONE : ' . $nome . '<b></h3>',
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
            ],
        ]
        ], // gridOption
    'options'=>['id'=>'dynagrid-cmn2'] // a unique identifier is important
]);
?> 

</div>           
    
</div>