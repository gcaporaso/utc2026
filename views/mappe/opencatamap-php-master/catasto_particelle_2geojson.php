<?php
#############################################
# Terreni
#############################################

// recupera la variabile idSoggetto
$idsog=htmlspecialchars($_GET["idSog"]);

######################################################### carlo ######################################################
// recupera il nominativo cercato, attraverso idSoggetto della tabella PersonaFisica o PersonaGiuridica
//$nominativo=htmlspecialchars($_GET["nome"]);
$nominativo=$_GET["n"];
####################################################### carlo fine ###################################################

// connette a catasto.db
$conn_c = new SQLite3('catasto.db');

// IMPOSTA LA QUERY per trovare le particelle ai terreni tramite idSoggetto $idsog
$query_c="
select 

tit.idParticella as idPart,
tit.idSoggetto,
tit.idImmobile,


ltrim(pa.foglio,'0') as fg,
ltrim(pa.numero,'0') as map,

CQ.decodifica as qualita,
ltrim(CP.classe,'0') as cl,
CP.ettari*10000+CP.are*100+CP.centiare as sup,
CP.redditoDomEuro as RD,
CP.redditoAgrEuro as RA,
(CD.decodifica||' '||tit.titoloNonCod||' '||tit.quotaNum||'/'||tit.quotaDen) as titolo

From TITOLARITA as tit, PARTICELLA as pa

left join CARATTERISTICHE_PARTICELLA as CP On CP.idParticella=idPart
left join COD_DIRITTO as CD on tit.codDiritto=CD.codice
left join COD_QUALITA as CQ on CP.codQualita=CQ.codice
where  
tit.idSoggetto = '" . $idsog . "' AND 
 
pa.idParticella = idPart

ORDER BY fg ASC, map ASC 
";



## imposto il limite di tempo, in secondi, per eseguire la query . Di default è 30s

set_time_limit(360);



//esegue la query
$rs_c = $conn_c->query($query_c);



### Apre la collezione GeoJson delle feature geometriche da particelle

$geojsonp = array(
    'type'      => 'FeatureCollection',
    'features'  => array()
);

 
$aFogMap = array();  // per costruire la query da passare a leaflet
$cFrom = "cFrom=foglio in (";

################# Estrae gli elementi: fa' un loop su tutti i record di catasto e ricerca in particelle
while ($row_c = $rs_c->fetchArray(SQLITE3_ASSOC)) {
    
	//APRE ->estrae i dati per effettuare la query su spatialite
    // occhio al formato del foglio e mappale che si cerca in Arenzano foglio - '0025' mappale '1100'
	$f=str_pad($row_c['fg'],4,'0',STR_PAD_LEFT) ;
	$m=$row_c['map'];
    #echo $f . " --- " . $m;

    # Connect to SQLite database
    $conn_p = new SQLite3('catasto_cart_4326.sqlite');

    # Build SQL SELECT statement and return the geometry as a GeoJSON element

    # $conn_p->exec("SELECT load_extension('mod_spatialite.so')");
    $conn_p->loadExtension('mod_spatialite.so');


    #$sql = "SELECT foglio, mappale, AsGeoJSON(geometry) as geom FROM Particelle where foglio='0025' and mappale='1100'";


  
    $sqlp = "SELECT foglio, mappale, AsGeoJSON(geometry) as geom FROM particelle where foglio= '" . $f ."' AND mappale= '" . $m ."' order by foglio and mappale";

    $rs_p = $conn_p->query($sqlp);
    if (!$rs_p) {
        echo 'An SQL error occured.\n';
        exit;
    }


    # APRE Loop through rows to build feature arrays
   

    while($row_p = $rs_p->fetchArray()) {
        
        //echo $row_p['foglio'] . " -- " . $row_p['mappale'] . " + ";

 
        $featurep = array
        (
            'type' => 'Feature', 
            'geometry' =>  json_decode($row_p['geom'], true),
            # Pass other attribute columns here
            'properties' => array
                    (
                        
                        'foglio' => $row_p['foglio'],
                        'mappale' => $row_p['mappale'],
                        # qui sotto inseriamo dei dati dalle tighe del db catasto 
                        # carlo magari dati + significativi qui modificando la query sopra sul catasto.db
                        'denominazione' => $nominativo,
                        'titolo' => $row_c['titolo'],
                        'qualita' => $row_c['qualita'],
                        'classe' => $row_c['cl'],
                        'sup' => $row_c['sup'],
                        'RA' => $row_c['RA'],
                        'RD' => $row_c['RD'],
                        'idPart' => $row_c['idPart']
                    
                    )
        );
        # Add feature arrays to feature collection array
        array_push($geojsonp['features'], $featurep);

    }	// CHIUDE Loop through rows to build feature arrays
    $nomefileGeojson = $row_c['idSoggetto'];
    $conn_p->close();
    $conn_p = NULL;

}   // CHIUDE -> estrae i dati per effettuare la query su spatialite

# ritorna il geojosn al consumer per poter interrogare ed aprire in qgis ad esempio 
# ma anche in leaflet 
header('Content-type: application/json');

#echo json_encode($geojsonp, JSON_PRETTY_PRINT);
#echo json_encode($geojson, JSON_NUMERIC_CHECK);


## imposto il limite di tempo, in secondi, per eseguire la query . Di default è 30s

// set_time_limit(420);

# Creiamo la directory se non esiste e scriviamo il risultato nella /tmp/cxc
# di unix 
$curdir=getcwd();
if (!is_dir( $curdir . '/tmp/cxcout')) {
        mkdir( $curdir . '/tmp/cxcout', 0777, true);
}
$filename='/tmp/cxcout/' . $nomefileGeojson . '_NCTR' . '.geojson';
$sysfilename=$curdir . $filename;
$fp = fopen($sysfilename, 'w');
fwrite($fp, json_encode($geojsonp, JSON_PRETTY_PRINT));
fclose($fp);


# closing the DB connection
$conn_c->close();
$conn_c = NULL;

header("Location: ./ll/mappa_ter.html?geojsonfile=" . ".." . $filename);
die();

exit;
?>
