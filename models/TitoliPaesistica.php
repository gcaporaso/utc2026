<?php

namespace app\models;

//use Yii;
//use yii\base\Model;
use app\models\Committenti;
use app\models\Paesistica;
use app\models\Tecnici;
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



class TitoliPaesistica extends ActiveRecord
{

	 public static function tableName()
    {
        return '{{%titoli_paesistica}}';
    }
	
    
   
    public function rules()
    {
        return [
            // username and password are both required
            [['NumeroProtocollo','DataProtocollo','DescrizioneIntervento', 
                'idcommittente','idtipo','StatoPratica','Indennita','Indpagata','FileAutorizzazione' ], 'required'],
            [['Compatibilita','TipoCatasto','CatastoFoglio','StatoPratica','CatastoParticella',
              'CatastoSub','titolo','NumeroAutorizzazione','DataAutorizzazione', 'Indennita',
                'IndirizzoImmobile', 'Progettista_ID','Direttore_Lavori_ID','Impresa_ID',
                'InviatoRegione','InviatoSoprintendenza'],'safe'],
            ['Latitudine','number','max'=>41.16,'min'=>41.08,'message' => 'Deve essere compresa tra 41.080000 e 41.160000'],
            ['Longitudine','number','max'=>14.7,'min'=>14.6,'message' => 'Deve essere compresa tra 14.600000 e 14.700000'],
            [['Indennita'],'number','min'=>0.00,'max'=>50000.00],
           ];
    }




  public function getPaesistiche(){

        // customer's items, matching 'id' column of `Item` to 'item_id' in OrderItem
        return $this->hasMany(Paesistica::className(), ['idtipo' => 'idtitoli_paesistica']);
                    
    }

  


	}
