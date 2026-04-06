<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Modulistica */

$this->title = 'Nuovo Modello';
$this->params['breadcrumbs'][] = ['label' => 'Modulistica', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="modulistica-create">

<!--    <h1><?php // Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
