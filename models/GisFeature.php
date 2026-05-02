<?php

namespace app\models;

use yii\db\ActiveRecord;

class GisFeature extends ActiveRecord
{
    public static function tableName()
    {
        return 'gis_features';
    }

    public function rules()
    {
        return [
            [['layer_id'], 'required'],
            [['layer_id'], 'integer'],
            [['proprieta'], 'safe'],
        ];
    }

    public function getLayer()
    {
        return $this->hasOne(GisLayer::class, ['id' => 'layer_id']);
    }
}
