<?php

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadMappeAggiornateForm extends Model
{
    /** @var UploadedFile */
    public $fileMappe;

    /** @var string */
    public $dataMappe;


    public function rules()
    {
        return [
            [['fileMappe', 'dataMappe', ], 'required'],
            ['fileMappe', 'file', 'extensions' => 'zip', 'checkExtensionByMimeType' => false],
            [['dataMappe'], 'date', 'format' => 'php:Y-m-d'],
        ];
    }
}
