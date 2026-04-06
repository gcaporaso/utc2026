<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="plugins/select2/js/select2.full.min.js"></script>
<link rel="stylesheet" href="plugins/sweetalert2/sweetalert2.min.css">
<link rel="stylesheet" href="plugins/select2/css/select2.min.css">
<?php
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\dynagrid\DynaGrid;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\file\FileInput;
use yii\helpers\Url;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
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
                <h3 class="card-title">Riepilogo Dati Pratica</h3>

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
                    <p><span>Protocollo:</span></p><div class="col-sm-1" style="color:blue;"><?= $modelp->NumeroProtocollo ?></div>
                    <p><span>Data:</span></p><div class="col-sm-1" style="color:blue"><?= Yii::$app->formatter->asDate($modelp->DataProtocollo, 'php:d-m-Y') ?></div>
                    <p><span>Sanatoria:</span></p><div class="col-sm-1" style="color:blue"><?= ($modelp->Sanatoria==1 ? 'Si' : 'No') ?></div>
                    <p><span>Stato:</span></p><div class="col-sm-2" style="color:blue"><?= $modelp->statoPratica->descrizione ?></div>
                    <p><span>Foglio:</span></p><div class="col-sm-1" style="color:blue"><?= $modelp->CatastoFoglio ?></div>
                    <p><span>Particella:</span></p><div class="col-sm-1" style="color:blue"><?= $modelp->CatastoParticella ?></div>
                    <p><span>Sub:</span></p><div class="col-sm-1" style="color:blue"><?= $modelp->CatastoSub ?></div>
                </div>
                <div class="row"> 
                    <p><span>Indirizzo:</span></p><div class="col-sm-2" style="color:blue"><?= $modelp->IndirizzoImmobile ?></div>
                    <p><span>Intervento:</span></p><div class="col-sm-9" style="color:blue" ><?= $modelp->DescrizioneIntervento ?></div>
                    
                </div> 
                <div class="row">
                    <p><span>Richiedente:</span></p><div class="col-sm-2" style="color:blue"><?= $modelp->richiedente->nomeCompleto ?></div>
                    <p><span>Numero Autorizzazione:</span></p><div class="col-sm-1" style="color:blue"><?= $modelp->NumeroTitolo ?></div>
                    <p><span>Data Autorizzazione:</span></p><div class='col-sm-1' style="color:blue"><?= Yii::$app->formatter->asDate($modelp->DataTitolo, 'php:d-m-Y') ?></div>
                </div> 
            </div>
        </div>
    </div>
<!--</div>-->
<div class="row">

<!--<div class="col-md-12">-->
<!--        <div class="card card-secondary"  style="margin-top: 5px" >
             INTESTAZIONE 
            <div class="card-header" >
                <h3 class="card-title">Gestione Pratica:</h3>

                 Button per ridimensionare e rimuovere CARD 
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>-->
        
        <!-- CONTENUTO -->   
        
<!--        <div class="card-body">-->
                
<!--        </div>        -->
        
        
<!--            <div class="row" style="margin: 5px 5px 2px 5px">-->
        
                <div class="col-12">
                <div class="card card-primary card-tabs">
                    <div class="card-header p-0 pt-1">
                        <ul class="nav nav-tabs" id="custom-tabs-tab" role="tablist">
                            <li class="nav-item">
                            <a class="nav-link active" id="custom-tabs-tecnici-tab" data-toggle="pill" href="#custom-tabs-allegati" role="tab" aria-controls="custom-tabs-allegati" aria-selected="true">Allegati</a>
                            </li>
<!--                            <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-amministrativi-tab" data-toggle="pill" href="#custom-tabs-amministrativi" role="tab" aria-controls="custom-tabs-amministrativi" aria-selected="false">Allegati Amministrativi</a>
                            </li>-->
                             <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-amministrativi-tab" data-toggle="pill" href="#custom-tabs-oneri" role="tab" aria-controls="custom-tabs-oneri" aria-selected="false">Oneri Concessori</a>
                            </li>
                             <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-amministrativi-tab" data-toggle="pill" href="#custom-tabs-documenti" role="tab" aria-controls="custom-tabs-documenti" aria-selected="false">Composizione Documenti</a>
                            </li>
                        </ul>
                    </div>

        <div class="card-body">
            <div class="tab-content" id="custom-tabs-one-tabContent">
                    <!-- ******************** -->
                    <!-- *** TAB ALLEGATI *** -->
                    <!-- ******************** -->
                    <div class="tab-pane fade show active" id="custom-tabs-allegati" role="tabpanel" aria-labelledby="custom-tabs-allegati">
