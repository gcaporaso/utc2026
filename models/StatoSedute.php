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



class StatoSedute extends ActiveRecord
{

	 public static function tableName()
    {
        return '{{%stato_sedute}}';
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
            [['idstato_sedute','descrizione'], 'required'],
           
            // rememberMe must be a boolean value
            //['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            //['password', 'validatePassword'],
        ];
    }

    
    
    public function getSedute(){

        // customer's items, matching 'id' column of `Item` to 'item_id' in OrderItem
        return $this->hasMany(SeduteCommissioni::className(), ['statoseduta'=> 'idstato_sedute']);
                    
    }
 	
	
	

	}
