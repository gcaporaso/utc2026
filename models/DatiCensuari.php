<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "dati_censuari".
 *
 * @property int $id
 * @property string $dataCensuari
 * @property string $file_path_database
 * @property string $created_at
 */
class DatiCensuari extends ActiveRecord
{
    public static function tableName()
    {
        return 'dati_censuari';
    }

    public function rules()
    {
        return [
            [['dataCensuari', 'file_path_database'], 'required'],
            ['dataCensuari', 'date', 'format' => 'php:Y-m-d'],
            ['file_path_database', 'string', 'max' => 255],
        ];
    }
}
