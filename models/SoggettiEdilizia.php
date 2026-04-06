<?php

namespace app\models;

use Yii;


class SoggettiEdilizia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'soggetti_edilizia_titolari';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
                
        return [
            [['edilizia_id','committenti_id','titolo_id','percentuale'], 'safe'],
            [['committenti_id','titolo_id','percentuale'], 'required'],
            ['percentuale','number','max'=>100.00,'min'=>0.01,'message' => 'Deve essere compresa tra 0 e 100'],
        ];
    }
   
    public function getEdilizia()
    {
         return $this->hasOne(Edilizia::className(), [ 'edilizia_id'=>'edilizia_id' ]);

    }

    public function getSoggetto()
    {
         return $this->hasOne(Committenti::className(), [ 'committenti_id'=>'committenti_id' ]);

    }
    
    
    public function getTitolo()
    {
         return $this->hasOne(TitoliPossesso::className(), [ 'idtitoli_possesso'=>'titolo_id' ]);

    }
  
    
    
    
}
