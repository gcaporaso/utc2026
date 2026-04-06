<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ComponentiCommissioni */

$this->title = 'Modifica Componenti Commissioni: ' . $model->idcomponenti_commissioni;
$this->params['breadcrumbs'][] = ['label' => 'Componenti Commissioni', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idcomponenti_commissioni, 'url' => ['view', 'id' => $model->idcomponenti_commissioni]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="componenti-commissioni-update">

<!--    <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_formcomponente', [
        'model' => $model,
    ]) ?>

</div>
