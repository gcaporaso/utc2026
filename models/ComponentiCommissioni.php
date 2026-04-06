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
class ComponentiCommissioni extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'componenti_commissioni';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Cognome', 'Nome'], 'required'],
            [['titolo_id'], 'integer'],
            [['DataNascita','email','pec','telefono','cellulare','titolo_id'],'safe'],
            [['Cognome', 'Nome','IndirizzoResidenza','ComuneResidenza'], 'string', 'max' => 45],
            [['ProvinciaResidenza'], 'string', 'max' => 2],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idcomponenti_commissioni' => 'Idcomponenti Commissioni',
            'Cognome' => 'Cognome',
            'Nome' => 'Nome',
            'titolo_id' => 'Titolo',
        ];
    }
    
    public function getComposizione(){
        return $this->hasOne(Composizioni::className(), ['componenti_id' => 'idcomponenti_commissioni']);
                    
    }
    
    public function getNomeCompleto()
    {
        if (isset($this->ComuneResidenza)) {
            $cmr=' - ' . $this->ComuneResidenza;
        } else {
            $cmr='';
        }
         if (isset($this->DataNascita)) {
            return $this->competenze->abbr_titolo . ' ' . $this->Cognome. ' ' .$this->Nome. ' ('.Yii::$app->formatter->asDate($this->DataNascita, 'd-M-Y').')' . $cmr; 
         } else {
            return $this->competenze->abbr_titolo . ' ' .$this->Cognome. ' ' .$this->Nome; // . $cmr 
        }
    }
    
    
      public function getCompetenze(){
        return $this->hasOne(TitoloComponente::className(), ['idtitolo_componente' => 'titolo_id']);
    }
    
}
