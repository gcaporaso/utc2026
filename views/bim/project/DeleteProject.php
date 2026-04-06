<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Project $model */

$this->title = 'Delete';
?>

<h1>Delete</h1>

<h3>Are you sure you want to delete this?</h3>

<div>
    <h4>Project</h4>
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

        <dt class="col-sm-2"><?= Html::encode('Creator User') ?></dt>
        <dd class="col-sm-10"><?= Html::encode($model->creatorUser->name ?? '') ?></dd>
    </dl>

    <?php $form = ActiveForm::begin([
        'action' => ['delete', 'id' => $model->id],
        'method' => 'post',
    ]); ?>
    
    <?= Html::hiddenInput('id', $model->id) ?>
    <?= Html::submitButton('Delete', ['class' => 'btn btn-danger']) ?>
    <?= Html::a('Back to List', ['index'], ['class' => 'btn btn-outline-secondary']) ?>
    
    <?php ActiveForm::end(); ?>
</div>
