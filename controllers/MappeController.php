<?php

namespace app\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use app\models\Mappe;
use app\models\Particella;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use SQLite3;
use yii\web\Response;
use yii\web\UploadedFile;
use app\models\DatiCensuari;
use app\models\DatiMappe;
use ZipArchive;
//use app\models\UploadDatiCatastaliForm;
//use kartik\mpdf\Pdf;
//use Mpdf\Mpdf;

/**
 * CommittentiController implements the CRUD actions for Committenti model.
 */
class MappeController extends Controller
{
    /**
     * {@inheritdoc}
     */


    /**
     * Lists all Committenti models.
     * @return mixed
     */
      
    public function actionIndex() {
//        $dataProvider = new ActiveDataProvider([
//            'query' => Mappe::find(),
//            'pagination' => false   // ensure all towers will appear on map
//        ]);
        $this->layout='main-map';
        $particella = new Particella();
        $ultimaMappa = DatiMappe::find()
        ->orderBy(['dataMappe' => SORT_DESC])
        ->one();     
        $cartellaMappe = $ultimaMappa ?  $ultimaMappa->folder_path:'mappe/b542/V2025-09-22';
        $model=\app\models\Edilizia::find()
                ->select(['NumeroProtocollo','DataProtocollo','NumeroTitolo','DataTitolo','DescrizioneIntervento','id_titolo','committenti.Cognome',
                          'committenti.Nome','committenti.Denominazione','Latitudine','Longitudine','RegimeGiuridico_id'])
                ->innerJoin('committenti', 'edilizia.id_committente=committenti_id')
                ->andWhere(['IS NOT','Latitudine', null])
                ->andWhere(['IS NOT', 'Longitudine',null])
                ->andWhere(['Stato_Pratica_id'=>5])
                ->asArray()
                ->all();
         //$num =\app\models\Edilizia::find()
         //       ->count();
        $num = \app\models\Edilizia::find()
                ->select(['NumeroTitolo','DataTitolo','DescrizioneIntervento','id_titolo','committenti.Cognome',
                          'committenti.Nome','committenti.Denominazione','Latitudine','Longitudine','RegimeGiuridico_id'])
                ->innerJoin('committenti', 'edilizia.id_committente=committenti_id')
                ->andWhere(['IS NOT','Latitudine', null])
                ->andWhere(['IS NOT', 'Longitudine',null])
                ->andWhere(['Stato_Pratica_id'=>5])
                ->asArray()
                ->count();

         
         
        $gisProgetti = \app\models\GisProgetto::find()
            ->with(['layers'])
            ->orderBy(['nome' => SORT_ASC])
            ->all();

        return $this->render('mappe', [
            'pratiche' => $model,
            'numero'=>$num,
            'particella'=>$particella,
            'cartellaMappe'=>$cartellaMappe,
            'gisProgetti'=>$gisProgetti
            
            
        ]);
         
         
    }
    
    
    public function actionProxy($lon, $lat)
    {
        $url = "https://wms.cartografia.agenziaentrate.gov.it/inspire/ajax/ajax.php"
             . "?op=getDatiOggetto&lon={$lon}&lat={$lat}";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // se dà problemi con https
        $response = curl_exec($ch);
        curl_close($ch);

        Yii::$app->response->format = Response::FORMAT_JSON;

        // Se il servizio non ritorna JSON ma testo, provo a decodificarlo
        $decoded = json_decode($response, true);
        if ($decoded === null) {
            // ritorna come testo
            return ['raw' => $response];
        }

        return $decoded;
    }

    /**
     * Proxy verso MinosX: autentica, seleziona il database e restituisce
     * i layer (lamps / cabinets) come GeoJSON con coordinate WGS84.
     *
     * GET /mappe/minosx-lamps?type=lamps|cabinets
     */
    public function actionMinosxLamps()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $p       = Yii::$app->params;
        $baseUrl = rtrim($p['minoxUrl'], '/');
        $type    = Yii::$app->request->get('type', 'lamps');
        $mapFn   = ($type === 'cabinets') ? 'read_cabinets' : 'read_lamps';

        // Bounding box generosa che copre l'intero comune
        $minx = 1600000; $miny = 4990000;
        $maxx = 1700000; $maxy = 5080000;

