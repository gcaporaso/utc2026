<!--<script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>-->
     
    <!-- the jQuery Library -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
     
  
     
<?php
use kartik\select2\Select2;
//use yii\helpers\ArrayHelper;
//use kartik\dynagrid\DynaGrid;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\Committenti;
use app\models\TitoliPossesso;
//use kartik\file\FileInput;
//use kartik\widgets\FileInput;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
//use yii\web\View;
//use hail812\adminlte\widgets\Alert;

\hail812\adminlte3\assets\PluginAsset::register($this)->add(['sweetalert2','select2','bs-custom-file-input']);


?>







<!--<section class="content-header">
<div class="container-fluid">
<div class="row mb-2">
<div class="col-sm-6">
<h1>Composizione Documenti</h1>
</div>
<div class="col-sm-6">
<ol class="breadcrumb float-sm-right">
<li class="breadcrumb-item"><a href="#">Home</a></li>
<li class="breadcrumb-item active">Advanced Form</li>
</ol>
</div>
</div>
</div>
</section>-->

<div class="container-fluid" >
    <div class="row">
        <div class="card card-secondary col-md-12 text-sm"  style="margin-top: 10px" >
             <div class="card-header" >
                <h3 class="card-title">Riepilogo Dati Pratica Sismica</h3>

                <!-- Button per ridimensionare e rimuovere CARD -->
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                    </button>
<!--                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                    </button>-->
                </div>
            </div>
            
             <div class="card-body">
                <div class="row">
                    <p><span>Richiedente:</span></p><div class="col-sm-2" style="color:blue"><?= $modelp->richiedente->nomeCompleto ?></div>
                    <p><span>Protocollo:</span></p><div class="col-sm-1" style="color:blue;"><?= $modelp->Protocollo ?></div>
                    <p><span>Data:</span></p><div class="col-sm-1" style="color:blue"><?= Yii::$app->formatter->asDate($modelp->DataProtocollo, 'php:d-m-Y') ?></div>
<!--                    <p><span>Sanatoria:</span></p><div class="col-sm-1" style="color:blue"><?php // ($modelp->Sanatoria==1 ? 'Si' : 'No') ?></div>-->
<!--                    <p><span>Stato:</span></p><div class="col-sm-1" style="color:blue"><?php // $modelp->statoPratica->descrizione ?></div>-->
                    <p><span>Foglio:</span></p><div class="col-sm-1" style="color:blue"><?= $modelp->CatastoFoglio ?></div>
                    <p><span>Particella:</span></p><div class="col-sm-1" style="color:blue"><?= $modelp->CatastoParticelle ?></div>
                    <p><span>Sub:</span></p><div class="col-sm-1" style="color:blue"><?= $modelp->CatastoSub ?></div>
                </div>
                <div class="row"> 
<!--                    <p><span>Indirizzo:</span></p><div class="col-sm-2" style="color:blue"><?php // $modelp->IndirizzoImmobile ?></div>-->
                    <p><span>Intervento:</span></p><div class="col-sm-9" style="color:blue" ><?= $modelp->DescrizioneLavori ?></div>
                    
                </div> 
            </div>
        </div>
    </div>

    <!-- toolbar -->
<div class="row">
    <div class="card card-default col-md-12"  >
            <!-- TOOLBAR -->
            <div class="card-header" >
                <?= Html::a('Allegati', ['sismica/allegati','idpratica'=>$idpratica], ['class'=>'btn btn-success']) ?>
                <?= Html::a('Soggetti Coinvolti', ['sismica/soggetti','idpratica'=>$idpratica], ['class'=>'btn btn-success','style'=>'margin-left:10px']) ?>
                <?= Html::a('Composizione Atti', ['sismica/atti','idpratica'=>$idpratica], ['class'=>'btn btn-success','style'=>'margin-left:10px']) ?>
            </div>
    </div>
</div>

    <div class="row">

        <div class="card card-secondary col-md-12"  style="margin-top: 5px" >
            <!-- INTESTAZIONE -->
            <div class="card-header" >
                <h3 class="card-title">Gestione TECNICI INCARICATI</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        
        <!-- CONTENUTO -->   
