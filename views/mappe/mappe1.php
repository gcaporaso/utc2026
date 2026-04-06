  

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
   integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
   crossorigin=""/>
 <!-- Make sure you put this AFTER Leaflet's CSS -->
 <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
   integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
   crossorigin=""></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.5.0/proj4.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/proj4leaflet/1.0.2/proj4leaflet.js"></script>


<?php
//use yii\data\ActiveDataProvider;
//use sjaakp\locator\Locator;
//use app\models\Tower;
//use sjaakp\locator\tiles\CatastoMap;
/**
 * @var $dataProvider ActiveDataProvider
 */




//$this->registerJsFile(Yii::getAlias('@web') . '/js/custom.js', ['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile('js/leaflet.edgebuffer.js');
$this->registerJsFile('js/L.Control.Opacity.js');
$this->registerJsFile('js/leaflet.shpfile.js');
$this->registerJsFile('js/shp.js');
$this->registerCssFile('css/L.Control.Opacity.css');
$this->registerJsFile('js/leaflet.extra-markers.min.js');
$this->registerCssFile('css/leaflet.extra-markers.min.css');


array_walk_recursive($pratiche, function (&$item, $key) {
    $item = null === $item ? '' : $item;
});
$this->registerJs("


var Bbox_width= 18.99-5.93;
var startResolution = Bbox_width/1024;
var grid_resolution = new Array(30);
for (var i = 0; i < 30; ++i) {
	grid_resolution[i] = startResolution / Math.pow(2, i);
}
var crs_6706 = new L.Proj.CRS('EPSG:6706',
   '+proj=longlat +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +no_defs',
   {
     origin: [0, 0],
     bounds: L.bounds([5.93, 34.76], [18.99, 47.1]),
     resolutions: grid_resolution
   })



var wmsOptions = {
    format:'image/png',
    version:'1.3.0',
    crs: crs_6706,
    transparent:true,
    width:'1024',
    height:'1024',
    maxZoom:22,
    //opacity:0.35,
    edgeBufferTiles: 1,
    layers:'province,CP.CadastralZoning,CP.CadastralParcel,fabbricati,strade,acque,vestizioni',
    };

var googleOptions = {
    transparent:true,
    format:'image/png',
    version:'1.3.0',
    crs: crs_6706,
    width:'1024',
    height:'1024',
    maxZoom:22,
    edgeBufferTiles: 1,
    };

const googleSat = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}',{
    subdomains:['mt0','mt1','mt2','mt3'],
    maptype:'satellite',
     maxZoom:22,
    key:'AIzaSyAveMVLa9nF0wcqnt_4_zrYz_GBgHG6dGY'
});


const googleHybrid = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}',{
    subdomains:['mt0','mt1','mt2','mt3'],
    maptype:'hybrid',
     maxZoom:22,
    key:'AIzaSyAveMVLa9nF0wcqnt_4_zrYz_GBgHG6dGY'
});

const googleTerrain = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}',{
    subdomains:['mt0','mt1','mt2','mt3'],
    maptype:'terrain',
     maxZoom:22,
    key:'AIzaSyAveMVLa9nF0wcqnt_4_zrYz_GBgHG6dGY'
});


const googleRoadMap = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}',{
    subdomains:['mt0','mt1','mt2','mt3'],
    maptype:'roadmap',
     maxZoom:22,
    key:'AIzaSyAveMVLa9nF0wcqnt_4_zrYz_GBgHG6dGY'
});


// Layer mappe catastali
//map.setView([43.731739, 10.401401], 17);
const catasto = L.tileLayer.wms('https://wms.cartografia.agenziaentrate.gov.it/inspire/wms/ows01.php?', wmsOptions);




var map = new L.map('mapid', {
    center: [ 41.1305,14.645538],
    zoom: 17,
    layers: [googleSat,catasto]
    });

var sca = L.icon({
iconUrl: '/var/www/ufficiotecnico/web/img/icons8.png', // posizione
iconSize: [48, 48], // dimensioni
});

<!-- definizione immagine/icona -->
 var pin_red = L.icon({
 iconUrl: 'img/pin_red.svg', // posizione
 iconSize: [48, 48], // dimensioni
 });

var scia = L.icon({
iconUrl: 'img/marker1.png', 
iconSize: [48, 48], // dimensioni
});
// API KEY
// AIzaSyAveMVLa9nF0wcqnt_4_zrYz_GBgHG6dGY
// https://maps.googleapis.com/maps/api/staticmap
// https://www.google.com/maps/embed/v1/MODE?
// https://www.google.com/maps/embed/v1/view
// ?key=YOUR_API_KEY
// &center=-33.8569,151.2152
//  &zoom=18
//  &maptype=satellite
//
//

var shpfile = new L.Shapefile(‘js/prg.ZIP’); 

//shpfile.addTo(map);


var baselayers = {
    'Google Satellite': googleSat,
//    'Google Hybrid': googleHybrid,
//    'Google Terrain': googleTerrain,
//    'Google RoadMap': googleRoadMap,
};

var overlayers = {
    'prg':shpfile,
    'Opacita Catastale': catasto,
};

//LayerControl
L.control.layers(
    baselayers,
    overlayers,
//    {
//    collapsed: false
//    }
).addTo(map);

//OpacityControl
var cop = new L.control.opacity(
    overlayers,
    {
       //label: 'Opacita',
       collapsed: true,
       default:35
    }
).addTo(map);
//cop.setOpacity(0.35);

// var opacitySlider = new L.Control.opacitySlider();
// map.addControl(opacitySlider);
// opacitySlider.setOpacityLayer(overlayers);
// overlayers.setOpacity(0.5);

