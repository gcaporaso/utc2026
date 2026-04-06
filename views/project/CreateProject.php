<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
//use yii\jui\DatePicker;
use kartik\datecontrol\DateControl;

/** @var yii\web\View $this */
/** @var app\models\Project $model */
/** @var yii\widgets\ActiveForm $form */

// Definisci qui gli array per gli enum Units e Progress (sostituisci con i tuoi valori reali)
$unitsList = [
    '1' => 'Metrico',
    '2' => 'Imperiale',
    
];

$progressList = [
    'NotStarted' => 'Not Started',
    'InProgress' => 'In Progress',
    'Completed' => 'Completed',
];

$this->title = 'Creazione di un nuovo Progetto';
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
        <?php $model->StartDate =  date('d-m-Y'); ?>
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->errorSummary($model) ?>

        <?= $form->field($model, 'Name')->textInput(['maxlength' => true])->label('Titolo del Progetto') ?>

        <?= $form->field($model, 'Units')->dropDownList($unitsList, ['prompt' => 'Seleziona unità di misura', 'options' => [ 1 => ['Selected'=>'selected']]]) ?>

        <?= $form->field($model, 'Progress')->dropDownList($progressList, ['prompt' => 'Seleziona Stato'])->label('Stato del Progetto') ?>

        <?= $form->field($model, 'Description')->textInput(['maxlength' => true])->label('Descrizione') ?>

        <?= $form->field($model, 'StartDate')->widget(DateControl::class, [
            'type'=>DateControl::FORMAT_DATETIME,
            
        ]) ?>

        <div class="form-group">
            <?= Html::a('<i class="fa-solid fa-circle-left"></i> &nbsp;Lista dei Progetti', ['index'], ['class' => 'btn btn-outline-secondary']) ?>
            <?= Html::submitButton('Crea', ['class' => 'btn btn-primary', 'style' => 'float:right; width:100px;']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
