<?php

namespace app\controllers;

use Yii;
//use yii\base\Model;
//use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
//use yii\filters\VerbFilter;
//use yii\data\Pagination;
use app\models\AllegatiFile;
//use yii\web\NotFoundHttpException;
use app\models\Edilizia;
use app\models\EmailSend;
use app\models\OneriConcessori;
use yii\db\Query;
use app\models\Modulistica;
use app\models\SoggettiEdilizia;
//use app\models\Committente;
//use app\models\Titolare;
use app\models\EdiliziaSearch;
use yii\data\ActiveDataProvider;
use kartik\dynagrid\models\DynaGridConfig;
use kartik\grid\EditableColumnAction;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;
//use App\models\Onericoncessori;
//use yii\helpers\Url;
use Mpdf\Mpdf;
//use yii\helpers\json;
use yii\filters\AccessControl;
use PhpOffice\PhpWord;
use PhpOffice\PhpWord\Shared;
use PhpOffice\PhpWord\Settings;
use Laminas\Mail\Message;
use Laminas\Mail\Transport\Smtp as SmtpTransport;
use Laminas\Mail\Transport\SmtpOptions;
use Laminas\ServiceManager\AbstractPluginManager;
use dominus77\sweetalert2\Alert;

class EdiliziaController extends Controller
{

//public $layout = 'main-g';
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                //'only' => ['logout'],
                'rules' => [
                    [
                        'allow'=>true,
                        'actions' => ['nuova','oneri','view','allegati','update','permesso',
                                      'delete','cancellafile','toword','cancellarata','gestione',
                                      'oneriup','emailsollecito','oneriajax','inviaemail','inviapec',
                                      'pianorateizzazioneajax', 'pdfoneri','autorizzapianoword',
                                      'procedura','download', 'test','elencopratiche','atti','documento',
                                      'editrata','elencopermessi','soggetti','cancellasoggetto'],
                        'roles' => ['RUP Pratiche Edilizie'],
                    ],
                    [
                        'allow'=>true,
                        'actions' => ['index'],
                        'roles' => ['@'],
                    ],
                ],
                 'denyCallback' => function ($rule, $action){
                // everything else is denied
                    if(Yii::$app->request->referrer){
                        return $this->redirect(Yii::$app->request->referrer);
                    } else {
                        return $this->goHome();
                    }
                
                },
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'logout' => ['post'],
//                ],
//            ],
           ],  
        ];
    }

    
    
    
    
    
//    /**
//    * (non-PHPdoc)
//    * @see CController::init()
//    */
//    public function init(){
//	//Yii::import('application.vendors.*');
//	//require_once('@vendor/laminas/laminas-loader/src/ModuleAutoloader.php');
//       // require_once('@vendor/laminas/laminas-mail/src/Trasport/Smtp.php');
//        require_once('@vendor/laminas/laminas-mail/src/Message.php');
//	parent::init();	
//    }
	
    
    
    
// public function actionAggiornaPathAllegati()
// {
//     $prefix = "/var/www/ufficiotecnico/web/allegati/";
//     // recupera tutti i record
//     $allegati = AllegatiFile::find()->all();
//      foreach($allegati as $allegato) {
//        if (strpos($allegato->path, $prefix) === 0) {
//             // rimuove il prefisso
//             $allegato->path = "/var/www/utcbim/web/allegati/" . substr($allegato->path, strlen($prefix));

//             // salva senza validazione (più veloce se il modello ha molte regole)
//             $allegato->save(false);
//         }

//      }
// return $this->renderContent("Aggiornamento completato!");
//     //return $this->redirect('index');
// }    
    
    


public function se_iterable($var)
{
    return is_iterable($var) ? $var : array();
}





    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return ArrayHelper::merge(parent::actions(), [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'oneriup' => [                                       // identifier for your editable action
                'class' => EditableColumnAction::className(),     // action class name
                'modelClass' => OneriConcessori::className(),     // the update model class
                'showModelErrors' => true,
//                'outputValue' => function ($model, $attribute, $key, $index) {
//                    $fmt = Yii::$app->formatter;
//                    $value = $model->$attribute;                 // your attribute value
//                    if (($attribute === 'importopagatorata') or ($attribute === 'importodovutorata')) {           // selective validation by attribute
//                        return $fmt->asDecimal($value, 2);       // return formatted value if desired
//                    } elseif (($attribute === 'datascadenza') or ($attribute === 'datapagamento')) {   // selective validation by attribute
//                        //$dateIT = date("m-d-Y", strtotime($value));
//                        //$dateIT=$fmt->asDate($value, 'short');
//                        return $fmt->asDate($value, 'php:Y-m-d');// return formatted value if desired
//                    }
//                    return '';                                   // empty is same as $value
//               },
            ],
//            'oneridatapagamento' => [                                       // identifier for your editable action
//                'class' => EditableColumnAction::className(),     // action class name
//                'modelClass' => OneriConcessori::className(),     // the update model class
//                'showModelErrors' => true,
//            ],
//            'captcha' => [
//                'class' => 'yii\captcha\CaptchaAction',
//                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
//            ],
//			'add-item' => [
//                'class' => 'relbraun\yii2repeater\actions\AppendAction',
//                'model' => 'frontend\models\Titolare',
//                'contentPath' => 'frontend/view/edilizia/aggiungi', //related to current controller
//            ],
//            'remove-item' => [
//                'class' => 'relbraun\yii2repeater\actions\DeleteAction',
//                'model' => 'frontend\models\Titolare',
//            ]
        ]);
    }
    public function actionValidate()
    {
        $model = new Edilizia();
        $request = \Yii::$app->getRequest();
        if ($request->isPost && $model->load($request->post())) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }

  /**
     * Visualizza la Homepage del modulo Edilizia.
     *
     * @return string
     */
    public function actionIndex()
    {
//  $this->layout = 'yourNewLayout';
	 //$query = Lavori::find();
         //$item=array();
        $this->layout = 'main';
	 $searchModel = new EdiliziaSearch;
	 $model = new DynaGridConfig();
	 $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
         $dataProvider->sort->defaultOrder = ['DataProtocollo' => SORT_DESC];
        //$dataProvider->pagination = ['pageSize' => 15];
         
        // validate if there is a editable input saved via AJAX
        if (Yii::$app->request->post('hasEditable')) {
            // instantiate your book model for saving
            $PraticaId = Yii::$app->request->post('editableKey');
            $axmodel = Edilizia::findOne($PraticaId);
            if (!$axmodel) {
                throw new HttpException(404, Yii::t('wac', 'Model not found!'));
            }
            // store a default json response as desired by editable
            //$out = Json::encode(['output'=>'', 'message'=>'']);

            // fetch the first entry in posted data (there should only be one entry 
            // anyway in this array for an editable submission)
            // - $posted is the posted data for Book without any indexes
            // - $post is the converted array for single model validation
           
            $post = [];
            $posted = current($_POST['Edilizia']);
            //$axmodel->DescrizioneIntervento = $posted['DescrizioneIntervento'];
            $post['Edilizia'] = $posted;
            //$post = ['Edilizia' => $posted];
            //$post = ['DescrizioneIntervento' => $posted];

            // load model like any single model validation
            if ($axmodel->load($post)) {
                // can save model or do something before saving model
                //$value = $axmodel->DescrizioneIntervento;
                $axmodel->save();
                
                $output = '';
                // custom output to return to be displayed as the editable grid cell
                // data. Normally this is empty - whereby whatever value is edited by
                // in the input by user is updated automatically.
                //$output = $axmodel->DescrizioneIntervento;
                $out = Json::encode(['output'=>$output, 'message'=>'']);
                // specific use case where you need to validate a specific
                // editable column posted when you have more than one
                // EditableColumn in the grid view. We evaluate here a
                // check to see if buy_amount was posted for the Book model
//                if (isset($posted['buy_amount'])) {
//                $output = Yii::$app->formatter->asDecimal($model->buy_amount, 2);
//                }

            // similarly you can check if the name attribute was posted as well
            // if (isset($posted['name'])) {
            // $output = ''; // process as you need
            // }
            //$out = Json::encode(['output'=>$output, 'message'=>'']);
            }
            // return ajax json encoded response and exit
            echo $out;
            return;
            }

        return $this->render('index', [
                        'Provider' =>$dataProvider,
			'Search' => $searchModel,
			'model' => $model,
                        //'dateto'=>$date,
        ]);
    }


// /**
//     * Visualizza la Homepage del modulo Edilizia.
//     *
//     * @return string
//     */
//    public function actionEdilizia()
//    {
////  $this->layout = 'yourNewLayout';
//	 //$query = Lavori::find();
//	 $searchModel = new EdiliziaSearch;
//	 $model = new DynaGridConfig();
//	 $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
////$dataProvider = new ActiveDataProvider([
////		    'query' => $query,
////    		'pagination' => [
////        		'pageSize' => 10,
////		    	],
////			]);
//
//        return $this->render('permessi', [
//            'Provider' => $dataProvider,
//			'Search' => $searchModel,
//			'model' => $model,
//        ]);
//    }


    
    /**
     * Action Gesione Pratica
     * @azione parametro opzionale
     *  1 = gestione base
     *  2 = aggiungi allegato
     * @return string
     */
    public function actionGestione($idpratica, $azione = 1)
    {
 	$modelp = Edilizia::findOne($idpratica);
        $modelo = new OneriConcessori();
        $modelemail=new EmailSend;
         //$model = new UploadForm();
        //$modtec = new AllegatiTecnici();
        $elencorate=OneriConcessori::find()
                        ->indexBy('idoneri')
                        ->where(['edilizia_id' => $idpratica]);
        $dataProvider=new ActiveDataProvider(['query' => $elencorate]);
        
        $modela = new AllegatiFile();
         //$model = new UploadForm();
        //$modtec = new AllegatiTecnici();
        
        $allegati=AllegatiFile::find()
                        ->where(['id_pratica' => $idpratica]);
	
        $dp1=new ActiveDataProvider(['query' => $allegati]);
        //$dp2=new ActiveDataProvider(['query' => $alegal]);
                            if (($modela->load(Yii::$app->request->post())) and ($azione==2)) {
                                // get the uploaded file instance. for multiple file uploads
                                // the following data will return an array
                                $fileobj = UploadedFile::getInstance($modela, 'nome');
                                if (!$fileobj) {
                                    \Yii::$app->getSession()->setFlash('error', 'Non sono riuscito a caricare il file.');
                                    return $this->redirect('allegati', ['idpratica'=>$idpratica]);
                                }    

                                if ($modela->validate()) { 

                                         // Crea una directory per la pratica se non esiste (numero di 9 cifre con zeri iniziali)
                                         $path=Yii::getAlias('@allegati') . '/' . trim(sprintf('%09u', $idpratica)) . '/'; 
                                        //FileHelper::createDirectory($path, $mode = 0775, $recursive = true); 
                                         if (!file_exists($path)) {
                                         FileHelper::createDirectory($path, $mode = 0775, $recursive = true); 
                                         }

                                        $filename = preg_replace('/[-\s]+/', '-',$fileobj->baseName) . '_' . uniqid('') . '.' . $fileobj->extension;
                                        $filePath = $path . $filename;
                                        //$filePath = $path . preg_replace('/[-\s]+/', '-', $fileobj->name) . '_' . uniqid('');
                                        if ($fileobj->saveAs($filePath)) {
                                            // rimuove caratteri speciali dal nome del file e aggiunge un identificatore unico alla fine del nome
                                            $modela->nomefile =  $filename;
                                            $modela->id_pratica=$idpratica;
                                            $modela->byte=$fileobj->size;
                                            $modela->tipo=$fileobj->type;
                                            $modela->path=$path;
                                            $modela->data_update=date('Y-m-d');
                                            $modela->tipopratica=1;
                                            //$model->tipologia=0
                                        } else {
                                            \Yii::$app->getSession()->setFlash('error', 'Non sono riuscito a salvare il file sul server.');
                                            return $this->redirect(['allegati', 'idpratica'=>$idpratica,'tipopratica'=>1]);
                                        }


                                    if($modela->save(false)){
                                        return $this->redirect(['allegati', 'idpratica'=>$idpratica,'tipopratica'=>1]);
                                    } else {
                                    // error in saving model
                                    Yii::$app->session->setFlash('error', 'Non sono riuscito a salvare nel database i dati! ' . date('Y-m-d'));
                                    //Yii::$app->session->setFlash('success', 'bla bla 2');
                                    //Yii::$app->session->setFlash('error', 'bla bla 3');
                                    }
                                } else {
                                $errores = $modela->getErrors();
                                $this->render('allegati', array('model' => $errores));

                                }
                            }
        return $this->render('gestione', ['modelp'=>$modelp,
            'modela'=>$modela, 'dpv1'=>$dp1, 'idpratica'=>$idpratica, 'model1'=>$allegati, 'model'=>$modelo, 'dprovider'=>$dataProvider ]);
        
//    return $this->render('gestione',['model' => $model]);

  }
    

    public function actionAddpratica()
     {
        $model=new Edilizia;

        // uncomment the following code to enable ajax-based validation

        // if(isset($_POST['ajax']) && $_POST['ajax']==='edilizia-form')
        // {
        //     echo ActiveForm::validate($model);
        //     Yii::app()->end();
        // }

          // $request = \Yii::$app->getRequest();
          // if ($request->isPost && $model->load($request->post())) {
          //     \Yii::$app->response->format = Response::FORMAT_JSON;
          //     return ['success' => $model->save()];
          // }
          // return $this->renderAjax('registration', [
          //     'model' => $model,
          // ]);


        // if(isset($_POST['edilizia-form']))
        // {
        //   if ($request->isPost && $model->load($request->post())) {
        //     $model->attributes=$_POST['edilizia-form'];
        //     if($model->validate())
        //     {
        //        // form inputs are valid, do something here
        //        //print_r($_REQUEST);
        //       return ['success' => $model->save()];
        //     }
        //   }
        // }
        // $this->renderAjax('aggiungi',array('model'=>$model));


        // //$model = new Comments();
        //    if (Yii::$app->request->isAjax) {
        //        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //
        //        if ($model->load(Yii::$app->requset->post()) && $model->save()) {
        //            echo Json::encode([
        //                'data' => [
        //                    'success' => true,
        //                    'model' => $model,
        //                    'message' => 'Pratica salvata.',
        //                ],
        //                'code' => 0,
        //            ]);
        //            return;
        //        } else {
        //            echo Json::encode([
        //                'data' => [
        //                    'success' => false,
        //                    'model' => $model,
        //                    'message' => 'Errore.',
        //                ],
        //                'code' => 1, // Some semantic codes that you know them for yourself
        //            ]);
        //            return;
        //        }
        //    }
        if (Yii::$app->request->isAjax) {
           $model = new Edilizia();

           if ($model->load(\Yii::$app->request->post())) {
             if ($model->validate()) {
                $model->save();
                return $model->idedilizia;
            }
            $html = $this->renderPartial('edilizia-form');
            return Json::encode($html);
          }
        }
      }


