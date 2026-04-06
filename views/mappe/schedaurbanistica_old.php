<?php
use yii;
use PhpOffice\PhpWord;
use PhpOffice\PhpWord\Shared;
use PhpOffice\PhpWord\Settings;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;







    
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
function Urbanistica($sez, $foglio, $particella)
    {
      // carico il file json contenente la geometria particelle catastali
      // in nome del file da caricare in base al foglio
      $pathfile =  \Yii::$app->params['Comune']. '/';
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
function Vincoli($sez, $foglio, $particella)
    {
        
          //****************************************
    // ricerca dei vincoli ****
    // 1 - VINCOLO PAESAGGISTICO
    // ***************************************
    $pathfile = \Yii::$app->params['Comune']. '/';
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
                $area = round($in->getArea()*100000.0*100000.0,0);
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
    $idrof = file_get_contents("b542/vincoloidrog.json");
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
   

    
    
include_once('../vendor/phayes/geophp/geoPHP.inc');
if(!isset($_GET['foglio']) or empty($_GET['foglio']))
	die( json_encode(array('ok'=>0, 'errmsg'=>'specifica parametro foglio') ) );
if(!isset($_GET['particella']) or empty($_GET['particella']))
	die( json_encode(array('ok'=>0, 'errmsg'=>'specifica parametro particella') ) );

$foglio = $_GET['foglio'];
$particella = $_GET['particella'];

$scheda= Urbanistica('', $foglio, $particella);
$vinc=Vincoli('', $foglio, $particella);
        
    $content =  
'
<p><strong>SCHEDA URBANISTICA' . date("Y") . ',</strong></p>
<p></p>
<p></p>
<p>PARTICELLA CATASTALE FOGLIO:' . $foglio .'</p> ' . $particella .'
<p></p>
<p>PIANO REGOLATORE GENERALE:</p>
<p></p>
<p>'. $scheda .'</p>
<p></p>
<p></p>
<p>VINCOLI:</p>
<p></p>
<p>' . $vinc . '</p>
<div><p></p></div>
<p>Campoli del Monte Taburno, li '. date("d-m-Y") . '</p>
<p>&nbsp;</p>';       
//$this->renderPartial('_reportView');
    
    // setup kartik\mpdf\Pdf component
    $pdf = new Pdf([
        'filename'=>'Scheda_urbanistica_foglio_'. $foglio .'_particella_'. $particella .'_' . date("d-m-Y"),
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
        'options' => ['title' => 'Scheda Urbanistica ' . date("d-m-Y")],
         // call mPDF methods on the fly
        'methods' => [ 
            'SetHeader'=>['Comune di Campoli del Monte Taburno - Ufficio Tecnico'], 
            'SetFooter'=>['{PAGENO}'],
        ]
    ]);
    //die( var_dump( Yii::app()->getRequest()->getIsSecureConnection() ) );
    // return the pdf output as per the destination setting
    return $pdf->render(); 
    
   //return $this->render('test', ['html' =>$content]);
  

$centro = 'OK';
$JSON = json_encode($centro);

#if($_SERVER['REMOTE_ADDR']=='127.0.0.1') sleep(1);
//simulate connection latency for localhost tests
@header("Content-type: application/json; charset=utf-8");

echo $JSON;	//AJAX request