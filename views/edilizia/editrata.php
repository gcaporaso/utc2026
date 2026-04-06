
<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
//use vkabachenko\filepond\widget\FilepondWidget;
use yii\widgets\Pjax;
use yii\helpers\Url;
//use kartik\dynagrid\DynaGrid;
//use kartik\grid\GridView;
use kartik\datecontrol\DateControl;
//use kartik\editable\Editable;
use yii\bootstrap\Modal;
use yii\web\View;
use yii\grid\GridView;

?>




<div class="row" style="padding-top:10px;">
    <div class="card card-secondary col-md-12"  style="margin-top: 5px" >
       <!-- INTESTAZIONE -->
        <div class="card-header" >
                <h3 class="card-title">Modifica Oneri Concessori Pratica</h3>
<!--                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                    </button>
                </div>-->
        </div>
       
       
       
        <div class="card-body">
            
    <?php $form = ActiveForm::begin([
        'action'=>['edilizia/editrata', 'id'=>$id, 'idpratica'=>$idpratica],
        'id' => 'oneri-form'
    ]) ?>
            <div class="row">    
<!--                <div class="col-md-12">    -->
                    
                    <div class="col-md-3">
                      <?= $form->field($model, 'tiporata')->dropDownList([0 => 'RATE', 1 => 'UNICA'])->label('TIPO RATA') ?>  
                    </div>              
                    <div class="col-md-2">
                      <?php echo $form->field($model, 'ratanumero')->textInput()->label('Rata Numero:') ?>
                    </div> 
            </div>
            <div class="row">
                    <div class="col-md-2">
                      <?php echo $form->field($model, 'importodovutorata')->textInput()->label('Importo dovuto:') ?>
                    </div> 
                    <div class="col-md-3">
                    <?php  echo $form->field($model, 'datascadenza')->widget(DateControl::classname(), [
                    'type' => 'date',
                    'ajaxConversion' => false,
                    'autoWidget' => true,
                    'widgetClass' => '',
                    'displayFormat' => 'php:d-m-Y',
                    'saveFormat' => 'php:Y-m-d',
                    'saveTimezone' => 'UTC',
                    'displayTimezone' => 'Europe/Amsterdam',
                    'language' => 'it',
                    'name' => 'dp_4',
                    'convertFormat'=>true,
                //'type' => DatePicker::TYPE_COMPONENT_APPEND,
                //'value'=>date("d-m-yyyy"),
                //'dateFormat' => 'php:d-m-Y',
                'widgetOptions' => [
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'php:d-m-Y',
                        'todayHighlight'=>true,
                        ],
                    
                ]])->label('Data Scadenza:'); ?>
                    </div>
            </div>
            <div class="row">    
                
                <div class="col-md-2">
                    <?php
                    echo $form->field($model, 'importopagatorata')->textInput()->label('Importo pagato:')
                    ?>
                </div>
                <div class="col-md-3">
                    <?php
                    echo $form->field($model, 'datapagamento')->widget(DateControl::classname(), [
                    'type' => 'date',
                    'ajaxConversion' => false,
                    'autoWidget' => true,
                    'widgetClass' => '',
                    'displayFormat' => 'php:d-m-Y',
                    'saveFormat' => 'php:Y-m-d',
                    'saveTimezone' => 'UTC',
                    'displayTimezone' => 'Europe/Amsterdam',
                    'language' => 'it',
                    'name' => 'dp_4',
                    'convertFormat'=>true,
                //'type' => DatePicker::TYPE_COMPONENT_APPEND,
                //'value'=>date("d-m-yyyy"),
                //'dateFormat' => 'php:d-m-Y',
                    'widgetOptions' => [
                        'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'php:d-m-Y',
                        'todayHighlight'=>true,
                        ],
                    ]])->label('Data Pagamento:'); ?>
                    </div>  
                <div class="col-md-1" style="margin-top:32px">
                    <?php echo $form->field($model, 'pagata')->checkbox()->label(false) ?>
                </div>
             </div>
            <div class="row">       
                    <div class="col-md-1" style="margin-top: 32px">
                        <?= Html::submitButton($model->isNewRecord ? 'Aggiungi' : 'Aggiorna', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                    </div>
<!--                    </div>-->
                </div>
            
             <?php ActiveForm::end() ?>
                </div>

        </div><!-- row -->
        
        </div><!-- card body -->
 