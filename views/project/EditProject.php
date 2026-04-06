<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\Project $model */
/** @var array $unitsList */
/** @var array $progressList */

$this->title = 'Edit Project';
$userCreator = $model->creatorUserId;
?>

<h1>Edit Project</h1>
<hr />

<div class="row">
    <div class="col-md-4">

        <?php $form = ActiveForm::begin([
            'action' => ['edit', 'id' => $model->id],
            'method' => 'post',
        ]); ?>

        <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'units')->dropDownList($unitsList, ['prompt' => 'Select Units']) ?>

        <?= $form->field($model, 'progress')->dropDownList($progressList, ['prompt' => 'Select Progress']) ?>

        <?= $form->field($model, 'startDate')->input('date') ?>

        <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Back to List', ['details', 'id' => $model->id], ['class' => 'btn btn-outline-secondary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>

<?php
// In _validationScriptsPartial.php puoi includere i JS per la validazione clientside (opzionale)
?>
