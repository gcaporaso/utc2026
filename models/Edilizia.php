<?php

namespace app\models;

//use Yii;
//use yii\base\Model;
use app\models\Committenti;
use app\models\TitoloEdilizio;
use app\models\Tecnici;
use app\models\Paesistica;
use yii\db\ActiveRecord;
use Yii;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
//class Lavori extends Model
//{
	
	
//	namespace app\models;



class Edilizia extends ActiveRecord
{

	 public static function tableName()
    {
        return '{{%edilizia}}';
    }
	
//public $OneriResidui;
//public $richiedente;
    //public $dataprotocollo_at_range;
  //  public $username;
//    public $password;
//    public $rememberMe = true;
//
//    private $_user = false;
//
//
//    *
//     * @return array the validation rules.
//     
    
    public $Anno;
    
    public function rules()
    {
        return [
            // username and password are both required
            [['NumeroProtocollo','DataProtocollo','DescrizioneIntervento', 
                'id_committente','id_titolo', ], 'required'],
            [['Sanatoria','CatastoTipo','CatastoFoglio','Stato_Pratica_id','TitoloOneroso','CatastoParticella',
              'richiedente','CatastoSub','titolo','NumeroTitolo','DataTitolo', 'COMPATIBILITA_PAESISTICA','AutPaesistica',
                'Data_Inizio_Lavori','Data_Fine_Lavori', 'IndirizzoImmobile',
                'PROGETTISTA_ARC_ID','DIR_LAV_ARCH_ID','PROGETTISTA_STR_ID','DIR_LAV_STR_ID','Collaudatore_id','IMPRESA_ID'],'safe'],
            ['Latitudine','number','max'=>41.16,'min'=>41.08,'message' => 'Deve essere compresa tra 41.080000 e 41.160000'],
            ['Longitudine','number','max'=>14.7,'min'=>14.6,'message' => 'Deve essere compresa tra 14.600000 e 14.700000'],
            [['Oneri_Costruzione','Oneri_Urbanizzazione'],'number','min'=>0.00,'max'=>50000.00],
            ['Oneri_Pagati','number','min'=>0.00,'max'=>50000.00],
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


	
//	 public function getImpreseesecutrici()
//    {
//        return $this->hasMany(Impresaesecutrice::className(), [ 'idPratica' => 'idedilizia']);
//     //       ->where('CodiceNatura = :cnatura', [':cnatura' => $natura]);
//    }

// public function getTecniciincaricati()
//    {
//        return $this->hasMany(Tecnicoincaricato::className(), [ 'idPratica' => 'idedilizia']);
//     //       ->where('CodiceNatura = :cnatura', [':cnatura' => $natura]);
//    }


//public function getPareri()
//    {
//        return $this->hasMany(Pareri::className(), [ 'idPratica' => 'idedilizia']);
//     //       ->where('CodiceNatura = :cnatura', [':cnatura' => $natura]);
//    }


//public function getElaboratiprogettuali()
//    {
//        return $this->hasMany(Elaboratiprogettuali::className(), [ 'idPratica' => 'idedilizia']);
//     //       ->where('CodiceNatura = :cnatura', [':cnatura' => $natura]);
//    }
	
//	public function getDocumentiedilizia()
//    {
//        return $this->hasMany(Documentiedilizia::className(), [ 'idPratica' => 'idedilizia']);
//     //       ->where('CodiceNatura = :cnatura', [':cnatura' => $natura]);
//    }


  public function getTipologia(){

        // customer's items, matching 'id' column of `Item` to 'item_id' in OrderItem
        return $this->hasOne(TipologiaEdilizia::className(), ['idtipologia' => 'tipologia_id']);
                    
    }

  
	
	public function getAllegati()
    {
        return $this->hasMany(AllegatiFile::className(), [ 'id_pratica' => 'edilizia_id']);
     //       ->where('CodiceNatura = :cnatura', [':cnatura' => $natura]);
    }
	

    
    
    
    
    

    
    
    
    
    
    
    
    
    
    
//	public function getCommittenti()
//	{
//    return $this->hasOne(Committente::className(), ['idcommittente' => 'idcommittente'])
//        ->viaTable('titolare', ['idpratica' => 'idedilizia']);
//
//	}
	
	

//    public function getPrimocommittente(){
//
//        // customer's items, matching 'id' column of `Item` to 'item_id' in OrderItem
//        return $this->hasOne(Committente::className(), ['committenti_id' => 'id_committente']);
//                    
//    }

      public function getResiduo(){

        // customer's items, matching 'id' column of `Item` to 'item_id' in OrderItem
          $onc=isset($this->Oneri_Costruzione)? floatval($this->Oneri_Costruzione):0;
          $onu=isset($this->Oneri_Urbanizzazione)? floatval($this->Oneri_Urbanizzazione):0;
          $pag=isset($this->Oneri_Pagati)? floatval($this->Oneri_Pagati):0;
            return $onc+$onu-$pag;      
        
                    
    }
    
   
    
     public function getStatoPratica(){

        // customer's items, matching 'id' column of `Item` to 'item_id' in OrderItem
        return $this->hasOne(StatoEdilizia::className(), ['idstato_edilizia' => 'Stato_Pratica_id']);
                    
    }
    
    public function getTitolo(){

        // customer's items, matching 'id' column of `Item` to 'item_id' in OrderItem
        return $this->hasOne(TitoloEdilizio::className(), ['titoli_id' => 'id_titolo']);
                    
    }

    public function getRichiedente()
    {
        return $this->hasOne(Committenti::className(), [ 'committenti_id' => 'id_committente']);
     //       ->where('CodiceNatura = :cnatura', [':cnatura' => $natura]);
    }

    
   public function getProgettistaArchitettonico()
    {
        return $this->hasOne(Tecnici::className(), [ 'tecnici_id' => 'PROGETTISTA_ARC_ID']);
     //       ->where('CodiceNatura = :cnatura', [':cnatura' => $natura]);
    }   
    
    public function getProgettistaStrutturale()
    {
        return $this->hasOne(Tecnici::className(), [ 'tecnici_id' => 'PROGETTISTA_STR_ID']);
     //       ->where('CodiceNatura = :cnatura', [':cnatura' => $natura]);
    }  
    
    
    public function getDirettoreArchitettonico()
    {
        return $this->hasOne(Tecnici::className(), [ 'tecnici_id' => 'DIR_LAV_ARCH_ID']);
     //       ->where('CodiceNatura = :cnatura', [':cnatura' => $natura]);
    }  
    
    
    
    public function getDirettoreStrutturale()
    {
        return $this->hasOne(Tecnici::className(), [ 'tecnici_id' => 'DIR_LAV_STR_ID']);
     //       ->where('CodiceNatura = :cnatura', [':cnatura' => $natura]);
    }  
    
     public function getCollaudatore()
    {
        return $this->hasOne(Tecnici::className(), [ 'tecnici_id' => 'Collaudatore_id']);
     //       ->where('CodiceNatura = :cnatura', [':cnatura' => $natura]);
    }  
    
    public function getImpresa()
    {
        return $this->hasOne(Imprese::className(), [ 'imprese_id' => 'IMPRESA_ID']);
     //       ->where('CodiceNatura = :cnatura', [':cnatura' => $natura]);
    }  
    
    
    
    
    
//        /* Getter for country name */
//        public function getEdiliziaNome() {
//            return $committente->nomeCompleto;
//        }



    public function getPareri()
    {
        return $this->hasMany(PareriCommissioni::className(), ['pratica_id' => 'edilizia_id']);
     
    }


    public function getSommario()
    {
        $cmr='Prot. ' . $this->NumeroProtocollo . ' del ' . Yii::$app->formatter->asDate($this->DataProtocollo, 'd-M-Y');
        $cmr = $cmr .  ' - ' . $this->richiedente->Cognome. ' ' . $this->richiedente->Nome;
         if (isset($this->richiedente->DataNascita)) {
             
            $cmr = $cmr . ' ('. Yii::$app->formatter->asDate($this->richiedente->DataNascita, 'd-M-Y').')'; 
         }
         $cmr = $cmr . ' - ' . $this->DescrizioneIntervento;
    Return $cmr;
    }

    /**
     * Relazione tra le tabelle Edilizia e Paesistica
     * @return Paesistica 
     */

public function getPraticaPesistica()
    {
        return $this->hasOne(Paesistica::className(), ['Edilizia_ID' => 'edilizia_id']);
     
    }


    
public function getnumeroAllegati()
    {
        //return $this->hasOne(Paesistica::className(), ['Edilizia_ID' => 'edilizia_id']);
        $na = self::findOne()
        ->joinWith(['allegati'])
        ->count();
     return $na;
    }
    
public function getnumeroPareri($id)
    {
        //return $this->hasOne(Paesistica::className(), ['Edilizia_ID' => 'edilizia_id']);
        $np = self::findOne($id)
        ->joinWith(['pareri'])
        ->count();
     return $np;
    }
    




}
