<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ComponentiCommissioni */

$this->title = 'Nuova Commissione';
$this->params['breadcrumbs'][] = ['label' => 'Commissioni', 'url' => ['commissioni']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="commissioni-create">

<!--    <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_formcommissione', [
        'model' => $model,
    ]) ?>

</div>
