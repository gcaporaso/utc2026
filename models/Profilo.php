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
class Profilo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fullname','comune','user_id'], 'safe'],
            
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'fullname' => 'Nome Completo',
        ];
    }
    
    

    
    
    
    
    
    
    
    
    
    
}