//public function actionListas() {
//    $out = [];
//
//        // echo Json::encode(['output'=>[[1=>'prova1'],[2=>'prova2']], 'selected'=>'']);
////         return;
//
//	print_r($_POST['depdrop_parents']);
//    if (isset($_POST['depdrop_parents'])) {
//        $parents = $_POST['depdrop_parents'];
//        if ($parents != null) {
//            $cat_id = $parents[0];
//			//$out=cup_sottosettore::find()->where(['CodiceSettore'=>$cat_id])->all();
//			$out=ArrayHelper::map(cup_sottosettore::find()->where(['CodiceSettore'=>$cat_id])->all(),'CodiceSottosettore', 'DescrizioneSottosettore');
//			//print_r($out);
//            //$out = self::getSubCatList($cat_id);
//            // the getSubCatList function will query the database based on the
//            // cat_id and return an array like below:
//            // [
//            //    ['id'=>'<sub-cat-id-1>', 'name'=>'<sub-cat-name1>'],
//            //    ['id'=>'<sub-cat_id_2>', 'name'=>'<sub-cat-name2>']
//            // ]
//			//$outf=array();
////			foreach ($out as $cs)
////			{
////				 $outf += [['id'=>'<'. $cs->CodiceSottosettore . '>', 'name'=>'<'.$cs->DescrizioneSottosettore . '>']];
////			}
////			print_r($outf);
//            echo Json::encode(['output'=>$out, 'selected'=>'']);
//            return;
//        }
//    }
//    echo Json::encode(['output'=>'', 'selected'=>'']);
//}
















//public function actionAddCommittente()
// {
//	// Yii::$app->response->format = Response::FORMAT_JSON;
//
//
//	    $model = new Edilizia();
//		$model =$model->load(Yii::$app->request->post('Edilizia'));
//		$modelC = [new Committente()];
////    	//$modelFiles = [new CreditFile()];
////    if (Yii::$app->request->isPost) {
////		//print_r(Yii::$app->request->post());
////        $model->load(Yii::$app->request->post());
////
//
//
//        $cid = Committente::find()->andWhere(['idcommittente' => $id])->all();
//        $data = [['idcommittente' => '', 'DenominazioneCognome' => '','Nome'=>'']];
//        foreach ($cid as $com) {
//            //$data[] = ['id' => $com->idcommittente, 'DenominazioneCognome' => $com->DenominazioneCognome,'Nome'=>$com->Nome];
//			$modelC[] = [$modelC->id => $com->idcommittente, $modelC->DenominazioneCognome => $com->DenominazioneCognome,$modelC->Nome=>$com->Nome];
//        }
//    //    return ['data' => $data];
//	$dataProvider = new ArrayDataProvider(['allModels' => $modelC]);
//    return $this->render('aggiungi', [
//        'model' => $model,
//		'Provider' => $dataProvider,
//
//    ]);
//
//
// }
//
//
//public function actionAddTitolare()
// {
//	if (Yii::app()->request->isPost)
//                {
//	    $model = new Titolare();
//		//$model =$model->load(Yii::$app->request->post('Titolare'));
//	    return $this->renderAjax('_dialogtitolare', [
//        'model' => $model,
//	    ]);
//				}
// }



 
 
 
  /**
     * Creates a new Edilizia model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionNuova()
    {
        $model = new Edilizia();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $model->Stato_Pratica_id=1;
                $model->TitoloOneroso=0;
                $model->DataProtocollo=date('Y-m-d',strtotime($model->DataProtocollo));
                $model->save();
                //return $this->redirect(['view', 'id' => $model->edilizia_id]);
                return $this->redirect(['index']);
            // all inputs are valid
            } else {
            // validation failed: $errors is an array containing error messages
                $errors = $model->errors;
            }
        }
        $model->id_titolo=4;
        return $this->render('nuova', [
            'model' => $model,
        ]);
    } 
 
 
 
 
 
 

/**
     * Signup new user
     * @return string
     */
//    public function actionAggiungi()
//    {
//    if (Yii::$app->request->isGet) {
//		    $model = new Edilizia();
//		     // $modelTitolare = new Titolare();
//		      //  $models = [];
//		//$query = Titolare::find()->where(['idtitolare' => '-1'])->indexBy('idtitolare');;
//		//$dataProvider = new ArrayDataProvider([
//    	//'query' => $query,
//	    //'pagination' => ['pagesize' => 10]
//		//]);
//    //$query = Titolare::find()->where(['idpratica' => -1]);
//	//	 $Provider = new ActiveDataProvider([
//       //'query' => $query]);
//      return $this->render('aggiungi', [
//          'model' => $model,
//		        //'modelTitolare' => $modelTitolare,
//		          //'modelsTitolari'  => (empty($models)) ? [new Titolare] : $models,
//	//	      'Provider' => $Provider,
//	    ]);
//	}
//
//
//
//    if (Yii::$app->request->isPost) {
//		//print_r(Yii::$app->request->post());
//        //$model->load(Yii::$app->request->post('Permesso'));
//	// ***************************************************
//	// Azione = 2 aggiungo una pratica al database  ***
//	// ***************************************************
//		if ($_POST['Azione']=2)	{
//       //const DATE_FORMAT = 'php:Y-m-d';
//			 $model = new Edilizia;
//			 if (!empty($_POST['Edilizia'])) {
//				if ($model->load(Yii::$app->request->post())) {
//					$model->attributes=$_POST['Edilizia'];
//					echo Yii::$app->session->setFlash('success', "Trovato Edilizia!");
//				}
//			 }
//       
//       if ($model->validate()) {
//         //$fmt = ($format == null) ? self::DATE_FORMAT : $format;
//           echo Yii::$app->session->setFlash('success', "Trovato Edilizia!");
//         $model->DataProtocollo=\Yii::$app->formatter->asDate($model->DataProtocollo, 'php:Y-m-d');
//          $model->save();
//          //$model->idedilizia = $model->getPrimaryKey();
//          //echo Yii::$app->session->setFlash('success', "Salvato!");
//          //$query = Titolare::find()->where(['idpratica' => $model->idedilizia]);
//          //$Provider = new ActiveDataProvider(['query' => $query]);
//         return $this->redirect(['edilizia/index']);
////       } else {
////         //$query = Titolare::find()->where(['idpratica' => -1]);    
////          //$Provider = new ActiveDataProvider(['query' => $query]);
////       return $this->render('aggiungi', ['model' => $model]);
//
//       }
//
//      }
//      
//
//
//    }
//
//
//  }


    /**
     * Signup new user
     * @return string
     */
    public function actionUpdate($id)
    {
    
	$model = Edilizia::findOne($id);
            

     if ($model->load(Yii::$app->request->post())) {
         if ($model->validate()) {
            if ($model->save()) {
             echo Yii::$app->session->setFlash('success', "Pratica Aggiornata!");
             return $this->redirect(['index']);
           } else {
               echo Yii::$app->session->setFlash('error', "Si è verificato un Errore. La Pratica non è stata aggiornata!");
           }
         }
    } 
    return $this->render('modifica',['model' => $model]);

  }

  
  
  
  
  /**
     * Signup new user
     * @return string
     */
    public function actionProcedura($idpratica)
    {
    
	$model = Edilizia::findOne($idpratica);
            

//     if ($model->load(Yii::$app->request->post())) {
//         if ($model->validate()) {
//            if ($model->save()) {
//             echo Yii::$app->session->setFlash('success', "Pratica Aggiornata!");
//             return $this->redirect(['index']);
//           } else {
//               echo Yii::$app->session->setFlash('error', "Si è verificato un Errore. La Pratica non è stata aggiornata!");
//           }
//         }
//    } 
    return $this->render('timeline',['model' => $model]);

  }
  
  
  
  
  
  
  
  
  
  
  
  /**
     * @return string
     * @throws \yii\base\Exception
     */
    public function actionUpload()
    {
        $post = \Yii::$app->request->post();
        reset($post);
        $fileName = key($post);
        $attribute = \Yii::$app->request->post($fileName);
        if (is_array($attribute)) {
            reset($attribute);
            $key = key($attribute);
            $fileName .= '[' . $key . ']' . (is_array($attribute[$key]) ? '[0]' : '');
        }

        $file = UploadedFile::getInstanceByName($fileName);

        $filePath = \Yii::$app->security->generateRandomString();
        FileHelper::createDirectory($this->module->basePath . $filePath);

        $file->saveAs($this->module->basePath . $filePath . '/' . $file->name);

        return $filePath;
    }
 

// public function actionFormemail($id) 
// {
//     
//    if ($model->load(Yii::$app->request->post())) {
//         return $this->redirect(['oneri','id'=>$id]); 
//    } 
//    
//        
//        $pratica= Edilizia::findOne($idpratica);
//        $modelemail=new app\Models\EmailSend;
//        $oneri= OneriConcessori::find()
//                        ->where(['edilizia_id' => $id])
//                        ->where(['pagata' => 0]);
//            $morosita='';
//            foreach ($oneri as $rata) {
//                $morosita = $morosita + 'Rata ' . $rata->ratanumero . ' di euro ' . $rata->importodovutorata . ' in scadenza ' . $rata->datascadenza .PHP_EOL;;
//            }
//        $modelemail->efrom='ing@comune.campolidelmontetaburno.bn.it';
//        $modelemail->eto=isset($pratica->richiedente->email)?$pratica->richiedente->email:'';
//        $modelemail->esubject='Sollecito Pagamento Oneri Concessori';
//        $body='Spettabile Sig. ' . $pratica->richiedente->Nomecompleto .PHP_EOL;
//        $body=$body+'Relativamente alla pratica edilizia Permesso di Costruire n. ' . $pratica->NumeroTitolo . ' del ' . date('php:d-m-Y',strtotime($pratica->DataTitolo)) .PHP_EOL;
//        $body=$body+'agli atti di ufficio non risulta il pagamento delle seguenti rate di Oneri Concessori:' .PHP_EOL;
//        $body=$body+$morosita;
//        $body=$body+"Qualora abbia provveduto al pagamento delle suddette rate si prega di consegnare all'ufficio tecnico le relative ricevute".PHP_EOL;
//        $body=$body+'Ove invece non abbia ancora provveduto al pagamento si diffida la sig. vostra al pagamento delle stesse entro dieci giorni.'.PHP_EOL;
//        $body=$body+"consegnando le ricevute all'ufficio tecnico comunale".PHP_EOL;
//        $body=$body+"decorsi i termini suddetti si procederà alla riscossione coattiva".PHP_EOL;
//        $body=$body+"Cordiali Saluti".PHP_EOL;
//        $body=$body+"Comune di Campoli del Monte Taburno".PHP_EOL;
//        $body=$body+"Il Responsabile del Settore Tecnico".PHP_EOL;
//        $body=$body+"Ing. Giuseppe Caporaso".PHP_EOL;
//        
//        
//        $modelemail->ebody=$body;        
////         return $this->renderAjax('formemail', ['modelemail'=>$modelemail,
////            'model'=>$pratica, 'id'=>$id]);
//        
//        return $this->renderView('formemail', [
//        'modelemail' => $modelemail,'id'=>$id
//    ]);
//}
   
    



public function actionOneriajax() 
 {
     
        $idpratica = $_POST['idpratica'];
        $tipo=$_POST['tipo'];
        if (isset($idpratica)) {
        //print_r($idpratica);
        // cerca i dati della pratica edilizia in archivio
        $pratica= Edilizia::findOne($idpratica);
        // cerca le rate già scadute 
        // e cioè con data scadenza precedenti a oggi
        //$oggi=date("Y-m-d");
        $oneri= OneriConcessori::find()
                        ->where(['edilizia_id' => $idpratica])
                        ->andWhere(['pagata' => 0])
                        ->andWhere(['<=','datascadenza',date("Y-m-d")])
                        ->all();
                        
            $morosita='';
            //var_dump($oneri);
        if (isset($oneri)) {    
            $num=0;
            foreach ($oneri as $rata) {
                $num=$num+1;
                $morosita = $morosita . $num . ') Rata ' . $rata->ratanumero . ' di euro ' . $rata->importodovutorata . ' in scadenza ' . date('d-m-Y',strtotime($rata->datascadenza)).PHP_EOL;
            }
        };
        //$modelemail->efrom='ing@comune.campolidelmontetaburno.bn.it';
        //$modelemail->eto=isset($pratica->richiedente->Email)?$pratica->richiedente->Email:'';
        //$modelemail->esubject='Sollecito Pagamento Oneri Concessori';
        $body='Spettabile Sig. ' . $pratica->richiedente->Nomecompleto .PHP_EOL;
        $body=$body . 'Relativamente alla pratica edilizia Permesso di Costruire n. ' . $pratica->NumeroTitolo . ' del ' . date('d-m-Y',strtotime($pratica->DataTitolo)) .PHP_EOL;
        $body=$body . 'agli atti di ufficio non risulta il pagamento delle seguenti rate di Oneri Concessori:' .PHP_EOL;
        $body=$body . $morosita;
        $body=$body . "Qualora abbia provveduto al pagamento delle suddette rate si prega di consegnare all'UTC le relative ricevute.".PHP_EOL;
        $body=$body . 'Ove invece non abbia ancora pagato si diffida la sig. vostra a provvedere entro dieci giorni,'.PHP_EOL;
        $body=$body . "consegnando poi le ricevute all'ufficio tecnico comunale.".PHP_EOL;
        $body=$body . "decorsi i termini suddetti si procederà alla riscossione coattiva.".PHP_EOL;
        $body=$body . "Cordiali Saluti.".PHP_EOL;
        $body=$body . "Comune di Campoli del Monte Taburno".PHP_EOL;
        $body=$body . "Il Responsabile del Settore Tecnico".PHP_EOL;
        $body=$body . "Ing. Giuseppe Caporaso";
        
        if ($tipo=='email') {
            $efrom='ing@comune.campolidelmontetaburno.bn.it';
        } else {
            $efrom='ingcampoli@pec.it';
        }
        //$modelemail->ebody=$body;        
        
         Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
         if ($tipo=='email') {
            $eto=isset($pratica->richiedente->Email)?$pratica->richiedente->Email:'';
         } else {
            $eto=isset($pratica->richiedente->PEC)?$pratica->richiedente->PEC:'';
         }
            //print_r(json_encode($data));
                $dati = [
                    //'success' => true,
                    'efrom' => $efrom,
                    'eto'=>$eto,
                    'esubject'=>'Sollecito Pagamento Oneri Concessori',
                    'ebody'=>$body,
                    //'message' => 'Model has been saved.',
                    ];
                return $dati;
        
        } else {
             Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            //print_r(json_encode($data));
                $dati = [
                    //'success' => true,
                    'efrom' => $efrom,
                    'eto'=>'',
                    'esubject'=>'Sollecito Pagamento Oneri Concessori',
                    'ebody'=>'',
                    //'message' => 'Model has been saved.',
                    ];
                return $dati;
        }
        
        
}












