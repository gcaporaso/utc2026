<?php

namespace app\models;

use Yii;


class TecniciSismica extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'soggetti_sismica_tecnici';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
                
        return [
            [['sismica_id','tecnici_id','ruolo_id','data_inizio','data_fine'], 'safe'],
            [['tecnici_id','sismica_id','ruolo_id'], 'required'],
            
        ];
    }
   
    public function getSismica()
    {
         return $this->hasOne(Sismica::className(), [ 'sismica_id'=>'sismica_id' ]);

    }

    public function getTecnico()
    {
         return $this->hasOne(Tecnici::className(), [ 'tecnici_id'=>'tecnici_id' ]);

    }
    
    
    public function getRuolo()
    {
         return $this->hasOne(RuoliTecnici::className(), [ 'idruoli_tecnici'=>'ruolo_id' ]);

    }
  
    
    
    
}