//// MARKER PRATICHE EDILIZIE
// Marker Icon
// Shepe = 'circle', 'square', 'star', or 'penta'
var ipc = L.ExtraMarkers.icon({
    icon: 'fa-dot-circle-o',
    markerColor: 'blue',
    shape: 'circle',
    prefix: 'fa'
  });
  
var iscia = L.ExtraMarkers.icon({
    icon: 'fa-bookmark',
    markerColor: 'orange',
    shape: 'star',
    prefix: 'fa'
  });

var isca = L.ExtraMarkers.icon({
    icon: 'fa-thumbs-o-up',
    markerColor: 'green',
    iconRotation:20,
    shape: 'penta',
    prefix: 'fa'
  });
  
var icila = L.ExtraMarkers.icon({
    icon: 'fa-compass',
    markerColor: 'green',
    
    shape: 'square',
    prefix: 'fa'
  });



var i;

var jpratiche = " . json_encode($pratiche) . "
    
   // alert(JSON.stringify(jpratiche));
for (i = 0; i < ". ($numero-1) ."; i++) {
    // alert('ID='+jpratiche[i]['id_titolo']);
    var _date =jpratiche[i]['DataTitolo'];
    var dateItems=_date.split('-');
    var DataTitolo= dateItems[2]+'-'+dateItems[1]+'-'+dateItems[0];
    
    var Richiedente='';
    if (parseInt(jpratiche[i]['RegimeGiuridico_id'])==1) {
    Richiedente = jpratiche[i]['Cognome']+ ' ' +jpratiche[i]['Nome']
    } else {
    Richiedente = jpratiche[i]['Denominazione']
    };
    //alert('idtitolo='+parseInt(jpratiche[i]['id_titolo']));
switch(parseInt(jpratiche[i]['id_titolo'])) {
  case 1:
    // CILA
    var msg='<div><p>CILA!<br />.</p></div>'+
    ' <br/>' + Richiedente + 
    ' <br/>' + jpratiche[i]['DescrizioneIntervento'] + '</p></div>';
    L.marker([jpratiche[i]['Latitudine'] ,jpratiche[i]['Longitudine']], {icon: iscia}).bindPopup(msg).addTo(map);
    break;
  case 2:
    // SCIA
    //alert('SCIA');
    var msg='<div><p>SCIA'+
    ' <br/>' + Richiedente + 
    ' <br/>' + jpratiche[i]['DescrizioneIntervento'] + '</p></div>';
    //definizione marker con icona personalizzata
    L.marker([jpratiche[i]['Latitudine'] ,jpratiche[i]['Longitudine']], {icon: iscia}).bindPopup(msg).addTo(map);
    break;
  case 3:
    // SuperSCIA
    var msg='<div><p>SuperSCIA'+
    ' <br/>' + Richiedente + 
    ' <br/>' + jpratiche[i]['DescrizioneIntervento'] + '</p></div>';
    break;
  case 4:
    // PC
    
    var msg='<div><p>Permesso Costruire ' + jpratiche[i]['NumeroTitolo'] + ' del ' + DataTitolo + 
            ' <br/>' + Richiedente + 
            ' <br/>' + jpratiche[i]['DescrizioneIntervento'] + '</p></div>';
    L.marker([jpratiche[i]['Latitudine'] ,jpratiche[i]['Longitudine']], {icon: ipc}).bindPopup(msg).addTo(map);
    break;
  case 5:
    // SCA
    //alert('SCA');
    var msg='<div><p>SCA'+
            ' <br/>' + Richiedente + 
            ' <br/>' + jpratiche[i]['DescrizioneIntervento'] + '</p></div>';
    L.marker([jpratiche[i]['Latitudine'] ,jpratiche[i]['Longitudine']], {icon: isca}).bindPopup(msg).addTo(map);
    break;
  case 6:
    // CIL
    break;
    var msg='<div><p>CIL!<br />This is a nice popup.</p></div>';
  case 7:
    // Autorizzazione Sismica
    break;
    var msg='<div><p>Autorizzazione Sismica!<br />This is a nice popup.</p></div>';
  case 8:
    // Condono Legge 47/85
    break;
    var msg='<div><p>Condono Edilizio Legge 47/85!<br />This is a nice popup.</p></div>';
  case 9:
    // Condono 724/94
    break;
    var msg='<div><p>Condono Edilizio Legge 724/85!<br />This is a nice popup.</p></div>';
  case 10:
    // Autorizzazione Paesaggistica
    break;
    var msg='<div><p>Autorizzazione Paesaggistica</p></div>';
default:
    // code block
    var msg='<div><p>Altro!<br /></p></div>';
    L.marker([jpratiche[i]['Latitudine'] ,jpratiche[i]['Longitudine']]).bindPopup(msg).addTo(map);    
} 
    //var msg='<div><p>Hello world!<br />This is a nice popup.</p></div>';

}



//definizione dell'oggetto popup
var popup = new L.popup({maxWidth: 500});
 
//gestione dell'evento click sulla mappa
map.on('click', function(evt) {
 var coord =evt.latlng;
 //invocata la funzione per generare l'URL della richiesta GetFeatureInfo 
 var gFIurl = getFeatureInfoUrl(map, wmsLayer, coord, crs_6706);
 if (gFIurl) {
	var xhttp;
        //istanza di una richiesta XHTTP
	xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
	if (this.readyState == 4 && this.status == 200) {
	   popup
           .setLatLng(coord)
           .setContent(xhttp.responseText)
           .openOn(map);;
	}
  };
  //bypass CORS policy
  xhttp.open('GET', 'https://cors-anywhere.herokuapp.com/' + gFIurl, true);
  xhttp.send();
 }
})

", yii\web\View::POS_READY);





?>

<div id="mapid" style="height:1024px;z-index:0;"></div>


