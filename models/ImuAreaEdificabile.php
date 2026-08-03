<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Valori unitari (€/mq) delle aree edificabili per anno.
 *
 * @property int    $id
 * @property int    $anno
 * @property string $zona
 * @property string $descrizione
 * @property float  $valore_mq
 * @property int    $attiva
 * @property string $note
 */
class ImuAreaEdificabile extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'imu_aree_edificabili';
    }

    public function rules(): array
    {
        return [
            [['anno', 'zona'], 'required'],
            ['anno', 'integer', 'min' => 2000, 'max' => 2100],
            ['zona', 'string', 'max' => 50],
            ['descrizione', 'string', 'max' => 255],
            ['valore_mq', 'number', 'min' => 0],
            ['attiva', 'integer'],
            ['note', 'string'],
            [['descrizione', 'note'], 'default', 'value' => ''],
            ['valore_mq', 'default', 'value' => 0],
            ['attiva', 'default', 'value' => 1],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'anno'        => 'Anno',
            'zona'        => 'Zona PRG',
            'descrizione' => 'Descrizione',
            'valore_mq'   => 'Valore unitario (€/mq)',
            'attiva'      => 'Attiva',
            'note'        => 'Note',
        ];
    }
}
