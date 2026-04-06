<?php

namespace app\models;

use Yii;

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
class SismicaTecnici extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'soggetti_sismica_tecnici';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idsoggetti_sismica_tecnici','sismica_id','tecnici_id','ruolo_id','data_inizio','data_fine'], 'safe'],
            [['ruolo_id','sismica_id','tecnici_id'], 'required'],
        ];
    }


    
    
      public function getRuolo()
    {
         return $this->hasOne(RuoliTecnici::className(), [ 'idruoli_tecnici'=>'ruolo_id' ]);

    }



    
    
    
    
    
    
    
    
    
    
    
}
