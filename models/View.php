<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use mdm\admin\models\User;

/**
 * This is the model class for table "view".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $type
 * @property string $creation_date
 * @property string $user_id
 * @property int $project_id
 *
 * @property User $creatorUser
 * @property Project $project
 * @property File[] $files
 * @property User[] $users
 * @property Task[] $tasks
 */
class View extends ActiveRecord
{
    public static function tableName()
    {
        return 'view';
    }

    public function rules()
    {
        return [
            [['Name', 'Type', 'UserId', 'ProjectId','CreatorUserId'], 'required'],
            [['CreationDate','Description'], 'safe'],
            // [['project_id'], 'integer'],
            // [['name'], 'string', 'max' => 255],
            // [['type'], 'string', 'max' => 32],
            // [['user_id'], 'string', 'max' => 64],
            // [['user_id'], 'exist', 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            // [['project_id'], 'exist', 'targetClass' => Project::class, 'targetAttribute' => ['project_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'Id'            => 'ID',
            'Name'          => 'View Name',
            'Description'   => 'Description',
            'Type'          => 'Type',
            'CreationDate' => 'Creation Date',
            'UserId'       => 'Creator User',
            'ProjectId'    => 'Project',
        ];
    }

    public function getCreatorUser()
    {
        return $this->hasOne(User::class, ['id' => 'UserId']);
    }

    public function getProject()
    {
        return $this->hasOne(Project::class, ['id' => 'ProjectId']);
    }

    public function getFiles()
    {
        return $this->hasMany(File::class, ['Id' => 'FilesId'])
            ->viaTable('FileView', ['ViewsId' => 'Id']);
    }

    public function getUsers()
    {
        return $this->hasMany(User::class, ['id' => 'UserId'])
            ->viaTable('UserView', ['sharedViewsId' => 'id']);
    }

    public function getIssues()
    {
        return $this->hasMany(View::class, ['Id' => 'Id']);
    }
}
