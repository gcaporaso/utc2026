<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
   integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
   crossorigin=""/>
 <!-- Make sure you put this AFTER Leaflet's CSS -->
 <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
   integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
   crossorigin=""></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.5.0/proj4.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/proj4leaflet/1.0.2/proj4leaflet.js"></script>
<script src="https://unpkg.com/geojson-vt@3.2.0/geojson-vt.js"></script>



        <script src="mappe/b542/ctrpunti_quota_0.js"></script>
        <script src="mappe/b542/CTRCURVEDILIVELLOESCARPATE_1.js"></script>
        <script src="mappe/b542/CTRIDROGRAFIA_E_OP_IDRPUNTI_2.js"></script>
        <script src="mappe/b542/CTRIDROGRAFIA_E_OP_IDRPOLIGONI_3.js"></script>
        <script src="mappe/b542/CTRIDROGRAFIA_E_OP_IDRLINEE_4.js"></script>
        <script src="mappe/b542/CTREDIFICIPOLIGONI_5.js"></script>
        <script src="mappe/b542/CTREDIFICILINEE_6.js"></script> 
        <script src="mappe/b542/foglio02_Fabbricati.js"></script> 
        <script src="mappe/b542/foglio02_Particelle.js"></script> 

<?php
// Mappe Campoli del Monte Taburno - B542
//use app\assets\MapAsset;
app\assets\MapAsset::register($this);

$this->registerJsFile('mappe/b542/layerstyle.js');
// Importo e registro mappe 
$this->registerJsFile('mappe/b542/prgjson.js');
$this->registerJsFile('mappe/b542/ptpjson.js');
$this->registerJsFile('mappe/b542/borghiagricoli.js');
$this->registerJsFile('mappe/b542/vincoloidrog.js');
$this->registerJsFile('mappe/b542/pianofrane.js');
$this->registerJsFile('mappe/b542/perimetro_comunale.js');
$this->registerJsFile('mappe/b542/foglio01_Fabbricati.js');
$this->registerJsFile('mappe/b542/foglio01_Particelle.js');
$this->registerJsFile('mappe/b542/foglio02_Fabbricati.js');
$this->registerJsFile('mappe/b542/foglio02_Particelle.js');
$this->registerJsFile('mappe/b542/foglio03_Fabbricati.js');
$this->registerJsFile('mappe/b542/foglio03_Particelle.js');
$this->registerJsFile('mappe/b542/foglio04_Fabbricati.js');
$this->registerJsFile('mappe/b542/foglio04_Particelle.js');
$this->registerJsFile('mappe/b542/foglio05_Fabbricati.js');
$this->registerJsFile('mappe/b542/foglio05_Particelle.js');
$this->registerJsFile('mappe/b542/foglio06_Fabbricati.js');
$this->registerJsFile('mappe/b542/foglio06_Particelle.js');
$this->registerJsFile('mappe/b542/foglio07_Fabbricati.js');
$this->registerJsFile('mappe/b542/foglio07_Particelle.js');
$this->registerJsFile('mappe/b542/foglio08_Fabbricati.js');
$this->registerJsFile('mappe/b542/foglio08_Particelle.js');
$this->registerJsFile('mappe/b542/foglio09_Fabbricati.js');
$this->registerJsFile('mappe/b542/foglio09_Particelle.js');
$this->registerJsFile('mappe/b542/foglio10_Fabbricati.js');
$this->registerJsFile('mappe/b542/foglio10_Particelle.js');
$this->registerJsFile('mappe/b542/foglio11_Fabbricati.js');
$this->registerJsFile('mappe/b542/foglio11_Particelle.js');
$this->registerJsFile('mappe/b542/foglio12_Fabbricati.js');
$this->registerJsFile('mappe/b542/foglio12_Particelle.js');

$this->registerCssFile('mappe/b542/catastostyle.css');
//use yii\data\ActiveDataProvider;
//use sjaakp\locator\Locator;
//use app\models\Tower;
//use sjaakp\locator\tiles\CatastoMap;
/**
 * @var $dataProvider ActiveDataProvider
 */




