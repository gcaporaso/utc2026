<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

/** @var yii\web\View $this */
/** @var app\models\UploadDatiCatastaliForm $model */

$this->title = 'Aggiorna Dati Censuari';
$this->params['breadcrumbs'][] = $this->title;

//\hail812\adminlte3\assets\PluginAsset::register($this)->add(['sweetalert2','select2','bs-custom-file-input']);
?>
<div class="site-dati-catastali">

     <h1><?= Html::encode($this->title) ?></h1>

    <p>Carica il database dei dati censuari aggiornati, specificando la data di riferimento:</p>
    <p>(Dal Software Visualizzazione Forniture Catastali - Visualizzazione Forniture Catastali -> database -> Copia di backup del database)</p>

    <?php $form = ActiveForm::begin([
        'action'=>['mappe/dati-censuari'],
        'id' => 'dati-censuari-form',
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

        <h4>Dati Censuari</h4>
        <div class="row">
            <div class="col-sm-2" style="margin-top: 24px">
                <?= $form->field($model, 'dataCensuari')->input('date')->label('Data di Riferimento Dati Censuari') ?>
            </div>
            
            <div class="col-sm" >
                <?= '<br><label>Seleziona il database da caricare</label>';?>
                <?php echo FileInput::widget([
                    'model' => $model,
                    'attribute' => 'fileCensuari',
                    // 'options' => ['multiple' => false],
                    'pluginOptions' => [
                         'showPreview' => false,
                    //     'showCaption' => true,
                    //     'showCancel' => false,
                    //     'browseClass' => 'btn btn-success',
                        'showCancel'=> false,
                         'showRemove' => false,
                         'showUpload' => false,
                         'browseLabel' => 'Seleziona db',
                         'initialCaption' => 'Seleziona file ...'
                    ]
                ]); 
                ?>
            </div> 
            
            <div class="col-sm-2" >
                
            </div>    
        </div>
        <div class="form-group" style="margin-top: 32px">
            <?= Html::submitButton('Aggiorna Dati', ['class' => 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>
</div>
<?php
// $js = <<<JS
// $(document).ready(function () {
//     bsCustomFileInput.init();
// });
// JS;
// $this->registerJs($js);
?>