<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Modulistica */

//$this->title = 'Modifica Modello ID: ' . $model->idmodulistica;
//$this->params['breadcrumbs'][] = ['label' => 'Modulisticas', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->idmodulistica, 'url' => ['view', 'id' => $model->idmodulistica]];
//$this->params['breadcrumbs'][] = 'Modifica';
?>
<!--<div class="modulistica-update">-->

    

    <?= $this->render('_form_update', [
        'model' => $model, 'id'=>$id
    ]) ?>

<!--</div>-->