<!--            <div class="row" style="margin: 5px 5px 2px 5px">-->
        
               

            <div class="card-body">
                <div class="row">
                    <!-- **************** -->
                    <!-- *** ALLEGATI *** -->
                    <!-- **************** -->
                
                         
                        <div class="col-md-4">  
                        <?php $form = ActiveForm::begin([
                            'action'=>['sismica/tecnici', 'idpratica'=>$idpratica],
                            'id' => 'soggetti-tecnici-form',
                        ]) ?>
                             <?php
                                $data = ArrayHelper::map(\app\models\Tecnici::find()->all(), 'tecnici_id', 'nometecnico');
                                echo $form->field($model, 'tecnici_id')->widget(Select2::classname(), [
                                   'data' => $data,
                                   'options' => ['class' => 'form-control','placeholder' => 'Soggetto ...'],
                                   'pluginOptions' => [
                                       'initialize' => true,
                                       'allowClear' => true
                                   ],])->label('Soggetto'); 
                            ?>
                            
                        </div>
                        <div class="col-md-3">    
                            <?php
                                $titoli = ArrayHelper::map(\app\models\RuoliTecnici::find()->all(), 'idruoli_tecnici', 'ruolo');
                                echo $form->field($model, 'ruolo_id')->widget(Select2::classname(), [
                                   'data' => $titoli,
                                   'options' => ['class' => 'form-control','placeholder' => 'Incarico ...'],
                                   'pluginOptions' => [
                                       'initialize' => true,
                                       'allowClear' => true
                                   ],])->label('Incarico'); 
                            ?>
                           
                            
                        </div>
                    
                                       
                        <div class="col-md-2"> 
                                <?= $form->field($model, 'data_inizio')->widget(DateControl::classname(), [
                                            'type' => 'date',
                                            'ajaxConversion' => false,
                                            'autoWidget' => true,
                                            'widgetClass' => '',
                                            'displayFormat' => 'php:d-m-Y',
                                            'saveFormat' => 'php:Y-m-d',
                                            'saveTimezone' => 'UTC',
                                            'displayTimezone' => 'Europe/Amsterdam',
                                            'language' => 'it',
                                            'name' => 'dp_3',
                                            'convertFormat'=>true,
                                        //'type' => DatePicker::TYPE_COMPONENT_APPEND,
                                        //'value'=>date("d-m-yyyy"),
                                        //'dateFormat' => 'php:d-m-Y',
                                        'widgetOptions' => [
                                            'pluginOptions' => [
                                                'autoclose' => true,
                                                'format' => 'php:d-m-Y',
                                                'todayHighlight'=>true,
                                                ],

                                ]])
                                ?>
                        </div>
                    <div class="col-md-2"> 
                                <?= $form->field($model, 'data_fine')->widget(DateControl::classname(), [
                                            'type' => 'date',
                                            'ajaxConversion' => false,
                                            'autoWidget' => true,
                                            'widgetClass' => '',
                                            'displayFormat' => 'php:d-m-Y',
                                            'saveFormat' => 'php:Y-m-d',
                                            'saveTimezone' => 'UTC',
                                            'displayTimezone' => 'Europe/Amsterdam',
                                            'language' => 'it',
                                            'name' => 'dp_3',
                                            'convertFormat'=>true,
                                        //'type' => DatePicker::TYPE_COMPONENT_APPEND,
                                        //'value'=>date("d-m-yyyy"),
                                        //'dateFormat' => 'php:d-m-Y',
                                        'widgetOptions' => [
                                            'pluginOptions' => [
                                                'autoclose' => true,
                                                'format' => 'php:d-m-Y',
                                                'todayHighlight'=>true,
                                                ],

                                ]])
                                ?>
                        </div>
                        <div class="col-sm-1"  style="margin-top:32px;"  >         
                                <?= Html::submitButton($model->isNewRecord ? 'Aggiungi' : 'Aggiorna', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                        </div>  
                        <?php ActiveForm::end() ?>
                            
                             
                </div>            
<!--                        </div>-->
            <div class="row">    
                            <div class="col-md-12">
                            <?php
                            //Pjax::begin(['id' => 'alltec']);
                            echo yii\grid\GridView::widget([
                            //echo DynaGrid::widget([
                                'dataProvider'=>$dpv,
                                'columns'=>[
                                [
                                'class'=>'yii\grid\ActionColumn',
//                                'order'=>DynaGrid::ORDER_FIX_LEFT,
                                    'contentOptions'=>array('style'=>'halign:center!important; width: 2%;'),        
                                    'template' => '{delete}',
                                    'header'=>'',
                                    //'width'=>'2%',
                                    'buttons'=>['delete' => function ($url,$model) use ($idpratica) {
                                               //$myurl = $url . '&idpratica='.$id;
                                               $url = Url::toRoute(['sismica/cancellatecnico','id'=>$model->idsoggetti_sismica_tecnici,'idpratica'=>$idpratica]);
                                              return Html::a('<span class="fas fa-trash"></span>', $url,
                                              [  
                                                 'title' => 'Elimina',
                                                 'data-confirm' => "Sei sicuro di volere eliminare questo soggetto?",
                                                 //'data-method' => 'post',
                                                 //'data-pjax' => 0
                                              ]);
                                     }]
                                ],
                                ['class'=>'yii\grid\SerialColumn', 
                                 'contentOptions'=>array('style'=>'halign:center!important; width: 2%;'),        
                                //'order'=>DynaGrid::ORDER_FIX_LEFT],
                                ],
                                ['attribute'=>'tecnico', //committente.nomeCompleto',
                                    'value'=>'tecnico.nometecnico',
                                    //'width'=>'10%',
                                    'label'=>'Tecnico'
                                ],     
                                ['attribute'=>'ruolo', //committente.nomeCompleto',
                                    'value'=>'ruolo.ruolo',
                                    //'width'=>'10%',
                                    'label'=>'Incarico'
                                ],                  
                                ['attribute'=>'data_inizio',
                                 'format' =>  ['date', 'php:d-m-Y'],
                                 'contentOptions'=>array('style'=>'halign:center!important; width: 8%;'),        
                                ], 
                                ['attribute'=>'data_fine',
                                 'format' =>  ['date', 'php:d-m-Y'],
                                 'contentOptions'=>array('style'=>'halign:center!important; width: 8%;'),        
                                ],              
                                ],
                            ////////////////////////////////
                            ///// OPZIONI DELLA TABELLA ////
                            ///////////////////////////////
//                            'storage'=>'db', //DynaGrid::TYPE_COOKIE,
//                            'dbUpdateNameOnly'=>true,
//                            'theme'=>'panel-info',
//                            //'emptyCell'=>' ',
//                            'showPersonalize'=>false,
                            //'pjax'=>true,
//                            'gridOptions'=>[
//                                'dataProvider'=>$dpv,
//                                //'filterModel'=>$Search,
//                                'showPageSummary'=>false,
//
//                                'panel'=>[
//                                        //'heading'=>'<h3 class="panel-title"><b>ELENCO ALLEGATI TECNICI<b></h3>',
//                                        //'before' =>  '<div style="padding-top: 7px;"><em>Elenco Documenti</em></div>',
//                                        'before' =>false,
//                                        'after' => false
//                                        ],
//                                'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
//                                //'headerRowOptions' => ['class' => 'kartik-sheet-style'],
//                                //'filterRowOptions' => ['class' => 'kartik-sheet-style'],
//                                'responsive'=>true,
//                                //'hover'=>true,
//                                'bordered' => true,
//                                'striped' => true,
//                                'condensed' => true,
//                                'persistResize' => false,
//                                //'submitButtonOptions'=>true,
//                                'toggleDataContainer' => ['class' => 'btn-group-sm'],
//                                'exportContainer' => ['class' => 'btn-group-sm'],
//                                'toolbar' =>  [
//                                    ['content'=>''
//                                    ],
//                                ]
//                                ], // gridOption
//                            'options'=>['id'=>'dynagrid-sg'] // a unique identifier is important
                        ]);
                            //Pjax::end();
                            ?> 
                            </div>  <!-- col-sm-12 -->  
            </div>    <!-- row -->
                   
                    
                
        </div><!-- card body -->

        </div> <!-- card card-secondary -->
     <!-- md-12 -->    
        <!-- FOOTER -->    
    <!--<div class="card-footer">
        <button type="submit" class="btn btn-primary">Componi</button>
        </div> -->
<!--    </div>  card secondary -->
<!--</div>  col-md-6 -->

</div><!-- row -->
</div><!-- conteiner-fluid -->




