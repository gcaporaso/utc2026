<!--<head>-->
<!--    <script type="module" src="js/maingis.js"></script>-->
    <link rel="stylesheet" href="node_modules/ol/ol.css" type="text/css">
    <link rel="stylesheet" href="node_modules/ol-popup/src/ol-popup.css" type="text/css">
    <link rel="stylesheet" href="node_modules/ol-geocoder/dist/ol-geocoder.min.css" type="text/css">
    <link rel="stylesheet" href="node_modules/ol-layerswitcher/dist/ol-layerswitcher.css" type="text/css">
<!--     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/openlayers/4.4.2/ol.css" type="text/css">-->
<!--    <link rel="stylesheet" href="https://unpkg.com/ol-popup@2.0.0/src/ol-popup.css">-->
<!--    <link href="https://cdn.jsdelivr.net/npm/ol-geocoder@latest/dist/ol-geocoder.min.css" rel="stylesheet">-->
<!--    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ol3-layerswitcher@1.1.2/src/ol3-layerswitcher.css" />-->
    <style type="text/css">
        html,
        body,
        #map {
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        body {
            font: 1em/1.5 BlinkMacSystemFont, -apple-system, "Segoe UI", "Roboto", "Oxygen", "Ubuntu", "Cantarell", "Fira Sans", "Droid Sans", "Helvetica Neue", "Helvetica", "Arial", sans-serif;
            color: #222;
            font-weight: 400;
        }

        .hidden {
            display: none;
        }

        #map {
            position: absolute;
            z-index: 1;
            top: 0;
            bottom: 0;
        }

        .layer-switcher {
            top: 3em;
            bottom:unset;
        }

    </style>
<!--    <script src="https://unpkg.com/openlayers@4.4.2"></script>-->
    
    <script src="node_modules/ol/dist/ol.js"></script>
    <script type="module" src="node_modules/ol/control.js"></script>
    <script type="module" src="node_modules/ol/source/TileWMS.js"></script>
    <script type="module" src="node_modules/ol/source/ImageWMS.js"></script>
<!--    <script src="https://unpkg.com/ol-popup@2.0.0"></script>-->
    <script src="node_modules/ol-popup/dist/ol-popup.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.4.4/proj4.js"></script>
    <script type="module" src="node_modules/ol/proj/proj4.js"></script>
<!--    <script src="https://cdn.jsdelivr.net/npm/ol-geocoder"></script>-->
    <script src="node_modules/ol-geocoder/dist/ol-geocoder.js"></script>
<!--    <script src="https://cdn.jsdelivr.net/npm/ol3-layerswitcher@1.1.2/src/ol3-layerswitcher.js"></script>-->
    <script src="node_modules/ol/dist/ol-layerswitcher.js"></script>
    <script type="module" src="node_modules/ol/Overlay.js"></script>
    <script src="mappe/b542/prgjson.js"></script>
<!--</head>-->
<script type="module">
//  import ImageWMS from '/node_modules/ol/source/ImageWMS.js';  
//  import Map from '/node_modules/ol/Map.js';  
//  import View from '/node_modules/ol/View.js';
//  import {Image, Tile} from '/node_modules/ol/layer.js';
//  import Projection from '/node_modules/ol/proj/Projection.js';
//  //import proj4 from 'js/proj4.js';
//  import {ScaleLine, defaults as defaultControls} from '/node_modules/ol/control.js';
//  import {fromLonLat} from '/node_modules/ol/proj.js';
  import {register} from "./node_modules/ol/proj/proj4.js";
  

</script>

