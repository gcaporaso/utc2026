<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;



class Particella extends \yii\base\Model
{
    
public $foglio;
public $particella;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            
            [['Foglio','Particella'], 'required'],
        ];
    }

    
    
    
    
}