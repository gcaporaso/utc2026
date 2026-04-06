<?php
$this->registerJs("
$('#selButton').on('click',function (event) {
//alert('OK');
var keys = $('#w3').yiiGridView('getSelectedRows');
    $.post({
        url : '/index.php?r=commissioni/addpraticaseduta',
        type:'post',
        data: { 'idcommissione':" . $idcommissione . ", 
                'idseduta':" . $idseduta . ", 
                'data':keys,
              ' _csrf' : '" . Yii::$app->request->getCsrfToken() . "'
               },
        //dataType:'json',
        success : function(data) {      
                alert('Tutto OK');
                },
        error : function(xhr, status, error){
                    var errorMessage = xhr.status + ' : ' + xhr.statusText
                    alert('Error - ' + errorMessage);
                }
     });
    
event.preventDefault();        

});

$('#sln').click(function() {
var str = $(this).text();
  if (str.trim()=='Apri Pannello Selezione Pratiche') {
    $(this).text('Chiudi Pannello Selezione Pratiche');
  } else {
    $(this).text('Apri Pannello Selezione Pratiche');
  };
});


", yii\web\View::POS_READY);
?>
<?php

/* @var $this yii\web\View */
$scommissione=strtoupper(\app\models\Commissioni::findOne($idcommissione)->tipologia->descrizione);
$sdata=date('d-m-Y',strtotime(\app\models\SeduteCommissioni::findOne($idseduta)->dataseduta));

$title='Pareri Pratiche Edilizie';
// Scegli Pratiche da Portare in Commissione
?>
<!--<div class="site-index">-->
<!--<button id="sln" class="btn btn-primary" style="margin-bottom:10px" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
    Apri Pannello Selezione Pratiche
</button>-->
    
<div class="card card-info collapsed-card" >
    <div class="card-header">
    <h3 class="card-title"><b>SELEZIONE PRATICHE DA SOTTOPORRE AD ESAME DA PARTE DELLA <?= $scommissione ?> DEL <?= $sdata ?></b></h3>
    <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse">
        <i class="fas fa-plus"></i>
        </button>
<!--        <button type="button" class="btn btn-tool" data-card-widget="remove">
        <i class="fas fa-times"></i>
        </button>-->
    </div>
    </div>
    <div class="card-body">
        <div class="row">
<!--            <div class="collapse" id="collapseExample">  -->
            <?php
            include('_selpratiche.php');    
            ?>
<!--            </div> -->
        </div>
    </div>
    <div class="card-footer">
        <?php
        echo \yii\helpers\Html::button('Aggiungi Pratiche Selezionate', ['class' => 'btn btn-primary','style'=>'float:right;','id'=>'selButton'])
        ?>
    </div>
    
</div> 

<div class="card card-info">
    <div class="card-header">
    <h3 class="card-title"><b>ELENCO PRATICHE DELLA <?= $scommissione ?> DEL <?= $sdata ?></b></h3>
    <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse">
        <i class="fas fa-minus"></i>
        </button>
<!--        <button type="button" class="btn btn-tool" data-card-widget="remove">
        <i class="fas fa-times"></i>
        </button>-->
    </div>
    </div>
    <div class="card-body">
        <div class="row">
<!--            <div class="collapse" id="collapseExample">  -->
            <?php
        include('_viewpareri.php');        
            ?>
<!--            </div> -->
        </div>
    </div>   
    <div class="card-footer">
        <?php
        echo \yii\helpers\Html::a('<i class="fas fa-redo"></i> Compila Verbale Commissione', ['commissioni/compilaverbale','idseduta'=>$idseduta], ['class' => 'btn btn-success']);
        ?>
    </div>
    
</div> 
    
    


