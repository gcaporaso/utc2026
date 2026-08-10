<?php

namespace app\controllers;

use mdm\admin\components\UserStatus;
use mdm\admin\models\form\Signup;
use Yii;
use yii\base\UserException;
use yii\filters\VerbFilter;

/**
 * Estende il controller vendor mdm\admin aggiungendo:
 * - layout corretto per login/signup
 * - logout verso pagina di login (non home)
 * - signup con assegnazione ruolo Cittadino e stato inattivo
 * - activate/deactivate con flash messages
 */
class AdminUserController extends \mdm\admin\controllers\UserController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs']['actions']['deactivate'] = ['post'];
        return $behaviors;
    }

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }
        $authActions = ['login', 'logout', 'signup', 'request-password-reset', 'reset-password', 'change-password'];
        $this->layout = in_array($action->id, $authActions, true)
            ? '@app/views/layouts/main-login'
            : ($this->module->mainLayout ?? null);
        return true;
    }

    public function actionLogout()
    {
        Yii::$app->getUser()->logout();
        return $this->redirect(['/admin/user/login']);
    }

    public function actionSignup()
    {
        $model = new Signup();
        if ($model->load(Yii::$app->getRequest()->post())) {
            if ($user = $model->signup()) {
                $auth = Yii::$app->authManager;
                $role = $auth->getRole('Cittadino');
                if ($role && !$auth->getAssignment('Cittadino', $user->id)) {
                    $auth->assign($role, $user->id);
                }
                Yii::$app->session->setFlash('success',
                    'Registrazione completata. Il tuo account è in attesa di attivazione da parte dell\'amministratore.'
                );
                return $this->redirect(['/admin/user/login']);
            }
        }
        return $this->render('signup', ['model' => $model]);
    }

    public function actionActivate($id)
    {
        $user = $this->findModel($id);
        if ($user->status == UserStatus::INACTIVE) {
            $user->status = UserStatus::ACTIVE;
            if ($user->save()) {
                Yii::$app->session->setFlash('success', "Utente «{$user->username}» attivato.");
            } else {
                $errors = $user->firstErrors;
                throw new UserException(reset($errors));
            }
        }
        return $this->redirect(['/admin/user/index']);
    }

    public function actionDeactivate($id)
    {
        $user = $this->findModel($id);
        if ($user->id === Yii::$app->user->id) {
            Yii::$app->session->setFlash('error', 'Non puoi disattivare il tuo stesso account.');
            return $this->redirect(['/admin/user/index']);
        }
        if ($user->status == UserStatus::ACTIVE) {
            $user->status = UserStatus::INACTIVE;
            if ($user->save()) {
                Yii::$app->session->setFlash('warning', "Utente «{$user->username}» disattivato.");
            } else {
                $errors = $user->firstErrors;
                throw new UserException(reset($errors));
            }
        }
        return $this->redirect(['/admin/user/index']);
    }
}
