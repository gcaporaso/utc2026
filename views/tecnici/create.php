<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Tecnici */

$this->title = Yii::t('app', 'Nuovo Tecnico');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tecnici'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tecnici-create">

<!--    <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
