<!--<script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>-->
     
    <!-- the jQuery Library -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
     
  
     
<?php
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\dynagrid\DynaGrid;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\models\Modulistica;
//use kartik\file\FileInput;
//use kartik\widgets\FileInput;
use yii\helpers\Url;
use yii\web\View;
//use hail812\adminlte\widgets\Alert;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
\hail812\adminlte3\assets\PluginAsset::register($this)->add(['sweetalert2','select2','bs-custom-file-input']);
//$this->registerJsFile('js/jquery-3.5.0.min.js');
//$this->registerJsFile('js/fileinput.min.js');
//$this->registerCssFile('css/pinofileinput.css');
$this->registerJs("
//$(document).ready(function () {
  bsCustomFileInput.init()
//  alert('prova');
//})
", yii\web\View::POS_READY);


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
                <h3 class="card-title">Riepilogo Dati Pratica SISMICA</h3>

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
                <?= Html::a('Allegati', ['sismica/allegati','idpratica'=>$idpratica], ['class'=>'btn btn-success','style'=>'margin-left:15px']) ?>
                <?= Html::a('Soggetti Coinvolti', ['sismica/soggetti','idpratica'=>$idpratica], ['class'=>'btn btn-success','style'=>'margin-left:10px']) ?>
                <?= Html::a('Tecnici Coinvolti', ['sismica/tecnici','idpratica'=>$idpratica], ['class'=>'btn btn-success','style'=>'margin-left:10px']) ?>
                
            </div>
    </div>
</div>

    <div class="row">

        <div class="card card-secondary col-md-12"  style="margin-top: 5px" >
            <!-- INTESTAZIONE -->
            <div class="card-header" >
                <h3 class="card-title">Composizione Documenti Pratica</h3>
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
               
                
                    <!-- **************** -->
                    <!-- *** ALLEGATI *** -->
                    <!-- **************** -->
                
                         
                    <div class="form">
                        <?php $form = ActiveForm::begin(); ?>
                        <div class="row">
                            <div class="col-md-8">
                            <?php
                            $listamodelli = ArrayHelper::map(Modulistica::find()->where(['categoria'=>3,'report'=>0])->all(), 'idmodulistica', 'descrizione');
                            echo $form->field($model, 'modello_id')->widget(Select2::classname(), [
                                'name' => 'SelModello',
                                'size' => Select2::MEDIUM,
                                'data' => $listamodelli,
                                'options' => ['id' => 'lstmodel', 'multiple' => false, 'value' => 1,
                                    'placeholder' => 'Seleziona il modello da usare ...',
                                ],
                                'pluginOptions' => ['allowClear' => false],
                            ])->label('Selezione Modello per composizione Documento');
//                            $form->field($model, 'modello_id')->dropDownList('modello_id', '', ArrayHelper::map(Modulistica::find()->where(['categoria'=>1])->asArray()->all(), 'idmodulistica', 'descrizione')); ?>
                            </div>    
                            <div class="col-md-2" style="margin-top:32px;">
                                <div class="form-group">
                                    <?= Html::submitButton('COMPONI', ['class' => 'btn btn-primary']); ?>
                                </div>
                            </div> 
                        </div>    
                        <?php ActiveForm::end(); ?>
                        </div>
                            
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


