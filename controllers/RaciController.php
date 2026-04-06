<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\Project;
use app\models\ProjectTask;
use app\models\ProjectTaskRaci;
use app\models\TaskCatalog;
use app\models\RaciRole;
use mdm\admin\models\User;

class RaciController extends Controller
{
    /**
     * Mostra la matrice RACI per un progetto
     */
    public function actionMatrix($projectId)
    {
        $this->layout = 'mainbim';
        $project = Project::findOne($projectId);
        if (!$project) {
            throw new NotFoundHttpException("Progetto non trovato");
        }

        $tasks = ProjectTask::find()->where(['project_id' => $projectId])->all();
        $users = User::find()->all(); // eventualmente filtrare per team del progetto
        $roles = RaciRole::find()->all();

        // Recupera le assegnazioni esistenti
        $assignments = ProjectTaskRaci::find()
            ->where(['task_id' => array_column($tasks, 'id')])
            ->all();

        $matrix = [];
        foreach ($assignments as $a) {
            $matrix[$a->task_id][$a->user_id] = $a->raci_role_id;
        }

        if (Yii::$app->request->isPost) {
            $postMatrix = Yii::$app->request->post('matrix', []);

            // Cancella vecchie assegnazioni del progetto
            ProjectTaskRaci::deleteAll(['task_id' => array_column($tasks, 'id')]);

            // Inserisce nuove assegnazioni
            foreach ($postMatrix as $taskId => $userRoles) {
                foreach ($userRoles as $userId => $roleId) {
                    if ($roleId) {
                        $m = new ProjectTaskRaci();
                        $m->task_id = $taskId;
                        $m->user_id = $userId;
                        $m->raci_role_id = $roleId;
                        $m->save(false);
                    }
                }
            }

            Yii::$app->session->setFlash('success', 'Matrice RACI aggiornata.');
            return $this->redirect(['matrix', 'projectId' => $projectId]);
        }

        return $this->render('matrix', [
            'project' => $project,
            'tasks' => $tasks,
            'users' => $users,
            'roles' => $roles,
            'matrix' => $matrix,
        ]);
    }

public function actionTasks($projectId)
{
    $this->layout = 'mainbim';
    $project = Project::findOne($projectId);
    if (!$project) {
        throw new NotFoundHttpException("Progetto non trovato");
    }

    $tasks = ProjectTask::find()->where(['project_id' => $projectId])->all();
    $catalog = TaskCatalog::find()->all();
    $newTask = new ProjectTask(['project_id' => $projectId]);

    if (Yii::$app->request->isPost) {
        // Aggiunta task dal catalogo
        if ($catalogId = Yii::$app->request->post('catalog_id')) {
            $cat = TaskCatalog::findOne($catalogId);
            if ($cat) {
                $task = new ProjectTask([
                    'project_id' => $projectId,
                    'name' => $cat->name,
                    'description' => $cat->description,
                ]);
                $task->save(false);
                Yii::$app->session->setFlash('success', 'Attività aggiunta dal catalogo.');
                return $this->redirect(['tasks', 'projectId' => $projectId]);
            }
        }

        // Aggiunta task personalizzato
        if ($newTask->load(Yii::$app->request->post()) && $newTask->save()) {
            Yii::$app->session->setFlash('success', 'Nuova attività aggiunta.');
            return $this->redirect(['tasks', 'projectId' => $projectId]);
        }
    }

    return $this->render('tasks', [
        'project' => $project,
        'tasks' => $tasks,
        'catalog' => $catalog,
        'newTask' => $newTask,
    ]);
}

public function actionDeleteTask($id, $projectId)
{
    $task = ProjectTask::findOne(['id' => $id, 'project_id' => $projectId]);
    if ($task) {
        $task->delete();
        Yii::$app->session->setFlash('success', 'Attività eliminata.');
    }
    return $this->redirect(['tasks', 'projectId' => $projectId]);
}













}