//$this->registerJsFile(Yii::getAlias('@web') . '/js/custom.js', ['depends' => [yii\web\JqueryAsset::className()]]);
// Importo e registro fogli di stile CSS
//$this->registerCssFile('css/L.Control.Opacity.css');
//$this->registerCssFile('css/leaflet.extra-markers.min.css');
//$this->registerCssFile('css/leaflet-measure.css');
//$this->registerCssFile('css/fontawesome-all.min.css');
//$this->registerCssFile('css/qgis2web.css');
//$this->registerCssFile('css/leaflet.css');
//$this->registerCssFile('css/leaflet.contextmenu.min.css');
//$this->registerCssFile('css/gh-pages.css');


//Importo e registro librerie JS
//$this->registerJsFile('js/leaflet.edgebuffer.js');
//$this->registerJsFile('js/L.Control.Opacity.js');
//$this->registerJsFile('js/leaflet.extra-markers.min.js');
//$this->registerJsFile('js/leaflet-measure.js');
//$this->registerJsFile('js/leaflet.contextmenu.min.js');
//$this->registerJsFile('js/getfutureinfo.js');
//$this->registerJsFile('js/leaflet-pip.js');
//$this->registerJsFile('js/catiline.js');
//$this->registerJsFile('js/leaflet.shpfile.js');
//$this->registerJsFile('js/shp.js');
//$this->registerJsFile('js/leaflet.ajax.min.js');


//$this->registerJsFile('leaflet.geojsoncss.min.js');


//$this->registerJsFile('js/ctr_curve.js');
//$this->registerJsFile('js/ctr_punti_quota.js');
//$this->registerJsFile('js/catastale.js');



// MAPPE BASE google
//$this->registerJsFile('js/googlemap.js',['position' => \yii\web\View::POS_HEAD]);
// MAPPE CATASTALI SERVIZIO WMS Agenzia Entrate
$this->registerJsFile('js/wms.map.catasto.js');

