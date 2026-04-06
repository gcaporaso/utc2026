<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="plugins/select2/js/select2.full.min.js"></script>
<link rel="stylesheet" href="plugins/sweetalert2/sweetalert2.min.css">
<link rel="stylesheet" href="plugins/select2/css/select2.min.css">
<?php
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>



<!--<section class="content-header">
<div class="container-fluid">
<div class="row mb-2">
<div class="col-sm-6">
<h1>Composizione Documenti</h1>
</div>
<div class="col-sm-6">
<ol class="breadcrumb float-sm-right">
<li class="breadcrumb-item"><a href="#">Home</a></li>
<li class="breadcrumb-item active">Advanced Form</li>
</ol>
</div>
</div>
</div>
</section>-->

<div class="container-fluid" >
    <div class="row">
        <div class="card card-secondary col-sm-12 text-sm"  style="margin-top: 10px" >
             <div class="card-header" >
                <h3 class="card-title">Riepilogo Dati Pratica</h3>

                <!-- Button per ridimensionare e rimuovere CARD -->
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            
             <div class="card-body">
                <div class="row">
                    <p><span>Richiedente:</span></p><div class="col-sm-2" style="color:blue"><?= $modelp->richiedente->nomeCompleto ?></div>
                    <p><span>Protocollo:</span></p><div class="col-sm-1" style="color:blue;"><?= $modelp->NumeroProtocollo ?></div>
                    <p><span>Data:</span></p><div class="col-sm-1" style="color:blue"><?= Yii::$app->formatter->asDate($modelp->DataProtocollo, 'php:d-m-Y') ?></div>
                    <p><span>Compatibilità:</span></p><div class="col-sm-1" style="color:blue"><?= ($modelp->Compatibilita==1 ? 'Si' : 'No') ?></div>
                    <p><span>Stato:</span></p><div class="col-sm-2" style="color:blue"><?= $modelp->statoPratica->descrizione ?></div>
                    <p><span>Foglio:</span></p><div class="col-sm-1" style="color:blue"><?= $modelp->CatastoFoglio ?></div>
                    <p><span>Particella:</span></p><div class="col-sm-1" style="color:blue"><?= $modelp->CatastoParticella ?></div>
                    <p><span>Sub:</span></p><div class="col-sm-1" style="color:blue"><?= $modelp->CatastoSub ?></div>
                </div>
                <div class="row"> 
                    <p><span>Indirizzo:</span></p><div class="col-sm-2" style="color:blue"><?= $modelp->IndirizzoImmobile ?></div>
                    <p><span>Intervento:</span></p><div class="col-sm-9" style="color:blue" ><?= $modelp->DescrizioneIntervento ?></div>
                    
                </div> 
            </div>
        </div>
            
    </div>
    <div class="row">
        <div class="card card-default col-md-12"  >
                <!-- TOOLBAR -->
                <div class="card-header" >
                    <?= Html::a('Allegati', ['paesistica/allegati','idpratica'=>$idpratica], ['class'=>'btn btn-success']) ?>
                    

                </div>
        </div>
    </div>




    <div class="row">
    <div class="col-md-12" >
        <div class="card card-secondary"  style="margin-top: 5px" >
            <!-- INTESTAZIONE -->
            <div class="card-header" >
                <h3 class="card-title">Composizione Documenti</h3>

                <!-- Button per ridimensionare e rimuovere CARD -->
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        <!-- CONTENUTO -->   
       <form>
        <div class="card-body">
        
            <div class="form-group">
            
            <div class="row">    
                <div class="col-md-8">   
                    <label>Modelli</label>
                    <select class="form-control select2" style="width: 100%;">
                       <?php
                       $docs=ArrayHelper::map(app\models\Modulistica::find()
                               ->where(['categoria'=>2,'report'=>0])
           //                    ->where(['report'=>0])
                               ->asArray()
                               ->all(),
                               'idmodulistica','descrizione');
                       foreach($docs as $key => $value) {
                       echo "<option value=' $key '> $value </option>";
                       }
                       ?>
                     </select>
                </div>
                <div class="col-md-1" style="margin-top: 32px">   
                    <button type="submit" class="btn btn-primary">Componi</button> 
                </div>
            
            </div>
       

            </div><!-- form group -->
        </div> 
           </form>
</div><!-- card secondary -->
</div><!-- col-md-12 -->
</div><!-- row -->
</div><!-- conteiner-fluid -->


