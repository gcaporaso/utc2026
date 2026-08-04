<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Log delle forniture F24 importate da SOGEI.
 *
 * @property int    $id
 * @property string $nome_file
 * @property string $data_fornitura
 * @property int    $anno_riferimento
 * @property int    $num_record
 * @property int    $num_imu
 * @property string $importato_il
 * @property string $note
 */
class ImuF24Fornitura extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'imu_f24_forniture';
    }

    public function rules(): array
    {
        return [
            ['nome_file', 'required'],
            ['nome_file', 'string', 'max' => 200],
            [['num_record', 'num_imu', 'anno_riferimento'], 'integer'],
            ['note', 'string'],
        ];
    }
}
