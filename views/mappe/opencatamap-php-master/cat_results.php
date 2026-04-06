<html>
   <meta charset="UTF-8"> 
<body>
<head>

<link rel="stylesheet" href="cxc.css" type="text/css" media="all">



</head>


<script>
function f_nctr() {
    var x = document.getElementById('NCTR');
    if (x.style.display === 'none') {
        x.style.display = 'block';
    } else {
        x.style.display = 'none';
    }
}
</script>

<script>
function f_nceu() {
    var x = document.getElementById('NCEU');
    if (x.style.display === 'none') {
        x.style.display = 'block';
    } else {
        x.style.display = 'none';
    }
}
</script>

<?php

//$idSog=trim($_GET["idSog"]);
$idSog=1;
//$denomin=($_GET["n"]);
$denomin="denomin";
include './testata.php';	
?>






<!--
23.03.2017 eliminato 

<script type="text/javascript">
function toggle_visibility(foo) {
  var e = document.getElementById(foo);
  e.style.display = ((e.style.display!='none') ? 'none' : 'block');
}
</script>

-->

<?php
print "<br>";
print "<span style='font-size:13px'><b>Risultati ricerca per categoria</span></b>";

//print "<br>";

#---------------------------------------------- totale dei numeri di righe ritornate
function SqliteNumRows($ris){
    $numRows = 0;
    while($row = $ris->fetchArray()){
        ++$numRows;
    }
    return $numRows;
}




$db = new SQLite3('catasto.db');



#################################################################################################
########################## --> NUOVO CATASTO EDILIZIO URBANO <-- ################################
#################################################################################################

$query_nceu="
       
 select 
distinct tit.idImmobile as idImm,
UI.IdMutazioneFinale,
tit.idSoggetto,

ltrim(II.foglio,'0') as fg,
ltrim(II.numero,'0') as map,
ltrim(II.subalterno,'0') as sub,
ltrim(UI.zona,'0') as zona,
CASE 
        WHEN 'UI'.'categoria'<>'A10'
        THEN replace( 'UI'.'categoria', '0', '/' )
        ELSE replace( 'UI'.'categoria', '10', '/10' )
        END as categoria,

CASE
       WHEN substr('UI'.'categoria',1,1)='A'
       THEN ('UI'.'consistenza'||' vani')
       WHEN substr('UI'.'categoria',1,1)='B'
       THEN ('UI'.'consistenza'||' mc')
       WHEN substr('UI'.'categoria',1,1)='C'
       THEN ('UI'.'consistenza'||' mq')
       ELSE ''
       END as consistenza,
       
(ltrim('UI'.'piano1','0')||' '||ltrim('UI'.'piano2','0')||' '||ltrim('UI'.'piano3','0')||' '||ltrim('UI'.'piano4','0')) as piano,

'CT'.'decodifica'||' '||'INDIRIZZI'.'indirizzo'||' ' ||ltrim('INDIRIZZI'.'civico1','0')||' ' ||ltrim('INDIRIZZI'.'civico2','0')||' ' ||ltrim('INDIRIZZI'.'civico3','0') as indirizzo,
UI.renditaEuro as rendita,

CD.decodifica as diritto,

tit.quotaNum||'/'||tit.quotaDen as quota

From TITOLARITA as tit, IDENTIFICATIVI_IMMOBILIARI as II

left join INDIRIZZI on INDIRIZZI.idImmobile=II.idImmobile
left join COD_DIRITTO as CD on CD.codice = tit.codDiritto
left join UNITA_IMMOBILIARI as UI on UI.idImmobile=tit.idImmobile and UI.IdMutazioneFinale='' 
left join COD_TOPONIMO as CT on INDIRIZZI.toponimo=CT.codice
where  II.idImmobile = tit.idImmobile AND categoria='A01'  
GROUP BY idImm
ORDER BY fg ASC, map ASC, sub ASC";

$risultato_nceu = $db->query($query_nceu);
$totRows_nceu=SqliteNumRows($risultato_nceu);
if ($totRows_nceu>0){

    echo "<hr>";
    print "<h4>Nuovo Catasto Edilizio Urbano</h4>";

    if ($totRows_nceu>1){
    	print "<p>estratti da urbano ". $totRows_nceu ." elementi -</p> ";
    	?>
	<button onclick="f_nceu()">mostra/nascondi</button>


	<?php
    	}
    	
      print "<p></p><a href=\"cat_results_2geojson.php\" target=\"_blank\">mappa</a></p>";

      print "<div id=\"NCEU\">";



    
    echo "<hr>";
    ?>

  

    <?php

    //print "<span style='font-size:10px'><h4>ricerca per foglio <b>" . per . "</h4></span></b>";

    print "<table>";

    print "<tr ><th></th><th></th><th>foglio</th><th>map</th><th>sub</th><th>zona</th><th>cat</th><th>consistenza</th><th>piano</th><th>indirizzo</th><th>rendita</th><th>diritto</th><th>quota</th></tr>";

    ## imposto il limite di tempo, in secondi, per eseguire la query . Di default è 30 s

    set_time_limit(360);

    $num=1;
    while ($table = $risultato_nceu->fetchArray(SQLITE3_ASSOC)) {

        //$idParticella=$table['idParticella'];
        
        print "<tr>";
        print "<td style='text-align:right';><p><small>" . $num .")</small></p></td>";
        print "<td style='text-align:left';>" . "<p><a href=\"nceu_results.php?id=". $table['idImm'] . "\" target=\"_self\">visura</a></p>" . "</td>";
        print "<td><b>" . $table['fg'] . "</b></td>";
        print "<td><b>" . $table['map'] . "</b></td>";
        //print "<td style='text-align:left';>" . $table['denomin'] . "</td>"; 
        print "<td><b>" . $table['sub'] . "</b></td>";
        print "<td>" . $table['zona'] . "</td>";
        print "<td>" . $table['categoria'] . "</td>";
        print "<td>" . $table['consistenza'] . "</td>";

        print "<td>" . $table['piano'] . "</td>";
        print "<td>" . $table['indirizzo'] . "</td>";
        print "<td>" . $table['rendita'] . "</td>";
        print "<td>" . $table['diritto'] . "</td>";
        print "<td>" . $table['quota'] . "</td>";
        print "</tr>";
        ++$num;
    }  
    print "</table>"; 

   
  }

  print "</div>";

?>



</body>
</html>
