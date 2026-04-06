<!--<script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>-->
     
    <!-- the jQuery Library -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script> -->
     
  
     
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
//\hail812\adminlte3\assets\PluginAsset::register($this)->add(['sweetalert2','bs-custom-file-input']);
//$this->registerJsFile('js/jquery-3.5.0.min.js');
//$this->registerJsFile('js/fileinput.min.js');
//$this->registerCssFile('css/pinofileinput.css');
//$this->registerJsFile('js/bs-custom-file-input.min.js');
$this->registerJs("
$(document).ready(function () {
  bsCustomFileInput.init()
//  alert('prova');
})
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
    <!-- toolbar -->
    <div class="row">

        <div class="card card-secondary col-md-12"  style="margin-top: 5px" >
            <!-- INTESTAZIONE -->
            <div class="card-header" >
                <h3 class="card-title">Composizione Certificato di destinazione urbanistica</h3>
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
                            $listamodelli = ArrayHelper::map(Modulistica::find()->where(['categoria'=>4])->all(), 'idmodulistica', 'descrizione');
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


