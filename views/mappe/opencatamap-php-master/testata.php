<?php

# use SQLite3;

$db = new SQLite3('catasto.db');


## $qdst  --> query dati scaricati da sister tabella FILE relativamente ai TERRENI

$qdst = "
select
descrizione||' '|| substr(dataElaborazione,9,2) ||'/'||substr(dataElaborazione,6,2)||'/'|| substr(dataElaborazione,1,4) as data
from FILE
WHERE 
descrizione like '%Terren%'
";


$ris_ter = $db->query($qdst);


while ($table = $ris_ter->fetchArray(SQLITE3_ASSOC)) {

# $dt -> data di estrazione dei dati relativi ai TERRENI

$dt=$table['data'];


}


## COMUNE

$qcod= "
select distinct
substr(nomeFile,0,5) as cod
from
FILE
";

$ris_cod = $db->query($qcod);

while ($t_c = $ris_cod->fetchArray(SQLITE3_ASSOC)) {

# $dt -> data di estrazione dei dati relativi ai TERRENI

$cod=$t_c['cod'];


}


## NOME DEL COMUNE

$qcom= "
select decodifica as comune

from
COD_COMUNE
WHERE
codice='$cod'
LIMIT 1
";

$ris_com = $db->query($qcom);

while ($t_com = $ris_com->fetchArray(SQLITE3_ASSOC)) {

# $dt -> data di estrazione dei dati relativi ai TERRENI

$com_logo=ucfirst(strtolower($t_com['comune']));
$com=$t_com['comune'];

}

#

## $qdst  --> query dati scaricati da sister tabella FILE relativamente ai FABBRICATI

$qdsf = "
select
descrizione||' '|| substr(dataElaborazione,9,2) ||'/'||substr(dataElaborazione,6,2)||'/'|| substr(dataElaborazione,1,4) as data
from FILE
WHERE 
descrizione like '%Fabb%'
";


$ris_fab = $db->query($qdsf);


while ($table = $ris_fab->fetchArray(SQLITE3_ASSOC)) {

# $dt -> data di estrazione dei dati relativi ai TERRENI

$df=$table['data'];


}


$db->close();


## tablla intestazione
?>
<h4>Ricerche su dati catastali</h4>
<table>
	<tr>
	<td style="text-align: left; width: 40%">
	<?php
	print "<p><a href=http://sister.agenziaentrate.gov.it/Main/index.jsp><small>" . $dt . "</p><p>" . $df . "</small></a></p>";
	?>
	<!-- eventuale dicitura a sinistra
	<p><br><br>pinco pallo</p>
	-->
	</td>
	<td style="text-align: center; width: 20%">
	<?php
	// 045/003 questo è il codice ISTAT, peccato non esista nel db... senno' era fatta
	print "<p><img src=\"https://upload.wikimedia.org/wikipedia/it/thumb/1/13/".$com_logo."-Stemma.png/80px-".$com_logo."-Stemma.png\" alt=\"logo\" width=\"50\"></p>";


print "COMUNE di " . $com;
	?>
	</td>
	<td style="text-align: right; width: 40%">
	<?php
	echo '
<p>  Marco Braida - Carlo A. Nicolini</p>
<p><a href="http://giscarrara/ubucomu/logout.php">logout</a></p>
<!-- eventuale dicitura a destra
<p><br><br>U.O. Progettazione e gestione PRG</p>
-->
';
	?>
	</td>
	</tr>

</table>

<?php


# marco -->
# Se non siamo già nel menu principale
# mostra il link al menu principale 


$currentFile = $_SERVER["PHP_SELF"]; # nel caso della index -> /catasto5c/index.php
$parts = Explode('/', $currentFile); # estratto l'array con / abbiamo catasto5c e index.php

if ($parts[count($parts) - 1] != 'index.php' ) # count($parts)= 3 (perchè?) -1 = 2 1=catasto5c 2=index.php
{
	/* disattivato LOGIN
	if (isset($_COOKIE["login"]))
		{
	*/
		echo '
		<br><a href="index.php"><span style="font-size: 12px">Torna al menu principale</span></a>
		';
	/* disattivato LOGIN
		}
	*/
}
else{
	print "<br>";
}
# <-- marco
#---------------------------------------------- totale dei numeri di righe ritornate

?>

