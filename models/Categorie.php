<?php

namespace app\models;

//use Yii;

/**
 * This is the model class for table "tipomodulistica".
 *
 * @property int $idtipomodulistica
 * @property string|null $codice
 * @property string $descrizione
 * @property int|null $settore 1 = Edilizia, 2 = Sismica, 3 = Urbanistica
 */
class Categorie extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categorie';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descrizione'], 'required'],
            [['descrizione'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idcategorie' => 'Id',
            'descrizione' => 'Descrizione',
        ];
    }
    
    
        public function getModello()
    {
        return $this->hasMany(\app\models\Modulistica::className(), [ 'categoria' => 'idcategorie']);
    }
    
    
    public function getCommissioni(){
        return $this->hasMany(Commissioni::className(), ['commissioni_categoria_id' =>'idcategorie' ]);
                    
    }
    
    
}
