<?php
//use yii;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * 
 */


// parte che legge i dati catastali
# use SQLite3;
$dblite = new SQLite3('mappe/b542/catasto.db');

//## $qdst  --> query dati scaricati da sister tabella FILE relativamente ai TERRENI
//$qdst = "
//select
//descrizione||' '|| substr(dataElaborazione,9,2) ||'/'||substr(dataElaborazione,6,2)||'/'|| substr(dataElaborazione,1,4) as data
//from FILE
//WHERE 
//descrizione like '%Terren%'
//";
//$ris_ter = $db->query($qdst);
//while ($table = $ris_ter->fetchArray(SQLITE3_ASSOC)) {
//# $dt -> data di estrazione dei dati relativi ai TERRENI
//$dt=$table['data'];
//}
//
//
//## COMUNE
//$qcod= "
//select distinct
//substr(nomeFile,0,5) as cod
//from
//FILE
//";
//$ris_cod = $db->query($qcod);
//while ($t_c = $ris_cod->fetchArray(SQLITE3_ASSOC)) {
//# $dt -> data di estrazione dei dati relativi ai TERRENI
//$cod=$t_c['cod'];
//}
//
//
//## NOME DEL COMUNE
//$qcom= "
//select decodifica as comune
//from
//COD_COMUNE
//WHERE
//codice='$cod'
//LIMIT 1
//";
//
//$ris_com = $db->query($qcom);
//while ($t_com = $ris_com->fetchArray(SQLITE3_ASSOC)) {
//# $dt -> data di estrazione dei dati relativi ai TERRENI
//$com_logo=ucfirst(strtolower($t_com['comune']));
//$com=$t_com['comune'];
//}
//
//## $qdst  --> query dati scaricati da sister tabella FILE relativamente ai FABBRICATI
//
//$qdsf = "
//select
//descrizione||' '|| substr(dataElaborazione,9,2) ||'/'||substr(dataElaborazione,6,2)||'/'|| substr(dataElaborazione,1,4) as data
//from FILE
//WHERE 
//descrizione like '%Fabb%'
//";
//
//$ris_fab = $db->query($qdsf);
//while ($table = $ris_fab->fetchArray(SQLITE3_ASSOC)) {
//# $df -> data di estrazione dei dati relativi ai FABBRICATI
//$df=$table['data'];
//}
//
//$db->close();

if(!isset($_GET['foglio']) or empty($_GET['foglio']))
	die( json_encode(array('ok'=>0, 'errmsg'=>'specifica parametro foglio') ) );
if(!isset($_GET['particella']) or empty($_GET['particella']))
	die( json_encode(array('ok'=>0, 'errmsg'=>'specifica parametro particella') ) );

// recupera le variabile della particella foglio e particella
$foglio = $_GET['foglio'];
$particella = $_GET['particella'];

#############################################
# Terreni
#############################################

//// recupera le variabile della particella fg e map, che arrivano da visura particella - mappa-
//$fg=htmlspecialchars($_GET["FOGLIO"]);
//$map=htmlspecialchars($_GET["PARTICELLA"]);

######################################################### carlo ######################################################
// recupera il nominativo cercato, attraverso idSoggetto della tabella PersonaFisica o PersonaGiuridica
//$nominativo=htmlspecialchars($_GET["nome"]);
//$nominativo=$_GET["n"];
####################################################### carlo fine ###################################################

// connette a catasto.db
//$conn_c = new SQLite3('catasto.db');

// IMPOSTA LA QUERY per trovare le particelle ai terreni tramite idSoggetto $idsog
$query_c="
select 
ltrim(pa.foglio,'0') as foglio,
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
foglio = '" . $foglio . "' AND particella='" .$particella. "'
 
ORDER BY fg ASC, map ASC 
";



## imposto il limite di tempo, in secondi, per eseguire la query . Di default è 30s

set_time_limit(360);



//esegue la query
//$rs_c = $conn_c->query($query_c);
$result = $dblite->query($query_c);

$row=$result->fetchArray();

$geo = '{"foglio":'. $row['foglio'] . ', "particella":'. $row['particella'] .',
                   Superficie = '. $row['sup'] .',
                   Reddito Dominicale = '. $row['RD'] .',
                   Reddito Agrario = '. $row['RA'] .',
                   Classe = '. $row['cl'] .',
                   Qualità = '. $row['qualita'] .'}';
### Apre la collezione GeoJson delle feature geometriche da particelle

//$geojsonp = array(
//    'type'      => 'FeatureCollection',
//    'features'  => array()
//);


$JSON = json_encode($geo);

# ritorna il geojosn al consumer per poter interrogare ed aprire in qgis ad esempio 
# ma anche in leaflet 
header('Content-type: application/json');

echo $JSON;	//AJAX request