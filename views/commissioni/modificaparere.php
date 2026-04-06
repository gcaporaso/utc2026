<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use app\models\SeduteCommissioni;
use app\models\Commissioni;
use app\models\TipoParere;
use kartik\select2\Select2;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ComponentiCommissioni */
/* @var $form yii\widgets\ActiveForm */
?>

<?php   
 
    // DetailView Attributes Configuration
$attributes = [
    [
        'columns' => [
            [
                'attribute'=>'NumeroProtocollo', 
                //'format'=>'raw', 
                //'value'=>'<kbd>'.$model->book_code.'</kbd>',
                'labelColOptions'=>['style'=>'width:10%;text-align: right;'],
                'valueColOptions'=>['style'=>'width:25%'], 
                'displayOnly'=>true
            ],
            [
                'attribute'=>'DataProtocollo', 
                'labelColOptions'=>['style'=>'width:10%;text-align: right;'],
                'value'=>Yii::$app->formatter->asDate($pratica->DataProtocollo, 'php:d-m-Y'),
                //'format'=>'date', 
                //'value'=>'titolo.TITOLO',
                //'format'=>'date',
                //'type'=>DetailView::INPUT_DATE,
                'widgetOptions' => [
                    'pluginOptions'=>['format'=>'dd-mm-yyyy']
                ],
                
                'displayOnly'=>true
            ],
        ],
    ],
    [
        'columns' => [
            [
                'attribute'=>'DescrizioneIntervento', 
                'label'=>'Descrizione Intervento',
                'labelColOptions'=>['style'=>'width:10%;text-align: right;'],
                'valueColOptions'=>['style'=>'width:25%'],
                //'groupOptions'=>['class'=>'text-center']
            ],
            [
                'attribute'=>'richiedente',
                'value'=>$pratica->richiedente->nomeCompleto,
                'labelColOptions'=>['style'=>'width:10%;text-align: right;'],
                
            ],
        ],
    ],
];

// View file rendering the widget
echo DetailView::widget([
    'model' => $pratica,
    'attributes' => $attributes,
    'mode' => 'view',
    'bordered' => true,
    'striped' => false,
    'condensed' => true,
    'responsive' => true,
    //'heading'=>'{title}',
    //'hover' => $hover,
    'hAlign'=>'right',
    'vAlign'=>'top',
    'enableEditMode'=>false,
    //'fadeDelay'=>$fadeDelay,
    
    'panel' => [
        'type' => 'primary', 
        'heading' => 'Dettaglio Pratica Commissione',
        
        //'footer' => '<div class="text-center text-muted">.....</div>'
    ],
    'container' => ['id'=>'kv-parere-view'],
    //'formOptions' => ['action' => Url::current(['#' => 'kv-demo'])] // your action to delete
]);
    
   ?> 
 


<div class="commissioni-form">
    <div><p></p></div>
        
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-sm-2">
            <?= $form->field($model, 'pratica_id')->textInput(['disabled' => 'disabled'])->label('ID Pratica'); ?>
        </div>
        <div class="col-sm-4">
        <?php
        $listatipi = ArrayHelper::map(Commissioni::find()->all(), 'idcommissioni', 'Descrizione');
            echo $form->field($model, 'commissioni_id')->dropDownList($listatipi, ['disabled' => 'disabled'])->label('Commissione');
        ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-2">
            
        <?php
        $listasedute = ArrayHelper::map(SeduteCommissioni::find()->all(), 'idsedute_commissioni', 'dataseduta');
            echo $form->field($model, 'seduta_id')->dropDownList($listasedute, ['disabled' => 'disabled'])->label('Data Seduta');
        ?>
        </div>
        <div class="col-sm-4">
        <?php
        $tipoparere = ArrayHelper::map(TipoParere::find()->all(), 'idtipoparere', 'esitoparere');
            echo $form->field($model, 'tipoparere_id')->dropDownList($tipoparere)->label('Tipo Parere');
        ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
        <?= $form->field($model, 'testoparere')->textarea(['rows' => '6'])->label('Testo Parere');  ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Salva', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