protected function renderView($view, $params = [])
{
    if (Yii::$app->request->isAjax) {
        return $this->renderAjax($view, $params);
    }
    return $this->render($view, $params);
}



    
    
      public function actionOneri($idpratica) 
    {
        $model = new OneriConcessori();
        $modelemail=new EmailSend;
        $modelp = Edilizia::findOne($idpratica);
         //$model = new UploadForm();
        //$modtec = new AllegatiTecnici();
        $elencorate=OneriConcessori::find()
                        ->indexBy('idoneri')
                        ->where(['edilizia_id' => $idpratica]);
        $dataProvider=new ActiveDataProvider(['query' => $elencorate]);
        
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) { 
                             $model->edilizia_id=$idpratica;
                             if ($model->importopagatorata>0) {
                                 $model->pagata=1;
                             } else {
                                 $model->pagata=0;
                             }
                if($model->save(false)){
                    echo Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_SUCCESS, [
                        [
                            //'title' => 'Oneri salvati',
                            'text' => 'Oneri aggiunti!',
                            //'confirmButtonText' => 'Done!',
                            'timer' => 2000,
                            'showConfirmButton'=> false,
                        ]
                     ]);
                    return $this->redirect(['oneri', 'idpratica'=>$idpratica]);
                } else {
                // error in saving model
                Yii::$app->session->setFlash('error', 'Non sono riuscito a salvare rata oneri! ' . date('Y-m-d'));
                //Yii::$app->session->setFlash('success', 'bla bla 2');
                //Yii::$app->session->setFlash('error', 'bla bla 3');
                return $this->redirect(['oneri', 'idpratica'=>$idpratica]);
                }
            } else {
            $errores = $model->getErrors();
            $this->render('oneri', ['idpratica'=>$idpratica,'model' => $errores,
            'modelp'=>$modelp    ]);
             
            }
        } 
        return $this->render('oneri', [
            'model'=>$model, 'dprovider'=>$dataProvider, 'idpratica'=>$idpratica,
            'modelp'=>$modelp    
        ]);
    }

    
    
//     /**
//     * Signup new user
//     * @return string
//     */
//    public function Oneridatapagamento()
//    {
//     //'class' => EditableColumnAction::className(),     // action class name
//     //           'modelClass' => Book::className(),                // the update model class
//             // if (Yii::$app->request->post('hasEditable')) {
//	        // instantiate your pratica model for saving
//	        $id = Yii::$app->request->post('editableKey');
//	        $model = OneriConcessori::findOne($id);
//	        // store a default json response as desired by editable
//	        $out = Json::encode(['output'=>'', 'message'=>'']);
//	        // fetch the first entry in posted data (there should 
//	        // only be one entry anyway in this array for an 
//	        // editable submission)
//	        // - $posted is the posted data for Pratica without any indexes
//	        // - $post is the converted array for single model validation
//	        $post = [];
//	        $posted = current($_POST['pagata']);
//	        $post['pagata'] = $posted;
//	        // load model like any single model validation
//	        if ($model->load($post)) {
//	            // can save model or do something before saving model
//	            $model->save();
//	            // custom output to return to be displayed as the editable grid cell
//	            // data. Normally this is empty - whereby whatever value is edited by 
//	            // in the input by user is updated automatically.
//	            $output = '';
//	            // similarly you can check if the name attribute was posted as well
//	            // if (isset($posted['name'])) {
//	            //   $output =  ''; // process as you need
//	            // } 
//	            $out = Json::encode(['output'=>$output, 'message'=>'']);
//	        } 
//	        // return ajax json encoded response and exit
//	        echo $out;
//	        return;
//             
//               // }
//    }


    
    /*
 * Genera Piano Rateizzazione Oneri Concessori
 * Riceve via Ajax in formato json i seguenti dati
 * @importo importo da rateizzare
 * @numero numero delle rate
 * @intervallo intervallo tra le rate
 * @primadata la data della scadenza della prima rata 
 * Ritorna 
 * success=true se non ci sono errori
 * success=false se ci sono errori
 */    
    public function actionPianorateizzazioneajax()
        {
        //$model=new EmailSend;
    // \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $data = \Yii::$app->request->post();
        if ($data) {
        // calcolo piano di rateizzazione 
            //$dataprimarata = date('d-m-Y',strtotime($data['primarata']));
            //list($giorno, $mese, $anno) = preg_split('/[-\.\/: ]/', $dataprimarata);
            $dataprima=date('d-m-Y',strtotime($data['primadata']));
            $numrate=$data['numrate'];
            $intervallo=$data['intervallo'];
            $pratica=$data['idpratica'];
            $importo=floatval($data['importo']);
            $importorata=$importo/$numrate;
            
            for ($mul = 1; $mul <= $numrate; ++$mul) {
                $oneri= new OneriConcessori;
                $oneri->tiporata=0;
                $oneri->edilizia_id=$pratica;
                $oneri->importodovutorata=$importorata;
                $oneri->ratanumero=$mul;
                if ($mul==1) {
                $oneri->datascadenza=date('Y-m-d',strtotime($data['primadata']));
                } else {
                    $nm=($mul-1)*($intervallo);
                    $newdate = strtotime('+'.$nm.' month' ,strtotime($dataprima)); // facciamo l'operazione
                    $newdate = date ('Y-m-d',$newdate); //trasformiamo la data nel formato accettato dal db YYYY-MM-DD
                    $oneri->datascadenza=$newdate;
                }
                // salva la rata nel database
                $oneri->save();
                $oneri=null;
            }
            
            
        }
        
         Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            //print_r(json_encode($data));
                $dati = [
                    'success' => true,
                    ];
                return $dati;
        
        
        }
    
    
     /**
     * Visualizza un Lavoro User model.
     * @param integer $id
     * @return mixed
     */
    public function actionAutorizzapianoword($idpratica)
    {
    //$pratica=Edilizia::findOne($id);
    $oneri= OneriConcessori::find()
                ->where(['edilizia_id' => $idpratica])
                ->all();
    $model = Edilizia::findOne($idpratica);
    $numrate=OneriConcessori::find()
                ->where(['edilizia_id' => $idpratica])
                ->count();                    
                        
     if (!isset($oneri)) {
         Yii::$app->session->setFlash('error', "Si è verificato un errore! Non sono rieuscito a generare il Prospetto.");
        return $this->redirect(Yii::$app->request->referrer);
     }            
                
   
        require_once \Yii::getAlias('@vendor') . '/phpoffice/phpword/bootstrap.php';

        date_default_timezone_set('UTC');
        error_reporting(E_ALL);
        define('CLI', (PHP_SAPI == 'cli') ? true : false);
        
        
        //$dompdfPath = $vendorDirPath . '/dompdf/dompdf';
        $dompdfPath = '@vendor/dompdf/dompdf';
        if (file_exists($dompdfPath)) {
        define('DOMPDF_ENABLE_AUTOLOAD', false);
        Settings::setPdfRenderer(Settings::PDF_RENDERER_DOMPDF, '@vendor/dompdf/dompdf');
        }

        Settings::loadConfig();
        // Set writers
        $writers = array('Word2007' => 'docx', 'ODText' => 'odt', 'RTF' => 'rtf', 'HTML' => 'html', 'PDF' => 'pdf');
        // Set PDF renderer
        if (null === Settings::getPdfRendererPath()) {
        $writers['PDF'] = null;
        }

        // Turn output escaping on
        Settings::setOutputEscapingEnabled(true);

        // Return to the caller script when runs by CLI
        if (CLI) {
        return;
        }

        // Create the Object.
        //$zip = new ZipArchive();

        // Use same filename for "save" and different filename for "save as".
        //$filename = 'edilizia\modelli\Mod_ED_04_Permesso_Costruire_Standard_Rev01.docx';
        //$outputFilename = 'edilizia\pratiche\P' . $model->id . '\PermessoCostruire.docx';

            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('/var/www/ufficiotecnico/web/modelli/Mod_ED_06_Piano_Rateizzazione_Oneri_Concessori_Rev01.docx');

            $templateProcessor->setValue('NumeroTitolo',$model->NumeroTitolo );
            $templateProcessor->setValue('DataTitolo',Yii::$app->formatter->asDate($model->DataTitolo,'php:d-m-Y'));
            
            // Dati istanza
            $templateProcessor->setValue('DataProtocollo',Yii::$app->formatter->asDate($model->DataProtocollo,'php:d-m-Y'));
            $templateProcessor->setValue('NumeroProtocollo',$model->NumeroProtocollo );
            
            // Richiedente
            $templateProcessor->setValue('Richiedente',$model->richiedente->Cognome . ' ' . $model->richiedente->Nome );
            //$templateProcessor->setValue('ComuneNascita',$model->richiedente->ComuneNascita);
            //$templateProcessor->setValue('ProvinciaNascita',$model->richiedente->ProvinciaNascita);
            //$templateProcessor->setValue('DataNascita',Yii::$app->formatter->asDate($model->richiedente->DataNascita,'d-m-Y'));
            $templateProcessor->setValue('ResidenzaComune',$model->richiedente->ComuneResidenza);
            $templateProcessor->setValue('ResidenzaIndirizzo',$model->richiedente->IndirizzoResidenza);
            $templateProcessor->setValue('ResidenzaProvincia',$model->richiedente->ProvinciaResidenza);
            $templateProcessor->setValue('ResidenzaCivico',$model->richiedente->NumeroCivicoResidenza);
            $templateProcessor->setValue('DescrizioneIntervento',$model->DescrizioneIntervento);
            $templateProcessor->setValue('IndirizzoImmobile',$model->IndirizzoImmobile);
            $templateProcessor->setValue('CatastaleFoglio',$model->CatastoFoglio);
            $templateProcessor->setValue('CatastaleParticella',$model->CatastoParticella);
            //$templateProcessor->setValue('CodiceFiscale',$model->richiedente->CodiceFiscale);
            
            if (isset($model->CatastoSub)) {
                $templateProcessor->setValue('CatastoSub',' Sub.' . $model->CatastoSub);
            } else {
                $templateProcessor->setValue('CatastoSub',' ');
            }
            //$intestato = $model->richiedente->Cognome . ' ' . $model->richiedente->Nome . ' nato a ' . $model->richiedente->ComuneNascita . ' (' . $model->richiedente->ProvinciaNascita . ')';
            //$templateProcessor->setValue('DescrizioneIntestatario',$intestato);
            
            // date termini inizio lavori e fine lavori
//            $date = date('d-m-Y');
//            $inizio = strtotime ('+1 year',strtotime($date)) ; // facciamo l'operazione
//            $fine = strtotime ('+4 year',strtotime($date)) ; // facciamo l'operazione
//            $inizio = date ('d-m-Y' ,$inizio); //trasformiamo la data nel formato accettato dal db YYYY-MM-DD
//            $fine = date ('d-m-Y' ,$fine); //trasformiamo la data nel formato accettato dal db YYYY-MM-DD
//            $templateProcessor->setValue('DataInizioPermesso',$inizio);
//            $templateProcessor->setValue('DataFinePermesso',$fine);
            // Simple table
             $templateProcessor->cloneRow('ratanumero', $numrate);
             $nmr=1;
             foreach ($oneri as $rata) {
                 $templateProcessor->setValue('ratanumero#'. trim($nmr), $rata->ratanumero);
                 $templateProcessor->setValue('importorata#'.trim($nmr), $rata->importodovutorata);
                 $templateProcessor->setValue('scadenza#'.trim($nmr), date('d-m-Y',strtotime($rata->datascadenza)));
                 $nmr=$nmr+1;
             }
            $templateProcessor->setValue('dataredazione', date('d-m-Y'));
            // Crea una directory per la pratica se non esiste (numero di 9 cifre con zeri iniziali)
            $path=Yii::getAlias('@pratiche') . '/' . trim(sprintf('%09u', $idpratica)) . '/'; 
             //FileHelper::createDirectory($path, $mode = 0775, $recursive = true); 
            if (!file_exists($path)) {
                FileHelper::createDirectory($path, $mode = 0777, $recursive = true); 
            }
            $file=$path . '/Piano_Rateizzazione_Oneri_Concessori_Rev01' . '_' . uniqid('') . '.docx';
            $templateProcessor->saveAs($file);
            // scarica il file generato
            if (file_exists($file)) {
                Yii::$app->response->sendFile($file);
            } 
        
        
        


    }
    
     /**
     * edita una rata dall'archivio Oneri Concessori della Pratica
     * @return string
     */
    public function actionEditrata($id,$idpratica)
    {
        $model = OneriConcessori::findOne($id); 
        //$model->delete();
        if ($model->load(Yii::$app->request->post())) {
         if ($model->validate()) {
            if ($model->save()) {
             //echo Yii::$app->session->setFlash('success', "Oneri Aggiornati!");
             echo Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_SUCCESS, [
                        [
                            //'title' => 'Oneri salvati',
                            'text' => 'Oneri aggiornati!',
                            //'confirmButtonText' => 'Done!',
                            'timer' => 1500,
                            'showConfirmButton'=> false,
                            'toast'=> true,
                            'position'=> 'top-end',
                        ]
                     ]);
                 
                return $this->redirect(['oneri','idpratica' => $idpratica]);
           } else {
                echo Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_ERROR, [
                        [
                            'title' => 'Errore',
                            'text' => 'Si è verificato un Errore. Oneri non sono stati aggiornati!',
                            //'confirmButtonText' => 'Done!',
                            'timer' => 2000,
                            'showConfirmButton'=> false,
                            'toast'=> true,
                            'position'=> 'top-end',
                        ]
                     ]);
               
           }
         }
    } 
    return $this->render('editrata',['model'=>$model, 'id' => $id, 'idpratica'=>$idpratica]);

        
    }
    
    
    
    
     /**
     * Cancella una rata dall'archivio Oneri Concessori della Pratica
     * @return string
     */
    public function actionCancellarata($id,$idpratica)
    {
        $model = OneriConcessori::findOne($id); 
        $model->delete();
        return $this->redirect(['oneri','id'=>$idpratica]);
    }