<!--<script src="node_modules/ol/dist/ol.js"></script>
<script src="node_modules/ol/dist/ol-layerswitcher.js"></script>
<script src="js/proj4.js"></script>
<script src="node_modules/ol/proj.js"></script>;
<script src="node_modules/ol/proj/proj4.js"></script>;
<script src="/node_modules/ol/dist/ol.js"></script>
<script src="/node_modules/ol/dist/ol-layerswitcher.js"></script>
<script src="/js/proj4.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.4.4/proj4.js"></script>-->
<?php
// Mappe Campoli del Monte Taburno - B542
//use app\assets\MapAsset;
//app\assets\GisAsset::register($this);
//$this->registerJsFile('node_modules/ol/dist/ol.js');
////$this->registerJsFile('node_modules/ol/Map.js');
////$this->registerJsFile('node_modules/ol/View.js');
////$this->registerJsFile('node_modules/ol/ol/layer.js');
////$this->registerJsFile('node_modules/ol/proj/Projection.js');
////$this->registerJsFile('node_modules/ol/control.js');
//$this->registerJsFile('node_modules/ol/dist/ol-layerswitcher.js');
////$this->registerJsFile('node_modules/ol/proj.js');
////$this->registerJsFile('node_modules/ol/proj/proj4.js');
//$this->registerCssFile('node_modules/ol/dist/ol-layerswitcher.css');
//$this->registerJsFile('js/proj4.js');
//$this->registerJsFile('js/wmsol.js');

//$this->registerJsFile('mappe/b542/layerstyle.js');
//// MAPPE CATASTALI SERVIZIO WMS Agenzia Entrate
//$this->registerJsFile('js/wms.map.catasto.js');
//$this->registerJsFile('mappe/b542/function_collection.js');
//
//
//// CSS Style
//$this->registerCssFile('mappe/b542/catastostyle.css');




// MAPPE BASE google
//$this->registerJsFile('js/googlemap.js',['position' => \yii\web\View::POS_HEAD]);

// altro codice JS

//$this->registerJsFile('mappe/b542/prgjson.js');




?>
<!--<div style="height:100vh;margin-top:0">-->
    <div id="mapgis" style="height:91vh;z-index:0;"></div>

    
<!--        </div>-->
<!--        <div style="margin-left:15px;margin-top:0px;display: inline-block;"><p id="_messaggio" style="color:red"></p></div>-->

    
<!--</div>  -->

<script>
    
var ETRS89Extent = [2, 33, 19, 48];
var CampoliExtent = [14.63, 41.12, 14.65, 41.13];
var UTMext = [-718644.6433171634562314, 3651293.4671865100972354, 873787.7128406565170735, 5398650.8619926832616329];
//var ETRS89Extent = [5.93, 40.46, 12.35, 40.57];
//proj4.defs('EPSG:6706', '+proj=longlat +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +no_defs');
proj4.defs('EPSG:6706','+proj=longlat +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +no_defs');


//proj4.defs("EPSG:4258", "+proj=longlat +datum=WGS84 +no_defs");
//proj4.defs("EPSG:3857", "+proj=merc +a=6378137 +b=6378137 +lat_ts=0.0 +lon_0=0.0 +x_0=0.0 +y_0=0 +k=1.0 +units=m +nadgrids=@null +wktext  +no_defs");
// Definizione della proiezione EPSG:25833 con Proj4js
//proj4.defs('EPSG:25833', '+proj=utm +zone=33 +ellps=GRS80 +units=m +no_defs');

// Creazione della proiezione OpenLayers basata su EPSG:25833
var projection25833 = new ol.proj.Projection({
  code: 'EPSG:25833',
  extent: UTMext,
  units: 'm'
});
var ETRS89proj = new ol.proj.Projection({
    code: 'EPSG:6706',
    //extent: ETRS89Extent
    extent: CampoliExtent
});


//var WGS84proj = new ol.proj.Projection({
//    code: 'EPSG:4258',
//    //extent: ETRS89Extent
//    //extent: CampoliExtent;
//});
//ol.proj.proj4.register(proj4);
ol.proj.addProjection(ETRS89proj);
register(proj4);
//ol.proj.addProjection(WGS84proj);
//ol.proj.addProjection(projection25833);
//const myprojection = ol.proj.get(WGS84proj);
//
//if (ETRS89proj) {
//  console.log(`La proiezione ${ETRS89proj} è stata registrata o aggiunta a OpenLayers.`);
//} else {
//  console.log(`La proiezione ${ETRS89proj} non è stata registrata o aggiunta a OpenLayers.`);
//}
//var location = window.location.pathname;
//info = stackinfo()

