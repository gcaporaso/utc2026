<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "commissioni".
 *
 * @property int $idcommissioni
 * @property string $Descrizione
 * @property int $NumeroDelibera
 * @property string $DataDelibera
 */
class TitoloComponente extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'titolo_componente';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['titolo','abbr_titolo'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            
            'titolo' => 'Titolo',
            'abbr_titolo' => 'Abbreviazione Titolo',
            ];
    }        
    
    public function getComponenti(){
        return $this->hasOne(ComponentiCommissioni::className(), ['titolo_id' => 'idtitolo_componente']);
    }
    
  public function getTecnici(){
        return $this->hasMany(Tecnici::className(), ['idtitolo' => 'idtitolo_componente']);
    }
    
    
    
}
