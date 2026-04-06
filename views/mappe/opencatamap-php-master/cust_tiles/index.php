<!DOCTYPE html>
<html lang="it">
	 <meta charset="UTF-8"> 
<head>
<link rel="stylesheet" href="cxc.css" type="text/css" media="all">
</head>

<body>

<?php
if ( !file_exists("catasto.db") or !file_exists( "catasto_cart_4326.sqlite") ) {
   echo "<pre>
Attenzione...

Questa procedura non e' installata completamente mancano i files catasto.db e/o catasto_cart_4326.sqlite...

Cosa bisogna fare:

- creare il file catasto.db che e' un file che deve essere generato dal menu backup della procedura Visualizzazione forniture catastali 
dopo aver installato l'applicativo disponibile a questo link:<a href=http://www.agenziaentrate.gov.it/wps/content/nsilib/nsi/home/cosadevifare/consultare+dati+catastali+e+ipotecari/scambio+dati+catastali+e+cartografici+con+enti+o+pa/portale+per+i+comuni/software+compilazione+lettura+dati+portale+comuni/software+visualizzazione+forniture+dati+catastali/indice+software+visualizzazione+forniture+dati+catastali>Visualizzazione forniture dati catastali</a> 
e dopo aver importato, i files di fornitura degli archivi che si devono richiedere al sito dell'Agenzia delle Entrate al 
<a href=http://www.agenziaentrate.gov.it/wps/content/Nsilib/Nsi/Home/Servizi+online/serv_terr/con_reg/Portale+per+i+Comuni/>Portale dei comuni</a>

- il file catasto_cart_4326 va' creato con qgis dopo aver effettuato la richiesta dei files cartografici 
sempre dal sito sopra citato, dopo aver importato la cartografia: 
si puo' convenientemente installare ed usare qgis per eseguire questa operazione.
I files cartografici catastali CXF si possono importare tramite il plugin di qgis cxf_in 
Questa parte cartografica va' poi esportata in formato spatialite in un file che chiameremo catasto_cart_4326.sqlite 
Nota Bene: l'export deve essere effettuato creando un file catasto_cart_4326.sqlite ed il livello esportato deve essere inserito con il nome particelle

Questi files devono essere resi disponibili nella cartella della procedura con gli appropriati permessi di lettura

Per maggiori istruzioni sulla corretta installazione di questo programma... si veda: .....
</pre>
";

exit;
}

include "testata.php";
?>


<h3>MENU PRINCIPALE</h3>
<hr>
<br>
<a href="cerca_nctr.php"><button style="width: 200px">Cerca per particella</button></a>
<br><br>
<a href="cerca_nominativo.php"><button style="width: 200px">Cerca per nominativo</button></a>
<br>
<br>
<a href="cerca_indirizzo.php"><button style="width: 200px">Cerca per indirizzo</button></a>
<br>
<br>
</body>
</html>
