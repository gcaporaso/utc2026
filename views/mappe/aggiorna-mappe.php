<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

/** @var yii\web\View $this */
/** @var app\models\UploadDatiCatastaliForm $model */

$this->title = 'Aggiorna Mappe Catastali Vettoriali GeoJson';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-dati-catastali">

     <h1><?= Html::encode($this->title) ?></h1>

    <p>Carica i file aggiornati per le mappe vettoriali in formato GeoJson, specificando la data di riferimento:</p>
    <p>Scarica i dati da Sister - Agenzia delle Entrate con la seguente procedura:</p>
    <p>Vai nalla sezione Servizi per Comuni/Enti -> Estrazione Dati Catastali</p>
    <p>Clicca su Nuova Prenotazione Terreni -> Cartografia</p>
    <p>Specifica: Tipo Esportazione:Tutti i dati, Formato Componente Vettoriale: GeoJSON, Sistema di riferimento: ETRF2000, Quadro d'unione: Senza</p>
    <p>Carica qui il file zip scaricato cliccando sul link Download Risposta</p>


    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

        <h4>Mappe Vettoriali</h4>
        <div class="row">
            <div class="col-sm-2" style="margin-top: 24px">
                <?= $form->field($model, 'dataMappe')->input('date')->label('Data di Riferimento Mappe') ?>
            </div>
            <div class="col-sm">
                <?= '<br><label>Seleziona il zip delle mappe GeoJson da caricare</label>';?>
                <?php echo FileInput::widget([
                    'model' => $model,
                    'attribute' => 'fileMappe',
                    // 'options' => ['multiple' => false],
                    'pluginOptions' => [
                        'showPreview' => false,
                        'showCancel'=> false,
                        'showRemove' => false,
                        'showUpload' => false,
                        'browseLabel' => 'Seleziona zip',
                        'initialCaption' => 'Seleziona file ...'
                    ]
                ]); 
                ?>
            </div>
            <div class="col-sm-2"></div>
        </div>
        <div class="form-group">
            <?= Html::submitButton('Aggiorna Mappe', ['class' => 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>
</div>
