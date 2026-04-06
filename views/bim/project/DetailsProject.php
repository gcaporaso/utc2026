<?php
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Project $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// Per ottenere l'ID utente loggato in Yii2:
$userId = Yii::$app->user->id;
?>

<head>
    <script src="https://kit.fontawesome.com/056ba6c557.js" crossorigin="anonymous"></script>
</head>

<h1><?= Html::encode($model->name) ?> Details</h1>

<hr />

<dl class="row">
    <dt class="col-sm-2"><?= Html::encode('Name') ?></dt>
    <dd class="col-sm-10"><?= Html::encode($model->name) ?></dd>

    <dt class="col-sm-2"><?= Html::encode('Units') ?></dt>
    <dd class="col-sm-10"><?= Html::encode($model->units) ?></dd>

    <dt class="col-sm-2"><?= Html::encode('Progress') ?></dt>
    <dd class="col-sm-10"><?= Html::encode($model->progress) ?></dd>

    <dt class="col-sm-2"><?= Html::encode('Start Date') ?></dt>
    <dd class="col-sm-10"><?= Html::encode($model->startDate) ?></dd>

    <dt class="col-sm-2"><?= Html::encode('Creation Date') ?></dt>
    <dd class="col-sm-10"><?= Html::encode($model->creationDate) ?></dd>

    <dt class="col-sm-2"><?= Html::encode('Description') ?></dt>
    <dd class="col-sm-10"><?= Html::encode($model->description) ?></dd>

    <dt class="col-sm-2"><?= Html::encode('User Creator') ?></dt>
    <dd class="col-sm-10"><?= Html::encode($model->creatorUser->name ?? '') ?></dd>

    <dt class="col-sm-2"><?= Html::encode('Members No.') ?></dt>
    <dd class="col-sm-10"><?= count($model->users) ?></dd>
</dl>

<div>
    <?= Html::a('<i class="fa-solid fa-circle-left"></i> &nbsp;Back to List', ['index'], ['class' => 'btn btn-outline-secondary']) ?>

    <?php if ($model->creatorUserId == $userId): ?>
        <?= Html::a('<i class="fa-solid fa-pen-to-square fa-lg"></i> Edit', ['edit', 'id' => $model->id], ['class' => 'btn btn-info', 'style' => 'margin: 0 12px;']) ?>
    <?php endif; ?>

    <?= Html::a('<i class="fa-solid fa-users"></i> &nbsp; Show Project Members', ['view-user', 'projectId' => $model->id], ['class' => 'btn btn-outline-dark']) ?>
</div>
