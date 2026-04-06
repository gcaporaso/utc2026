
<html>
	 <meta charset="UTF-8"> 
<body>
<head>

<link rel="stylesheet" href="cxc.css" type="text/css" media="all">

</head>

<?php

$id=htmlspecialchars($_GET["id"]);


if(!empty($_GET["n"]))
{
	$denomin=htmlspecialchars($_GET["n"]);
}
else {
	$denomin="nessun valore";
}


include "testata.php";

print "<hr>";


// --------------------------------------


function SqliteNumRows($ris){
    $numRows = 0;
    while($row = $ris->fetchArray()){
        ++$numRows;
    }
    return $numRows;
}



$db = new SQLite3('catasto.db');
//$db->loadExtension('mod_spatialite.so');












// TABELLA LISTATO UIU PER LE PARTICELLE CERCATE
// PRIMA TROVO LE UIU


$query_UIU="
select 
ltrim('IDENTIFICATIVI_IMMOBILIARI'.'foglio','0') as foglio,
ltrim('IDENTIFICATIVI_IMMOBILIARI'.'numero','0') as numero,
ltrim('IDENTIFICATIVI_IMMOBILIARI'.'subalterno','0') as sub
from 
'IDENTIFICATIVI_IMMOBILIARI'
WHERE
'IDENTIFICATIVI_IMMOBILIARI'.'idImmobile'='" . $id . "' ";


