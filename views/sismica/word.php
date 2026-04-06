<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Edilizia */

$this->title = 'Generazione Documenti per Pratica SISMICA ' . $model->sismica_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sismica'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="edilizia-view">

<!--    <h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?php // Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->edilizia_id], ['class' => 'btn btn-primary']) ?>
        <?php // Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->edilizia_id], [
         //   'class' => 'btn btn-danger',
         //   'data' => [
         //       'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
         //       'method' => 'post',
         //   ],
        //]) ?>
    </p>

 <?php   
 
    // DetailView Attributes Configuration
$attributes = [
    [
        'group'=>true,
        'label'=>'Riepilogo Sintetico Dati Istanza',
        'rowOptions'=>['class'=>'bg-info']
    ],
    [
        'columns' => [
            [
                'attribute'=>'Protocollo', 
                //'format'=>'raw', 
                //'value'=>'<kbd>'.$model->book_code.'</kbd>',
                'labelColOptions'=>['class'=>'col-sm-2','style'=>'text-align: right;'],
                'valueColOptions'=>['class'=>'col-sm-1'], 
                'displayOnly'=>true
            ],
            [
                'attribute'=>'DataProtocollo', 
                'labelColOptions'=>['class'=>'col-sm-1','style'=>'text-align: right;'],
                'value'=>Yii::$app->formatter->asDate($model->DataProtocollo, 'php:d-m-Y'),
                //'format'=>'date', 
                //'value'=>'titolo.TITOLO',
                //'format'=>'date',
                //'type'=>DetailView::INPUT_DATE,
                'widgetOptions' => [
                    'pluginOptions'=>['format'=>'dd-mm-yyyy']
                ],
                'valueColOptions'=>['class'=>'col-sm-1'], 
                'displayOnly'=>true
            ],
            
           
            [
                'attribute'=>'CatastoFoglio',
                'label'=>'Foglio',
                //'value'=>'richiedente.nomeCompleto',
                'value'=>isset($model->CatastoFoglio) ? $model->CatastoFoglio:'',
                'labelColOptions'=>['class'=>'col-sm-1','style'=>'text-align: right;'],
                'valueColOptions'=>['class'=>'col-sm-1',],
            ],
            [
                'attribute'=>'CatastoParticelle', 
                'label'=>'Particelle',
                'labelColOptions'=>['class'=>'col-sm-1','style'=>'text-align: right;'],
                'valueColOptions'=>['class'=>'col-sm-1',],
                'value'=>isset($model->CatastoParticelle) ? $model->CatastoParticelle:'',
                //'value'=>'statoPratica.descrizione',
                //'format'=>'raw', 
                //'value'=>"<span class='badge' style='background-color: {$model->color}'> </span>  <code>" . $model->color . '</code>',
                //'type'=>DetailView::INPUT_COLOR,
                //'valueColOptions'=>['style'=>'width:15%'], 
            ],
            [
                'attribute'=>'CatastoSub', 
                'label'=>'Sub.',
                'labelColOptions'=>['class'=>'col-sm-1','style'=>'text-align: right;'],
                'value'=>isset($model->CatastoSub) ? $model->CatastoSub:'',
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
                'attribute'=>'richiedente',
                'value'=>$model->richiedente->nomeCompleto,
                'labelColOptions'=>['class'=>'col-sm-2','style'=>'text-align: right;'],
                //'labelColOptions'=>['style'=>'width:16%;text-align: right;'],
                'valueColOptions'=>['class'=>'col-sm-2'],
            ],
            [
                'attribute'=>'IndirizzoImmobile',
                'value'=>isset($model->IndirizzoImmobile) ? $model->IndirizzoImmobile : '',
                'labelColOptions'=>['class'=>'col-sm-1','style'=>'text-align: right;'],
                'valueColOptions'=>['class'=>'col-sm-2'],
                //'label'=>'Buy Amount ($)',
                //'format'=>['decimal', 2],
                //'inputContainer' => ['class'=>'col-sm-3'],
                //'valueColOptions'=>['class'=>'col-sm-3'], 
            ],
            [
                'attribute'=>'DescrizioneLavori', 
                'label'=>'Descrizione Intervento',
                'labelColOptions'=>['class'=>'col-sm-1','style'=>'text-align: right;'],
                'valueColOptions'=>['class'=>'col-sm-2'],             
            ],
//             [
//                'attribute'=>'statoPratica', 
//                'value'=>$model->statoPratica->descrizione,
//                //'format'=>'raw', 
//                //'value'=>"<span class='badge' style='background-color: {$model->color}'> </span>  <code>" . $model->color . '</code>',
//                //'type'=>DetailView::INPUT_COLOR,
//                'labelColOptions'=>['class'=>'col-sm-1','style'=>'text-align: right;'],
//                'valueColOptions'=>['class'=>'col-sm-1'], 
//                //'valueColOptions'=>['style'=>'width:25%'],
//            ],
            
            
        ],
    ],
    // Riga intestazione 2 ==> UBICAZIONE IMMOBILE
    
    
    
    [
        'columns' => [
            [
                'attribute'=>'NumeroAUTORIZZAZIONE',
                'label'=>'Numero Autorizzazione SISMICA',
                'labelColOptions'=>['class'=>'col-sm-2','style'=>'text-align: right;'],
                //'value'=>'richiedente.nomeCompleto',
                'valueColOptions'=>['class'=>'col-sm-1',],
            ],
            [
                'attribute'=>'DataAUTORIZZAZIONE', 
                //'format'=>'date',
                'value'=>isset($model->DataAUTORIZZAZIONE) ? Yii::$app->formatter->asDate($model->DataAUTORIZZAZIONE, 'php:d-m-Y'):'',
                'label'=>'Data Autorizzazione',
                //'type'=>DetailView::INPUT_DATE,
                'labelColOptions'=>['class'=>'col-sm-1','style'=>'text-align: right;'],
                'valueColOptions'=>['class'=>'col-sm-1',],
                'widgetOptions' => [
                    'pluginOptions'=>['format'=>'dd-mm-yyyy']
                ],
                //'value'=>'statoPratica.descrizione',
                //'format'=>'raw', 
                //'value'=>"<span class='badge' style='background-color: {$model->color}'> </span>  <code>" . $model->color . '</code>',
                //'type'=>DetailView::INPUT_COLOR,
                //'valueColOptions'=>['style'=>'width:30%'], 
            ],
//            [
//                'attribute'=>'NumeroAutorizzazionePaesaggistica',
//                'label'=>$model->COMPATIBILITA_PAESISTICA ? 'Numero Compatibilità Paesaggistica':'Numero Autorizzazione Paesaggistica',
//                //'value'=>'richiedente.nomeCompleto',
//                'labelColOptions'=>['class'=>'col-sm-2','style'=>'text-align: right;'],
//                'valueColOptions'=>['class'=>'col-sm-1',],
//            ],
//            [
//                'attribute'=>'DataAutorizzazionePaesaggistica', 
//                //'format'=>'date',
//                'label'=>$model->COMPATIBILITA_PAESISTICA ? 'Data Compatibilità Paesaggistica':'Data Autorizzazione Paesaggistica',
//                'value'=>isset($model->DataAutorizzazionePaesaggistica) ? Yii::$app->formatter->asDate($model->DataAutorizzazionePaesaggistica, 'php:d-m-Y'):'',
//                //'type'=>DetailView::INPUT_DATE,
//                'labelColOptions'=>['class'=>'col-sm-2','style'=>'text-align: right;'],
//                'widgetOptions' => [
//                    'pluginOptions'=>['format'=>'dd-mm-yyyy']
//                ],
//                //'value'=>'statoPratica.descrizione',
//                //'format'=>'raw', 
//                //'value'=>"<span class='badge' style='background-color: {$model->color}'> </span>  <code>" . $model->color . '</code>',
//                //'type'=>DetailView::INPUT_COLOR,
//                //'valueColOptions'=>['style'=>'width:30%'], 
//            ],
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
        'heading' => 'Dettaglio Pratica ' . $idsismica,
        
        //'footer' => '<div class="text-center text-muted">.....</div>'
    ],
    'deleteOptions'=>[ // your ajax delete parameters
        'params' => ['id' => $model->sismica_id, 'kvdelete'=>false],
    ],
    'container' => ['id'=>'kv-demo'],
    //'formOptions' => ['action' => Url::current(['#' => 'kv-demo'])] // your action to delete
]);

