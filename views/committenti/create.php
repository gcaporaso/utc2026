<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Committenti */

$this->title = Yii::t('app', 'Nuovo Richiedente');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Richiedenti'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="committenti-create">

<!--    <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
