<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

?>
<div class="edilizia-view">

 <?php   
 
    // DetailView Attributes Configuration
$attributes = [
            [
                'attribute'=>'testoparere', 
                //'format'=>'raw', 
                //'value'=>'<kbd>'.$model->book_code.'</kbd>',
                //'valueColOptions'=>['style'=>'width:24%'], 
                'displayOnly'=>true,
                'label'=>'Parere Espresso'
            ],

];

// View file rendering the widget
echo DetailView::widget([
    'model' => $model,
    'attributes' => $attributes,
    'mode' => 'view',
    'bordered' => true,
    'striped' => false,
    'condensed' => true,
    'responsive' => true,
    //'heading'=>'{title}',
    //'hover' => $hover,
    'hAlign'=>'right',
    'vAlign'=>'top',
    'enableEditMode'=>false,
    //'fadeDelay'=>$fadeDelay,
    
//    'panel' => [
//        'type' => 'primary', 
//        'heading' => 'parere espresso dalla commissione ',
//        //'footer' => '<div class="text-center text-muted">.....</div>'
//    ],
    'container' => ['id'=>'kv-demo'],
    //'formOptions' => ['action' => Url::current(['#' => 'kv-demo'])] // your action to delete
]);
    
   ?> 


</div>
