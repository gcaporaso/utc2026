<?php

use yii\helpers\Html;
//use yii\widgets\DetailView;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Committenti */

$this->title = $model->committenti_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Committente'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="committenti-view">



    <?php
    
        // DetailView Attributes Configuration
$attributes = [
    [
        'group'=>true,
        'label'=>'SEZIONE 1: Generalità',
        'rowOptions'=>['class'=>'bg-info']
    ],
    [
        'columns' => [
            [
                'attribute'=>'Denominazione', 
                //'format'=>'raw', 
                //'value'=>'<kbd>'.$model->book_code.'</kbd>',
                'labelColOptions'=>['class'=>'col-sm-2','style'=>'text-align: right;'],
                'valueColOptions'=>['class'=>'col-sm-2'], 
                'displayOnly'=>true
            ],
            [
                'attribute'=>'Cognome', 
                //'format'=>'raw', 
                //'value'=>'<kbd>'.$model->book_code.'</kbd>',
                'labelColOptions'=>['class'=>'col-sm-1','style'=>'text-align: right;'],
                'valueColOptions'=>['class'=>'col-sm-2'], 
                'displayOnly'=>true
            ],
             [
                'attribute'=>'Nome', 
                //'format'=>'raw', 
                'labelColOptions'=>['class'=>'col-sm-1','style'=>'text-align: right;'],
                'valueColOptions'=>['class'=>'col-sm-2'], 
                 'displayOnly'=>true
            ],
            [
                'attribute'=>'DataNascita', 
                'labelColOptions'=>['class'=>'col-sm-1','style'=>'text-align: right;'],
                'value'=>Yii::$app->formatter->asDate($model->DataNascita, 'php:d-m-Y'),
                //'format'=>'date', 
                //'value'=>'titolo.TITOLO',
                //'format'=>'date',
                //'type'=>DetailView::INPUT_DATE,
                'widgetOptions' => [
                    'pluginOptions'=>['format'=>'dd-mm-yyyy']
                ],
                'valueColOptions'=>['style'=>'width:10%'], 
                'displayOnly'=>true
            ],
            
        ],
    ],
    [
        'columns' => [
            [
                'attribute'=>'ComuneNascita', 
                //'label'=>'Descrizione Intervento',
                'labelColOptions'=>['class'=>'col-sm-2','style'=>'text-align: right;'],
                'valueColOptions'=>['class'=>'col-sm-2'], 
                //'groupOptions'=>['class'=>'text-center']
            ],
            [
                'attribute'=>'ProvinciaNascita', 
                //'value'=>$model->tipologia->sommarioTipologia,
                'labelColOptions'=>['class'=>'col-sm-1','style'=>'text-align: right;'],
                //'valueColOptions'=>['class'=>'col-sm-2'], 
                //'label'=>'Descrizione Intervento',
                //'groupOptions'=>['class'=>'text-center']
            ],
        ],
    ],
    [
        'columns' => [
            [
                'attribute'=>'IndirizzoResidenza',
                'value'=>(isset($model->NumeroCivicoResidenza)) ? $model->IndirizzoResidenza . ','. $model->NumeroCivicoResidenza:'',
                //'labelColOptions'=>['style'=>'width:16%;text-align: right;'],
                'labelColOptions'=>['class'=>'col-sm-2','style'=>'text-align: right;'],
                'valueColOptions'=>['class'=>'col-sm-2'], 
            ],
            [
                'attribute'=>'CapResidenza',
                //'value'=>$model->richiedente->nomeCompleto,
                //'labelColOptions'=>['style'=>'width:16%;text-align: right;'],
                'labelColOptions'=>['class'=>'col-sm-1','style'=>'text-align: right;'],
                'valueColOptions'=>['class'=>'col-sm-1'], 

            ],
            [
                'attribute'=>'ComuneResidenza', 
                //'value'=>$model->statoPratica->descrizione,
                //'format'=>'raw', 
                //'value'=>"<span class='badge' style='background-color: {$model->color}'> </span>  <code>" . $model->color . '</code>',
                //'type'=>DetailView::INPUT_COLOR,
                'labelColOptions'=>['class'=>'col-sm-1','style'=>'text-align: right;'],
                'valueColOptions'=>['class'=>'col-sm-2'], 
                //'valueColOptions'=>['style'=>'width:25%'],
            ],
            [
                'attribute'=>'ProvinciaResidenza', 
                //'value'=>$model->statoPratica->descrizione,
                //'format'=>'raw', 
                //'value'=>"<span class='badge' style='background-color: {$model->color}'> </span>  <code>" . $model->color . '</code>',
                //'type'=>DetailView::INPUT_COLOR,
                'labelColOptions'=>['class'=>'col-sm-1','style'=>'text-align: right;'],
                
                //'valueColOptions'=>['style'=>'width:25%'],
            ],
        ],
    ],
    // Riga intestazione 2 ==> CONTATTI
    [
        'group'=>true,
        'label'=>'SEZIONE 2: Dati Fiscali',
        'rowOptions'=>['class'=>'bg-info'],
        //'groupOptions'=>['class'=>'text-center']
    ],
    [
        'columns' => [
            [
            'attribute'=>'PartitaIVA',
            'labelColOptions'=>['class'=>'col-sm-2','style'=>'text-align: right;'],
            'valueColOptions'=>['class'=>'col-sm-2'], 
            //'value'=>isset($model->IndirizzoImmobile) ? $model->IndirizzoImmobile : '',
            //'label'=>'Buy Amount ($)',
            //'format'=>['decimal', 2],
            //'inputContainer' => ['class'=>'col-sm-3'],
            //'valueColOptions'=>['class'=>'col-sm-3'], 
            ],
            [
            'attribute'=>'CodiceFiscale',
            'labelColOptions'=>['class'=>'col-sm-1','style'=>'text-align: right;'],
                //'value'=>$model->CatastoTipo ? 'Terreni' : 'Fabbricati',
            //'label'=>'Catasto',
            //'format'=>['decimal', 2],
            //'inputContainer' => ['class'=>'col-sm-3'],
            //'valueColOptions'=>['class'=>'col-sm-3'], 
            ],
        ]
    ],
     // Riga intestazione 2 ==> CONTATTI
    [
        'group'=>true,
        'label'=>'SEZIONE 3: Dati di Contatto',
        'rowOptions'=>['class'=>'bg-info'],
        //'groupOptions'=>['class'=>'text-center']
    ],
    [
        'columns' => [
            [
                'attribute'=>'Telefono',
                //'label'=>'Foglio',
                //'value'=>'richiedente.nomeCompleto',
                //'value'=>isset($model->CatastoFoglio) ? $model->CatastoFoglio:'',
                'labelColOptions'=>['class'=>'col-sm-2','style'=>'text-align: right;'],
                'valueColOptions'=>['class'=>'col-sm-2'], 
            ],
            [
                'attribute'=>'Cellulare', 
                //'label'=>'Particella',
                'labelColOptions'=>['class'=>'col-sm-1','style'=>'text-align: right;'],
                //'valueColOptions'=>['class'=>'col-sm-1'], 
                //'value'=>isset($model->CatastoParticella) ? $model->CatastoParticella:'',
                //'value'=>'statoPratica.descrizione',
                //'format'=>'raw', 
                //'value'=>"<span class='badge' style='background-color: {$model->color}'> </span>  <code>" . $model->color . '</code>',
                //'type'=>DetailView::INPUT_COLOR,
                //'valueColOptions'=>['style'=>'width:15%'], 
            ],
        ],
    ],
    [
        'columns' => [
            [
                'attribute'=>'Email', 
                //'label'=>'Sub.',
                'labelColOptions'=>['class'=>'col-sm-2','style'=>'text-align: right;'],
                'valueColOptions'=>['class'=>'col-sm-2'], 
                //'value'=>isset($model->CatastoSub) ? $model->CatastoSub:'',
                //'value'=>'statoPratica.descrizione',
                //'format'=>'raw', 
                //'value'=>"<span class='badge' style='background-color: {$model->color}'> </span>  <code>" . $model->color . '</code>',
                //'type'=>DetailView::INPUT_COLOR,
                //'valueColOptions'=>['style'=>'width:15%'], 
            ],
            [
                'attribute'=>'PEC', 
                //'label'=>'Sub.',
                'labelColOptions'=>['class'=>'col-sm-1','style'=>'text-align: right;'],
                //'value'=>isset($model->CatastoSub) ? $model->CatastoSub:'',
                //'value'=>'statoPratica.descrizione',
                //'format'=>'raw', 
                //'value'=>"<span class='badge' style='background-color: {$model->color}'> </span>  <code>" . $model->color . '</code>',
                //'type'=>DetailView::INPUT_COLOR,
                //'valueColOptions'=>['style'=>'width:15%'], 
            ],
        ],
    ],
];

