<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "project_task_raci".
 *
 * @property int $task_id
 * @property int $user_id
 * @property int $raci_role_id
 *
 * @property ProjectTask $task
 * @property User $user
 * @property RaciRole $raciRole
 */
class ProjectTaskRaci extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%project_task_raci}}';
    }

    public static function primaryKey()
    {
        return ['task_id', 'user_id', 'raci_role_id'];
    }

    public function rules()
    {
        return [
            [['task_id', 'user_id', 'raci_role_id'], 'required'],
            [['task_id', 'user_id', 'raci_role_id'], 'integer'],
            [['task_id', 'user_id', 'raci_role_id'], 'unique', 'targetAttribute' => ['task_id', 'user_id', 'raci_role_id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'task_id' => 'Task',
            'user_id' => 'Utente',
            'raci_role_id' => 'Ruolo RACI',
        ];
    }

    public function getTask()
    {
        return $this->hasOne(ProjectTask::class, ['id' => 'task_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getRaciRole()
    {
        return $this->hasOne(RaciRole::class, ['id' => 'raci_role_id']);
    }
}
