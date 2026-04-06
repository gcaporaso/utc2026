<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Edilizia */

$this->title = 'Eventi e Dettagli Procedura Pratica Edilizia ' . $model->edilizia_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pratiche'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="edilizia-view">


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
                'attribute'=>'NumeroProtocollo', 
                'labelColOptions'=>['class'=>'col-sm-1','style'=>'text-align: right;'],
                'valueColOptions'=>['class'=>'col-sm-1'], 
                'displayOnly'=>true
            ],
            [
                'attribute'=>'DataProtocollo', 
                'labelColOptions'=>['class'=>'col-sm-1','style'=>'text-align: right;'],
                'value'=>Yii::$app->formatter->asDate($model->DataProtocollo, 'php:d-m-Y'),
                'widgetOptions' => [
                    'pluginOptions'=>['format'=>'dd-mm-yyyy']
                ],
                'labelColOptions'=>['class'=>'col-sm-1','style'=>'text-align: right;'],
                'valueColOptions'=>['class'=>'col-sm-1'], 
                'displayOnly'=>true
            ],
            [
                'attribute'=>'richiedente',
                'value'=>$model->richiedente->nomeCompleto,
                'labelColOptions'=>['class'=>'col-sm-1','style'=>'text-align: right;'],
                'valueColOptions'=>['class'=>'col-sm-2'],
            ],
            [
                'attribute'=>'DescrizioneIntervento', 
                'label'=>'Descrizione Intervento',
                'labelColOptions'=>['class'=>'col-sm-1','style'=>'text-align: right;'],
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
        'heading' => 'Dettaglio Pratica ' . $model->edilizia_id,
        
        //'footer' => '<div class="text-center text-muted">.....</div>'
    ],
    'deleteOptions'=>[ // your ajax delete parameters
        'params' => ['id' => $model->edilizia_id, 'kvdelete'=>false],
    ],
    'container' => ['id'=>'kv-demo'],
]);
    
   ?> 
<?php 
    // Costruisco Array di eventi
    // Permesso di costruire
    $eventi=[['Tipo'=>1,'Evento'=>'Presentazione Istanza','data'=>$model->DataProtocollo]];
    // entro 10 giorni 
    $eventi = $eventi + [['Tipo'=>2,'Evento'=>'Scadenza Termine Comunicazione Nome Responsabile Procedimento','data'=>date('Y-m-d', strtotime($model->DataProtocollo. ' + 10 days'))]];
    // entro 30 giorni
    $eventi = $eventi + [['Tipo'=>3,'Evento'=>'Scadenza Termine Richiesta Integrazioni','data'=>date('Y-m-d', strtotime($model->DataProtocollo. ' + 30 days'))]];
    // entro 60 giorni
    $eventi = $eventi + [['Tipo'=>4,'Evento'=>'Scadenza Termine Acquisizione Documenti','data'=>date('Y-m-d', strtotime($model->DataProtocollo. ' + 60 days'))]];
    // entro 90 giorni
    $eventi = $eventi + [['Tipo'=>5,'Evento'=>'Scadenza Termine Emissione Provvedimento Finale','data'=>date('Y-m-d', strtotime($model->DataProtocollo. ' + 90 days'))]];
    
    
      
      
    //$arr = array(["evento"=>"prova1","data"=>"11-02-2016"],["evento"=>"prova2","data"=>"11-01-2015"],["evento"=>"prova3","data"=>"11-03-2019"]);    
    function date_sort($a, $b) {
        $a1=strtotime($a['data']);
        $b1=strtotime($b['data']);
        return ($a1-$b1);
    }
    usort($eventi, "date_sort");
?>
<ul class="timeline">

    <!-- timeline time label -->
    <li class="time-label">
        <span class="bg-red">
            <?php echo date('d-m-Y',strtotime($model->DataProtocollo)) ?>
        </span>
    </li>
    <!-- /.timeline-label -->

    <!-- Termini del procedimento -->
    <?php foreach($eventi as $key => $value)
        {
//            if($key == 'date')
//            {
//      
//            }
        ?>
    <!-- timeline item -->
    <li>
        <!-- timeline icon -->
        <i class="fa fa-envelope bg-blue"></i>
        <div class="timeline-item">
            <span class="time"><i class="fa fa-clock-o"></i></span>

            <h3 class="timeline-header"><a href="#"><?php echo date('d-m-Y', strtotime($value['data'])). ' - '. $value['Evento'] ?></a> ...</h3>

            <div class="timeline-body">
                
            </div>

            <div class="timeline-footer">
                <a class="btn btn-primary btn-xs">info</a>
            </div>
        </div>
    </li>
    
    <?php } ?>
    
    
    
    
    
    
    <!-- timeline item -->
    <li>
        <!-- timeline icon -->
        <i class="fa fa-envelope bg-blue"></i>
        <div class="timeline-item">
            <span class="time"><i class="fa fa-clock-o"></i> 12:05</span>

            <h3 class="timeline-header"><a href="#">Support Team</a> ...</h3>

            <div class="timeline-body">
                ...
                Content goes here
            </div>

            <div class="timeline-footer">
                <a class="btn btn-primary btn-xs">...</a>
            </div>
        </div>
    </li>
    <!-- END timeline item -->

    

</ul>