/*
 *  Invia una email di sollecito pagamento Oneri Concessori
 * Riceve via Ajax in formato json i seguenti dati
 * @efrom email mittente
 * @eto email destinatario
 * @esubject Oggetto della email
 * @ebody Messaggio email
 * Ritorna 
 * success=true se non ci sono errori
 * success=false se ci sono errori
 */    
    public function actionInviaemail()
        {
        //$model=new EmailSend;
    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $data = \Yii::$app->request->post();
        if ($data) {
        //if ($model->load(Yii::$app->request->post())) {
             Yii::$app->mailer->compose()
                ->setFrom($data['efrom'])
                ->setTo($data['eto'])
                ->setSubject($data['esubject'])
                ->setTextBody($data['ebody'])
                ->send();
            
             Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                $dati = [
                    'success' => true,
                    ];
                return $dati;
        } else {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
              //print_r(json_encode($data));
                $dati = [
                    'success' => false,
                    ];
                return $dati;
        }
        }
    
    
        /*
 *  Invia una email di sollecito pagamento Oneri Concessori
 * Riceve via Ajax in formato json i seguenti dati
 * @efrom email mittente
 * @eto email destinatario
 * @esubject Oggetto della email
 * @ebody Messaggio email
 * Ritorna 
 * success=true se non ci sono errori
 * success=false se ci sono errori
 */    
    public function actionPec()
        {
        //$model=new EmailSend;
    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $data = \Yii::$app->request->post();
        if ($data) {
        //if ($model->load(Yii::$app->request->post())) {
             Yii::$app->mailer->compose()
                ->setFrom($data['efrom'])
                ->setTo($data['eto'])
                ->setSubject($data['esubject'])
                ->setTextBody($data['ebody'])
                ->send();
            
             Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                $dati = [
                    'success' => true,
                    ];
                return $dati;
        } else {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
              //print_r(json_encode($data));
                $dati = [
                    'success' => false,
                    ];
                return $dati;
        }
        }
    
        
        
        
        
/*
 * Invia una PEC di sollecito pagamento Oneri Concessori
 * Riceve via Ajax in formato json i seguenti dati
 * @efrom email mittente
 * @eto email destinatario
 * @esubject Oggetto della email
 * @ebody Messaggio email
 * Ritorna in formato JSON
 * success=true se non ci sono errori
 * success=false se ci sono errori
 */    
    public function actionInviapec()
    {
    // Setto i parametri della connessione
    $transport = new SmtpTransport();
    $options   = new SmtpOptions([
        'name'              => 'smtps.pec.aruba.it',
        'host'              => 'smtps.pec.aruba.it',
        'connection_class'  => 'login',
        'port'=>465,
        'connection_time_limit' => 300, // recreate the connection 5 minutes after connect()
        'connection_class'      => 'plain',
        'connection_config' => [
            'username' => 'ingcampoli@pec.it',
            'password' => 'pep964caporaso',
            'use_complete_quit'   => false, // Dont send 'QUIT' on __destruct()
            'ssl'      => 'ssl',
            ],
    ]);
    // genero un nuovo messaggio email sulla base dei dati 
    $message = new Message();
    // controllo se il form ha inviato i dati 
    // e valido i dati
    if ($data) {
        $message->addFrom($data['efrom']);
        $message->addTo($data['eto']);
        $message->setSubject($data['esubject']);
        $message->setBody($data['ebody']);
    
        $transport->setOptions($options);

        if( $transport->send($message) === false ) {
            //echo "Errore nell'invio della mail!" ;
            $dati = ['success' => true];
        } else {
            $dati = ['success' => false];
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $dati;
        } else {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $dati = ['success' => false];
            return $dati;
        }
}
    
/*
 * Genera un report della situazione Oneri Concessori di una pratica edilizia
 * PARAMETRI INPUT
 * @idpratica id della pratica edilizia  
 */


public function actionPdfoneri($idpratica) {
    // get your HTML raw content without any layouts or scripts
    $pratica= Edilizia::findOne($idpratica);
     $oneri= OneriConcessori::find()
                ->where(['edilizia_id' => $idpratica])
                ->all();
                        
                        
     if (!isset($oneri)) {
         Yii::$app->session->setFlash('error', "Si è verificato un errore! Non sono rieuscito a generare il Prospetto.");
        return $this->redirect(Yii::$app->request->referrer);
     }
     
    $content =  
'
<p><strong>PROSPETTO ONERI CONCESSORI PRATICA n. ' . $idpratica .'</strong></p>
<p>Richiedente: '. $pratica->richiedente->nomeCompleto .'</p>
<p>OGGETTO: '. $pratica->DescrizioneIntervento .'</p>
<p>Permesso di Costruire n. '. $pratica->NumeroTitolo .' del '. date('d-m-Y',strtotime($pratica->DataTitolo)) .'</p>
<p>Riepilogo Rate Oneri Concessori:</p>
<table width="800" style="border:1px solid black;border-collapse:collapse;">
  <tr>
    <th width="64" height="35" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">Tipo</th>
    <th width="35" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">N.</th>
    <th width="110" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">Importo Dovuto</th>
    <th width="66" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">Data Scadenza</th>
    <th width="56" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">Pagata?</th>
    <th width="104" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">Importo Pagato</th>
    <th width="100" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">Data Pagamento</th>
  </tr>';
    $tipo=['Rata','Unica'];
    $riga='';
    foreach ($oneri as $rata) {
        $rp='Si';
        $dp='';
        if ($rata->pagata==0) {$rp='No';}
        if (isset($rata->datapagamento)) {$dp=date('d-m-Y',strtotime($rata->datapagamento));}
        $riga = $riga . '<tr><td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $tipo[$rata->tiporata] .'</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $rata->ratanumero .'</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $rata->importodovutorata .'</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. date('d-m-Y',strtotime($rata->datascadenza)) .'</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $rp .'</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $rata->importopagatorata . '</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $dp .'</td><tr>';
         $content = $content . $riga; 
     }
  

 $content=$content . ' 
</table>
<div><p></p></div>
<div><p></p></div>
<p>Campoli del Monte Taburno, li '. date("d-m-Y") . '</p>
<p>&nbsp;</p>';       
//$this->renderPartial('_reportView');
    
    // setup kartik\mpdf\Pdf component
    $pdf = new Pdf([
        'filename'=>'Prospetto_Oneri_Concessori_Pratica_' . $idpratica,
        // set to use core fonts only
        'mode' => Pdf::MODE_CORE, 
        // A4 paper format
        'format' => Pdf::FORMAT_A4, 
        // portrait orientation
        'orientation' => Pdf::ORIENT_PORTRAIT, 
        // stream to browser inline
        'destination' => Pdf::DEST_BROWSER, 
        // your html content input
        'content' => $content,  
        // format content from your own css file if needed or use the
        // enhanced bootstrap css built by Krajee for mPDF formatting 
        'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
        // any css to be embedded if required
        'cssInline' => '.kv-heading-1{font-size:18px}', 
         // set mPDF properties on the fly
        'options' => ['title' => 'Prospetto Riepilogo Oneri Concessori Pratica Edilizia n. ' . $idpratica],
         // call mPDF methods on the fly
        'methods' => [ 
            'SetHeader'=>['Comune di Campoli del Monte Taburno - Ufficio Tecnico'], 
            'SetFooter'=>['{PAGENO}'],
        ]
    ]);
    
    // return the pdf output as per the destination setting
    return $pdf->render(); 
}


private function numpratiche($Anno, $id, $elenco)
{
    $numero=0;
    $tot=count($elenco);
  foreach ($elenco as $item) {
       if (($item['Anno']==$Anno) and ($item['id_titolo']==$id))
       {
           $numero = $item['Numero'];
           break;
       }
   }
   
   return $numero;
}


/**
 * Genera un PDF con una tabella contenente il REPORT con il numero delle pratiche 
 * presenti in archivio divise per anno e per tipologia
 */
public function actionElencopratiche() {

/*
 * Genera un array con gli anni in cui sono state registrate pratiche
 * per la generazione di report
 */
    // Questa query funziona 
    $anni = Edilizia::find()
            ->select(['Year(DataProtocollo) AS Anno'])->distinct()
            ->groupBy('Anno')
            ->orderBy('Anno ASC')
            ->asArray()
            ->all();

    

    
    // Questa query funziona 
    $pratiche= Edilizia::find()
            ->select(['Year(DataProtocollo) AS Anno','id_titolo' ,'COUNT(id_titolo) as Numero'])->distinct()
            ->groupBy('Anno, id_titolo')
            ->orderBy('Anno ASC')
            ->asArray()
            ->all();

    
// return $this->render('test', ['html' =>json_encode($anni)]);
    
    
     if (!isset($pratiche)) {
         Yii::$app->session->setFlash('error', "Si è verificato un errore! Non sono rieuscito a generare la Tabella.");
        return $this->redirect(Yii::$app->request->referrer);
     }
     
    $content =  
'
<p><strong>ELENCO PRATICHE EDILIZIE IN ARCHIVIO</strong></p>
<p></p>

<table width="800" style="border:1px solid black;border-collapse:collapse;">
  <tr>
    <th width="64" height="35" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">Anno</th>
    <th width="35" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">CILA</th>
    <th width="110" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">SCIA</th>
    <th width="66" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">SUPERSCIA</th>
    <th width="56" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">Permesso</th>
    <th width="104" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">SCA</th>
    <th width="100" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">CIL</th>
    <th width="100" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">Cond.47/85</th>
    <th width="100" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">Cond.724/94</th>
    <th width="100" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">Conc.Edilizia</th>
    <th width="100" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">Altro</th>
  </tr>';
//    $anno=0;
//    $EAnni=Array();
//    //$nanni=[];
//    $nanni = ['' => [0,0,0,0,0,0,0,0,0,0,0,0,0]];
//    foreach ($pratiche as $item) {
//        if ($item->Anno <> $anno) {
//            $EAnni[]=$item->Anno;
//            $anno=$item->Anno;
//        }
//    };
    
    
            
    
    
            
//    $tipo=['Rata','Unica'];
    $riga='';
    foreach ($anni as $anno) {
         //$pratiche->andWhere(['=', 'Anno', $anno]);
         
//        $rp='Si';
//        $dp='';
//        if ($rata->pagata==0) {$rp='No';}
//        if (isset($rata->datapagamento)) {$dp=date('d-m-Y',strtotime($rata->datapagamento));}
        $riga = '<tr><td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $anno['Anno'] .'</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $this->numpratiche($anno['Anno'],1,$pratiche) .'</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $this->numpratiche($anno['Anno'],2,$pratiche) .'</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $this->numpratiche($anno['Anno'],3,$pratiche) .'</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $this->numpratiche($anno['Anno'],4,$pratiche) .'</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $this->numpratiche($anno['Anno'],5,$pratiche) .'</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $this->numpratiche($anno['Anno'],6,$pratiche) .'</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $this->numpratiche($anno['Anno'],8,$pratiche) .'</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $this->numpratiche($anno['Anno'],9,$pratiche) .'</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $this->numpratiche($anno['Anno'],11,$pratiche) .'</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $this->numpratiche($anno['Anno'],12,$pratiche) .'</td><tr>';
         $content = $content . $riga; 
     }
  

 $content=$content . ' 
</table>
<div><p></p></div>
<div><p></p></div>
<p>Campoli del Monte Taburno, li '. date("d-m-Y") . '</p>
<p>&nbsp;</p>';       
//$this->renderPartial('_reportView');
    
    // setup kartik\mpdf\Pdf component
//    $pdf = new Pdf([
//        'filename'=>'Elenco_Pratiche_Archivio' . date("d-m-Y"),
//        // set to use core fonts only
//        'mode' => Pdf::MODE_CORE, 
//        // A4 paper format
//        'format' => Pdf::FORMAT_A4, 
//        // portrait orientation
//        'orientation' => Pdf::ORIENT_PORTRAIT, 
//        // stream to browser inline
//        'destination' => Pdf::DEST_BROWSER, 
//        // your html content input
//        'content' => $content,  
//        // format content from your own css file if needed or use the
//        // enhanced bootstrap css built by Krajee for mPDF formatting 
//        'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
//        // any css to be embedded if required
//        'cssInline' => '.kv-heading-1{font-size:18px}', 
//         // set mPDF properties on the fly
//        'options' => ['title' => 'Elenco Pratiche in Archivio alla data ' . date("d-m-Y")],
//         // call mPDF methods on the fly
//        'methods' => [ 
//            'SetHeader'=>['Comune di Campoli del Monte Taburno - Ufficio Tecnico'], 
//            'SetFooter'=>['{PAGENO}'],
//        ]
//    ]);
    //die( var_dump( Yii::app()->getRequest()->getIsSecureConnection() ) );
    // return the pdf output as per the destination setting
  //  return $pdf->render(); 
    
   //return $this->render('test', ['html' =>$content]);
 
 $mpdf = new \Mpdf\Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4',
        'tempDir' => Yii::getAlias('@webroot') . '/tmp/mpdf'
//        'margin_left' => 0,
//        'margin_right' => 0,
//        'margin_top' => 0,
//        'margin_bottom' => 0,
//        'margin_header' => 0,
//        'margin_footer' => 0
    ]);
 
    $mpdf->SetHTMLHeader('<div align="center"><p>Comune di Campoli del Monte Taburno - Ufficio Tecnico</p></div>');
    
     $mpdf->WriteHTML($content);
    $mpdf->Output('elencopratiche.pdf', 'I');
    return;
 
 
    
}



/**
 * Genera un PDF con una tabella contenente il REPORT con elenco permessi costruire 
 * presenti in archivio divise per anno
 */
