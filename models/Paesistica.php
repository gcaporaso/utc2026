<?php

namespace app\models;

//use Yii;
//use yii\base\Model;
use app\models\Committenti;
use app\models\TitoliPaesistica;
use app\models\Tecnici;
use app\models\Edilizia;
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



class Paesistica extends ActiveRecord
{

	 public static function tableName()
    {
        return '{{%paesistica}}';
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
    
    public function rules()
    {
        return [
            // username and password are both required
            [['NumeroProtocollo','DataProtocollo','DescrizioneIntervento', 
                'idcommittente','idtipo','StatoPratica' ], 'required'],
            [['NumeroProtocollo','DataProtocollo','DescrizioneIntervento', 'idcommittente','idtipo','StatoPratica',
               'Compatibilita','TipoCatasto','CatastoFoglio','StatoPratica','CatastoParticella','IndPagata',
               'CatastoSub','titolo','NumeroAutorizzazione','DataAutorizzazione', 'Indennita','FileAutorizzazione',
               'IndirizzoImmobile', 'Progettista_ID','Direttore_Lavori_ID','Impresa_ID',
               'InviatoRegione','InviatoSoprintendenza','Edilizia_ID'],'safe'],
            ['Latitudine','number','max'=>41.16,'min'=>41.08,'message' => 'Deve essere compresa tra 41.080000 e 41.160000'],
            ['Longitudine','number','max'=>14.7,'min'=>14.6,'message' => 'Deve essere compresa tra 14.600000 e 14.700000'],
            [['Indennita'],'number','min'=>0.00,'max'=>50000.00],
           ];
    }


  public function getTipo(){

        // customer's items, matching 'id' column of `Item` to 'item_id' in OrderItem
        return $this->hasOne(TitoliPaesistica::className(), ['idtitoli_paesistica' => 'idtipo']);
                    
    }

  
	
	public function getAllegati()
    {
        return $this->hasMany(AllegatiFile::className(), [ 'id_pratica' => 'idpaesistica']);
     //       ->where('CodiceNatura = :cnatura', [':cnatura' => $natura]);
    }
	

    
     public function getStatoPratica(){

        // customer's items, matching 'id' column of `Item` to 'item_id' in OrderItem
        return $this->hasOne(StatoEdilizia::className(), ['idstato_edilizia' => 'StatoPratica']);
                    
    }
    
   
    public function getRichiedente()
    {
        return $this->hasOne(Committenti::className(), [ 'committenti_id' => 'idcommittente']);
     //       ->where('CodiceNatura = :cnatura', [':cnatura' => $natura]);
    }

    
   public function getProgettista()
    {
        return $this->hasOne(Tecnici::className(), [ 'tecnici_id' => 'Progettista_ID']);
     //       ->where('CodiceNatura = :cnatura', [':cnatura' => $natura]);
    }   
    
      
    
    public function getDirettoreLavori()
    {
        return $this->hasOne(Tecnici::className(), [ 'tecnici_id' => 'DIR_LAV_ARCH_ID']);
     //       ->where('CodiceNatura = :cnatura', [':cnatura' => $natura]);
    }  
    
    
    
    public function getDirettoreStrutturale()
    {
        return $this->hasOne(Tecnici::className(), [ 'tecnici_id' => 'Drettore_Lavori_ID']);
     //       ->where('CodiceNatura = :cnatura', [':cnatura' => $natura]);
    }  
    
   
    public function getImpresa()
    {
        return $this->hasOne(Imprese::className(), [ 'imprese_id' => 'Impresa_ID']);
     //       ->where('CodiceNatura = :cnatura', [':cnatura' => $natura]);
    }  
    
    public function getPareri()
    {
        return $this->hasMany(PareriCommissioni::className(), ['pratica_id' => 'idpaesistica']);
     
    }


   /**
    * Relazione tra tabella Edilizia e Paesistica
    * @return Edilizia
    */
public function getPraticaEdilizia()
    {
        return $this->hasOne(Edilizia::className(), ['edilizia_id' => 'Edilizia_ID']);
     
    }
    
    
    
}
