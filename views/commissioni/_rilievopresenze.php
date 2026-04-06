<?php
/* @var $this yii\web\View */
use kartik\dynagrid\DynaGrid;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\Html;
use mdm\admin\components\Helper;
use yii\helpers\Url;

$layout = <<< HTML
<div class="modal-dialog">
    {summary}
</div>
<div class="clearfix"></div>
{items}
HTML;


$this->registerJs("


$('#aggiornaButton').on('click',function (event) {

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
                alert('Tutto OK');
                },
        error : function(xhr, status, error){
                    var errorMessage = xhr.status + ' : ' + xhr.statusText
                    alert('Error - ' + errorMessage);
                }
     });
 $('.modal').modal('hide'); //hide popup     
event.preventDefault();        

});


", yii\web\View::POS_READY);



?>

<?php
echo DynaGrid::widget([
   	'columns'=>[
        ['attribute'=>'titolo',
         'value'=>'titolo.abbr_titolo',
         'hAlign'=>'center',
         'width'=>'5%'
        ],
        ['attribute'=>'componenti',
         'value'=>'componenti.Cognome',
         'label'=>'Cognome',
         //'hAlign'=>'center',
        ],
        ['attribute'=>'componenti',
            'value'=>'componenti.Nome',
            'label'=>'Nome',
        ],
    ['class'=>'kartik\grid\CheckboxColumn', 
	'noWrap'=>true,
        'width'=>'5%',
        'checkboxOptions' => function ($seduta, $key, $index, $column) {
        //$exp=$index-1;
        //pow(2,$exp)
        if (isset($seduta)) {
            $check=true;
        }    else {
            $check=false;
        }
           return ['checked' =>$check ];
        },
	 //'filterType'=>GridView::FILTER_CHECKBOX,
	 'order'=>DynaGrid::ORDER_FIX_RIGHT,
    ]],
    ////////////////////////////////
    ///// OPZIONI DELLA TABELLA ////
    ///////////////////////////////
    'storage'=>'db', //DynaGrid::TYPE_COOKIE,
    'dbUpdateNameOnly'=>true,
    'theme'=>'panel-info',
    //'emptyCell'=>' ',
    //'showPersonalize'=>false,
    //'pjax'=>true,
    'gridOptions'=>[
        'dataProvider'=>$SelProvider,
        //'filterModel'=>$Search,
        'layout' => $layout,
        'showPageSummary'=>false,
        
        'panel'=>[
		'heading'=>'<h3 class="panel-title"><b>RILIEVO PRESENZE<b></h3>',
		'before' => false , // '<div style="padding-top: 7px;"><em>Elenco Componenti</em></div>',
                'after' => Html::button('Annulla', ['class' => 'btn btn-success', 
                            'id'=>'annullaButton',
                            'data-dismiss'=>'modal',
                            ]) .' '.
                 Html::button('Salva', ['class' => 'btn btn-success', 
                            'id'=>'aggiornaButton'
                            ]),
		],
	//'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
	'headerRowOptions' => ['class' => 'kartik-sheet-style'],
    	//'filterRowOptions' => ['class' => 'kartik-sheet-style'],
	'responsive'=>true,
    	'hover'=>true,
    	'bordered' => true,
    	'striped' => true,
    	'condensed' => true,
    	'persistResize' => false,
        //'submitButtonOptions'=>true,
    	//'toggleDataContainer' => ['class' => 'btn-group-sm'],
        ], // gridOption
    'options'=>['id'=>'dynagrid-pres81'] // a unique identifier is important
]);
 
 ?>
