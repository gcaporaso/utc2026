<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Committenti;

/**
 * CommittentiSearch represents the model behind the search form of `app\models\Committenti`.
 */
class CommittentiSearch extends Committenti
{
    
    public $data_inizio_nascita; //start time
    public $data_fine_nascita; //End time
    public $nomeCompleto;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['committenti_id', 'RegimeGiuridico_id', 'Cellulare'], 'integer'],
            [['Cognome', 'Nome', 'DataNascita', 'NOME_COMPOSTO', 'IndirizzoResidenza', 'ComuneResidenza', 'nomeCompleto',
                'ProvinciaResidenza', 'CodiceFiscale', 'PartitaIVA', 'Email', 'PEC', 'Telefono', 'Denominazione','Fax',
                'ComuneNascita', 'ProvinciaNascita', 'NumeroCivicoResidenza', 'CapResidenza','data_inizio_nascita','data_fine_nascita',], 'safe'],
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
        $query = Committenti::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pagesize' => 15 // in case you want a default pagesize
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'committenti_id' => $this->committenti_id,
            'RegimeGiuridico_id' => $this->RegimeGiuridico_id,
            'Cellulare' => $this->Cellulare,
        ]);

        $query->andFilterWhere(['like', 'Cognome', $this->Cognome])
            ->andFilterWhere(['like', 'Nome', $this->Nome])
            //->andFilterWhere(['like', 'DataNascita', $this->DataNascita])
            //->andFilterWhere(['like', 'NOME_COMPOSTO', $this->NOME_COMPOSTO])
            ->andFilterWhere(['like', 'IndirizzoResidenza', $this->IndirizzoResidenza])
            ->andFilterWhere(['like', 'ComuneResidenza', $this->ComuneResidenza])
            ->andFilterWhere(['like', 'ProvinciaResidenza', $this->ProvinciaResidenza])
            ->andFilterWhere(['like', 'CodiceFiscale', $this->CodiceFiscale])
            ->andFilterWhere(['like', 'PartitaIVA', $this->PartitaIVA])
            ->andFilterWhere(['like', 'Email', $this->Email])
            ->andFilterWhere(['like', 'PEC', $this->PEC])
            ->andFilterWhere(['like', 'Telefono', $this->Telefono])
            ->andFilterWhere(['like', 'Denominazione', $this->Denominazione])
            ->andFilterWhere(['like', 'ComuneNascita', $this->ComuneNascita])
            ->andFilterWhere(['like', 'ProvinciaNascita', $this->ProvinciaNascita])
            ->andFilterWhere(['like', 'NumeroCivicoResidenza', $this->NumeroCivicoResidenza])
            ->andFilterWhere(['like', 'CapResidenza', $this->CapResidenza]);

        
        
         // Filtro su Data Nascita
         if ($this->data_inizio_nascita && $this->data_fine_nascita) {
             $data_inizio_nascita =  date("Y-m-d", strtotime($this->data_inizio_nascita));
             $data_fine_nascita   = date("Y-m-d", strtotime($this->data_fine_nascita));
            $query->andFilterWhere(['between', 'DataNascita', $data_inizio_nascita, $data_fine_nascita]);
        }
        
        
        $query->andWhere('Cognome LIKE "%' . $this->nomeCompleto . '%" ' .
        'OR Nome LIKE "%' . $this->nomeCompleto . '%"' .
        'OR Denominazione LIKE "%' . $this->nomeCompleto . '%"'
    );
        
        return $dataProvider;
    }
}
