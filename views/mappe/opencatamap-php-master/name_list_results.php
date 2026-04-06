<html>
   <meta charset="UTF-8"> 
<body onLoad="contaselected('ListaIdForm',null)">  
<head>

<link href="https://fonts.googleapis.com/css?family=Slabo+27px" rel="stylesheet">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" type="text/javascript"></script>
<script src="http://code.jquery.com/ui/1.9.1/jquery-ui.min.js" type="text/javascript"></script>

<link rel="stylesheet" href="cxc.css" type="text/css" media="all">


<script type="text/javascript" language="javascript">// <![CDATA[
function checkAll(formname, elem, modo)
{
  var checkboxes = new Array(); 
  var nck = 0 ; 
  var totnck = 0 ; 
  checkboxes = document[formname].getElementsByTagName('input');
 
  for (var i=0; i<checkboxes.length; i++)  {
    if (checkboxes[i].type == 'checkbox' && checkboxes[i].id=='cbdet' )   {
      totnck=totnck+1;
      if (modo=='toggle') {
          checkboxes[i].checked = !checkboxes[i].checked;
        }
    if (modo=='sel_unsel_all') {

           checkboxes[i].checked = elem.checked;
         }
       if (checkboxes[i].checked) {
        nck=nck+1;
      }
    }
  }
 
 document.getElementById('textsele').textContent = nck + "/" + totnck;
 
 }
 

function contaselected(formname, elem)
{
  var checkboxes = new Array(); 
  var nck = 0 ; 
  var totnck = 0 ; 
  checkboxes = document[formname].getElementsByTagName('input');
   for (var i=0; i<checkboxes.length; i++)  {
    if (checkboxes[i].type == 'checkbox' && checkboxes[i].id=='cbdet' )   {
      totnck=totnck+1;
      if (checkboxes[i].checked) {
        nck=nck+1;
      }
    }
  }
 
 document.getElementById('textsele').textContent = nck + "/" + totnck;

 
 if (totnck<1) {
           document.getElementById('buttonClass').style.visibility = 'hidden';
      } else {
           document.getElementById('buttonClass').style.visibility = 'visible';
   }






 }


// ]]></script>

<script type="text/javascript">
window.onclick = function(e) { 
  // alert(e.target.parentNode);
  if (e.target.id == 'cbdet') {
  contaselected('ListaIdForm',null);
}
};


</script>

</head>

<?php

$n=trim(htmlspecialchars($_GET["n"]));
$ritorna=$n;
include "testata.php";

//print "<br>";


function SqliteNumRows($ris){
    $numRows = 0;
    while($row = $ris->fetchArray()){
        ++$numRows;
    }
    return $numRows;
}

$db = new SQLite3('catasto.db');
//$db->loadExtension('mod_spatialite.so');




$query="
  select 
DISTINCT tit.idSoggetto as idSog,
tit.titoloNonCod as titplus,
tit.tipoSoggetto as tipsog,


CASE
WHEN tit.tipoSoggetto='P'
THEN per.cognome||' '||per.nome
WHEN tit.tipoSoggetto='G'
THEN giu.denominazione
END as denomin,

CC.decodifica as lnasc,

substr('per'.'dataNascita',9,2)||'/'||substr('per'.'dataNascita',6,2)||'/'||substr('per'.'dataNascita',1,4) as dnasc,

CASE
WHEN tit.tipoSoggetto='P'
THEN trim(per.codFiscale)
WHEN tit.tipoSoggetto='G'
THEN trim(giu.codFiscale)
END as codice_fiscale

From titolarita as tit

left join persona_fisica as per On tit.idSoggetto = per.idSoggetto
left join persona_giuridica as giu On tit.idSoggetto = giu.idSoggetto
left join COD_COMUNE as CC On per.luogoNascita = CC.codice
where denomin like '%" . $n . "%' 

GROUP BY idSog

ORDER BY denomin ASC, substr('per'.'dataNascita',1,4) DESC, idSog ASC ";


## imposto il limite di tempo, in secondi, per eseguire la query . Di default è 30 s

set_time_limit(360);


//$risultato = $db->query($query);
$risultato = $db->query($query);

$totRows=SqliteNumRows($risultato);

