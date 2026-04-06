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
class Tecnici extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tecnici';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['DATA_NASCITA','nomeCompleto'], 'safe'],
            [['COGNOME', 'PROVINCIA_ALBO', 'NUMERO_ISCRIZIONE', 'TELEFONO', 'FAX', 'CELLULARE'], 'string', 'max' => 12],
            [['NOME', 'ALBO', 'COMUNE_RESIDENZA', 'CODICE_FISCALE'], 'string', 'max' => 25],
            [['COMUNE_NASCITA', 'PROVINCIA_NASCITA', 'NOME_COMPOSTO', 'P_IVA', 'EMAIL'], 'string', 'max' => 255],
            [['INDIRIZZO', 'PEC', 'Denominazione'], 'string', 'max' => 45],
            [['PROVINCIA_RESIDENZA'], 'string', 'max' => 5],
            ['P_IVA','match', 'pattern' => '/^[0-9]{11}$/i'],
            [['EMAIL','PEC'],'email']
            
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tecnici_id' => 'Tecnici ID',
            'COGNOME' => 'Cognome',
            'NOME' => 'Nome',
            'COMUNE_NASCITA' => 'Comune Nascita',
            'PROVINCIA_NASCITA' => 'Provincia Nascita',
            'DATA_NASCITA' => 'Data Nascita',
            'ALBO' => 'Albo',
            'PROVINCIA_ALBO' => 'Provincia Albo',
            'NUMERO_ISCRIZIONE' => 'Numero Iscrizione',
            'NOME_COMPOSTO' => 'Nome Composto',
            'INDIRIZZO' => 'Indirizzo',
            'COMUNE_RESIDENZA' => 'Comune Residenza',
            'PROVINCIA_RESIDENZA' => 'Provincia Residenza',
            'CODICE_FISCALE' => 'Codice Fiscale',
            'P_IVA' => 'P Iva',
            'TELEFONO' => 'Telefono',
            'FAX' => 'Fax',
            'CELLULARE' => 'Cellulare',
            'EMAIL' => 'Email',
            'PEC' => 'Pec',
            'Denominazione' => 'Denominazione',
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
        return $this->DENOMINAZIONE;
            }
    }
	
    public function getPraticheProgettistaArchitettonico()
    {
         return $this->hasMany(Edilizia::className(), [ 'PROGETTISTA_ARC_ID'=>'tecnici_id' ]);

    }


    public function getPraticheProgettistaStrutturale()
    {
         return $this->hasMany(Edilizia::className(), [ 'PROGETTISTA_STR_ID'=>'tecnici_id' ]);

    }

    
    
        public function getPraticheDirettoreLavoriArchitettonico()
    {
         return $this->hasMany(Edilizia::className(), [ 'DIR_LAV_ARCH_ID'=>'tecnici_id' ]);

    }

    
    
        public function getPraticheDirettoreLavoriStrutturale()
    {
         return $this->hasMany(Edilizia::className(), [ 'DIR_LAV_STR_ID'=>'tecnici_id' ]);

    }

        public function getPraticheCollaudatore()
    {
         return $this->hasMany(Edilizia::className(), [ 'Collaudatore_id'=>'tecnici_id' ]);

    }

      public function getSismicaCollaudatore()
    {
         return $this->hasMany(Sismica::className(), [ 'COLLAUDATORE_ID'=>'tecnici_id' ]);

    }

    
    
      public function getTitolo()
    {
         return $this->hasOne(TitoloComponente::className(), [ 'idtitolo_componente'=>'idtitolo' ]);

    }



    
    
    public function getNomeBreve()
    {
     if ($this->formaGiuridica==0) {
        return $this->COGNOME. ' ' .$this->NOME; 
     } else {
        return $this->DENOMINAZIONE;
            }
    }
    
    
     public function getNometecnico()
    {
     if ($this->formaGiuridica==0) {
        return $this->titolo->abbr_titolo . ' ' . $this->COGNOME. ' ' .$this->NOME. ' ('.Yii::$app->formatter->asDate($this->DATA_NASCITA, 'd-M-Y').')';  
     } else {
        return $this->DENOMINAZIONE;
            }
    }
    
    
    
    
    
    
    
    
    
    
}
