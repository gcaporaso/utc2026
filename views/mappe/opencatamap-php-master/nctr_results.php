<html>
	 <meta charset="UTF-8"> 
<body>
<head>

<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
<link rel="stylesheet" href="cxc.css" type="text/css" media="all">
</head>
<?php
if (!$_GET["f"] or !$_GET["m"])
{
  echo "Parametri foglio e/o mappale mancanti !";
  exit;
}

$f=htmlspecialchars($_GET["f"]);
$m=htmlspecialchars($_GET["m"]);

// $denomin=htmlspecialchars($_GET["denomin"]);

$f=ltrim( $f, '0' ); // trimmo gli zeri se presenti in foglio 0025 diventa 25
$m=ltrim( $m, '0' ); // trimmo gli zeri se presenti in numero 00025 diventa 25

include "testata.php";



function SqliteNumRows($ris)
{
    $numRows = 0;
    while($row = $ris->fetchArray())
    {
        ++$numRows;
    }
    return $numRows;
}

$db = new SQLite3('catasto.db');
//$db->loadExtension('mod_spatialite.so');

$query="
  SELECT 
  
'PARTICELLA'.'idParticella', 
'CARATTERISTICHE_PARTICELLA'.IdMutazioneFinale,
'COD_QUALITA'.decodifica,
ltrim('PARTICELLA'.foglio,'0')as foglio,
ltrim('PARTICELLA'.numero,'0') as numero,
ltrim('PARTICELLA'.subalterno,'0') as sub,
ltrim('CARATTERISTICHE_PARTICELLA'.classe,'0') AS classe,
'COD_QUALITA'.'decodifica' as qualita,
('CARATTERISTICHE_PARTICELLA'.ettari*10000)+('CARATTERISTICHE_PARTICELLA'.are*100)+('CARATTERISTICHE_PARTICELLA'.centiare) as area,
'CARATTERISTICHE_PARTICELLA'.'redditoAgrEuro' AS redd_agr,
'CARATTERISTICHE_PARTICELLA'.'redditoDomEuro' AS redd_dom
FROM 
	'PARTICELLA', 
	'COD_QUALITA', 
	'CARATTERISTICHE_PARTICELLA'
WHERE 
ltrim(foglio,'0') ='" . $f . "' AND ltrim(numero,'0')='" . $m ."' AND
'PARTICELLA'.'idParticella'='CARATTERISTICHE_PARTICELLA'.'idParticella' AND
  'COD_QUALITA'.'codice' = 'CARATTERISTICHE_PARTICELLA'.'codQualita' AND 'CARATTERISTICHE_PARTICELLA'.IdMutazioneFinale='' ";

$risultato = $db->query($query);
$totRow=SqliteNumRows($risultato);


if ($totRow>0)
{
print "<h3>ricerca per</h3><p>foglio " . $f . " mappale " . $m ."</p>";
echo "<hr>";
print "<h3>RICERCA CATASTO TERRENI</h3><br>";

echo "<hr>";
if ($totRow>1)
{
print "<tr><p>restituiti " . $totRows . " risultati" . "</p></tr>";
}

print "<table>";

print "<tr><th> </th><th>foglio</th><th>mappale</th><th>sub</th><th>qualita</th><th>cl</th><th>area</th><th>RA</th><th>RD</th></tr>";

while ($table = $risultato->fetchArray(SQLITE3_ASSOC)) 
  {

    $idParticella=$table['idParticella'];
    
    print "<tr>";
    print "<td>";
    print "<p> <a href=\"catasto_particella_2geojson.php?fg=$f&map=$m\" target=\"_blank\"> mappa</a></p>";
    print "<div id=\"NCTR\">";
    print "</td>";
    print "<td>" . $table['foglio'] . "</td>";
    print "<td>" . $table['numero'] . "</td>";
    print "<td>" . $table['sub'] . "</td>"; 
    print "<td>" . $table['qualita'] . "</td>";
    print "<td>" . $table['classe'] . "</td>"; 
    print "<td>" . $table['area'] . "</td>";
    print "<td>" . $table['redd_agr'] . "</td>";
    print "<td>" . $table['redd_dom'] . "</td>";  
    print "</tr>";

#spostamneto codice STRAPPO
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

$risultato_tit = $db->query($query_tit);

$totRows=SqliteNumRows($risultato_tit);


if ($totRows>0)
{

      print "</table>"; 

      print "<p class = \"sl\">t i t o l a r i</p>";

      print "<table>";

      print "<tr style='vertical-align:top'><th style='text-align:left';>denominazione</th><th>comune di<br>nascita</th><th>data di<br>nascita</th><th>cod fiscale</th><th>diritto</th><th>quota</th></tr>";

      while ($table_tit = $risultato_tit->fetchArray(SQLITE3_ASSOC)) 
        {
        $idSog=$table_tit['idSogg'];
        $tipo=$table_tit['tipo'];
        $denomin=$table_tit['denominazione']." ".$table_tit['com_nasc']." ".$table_tit['data_nasc'];
            print "<tr>";
                
                          
                print "<td style='text-align:left';>" . "<p><a href=\"nctr_soggetto.php?idSog=$idSog&tipo=$tipo&n=$denomin\" target=\"_self\">". $table_tit['denominazione'] ."</a></p>" . "</td>";
               
                     
        //print "<td style='text-align:left';>" . $table_tit['denominazione'] . "</td>";
        print "<td>" . $table_tit['com_nasc'] . "</td>"; 
        print "<td>" . $table_tit['data_nasc'] . "</td>";
        print "<td>" . $table_tit['cod_fisc'] . "</td>"; 
        print "<td>" . $table_tit['diritto'] . "</td>";
        print "<td>" . $table_tit['quota'] . "</td>";
        print "</tr>";



        }



      }  
print "</table>"; 
}
}  

