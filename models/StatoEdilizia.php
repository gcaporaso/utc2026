<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
//class Lavori extends Model
//{
	
	
//	namespace app\models;



class StatoEdilizia extends ActiveRecord
{

	 public static function tableName()
    {
        return '{{%stato_edilizia}}';
    }
	
  //  public $username;
//    public $password;
//    public $rememberMe = true;
//
//    private $_user = false;
//
//
//    *
//     * @return array the validation rules.
//     
    public function rules()
    {
        return [
            // username and password are both required
            [['idstato_edilizia','descrizione'], 'required'],
            // rememberMe must be a boolean value
            //['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            //['password', 'validatePassword'],
        ];
    }

    
    
    public function getPratica(){

        // customer's items, matching 'id' column of `Item` to 'item_id' in OrderItem
        return $this->hasMany(Edilizia::className(), ['Stato_Pratica_id'=> 'idstato_edilizia']);
                    
    }
 	
    
     public function getStatoSismica(){

        // customer's items, matching 'id' column of `Item` to 'item_id' in OrderItem
        return $this->hasMany(Sismica::className(), ['StatoPratica'=> 'idstato_edilizia']);
                    
    }
	
	

	}
