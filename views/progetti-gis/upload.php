<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Importa layer — ' . $progetto->nome;
?>
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">Importa layer GIS</h1>
        <small class="text-muted">Progetto: <?= Html::encode($progetto->nome) ?></small>
    </div>
</div>
<div class="content">
<div class="container-fluid">
<div class="card" style="max-width:640px;">
    <div class="card-body">

        <div class="alert alert-info">
            <i class="fas fa-info-circle mr-1"></i>
            Formati accettati: <b>GeoJSON</b> (.geojson, .json) oppure <b>Shapefile</b> (.zip contenente almeno .shp + .dbf).
            Le geometrie vengono riproiettate in <b>WGS84 (EPSG:4326)</b>.
        </div>

        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

        <div class="form-group">
            <label>Nome layer</label>
            <input type="text" name="nome_layer" class="form-control" placeholder="es. Rilievo planimetrico 2024" required />
        </div>

        <div class="form-group">
            <label>File GIS</label>
            <input type="file" name="gis_file" class="form-control-file" accept=".geojson,.json,.zip" required />
        </div>

        <div class="form-group">
            <label>Sistema di riferimento del file <small class="text-muted">(rilevato automaticamente dal .prj per gli shapefile)</small></label>
            <select name="srid" id="srid-select" class="form-control" onchange="document.getElementById('srid-manual').value='';">
                <?php foreach ($crsOptions as $epsg => $label): ?>
                    <option value="<?= $epsg ?>" <?= $epsg === 4326 ? 'selected' : '' ?>><?= Html::encode($label) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Oppure inserisci codice EPSG manualmente <small class="text-muted">(sovrascrive la selezione sopra)</small></label>
            <input type="number" name="srid_manual" id="srid-manual" class="form-control" placeholder="es. 7794"
                   min="1" style="max-width:200px;"
                   onchange="if(this.value) document.getElementById('srid-select').value='';" />
        </div>

        <div class="form-group">
            <?= Html::submitButton('<i class="fas fa-upload mr-1"></i>Importa', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Annulla', ['view', 'id' => $progetto->id], ['class' => 'btn btn-secondary ml-2']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
</div>
</div>
