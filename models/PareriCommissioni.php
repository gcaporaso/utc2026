<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pareri_commissioni".
 *
 * @property int $idpareri_commissioni
 * @property int $commissioni_id
 * @property int $pratica_id
 * @property int $seduta_id
 * @property int $tipoparere_id
 * @property string $testoparere
 */
class PareriCommissioni extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pareri_commissioni';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['commissioni_id', 'pratica_id', 'seduta_id', 'tipoparere_id', 'testoparere'], 'required'],
            [['commissioni_id', 'pratica_id', 'seduta_id', 'tipoparere_id'], 'integer'],
            [['testoparere'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idpareri_commissioni' => 'Idpareri Commissioni',
            'commissioni_id' => 'Commissioni ID',
            'pratica_id' => 'Pratica ID',
            'seduta_id' => 'Seduta ID',
            'tipoparere_id' => 'Tipoparere ID',
            'testoparere' => 'Testoparere',
        ];
    }
    
    
     public function getCommissione(){
        return $this->hasOne(Commissioni::className(), ['idcommissioni' => 'commissioni_id']);
                    
    }
    
    
     public function getSeduta(){
        return $this->hasOne(SeduteCommissioni::className(), ['idsedute_commissioni' => 'seduta_id']);
                    
    }
    
    
     public function getPratica(){
        return $this->hasOne(Edilizia::className(), ['edilizia_id' => 'pratica_id']);
                    
    }
    
    
    public function getPraticaByDataASC(){
        return $this->hasOne(Edilizia::className(), ['edilizia_id' => 'pratica_id'])->orderBy(['DataProtocollo'=>SORT_ASC]);
                    
    }
    
    
     public function getTipoParere(){
        return $this->hasOne(TipoParere::className(), ['idtipoparere' => 'tipoparere_id']);
                    
    }
    
}
