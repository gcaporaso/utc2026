<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "task_catalog".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 *
 * @property ProjectTask[] $projectTasks
 */
class TaskCatalog extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%task_catalog}}';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 200],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Nome attività',
            'description' => 'Descrizione',
        ];
    }

    /**
     * Relazione con le attività di progetto (se vuoi collegare in futuro
     * ProjectTask a TaskCatalog tramite una colonna tipo catalog_id).
     */
    public function getProjectTasks()
    {
        return $this->hasMany(ProjectTask::class, ['catalog_id' => 'id']);
    }
}
