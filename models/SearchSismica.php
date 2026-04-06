<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sismica;

/**
 * SearchSismica represents the model behind the search form of `app\models\Sismica`.
 */
class SearchSismica extends Sismica
{
    
    public $data_inizio_protocollo; //start time
    public $data_fine_protocollo; //End time
    public $data_inizio_titolo; //start time
    public $data_fine_titolo; //End time
    public $richiedente;
    public $titolo;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sismica_id', 'pratica_id', 'Protocollo', 'committenti_id', 'TipoDenuncia', 'cemento_armato', 'IstruttoreID', 'NumeroAUTORIZZAZIONE', 
                'TrasmissioneGenioCivile', 'PubblicazioneALBO', 'Ritiro', 'TipoTitolo', 'TipoCommittente', 'TipoProcedimento', 'Variante', 
                'Sopraelevazione', 'Ampliamento', 'Integrazione', 'CatastoTipo', 'ClassificazioneSismica', 'AbitatoConsolidare', 'InteresseStatale', 
                'InteresseRegionale', 'Articolo65', 'GeologoID'], 'integer'],
            [['DataProtocollo', 'DescrizioneLavori', 'PROG_ARCH_ID', 'DD_LL_ARCH_ID', 'PROG_STR_ID', 'DIR_LAV_STR_ID', 'IMPRESA_ID', 'COLLAUDATORE_ID', 
                'data_inizio_titolo','data_fine_titolo','data_inizio_protocollo','data_fine_protocollo','DataAUTORIZZAZIONE', 'DataVersamentoContributo',
                'PAGATO_COMMISSIONE', 'Inizio_Lavori', 'Fine_Lavori', 'Data_Strutture_Ultimate', 'Data_Collaudo', 'CatastoFoglio', 'CatastoParticelle', 
                'CatastoSub', 'StrutturaAltro', 'DestinazioneDuso','richiedente','ImportoPagata','TipoTitolo','titolo'], 'safe'],
            [['ImportoContributo', 'AccelerazioneAg','ImportoPagato'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Sismica::find()
        ->indexBy('sismica_id')
        ->joinWith(['richiedente']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
            'pagesize' => 15 // default è 10
        ]
        ]);

        $this->load($params);

