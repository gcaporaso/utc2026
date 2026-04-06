<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipologia".
 *
 * @property int $tipologia_id
 * @property string $Categoria
 * @property string $DESCRIZIONE
 * @property string|null $Normativa
 */
class TipologiaEdilizia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipologia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Categoria', 'DESCRIZIONE'], 'required'],
            [['Categoria'], 'string', 'max' => 25],
            [['DESCRIZIONE'], 'string', 'max' => 800],
            [['Normativa'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idtipologia' => 'ID',
            'Categoria' => 'Categoria',
            'DESCRIZIONE' => 'Descrizione',
            'Normativa' => 'Normativa',
        ];
    }
    
  public function getSommarioTipologia()
    {
     
        return $this->Categoria . ') ' . $this->DESCRIZIONE . ' (' . $this->Normativa . ')';
     
    }
    
    
    
    
    public function getPratica(){

        // customer's items, matching 'id' column of `Item` to 'item_id' in OrderItem
        return $this->hasMany(Edilizia::className(), ['tipologia_id' => 'idtipologia']);
                    
    }
    
    
}
