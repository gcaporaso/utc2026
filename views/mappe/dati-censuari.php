<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

/** @var yii\web\View $this */
/** @var app\models\UploadDatiCatastaliForm $model */
/** @var app\models\DatiCensuari[] $forniture */

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
<?php if (!empty($forniture)): ?>
<div class="card shadow-sm mt-4">
    <div class="card-header">
        <i class="fas fa-database me-2"></i>Forniture catastali caricate
    </div>
    <div class="card-body p-0">
        <table class="table table-sm table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th class="text-center" style="width:60px">#</th>
                    <th class="text-center">Data di riferimento</th>
                    <th>File database</th>
                    <th class="text-center" style="width:80px">Dim.</th>
                    <th class="text-center" style="width:90px">Stato</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($forniture as $i => $f):
                    $fullPath = \Yii::getAlias('@webroot') . '/' . $f->file_path_database;
                    $exists   = file_exists($fullPath);
                    $size     = $exists ? filesize($fullPath) : 0;
                    $sizeFmt  = $size > 1048576
                        ? round($size / 1048576, 1) . ' MB'
                        : ($size > 1024 ? round($size / 1024) . ' KB' : $size . ' B');
                    $attivo   = $i === 0; // il più recente è quello attivo
                    $data     = \Yii::$app->formatter->asDate($f->dataCensuari, 'php:d/m/Y');
                ?>
                <tr class="<?= $attivo ? 'table-success' : '' ?>">
                    <td class="text-center text-muted small"><?= $f->iddati_censuari ?></td>
                    <td class="text-center fw-semibold"><?= Html::encode($data) ?></td>
                    <td class="small"><code><?= Html::encode(basename($f->file_path_database)) ?></code></td>
                    <td class="text-center small"><?= $exists ? $sizeFmt : '<span class="text-danger">mancante</span>' ?></td>
                    <td class="text-center">
                        <?php if ($attivo): ?>
                            <span class="badge bg-success">Attivo</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Storico</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>