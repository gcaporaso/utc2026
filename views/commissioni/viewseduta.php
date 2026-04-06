<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SeduteCommissioni */

$this->title = $model->idsedute_commissioni;
$this->params['breadcrumbs'][] = ['label' => 'Sedute Commissionis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="sedute-commissioni-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->idsedute_commissioni], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->idsedute_commissioni], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idsedute_commissioni',
            'commissione_id',
            'dataseduta',
            'statoseduta',
            'presenze',
            'orarioconvocazione',
            'orarioinizio',
            'orariofine',
            'numero',
        ],
    ]) ?>

</div>