// View file rendering the widget
echo DetailView::widget([
    'model' => $model,
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
        'heading' => 'Dettaglio Richiedente ' . $model->committenti_id,
        
        //'footer' => '<div class="text-center text-muted">.....</div>'
    ],
    'deleteOptions'=>[ // your ajax delete parameters
        'params' => ['id' => $model->committenti_id, 'kvdelete'=>false],
    ],
    'container' => ['id'=>'kv-demo1'],
    //'formOptions' => ['action' => Url::current(['#' => 'kv-demo'])] // your action to delete
]);

    
    
    
//    echo DetailView::widget([
//        'model' => $model,
//        'attributes' => [
//            'committenti_id',
//            'Cognome',
//            'Nome',
//            'DataNascita',
//            'RegimeGiuridico_id',
//            'NOME_COMPOSTO',
//            'IndirizzoResidenza',
//            'ComuneResidenza',
//            'ProvinciaResidenza',
//            'CodiceFiscale',
//            'PartitaIVA',
//            'Email:email',
//            'PEC',
//            'Telefono',
//            'Cellulare',
//            'Denominazione',
//            'ComuneNascita',
//            'ProvinciaNascita',
//            'NumeroCivicoResidenza',
//            'CapResidenza',
//        ],
//    ]) 
?>

</div>
