<?php
/** @var app\models\File $model */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Cancella File dal Progetto';

// Creiamo il nome file senza la parte dopo '@'
$parts = explode('@', $model->Name);
$name = $parts[0] . $model->Type;
?>

<h1>Cancella</h1>

<h3 style="color:red">Sei sicuro di voler cancellare questo file?</h3>
<div>
    <h4><?= Html::encode($name) ?></h4>
    <hr />
    <dl class="row">
        <dt class="col-sm-2">
            <?= Html::encode($model->getAttributeLabel('Name')) ?>
        </dt>
        <dd class="col-sm-10">
            <?= Html::encode($name) ?>
        </dd>
        <dt class="col-sm-2">
            <?= Html::encode($model->getAttributeLabel('UploadDate')) ?>
        </dt>
        <dd class="col-sm-10">
            <?= date('d/m/Y \o\r\e\: H:m:i', strtotime(Html::encode($model->UploadDate))) ?>
        </dd>
    </dl>
    
    <?= Html::beginForm(['folder/delete-file-from-project-confermato', 'id' => $model->Id, 'projectId' => $model->ProjectId], 'post') ?>
        <?= Html::hiddenInput('id', $model->Id) ?>
        <?= Html::submitButton('Cancella', ['class' => 'btn btn-danger']) ?>
        
        <?= Html::a('<i class="fa-solid fa-circle-left"></i> &nbsp;Annulla', 
            ['folder/index', 'projectId' => $model->ProjectId], 
            ['class' => 'btn btn-outline-secondary']
        ) ?>
    <?= Html::endForm() ?>
</div>
