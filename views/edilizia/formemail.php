<?php
use yii\widgets\ActiveForm;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="modalContent">
    <div class="modal-body">
    <?php $form = ActiveForm::begin(['options' => ['data-ajax' => 1],'id' => 'email-form']); ?>
    <?= $form->field($modelemail, 'efrom')->textInput(['autofocus' => true])->label('Da:') ?>
    <?= $form->field($modelemail, 'eto')->label('a:') ?>
    <?= $form->field($modelemail, 'esubject')->label('Oggetto:') ?>
    <?= $form->field($modelemail, 'ebody')->textarea(['rows' => 6])->label('Testo:') ?>
    <div class="form-group">
        <?= Html::submitButton('Invia', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    </div>
</div>
