<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var $project app\models\Project */
/** @var $tasks app\models\ProjectTask[] */
/** @var $catalog app\models\TaskCatalog[] */
/** @var $newTask app\models\ProjectTask */
?>

<h1>Attività del progetto: <?= Html::encode($project->Name) ?></h1>

<div class="row">
    <div class="col-md-6">
        <h3>Aggiungi dal catalogo</h3>
        <?php $form = ActiveForm::begin(); ?>
        <?= Html::dropDownList('catalog_id', null,
            \yii\helpers\ArrayHelper::map($catalog, 'id', 'name'),
            ['class' => 'form-control', 'prompt' => 'Seleziona attività...']
        ) ?>
        <br>
        <?= Html::submitButton('Aggiungi', ['class' => 'btn btn-primary']) ?>
        <?php ActiveForm::end(); ?>
    </div>

    <div class="col-md-6">
        <h3>Aggiungi attività personalizzata</h3>
        <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($newTask, 'name')->textInput(['maxlength' => true]) ?>
        <?= $form->field($newTask, 'description')->textarea(['rows' => 3]) ?>
        <?= Html::submitButton('Aggiungi', ['class' => 'btn btn-success']) ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<hr>

<h3>Elenco attività del progetto</h3>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Descrizione</th>
            <th style="width: 120px;">Azioni</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tasks as $task): ?>
            <tr>
                <td><?= Html::encode($task->name) ?></td>
                <td><?= Html::encode($task->description) ?></td>
                <td>
                    <?= Html::a('Elimina', ['delete-task', 'id' => $task->id, 'projectId' => $project->Id], [
                        'class' => 'btn btn-danger btn-sm',
                        'data' => [
                            'confirm' => 'Sei sicuro di voler eliminare questa attività?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
