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
class Commissioni extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'commissioni';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Descrizione', 'Tipo'], 'required'],
            [['NumeroDelibera', 'DataDelibera'],'safe'],
            [['NumeroDelibera','Tipo'], 'integer'],
            [['Descrizione'], 'string', 'max' => 65],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idcommissioni' => 'Idcommissioni',
            'Descrizione' => 'Descrizione',
            'NumeroDelibera' => 'Numero Delibera',
            'DataDelibera' => 'Data Delibera',
        ];
    }
    
    
    public function getComposizione(){
        return $this->hasMany(Composizioni::className(), ['commissioni_id' => 'idcommissioni']);
    }
    
     public function getTipologia(){
        return $this->hasOne(TipoCommissioni::className(), ['idtipo_commissioni' => 'Tipo']);
    }
    
    public function getSedute(){
        return $this->hasMany(SeduteCommissioni::className(), ['commissioni_id' => 'idcommissioni']);
    }
    
    
    public function getPareri(){
        return $this->hasMany(PareriCommissioni::className(), ['commissioni_id' =>'idcommissioni' ]);
                    
    }
    
    public function getCategoria(){
        return $this->hasOne(Categorie::className(), ['idcategorie' =>'commissioni_categoria_id' ]);
                    
    }
    
    
    
    
    
    
}