        // --- 1. Login ---
        $cookieJar = tempnam(sys_get_temp_dir(), 'minosx_');
        $ch = curl_init($baseUrl . '/index.php');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_COOKIEJAR      => $cookieJar,
            CURLOPT_COOKIEFILE     => $cookieJar,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query([
                'username'      => $p['minoxUser'],
                'user_password' => $p['minoxPassword'],
                'access'        => 'Enter',
            ]),
        ]);
        curl_exec($ch);
        curl_close($ch);

        // --- 2. Seleziona database ---
        $ch = curl_init($baseUrl . '/select_obj.php');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_COOKIEJAR      => $cookieJar,
            CURLOPT_COOKIEFILE     => $cookieJar,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query(['select_db' => $p['minoxDb']]),
        ]);
        curl_exec($ch);
        curl_close($ch);

        // Helper cURL riutilizzabile (cookieJar già aperto)
        $curlPost = function($url, $fields) use ($cookieJar) {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_COOKIEJAR      => $cookieJar,
                CURLOPT_COOKIEFILE     => $cookieJar,
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => http_build_query($fields),
            ]);
            $r = curl_exec($ch);
            curl_close($ch);
            return $r;
        };

        // --- 3. Leggi layer ---
        $raw = $curlPost($baseUrl . '/function_map.php', [
            'map_function' => $mapFn,
            'minx'         => $minx,
            'miny'         => $miny,
            'maxx'         => $maxx,
            'maxy'         => $maxy,
        ]);

        $data = json_decode($raw, true);
        if (!$data || !isset($data['features'])) {
            @unlink($cookieJar);
            Yii::$app->response->statusCode = 502;
            return ['error' => 'Risposta non valida da MinosX'];
        }

        // --- 4. Mappa lamp_id → nome quadro (solo per le lampade) ---
        $lampCabinet = [];
        if ($type !== 'cabinets') {
            $cabRaw = $curlPost($baseUrl . '/function_map.php', [
                'map_function' => 'read_cabinets',
                'minx' => $minx, 'miny' => $miny,
                'maxx' => $maxx, 'maxy' => $maxy,
            ]);
            $cabData = json_decode($cabRaw, true);
            if ($cabData && isset($cabData['features'])) {
                foreach ($cabData['features'] as $cab) {
                    $cabId   = $cab['properties']['id'];
                    $cabName = $cab['properties']['name'];   // es. "Q01"
                    // Ottieni figli tramite jqueryFileTree
                    $html = $curlPost($baseUrl . '/jqueryFileTree.php', ['dir' => $cabId]);
                    // Estrai gli id lampade dagli attributi rel="<id>"
                    if (preg_match_all('/rel="([a-f0-9]{32})"/', $html, $m)) {
                        foreach ($m[1] as $lampId) {
                            $lampCabinet[$lampId] = $cabName;
                        }
                    }
                }
            }
        }

        @unlink($cookieJar);

        // --- 5. Converti coordinate EPSG:3857 → WGS84 e aggiungi campo quadro ---
        foreach ($data['features'] as &$feature) {
            if ($feature['geometry']['type'] === 'Point') {
                [$x, $y] = $feature['geometry']['coordinates'];
                $lon = $x / 20037508.342789244 * 180.0;
                $lat = rad2deg(2.0 * atan(exp($y / 6378137.0)) - M_PI / 2.0);
                $feature['geometry']['coordinates'] = [
                    round($lon, 7),
                    round($lat, 7),
                ];
            }
            if ($type !== 'cabinets') {
                $id = $feature['properties']['id'] ?? '';
                $feature['properties']['QUADRO'] = $lampCabinet[$id] ?? '';
            }
        }
        unset($feature);

        return $data;
    }

        public function actionDatiCensuari()
    {
        $model = new \app\models\UploadDatiCensuariForm();
        $this->layout='main';
        if (Yii::$app->request->isPost) {
            // var_dump($_POST);
            // var_dump($_FILES);
            // exit;
            if ($model->load(Yii::$app->request->post())) {
                    $model->fileCensuari = UploadedFile::getInstance($model, 'fileCensuari');
                    $model->dataCensuari = Yii::$app->request->post('UploadDatiCensuariForm')['dataCensuari'];
                    

                    if ($model->validate()) {
                        $uploadDir = Yii::getAlias('@webroot/mappe/b542/uploads/');

                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }

                        $dataFormattata = Yii::$app->formatter->asDate($model->dataCensuari, 'php:d-m-Y');
                        // Costruisci il nuovo nome del file
                        $fileName = 'catasto_' . $dataFormattata . '.db';
                        $relativePath = 'mappe/b542/uploads/' . $fileName; // 👈 path relativa
                        $pathCensuari = $uploadDir . $fileName;
                        //$pathCensuari = $uploadDir . $model->fileCensuari->baseName . '.' . $model->fileCensuari->extension;
                        if ($model->fileCensuari->saveAs($pathCensuari)) {
                            $dati = new DatiCensuari();
                            $dati->dataCensuari = Yii::$app->formatter->asDate($model->dataCensuari, 'php:Y-m-d');
                            $dati->file_path_database = $relativePath;
                            if ($dati->save()) {
                                Yii::$app->session->setFlash('success', 'Dati censuari caricati e registrati.');
                            } else {
                                Yii::$app->session->setFlash('error', 'Errore nel salvataggio su database.');
                            }  
                        // Qui puoi aggiornare il database con le due date
                       // Yii::info("Aggiornamento dati censuari al {$model->dataCensuari}", __METHOD__);

                       // Yii::$app->session->setFlash('success', 'Dati censuari caricati con successo.');
                        return $this->redirect(['mappe/index']);
                    }
            }
        }    
    }
    return $this->render('dati-censuari', [
        'model' => $model,
    ]);
    }
    
    public function actionAggiornaMappe()
    {
        $model = new \app\models\UploadMappeAggiornateForm();

    if (Yii::$app->request->isPost) {
        $model->fileMappe = UploadedFile::getInstance($model, 'fileMappe');
        $model->dataMappe = Yii::$app->request->post('UploadMappeAggiornateForm')['dataMappe'];


        if ($model->validate()) {
            $dateString = Yii::$app->formatter->asDate($model->dataMappe, 'php:Y-m-d');
            $relativePath = "mappe/b542/V{$dateString}";
            $uploadDir = Yii::getAlias('@webroot/'.$relativePath);
            
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            // scompattare i file nella cartella 
            
         
            // Salva lo zip temporaneo
            $zipPath = $uploadDir . '/upload.zip';
            $model->fileMappe->saveAs($zipPath);
            $zip = new ZipArchive();
            if ($zip->open($zipPath) === true) {
                $zip->extractTo($uploadDir);
                $zip->close();
                unlink($zipPath); // elimina lo zip dopo l’estrazione
            } else {
                Yii::$app->session->setFlash('error', 'Errore nell’apertura del file ZIP.');
                return $this->redirect(['mappe/index']);
            }
            // Salva record in DB
            $dati = new DatiMappe();
            $dati->dataMappe = $dateString;
            $dati->folder_path = $relativePath;
            if ($dati->save()) {
                Yii::$app->session->setFlash('success', 'Mappe aggiornate con successo.');
            } else {
                Yii::$app->session->setFlash('error', 'Errore nel salvataggio su database.');
            }

            return $this->redirect(['mappe/index']);

        }
    }

    return $this->render('aggiorna-mappe', [
        'model' => $model,
    ]);
    }
    
    
    /**
     * Displays a single Committenti model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Committenti model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
//        $model = new Mappe();
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->committenti_id]);
//        }
//
//        return $this->render('create', [
//            'model' => $model,
//        ]);
    }

    /**
     * Updates an existing Committenti model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->committenti_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Committenti model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Committenti model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Committenti the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mappe::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    
    
    
//    /**
//     * Prove Tecniche
//     * @param integer $id
//     * @return mixed
//     * @throws NotFoundHttpException if the model cannot be found
//     */
//    public function actionCercaparticella()
//{
//    $model = new Particella();
//    if (Yii::$app->request->isAjax) {
//        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//
//        if ($model->load(Yii::$app->request->post())) {
//            return [
//                'data' => [
//                    'success' => true,
//                    'model' => $model,
//                    'message' => 'Model has been saved.',
//                ],
//                'code' => 0,
//            ];
//        } else {
//            return [
//                'data' => [
//                    'success' => false,
//                    'model' => null,
//                    'message' => 'An error occured.',
//                ],
//                'code' => 1, // Some semantic codes that you know them for yourself
//            ];
//        }
//    }
//}
    