// module.exports = getCurrentScript;
//const locatione = (function () {
//                if (document.currentScript) {
//                    let link = document.currentScript.src;
//                    let lastIndex = link.lastIndexOf('/');
//                    link = link.substring(0, lastIndex);
//                    return link;
//                }
// })();
////let path = process.cwd();
//console.log(locatione);
//console.log({path: __dirname});
//console.log(getRunningScript()());
//url = window.location
//console.log(url);
//currentdir = new URL(url.pathname.replace( /[^\/]*$/, ''), url.origin);
//console.log(currentdir);
//var path = window.location.hostname;
//var directory = window.location.pathname.replace(/[^\\\/]*$/, '');
//console.log(`directory host ${path}`);
//console.log(`directory corrente ${directory}`);
//register(proj4);

//var Bbox_width= 18.99-5.93;
//var startResolution = Bbox_width/1024;
//var grid_resolution = new Array(30);
//for (var i = 0; i < 30; ++i) {
//	grid_resolution[i] = startResolution / Math.pow(2, i);
//}

//        //var startResolution = ol.extent.getWidth(ETRS89Extent) / 1024;
//        var startResolution = ol.extent.getWidth(ETRS89Extent) / 1024;
//        var resolutions = new Array(22);
//        for (var i = 0, ii = resolutions.length; i < ii; ++i) {
//            resolutions[i] = startResolution / Math.pow(2, i);
//        }
//        var tileGrid = new ol.tilegrid.TileGrid({
//            extent: ETRS89Extent,
//            //extent: CampoliExtent;
//            resolutions: resolutions,//grid_resolution, //resolutions,
//            tileSize: [1024, 1024]
//        });


  //var startResolution = ol.extent.getWidth(ETRS89Extent) / 1024;
        var startResolution = ol.extent.getWidth(CampoliExtent) / 1024;
        var resolutions = new Array(22);
        for (var i = 0, ii = resolutions.length; i < ii; ++i) {
            resolutions[i] = startResolution / Math.pow(2, i);
        }
        var tileGrid = new ol.tilegrid.TileGrid({
            extent: CampoliExtent,
            //extent: CampoliExtent;
            resolutions: resolutions,//grid_resolution, //resolutions,
            tileSize: [1024, 1024]
        });




 //console.log(projection25833.getExtent());
// console.log(window.location.pathname);
// console.log(document.URL);
 
var catasto_attributions = '<br/>cartografia di base:<br/>© ' + '<a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors' +
                           '<br/>cartografia catastale:<br/>© ' + '<a href="https://creativecommons.org/licenses/by-nc-nd/2.0/it/">Agenzia delle Entrate</a>';
                           
//        var catasto_attributions = [
//            new ol.Attribution({
//                html: '<br/>cartografia di base:<br/>© ' + '<a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
//            }),
//            new ol.Attribution({
//                html: '<br/>cartografia catastale:<br/>© ' + '<a href="https://creativecommons.org/licenses/by-nc-nd/2.0/it/">Agenzia delle Entrate</a>'
//            }),
//            new ol.Attribution({
//                html: '<br/>navigatore:<br/> © ' + '<a href="https://tldrlegal.com/license/bsd-2-clause-license-(freebsd)">Enrico Ferreguti</a>'
//            }),
//        ];

//        var parcel_source = new ol.source.ImageWMS({
//            //projection: ETRS89proj,
//            projection: 'EPSG:4258',
//            attributions: catasto_attributions,
//            url: 'https://wms.cartografia.agenziaentrate.gov.it/inspire/wms/ows01.php',
//            params: {
//                'LAYERS': 'CP.CadastralParcel',
////                'TILED': true,
//                'VERSION':'1.3.0'
//            },
//            serverType: 'mapserver'
//        });
//
//        var map_getf = new ol.Map({
//            layers: [],
//            view: new ol.View({
//                projection: 'EPSG:4258', //ETRS89proj
//            })
//        });