//        if (!$this->validate()) {
//            // uncomment the following line if you do not want to return any records when validation fails
//            // $query->where('0=1');
//            return $dataProvider;
//        }

        // grid filtering conditions
        $query->andFilterWhere([
            'sismica_id' => $this->sismica_id,
            'pratica_id' => $this->pratica_id,
            'DataProtocollo' => $this->DataProtocollo,
            'Protocollo' => $this->Protocollo,
            'committenti_id' => $this->committenti_id,
            'TipoDenuncia' => $this->TipoDenuncia,
            'cemento_armato' => $this->cemento_armato,
            'IstruttoreID' => $this->IstruttoreID,
            'NumeroAUTORIZZAZIONE' => $this->NumeroAUTORIZZAZIONE,
            'DataAUTORIZZAZIONE' => $this->DataAUTORIZZAZIONE,
            'ImportoContributo' => $this->ImportoContributo,
            'DataVersamentoContributo' => $this->DataVersamentoContributo,
            'TrasmissioneGenioCivile' => $this->TrasmissioneGenioCivile,
            'PubblicazioneALBO' => $this->PubblicazioneALBO,
            'Ritiro' => $this->Ritiro,
            'Inizio_Lavori' => $this->Inizio_Lavori,
            'Fine_Lavori' => $this->Fine_Lavori,
            'Data_Strutture_Ultimate' => $this->Data_Strutture_Ultimate,
            'Data_Collaudo' => $this->Data_Collaudo,
            //'TipoTitolo' => $this->TipoTitolo,
            'TipoCommittente' => $this->TipoCommittente,
            'TipoProcedimento' => $this->TipoProcedimento,
            'Variante' => $this->Variante,
            'Sopraelevazione' => $this->Sopraelevazione,
            'Ampliamento' => $this->Ampliamento,
            'Integrazione' => $this->Integrazione,
            'CatastoTipo' => $this->CatastoTipo,
            'ClassificazioneSismica' => $this->ClassificazioneSismica,
            'AccelerazioneAg' => $this->AccelerazioneAg,
            'AbitatoConsolidare' => $this->AbitatoConsolidare,
            'InteresseStatale' => $this->InteresseStatale,
            'InteresseRegionale' => $this->InteresseRegionale,
            'Articolo65' => $this->Articolo65,
            'GeologoID' => $this->GeologoID,
            'ImportoPagato'=>$this->ImportoPagato
        ]);

        $query->andFilterWhere(['like', 'DescrizioneLavori', $this->DescrizioneLavori])
            ->andFilterWhere(['like', 'PROG_ARCH_ID', $this->PROG_ARCH_ID])
            ->andFilterWhere(['like', 'DD_LL_ARCH_ID', $this->DD_LL_ARCH_ID])
            ->andFilterWhere(['like', 'PROG_STR_ID', $this->PROG_STR_ID])
            ->andFilterWhere(['like', 'DIR_LAV_STR_ID', $this->DIR_LAV_STR_ID])
            ->andFilterWhere(['like', 'IMPRESA_ID', $this->IMPRESA_ID])
            ->andFilterWhere(['like', 'COLLAUDATORE_ID', $this->COLLAUDATORE_ID])
            ->andFilterWhere(['like', 'PAGATO_COMMISSIONE', $this->PAGATO_COMMISSIONE])
            ->andFilterWhere(['like', 'CatastoFoglio', $this->CatastoFoglio])
            ->andFilterWhere(['like', 'CatastoParticelle', $this->CatastoParticelle])
            ->andFilterWhere(['like', 'CatastoSub', $this->CatastoSub])
            ->andFilterWhere(['like', 'StrutturaAltro', $this->StrutturaAltro])
            ->andFilterWhere(['like', 'DestinazioneDuso', $this->DestinazioneDuso]);

         if ($this->richiedente) {
            $ric= explode(" ", $this->richiedente);

            //echo count($ric);
            if (count($ric)>2) {
            $query->orFilterWhere(['and','committenti.Cognome like "%'. $ric[0]. ' '. $ric[1]. '%"','committenti.Cognome like "%'. $ric[2]. '%"']);
            $query->orFilterWhere(['and','committenti.Cognome like "%'. $ric[0]. '%"','committenti.Nome like "%'. $ric[1]. ' ' . $ric[2] . '%"']);
    //        $query->andFilterWhere(['and', 'committenti.Nome like "%' . $ric[1]. '%"','committenti.RegimeGiuridico_id=1']);
    //        $query->andFilterWhere(['and', 'committenti.Nome like "%' . $ric[2]. '%"','committenti.RegimeGiuridico_id=1']);
    //        $query->orFilterWhere(['and','committenti.Denominazione like "%' . $ric[0] . '%"','committenti.RegimeGiuridico_id>1']);
            } elseif (count($ric)>1) {
            $query->andFilterWhere(['and','committenti.Cognome like "%'. $ric[0]. '%"','committenti.RegimeGiuridico_id=1']);
            $query->andFilterWhere(['and', 'committenti.Nome like "%' . $ric[1]. '%"','committenti.RegimeGiuridico_id=1']);
            $query->orFilterWhere(['and','committenti.Denominazione like "%' . $ric[0] . '%"','committenti.RegimeGiuridico_id>1']);
            //$query->andFilterWhere(['and','committenti.Denominazione like "' . $ric[0] . '"','committenti.RegimeGiuridico_id>0']);
            } elseif (count($ric)>0) {
            $query->orFilterWhere(['and','committenti.Cognome like "%'. $ric[0]. '%"','committenti.RegimeGiuridico_id=1']);
            $query->orFilterWhere(['and','committenti.Denominazione like "%' . $ric[0] . '%"','committenti.RegimeGiuridico_id>1']);
            $query->orFilterWhere(['and', 'committenti.Nome like "%' . $ric[0]. '%"','committenti.RegimeGiuridico_id=1']);
            }
        }
        
        
          // Ricerca pratiche per tipo di titolo
        if ($this->titolo) {
            $query->andFilterWhere(['=', 'TipoTitolo', $this->titolo]);
        }
        
        return $dataProvider;
    }
}
