<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Edilizia */

$this->title = Yii::t('app', 'Inserimento Nuova Pratica Edilizia');
$this->params['breadcrumbs'][] = ['label' => 'Pratica', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="edilizia-create">

    

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
