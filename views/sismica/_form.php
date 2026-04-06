<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use app\models\Committenti;
use app\models\TitoliSismica;
use app\models\TipoProcedimentoSismica;
use app\models\Tecnici;
use app\models\Imprese;
use app\models\StatoEdilizia;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model app\models\Sismica */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="card card-secondary col-md-12"  style="margin-top: 5px" >
       <!-- INTESTAZIONE -->
        <div class="card-header" >
                <?php if ($model->scenario=='insert') { ?>
                <h3 class="card-title">INSERIMENTO DATI NUOVA PRATICA SISMICA</h3>
                <?php } else { ?>        
                <h3 class="card-title">AGGIORNAMENTO DATI PRATICA SISMICA</h3><!-- comment -->
                <?php } ?>        
<!--                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                    </button>
                </div>-->
        </div>

    <?php $form = ActiveForm::begin(); ?>
<div class="card-body">

    
    <?php
        $etecnici = ArrayHelper::map(Tecnici::find()->all(), 'tecnici_id', 'nomeCompleto');
        $egeologi = ArrayHelper::map(Tecnici::find()->where(['idtitolo'=>7])->all(), 'tecnici_id', 'nomeCompleto');
    ?>
<div class="row">
    <div class="col-sm-1" >
        <?= $form->field($model, 'Protocollo')->textInput()->label('Prot.llo Istanza') ?>    
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'DataProtocollo')->widget(DateControl::classname(), [
                    'type' => 'date',
                    'ajaxConversion' => false,
                    'autoWidget' => true,
                    'widgetClass' => '',
                    'displayFormat' => 'php:d-m-Y',
                    'saveFormat' => 'php:Y-m-d',
                    'saveTimezone' => 'UTC',
                    'displayTimezone' => 'Europe/Amsterdam',
                    'language' => 'it',
                    'name' => 'dp_3',
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
                    
        ]])
        ?>
    </div>
    
    <div class="col-sm-2">
        <?php
        //$default = TitoliSismica::findOne(1)->descrizione;
        $model->TipoTitolo=1;
        $data = ArrayHelper::map(TitoliSismica::find()->all(), 'idtitoli_sismica', 'descrizione');
        echo $form->field($model, 'TipoTitolo')->widget(Select2::classname(), [
       'data' => $data,
       //'initValueText' => $default,
       'options' => ['placeholder' => 'Tipo Richiesta ...'],
       'pluginOptions' => [
           'allowClear' => true
       ],])->label('Tipo Richiesta'); 
        ?>
    </div>
    <div class="col-sm-2">
        <?php
        $model->TipoProcedimento=1;
        $data = ArrayHelper::map(TipoProcedimentoSismica::find()->all(), 'idtipo', 'descrizione');
        echo $form->field($model, 'TipoProcedimento')->widget(Select2::classname(), [
       'data' => $data,
       'options' => ['placeholder' => 'Tipo Procedimento ...'],
       'pluginOptions' => [
           'allowClear' => true
       ],])->label('Tipo Procedimento'); 
        ?>
    </div>
    <div class="col-sm-2">
        <?php
        $a= ['1' => 'Nuova Costruzione', '2' => 'Costruzione Esistente - Adeguamento', '3' => 'Costruzione Esistente - Miglioramento', '4' => 'Costruzione Esistente - Riparazione o Intervento Locale'];
        echo $form->field($model, 'TipoDenuncia')->dropDownList($a)->label('Tipo Denuncia');;
        ?>
    </div>
    
</div>
<div class="row">
    <div class="col-sm-7" >
        <?= $form->field($model, 'DescrizioneLavori')->textarea(['row' => '6']) ?>
    </div>
    <div class="col-sm-2" >
     <?= $form->field($model, 'Ubicazione')->textInput(['maxlength' => true]) ?>
    </div>
    
</div>
<div class="row">
    <div class="col-sm-1" >    
    <?= $form->field($model, 'NumeroProtocolloAutorizzazione')->textInput()->label('Prot. Aut.zione') ?>
    </div>
    <div class="col-sm-1" >    
    <?= $form->field($model, 'NumeroAUTORIZZAZIONE')->textInput()->label('Nr. Autorizzazione') ?>
    </div> 
    <div class="col-sm-2" >
         <?= $form->field($model, 'DataAUTORIZZAZIONE')->widget(DateControl::classname(), [
                    'type' => 'date',
                    'ajaxConversion' => false,
                    'autoWidget' => true,
                    'widgetClass' => '',
                    'displayFormat' => 'php:d-m-Y',
                    'saveFormat' => 'php:Y-m-d',
                    'saveTimezone' => 'UTC',
                    'displayTimezone' => 'Europe/Amsterdam',
                    'language' => 'it',
                    'name' => 'dp_3',
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
                    
        ]])
        ?>
    </div>
    <div class="col-sm-2">    
     <?php
     $stato = ArrayHelper::map(StatoEdilizia::find()->all(), 'idstato_edilizia', 'descrizione');
     echo $form->field($model, 'StatoPratica')->widget(Select2::classname(), [
    'data' => $stato,
    'options' => ['placeholder' => 'Stato Pratica ...'],
    'pluginOptions' => [
        'allowClear' => true
    ],])->label('Stato Pratica'); ?>
    </div>
    
    
    
    
    
