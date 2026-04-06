<?php
use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use kartik\grid\GridView;
//use kartik\select2\Select2;
use yii\helpers\Url;
use kartik\dynagrid\DynaGrid;
//use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use app\models\TipoCommissioni;
//use yii\bootstrap\Modal;





/// Definisco i pulsanti da visualizzare sulla Toolbar
//$plus= Html::a('<i class="fas fa-plus"></i>', 
//			['commissioni/nuovaseduta','idtipocommissione'=>$idtipocommissione,'idcommissione'=>$idcommissione,], 
//                        ['title'=>'Aggiungi Seduta', 'class'=>'btn btn-success']);
//$reset = Html::a('<i class="fas fa-undo"></i>', 
//			['index'], ['data-pjax'=>0, 'class' => 'btn btn-default', 'title'=>'Resetta Impostazioni']);
//$filter ='{dynagridFilter}';
//$sort ='{dynagridSort}';
//$setting ='{dynagrid}';
//$toolbar=$plus;
//$toolbar2=$reset.$filter.$sort.$setting;



//$this->registerJsFile("@web/js/jquery-3.5.0.min");
//$url = Yii::$app->urlManager->createUrl('modulesname/controllername/add-contact');
//$this->registerJs("$(function() {
//   $('#popupModal').click(function(e) {
//     e.preventDefault();
//     $('#modal').modal('show').find('.modal-content')
//     .load($(this).attr('href'));
//   });
//});");
$script = <<< JS
//alert($().jquery);
$(".persone-modal-click").click(function () {
//        alert("ok2");
  //          e.preventDefault();
    $("#PresenzeModal")
        .modal("show")
        .find(".modal-body")
        .load($(this).attr("value"));
    //alert("SISI");
  return false;  
});
JS;
$this->registerJs($script);
$this->registerCssFile("@web/css/modal.css");

?>


  



<?php
/* @var $this yii\web\View */
/* @var $model app\models\Edilizia */

$this->title = 'Gestione Sedute Commissioni';
//$this->params['breadcrumbs'][] = ['label' => 'Sedute', 'url' => ['sedute']];
//$this->params['breadcrumbs'][] = $this->title;
?>



