<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property int    $id
 * @property string $nome_file
 * @property string $anno_mese      'YYYY-MM'
 * @property string $data_inizio
 * @property string $data_fine
 * @property int    $num_variazioni
 * @property int    $num_soggetti
 * @property string $importato_il
 * @property string $note
 */
class IciFornitura extends ActiveRecord
{
    public static function tableName(): string { return 'ici_forniture'; }

    public function rules(): array
    {
        return [
            ['nome_file', 'required'],
            ['nome_file', 'string', 'max' => 200],
            ['anno_mese', 'string', 'max' => 7],
            [['num_variazioni', 'num_soggetti'], 'integer'],
            [['data_inizio', 'data_fine'], 'safe'],
            ['note', 'string'],
        ];
    }
}
