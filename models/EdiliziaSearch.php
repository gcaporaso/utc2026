<?php

namespace app\models;

//use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Edilizia;
//use app\models\Committenti;
//use yii\helpers\VarDumper;

/**
 * LavoriSearch represents the model behind the search form of `frontend\models\Lavori`.
 */
class EdiliziaSearch extends Edilizia
{
    
//    public function attributes()
//{
//    // add related fields to searchable attributes
//    return array_merge(parent::attributes(), ['nomeCompleto']);
//}
    //public $dataprotocollo_at_range; 
    public $data_inizio_protocollo; //start time
    public $data_fine_protocollo; //End time
    public $data_inizio_titolo; //start time
    public $data_fine_titolo; //End time
    public $richiedente;
    public $titolo;
    public $statoPratica;
    public $residuo;
    
    
    
    
    
    
//    public function attributes()
//{
//    // add related fields to searchable attributes
//    return array_merge(parent::attributes(), ['committente.nomeCompleto']);
//}
    
    
    
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
//        return [
//            [['idedilizia', 'NumeroProtocollo', 'DerogaUrbanistica','DerogaDensita',
//                'DerogaAltezza','DerogaDistanza','DerogaDestinazione', 'TipoRichiesta',
//                'idcommittente1'], 'integer'],
//            [['CatastaleFoglio','CatastaleParticella','CatastaleSub','DescrizioneIntervento',
//                'CatastoTipo','TitoloOneroso','DataProtocollo',
//                'IndirizzoImmobile','DataProtocollo','Sanatoria','Tipologia','TipoRichiesta','idcommittente2',
//                'idcommittente3','idcommittente4','idcommittente5',
//                'nomeCompleto','dataprotocollo_at_range'], 'safe'],
//            
//        ];
        
        return [
            // username and password are both required
            [['NumeroProtocollo','DataProtocollo','DescrizioneIntervento','CatastoTipo','TitoloOneroso','titolo','statoPratica',
                'id_committente','IndirizzoImmobile','id_titolo', 'NumeroTitolo','DataTitolo','richiedente',
                'Oneri_Costruzione','Oneri_Urbanizzazione','Oneri_Pagati','residuo'], 'safe'],
            [['data_inizio_protocollo','data_fine_protocollo','data_inizio_titolo','data_fine_titolo','Sanatoria','CatastoFoglio','CatastoParticella','CatastoSub'],'safe'],
            // rememberMe must be a boolean value
//            [['CatastaleFoglio','CatastaleParticella','CatastaleSub',
//                'DerogaUrbanistica','DerogaDensita','DerogaAltezza','DerogaDistanza','DerogaDestinazione',
//                'Sanatoria','Tipologia','TipoRichiesta','idcommittente2','idcommittente3','idcommittente4','idcommittente5'], 'safe'],
//                [['CatastaleFoglio','CatastaleParticella','CatastaleSub',
//            [['DerogaUrbanistica','DerogaDensita','DerogaAltezza','DerogaDistanza','DerogaDestinazione',
//                'Sanatoria','Tipologia','TipoRichiesta','committente_id2','committente_id3'], 'safe'],
            // password is validated by validatePassword()
            //['password', 'validatePassword'],
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
        //$query = Permessi::find();
		//$query = Permessi::getProva();
		$query = Edilizia::find()
                        ->indexBy('edilizia_id'); //->joinWith(['committente1'=> function($query) { $query->from(['committente1' => 'Committente']); }]); //->all();
                $query->joinWith(['richiedente']); 
		
         //print_r($params);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            //'sort'=> ['defaultOrder' => ['DataProtocollo'=>SORT_ASC]],
            'pagination' => [
            'pagesize' => 15 // default è 10
        ]
        ]);
        
        
        /**
     * Setup your sorting attributes
     * Note: This is setup before the $this->load($params) 
     * statement below
     */
     $dataProvider->setSort([
        'attributes' => [
            //'id',
            'DataProtocollo'=>[
                 'desc' => ['DataProtocollo' => SORT_DESC],
                 'asc' => ['DataProtocollo' => SORT_ASC],   
                 'default' => SORT_ASC
            ],
             'NumeroProtocollo'=>[
                 'desc' => ['NumeroProtocollo' => SORT_DESC],
                 'asc' => ['NumeroProtocollo' => SORT_ASC],   
                 'default' => SORT_ASC
            ],
            'NumeroTitolo'=>[
                 'desc' => ['NumeroTitolo' => SORT_DESC],
                 'asc' => ['NumeroTitolo' => SORT_ASC],   
                 'default' => SORT_ASC
            ],
            'DataTitolo'=>[
                 'desc' => ['DataTitolo' => SORT_DESC],
                 'asc' => ['DataTitolo' => SORT_ASC],   
                 'default' => SORT_ASC
            ],
            'residuo'=>[
                 'desc' => ['((Oneri_Costruzione+Oneri_Urbanizzazione)-Oneri_Pagati)' => SORT_DESC],
                 'asc' => ['((Oneri_Costruzione+Oneri_Urbanizzazione)-Oneri_Pagati)' => SORT_ASC],   
                 'default' => SORT_DESC
            ]
        ]
    ]);
        
        
        // Carico i parametri di ricerca
        $this->load($params);

        
