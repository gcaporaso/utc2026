<?php

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadDatiCensuariForm extends Model
{
    /** @var UploadedFile */
    public $fileCensuari;

    /** @var string */
    public $dataCensuari;

    public function rules()
    {
        return [
            [['fileCensuari', 'dataCensuari'], 'required'],
            ['fileCensuari', 'file', 'extensions' => 'db', 'checkExtensionByMimeType' => false],
            [['dataCensuari'], 'date', 'format' => 'php:Y-m-d'],
        ];
    }
}
