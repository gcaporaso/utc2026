<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\View $model */

$this->title = 'Dettagli del Progetto';
$this->params['breadcrumbs'][] = ['label' => 'Views', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="view-details">

    <h1><?= Html::encode($this->title) ?></h1>

    <h4>View</h4>
    <hr />

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'description',
            'type',
            'creationDate',
            [
                'attribute' => 'creatorUser',
                'value' => $model->creatorUser ? $model->creatorUser->username : null, 
                // oppure 'email' se preferisci mostrare la mail
            ],
            [
                'attribute' => 'project',
                'value' => $model->project ? $model->project->Description : null,
            ],
        ],
    ]) ?>

    <p>
        <?= Html::a('Edit', ['update', 'id' => $model->Id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Back to List', ['index'], ['class' => 'btn btn-secondary']) ?>
    </p>

</div>
