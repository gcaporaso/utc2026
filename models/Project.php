<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use mdm\admin\models\User;

/**
 * This is the model class for table "project".
 *
 * @property int $id
 * @property string $name
 * @property string $units
 * @property string $progress
 * @property string $start_date
 * @property string $creation_date
 * @property string $description
 * @property string $creator_user_id
 *
 * @property User $creatorUser
 * @property User[] $users
 * @property View[] $views
 * @property Folder[] $folders
 * @property File[] $files
 * @property Team[] $teams
 * @property Task[] $tasks
 */
class Project extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Projects';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Name', 'Units', 'Description','Progress','StartDate', 'CreationDate'], 'required'],
            [['Description'], 'string'],
            [['StartDate', 'CreationDate','CreatorUserId'], 'safe'],
            
            
            
            
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'Name' => 'Titolo del Progetto',
            'Units' => 'Unità di Misura',
            'Progress' => 'Stato del Progetto',
            'StartDate' => 'Data di avvio del progetto',
            'CreationDate' => 'Data di Creazione',
            'Description' => 'Descrizione del progetto',
            'CreatorUserId' => 'ID utente creatore',
        ];
    }

    // --- Relations ---

    public function getCreatorUser()
    {
        return $this->hasOne(User::class, ['id' => 'CreatorUserId']);
    }

    public function getUsers()
    {
        //return $this->hasMany(User::class, ['id' => 'id']);
        return $this->hasMany(User::class, ['id' => 'UserId'])
                    ->viaTable('ProjectUser', ['WorkonProjectsId' => 'Id']);
    }

    public function getViews()
    {
        return $this->hasMany(View::class, ['ProjectId' => 'Id']);
    }

    public function getFolders()
    {
        return $this->hasMany(Folder::class, ['ProjectId' => 'Id']);
    }

    public function getFiles()
    {
        return $this->hasMany(File::class, ['ProjectId' => 'Id']);
    }

    public function getTeams()
    {
        return $this->hasMany(Team::class, ['ProjectId' => 'Id']);
    }

    public function getIssue()
    {
        return $this->hasMany(Issue::class, ['Project_id' => 'Id']);
    }
}
