<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SeduteCommissioni */

$this->title = 'Modifica Seduta Commissione: ' . $model->idsedute_commissioni;
//$this->params['breadcrumbs'][] = ['label' => 'Sedute Commissioni', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->idsedute_commissioni, 'url' => ['view', 'id' => $model->idsedute_commissioni]];
//
//$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sedute-commissioni-update">
<?=    
$this->render('_formseduta', [
                'model' => $model,
                'idcommissione'=>$idcommissione,
                'idseduta'=>$idseduta
       ])
?>
        
</div>