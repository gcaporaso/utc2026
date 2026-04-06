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
class TipoCommissioni extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_commissioni';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descrizione', ], 'required'],
            ['idtipo_commissioni','safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'descrizione' => 'Descrizione',
        ];
    }
    
    
    public function getCommissione(){
        return $this->hasMany(Commissioni::className(), ['Tipo'=>'idtipo_commissioni']);
    }
    
    
}
