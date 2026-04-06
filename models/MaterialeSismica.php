<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;
use Yii;
/**
 * Description of TitoliSismica
 *
 * @author Giuseppe
 */
class MaterialeSismica extends \yii\db\ActiveRecord
{
/**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'materiale';
    }    //put your code here
    
    
//    public function getPraticaSismica()
//    {
//        return $this->hasOne(Sismica::className(), [ 'sismica_id' => 'materiale_id']);
//     //       ->where('CodiceNatura = :cnatura', [':cnatura' => $natura]);
//    }
    
    
    
    
    
}