</div>       
<div class="row">
    <div class="col-sm-2" >
        <?= $form->field($model, 'ImportoContributo')->textInput(['maxlength' => true]) ?>
    </div>
     <div class="col-sm-2" >
        <?= $form->field($model, 'ImportoPagato')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-2" >
        <?= $form->field($model, 'DataVersamentoContributo')->widget(DateControl::classname(), [
                    'type' => 'date',
                    'ajaxConversion' => false,
                    'autoWidget' => true,
                    'widgetClass' => '',
                    'displayFormat' => 'php:d-m-Y',
                    'saveFormat' => 'php:Y-m-d',
                    'saveTimezone' => 'UTC',
                    'displayTimezone' => 'Europe/Amsterdam',
                    'language' => 'it',
                    'name' => 'dp_3',
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
                    
        ]])
        ?>
    </div>
    <div class="col-sm-2" style="margin-top:30px">
    <?= $form->field($model, 'PAGATO_COMMISSIONE')->checkbox()->label(false); ?>
    </div>
</div>        

<div class="row">
     
     <div class="col-sm-2" >
         <?php 
         $cattipo=[0=>'N.C.T.',1=>'N.C.E.U.'];
         echo $form->field($model, 'CatastoTipo')->widget(Select2::classname(), [
        'data' => $cattipo,
        'options' => [
            'placeholder' => 'Tipo Catasto ...',
            'value' => 0,],
        'pluginOptions' => [
        'allowClear' => true
    ],])->label('Tipo Catasto');
         ?>
     </div>
     <div class="col-sm-1" >
    <?= $form->field($model, 'CatastoFoglio')->textInput() ?>
    </div>
    <div class="col-sm-2" >
    <?= $form->field($model, 'CatastoParticelle')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-1" >
    <?= $form->field($model, 'CatastoSub')->textInput(['maxlength' => true]) ?>
    </div>
</div>
<div class="row">    
    <div class="col-sm-2" style="margin-top:30px">
        <?php
        echo $form->field($model, 'Sopraelevazione')->checkbox()->label(false);
        ?>
    </div>
    <div class="col-sm-2" style="margin-top:30px">
        <?php
        echo $form->field($model, 'Ampliamento')->checkbox()->label(false);
        ?>
    </div>
    <div class="col-sm-2" style="margin-top:30px">
        <?php
        echo $form->field($model, 'Integrazione')->checkbox()->label(false);
        ?>
    </div>
    <div class="col-sm-2" style="margin-top:30px">
        <?php
        echo $form->field($model, 'Variante')->checkbox()->label(false);
        ?>
    </div>
</div>   
<div class="row">
     
    <div class="col-sm-2" >
     <?= $form->field($model, 'muratura_ordinaria')->checkbox()->label(false); ?>   
    </div>
        <div class="col-sm-2" >
     <?= $form->field($model, 'muratura_armata')->checkbox()->label(false); ?>   
    </div>
    <div class="col-sm-2" >
     <?= $form->field($model, 'cemento_armato')->checkbox()->label(false); ?>   
    </div>
    <div class="col-sm-2" >
     <?= $form->field($model, 'cemento_precompresso')->checkbox()->label(false); ?>   
    </div>
</div>
<div class="row">
    <div class="col-sm-2" >
     <?= $form->field($model, 'struttura_metallica')->checkbox()->label(false); ?>   
    </div>
    <div class="col-sm-2" >
     <?= $form->field($model, 'struttura_legno')->checkbox()->label(false); ?>   
    </div>
    <div class="col-sm-2" >
     <?= $form->field($model, 'StrutturaAltro')->checkbox()->label(false); ?>   
    </div>
</div>
    
<div class="row">
     
    <div class="col-sm-2" >
     <?= $form->field($model, 'TrasmissioneGenioCivile')->checkbox()->label(false); ?>   
    </div>
        <div class="col-sm-2" >
     <?= $form->field($model, 'PubblicazioneALBO')->checkbox()->label(false); ?>   
    </div>
    <div class="col-sm-1" >
     <?= $form->field($model, 'Ritiro')->checkbox()->label(false); ?>   
    </div>