<!--                        <div class="row">-->
                          
                            <?php $form = ActiveForm::begin([
                    'action'=>['edilizia/gestione', 'idpratica'=>$idpratica, 'azione'=>2],
                    'id' => 'allegati-tecnici-form',
                    'options' => ['enctype' => 'multipart/form-data'],
                ]) ?>
                <div class="row">
                    <div class="col-sm-4"  >    
                        <?= $form->field($modela, 'descrizione')->label(false)->textInput(['maxlength' => true])->label('Descrizione') ?>
                    </div>
                    <div class="col-sm-2">    
                        <?= $form->field($modela, 'tipologia')->dropDownList([0 => 'Elaborato Tecnico/Grafico', 1 => 'Atto Amministrativo'])->label('tipologia allegato'); ?>
                    </div>
                    <div class="custom-file col-sm-5" style="margin-top: 32px" > 
                            <?php echo $form->field($modela, 'nomefile')->widget(FileInput::classname(), [
                            'name' => 'attachment_51',
                            'pluginOptions' => [
                            'showPreview' => false,
                            'browseLabel' => '',
                            'removeLabel' => '',
                            'cancelLabel'=>'',
                    //        'browseClass' => 'btn btn-primary btn-block',
                    //        'browseIcon' => '<i class="fas fa-camera"></i> ',
                    //        'browseLabel' =>  'Select Photo',
                            'showCaption' => true,
                            'showRemove' => false,
                            'showUpload' => false,
                            'showCancel'=>false, 
                            ],
                            'options' => ['accept' => 'gdoc/*']
                            ])->label(false) ?>
                    </div>
                    <div class="col-sm-1"  style="margin-top:35px;align-content: flex-end"  >         
                            <?= Html::submitButton($modela->isNewRecord ? 'Aggiungi' : 'Aggiorna', ['class' => $modela->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                    </div>  
                </div>
                <?php ActiveForm::end() ?>
                            
<!--                        </div>-->
                        <div class="row">    
                            <div class="col-sm-12">
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
                                              return Html::a('<span class="fas fa-trash"></span>', $url,
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
                                        //'heading'=>'<h3 class="panel-title"><b>ELENCO ALLEGATI TECNICI<b></h3>',
                                        //'before' =>  '<div style="padding-top: 7px;"><em>Elenco Documenti</em></div>',
                                        'before' =>false,
                                        'after' => false
                                        ],
                                'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
                                //'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                                //'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                                'responsive'=>true,
                                //'hover'=>true,
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
                            'options'=>['id'=>'dynagrid-f'] // a unique identifier is important
                        ]);
                            //Pjax::end();
                            ?> 
                            </div>    
                        </div>    
                    </div>
                    
                     <!-- ******************** -->
                    <!-- *** TAB ALLEGATI *** -->
                    <!-- ******************** -->
                    <div class="tab-pane fade show active" id="custom-tabs-oneri" role="tabpanel" aria-labelledby="custom-tabs-oneri">

                        <?php include('oneri.php'); ?>
                        
                    </div>
                    <!-- ********************************** -->
                    <!-- *** TAB COMPOSIZIONE DOCUMENTI *** -->
                    <!-- ********************************** -->
                    
                    <div class="tab-pane fade" id="custom-tabs-documenti" role="tabpanel" aria-labelledby="custom-tabs-documenti">
                    
                        <!-- CONTENUTO -->   
                       <form>
                        <div class="card-body">

                        <div class="form-group">
                         <label>Modelli</label>
                         <select class="form-control select2" style="width: 100%;">
                            <?php
                            $docs=ArrayHelper::map(app\models\Modulistica::find()
                                    ->where(['categoria'=>1,'report'=>0])
                //                    ->where(['report'=>0])
                                    ->asArray()
                                    ->all(),
                                    'idmodulistica','descrizione');
                            foreach($docs as $key => $value) {
                            echo "<option value=' $key '> $value </option>";
                            }
                            ?>
                          </select>
                         </div>
                         </div><!-- card-body -->
                        </form>

                        <!-- FOOTER -->    
                        <div class="row">
                        <button type="submit" class="btn btn-primary">Componi</button>
                        </div> 
                    
                    
                </div>
                    
                    
                    
                    
                </div><!-- tab content -->
            </div><!-- card body -->

        </div> <!-- card tabs -->
        </div> <!-- col -->
<!--        </div>  card row -->
<!--        </div>  card body -->
    
        
        <!-- FOOTER -->    
    <!--<div class="card-footer">
        <button type="submit" class="btn btn-primary">Componi</button>
        </div> -->
<!--    </div>  card secondary -->
<!--</div>  col-md-6 -->

</div><!-- row -->
</div><!-- conteiner-fluid -->


