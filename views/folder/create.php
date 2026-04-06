<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Folder $model */
/** @var int $ProjId */
/** @var app\models\Project $project */

$this->title = 'Crezione nuova cartella';
$user = Yii::$app->user->id;
?>

<head>
    <script src="https://kit.fontawesome.com/056ba6c557.js" crossorigin="anonymous"></script>
</head>

<h1><?= Html::encode($project->Name) ?></h1>

<h4>Creazione di una nuova cartella</h4>
<hr/>

<div class="row">
    <div class="col-md-4">
        <?php $form = ActiveForm::begin([
            'action' => ['folder/create', 'parentId'=>$parentId,'projectId' => $project->Id, 'UserId' => $user],
            'method' => 'post'
        ]); ?>

        <?= $form->errorSummary($model, ['class' => 'text-danger']) ?>

        <?= $form->field($model, 'Name')->textInput(['class' => 'form-control']) ?>

        <div class="form-group">
            <?= Html::submitButton('Crea', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('<i class="fa-solid fa-circle-left"></i> &nbsp; Torna alla Lista',
                ['folder/index', 'projectId' => $project->Id],
                ['class' => 'btn btn-outline-dark']
            ) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
