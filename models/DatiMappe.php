<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "dati_mappe".
 *
 * @property int $id
 * @property string $dataMappe
 * @property string $folder_path
 * @property string $created_at
 */
class DatiMappe extends ActiveRecord
{
    public static function tableName()
    {
        return 'dati_mappe';
    }

    public function rules()
    {
        return [
            [['dataMappe', 'folder_path'], 'required'],
            ['dataMappe', 'date', 'format' => 'php:Y-m-d'],
            ['folder_path', 'string', 'max' => 255],
        ];
    }
}