// ****************** PRG
const vectorSource = new ol.source.Vector({
  features: new ol.format.GeoJSON().readFeatures(prg,{dataProjection: 'EPSG:6706', featureProjection: 'EPSG:3857'})
});

//vectorSource.addFeature(new Feature(new Circle([5e6, 7e6], 1e6)));

const prgLayer = new ol.layer.Vector({
  declutter: true,
  source: vectorSource,
  interactive: true
  //style: styleFunction,
});




        var map = new ol.Map({
          //  maxResolution: 1000,
            layers: [
//                new ol.layer.Tile({
//                    source: new ol.source.OSM({
//                        attributions: catasto_attributions,
//                        //projection: ETRS89proj,
//                    }),
//                    opacity: 0.3
//                }),
                new ol.layer.Tile({
                  source: new ol.source.XYZ({
                    projection: 'EPSG:6706',  
                    //url: 'http://mt{0-3}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}'
                      url: 'http://mt{0-3}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}&key=AIzaSyAveMVLa9nF0wcqnt_4_zrYz_GBgHG6dGY',
                      attributions: '© Google',
                  }),
                  //projection: 'EPSG:3857'
                }),
//                new ol.layer.Group({
//                    title: 'Altre Mappe',
//                    fold: 'open',
//                    layers:[
//                            new ol.layer.Tile({
//                                source: new ol.source.OSM({
//                        //attributions: catasto_attributions,
//                        //projection: ETRS89proj,
//                                }),
//                            opacity: 0.5
//                            }),
//                    ]
//                }),
                /// ******* GRUPPO LAYER CATASTO ******************
//                 new ol.layer.Group({
//                    title: 'Catasto',
//                    fold: 'open',
//                    layers:[
//                        new ol.layer.Tile({
//                            title: 'province',
//                            opacity:0.5,
//                            visible: false,
//                            source: new ol.source.TileWMS({
//                                //projection: ETRS89proj,
//                                //projection: projection25833,
//                                attributions: catasto_attributions,
//                                url: 'https://wms.cartografia.agenziaentrate.gov.it/inspire/wms/ows01.php',
//                                params: {
//                                    'LAYERS': 'province',
//                                    'VERSION':'1.3.0',
//                                    'SRS':'EPSG:6706'
//                                },
//                                serverType: 'geoserver',
//                                ratio: 96,
//                                resolutions: 96,
//                                tileGrid: tileGrid
//                            })
//                            //projection: 'EPSG:3857'
//
//                        }),
//                        new ol.layer.Tile({
//                            title: 'fogli',
//                            opacity:0.5,
//                            visible: true,
//                            source: new ol.source.TileWMS({
//                                //projection: ETRS89proj,
//                                //projection: projection25833,
//                                attributions: catasto_attributions,
//                                url: 'https://wms.cartografia.agenziaentrate.gov.it/inspire/wms/ows01.php',
//                                params: {
//                                    'LAYERS': 'CP.CadastralZoning',
//                                    'TILED': true,
//                                    'VERSION':'1.1.1',
//                                    'SRS':'EPSG:6706'
//                                },
////                                serverType: 'mapserver',
//                                tileGrid: tileGrid
//
//                            })
//                            //projection: 'EPSG:3857'
//                        }),
//                        new ol.layer.Tile({
//                            title: 'strade',
//                            opacity:0.3,
//                            visible: false,
//                            source: new ol.source.TileWMS({
//                                //projection: ETRS89proj,
//                                //projection: projection25833,
//                                attributions: catasto_attributions,
//                                url: 'https://wms.cartografia.agenziaentrate.gov.it/inspire/wms/ows01.php',
//                                params: {
//                                    'LAYERS': 'strade',
//                                    'VERSION':'1.1.1',
//                                    'SRS':'EPSG:6706'
//                                },
////                                serverType: 'mapserver',
//                                tileGrid: tileGrid
//                            })
//                            //projection: 'EPSG:3857'
//                        }),
//                        new ol.layer.Tile({
//                            title: 'acque',
//                            opacity: 0.700000,
//                            visible: false,
//                            source: new ol.source.TileWMS({
//                                //projection: ETRS89proj,
//                                //projection: projection25833,
//                                attributions: catasto_attributions,
//                                url: 'https://wms.cartografia.agenziaentrate.gov.it/inspire/wms/ows01.php',
//                                params: {
//                                    'LAYERS': 'acque',
//                                    'TRASPARENT':false,
//                                    'TILED': true,
//                                    'VERSION':'1.1.1',
//                                    'SRS':'EPSG:6706'
//                                   // 'WIDTH':256,
//                                   // 'HEIGHT':256
//                                },
////                                serverType: 'mapserver',
//                                tileGrid: tileGrid
//                            })
//                            //projection: 'EPSG:3857'
//                        }),
                        new ol.layer.Tile({
                            title: 'particelle',
                            visible: true,
                            source: new ol.source.TileWMS({
                                //projection: ETRS89proj,
                                //projection: projection25833,
                                //projection: 'EPSG:3857',
                                projection: 'EPSG:4258',
        //                        crossOrigin: 'anonymous',
                                attributions: catasto_attributions,
        //                      url: 'https://wms.cartografia.agenziaentrate.gov.it/inspire/wms/ows01.php',
                                url: 'http://192.168.1.185:8082/geoserver/PROVE/wms',
                                params: {
                                    'LAYERS': 'PROVE:CP.CadastralParcel',
                                    'TILED':true,
                                    //'CRS':'EPSG:4258',
                                    //'BBOX':[33, 2, 48, 19],
                                    'FORMAT': 'image/png',
                                    'STYLES':'default',
//                                    'TRASPARENT':false,
        //                            'TILED': true,
                                    'VERSION':'1.3.0',
                                    'WIDTH':1024,
                                    'HEIGHT':1024
                                },
                                serverType: 'geoserver',
//                                FORMAT: 'image/png',
                               // hidpi:96,
                                tileGrid: tileGrid

                            }),
                            //opacity:0.5
                            //projection: 'EPSG:3857'
                        }),
//                        new ol.layer.Tile({
//                            title: 'fabbricati',
//                            visible: true,
//                            source: new ol.source.TileWMS({
//                                //projection: ETRS89proj,
//                                //projection: projection25833,
//        //                        crossOrigin: 'anonymous',
//                                attributions: catasto_attributions,
//                                url: 'https://wms.cartografia.agenziaentrate.gov.it/inspire/wms/ows01.php',
//                                params: {
//                                    'LAYERS': 'fabbricati',
//                                    'TILED': true,
//                                    'VERSION':'1.1.1',
//                                    'SRS':'EPSG:6706'
//                                },
////                                serverType: 'mapserver',
//                                tileGrid: tileGrid
//                            }),
//                            //projection: 'EPSG:3857',
//                           opacity:0.3 
//                        }),
//                        new ol.layer.Tile({
//                            title: 'vestizioni',
//                            visible: true,
//                            opacity:0.3,
//                            source: new ol.source.TileWMS({
//                               //projection: ETRS89proj,
//                                //projection: projection25833,
//
//        //                        crossOrigin: 'anonymous',
//                                attributions: catasto_attributions,
//                                url: 'https://wms.cartografia.agenziaentrate.gov.it/inspire/wms/ows01.php',
//                                params: {
//                                    'LAYERS': 'vestizioni',
//                                     'VERSION':'1.1.1',
//                                     'SRS':'EPSG:6706'
//                                    
//                                },
////                                serverType: 'mapserver',
//                                tileGrid: tileGrid
//                            })
//        //                    projection: 'EPSG:3857'
//                        }),
                         
//                     ]
//                 }),
                 /// ******* GRUPPO LAYER PRG ******************
//                   new ol.layer.Group({
//                    title: 'PRG',
//                    fold: 'close',
//                    layers:[
//                        new ol.layer.Vector({
//                            title:'zonizzazione',
//                            //CRS:'EPSG:6706',
//                            sorce:vectorSource
//                        })
//                    ]    
//                 })   
//               new ol.layer.Image({
//                    title: 'Comuni',
//                    visible: true,
//                    opacity:0.7,
//                    source: new ol.source.ImageWMS({
//                        //projection: ETRS89proj,
//                        projection: 'EPSG:4326',
//                        
////                        crossOrigin: 'anonymous',
//                        //attributions: catasto_attributions,
//                        url: 'https://sit2.regione.campania.it/geoserver/RegioneCampania.Cartografia.Base/wms',
//                        params: {
//                            'LAYERS': 'mappa_cartografia_base',
////                            'VERSION':'1.3.0'
//                    
//                        },
//                        serverType: 'geoserver',
//                        //tileGrid: tileGrid
//                    }),
////                    projection: 'EPSG:3857'
//                }), 
            ],
            target: 'mapgis',
            controls: ol.control.defaults.defaults({
                //new ol.control.ScaleLine(),
                zoom: true,
                attribution: true,
                rotate: false
//                new ol.control.MousePosition(),
//                new ol.control.OverviewMap()
            }),
            //projetion: 'EPSG:6706',
            view: new ol.View({
                //projection: ETRS89proj,
                //projection: 'EPSG:3857',
                projection: 'EPSG:6706',
                //center: ol.proj.transform(ol.extent.getCenter(ETRS89Extent), ETRS89proj, 'EPSG:3857'),
                //center: ol.proj.transform(ol.extent.getCenter([14.63, 41.127, 14.66, 41.13]), ETRS89proj, 'EPSG:6706'),
                //center: ol.proj.transform([14,63,41,27], 'EPSG:6706', 'EPSG:3857'),
                //center: ol.proj.transform([1630287.34,5031747.17], ETRS89proj, 'EPSG:3857'),
                //center: [1630287.34,5031747.17],
                center: [14,63,41,27],
                zoom: 16
            })
        });

