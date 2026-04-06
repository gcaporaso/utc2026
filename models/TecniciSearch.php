<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Tecnici;

/**
 * TecniciSearch represents the model behind the search form of `app\models\Tecnici`.
 */
class TecniciSearch extends Tecnici
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tecnici_id'], 'integer'],
            [['COGNOME', 'NOME', 'COMUNE_NASCITA', 'PROVINCIA_NASCITA', 'DATA_NASCITA', 'ALBO', 'PROVINCIA_ALBO', 'NUMERO_ISCRIZIONE', 'NOME_COMPOSTO', 'INDIRIZZO', 'COMUNE_RESIDENZA', 'PROVINCIA_RESIDENZA', 'CODICE_FISCALE', 'P_IVA', 'TELEFONO', 'FAX', 'CELLULARE', 'EMAIL', 'PEC', 'Denominazione'], 'safe'],
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
        $query = Tecnici::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pagesize' => 15 // default è 10
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
            'tecnici_id' => $this->tecnici_id,
            'DATA_NASCITA' => $this->DATA_NASCITA,
        ]);

        $query->andFilterWhere(['like', 'COGNOME', $this->COGNOME])
            ->andFilterWhere(['like', 'NOME', $this->NOME])
            ->andFilterWhere(['like', 'COMUNE_NASCITA', $this->COMUNE_NASCITA])
            ->andFilterWhere(['like', 'PROVINCIA_NASCITA', $this->PROVINCIA_NASCITA])
            ->andFilterWhere(['like', 'ALBO', $this->ALBO])
            ->andFilterWhere(['like', 'PROVINCIA_ALBO', $this->PROVINCIA_ALBO])
            ->andFilterWhere(['like', 'NUMERO_ISCRIZIONE', $this->NUMERO_ISCRIZIONE])
            ->andFilterWhere(['like', 'NOME_COMPOSTO', $this->NOME_COMPOSTO])
            ->andFilterWhere(['like', 'INDIRIZZO', $this->INDIRIZZO])
            ->andFilterWhere(['like', 'COMUNE_RESIDENZA', $this->COMUNE_RESIDENZA])
            ->andFilterWhere(['like', 'PROVINCIA_RESIDENZA', $this->PROVINCIA_RESIDENZA])
            ->andFilterWhere(['like', 'CODICE_FISCALE', $this->CODICE_FISCALE])
            ->andFilterWhere(['like', 'P_IVA', $this->P_IVA])
            ->andFilterWhere(['like', 'TELEFONO', $this->TELEFONO])
            ->andFilterWhere(['like', 'FAX', $this->FAX])
            ->andFilterWhere(['like', 'CELLULARE', $this->CELLULARE])
            ->andFilterWhere(['like', 'EMAIL', $this->EMAIL])
            ->andFilterWhere(['like', 'PEC', $this->PEC])
            ->andFilterWhere(['like', 'Denominazione', $this->Denominazione]);

        return $dataProvider;
    }
}
