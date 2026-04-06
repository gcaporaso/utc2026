<?php
use yii;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * 
 */
//include_once('/var/www/ufficiotecnico/vendor/phayes/geophp/geoPHP.inc');
include_once('../vendor/phayes/geophp/geoPHP.inc');
if(!isset($_GET['foglio']) or empty($_GET['foglio']))
	die( json_encode(array('ok'=>0, 'errmsg'=>'specifica parametro foglio') ) );
if(!isset($_GET['particella']) or empty($_GET['particella']))
	die( json_encode(array('ok'=>0, 'errmsg'=>'specifica parametro particella') ) );

//$data = json_decode('[
//	{"loc":[41.575330,13.102411], "title":"aquamarine"},
//	{"loc":[41.575730,13.002411], "title":"black"},
//	{"loc":[41.807149,13.162994], "title":"blue"},
//	{"loc":[41.507149,13.172994], "title":"chocolate"},
//	{"loc":[41.847149,14.132994], "title":"coral"},
//	{"loc":[41.219190,13.062145], "title":"cyan"},
//	{"loc":[41.344190,13.242145], "title":"darkblue"},	
//	{"loc":[41.679190,13.122145], "title":"darkred"},
//	{"loc":[41.329190,13.192145], "title":"darkgray"},
//	{"loc":[41.379290,13.122545], "title":"dodgerblue"},
//	{"loc":[41.409190,13.362145], "title":"gray"},
//	{"loc":[41.794008,12.583884], "title":"green"},	
//	{"loc":[41.805008,12.982884], "title":"greenyellow"},
//	{"loc":[41.536175,13.273590], "title":"red"},
//	{"loc":[41.516175,13.373590], "title":"rosybrown"},
//	{"loc":[41.506175,13.173590], "title":"royalblue"},
//	{"loc":[41.836175,13.673590], "title":"salmon"},
//	{"loc":[41.796175,13.570590], "title":"seagreen"},
//	{"loc":[41.436175,13.573590], "title":"seashell"},
//	{"loc":[41.336175,13.973590], "title":"silver"},
//	{"loc":[41.236175,13.273590], "title":"skyblue"},
//	{"loc":[41.546175,13.473590], "title":"yellow"},
//	{"loc":[41.239190,13.032145], "title":"white"}
//]',true);	//SIMULATE A DATABASE data
////the searched field is: title
//
//if(isset($_GET['cities']))	//SIMULATE A BIG DATABASE, for ajax-bulk.html example
//	$data = json_decode( file_get_contents('cities15000.json'), true);
////load big data store, cities15000.json (about 14000 records)
$foglio = $_GET['foglio'];
$particella = $_GET['particella'];


 // carico il file json contenente la geometria particelle catastali
      // in nome del file da caricare in base al foglio
      //$pathfile = 'mappe/'. Yii::$app->params['Comune']. '/';
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
         die( json_encode(array('ok'=>0, 'errmsg'=>'particella non trovata') ) );
     }



//function searchInit($text)	//search initial text in titles
//{
//	$reg = "/^".$_GET['q']."/i";	//initial case insensitive searching
//	return (bool)@preg_match($reg, $text['title']);
//}
//$fdata = array_filter($data, 'searchInit');	//filter data
//$fdata = array_values($fdata);	//reset $fdata indexs
//
//$JSON = json_encode($fdata,true);
//
//#if($_SERVER['REMOTE_ADDR']=='127.0.0.1') sleep(1);
////simulate connection latency for localhost tests
//@header("Content-type: application/json; charset=utf-8");
//
//if(isset($_GET['callback']) and !empty($_GET['callback']))	//support for JSONP request
//	echo $_GET['callback']."($JSON)";
//else
//	echo $JSON;	//AJAX request


$centro = $rpc->centroid();
$JSON = json_encode($centro);

#if($_SERVER['REMOTE_ADDR']=='127.0.0.1') sleep(1);
//simulate connection latency for localhost tests
@header("Content-type: application/json; charset=utf-8");

echo $JSON;	//AJAX request