$risultato_UIU = $db->query($query_UIU);
$totRows=SqliteNumRows($risultato_UIU);
//echo $totRows;
if ($totRows>0){



echo "<hr>";
print "Visura per UIU - CATASTO URBANO";
echo "<hr>";




print "<table style='width:25%';>";

print "<tr><th></th><th>foglio</th><th>mappale</th><th>sub</th></tr>";

while ($table_UIU = $risultato_UIU->fetchArray(SQLITE3_ASSOC)) {

    //$idParticella=$table_UIU['idParticella'];
    
    print "<tr>";

    // print "<p> <a href=\"catasto_particella_2geojson.php?fg=$f&map=$m\" target=\"_blank\"> mappa</a></p>";
    print "<td><a href=\"catasto_particella_2geojson.php?fg=" . $table_UIU['foglio'] . "&map=" . $table_UIU['numero'] . "\" target=\"_blank\"> mappa</a></td>";
    //print "<td>" . $table_UIU['idImmobile'] . "</td>";
    print "<td><b>" . $table_UIU['foglio'] . "</b>   </td>"; 
    print "<td><b>" . $table_UIU['numero'] . "</b></td>"; 
    print "<td><b>" . $table_UIU['sub'] . "</b></td>";
    //flavio stesso discorso della linea 64...... corretto carlo
   /*
    print "<td>" . $table_nceu['zona'] . "</td>"; 
    print "<td>" . $table_nceu['categoria'] . "</td>";
    print "<td>" . $table_nceu['classe'] . "</td>";
    print "<td>" . $table_nceu['consistenza'] . "</td>"; 
    print "<td>" . $table_nceu['rendita'] . "</td>";
    print "<td>" . $table_nceu['piano'] . "</td>";
    print "<td>" . $table_nceu['indirizzo'] . "</td>"; 
    */
    print "</tr>";
}  
print "</table>"; 


print "<table>";

// Visualizzo l'UNITA IMMOBILIARE


$query_nceu="
select 

'UNITA_IMMOBILIARI'.'idImmobile',

/* DATI CENSUARI UIU */
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
       trim('UNITA_IMMOBILIARI'.'zona','0') as zona,
       '€ '||' '||'UNITA_IMMOBILIARI'.'renditaEuro' as rendita,
       (ltrim('UNITA_IMMOBILIARI'.'piano1','0')||' '||ltrim('UNITA_IMMOBILIARI'.'piano2','0')||' '||ltrim('UNITA_IMMOBILIARI'.'piano3','0')||' '||ltrim('UNITA_IMMOBILIARI'.'piano4','0')) as piano,
       'COD_TOPONIMO'.'decodifica'||' '||'INDIRIZZI'.'indirizzo'||' ' ||ltrim('INDIRIZZI'.'civico1','0')||' ' ||ltrim('INDIRIZZI'.'civico2','0')||' ' ||ltrim('INDIRIZZI'.'civico3','0') as indirizzo



from
'UNITA_IMMOBILIARI'




left join
  'INDIRIZZI'
  on
  'INDIRIZZI'.'idImmobile' = '" . $id . "'
  
left join
  'COD_TOPONIMO'
  on
  'COD_TOPONIMO'.'codice' = 'INDIRIZZI'.'toponimo'



WHERE
'UNITA_IMMOBILIARI'.'idImmobile'='" . $id . "'";


$risultato_nceu = $db->query($query_nceu);
$totRows=SqliteNumRows($risultato_nceu);
if ($totRows>1){
print "<tr><p>restituiti " . $totRows . " risultati" . "</p></tr>";
}
//echo $totRows;
if ($totRows>0){
  
print "<tr><td colspan=\"8\"></td></tr>";
print "<tr><th>-----</th><th>zona</th><th>cat</th><th>cl</th><th>consist</th><th>superf.</th><th>rendita</th><th>piano</th><th>indirizzo</th></tr>";


while ($table_nceu = $risultato_nceu->fetchArray(SQLITE3_ASSOC)) {

    
    
    print "<tr>";
    print "<td></td>"; 
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



// INSERISCO LE UTILITA COMUNI

$query_UC="select
ltrim(UC.foglio,'0') as foglio,
ltrim(UC.numero,'0') as numero,
ltrim(UC.subalterno,'0') as sub

FROM UTILITA_COMUNI_UI as UC

WHERE UC.idImmobile = '" . $id . "' ";

$risultato_UC = $db->query($query_UC);
$totRows_UC=SqliteNumRows($risultato_UC);
//echo $totRows;


if ($totRows_UC>0){
  print "<table style='width:25%';>";
print "<tr ><td style='text-align:left';>utilita comuni</td></tr>";
                // print "<tr><th>foglio</th><th>mappale</th><th>sub</th></tr>";

                while ($table_UC = $risultato_UC->fetchArray(SQLITE3_ASSOC)) {

                //$idParticella=$table_nceu['idParticella'];
                
                print "<tr>";
                //print "<td>" . $table_nceu['idImmobile'] . "</td>";
                print "<td style='text-align:left';>foglio " . $table_UC['foglio'] . "    map ". $table_UC['numero'] . "    sub ". $table_UC['sub'] . "   </td>"; 
                              
                print "</tr>";
            }  
                print "</table>"; 

}



print "<br>";



}
/* distict 
variante SELECT DISTINCT */
$query_tit="
select  
'TITOLARITA'.idSoggetto as idSog,
'TITOLARITA'.'idImmobile',
'TITOLARITA'.'titoloNonCod' as titplus,

trim('IDENTIFICATIVI_IMMOBILIARI'.'foglio','0') as foglio,
trim('IDENTIFICATIVI_IMMOBILIARI'.'numero','0') as numero,
trim('IDENTIFICATIVI_IMMOBILIARI'.'subalterno','0') as sub, 
'IDENTIFICATIVI_IMMOBILIARI'.'progr' as stadio,
'TITOLARITA'.'tipoSoggetto' as tipo, 
'TITOLARITA'.'idSoggetto' as idSogg,

/* DATI CENSUARI UIU */
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
       'COD_TOPONIMO'.'decodifica'||' '||'INDIRIZZI'.'indirizzo'||' ' ||ltrim('INDIRIZZI'.'civico1','0')||' ' ||ltrim('INDIRIZZI'.'civico2','0')||' ' ||ltrim('INDIRIZZI'.'civico3','0') as indirizzo,

/* denominazione intestatario */


CASE
WHEN 'TITOLARITA'.'tipoSoggetto'='P' THEN 'PERSONA_FISICA'.'cognome'||' '||'PERSONA_FISICA'.'nome'
WHEN 'TITOLARITA'.'tipoSoggetto'='G' THEN 'PERSONA_GIURIDICA'.'denominazione'
END as denominazione,
'PERSONA_FISICA'.'cognome' as cogn,
'PERSONA_FISICA'.'nome' as nom,

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


'COD_DIRITTO'.'decodifica'||' '||'TITOLARITA'.'titoloNonCod' as diritto,
'TITOLARITA'.'quotaNum'||'/'||'TITOLARITA'.'quotaDen' as quota

from
'TITOLARITA','IDENTIFICATIVI_IMMOBILIARI'

left join 'UNITA_IMMOBILIARI' on 'UNITA_IMMOBILIARI'.'idImmobile'='" . $id . "'

left join 'INDIRIZZI'  on  'INDIRIZZI'.'idImmobile' = '" . $id . "'
  
left join  'COD_TOPONIMO'  on  'COD_TOPONIMO'.'codice' = 'INDIRIZZI'.'toponimo'

left join 'PERSONA_FISICA' ON 'PERSONA_FISICA'.'idSoggetto'='TITOLARITA'.'idSoggetto'

left join PERSONA_GIURIDICA ON 'PERSONA_GIURIDICA'.'idSoggetto'='TITOLARITA'.'idSoggetto'

left join COD_COMUNE ON 'PERSONA_FISICA'.'luogoNascita'='COD_COMUNE'.'codice'

left join 'COD_DIRITTO' ON 'TITOLARITA'.'codDiritto'='COD_DIRITTO'.'codice'

WHERE

'TITOLARITA'.'idImmobile'='" . $id . "' and 'IDENTIFICATIVI_IMMOBILIARI'.'idImmobile'='" . $id . "' 
GROUP By idSogg
";
/* ultima riga query */
/* GROUP BY idSog */


$risultato_tit = $db->query($query_tit);
$totRows=SqliteNumRows($risultato_tit);


if ($totRows>0) {

if ($totRows>1){
print "<tr><p>restituiti " . $totRows . " risultati" . "</p></tr>";
}

print "<table>";


print "<tr><th></th><th></th><th style='text-align:left;'>denominazione</th><th>luogo di<br>nascita</th><th>data di<br>nascita</th><th>codice fiscale</th><th>diritto</th><th>quota</th></tr>";

while ($table_tit = $risultato_tit->fetchArray(SQLITE3_ASSOC)) {
$idSog=$table_tit['idSog'];
$tipo=$table_tit['tipo'];

//flavio 
//cosa è $table? non la tova ... 


$nominativo = $table_tit['denominazione'] . " " .$table_tit['com_nasc'] . " " . $table_tit['data_nasc'] . " " . $table_tit['cod_fisc'];
$cogn =   strtolower($table_tit['cogn']);
//$cogn=str_replace("`", "' ", $table_tit['cogn']);
$nom= $table_tit['nom'];

$anom = explode(' ', $nom);
    $nom=strtolower($anom[0]);

    $dug="";
    $descrizione="";
    $civico="";
    $comune_nascita="";
    


$data=$table_tit['data_nasc'];


    //$idParticella=$table_tit['idParticella'];
    
    print "<tr>";
    print "<td></td>";
    //print "<td>" . $table_tit['stadio'] . "</td>"; 
    // verifico se l'utente è loggato
    
    

    print "<td style='text-align:left';>" . "<p><a href=\"nctr_soggetto.php?idSog=$idSog&tipo=$tipo&n=$nominativo\" target=\"_self\">". $table_tit['denominazione'] ."</a></p>" . "</td>";

    
    // fine verifica 
    //print "<td style='text-align:left';><b>" . $table_tit['denominazione'] . "</b></td>";
    print "<td>" . $table_tit['com_nasc'] . "</td>"; 
    print "<td>" . $table_tit['data_nasc'] . "</td>";
    print "<td>" . $table_tit['cod_fisc'] . "</td>"; 
    print "<td>" . $table_tit['diritto'] . "</td>";
    print "<td>" . $table_tit['quota'] . "</td>";
   
    print "</tr>";
}  
print "</table>"; 
}
}

$db->close();

?>



</body>
</html>
