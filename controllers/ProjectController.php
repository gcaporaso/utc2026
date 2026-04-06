<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\models\Project;
//use app\models\User;
use mdm\admin\models\User;

class ProjectController extends Controller
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


    // public function beforeAction($action)
    // {
    //    //$this->layout = 'project_main';
    //    //return false;
    // }
    public function init()
    {
        parent::init();
        $this->layout = 'project_main';
        //$this->layout = '@app/views/layouts/blank';
    }

    public function actionIndex($addedUser = null)
    {
        //$this->layout = 'project_main';
        $userLog = User::find()->with('workonProjects')->where(['id' => $addedUser])->one();
        $projectList = Project::find()->with(['creatorUser', 'users'])->all();

        return $this->render('Index', [
            'projects' => $projectList,
            'userLog' => $userLog,
        ]);
    }

    public function actionDetails($id)
    {
        $userId = Yii::$app->user->id;
        $user = User::findOne($userId);
        Yii::$app->session->set('userName', $user->username);
        Yii::$app->session->set('projid', $id);

        $project = Project::find()->with(['creatorUser', 'users'])->where(['id' => $id])->one();
        if (!$project) {
            throw new NotFoundHttpException('Project not found.');
        }

        return $this->render('DetailsProject', ['model' => $project]);
    }

    public function actionCreate()
    {
        $project = new Project();
        $project->CreationDate = date('Y-m-d H:i:s');
        if ($project->load(Yii::$app->request->post()) && $project->validate()) {
            $project->CreatorUserId = Yii::$app->user->id;
            
            $project->save();
            return $this->redirect(['folder/index','projectId'=>$project->Id]);
        }

        return $this->render('CreateProject', ['model' => $project]);
    }

    public function actionCreateFolder($projectId)
    {
        return $this->redirect(['folders/create', 'ProjectId' => $projectId]);
    }

    public function actionEdit($id)
    {
        $project = Project::find()->with('creatorUser')->where(['id' => $id])->one();
        if (!$project) {
            throw new NotFoundHttpException('Project not found.');
        }

        if ($project->load(Yii::$app->request->post()) && $project->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('edit', ['project' => $project]);
    }

    public function actionDelete($id)
    {
        $project = Project::find()->with(['creatorUser', 'folders', 'files'])->where(['id' => $id])->one();
        if (!$project) {
            throw new NotFoundHttpException('Project not found.');
        }

        return $this->render('delete', ['project' => $project]);
    }

    public function actionDeleteConfirmed($id)
    {
        $project = Project::findOne($id);
        if ($project) {
            $project->delete();
        }
        return $this->redirect(['index']);
    }

    public function actionAddUser($projectId)
    {
        $project = Project::find()->with('users')->where(['Id' => $projectId])->one();
        $numUsers = Count(User::find()->with('workonProjects')->asArray()->all());
        $users = User::find()->with('workonProjects')->all();

        return $this->render('AddUser', [
            'project' => $project,
            'numU'=> $numUsers,
            'users' => $users,
        ]);
    }

    public function actionAddedUser($projectId, $ProjectUser)
    {
        $user = User::find()->where(['id' => $ProjectUser])->one();
        $project = Project::findOne($projectId);
        $user->link('workonProjects', $project);
        //$project->link('users', $user);
        return $this->redirect(['add-user', 'projectId' => $projectId]);
    }

    public function actionViewUser($projectId)
    {
        $project = Project::find()->with('users')->where(['id' => $projectId])->one();
        $projectUser = User::find()->with('workonProjects')->all();

        return $this->render('ViewUser', [
            'project' => $project,
            'projectUser' => $projectUser,
        ]);
    }

    public function actionDeleteUser($ProjectUser, $projectId)
    {
        $user = User::find()->with('workonProjects')->where(['id' => $ProjectUser])->one();
        $project = Project::find()->with('users')->where(['Id' => $projectId])->one();
        $user->unlink('workonProjects', $project, true);
        $project->unlink('users', $user, true);
        return $this->redirect(['view-user', 'projectId' => $projectId]);
    }

    protected function findModel($id)
    {
        if (($model = Project::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested project does not exist.');
    }
}
