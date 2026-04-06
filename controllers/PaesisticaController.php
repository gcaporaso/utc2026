<?php

namespace app\controllers;

use Yii;
//use yii\base\Model;
//use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
//use yii\filters\VerbFilter;
//use yii\data\Pagination;
use app\models\AllegatiPaesistica;
//use yii\web\NotFoundHttpException;
use app\models\Paesistica;

use app\models\Edilizia;
use app\models\EmailSend;
use yii\db\Query;
use app\models\Modulistica;
//use app\models\Committente;
//use app\models\Titolare;
use app\models\PaesisticaSearch;
use yii\data\ActiveDataProvider;
use kartik\dynagrid\models\DynaGridConfig;
use kartik\grid\EditableColumnAction;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;
//use App\models\Onericoncessori;
//use yii\helpers\Url;
use kartik\mpdf\Pdf;
//use yii\helpers\json;
use yii\filters\AccessControl;
use PhpOffice\PhpWord;
use PhpOffice\PhpWord\Shared;
use PhpOffice\PhpWord\Settings;
use Laminas\Mail\Message;
use Laminas\Mail\Transport\Smtp as SmtpTransport;
use Laminas\Mail\Transport\SmtpOptions;
use Laminas\ServiceManager\AbstractPluginManager;

class PaesisticaController extends Controller
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
                        'actions' => ['nuova','oneri','view','allegati','update','autorizzazione',
                                      'delete','cancellafile','toword','atti',
                                      'emailsollecito','oneriajax','inviaemail','inviapec',
                                      'procedura','download', 'test','elencopratiche','elencoautorizzazioni'],
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

    
    
    
    
    
    


public function se_iterable($var)
{
    return is_iterable($var) ? $var : array();
}





