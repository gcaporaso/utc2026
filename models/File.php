<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use mdm\admin\models\User;

/**
 * This is the model class for table "file".
 *
 * @property int $id
 * @property string $name
 * @property string $path
 * @property string|null $type
 * @property string $upload_date
 * @property string|null $creator_user_id
 * @property int $project_id
 * @property int|null $folder_id
 *
 * @property User $creatorUser
 * @property Project $project
 * @property Folder|null $folder
 * @property User[] $users
 * @property View[] $views
 */
class File extends ActiveRecord
{
    public static function tableName()
    {
        return 'Files';
    }

    public function rules()
    {
        return [
            [['Name', 'Path', 'ProjectId','Type'], 'required'],
            [['UploadDate','UserId','CreatorUserId','FolderId'], 'safe'],
            // [['project_id', 'folder_id'], 'integer'],
            // [['name', 'path', 'type'], 'string', 'max' => 255],
            // [['creator_user_id'], 'string', 'max' => 64],
            // [['creator_user_id'], 'exist', 'targetClass' => User::class, 'targetAttribute' => ['creator_user_id' => 'id']],
            // [['project_id'], 'exist', 'targetClass' => Project::class, 'targetAttribute' => ['project_id' => 'id']],
            // [['folder_id'], 'exist', 'targetClass' => Folder::class, 'targetAttribute' => ['folder_id' => 'id']],
        ];
    }

 public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'Name' => 'Nome File',
            'Path' => 'Percorso del File',
            'UploadDate'=>'Data di Caricamento a Progetto',
            'Type' => 'Tipo di File',
            'CreatorUserId' => 'Creatore del File',
            'UserId' => 'Creatore',
            'ProjectId' => 'ID Progetto',
            'FolderId'=>'ID Cartella'
        ];
    }


    public function getCreatorUser()
    {
        return $this->hasOne(User::class, ['id' => 'CreatorUserId']);
    }

    public function getProject()
    {
        return $this->hasOne(Project::class, ['Id' => 'ProjectId']);
    }

    public function getFolder()
    {
        return $this->hasOne(Folder::class, ['Id' => 'FolderId']);
    }

    public function getUsers()
    {
        return $this->hasMany(User::class, ['id' => 'UserId'])
            ->viaTable('FileUser', ['SharedFilesId' => 'Id']);
    }

    public function getViews()
    {
        return $this->hasMany(View::class, ['Id' => 'ViewsId'])
            ->viaTable('FileView', ['FilesId' => 'Id']);
    }
}
