<?php
/* @var $this yii\web\View */
//use kartik\dynagrid\DynaGrid;
//use kartik\grid\GridView;
//use kartik\select2\Select2;
use yii\helpers\Html;
//use mdm\admin\components\Helper;
//use yii\helpers\Url;
//use yii\widgets\ActiveForm;

//$layout = <<< HTML
//<div class="modal-dialog">
//    {summary}
//</div>
//
//{items}
//HTML;


$this->registerJs("

//alert('OK');
$('#aggiornaButton').on('click',function (event) {
//alert('aggiornaButton click');
var keys = $('#PresenzeGridView').yiiGridView('getSelectedRows');
    $.post({
        url : '/index.php?r=commissioni/aggiornapresenze',
        type:'post',
        data: { 'idseduta':". $idseduta . ",
                'data':keys,
              ' _csrf' : '" . Yii::$app->request->getCsrfToken() . "'
               },
        //dataType:'json',
        success : function(data) {      
                //alert('Salvato! Tutto OK');
                $('.modal').modal('hide'); //hide popup  
                },
        error : function(xhr, status, error){
                    var errorMessage = xhr.status + ' : ' + xhr.statusText
                    alert('Error - ' + errorMessage);
                }
     });
    
event.preventDefault();        

});

", yii\web\View::POS_READY);



?>


<!--<div class="card-default">
        <div class="card-header" >
                <h3 class="card-title">Rilievo Presenti Seduta</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                    </button>
                </div>    
        </div>   
        <div class="card-body">-->
       

    <?php    
    echo yii\grid\GridView::widget([
        'dataProvider'=>$SelProvider,  
        'id'=>'PresenzeGridView',
   	'columns'=>
        [
            ['attribute'=>'titolo',
             'value'=>'titolo.abbr_titolo',
            ],
            ['attribute'=>'componenti',
             'value'=>'componenti.Cognome',
             'label'=>'Cognome',
            ],
            ['attribute'=>'componenti',
                'value'=>'componenti.Nome',
                'label'=>'Nome',
            ],
            ['class'=>'yii\grid\CheckboxColumn', 
//            'noWrap'=>true,
            'checkboxOptions' => function ($SelProvider, $key, $index, $column) use ($presenti) {
                $md1=pow(2,$index);
                $presente= intval($presenti) & $md1;
                if ($presente>0) {
                    $check=true;
                }    else {
                    $check=false;
                }
                   return ['checked' =>$check ];
            },
            ]
        ],
    ]);        
     ?>
