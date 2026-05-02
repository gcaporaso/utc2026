<?php
use yii\helpers\Html;

$this->title = $progetto->nome;
?>
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0"><?= Html::encode($progetto->nome) ?>
            <small class="text-muted ml-2" style="font-size:0.6em;"><?= $progetto->getTipoLabel() ?></small>
        </h1>
    </div>
</div>
<div class="content">
<div class="container-fluid">

<?php foreach (Yii::$app->session->getAllFlashes() as $type => $msg): ?>
    <div class="alert alert-<?= $type === 'error' ? 'danger' : 'success' ?> alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?= Html::encode($msg) ?>
    </div>
<?php endforeach; ?>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><b>Informazioni</b></div>
            <div class="card-body">
                <?php if ($progetto->descrizione): ?>
                    <p><?= nl2br(Html::encode($progetto->descrizione)) ?></p>
                <?php endif; ?>
                <small class="text-muted">Creato il: <?= Yii::$app->formatter->asDatetime($progetto->created_at) ?></small>
            </div>
            <div class="card-footer">
                <?= Html::a('<i class="fas fa-map mr-1"></i>Visualizza in Mappa', ['/mappe/index'], ['class' => 'btn btn-success btn-sm']) ?>
                <?= Html::a('<i class="fas fa-list mr-1"></i>Elenco', ['index'], ['class' => 'btn btn-secondary btn-sm ml-1']) ?>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <b>Layer</b>
                <?= Html::a('<i class="fas fa-upload mr-1"></i>Importa layer', ['upload', 'id' => $progetto->id], ['class' => 'btn btn-primary btn-sm float-right']) ?>
            </div>
            <div class="card-body p-0">
                <?php if (empty($progetto->layers)): ?>
                    <p class="p-3 text-muted">Nessun layer. Importa un file GeoJSON o Shapefile.</p>
                <?php else: ?>
                <table class="table table-sm table-hover mb-0">
                    <thead><tr><th>Nome</th><th>Tipo geometria</th><th>SRID orig.</th><th>Feature</th><th></th></tr></thead>
                    <tbody>
                    <?php foreach ($progetto->layers as $layer): ?>
                    <tr>
                        <td><?= Html::encode($layer->nome) ?></td>
                        <td><code><?= Html::encode($layer->tipo_geometria) ?></code></td>
                        <td><?= $layer->srid ?></td>
                        <td><?= $layer->getFeatureCount() ?></td>
                        <td>
                            <?= Html::a('<i class="fas fa-trash"></i>', ['delete-layer', 'id' => $layer->id], [
                                'class' => 'btn btn-xs btn-danger',
                                'data'  => ['confirm' => 'Eliminare il layer e tutte le sue feature?', 'method' => 'post'],
                            ]) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

</div>
</div>
