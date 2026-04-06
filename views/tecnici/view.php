<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Tecnici */

$this->title = $model->tecnici_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tecnicis'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="tecnici-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->tecnici_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->tecnici_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'tecnici_id',
            'COGNOME',
            'NOME',
            'COMUNE_NASCITA',
            'PROVINCIA_NASCITA',
            'DATA_NASCITA',
            'ALBO',
            'PROVINCIA_ALBO',
            'NUMERO_ISCRIZIONE',
            'NOME_COMPOSTO',
            'INDIRIZZO',
            'COMUNE_RESIDENZA',
            'PROVINCIA_RESIDENZA',
            'CODICE_FISCALE',
            'P_IVA',
            'TELEFONO',
            'FAX',
            'CELLULARE',
            'EMAIL:email',
            'PEC',
            'Denominazione',
        ],
    ]) ?>

</div>
