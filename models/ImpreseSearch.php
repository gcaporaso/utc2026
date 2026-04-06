<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Imprese;

/**
 * ImpreseSearch represents the model behind the search form of `app\models\Imprese`.
 */
class ImpreseSearch extends Imprese
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['imprese_id', 'Cassa_Edile', 'Telefono', 'Cellulare'], 'integer'],
            [['RAGIONE_SOCIALE', 'COGNOME', 'NOME', 'DATA_NASCITA', 'PROVINCIA_NASCITA', 'NOME_COMPOSTO', 'INDIRIZZO', 'COMUNE_RESIDENZA', 'PROVINCIA_RESIDENZA', 'CODICE_FISCALE', 'PartitaIVA', 'EMAIL', 'PEC', 'INPS', 'INAIL'], 'safe'],
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
        $query = Imprese::find();

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
            'imprese_id' => $this->imprese_id,
            'DATA_NASCITA' => $this->DATA_NASCITA,
            'Cassa_Edile' => $this->Cassa_Edile,
            'Telefono' => $this->Telefono,
            'Cellulare' => $this->Cellulare,
        ]);

        $query->andFilterWhere(['like', 'RAGIONE_SOCIALE', $this->RAGIONE_SOCIALE])
            ->andFilterWhere(['like', 'COGNOME', $this->COGNOME])
            ->andFilterWhere(['like', 'NOME', $this->NOME])
            ->andFilterWhere(['like', 'PROVINCIA_NASCITA', $this->PROVINCIA_NASCITA])
            ->andFilterWhere(['like', 'NOME_COMPOSTO', $this->NOME_COMPOSTO])
            ->andFilterWhere(['like', 'INDIRIZZO', $this->INDIRIZZO])
            ->andFilterWhere(['like', 'COMUNE_RESIDENZA', $this->COMUNE_RESIDENZA])
            ->andFilterWhere(['like', 'PROVINCIA_RESIDENZA', $this->PROVINCIA_RESIDENZA])
            ->andFilterWhere(['like', 'CODICE_FISCALE', $this->CODICE_FISCALE])
            ->andFilterWhere(['like', 'PartitaIVA', $this->PartitaIVA])
            ->andFilterWhere(['like', 'EMAIL', $this->EMAIL])
            ->andFilterWhere(['like', 'PEC', $this->PEC])
            ->andFilterWhere(['like', 'INPS', $this->INPS])
            ->andFilterWhere(['like', 'INAIL', $this->INAIL]);

        return $dataProvider;
    }
}