// altro codice JS
array_walk_recursive($pratiche, function (&$item, $key) {
    $item = null === $item ? '' : $item;
});
$this->registerJs("



// Layer mappe catastali VETTORIALI Aggiornate al 14-02-2021
//var shpfile = new L.Shapefile('mappe/catastale.zip',{style:function(feature){return {color:'black',fillColor:'red',fillOpacity:.75}}});

//var scurve = {
//    //fillColor: setEthnic2Color(feature.properties.Ethnic2),
//    weight: 1,
//    opacity: 1,
//    color: 'red',
//    dashArray: '3',
//    fillOpacity: 0.7
//  };

////var ctr = L.geoJson({style: scurve});
//var ctr = L.geoJson();
//var curve = 'mappe/b542/morfologia.zip';
//        shp(curve).then(function(data){
//        ctr.addData(data);
//        
//        });
//ctr.setStyle({
//    'color': 'red',
//     'weight': 1});



        
 var ctr_curve = new L.geoJson(json_CTRCURVEDILIVELLOESCARPATE_1, {
            attribution: '',
            interactive: true,
            dataVar: 'json_CTRCURVEDILIVELLOESCARPATE_1',
            //layerName: 'layer_CTRCURVEDILIVELLOESCARPATE_1',
            //pane: 'pane_CTRCURVEDILIVELLOESCARPATE_1',
            //onEachFeature: pop_CTRCURVEDILIVELLOESCARPATE_1,
            style: style_curve,
            onEachFeature: function (feature, layer) {
            label = String(feature.properties.elevation);
            layer.bindTooltip(label, {permanent: true, opacity: 0.5,direction: 'center',
                                      className: 'curvelivello'});
            }
        }); 

var ctr_edifici = new L.geoJson(json_CTREDIFICIPOLIGONI_5, {
            attribution: '',
            interactive: true,
            dataVar: 'json_CTREDIFICIPOLIGONI_5',
            //layerName: 'layer_CTRCURVEDILIVELLOESCARPATE_1',
            //pane: 'pane_CTRCURVEDILIVELLOESCARPATE_1',
            //onEachFeature: pop_CTRCURVEDILIVELLOESCARPATE_1,
            style: style_edifici,
        }); 

var catastale_f1_edifici = new L.geoJson(json_Fabbricati_01, {
            attribution: '',
            interactive: true,
            dataVar: 'json_Fabbricati_01',
            style: style_Fabbricati,
        }); 

var catastale_f1_particelle = new L.geoJson(json_Particelle_01, {
            attribution: '',
            interactive: true,
            dataVar: 'json_Particelle_01',
            style: style_Particelle,
            onEachFeature: function (feature, layer) {
            layer.bindTooltip(feature.properties.NUMERO, {permanent: true, opacity: 0.5,direction: 'center',
                                      className: 'particelle'});
            }
        }); 


var catastale_f2_edifici = new L.geoJson(foglio02_Fabbricati, {
            attribution: '',
            interactive: true,
            dataVar: 'foglio02_Fabbricati',
            //layerName: 'layer_CTRCURVEDILIVELLOESCARPATE_1',
            //pane: 'pane_CTRCURVEDILIVELLOESCARPATE_1',
            //onEachFeature: pop_CTRCURVEDILIVELLOESCARPATE_1,
            style: style_Fabbricati,
            
        }); 


var catastale_f2_particelle = 
          new L.geoJson(null, {
           style: style_Particelle,
           onEachFeature: function (feature, layer) {
            layer.bindTooltip(feature.properties.NUMERO, {permanent: true, opacity: 0.5,direction: 'center',
                                      className: 'particelle'});
            }
           
  });
 catastale_f2_particelle.addData(foglio02_Particelle);        


var catastale_f3_edifici = new L.geoJson(json_Fabbricati_03, {
            attribution: '',
            interactive: true,
            dataVar: 'json_Fabbricati_03',
            style: style_Fabbricati,
            
        }); 

var catastale_f3_particelle = new L.geoJson(json_Particelle_03, {
            attribution: '',
            interactive: true,
            dataVar: 'json_Particelle_03',
            style: style_Particelle,
            onEachFeature: function (feature, layer) {
            layer.bindTooltip(feature.properties.NUMERO, {permanent: true, opacity: 0.5,direction: 'center',
                                      className: 'particelle'});
            }
            
        }); 

var catastale_f4_edifici = new L.geoJson(json_Fabbricati_04, {
            attribution: '',
            interactive: true,
            dataVar: 'json_Fabbricati_04',
            style: style_Fabbricati,
        }); 

var catastale_f4_particelle = new L.geoJson(json_Particelle_04, {
            attribution: '',
            interactive: true,
            dataVar: 'json_Particelle_04',
            style: style_Particelle,
            onEachFeature: function (feature, layer) {
            layer.bindTooltip(feature.properties.NUMERO, {permanent: true, opacity: 0.5,direction: 'center',
                                      className: 'particelle'});
            }
            
        }); 

var catastale_f5_edifici = new L.geoJson(json_Fabbricati_05, {
            attribution: '',
            interactive: true,
            dataVar: 'json_Fabbricati_05',
            style: style_Fabbricati,
        }); 

var catastale_f5_particelle = new L.geoJson(json_Particelle_05, {
            attribution: '',
            interactive: true,
            dataVar: 'json_Particelle_05',
            style: style_Particelle,
            onEachFeature: function (feature, layer) {
            layer.bindTooltip(feature.properties.NUMERO, {permanent: true, opacity: 0.5,direction: 'center',
                                      className: 'particelle'});
            }
            
        }); 

var catastale_f6_edifici = new L.geoJson(json_Fabbricati_06, {
            attribution: '',
            interactive: true,
            dataVar: 'json_Fabbricati_06',
            style: style_Fabbricati,
        }); 

var catastale_f6_particelle = new L.geoJson(json_Particelle_06, {
            attribution: '',
            interactive: true,
            dataVar: 'json_Particelle_06',
            style: style_Particelle,
            onEachFeature: function (feature, layer) {
            layer.bindTooltip(feature.properties.NUMERO, {permanent: true, opacity: 0.5,direction: 'center',
                                      className: 'particelle'});
            }
            
        }); 

var catastale_f7_edifici = new L.geoJson(json_Fabbricati_07, {
            attribution: '',
            interactive: true,
            dataVar: 'json_Fabbricati_07',
            style: style_Fabbricati,
        }); 

var catastale_f7_particelle = new L.geoJson(json_Particelle_07, {
            attribution: '',
            interactive: true,
            dataVar: 'json_Particelle_07',
            style: style_Particelle,
            onEachFeature: function (feature, layer) {
            layer.bindTooltip(feature.properties.NUMERO, {permanent: true, opacity: 0.5,direction: 'center',
                                      className: 'particelle'});
            }
            
        }); 

var catastale_f8_edifici = new L.geoJson(json_Fabbricati_08, {
            attribution: '',
            interactive: true,
            dataVar: 'json_Fabbricati_08',
            style: style_Fabbricati,
            
        }); 

var catastale_f8_particelle = new L.geoJson(json_Particelle_08, {
            attribution: '',
            interactive: true,
            dataVar: 'json_Particelle_08',
            style: style_Particelle,
            onEachFeature: function (feature, layer) {
            layer.bindTooltip(feature.properties.NUMERO, {permanent: true, opacity: 0.5,direction: 'center',
                                      className: 'particelle'});
            }
            
        }); 

var catastale_f9_edifici = new L.geoJson(json_Fabbricati_09, {
            attribution: '',
            interactive: true,
            dataVar: 'json_Fabbricati_09',
            style: style_Fabbricati,
        }); 

var catastale_f9_particelle = new L.geoJson(json_Particelle_09, {
            attribution: '',
            interactive: true,
            dataVar: 'json_Particelle_09',
            style: style_Particelle,
            onEachFeature: function (feature, layer) {
            layer.bindTooltip(feature.properties.NUMERO, {permanent: true, opacity: 0.5,direction: 'center',
                                      className: 'particelle'});
            }
            
        }); 


var catastale_f10_edifici = new L.geoJson(json_Fabbricati_10, {
            attribution: '',
            interactive: true,
            dataVar: 'json_Fabbricati_10',
            style: style_Fabbricati,
        }); 

var catastale_f10_particelle = new L.geoJson(json_Particelle_10, {
            attribution: '',
            interactive: true,
            dataVar: 'json_Particelle_10',
            style: style_Particelle,
            onEachFeature: function (feature, layer) {
            layer.bindTooltip(feature.properties.NUMERO, {permanent: true, opacity: 0.5,direction: 'center',
                                      className: 'particelle'});
            }
            
        }); 

var catastale_f11_edifici = new L.geoJson(json_Fabbricati_11, {
            attribution: '',
            interactive: true,
            dataVar: 'json_Fabbricati_11',
            style: style_Fabbricati,
        }); 

var catastale_f11_particelle = new L.geoJson(json_Particelle_11, {
            attribution: '',
            interactive: true,
            dataVar: 'json_Particelle_11',
            style: style_Particelle,
            onEachFeature: function (feature, layer) {
            layer.bindTooltip(feature.properties.NUMERO, {permanent: true, opacity: 0.5,direction: 'center',
                                      className: 'particelle'});
            }
            
        }); 

var catastale_f12_edifici = new L.geoJson(json_Fabbricati_12, {
            attribution: '',
            interactive: true,
            dataVar: 'json_Fabbricati_12',
            style: style_Fabbricati,
        }); 

var catastale_f12_particelle = new L.geoJson(json_Particelle_12, {
            attribution: '',
            interactive: true,
            dataVar: 'json_Particelle_12',
            style: style_Particelle,
            onEachFeature: function (feature, layer) {
            layer.bindTooltip(feature.properties.NUMERO, {permanent: true, opacity: 0.5,direction: 'center',
                                      className: 'particelle'});
            }
            
        }); 


//var cstyle = {
//color: '#008000',
//weight: 1,
//opacity: 0.6
// }, 
//var foglio2 =  new L.Shapefile('mappe/b542/B542000200');



var map = new L.map('mapid', {
    center: [ 41.1305,14.645538],
    zoom: 17,
    layers: [googleSat,catasto],
    contextmenu: true,
    contextmenuWidth: 140,
    contextmenuItems: [{
	text: 'Info Urbanistiche',
	callback: showInfo
	}, {
	text: 'Info Particella',
	callback: showInfo2
	},{
        text: 'Coordinate Qui',
	callback: showCoordinates
	}, {
	text: 'Centra Qui',
	callback: centerMap
	}, '-', {
	text: 'Zoom -',
	icon: 'img/zoom-in.png',
	callback: zoomIn
	}, {
	text: 'Zoom +',
	icon: 'img/zoom-out.png',
	callback: zoomOut
	}]
    });
    

 
function showCoordinates (e) {
	alert(e.latlng);
}

function centerMap (e) {
	map.panTo(e.latlng);
}

function zoomIn (e) {
	map.zoomIn();
}

function zoomOut (e) {
	map.zoomOut();
}
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

var myStyle = {
    'color': '#ff7800',
    'weight': 5,
    'opacity': 0.65
};
// Piano Regolatore Generale
function onEachFeature(feature, layer) {
    // does this feature have a property named popupContent?
    if (feature.properties && feature.properties.Z) {
        layer.bindPopup(feature.properties.Z);
    }
    if (feature.properties && feature.properties.zonizzazio) {
        layer.bindPopup(feature.properties.zonizzazio);
    }
    if (feature.style) {
    var style = feature.style;
        layer.setStyle(style);
    }
    if (feature.properties && feature.properties.rischio) {
        layer.bindPopup(feature.properties.rischio);
    }
    
}


//var prgLayer = L.geoJSON(geojsonFeature, {
//    onEachFeature: onEachFeature
//});


// Piano Regolatore Generale
//var prgLayer = L.geoJSON();
var prgLayer = L.geoJSON(prg,{onEachFeature: onEachFeature});
//prgLayer.addData(prg);

// Piano Paesaggistico
var ptpLayer = L.geoJSON(ptpjson,{onEachFeature: onEachFeature});
//ptpLayer.addData(ptpjson);

// Borghi agricoli PRG
var borghiLayer = L.geoJSON(json_borghi_agricoli,{onEachFeature: onEachFeature});
//ptpLayer.addData(json_borghi_agricoli);

// Vincolo Idrogeologico 
var vidroLayer = L.geoJSON(vidro);

// CTR 
//var ctrp = L.geoJSON(ctrpunti);
//var ctrc = L.geoJSON(curve);

// Area a Rischio Frane Autorità di Bacino
var pabLayer = L.geoJSON(pfraneab,{onEachFeature: onEachFeature});

// Catastale Vettoriale
// var vcatLayer = L.geoJSON(vcataslate);

// Perimetor
var PerimetroLayer = L.geoJSON(perimetro);

var baselayers = {
    'Google Satellite': googleSat,
//    'Google Hybrid': googleHybrid,
//    'Google Terrain': googleTerrain,
//    'Google RoadMap': googleRoadMap,
};

var layerPermessi = L.layerGroup();
var layerSCIA = L.layerGroup();
var layerSCA = L.layerGroup();
var layerCILA = L.layerGroup(); //.addTo(map);
var layerAltro = L.layerGroup(); //.addTo(map);
var pratiche = L.layerGroup([layerPermessi,layerSCIA,layerSCA,layerAltro]).addTo(map);

var overlayers = {
    'Catastale Raster': catasto,
    'Catastale Foglio 1 Edifici':catastale_f1_edifici,
    'Catastale Foglio 1 Particelle':catastale_f1_particelle,
    'Catastale Foglio 2 Edifici':catastale_f2_edifici,
    'Catastale Foglio 2 Particelle':catastale_f2_particelle,
    'Catastale Foglio 3 Edifici':catastale_f3_edifici,
    'Catastale Foglio 3 Particelle':catastale_f3_particelle,
    'Catastale Foglio 4 Edifici':catastale_f4_edifici,
    'Catastale Foglio 4 Particelle':catastale_f4_particelle,
    'Catastale Foglio 5 Edifici':catastale_f5_edifici,
    'Catastale Foglio 5 Particelle':catastale_f5_particelle,
    'Catastale Foglio 6 Edifici':catastale_f6_edifici,
    'Catastale Foglio 6 Particelle':catastale_f6_particelle,
    'Catastale Foglio 7 Edifici':catastale_f7_edifici,
    'Catastale Foglio 7 Particelle':catastale_f7_particelle,
    'Catastale Foglio 8 Edifici':catastale_f8_edifici,
    'Catastale Foglio 8 Particelle':catastale_f8_particelle,
    'Catastale Foglio 9 Edifici':catastale_f9_edifici,
    'Catastale Foglio 9 Particelle':catastale_f9_particelle,
    'Catastale Foglio 10 Edifici':catastale_f10_edifici,
    'Catastale Foglio 10 Particelle':catastale_f10_particelle,
    'Catastale Foglio 11 Edifici':catastale_f11_edifici,
    'Catastale Foglio 11 Particelle':catastale_f11_particelle,
    'Catastale Foglio 12 Edifici':catastale_f12_edifici,
    'Catastale Foglio 12 Particelle':catastale_f12_particelle,
    'PRG':prgLayer,
    'CTR: Curve Livello':ctr_curve,
    'CTR: Edifici':ctr_edifici,
    'Piano Paesistico':ptpLayer,
    'Borghi':borghiLayer,
    'Vinc. Idrogeologico':vidroLayer,
    'Rischio Frane':pabLayer,
    'Perimetro':PerimetroLayer,
    'Pretiche Edilizie':pratiche,
    //'Permessi Costruire':layerPermessi,
    //'SCIA':layerSCIA,
    //'CILA':layerCILA,
    //'Agibilità (SCA)':layerSCA,
    //'Altre Pratiche':layerAltro,
    //'Catastale Vector':vcatLayer
};

var opacitylayers = {
'Opacità Catastale': catasto,
//'Vector Catastale':shpfile,
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
    //overlayers,
    opacitylayers,
    {
       //label: 'Opacita',
       collapsed: true,
       default:35
    }
).addTo(map);


// Control Misurazioni
var measureControl = new L.Control.Measure({
            position: 'topleft',
            primaryLengthUnit: 'meters',
            secondaryLengthUnit: 'kilometers',
            primaryAreaUnit: 'sqmeters',
            secondaryAreaUnit: 'hectares',
            localization:'it'
        });
measureControl.addTo(map); 
document.getElementsByClassName('leaflet-control-measure-toggle')[0]
        .innerHTML = '';
document.getElementsByClassName('leaflet-control-measure-toggle')[0] 



//cop.setOpacity(0.35);

// var opacitySlider = new L.Control.opacitySlider();
// map.addControl(opacitySlider);
// opacitySlider.setOpacityLayer(overlayers);
// overlayers.setOpacity(0.5);




function showInfo (evt) {
    var coord =evt.latlng;
    var prg = leafletPip.pointInLayer(coord, prgLayer,true);
    var ptp = leafletPip.pointInLayer(coord, ptpLayer,true);
    var borghi = leafletPip.pointInLayer(coord, borghiLayer,true);
    var vidro = leafletPip.pointInLayer(coord, vidroLayer,true);
    var vfrane = leafletPip.pointInLayer(coord, pabLayer,true);
    var inperimetro = leafletPip.pointInLayer(coord, PerimetroLayer,true);
    if (Object.keys(inperimetro).length === 0) {
        alert('Attenzione: '+'\\n'+
              'Il punto che hai selezionato è fuori'+'\\n'+
              'del territorio comunale!'
              );
        
    } else {
        var isborgo='SI';
        var isIdro='SI';
        var isFrana='NO';
        if (Object.keys(borghi).length === 0) {
            var isborgo='NO';
        }
        if (Object.keys(vidro).length === 0) {
            var isIdro='NO';
        }
        if (Object.keys(vfrane).length > 0) {
            var isFrana=vfrane[0].feature.properties.rischio;
            //alert(JSON.stringify(vfrane[0].feature.properties,null,4));
        }

        alert('Zona PRG: '+ prg[0].feature.properties.Z+'\\n'+
              'Zona PTP: '+ ptp[0].feature.properties.zonizzazio+'\\n'+
              'BORGHI AGRICOLI: '+isborgo+'\\n'+
              'Vincolo Idrogeologico: '+isIdro+'\\n'+
              'Rischio Frana: '+isFrana
              );
    }
 }


//definizione dell'oggetto popup
var popup = L.popup({maxWidth: 500});
 
//gestione dell'evento click sulla mappa
function showInfo2 (evt) {
 var coord =evt.latlng;
 //alert (coord);
 //invocata la funzione per generare l'URL della richiesta GetFeatureInfo 
 //var gFIurl = getFeatureInfoUrl(map, catasto, coord, crs_6706);
 //var BBOX = str(xx–1)+','+str(yy–1)+','+str(xx+1)+','+str(yy+1)
 //var gFIurl = https://wms.cartografia.agenziaentrate.gov.it/inspire/ajax/ajax.php?op=getDatiOggetto&lon=13.62607730254396&lat=37.327193489720905;
 var gFIurl = 'https://wms.cartografia.agenziaentrate.gov.it/inspire/ajax/ajax.php?op=getDatiOggetto&lon=' + evt.latlng.lng.toString()+'&lat='+ evt.latlng.lat.toString();

 //alert(gFIurl);
 if (gFIurl) {
	var xhttp;
        //istanza di una richiesta XHTTP
	xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
	if (this.readyState == 4 && this.status == 200) {
        //alert(gFIurl);
        var obj = JSON.parse(xhttp.responseText);

        alert('Foglio = ' + obj.FOGLIO + ' Particella = ' + obj.NUM_PART);
//	   popup
//           .setLatLng(coord)
//           .setContent(xhttp.responseText)
//           .openOn(map);
	}
  };
  //bypass CORS policy
  // xhttp.open('GET', 'https://cors-anywhere.herokuapp.com/' + gFIurl, true);
  xhttp.open('GET', gFIurl, true);
  xhttp.send();
 }
}










