<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "raci_role".
 *
 * @property int $id
 * @property string $code
 * @property string $label
 *
 * @property ProjectTaskRaci[] $projectTaskRacis
 */
class RaciRole extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%raci_role}}';
    }

    public function rules()
    {
        return [
            [['code', 'label'], 'required'],
            ['code', 'in', 'range' => ['R', 'A', 'C', 'I']],
            ['label', 'string', 'max' => 50],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Codice',
            'label' => 'Etichetta',
        ];
    }

    public function getProjectTaskRacis()
    {
        return $this->hasMany(ProjectTaskRaci::class, ['raci_role_id' => 'id']);
    }
}