public function actionElencopermessi() {

/*
 * Genera un array con gli anni in cui sono state registrate pratiche
 * per la generazione di report
 */


// Questa query funziona 
    $pratiche = Edilizia::find()
            ->select(['id_committente', 'NumeroTitolo', 'DataTitolo', 'NumeroProtocollo', 'DataProtocollo', 'DescrizioneIntervento'])
            ->where(['Year(DataTitolo)' => date('Y')])
            //->where(['Year(DataTitolo)' => 2013])
            ->orderBy('NumeroTitolo ASC','DataTitolo ASC')
//            ->asArray()
            ->all();

//    // Questa query funziona 
//    $anni = Edilizia::find()
//            ->select(['Year(DataProtocollo) AS Anno'])->distinct()
//            ->groupBy('Anno')
//            ->orderBy('Anno ASC')
//            ->asArray()
//            ->all();

    

    
//    // Questa query funziona 
//    $pratiche= Edilizia::find()
//            ->select(['Year(DataProtocollo) AS Anno','id_titolo' ,'COUNT(id_titolo) as Numero'])->distinct()
//            ->groupBy('Anno, id_titolo')
//            ->orderBy('Anno ASC')
//            ->asArray()
//            ->all();

    
// return $this->render('test', ['html' =>json_encode($anni)]);
    
    
     if (!isset($pratiche)) {
         Yii::$app->session->setFlash('error', "Si è verificato un errore! Non sono rieuscito a generare la Tabella.");
        return $this->redirect(Yii::$app->request->referrer);
     }
     
    $content =  
'
<p><strong>ELENCO PERMESSI DI COSTRUIRE RILASCIATI ANNO ' . date("Y") . ',</strong></p>
<p></p>

<table width="800" style="border:1px solid black;border-collapse:collapse;">
  <tr>
    <th width="32" height="35" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">Numero</th>
    <th width="64" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">Data</th>
    <th width="110" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">Richiedente</th>
    <th width="32" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">Protocollo</th>
    <th width="64" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">Data Protocollo</th>
    <th width="104" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">Descrizione</th>
  </tr>';
//    $anno=0;
//    $EAnni=Array();
//    //$nanni=[];
//    $nanni = ['' => [0,0,0,0,0,0,0,0,0,0,0,0,0]];
//    foreach ($pratiche as $item) {
//        if ($item->Anno <> $anno) {
//            $EAnni[]=$item->Anno;
//            $anno=$item->Anno;
//        }
//    };
    
    
            
    
    
            
//    $tipo=['Rata','Unica'];
    $riga='';
    foreach ($pratiche as $pratica) {
         //$pratiche->andWhere(['=', 'Anno', $anno]);
         
//        $rp='Si';
//        $dp='';
//        if ($rata->pagata==0) {$rp='No';}
//        if (isset($rata->datapagamento)) {$dp=date('d-m-Y',strtotime($rata->datapagamento));}
//        $riga = '<tr><td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $pratica['NumeroTitolo'] .'</td>'
//                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $pratica['DataTitolo'] .'</td>'
//                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $pratica['NumeroProtocollo'] .'</td>'
//                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $pratica['DataProtocollo'] .'</td>'
//                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $pratica['DataTitolo'] .'</td>'
//                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $pratica['DescrizioneIntervento'] .'</td><tr>';
         $riga = '<tr><td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $pratica->NumeroTitolo .'</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. Yii::$app->formatter->asDate($pratica->DataTitolo,'php:d-m-Y') .'</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $pratica->richiedente->nomeCompleto .'</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $pratica->NumeroProtocollo .'</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. Yii::$app->formatter->asDate($pratica->DataProtocollo,'php:d-m-Y') .'</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $pratica->DescrizioneIntervento .'</td><tr>';
         $content = $content . $riga; 
     }
  

 $content=$content . ' 
</table>
<div><p></p></div>
<div><p></p></div>
<p>Campoli del Monte Taburno, li '. date("d-m-Y") . '</p>
<p>&nbsp;</p>';       
//$this->renderPartial('_reportView');
    
 
 
 
    $mpdf = new \Mpdf\Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4',
        'tempDir' => Yii::getAlias('@webroot') . '/tmp/mpdf'
//        'margin_left' => 0,
//        'margin_right' => 0,
//        'margin_top' => 0,
//        'margin_bottom' => 0,
//        'margin_header' => 0,
//        'margin_footer' => 0
    ]);
 
 
 
 
 
//    // setup kartik\mpdf\Pdf component
//    $pdf = new Pdf([
//        'filename'=>'Elenco_Permessi_Anno' . date("d-m-Y"),
//        // set to use core fonts only
//        'mode' => Pdf::MODE_CORE, 
//        // A4 paper format
//        'format' => Pdf::FORMAT_A4, 
//        // portrait orientation
//        'orientation' => Pdf::ORIENT_PORTRAIT, 
//        // stream to browser inline
//        'destination' => Pdf::DEST_BROWSER, 
//        // your html content input
//        'content' => $content,  
//        // format content from your own css file if needed or use the
//        // enhanced bootstrap css built by Krajee for mPDF formatting 
//        'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
//        // any css to be embedded if required
//        'cssInline' => '.kv-heading-1{font-size:18px}', 
//         // set mPDF properties on the fly
//        'options' => ['title' => 'Elenco Pratiche in Archivio alla data ' . date("d-m-Y")],
//         // call mPDF methods on the fly
//        'methods' => [ 
//            'SetHeader'=>['Comune di Campoli del Monte Taburno - Ufficio Tecnico'], 
//            'SetFooter'=>['{PAGENO}'],
//        ]
//    ]);
    //die( var_dump( Yii::app()->getRequest()->getIsSecureConnection() ) );
    // return the pdf output as per the destination setting
    //return $pdf->render(); 
    
   //return $this->render('test', ['html' =>$content]);
    $mpdf->SetHTMLHeader('<div align="center"><p>Comune di Campoli del Monte Taburno - Ufficio Tecnico</p></div>');
    
     $mpdf->WriteHTML($content);
    $mpdf->Output('elencopermessi.pdf', 'I');
    return;
    
    
    
}





public function actionAtti($idpratica) 
    {
        //$model = new AllegatiFile();
       $model = new \yii\base\DynamicModel(['modello_id']);
       $model->addRule(['modello_id'], 'safe');
        $elenco=Modulistica::find()
                        ->where(['categoria'=>1]);
        $modelp = Edilizia::findOne($idpratica);
        if ($model->load(Yii::$app->request->post())) {
            return $this->redirect(['edilizia/documento', 'modello_id'=>$model->modello_id,'idpratica'=>$idpratica]);
        }
        
        
        
        return $this->render('atti', [
            'model'=>$model,'idpratica'=>$idpratica,'modelp'=>$modelp
        ]);
    }
  

public function actionSoggetti($idpratica) 
    {
        $model = new SoggettiEdilizia();
        $model->percentuale=100;
         //$model = new UploadForm();
        //$modtec = new AllegatiTecnici();
        $soggetti=SoggettiEdilizia::find()->where(['edilizia_id' => $idpratica]);
        $modelp = Edilizia::findOne($idpratica);
        $dp=new ActiveDataProvider(['query' => $soggetti]);
        if ($model->load(Yii::$app->request->post())) {
            
            
                $model->edilizia_id=$idpratica;
            if ($model->validate()) { 
                if($model->save()){
                    Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_SUCCESS, [
                    [
                        //'title' => 'Errore',
                        'text' => 'Soggetto aggiunto con successo!',
                        //'confirmButtonText' => 'Done!',
                        'timer' => 2000,
                        'showConfirmButton'=>false,
                        'position'=>'top-end',
                    ]
                 ]);
                    return $this->redirect(['soggetti', 'idpratica'=>$idpratica]);
                } else {
                // error in saving model
                Yii::$app->session->setFlash('error', 'Non sono riuscito ad aggiungere il soggetto! ');
                }
            } else {
                $errors = $model->errors;
                //$errores = $model->getErrors();
                //$this->render('soggetti', ['model' => $model,'dpv'=>$dp,'idpratica'=>$idpratica,'modelp'=>$modelp]);
             
            }
        }
        return $this->render('soggetti', [
            'model'=>$model, 'dpv'=>$dp,'idpratica'=>$idpratica,'modelp'=>$modelp
        ]);
    }

      /**
     * Signup new user
     * @return string
     */
    public function actionCancellasoggetto($id,$idpratica)
    {
        $model = SoggettiEdilizia::findOne($id); 
        $model->delete();
        return $this->redirect(['soggetti','idpratica'=>$idpratica]);
    }
    


    
  public function actionAllegati($idpratica) 
    {
        $model = new AllegatiFile();
         //$model = new UploadForm();
        //$modtec = new AllegatiTecnici();
        $allegati=AllegatiFile::find()
                        ->where(['id_pratica' => $idpratica,'tipopratica'=>1]);
        $modelp = Edilizia::findOne($idpratica);
//        $atecnici=AllegatiFile::find()
//                        ->where(['id_pratica' => $idpratica,'tipologia'=>0,'tipopratica'=>1]);
//	$alegal=AllegatiFile::find()
//                        ->where(['id_pratica' => $idpratica,'tipologia'=>1,'tipopratica'=>1]);
        $dp=new ActiveDataProvider(['query' => $allegati]);
//        $dp2=new ActiveDataProvider(['query' => $alegal]);
        if ($model->load(Yii::$app->request->post())) {
            // get the uploaded file instance. for multiple file uploads
            // the following data will return an array
            //$fileobj = UploadedFile::getInstance($model, 'nome');
            $file = UploadedFile::getInstance($model, 'nome');
            $model->nome=$file;
            if (!$model->nome) {
                Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_ERROR, [
                    [
                        //'title' => 'Errore',
                        'text' => 'Si è verificato un errore:' ,
                        //'confirmButtonText' => 'Done!',
                        'timer' => 2000,
                        'showConfirmButton'=>false,
                        'position'=>'top-end',
                    ]
                 ]);
                //\Yii::$app->getSession()->setFlash(Alert::TYPE_ERROR, 'Congratulations!');
                return $this->redirect(['edilizia/allegati', 'dpv'=>$dp,'idpratica'=>$idpratica,'modelp'=>$modelp]);
            }
            
            
            // Crea una directory per la pratica se non esiste (numero di 9 cifre con zeri iniziali)
            $path=Yii::getAlias('@pathallegati') . 'edilizia/' . trim(sprintf('%09u', $idpratica)) . '/'; 
            //FileHelper::createDirectory($path, $mode = 0775, $recursive = true); 
            if (!file_exists($path)) {
                FileHelper::createDirectory($path, $mode = 0775, $recursive = true); 
                }
            $filename = preg_replace('/[-\s]+/', '-',$model->nome->baseName) . '_' . uniqid('') . '.' . $model->nome->extension;
            $filePath = $path . $filename;
            $model->nome->name=$filename;
            //$filePath = $path . preg_replace('/[-\s]+/', '-', $fileobj->name) . '_' . uniqid('');
            if (!$model->nome->saveAs($filePath,false)) {
            // $this->nome->saveAs('allegatitecnici/' . $this->nome->baseName . '.' . $this->nome->extension);
                Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_ERROR, [
                    [
                        //'title' => 'Errore',
                        'text' => 'Si è verificato un errore nel caricare il file!',
                        //'confirmButtonText' => 'Done!',
                        'timer' => 2000,
                        'showConfirmButton'=>false,
                        'position'=>'top-end',
                    ]
                 ]);    
                return $this->redirect(['edilizia/allegati', 'dpv'=>$dp,'idpratica'=>$idpratica,'modelp'=>$modelp]);
            }
                //\Yii::$app->getSession()->setFlash(Alert::TYPE_ERROR, 'Congratulations!');
                
            if ($model->validate()) { 
                 
//                     // Crea una directory per la pratica se non esiste (numero di 9 cifre con zeri iniziali)
                     //$path=Yii::getAlias('@allegati') . '/' . trim(sprintf('%09u', $idpratica)) . '/'; 
//                    //FileHelper::createDirectory($path, $mode = 0775, $recursive = true); 
//                     if (!file_exists($path)) {
//                     FileHelper::createDirectory($path, $mode = 0775, $recursive = true); 
//                     }
//                    
//                    $filename = preg_replace('/[-\s]+/', '-',$fileobj->baseName) . '_' . uniqid('') . '.' . $fileobj->extension;
//                    $filePath = $path . $filename;
//                    //$filePath = $path . preg_replace('/[-\s]+/', '-', $fileobj->name) . '_' . uniqid('');
//                    if ($fileobj->saveAs($filePath)) {
                        // rimuove caratteri speciali dal nome del file e aggiunge un identificatore unico alla fine del nome
                        $model->nomefile =  $filename; 
                        $model->id_pratica=$idpratica;
                        $model->byte= $model->nome->size; //$fileobj->size;
                        $model->tipo= $model->nome->type; //$fileobj->type;
                        $model->path=$path;
                        $model->data_update=date('Y-m-d');
                        $model->tipopratica=1;
                        //$model->tipologia=0
//                    } else {
//                        \Yii::$app->getSession()->setFlash('error', 'Non sono riuscito a salvare il file sul server.');
//                        return $this->redirect(['allegati', 'idpratica'=>$idpratica,'tipopratica'=>1]);
//                    }
                
            
                if($model->save(false)){
                    Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_SUCCESS, [
                    [
                        //'title' => 'Errore',
                        'text' => 'Allegato aggiunto con successo!',
                        //'confirmButtonText' => 'Done!',
                        'timer' => 2000,
                        'showConfirmButton'=>false,
                        'position'=>'top-end',
                    ]
                 ]);
                    return $this->redirect(['allegati', 'idpratica'=>$idpratica,'tipopratica'=>1]);
                } else {
                // error in saving model
                Yii::$app->session->setFlash('error', 'Non sono riuscito a salvare nel database i dati! ' . date('Y-m-d'));
                //Yii::$app->session->setFlash('success', 'bla bla 2');
                //Yii::$app->session->setFlash('error', 'bla bla 3');
                }
            } else {
            $errores = $model->getErrors();
            $this->render('allegati', ['model' => $errores,'dpv'=>$dp,'idpratica'=>$idpratica,'modelp'=>$modelp]);
             
            }
        }
        return $this->render('allegati', [
            'model'=>$model, 'dpv'=>$dp,'idpratica'=>$idpratica,'modelp'=>$modelp
        ]);
    }
    
    
   
     /**
     * Signup new user
     * @return string
     */
    public function actionCancellafile($id,$idpratica)
    {
        $model = AllegatiFile::findOne($id); 
        $model->delete();
        return $this->redirect(['allegati','idpratica'=>$idpratica]);
    }
    
    
    
    
    
    public function actionEliminaAllegati($id) 
    {
        $model = new AllegatiFile();
         //$model = new UploadForm();
        //$modtec = new AllegatiTecnici();
        $atecnici=AllegatiFile::find()
                        ->where(['id_pratica' => $id,'tipologia'=>0]);
	$alegal=AllegatiFile::find()
                        ->where(['id_pratica' => $id,'tipologia'=>1]);
        $dp1=new ActiveDataProvider(['query' => $atecnici]);
        $dp2=new ActiveDataProvider(['query' => $alegal]);
        if ($model->load(Yii::$app->request->post())) {
            // get the uploaded file instance. for multiple file uploads
            // the following data will return an array
            $fileobj = UploadedFile::getInstance($model, 'nome');

            if ($model->validate()) { 
                 if ($fileobj) {
                     // Crea una directory per la pratica se non esiste (numero di 9 cifre con zeri iniziali)
                     $path=Yii::getAlias('@allegati') . '/' . trim(sprintf('%09u', $id)) . '/'; 
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
                        $model->id_pratica=$id;
                        $model->byte=$fileobj->size;
                        $model->tipo=$fileobj->type;
                        $model->path=$path;
                        $model->data_update=date('Y-m-d');
                        //$model->tipologia=0
                    }
                }
            
                if($model->save(false)){
                    return $this->redirect(['allegati', 'id'=>$id]);
                } else {
                // error in saving model
                Yii::$app->session->setFlash('error', 'Non sono riuscito a salvare nel database i dati! ' . date('Y-m-d'));
                //Yii::$app->session->setFlash('success', 'bla bla 2');
                //Yii::$app->session->setFlash('error', 'bla bla 3');
                }
            } else {
            $errores = $model->getErrors();
            $this->render('allegati', array('model' => $errores));
             
            }
        }
        return $this->render('allegati', [
            'model'=>$model, 'dpv1'=>$dp1, 'dpv2'=>$dp2,'id'=>$id
        ]);
    }
    
    
