<?php
/** @var app\models\Folder $model */
/** @var int $project */

use yii\helpers\Html;

$this->title = 'Cancellazione Cartella';

// Project ID passato dal controller
$project = isset($project) ? $project : $model->ProjectId;
?>

<h3 style="color:red;">Sei sicuro di voler cancellare questa cartella? <?= Html::encode($model->name) ?> ?</h3>
<div>
    <hr />
    <dl class="row">
        <dt class="col-sm-2">
            <?= Html::encode($model->getAttributeLabel('Name')) ?>
        </dt>
        <dd class="col-sm-10">
            <?= Html::encode($model->name) ?>
        </dd>
        <dt class="col-sm-2">
            <?= Html::encode($model->getAttributeLabel('CreationDate')) ?>
        </dt>
        <dd class="col-sm-10">
            <?= Html::encode($model->CreationDate) ?>
        </dd>
    </dl>
    
    <?= Html::beginForm(['folders/delete', 'id' => $model->Id, 'projectId' => $project], 'post') ?>
        <?= Html::hiddenInput('id', $model->Id) ?>
        <?= Html::submitButton('Delete', ['class' => 'btn btn-danger']) ?>

        <?php if ($model->ParentId == 0): ?>
            <?= Html::a('<i class="fa-solid fa-circle-left"></i> &nbsp;Annulla', 
                ['folder/index', 'projectId' => $project], 
                ['class' => 'btn btn-outline-secondary']
            ) ?>
        <?php else: ?>
            <?= Html::a('<i class="fa-solid fa-circle-left"></i> &nbsp;Back to List', 
                ['folder/inner-det', 'id' => $model->ParentId, 'projectId' => $model->ProjectId], 
                ['class' => 'btn btn-outline-secondary']
            ) ?>
        <?php endif; ?>

    <?= Html::endForm() ?>
</div>
