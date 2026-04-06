<!--<script src="plugins/jquery/jquery.min.js"></script>-->
<!--<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>-->
<!--<link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">-->
<!--<link rel="stylesheet" href="tempusdominus-bootstrap-4.min.css">-->
<script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" crossorigin="anonymous">    
    
<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\Tipomodulistica;
use kartik\select2\Select2;
//use kartik\date\DatePicker;
use kartik\datecontrol\DateControl;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\Edilizia */
/* @var $form yii\widgets\ActiveForm */
\hail812\adminlte3\assets\PluginAsset::register($this)->add('bs-custom-file-input');
date_default_timezone_set('UTC');
$this->registerJs("
$(document).ready(function () {
  bsCustomFileInput.init()
  $('#customFile').fileinput();
})
", yii\web\View::POS_READY);
//$this->registerJs("
//    $('.datepicker').datepicker({
//    format: {
//        /*
//         * Say our UI should display a week ahead,
//         * but textbox should store the actual date.
//         * This is useful if we need UI to select local dates,
//         * but store in UTC
//         */
//        toDisplay: function (date, format, language) {
//            var d = new Date(date);
//            d.setDate(d.getDate() - 7);
//            return d.toISOString();
//        },
//        toValue: function (date, format, language) {
//            var d = new Date(date);
//            d.setDate(d.getDate() + 7);
//            return new Date(d);
//        }
//    }
//});
//", yii\web\View::POS_READY);
?>
    <div class="row">
        <div class="card card-secondary col-sm-12"  style="margin-top: 10px" >
             <div class="card-header" >
                <h3 class="card-title"><?= $model->isNewRecord ? 'Nuovo Modello' : 'Modifica Modello' ?></h3>
            </div>

        <div class="card-body">           
        <div class="row">
        <div class="form-group col-sm-12">

    <?php $form = ActiveForm::begin(); ?>
<div class="row">
        <div class="col-sm-2">
        <?php
        $listacategorie =ArrayHelper::map(app\models\Categorie::find()->asArray()->all(), 'idcategorie', 'descrizione');
            echo $form->field($model, 'categoria')->widget(Select2::classname(), [
                'name' => 'SelCategoria',
                'size' => Select2::MEDIUM,
                'data' => $listacategorie,
                'options' => ['id' => 'lstcat', 'multiple' => false, 'value' => 1,
                    'placeholder' => 'Seleziona categoria ...',
                ],
                'pluginOptions' => ['allowClear' => false],
            ])->label('Categoria Modello');
            ?>
        </div>
        <div class="col-sm-1">
        </div>
    <div class="col-sm-1">
            <?= $form->field($model, 'report')->checkbox()->label('') ?>
        </div>
</div>
<div class="row">
        <div class="col-sm-3" >
        <?= $form->field($model, 'descrizione')->textInput() ?>    
        </div>
        <div class="col-sm-2" >
        <?= $form->field($model, 'codice')->textInput() ?>    
        </div>
</div>
<div class="row">
        <div class="col-sm-2" >
        <?= $form->field($model, 'numerorevisione')->textInput()->label('Numero Revisione') ?>    
        </div>
        <div class="col-sm-2">
        <?php //  $form->field($model, 'datarevisione')->widget(DatePicker::classname(), [
//                'language' => 'it',
//                'name' => 'dp_3',
//                'layout'=>'{input}{picker}',
//                'convertFormat'=>true,
//                'type' => DatePicker::TYPE_COMPONENT_APPEND,
//                'value'=>date("dd-mm-yyyy"),
//                'pluginOptions' => [
//                    'autoclose' => true,
//                    'format' => 'dd-mm-yyyy',
//                    'todayHighlight'=>true,
//        ]])->label('Data Revisione')
        ?>
            
    <?= $form->field($model, 'datarevisione')->widget(DateControl::classname(), [
    'type' => 'date',
    'ajaxConversion' => true,
    'autoWidget' => true,
    'widgetClass' => '',
    //'layout'=>'{input}{picker}',
    'displayFormat' => 'php:d-m-Y',
    'saveFormat' => 'php:Y-m-d',
    'saveTimezone' => 'Europe/Amsterdam',
    'displayTimezone' => 'Europe/Amsterdam',
//    'saveOptions' => [
//        'label' => 'Input saved as: ',
//        'type' => 'text',
//        'readonly' => true,
//        'class' => 'hint-input text-muted'
//    ],
    'widgetOptions' => [
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'php:d-m-Y'
        ]
    ],
    'language' => 'it'
])->label('Data Revisione');
  ?>          
            
<!--            <div class="input-group date" id="datetimepicker4" data-target-input="nearest">
                    <input type="text" class="form-control datetimepicker-input" 
                            id="modulistica-datarevisione"  name="Modulistica[datarevisione]"
                            value=<?php // $model->datarevisione ?>
                           data-target="#datetimepicker4"/>
                    <div class="input-group-append" data-target="#datetimepicker4" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
        </div>
        <script type="text/javascript">
                $(function () {
                    $('#datetimepicker4').datetimepicker({
                        format: 'L'
                    });
                });
        </script>-->
        </div>
</div>
  

<div class="row">
    <div class="col-sm-6" >
        <div class="custom-file">
        <?php echo $form->field($model, 'nomefile')->widget(FileInput::classname(), [
                'name' => 'attachment_50',
                'pluginOptions' => [
                    'showPreview' => false,
                    'browseLabel' => '',
                    'removeLabel' => '',
                    'cancelLabel'=>'',
//                    'allowedFileExtensions' => ['docx'],
            //        'browseClass' => 'btn btn-primary btn-block',
            //        'browseIcon' => '<i class="fas fa-camera"></i> ',
            //        'browseLabel' =>  'Select Photo',
                    'showCaption' => true,
                    'showRemove' => false,
                    'showUpload' => false,
                    'showCancel'=>false, 
                ],
                'options' => ['accept' => '.docx']
])->label('') ?>
        
        
<!--            <input type="file" class="custom-file-input" id="customFile">
            <label class="custom-file-label" for="customFile">Seleziona Modello .docx</label>-->
        </div>
        
        
    </div>
</div> 
<!--<div class="row">
    <div class="col-sm-6" >
        <?php // echo $form->field($model, 'path')->textInput() ?>
    </div>
</div> -->
 </div><!--row-->
 </div><!--card body-->
 </div><!--card-->
 
<!-- FOOTER -->    
<div class="card-footer">
    <?= Html::submitButton('Salva', ['name' => 'salva','class' => 'btn btn-success']) ?>
    <?= Html::a('Cancel', ['modulistica/index'], ['class'=>'btn btn-primary', 'style'=>'margin-left:20px']) ?>
    <?= Html::a('Elenco Variabili',['modulistica/download','filename'=> '/var/www/ufficiotecnico/web/modulistica/elenco-variabili-rev01.pdf'],['style'=>'margin-left:20px']);  ?>
</div>

    

</div><!--card-->
    <?php ActiveForm::end(); ?>

</div><!--row-->
            
   
 
 