// public function downloadFile($filename){
//    if(!empty($filename)){
//        header("Content-type:application/pdf"); //for pdf file
//        //header('Content-Type:text/plain; charset=ISO-8859-15');
//        //if you want to read text file using text/plain header
//        header('Content-Disposition: attachment; filename="'.basename($filename).'"');
//        header('Content-Length: ' . filesize($filename));
//        readfile($filename);
//        Yii::$app->end();
//    } else {
//         Yii::$app->session->setFlash('error', 'Non sono riuscito a trovare il file: ' . $filename);
//    }
//        
//}

//public function actionDownload($id){
//    $model=Ip::model()->findByPk($id);
//    $path = Yii::app()->basePath . 'location of file to be downloaded'.$model->filename;
//    $this->downloadFile($path);
//}
  
  
   public function actionDownload($filename) 
    { 
       
        if (file_exists($filename)) {
            Yii::$app->response->sendFile($filename);
        } else {
            throw new NotFoundHttpException('file ' . $filename .' non trovato');
            Yii::$app->session->setFlash('error', 'Non sono riuscito a trovare il file: ' . $filename);
            
        }
    }
  
  
  
  
  public function actionAllegatitecnici2($id)
{
        $model = new AllegatiTecnici();
		
        if ($model->load(Yii::$app->request->post()))  {
                $model->file = UploadedFile::getInstance($model, 'file');

            if ($model->file && $model->validate()) {                
                $model->file->saveAs('allegatitecnici/' . $model->file->baseName . '.' . $model->file->extension);
                $model->save();
                echo JSON::encode(array(
		'error'=>'false',
		//'status'=>'HPI check complete', 
		//'trim'=>$trim;
		//'chassis_number'=>$chassis_number,
		));
                }
            } else {
                // example JSON response from server
       
		echo JSON::encode(array(
		'error'=>'true',
		//'status'=>'HPI check complete', 
		//'trim'=>$trim;
		//'chassis_number'=>$chassis_number,
		));
            }
} 

  
   /**
     * Gestione Allegati
     * @return string
     */
    public function actionAllegati3($id)
    {
  
        //$model = new UploadForm();
        $modtec = new AllegatiTecnici();
        $modamm = new AllegatiAmministrativi();
                
        $atecnici=AllegatiTecnici::find()
                        ->where(['id_pratica' => $id]);
	$alegal=AllegatiAmministrativi::find()
                        ->where(['id_pratica' => $id]);
        $dp1=new ActiveDataProvider(['query' => $atecnici]);
        $dp2=new ActiveDataProvider(['query' => $alegal]);

        if (Yii::$app->request->isPost) {
            $modtec->imageFile = allegatiTecnici::getInstance($modtec, 'imageFile');
            if ($modtec->upload()) {
                // file is uploaded successfully
                return;
            }
        }

        //return $this->render('upload', ['model' => $model]);
    return $this->render('allegati', ['modtec'=>$modtec,
                                           'modamm'=>$modamm,
                                           'dprov1'=>$dp1,
                                           'dprov2'=>$dp2,
                                            'id'=>$id]);
    }
  
  
  
  
  
    /**
     * Gestione Allegati
     * @return string
     */
    public function actionAllegati2($id)
    {
                
                //$model = Edilizia::findOne($id);
      
                $modtec = new AllegatiTecnici();
                $modamm = new AllegatiAmministrativi();
                
                $atecnici=AllegatiTecnici::find()
                        ->where(['id_pratica' => $id]);
		$alegal=AllegatiAmministrativi::find()
                        ->where(['id_pratica' => $id]);
                $dp1=new ActiveDataProvider(['query' => $atecnici]);
                $dp2=new ActiveDataProvider(['query' => $alegal]);
                
                
//                if ($modtec->load(Yii::$app->request->post())) {  
//                    $modtec->file = UploadedFile::getInstance($modtec, 'file');
//
//                    if ($modtec->file && $modtec->validate()) {                
//                    $modtec->file->saveAs('allegatitecnici/' . $modtec->file->baseName . '.' . $modtec->file->extension);
//                    $modtec->save();
//                    echo JSON::encode(array(
//		'error'=>'false',
//		//'status'=>'HPI check complete', 
//		//'trim'=>$trim;
//		//'chassis_number'=>$chassis_number,
//		));
//                
//                } else {
//                // example JSON response from server
//       
//		echo JSON::encode(array(
//		'error'=>'true',
//		//'status'=>'HPI check complete', 
//		//'trim'=>$trim;
//		//'chassis_number'=>$chassis_number,
//		));
//                }
//                }
		return $this->render('allegati', ['modtec'=>$modtec,
                                           'modamm'=>$modamm,
                                           'dprov1'=>$dp1,
                                           'dprov2'=>$dp2,
                                            'id'=>$id]);
            
         
    }

