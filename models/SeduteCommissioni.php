<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "componenti_commissioni".
 *
 * @property int $idcomponenti_commissioni
 * @property string $Cognome
 * @property string $Nome
 * @property int $Tipologia
 */
class SeduteCommissioni extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sedute_commissioni';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['commissione_id', 'dataseduta','statoseduta','presenze','orarioconvocazione','numero'], 'required'],
            
             [['statoseduta','orarioinizio','orariofine'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'orarioconvocazione' => 'Orario Convocazione',
            'dataseduta' => 'Data Seduta',
            'statoseduta' => 'Stato Seduta',
            'orarioinizio' => 'Orario Inizio',
            'orariofine' => 'Orario Fine',
        ];
    }
    
    public function getCommissione(){
        return $this->hasOne(Commissioni::className(), ['idcommissioni' => 'commissione_id']);
                    
    }
    
    public function getStato(){
        return $this->hasOne(StatoSedute::className(), ['idstato_sedute' => 'statoseduta']);
                    
    }
    
     public function getPareri(){
        return $this->hasMany(PareriCommissioni::className(), ['seduta_id' => 'idsedute_commissioni']);
                    
    }
    
}