////////////////////////////////////////
//// MARKER PRATICHE EDILIZIE /////////
///////////////////////////////////////
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

var icond = L.ExtraMarkers.icon({
    icon: 'fa-dot-circle-o',
    markerColor: 'cian',
    shape: 'circle',
    prefix: 'fa'
  });


var i;

var jpratiche = " . json_encode($pratiche) . "
    
//   alert(JSON.stringify(jpratiche));
for (i = 0; i < ". ($numero-1) ."; i++) {
    // alert('ID='+jpratiche[i]['id_titolo']);
    
    if (!(jpratiche[i].length && jpratiche[i].val().length === 0 )) {
           // alert('OK length');
            var _date =jpratiche[i]['DataTitolo'];
            var _dateP =jpratiche[i]['DataProtocollo'];
            var dateItems=_date.split('-');
            var dateItemsP=_dateP.split('-');
            var DataTitolo= dateItems[2]+'-'+dateItems[1]+'-'+dateItems[0];
            var DataProtocollo= dateItemsP[2]+'-'+dateItemsP[1]+'-'+dateItemsP[0];
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
            var msg='<div><p>CILA!'+
            ' <br/>' + Richiedente + 
            ' <br/>' + 'Prot. ' + jpratiche[i]['NumeroProtocollo'] + ' del '+ DataProtocollo +
            ' <br/>' + jpratiche[i]['DescrizioneIntervento'] + '</p></div>';
            markerCILA=L.marker([jpratiche[i]['Latitudine'] ,jpratiche[i]['Longitudine']], {icon: icila}).bindPopup(msg);
            layerCILA.addLayer(markerCILA);
            break;
          case 2:
            // SCIA
            //alert('SCIA');
            var msg='<div><p>SCIA'+
            ' <br/>' + Richiedente + 
            ' <br/>' + 'Prot. ' + jpratiche[i]['NumeroProtocollo'] + ' del '+ DataProtocollo +
            ' <br/>' + jpratiche[i]['DescrizioneIntervento'] + '</p></div>';
            //definizione marker con icona personalizzata
            markerSCIA=L.marker([jpratiche[i]['Latitudine'] ,jpratiche[i]['Longitudine']], {icon: iscia}).bindPopup(msg);
            layerSCIA.addLayer(markerSCIA);
            break;
          case 3:
            // SuperSCIA
            var msg='<div><p>SuperSCIA'+
            ' <br/>' + Richiedente + 
            ' <br/>' + 'Prot. ' + jpratiche[i]['NumeroProtocollo'] + ' del '+ DataProtocollo +
            ' <br/>' + jpratiche[i]['DescrizioneIntervento'] + '</p></div>';
            markerAltro=L.marker([jpratiche[i]['Latitudine'] ,jpratiche[i]['Longitudine']], {icon: iscia}).bindPopup(msg);
            layerAltro.addLayer(markerAltro);
            break;
            
          case 4:
            // PC

            var msg='<div><p>Permesso Costruire ' + jpratiche[i]['NumeroTitolo'] + ' del ' + DataTitolo + 
                    ' <br/>' + Richiedente + 
                    ' <br/>' + jpratiche[i]['DescrizioneIntervento'] + '</p></div>';
            //L.marker([jpratiche[i]['Latitudine'] ,jpratiche[i]['Longitudine']], {icon: ipc}).bindPopup(msg).addTo(map);
            markerPC = L.marker([jpratiche[i]['Latitudine'] ,jpratiche[i]['Longitudine']], {icon: ipc}).bindPopup(msg);
            layerPermessi.addLayer(markerPC);
            break;
          case 5:
            // SCA
            //alert('SCA');
            var msg='<div><p>SCA'+
                    ' <br/>' + Richiedente + 
                    ' <br/>' + 'Prot. ' + jpratiche[i]['NumeroProtocollo'] + ' del '+ DataProtocollo +
                    ' <br/>' + jpratiche[i]['DescrizioneIntervento'] + '</p></div>';
            markerSCA = L.marker([jpratiche[i]['Latitudine'] ,jpratiche[i]['Longitudine']], {icon: isca}).bindPopup(msg);
            layerSCA.addLayer(markerSCA);
            break;
          case 6:
            // CIL
            break;
            var msg='<div><p>CIL!' +
            ' <br/>' + Richiedente + 
            ' <br/>' + 'Prot. ' + jpratiche[i]['NumeroProtocollo'] + ' del '+ DataProtocollo +
            ' <br/>' + jpratiche[i]['DescrizioneIntervento'] + '</p></div>';
            markerCIL = L.marker([jpratiche[i]['Latitudine'] ,jpratiche[i]['Longitudine']], {icon: isca}).bindPopup(msg);
            layerAltro.addLayer(markerCIL);

          case 7:
            // Autorizzazione Sismica
            break;
            var msg='<div><p>Autorizzazione Sismica!<br />This is a nice popup.</p></div>';
          case 8:
            // Condono Legge 47/85
            break;
            var msg='<div><p>Condono Edilizio Legge 47/85!<br />.</p></div>';
            markerCondono= L.marker([jpratiche[i]['Latitudine'] ,jpratiche[i]['Longitudine']], {icon: icond}).bindPopup(msg);
            layerAltro.addLayer(markerCondono);

          case 9:
            // Condono 724/94
            
            var msg='<div><p>Condono Edilizio Legge 724/94!'+
            ' <br/>' + 'Permesso Costruire ' + jpratiche[i]['NumeroTitolo'] + ' del ' + DataTitolo +
            ' <br/>' + Richiedente + 
            ' <br/>' + jpratiche[i]['DescrizioneIntervento'] + '</p></div>';
            markerCN=L.marker([jpratiche[i]['Latitudine'] ,jpratiche[i]['Longitudine']], {icon: icond}).bindPopup(msg);
            layerAltro.addLayer(markerCN);
            break;
          case 10:
            // Autorizzazione Paesaggistica
            break;
            var msg='<div><p>Autorizzazione Paesaggistica</p></div>';
        default:
            // code block
            var msg='<div><p>Altro!<br /></p></div>';
            markerA = L.marker([jpratiche[i]['Latitudine'] ,jpratiche[i]['Longitudine']]).bindPopup(msg);    
            layerAltro.addLayer(markerA);
        } 
            //var msg='<div><p>Hello world!<br />This is a nice popup.</p></div>';
    }        
}



//definizione dell'oggetto popup
// var popup = new L.popup({maxWidth: 500});
 
//gestione dell'evento click sulla mappa
//map.on('click', function(evt) {
// var coord =evt.latlng;
// //invocata la funzione per generare l'URL della richiesta GetFeatureInfo 
// var gFIurl = getFeatureInfoUrl(map, wmsLayer, coord, crs_6706);
// if (gFIurl) {
//	var xhttp;
//        //istanza di una richiesta XHTTP
//	xhttp = new XMLHttpRequest();
//	xhttp.onreadystatechange = function() {
//	if (this.readyState == 4 && this.status == 200) {
//	   popup
//           .setLatLng(coord)
//           .setContent(xhttp.responseText)
//           .openOn(map);;
//	}
//  };
//  //bypass CORS policy
//  // xhttp.open('GET', 'https://cors-anywhere.herokuapp.com/' + gFIurl, true);
//  xhttp.open('GET', gFIurl, true);
//  xhttp.send();
// }
//})

", yii\web\View::POS_READY);





?>

<div id="mapid" style="height:1024px;z-index:0;"></div>