//     if ($model->load(Yii::$app->request->post())) {
//         if ($model->validate()) {
//            if ($model->save()) {
//             echo Yii::$app->session->setFlash('success', "Pratica Aggiornata!");
//             return $this->redirect(['index']);
//           } else {
//               echo Yii::$app->session->setFlash('error', "Si è verificato un Errore. La Pratica non è stata aggiornata!");
//           }
//         }
//    } 
//    return $this->render('modifica',['model' => $model]);

  //}

  
  
  
  /**
     * Genera il documento word con i dati all'interno:
     * @modello_id è id del modello da utilizzare
     * @idpratica è id della pratica da cui prelevare i dati 
     * 
     * Edit a Word 2007 and newer .docx file.
     * Utilizes the zip extension http://php.net/manual/en/book.zip.php
     * to access the document.xml file that holds the markup language for
     * contents and formatting of a Word document.
     *
     * In this example we're replacing some token strings.  Using
     * the Office Open XML standard ( https://en.wikipedia.org/wiki/Office_Open_XML )
     * you can add, modify, or remove content or structure of the document.
     * @param integer $id
     * @return mixed
     */
    public function actionDocumento($modello_id, $idpratica)
    {
        
//        $modelid = new \yii\base\DynamicModel(['modello_id']);
//         if ($modelid->load(Yii::$app->request->post())) {
//             $idmodello=$modelid->modello_id;
//         }
        $modello=Modulistica::findOne($modello_id);
        $filemodello=$modello->path . $modello->nomefile;                
        $model = Edilizia::findOne($idpratica);
        
        
       // require_once \Yii::getAlias('@vendor') . '/phpoffice/phpword/bootstrap.php';

        date_default_timezone_set('UTC');
        error_reporting(E_ALL);
        define('CLI', (PHP_SAPI == 'cli') ? true : false);
        //$model = Edilizia::findOne($id);
        
        $pareri = \app\models\PareriCommissioni::find()
                ->innerJoin('commissioni','pareri_commissioni.commissioni_id=commissioni.idcommissioni')
                ->innerJoin('tipo_commissioni','commissioni.Tipo=tipo_commissioni.idtipo_commissioni')
                ->innerJoin('sedute_commissioni','sedute_commissioni.idsedute_commissioni=pareri_commissioni.seduta_id')
                ->where('pareri_commissioni.pratica_id='.$idpratica)
                ->One();
                
                //SELECT * FROM utc.pareri_commissioni p 
                //inner join commissioni 
                //on (p.commissioni_id=commissioni.idcommissioni) 
                //inner join tipo_commissioni on (commissioni.Tipo=tipo_commissioni.idtipo_commissioni) 
                //where (p.pratica_id=78); 
        
        //$dompdfPath = $vendorDirPath . '/dompdf/dompdf';
        $dompdfPath = '@vendor/dompdf/dompdf';
        if (file_exists($dompdfPath)) {
        define('DOMPDF_ENABLE_AUTOLOAD', false);
        Settings::setPdfRenderer(Settings::PDF_RENDERER_DOMPDF, '@vendor/dompdf/dompdf');
        }

        Settings::loadConfig();
        // Set writers
        $writers = array('Word2007' => 'docx', 'ODText' => 'odt', 'RTF' => 'rtf', 'HTML' => 'html', 'PDF' => 'pdf');
        // Set PDF renderer
        if (null === Settings::getPdfRendererPath()) {
        $writers['PDF'] = null;
        }

        // Turn output escaping on
        Settings::setOutputEscapingEnabled(true);

        // Return to the caller script when runs by CLI
        if (CLI) {
        return;
        }

        // Create the Object.
        //$zip = new ZipArchive();

        // Use same filename for "save" and different filename for "save as".
        //$filename = 'edilizia\modelli\Mod_ED_04_Permesso_Costruire_Standard_Rev01.docx';
        //$outputFilename = 'edilizia\pratiche\P' . $model->id . '\PermessoCostruire.docx';

            //$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('/var/www/ufficiotecnico/web/modelli/Mod_ED_04_Permesso_Costruire_Standard_Rev02.docx');
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($filemodello);
        

            // Variables on different parts of document
            //$templateProcessor->setValue('weekday', date('l'));            // On section/content
            //$templateProcessor->setValue('time', date('H:i'));             // On footer
//            $templateProcessor->setValue('Denominazione_Ente','COMUNE DI CAMPOLI DEL MONTE TABURNO' ); //realpath(__DIR__)); // On header
//            $templateProcessor->setValue('Indirizzo','Piazza La Marmora' );
//            $templateProcessor->setValue('NumeroCivico','14' );
//            $templateProcessor->setValue('CAP','82030' );
//            $templateProcessor->setValue('Comune','CAMPOLI DEL MONTE TABURNO' );
//            $templateProcessor->setValue('Provincia','BN' );
//            $templateProcessor->setValue('Telefono','0824/873039' );
//            $templateProcessor->setValue('Fax','0824/873079' );
//            $templateProcessor->setValue('Email','info@comune.campolidelmontetaburno.bn.it' );
//            $templateProcessor->setValue('Pec','campolimt@pec.it' );
            
            //$templateProcessor->setValue('NumeroProtocolloTitolo','991' );
            //$templateProcessor->setValue('DataProtocolloTitolo','16/09/2018' );
            
            $templateProcessor->setValue('NumeroTitolo',$model->NumeroTitolo );
            $templateProcessor->setValue('DataTitolo',Yii::$app->formatter->asDate($model->DataTitolo,'php:d-m-Y'));
            
            // Dati istanza
            $templateProcessor->setValue('DataProtocollo',Yii::$app->formatter->asDate($model->DataProtocollo,'php:d-m-Y'));
            $templateProcessor->setValue('NumeroProtocollo',$model->NumeroProtocollo );
            
            // Richiedente
            // se trattasi di un soggetto persona fisica 
            if ($model->richiedente->RegimeGiuridico_id==1) {
                $templateProcessor->setValue('Richiedente',$model->richiedente->Cognome . ' ' . $model->richiedente->Nome );    
                $templateProcessor->setValue('Cognome',$model->richiedente->Cognome);    
                $templateProcessor->setValue('Cognome',$model->richiedente->Nome);    
                $templateProcessor->setValue('ComuneNascita',$model->richiedente->ComuneNascita);
                $templateProcessor->setValue('ProvinciaNascita',$model->richiedente->ProvinciaNascita);
                $templateProcessor->setValue('DataNascita',Yii::$app->formatter->asDate($model->richiedente->DataNascita,'d-m-Y'));
            } else {
                $templateProcessor->setValue('Richiedente',$model->richiedente->Denominazione );    
            }
            $templateProcessor->setValue('ResidenzaComune',$model->richiedente->ComuneResidenza);
            $templateProcessor->setValue('ResidenzaIndirizzo',$model->richiedente->IndirizzoResidenza);
            $templateProcessor->setValue('ResidenzaCap',$model->richiedente->CapResidenza);
            $templateProcessor->setValue('ResidenzaProvincia',$model->richiedente->ProvinciaResidenza);
            $templateProcessor->setValue('ResidenzaCivico',$model->richiedente->NumeroCivicoResidenza);
            $templateProcessor->setValue('DescrizioneIntervento',$model->DescrizioneIntervento);
            $templateProcessor->setValue('IndirizzoImmobile',$model->IndirizzoImmobile);
            $templateProcessor->setValue('CatastaleFoglio',$model->CatastoFoglio);
            $templateProcessor->setValue('CatastaleParticella',$model->CatastoParticella);
            $templateProcessor->setValue('CodiceFiscale',$model->richiedente->CodiceFiscale);
            $templateProcessor->setValue('OneriCostruzione',number_format (floatval($model->Oneri_Costruzione),2,',','.'));
            $templateProcessor->setValue('OneriUrbanizzazione',number_format (floatval($model->Oneri_Urbanizzazione),2,',','.'));
             $onc=isset($model->Oneri_Costruzione)? floatval($model->Oneri_Costruzione):0;
            $onu=isset($model->Oneri_Urbanizzazione)? floatval($model->Oneri_Urbanizzazione):0;
            $totOneri = $onc+$onu;
            $templateProcessor->setValue('TotaleOneriConcessori',number_format ($totOneri,2,',','.'));
            
            if (isset($model->CatastoSub)) {
                $templateProcessor->setValue('CatastoSub',' Sub.' . $model->CatastoSub);
            } else {
                $templateProcessor->setValue('CatastoSub',' ');
            }
            $intestato = $model->richiedente->Cognome . ' ' . $model->richiedente->Nome . ' nato a ' . $model->richiedente->ComuneNascita . ' (' . $model->richiedente->ProvinciaNascita . ')';
            $templateProcessor->setValue('DescrizioneIntestatario',$intestato);
            
            // date termini inizio lavori e fine lavori
            $date = date('d-m-Y');
            $inizio = strtotime ('+1 year',strtotime($date)) ; // facciamo l'operazione
            $fine = strtotime ('+4 year',strtotime($date)) ; // facciamo l'operazione
            $inizio = date ('d-m-Y' ,$inizio); //trasformiamo la data nel formato accettato dal db YYYY-MM-DD
            $fine = date ('d-m-Y' ,$fine); //trasformiamo la data nel formato accettato dal db YYYY-MM-DD
            $templateProcessor->setValue('DataInizioPermesso',$inizio);
            $templateProcessor->setValue('DataFinePermesso',$fine);
            
            
            // COMMISSIONI
            //{DescrizioneCommissioni}
            if (isset($pareri)) {
                $commmsg = 'La '. $pareri->commissione->tipologia->descrizione . ' nella seduta del ';
                if ($pareri->tipoparere_id==2) {
                $commmsg = $commmsg . Yii::$app->formatter->asDate($pareri->seduta->dataseduta,'php:d-m-Y') . ' Verbale n. ' . $pareri->seduta->numero. ' ha espresso parere favorevole.';    
                $templateProcessor->setValue('DescrizionePareri','');
                } else if ($pareri->tipoparere_id==3){
                $commmsg = $commmsg . Yii::$app->formatter->asDate($pareri->seduta->dataseduta,'php:d-m-Y') . ' Verbale n. ' . $pareri->seduta->numero. ' ha espresso il seguente parere favorevole con prescrizioni:';
                $templateProcessor->setValue('DescrizionePareri',$pareri->testoparere);
                } else {
                    $commmsg='';
                }
                
                $templateProcessor->setValue('DescrizioneCommissioni',$commmsg);
                
                
            }
            $templateProcessor->setValue('DataProposta',Yii::$app->formatter->asDate(date('Y-m-d'),'php:d-m-Y'));
            // Simple table
//            $templateProcessor->cloneRow('rowValue', 10);
//
//            $templateProcessor->setValue('rowValue#1', 'Sun');
//            $templateProcessor->setValue('rowValue#2', 'Mercury');
//            $templateProcessor->setValue('rowValue#3', 'Venus');
//            $templateProcessor->setValue('rowValue#4', 'Earth');
//            $templateProcessor->setValue('rowValue#5', 'Mars');
//            $templateProcessor->setValue('rowValue#6', 'Jupiter');
//            $templateProcessor->setValue('rowValue#7', 'Saturn');
//            $templateProcessor->setValue('rowValue#8', 'Uranus');
//            $templateProcessor->setValue('rowValue#9', 'Neptun');
//            $templateProcessor->setValue('rowValue#10', 'Pluto');
//
//            $templateProcessor->setValue('rowNumber#1', '1');
//            $templateProcessor->setValue('rowNumber#2', '2');
//            $templateProcessor->setValue('rowNumber#3', '3');
//            $templateProcessor->setValue('rowNumber#4', '4');
//            $templateProcessor->setValue('rowNumber#5', '5');
//            $templateProcessor->setValue('rowNumber#6', '6');
//            $templateProcessor->setValue('rowNumber#7', '7');
//            $templateProcessor->setValue('rowNumber#8', '8');
//            $templateProcessor->setValue('rowNumber#9', '9');
//            $templateProcessor->setValue('rowNumber#10', '10');
//
//            // Table with a spanned cell
//            $templateProcessor->cloneRow('userId', 3);
//
//            $templateProcessor->setValue('userId#1', '1');
//            $templateProcessor->setValue('userFirstName#1', 'James');
//            $templateProcessor->setValue('userName#1', 'Taylor');
//            $templateProcessor->setValue('userPhone#1', '+1 428 889 773');
//
//            $templateProcessor->setValue('userId#2', '2');
//            $templateProcessor->setValue('userFirstName#2', 'Robert');
//            $templateProcessor->setValue('userName#2', 'Bell');
//            $templateProcessor->setValue('userPhone#2', '+1 428 889 774');
//
//            $templateProcessor->setValue('userId#3', '3');
//            $templateProcessor->setValue('userFirstName#3', 'Michael');
//            $templateProcessor->setValue('userName#3', 'Ray');
//            $templateProcessor->setValue('userPhone#3', '+1 428 889 775');

            //echo date('H:i:s'), ' Saving the result document...', EOL;
            
            // Crea una directory per la pratica se non esiste (numero di 9 cifre con zeri iniziali)
            $path=Yii::getAlias('@pathedilizia') . '/' . trim(sprintf('%09u', $idpratica)) . '/'; 
             //FileHelper::createDirectory($path, $mode = 0775, $recursive = true); 
            if (!file_exists($path)) {
                FileHelper::createDirectory($path, $mode = 0777, $recursive = true); 
            }
            //$path = Yii::getAlias('@web') . '/pratiche/P' . trim(sprintf('%09u', $id));
//            $realpath = realpath($path);
//            // If it exist, check if it's a directory
//            if (!($realpath !== false AND is_dir($realpath))) 
//            {
//            FileHelper::createDirectory($path);
//            }
            $file=$path . '/documento' . '_' . uniqid('') . '.docx';
            $templateProcessor->saveAs($file);

            //echo getEndingNotes(array('Word2007' => 'docx'), 'Sample_07_TemplateCloneRow.docx');
//            if (!CLI) {
//                include_once 'Sample_Footer.php';
//            }
            // scarica il file generato
            if (file_exists($file)) {
                Yii::$app->response->sendFile($file);
            } 
        
        
        
        
        //return $this->redirect(['index']);
    }

  
  
  
  
  
  
  
  
  
  
 /**
     * Genera il documento word Permesso Costruire con i dati all'interno.
     * 
     * Edit a Word 2007 and newer .docx file.
     * Utilizes the zip extension http://php.net/manual/en/book.zip.php
     * to access the document.xml file that holds the markup language for
     * contents and formatting of a Word document.
     *
     * In this example we're replacing some token strings.  Using
     * the Office Open XML standard ( https://en.wikipedia.org/wiki/Office_Open_XML )
     * you can add, modify, or remove content or structure of the document.
     * @param integer $id
     * @return mixed
     */
    public function actionPermesso($id)
    {
       // require_once \Yii::getAlias('@vendor') . '/phpoffice/phpword/bootstrap.php';

        date_default_timezone_set('UTC');
        error_reporting(E_ALL);
        define('CLI', (PHP_SAPI == 'cli') ? true : false);
        $model = Edilizia::findOne($id);
        
        $pareri = \app\models\PareriCommissioni::find()
                ->innerJoin('commissioni','pareri_commissioni.commissioni_id=commissioni.idcommissioni')
                ->innerJoin('tipo_commissioni','commissioni.Tipo=tipo_commissioni.idtipo_commissioni')
                ->innerJoin('sedute_commissioni','sedute_commissioni.idsedute_commissioni=pareri_commissioni.seduta_id')
                ->where('pareri_commissioni.pratica_id='.$id)
                ->One();
                
                //SELECT * FROM utc.pareri_commissioni p 
                //inner join commissioni 
                //on (p.commissioni_id=commissioni.idcommissioni) 
                //inner join tipo_commissioni on (commissioni.Tipo=tipo_commissioni.idtipo_commissioni) 
                //where (p.pratica_id=78); 
        
        //$dompdfPath = $vendorDirPath . '/dompdf/dompdf';
        $dompdfPath = '@vendor/dompdf/dompdf';
        if (file_exists($dompdfPath)) {
        define('DOMPDF_ENABLE_AUTOLOAD', false);
        Settings::setPdfRenderer(Settings::PDF_RENDERER_DOMPDF, '@vendor/dompdf/dompdf');
        }

        Settings::loadConfig();
        // Set writers
        $writers = array('Word2007' => 'docx', 'ODText' => 'odt', 'RTF' => 'rtf', 'HTML' => 'html', 'PDF' => 'pdf');
        // Set PDF renderer
        if (null === Settings::getPdfRendererPath()) {
        $writers['PDF'] = null;
        }

        // Turn output escaping on
        Settings::setOutputEscapingEnabled(true);

        // Return to the caller script when runs by CLI
        if (CLI) {
        return;
        }

        // Create the Object.
        //$zip = new ZipArchive();

        // Use same filename for "save" and different filename for "save as".
        //$filename = 'edilizia\modelli\Mod_ED_04_Permesso_Costruire_Standard_Rev01.docx';
        //$outputFilename = 'edilizia\pratiche\P' . $model->id . '\PermessoCostruire.docx';

            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('/var/www/ufficiotecnico/web/modelli/Mod_ED_04_Permesso_Costruire_Standard_Rev02.docx');

            // Variables on different parts of document
            //$templateProcessor->setValue('weekday', date('l'));            // On section/content
            //$templateProcessor->setValue('time', date('H:i'));             // On footer
//            $templateProcessor->setValue('Denominazione_Ente','COMUNE DI CAMPOLI DEL MONTE TABURNO' ); //realpath(__DIR__)); // On header
//            $templateProcessor->setValue('Indirizzo','Piazza La Marmora' );
//            $templateProcessor->setValue('NumeroCivico','14' );
//            $templateProcessor->setValue('CAP','82030' );
//            $templateProcessor->setValue('Comune','CAMPOLI DEL MONTE TABURNO' );
//            $templateProcessor->setValue('Provincia','BN' );
//            $templateProcessor->setValue('Telefono','0824/873039' );
//            $templateProcessor->setValue('Fax','0824/873079' );
//            $templateProcessor->setValue('Email','info@comune.campolidelmontetaburno.bn.it' );
//            $templateProcessor->setValue('Pec','campolimt@pec.it' );
            
            //$templateProcessor->setValue('NumeroProtocolloTitolo','991' );
            //$templateProcessor->setValue('DataProtocolloTitolo','16/09/2018' );
            
            $templateProcessor->setValue('NumeroTitolo',$model->NumeroTitolo );
            $templateProcessor->setValue('DataTitolo',Yii::$app->formatter->asDate($model->DataTitolo,'php:d-m-Y'));
            
            // Dati istanza
            $templateProcessor->setValue('DataProtocollo',Yii::$app->formatter->asDate($model->DataProtocollo,'php:d-m-Y'));
            $templateProcessor->setValue('NumeroProtocollo',$model->NumeroProtocollo );
            
            // Richiedente
            $templateProcessor->setValue('Richiedente',$model->richiedente->Cognome . ' ' . $model->richiedente->Nome );
            $templateProcessor->setValue('ComuneNascita',$model->richiedente->ComuneNascita);
            $templateProcessor->setValue('ProvinciaNascita',$model->richiedente->ProvinciaNascita);
            $templateProcessor->setValue('DataNascita',Yii::$app->formatter->asDate($model->richiedente->DataNascita,'d-m-Y'));
            $templateProcessor->setValue('ResidenzaComune',$model->richiedente->ComuneResidenza);
            $templateProcessor->setValue('ResidenzaIndirizzo',$model->richiedente->IndirizzoResidenza);
            $templateProcessor->setValue('ResidenzaProvincia',$model->richiedente->ProvinciaResidenza);
            $templateProcessor->setValue('ResidenzaCivico',$model->richiedente->NumeroCivicoResidenza);
            $templateProcessor->setValue('DescrizioneIntervento',$model->DescrizioneIntervento);
            $templateProcessor->setValue('IndirizzoImmobile',$model->IndirizzoImmobile);
            $templateProcessor->setValue('CatastaleFoglio',$model->CatastoFoglio);
            $templateProcessor->setValue('CatastaleParticella',$model->CatastoParticella);
            $templateProcessor->setValue('CodiceFiscale',$model->richiedente->CodiceFiscale);
            $templateProcessor->setValue('OneriCostruzione',number_format (floatval($model->Oneri_Costruzione),2,',','.'));
            $templateProcessor->setValue('OneriUrbanizzazione',number_format (floatval($model->Oneri_Urbanizzazione),2,',','.'));
             $onc=isset($model->Oneri_Costruzione)? floatval($model->Oneri_Costruzione):0;
            $onu=isset($model->Oneri_Urbanizzazione)? floatval($model->Oneri_Urbanizzazione):0;
            $totOneri = $onc+$onu;
            $templateProcessor->setValue('TotaleOneriConcessori',number_format ($totOneri,2,',','.'));
            
            if (isset($model->CatastoSub)) {
                $templateProcessor->setValue('CatastoSub',' Sub.' . $model->CatastoSub);
            } else {
                $templateProcessor->setValue('CatastoSub',' ');
            }
            $intestato = $model->richiedente->Cognome . ' ' . $model->richiedente->Nome . ' nato a ' . $model->richiedente->ComuneNascita . ' (' . $model->richiedente->ProvinciaNascita . ')';
            $templateProcessor->setValue('DescrizioneIntestatario',$intestato);
            
            // date termini inizio lavori e fine lavori
            $date = date('d-m-Y');
            $inizio = strtotime ('+1 year',strtotime($date)) ; // facciamo l'operazione
            $fine = strtotime ('+4 year',strtotime($date)) ; // facciamo l'operazione
            $inizio = date ('d-m-Y' ,$inizio); //trasformiamo la data nel formato accettato dal db YYYY-MM-DD
            $fine = date ('d-m-Y' ,$fine); //trasformiamo la data nel formato accettato dal db YYYY-MM-DD
            $templateProcessor->setValue('DataInizioPermesso',$inizio);
            $templateProcessor->setValue('DataFinePermesso',$fine);
            
            
            // COMMISSIONI
            //{DescrizioneCommissioni}
            if (isset($pareri)) {
                $commmsg = 'La '. $pareri->commissione->tipologia->descrizione . ' nella seduta del ';
                if ($pareri->tipoparere_id==2) {
                $commmsg = $commmsg . Yii::$app->formatter->asDate($pareri->seduta->dataseduta,'php:d-m-Y') . ' Verbale n. ' . $pareri->seduta->numero. ' ha espresso parere favorevole.';    
                $templateProcessor->setValue('DescrizionePareri','');
                } else if ($pareri->tipoparere_id==3){
                $commmsg = $commmsg . Yii::$app->formatter->asDate($pareri->seduta->dataseduta,'php:d-m-Y') . ' Verbale n. ' . $pareri->seduta->numero. ' ha espresso il seguente parere favorevole con prescrizioni:';
                $templateProcessor->setValue('DescrizionePareri',$pareri->testoparere);
                } else {
                    $commmsg='';
                }
                
                $templateProcessor->setValue('DescrizioneCommissioni',$commmsg);
                
                
            }
            $templateProcessor->setValue('DataProposta',Yii::$app->formatter->asDate(date('Y-m-d'),'php:d-m-Y'));
            // Simple table
//            $templateProcessor->cloneRow('rowValue', 10);
//
//            $templateProcessor->setValue('rowValue#1', 'Sun');
//            $templateProcessor->setValue('rowValue#2', 'Mercury');
//            $templateProcessor->setValue('rowValue#3', 'Venus');
//            $templateProcessor->setValue('rowValue#4', 'Earth');
//            $templateProcessor->setValue('rowValue#5', 'Mars');
//            $templateProcessor->setValue('rowValue#6', 'Jupiter');
//            $templateProcessor->setValue('rowValue#7', 'Saturn');
//            $templateProcessor->setValue('rowValue#8', 'Uranus');
//            $templateProcessor->setValue('rowValue#9', 'Neptun');
//            $templateProcessor->setValue('rowValue#10', 'Pluto');
//
//            $templateProcessor->setValue('rowNumber#1', '1');
//            $templateProcessor->setValue('rowNumber#2', '2');
//            $templateProcessor->setValue('rowNumber#3', '3');
//            $templateProcessor->setValue('rowNumber#4', '4');
//            $templateProcessor->setValue('rowNumber#5', '5');
//            $templateProcessor->setValue('rowNumber#6', '6');
//            $templateProcessor->setValue('rowNumber#7', '7');
//            $templateProcessor->setValue('rowNumber#8', '8');
//            $templateProcessor->setValue('rowNumber#9', '9');
//            $templateProcessor->setValue('rowNumber#10', '10');
//
//            // Table with a spanned cell
//            $templateProcessor->cloneRow('userId', 3);
//
//            $templateProcessor->setValue('userId#1', '1');
//            $templateProcessor->setValue('userFirstName#1', 'James');
//            $templateProcessor->setValue('userName#1', 'Taylor');
//            $templateProcessor->setValue('userPhone#1', '+1 428 889 773');
//
//            $templateProcessor->setValue('userId#2', '2');
//            $templateProcessor->setValue('userFirstName#2', 'Robert');
//            $templateProcessor->setValue('userName#2', 'Bell');
//            $templateProcessor->setValue('userPhone#2', '+1 428 889 774');
//
//            $templateProcessor->setValue('userId#3', '3');
//            $templateProcessor->setValue('userFirstName#3', 'Michael');
//            $templateProcessor->setValue('userName#3', 'Ray');
//            $templateProcessor->setValue('userPhone#3', '+1 428 889 775');

            //echo date('H:i:s'), ' Saving the result document...', EOL;
            
            // Crea una directory per la pratica se non esiste (numero di 9 cifre con zeri iniziali)
            $path=Yii::getAlias('@pathallegati') . 'edilizia/' . trim(sprintf('%09u', $id)) . '/'; 
             //FileHelper::createDirectory($path, $mode = 0775, $recursive = true); 
            if (!file_exists($path)) {
                FileHelper::createDirectory($path, $mode = 0777, $recursive = true); 
            }
            //$path = Yii::getAlias('@web') . '/pratiche/P' . trim(sprintf('%09u', $id));
//            $realpath = realpath($path);
//            // If it exist, check if it's a directory
//            if (!($realpath !== false AND is_dir($realpath))) 
//            {
//            FileHelper::createDirectory($path);
//            }
            $file=$path . '/PermessoCostruire' . '_' . uniqid('') . '.docx';
            $templateProcessor->saveAs($file);

            //echo getEndingNotes(array('Word2007' => 'docx'), 'Sample_07_TemplateCloneRow.docx');
//            if (!CLI) {
//                include_once 'Sample_Footer.php';
//            }
            // scarica il file generato
            if (file_exists($file)) {
                Yii::$app->response->sendFile($file);
            } 
        
        
        
        
        //return $this->redirect(['index']);
    }


    
    
    
   
    
    
    
    
    
    
    
