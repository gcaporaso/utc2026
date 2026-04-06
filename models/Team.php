<?php

namespace app\models;

use Yii;
use mdm\admin\models\User;

/**
 * This is the model class for table "team".
 *
 * @property int $id
 * @property string $name
 * @property string|null $creator_user_id
 * @property string|null $team_leader_id
 * @property int $project_id
 *
 * @property User $creatorUser
 * @property User $teamLeader
 * @property Project $project
 * @property User[] $users
 */
class Team extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Teams';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Name', 'ProjectId'], 'required'],
            [['CreatorUserId','TeamLeaderId','ProjectId'],'safe']
            // [['project_id'], 'integer'],
            // [['name'], 'string', 'max' => 255],
            // [['creator_user_id', 'team_leader_id'], 'string', 'max' => 64],
            // [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::class, 'targetAttribute' => ['project_id' => 'id']],
            // [['creator_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['creator_user_id' => 'id']],
            // [['team_leader_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['team_leader_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id'               => 'ID',
            'Name'             => 'Nome del Team',
            'CreatorUserId'  => 'Creatoe User',
            'TeamLeaderId'   => 'Team Leader',
            'ProjectId'       => 'Progetto',
        ];
    }

    /**
     * Relazione con l'utente creatore del team
     */
    public function getCreatorUser()
    {
        return $this->hasOne(User::class, ['id' => 'CreatorUserId']);
    }

    /**
     * Relazione con il team leader
     */
    public function getTeamLeader()
    {
        return $this->hasOne(User::class, ['id' => 'TeamLeaderId']);
    }

    /**
     * Relazione con il progetto
     */
    public function getProject()
    {
        return $this->hasOne(Project::class, ['Id' => 'ProjectId']);
    }

    /**
     * Relazione many-to-many con gli utenti
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['id' => 'UserId'])
            ->viaTable('TeamUsers', ['TeamId' => 'Id']);
    }
}
