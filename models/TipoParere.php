<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipoparere".
 *
 * @property int $idtipoparere
 * @property string $esitoparere
 */
class TipoParere extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipoparere';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['esitoparere'], 'required'],
            [['esitoparere'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idtipoparere' => 'Idtipoparere',
            'esitoparere' => 'Esitoparere',
        ];
    }
    
    public function getParere(){
        return $this->hasOne(PareriCommissioni::className(), ['tipoparere_id' =>'idtipoparere']);
                    
    } 
    
    
    
}