</div>
<div class="row">
    <div class="col-sm-3">    
        <?php
        $data = ArrayHelper::map(Committenti::find()->all(), 'committenti_id', 'nomeCompleto');
        echo $form->field($model, 'committenti_id')->widget(Select2::classname(), [
       'data' => $data,
       'options' => ['placeholder' => 'Richiedente ...'],
       'pluginOptions' => [
           'allowClear' => true
       ],])->label('Dichiarante'); 
        ?>
    </div>
    <div class="col-sm-2">   
        
         <?php 
         $tipoc=[0=>'Committente Privato',1=>'Costruttore che esegue in proprio',2=>'Committente Pubblico'];
         echo $form->field($model, 'TipoCommittente')->widget(Select2::classname(), [
        'data' => $tipoc,
        'options' => [
            'placeholder' => 'Tipo Dichiarante ...',
            'value' => 0],
        'pluginOptions' => [
        'allowClear' => true
    ],])->label('Tipo Dichiarante');
         ?>
    </div>
    <div class="col-sm-1" style="margin-top:30px">
     <?= $form->field($model, 'societa')->checkbox()->label(false); ?>   
    </div>
        <div class="col-sm-2" style="margin-top:30px">
     <?= $form->field($model, 'ente_pubblico')->checkbox()->label(false); ?>   
    </div>
</div>
<hr style='padding-left:10px;padding-left:10px;border-style:groove;border-width: 1px;'>
<!--<div class="row">
    <div class="col-sm-2">    
     <?php
//     //$etecnici = ArrayHelper::map(Tecnici::find()->all(), 'tecnici_id', 'nomeCompleto');
//     echo $form->field($model, 'PROG_ARCH_ID')->widget(Select2::classname(), [
//    'data' => $etecnici,
//    'options' => ['placeholder' => 'Progettista Arch. ...'],
//    'pluginOptions' => [
//        'allowClear' => true
//    ],])->label('Progettista Architettonico'); 
     ?>
    </div>
    <div class="col-sm-2">    
     <?php
//     //$etecnici = ArrayHelper::map(Tecnici::find()->all(), 'tecnici_id', 'nomeCompleto');
//     echo $form->field($model, 'PROG_STR_ID')->widget(Select2::classname(), [
//    'data' => $etecnici,
//    'options' => ['placeholder' => 'Progettista Strutturale ...'],
//    'pluginOptions' => [
//        'allowClear' => true
//    ],])->label('Progettista Strutturale'); 
     ?>
    </div>
    <div class="col-sm-2">    
     <?php
//     echo $form->field($model, 'GeologoID')->widget(Select2::classname(), [
//    'data' => $egeologi,
//    'options' => ['placeholder' => 'Geologo ....'],
//    'pluginOptions' => [
//        'allowClear' => true
//    ],])->label('Geologo'); 
     ?>
    </div> 
    
    <div class="col-sm-2">    
     <?php
//     echo $form->field($model, 'DD_LL_ARCH_ID')->widget(Select2::classname(), [
//    'data' => $etecnici,
//    'options' => ['placeholder' => 'Direttore Lavori Architettonico ....'],
//    'pluginOptions' => [
//        'allowClear' => true
//    ],])->label('Direttore Lavori Architettonico'); 
     ?>
    </div>
    
    <div class="col-sm-2">    
     //<?php
     //$etecnici = ArrayHelper::map(Tecnici::find()->all(), 'tecnici_id', 'nomeCompleto');
//     echo $form->field($model, 'DIR_LAV_STR_ID')->widget(Select2::classname(), [
//    'data' => $etecnici,
//    'options' => ['placeholder' => 'Direttore Lavori Strutturale ...'],
//    'pluginOptions' => [
//        'allowClear' => true
//    ],])->label('Direttore Lavori Strutturale'); 
     ?>
    </div>
</div>-->
 <div class="row">   
    <div class="col-sm-2">    
     <?php
     $imprese = ArrayHelper::map(Imprese::find()->all(), 'imprese_id', 'nomeCompleto');
     echo $form->field($model, 'IMPRESA_ID')->widget(Select2::classname(), [
    'data' => $imprese,
    'options' => ['placeholder' => 'Impresa Esecutrice ...'],
    'pluginOptions' => [
        'allowClear' => true
    ],])->label('Impresa Esecutrice'); ?>
    </div>
    <div class="col-sm-2">    
     <?php
     //$etecnici = ArrayHelper::map(Tecnici::find()->all(), 'tecnici_id', 'nomeCompleto');
     echo $form->field($model, 'COLLAUDATORE_ID')->widget(Select2::classname(), [
    'data' => $etecnici,
    'options' => ['placeholder' => 'Collaudatore ...'],
    'pluginOptions' => [
        'allowClear' => true
    ],])->label('Collaudatore'); ?>
    </div>
