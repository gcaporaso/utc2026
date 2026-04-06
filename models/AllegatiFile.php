<?php

namespace app\models;

use Yii;
use yii\helpers\FileHelper;
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
class AllegatiFile extends \yii\db\ActiveRecord
{
  
    public $nome;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'allegati_edilizia';
    }

   public function rules()
	{
		return array(
                        [['nomefile','byte','nome','descrizione','tipologia', 'data_update','tipopratica'], 'safe'],
			[['nome'], 'file', 'skipOnEmpty' => false, 'extensions'=>'pdf, jpg, gif, png'],
                        [['nome'],'required']
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
    
    
    public function getTipos(){

        if ($this->tipologia ==1) {
            return 'Amministrativo';   
        } else {
            return 'Tecnico';   
        }
        
                    
    }
    
        
        
        
    
    
     public function upload($idpratica)
    {
//        if ($this->validate()) {
//            
//             // Crea una directory per la pratica se non esiste (numero di 9 cifre con zeri iniziali)
//                     $path=Yii::getAlias('@allegati') . '/' . trim(sprintf('%09u', $idpratica)) . '/'; 
//                    //FileHelper::createDirectory($path, $mode = 0775, $recursive = true); 
//                     if (!file_exists($path)) {
//                        FileHelper::createDirectory($path, $mode = 0775, $recursive = true); 
//                     }
//                    $filename = preg_replace('/[-\s]+/', '-',$this->nome->baseName) . '_' . uniqid('') . '.' . $this->nome->extension;
//                    $filePath = $path . $filename;
//                    //$filePath = $path . preg_replace('/[-\s]+/', '-', $fileobj->name) . '_' . uniqid('');
//                    if ($this->nome->saveAs($filePath)) {
//                       // $this->nome->saveAs('allegatitecnici/' . $this->nome->baseName . '.' . $this->nome->extension);
//                        return $filename;
//                    } else {
//                        return null;
//                    }
//        }
    }    
    
    
    
     public function getPratica(){

        // customer's items, matching 'id' column of `Item` to 'item_id' in OrderItem
        return $this->hasOne(Edilizia::className(), ['edilizia_id' => 'id_pratica']);
                    
    }
    
    
    
    
    
}
