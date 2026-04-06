<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property string $title
 * @property string|null $progress
 * @property string|null $priority
 * @property string|null $description
 * @property string|null $creation_date
 * @property string|null $dead_line
 * @property string $creator_user_id
 * @property string|null $assignedto_user_id
 * @property int $project_id
 * @property int|null $team_id
 * @property int|null $view_id
 *
 * @property User $creatorUser
 * @property User|null $assignedtoUser
 * @property Project $project
 * @property Team|null $team
 * @property View|null $view
 */
class Issue extends ActiveRecord
{
    public static function tableName()
    {
        return 'Issue';
    }

    public function rules()
    {
        return [
            [['Title', 'CreatorUserId', 'ProjectId'], 'required'],
            [['CreationDate', 'DeadLine','ViewId','Priority','Progress','Description','CreatorUserId1',
               'ProjectId','AssignedtoUserId'], 'safe'],
            // [['creator_user_id'], 'exist', 'targetClass' => User::class, 'targetAttribute' => ['creator_user_id' => 'id']],
            // [['assignedto_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['assignedto_user_id' => 'id']],
            // [['project_id'], 'exist', 'targetClass' => Project::class, 'targetAttribute' => ['project_id' => 'id']],
            // [['team_id'], 'exist', 'skipOnError' => true, 'targetClass' => Team::class, 'targetAttribute' => ['id' => 'team_id']],
            // [['view_id'], 'exist', 'skipOnError' => true, 'targetClass' => View::class, 'targetAttribute' => ['id' => 'view_id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'Id'                 => 'ID',
            'Title'              => 'Title',
            'Progress'           => 'Stato',
            'Priority'           => 'Priority',
            'Description'        => 'Descrizione',
            'CreationDate'       => 'Data Creazione',
            'DeadLine'           => 'Data Scadenza',
            'CreatorUserId'      => 'Creatore',
            'AssignedtoUserId'   => 'Assegnato a',
            'ProjectId'          => 'Progetto',
            'TeamId'             => 'Team',
            'ViewId'             => 'View',
        ];
    }

    public function getCreatorUser()
    {
        return $this->hasOne(User::class, ['id' => 'CreatorUserId']);
    }

    public function getAssignedtoUser()
    {
        return $this->hasOne(User::class, ['id' => 'AssignedtoUserId']);
    }

    public function getProject()
    {
        return $this->hasOne(Project::class, ['id' => 'ProjectId']);
    }

    public function getTeam()
    {
        return $this->hasOne(Team::class, ['id' => 'TeamId']);
    }

    public function getView()
    {
        return $this->hasOne(View::class, ['id' => 'ViewId']);
    }
}