// Controller action
//public function actionDetailViewDemo() 
//{
//    $model = new Demo;
//    $post = Yii::$app->request->post();   
//    // process ajax delete
//    if (Yii::$app->request->isAjax && isset($post['kvdelete'])) {
//        echo Json::encode([
//            'success' => true,
//            'messages' => [
//                'kv-detail-info' => 'The book # 1000 was successfully deleted. ' . 
//                    Html::a('<i class="fas fa-hand-right"></i>  Click here', 
//                        ['/site/detail-view-demo'], ['class' => 'btn btn-sm btn-info']) . ' to proceed.'
//            ]
//        ]);
//        return;
//    }
//    // return messages on update of record
//    if ($model->load($post) && $model->save()) {
//        Yii::$app->session->setFlash('kv-detail-success', 'Success Message');
//        Yii::$app->session->setFlash('kv-detail-warning', 'Warning Message');
//    }
//    return $this->render('detail-view', ['model'=>$model]);
//}
    
   ?> 
    <table  style="width:100%;border: 1px solid grey;padding:15px">
        <thead>
            
        </thead>
        <tbody>
            <tr >
                <td class="col-sm-2" style="padding:15px;border:1px solid grey;">Autorizzazione Sismica </td>
                <td class="col-sm-2" style="border:1px solid grey;">
                    <?php
                    if (!isset($model->NumeroAUTORIZZAZIONE)) {
                    echo Html::a('<i class="glyphicon glyphicon-equalizer"> Autorizzazione Sismica</i>', Url::to(['sismica/autorizzazione', 'id' => $model->sismica_id]), [
                        'title' => 'Autorizzazione Sismica',
                        'class' => 'btn btn-primary',
                        'disabled' => 'disabled'
                    ]);
                        
                    } else {
                    echo Html::a('<i class="glyphicon glyphicon-equalizer"> Autorizzazione Sismica</i>', Url::to(['sismica/autorizzazione', 'id' => $model->sismica_id]), [
                        'title' => 'Autorizzazione Sismica',
                        'class' => 'btn btn-primary',
                    ]); 
                    }
                    ?>
                    
                </td>
                <td class="col-sm-2" style="padding:15px;border:1px solid grey;">Avviso Rilascio Permesso Costruire </td>
                <td class="col-sm-2" style="border:1px solid grey;">
                    <?php
                    echo Html::a('<i class="glyphicon glyphicon-equalizer"> Avviso Rilascio Autorizzazione Sismica</i>', Url::to(['sismica/AvvisoRilascio', 'id' => $model->sismica_id]), [
                        'title' => 'Autorizzazione Sismica',
                        'class' => 'btn btn-primary',
//                        'data' => [
//                       //'confirm' => '¿Realmente deseas eliminar este elemento?',            
//                       'method' => 'post',
//                        ]
                    ]); 
                    ?>
                </td>
                <td class="col-sm-2" style="padding:15px;border:1px solid grey;">Richiesta Integrazioni</td>
                <td class="col-sm-2" style="border:1px solid grey;">
                    <?php
                    echo Html::a('<i class="glyphicon glyphicon-equalizer"> Richiesta Integrazioni</i>', Url::to(['sismica/richiestaIntegrazioni', 'id' => $model->sismica_id]), [
                        'title' => 'Autorizzazione Sismica',
                        'class' => 'btn btn-primary',
//                        'data' => [
//                       //'confirm' => '¿Realmente deseas eliminar este elemento?',            
//                       'method' => 'post',
//                        ]
                    ]); 
                    ?>
                </td>
            </tr>
            <tr>
                <td class="col-sm-2" style="padding:15px;border:1px solid grey;">Prospetto Liquidazione Commissione</td>
                <td class="col-sm-2" style="border:1px solid grey;">
                    <?php
                    echo Html::a('<i class="glyphicon glyphicon-trash"> Prospetto Liquidazione Commissione</i>', Url::to(['sismica/prospettoCommissione', 'id' => $model->sismica_id]), [
                   'title' => 'Prospetto Liquidazione Commissione',
                   'class' => 'btn btn-primary',
//                   'data' => [
//                       'confirm' => '¿Realmente deseas eliminar este elemento?',            
//                       'method' => 'post',
//                   ]
                    ]); ?>
                </td>
                <td class="col-sm-2" style="padding:15px;border:1px solid grey;">....</td>
                <td class="col-sm-2" style="border:1px solid grey;">
                    <?php
