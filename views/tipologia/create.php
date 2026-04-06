<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TipologiaEdilizia */

$this->title = Yii::t('app', 'Create Tipologia Edilizia');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tipologia Edilizias'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tipologia-edilizia-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
