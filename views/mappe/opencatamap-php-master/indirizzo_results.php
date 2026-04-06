<html>
	 <meta charset="UTF-8"> 
<body>
<head>

<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
<link rel="stylesheet" href="cxc.css" type="text/css" media="all">
</head>
<?php
if (!$_GET["s"]){
  echo " nome strada obbligatorio !!!";
  exit;
}
$s=$_GET["s"];


$c=$_GET["c"];
//if (!$_GET["c"]){$c ="NULL";}

$d=$_GET["d"];
//if (!$_GET["d"]){$d="NULL"}


//$search=$d." ".$s. " " .$c;
// $denomin=htmlspecialchars($_GET["denomin"]);

//$s=ltrim( $s, '0' ); // trimmo gli zeri se presenti in foglio 0025 diventa 25
//$c=ltrim( $c, '0' ); // trimmo gli zeri se presenti in numero 00025 diventa 25

include "testata.php";
//echo $search;


function SqliteNumRows($ris){
    $numRows = 0;
    while($row = $ris->fetchArray()){
        ++$numRows;
    }
    return $numRows;
}

$db = new SQLite3('catasto.db');
//$db->loadExtension('mod_spatialite.so');

set_time_limit(3600);

// preparo la query per le UIU

$query_nceu="


select 
'IND'.'idImmobile',
ltrim('II'.'foglio','0') as foglio,
ltrim('II'.'numero','0') as numero,
ltrim('II'.'subalterno','0') as sub,
ltrim('UI'.'zona','0') as zona,
ltrim(UI.superficie) as superficie,


ltrim('UI'.'categoria') as categoria,


        
       ltrim('UI'.'classe','0') as classe,
       CASE
       WHEN substr('UI'.'categoria',1,1)='A'
       THEN ('UI'.'consistenza'||' vani')
       WHEN substr('UI'.'categoria',1,1)='B'
       THEN ('UI'.'consistenza'||' mc')
       WHEN substr('UI'.'categoria',1,1)='C'
       THEN ('UI'.'consistenza'||' mq')
       ELSE ''
       END as consistenza,
       '€ '||' '||'UI'.'renditaEuro' as rendita,
       (ltrim('UI'.'piano1','0')||' '||ltrim('UI'.'piano2','0')||' '||ltrim('UI'.'piano3','0')||' '||ltrim('UI'.'piano4','0')) as piano,

       'COD_TOPONIMO'.'decodifica' as d,

       'COD_TOPONIMO'.'decodifica'||' '||'IND'.'indirizzo'||' ' ||ltrim('IND'.'civico1','0')||' ' ||ltrim('IND'.'civico2','0')||' ' ||ltrim('IND'.'civico3','0') as indirizzo,
       ltrim('IND'.'civico1','0')||' ' ||ltrim('IND'.'civico2','0')||' ' ||ltrim('IND'.'civico3','0') as civic
       
from  'IDENTIFICATIVI_IMMOBILIARI' as 'II','INDIRIZZI' as 'IND'

left join
  'COD_TOPONIMO'
  on
  'COD_TOPONIMO'.'codice' = 'IND'.'toponimo'

left join
'UNITA_IMMOBILIARI' as 'UI'
on
'UI'.'idImmobile'='IND'.'idImmobile' and UI.IdMutazioneFinale=''and categoria is not null


where 'II'.'idImmobile'='IND'.'idImmobile' AND d like '%" .$d."%' AND indirizzo like '%".$s."%' AND civic like '%".$c."%'


GROUP BY 'IND'.'idImmobile'
ORDER BY 'IND'.'idImmobile'";


$risultato_nceu = $db->query($query_nceu);
$totRow_nceu=SqliteNumRows($risultato_nceu);
if ($totRow_nceu>0){


echo "<hr>";
?>

<h3>RICERCA CATASTO URBANO PER INDIRIZZO</h3>

<?php
if ($totRow_nceu>1){
  print "<p>trovati ". $totRow_nceu . " elementi per ".$d." ".$s." ".$c." </p>";
  
  ?>
  <button onclick="f_nctr()">mostra/nascondi</button>
  <?php
  
  }
  print "<p> <a href=\"indirizzo_results_2geojson.php?s=$s&c=$c&d=$d\" target=\"_blank\"> mappa...</a></p>";
print "<div id=\"NCTR\">";


echo "<hr>";
print "<table>";


print "<tr><th>     </th><th>foglio</th><th>mappale</th><th>sub</th><th>zona</th><th>cat</th><th>cl</th><th>consist</th><th>superf.</th><th>rendita</th><th>piano</th><th>indirizzo</th></tr>";

while ($table_nceu = $risultato_nceu->fetchArray(SQLITE3_ASSOC)) {
    // $nRighe = $table_nceu['count'];
    $idImmobile= $table_nceu['idImmobile'];

    //$idParticella=$table_nceu['idParticella'];
    
    print "<tr>";
    print "<td>" . "<p><a href=\"nceu_results.php?id=$idImmobile\" target=\"_self\">".$idImmobile."</a></p>" . "</td>";
    print "<td>" . $table_nceu['foglio'] . "</td>";
    print "<td>" . $table_nceu['numero'] . "</td>"; 
    print "<td>" . $table_nceu['sub'] . "</td>";
    print "<td>" . $table_nceu['zona'] . "</td>"; 
    print "<td>" . $table_nceu['categoria'] . "</td>";
    print "<td>" . $table_nceu['classe'] . "</td>";
    print "<td>" . $table_nceu['consistenza'] . "</td>"; 
    print "<td>" . $table_nceu['superficie'] . "</td>"; 
    print "<td>" . $table_nceu['rendita'] . "</td>";
    print "<td>" . $table_nceu['piano'] . "</td>";
    
    print "<td>" . $table_nceu['indirizzo'] . "</td>"; 
    print "</tr>";
}  
print "</table>"; 
}
echo "<hr><br>";

$db->close();

?>



</body>
</html>


