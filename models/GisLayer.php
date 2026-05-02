<?php

namespace app\models;

use yii\db\ActiveRecord;

class GisLayer extends ActiveRecord
{
    public static function tableName()
    {
        return 'gis_layers';
    }

    public function rules()
    {
        return [
            [['progetto_id', 'nome', 'tipo_geometria'], 'required'],
            [['progetto_id', 'srid'], 'integer'],
            [['nome', 'tipo_geometria'], 'string', 'max' => 255],
            [['stile'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'             => 'ID',
            'progetto_id'    => 'Progetto',
            'nome'           => 'Nome layer',
            'tipo_geometria' => 'Tipo geometria',
            'srid'           => 'SRID',
            'stile'          => 'Stile',
        ];
    }

    public function getProgetto()
    {
        return $this->hasOne(GisProgetto::class, ['id' => 'progetto_id']);
    }

    public function getFeatures()
    {
        return $this->hasMany(GisFeature::class, ['layer_id' => 'id']);
    }

    public function getFeatureCount()
    {
        return $this->hasMany(GisFeature::class, ['layer_id' => 'id'])->count();
    }
}
