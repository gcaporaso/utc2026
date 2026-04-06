<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ComponentiCommissioni;

/**
 * ComponentiSearch represents the model behind the search form of `app\models\ComponentiCommissioni`.
 */
class ComponentiSearch extends ComponentiCommissioni
{
     public $competenze;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idcomponenti_commissioni'], 'integer'],
            [['Cognome', 'Nome','ComuneResidenza','ProvinciaResidenza','IndirizzoResidenza','competenze'], 'safe'],
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
        $query = ComponentiCommissioni::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'idcomponenti_commissioni' => $this->idcomponenti_commissioni,
            //'titolo_id' => $this->titolo_id,
        ]);
        
        if ($this->competenze) {
            $query->andFilterWhere(['=', 'titolo_id', $this->competenze]);
        }

        if ($this->IndirizzoResidenza) {
            $query->andFilterWhere(['like', 'IndirizzoResidenza', $this->IndirizzoResidenza]);
        }
        
         if ($this->ComuneResidenza) {
            $query->andFilterWhere(['like', 'ComuneResidenza', $this->ComuneResidenza]);
        }
        
         if ($this->ProvinciaResidenza) {
            $query->andFilterWhere(['like', 'ProvinciaResidenza', $this->ProvinciaResidenza]);
        }
        
        
        $query->andFilterWhere(['like', 'Cognome', $this->Cognome])
            ->andFilterWhere(['like', 'Nome', $this->Nome]);

        return $dataProvider;
    }
}
