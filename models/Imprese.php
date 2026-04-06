<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "imprese".
 *
 * @property int $imprese_id
 * @property string|null $RAGIONE_SOCIALE
 * @property string|null $COGNOME
 * @property string|null $NOME
 * @property string|null $DATA_NASCITA
 * @property string|null $PROVINCIA_NASCITA
 * @property string|null $NOME_COMPOSTO
 * @property string|null $INDIRIZZO
 * @property string|null $COMUNE_RESIDENZA
 * @property string|null $PROVINCIA_RESIDENZA
 * @property string|null $CODICE_FISCALE
 * @property string|null $P.IVA
 * @property string|null $EMAIL
 * @property string|null $PEC
 * @property int $Cassa_Edile
 * @property string|null $INPS
 * @property string|null $INAIL
 * @property int $Telefono
 * @property int $Cellulare
 */
class Imprese extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'imprese';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['DATA_NASCITA'], 'safe'],
            [['RAGIONE_SOCIALE','formaGiuridica'], 'required'],
            [['Cassa_Edile', 'Telefono', 'Cellulare'], 'string', 'max' => 12],
            [['Telefono', 'Cellulare'], 'integer'],
            [['RAGIONE_SOCIALE', 'PROVINCIA_NASCITA'], 'string', 'max' => 255],
            [['COGNOME', 'INPS', 'INAIL'], 'string', 'max' => 12],
            [['NOME', 'COMUNE_RESIDENZA'], 'string', 'max' => 25],
            [['NOME_COMPOSTO', 'INDIRIZZO'], 'string', 'max' => 45],
            [['PROVINCIA_RESIDENZA'], 'string', 'max' => 5],
            [['EMAIL', 'PEC'], 'email'],
            ['CODICE_FISCALE', 'match', 'pattern' => '/^[A-Z]{6}[0-9LMNPQRSTUV]{2}[ABCDEHLMPRST][0-9LMNPQRSTUV]{2}[A-Z][0-9LMNPQRSTUV]{3}[A-Z]$/i'],
            ['PartitaIVA','match', 'pattern' => '/^[0-9]{11}$/i'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'imprese_id' => 'Imprese ID',
            'RAGIONE_SOCIALE' => 'Ragione Sociale',
            'COGNOME' => 'Cognome',
            'NOME' => 'Nome',
            'DATA_NASCITA' => 'Data Nascita',
            'PROVINCIA_NASCITA' => 'Provincia Nascita',
            'NOME_COMPOSTO' => 'Nome Composto',
            'INDIRIZZO' => 'Indirizzo',
            'COMUNE_RESIDENZA' => 'Comune Residenza',
            'PROVINCIA_RESIDENZA' => 'Provincia Residenza',
            'CODICE_FISCALE' => 'Codice Fiscale',
            'PartitaIVA' => 'Partita Iva',
            'EMAIL' => 'Email',
            'PEC' => 'Pec',
            'Cassa_Edile' => 'Cassa Edile',
            'INPS' => 'Inps',
            'INAIL' => 'Inail',
            'Telefono' => 'Telefono',
            'Cellulare' => 'Cellulare',
        ];
    }
    
    
    public function getNomeCompleto()
    {
     if ($this->formaGiuridica==0) {
         if (isset($this->DATA_NASCITA)) {
        return $this->COGNOME. ' ' .$this->NOME. ' ('.Yii::$app->formatter->asDate($this->DATA_NASCITA, 'd-M-Y').')'; 
         } else {
         return $this->COGNOME. ' ' .$this->NOME; 
         }
     } else {
        return $this->RAGIONE_SOCIALE;
            }
    }
    
     public function getPraticheImpresa()
    {
         return $this->hasMany(Edilizia::className(), [ 'IMPRESA_ID'=>'Imprese_id' ]);

    }
    
    
    
    
}
