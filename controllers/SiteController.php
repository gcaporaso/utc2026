<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
//use app\models\User;
use app\models\ContactForm;
use app\models\Edilizia;
use yii\web\UrlManager;
use yii\db\Query;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            $this->layout = 'main-login';
            //Yii::$app->runAction('site/login');
            //Yii::$app->user->loginUrl[0]
            return $this->redirect(['admin/user/login']);
            //return $this->redirect(Yii::$app->user->loginUrl[0]);
            //return $this->redirect(['saml/login']);
        
            
        } else {
// La Home Page visualizza statistiche sulle pratiche presentate nell'anno            
// SELECT count(e.edilizia_id) as numero,t.TITOLO FROM edilizia e inner join titoli t on (e.id_titolo=t.titoli_id) Where e.DataProtocollo between '2018-01-01' AND '2018-12-31' GROUP BY t.TITOLO;
// 
            // numero presentate presentate per tipologia
            $statistiche= Edilizia::find()
                ->Select('count(id_titolo) as numero, id_titolo')
                ->Where(['Between','DataProtocollo', (date('Y')-1).'-01-01',(date('Y')-1).'-12-31'])
                ->GroupBy(['id_titolo'])
                ->asArray()
                ->createCommand()
                ->queryAll();

            // tempi medi permessi rilascio di costruire
                $media1 = (new yii\db\Query)
                ->select(['DATEDIFF(`DataTitolo`,`DataProtocollo`) as media'])
                ->from('edilizia')
                ->Where(['>','DataProtocollo', (date('Y')-3) .'-12-31'])
                ->andWhere(['id_titolo'=>4])
                ->andWhere(['>','NumeroTitolo',0])
                ->all();

                $totale1=0;
                $numero1=0;
                
                foreach ($media1 as $value) {
                    $totale1=$totale1+$value['media'];
                    $numero1=$numero1+1;
                }
                if ($numero1 ==0) {
                    $average1=0;    
                } else {
                $average1=(int)($totale1/$numero1);    
                }
                

                           // tempi medi permessi rilascio di costruire
                $media2 = (new yii\db\Query)
                ->select(['DATEDIFF(`DataAUTORIZZAZIONE`,`DataProtocollo`) as media'])
                ->from('sismica')
                ->Where(['>','DataProtocollo', (date('Y')-5) .'-12-31'])
                ->andWhere(['TipoTitolo'=>1])
                ->andWhere(['>','NumeroAUTORIZZAZIONE',0])
                ->all();

                $totale2=0;
                $numero2=0;
                
                foreach ($media2 as $value) {
                    $totale2=$totale2+$value['media'];
                    $numero2=$numero2+1;
                }
                $average2=(int)($totale2/$numero2);

                
                $oneric = (new yii\db\Query)
                        ->from('edilizia')
                        ->Where(['between', 'DataProtocollo', date((date('Y')-1).'-01-01'), date((date('Y')-1).'-12-31')]);
                
                $oc =  $oneric->sum('Oneri_Costruzione');
                
                $ou =  $oneric->sum('Oneri_Urbanizzazione');
                        
                
                $op = $oneric->sum('Oneri_Pagati');
                        
                $op1 = is_null($op)?0:$op;
//                $oneriu = (new yii\db\Query)
//                        ->from('edilizia')
//                        ->sum('Oneri_Urbanizzazione')
//                       // ->Where(['between', 'DataProtocollo', (date('Y')-1) .'-01-01', (date('Y')-1) .'-12-31'])
//                        ->queryScalar();
//                
//                $onerip = (new yii\db\Query)
//                        ->from('edilizia')
//                        ->sum('Oneri_Pagati')
//                       // ->where(['between', 'DataProtocollo', (date('Y')-1) .'-01-01', (date('Y')-1) .'-12-31'])
//                        ->queryScalar();
                
                $onerideliberati = $oc+$ou;
                

            
            $this->layout = 'main';
            return $this->render('index',['statistiche' => $statistiche,
                                 'media1'=>$average1,
                                 'media2'=>$average2,
                                 'oneri'=>$onerideliberati,
                                 'pagati'=>$op1]);
        }
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        //$model = new User();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        $this->layout = 'main-login';
        $model->password = 'giuseppe01$';
        $model->username = 'pino';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        $this->layout = 'main-login';
        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
    
    
    public function beforeAction($action)
    {
//    // your custom code here, if you want the code to run before action filters,
//    // which are triggered on the [[EVENT_BEFORE_ACTION]] event, e.g. PageCache or AccessControl
//        
//         if (Yii::$app->user->isGuest) {
//            $this->redirect(['admin/user/login']);
//          //return $this->redirect(['site/login']);
//             //$this->redirect(Yii::$app->user->loginUrl[0]);
//         } else {
//             $this->redirect(['site/index']);
//          //return $this->render('index');
//        }

    if (!parent::beforeAction($action)) {
        return false;
    }

    // other custom code here

    return true; // or false to not run the action
    }
    
    
    
    
    
}
