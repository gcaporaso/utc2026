<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "project_task".
 *
 * @property int $id
 * @property int $project_id
 * @property string $name
 * @property string|null $description
 *
 * @property Project $project
 * @property ProjectTaskRaci[] $raciAssignments
 */
class ProjectTask extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%project_task}}';
    }

    public function rules()
    {
        return [
            [['project_id', 'name'], 'required'],
            [['project_id'], 'integer'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 200],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'project_id' => 'Progetto',
            'name' => 'Nome Attività',
            'description' => 'Descrizione',
        ];
    }

    public function getProject()
    {
        return $this->hasOne(Project::class, ['id' => 'project_id']);
    }

    public function getRaciAssignments()
    {
        return $this->hasMany(ProjectTaskRaci::class, ['task_id' => 'id']);
    }

    /**
     * Restituisce la mappa [user_id => raci_role_id] per il task
     */
    public function getRaciMatrix()
    {
        $matrix = [];
        foreach ($this->raciAssignments as $assign) {
            $matrix[$assign->user_id] = $assign->raci_role_id;
        }
        return $matrix;
    }

    /**
     * Restituisce il ruolo RACI di un utente specifico su questo task
     */
    public function getUserRaciRole($userId)
    {
        foreach ($this->raciAssignments as $assign) {
            if ($assign->user_id == $userId) {
                return $assign->raciRole;
            }
        }
        return null;
    }
}
