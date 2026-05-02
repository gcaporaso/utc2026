<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\GisProgetto;

$this->title = 'Nuovo Progetto GIS';
?>
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0"><?= Html::encode($this->title) ?></h1>
    </div>
</div>
<div class="content">
<div class="container-fluid">
<div class="card" style="max-width:600px;">
    <div class="card-body">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'nome')->textInput(['maxlength' => 255]) ?>

        <?= $form->field($model, 'tipo')->dropDownList(GisProgetto::tipoLabels(), ['prompt' => '-- Seleziona tipo --']) ?>

        <?= $form->field($model, 'descrizione')->textarea(['rows' => 4]) ?>

        <div class="form-group">
            <?= Html::submitButton('<i class="fas fa-save mr-1"></i>Salva', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Annulla', ['index'], ['class' => 'btn btn-secondary ml-2']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
</div>
</div>
