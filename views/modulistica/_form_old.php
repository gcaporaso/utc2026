<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\Categoriemodulistica;
use kartik\select2\Select2;
use kartik\date\DatePicker;


/* @var $this yii\web\View */
/* @var $model app\models\Edilizia */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="modulistica-form">

    <?php $form = ActiveForm::begin([
        'action'=>['modulistica/nuovo'],
        'id' => 'modulistica-form',
        'options' => ['enctype' => 'multipart/form-data'],
    ]); ?>
<div class="row">
        <div class="col-sm-6">
        <?php
        $listatitoli = ArrayHelper::map(Categoriemodulistica::find()->all(), 'idtipomodulistica', 'descrizione');
            echo $form->field($model, 'categoria')->widget(Select2::classname(), [
                'name' => 'SelTipo',
                'size' => Select2::MEDIUM,
                'data' => $listatitoli,
                'options' => ['id' => 'lstitolo', 'multiple' => false, 'value' => 1,
                    'placeholder' => 'Seleziona tipo ...',
                ],
                'pluginOptions' => ['allowClear' => false],
            ])->label('Tipo Modello');
            ?>
        </div>
</div>
<div class="row">
        <div class="col-sm-3" >
        <?= $form->field($model, 'descrizione')->textInput() ?>    
        </div>
        <div class="col-sm-2" >
        <?= $form->field($model, 'codice')->textInput() ?>    
        </div>
</div>
<div class="row">
        <div class="col-sm-1" >
        <?= $form->field($model, 'numerorevisione')->textInput() ?>    
        </div>
        <div class="col-sm-2">
        <?= $form->field($model, 'datarevisione')->widget(DatePicker::classname(), [
                'language' => 'it',
                'name' => 'dp_3',
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                'value'=>date("d-m-yyyy"),
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'd-m-yyyy',
                    'todayHighlight'=>true,
                    
        ]])
        ?>
        </div>
</div>

<div class="row">
    <div class="col-sm-6" >
        <?php echo $form->field($model, 'nomefile')->label(false)->fileInput() ?>
    </div>
</div> 
<!--<div class="row">
    <div class="col-sm-6" >
        <?php // echo $form->field($model, 'path')->textInput() ?>
    </div>
</div> -->
    <div class="form-group">
        <?= Html::submitButton('Salva', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
