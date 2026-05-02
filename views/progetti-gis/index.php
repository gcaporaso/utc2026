<?php
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Progetti GIS';
?>
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0"><?= Html::encode($this->title) ?></h1>
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

<div class="card">
    <div class="card-header">
        <?= Html::a('<i class="fas fa-plus mr-1"></i>Nuovo Progetto', ['create'], ['class' => 'btn btn-primary btn-sm']) ?>
    </div>
    <div class="card-body p-0">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => ['class' => 'table table-hover table-sm mb-0'],
            'columns' => [
                'id',
                'nome',
                [
                    'attribute' => 'tipo',
                    'value'     => fn($m) => $m->getTipoLabel(),
                ],
                [
                    'attribute' => 'created_at',
                    'format'    => 'datetime',
                ],
                [
                    'class'    => 'yii\grid\ActionColumn',
                    'template' => '{view} {mappa} {delete}',
                    'buttons'  => [
                        'view'   => fn($u, $m) => Html::a('<i class="fas fa-list"></i>', ['view',  'id' => $m->id], ['class' => 'btn btn-xs btn-info',    'title' => 'Dettaglio']),
                        'mappa'  => fn($u, $m) => Html::a('<i class="fas fa-map"></i>',  ['/mappe/index'], ['class' => 'btn btn-xs btn-success', 'title' => 'Visualizza in Mappa']),
                        'delete' => fn($u, $m) => Html::a('<i class="fas fa-trash"></i>', ['delete','id' => $m->id], [
                            'class' => 'btn btn-xs btn-danger',
                            'data'  => ['confirm' => 'Eliminare il progetto e tutti i suoi layer?', 'method' => 'post'],
                        ]),
                    ],
                ],
            ],
        ]) ?>
    </div>
</div>

</div>
</div>