<div class="container-fluid" >
    <div class="row">

        <?php  $elcom= TipoCommissioni::find()->all();
        $selcomm='';
        foreach ($elcom as $cm) {
            if ($idtipocommissione==$cm->idtipo_commissioni) {
                $selcomm=$cm->descrizione;
            } else {
            echo Html::a($cm->descrizione, 
                            ['commissioni/sedute','idtipocommissione'=>$cm->idtipo_commissioni], 
                            ['class'=>'btn btn-success', 'style'=>'margin-left:13px;margin-right:3px;margin-top:10px']);
            }
        }
        ?>

    </div>    
    <div class="col-sm" style="margin-top:10px;margin-left:0px;margin-right:0px">
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
                                 'data-confirm' => "Sei sicuro di volere eliminare questa seduta?",
                                 'data-method' => 'post',
                                 //'data-pjax' => 0
                              ]);
                     }],
                'urlCreator' => function ($action, $model, $key, $index) use ($idcommissione) {
                    if ($action === 'delete') {
                    $url = Url::toRoute(['commissioni/cancellasedutacommissione','idseduta'=>$model->idsedute_commissioni,'idcommissione'=>$idcommissione]); // your own url generation logic
                    return $url;
                    }
                    }
                ],
                [   'class'=>'\kartik\grid\SerialColumn', 
                        'order'=>DynaGrid::ORDER_FIX_LEFT],
                ['attribute'=>'numero',
                 'hAlign'=>'center',   
                 'width'=>'%'
                ],
                ['attribute'=>'dataseduta',
                 'format' =>  ['date', 'php:d-m-Y'],
                 'hAlign'=>'center',
                'filterType' => GridView::FILTER_DATE_RANGE,
                'filterWidgetOptions' => [
                        'startAttribute' => 'data_inizio_seduta', //Attribute of start time
                        'endAttribute' => 'data_fine_seduta',   //The attributes of the end time
                        'convertFormat'=>true, // Importantly, true uses the local - > format time format to convert PHP time format to js time format.
                        'pluginOptions' => [
                            'format' => 'yyyy-mm-dd',//Date format
                            'locale'=>['format' => 'd-m-Y'], //php formatting time
                        ]
                    ],

                //The use of single time selector
                'width'=>'11%'],
                ['attribute'=>'stato',
                'hAlign'=>'center',
                'value'=>'stato.descrizione',
                'filter' => ArrayHelper::map(app\models\StatoSedute::find()->asArray()->all(), 'idstato_sedute', 'descrizione'),
                ],
                ['attribute'=>'orarioconvocazione',
                ],
                ['attribute'=>'orarioinizio',
                ],
                ['attribute'=>'orariofine',
                ],
                [
                'class'=>'kartik\grid\ActionColumn',
                'order'=>DynaGrid::ORDER_FIX_RIGHT,
                'template' => '{pratiche}',
                'header'=>'',
                'width'=>'5%',
                'buttons' => [
                    'pratiche' => function ($url, $model) {
                        return Html::a('<span class="fas fa-book"></span>', $url, [
                        'title' => 'pratiche',
                        ]);}
                    ],
                'urlCreator' => function ($action, $model, $key, $index) use($idcommissione) {
                    if ($action === 'pratiche') {
                    $url = Url::toRoute(['commissioni/parerisedute','idcommissione'=>$idcommissione,'idseduta'=>$model->idsedute_commissioni]); // your own url generation logic
                    return $url;
                    }
                }
                ],
                [
                'class'=>'kartik\grid\ActionColumn',
                'order'=>DynaGrid::ORDER_FIX_RIGHT,
                'template' => '{presenze}',
                'header'=>'',
                'width'=>'5%',
                'buttons' => [
                    'presenze' => function ($url, $model) {
                        return Html::a('<span class="fas fa-user quick-modal"></span>','#', [
                        //'title' => 'modifica',
                        'value'=>Url::toRoute(['commissioni/rilevapresenze','idseduta'=>$model->idsedute_commissioni]),    
                        'class'=>'persone-modal-click',   
//                        'onclick'=>'return false;',    
//                        'data-toggle' => 'modal',
//                        'data-target' => '#PresenzeFormModel',    
                        // 'id'=>'modalButton',
                        ]);
                    }
                    ],
                 ],
                [
                'class'=>'kartik\grid\ActionColumn',
                'order'=>DynaGrid::ORDER_FIX_RIGHT,
                'template' => '{modifica}',
                'header'=>'',
                'width'=>'5%',
                'buttons' => [
                    'modifica' => function ($url, $model) {
                        return Html::a('<span class="fas fa-pencil-alt"></span>', $url, [
                        'title' => 'modifica',
                        ]);}
                    ],
                'urlCreator' => function ($action, $model, $key, $index) use($idcommissione) {
                    if ($action === 'modifica') {
                    $url = Url::toRoute(['commissioni/modificaseduta','idcommissione'=>$idcommissione,'idseduta'=>$model->idsedute_commissioni]); // your own url generation logic
                    return $url;
                    }
                }
                ],
                [
                'class'=>'kartik\grid\ActionColumn',
                'order'=>DynaGrid::ORDER_FIX_RIGHT,
                'template' => '{verbale}',
                'header'=>'',
                'width'=>'5%',
                'buttons' => [
                    'verbale' => function ($url, $model) {
                        return Html::a('<span class="fas fa-file-word"></span>', $url, [
                        'title' => 'verbale',
                        ]);}
                    ],
                'urlCreator' => function ($action, $model, $key, $index) use($idcommissione) {
                    if ($action === 'verbale') {
                    $url = Url::toRoute(['commissioni/compilaverbale','idseduta'=>$model->idsedute_commissioni]); // your own url generation logic
                    return $url;
                    }
                }
                ],
            ],
            ////////////////////////////////
            ///// OPZIONI DELLA TABELLA ////
            ///////////////////////////////
            'storage'=>'db', //DynaGrid::TYPE_COOKIE,
            'dbUpdateNameOnly'=>true,
            'theme'=>'panel-info',
            //'emptyCell'=>' ',
            'showPersonalize'=>true,
            'gridOptions'=>[
                'dataProvider'=>$Provider,
                'filterModel'=>$Search,
                'showPageSummary'=>false,

                'panel'=>[
                        'heading'=>'<h3 class="panel-title"><b>ELENCO SEDUTE DELLA COMMISSIONE : ' . $selcomm . '<b></h3>',
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
                    ['content'=>Html::a('<i class="fas fa-plus"></i>', 
                                ['commissioni/nuovaseduta','idtipocommissione'=>$idtipocommissione,'idcommissione'=>$idcommissione,], 
                                ['title'=>'Aggiungi Seduta', 'class'=>'btn btn-success'])
                    ],
                    ['content'=>Html::a('<i class="fas fa-undo"></i>', 
                                ['index'], ['data-pjax'=>0, 'class' => 'btn btn-default', 'title'=>'Resetta Impostazioni'])
                    ],
                    ['content'=>'{dynagridFilter}{dynagridSort}{dynagrid}{export}'
                    ],
                ]
                ], // gridOption
            'options'=>['id'=>'dynagrid-cmn2'] // a unique identifier is important
        ]);
    ?> 
</div>
<!-- Button trigger modal -->
<!--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#PresenzeModal">
  Launch demo modal
</button>
</div>    -->



<!-- Modal -->
<div class="modal fade" id="PresenzeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Registrazione Presenti</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
        <?php echo Html::button('Salva', ['class' => 'btn btn-success', 'id'=>'aggiornaButton']); ?>
<!--        <button type="button" class="btn btn-primary">Salva</button>-->
      </div>
    </div>
  </div>
</div>