//                    echo Html::a('<i class="glyphicon glyphicon-trash"> Report Incassi Contributo</i>', Url::to(['sismica/report', 'id' => $model->sismica_id]), [
//                   'title' => 'Report Incassi Contributo',
//                   'class' => 'btn btn-primary',
////                   'data' => [
////                       'confirm' => '¿Realmente deseas eliminar este elemento?',            
////                       'method' => 'post',
////                   ]
//                    ]);
                    ?>
                </td>
                <td class="col-sm-2" style="padding:15px;border:1px solid grey;">Comunicazione Responsabile Procedimento</td>
                <td class="col-sm-2" style="border:1px solid grey;">
                    <?php
                    echo Html::a('<i class="glyphicon glyphicon-trash"> Comunicazione Responsabile Procedimento</i>', Url::to(['sismica/rup', 'id' => $model->sismica_id]), [
                   'title' => 'Comunicazione Responsabile Procedimento',
                   'class' => 'btn btn-primary',
//                   'data' => [
//                       'confirm' => '¿Realmente deseas eliminar este elemento?',            
//                       'method' => 'post',
//                   ]
                    ]); ?>
                </td>
            </tr>
            <tr>
                <td class="col-sm-2" style="padding:15px;border:1px solid grey;">Richiesta Pagamento Contributo</td>
                <td class="col-sm-2" style="padding:15px;border:1px solid grey;">       
                    <?php 
                    echo Html::a('<i class="glyphicon glyphicon-pencil"> Richiesta Pagamento Contributo </i>', Url::to(['sismica/contributo', 'id' => $model->sismica_id]), [
                   'title' => 'Richiesta Pagamento Contributo',
                   'class' => 'btn btn-primary',
                    ]);
                    ?>                   
                </td>
                <td class="col-sm-2" style="padding:15px;border:1px solid grey;">....</td>
                <td class="col-sm-2" style="padding:15px;border:1px solid grey;">       
                    <?php 
