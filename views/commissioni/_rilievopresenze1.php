<?php
/* @var $this yii\web\View */
use kartik\dynagrid\DynaGrid;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\Html;
use mdm\admin\components\Helper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

//$layout = <<< HTML
//<div class="modal-dialog">
//    {summary}
//</div>
//
//{items}
//HTML;


$this->registerJs("

Alert('OK');
$('#aggiornaButton').on('click',function (event) {
Alert('aggiornaButton click');
var keys = $('#w6').yiiGridView('getSelectedRows');
    $.post({
        url : '/index.php?r=commissioni/aggiornapresenze',
        type:'post',
        data: { 'idseduta':". $idseduta . ",
                'data':keys,
              ' _csrf' : '" . Yii::$app->request->getCsrfToken() . "'
               },
        //dataType:'json',
        success : function(data) {      
                alert('Salvato! Tutto OK');
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

    

<?php
//echo DynaGrid::widget([
//   	'columns'=>[
//        ['attribute'=>'titolo',
//         'value'=>'titolo.abbr_titolo',
//         'hAlign'=>'center',
//         'width'=>'5%'
//        ],
//        ['attribute'=>'componenti',
//         'value'=>'componenti.Cognome',
//         'label'=>'Cognome',
//         //'hAlign'=>'center',
//        ],
//        ['attribute'=>'componenti',
//            'value'=>'componenti.Nome',
//            'label'=>'Nome',
//        ],
//    ['class'=>'kartik\grid\CheckboxColumn', 
//	'noWrap'=>true,
//        'width'=>'5%',
//        'checkboxOptions' => function ($SelProvider, $key, $index, $column) use ($presenti) {
////        $exp=$index-1;
//        //$RW=2;
//        $md1=pow(2,$index);
//        //$RW=$RW+1;
//        $presente= intval($presenti) & $md1;
//        //$presente=1; ///
//        if ($presente>0) {
//            $check=true;
//        }    else {
//            $check=false;
//        }
//           return ['checked' =>$check ];
//        },
//	 //'filterType'=>GridView::FILTER_CHECKBOX,
//	 'order'=>DynaGrid::ORDER_FIX_RIGHT,
//    ]],
//    ////////////////////////////////
//    ///// OPZIONI DELLA TABELLA ////
//    ///////////////////////////////
//    'storage'=>'db', //DynaGrid::TYPE_COOKIE,
//    'dbUpdateNameOnly'=>true,
//    'theme'=>'panel-info',
//    //'emptyCell'=>' ',
//    //'showPersonalize'=>false,
//    //'pjax'=>true,
//    'gridOptions'=>[
//        'dataProvider'=>$SelProvider,
//        //'filterModel'=>$Search,
//        //'layout' => $layout,
//        'showPageSummary'=>false,
//        
//        'panel'=>[
//		'heading'=>'<h3 class="panel-title"><b>RILIEVO PRESENZE<b></h3>',
//		'before' => false , // '<div style="padding-top: 7px;"><em>Elenco Componenti</em></div>',
//                'footer' => false,
//                'after' => Html::button('Annulla', ['class' => 'btn btn-success', 
//                            'id'=>'annullaButton',
//                            'data-dismiss'=>'modal',
//                            ]) .' '.
//                 Html::button('Salva', ['class' => 'btn btn-success', 
//                            'id'=>'aggiornaButton'
//                            ]),
//		],
//        
//	//'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
//	'headerRowOptions' => ['class' => 'kartik-sheet-style'],
//    	//'filterRowOptions' => ['class' => 'kartik-sheet-style'],
//	'responsive'=>true,
//    	'hover'=>true,
//    	'bordered' => true,
//    	'striped' => true,
//    	'condensed' => true,
//    	'persistResize' => false,
//        //'submitButtonOptions'=>true,
//    	//'toggleDataContainer' => ['class' => 'btn-group-sm'],
//        ], // gridOption
//    'options'=>['id'=>'dynagrid-pres81'] // a unique identifier is important
//]);
?> 

<div class="card card-default">
            <div class="card-header" >
                <h3 class="card-title">Rilievo Presenti Seduta</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                    </button>
                </div>    
            </div>   
<div class="card-body">
<?php    
echo yii\grid\GridView::widget([
        'dataProvider'=>$SelProvider,  
        
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
</div>
   <div class="card-footer">
       <div class="row">
           <div class="col-md-4">
            <?= Html::button('Annulla', ['class' => 'btn btn-success', 
                                 'id'=>'annullaButton',
                                 'data-dismiss'=>'modal',
                                 ]); ?>
           </div>
           <div  class="col-md-4">
            <?= Html::button('Salva', ['class' => 'btn btn-success', 
                                 'id'=>'aggiornaButton'
                                 ]); ?>
           </div>   
       </div>   
    </div>
</div>  
    