<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "modulistica".
 *
 * @property int $idmodulistica
 * @property string $nomefile
 * @property string $path
 * @property string $descrizione
 * @property int $numerorevisione
 * @property string $datarevisione
 * @property int $tipo
 */
class Modulistica extends \yii\db\ActiveRecord
{
    
     public $fname;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'modulistica';
    }

    /**
     * {@inheritdoc}
     * qui andrebbe fatta validazione per accettare solo .docx
     * application/vnd.openxmlformats-officedocument.wordprocessingml.document 
     *  [['file'], 'file', 'extensions' => 'docx', 'mimeTypes' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',],
     */
    public function rules()
    {
        return [
            [['descrizione', 'datarevisione','categoria'], 'required'],
            [['numerorevisione', 'categoria'], 'integer'],
            [['idmodulistica','codice','nomefile', 'descrizione', 'datarevisione', 'categoria',
              'fname','numerorevisione','path'], 'safe'],
            //[['nomefile'],'file', 'skipOnEmpty' => false, 'extensions' => 'docx'],
            //[['path', 'descrizione'], 'string', 'max' => 255],
            [['nomefile','path', 'descrizione'], 'string', 'max' => 255],
            [['fname'], 'file', 'skipOnEmpty' => false, 'extensions'=>'docx'],
            [['fname'],'required']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idmodulistica' => 'Idmodulistica',
            'nomefile' => 'Nomefile',
            'path' => 'Path',
            'descrizione' => 'Descrizione',
            'numerorevisione' => 'Numerorevisione',
            'datarevisione' => 'Datarevisione',
            'categora'=>'Categoria'
        ];
    }

    
    
      public function getSettore()
    {
        return $this->hasOne(\app\models\Categorie::className(), [ 'idcategorie' => 'categoria']);
    }
    
    
//     public function upload()
//    {
//        if ($this->validate()) {
//            $this->nomefile->saveAs('uploads/' . $this->nomefile->baseName . '.' . $this->nomefile->extension);
//            return true;
//        } else {
//            return false;
//        }
//    }
    
    
    }



