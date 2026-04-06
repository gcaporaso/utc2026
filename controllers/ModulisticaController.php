<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\Modulistica;
use app\models\ModulisticaSearch;
use yii\web\UrlManager;
use kartik\dynagrid\models\DynaGridConfig;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;
use kartik\growl\Growl;
use dominus77\sweetalert2\Alert;

class ModulisticaController extends Controller
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
                        'actions' => ['logout','index'],
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
     * Visualizza la Modulistica.
     *
     * @return string
     */
    public function actionIndex()
    {
//  $this->layout = 'yourNewLayout';
	 //$query = Lavori::find();
         //$item=array();
         $this->layout = 'main';
	 $searchModel = new ModulisticaSearch;
	 $model = new DynaGridConfig();
	 $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    return $this->render('index', [
                        'Provider' =>$dataProvider,
			'Search' => $searchModel,
			'model' => $model
        ]);
    }


 /**
     * Creates a new Modulistica model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionNuovo()
    {
        $model = new Modulistica();
        $model->path='modulistica';
        if ($model->load(Yii::$app->request->post())) {
            $fileobj = UploadedFile::getInstance($model, 'nomefile');
            if (!$fileobj) {
                \Yii::$app->getSession()->setFlash('error', 'Non sono riuscito a caricare il file.');
                return $this->redirect('index');
            }    
            $path=Yii::getAlias('@modulistica'); 
            $model->path=$path;
            if ($model->validate()) {
                
                // Crea una directory per la modulistica se non esiste
                     
                    //FileHelper::createDirectory($path, $mode = 0775, $recursive = true); 
                     if (!file_exists($path)) {
                     FileHelper::createDirectory($path, $mode = 0775, $recursive = true); 
                     }
                    
                    $filename = preg_replace('/[-\s]+/', '-',$fileobj->baseName) . '_' . uniqid('') . '.' . $fileobj->extension;
                    $filePath = $path . $filename;
                    //$filePath = $path . preg_replace('/[-\s]+/', '-', $fileobj->name) . '_' . uniqid('');
                    if ($fileobj->saveAs($filePath)) {
                        // rimuove caratteri speciali dal nome del file e aggiunge un identificatore unico alla fine del nome
                        $model->nomefile =  $filename;
                        //$model->id_pratica=$idpratica;
                        //$model->byte=$fileobj->size;
                        //$model->tipo=$fileobj->type;
                        $model->path=$path;
                        //$model->data_update=date('Y-m-d');
                        //$model->tipologia=0
                        
                         Yii::$app->getSession()->setFlash('success', [
                             'type' => 'success',
                             'duration' => 3000,
                             'icon' => 'glyphicon glyphicon-ok',
                             'message' => 'Il Modello è stato aggiunto correttamente!',
                             'title' => 'Registrato',
                             'positonY' => 'top',
                             'positonX' => 'right'
                         ]);
                        
                        
                    } else {
                        \Yii::$app->getSession()->setFlash('error', 'Non sono riuscito a salvare il file sul server.');
                        return $this->redirect(['index']);
                    }
                
                
                
                $model->datarevisione=date('Y-m-d',strtotime($model->datarevisione));
                $model->save();
                //return $this->redirect(['view', 'id' => $model->edilizia_id]);
                //\Yii::$app->getSession()->setFlash('error', 'Errore di validazione.');
                return $this->redirect(['index']);
            // all inputs are valid
            } else {
            // validation failed: $errors is an array containing error messages
                \Yii::$app->getSession()->setFlash('error', 'Errore di validazione.');
                $errors = $model->errors;
            }
        }
        //$model->id_titolo=4;
        return $this->render('create', [
            'model' => $model,
        ]);
    }    
    
    
    public function actionDownload($filename) 
    { 
        if (file_exists($filename)) {
            //ini_set('max_execution_time', 5*60);
            Yii::$app->response->sendFile($filename);
        } else {
            throw new NotFoundHttpException('file ' . $filename .' non trovato');
            Yii::$app->session->setFlash('error', 'Non sono riuscito a trovare il file: ' . $filename);
            
        }
    }
    
    
  /**
     * Signup new user
     * @return string
     */
    public function actionUpdate($id)
    {
    
	$model = Modulistica::findOne($id);
            

     if ($model->load(Yii::$app->request->post())) {
         $fileobj = UploadedFile::getInstance($model, 'fname');
         $model->fname=$fileobj;
            if (!$fileobj) {
                \Yii::$app->getSession()->setFlash('error', 'Non sono riuscito a caricare il file.');
                return $this->redirect(['modulistica/update','id'=>$id]);
            }    
            $path=Yii::getAlias('@modulistica'); 
            $model->path=$path;
         
         if ($model->validate()) {
                    $filename = preg_replace('/[-\s]+/', '-',$fileobj->baseName) . '_' . uniqid('') . '.' . $fileobj->extension;
                    $filePath = $path . $filename;
                    if ($fileobj->saveAs($filePath)) {
                        // rimuove caratteri speciali dal nome del file e aggiunge un identificatore unico alla fine del nome
                        $model->nomefile =  $filename;
                        //$model->id_pratica=$idpratica;
                        //$model->byte=$fileobj->size;
                        //$model->tipo=$fileobj->type;
                        $model->path=$path;
                        //$model->data_update=date('Y-m-d');
                        //$model->tipologia=0
                    } else {
                        Yii::$app->session->setFlash(Alert::TYPE_ERROR, [
                        [
                             
                             'timer' => 3000,
                             'showConfirmButton'=>false,
                             'text' => 'Non sono riuscito a salvare il modello sul server.',
                             //'title' => 'Errore!',
                             'toast'=> true,
                             'position'=>'top-end',
                        ]    
                        ]);
                        return $this->redirect(['modulistica/update','id'=>$id]);
                    }
                
                $model->datarevisione=date('Y-m-d',strtotime($model->datarevisione));
                
            if ($model->save(false)) {
                
                
                   Yii::$app->getSession()->setFlash(Alert::TYPE_SUCCESS, [
                           [
                             
                             'timer' => 3000,
                             'toast'=> true,
                             //'icon' => 'glyphicon glyphicon-ok',
                             'text' => 'Il Modello è stato aggiornato correttamente!',
                             //'title' => 'Registrato',
                             'position'=>'top-end',
                            'showConfirmButton'=>false,
                            ]   
                         ]);
                

             //echo Yii::$app->session->setFlash('success', "Modello Aggiornato!");
            return $this->redirect(['modulistica/index']);
           } else {
               Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_ERROR, [
                       [
                             'timer' => 3000,
                             'position'=>'top-end',
                             'text' => 'Si è verificato un Errore. Il Modello non è stato aggiornato!',
                             //'title' => 'Errore!',
                             'toast'=> true,
                             'showConfirmButton'=>false,
                        ]   
                ]);
               
           }
         }
    } 
    return $this->render('modifica',['model' => $model,'id'=>$id]);

  }
   
  
  /**
     * Signup new user
     * @return string
     */
    public function actionDelete($id)
    {
        $model = Modulistica::findOne($id); 
        $model->delete();
        return $this->redirect(['index']);
    }
    
//    public function beforeAction($action)
//    {
//    // your custom code here, if you want the code to run before action filters,
//    // which are triggered on the [[EVENT_BEFORE_ACTION]] event, e.g. PageCache or AccessControl
//        
////         if (Yii::$app->user->isGuest) {
////            $this->redirect(['admin/user/login']);
////         } else {
////            $this->render('index');
////        }
//
////    if (!parent::beforeAction($action)) {
////        return false;
////    }
//
//    // other custom code here
//
//    return true; // or false to not run the action
//    }
    
    
    
    
    
}