//                    echo Html::a('<i class="glyphicon glyphicon-pencil"> .... </i>', Url::to(['edilizia/altro', 'id' => $model->sismica_id]), [
//                   'title' => 'Altro ..',
//                   'class' => 'btn btn-primary',
//                    ]);
                    ?>                   
                </td>
                <td class="col-sm-2" style="padding:15px;border:1px solid grey;">.... </td>
                <td class="col-sm-2" style="padding:15px;border:1px solid grey;">       
                    <?php 
//                    echo Html::a('<i class="glyphicon glyphicon-pencil"> Altro </i>', Url::to(['edilizia/altro', 'id' => $model->sismica_id]), [
//                   'title' => 'Altro ..',
//                   'class' => 'btn btn-primary',
//                    ]);
                    ?>                   
                </td>
            </tr>
 <tr>
                <td class="col-sm-2" style="padding:15px;border:1px solid grey;">....</td>
                <td class="col-sm-2" style="padding:15px;border:1px solid grey;">       
                </td>
                <td class="col-sm-2" style="padding:15px;border:1px solid grey;">....</td>
                <td class="col-sm-2" style="padding:15px;border:1px solid grey;">       
                </td>
                <td class="col-sm-2" style="padding:15px;border:1px solid grey;">.... </td>
                <td class="col-sm-2" style="padding:15px;border:1px solid grey;">       
                </td>
            </tr>
            <tr>
                <td class="col-sm-2" style="padding:15px;border:1px solid grey;">....</td>
                <td class="col-sm-2" style="padding:15px;border:1px solid grey;">       
                </td>
                <td class="col-sm-2" style="padding:15px;border:1px solid grey;">....</td>
                <td class="col-sm-2" style="padding:15px;border:1px solid grey;">       
                </td>
                <td class="col-sm-2" style="padding:15px;border:1px solid grey;">....</td>
                <td class="col-sm-2" style="padding:15px;border:1px solid grey;">       
                </td>
            </tr>
            <tr>
                <td class="col-sm-2" style="padding:15px;border:1px solid grey;">....</td>
                <td class="col-sm-2" style="padding:15px;border:1px solid grey;">       
                </td>
                <td class="col-sm-2" style="padding:15px;border:1px solid grey;">....</td>
                <td class="col-sm-2" style="padding:15px;border:1px solid grey;">       
                </td>
                <td class="col-sm-2" style="padding:15px;border:1px solid grey;">.... </td>
                <td class="col-sm-2" style="padding:15px;border:1px solid grey;">       
                </td>
            </tr>
            <tr>
                <td class="col-sm-2" style="padding:15px;border:1px solid grey;">....</td>
                <td class="col-sm-2" style="padding:15px;border:1px solid grey;">       
                </td>
                <td class="col-sm-2" style="padding:15px;border:1px solid grey;">.....</td>
                <td class="col-sm-2" style="padding:15px;border:1px solid grey;">       
                </td>
                <td class="col-sm-2" style="padding:15px;border:1px solid grey;">....</td>
                <td class="col-sm-2" style="padding:15px;border:1px solid grey;">       
                                     
                </td>
            </tr>
</tbody>
</table>
</div>
