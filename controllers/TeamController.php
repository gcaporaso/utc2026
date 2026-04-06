<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use app\models\Team;
use app\models\User;
use app\models\Project;

class TeamController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex($projectId)
    {
        $teams = Team::find()->with(['creatorUser', 'project', 'users'])->all();
        return $this->render('index', [
            'teams' => $teams,
            'projectId' => $projectId,
        ]);
    }

    public function actionTeamUsers($teamId)
    {
        $team = Team::find()->with('users')->where(['id' => $teamId])->one();
        return $this->render('team-users', [
            'users' => $team->users,
            'teamId' => $teamId,
        ]);
    }

    public function actionDetails($id, $projectId)
    {
        $team = Team::find()->with(['creatorUser', 'project', 'users'])->where(['id' => $id])->one();
        if (!$team) {
            throw new NotFoundHttpException('Team not found.');
        }
        return $this->render('details', [
            'team' => $team,
            'projectId' => $projectId,
        ]);
    }

    public function actionCreate($projectId)
    {
        $team = new Team();
        $project = Project::find()->with('users')->where(['id' => $projectId])->one();
        $users = $project->users;

        if ($this->request->isPost && $team->load(Yii::$app->request->post())) {
            $leader = User::findOne($team->team_leader_id);
            $team->project_id = $projectId;
            $team->users = [$leader];
            if ($team->save()) {
                return $this->redirect(['index', 'projectId' => $projectId]);
            }
        }

        return $this->render('create', [
            'team' => $team,
            'users' => $users,
            'projectId' => $projectId,
        ]);
    }

    public function actionEdit($id, $projectId)
    {
        $team = Team::find()->with('users')->where(['id' => $id])->one();
        if (!$team) {
            throw new NotFoundHttpException('Team not found.');
        }

        if ($this->request->isPost && $team->load(Yii::$app->request->post()) && $team->save()) {
            return $this->redirect(['index', 'projectId' => $projectId]);
        }

        return $this->render('edit', [
            'team' => $team,
            'users' => $team->users,
            'projectId' => $projectId,
        ]);
    }

    public function actionDelete($id, $projectId)
    {
        $team = Team::find()->with('users')->where(['id' => $id])->one();
        if ($team) {
            foreach ($team->users as $user) {
                $team->unlink('users', $user, true);
            }
            $team->delete();
        }
        return $this->redirect(['index', 'projectId' => $projectId]);
    }

    public function actionUsersDetails($id, $projectId)
    {
        $team = Team::find()->with('users')->where(['id' => $id])->one();

        return $this->render('users-details', [
            'users' => $team->users,
            'teamId' => $id,
            'teamLeader' => $team->team_leader_id,
            'teamName' => $team->name,
            'projectId' => $projectId,
        ]);
    }
}
