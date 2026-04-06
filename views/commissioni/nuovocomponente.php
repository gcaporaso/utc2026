<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ComponentiCommissioni */

$this->title = 'Nuovo Componente Commissioni';
$this->params['breadcrumbs'][] = ['label' => 'Componenti Commissioni', 'url' => ['componenti']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="componenti-commissioni-create">

<!--    <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_formcomponente', [
        'model' => $model,
    ]) ?>

</div>