if ($totRows>0)
{


                echo "<hr>";
                print "<h3>ricerca per</h3><p>" . $n . "</p>";
                // se il risultato e' maggiore di 1
                if ($totRows>1)
                                {
                                print "<p>restituiti " . $totRows . " risultati</p>";
                                }
                echo "<hr>";


                print "<form id=\"ListaIdForm\" name=\"ListaIdForm\" method=\"POST\" action=\"nctr_soggetto_multi.php\">";
                print "<div id=\"checkboxlist\">";

                print "<table>";


print "<tr ><th> 

               
   <p>tutti</p>
<input type=\"checkbox\"   onclick=\"javascript:checkAll('ListaIdForm', this, 'sel_unsel_all');\" href=\"javascript:void();\"  /> <br><div id='textsele'></div>



</th><th style=\"text-align:left\";><b></b></th><th style='text-align:left';>denominazione</th><th style='text-align:left';>luogo di<br>nascita</th><th style='text-align:left';>data di<br>nascita</th><th>codice<br>fiscale</th></tr>";

                while ($table = $risultato->fetchArray(SQLITE3_ASSOC)) 
                {

                    $idSog= $table['idSog'];
                    $tipo=$table['tipsog'];
                    $nomin=$table['denomin'];
                    $denomin=$table['denomin'] ." " . $table['titplus']. " " .$table['lnasc']. " " .$table['dnasc']." ".$table['codice_fiscale'];
                    print "<tr>";

                    print "<td><div><input type=\"checkbox\" id=\"cbdet\" value=\"" . $idSog . "," . $denomin . "\" class=\"chk\">

                    <input method=\"Get\" type=\"hidden\" value=\"". $n ."\" name=\"n\";>
                    </div></td>\n";
                    ?>

                    <?php

                    print "<td>" . "<p><a href=\"nctr_soggetto.php?idSog=$idSog&ricerca=$n&tipo=$tipo&n=$denomin\" target=\"_self\">".$idSog."</a></p>" . "</td>";
                    print "<td style='text-align:left';>" . $nomin . "</td>"; 
                    print "<td style='text-align:left';>" . $table['lnasc'] . "</td>"; 
                    print "<td style='text-align:left';>" . $table['dnasc'] . "</td>"; 
                    print "<td>" . $table['codice_fiscale'] . "</td>";
                     
                    print "</tr>";
                }  



print "</table>"; 

print "</div>";
print '<input type="hidden" id="idSogList" name="idSogList" value="" />';
#print '<input type="hidden" id="nSogList" name="nSogList" value="" />';
print '<input type="submit" value="Cerca i valori selezionati" id="buttonClass"> ';
print ' </div>';



print "</form>";
?>

<script type="text/javascript">

/* if the page has been fully loaded we add two click handlers to the button */
$(document).ready(function () {
  /* Get the checkboxes values based on the class attached to each check box */
  $("#buttonClass").click(function() {
      getValueUsingClass();
  });
  
  /* Get the checkboxes values based on the parent div id */
  $("#buttonParent").click(function() {
      getValueUsingParentTag();
  });
});

function getValueUsingClass(){
  /* declare an checkbox array */
  var chkArray = [];
  
  /* look for all checkboes that have a class 'chk' attached to it and check if it was checked */
  $(".chk:checked").each(function() {
    chkArray.push($(this).val());
    //document.getElementById('idSogList').push($(this).val());
  });
  
  /* we join the array separated by the comma */
  var selected;
  selected = chkArray.join(',') + ",";
  document.getElementById('idSogList').value = selected; 
  

  /* check if there is selected checkboxes, by default the length is 1 as it contains one single comma */
  if(selected.length > 1){
    //alert("valore della variabile document.getElementById('idSogList').value " + document.getElementById('idSogList').value); 
    //open("./nctr_soggetto_multi.php?idSogList=" + selected + "&n=Selezione multivalore")

//  document.ListaIdForm.idSogList.value = chkArray;
 //  document.forms["ListaIdForm"].submit();

    // document.ListaIdForm.idSogList.value = idSogList;
    //open("./nctr_soggetto_multi.php?idSogList" + "&n=Selezione multivalore")
  }else{
    alert("Selezionate almeno una riga tramite il checkbox");
   return false;
  }
}

function getValueUsingParentTag(){
  var chkArray = [];
  
  /* look for all checkboes that have a parent id called 'checkboxlist' attached to it and check if it was checked */
  $("#checkboxlist input:checked").each(function() {
    chkArray.push($(this).val());
  });
  
  /* we join the array separated by the comma */
  var selected;
  selected = chkArray.join(',') + ",";
  
  /* check if there is selected checkboxes, by default the length is 1 as it contains one single comma */
  if(selected.length > 1){
    alert("You have selected " + selected); 
  }else{
    alert("Please at least one of the checkbox"); 
  }
}
</script>

<?php
}ELSE {
?>



<!-- se il risultato della ricerca non è > 0 esegue lo java script... e riporta alla pagina di ricerca -->


<script type="text/javascript">


  function doRedirect() {
    //Genera il link alla pagina che si desidera raggiungere
    location.href = "cerca_nominativo.php"
  }
  
  //Questo è il messaggio di attesa di redirect in corso…
  document.write("Nessun risultato... sarai riportato alla maschera di ricerca");
  
  //Fa partire il redirect dopo 10 secondi da quando l'intermprete JavaScript ha rilevato la funzione
  //Fa partire il redirect dopo 2 secondi da quando l'intermprete JavaScript ha rilevato la funzione
  window.setTimeout("doRedirect()", 2000);

</script>


<!-- FINE java -->

<?php
}
echo "<br>";
?>



</body>
</html>
