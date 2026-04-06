<?php
#################################
# Catasto urbano
################################

// recupera la variabile idSoggetto
//$idsog=trim($_GET["idSog"]);

################################### carlo ######################################################
// recupera il nominativo cercato, attraverso $idsoggetto della tabella PersonaFisica o PersonaGiuridica
//$nominativo=htmlspecialchars($_GET["nome"]);
//$nominativo=$_GET["n"];
################################### carlo fine ###################################################

// connette a catasto.db
$conn_c = new SQLite3('catasto.db');

// IMPOSTA LA QUERY per trovare le particelle al NCTR tramite idSoggetto $$idsog

// * modificata query -> eliminato il raggruppamento per id_immobile che escludeva le particelle graffate nella 
// creazione del geojson


$query_nceu="
select 

ltrim(II.foglio,'0') as fg,
ltrim(II.numero,'0') as map,
ltrim(II.subalterno,'0') as sub,
tit.idImmobile as idImmobile,
UI.IdMutazioneFinale,
tit.idSoggetto,
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

        (CD.decodifica ||' '||tit.quotaNum||'/'||tit.quotaDen) as titolo

        From IDENTIFICATIVI_IMMOBILIARI as II

            left join INDIRIZZI on INDIRIZZI.idImmobile=II.idImmobile
            left join titolarita as tit On II.idImmobile = tit.idImmobile
            left join COD_DIRITTO as CD on CD.codice = tit.codDiritto
            left join UNITA_IMMOBILIARI as UI on UI.idImmobile=tit.idImmobile
            left join COD_TOPONIMO as CT on INDIRIZZI.toponimo=CT.codice
        where  II.idImmobile = tit.idImmobile AND categoria='A01'  
        GROUP BY II.idImmobile
        ORDER BY fg ASC, map ASC, sub ASC";


## imposto il limite di tempo, in secondi, per eseguire la query . Di default è 30 s
## impostato il limite di tempo ad 1 h -> 3600 sec
set_time_limit(3600);

//esegue la query
$rs_c = $conn_c->query($query_nceu);

### Apre la collezione GeoJson delle feature geometriche da particelle

$geojsonp = array(
    'type'      => 'FeatureCollection',
    'features'  => array()
);


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

    $sqlp = "SELECT foglio, mappale, AsGeoJSON(geometry) as geom FROM Particelle where foglio= '" . $f ."' AND mappale= '" . $m ."' ";

    $rs_p = $conn_p->query($sqlp);
    if (!$rs_p) {   
        echo 'An SQL error occured.\n';
        exit;
    }


    # APRE Loop through rows to build feature arrays

    while($row_p = $rs_p->fetchArray()) {
        
        //echo $row_p['foglio'] . " -- " . $row_p['mappale'];

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
                        'sub' => $row_c['sub'],
                        //'denominazione' => $nominativo,
                        'titolo' => $row_c['titolo'],
                        'zona' => $row_c['zona'],
                        'cat' => $row_c['categoria'],
                        'cons' => $row_c['consistenza'],
                        'indirizzo' => $row_c['indirizzo'],
                        'piano' => $row_c['piano'],
                        'rendita' => $row_c['rendita'],
                        'idImm' => $row_c['idImmobile']
                    
                    )
        );
        # Add feature arrays to feature collection array
        array_push($geojsonp['features'], $featurep);

    }   // CHIUDE Loop through rows to build feature arrays
    $nomefileGeojson = 'catA1' ;
    $conn_p->close();
    $conn_p = NULL;



}   // CHIUDE -> estrae i dati per effettuare la query su spatialite

# ritorna il geojosn al consumer per poter interrogare ed aprire in qgis ad esempio 
# ma anche in leaflet 
header('Content-type: application/json');

#echo json_encode($geojsonp, JSON_PRETTY_PRINT);
#echo json_encode($geojson, JSON_NUMERIC_CHECK);

# Creiamo la directory se non esiste e scriviamo il risultato nella /tmp/cxc
# di unix 
$curdir=getcwd();
if (!is_dir( $curdir . '/tmp/cxcout')) {
        mkdir( $curdir . '/tmp/cxcout', 0777, true);
}
$filename='/tmp/cxcout/' . $nomefileGeojson . '_NCEU' . '.geojson';
$sysfilename=$curdir . $filename;
$fh = fopen($sysfilename, 'w');
fwrite($fh, json_encode($geojsonp, JSON_PRETTY_PRINT));
fclose($fh);


# closing the DB connection
$conn_c->close();
$conn_c = NULL;

header("Location: ./ll/mappa_urb.html?geojsonfile=" . ".." . $filename);
die();

exit;


?>
