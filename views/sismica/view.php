<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sismica */

$this->title = $model->sismica_id;
$this->params['breadcrumbs'][] = ['label' => 'Sismicas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="sismica-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->sismica_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->sismica_id], [
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
            'sismica_id',
            'pratica_id',
            'DataProtocollo',
            'Protocollo',
            'DescrizioneLavori',
            'committenti_id',
            'TipoDenuncia',
            'StrutturaPortante',
            'PROG_ARCH_ID',
            'DD_LL_ARCH_ID',
            'PROG_STR_ID',
            'DIR_LAV_STR_ID',
            'IMPRESA_ID',
            'COLLAUDATORE_ID',
            'IstruttoreID',
            'ISTRUTTORE',
            'NumeroAUTORIZZAZIONE',
            'DataAUTORIZZAZIONE',
            'ImportoContributo',
            'DataVersamentoContributo',
            'PAGATO_COMMISSIONE',
            'TrasmissioneGenioCivile',
            'PubblicazioneALBO',
            'Ritiro',
            'Inizio_Lavori',
            'Fine_Lavori',
            'Data_Strutture_Ultimate',
            'Data_Collaudo',
            'TipoTitolo',
            'TipoCommittente',
            'TipoProcedimento',
            'Variante',
            'Sopraelevazione',
            'Ampliamento',
            'Integrazione',
            'CatastoTipo',
            'CatastoFoglio',
            'CatastoParticelle',
            'CatastoSub',
            'StrutturaAltro',
            'ClassificazioneSismica',
            'AccelerazioneAg',
            'AbitatoConsolidare',
            'InteresseStatale',
            'InteresseRegionale',
            'Articolo65',
            'GeologoID',
            'DestinazioneDuso',
        ],
    ]) ?>

</div>