/**
     * Signup new user
     * @return string
     */
    public function actionDelete($id)
    {
        $model = Edilizia::findOne($id); 
        // verifico se ha collegamenti
        // ALLEGATI
        $hoAllegati = $model->getAllegati()->count()>0;
        if ($hoAllegati) {
           Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_ERROR, [
                    [
                        //'title' => 'Errore',
                        'text' => 'Non posso cancellare la pratica perché contiene allegati!',
                        //'confirmButtonText' => 'Done!',
                        'timer' => 2000,
                        'showConfirmButton'=>false,
                        'position'=>'top-end',
                    ]
            ]);
            return $this->redirect(['index']);
        }
        $hoPareri = $model->getPareri()->count()>0;
        if ($hoPareri) {
           Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_ERROR, [
                    [
                        //'title' => 'Errore',
                        'text' => 'Non posso cancellare la pratica perché contiene pareri commissioni!',
                        //'confirmButtonText' => 'Done!',
                        'timer' => 2000,
                        'showConfirmButton'=>false,
                        'position'=>'top-end',
                    ]
            ]);
            return $this->redirect(['index']);
        }
        
        
        $model->delete();
        return $this->redirect(['index']);
    }
    
    
    /**
     * Visualizza un Lavoro User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		//$this->idl=$id;
		//$this->layout = 'lay-lavori';
		$pratica=Edilizia::findOne($id);
                $atecnici=AllegatiFile::find()
                        ->where(['id_pratica' => $id,'tipologia'=>0,'tipopratica'=>1])
                        ->all();
                $alegal=AllegatiFile::find()
                        ->where(['id_pratica' => $id,'tipologia'=>1,'tipopratica'=>1])
                        ->all();
		//$user = User::find()->where(['name' => 'CeBe'])->one();
		//$natura = Cup_natura::find()->where(['Codice' => $lavori->CodiceNatura])->one();
		//$tipologia=Cup_tipologia::find()->where(['CodiceNatura' => $lavori->CodiceNatura,'CodiceTipologia' => $lavori->CodiceTipologia])->one()->DescrizioneTipologia;
        return $this->render('view', [
                'model' => $pratica,
               'idedilizia'=>$id,
                'atecnici'=>$atecnici,
                'alegal'=>$alegal,
        ]);
    }
    
    
    /**
     * Visualizza un Lavoro User model.
     * @param integer $id
     * @return mixed
     */
    public function actionToword($idpratica)
    {
		//$this->idl=$id;
		//$this->layout = 'lay-lavori';
		$pratica=Edilizia::findOne($idpratica);
		//$user = User::find()->where(['name' => 'CeBe'])->one();
		//$natura = Cup_natura::find()->where(['Codice' => $lavori->CodiceNatura])->one();
		//$tipologia=Cup_tipologia::find()->where(['CodiceNatura' => $lavori->CodiceNatura,'CodiceTipologia' => $lavori->CodiceTipologia])->one()->DescrizioneTipologia;
        return $this->render('toword', [
                'model' => $pratica,
               'idedilizia'=>$idpratica
        ]);
    }
    
      
    /**
     * Visualizza il pannello Oneri Concessori collegato alla pratica.
     * @param integer $id
     * @return mixed
     */
    public function actionOneri2($id,$idedilizia)
    {
        if (Yii::$app->request->isGet) {
            $oneri=Onericoncessori::find()->where('edilizia_id'===$idedilizia)->one();
        if (!isset($oneri)) {
            $oneri=new Onericoncessori();
            $oneri->SetDefaultValue();
            $oneri->edilizia_id=$idedilizia;
            $oneri->save();
            $oneri->refresh();
//            return $this->render('oneri', [
//                'model' => $oneri,
//                'idedilizia'=>$idedilizia,
//                'id'=>$oneri->id]);
        }
        return $this->render('oneri',[ 
                    'model' => $oneri,
                    'id'=>$oneri->id,
                    'idedilizia'=>$idedilizia]);
        }
        //Yii::$app->getRequest()->getQueryParam('param')
        if (Yii::$app->request->isPost) {
            //$oneri=new Onericoncessori();
                $post = Yii::$app->request->post(); 
                if (empty($post['Onericoncessori']['id'])) {
                throw new NotFoundHttpException('Oneri non trovati.');
                }
            $oneri=Onericoncessori::findOne($post['Onericoncessori']['id']);
            if ($oneri->load($post) && $oneri->validate()) {
                //DateToMysql
                if (isset($oneri->ScadenzaRataCostruzione1)) {$oneri->ScadenzaRataCostruzione1=$this->DateToMysql($oneri->ScadenzaRataCostruzione1);};
                if (isset($oneri->ScadenzaRataCostruzione2)) {$oneri->ScadenzaRataCostruzione2=$this->DateToMysql($oneri->ScadenzaRataCostruzione2);};
                if (isset($oneri->ScadenzaRataCostruzione3)) {$oneri->ScadenzaRataCostruzione3=$this->DateToMysql($oneri->ScadenzaRataCostruzione3);};
                if (isset($oneri->ScadenzaRataCostruzione4)) {$oneri->ScadenzaRataCostruzione4=$this->DateToMysql($oneri->ScadenzaRataCostruzione4);};
                if (isset($oneri->ScadenzaRataCostruzione5)) {$oneri->ScadenzaRataCostruzione5=$this->DateToMysql($oneri->ScadenzaRataCostruzione5);};
                if (isset($oneri->ScadenzaRataCostruzione6)) {$oneri->ScadenzaRataCostruzione6=$this->DateToMysql($oneri->ScadenzaRataCostruzione6);};
                $oneri->save();

                return $this->render('oneri',[ 
                    'id'=>$oneri->id,
                    'idedilizia'=>$idedilizia,
                    'model' => $oneri
                     ]); 
            } else {
                return $this->render('oneri', [
                    'idedilizia'=>$idedilizia,
                    'model' => $oneri,
            ]);
        }
//        } else {
//                return $this->render('oneri', ['idedilizia'=>$idedilizia,'model'=>$oneri]);
//        }
                
                            
         }
         
    }
        
        
    
    
    
    
      /**
     *  Ajax 
     * 
     * @param integer $id
     * @return Edilizia the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    //
    public function actionNumerapermesso()
    {
    
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            //print_r(json_encode($data));
                $dati = [
                    'success' => true,
                    'numero' => $this->NuovoNumeroPermesso(),
                    //'message' => 'Model has been saved.',
                    ];
                return $dati;
        }
    }    
    
    
    
  Public function NuovoNumeroPermesso() 
    {
        $newnumber = Edilizia::find()
                ->where('year(DataTitolo) = ' . date('Y'))
                ->select('max(NumeroTitolo)')
                 ->scalar();
            return $newnumber+1;
        
    }    
    
    
    Public function NuovoNumeroPaesistica() 
    {
        $newaut = Edilizia::find()
                ->where('year(DataAutorizzazionePaesaggistica) = ' . date('Y'))
                ->select('max(NumeroAutorizzazionePaesaggistica)')
                 ->scalar();
            return $newaut+1;
    }    
   
    
    /**
     *  Ajax 
     * 
     * @param integer $id
     * @return Edilizia the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    //
    public function actionNumerapaesistica()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            //print_r(json_encode($data));
                $dati = [
                    'success' => true,
                    'numero' => $this->NuovoNumeroPaesistica(),
                    //'message' => 'Model has been saved.',
                    ];
                return $dati;
        }
    }    
       
    
    
    
    
    
    
    
    
//        $model = Onericoncessori::findOne($post['Onericoncessori']['id']);
//
//        if ($model->load($post) && $model->save()) {
//            return $this->redirect(['oneri', 'id' => $model->productId]);
//        } else {
//            return $this->render('oneri', [
//                'model' => $model,
//            ]);
//        }
    
 
          Private function DateToMysql($dateIT)
            {
            $newdate=\Yii::$app->formatter->asDate($dateIT, 'php:Y-m-d');     
            return $newdate;
            }
         
         
 
//          public function beforeSave($insert)
//
//        {
//            if (isset($this->DataProtocollo)) {
//                $this->DataProtocollo = Yii::$app->formatter->asDate($this->DataProtocollo, 'yyyy-MM-dd');
//            }
//            if (isset($this->Data_OK)) {
//                $this->Data_OK = Yii::$app->formatter->asDate($this->Data_OK, 'yyyy-MM-dd');
//            }
//            if (isset($this->Data_Inizio_Lavori)) {
//                $this->Data_Inizio_Lavori = Yii::$app->formatter->asDate($this->Data_Inizio_Lavori, 'yyyy-MM-dd');
//            }
//            if (isset($this->Data_Fine_Lavori)) {
//                $this->Data_Fine_Lavori = Yii::$app->formatter->asDate($this->Data_Fine_Lavori, 'yyyy-MM-dd');
//            }
//
//            parent::beforeSave($insert);
//            return true;
//        }            
//
//    public function afterFind()
//    {
//            if (isset($this->DataProtocollo)) {
//                $this->DataProtocollo = date("d-m-Y", strtotime($model->DataProtocollo));
//            }
//            if (isset($this->Data_OK)) {
//                $this->Data_OK = date("d-m-Y", strtotime($model->Data_OK));
//            }
//            if (isset($this->Data_Inizio_Lavori)) {
//                $this->Data_Inizio_Lavori = date("d-m-Y", strtotime($model->Data_Inizio_Lavori));
//            }
//            if (isset($this->Data_Fine_Lavori)) {
//                $this->Data_Fine_Lavori = date("d-m-Y", strtotime($model->Data_Fine_Lavori));
//            }
//        parent::afterFind();
//        return true;
//    }
            
public function test()
{
    return $this->render('test', [
            'html'=>$contenuto
        ]);
            
}           
            
            
public function beforeAction($action)
    {
        $this->layout = 'main'; 
        return parent::beforeAction($action);
    }
            

//public function behaviors()
//{
//    return [
//        'httpCache' => [
//            'class' => \yii\filters\HttpCache::className(),
//            'only' => ['list'],
//            'lastModified' => function ($action, $params) {
//                $q = new Query();
//                return strtotime($q->from('users')->max('updated_timestamp'));
//            },
//            // 'etagSeed' => function ($action, $params) {
//                // return // generate etag seed here
//            //}
//        ],
//    ];
//}
    
    
    
    
            
}
     
     
 