//        var geocoder = new Geocoder('nominatim', {
//            provider: 'photon',
//            lang: 'it-IT',
//            placeholder: 'Inserisci indirizzo ...',
//            limit: 5,
//            keepOpen: true
//        });
//        map.addControl(geocoder);

        var addr_popup = new ol.Overlay.Popup();
        map.addOverlay(addr_popup);
        var getf_popup = new ol.Overlay.Popup();
        map.addOverlay(getf_popup);

//        geocoder.on('addresschosen', function(evt) {
//            map.getView().setCenter(evt.coordinate)
//            map.getView().setZoom(18)
//            window.setTimeout(function() {
//                addr_popup.show(evt.coordinate, evt.address.formatted);
//            }, 3000);
//        });


// var map = window.map; // sostituire "map" con il nome dell'oggetto olMap nella tua applicazione
//    var layers = map.getLayers().getArray();
//    layers.forEach(function(layer) {
//        //console.log(layer.getProperties());
//        console.log(layer.getSource().getProjection().getCode());
//    });


        var mousePositionControl = new ol.control.MousePosition({
          coordinateFormat: ol.coordinate.createStringXY(4),
          projection: ETRS89proj,
          target: document.getElementById('mouse-position'),
          undefinedHTML: '&nbsp;'
        });
        map.addControl(mousePositionControl);
        var layerSwitcher = new ol.control.LayerSwitcher({
            tipLabel: 'Legenda' // Optional label for button
        });
        map.addControl(layerSwitcher);
        //map.zoomToMaxExtent();

