<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Commissioni;

/**
 * ComponentiSearch represents the model behind the search form of `app\models\ComponentiCommissioni`.
 */
class CommissioniSearch extends Commissioni
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
           [['idcommissioni','Descrizione', 'NumeroDelibera', 'DataDelibera','tipologia'], 'safe'],
            [['NumeroDelibera','Tipo'], 'integer'],
            [['DataDelibera'], 'safe'],
            [['Descrizione'], 'string', 'max' => 65],
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
        $query = Commissioni::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'idcommissioni' => $this->idcommissioni,
            'NumeroDelibera' => $this->NumeroDelibera,
            'DataDelibera' => $this->DataDelibera,
            'Tipo'=>$this->Tipo
        ]);

        return $dataProvider;
    }
}