//    /**
//     * {@inheritdoc}
//     */
//    public function actions()
//    {
//        return ArrayHelper::merge(parent::actions(), [
//            'error' => [
//                'class' => 'yii\web\ErrorAction',
//            ],
//            'oneriup' => [                                       // identifier for your editable action
//                'class' => EditableColumnAction::className(),     // action class name
//                'modelClass' => OneriConcessori::className(),     // the update model class
//                'showModelErrors' => true,
//            ],
//        ]);
//    }




    public function actionValidate()
    {
        $model = new Paesistica();
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
    public function ImportaPaesisticaDaEdilizia()
    {
         // Questa query funziona 
    $epratiche= Edilizia::find()
            ->where(['>', 'NumeroAutorizzazionePaesaggistica',0])
            ->all();
    
    
    //return $this->render('test', ['html' =>json_encode($epratiche)]); 
    
        foreach ($epratiche as $pratica) {
            $paes=new Paesistica();
            $paes->NumeroProtocollo=$pratica->NumeroProtocollo;
            $paes->DataProtocollo=$pratica->DataProtocollo;
            $paes->idcommittente=$pratica->id_committente;
            $paes->DescrizioneIntervento=$pratica->DescrizioneIntervento;
            $paes->Progettista_ID=$pratica->PROGETTISTA_ARC_ID;
            $paes->Direttore_Lavori_ID=$pratica->DIR_LAV_ARCH_ID;
            $paes->Impresa_ID=$pratica->IMPRESA_ID;
            $paes->CatastoFoglio=$pratica->CatastoFoglio;
            $paes->CatastoParticella=$pratica->CatastoParticella;
            $paes->CatastoSub=$pratica->CatastoSub;
            $paes->Latitudine=$pratica->Latitudine;
            $paes->Longitudine=$pratica->Longitudine;
            $paes->NumeroAutorizzazione=$pratica->NumeroAutorizzazionePaesaggistica;
            $paes->DataAutorizzazione=$pratica->DataAutorizzazionePaesaggistica;
            $paes->Compatibilita=$pratica->COMPATIBILITA_PAESISTICA;
            $paes->IndirizzoImmobile=$pratica->IndirizzoImmobile;
            $paes->StatoPratica=5;
            if ($pratica->IndirizzoImmobile) {
                $paes->idtipo=2;
            } else {
                $paes->idtipo=1;
            }

         $paes->save(false);
         };
        
     //return $this->render('test', ['html' =>json_encode($anni)]); 
        
    }

    
    
    
    
  /**
     * Visualizza la Homepage del modulo Edilizia.
     *
     * @return string
     */
    public function actionIndex()
    {
        
       // $ai = $this->ImportaPaesisticaDaEdilizia();
//  $this->layout = 'yourNewLayout';
	 //$query = Lavori::find();
         //$item=array();
        $this->layout = 'main';
	 $searchModel = new PaesisticaSearch;
	 $model = new DynaGridConfig();
	 $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
         $dataProvider->sort->defaultOrder = ['DataProtocollo' => SORT_DESC];
        //$dataProvider->pagination = ['pageSize' => 15];
         
    return $this->render('index', [
                        'Provider' =>$dataProvider,
			'Search' => $searchModel,
			'model' => $model,
                        //'dateto'=>$date,
        ]);
    }




    public function actionAddpratica()
     {
        $model=new Paesistica;

        if (Yii::$app->request->isAjax) {
           $model = new Paesistica();

           if ($model->load(\Yii::$app->request->post())) {
             if ($model->validate()) {
                $model->save();
                return $model->idpaesistica;
            }
            $html = $this->renderPartial('paesistica-form');
            return Json::encode($html);
          }
        }
    }




 
 
 
  /**
     * Creates a new Edilizia model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionNuova()
    {
        $model = new Paesistica();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                //$model->StatoPratica=1;
                $model->FileAutorizzazione=0;
                //$model->Indennita=0;
                $model->DataProtocollo=date('Y-m-d',strtotime($model->DataProtocollo));
                $model->save();
                return $this->redirect(['index']);
            } else {
            // validation failed: $errors is an array containing error messages
                $errors = $model->errors;
            }
        }
        $model->idtipo=1;
        $model->StatoPratica=1;
        return $this->render('nuova', [
            'model' => $model,
        ]);
    } 
 
 
  /**
     * Action Gesione Pratica
     * @return string
     */
    public function actionAtti($idpratica)
    {
          //$model = new AllegatiFile();
       $model = new \yii\base\DynamicModel(['modello_id']);
       $model->addRule(['modello_id'], 'safe');
//        $elenco=Modulistica::find()
//                        ->where(['categoria'=>2]);
        $modelp = Paesistica::findOne($idpratica);
        if ($model->load(Yii::$app->request->post())) {
            return $this->redirect(['paesistica/documento', 'modello_id'=>$model->modello_id,'idpratica'=>$idpratica]);
        }
        
        
        
        return $this->render('atti', [
            'model'=>$model,'idpratica'=>$idpratica,'modelp'=>$modelp
        ]);
        
        
//        
// 	$model = Paesistica::findOne($idpratica);
//        //$docs = ArrayHelper::map(app\models\Modulistica::find()->where(['categoria'=>2])->asArray()->all(),'idmodulistica','descrizione');
////        $docs = Modulistica::find()
////                ->where(['categoria'=>2])
////                ->asArray()
////                ->all();
//    return $this->render('atti',['model' => $model]);

  }       
        
    
        
        

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
            $path=Yii::getAlias('@pathallegati') . 'paesistica/' . trim(sprintf('%09u', $idpratica)) . '/'; 
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
     * Signup new user
     * @return string
     */
    public function actionUpdate($id)
    {
    
	$model = Paesistica::findOne($id);
            

     if ($model->load(Yii::$app->request->post())) {
         if ($model->validate()) {
            if ($model->save()) {
             echo Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_SUCCESS, [
                        [
                            //'title' => 'Oneri salvati',
                            'text' => 'Pratica Aggiornata!',
                            //'confirmButtonText' => 'Done!',
                            'timer' => 1500,
                            'showConfirmButton'=> false,
                            'toast'=> true,
                            'position'=> 'top-end',
                        ]
                     ]);
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
    
	$model = Paesistica::findOne($idpratica);
            

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
 

protected function renderView($view, $params = [])
{
    if (Yii::$app->request->isAjax) {
        return $this->renderAjax($view, $params);
    }
    return $this->render($view, $params);
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


private function numpratiche($Anno, $id, $elenco)
{
    $numero=0;
    $tot=count($elenco);
  foreach ($elenco as $item) {
       if (($item['Anno']==$Anno) and ($item['idtipo']==$id))
       {
           $numero = $item['Numero'];
           break;
       }
   }
   
   return $numero;
}



public function SelezionaAnno(){
   $manager = "Modal::begin([
    'header' => '<h2>Hello world</h2>',
     'toggleButton' => ['label' => 'click me'],
    ]);
    Modal::end();";
   return $this->asJson($this->renderAjax('modal_content_view', ['manager'=>$manager]));
}








/**
 * Genera un PDF con una tabella contenente il REPORT con elenco permessi costruire 
 * presenti in archivio divise per anno
 */
public function actionElencoautorizzazioni() {

/*
 * Genera un array con gli anni in cui sono state registrate pratiche
 * per la generazione di report
 */
//$anno=$this->SelezionaAnno();

// Questa query funziona 
    $pratiche = Paesistica::find()
            ->select(['idcommittente', 'NumeroAutorizzazione', 'DataAutorizzazione', 'NumeroProtocollo', 'DataProtocollo', 'DescrizioneIntervento'])
            ->where(['Year(DataAutorizzazione)' => date('Y')])
            //->where(['Year(DataAutorizzazione)' => 2013])
            ->orderBy('NumeroAutorizzazione ASC','DataAutorizzazione ASC')
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
<p><strong>ELENCO AUTORIZZAZIONI PAESISTICHE RILASCIATE ANNO ' . date("Y") . ',</strong></p>
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
         $riga = '<tr><td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $pratica->NumeroAutorizzazione .'</td>'
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. Yii::$app->formatter->asDate($pratica->DataAutorizzazione,'php:d-m-Y') .'</td>'
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
    
//    // setup kartik\mpdf\Pdf component
//    $pdf = new Pdf([
//        'filename'=>'Elenco_Autorizzazioni_Paesistiche_Anno' . date("d-m-Y"),
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
//    //die( var_dump( Yii::app()->getRequest()->getIsSecureConnection() ) );
//    // return the pdf output as per the destination setting
//    return $pdf->render(); 
    
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
    $mpdf->Output('elencoautpaesistiche.pdf', 'I');
    return;
   
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
    $anni = Paesistica::find()
            ->select(['Year(DataProtocollo) AS Anno'])->distinct()
            ->groupBy('Anno')
            ->orderBy('Anno ASC')
            ->asArray()
            ->all();

    

    
    // Questa query funziona 
    $pratiche= Paesistica::find()
            ->select(['Year(DataProtocollo) AS Anno','idtipo' ,'COUNT(idtipo) as Numero'])->distinct()
            ->groupBy('Anno, idtipo')
            ->orderBy('Anno ASC')
            ->asArray()
            ->all();

    
// return $this->render('test', ['html' =>json_encode($anni)]);
    
    
     if (!isset($pratiche)) {
         Yii::$app->session->setFlash('error', "Si è verificato un errore! Non sono riuscito a generare la Tabella.");
        return $this->redirect(Yii::$app->request->referrer);
     }
     
    $content =  
'
<p><strong>ELENCO NUMERO PRATICHE PAESAGGISTICHE PER ANNO</strong></p>
<p></p>

<table width="800" style="border:1px solid black;border-collapse:collapse;">
  <tr>
    <th width="64" height="35" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">Anno</th>
    <th width="35" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">AUTORIZZAZIONE art.146</th>
    <th width="110" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">COMPATIBILITA Art.167</th>
    <th width="66" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">COMPATIBILITA L.47/85</th>
    <th width="56" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">AUTORIZZAZIONE d.P.R. 31/2017</th>
    <th width="104" scope="col" style="border:1px solid black;border-collapse:collapse;text-align:center">AUTORIZZAZIONE EX Art. 7 L.1497/39</th>
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
                . '<td style="border:1px solid black;border-collapse:collapse;text-align:center">'. $this->numpratiche($anno['Anno'],5,$pratiche) .'</td><tr>';
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
//    //die( var_dump( Yii::app()->getRequest()->getIsSecureConnection() ) );
//    // return the pdf output as per the destination setting
//    return $pdf->render(); 
    
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
    $mpdf->Output('elencopratichepaesistiche.pdf', 'I');
    return;
 
}










    
 public function actionAllegati($idpratica) 
    {
        $model = new AllegatiPaesistica();
         //$model = new UploadForm();
        //$modtec = new AllegatiTecnici();
        $allegati=AllegatiPaesistica::find()
                        ->where(['id_pratica' => $idpratica,'tipopratica'=>1]);
        $modelp = Paesistica::findOne($idpratica);
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
                return $this->redirect(['paesistica/allegati', 'dpv'=>$dp,'idpratica'=>$idpratica,'modelp'=>$modelp]);
            }
            
            
            // Crea una directory per la pratica se non esiste (numero di 9 cifre con zeri iniziali)
            $path=Yii::getAlias('@pathallegati') . 'paesistica/' . trim(sprintf('%09u', $idpratica)) . '/'; 
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
                return $this->redirect(['paesistica/allegati', 'dpv'=>$dp,'idpratica'=>$idpratica,'modelp'=>$modelp]);
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
        $model = AllegatiPaesistica::findOne($id); 
        $model->delete();
        return $this->redirect(['allegati','idpratica'=>$idpratica]);
    }
    
    
    
    
    
    public function actionEliminaAllegati($id) 
    {
        $model = new AllegatiFile();
         //$model = new UploadForm();
        //$modtec = new AllegatiTecnici();
        $atecnici=AllegatiFile::find()
                        ->where(['id_pratica' => $id,'tipologia'=>0,'tipopratica'=>3]);
	$alegal=AllegatiFile::find()
                        ->where(['id_pratica' => $id,'tipologia'=>1,'tipopratica'=>3]);
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
            //ini_set('max_execution_time', 5*60);
            Yii::$app->response->sendFile($filename);
            //return Yii::$app->response->xSendFile($filename); //,$filename,['mimeType'=>'pdf','inline'=>false]);    
            //return true;
            //exit;

            //return Yii::$app->response->sendFile($filename); //,file_get_contents($filename),'application/pdf');
            // Yii::$app->session->setFlash('success', 'file: ' . $filename);
        } else {
            throw new NotFoundHttpException('file ' . $filename .' non trovato');
            Yii::$app->session->setFlash('error', 'Non sono riuscito a trovare il file: ' . $filename);
            
        }
        //Yii::$app->end();
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
    public function actionAutorizzazione($id)
    {
       // require_once \Yii::getAlias('@vendor') . '/phpoffice/phpword/bootstrap.php';

        date_default_timezone_set('UTC');
        error_reporting(E_ALL);
        define('CLI', (PHP_SAPI == 'cli') ? true : false);
        $model = Paesistica::findOne($id);
        
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
            $path=Yii::getAlias('@pratiche') . '/' . trim(sprintf('%09u', $id)) . '/'; 
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
        $model = Paesistica::findOne($id);
        $hoAllegati = $model->getAllegati()->count()>0;
        if ($hoAllegati) {
           Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_ERROR, [
                    [
                        //'title' => 'Errore',
                        'text' => 'Non posso cancellare la pratica perché contiene allegati!',
                        //'confirmButtonText' => 'Done!',
                        'timer' => 2500,
                        'showConfirmButton'=>false,
                        'position'=>'center',
                    ]
            ]);
            return $this->redirect(['index']);
        }
        $hoPareri = $model->getPareri()->count()>0;
        if ($hoAllegati) {
           Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_ERROR, [
                    [
                        //'title' => 'Errore',
                        'text' => 'Non posso cancellare la pratica perché contiene pareri commissioni collegati!',
                        //'confirmButtonText' => 'Done!',
                        'timer' => 2500,
                        'showConfirmButton'=>false,
                        'position'=>'center',
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
		$pratica=Paesistica::findOne($id);
                $atecnici=AllegatiFile::find()
                        ->where(['id_pratica' => $id,'tipologia'=>0,'tipopratica'=>3])
                        ->all();
                $alegal=AllegatiFile::find()
                        ->where(['id_pratica' => $id,'tipologia'=>1,'tipopratica'=>3])
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
     
     
 