//        if (!$this->validate()) {
//            // uncomment the following line if you do not want to return any records when validation fails
//            // $query->where('0=1');
//             $query->joinWith(['Committente']);
//            return $dataProvider;
//        }

        // grid filtering conditions
        $query->andFilterWhere([
         //   'idedilizia' => $this->idedilizia,
            'NumeroProtocollo' => $this->NumeroProtocollo,
          //  'DataProtocollo' => $this->DataProtocollo,
        //    'DerogaUrbanistica' => $this->DerogaUrbanistica,
        //    'TipoRichiesta'=>$this->TipoRichiesta,
            'TitoloOneroso'=>$this->TitoloOneroso,
            'Sanatoria'=>$this->Sanatoria,
            'CatastoFoglio'=> $this->CatastoFoglio,
            'CatastoParticella'=> $this->CatastoParticella,
            //'statoPratica'=>$this->statoPratica,
            //'IndirizzoImmobile'=>$this->IndirizzoImmobile,
            //'committente1'=>$this->committente1,
            
	        ]);

      
        
        // Ricerca pratiche per tipo Stato Pratica
        if ($this->statoPratica) {
            $query->andFilterWhere(['=', 'Stato_Pratica_id', $this->statoPratica]);
        }
        
        
        // Filtro su Data Protocollo
         if ($this->data_inizio_protocollo && $this->data_fine_protocollo) {
             $data_inizio_protocollo =  date("Y-m-d", strtotime($this->data_inizio_protocollo));
             $data_fine_protocollo   = date("Y-m-d", strtotime($this->data_fine_protocollo));
            $query->andFilterWhere(['between', 'DataProtocollo', $data_inizio_protocollo, $data_fine_protocollo]);
        }
        
        // Ricerca pratiche per data titolo
        if ($this->data_inizio_titolo && $this->data_fine_titolo) {
            //$null = new \yii\db\Expression('null');
            $data_inizio_titolo =  date("Y-m-d", strtotime($this->data_inizio_titolo));
            $data_fine_titolo   = date("Y-m-d", strtotime($this->data_fine_titolo));
            $query->andWhere(['between', 'DataTitolo', $data_inizio_titolo, $data_fine_titolo]);
            $query->andWhere(['not', ['DataTitolo'=>null]]);
        }
        
        // Ricerca pratiche per tipo di richiesta
        if ($this->titolo) {
            $query->andFilterWhere(['=', 'id_titolo', $this->titolo]);
        }
        
        
        
        
        $query->andFilterWhere(['like', 'IndirizzoImmobile', $this->IndirizzoImmobile]);
        
        $query->andFilterWhere(['like', 'DescrizioneIntervento', $this->DescrizioneIntervento]);
        //$query->andFilterWhere(['=', 'Titoloedilizio.idtitoloedilizio', $this->TipoRichiesta]);
//        $query->joinWith(['titolo' => function ($q) {
//        $q->where('Titoloedilizio.idtitoloedilizio =' . $this->TipoRichiesta);
//    }]);
       
    /*  filtro sul Cognome, Nome o Denominazione del Committente
    *   il filtro agisce su Nome e Cognome se il Regime Giuridico è 0=Privato
    *   il filtro agisce su Denominazione se il Regime Giuridico è 1=ditta 2=Ente Pubblico
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
        
      
        
          //Filtro per Residuo Oneri Concessori
        // 0 = nessun filtro (tutti i dati)
        // 1 = filtra quelli che hanno residuo oneri da pagare
        // 2 = filtra tutti quelli che hanno residuo 0
//        if ($this->residuo) {
//            if ($this->residuo == 1) {
//                $query->andFilterHaving(['>','residuo',0]);
//            } else if ($this->residuo == 2) {
//                $query->andFilterHaving(['=', 'residuo', 0]);
//            }
//        }
        
        
        
         // filter per nomeCompleto
//    $query->joinWith(['committente' => function ($q) {
//        $q->where('committenti.Cognome LIKE "%' . $this->nomeCompleto . '%"' .
//        'OR committenti.Nome LIKE "%' . $this->nomeCompleto . '%"' . 'OR committenti.Denominazione LIKE "%' . $this->nomeCompleto . '%"');
//    }]);
        //$query->andFilterWhere(['LIKE', 'committente1.nomeCompleto', $this->idcommittente1]); //getAttribute('committente1.nomeCompleto')]);
        //$query->andFilterWhere(['like', 'primocommittente.nomeCompleto', $this->primocommittente]);
        return $dataProvider;
    }
}