</div>
<hr style='padding-left:10px;padding-left:10px;border-style:groove;border-width: 1px;'>
<div class="row">   
    <div class="col-sm-2">
<?php 
$istruttori = ArrayHelper::map(\app\models\Composizioni::find()
            ->joinWith(['componenti'], true, 'INNER JOIN')
            ->joinWith(['commissione'], true, 'INNER JOIN')
            ->where(['commissioni.Tipo'=>3])
            ->all(),'componenti.idcomponenti_commissioni', 'componenti.nomeCompleto');

echo $form->field($model, 'IstruttoreID')->widget(Select2::classname(), [
    'data' => $istruttori,
    'options' => ['placeholder' => 'Istruttore ...'],
    'pluginOptions' => [
        'allowClear' => true
    ],])->label('Istruttore');

?>
    </div>
 </div>    
<hr style='padding-left:10px;padding-left:10px;border-style:groove;border-width: 1px;'>
<div class="row">   
    <div class="col-sm-10">

    <?php 
        $esommario = ArrayHelper::map(\app\models\Edilizia::find()
            ->all(),'edilizia_id', 'sommario');

        echo $form->field($model, 'pratica_id')->widget(Select2::classname(), [
        'data' => $esommario,
    'options' => ['placeholder' => 'Pratica ...'],
    'pluginOptions' => [
        'allowClear' => true
    ],])->label('Riferita alla Pratica Edilizia:');

?>
        
        
        
    </div>    
 </div>    
 <hr style='padding-left:10px;padding-left:10px;border-style:groove;border-width: 1px;'>   
    
<div class="row">   
    <div class="col-sm-2">
        
        <?= $form->field($model, 'Inizio_Lavori')->widget(DateControl::classname(), [
                    'type' => 'date',
                    'ajaxConversion' => false,
                    'autoWidget' => true,
                    'widgetClass' => '',
                    'displayFormat' => 'php:d-m-Y',
                    'saveFormat' => 'php:Y-m-d',
                    'saveTimezone' => 'UTC',
                    'displayTimezone' => 'Europe/Amsterdam',
                    'language' => 'it',
                    'name' => 'dp_3',
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
                    
        ]])
        ?>
    </div>    
    <div class="col-sm-2">
        <?= $form->field($model, 'Fine_Lavori')->widget(DateControl::classname(), [
                    'type' => 'date',
                    'ajaxConversion' => false,
                    'autoWidget' => true,
                    'widgetClass' => '',
                    'displayFormat' => 'php:d-m-Y',
                    'saveFormat' => 'php:Y-m-d',
                    'saveTimezone' => 'UTC',
                    'displayTimezone' => 'Europe/Amsterdam',
                    'language' => 'it',
                    'name' => 'dp_3',
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
                    
        ]])
        ?>
    </div> 
        <div class="col-sm-2">
            <?= $form->field($model, 'Data_Strutture_Ultimate')->widget(DateControl::classname(), [
                    'type' => 'date',
                    'ajaxConversion' => false,
                    'autoWidget' => true,
                    'widgetClass' => '',
                    'displayFormat' => 'php:d-m-Y',
                    'saveFormat' => 'php:Y-m-d',
                    'saveTimezone' => 'UTC',
                    'displayTimezone' => 'Europe/Amsterdam',
                    'language' => 'it',
                    'name' => 'dp_3',
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
                    
        ]])
        ?>
    </div> 
        <div class="col-sm-2">
       
      <?= $form->field($model, 'Data_Collaudo')->widget(DateControl::classname(), [
                    'type' => 'date',
                    'ajaxConversion' => false,
                    'autoWidget' => true,
                    'widgetClass' => '',
                    'displayFormat' => 'php:d-m-Y',
                    'saveFormat' => 'php:Y-m-d',
                    'saveTimezone' => 'UTC',
                    'displayTimezone' => 'Europe/Amsterdam',
                    'language' => 'it',
                    'name' => 'dp_3',
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
                    
        ]])
        ?>      
    </div> 
    
    
 </div> 
 
    </div>       
       <div class="card-footer">
           <?= Html::submitButton(Yii::t('app', 'Salva'), ['class' => 'btn btn-success']) ?>
           <?= Html::a('Annulla', ['sismica/index'], ['class'=>'btn btn-primary', 'style'=>'margin-left:20px']) ?>
           <?php ActiveForm::end(); ?>
       </div>
 
</div>
