<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SeduteCommissioni */

$this->title = 'Nuova Seduta Commissione';
//$this->params['breadcrumbs'][] = ['label' => 'Sedute Commissioni', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sedute-commissioni-create">

<!--    <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_formseduta', [
        'model' => $model,
        'idtipocommissione'=>$idtipocommissione
    ]) ?>

</div>
