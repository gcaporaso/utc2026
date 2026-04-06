<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Imprese */

$this->title = Yii::t('app', 'Nuova Impresa');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Imprese'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="imprese-create">

<!--    <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
