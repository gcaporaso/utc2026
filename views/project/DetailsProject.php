<?php
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Project $model */

$this->title = $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// Per ottenere l'ID utente loggato in Yii2:
$userId = Yii::$app->user->id;
?>

<head>
    <script src="https://kit.fontawesome.com/056ba6c557.js" crossorigin="anonymous"></script>
</head>

<h1><?= Html::encode($model->Name) ?> - Dettagli</h1>

<hr />

<dl class="row">
    <dt class="col-sm-2"><?= Html::encode('Titolo del Progetto') ?></dt>
    <dd class="col-sm-10"><?= Html::encode($model->Name) ?></dd>

    <dt class="col-sm-2"><?= Html::encode('Unità di Misura') ?></dt>
    <dd class="col-sm-10"><?= Html::encode($model->Units) ?></dd>

    <dt class="col-sm-2"><?= Html::encode('Stato del Progetto') ?></dt>
    <dd class="col-sm-10"><?= Html::encode($model->Progress) ?></dd>

    <dt class="col-sm-2"><?= Html::encode('Data di Avvio') ?></dt>
    <dd class="col-sm-10"><?= Html::encode($model->StartDate) ?></dd>

    <dt class="col-sm-2"><?= Html::encode('Data di creazione') ?></dt>
    <dd class="col-sm-10"><?= Html::encode($model->CreationDate) ?></dd>

    <dt class="col-sm-2"><?= Html::encode('Descrizione') ?></dt>
    <dd class="col-sm-10"><?= Html::encode($model->Description) ?></dd>

    <dt class="col-sm-2"><?= Html::encode('Creatore del progetto') ?></dt>
    <dd class="col-sm-10"><?= Html::encode($model->creatorUser->Name ?? '') ?></dd>

    <dt class="col-sm-2"><?= Html::encode('Numero Partecipanti') ?></dt>
    <dd class="col-sm-10"><?= count($model->users) ?></dd>
</dl>

<div>
    <?= Html::a('<i class="fa-solid fa-circle-left"></i> &nbsp;Torna ad elenco Progetti', ['index'], ['class' => 'btn btn-outline-secondary']) ?>

    <?php if ($model->CreatorUserId == $userId): ?>
        <?= Html::a('<i class="fa-solid fa-pen-to-square fa-lg"></i> Edit', ['edit', 'id' => $model->Id], ['class' => 'btn btn-info', 'style' => 'margin: 0 12px;']) ?>
    <?php endif; ?>

    <?= Html::a('<i class="fa-solid fa-users"></i> &nbsp; Visualizza Partecipanti', ['view-user', 'projectId' => $model->Id], ['class' => 'btn btn-outline-dark']) ?>
</div>
