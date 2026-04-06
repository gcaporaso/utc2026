<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ComponentiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Componenti Commissionis';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="componenti-commissioni-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Componenti Commissioni', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'idcomponenti_commissioni',
            'Cognome',
            'Nome',
            'Tipologia',
            'cellulare',
            'telefono',
            'email',
            'pec',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
