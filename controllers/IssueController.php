<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use app\models\Issue;
use mdm\admin\models\User;
use app\models\Project;
use app\models\Team;
use app\models\View;

class IssueController extends Controller
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
        $userId = Yii::$app->user->id;
        $user = User::findOne($userId);
        $user->has_tasks = false;
        $user->save();

        $tasks = Issue::find()
            ->with(['assignedtoUser', 'creatorUser', 'project', 'team', 'view', 'team.users'])
            ->where(['project_id' => $projectId])
            ->all();

        return $this->render('index', [
            'tasks' => $tasks,
            'projectId' => $projectId,
        ]);
    }

    public function actionDetails($id)
    {
        $task = Issue::find()
            ->with(['assignedtoUser', 'creatorUser', 'project', 'team', 'view'])
            ->where(['id' => $id])
            ->one();

        if (!$task) {
            throw new NotFoundHttpException('Task not found.');
        }

        return $this->render('details', [
            'task' => $task,
        ]);
    }

    public function actionCreate($projectId)
    {
        $task = new Issue();
        $project = Project::find()->with('users')->where(['id' => $projectId])->one();
        $users = $project->users;

        if ($this->request->isPost && $task->load(Yii::$app->request->post())) {
            if ($task->assignedto_user_id) {
                $user = User::findOne($task->assignedto_user_id);
                $user->has_tasks = true;
                $user->save();
            }
            $task->save();
            return $this->redirect(['index', 'projectId' => $task->project_id]);
        }

        return $this->render('create', [
            'task' => $task,
            'users' => $users,
            'projectId' => $projectId,
        ]);
    }

    public function actionCreateFromView($projectId, $viewId)
    {
        $task = new Issue();
        $project = Project::find()->with('users')->where(['id' => $projectId])->one();
        $users = $project->users;
        $viewName = View::findOne($viewId)->name;

        if ($this->request->isPost && $task->load(Yii::$app->request->post())) {
            $task->save();
            return $this->redirect(['index', 'projectId' => $task->project_id]);
        }

        return $this->render('create-from-view', [
            'task' => $task,
            'users' => $users,
            'projectId' => $projectId,
            'viewId' => $viewId,
            'viewName' => $viewName,
        ]);
    }

    public function actionEdit($id, $projectId)
    {
        $task = Issue::findOne($id);
        if (!$task) {
            throw new NotFoundHttpException('Task not found.');
        }

        $project = Project::find()->with('users')->where(['id' => $projectId])->one();
        $users = $project->users;

        if ($this->request->isPost && $task->load(Yii::$app->request->post()) && $task->save()) {
            return $this->redirect(['index', 'projectId' => $task->project_id]);
        }

        return $this->render('edit', [
            'task' => $task,
            'users' => $users,
            'projectId' => $projectId,
        ]);
    }

    public function actionDelete($id, $projectId)
    {
        $task = Issue::findOne($id);
        if ($task) {
            $task->delete();
        }
        return $this->redirect(['index', 'projectId' => $projectId]);
    }
}
