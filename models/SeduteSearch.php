<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ComponentiCommissioni;

/**
 * ComponentiSearch represents the model behind the search form of `app\models\ComponentiCommissioni`.
 */
class SeduteSearch extends SeduteCommissioni
{
    
    
    public $data_inizio_seduta; //start time
    public $data_fine_seduta; //End time
    public $stato;
    
    
    
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
             [['commissione_id', 'dataseduta','statoseduta','presenze','orarioconvocazione',
                 'orarioinizio','orariofine','stato'], 'safe'],
            
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
    public function search($params,$idcommissione)
    {
        $query = SeduteCommissioni::find()
                ->joinWith(['commissione'])
                //->where(['commissione_id'=>$idcommissione]);
                ->where(['Tipo'=>$idcommissione]);

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
            //'commissione_id', 'dataseduta','statoseduta','presenze','orarioconvocazione', 'orarioinizio','orariofine'
            //'commissione_id'=>$idcommissione,
            'dataseduta' => $this->dataseduta,
            //'statoseduta' => $this->statoseduta,
            'presenze' => $this->presenze,
            'orarioconvocazione' => $this->orarioconvocazione,
            'orarioinizio'=> $this->orarioinizio,
            'orariofine'=> $this->orariofine,
        ]);


        // Ricerca pratiche per tipo Stato Pratica
        if ($this->stato) {
            $query->andFilterWhere(['=', 'statoseduta', $this->stato]);
        }
        
        
        
        
        return $dataProvider;
    }
}
