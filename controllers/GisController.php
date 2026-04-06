<?php

namespace app\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use app\models\Mappe;
use app\models\Particella;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
//use kartik\mpdf\Pdf;
//use Mpdf\Mpdf;

/**
 * CommittentiController implements the CRUD actions for Committenti model.
 */
class GisController extends Controller
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
//
//        $model=\app\models\Edilizia::find()
//                ->select(['NumeroProtocollo','DataProtocollo','NumeroTitolo','DataTitolo','DescrizioneIntervento','id_titolo','committenti.Cognome',
//                          'committenti.Nome','committenti.Denominazione','Latitudine','Longitudine','RegimeGiuridico_id'])
//                ->innerJoin('committenti', 'edilizia.id_committente=committenti_id')
//                ->andWhere(['IS NOT','Latitudine', null])
//                ->andWhere(['IS NOT', 'Longitudine',null])
//                ->andWhere(['Stato_Pratica_id'=>5])
//                ->asArray()
//                ->all();
//         //$num =\app\models\Edilizia::find()
//         //       ->count();
//        $num = \app\models\Edilizia::find()
//                ->select(['NumeroTitolo','DataTitolo','DescrizioneIntervento','id_titolo','committenti.Cognome',
//                          'committenti.Nome','committenti.Denominazione','Latitudine','Longitudine','RegimeGiuridico_id'])
//                ->innerJoin('committenti', 'edilizia.id_committente=committenti_id')
//                ->andWhere(['IS NOT','Latitudine', null])
//                ->andWhere(['IS NOT', 'Longitudine',null])
//                ->andWhere(['Stato_Pratica_id'=>5])
//                ->asArray()
//                ->count();

         
         
        return $this->render('mappe3', [
//            'pratiche' => $model,
//            'numero'=>$num,
            'particella'=>$particella
            
            
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

$scheda= $this->Urbanistica('', $foglio, $particella);
$vinc=$this->Vincoli('', $foglio, $particella);
        
    $content =  
'
<p><b><strong>SCHEDA URBANISTICA</strong></b></p>
<p></p>
<p></p>
<p>RIFERIMENTI CATASTALI:</p>
<p>Foglio: <b>' . $foglio .'</b> Particella <b>' . $particella .'</b></p>
<p></p>
<p><b>PIANO REGOLATORE GENERALE:</b></p>
<p>Destinazione Urbanistica:</p>'. $scheda .'
<p></p>
<p></p>
<p><b>VINCOLI:</b></p>
<p>' . $vinc . '</p>
<p></p>
<p style="font-size: 0.75rem">Note: La presente scheda è per uso interno e non costituisce certificato ufficiale di destinazione urbanistica</p>
<p></p>
<p></p>
<div><p></p></div>
<p>Campoli del Monte Taburno, li '. date("d-m-Y") . '</p>
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


    
    
    
}
