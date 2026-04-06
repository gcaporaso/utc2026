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
class TitoliSismica extends \yii\db\ActiveRecord
{
/**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'titoli_sismica';
    }    //put your code here
    
    
    public function getPraticaSismica()
    {
        return $this->hasOne(Sismica::className(), [ 'TipoTitolo' => 'idtitoli_sismica']);
     //       ->where('CodiceNatura = :cnatura', [':cnatura' => $natura]);
    }
    
    
    
    
    
}