//        map.on('singleclick', function(evt) {
//            //map_getf.getView().setCenter(ol.proj.transform(map.getView().getCenter(), 'EPSG:3857', ETRS89proj));
//            //map_getf.getView().setZoom(map.getView().getZoom());
//            
//            //var viewResolution = map.getView().getResolution();
//            var url = parcel_source.getFeatureInfoUrl(
//                ol.proj.transform(evt.coordinate, 'EPSG:3857', ETRS89proj), map.getView().getResolution(), ETRS89proj.getCode(), {
//                    'INFO_FORMAT': 'text/html',
//                    'QUERY_LAYERS': ['CP.CadastralParcel']
//                });
//            if (url) {
//                var xhttp;
//                xhttp = new XMLHttpRequest();
//                xhttp.onreadystatechange = function() {
//                    if (this.readyState == 4 && this.status == 200) {
//                        getf_popup.show(evt.coordinate, xhttp.responseText);;
//                    }
//                };
//                //bypass cors policy
//                xhttp.open("GET", "https://cors-anywhere.herokuapp.com/" + url, true);
//                xhttp.send();
//
//            }
//        });








//        var map = new ol.Map({
//            layers: [
//                new ol.layer.Tile({
//                    source: new ol.source.OSM({attributions: catasto_attributions})
//                }),
//                new ol.layer.Image({
//                    title: 'province',
//                    visible: false,
//                    source: new ol.source.ImageWMS({
//                        //projection: ETRS89proj,
//                        projection: 'EPSG:6706',
//                        crossOrigin: 'anonymous',
//                        attributions: catasto_attributions,
//                        url: 'https://wms.cartografia.agenziaentrate.gov.it/inspire/wms/ows01.php',
//                        params: {
//                            'LAYERS': 'province',
//                            'VERSION':'1.3',
//                            'FORMAT': 'image/jpeg',
//                            //'CRS': crs_6706,
//                        },
//                        serverType: 'mapserver',
//                        tileGrid: tileGrid
//                    })
//                }),
//                new ol.layer.Image({
//                    title: 'province',
//                    visible: true,
//                    source: new ol.source.ImageWMS({
//                        projection: ETRS89proj,
//                        attributions: catasto_attributions,
//                        url: 'https://wms.cartografia.agenziaentrate.gov.it/inspire/wms/ows01.php',
//                        params: {
//                            'LAYERS': 'province',
//                            'VERSION':'1.3.0'
//                        },
//                        serverType: 'mapserver',
//                        tileGrid: tileGrid
//                    })
//                }),
//                new ol.layer.Image({
//                    title: 'fogli',
//                    visible: true,
//                    source: new ol.source.ImageWMS({
//                        projection: ETRS89proj,
//                        attributions: catasto_attributions,
//                        url: 'https://wms.cartografia.agenziaentrate.gov.it/inspire/wms/ows01.php',
//                        params: {
//                            'LAYERS': 'CP.CadastralZoning',
//                            'VERSION':'1.3.0'
//                        },
//                        serverType: 'mapserver'
//                    })
//                }),
//                new ol.layer.Image({
//                    title: 'strade',
//                    visible: false,
//                    source: new ol.source.ImageWMS({
//                        projection: ETRS89proj,
//                        attributions: catasto_attributions,
//                        url: 'https://wms.cartografia.agenziaentrate.gov.it/inspire/wms/ows01.php',
//                        params: {
//                            'LAYERS': 'strade',
//                            'VERSION':'1.3.0'
//                        },
//                        serverType: 'mapserver'
//                    })
//                }),
//                new ol.layer.Tile({
//                    title: 'acque',
//                    visible: false,
//                    source: new ol.source.TileWMS({
//                        projection: ETRS89proj,
//                        attributions: catasto_attributions,
//                        url: 'https://wms.cartografia.agenziaentrate.gov.it/inspire/wms/ows01.php',
//                        params: {
//                            'LAYERS': 'acque',
//                            'VERSION':'1.3.0'
//                        },
//                        serverType: 'mapserver'
//                    })
//                }),
//                new ol.layer.Tile({
//                    title: 'particelle',
//                    extent: ETRS89Extent,
//                    visible: true,
//                    source: new ol.source.TileWMS({
//                        projection: ETRS89proj,
//                        attributions: catasto_attributions,
//                        url: 'https://wms.cartografia.agenziaentrate.gov.it/inspire/wms/ows01.php',
//                        params: {
//                            'LAYERS': 'CP.CadastralParcel',
//                            'VERSION':'1.3.0'
//                        },
//                        serverType: 'mapserver'
//                    })
//                }),
//                new ol.layer.Tile({
//                    title: 'fabbricati',
//                    visible: true,
//                    source: new ol.source.TileWMS({
//                        projection: ETRS89proj,
//                        attributions: catasto_attributions,
//                        url: 'https://wms.cartografia.agenziaentrate.gov.it/inspire/wms/ows01.php',
//                        params: {
//                            'LAYERS': 'fabbricati',
//                            'VERSION':'1.3.0'
//                        },
//                        serverType: 'mapserver'
//                    })
//                }),
//                new ol.layer.Tile({
//                    title: 'vestizioni',
//                    visible: true,
//                    source: new ol.source.TileWMS({
//                        projection: ETRS89proj,
//                        attributions: catasto_attributions,
//                        url: 'https://wms.cartografia.agenziaentrate.gov.it/inspire/wms/ows01.php',
//                        params: {
//                            'LAYERS': 'vestizioni',
//                            'VERSION':'1.3.0'
//                        },
//                        serverType: 'mapserver'
//                    })
//                })
//            ],
//            target: 'mapgis',
////            controls: ol.control.defaults().extend([
////                //new ol.control.ScaleLine(),
////                new ol.control.MousePosition(),
////                new ol.control.OverviewMap()
////            ]),
//            //controls: ol.control.defaultControls().extend([new ScaleLine(), new MousePosition]),
//            //controls: defaultControls.extend([new ScaleLine()]),
//            view: new ol.View({
////                center: ol.proj.transform(ol.extent.getCenter(ETRS89Extent), ETRS89proj, 'EPSG:3857'),
////                zoom: 6
//                center: ol.proj.fromLonLat([1638014.651, 5015561.131]),
//                zoom: 16   
//            })
//        });
//
////        var geocoder = new Geocoder('nominatim', {
////            provider: 'photon',
////            lang: 'it-IT',
////            placeholder: 'Inserisci indirizzo ...',
////            limit: 5,
////            keepOpen: true
////        });
////        map.addControl(geocoder);
//
////        var addr_popup = new ol.Overlay.Popup();
////        map.addOverlay(addr_popup);
////        var getf_popup = new ol.Overlay.Popup();
////        map.addOverlay(getf_popup);
//
////        geocoder.on('addresschosen', function(evt) {
////            map.getView().setCenter(evt.coordinate)
////            map.getView().setZoom(18)
////            window.setTimeout(function() {
////                addr_popup.show(evt.coordinate, evt.address.formatted);
////            }, 3000);
////        });
//        
//        var layerSwitcher = new ol.control.LayerSwitcher({
//            tipLabel: 'Legenda' // Optional label for button
//        });
//        map.addControl(layerSwitcher);
//
//        map.on('singleclick', function(evt) {
//            map_getf.getView().setCenter(ol.proj.transform(map.getView().getCenter(), 'EPSG:3857', ETRS89proj));
//            map_getf.getView().setZoom(map.getView().getZoom());
//            var viewResolution = map_getf.getView().getResolution();
//            var url = parcel_source.getGetFeatureInfoUrl(
//                ol.proj.transform(evt.coordinate, 'EPSG:3857', ETRS89proj), map_getf.getView().getResolution(), ETRS89proj.getCode(), {
//                    'INFO_FORMAT': 'text/html',
//                    'QUERY_LAYERS': ['CP.CadastralParcel']
//                });
//            if (url) {
//                var xhttp;
//                xhttp = new XMLHttpRequest();
//                xhttp.onreadystatechange = function() {
//                    if (this.readyState == 4 && this.status == 200) {
//                        getf_popup.show(evt.coordinate, xhttp.responseText);;
//                    }
//                };
//                //bypass cors policy
//                xhttp.open("GET", "https://cors-anywhere.herokuapp.com/" + url, true);
//                xhttp.send();
//
//            }
//        });
</script>



