

<?php

namespace app\models;

use Yii;
use sjaakp\spatial\ActiveRecord;


/**
 * This is the model class for table "Mappe".
 *
 */
class Mappe extends ActiveRecord 
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mappe';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['location', 'mapcenter','mapzoom'], 'safe'],
        ];
    }

    
    
}
