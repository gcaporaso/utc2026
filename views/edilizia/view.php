<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Edilizia */

$this->title = 'Pratica Edilizia ' . $model->edilizia_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pratiche'), 'url' => ['index']];
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
        'label'=>'SEZIONE 1: Dati Istanza',
        'rowOptions'=>['class'=>'bg-info']
    ],
    [
        'columns' => [
            [
                'attribute'=>'NumeroProtocollo', 
                //'format'=>'raw', 
                //'value'=>'<kbd>'.$model->book_code.'</kbd>',
                'valueColOptions'=>['style'=>'width:24%'], 
                'displayOnly'=>true
            ],
            [
                'attribute'=>'DataProtocollo', 
                'labelColOptions'=>['style'=>'width:13%;text-align: right;'],
                'value'=>Yii::$app->formatter->asDate($model->DataProtocollo, 'php:d-m-Y'),
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
            [
                'attribute'=>'Sanatoria', 
                //'format'=>'raw', 
                'value'=>$model->Sanatoria ? 'Si' : 'No',
                'valueColOptions'=>['class'=>'col-sm-3'], 
                'displayOnly'=>true
            ],
            
        ],
    ],
    [
        'columns' => [
            [
                'attribute'=>'DescrizioneIntervento', 
                'label'=>'Descrizione Intervento',
                'valueColOptions'=>['style'=>'width:24%'],
                //'groupOptions'=>['class'=>'text-center']
            ],
            [
                'attribute'=>'tipologia', 
                'value'=>$model->tipologia->sommarioTipologia,
                'labelColOptions'=>['style'=>'width:13%;text-align: right;'],
                //'label'=>'Descrizione Intervento',
                //'groupOptions'=>['class'=>'text-center']
            ],
        ],
    ],
    [
        'columns' => [
            [
                'attribute'=>'richiedente',
                'value'=>$model->richiedente->nomeCompleto,
                //'labelColOptions'=>['style'=>'width:16%;text-align: right;'],
                'valueColOptions'=>['style'=>'width:24%'],
            ],
            [
                'attribute'=>'statoPratica', 
                'value'=>$model->statoPratica->descrizione,
                //'format'=>'raw', 
                //'value'=>"<span class='badge' style='background-color: {$model->color}'> </span>  <code>" . $model->color . '</code>',
                //'type'=>DetailView::INPUT_COLOR,
                'labelColOptions'=>['style'=>'width:13%;text-align: right;'],
                //'valueColOptions'=>['style'=>'width:25%'],
            ],
        ],
    ],
    // Riga intestazione 2 ==> UBICAZIONE IMMOBILE
    [
        'group'=>true,
        'label'=>'SEZIONE 2: Ubicazione immobile oggetto di intervento e Riferimenti Catastali',
        'rowOptions'=>['class'=>'bg-info'],
        //'groupOptions'=>['class'=>'text-center']
    ],
    [
        'attribute'=>'IndirizzoImmobile',
        'value'=>isset($model->IndirizzoImmobile) ? $model->IndirizzoImmobile : '',
        //'label'=>'Buy Amount ($)',
        //'format'=>['decimal', 2],
        //'inputContainer' => ['class'=>'col-sm-3'],
        //'valueColOptions'=>['class'=>'col-sm-3'], 
    ],
    [
        'attribute'=>'CatastoTipo',
        'value'=>$model->CatastoTipo ? 'Terreni' : 'Fabbricati',
        'label'=>'Catasto',
        //'format'=>['decimal', 2],
        //'inputContainer' => ['class'=>'col-sm-3'],
        //'valueColOptions'=>['class'=>'col-sm-3'], 
    ],
    [
        'columns' => [
            [
                'attribute'=>'CatastoFoglio',
                'label'=>'Foglio',
                //'value'=>'richiedente.nomeCompleto',
                'value'=>isset($model->CatastoFoglio) ? $model->CatastoFoglio:'',
                'valueColOptions'=>['style'=>'width:24%'],
            ],
            [
                'attribute'=>'CatastoParticella', 
                'label'=>'Particella',
                'labelColOptions'=>['style'=>'width:13%;text-align: right;'],
                'valueColOptions'=>['style'=>'width:25%'],
                'value'=>isset($model->CatastoParticella) ? $model->CatastoParticella:'',
                //'value'=>'statoPratica.descrizione',
                //'format'=>'raw', 
                //'value'=>"<span class='badge' style='background-color: {$model->color}'> </span>  <code>" . $model->color . '</code>',
                //'type'=>DetailView::INPUT_COLOR,
                //'valueColOptions'=>['style'=>'width:15%'], 
            ],
            [
                'attribute'=>'CatastoSub', 
                'label'=>'Sub.',
                'labelColOptions'=>['style'=>'width:5%;text-align: right;'],
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
                'attribute'=>'Latitudine',
                'valueColOptions'=>['style'=>'width:24%'],
                //'value'=>'richiedente.nomeCompleto',
                //'valueColOptions'=>['style'=>'width:15%'],
            ],
            [
                'attribute'=>'Longitudine', 
                'labelColOptions'=>['style'=>'width:13%;text-align: right;'],
                //'value'=>'statoPratica.descrizione',
                //'format'=>'raw', 
                //'value'=>"<span class='badge' style='background-color: {$model->color}'> </span>  <code>" . $model->color . '</code>',
                //'type'=>DetailView::INPUT_COLOR,
                //'valueColOptions'=>['style'=>'width:30%'], 
            ],
        ],
    ],
     // Riga intestazione 3 ==> STATO PRATICA
    [
        'group'=>true,
        'label'=>'SEZIONE 3: Stato Pratica',
        
        'rowOptions'=>['class'=>'bg-info'],
        //'groupOptions'=>['class'=>'text-center']
    ],
    [
        'attribute'=>'statoPratica',
        'value'=>$model->statoPratica->descrizione,
        //'label'=>'Buy Amount ($)',
        //'format'=>['decimal', 2],
        'inputContainer' => ['class'=>'col-sm-3'],
    ],
    [
        'columns' => [
            [
                'attribute'=>'NumeroTitolo',
                'label'=>'Numero Permesso di Costruire',
                //'value'=>'richiedente.nomeCompleto',
                'valueColOptions'=>['style'=>'width:24%'],
            ],
            [
                'attribute'=>'DataTitolo', 
                //'format'=>'date',
                'value'=>isset($model->DataTitolo) ? Yii::$app->formatter->asDate($model->DataTitolo, 'php:d-m-Y'):'',
                'label'=>'Data Permesso',
                //'type'=>DetailView::INPUT_DATE,
                'labelColOptions'=>['style'=>'width:13%;text-align: right;'],
                'widgetOptions' => [
                    'pluginOptions'=>['format'=>'dd-mm-yyyy']
                ],
                //'value'=>'statoPratica.descrizione',
                //'format'=>'raw', 
                //'value'=>"<span class='badge' style='background-color: {$model->color}'> </span>  <code>" . $model->color . '</code>',
                //'type'=>DetailView::INPUT_COLOR,
                //'valueColOptions'=>['style'=>'width:30%'], 
            ],
        ],
    ],
    [
        'columns'=>  [
            [
            'attribute'=>'AutPaesistica',
            'value'=>$model->AutPaesistica ? 'Si' : 'No',
            'label'=>'Soggetta ad Autorizzazione Paesistica?',
            //'label'=>'Buy Amount ($)',
            //'format'=>['decimal', 2],
            'valueColOptions'=>['style'=>'width:24%'],
            ],
            [
            'attribute'=>'COMPATIBILITA_PAESISTICA',
            'value'=>$model->COMPATIBILITA_PAESISTICA ? 'Si' : 'No',
            'label'=>'Trattasi di Compatibilità Paesistica?',
            //'label'=>'Buy Amount ($)',
            //'format'=>['decimal', 2],
            'labelColOptions'=>['style'=>'width:13%;text-align: right;'],
            ],
        ],
    ],
    [
        'columns' => [
            [
                'attribute'=>'NumeroAutorizzazionePaesaggistica',
                'label'=>$model->COMPATIBILITA_PAESISTICA ? 'Numero Compatibilità Paesaggistica':'Numero Autorizzazione Paesaggistica',
                //'value'=>'richiedente.nomeCompleto',
                'valueColOptions'=>['style'=>'width:24%'],
            ],
            [
                'attribute'=>'DataAutorizzazionePaesaggistica', 
                //'format'=>'date',
                'label'=>$model->COMPATIBILITA_PAESISTICA ? 'Data Compatibilità Paesaggistica':'Data Autorizzazione Paesaggistica',
                'value'=>isset($model->DataAutorizzazionePaesaggistica) ? Yii::$app->formatter->asDate($model->DataAutorizzazionePaesaggistica, 'php:d-m-Y'):'',
                //'type'=>DetailView::INPUT_DATE,
                'labelColOptions'=>['style'=>'width:13%;text-align: right;'],
                'widgetOptions' => [
                    'pluginOptions'=>['format'=>'dd-mm-yyyy']
                ],
                //'value'=>'statoPratica.descrizione',
                //'format'=>'raw', 
                //'value'=>"<span class='badge' style='background-color: {$model->color}'> </span>  <code>" . $model->color . '</code>',
                //'type'=>DetailView::INPUT_COLOR,
                //'valueColOptions'=>['style'=>'width:30%'], 
            ],
        ],
    ],
    
    
    // Riga intestazione 4 ==> TECNICI INCARICATI
    [
        'group'=>true,
        'label'=>'SEZIONE 4: Tecnici incaricati',
        'rowOptions'=>['class'=>'bg-info'],
        //'type'=>DetailView::INPUT_SWITCH,
        //'groupOptions'=>['class'=>'text-center']
    ],
    [
        'columns' => [
            [
                'attribute'=>'progettistaArchitettonico',
                'value'=> isset($model->progettistaArchitettonico) ? $model->progettistaArchitettonico->nomeCompleto: '',
                'label'=>'Progettista Architettonico',
                'valueColOptions'=>['style'=>'width:24%'],
            ],
            [
                'attribute'=>'direttoreArchitettonico',
                'value'=>isset($model->direttoreArchitettonico) ? $model->direttoreArchitettonico->nomeCompleto:'',
                'label'=>'Direttore Lavori Architettonico',
                'labelColOptions'=>['style'=>'width:13%;text-align: right;'],
                //'valueColOptions'=>['style'=>'width:20%'],
            ],
        ],
    ],
    [
        'columns' => [
            [
                'attribute'=>'progettistaStrutturale',
                'value'=>isset($model->progettistaStrutturale) ? $model->progettistaStrutturale->nomeCompleto :'',
                'label'=>'Progettista Strutturale',
                'valueColOptions'=>['style'=>'width:24%'],
            ],
            [
                'attribute'=>'DIR_LAV_STR_ID',
                'value'=>isset($model->direttoreStrutturale) ? $model->direttoreStrutturale->nomeCompleto:'',
                'label'=>'Direttore Lavori Strutturale',
                'labelColOptions'=>['style'=>'width:13%;text-align: right;'],
            ],
        ],
    ],
    [
        'columns' => [
            [
                'attribute'=>'IMPRESA_ID',
                'value'=>isset($model->impresa) ? $model->impresa->nomeCompleto:'',
                'label'=>'Impresa Esecutrice',
                'valueColOptions'=>['style'=>'width:24%'],
            ],
            [
                'attribute'=>'Collaudatore_id',
                'value'=>isset($model->Collaudatore) ? $model->Collaudatore->nomeCompleto:'',
                'labelColOptions'=>['style'=>'width:13%;text-align: right;'],
                'label'=>'Collaudatore',
            ],
        ],
    ],
    
    // Riga intestazione 5 ==> STATO ONERI CONCESSORI
    [
        'group'=>true,
        'label'=>'SEZIONE 5: Stato Oneri Concessori',
        'rowOptions'=>['class'=>'bg-info'],
        //'groupOptions'=>['class'=>'text-center']
    ],
    [
                'attribute'=>'TitoloOneroso', 
                'label'=>'Soggetta a Oneri Concessori?', 
                'value'=>$model->TitoloOneroso ? 'Si' : 'No',
                //'valueColOptions'=>['class'=>'col-sm-3'], 
                //'displayOnly'=>true
    ],
    [
        'attribute'=>'Oneri_Costruzione',
        'label'=>'Oneri Costruzione (€)',
        //'value'=>isset($model->ONERI) ? $model->ONERI:'',
        'format'=>['decimal', 2],
        'inputContainer' => ['class'=>'col-sm-6'],
    ],
    [
        'attribute'=>'Oneri_Urbanizzazione',
        'label'=>'Oneri Urbanizzazione (€)',
        //'value'=>isset($model->ONERI) ? $model->ONERI:'',
        'format'=>['decimal', 2],
        'inputContainer' => ['class'=>'col-sm-6'],
    ],
    [
        'attribute'=>'Oneri_Pagati',
        'label'=>'Oneri Pagati (€)',
        'format'=>['decimal', 2],
        'inputContainer' => ['class'=>'col-sm-6'],
    ],
    [
        'label'=>'Differenza (€)',
        'value'=>$model->Oneri_Costruzione + $model->Oneri_Urbanizzazione - $model->Oneri_Pagati,
        'format'=>['decimal', 2],
        'inputContainer' => ['class'=>'col-sm-6'],
        // hide this in edit mode by adding `kv-edit-hidden` CSS class
        'rowOptions'=>['class'=>'warning kv-edit-hidden', 'style'=>'border-top: 5px double #dedede'],
    ],
   
   // Riga intestazione 6 ==> STATO LAVORI
   [
        'group'=>true,
        'label'=>'SEZIONE 6: Stato Lavori',
        
        'rowOptions'=>['class'=>'bg-info'],
        //'groupOptions'=>['class'=>'text-center']
    ],
    [
        'columns' => [
            [
                'attribute'=>'Data_Inizio_Lavori', 
                //'format'=>'date',
                'label'=>'Data Inizio Lavori',
                'value'=>isset($model->Data_Inizio_Lavori) ? Yii::$app->formatter->asDate($model->Data_Inizio_Lavori, 'php:d-m-Y'):'',
                //'type'=>DetailView::INPUT_DATE,
                'valueColOptions'=>['style'=>'width:24%'],
                //'labelColOptions'=>['style'=>'width:13%;text-align: right;'],
                'widgetOptions' => [
                    'pluginOptions'=>['format'=>'dd-mm-yyyy']
                ],
            ],
            [
                'attribute'=>'Data_Fine_Lavori', 
                //'format'=>'date',
                'label'=>'Data Fine Lavori',
                'value'=>isset($model->Data_Fine_Lavori) ? Yii::$app->formatter->asDate($model->Data_Fine_Lavori, 'php:d-m-Y'):'',
                //'type'=>DetailView::INPUT_DATE,
                'labelColOptions'=>['style'=>'width:13%;text-align: right;'],
                'widgetOptions' => [
                    'pluginOptions'=>['format'=>'dd-mm-yyyy']
                ],
                //'value'=>'statoPratica.descrizione',
                //'format'=>'raw', 
                //'value'=>"<span class='badge' style='background-color: {$model->color}'> </span>  <code>" . $model->color . '</code>',
                //'type'=>DetailView::INPUT_COLOR,
                //'valueColOptions'=>['style'=>'width:30%'], 
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
        'heading' => 'Dettaglio Pratica ' . $idedilizia,
        
        //'footer' => '<div class="text-center text-muted">.....</div>'
    ],
    'deleteOptions'=>[ // your ajax delete parameters
        'params' => ['id' => $model->edilizia_id, 'kvdelete'=>false],
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
    
    
    
    
    
    
    
    
    <?php // DetailView::widget([
//        'model' => $model,
//        'attributes' => [
//            'edilizia_id',
//            'idpratica',
//            'DataProtocollo',
//            'NumeroProtocollo',
//            'id_committente',
//            'id_titolo',
//            'DescrizioneIntervento',
//            'PROGETTISTA_ARC_ID',
//            'PROGETTISTA_ARCHITETTONICO',
//            'DIR_LAV_ARCH_ID',
//            'DIRETTORE_LAVORI_ARCHITETTONICO',
//            'PROGETTISTA_STR_ID',
//            'PROGETTISTA_STRUTTURE',
//            'DIR_LAV_STR_ID',
//            'DIRETTORE_LAVORI_STRUTTURE',
//            'IMPRESA_ID',
//            'IMPRESA',
//            'CatastoFoglio',
//            'CatastoParticella',
//            'CatastoSub',
//            'ESITO-UTC',
//            'Data_OK',
//            'Stato_Pratica_id',
//            'Latitudine',
//            'Longitudine',
//            'AutPaesistica',
//            'Diritti_AP',
//            'NumeroTitolo',
//            'DataTitolo',
//            'Sanatoria',
//            'NumeroAutorizzazionePaesaggistica',
//            'DataAutorizzazionePaesaggistica',
//            'COMPATIBILITA_PAESISTICA',
//            'TitoloOneroso',
//            'ONERI',
//            'Pagato',
//            'Data_Inizio_Lavori',
//            'Data_Fine_Lavori',
//            'CatastoTipo',
//            'IndirizzoImmobile',
//        ],
//    ]) ?>

</div>
<div class="kv-flat-b">
    <div class="kv-detail-view table-responsive">
        <table id="wf" class="table table-bordered table-condensed detail-view">
            <tbody><tr class="bg-info"><th colspan="2">SEZIONE 7: Allegati Amministrativi</th></tr>
            <tr class="kv-child-table-row"><td class="kv-child-table-cell" colspan="2">
                    <table class="kv-child-table">
                        <tbody><tr class="kv-child-table"><th  style="width: 50%;">Descrizione</th>
                                    <th style="width: 25%;">Tipo</th>
                                    <th style="width: 25%;">byte</th></tr>
            
<?php
foreach ($alegal as $legal) {
    echo '<tr>';
    echo '<td style="width: 50%;background-color:white;border-left: 1px #ddd solid;border-right:1px #ddd solid;border-bottom:1px #ddd solid;">'. Html::a($legal->descrizione,['edilizia/download','filename'=> $legal->path . $legal->nomefile]) .'</td>';
    echo '<td style="width: 25%;background-color:white;border-left: 1px #ddd solid;border-right:1px #ddd solid;border-bottom:1px #ddd solid;">'. $legal->tipo .'</td>';
    echo '<td style="width: 25%;background-color:white;border-left: 1px #ddd solid;border-right:1px #ddd solid;border-bottom:1px #ddd solid;">'. $legal->byte .'</td>';
    echo '</tr>';
}
?>
            
                        </tbody>
                    </table>
                </td></tr></tbody></table>            
</div>
</div>
<div class="kv-flat-b">
    <div class="kv-detail-view table-responsive">
        <table id="wf" class="table table-bordered table-condensed detail-view">
            <tbody><tr class="bg-info"><th colspan="2">SEZIONE 7: Allegati Tecnici</th></tr>
            <tr class="kv-child-table-row"><td class="kv-child-table-cell" colspan="2">
                    <table class="kv-child-table">
                        <tbody><tr class="kv-child-table"><th  style="width: 50%;">Descrizione</th>
                                    <th style="width: 25%;">Tipo</th>
                                    <th style="width: 25%;">byte</th></tr>
            
<?php
foreach ($atecnici as $tec) {
    echo '<tr>';
    echo '<td style="width: 50%;background-color:white;border-left: 1px #ddd solid;border-right:1px #ddd solid;border-bottom:1px #ddd solid;">'. Html::a($tec->descrizione,['edilizia/download','filename'=> $tec->path . $tec->nomefile]) .'</td>';
    echo '<td style="width: 25%;background-color:white;border-left: 1px #ddd solid;border-right:1px #ddd solid;border-bottom:1px #ddd solid;">'. $tec->tipo .'</td>';
    echo '<td style="width: 25%;background-color:white;border-left: 1px #ddd solid;border-right:1px #ddd solid;border-bottom:1px #ddd solid;">'. $tec->byte .'</td>';
    echo '</tr>';
}
?>
            
                        </tbody>
                    </table>
                </td></tr></tbody></table>            
</div>
</div>
    <div><hr></div>