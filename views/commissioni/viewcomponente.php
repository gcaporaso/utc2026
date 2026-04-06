<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ComponentiCommissioni */

$this->title = $model->idcomponenti_commissioni;
$this->params['breadcrumbs'][] = ['label' => 'Componenti Commissioni', 'url' => ['componenti']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="componenti-commissioni-view">

<!--    <h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('Modifica', ['updatecomponente', 'idcomponente' => $model->idcomponenti_commissioni], ['class' => 'btn btn-primary']) ?>
        <?php // Html::a('Cancella', ['deleteComponente', 'idcomponente' => $model->idcomponenti_commissioni], [
//            'class' => 'btn btn-danger',
//            'data' => [
//                'confirm' => 'Sei sicuro di voler cancellare questo componente?',
//                'method' => 'post',
//            ],
        // ]) 
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idcomponenti_commissioni',
            'Cognome',
            'Nome',
            'DataNascita',
            'IndirizzoResidenza',
            'ComuneResidenza',
            'ProvinciaResidenza',
            'Tipologia',
        ],
    ]) ?>

</div>
