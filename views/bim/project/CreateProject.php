<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\jui\DatePicker;

/** @var yii\web\View $this */
/** @var app\models\Project $model */
/** @var yii\widgets\ActiveForm $form */

// Definisci qui gli array per gli enum Units e Progress (sostituisci con i tuoi valori reali)
$unitsList = [
    'Unit1' => 'Unit1',
    'Unit2' => 'Unit2',
    'Unit3' => 'Unit3',
];

$progressList = [
    'NotStarted' => 'Not Started',
    'InProgress' => 'In Progress',
    'Completed' => 'Completed',
];

$this->title = 'Create Project';
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<head>
    <script src="https://kit.fontawesome.com/056ba6c557.js" crossorigin="anonymous"></script>
</head>

<h1><?= Html::encode($this->title) ?></h1>

<hr />

<div class="row">
    <div class="col-md-8" style="padding-left:500px">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->errorSummary($model) ?>

        <?= $form->field($model, 'Name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'Units')->dropDownList($unitsList, ['prompt' => 'Select Units']) ?>

        <?= $form->field($model, 'Progress')->dropDownList($progressList, ['prompt' => 'Select Progress']) ?>

        <?= $form->field($model, 'Description')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'StartDate')->widget(DatePicker::class, [
            'dateFormat' => 'yyyy-MM-dd',
            'options' => ['class' => 'form-control'],
        ]) ?>

        <div class="form-group">
            <?= Html::a('<i class="fa-solid fa-circle-left"></i> &nbsp;Back to List', ['index'], ['class' => 'btn btn-outline-secondary']) ?>
            <?= Html::submitButton('Create', ['class' => 'btn btn-primary', 'style' => 'float:right; width:100px;']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
