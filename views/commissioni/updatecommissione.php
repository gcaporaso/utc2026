<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ComponentiCommissioni */

$this->title = 'Modifica Commissione: ' . $model->idcommissioni;
$this->params['breadcrumbs'][] = ['label' => 'Commissioni', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idcommissioni, 'url' => ['view', 'id' => $model->idcommissioni]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="commissioni-update">

<!--    <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_formcommissione', [
        'model' => $model,
    ]) ?>

</div>
