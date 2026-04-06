<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Cdu;

/**
 * SearchSismica represents the model behind the search form of `app\models\Sismica`.
 */
class CduSearch extends Cdu
{
    
    public $data_inizio_protocollo; //start time
    public $data_fine_protocollo; //End time
    public $richiedente;
    /**
     * {@inheritdoc}
     */
  /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idcdu'], 'safe'],
            [['NumeroProtocollo','DataProtocollo','idrich','sez1','foglio1','particelle1','richiedente'], 'safe'],
            [['sez2','foglio2','sez3','foglio3','sez4','foglio4'], 'string', 'max' => 45],
            [['particelle2','particelle3','particelle4'], 'string', 'max' => 255],
            [['tipodestinatario', 'esenzione'], 'required'],
            [['tipodestinatario', 'esenzione'], 'integer'],
            //[['particelle1','particelle2','particelle3','particelle4'], 'match', 'pattern' => '^([1-9]{1}[0-9]{0,3}[,]?)+$', 'message' => 'Devi specificare solo numeri separati da virgola'],
            ['esenzione','safe']
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
        $query = Cdu::find()
                ->indexBy('idcdu');
        $query->joinWith(['richiedente']);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            //$query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'idcdu' => $this->idcdu,
            'pratica_id' => $this->pratica_id,
            'DataProtocollo' => $this->DataProtocollo,
            'NumeroProtocollo' => $this->NumeroProtocollo,
            //'idrich' => $this->idrich,
            'sez1' => $this->sez1,
            'sez2' => $this->sez2,
            'sez3' => $this->sez3,
            'sez4' => $this->sez4,
            ['like','foglio1',$this->foglio1],
            ['like','foglio2',$this->foglio2],
            ['like','foglio3',$this->foglio3],
            ['like','foglio4',$this->foglio4],
            ['like','particelle1',$this->particelle1],
            ['like','particelle2',$this->particelle2],
            ['like','particelle3',$this->particelle3],
            ['like','particelle4',$this->particelle4],
            'tipodestinatario' => $this->tipodestinatario,
            'esenzione' => $this->esenzione,
        ]);

        
        
        
        
    /*  filtro sul Cognome, Nome o Denominazione del Committente
    *   il filtro agisce su Nome e Cognome se il Regime Giuridico è 1=Privato
    *   il filtro agisce su Denominazione se il Regime Giuridico è >1 ditta, Ente Pubblico
    */
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
        
        
        
        
//        $query->andFilterWhere(['like', 'DescrizioneLavori', $this->DescrizioneLavori])
//            ->andFilterWhere(['like', 'PROG_ARCH_ID', $this->PROG_ARCH_ID])
//            ->andFilterWhere(['like', 'DD_LL_ARCH_ID', $this->DD_LL_ARCH_ID])
//            ->andFilterWhere(['like', 'PROG_STR_ID', $this->PROG_STR_ID])
//            ->andFilterWhere(['like', 'DIR_LAV_STR_ID', $this->DIR_LAV_STR_ID])
//            ->andFilterWhere(['like', 'IMPRESA_ID', $this->IMPRESA_ID])
//            ->andFilterWhere(['like', 'COLLAUDATORE_ID', $this->COLLAUDATORE_ID])
//            ->andFilterWhere(['like', 'PAGATO_COMMISSIONE', $this->PAGATO_COMMISSIONE])
//            ->andFilterWhere(['like', 'CatastoFoglio', $this->CatastoFoglio])
//            ->andFilterWhere(['like', 'CatastoParticelle', $this->CatastoParticelle])
//            ->andFilterWhere(['like', 'CatastoSub', $this->CatastoSub])
//            ->andFilterWhere(['like', 'StrutturaAltro', $this->StrutturaAltro])
//            ->andFilterWhere(['like', 'DestinazioneDuso', $this->DestinazioneDuso]);

        return $dataProvider;
    }
}