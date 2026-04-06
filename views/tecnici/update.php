<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Tecnici */

$this->title = Yii::t('app', 'Modifica dati tecnico n. {name}', [
    'name' => $model->tecnici_id,
]);
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tecnicis'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->tecnici_id, 'url' => ['view', 'id' => $model->tecnici_id]];
//$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tecnici-update">

    

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
