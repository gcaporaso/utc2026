<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "composizioni".
 *
 * @property int $idcomposizioni
 * @property int $commissioni_id
 * @property int $componenti_id
 */
class Composizioni extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'composizioni';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['commissioni_id', 'componenti_id'], 'required'],
            [['commissioni_id', 'componenti_id'], 'integer'],
            [['commissioni_id', 'componenti_id'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idcomposizioni' => 'Idcomposizioni',
            'commissioni_id' => 'Commissioni ID',
            'componenti_id' => 'Componenti ID',
        ];
    }
    
    public function getCommissione(){
        return $this->hasOne(Commissioni::className(), ['idcommissioni' => 'commissioni_id']);
                    
    }
    
    public function getTitolo(){
        return $this->hasOne(TitoloComponente::className(), ['idtitolo_componente' => 'titolo_id'])
                ->via('componenti');
                    
    }
    
    
    public function getComponenti(){
        return $this->hasOne(ComponentiCommissioni::className(), ['idcomponenti_commissioni' => 'componenti_id']);
                    
    }
    
    
}
