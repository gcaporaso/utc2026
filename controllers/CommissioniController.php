<?php

namespace app\controllers;

use Yii;
use app\models\CommissioniSearch;
use app\models\ComponentiSearch;
use app\models\Commissioni;
use app\models\Composizioni;
use app\models\TipoCommissioni;
use app\models\SeduteSearch;
use app\models\ComponentiCommissioni;
//use app\models\EdiliziaSearch;
use app\models\Edilizia;
use kartik\dynagrid\models\DynaGridConfig;
use yii\data\ActiveDataProvider;
use app\models\PareriCommissioni;
use PhpOffice\PhpWord;
use PhpOffice\PhpWord\Shared;
use PhpOffice\PhpWord\Settings;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class CommissioniController extends \yii\web\Controller
{
    public function actionCommissioni()
    {
        $searchModel = new CommissioniSearch;
        $model = new DynaGridConfig();
	$dataProvider = $searchModel->search(Yii::$app->request->queryParams);  
        return $this->render('commissioni',[
                        'Provider' => $dataProvider,
			'Search' => $searchModel,
			'model' => $model]);
    }

    
    public function actionNuovacommissione()
    {
     $model = new Commissioni();

         if ($model->load(Yii::$app->request->post())) {
//            if ($model->validate()) {
                $model->save(false);
                
                return $this->redirect(['commissioni/commissioni']);
            // all inputs are valid
//            } else {
//            // validation failed: $errors is an array containing error messages
//                $errors = $model->errors;
//                return $this->redirect(['commissioni/commissioni']);
//            }
        }
        
        return $this->render('nuovacommissione', [
            'model' => $model,
        ]);
    }
    
    
    
    /**
     * Signup new user
     * @return string
     */
    public function actionUpdatecommissione($idcommissione)
    {
    
	$model = Commissioni::findOne($idcommissione);
            

     if ($model->load(Yii::$app->request->post())) {
         if ($model->validate()) {
            if ($model->save()) {
             echo Yii::$app->session->setFlash('success', "Commissione Aggiornata!");
             return $this->redirect(['commissioni']);
           } else {
               echo Yii::$app->session->setFlash('error', "Si è verificato un Errore. La Commissione non è stata aggiornato!");
           }
         }
    } 
    return $this->render('updatecommissione',['model' => $model]);

  }
    
    
    
    
    
    
    
    
     public function actionComposizione($idcommissione) 
    {
        $model = new Composizioni();
        $dp1 = new ActiveDataProvider([
            'query' => Composizioni::find()
                        ->where(['commissioni_id'=>$idcommissione])
//                        ->with(['componenti','titolo']),
//            'pagination' => false,
        ]);
        
//        $elcomponenti= Composizioni::find()
//                        ->where(['commissioni_id' => $idcommissione])
//                        ->all();
//        $dp1=new ActiveDataProvider(['query' => $elcomponenti]);
        
        $componente= new ComponentiCommissioni();
        
        $nomecommissione=Commissioni::findOne($idcommissione)->Descrizione;
        if (Yii::$app->request->post()) {
                $data = Yii::$app->request->post();
                $idcomp = $data['ComponentiCommissioni']['idcomponenti_commissioni'];
                    $model->commissioni_id=$idcommissione;
                    $model->componenti_id=$idcomp;
                if($model->save(false)){
                    return $this->redirect(['composizione', 'dpv1'=>$dp1, 'idcommissione'=>$idcommissione, 'nome'=>$nomecommissione, 'componente'=>$componente]);
                } else {
                // error in saving model
                Yii::$app->session->setFlash('error', 'Non sono riuscito a salvare nel database i dati! ' . date('Y-m-d'));
                //Yii::$app->session->setFlash('success', 'bla bla 2');
                //Yii::$app->session->setFlash('error', 'bla bla 3');
                }
        }
        return $this->render('composizione2', [
            'model'=>$model, 'dpv1'=>$dp1, 'idcommissione'=>$idcommissione, 'nome'=>$nomecommissione, 'componente'=>$componente
        ]);
    }
    
    

    /**
     * Signup new user
     * @return string
     */
    public function actionCancellapraticaseduta($idcommissione, $idseduta,$pratica)
    {
        $model = PareriCommissioni::findOne($pratica);
        if (isset($model)) {
            $model->delete();
        } else {
            Yii::$app->session->setFlash('error', 'Non sono riuscito a cancellare la pratica dalla seduta della commissione! ');
        }
        return $this->redirect(['parerisedute','idcommissione'=>$idcommissione,'idseduta'=>$idseduta]);
    }



    /**
     * Signup new user
     * @return string
     */
    public function actionCancellacomposizionecomponente($idcomponente,$idcommissione)
    {
        $model = Composizioni::findOne($idcomponente);
        if (isset($model)) {
            $model->delete();
        } else {
            Yii::$app->session->setFlash('error', 'Non sono riuscito a cancellare il componente! ');
        }
        return $this->redirect(['composizione','idcommissione'=>$idcommissione]);
    }
    

    
    
    
    
    
    /**
     * 
     * @param type $idtipocommissione
     * @return type
     * idcommissioni viene impostato per default al massimo id 
     * in questo modo si presenta l'ultima commissione inserita
     * parametro che è comunque configurabile nella fase di input
     */
    public function actionSedute($idtipocommissione)
    {
        $maxid = Commissioni::find()
                ->where(['Tipo'=>$idtipocommissione])
                ->max('idcommissioni');
//        $idcomattuale= Commissioni::find()
//                    ->where(['Tipo'=>$idtipocommissione,'idcommissioni' => Commissioni::find()->select(['idcommissioni'=>'MAX(`idcommissioni`)'])->one()->idcommissioni])
//                    ->one();
//        if (!isset($idcomattuale)) {$idcomattuale=1;};
//        if (!isset($idtipocommissione)) {$idtipocommissione=1;};
        $searchModel = new SeduteSearch;
        $model = new DynaGridConfig();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$idtipocommissione); 
        return $this->render('sedute',[
                        'Provider' => $dataProvider,
			'Search' => $searchModel,
                        //'SelProvider' => $SelComponenti,
                        'idtipocommissione'=>$idtipocommissione,
                        'idcommissione'=>$maxid,
			'model' => $model]);
    }

    
    
    public function actionNuovaseduta($idtipocommissione,$idcommissione)
    {
     $model = new \app\models\SeduteCommissioni();
     $newnumber = \app\models\SeduteCommissioni::find()
                ->innerJoin('commissioni', 'sedute_commissioni.commissione_id=commissioni.idcommissioni')
                ->where(['year(dataseduta)' => date('Y'),'commissioni.Tipo'=>$idtipocommissione])
                ->select('max(numero)')
                 ->scalar();
        $newnumber=$newnumber+1;
        $model->commissione_id=$idcommissione;
        $model->statoseduta=0;
        $model->numero=$newnumber;
        $model->presenze=0;
         if ($model->load(Yii::$app->request->post())) {
                //$model->presenze=0;
                
                $model->save(false);
                
                return $this->redirect(['commissioni/sedute','idtipocommissione'=>$idtipocommissione]);
            // all inputs are valid
//            } else {
//            // validation failed: $errors is an array containing error messages
//                $errors = $model->errors;
//                return $this->redirect(['commissioni/commissioni']);
//            }
        }
        
        return $this->render('nuovaseduta', [
            'model' => $model,
            'idtipocommissione'=>$idtipocommissione
        ]);
    }
    
    
     /**
     * Signup new user
     * @return string
     */
    public function actionCancellasedutacommissione($idseduta,$idcommissione)
    {
       $model = \app\models\SeduteCommissioni::findOne($idseduta);
        if (isset($model)) {
            $model->delete();
        } else {
            Yii::$app->session->setFlash('error', 'Non sono riuscito a cancellare il componente! ');
        }
        //return $this->redirect(['commissioni/sedute','idcommissione'=>$idcommissione]);
        return $this->redirect(['commissioni/sedute','idtipocommissione'=>$model->commissione->Tipo]);
    }
    
    
    public function actionModificaseduta($idcommissione,$idseduta)
    {
    
    $model = \app\models\SeduteCommissioni::findOne($idseduta);
	 
        if ($model->load(Yii::$app->request->post())) {
            //$model->orarioconvocazione=date('HH:MM:SS',strtotime($model->orarioconvocazione));
            if ($model->validate() & $model->save(false)) {
                //dd($model->presenze);
                //return $this->redirect(['commissioni/sedute','idcommissione'=>$idcommissione]);
                return $this->redirect(['commissioni/sedute','idtipocommissione'=>$model->commissione->Tipo]);
            // all inputs are valid
            } else {
            // validation failed: $errors is an array containing error messages
                $errors = $model->errors;
                 Yii::$app->session->setFlash('error', 'Non sono riuscito a salvare!');
                return $this->redirect(['commissioni/modificaseduta','idcommissione'=>$idcommissione,'idseduta'=>$idseduta]);
            }
        }
        
        
         return $this->render('modificaseduta',[
			'model' => $model,
                        'idcommissione'=>$idcommissione,
                        'idseduta'=>$idseduta]);
    }
    
    
    
    
    
    public function actionParerisedute($idcommissione,$idseduta)
    {
         //$searchModel = new EdiliziaSearch;
        
	 $model = new DynaGridConfig();
	 //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    
        $dataProvider = new ActiveDataProvider([
            'query' => Edilizia::find()
                ->where(['Stato_Pratica_id'=>3]),
                'pagination' => [
                'pageSize' => 10,
                ],
        ]);
         
        $modelpareri = new DynaGridConfig();
	 //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    
        $PareridataProvider = new ActiveDataProvider([
            'query' => PareriCommissioni::find()
                ->where(['commissioni_id'=>$idcommissione,'seduta_id'=>$idseduta])
             ->joinWith('pratica')
             ->orderBy('DataProtocollo ASC'),
//                ->with('prices' => function(\yii\db\ActiveQuery $query) {
//                    $query->orderBy('device_price DESC');
//                    }),
                'pagination' => [
                'pageSize' => 10,
                ],
        ]);
        
        
        
         return $this->render('parerisedute',[
                        'Provider' => $dataProvider,
                        'ProviderPareri'=>$PareridataProvider,
			//'Search' => $searchModel,
                        'idcommissione'=>$idcommissione,
                        'idseduta'=>$idseduta,
                        'modelpareri'=>$modelpareri,
			'model' => $model]);
    }
    
    
    
    
        public function actionModificaparere($idparere)
    {
         //$searchModel = new EdiliziaSearch;
         $model = PareriCommissioni::findOne($idparere);
	 $modelpratica = Edilizia::findOne($model->pratica_id);
         
	 
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $model->save();
                return $this->redirect(['commissioni/parerisedute','idcommissione'=>$model->commissioni_id,'idseduta'=>$model->seduta_id]);
            // all inputs are valid
            } else {
            // validation failed: $errors is an array containing error messages
                $errors = $model->errors;
                return $this->redirect(['commissioni/modificaparere','idparere'=>$idparere]);
            }
        }
        
        
         return $this->render('modificaparere',[
                        'pratica'=>$modelpratica,
			'model' => $model]);
    }

    
    
 /**
     * Genera il documento Verbale Seduta con i dati all'interno.
     * 
     * Edit a Word 2007 and newer .docx file.
     * Utilizes the zip extension http://php.net/manual/en/book.zip.php
     * to access the document.xml file that holds the markup language for
     * contents and formatting of a Word document.
     *
     * In this example we're replacing some token strings.  Using
     * the Office Open XML standard ( https://en.wikipedia.org/wiki/Office_Open_XML )
     * you can add, modify, or remove content or structure of the document.
     * @param integer $idseduta
     * @return mixed
     */
   public function actionCompilaverbale($idseduta)
    {
        //require_once \Yii::getAlias('@vendor') . '/phpoffice/phpword/bootstrap.php';
        $seduta= \app\models\SeduteCommissioni::findOne($idseduta);
        // dati dei componenti della commissione
        $tipo= \app\models\SeduteCommissioni::find()
                ->where(['idsedute_commissioni'=>$idseduta])
                ->with(['commissione'])
                ->one();
        $componenti= Composizioni::find()
                        ->where(['commissioni_id'=>$seduta->commissione_id])
                        ->with(['componenti','titolo'])
                        ->all();
        $numcmp=Composizioni::find()
                        ->where(['commissioni_id'=>$seduta->commissione_id])
                        ->with('componenti')
                        ->count();
        // dati delle pratiche esaminate
        $pratiche= PareriCommissioni::find()
                    ->where(['seduta_id'=>$idseduta])
                    ->innerJoin('edilizia', 'pratica_id=edilizia_id')
                    ->orderBy('DataProtocollo')
                    //->with(['praticaByDataASC'])
                    //->with(['praticaByDataASC'])
                    ->all();
        $numpratiche=PareriCommissioni::find()
                    ->where(['seduta_id'=>$idseduta])
                    ->with(['praticaByDataASC'])
                    ->count();
        
        
        
        date_default_timezone_set('UTC');
        error_reporting(E_ALL);
        define('CLI', (PHP_SAPI == 'cli') ? true : false);
        //$model = Edilizia::findOne($id);
        
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
        
            if ($tipo->commissione->Tipo==1) {
                // trattasi di commissione edilizia
                $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('/var/www/utcbim/web/modelli/Mod_CM_01_Verbale_Commissione_Edilizia_REV01.docx');
            } else {
                 //if ($seduta->commissione->Tipo=2)
                // trattasi di commissione del paesaggio
                $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('/var/www/utcbim/web/modelli/Mod_CM_02_Verbale_Commissione_Paesaggio_REV01.docx');
            }

            $templateProcessor->setValue('Numero',$seduta->numero );
            //$templateProcessor->setValue('Numero','*'.Yii::getAlias('@app').'*');
            //echo Yii::getAlias('@foo');   
            $templateProcessor->setValue('dataseduta',Yii::$app->formatter->asDate($seduta->dataseduta,'php:d-m-Y'));
            
            // Dati istanza
            $templateProcessor->setValue('anno',Yii::$app->formatter->asDate($seduta->dataseduta,'php:Y'));
            $templateProcessor->setValue('mese',Yii::$app->formatter->asDate($seduta->dataseduta,'php:F'));
            $templateProcessor->setValue('giorno',Yii::$app->formatter->asDate($seduta->dataseduta,'php:d'));
            
            //foreach ($componenti as $componente) {
                $templateProcessor->cloneBlock('Commissione', $numcmp, true, true);
            //}
                $nm=1;
                 foreach ($componenti as $componente) {
                     $templateProcessor->setValue('titolo#'.$nm,$componente->componenti->competenze->abbr_titolo);
                     $templateProcessor->setValue('Cognome#'.$nm,$componente->componenti->Cognome);
                     $templateProcessor->setValue('Nome#'.$nm,$componente->componenti->Nome);
                     $exp=$nm-1;
                     
                     if (pow(2,$exp) & $seduta->presenze) {
                         $templateProcessor->setValue('presenza#'.$nm,'Presente');
                     } else {
                         $templateProcessor->setValue('presenza#'.$nm,'Assente');
                     };
                     $nm=$nm+1;
                 }
            $templateProcessor->setValue('orainizio',Yii::$app->formatter->asTime($seduta->orarioinizio,'php:H:i'));


            $templateProcessor->cloneBlock('Pratica', $numpratiche, true, true);
            //}
                $nm=1;
                 foreach ($pratiche as $pratica) {
                     $templateProcessor->setValue('NumeroProtocollo#'.$nm,$pratica->praticaByDataASC->NumeroProtocollo);
                     $templateProcessor->setValue('DataProtocollo#'.$nm,Yii::$app->formatter->asDate($pratica->praticaByDataASC->DataProtocollo,'php:d-m-Y'));
                     $templateProcessor->setValue('Richiedente#'.$nm,$pratica->praticaByDataASC->richiedente->nomeCompleto);
                     $templateProcessor->setValue('DescrizioneIntervento#'.$nm,$pratica->praticaByDataASC->DescrizioneIntervento);
                     $templateProcessor->setValue('parere#'.$nm,$pratica->testoparere);
                     $nm=$nm+1;
                 }
            $templateProcessor->setValue('orainizio',Yii::$app->formatter->asTime($seduta->orarioinizio,'php:H:i'));







            
            // Crea una directory per la pratica se non esiste (numero di 9 cifre con zeri iniziali)
            $path=Yii::getAlias('@commissioni') . '/' . trim(sprintf('%09u', $idseduta)) . '/'; 
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
            $file=$path . '/VerbaleCommissione' . '_' . trim(sprintf('%02u', $seduta->numero)) . '_del_' . Yii::$app->formatter->asDate($seduta->dataseduta,'php:d-m-Y').'_' . uniqid('') . '.docx';
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
    
    
    // rilevapresenze
    public function actionRilevapresenze($idseduta)
{
        $seduta= \app\models\SeduteCommissioni::findOne($idseduta);
        //$presenze=$seduta->presenze;
//        $SelComponenti = new ActiveDataProvider([
//            'query' => Composizioni::find()
//                        ->where(['commissioni_id'=>$idcommissione])
//                        ->with(['componenti','titolo']),
//            'pagination' => false,
//        ]);
//        $componenti=Composizioni::find()
//                        ->where(['commissioni_id'=>$seduta->commissione_id])
//                        ->with(['componenti','titolo'])
//                        ->all();
        $SelComponenti = new ActiveDataProvider([
            'query' => Composizioni::find()
                        ->where(['commissioni_id'=>$seduta->commissione_id])
                        ->with(['componenti','titolo']),
            'pagination' => false,
        ]);
    //$this->layout=false;
    
    return $this->renderAjax('_rilievopresenze2', [
        //'idcommissione' => $idcommissione,
        //'componenti'=>
        'presenti'=>$seduta->presenze,
        'idseduta'=>$idseduta,
        'seduta'=>$seduta,
        'SelProvider'=>$SelComponenti,
        
    ]);
}
   

 public function actionAggiornapresenze()
    {
         //$idcommissione=$_POST['idcommissione'];
         $idseduta=$_POST['idseduta'];
         $seduta= \app\models\SeduteCommissioni::findOne($idseduta);
         //dd($_POST['data']);
        if (isset($_POST['data'])) {
            //$keys=\yii\helper\Json::decode($_POST['keylist']);
            $keys=$_POST['data'];
        }
            $index = array_keys($keys);
            $idcomposizione = array_values($keys);
            $num = count($keys);
            $commissione=Composizioni::find()
                    ->where(['commissioni_id'=>$seduta->commissione_id])
                    ->with(['componenti'])
                    ->all();
            $i=0;
            $presenze=0;
            //dd($lista);
            foreach ($commissione as $componente) {
                if (in_array($componente->idcomposizioni, $idcomposizione)) {
                    $presenze=$presenze+pow(2,$i);
                }
                $i=$i+1;
            }
            
          $seduta= \app\models\SeduteCommissioni::findOne($idseduta);
          $seduta->presenze=$presenze;
          $seduta->save(false);
          
          
           Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            //print_r(json_encode($data));
                $dati = [
                    'success' => true,
                    ];
                return $dati;
        
      //  return $this->redirect(['parerisedute','idcommissione'=>$idcommissione,'idseduta'=>$idseduta]);
    }
    









//    public function actionSettapresenze() 
// {
//     
//        $idcommissione = $_POST['idcommissione'];
//        $componenti= Composizioni::find()
//                        ->where(['commissioni_id'=>$seduta->commissione_id])
//                        ->with(['componenti','titolo'])
//                        ->all();
//        
//        
//        $tipo=$_POST['tipo'];
//        if (isset($idpratica)) {
//        //print_r($idpratica);
//        // cerca i dati della pratica edilizia in archivio
//        $pratica= Edilizia::findOne($idpratica);
//        // cerca le rate già scadute 
//        // e cioè con data scadenza precedenti a oggi
//        //$oggi=date("Y-m-d");
//        $oneri= OneriConcessori::find()
//                        ->where(['edilizia_id' => $idpratica])
//                        ->andWhere(['pagata' => 0])
//                        ->andWhere(['<=','datascadenza',date("Y-m-d")])
//                        ->all();
//                        
//            $morosita='';
//            //var_dump($oneri);
//        if (isset($oneri)) {    
//            $num=0;
//            foreach ($oneri as $rata) {
//                $num=$num+1;
//                $morosita = $morosita . $num . ') Rata ' . $rata->ratanumero . ' di euro ' . $rata->importodovutorata . ' in scadenza ' . date('d-m-Y',strtotime($rata->datascadenza)).PHP_EOL;
//            }
//        };
//        //$modelemail->efrom='ing@comune.campolidelmontetaburno.bn.it';
//        //$modelemail->eto=isset($pratica->richiedente->Email)?$pratica->richiedente->Email:'';
//        //$modelemail->esubject='Sollecito Pagamento Oneri Concessori';
//        $body='Spettabile Sig. ' . $pratica->richiedente->Nomecompleto .PHP_EOL;
//        $body=$body . 'Relativamente alla pratica edilizia Permesso di Costruire n. ' . $pratica->NumeroTitolo . ' del ' . date('d-m-Y',strtotime($pratica->DataTitolo)) .PHP_EOL;
//        $body=$body . 'agli atti di ufficio non risulta il pagamento delle seguenti rate di Oneri Concessori:' .PHP_EOL;
//        $body=$body . $morosita;
//        $body=$body . "Qualora abbia provveduto al pagamento delle suddette rate si prega di consegnare all'UTC le relative ricevute.".PHP_EOL;
//        $body=$body . 'Ove invece non abbia ancora pagato si diffida la sig. vostra a provvedere entro dieci giorni,'.PHP_EOL;
//        $body=$body . "consegnando poi le ricevute all'ufficio tecnico comunale.".PHP_EOL;
//        $body=$body . "decorsi i termini suddetti si procederà alla riscossione coattiva.".PHP_EOL;
//        $body=$body . "Cordiali Saluti.".PHP_EOL;
//        $body=$body . "Comune di Campoli del Monte Taburno".PHP_EOL;
//        $body=$body . "Il Responsabile del Settore Tecnico".PHP_EOL;
//        $body=$body . "Ing. Giuseppe Caporaso";
//        
//        if ($tipo=='email') {
//            $efrom='ing@comune.campolidelmontetaburno.bn.it';
//        } else {
//            $efrom='ingcampoli@pec.it';
//        }
//        //$modelemail->ebody=$body;        
//        
//         Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//         if ($tipo=='email') {
//            $eto=isset($pratica->richiedente->Email)?$pratica->richiedente->Email:'';
//         } else {
//            $eto=isset($pratica->richiedente->PEC)?$pratica->richiedente->PEC:'';
//         }
//            //print_r(json_encode($data));
//                $dati = [
//                    //'success' => true,
//                    'efrom' => $efrom,
//                    'eto'=>$eto,
//                    'esubject'=>'Sollecito Pagamento Oneri Concessori',
//                    'ebody'=>$body,
//                    //'message' => 'Model has been saved.',
//                    ];
//                return $dati;
//        
//        } else {
//             Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//            //print_r(json_encode($data));
//                $dati = [
//                    //'success' => true,
//                    'efrom' => $efrom,
//                    'eto'=>'',
//                    'esubject'=>'Sollecito Pagamento Oneri Concessori',
//                    'ebody'=>'',
//                    //'message' => 'Model has been saved.',
//                    ];
//                return $dati;
//        }
//        
//        
//}

    
    
    
    
    
    
    
    
    public function actionAddpraticaseduta()
    {
         $idcommissione=$_POST['idcommissione'];
         $idseduta=$_POST['idseduta'];
        if (isset($_POST['data'])) {
            //$keys=\yii\helper\Json::decode($_POST['keylist']);
            $keys=$_POST['data'];
        }
        // aggiungo le pratiche selezionate alla seduta verificando che non è già presente
//        $elpratiche= PareriCommissioni::find()
//                ->where(['commissioni_id'=>$idcommissione,'seduta_id'=>$idseduta])
//                ->asArray()
//                ->all();
//        if (!isset($keys)) {
//            echo Yii::$app->session->setFlash('error', "Si è verificato un Errore. Pratiche non aggiunte alla sedua!");
//            return $this->redirect(['parerisedute','idcommissione'=>$idcommissione,'idseduta'=>$idseduta]);
//        }
            $lista = array_keys($keys);
            $aggiunte = 0;
            $duplicate = 0;

            foreach ($lista as $k) {
                $isPresente = PareriCommissioni::find()
                    ->where(['commissioni_id' => $idcommissione, 'seduta_id' => $idseduta, 'pratica_id' => $keys[$k]])
                    ->count();
                if ($isPresente == 0) {
                    $model = new PareriCommissioni();
                    $model->commissioni_id = $idcommissione;
                    $model->seduta_id      = $idseduta;
                    $model->pratica_id     = $keys[$k];
                    $model->tipoparere_id  = 1;
                    $model->testoparere    = '#';
                    $model->save(false);
                    $aggiunte++;
                } else {
                    $duplicate++;
                }
            }

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['success' => true, 'aggiunte' => $aggiunte, 'duplicate' => $duplicate];
        }
        return $this->redirect(['parerisedute','idcommissione'=>$idcommissione,'idseduta'=>$idseduta]);
    }
    
    
    
    
    
   
    
    
    
    
    
    
    
    
    
    
    
    public function actionComponenti()
    {
        $searchModel = new ComponentiSearch;
        $model = new DynaGridConfig();
	$dataProvider = $searchModel->search(Yii::$app->request->queryParams);  
        return $this->render('componenti',[
                        'Provider' => $dataProvider,
			'Search' => $searchModel,
			'model' => $model]);
    }

    
    
    
    public function actionNuovocomponente()
    {
     $model = new ComponentiCommissioni();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $model->save();
                return $this->redirect(['componenti']);
            // all inputs are valid
            } else {
            // validation failed: $errors is an array containing error messages
                $errors = $model->errors;
                return $this->redirect(['componenti']);
            }
        }
        
        return $this->render('nuovocomponente', [
            'model' => $model,
        ]);
    }
    
     /**
     * Signup new user
     * @return string
     */
    public function actionUpdatecomponente($idcomponente)
    {
    
	$model = ComponentiCommissioni::findOne($idcomponente);
            

     if ($model->load(Yii::$app->request->post())) {
         if ($model->validate()) {
            if ($model->save()) {
             echo Yii::$app->session->setFlash('success', "Componente Aggiornato!");
             return $this->redirect(['componenti']);
           } else {
               echo Yii::$app->session->setFlash('error', "Si è verificato un Errore. Il Componente non è stata aggiornato!");
           }
         }
    } 
    return $this->render('updatecomponente',['model' => $model]);

  }
    
    
  /**
     * Signup new user
     * @return string
     */
    public function actionDeleteComponente($idcomponente)
    {
        $model = ComponentiCommissioni::findOne($idcomponente);
        $model->delete();
        return $this->redirect(['componenti']);
    }
    
    
    /**
     * Visualizza un Lavoro User model.
     * @param integer $id
     * @return mixed
     */
    public function actionViewcomponente($idcomponente)
    {
		$model = ComponentiCommissioni::findOne($idcomponente);
        return $this->render('viewcomponente', [
                'model' => $model
        ]);
    }
  
    
    
    
    
    
   

    public function actionPareri()
    {
        return $this->render('pareri');
    }

   

}
