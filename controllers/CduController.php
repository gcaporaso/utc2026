<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controllers;
//include_once(\Yii::getAlias('@vendor').'/phayes/geophp/geoPHP.inc');

use Yii;
use yii\data\ActiveDataProvider;
use kartik\dynagrid\models\DynaGridConfig;
use PhpOffice\PhpWord;
use PhpOffice\PhpWord\Shared;
use PhpOffice\PhpWord\Settings;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;
use app\models\Cdu;
use app\models\CduSearch;
use app\models\Modulistica;
use app\models\DatiMappe;
//use yii\BaseYii;
//use phayes\geophp;

class CduController extends \yii\web\Controller
{

    
    
    
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
	 $searchModel = new CduSearch;
	 $model = new DynaGridConfig();
	 $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
         $dataProvider->sort->defaultOrder = ['DataProtocollo' => SORT_DESC];
        //$dataProvider->pagination = ['pageSize' => 15];
         

        return $this->render('index', [
            'Provider' => $dataProvider,
			'Search' => $searchModel,
			'model' => $model,
                        //'dateto'=>$date,
        ]);
    }
    
    /**
     * Signup new user
     * @return string
     */
    public function actionDelete($id)
    {
        $model = Cdu::findOne($id); 
        $model->delete();
        return $this->redirect(['index']);
    }
    
    
    
     /**
     * Signup new user
     * @return string
     */
    public function actionUpdate($id)
    {
    
	$model = Cdu::findOne($id);
            

     if ($model->load(Yii::$app->request->post())) {
         if ($model->validate()) {
            if ($model->save()) {
             echo Yii::$app->session->setFlash('success', "Richiesta Aggiornata!");
             return $this->redirect(['index']);
           } else {
               echo Yii::$app->session->setFlash('error', "Si è verificato un Errore. La Pratica non è stata aggiornata!");
           }
         }
    } 
    return $this->render('modifica',['model' => $model]);

  }
    
    
 
  /**
     * Action Gesione Pratica
     * @return string
     */
    public function actionAtti($idcdu)
    {
          //$model = new AllegatiFile();
       $model = new \yii\base\DynamicModel(['modello_id']);
       $model->addRule(['modello_id'], 'safe');
//        $elenco=Modulistica::find()
//                        ->where(['categoria'=>2]);
       // $modelp = Cdu::findOne($idcdu);
        if ($model->load(Yii::$app->request->post())) {
            return $this->redirect(['cdu/compilacdu', 'modello_id'=>$model->modello_id,'idcdu'=>$idcdu]);
        }
        
        
        
        return $this->render('atti', [
            'model'=>$model,'idcdu'=>$idcdu
        ]);
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
    public function actionDocumento($modello_id, $idcdu)
    {
        $modello=Modulistica::findOne($modello_id);
        $filemodello=$modello->path . $modello->nomefile;                
        $model = Cdu::findOne($idcdu);
       // require_once \Yii::getAlias('@vendor') . '/phpoffice/phpword/bootstrap.php';

        date_default_timezone_set('UTC');
        error_reporting(E_ALL);
        define('CLI', (PHP_SAPI == 'cli') ? true : false);
        
        
        
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
            
//            $templateProcessor->setValue('NumeroTitolo',$model->NumeroTitolo );
//            $templateProcessor->setValue('DataTitolo',Yii::$app->formatter->asDate($model->DataTitolo,'php:d-m-Y'));
            
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
        
        
  
    
    
    
    
    
    
    
    
    
    
     public function actionNuovo()
    {
/**
     * Creates a new Cdu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
     
        $model = new Cdu();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
//                $model->Stato_Pratica_id=1;
//                $model->TitoloOneroso=0;
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
        //$model->id_titolo=4;
        return $this->render('nuovo', [
            'model' => $model,
        ]);
     
    }

    public function actionTestGeo()
    {
        require_once \Yii::$app->basePath.'/vendor/phayes/geophp/geoPHP.inc';

            $poly = \geoPHP::load('POLYGON((0 0, 10 0, 10 10, 0 10, 0 0))', 'wkt');
            $point = \geoPHP::load('POINT(5 5)', 'wkt');
            if (class_exists('GEOSGeometry')) {
                return $this->renderContent("✔ GEOS attivo\n");;
            } else {
                return $this->renderContent("⚠ GEOS NON attivo\n");
            }
            if ($poly->contains($point)) {
                return $this->renderContent("GEOS attivo: il poligono contiene il punto", __METHOD__);
            } else {
                return $this->renderContent("GEOS attivo: il poligono NON contiene il punto");
            }

         if (\geoPHP::geosInstalled()) {
            return $this->renderContent('geoPHP è disponibile!');
            //print "GEOS is installed.\n";
        }
        else {

            if (class_exists('GEOSGeometry')) {
                //echo "GEOS disponibile!";
                return $this->renderContent('GEOS disponibile');
            } else {
                return $this->renderContent("GEOS NON disponibile, uso solo PHP puro.");
            }
            
            //print "GEOS is not installed.\n";
        }
        
        // if (class_exists('geoPHP')) {
        //     return $this->renderContent('geoPHP è disponibile!');
        // } else {
        //     return $this->renderContent('geoPHP non caricata');
        // }
    }




    public function actionHello()
    {
        return $this->render('hello');
    }

    /*
     * Definisce la destinazione urbanistica di una particella catastale
     * 
     * @param $sez - Sezione Catastale censuaria
     * @param $foglio - Foglio in cui ricade la particella catastale
     * @param $particella - numero della particella catastale
     * 
     * @return Ritorna una stringa che descrive le zone omogenee in cui ricade la particella
     *         e i relativi vincoli
     */
    public function Urbanistica($sez, $foglio, $particella)
    {
      // carico il file json contenente la geometria particelle catastali
      // in nome del file da caricare in base al foglio
    //  $pathfile = 'mappe/'. \Yii::$app->params['Comune']. '/';
    //  $nomefile = 'foglio' . trim(sprintf('%02u', $foglio)) . '.json';


    $ultimaMappa = DatiMappe::find()
    ->orderBy(['dataMappe' => SORT_DESC])
    ->one();     
    $pathfile = $ultimaMappa ?  $ultimaMappa->folder_path:'mappe/b542/V2025-09-22';
    $nomefile = 'B542_00' . trim(sprintf('%02u', $foglio)) . '00.geojson';    
      //$zone=array();
      // leggo il contenuto del file
      $catasto = file_get_contents($pathfile . '/'. $nomefile);
      // converto la stringa in oggetto 
      $jsone =json_decode($catasto,true);

    // Filtra solo le features con LIVELLO == PARTICELLE
    $filteredFeatures = array_filter($jsone['features'], function($feature) {
        return isset($feature['properties']['LIVELLO']) 
            && $feature['properties']['LIVELLO'] === 'PARTICELLE';
    });

    // Crea un nuovo oggetto GeoJSON solo con quelle feature
    $newGeoJson = [
        'type' => 'FeatureCollection',
        'features' => array_values($filteredFeatures) // array_values per reindicizzare
    ];

    // Se vuoi come oggetto PHP:
    $particelle = json_decode(json_encode($newGeoJson));

      $rpc=null;
      // trovo la geometria della particella
      foreach($particelle->features as $prc) {
          if (($prc->properties->FOGLIO == sprintf('%04u', $foglio)) and ($prc->properties->CODICE == $particella)) {
              $rpc = \geoPHP::load($prc->geometry,'json');
            $geoms = $rpc->getComponents(); 
            $first_wkt = $geoms[0]->out('wkt');
            //   //$linestring1 = $geomComponents[0]->getComponents();
            //   //$linestring2 = $geomComponents[1]->getComponents();
            Yii::info('***************************************************************************');
            Yii::info('PARTICELLA FOGLIO '. $foglio . ' Numero ' . $particella . ' >> GEOMETRIA');
            Yii::info($first_wkt);
            Yii::info('***************************************************************************');
              break;
          }
      }
     if (is_null($rpc)) {
         //non ho trovato la particella
        return 'Particella non trovata!';
         // da fare **********************************
     }
     
    // $rpc=$rpc->buffer(0);
     //$rpc=$rpc->geos->is_valid;
//     if ($rpc->geos()->is_valid) {
//         //non ho trovato la particella
//        return 'Geometria Particella non valida!';
//         // da fare **********************************
//     }
     
    //****************************************
    // ricerca zona del piano urbanistico ****
    // ***************************************
    $prgf = file_get_contents("mappe/b542/prg_epsg7792.geojson");
    $json_b = json_decode($prgf);

    // interrogo i vari layer per verificare la destinazione urbanistica
    // verifico se la particella è contenuta interamente nella zona
    $destinazione='non trovata!';
    $trovata=false;
    foreach($json_b->features as $zona) {
        Yii::info('***************************************************************************');
        Yii::info('PRG zona: ' . $zona->properties->z . ' - ' . $zona->properties->estes);

        
        $zo = \geoPHP::load($zona,'json');
        Yii::info($zona->geometry->coordinates);
        Yii::info('***************************************************************************');
       // $zo=$zo->buffer(0);
            //if($zo->contains($rpc)) {
            if($zo->covers($rpc)) {
                $destinazione = $zona->properties->z . ' - ' . $zona->properties->estes;
                //echo 'Trovata : =>' . $zona->properties->Z; 
                Yii::info('TROVATA!!!!');
                $trovata=true;
                break;    
            }
    
    }
     
//    if ($trovata=false) {
//        $destinazione='false';
//    } else {
//        $destinazione='true';
//    }
     
    // se la particella non è interna allora interseca un contorno di zona
        if ($trovata==false) {
            $destinazione='';
            $des=array();
            foreach($json_b->features as $zona) {
            $zo = \geoPHP::load($zona,'json');
            //print_r($zona);
            if ($zo->intersects($rpc)) {
                $in = $zo->intersection($rpc);       
                    //print_r($in);
                    if (!$in==null) {
                    //ho trovato una intersezione con una zona del PRG
                    //$area = round($in->getArea()*100000.0*100000.0,0);
                    $area = round($in->getArea(),0);
                    array_push($des,['zona'=>$zona->properties->z . ' - ' . $zona->properties->estes, 'area'=>(string)$area]);
                    //if ($area>5) {
                    //$destinazione = $destinazione. $zona->properties->Z . ' - ' . $zona->properties->estes . ' per mq ' . (string)$area . ' *         ';
                    //};
                    //echo 'Trovata : =>' . $zona->properties->Z; 
                    $trovata=true;

                   // break;    
                    }
            }
            }
            // esamino il risultato ed elimino le zone con meno di 3 mq
            $des1=array();
            $arr_length = count($des);
            for($i=0;$i<$arr_length;$i++)
            {
                if ((int)$des[$i]['area']>3) {
                    array_push($des1,$des[$i]);
                }
            // calculations
            }
            
            $arr_length1 = count($des1);
            for($i=0;$i<$arr_length1;$i++)
            {
                if ($arr_length1>1) {
                    if ($i==$arr_length1-1) {
                    //$d =$d . $a[$i]['zona']. ' per ' . $a[$i]['area'] . ' ';
                    $destinazione =$destinazione . $des1[$i]['zona'] . ' per la restante superficie'.'         ';
                    } else {
                    $destinazione =$destinazione . $des1[$i]['zona'] . ' per mq '. $des[$i]['area']. '         ';
                    }
                } else {
                    $destinazione =$des1[$i]['zona'];
                }
            }
            
            
        } 
        
        
        // ritorno una stringa con la destinazione urbanistica della particella
        return $destinazione; 
    }
    
    
    
     /*
     * Definisce i Vincoli a cui è soggetta una particella catastale
     * 
     * @param $sez - Sezione Catastale censuaria
     * @param $foglio - Foglio in cui ricade la particella catastale
     * @param $particella - numero della particella catastale
     * 
     * @return Ritorna una stringa che descrive i vincoli a cui è soggetta la particella
     */
    public function Vincoli($sez, $foglio, $particella)
    {
        
          //****************************************
    // ricerca dei vincoli ****
    // 1 - VINCOLO PAESAGGISTICO
    // ***************************************
    // $pathfile = 'mappe/'. \Yii::$app->params['Comune']. '/';
    // $nomefile = 'foglio' . trim(sprintf('%02u', $foglio)) . '.json';
    $ultimaMappa = DatiMappe::find()
    ->orderBy(['dataMappe' => SORT_DESC])
    ->one();     
    $pathfile = $ultimaMappa ?  $ultimaMappa->folder_path:'mappe/b542/V2025-09-22';
    $nomefile = 'B542_00' . trim(sprintf('%02u', $foglio)) . '00.geojson';    
      //$zone=array();
      // leggo il contenuto del file
      $catasto = file_get_contents($pathfile . '/'. $nomefile);
      // converto la stringa in oggetto 
      $jsone =json_decode($catasto,true);
// Filtra solo le features con LIVELLO == PARTICELLE
    $filteredFeatures = array_filter($jsone['features'], function($feature) {
        return isset($feature['properties']['LIVELLO']) 
            && $feature['properties']['LIVELLO'] === 'PARTICELLE';
    });

    // Crea un nuovo oggetto GeoJSON solo con quelle feature
    $newGeoJson = [
        'type' => 'FeatureCollection',
        'features' => array_values($filteredFeatures) // array_values per reindicizzare
    ];

    // Se vuoi come oggetto PHP:
    $particelle = json_decode(json_encode($newGeoJson));




    $ptpf = file_get_contents("mappe/b542/ptp_epsg7792.geojson");
    $json_b = json_decode($ptpf);

    
    // leggo il contenuto del file
    //   $catasto = file_get_contents($pathfile . $nomefile);
    //   // converto la stringa in oggetto 
    //   $jsone =json_decode($catasto);


        // if (class_exists('geoPHP')) {
        //     echo "La classe geoPHP è stata caricata.\n";
        // } else {
        //     echo "La classe geoPHP non è stata ancora caricata.\n";
        //     // Potresti chiamare la funzione __autoload qui se hai implementato un autoloading personalizzato
        // }


      $rpc=null;
      // trovo la geometria della particella
      //Yii::info('**** INIZIO ESAME VINCOLI PARTICELLE **********');
      foreach($particelle->features as $prc) {
        //Yii::info('>>> FOGLIO: ' . $prc->properties->FOGLIO);
        Yii::$app->view->registerJs("console.log('Ciao dalla actionIndex!');");
          if (($prc->properties->FOGLIO == sprintf('%04u', $foglio)) and ($prc->properties->CODICE == $particella)) {
              $rpc = \geoPHP::load($prc->geometry,'json');
              break;
          }
      }
    
    
    if (is_null($rpc)) {
         //non ho trovato la particella
        return 'Particella non trovata!';
         // da fare **********************************
     }
    
    // interrogo i vari layer per verificare la destinazione urbanistica
    // verifico se la particella è contenuta interamente nella zona
    //$destinazione='non trovata!';
    $trovata=false;
    $vincolo='';
    foreach($json_b->features as $zona) {
        $zo = \geoPHP::load($zona,'json');
            if($zo->contains($rpc)) {
                $vincolo = 'Vincolo Paesaggistico: zona '. $zona->properties->zonizzazio . ' - ' . $zona->properties->descr_eses;
                //echo 'Trovata : =>' . $zona->properties->Z; 
                $trovata=true;
                break;    
            }
    
    }
 
    
    
//    if ($trovata=false) {
//        $destinazione='false';
//    } else {
//        $destinazione='true';
//    }
     
    // se la particella non è interna allora interseca un contorno di zona
    if ($trovata==false) {
        $vincolo='';
        foreach($json_b->features as $zona) {
        $zo = \geoPHP::load($zona,'json');
        //print_r($zona);
        if ($zo->intersects($rpc)) {
            $in = $zo->intersection($rpc);       
                //print_r($in);
                if (!$in==null) {
//                    echo 'Particella Catastale:';;
//                    echo PHP_EOL;
//                    print_r($rpc);
//                    echo PHP_EOL;
//                    echo '************zona PRG********************';
//                    echo PHP_EOL;
//                    print_r($zona);
//                    echo PHP_EOL;
//                    echo '**************Intersezione**************';
//                    echo PHP_EOL;
//                    print_r($in);
//                    echo PHP_EOL;
//                    echo '<script language="javascript">';
//                    echo 'alert(' . print_r($in) . ')';
//                    echo '</script>';
                //ho trovato una intersezione con una zona del PRG
                $area = round($in->getArea(),0);
                if ($area>5) {
                $vincolo = $vincolo . 'Vincolo Paesaggistico:'. $zona->properties->zonizzazio . ' - ' . $zona->properties->descr_eses . ' per mq ' . (string)$area . '      ';
                };
                //echo 'Trovata : =>' . $zona->properties->Z; 
                $trovata=true;
    
               // break;    
                }
        }
        }
    }
    
     
     //****************************************
    // ricerca dei vincoli ****
    // 1 - VINCOLO IDROGEOLOGICO
    // ***************************************
    $idrof = file_get_contents("mappe/b542/idrogeo_epsg7792.geojson");
    $json_b = json_decode($idrof);

    // interrogo i vari layer per verificare la destinazione urbanistica
    // verifico se la particella è contenuta interamente nella zona
    //$destinazione='non trovata!';
    $trovata=false;
    $vincolo2='';
    foreach($json_b->features as $zona) {
        $zo = \geoPHP::load($zona,'json');
            if($zo->contains($rpc)) {
                $vincolo2 = 'Vincolo Idrogeologico ';
                //echo 'Trovata : =>' . $zona->properties->Z; 
                $trovata=true;
                break;    
            }
    
    }
     
//    if ($trovata=false) {
//        $destinazione='false';
//    } else {
//        $destinazione='true';
//    }
     
    // se la particella non è interna allora interseca un contorno di zona
    if ($trovata==false) {
        $vincolo2='';
        foreach($json_b->features as $zona) {
        $zo = \geoPHP::load($zona,'json');
        //print_r($zona);
        if ($zo->intersects($rpc)) {
            $in = $zo->intersection($rpc);       
                //print_r($in);
                if (!$in==null) {
//                    echo 'Particella Catastale:';;
//                    echo PHP_EOL;
//                    print_r($rpc);
//                    echo PHP_EOL;
//                    echo '************zona PRG********************';
//                    echo PHP_EOL;
//                    print_r($zona);
//                    echo PHP_EOL;
//                    echo '**************Intersezione**************';
//                    echo PHP_EOL;
//                    print_r($in);
//                    echo PHP_EOL;
//                    echo '<script language="javascript">';
//                    echo 'alert(' . print_r($in) . ')';
//                    echo '</script>';
                //ho trovato una intersezione con una zona del PRG
                $area = round($in->getArea(),0);
                if ($area>5) {
                $vincolo2 = $vincolo2 . 'Vincolo Idrogeologico per mq ' . (string)$area . '     ';
                };
                //echo 'Trovata : =>' . $zona->properties->Z; 
                $trovata=true;
    
               // break;    
                }
        }
        }
    }
   
    return $vincolo . $vincolo2;
    }
    
    
    
    
    /**
     * Genera il documento Certificato di Destinazione Urbanistica con i dati all'interno.
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
   public function actionCompilacdu($modello_id, $idcdu)
    {
       // usa la libreria geoPHP e sue dipendenze
      require_once \Yii::$app->basePath.'/vendor/phayes/geophp/geoPHP.inc';
      \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(false);
      
      // trova i dati della richiesta CDU
      $model = Cdu::findOne($idcdu);
      $modello= \app\models\Modulistica::findOne($modello_id);
      $filemodello=$modello->path . $modello->nomefile;   
           
      //if ( ! class_exists('geoPHP')) die('Esiste!');
     //  var_dump(file_exists('/var/www/ufficiotecnico/vendor/phayes/geophp'.'/geoPHP.inc'));
       
       // $catastale = \geoPHP::load($catasto, 'json');
//        $multipoint_points = $multipoint->getComponents();
//        $first_wkt = $multipoint_points[0]->out('wkt');
       
        date_default_timezone_set('UTC');
        error_reporting(E_ALL);
        define('CLI', (PHP_SAPI == 'cli') ? true : false);
        //$model = Edilizia::findOne($id);
        
//        //$dompdfPath = $vendorDirPath . '/dompdf/dompdf';
        $dompdfPath = '@vendor/dompdf/dompdf';
        if (file_exists($dompdfPath)) {
        define('DOMPDF_ENABLE_AUTOLOAD', false);
        Settings::setPdfRenderer(Settings::PDF_RENDERER_DOMPDF, '@vendor/dompdf/dompdf');
        }
//
        Settings::loadConfig();
//        // Set writers
        $writers = array('Word2007' => 'docx', 'ODText' => 'odt', 'RTF' => 'rtf', 'HTML' => 'html' );
        // Set PDF renderer
        if (null === Settings::getPdfRendererPath()) {
        $writers['PDF'] = null;
        }
//
//        // Turn output escaping on
        Settings::setOutputEscapingEnabled(true);
//
//        // Return to the caller script when runs by CLI
        if (CLI) {
        return;
        }
        
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($filemodello);
//        if ($model->tipodestinatario==0) {
//            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('/var/www/ufficiotecnico/web/modelli/Mod_UR_01_Certificato Destinazione Urbanistica_Privati_Rev01.docx');
//        } else {
//            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('/var/www/ufficiotecnico/web/modelli/Mod_UR_02_Certificato Destinazione Urbanistica_Enti_Rev01.docx');
//        }

//      $richiesta = [['foglio'=>2,'particella'=>130],['foglio'=>2,'particella'=>131],['foglio'=>2,'particella'=>132]];
//        $f = '11';
//        $p = '123';
      //$catasto = file_get_contents("mappe/b542/catastale.json");
      // dalla richiesta trovo elenco delle particelle
     // tolgo tutti gli stazi 
      $a = str_replace(' ', '', $model->particelle1);
      // ricavo le particelle
      $particelle1 = explode(",", $a);  
      $foglio1 = trim($model->foglio1);
      $numparticelle1=count($particelle1);
      //$numparticelle1=count($particelle1);
      if (trim($model->particelle2)<>'') {
      $b = str_replace(' ', '', $model->particelle2);
      $particelle2 = explode(",", $b);  
      $foglio2 = trim($model->foglio2);
      $numparticelle2=count($particelle2);
      } else {
          $numparticelle2=0;
      }
      if (trim($model->particelle3)<>'') {
      $c = str_replace(' ', '', $model->particelle3);
      $particelle3 = explode(",", $c);  
      $foglio3 = trim($model->foglio3);
      $numparticelle3=count($particelle3);
      } else {
          $numparticelle3=0;
      }
      if (trim($model->particelle4)<>'') {
      $d = str_replace(' ', '', $model->particelle4);
      $particelle4 = explode(",", $d);  
      $foglio4 = trim($model->foglio4);
      $numparticelle4=count($particelle4);
      } else {
          $numparticelle4=0;
      }

      $totale=$numparticelle1+$numparticelle2+$numparticelle3+$numparticelle4;
      $templateProcessor->cloneRow('foglio', $totale);  
    // primo blocco particelle
      $nmr=1;
      $zone=array();
    foreach ($particelle1 as $particella)        
    {    
        // ****** DA FARE ***************
        // cerco la destinazione urbanistica della particella
        $des=$this->Urbanistica('',$foglio1,$particella);
        
        $templateProcessor->setValue('foglio#'. trim($nmr),$foglio1);
        $templateProcessor->setValue('particella#'. trim($nmr),$particella);
        $templateProcessor->setValue('zona#'. trim($nmr),$des);
        // cerco i vincoli
        $templateProcessor->setValue('vincoli#'. trim($nmr),$this->Vincoli('',$foglio1,$particella));
     $nmr=$nmr+1;
    
    } 
     
    if  ($numparticelle2>0) {
     foreach ($particelle2 as $particella)        
    {    
        // ****** DA FARE ***************
        $des=$this->Urbanistica('',$foglio2,$particella);
        $templateProcessor->setValue('foglio#'. trim($nmr),$foglio2);
        $templateProcessor->setValue('particella#'. trim($nmr),$particella);
        $templateProcessor->setValue('zona#'. trim($nmr),$des);
        $templateProcessor->setValue('vincoli#'. trim($nmr),$this->Vincoli('',$foglio2,$particella));
     $nmr=$nmr+1;
    
    }
    }
    
    if ($numparticelle3>0) {
      foreach ($particelle3 as $particella)        
        {    
        // ****** DA FARE ***************
        $des=$this->Urbanistica('',$foglio3,$particella);
        $templateProcessor->setValue('foglio#'. trim($nmr),$foglio3);
        $templateProcessor->setValue('particella#'. trim($nmr),$particella);
        $templateProcessor->setValue('zona#'. trim($nmr),$des);
        $templateProcessor->setValue('vincoli#'. trim($nmr),$this->Vincoli('',$foglio3,$particella));
        $nmr=$nmr+1;
    
        } 
    }
     
    if ($numparticelle4>0) {
        foreach ($particelle4 as $particella)        
        {    
            $des=$this->Urbanistica('',$foglio4,$particella);
            $templateProcessor->setValue('foglio#'. trim($nmr),$foglio4);
            $templateProcessor->setValue('particella#'. trim($nmr),$particella);
            $templateProcessor->setValue('zona#'. trim($nmr),$des);
            $templateProcessor->setValue('vincoli#'. trim($nmr),$this->Vincoli('',$foglio4,$particella));
         $nmr=$nmr+1;

        } 
    }
    
    
// Compila il certificato
// Richiedente
if ($model->richiedente->RegimeGiuridico_id<>1) {
    // trattasi di un soggetto giuridico
    //$templateProcessor->deleteBlock('Privato');
    //nato/a a ${LuogoNascita} il ${DataNascita} e residente a ${LuogoResidenza} alla Via ${IndirizzoResidenza} n. ${NumeroCivico}
    $templateProcessor->setValue('Richiedente',$model->richiedente->Denominazione);
    $templateProcessor->setValue('ComuneSede',$model->richiedente->ComuneResidenza);
    $templateProcessor->setValue('IndirizzoSede',$model->richiedente->IndirizzoResidenza);
    $templateProcessor->setValue('CapSede',$model->richiedente->CapResidenza);
    $templateProcessor->setValue('ProvinciaSede',$model->richiedente->ProvinciaResidenza);
    $templateProcessor->setValue('NumeroCivicoSede',$model->richiedente->NumeroCivicoResidenza);
    
} else {
    // trattasi di un Privato
    //$templateProcessor->deleteBlock('Societa');
    //$testo = $model->richiedente->Cognome . ' ' . $model->richiedente->Nome . ' nato a '. $model->richiedente->ComuneNascita;
    //$testo =$testo . ' il ' . Yii::$app->formatter->asDate($model->richiedente->DataNascita,'php:d-m-Y') . ' e residente a ' . $model->richiedente->ComuneResidenza . ' alla Via ' . $model->richiedente->IndirizzoResidenza;
    //$templateProcessor->setValue('Richiedente',$testo);
    //$templateProcessor->cloneBlock('Societa', 0, true, true);
    $templateProcessor->setValue('Richiedente',$model->richiedente->Cognome . ' ' . $model->richiedente->Nome);
    $templateProcessor->setValue('LuogoNascita',$model->richiedente->ComuneNascita);
    $templateProcessor->setValue('DataNascita',Yii::$app->formatter->asDate($model->richiedente->DataNascita,'php:d-m-Y'));
    $templateProcessor->setValue('LuogoResidenza',$model->richiedente->ComuneResidenza);
    $templateProcessor->setValue('IndirizzoResidenza',$model->richiedente->IndirizzoResidenza);
    $templateProcessor->setValue('NumeroCivico',$model->richiedente->NumeroCivicoResidenza);
    
}  

 // Richiesta
 $templateProcessor->setValue('ProtocolloRichiesta',$model->NumeroProtocollo);
 $templateProcessor->setValue('ProtocolloData',Yii::$app->formatter->asDate($model->DataProtocollo,'php:d-m-Y'));
 
 //

$templateProcessor->setValue('DataCertificato',date('d/m/Y'));
$templateProcessor->cloneBlock('norme', count($zone), true, true);
//print_r($zone);
//$zn=1;
//foreach ($zone as $zona)
//switch ($zona) {
//    case 'A':
//        $templateProcessor->setImageValue('estrattonorme#'. trim($zn), array('path' => '/var/www/ufficiotecnico/web/modelli/A.png'));
//        break;
//    case 'B':
//        $templateProcessor->setImageValue('estrattonorme#'. trim($zn), array('path' => '/var/www/ufficiotecnico/web/modelli/B_Parte1.png'));
//        $templateProcessor->setImageValue('secestrattonorme#'. trim($zn), array('path' => '/var/www/ufficiotecnico/web/modelli/B_Parte2.png'));
//        $templateProcessor->setValue('terestrattonorme#'. trim($zn),'');
//        $templateProcessor->setValue('quatestrattonorme#'. trim($zn),'');
//        $zn=$zn+1;
//        break;
//    case 'B1':
//        $templateProcessor->setImageValue('estrattonorme#'. trim($zn), array('path' => '/var/www/ufficiotecnico/web/modelli/B1.png'));
//        $zn=$zn+1;
//        break;
//    case 'C1':
//    case 'C2':
//    case 'C3':
//    case 'C4':
//    case 'C5':
//    case 'C6':
//    case 'C7':
//    case 'C8':
//    case 'C9':
//    case 'C10':
//    case 'C11':
//    case 'C12':
//        $templateProcessor->setImageValue('estrattonorme#'. trim($zn), array('path' => '/var/www/ufficiotecnico/web/modelli/C_Parte1.png','width' => '100%'));
//        $templateProcessor->setImageValue('secestrattonorme#'. trim($zn), array('path' => '/var/www/ufficiotecnico/web/modelli/C_Parte2.png','width' => '100%',));
//        $templateProcessor->setValue('terestrattonorme#'. trim($zn),'');
//        $templateProcessor->setValue('quatestrattonorme#'. trim($zn),'');
//        $zn=$zn+1;
//        break;
//    case 'C1I':
//    case 'C2I':
//    case 'C3I':
//        $templateProcessor->setImageValue('estrattonorme#'. trim($zn), array('path' => '/var/www/ufficiotecnico/web/modelli/CI.png'));
//        $templateProcessor->setImageValue('secestrattonorme#'. trim($zn), array('path' => '/var/www/ufficiotecnico/web/modelli/CI_Parte2.png'));
//        $zn=$zn+1;
//        break;
//    case 'C4I':
//        $templateProcessor->setImageValue('estrattonorme#'. trim($zn), array('path' => '/var/www/ufficiotecnico/web/modelli/C4I.png'));
//        $zn=$zn+1;
//        break;
//    case 'D':
//        $templateProcessor->setImageValue('estrattonorme#'. trim($zn), array('path' => '/var/www/ufficiotecnico/web/modelli/D_Parte1.png'));
//        $templateProcessor->setImageValue('secestrattonorme#'. trim($zn), array('path' => '/var/www/ufficiotecnico/web/modelli/D_Parte2.png'));
//        $zn=$zn+1;
//        break;
//    case 'P':
//        $templateProcessor->setImageValue('estrattonorme#'. trim($zn), array('path' => '/var/www/ufficiotecnico/web/modelli/Parcheggi_Parte1.png'));
//        $templateProcessor->setImageValue('secestrattonorme#'. trim($zn), array('path' => '/var/www/ufficiotecnico/web/modelli/Parcheggi_Parte2.png'));
//        $zn=$zn+1;
//        break;
//    case 'ASILO - SCUOLA':
//        $templateProcessor->setImageValue('estrattonorme#'. trim($zn), array('path' => '/var/www/ufficiotecnico/web/modelli/Istruzione.png'));
//        $zn=$zn+1;
//        break;
//    case 'Ct':
//        $templateProcessor->setImageValue('estrattonorme#'. trim($zn), array('path' => '/var/www/ufficiotecnico/web/modelli/Ct.png'));
//        $templateProcessor->setImageValue('secestrattonorme#'. trim($zn), array('path' => '/var/www/ufficiotecnico/web/modelli/Ct_Parte2.png'));
//        $zn=$zn+1;
//        break;
//    case 'H':
//        $templateProcessor->setImageValue('estrattonorme#'. trim($zn), array('path' => '/var/www/ufficiotecnico/web/modelli/H.png'));
//        $zn=$zn+1;
//        break;
//    case 'AR':
//    case 'CIMITERO':
//    case 'CIMITERO - FASCIA RISPETTO':
//        $templateProcessor->setImageValue('estrattonorme#'. trim($zn), array('path' => '/var/www/ufficiotecnico/web/modelli/Urbanizzazione.png'));
//        $zn=$zn+1;
//        break;
//    case 'CAMPING':
//        $templateProcessor->setImageValue('estrattonorme#'. trim($zn), array('path' => '/var/www/ufficiotecnico/web/modelli/Ufficio_Postale.png'));
//        $zn=$zn+1;
//        break;
//    case 'VERDE PUBBLICO':
//        $templateProcessor->setImageValue('estrattonorme#'. trim($zn), array('path' => '/var/www/ufficiotecnico/web/modelli/Verde_Pubblico.png'));
//        $zn=$zn+1;
//        break;
//}
    




//    $templateProcessor->cloneRow('ratanumero', $numrate);
//    $nmr=1;
//    foreach ($oneri as $rata) {
//        $templateProcessor->setValue('ratanumero#'. trim($nmr), $rata->ratanumero);
//        $templateProcessor->setValue('importorata#'.trim($nmr), $rata->importodovutorata);
//        $templateProcessor->setValue('scadenza#'.trim($nmr), date('d-m-Y',strtotime($rata->datascadenza)));
//        $nmr=$nmr+1;
//    }



//$results = array_filter($arr['people'], function($people) {
//  return $people['id'] == 8097;
//});



//
//        // Create the Object.
//        //$zip = new ZipArchive();
//
//        // Use same filename for "save" and different filename for "save as".
//        //$filename = 'edilizia\modelli\Mod_ED_04_Permesso_Costruire_Standard_Rev01.docx';
//        //$outputFilename = 'edilizia\pratiche\P' . $model->id . '\PermessoCostruire.docx';
//        
//            if ($tipo->commissione->Tipo==1) {
//                // trattasi di commissione edilizia
//                $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('/var/www/ufficiotecnico/web/modelli/Mod_CM_01_Verbale_Commissione_Edilizia_REV01.docx');
//            } else {
//                 //if ($seduta->commissione->Tipo=2)
//                // trattasi di commissione del paesaggio
//                $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('/var/www/ufficiotecnico/web/modelli/Mod_CM_02_Verbale_Commissione_Paesaggio_REV01.docx');
//            }
//
//            $templateProcessor->setValue('Numero',$seduta->numero );
//            //$templateProcessor->setValue('Numero','*'.Yii::getAlias('@app').'*');
//            //echo Yii::getAlias('@foo');   
//            $templateProcessor->setValue('dataseduta',Yii::$app->formatter->asDate($seduta->dataseduta,'php:d-m-Y'));
//            
//            // Dati istanza
//            $templateProcessor->setValue('anno',Yii::$app->formatter->asDate($seduta->dataseduta,'php:Y'));
//            $templateProcessor->setValue('mese',Yii::$app->formatter->asDate($seduta->dataseduta,'php:F'));
//            $templateProcessor->setValue('giorno',Yii::$app->formatter->asDate($seduta->dataseduta,'php:d'));
//            
//            //foreach ($componenti as $componente) {
//                $templateProcessor->cloneBlock('Commissione', $numcmp, true, true);
//            //}
//                $nm=1;
//                 foreach ($componenti as $componente) {
//                     $templateProcessor->setValue('titolo#'.$nm,$componente->titolo->abbr_titolo);
//                     $templateProcessor->setValue('Cognome#'.$nm,$componente->componenti->Cognome);
//                     $templateProcessor->setValue('Nome#'.$nm,$componente->componenti->Nome);
//                     $exp=$nm-1;
//                     
//                     if (pow(2,$exp) & $seduta->presenze) {
//                         $templateProcessor->setValue('presenza#'.$nm,'Presente');
//                     } else {
//                         $templateProcessor->setValue('presenza#'.$nm,'Assente');
//                     };
//                     $nm=$nm+1;
//                 }
//            $templateProcessor->setValue('orainizio',Yii::$app->formatter->asTime($seduta->orarioinizio,'php:H:i'));
//
//
//            $templateProcessor->cloneBlock('Pratica', $numpratiche, true, true);
//            //}
//                $nm=1;
//                 foreach ($pratiche as $pratica) {
//                     $templateProcessor->setValue('NumeroProtocollo#'.$nm,$pratica->praticaByDataASC->NumeroProtocollo);
//                     $templateProcessor->setValue('DataProtocollo#'.$nm,Yii::$app->formatter->asDate($pratica->praticaByDataASC->DataProtocollo,'php:d-m-Y'));
//                     $templateProcessor->setValue('Richiedente#'.$nm,$pratica->praticaByDataASC->richiedente->nomeCompleto);
//                     $templateProcessor->setValue('DescrizioneIntervento#'.$nm,$pratica->praticaByDataASC->DescrizioneIntervento);
//                     $templateProcessor->setValue('parere#'.$nm,$pratica->testoparere);
//                     $nm=$nm+1;
//                 }
//            $templateProcessor->setValue('orainizio',Yii::$app->formatter->asTime($seduta->orarioinizio,'php:H:i'));
//
//
//
//
//
//
//
//            
    // Crea la directory cdu se non esiste
//            $path=Yii::getAlias('@commissioni') . '/' . trim(sprintf('%09u', $idseduta)) . '/'; 
//             //FileHelper::createDirectory($path, $mode = 0775, $recursive = true); 
//            if (!file_exists($path)) {
//                FileHelper::createDirectory($path, $mode = 0777, $recursive = true); 
//            }
           //$path = Yii::getAlias('@web') . '/pratiche/P' . trim(sprintf('%09u', $id));
////            $realpath = realpath($path);
////            // If it exist, check if it's a directory
////            if (!($realpath !== false AND is_dir($realpath))) 
////            {
////            FileHelper::createDirectory($path);
////            }

$file=Yii::getAlias('@cdu') . '/Certificato_Destinazione_Urbanistica' . '_del_' . Yii::$app->formatter->asDate(date('d-m-Y'),'php:d-m-Y').'_' . uniqid('') . '.docx';
$templateProcessor->saveAs($file);
//
//            //echo getEndingNotes(array('Word2007' => 'docx'), 'Sample_07_TemplateCloneRow.docx');
////            if (!CLI) {
////                include_once 'Sample_Footer.php';
////            }
//            // scarica il file generato
            if (file_exists($file)) {
               Yii::$app->response->sendFile($file);
            } 
//        
//        
//        
//        
//        //return $this->redirect(['index']);


    }
 

    function debug_to_console($data) {
        echo "<script>console.log('" . json_encode($data) . "');</script>";
    }



}