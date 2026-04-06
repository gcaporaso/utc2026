<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SearchSismica */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sismicas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sismica-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Sismica', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'sismica_id',
            'pratica_id',
            'DataProtocollo',
            'Protocollo',
            'DescrizioneLavori',
            //'committenti_id',
            //'TipoDenuncia',
            //'StrutturaPortante',
            //'PROG_ARCH_ID',
            //'DD_LL_ARCH_ID',
            //'PROG_STR_ID',
            //'DIR_LAV_STR_ID',
            //'IMPRESA_ID',
            //'COLLAUDATORE_ID',
            //'IstruttoreID',
            //'ISTRUTTORE',
            //'NumeroAUTORIZZAZIONE',
            //'DataAUTORIZZAZIONE',
            //'ImportoContributo',
            //'DataVersamentoContributo',
            //'PAGATO_COMMISSIONE',
            //'TrasmissioneGenioCivile',
            //'PubblicazioneALBO',
            //'Ritiro',
            //'Inizio_Lavori',
            //'Fine_Lavori',
            //'Data_Strutture_Ultimate',
            //'Data_Collaudo',
            //'TipoTitolo',
            //'TipoCommittente',
            //'TipoProcedimento',
            //'Variante',
            //'Sopraelevazione',
            //'Ampliamento',
            //'Integrazione',
            //'CatastoTipo',
            //'CatastoFoglio',
            //'CatastoParticelle',
            //'CatastoSub',
            //'StrutturaAltro',
            //'ClassificazioneSismica',
            //'AccelerazioneAg',
            //'AbitatoConsolidare',
            //'InteresseStatale',
            //'InteresseRegionale',
            //'Articolo65',
            //'GeologoID',
            //'DestinazioneDuso',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
