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
  function f_nceu() 
  {
      var x = document.getElementById('NCEU');
      if (x.style.display === 'none') {
          x.style.display = 'block';
      } else {
          x.style.display = 'none';
      }
  }
  </script>

<?php

$idSog=trim($_GET["idSog"]);

$denomin=($_GET["n"]);

$tipo=($_GET["tipo"]);
$n=($_GET["ricerca"]);

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
print "<span style='font-size:13px'><b>Risultati per il nominativo <b>" . $denomin . "</b></span></b>";

//print "<br>";

  #---------------------------------------------- totale dei numeri di righe ritornate
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


#################################################################################################
########################## --> CERCA IN NUOVO CATASTO TERRENI <-- ###############################
#################################################################################################

// 25.03.2017 modificata query con distict e GROUP BY, per evidenziare le particelle una sola volta anche quando idSoggetto compare più volte

$query="select 


distinct tit.idParticella as idPart,
tit.idSoggetto,
ltrim(pa.foglio,'0') as fg,
ltrim(pa.numero,'0') as map,
ltrim(pa.subalterno,'0') as sub,
CQ.decodifica as qualita,
ltrim(CP.classe,'0') as cl,
CP.ettari*10000+CP.are*100+CP.centiare as superf,
CP.redditoDomEuro as RD,
CP.redditoAgrEuro as RA,
CD.decodifica as diritto,
tit.quotaNum ||'/'|| tit.quotaDen as quota

From TITOLARITA as tit, PARTICELLA as pa
left join CARATTERISTICHE_PARTICELLA as CP on CP.idParticella=idPart

left join COD_DIRITTO as CD on tit.codDiritto=CD.codice
left join COD_QUALITA as CQ on CP.codQualita=CQ.codice

where tit.idSoggetto = '" . $idSog . "' and tit.idParticella=pa.idParticella  and tipoSoggetto= '" . $tipo ."'
GROUP BY idPart  
ORDER BY fg ASC, map ASC
";


## imposto il limite di tempo, in secondi, per eseguire la query . Di default è 30s
set_time_limit(420);


$risultato = $db->query($query);
$totRows=SqliteNumRows($risultato);
if ($totRows>0)
    {






      echo "<hr>";
      print "<h4>Nuovo Catasto Terreni Revisionato </h4>";

      if ($totRows>1)
      {
      	print "<p>estratti da terreni ". $totRows . " elementi </p>";
      	?>
      	<button onclick="f_nctr()">mostra/nascondi</button>


      	<?php
    	}
      print "<p> <a href=\"catasto_particelle_2geojson.php?idSog=$idSog&tipo=$tipo&n=$denomin\" target=\"_blank\"> mappa...</a></p>";
      print "<div id=\"NCTR\">";
      /*
      23.03.2017 eliminato show-hide

        print "<div id=\"terreni\" class=\"toggle_t\">"; 

      */
      //echo "<hr>";


      print "<table>";

      print "<tr ><th></th><th></th><th></th><th>foglio</th><th>map</th><th>sub</th><th>qualita</th><th>cl</th><th>superf.</th><th>RD</th><th>RA</th><th>diritto</th><th>quota</th></tr>";
      $num=1;



        while ($table = $risultato->fetchArray(SQLITE3_ASSOC)) 
        {

          $idPart=$table['idPart'];
          $f=$table['fg'];
          $m=$table['map'];

            //$idParticella=$table['idParticella'];
            
            print "<tr style='vertical-align:middle';>";
            print "<td></td>";
            
            print "<td style='text-align:right';><p><small>" . $num .")</small></p></td>";
            
            print "<td style='text-align:left';>" . "<p><a href=\"nctr_results.php?f=$f&m=$m&n=$denomin\" target=\"_self\">visura</a></p>" . "</td>";
            print "<td><b>" . $table['fg'] . "</b></td>";
            print "<td><b>" . $table['map'] . "</b></td>";
            print "<td><b>" . $table['sub'] . "</b></td>";
            //print "<td style='text-align:left';>" . $table['denomin'] . "</td>"; 
            print "<td>" . $table['qualita'] . "</td>";
            print "<td>" . $table['cl'] . "</td>";
            print "<td>" . $table['superf'] . "</td>";
            print "<td>" . $table['RD'] . "</td>";
            print "<td>" . $table['RA'] . "</td>";
            print "<td>" . $table['diritto'] . "</td>";
            print "<td>" . $table['quota'] . "</td>";
            print "</tr>";
            ++$num;
        } 
    print "</table>"; 


    }


print "</div>";
echo "<br>";


#################################################################################################
########################## --> NUOVO CATASTO EDILIZIO URBANO <-- ################################
#################################################################################################

$query_nceu="
       
 select 

UI.IdMutazioneFinale,
tit.idSoggetto,
tit.idImmobile as idImm,
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
       'UI'.'superficie',
       
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
where  tit.idSoggetto = '" . $idSog . "' and II.idImmobile = tit.idImmobile AND categoria!='' and tipoSoggetto= '" . $tipo ."' 

ORDER BY fg ASC, map ASC, sub ASC";

$risultato_nceu = $db->query($query_nceu);
$totRows_nceu=SqliteNumRows($risultato_nceu);
if ($totRows_nceu>0)
      {

          echo "<hr>";
          print "<h4>Nuovo Catasto Edilizio Urbano</h4>";

          if ($totRows_nceu>1)
          {
          	print "<p>estratti da urbano ". $totRows_nceu ." elementi -</p> ";
          	?>
      	     <button onclick="f_nceu()">mostra/nascondi</button>


      	   <?php
          }
          	
            print "<p></p><a href=\"catasto_nceu_2geojson.php?idSog=$idSog&tipo=$tipo&n=$denomin\" target=\"_blank\">mappa...</a></p>";

            print "<div id=\"NCEU\">";



          
          echo "<hr>";
          ?>

        

          <?php

          //print "<span style='font-size:10px'><h4>ricerca per foglio <b>" . per . "</h4></span></b>";

          print "<table>";

          print "<tr ><th></th><th></th><th>foglio</th><th>map</th><th>sub</th><th>zona</th><th>cat</th><th>consistenza</th><th>superf.</th><th>piano</th><th>indirizzo</th><th>rendita</th><th>diritto</th><th>quota</th></tr>";

          ## imposto il limite di tempo, in secondi, per eseguire la query . Di default è 30 s

          set_time_limit(360);

          $num=1;
          while ($table = $risultato_nceu->fetchArray(SQLITE3_ASSOC)) 
            {

              //$idParticella=$table['idParticella'];
              
              print "<tr style='vertical-align:middle';>";
              print "<td style='text-align:right';><p><small>" . $num .")</small></p></td>";
              print "<td style='text-align:left';>" . "<p><a href=\"nceu_results.php?id=". $table['idImm'] . "\" target=\"_self\">visura</a></p>" . "</td>";
              print "<td><b>" . $table['fg'] . "</b></td>";
              print "<td><b>" . $table['map'] . "</b></td>";
              //print "<td style='text-align:left';>" . $table['denomin'] . "</td>"; 
              print "<td><b>" . $table['sub'] . "</b></td>";
              print "<td>" . $table['zona'] . "</td>";
              print "<td>" . $table['categoria'] . "</td>";
              print "<td>" . $table['consistenza'] . "</td>";
              print "<td>" . $table['superficie'] . "</td>";
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
