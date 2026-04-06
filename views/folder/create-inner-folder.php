<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var app\models\Folder $model */
/** @var int $parentId */
/** @var int $projectId */

$this->title = 'Creazione Sottocartella';
?>

<h1>Creazione Sottocartella</h1>

<hr />
<div class="row">
    <div class="col-md-4">

        <?php $form = ActiveForm::begin([
            'action' => ['folder/create-inner-folder', 'id' => $parentId, 'projectId'=>$projectId],
            'method' => 'post'
        ]); ?>

        <!-- Mostra eventuali errori di validazione -->
        <?= $form->errorSummary($model, ['class' => 'text-danger']) ?>

        <div class="form-group">
            <?= $form->field($model, 'Name')->textInput(['maxlength' => true]) ?>
        </div>

        <!-- Campo nascosto per user_id -->
        <?= Html::hiddenInput('Folder[UserId]', Yii::$app->user->id) ?>

        <!-- Campo nascosto per project_id -->
        <?= Html::hiddenInput('Folder[ProjectId]', $projectId) ?>

        <!-- Campo nascosto per id (non obbligatorio se gestito dal DB) -->
        <!-- <?php //echo Html::hiddenInput('Folder[id]', $model->Id) ?> -->

        <div class="form-group">
            <?= Html::a('Indietro', ['folder/inner-det', 'id' => $parentId, 'projectId' => $projectId], ['class' => 'btn btn-outline-secondary']) ?>
            <?= Html::submitButton('Crea Cartella', ['class' => 'btn btn-primary']) ?>
            
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
