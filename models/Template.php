<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "template".
 *
 * @property int $id
 * @property string $name
 */
class Template extends ActiveRecord
{
    public static function tableName()
    {
        return 'template';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }
}