public function EsisteParticella($foglio,$particella)
{
    // carico il file json contenente la geometria particelle catastali
      // in nome del file da caricare in base al foglio
      $pathfile = 'mappe/'. \Yii::$app->params['Comune']. '/';
      $nomefile = 'foglio' . trim(sprintf('%02u', $foglio)) . '.json';
      //$zone=array();
      // leggo il contenuto del file
      $catasto = file_get_contents($pathfile . $nomefile);
      // converto la stringa in oggetto 
      $jsone =json_decode($catasto);
      
      foreach($jsone->features as $prc) {
          if (($prc->properties->FOGLIO == $foglio) and ($prc->properties->NUMERO == $particella)) {
              $rpc = \geoPHP::load($prc->geometry,'json');
              break;
          }
      }
     if (is_null($rpc)) {
         //non ho trovato la particella
        return false;
         // da fare **********************************
     } else {
         return true;
     }
      
      
}

/*
* Cerca una particella in base a foglio e particella forniti
* se trovata fa il zoom della mappa sulla particella 
*/
    
 public function actionCercaparticella($foglio, $particella)
{
    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    // qui metti la logica di ricerca
    include_once('../vendor/phayes/geophp/geoPHP.inc');
        if(!isset($_GET['foglio']) or empty($_GET['foglio']))
	        die( json_encode(array('ok'=>0, 'errmsg'=>'specifica parametro foglio') ) );
    if(!isset($_GET['particella']) or empty($_GET['particella']))
	    die( json_encode(array('ok'=>0, 'errmsg'=>'specifica parametro particella') ) );

// $foglio = $_GET['foglio'];
// $particella = $_GET['particella'];

        $pathfile = 'mappe/b542/';
        $nomefile = 'foglio' . trim(sprintf('%02u', $foglio)) . '.json';
        //$zone=array();
        // leggo il contenuto del file
        $catasto = file_get_contents($pathfile . $nomefile);
        // converto la stringa in oggetto 
        $jsone =json_decode($catasto);
         $rpc=null;
      // trovo la geometria della particella
        foreach($jsone->features as $prc) {
            if (($prc->properties->FOGLIO == $foglio) and ($prc->properties->NUMERO == $particella)) {
                $rpc = \geoPHP::load($prc->geometry,'json');
                //$rpc = $prc->geometry;
                break;
            }
        }
        if (is_null($rpc)) {
            //non ho trovato la particella
            //return 'Particella non trovata!';
            // da fare **********************************
           return ['ok'=>1, 'errmsg'=>'particella non trovata'];
        } else {
            $centro = $rpc->centroid();
           return ['ok'=>0, 'errmsg'=>'Nessun errore', 'Latitudine'=>$centro->gety(),'Longitudine'=>$centro->getx()];
        }
    
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
      $pathfile = 'mappe/'. \Yii::$app->params['Comune']. '/';
      $nomefile = 'foglio' . trim(sprintf('%02u', $foglio)) . '.json';
      //$zone=array();
      // leggo il contenuto del file
      $catasto = file_get_contents($pathfile . $nomefile);
      // converto la stringa in oggetto 
      $jsone =json_decode($catasto);

      $rpc=null;
      // trovo la geometria della particella
      foreach($jsone->features as $prc) {
          if (($prc->properties->FOGLIO == $foglio) and ($prc->properties->NUMERO == $particella)) {
              $rpc = \geoPHP::load($prc->geometry,'json');
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
    $prgf = file_get_contents($pathfile . "prgjson.json");
    $json_b = json_decode($prgf);

    // interrogo i vari layer per verificare la destinazione urbanistica
    // verifico se la particella è contenuta interamente nella zona
    $destinazione='non trovata!';
    $trovata=false;
    foreach($json_b->features as $zona) {
        $zo = \geoPHP::load($zona,'json');
       // $zo=$zo->buffer(0);
            if($zo->contains($rpc)) {
                $destinazione = $zona->properties->z . ' - ' . $zona->properties->estes;
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
                    $area = round($in->getArea()*100000.0*100000.0,0);
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
                    $destinazione ='<p>'. $destinazione . $des1[$i]['zona'] . ' per la restante superficie'.'</p>';
                    } else {
                    $destinazione ='<p>'. $destinazione . $des1[$i]['zona'] . ' per mq '. $des[$i]['area'].'</p>';
                    }
                } else {
                    $destinazione ='<p>'. $des1[$i]['zona'] .'</p>';
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
    $pathfile = 'mappe/'. \Yii::$app->params['Comune']. '/';
    $nomefile = 'foglio' . trim(sprintf('%02u', $foglio)) . '.json';
    $ptpf = file_get_contents($pathfile . "ptpjson.json");
    $json_b = json_decode($ptpf);

    
    // leggo il contenuto del file
      $catasto = file_get_contents($pathfile . $nomefile);
      // converto la stringa in oggetto 
      $jsone =json_decode($catasto);

      $rpc=null;
      // trovo la geometria della particella
      foreach($jsone->features as $prc) {
          if (($prc->properties->FOGLIO == $foglio) and ($prc->properties->NUMERO == $particella)) {
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
                $vincolo = '<p>' .'Vincolo Paesaggistico: zona '. $zona->properties->zonizzazio . ' - ' . $zona->properties->descr_eses . '</p>';
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
                $area = round($in->getArea()*100000.0*100000.0,0);
                if ($area>5) {
                $vincolo = $vincolo . '<p>' . 'Vincolo Paesaggistico:'. $zona->properties->zonizzazio . ' - ' . $zona->properties->descr_eses . ' per mq ' . (string)$area . '</p>';
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
    $idrof = file_get_contents("mappe/b542/vincoloidrog.json");
    $json_b = json_decode($idrof);

    // interrogo i vari layer per verificare la destinazione urbanistica
    // verifico se la particella è contenuta interamente nella zona
    //$destinazione='non trovata!';
    $trovata=false;
    $vincolo2='';
    foreach($json_b->features as $zona) {
        $zo = \geoPHP::load($zona,'json');
            if($zo->contains($rpc)) {
                $vincolo2 = '<p>' . 'Vincolo Idrogeologico ' . '</p>';
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
                $area = round($in->getArea()*100000.0*100000.0,0);
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
 * Genera un PDF con una tabella contenente il REPORT con elenco permessi costruire 
 * presenti in archivio divise per anno
 */
public function actionSchedaurbanistica($foglio,$particella) {

/*
 * Genera un PDF con la scheda urbanistica della particella specificata
 */


  
 include_once('../vendor/phayes/geophp/geoPHP.inc');
if(!isset($_GET['foglio']) or empty($_GET['foglio']))
	die( json_encode(array('ok'=>0, 'errmsg'=>'specifica parametro foglio') ) );
if(!isset($_GET['particella']) or empty($_GET['particella']))
	die( json_encode(array('ok'=>0, 'errmsg'=>'specifica parametro particella') ) );

$foglio = $_GET['foglio'];
$particella = $_GET['particella'];

$scheda = $this->Urbanistica('', $foglio, $particella);
$vinc   = $this->Vincoli('', $foglio, $particella);

// ── Intestatari dal catasto terreni ─────────────────────────────────────────
$intestatariHtml = '';
$ultimoDb = DatiCensuari::find()->orderBy(['dataCensuari' => SORT_DESC])->one();
if ($ultimoDb) {
    $dblite = new SQLite3($ultimoDb->file_path_database);
    $rowPart = $dblite->querySingle(
        "SELECT idParticella FROM PARTICELLA
         WHERE ltrim(foglio,'0')='" . SQLite3::escapeString($foglio) . "'
           AND ltrim(numero,'0')='" . SQLite3::escapeString($particella) . "'",
        true
    );
    if (!empty($rowPart['idParticella'])) {
        $idPart = $rowPart['idParticella'];
        $risTit = $dblite->query("
            SELECT
              CASE WHEN T.tipoSoggetto='P'
                   THEN PF.cognome||' '||PF.nome
                   ELSE PG.denominazione END AS denominazione,
              CASE WHEN T.tipoSoggetto='P' THEN PF.codFiscale
                   ELSE PG.codFiscale END AS cod_fisc,
              CD.decodifica||' '||T.titoloNonCod AS diritto,
              T.quotaNum||'/'||T.quotaDen AS quota
            FROM TITOLARITA T
            LEFT JOIN PERSONA_FISICA    PF ON PF.idSoggetto = T.idSoggetto
            LEFT JOIN PERSONA_GIURIDICA PG ON PG.idSoggetto = T.idSoggetto
            LEFT JOIN COD_DIRITTO       CD ON CD.codice = T.codDiritto
            WHERE T.idParticella='" . SQLite3::escapeString($idPart) . "'
            ORDER BY PF.cognome, PF.nome, PG.denominazione
        ");
        $righe = '';
        while ($t = $risTit->fetchArray(SQLITE3_ASSOC)) {
            $righe .= '<tr>'
                . '<td style="padding:2px 6px;border:1px solid #ccc;">' . htmlspecialchars($t['denominazione'] ?? '') . '</td>'
                . '<td style="padding:2px 6px;border:1px solid #ccc;">' . htmlspecialchars($t['cod_fisc'] ?? '') . '</td>'
                . '<td style="padding:2px 6px;border:1px solid #ccc;">' . htmlspecialchars($t['diritto'] ?? '') . '</td>'
                . '<td style="padding:2px 6px;border:1px solid #ccc;">' . htmlspecialchars($t['quota'] ?? '') . '</td>'
                . '</tr>';
        }
        if ($righe) {
            $intestatariHtml = '
<p><b>INTESTATARI:</b></p>
<table style="width:100%;border-collapse:collapse;font-size:0.85rem;">
  <thead>
    <tr style="background:#e8e8e8;">
      <th style="padding:2px 6px;border:1px solid #ccc;text-align:left;">Denominazione</th>
      <th style="padding:2px 6px;border:1px solid #ccc;text-align:left;">Cod. Fiscale</th>
      <th style="padding:2px 6px;border:1px solid #ccc;text-align:left;">Diritto</th>
      <th style="padding:2px 6px;border:1px solid #ccc;text-align:left;">Quota</th>
    </tr>
  </thead>
  <tbody>' . $righe . '</tbody>
</table>
<p></p>';
        }
    }
    $dblite->close();
}
// ────────────────────────────────────────────────────────────────────────────

    $content =
'<p><b><strong>SCHEDA URBANISTICA</strong></b></p>
<p></p>
<p>RIFERIMENTI CATASTALI:</p>
<p>Foglio: <b>' . $foglio . '</b> &nbsp; Particella: <b>' . $particella . '</b></p>
<p></p>'
. $intestatariHtml .
'<p><b>PIANO REGOLATORE GENERALE:</b></p>
<p>Destinazione Urbanistica:</p>' . $scheda . '
<p></p>
<p><b>VINCOLI:</b></p>
<p>' . $vinc . '</p>
<p></p>
<p style="font-size: 0.75rem">Note: La presente scheda è per uso interno e non costituisce certificato ufficiale di destinazione urbanistica</p>
<p></p>
<div><p></p></div>
<p>Campoli del Monte Taburno, li ' . date("d-m-Y") . '</p>
<p>&nbsp;</p>';       
//$this->renderPartial('_reportView');
    
    // setup kartik\mpdf\Pdf component
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
//    $pdf1 = new \Mpdf\Mpdf([
//        'filename'=>'Scheda_urbanistica_foglio_'. $foglio .'_particella_'. $particella .'_' . date("d-m-Y"),
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
//        'options' => ['title' => 'Scheda Urbanistica ' . date("d-m-Y")],
//         // call mPDF methods on the fly
//        'methods' => [ 
//            'SetHeader'=>['Comune di Campoli del Monte Taburno - Ufficio Tecnico'], 
//            'SetFooter'=>['{PAGENO}'],
//        ]
//    ]);
    //die( var_dump( Yii::app()->getRequest()->getIsSecureConnection() ) );
    // return the pdf output as per the destination setting
    $mpdf->SetHTMLHeader('<div align="center"><p>Comune di Campoli del Monte Taburno - Ufficio Tecnico</p></div>');
    

    //$mpdf->SetHTMLFooter($footer);
    
    $mpdf->WriteHTML($content);
    $mpdf->Output('schedaurbanistica.pdf', 'I');
    return; 
    
   //return $this->render('test', ['html' =>$content]);
  


}


public function actionCatasto()
// cerco il tipo di particella
// per vedere se è una particella del catasto terreni
// o una particella del catasto fabbricati 
// o è fuori del Comune

{

if (Yii::$app->request->isAjax) {
    $data = Yii::$app->request->post();
    $dblite = new SQLite3('mappe/b542/catasto.db');
    $foglio= $data['foglio'];
    $particella= $data['particella'];
    set_time_limit(30);

    $query_particella="
    select 
    ltrim(pa.foglio,'0') as fglo,
    ltrim(pa.numero,'0') as particella,
    codComune
    From  PARTICELLA as pa
    where  
    fglo = '" . $foglio . "' AND particella='" .$particella. "'
    ";
    $row = $dblite->querySingle($query_particella);
    
     if ($row) {
         // si tratta di particella del catasto terreni
        $ges=[];
        $ges['Catasto']="T";
        //$ges['Comune']=$row['codComune'];
        $ges['foglio'] = $foglio;
        $ges['particella'] = $particella;
        $JSONT = json_encode($ges);  
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $JSONT;
        }
    // se sto qua vuo dire che non ho trovato particelle del catasto terreni
    // cerco all'urbano
    $query_nceua="
    SELECT
    II.idImmobile,
    codComune,
    ltrim(II.foglio,'0') as fglo,
    ltrim(II.numero,'0') as particella
    FROM 
    IDENTIFICATIVI_IMMOBILIARI as II
    where fglo = '" . $foglio . "' AND particella='" . $particella . "'
    ";   
    
   
    
    
    
    $rowu = $dblite->querySingle($query_nceua);
    if (count($rowu)>0) {
         // si tratta di particella del catasto terreni
        $geu=[];
        $geu['Catasto']="F";
        $geu['Comune']=$row['codComune'];
        $geu['foglio'] = $foglio;
        $geu['particella'] = $particella;
        $JSONU = json_encode($geu);  
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $JSONU;
        }
    // se sto qui allora la particella non è ne urbana ne terreni
    $geb=[];
    $geb['Catasto']="0";
    //$geb['Comune']=$row['codComune'];
    $geb['foglio'] = $foglio;
    $geb['particella'] = $particella;
    $JSONB = json_encode($geb);  
    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    return $JSONB;    
        
        
    }
}


public function actionVisurafabbricati()

{

if (Yii::$app->request->isAjax) {
    $data = Yii::$app->request->post();
//    $fg= explode(":", $data['foglio']);
//    $searchby= explode(":", $data['searchby']);
//    $searchname= $searchname[0];
//    $searchby= $searchby[0];
//    $search = // your logic;
    $ultimoDatatabase = DatiCensuari::find()
        ->orderBy(['dataCensuari' => SORT_DESC])
        ->one();     
    
    $pathdb = $ultimoDatatabase ? $ultimoDatatabase->file_path_database : null;
    if (!($pathdb === null)) {
        $dblite = new SQLite3($pathdb);
    } else {
        $geb['esito'] = 'Database non trovato! Aggiornare dati censuari oppure contattare amministratore del sistema.';
        $JSONB = json_encode($geb);  
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $JSONB;   
    }    
    //$dblite = new SQLite3($pathdb);        
    //$dblite = new SQLite3('mappe/b542/catasto.db');
    
    
    $foglio= $data['foglio'];
    $particella= $data['particella'];
    
//    if(!isset($_GET['foglio']) or empty($_GET['foglio']))
//	die( json_encode(array('ok'=>0, 'errmsg'=>'specifica parametro foglio') ) );
//    if(!isset($_GET['particella']) or empty($_GET['particella']))
//	die( json_encode(array('ok'=>0, 'errmsg'=>'specifica parametro particella') ) );

    // recupera le variabile della particella foglio e particella
//    $foglio = $_POST['foglio'];
//    $particella = $_POST['particella'];
    // IMPOSTA LA QUERY per trovare le particelle ai terreni tramite idSoggetto $idsog
    set_time_limit(360);
    $query_nceua="
    SELECT
    II.idImmobile,
    ltrim(II.foglio,'0') as fglo,
    ltrim(II.numero,'0') as particella,
    II.subalterno,
    UI.categoria,
    UI.classe,
    UI.consistenza,
    UI.superficie,
    UI.renditaEuro,
    UI.piano1,
    UI.piano2
    FROM 
    IDENTIFICATIVI_IMMOBILIARI as II
    left join UNITA_IMMOBILIARI as UI on UI.idImmobile=II.idImmobile 
    where fglo = '" . $foglio . "' AND particella='" . $particella . "'
    ";    

$query_nc2=" 
   SELECT DISTINCT
    II.idImmobile,
    ltrim(II.foglio,'0') as fglo,
    ltrim(II.numero,'0') as particella,
    ltrim(II.subalterno,'0') as sub,
	categoria,
	classe,
	consistenza,
        superficie,
	renditaEuro,
	CTOP.decodifica,
	IND.indirizzo,
    ltrim(IND.civico1,'0') as civico1,    
        piano1,
        piano2
    FROM 
    IDENTIFICATIVI_IMMOBILIARI as II
	left join INDIRIZZI as IND on IND.idImmobile = II.idImmobile
	left join COD_TOPONIMO as CTOP on   CTOP.codice = IND.toponimo
	left join UNITA_IMMOBILIARI as UI on UI.idImmobile = II.idImmobile
    where fglo = '" . $foglio . "' AND particella='" . $particella . "' 
    GROUP BY
	II.idImmobile
    ";

$query_sog="
select  
TITOLARITA.idSoggetto as idSog,
TITOLARITA.idImmobile,
TITOLARITA.titoloNonCod as titplus,
TITOLARITA.tipoSoggetto as tipo, 
TITOLARITA.idSoggetto as idSogg,

CASE
WHEN TITOLARITA.tipoSoggetto='P' THEN PERSONA_FISICA.cognome||' '||PERSONA_FISICA.nome
WHEN TITOLARITA.tipoSoggetto='G' THEN PERSONA_GIURIDICA.denominazione
END as denominazione,
PERSONA_FISICA.cognome as cogn,
PERSONA_FISICA.nome as nom,
CASE
WHEN TITOLARITA.tipoSoggetto='P' THEN COD_COMUNE.decodifica
END as com_nasc,

CASE
WHEN TITOLARITA.tipoSoggetto='P' THEN substr(PERSONA_FISICA.dataNascita,9,2)||'/'||substr(PERSONA_FISICA.dataNascita,6,2)||'/'||substr(PERSONA_FISICA.dataNascita,1,4)
END as data_nasc,

CASE
WHEN TITOLARITA.tipoSoggetto='P' THEN PERSONA_FISICA.codFiscale
WHEN TITOLARITA.tipoSoggetto='G' THEN PERSONA_GIURIDICA.codFiscale
END as cod_fisc,
COD_DIRITTO.decodifica||' '||TITOLARITA.titoloNonCod as diritto,
TITOLARITA.quotaNum||'/'||TITOLARITA.quotaDen as quota
from
TITOLARITA,IDENTIFICATIVI_IMMOBILIARI, INDIRIZZI
left join UNITA_IMMOBILIARI as UI on UI.idImmobile = IDENTIFICATIVI_IMMOBILIARI.idImmobile
left join INDIRIZZI as IND on IND.idImmobile =IDENTIFICATIVI_IMMOBILIARI.idImmobile
left join PERSONA_FISICA ON PERSONA_FISICA.idSoggetto=TITOLARITA.idSoggetto
left join PERSONA_GIURIDICA ON PERSONA_GIURIDICA.idSoggetto=TITOLARITA.idSoggetto
left join COD_COMUNE ON PERSONA_FISICA.luogoNascita=COD_COMUNE.codice
left join COD_DIRITTO ON TITOLARITA.codDiritto=COD_DIRITTO.codice
WHERE
TITOLARITA.idImmobile='832632848' and IDENTIFICATIVI_IMMOBILIARI.idImmobile='832632848' 
GROUP By idSogg
";








    $rowu = $dblite->query($query_nc2);
    $arrsub=array();
    
    while ($rowi = $rowu->fetchArray()) {
        
        $query_sog="
            select  
            TITOLARITA.idSoggetto as idSog,
            TITOLARITA.idImmobile,
            TITOLARITA.titoloNonCod as titplus,
            TITOLARITA.tipoSoggetto as tipo, 
            TITOLARITA.idSoggetto as idSogg,

            CASE
            WHEN TITOLARITA.tipoSoggetto='P' THEN PERSONA_FISICA.cognome||' '||PERSONA_FISICA.nome
            WHEN TITOLARITA.tipoSoggetto='G' THEN PERSONA_GIURIDICA.denominazione
            END as denominazione,
            PERSONA_FISICA.cognome as cogn,
            PERSONA_FISICA.nome as nom,
            CASE
            WHEN TITOLARITA.tipoSoggetto='P' THEN COD_COMUNE.decodifica
            END as com_nasc,

            CASE
            WHEN TITOLARITA.tipoSoggetto='P' THEN substr(PERSONA_FISICA.dataNascita,9,2)||'/'||substr(PERSONA_FISICA.dataNascita,6,2)||'/'||substr(PERSONA_FISICA.dataNascita,1,4)
            END as data_nasc,

            CASE
            WHEN TITOLARITA.tipoSoggetto='P' THEN PERSONA_FISICA.codFiscale
            WHEN TITOLARITA.tipoSoggetto='G' THEN PERSONA_GIURIDICA.codFiscale
            END as cod_fisc,
            COD_DIRITTO.decodifica||' '||TITOLARITA.titoloNonCod as diritto,
            TITOLARITA.quotaNum||'/'||TITOLARITA.quotaDen as quota
            from
            TITOLARITA,IDENTIFICATIVI_IMMOBILIARI, INDIRIZZI
            left join UNITA_IMMOBILIARI as UI on UI.idImmobile = IDENTIFICATIVI_IMMOBILIARI.idImmobile
            left join INDIRIZZI as IND on IND.idImmobile =IDENTIFICATIVI_IMMOBILIARI.idImmobile
            left join PERSONA_FISICA ON PERSONA_FISICA.idSoggetto=TITOLARITA.idSoggetto
            left join PERSONA_GIURIDICA ON PERSONA_GIURIDICA.idSoggetto=TITOLARITA.idSoggetto
            left join COD_COMUNE ON PERSONA_FISICA.luogoNascita=COD_COMUNE.codice
            left join COD_DIRITTO ON TITOLARITA.codDiritto=COD_DIRITTO.codice
            WHERE
            TITOLARITA.idImmobile=" . $rowi['idImmobile'] . " and IDENTIFICATIVI_IMMOBILIARI.idImmobile=" . $rowi['idImmobile'] . " 
            GROUP By idSogg
            ";
        $rowsg = $dblite->query($query_sog);
        $arrsog=array();
        while ($rows = $rowsg->fetchArray()) {
            $sf = array(
            'denominazione'=> $rows['denominazione'],
            'comune_nascita'=> $rows['com_nasc'],
            'data_nascita'=> $rows['data_nasc'],
            'condice_fiscale'=> $rows['cod_fisc'],
            'diritto'=> $rows['diritto'],
            'quota'=> $rows['quota'],
            'tipo'=>$rows['tipo']
         );   
            array_push($arrsog, $sf);
        }
        
        $xf = array(
            'sub'=> $rowi['sub'],
            'categoria'=> $rowi['categoria'],
            'classe'=> $rowi['classe'],
            'consistenza'=> $rowi['consistenza'],
            'superficie'=> $rowi['superficie'],
            'piano1'=> $rowi['piano1'],
            'piano2'=> $rowi['piano2'],
            'rendita'=> $rowi['renditaEuro'],
            'indirizzo'=>$rowi['indirizzo'],
            'decodifica'=>$rowi['decodifica'],
            'civico1'=>$rowi['civico1'],
            'intestatari'=>$arrsog    
         );   
         array_push($arrsub, $xf);
        }
        $gen=[];
        $gen['Catasto']="F";
        $gen['foglio'] = $foglio;
        $gen['particella'] = $particella;
        $gen['esub']=$arrsub;
        $gen['esiste']="SI";
        $gen['esito']="OK";


            $JSONU = json_encode($gen);  
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return $JSONU;
    }
}
    

public function actionVisuraterreni()

{

if (Yii::$app->request->isAjax) {
    $data = Yii::$app->request->post();
//    $fg= explode(":", $data['foglio']);
//    $searchby= explode(":", $data['searchby']);
//    $searchname= $searchname[0];
//    $searchby= $searchby[0];
//    $search = // your logic;
    $ultimoDatatabase = DatiCensuari::find()
        ->orderBy(['dataCensuari' => SORT_DESC])
        ->one();     
    
    $pathdb = $ultimoDatatabase ? $ultimoDatatabase->file_path_database : null;
    
    if (!($pathdb === null)) {
        $dblite = new SQLite3($pathdb);
    } else {
        $geb=[];
        $geb['esito'] = 'Database non trovato! Aggiornare dati censuari oppure contattare amministratore del sistema.';
        $JSONB = json_encode($geb);  
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $JSONB;   
    }
    
    $foglio= $data['foglio'];
    $particella= $data['particella'];
    
//    if(!isset($_GET['foglio']) or empty($_GET['foglio']))
//	die( json_encode(array('ok'=>0, 'errmsg'=>'specifica parametro foglio') ) );
//    if(!isset($_GET['particella']) or empty($_GET['particella']))
//	die( json_encode(array('ok'=>0, 'errmsg'=>'specifica parametro particella') ) );

    // recupera le variabile della particella foglio e particella
//    $foglio = $_POST['foglio'];
//    $particella = $_POST['particella'];
    // IMPOSTA LA QUERY per trovare le particelle ai terreni tramite idSoggetto $idsog
    $query_c="
    select 
    ltrim(pa.foglio,'0') as fglo,
    ltrim(pa.numero,'0') as particella,
    pa.idParticella as idPart,

    CQ.decodifica as qualita,
    ltrim(CP.classe,'0') as cl,
    CP.ettari*10000+CP.are*100+CP.centiare as sup,
    CP.redditoDomEuro as RD,
    CP.redditoAgrEuro as RA
    From  PARTICELLA as pa

    left join CARATTERISTICHE_PARTICELLA as CP On CP.idParticella=idPart

    left join COD_QUALITA as CQ on CP.codQualita=CQ.codice
    where  
    fglo = '" . $foglio . "' AND particella='" .$particella. "'

    ORDER BY foglio ASC, particella ASC 
    ";
        
    set_time_limit(360);


    //esegue la query
    //$rs_c = $conn_c->query($query_c);
    //$result = $dblite->querySingle($query_c);
    $row = $dblite->querySingle($query_c,true);
    if (count($row)===0) {
        // non ho trovato nessun dato della particella
        // probabilmente è ente urbano
        // cerco tra le unità urbane
        $gen=[];
        $gen['Catasto']="T";
        $gen['foglio'] = $foglio;
        $gen['particella'] = $particella;
        $gen['esiste'] = "NO";
        $gen['esito'] = "OK";
        
        $JSONU = json_encode($gen);  
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $JSONU;
    }
    
    
    $idParticella = $row['idPart'];
    // ********* idParticella ?????????????????????????????
// RICERCA TITOLARI    
$query_tit="
SELECT
'PARTICELLA'.'idParticella',
'TITOLARITA'.'tipoSoggetto' as tipo,
'TITOLARITA'.'idSoggetto' as idSogg,
CASE
WHEN 'TITOLARITA'.'tipoSoggetto'='P' THEN 'PERSONA_FISICA'.'cognome'||' '||'PERSONA_FISICA'.'nome'
WHEN 'TITOLARITA'.'tipoSoggetto'='G' THEN 'PERSONA_GIURIDICA'.'denominazione'
END as 'denominazione',
CASE
WHEN 'TITOLARITA'.'tipoSoggetto'='P' THEN 'COD_COMUNE'.'decodifica'
END as com_nasc,
CASE
WHEN 'TITOLARITA'.'tipoSoggetto'='P' THEN substr('PERSONA_FISICA'.'dataNascita',9,2)||'/'||substr('PERSONA_FISICA'.'dataNascita',6,2)||'/'||substr('PERSONA_FISICA'.'dataNascita',1,4)
END as data_nasc,
CASE
WHEN 'TITOLARITA'.'tipoSoggetto'='P' THEN 'PERSONA_FISICA'.'codFiscale'
WHEN 'TITOLARITA'.'tipoSoggetto'='G' THEN 'PERSONA_GIURIDICA'.'codFiscale'
END as cod_fisc,
COD_DIRITTO.'decodifica'||' '||'TITOLARITA'.'titoloNonCod' as diritto,
'TITOLARITA'.'quotaNum'||'/'||'TITOLARITA'.'quotaDen' as quota
from
'PARTICELLA','TITOLARITA'
left join 'PERSONA_FISICA'
ON 'PERSONA_FISICA'.'idSoggetto'='TITOLARITA'.'idSoggetto'
left join 'PERSONA_GIURIDICA'
ON 'PERSONA_GIURIDICA'.'idSoggetto'='TITOLARITA'.'idSoggetto'
left join 'COD_COMUNE'
ON 'PERSONA_FISICA'.'luogoNascita'='COD_COMUNE'.'codice'
left join COD_DIRITTO
ON 'TITOLARITA'.'codDiritto'='COD_DIRITTO'.'codice'
WHERE
'TITOLARITA'.'idParticella'='" . $idParticella . "' AND
'PARTICELLA'.'idParticella'='TITOLARITA'.'idParticella'
ORDER by 'PERSONA_FISICA'.'cognome' asc,'PERSONA_FISICA'.'nome', 'PERSONA_GIURIDICA'.'denominazione'
";    

$ris_tit = $dblite->query($query_tit);
//$totRows=SqliteNumRows($ris_tit);   
$arrtit = array();
while ($table_tit = $ris_tit->fetchArray(SQLITE3_ASSOC)) 
{
    $xt = array(
        "tipo"=> $table_tit['tipo'],
        "idsoggetto"=> $table_tit['idSogg'],
        "denom" => $table_tit['denominazione'],
        "comune_nascita" => $table_tit['com_nasc'],
        "data_nascita" => $table_tit['data_nasc'],
        "codice_fiscale" => $table_tit['cod_fisc'],
        "diritto"=>$table_tit['diritto'],
        "quota"=>$table_tit['quota']
        );
    array_push($arrtit, $xt);
    

}



//print_r($row);
//    $row=$result->fetchArray();
//    foreach($data as $row) {
//
//    }
    $geo=[];
    $geo['foglio'] = $row['fglo'];
    $geo['particella'] = $row['particella'];
    $geo['superficie'] = $row['sup'];
    $geo['redditoDom'] = $row['RD'];
    $geo['redditoAgr'] = $row['RA'];
    $geo['Classe'] = $row['cl'];
    $geo['Qualita'] = $row['qualita'];
    $geo['titolari']=$arrtit;
    $geo['Catasto']="T";
    $geo['esiste'] = "SI";
    $geo['esito'] = "OK";
//    $geo = '{"foglio":'. $row['fglo'] . ', "particella":'. $row['particella'] .',
//                       "superficie": '. $row['sup'] .',
//                       "redditoDom": '. $row['RD'] .',
//                       "redditoAgr": '. $row['RA'] .',
//                       "Classe" : '. $row['cl'] .',
//                       "Qualita": '. $row['qualita'] .'}'; 


    $JSON = json_encode($geo);  
    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    return $JSON;
  }
}




    
}
