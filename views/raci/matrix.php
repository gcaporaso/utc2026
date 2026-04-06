<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var $project app\models\Project */
/** @var $tasks app\models\ProjectTask[] */
/** @var $users app\models\User[] */
/** @var $roles app\models\RaciRole[] */
/** @var $matrix array */
?>

<h1>Matrice RACI - <?= Html::encode($project->Name) ?></h1>

<?php $form = ActiveForm::begin(); ?>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Attività</th>
            <?php foreach ($users as $user): ?>
                <th><?= Html::encode($user->username) ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tasks as $task): ?>
            <tr>
                <td><strong><?= Html::encode($task->name) ?></strong></td>
                <?php foreach ($users as $user): ?>
                    <td>
                        <?= Html::dropDownList(
                            "matrix[{$task->id}][{$user->id}]",
                            $matrix[$task->id][$user->id] ?? null,
                            \yii\helpers\ArrayHelper::map($roles, 'id', 'label'),
                            ['prompt' => '']
                        ) ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="form-group">
    <?= Html::submitButton('Salva', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>
