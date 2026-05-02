<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class GisProgetto extends ActiveRecord
{
    const TIPO_OPERA    = 'opera_pubblica';
    const TIPO_RILIEVO  = 'rilievo_topografico';

    public static function tableName()
    {
        return 'gis_progetti';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function rules()
    {
        return [
            [['nome', 'tipo'], 'required'],
            [['nome'], 'string', 'max' => 255],
            [['descrizione'], 'string'],
            [['tipo'], 'in', 'range' => [self::TIPO_OPERA, self::TIPO_RILIEVO]],
            [['created_by'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'nome'        => 'Nome progetto',
            'descrizione' => 'Descrizione',
            'tipo'        => 'Tipo',
            'created_at'  => 'Creato il',
            'updated_at'  => 'Aggiornato il',
            'created_by'  => 'Creato da',
        ];
    }

    public static function tipoLabels()
    {
        return [
            self::TIPO_OPERA   => 'Opera Pubblica',
            self::TIPO_RILIEVO => 'Rilievo Topografico',
        ];
    }

    public function getTipoLabel()
    {
        return self::tipoLabels()[$this->tipo] ?? $this->tipo;
    }

    public function getLayers()
    {
        return $this->hasMany(GisLayer::class, ['progetto_id' => 'id']);
    }
}