ELSE 
{
?>
<!-- QUESTO E' DA ELIMINARE .... non serve -->
<!-- se il risultato della ricerca non è > 0 esegue lo java script... e riporta alla pagina di ricerca -->
<!--

<script type="text/javascript">

  function doRedirect() {
    //Genera il link alla pagina che si desidera raggiungere
    location.href = "cerca_nctr.php"
  }
  
  //Questo è il messaggio di attesa di redirect in corso…
  document.write("Nessun risultato... sarai riportato alla finestra di ricerca");
  
  //Fa partire il redirect dopo 10 secondi da quando l'intermprete JavaScript ha rilevato la funzione
  //Fa partire il redirect dopo 2 secondi da quando l'intermprete JavaScript ha rilevato la funzione
  window.setTimeout("doRedirect()", 2000);


</script>
-->
<!-- FINE java 


#### STRAPPO 

-->
<?php
}


echo "<br>";


// preparo la query per le UIU

$query_nceu="
      SELECT DISTINCT 'IDENTIFICATIVI_IMMOBILIARI'.'idImmobile',
      'UNITA_IMMOBILIARI'.IdMutazioneFinale,
       ltrim('IDENTIFICATIVI_IMMOBILIARI'.'foglio','0') as foglio,
       ltrim('IDENTIFICATIVI_IMMOBILIARI'.'numero','0') as numero,
       ltrim('IDENTIFICATIVI_IMMOBILIARI'.'subalterno','0') as sub,
       ltrim('UNITA_IMMOBILIARI'.'zona','0') as zona,
       
        CASE 
        WHEN 'UNITA_IMMOBILIARI'.'categoria'<>'A10'
        THEN replace( 'UNITA_IMMOBILIARI'.'categoria', '0', '/' )
        ELSE replace( 'UNITA_IMMOBILIARI'.'categoria', '10', '/10' )
        END as categoria,
        
       ltrim('UNITA_IMMOBILIARI'.'classe','0') as classe,
       CASE
       WHEN substr('UNITA_IMMOBILIARI'.'categoria',1,1)='A'
       THEN ('UNITA_IMMOBILIARI'.'consistenza'||' vani')
       WHEN substr('UNITA_IMMOBILIARI'.'categoria',1,1)='B'
       THEN ('UNITA_IMMOBILIARI'.'consistenza'||' mc')
       WHEN substr('UNITA_IMMOBILIARI'.'categoria',1,1)='C'
       THEN ('UNITA_IMMOBILIARI'.'consistenza'||' mq')
       ELSE ''
       END as consistenza,
       'UNITA_IMMOBILIARI'.'superficie',
       '€ '||' '||'UNITA_IMMOBILIARI'.'renditaEuro' as rendita,
       (ltrim('UNITA_IMMOBILIARI'.'piano1','0')||' '||ltrim('UNITA_IMMOBILIARI'.'piano2','0')||' '||ltrim('UNITA_IMMOBILIARI'.'piano3','0')||' '||ltrim('UNITA_IMMOBILIARI'.'piano4','0')) as piano,
       'COD_TOPONIMO'.'decodifica'||' '||'INDIRIZZI'.'indirizzo'||' ' ||ltrim('INDIRIZZI'.'civico1','0')||' ' ||ltrim('INDIRIZZI'.'civico2','0')||' ' ||ltrim('INDIRIZZI'.'civico3','0') as indirizzo
       
  FROM 'IDENTIFICATIVI_IMMOBILIARI', 'UNITA_IMMOBILIARI'
  
  left join
  'INDIRIZZI'
  on
  'INDIRIZZI'.'idImmobile' = 'IDENTIFICATIVI_IMMOBILIARI'.'idImmobile'
  
left join
  'COD_TOPONIMO'
  on
  'COD_TOPONIMO'.'codice' = 'INDIRIZZI'.'toponimo'
  
  WHERE 
  ltrim(foglio,'0') ='" . $f . "' AND ltrim(numero,'0')='" . $m ."'  AND 'IDENTIFICATIVI_IMMOBILIARI'.'idImmobile'='UNITA_IMMOBILIARI'.'idImmobile' and 'UNITA_IMMOBILIARI'.IdMutazioneFinale='' and categoria !=''
  GROUP BY 'IDENTIFICATIVI_IMMOBILIARI'.'idImmobile'
  ORDER BY   'foglio' asc, 'IDENTIFICATIVI_IMMOBILIARI'.'numero' asc, 'IDENTIFICATIVI_IMMOBILIARI'.'subalterno'
asc";


$risultato_nceu = $db->query($query_nceu);
$totRow_nceu=SqliteNumRows($risultato_nceu);
if ($totRow_nceu>0){


echo "<hr>";
?>

<h3>RICERCA CATASTO URBANO</h3>

<?php


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
