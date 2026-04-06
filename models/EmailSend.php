<?php

namespace app\models;

use Yii;
//use yii\web\UploadedFile;

/**
 * This is the model class for table "tecnici".
 *
 * @property int $tecnici_id
 * @property string|null $COGNOME
 * @property string|null $NOME
 * @property string|null $COMUNE_NASCITA
 * @property string|null $PROVINCIA_NASCITA
 * @property string|null $DATA_NASCITA
 * @property string|null $ALBO
 * @property string|null $PROVINCIA_ALBO
 * @property string|null $NUMERO_ISCRIZIONE
 * @property string|null $NOME_COMPOSTO
 * @property string|null $INDIRIZZO
 * @property string|null $COMUNE_RESIDENZA
 * @property string|null $PROVINCIA_RESIDENZA
 * @property string|null $CODICE_FISCALE
 * @property string|null $P_IVA
 * @property string|null $TELEFONO
 * @property string|null $FAX
 * @property string|null $CELLULARE
 * @property string|null $EMAIL
 * @property string|null $PEC
 * @property string|null $Denominazione
 */
class EmailSend extends \yii\base\Model
{
  
    
    public $efrom;
    public $eto;
    public $esubject;
    public $ebody;
    public $ecc;
    public $ebc;
    public $pratica_id;
    

   public function rules()
	{
		return array(
                        [['efrom','eto','esubject','etextbody','ecc', 'ebc','pratica_id'], 'safe'],
			
		);
	}

    /**
     * {@inheritdoc}
     */
//    public function attributeLabels()
//    {
//        return [
//            'tecnici_id' => 'Tecnici ID',
//            'COGNOME' => 'Cognome',
//            'NOME' => 'Nome',
//            'COMUNE_NASCITA' => 'Comune Nascita',
//            'PROVINCIA_NASCITA' => 'Provincia Nascita',
//            'DATA_NASCITA' => 'Data Nascita',
//            'ALBO' => 'Albo',
//            'PROVINCIA_ALBO' => 'Provincia Albo',
//            'NUMERO_ISCRIZIONE' => 'Numero Iscrizione',
//            'NOME_COMPOSTO' => 'Nome Composto',
//            'INDIRIZZO' => 'Indirizzo',
//            'COMUNE_RESIDENZA' => 'Comune Residenza',
//            'PROVINCIA_RESIDENZA' => 'Provincia Residenza',
//            'CODICE_FISCALE' => 'Codice Fiscale',
//            'P_IVA' => 'P Iva',
//            'TELEFONO' => 'Telefono',
//            'FAX' => 'Fax',
//            'CELLULARE' => 'Cellulare',
//            'EMAIL' => 'Email',
//            'PEC' => 'Pec',
//            'Denominazione' => 'Denominazione',
//        ];
//    }
    
    
    
    
    
    
    
}
