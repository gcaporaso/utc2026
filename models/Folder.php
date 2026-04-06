<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use mdm\admin\models\User;

/**
 * This is the model class for table "folder".
 *
 * @property int $id
 * @property string $name
 * @property string $creation_date
 * @property bool $has_parent
 * @property int|null $parent_id
 * @property string $user_id
 * @property int $project_id
 *
 * @property Folder $parent
 * @property Folder[] $innerFolders
 * @property User $creatorUser
 * @property Project $project
 * @property User[] $users
 * @property File[] $files
 */
class Folder extends ActiveRecord
{
    public static function tableName()
    {
        return 'Folders';
    }

    public function rules()
    {
        return [
            [['Name',  'ProjectId'], 'required'],
            [['CreationDate','UserId','HasParent','ParentId','UserId','CreatorUserId','ProjectId','FolderId'], 'safe'],
            // [['has_parent'], 'boolean'],
            // [['parent_id', 'project_id'], 'integer'],
            // [['name'], 'string', 'max' => 255],
            // [['user_id'], 'string', 'max' => 64],
            // [['parent_id'], 'exist', 'targetClass' => self::class, 'targetAttribute' => ['parent_id' => 'id']],
            // [['user_id'], 'exist', 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            // [['project_id'], 'exist', 'targetClass' => Project::class, 'targetAttribute' => ['project_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'Name' => 'Nome Cartella',
            'CreationDate' => 'Data Creazione',
            'HasParent' => 'Ha cartella superiore',
            'ParentId' => 'Cartella superiore',
            'UserId' => 'Creatore',
            'ProjectId' => 'Progetto',
        ];
    }

    public function getParent()
    {
        return $this->hasOne(Folder::class, ['Id' => 'ParentId']);
    }

    public function getInnerFolders()
    {
        return $this->hasMany(Folder::class, ['ParentId' => 'Id']);
    }

    public function getCreatorUser()
    {
        return $this->hasOne(User::class, ['id' => 'CreatorUserId']);
    }

    public function getProject()
    {
        return $this->hasOne(Project::class, ['Id' => 'ProjectId']);
    }

    public function getUsers()
    {
        return $this->hasMany(User::class, ['id' => 'UserId'])
            ->viaTable('FolderUser', ['SharedFoldersId' => 'Id']);
    }

    public function getFiles()
    {
        return $this->hasMany(File::class, ['FolderId' => 'Id']);
    }
}
