<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

header("Access-Control-Allow-Origin: http://192.168.1.100");

//echo "Il Toro ritrova Zaza anche se sta miagolando nei bassifondi della serie A";
?>
<!--<head>-->
<!--    <script type="module" src="js/maingis.js"></script>-->
    <link rel="stylesheet" href="node_modules/ol/ol.css" type="text/css">
<!--    <link rel="stylesheet" href="node_modules/ol-popup/src/ol-popup.css" type="text/css">-->
    <link rel="stylesheet" href="node_modules/ol-geocoder/dist/ol-geocoder.min.css" type="text/css">
    <link rel="stylesheet" href="node_modules/ol/dist/ol-layerswitcher.css" type="text/css">
    <link rel="stylesheet" href="node_modules/ol-contextmenu/dist/ol-contextmenu.css" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- ol-ext -->
    <link rel="stylesheet" href="node_modules/ol-ext/dist/ol-ext.min.css" />
    
    <style type="text/css">
        html,
        body,
/*        #image {
        background-color: #eee;
        padding: 1em;
        clear: both;
        display: inline-block;
        margin: 1em 0;
      }*/
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
/*        .ol-popup {
        position: absolute;
        background-color: white;
        box-shadow: 0 1px 4px rgba(0,0,0,0.2);
        padding: 15px;
        border-radius: 10px;
        border: 1px solid #cccccc;
        bottom: 12px;
        left: -50px;
        min-width: 280px;
      }
      .ol-popup:after, .ol-popup:before {
        top: 100%;
        border: solid transparent;
        content: " ";
        height: 0;
        width: 0;
        position: absolute;
        pointer-events: none;
      }
      .ol-popup:after {
        border-top-color: white;
        border-width: 10px;
        left: 48px;
        margin-left: -10px;
      }
      .ol-popup:before {
        border-top-color: #cccccc;
        border-width: 11px;
        left: 48px;
        margin-left: -11px;
      }
      .ol-popup-closer {
        text-decoration: none;
        position: absolute;
        top: 2px;
        right: 8px;
      }
      .ol-popup-closer:after {
        content: "✖";
      }*/
      
    .ol-overlay.menu {
      width: 5%;
      background: #fff;
      color: #333;
      box-shadow: 0px 0px 5px #000;
      padding: 0.5em;
      -webkit-transition: all 0.25s;
      transition: all 0.25s;
    }
     style the close box 
    .ol-overlay.menu .ol-closebox {
      right:1em;  
/*      left: 1em;*/
      top: 0.5em;
    }
    .ol-overlay.menu .ol-closebox:before {
      content:"\f0c9";
      font-family:FontAwesome;
    }
    #menu {
      padding-top: 1.5em;
      font-size: 0.9em;
      right: 0.5em;
    }
     menu button 
    .ol-control.menu {
      top: 0.5em;
/*      left: 0.5em;*/
      
      right: 0.5em;
    }
/*    .ol-zoom {
      left: auto;
      right: 0.5em;
    }
    .ol-rotate {
      right: 3em;
    }
    .ol-touch .ol-rotate {
      right: 3.5em;
    }*/
    
    .ol-overlay img {
      max-width: 90%;
    }
    .data,
    .data p {
      margin:0;
      text-align: center;
      font-size:0.9em;
    }
    
    .ol-custom{
    z-index: 1000;
    top: 0.5em;
    right: .5em;
  
    }    
        
    
    
     .hideOpacity .layerswitcher-opacity {
      display:none;
    }
    .hideOpacity .ol-layerswitcher .layerup {
      height: 1.5em;
    }
    .showPercent .layerSwitcher .ol-layerswitcher .layerswitcher-opacity-label {
      display: block;
    }

    .ol-header > div {
      width:100%; 
    }
    .toggleVisibility {
      padding-left: 1.6em;
      cursor: pointer;
      border-bottom: 2px solid #369;
      margin-bottom: 0.5em; 
    }
    .toggleVisibility:before {
      background-color: #fff;
      border: 2px solid #369;
      box-sizing: border-box;
      content: "";
      display: block;
      height: 1.2em;
      left: 0.1em;
      margin: 0;
      position: absolute;
      width: 1.2em;
    }
    .toggleVisibility.show:before {
      background: #369;
    }
    
    </style>
<!--    <script src="https://unpkg.com/openlayers@4.4.2"></script>-->
    
    <script src="node_modules/ol/dist/ol.js"></script>
    <script type="module" src="node_modules/ol/control.js"></script>
    <script type="module" src="node_modules/ol/source/TileWMS.js"></script>
    <script type="module" src="node_modules/ol/source/ImageWMS.js"></script>
<!--    <script src="https://unpkg.com/ol-popup@2.0.0"></script>-->
<!--    <script src="node_modules/ol-popup/dist/ol-popup.js"></script>-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.4.4/proj4.js"></script>
    <script type="module" src="node_modules/ol/proj/proj4.js"></script>
<!--    <script src="https://cdn.jsdelivr.net/npm/ol-geocoder"></script>-->
    <script src="node_modules/ol-geocoder/dist/ol-geocoder.js"></script>
<!--    <script src="https://cdn.jsdelivr.net/npm/ol3-layerswitcher@1.1.2/src/ol3-layerswitcher.js"></script>-->
    <script src="node_modules/ol/dist/ol-layerswitcher.js"></script>
    <script type="module" src="node_modules/ol/Overlay.js"></script>
    <script type="module" src="node_modules/ol/layer/Group.js"></script>
<!--    <script type="module" src="node_modules/ol/loadingstrategy.js"></script>-->
    <script  src="node_modules/ol-contextmenu/dist/ol-contextmenu.iife.js"></script>
    <script src="https://cdn.polyfill.io/v2/polyfill.min.js?features=requestAnimationFrame,Element.prototype.classList,URL,Object.assign"></script>
  
    <script type="text/javascript" src="node_modules/ol-ext/dist/ol-ext.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
    <!-- https://github.com/MrRio/jsPDF -->
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<!--    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>-->
<!--    <script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script>-->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>
<!--    <script type="module" src="node_modules/jspdf/dist/jspdf.es.min.js"></script>-->
    
  <!-- filesaver-js -->
  
  <script type="module" src="node_modules/file-saver/dist/FileSaver.min.js"></script>
  
  
<!--     Pointer events polyfill for old browsers, see https://caniuse.com/#feat=pointer 
    <script src="https://unpkg.com/elm-pep"></script>-->
<!--    <script src="https://epsg.io/4326"></script>-->
<!--    <script type="module" src="node_modules/ol-contextmenu/dist/ol-contextmenu.js"></script>-->
    <script src="mappe/b542/prgjson.js"></script>
    <script src="js/mapfunctions.js"></script>
    <?php 
    $this->registerJsFile('mappe/b542/function_collection.js'); 
    $this->registerJsFile('mappe/b542/ptpjson.js');
    ?>
<!--</head>-->
<!--<script type="module">
  import {register} from "node_modules/ol/proj/proj4.js";
</script>-->

    <div id="mapgis" style="height:91vh;z-index:0;"></div>
    
     <?php 
    $form = ActiveForm::begin([
        'action' => [''],
        'id' => 'urb-form',
        'options' => [
            'class' => 'particelle-form'
        ]
    ]); 
    ?>
        <div style="margin-left:15px;margin-top:5px!important;display: inline-block;">    
            <?php // 'template' => '{label}{input}{error}{hint}',?>
        <?= $form->field($particella, 'foglio',['options' => ['class' => 'form-group form-inline'],])
       ->textInput(['style' => 'width: 40px; height:30px; margin-left: 5px;margin-top: 5px;']);
        ?>
        </div>
        <div style="margin-left:5px;margin-top:5px;display: inline-block;">    
        <?= $form->field($particella, 'particella',[
        'options' => ['class' => 'form-group form-inline'],])
        ->textInput(['style' => 'width: 60px; height:30px; margin-left: 5px;margin-top: 5px;']);
        ?>
        </div>
        <div style="margin-left:15px;margin-top:5px;display: inline-block;"> 
        <?php 
        //Html::a('<span class="fas fa-search"></span>', Url::toRoute(['edilizia/allegati','idpratica'=>$model->edilizia_id]),
//                      [  
//                         'title' => 'Gestione',
//                          
//                         //'data-confirm' => "Sei sicuro di volere eliminare questa istanza?",
//                         //'data-method' => 'post',
//                         //'data-pjax' => 0
//                      ]);   ?>
         <?php 
         // BUTTON PER CERCARE E ZOMMARE SULLA PARTICELLA SPECIFICATA
         //Html::submitButton('<i class="fas fa-search"></i>', ['class' => "btn btn-success _cercap",'style' => 'margin-top: 5px;height:30px;','id' => 'btnCerca']);
         //echo Html::submitButton(Icon::show('search'), ['class' => 'btn btn-primary _cercap btn', 'name' => 'action', 'value' => 'save']);
         echo Html::a('<i class="fas fa-search fa-sm" ></i>',[''], ['title'=>'Zoom alla particella indicata', 
             'class'=>'btn btn-primary _cercap','style' => 'margin-top: 2px;width:30px;height:30px;padding-left:7px!important;padding-top:0!important','id' => 'btnCerca',
             ])
     
         ?>
         <?php 
         // BUTTON PER STAMPARE LA SCHEDA URBANISTICA DELLA PARTICELLA
         //Html::submitButton('<i class="fas fa-search"></i>', ['class' => "btn btn-success _cercap",'style' => 'margin-top: 5px;height:30px;','id' => 'btnCerca']);
         //echo Html::submitButton(Icon::show('search'), ['class' => 'btn btn-primary _cercap btn', 'name' => 'action', 'value' => 'save']);
         echo Html::a('<i class="fas fa-file-pdf fa-sm" ></i>',$url=false, ['title'=>'Scheda urbanistica della particella indicata', 
             'class'=>'btn btn-success _surb','style' => 'margin-top: 2px;margin-left:4px;width:30px;height:30px;padding-left:8px!important;padding-top:0!important','id' => 'btnUrb',
             ])
     
         ?>
         </div>

    <?php ActiveForm::end(); ?>
    
    
    
  
    
<!--    <div id="info">&nbsp;</div>-->
    <div id="popup" class="ol-popup">
      <a href="#" id="popup-closer" class="ol-popup-closer"></a>
      <div id="popup-content"></div>
    </div>
<!-- Content of the menu -->
<!--  <div id="menu">
    <h1>Menu</h1>
    <p style="border-bottom:1px solid #999;">
      <i>ol.control.Overlay</i> can be used to display a menu or information on the top of the map.
    </p>
    <div class="data"></div>
  </div>-->

<script>
/**
 * Elements that make up the popup.
 */
const container = document.getElementById('popup');
const content = document.getElementById('popup-content');
const closer = document.getElementById('popup-closer');    

/**
 * Create an overlay to anchor the popup to the map.
 */
const overlay = new ol.Overlay({
  element: container,
  autoPan: {
    animation: {
      duration: 250,
    },
  },
});
/**
 * Add a click handler to hide the popup.
 * @return {boolean} Don't follow the href.
 */
closer.onclick = function () {
  overlay.setPosition(undefined);
  closer.blur();
  return false;
};
let popover;
function disposePopover() {
  if (popover) {
    popover.dispose();
    popover = undefined;
  }
}

//$(document).ready(function(){
//  $("button").click(function(){
//    $("#test").hide();
//  });
//});
// window.load = function () {
//        document.getElementsByClassName('ol-ext-prin-dialog')[0].style.width = '80%';
//        alert('caricato');
//     };



var ETRS89Extent = [2, 33, 19, 48];
var CampoliExtent = [14.63, 41.12, 14.65, 41.13];
const PRGExtent = [469698.95452470373, 4548824.074860521, 473097.1801707037, 4554094.021768384];
var UTMext = [-718644.6433171634562314, 3651293.4671865100972354, 873787.7128406565170735, 5398650.8619926832616329];
proj4.defs('EPSG:6706','+proj=longlat +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +no_defs');

//// Creazione della proiezione OpenLayers basata su EPSG:25833
//var projection25833 = new ol.proj.Projection({
//  code: 'EPSG:25833',
//  extent: UTMext,
//  units: 'm'
//});
var ETRS89proj = new ol.proj.Projection({
    code: 'EPSG:6706',
    extent: ETRS89Extent
    //extent: CampoliExtent
});


//proj4.defs('EPSG:32633','+proj=utm +zone=33 +datum=WGS84 +units=m +no_defs +type=crs');
proj4.defs('EPSG:32633', '+proj=utm +zone=33 +datum=WGS84 +units=m +no_defs');
//
var PRGproj = new ol.proj.Projection({
    code: 'EPSG:32633',
    extent: PRGExtent
    //extent: CampoliExtent
});
ol.proj.addProjection(PRGproj);

//ol.proj.addProjection(ETRS89proj);
ol.proj.proj4.register(proj4);
// console.log(proj4);

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


        var prgResolution = ol.extent.getWidth(PRGExtent) / 1024;
        var presolutions = new Array(22);
        for (var i = presolutions.length; i>=0; i--) {
        //for (var i = 0, ii = presolutions.length; i < ii; ++i) {
            presolutions[i] = prgResolution / Math.pow(2, i);
        }
        var prgGrid = new ol.tilegrid.TileGrid({
            extent: PRGExtent,
            //extent: CampoliExtent;
            resolutions: presolutions,//grid_resolution, //resolutions,
            tileSize: [1024, 1024]
        });




 //console.log(projection25833.getExtent());
// console.log(window.location.pathname);
// console.log(document.URL);
 
var catasto_attributions = '<br/>cartografia catastale:<br/>© ' + '<a href="https://creativecommons.org/licenses/by-nc-nd/2.0/it/">Agenzia delle Entrate</a>';
                           


const wmsSourceParticelle = new ol.source.TileWMS({
                                projection: ETRS89proj,
                                crossOrigin: "Anonymous",
                                attributions: catasto_attributions,
                                url: 'https://wms.cartografia.agenziaentrate.gov.it/inspire/wms/ows01.php?language=ita&',
        //                        url: 'http://192.168.1.185:8082/geoserver/PROVE/wms',
                                params: {
                                    'LAYERS': 'CP.CadastralParcel',
                                    'TILED':true,
                    //                'CRS':'EPSG:6706',
                                    'FORMAT': 'image/png',
                                    'STYLES':'default',
                                 //   'TRASPARENT':true,
        //                            'TILED': true,
                                    'VERSION':'1.1.1',
                                    'WIDTH':1024,
                                    'HEIGHT':1024,
                                },
                                serverType: 'mapserver',
                                
                            });
 
 const mapview = new ol.View({
                //projection: ETRS89proj,
                //center: [14,63,41,27],
                center: [1630207,5031895],
                zoom: 17
            });
 
//vectorSource.addFeature(new Feature(new Circle([5e6, 7e6], 1e6)));
// ****************** PRG
const vectorSource = new ol.source.Vector({
  features: new ol.format.GeoJSON().readFeatures(prg,{dataProjection: 'EPSG:6706', featureProjection: 'EPSG:3857'})
});

const  PrgStyleFunction = function (feature) {
     
//            const colorTable = {
//                "C4": "#e6e600",
//                "B1": "#0000ff",
//                "A": 'rgba(255, 255, 0, 0.37)', //"#CC0000",
//                "P": "#669999"
//            };     
        
        if (feature.get("Z") === "A") {
            return new ol.style.Style({
                stroke: new ol.style.Stroke({
                        lineDash: [1, 4],
                        color: "red",
                        width: 2
                    }),
                fill: new ol.style.Fill({
                        color: 'rgba(255, 255, 0, 0.37)'
                    })
                                
            })
        }
        if (feature.get("Z") === "B") {
            return new ol.style.Style({
                stroke: new ol.style.Stroke({
                        lineDash: [1, 4],
                        color: "white",
                        width: 1
                    }),
                fill: new ol.style.Fill({
                        color: 'rgba(24, 88, 242, 0.37)'
                    })
                                
            })
        }
        
            
    }
    
   const prgLayer = new ol.layer.Vector({
  title: 'PRG Geojson',     
  declutter: true,
  source: vectorSource,
  interactive: true,
  enableOpacitySliders: true,
  visible: false,
  style: PrgStyleFunction
});



//// ********************************************************************
//// LAYER Pratiche Edilizie ********************************************
//// ********************************************************************
//const baseUrl = "http://192.168.1.100:8080/geoserver/wfs?";
// let paramsObj = {
//                    servive: "WFS",
//                    version: "2.0.0",
//                    request: "GetFeature",
//                    outputFormat: "application/json",
//                    crs: "EPSG:3857",
//                    srsName: "EPSG:3857",
//                };
var cilaSource = new ol.source.Vector({
    format: new ol.format.GeoJSON(),
    url: 'http://192.168.1.100:8080/geoserver/Campoli/ows?' +
            'service=WFS&' +
            'version=2.0.0&' +
            'request=GetFeature&' +
            'typeName=Campoli%3ACila&' +
            'maxFeatures=500&' +
            'outputFormat=application%2Fjson' 
});

const iconCilaStyle = new ol.style.Style({
  image: new ol.style.Icon({
    anchor: [0.5, 24],
    anchorXUnits: 'fraction',
    anchorYUnits: 'pixels',
    src: 'immagini/Map-Marker-Ball-Right-Azure-icon.png',
    scale:0.5
  }),
});


const cilalayer = new ol.layer.Vector({
  title: 'CILA',    
  source: cilaSource,
  renderBuffer: 200,
  style: iconCilaStyle
  //zIndex: 5
});

//console.log('Numero Feature Cila =' + cilalayer.getSource().getFeatures().length);
//for (let i = 0; i < cilalayer.getSource().getFeatures().length; i++) { 
//    cilalayer.getSource().getFeatures[i].setStyle(iconCilaStyle);  
//    //console.log(cilaSource.getFeatures[i].getGeometry());
//}
//cilalayer.getSource().on('change', function(evt){
//  const source = evt.target;
//  if (source.getState() === 'ready') {
//    const numFeatures = source.getFeatures().length;
//    console.log("Numero Feature Cila: " + numFeatures);
//  }
//});

var sciaSource = new ol.source.Vector({
    format: new ol.format.GeoJSON(),
    url: 'http://192.168.1.100:8080/geoserver/Campoli/ows?' +
            'service=WFS&' +
            'version=2.0.0&' +
            'request=GetFeature&' +
            'typeName=Campoli%3ASCIA&' +
            'maxFeatures=500&' +
            'outputFormat=application%2Fjson' //+
});

 
const scialayer = new ol.layer.Vector({
  title: 'SCIA',    
  source: sciaSource,
  style: new ol.style.Style({
    image: new ol.style.Icon({
        anchor: [0.4, 24],
        anchorXUnits: 'fraction',
        anchorYUnits: 'pixels',
        src: 'immagini/Icons-Land-Vista-Map-Markers-Map-Marker-Marker-Inside-Pink.24.png',
        scale:0.8
        })
    })
});



//var pcSource = new ol.source.Vector({
//    format: new ol.format.GeoJSON(),
//    url: 'http://192.168.1.100:8080/geoserver/Campoli/ows?' +
//            'service=WFS&' +
//            'version=2.0.0&' +
//            'request=GetFeature&' +
//            'typeName=Campoli%3APermessi&' +
//            'maxFeatures=500&' +
//            'outputFormat=application%2Fjson' 
//});


const pclayer = new ol.layer.Vector({
  title: 'Permessi Costruire',    
  source: new ol.source.Vector({
    format: new ol.format.GeoJSON(),
    url: 'http://192.168.1.100:8080/geoserver/Campoli/ows?' +
            'service=WFS&' +
            'version=2.0.0&' +
            'request=GetFeature&' +
            'typeName=Campoli%3APermessi&' +
            'maxFeatures=500&' +
            'outputFormat=application%2Fjson' 
    }),
  renderBuffer: 200,
  style: new ol.style.Style({
    image: new ol.style.Icon({
        anchor: [0.4, 24],
        anchorXUnits: 'fraction',
        anchorYUnits: 'pixels',
        src: 'immagini/Map-Marker-Push-Pin-1-Right-Chartreuse-icon.png',
        scale:0.5
        })
    })
});

















//scialayer.getSource().forEachFeature(function (feature) {
//     console.log(feature);
// });
//
//cilalayer.getSource().forEachFeature(function (feature) {
//     console.log(feature);
// });







//// generate a GetFeature request
//const featureRequest = new WFS().writeGetFeature({
//  srsName: 'EPSG:3857',
//  featureNS: 'http://openstreemap.org',
//  //featurePrefix: 'osm',
//  featureTypes: ['water_areas'],
//  outputFormat: 'application/json',
//  filter: andFilter(
//    likeFilter('name', 'Mississippi*'),
//    equalToFilter('waterway', 'riverbank')
//  ),
//});
//
//// then post the request and add the received features to a layer
//fetch('https://ahocevar.com/geoserver/wfs', {
//  method: 'POST',
//  body: new XMLSerializer().serializeToString(featureRequest),
//})
//  .then(function (response) {
//    return response.json();
//  })
//  .then(function (json) {
//    const features = new GeoJSON().readFeatures(json);
//    vectorSource.addFeatures(features);
//    map.getView().fit(vectorSource.getExtent());
//  });
//  
  

//const iconSource = new ol.source.Vector({
//  features: new ol.format.GeoJSON().readFeatures(prg,{dataProjection: 'EPSG:6706', featureProjection: 'EPSG:3857'})
//});
//
//const praticheLayer = new ol.layer.Vector({
//  title: 'Pratiche',     
//  declutter: true,
//  source: iconSource,
//  interactive: true,
//  enableOpacitySliders: true,
//  visible: false,
//  //style: pratiche_style
//});
        
        

 
        


        var map = new ol.Map({
          //  maxResolution: 1000,
            layers: [
                 new ol.layer.Group({
                    title: 'mappe google',
                    fold: 'close',
                    layers: [
                            new ol.layer.Tile({
                              title: 'ibrida',
                              baseLayer: true,
                              visible: false,
                              type: 'base',
                              source: new ol.source.XYZ({
                                  crossOrigin : "Anonymous",
                                  //projection: 'EPSG:3857',  
                                //url: 'http://mt{0-3}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}'
                                  //url: 'http://mt{0-3}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}&key=AIzaSyAveMVLa9nF0wcqnt_4_zrYz_GBgHG6dGY',
                                  url: 'http://mt0.google.com/vt/lyrs=y&hl=en&x={x}&y={y}&z={z}&key=AIzaSyAveMVLa9nF0wcqnt_4_zrYz_GBgHG6dGY',
                                  attributions: '© Google',

                              }),
                              //projection: 'EPSG:3857'
                            }),
                            new ol.layer.Tile({
                                title: 'satellite',
                                baseLayer: true,
                                type: 'base',
                              source: new ol.source.XYZ({
                                  crossOrigin : "Anonymous",
                                  //projection: 'EPSG:3857',  
                                //url: 'http://mt{0-3}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}'
                                  //url: 'http://mt{0-3}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}&key=AIzaSyAveMVLa9nF0wcqnt_4_zrYz_GBgHG6dGY',
                                  url: 'http://mt0.google.com/vt/lyrs=s&hl=en&x={x}&y={y}&z={z}&key=AIzaSyAveMVLa9nF0wcqnt_4_zrYz_GBgHG6dGY',
                                  attributions: '© Google',

                              }),
                              //projection: 'EPSG:3857'
                            }),
                    ]
                    }),
//                 new ol.layer.Group({
//                    title: 'catasto',
//                    fold: 'close',
//                    layers: [
//                        new ol.layer.Tile({
//                            title: 'particelle',
//                            visible: false,
//                            source: wmsSourceParticelle,
//                            opacity:0.5
//                        }),
//                        new ol.layer.Tile({
//                            title: 'fabbricati',
//                            visible: false,
//                            source: new ol.source.TileWMS({
//                                projection: ETRS89proj,
//                                crossOrigin : "Anonymous",
//                                attributions: catasto_attributions,
//                                url: 'https://wms.cartografia.agenziaentrate.gov.it/inspire/wms/ows01.php?language=ita&',
//        //                        url: 'http://192.168.1.185:8082/geoserver/PROVE/wms',
//                                params: {
//                                    'LAYERS': 'fabbricati',
//                                    'TILED':true,
//                                //    'CRS':'EPSG:6706',
//                                    'FORMAT': 'image/png',
//                                    'STYLES':'default',
//                                  //  'TRASPARENT':true,
//        //                            'TILED': true,
//                                    'VERSION':'1.1.1',
//                                    'WIDTH':1024,
//                                    'HEIGHT':1024,
//                                },
//                                serverType: 'mapserver',
//                                
//                            }),
//                            opacity:0.6
//                        }),
//                        new ol.layer.Tile({
//                            title: 'vestizioni',
//                            visible: false,
//                            source: new ol.source.TileWMS({
//                                projection: ETRS89proj,
//                                crossOrigin : "Anonymous",
//                                attributions: catasto_attributions,
//                                url: 'https://wms.cartografia.agenziaentrate.gov.it/inspire/wms/ows01.php?language=ita&',
//        //                        url: 'http://192.168.1.185:8082/geoserver/PROVE/wms',
//                                params: {
//                                    'LAYERS': 'vestizioni',
//                                    'TILED':true,
//                            //        'SRS':'EPSG:6706',
//                                    'FORMAT': 'image/png',
//        //                            'STYLES':'default',
//        //                            'TRASPARENT':true,
//        //                            'TILED': true,
//                                    'VERSION':'1.1.1',
////                                    'WIDTH':1024,
////                                    'HEIGHT':1024,
//                                },
//                                serverType: 'mapserver',
//                                
//                            }),
//                            opacity:0.9
//                        }),
//                        new ol.layer.Tile({
//                            title: 'strade',
//                            visible: false,
//                            source: new ol.source.TileWMS({
//                                projection: ETRS89proj,
//                                crossOrigin : "Anonymous",
//                                attributions: catasto_attributions,
//                                url: 'https://wms.cartografia.agenziaentrate.gov.it/inspire/wms/ows01.php?language=ita&',
//        //                        url: 'http://192.168.1.185:8082/geoserver/PROVE/wms',
//                                params: {
//                                    'LAYERS': 'strade',
//                                    'TILED':true,
//                            //        'CRS':'EPSG:6706',
//                                    'FORMAT': 'image/png',
//                                    'STYLES':'default',
//                                    //'TRASPARENT':true,
//        //                            'TILED': true,
//                                    'VERSION':'1.1.1',
//                                    'WIDTH':1024,
//                                    'HEIGHT':1024,
//                                },
//                                serverType: 'mapserver',
//                                
//                            }),
//                            opacity:0.9
//                        }),
//                        new ol.layer.Tile({
//                            title: 'acque',
//                            visible: false,
//                            source: new ol.source.TileWMS({
//                                projection: ETRS89proj,
//                                crossOrigin : "Anonymous",
//                                attributions: catasto_attributions,
//                                url: 'https://wms.cartografia.agenziaentrate.gov.it/inspire/wms/ows01.php?language=ita&',
//        //                        url: 'http://192.168.1.185:8082/geoserver/PROVE/wms',
//                                params: {
//                                    'LAYERS': 'acque',
//                                    'TILED':true,
//                      //              'CRS':'EPSG:6706',
//                                    'FORMAT': 'image/png',
//                                    'STYLES':'default',
//                                    '//TRASPARENT':true,
//        //                            'TILED': true,
//                                    'VERSION':'1.1.1',
//                                    'WIDTH':1024,
//                                    'HEIGHT':1024,
//                                },
//                                serverType: 'mapserver',
//                                
//                            }),
//                            opacity:0.9
//                        }),
//                        new ol.layer.Tile({
//                            title: 'fogli',
//                            visible: false,
//                            source: new ol.source.TileWMS({
//                                projection: ETRS89proj,
//                                crossOrigin : "Anonymous",
//                                attributions: catasto_attributions,
//                                url: 'https://wms.cartografia.agenziaentrate.gov.it/inspire/wms/ows01.php?language=ita&',
//        //                        url: 'http://192.168.1.185:8082/geoserver/PROVE/wms',
//                                params: {
//                                    'LAYERS': 'CP.CadastralZoning',
//                                    'TILED':true,
//                      //              'CRS':'EPSG:6706',
//                                    'FORMAT': 'image/png',
//                                    'STYLES':'default',
//                                    //'TRASPARENT':true,
//        //                            'TILED': true,
//                                    'VERSION':'1.1.1',
//                                    'WIDTH':1024,
//                                    'HEIGHT':1024,
//                                },
//                                serverType: 'mapserver',
//                                
//                            }),
//                            opacity:0.9
//                        })
//                    ]
//                 }),
                 new ol.layer.Group({
                    title: 'PRG',
                    fold: 'close',
                    visible: false,
                   layers: [//prgLayer,
                            new ol.layer.Tile({
                            title: 'Zonizzazione',
                            //style: PrgStyleFunction,
                            visible: false,
                            source: new ol.source.TileWMS({
                                //projection: ETRS89proj,
                                projection: PRGproj,
                                crossOrigin : "Anonymous",
                                //attributions: catasto_attributions,
                                url: 'http://192.168.1.100:8080/geoserver/Campoli/wms',
                                params: {
                                    'LAYERS': 'Campoli:PRG',
                                    'TILED':true,
                                    //'FORMAT': 'image/png',
                                    //'STYLES':'default',
                                    //'VERSION':'1.1.1',
                                    'WIDTH':1024,
                                    'HEIGHT':1024,
                                },
                                serverType: 'geoserver',
                                //tileGrid: prgGrid
                            }),
                            opacity:0.6
                        }),

                            ]
                 }),   
                 new ol.layer.Group({
                    title: 'Catasto',
                    fold: 'close',
                    layers: [
                        new ol.layer.Tile({
                            title: 'Particelle',
                            visible: true,
                            source: new ol.source.TileWMS({
                                //attributions: catasto_attributions,
                                crossOrigin : "Anonymous",
                                url: 'http://192.168.1.100:8080/geoserver/Campoli/wms', 
                                params: {
                                    'LAYERS': 'Campoli:CP.CadastralParcel',
                                    'TILED':true,
                                    'WIDTH':1024,
                                    'HEIGHT':1024,
                                },
                                serverType: 'geoserver',
                            }),
                            opacity:0.4
                        }),
                        new ol.layer.Tile({
                            title: 'Fabbricati',
                            visible: true,
                            source: new ol.source.TileWMS({
                                //attributions: catasto_attributions,
                                crossOrigin : "Anonymous",
                                url: 'http://192.168.1.100:8080/geoserver/Campoli/wms', 
                                params: {
                                    'LAYERS': 'Campoli:fabbricati',
                                    'TILED':true,
                                    'WIDTH':1024,
                                    'HEIGHT':1024,
                                },
                                serverType: 'geoserver',
                            }),
                            opacity:0.4
                        }),
                        new ol.layer.Tile({
                            title: 'CadastralZoning',
                            visible: true,
                            source: new ol.source.TileWMS({
                                //attributions: catasto_attributions,
                                crossOrigin : "Anonymous",
                                url: 'http://192.168.1.100:8080/geoserver/Campoli/wms', 
                                params: {
                                    'LAYERS': 'Campoli:CP.CadastralZoning',
                                    'TILED':true,
                                    'WIDTH':1024,
                                    'HEIGHT':1024,
                                },
                                serverType: 'geoserver',
                            }),
                            opacity:0.4
                        }),
                        new ol.layer.Tile({
                            title: 'Acque',
                            visible: true,
                            source: new ol.source.TileWMS({
                                attributions: catasto_attributions,
                                crossOrigin : "Anonymous",
                                url: 'http://192.168.1.100:8080/geoserver/Campoli/wms', 
                                params: {
                                    'LAYERS': 'Campoli:acque',
                                    'TILED':true,
                                    'WIDTH':1024,
                                    'HEIGHT':1024,
                                },
                                serverType: 'geoserver',
                            }),
                            opacity:0.4
                        }),
                        new ol.layer.Tile({
                            title: 'Province',
                            visible: true,
                            source: new ol.source.TileWMS({
                                //attributions: catasto_attributions,
                                crossOrigin : "Anonymous",
                                url: 'http://192.168.1.100:8080/geoserver/Campoli/wms', 
                                params: {
                                    'LAYERS': 'Campoli:province',
                                    'TILED':true,
                                    'WIDTH':1024,
                                    'HEIGHT':1024,
                                },
                                serverType: 'geoserver',
                            }),
                            opacity:0.4
                        }),
                        new ol.layer.Tile({
                            title: 'Vestizioni',
                            visible: true,
                            source: new ol.source.TileWMS({
                                attributions: catasto_attributions,
                                crossOrigin : "Anonymous",
                                url: 'http://192.168.1.100:8080/geoserver/Campoli/wms', 
                                params: {
                                    'LAYERS': 'Campoli:vestizioni',
                                    'TILED':true,
                                    'WIDTH':1024,
                                    'HEIGHT':1024,
                                },
                                serverType: 'geoserver',
                            }),
                            //opacity:0.8
                        }),
                    ]
                 }), 
                 new ol.layer.Group({
                    title: 'CTR',
                    fold: 'close',
                    visible: false,
                    layers: [
                        new ol.layer.Tile({
                            title: 'curve livello',
                            visible: false,
                            source: new ol.source.TileWMS({
                                //attributions: catasto_attributions,
                                crossOrigin : "Anonymous",
                                url: 'http://192.168.1.100:8080/geoserver/Campoli/wms', 
                                params: {
                                    'LAYERS': 'Campoli:lines',
                                    'TILED':true,
                                    'WIDTH':1024,
                                    'HEIGHT':1024,
                                },
                                serverType: 'geoserver',
                            }),
                            //opacity:0.8
                        }),
                    ]
                 }),   
                 new ol.layer.Group({
                    title: 'Pratiche Edilizie',
                    visible:false,
                    fold: 'close',
                    layers: [pclayer, cilalayer, scialayer
//                        new ol.layer.Tile({
//                            title: 'SCIA',
//                            visible: true,
//                            source: new ol.source.TileWMS({
//                                //attributions: catasto_attributions,
//                                url: 'http://192.168.1.100:8080/geoserver/Campoli/wms', 
//                                params: {
//                                    'LAYERS': 'Campoli:Pratiche',
////                                    'TILED':true,
////                                    'WIDTH':1024,
////                                    'HEIGHT':1024
//                                },
//                                serverType: 'geoserver'
//                            })
//                            //opacity:0.8
//                        })
                    ]
                 }),
                 
            ],
            overlays: [overlay],
            target: 'mapgis',
            controls: ol.control.defaults.defaults({
                //new ol.control.ScaleLine(),
                zoom: true,
                attribution: true,
                rotate: false
            }),
            view: mapview
        });




   // scheda urbanistica
    $('._surb').on('click', function(event){
        //$(form).attr('action', '/index.php?r=mappe/schedaurbanistica')
        //event.preventDefault();
        var foglio= $('#particella-foglio').val();
        var particella = $('#particella-particella').val();
        if ((foglio=='') && (particella=='')) {
        alert('devi specificare foglio e particella');
        brack;
        }
        if (foglio=='') {
        alert('devi specificare il foglio');
        brack;
        }
        if (particella=='') {
        alert('devi specificare la particella');
        brack;
        }
        var url = 'index.php?r=mappe/schedaurbanistica'
        url += '&foglio='+foglio;
        url += '&particella='+particella;
        //alert(url);
        $('#urb-form').attr('action', url);
        //alert($('#urb-form').attr('action'));
        //$('#urb-form').submit;
        $('#urb-form').yiiActiveForm('validate', true);
        $('#urb-form').yiiActiveForm('submitForm');
    });


    // Ricerca Particelle Catastali
        $('._cercap').on('click', function(event){

                event.preventDefault(); // stopping submitting
   
                $.ajax({
                    url:'cercaparticella.php',
                    type:'GET',
                    dataType:'json',
                    data:{foglio:$('#particella-foglio').val(),particella:$('#particella-particella').val()}
                    }).done(function(result) {
                        if ('errmsg' in result) {
                        // particella non trovata
                        alert('Particella non trovata!');
                        //document.getElementById('_messaggio').innerHTML = 'particella non trovata'; 
                        } else {
                        // particella trovata
                        // OK con centroid
                        //alert(JSON.stringify(result));
                        alert('particella trovata ' + result.coords + ' in EPSG: '+ console.log(mapview.getProjection().getCode()) );
                        console.log(result.coords);
                        mapview.setCenter(result.coords);
                        //document.getElementById('_messaggio').innerHTML = ''; 
                        }

                        // Particella
                        //map.setView(new L.LatLng(result.coords[1], result.coords[0]), 20);



                    });
       });










// ************** CONTROLLI ****************************************//
//        var addr_popup = new ol.Overlay.Popup();
//        map.addOverlay(addr_popup);
//        var getf_popup = new ol.Overlay.Popup();
//        map.addOverlay(getf_popup);

//        var mousePositionControl = new ol.control.MousePosition({
//          coordinateFormat: ol.coordinate.createStringXY(4),
//          //projection: ETRS89proj,
//          target: document.getElementById('mouse-position'),
//          undefinedHTML: '&nbsp;',
//          
//        });
//        map.addControl(mousePositionControl);
//        
//        
//        
        // Overlay menu
//        var menu = new ol.control.Overlay ({ 
//          closeBox : false, 
//          closeOnClick: true,
//          className: "slide-left menu", 
//          content: $("#menu").get(0)
//        });
//        map.addControl(menu);
////        //menu.setPosition('bottom-right');
////        
//        // A toggle control to show/hide the menu
//        var t = new ol.control.Toggle({
//          html: '<i class="fa fa-bars" ></i>',
//          className: "menu",
//          title: "Menu",
//          onToggle: function() { menu.toggle(); }
//        });
//        map.addControl(t);
        
        
        
        // Main control bar
        var mainbar = new ol.control.Bar();
        map.addControl(mainbar);
        
        
        
        /** Print dialog labels (for customisation) */
        ol.control.PrintDialog.prototype._labels['it']={
            title: 'Stampa',
            orientation: 'Orientation',
            portrait: 'Verticale',
            landscape: 'Orizzontale',
            size: 'Formato Carta',
            custom: 'screen size',
            margin: 'Margini',
            scale: 'Scala',
            legend: 'Legenda',
            north: 'Orientamento',
            mapTitle: 'Titolo',
            saveas: 'Salva con nome...',
            saveLegend: 'Salva legenda...',
            copied: '✔ Copiato nella clipboard',
            errorMsg: 'Non posso salvare map canvas...',
            printBt: 'Stampa...',
            clipboardFormat: 'copia nella clipboard...',
            jpegFormat: 'salva come jpeg',
            pngFormat: 'salva come png',
            pdfFormat: 'salva come pdf',
            none: 'none',
            small: 'small',
            large: 'large',  
            cancel: 'Annulla'
          };        
        ol.control.PrintDialog.prototype.scales['2000']='1/2.000';
        ol.control.PrintDialog.prototype.scales['1000']='1/1.000';
          
        
         
         
        // Print control
        var printControl = new ol.control.PrintDialog({ 
          // targetDialog: map.getTargetElement() 
          //width: '80%',
          //openWindow: true,
          lang: 'it',
          title:'Stampa la mappa',
          targetDialog: $('#xcon'),
//          _labels: {it:{
//            title: 'Stampa',
//            orientation: 'Orientation',
//            portrait: 'Verticale',
//            landscape: 'Orizzonntale',
//            size: 'Formato Carta',
//            custom: 'screen size',
//            margin: 'Margini',
//            scale: 'Scala',
//            legend: 'Legenda',
//            north: 'Orientamento',
//            mapTitle: 'Titolo',
//            saveas: 'Salva con nome...',
//            saveLegend: 'Salva legenda...',
//            copied: '✔ Copiato nella clipboard',
//            errorMsg: 'Non posso salvare map canvas...',
//            printBt: 'Stampa...',
//            clipboardFormat: 'copia nella clipboard...',
//            jpegFormat: 'salva come jpeg',
//            pngFormat: 'salva come png',
//            pdfFormat: 'salva come pdf',
//            none: 'none',
//            small: 'small',
//            large: 'large',  
//            cancel: 'Annulla'
//          }}
          //html:'<img src="immagini/printer_16.png" width="16" height="16" >',
        });

        printControl.setSize('A3');
        printControl.setOrientation('landscape');
        
        
        mainbar.addControl(printControl);
          // ScaleLine
         map.addControl(new ol.control.CanvasScaleLine());
         map.addControl(new ol.control.CanvasAttribution({ canvas: true }));
         // Add a title control
        map.addControl(new ol.control.CanvasTitle({ 
          title: 'titolo', 
          visible: false,
          style: new ol.style.Style({ text: new ol.style.Text({ font: '20px "Lucida Grande",Verdana,Geneva,Lucida,Arial,Helvetica,sans-serif'}) })
        }));
        
        printControl.on(['show'], function(e) {
            $('.ol-ext-print-dialog').css('width','96%');
            $('.ol-ext-print-dialog').css('margin-left','4%');
            //alert('load');
        });
         /* On print > save image file */
        printControl.on(['print','error'], function(e) {
            //alert('print event');
            console.log(e);
          // Print success
          if (e.image) {
              //alert('image');
            if (e.pdf) {
              // Export pdf using the print info
             
              var pdf = new jsPDF({
                orientation: e.print.orientation,
                unit: e.print.unit,
                format: e.print.size
              });
              pdf.addImage(e.image, 'JPEG', e.print.position[0], e.print.position[0], e.print.imageWidth, e.print.imageHeight);
              pdf.save(e.print.legend ? 'legend.pdf' : 'map.pdf');
            } else  {
              // Save image as file
              e.canvas.toBlob(function(blob) {
                var name = (e.print.legend ? 'legend.' : 'map.')+e.imageType.replace('image/','');
                saveAs(blob, name);
              }, e.imageType, e.quality);
            }
          } else {
            console.warn('No canvas to export');
          }
        });
        
        
          /* Standard Controls */
        mainbar.addControl (new ol.control.ZoomToExtent({  
            extent: [1629000, 5027000 , 1633000, 5033000],
            tipLabel: 'zoom estensione'
        }));
        //mainbar.addControl (new ol.control.Rotate());
        mainbar.addControl (new ol.control.FullScreen({tipLabel: 'visualizza a schermo intero'}));
        
        mainbar.setPosition('bottom-right');
    
        var layerSwitcher = new ol.control.LayerSwitcher({
//            tipLabel: 'Legenda', // Optional label for button
            collapsed: true,
            //enableOpacitySliders: false, 
            mouseover:true // show the panel on mouseover, default false
        });
        $('body').addClass('hideOpacity');
        // Aggiungo un button che permette di nascondere opacity slider
        var button = $('<div id="opb" class="toggleVisibility" title="Visualizza/Nasconde barre per regolazione opacità">')
        //var button = $('<input name="opacity" type="checkbox" class="toggleVisibility" checked="checked"/>')
            .text("barra opacità")
            .click(function() {
//              var a = map.getLayers().getArray();
//              var b = !a[0].getVisible();
//              if (b) button.removeClass("show");
//              else button.addClass("show");
//              for (var i=0; i<a.length; i++) {
//                a[i].setVisible(b);
//            if ($("#opb").prop("checked")) $('body').addClass('hideOpacity');
            $('body').toggleClass('hideOpacity');
            $('#opb').toggleClass('show');
//            var a = map.getLayers().getArray();
//            var b = !a[0].getVisible();
//            if (b) button.removeClass("show");
//            else button.addClass("show");
              
            });
        layerSwitcher.setHeader($('<div>').append(button).get(0));
        //layerSwitcher.setHeader('<div><input name="opacity" type="checkbox" class="toggleVisibility" checked="checked"/>Opacity</div>');
        map.addControl(layerSwitcher);
        //map.zoomToMaxExtent();

        // ScaleLine
         map.addControl(new ol.control.CanvasScaleLine());
         map.addControl(new ol.control.CanvasAttribution({ canvas: true }));
         // Add a title control
        map.addControl(new ol.control.CanvasTitle({ 
          title: 'titolo', 
          visible: false,
          style: new ol.style.Style({ text: new ol.style.Text({ font: '20px "Lucida Grande",Verdana,Geneva,Lucida,Arial,Helvetica,sans-serif'}) })
        }));

 // barra impostazioni
   var controlbar = new ol.control.Bar(
    {	controls:[ new ol.control.Toggle(
        {	html: '<img src="immagini/settings_16.png" width="16" height="16" title="impostazioni">',
          //onToggle: function() { info(); },
          // Nested control bar
          bar: new ol.control.Bar(
            {	toggleOne: true,
              controls:
              [	new ol.control.Toggle(
                  {	html:'<img src="immagini/printer_16.png" width="16" height="16" >', 
                        onToggle: function() { 
                            $('ol-ext-print-dialog').css('width','80%');
                            alert('onToggle');
                        },
                        active:true,
                        title:'visualizza/nasconde barra opacità nella lista dei layer',
                    // Second level nested control bar
                        bar: new ol.control.Bar(
                            {	toggleOne: true,
                            controls:
                            [	new ol.control.Button(
                            {	html:"2.1", 
                              className: "ol-text-button",
                             // handleClick: function(b) { info("Button 2.1 clicked"); } 
                            }),
                          new ol.control.Button(
                            {	html:"22", 
                              className: "ol-text-button",
                             // handleClick: function(b) { info("Button 2.2 clicked"); } 
                            })
                        ]
                      })
                  })
              ]
            })
        })
      ]
    });
    controlbar.setPosition('top-left')
    map.addControl(controlbar);
  /**/






// Select  interaction
  function NomeRichiedente(Cognome,Nome,Denominazione,DataNascita,RegimeGiuridico) {
    if (RegimeGiuridico==1) {
        return Cognome +' '+ Nome +' ('+DataIT(DataNascita)+')';
        } else {
        return Denominazione;
    }
  }
  
  function DataIT(datas) {
        var date1=datas;
        if (datas.length==11) {
           date1 = datas.substring(0,10);
       }
        var date2 = new Date(date1);
       var datestring = ('0'+ date2.getDate()).slice(-2)  + "-" +('0'+(date2.getMonth()+1)).slice(-2) + "-" + date2.getFullYear();
       return datestring; // date2.toLocaleDateString();
  }
  
  var _NomeRichiedente ='';
  var select = new ol.interaction.Select({
    hitTolerance: 5,
    //multi: true,
    condition: ol.events.condition.singleClick,
    layers: [pclayer, cilalayer, scialayer],
  });
  map.addInteraction(select);

    const titoli = ['0', 'CILA', 'SCIA', 'SUPER-SCIA', 'Permesso Costruire', 'Segnalazione Certificata per L\'Agibilità',
                    'Comunicazione Inizio Lavori Opere Temporanee', 'Autorizzazione Sismica', 'Contono Legge 47/85', 
                    'Condono Legge 724/1994', 'Autorizzazione Paesaggistica', 'Concessione Edilizia'
                    ,'Altre Autorizzazioni', 'CILA SUPERBONUS'];
  // Select control
  
  var popuped = new ol.Overlay.PopupFeature({
    popupClass: 'default anim',
    select: select,
    positionning: 'auto',
    keepSelection: true,
    //canFix: true,
    /** /
    template: function(f) {
      return {
        title: function(f) { return f.get('nom')+' ('+f.get('id')+')' },
        attributes: { 
          region: { title: 'Région' }, 
          arrond: 'arrond', 
          cantons: 'cantons', 
          communes: 'communes', 
          pop: 'pop' 
        }
      }
    },
    /**/
//    onshow: function() {
//        for (var feature in select.getFeatures()) {
//                console.log(feature.getGeometry());
//                //alert(feature.getGeometry().getFirstCoordinate());
//            }
//                    },
    template:  {
        title:  function(f) {
                _NomeRichiedente=NomeRichiedente(f.get('Cognome'),f.get('Nome'),f.get('Denominazione'),f.get('DataNascita'),f.get('RegimeGiuridico_id'));
                console.log(_NomeRichiedente);
                return titoli[f.get('id_titolo')]+' ('+f.get('edilizia_id')+')';
                },
        attributes: //['id_titolo','edilizia_id','DataProtocollo']
        { 
            //'NOME_COMPOSTO':{title: 'Richiedente'},
            'Nome':{visible:false},
            'Denominazione':{visible:false},
            'RegimeGiuridico_id':{visible:false},
            'Cognome':  {title: 'Richiedente',
                         format: function() {
                            return _NomeRichiedente;                                
                         }
                        },
//            'id_titolo':{title: 'id titolo'}, 
            'NumeroProtocollo': {title:'Numero Protocollo'},
            'DataProtocollo': { title: 'Data Protocollo',
                                 format: function(val, f) { 
                                     return DataIT(f.get('DataProtocollo'));
//                                     var date = f.get('DataProtocollo');
//                                     var date1=date;
//                                     if (date.length==11) {
//                                        date1 = date.substring(0,10);
//                                    }
//                                     //console.log(date1);
//                                     var date2 = new Date(date1);
//                                     //console.log(date2);
//                                     //console.log(Date.parse(f.get('DataProtocollo')));
//                                    var datestring = ('0'+ date2.getDate()).slice(-2)  + "-" +('0'+(date2.getMonth()+1)).slice(-2) + "-" + date2.getFullYear();
//                                    return datestring; // date2.toLocaleDateString();
                                 }
                                        
            },
            'NumeroTitolo':{title:'Numero Permesso',
                            visible: function(f) { return f.get('NumeroTitolo')>0 ? true : false}
            },
            'DataTitolo':{title:'Data Permesso',
                            visible: function(f) { 
                                
                                return f.get('DataTitolo').length>0 ? true : false
                            },
                            format: function(val, f) { return DataIT(f.get('DataTitolo')); }
            }

            //          
//          'cantons': { title: 'Cantons' },
//          'communes': { title: 'Communes' },
          // with prefix and suffix
//          'pop': { 
//            title: 'Population',  // attribute's title
//            before: '',           // something to add before
//            format: ol.Overlay.PopupFeature.localString(),  // format as local string
//            after: ' hab.'        // something to add after
//          },
//          // calculated attribute
//          'pop2': {
//            title: 'Population (kHab.)',  // attribute's title
//            format: function(val, f) { 
//              return Math.round(parseInt(f.get('pop'))/100).toLocaleString() + ' kHab.' 
//            }
//          }
          /* Using localString with a date * /
          'date': { 
            title: 'Date', 
            format: ol.Overlay.PopupFeature.localString(undefined, { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) 
          }
          /**/
        }
    }
  });
  //popuped.set
  map.addOverlay (popuped);











/// OK FUNZIONA
//        map.on('singleclick', function (evt) {
//            const coord = ol.proj.transform(evt.coordinate, 'EPSG:3857', ETRS89proj);
//            var gFIurl = 'https://wms.cartografia.agenziaentrate.gov.it/inspire/ajax/ajax.php?op=getDatiOggetto&lon=' + coord[0].toString()+ '&lat=' + coord[1].toString();
//                 if (gFIurl) {
//                    var xhttp;
//                    //istanza di una richiesta XHTTP
//                    xhttp = new XMLHttpRequest();
//
//                    xhttp.onreadystatechange = function() {
//                    if (this.readyState == 4 && this.status == 200) {
//                    var obj = JSON.parse(xhttp.responseText);
//                    alert(obj.DENOM  +'\n'+
//                            'Foglio = ' + obj.FOGLIO + '  Particella = ' + obj.NUM_PART +'\n'+
//                            'Latitudine = ' + coord.toString()
//                         );
//                    }
//              };
//              //bypass CORS policy
//              //xhttp.open('GET', 'https://cors-anywhere.herokuapp.com/' + gFIurl, true);
//              //xhttp.responseType = 'json';
//              xhttp.open('GET', gFIurl, true);
//              xhttp.send();
//            }
//
//        });
        
        function infoParticella(evt) {
                     const coord = ol.proj.transform(evt.coordinate, 'EPSG:3857', ETRS89proj);
            var gFIurl = 'https://wms.cartografia.agenziaentrate.gov.it/inspire/ajax/ajax.php?op=getDatiOggetto&lon=' + coord[0].toString()+ '&lat=' + coord[1].toString();
                 if (gFIurl) {
                    var xhttp;
                    //istanza di una richiesta XHTTP
                    xhttp = new XMLHttpRequest();

                    xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                    var obj = JSON.parse(xhttp.responseText);
                    alert(obj.DENOM  +'\n'+
                            'Foglio = ' + obj.FOGLIO + '  Particella = ' + obj.NUM_PART +'\n'+
                            'Latitudine = ' + coord.toString()
                         );
                    }
              };
              //bypass CORS policy
              //xhttp.open('GET', 'https://cors-anywhere.herokuapp.com/' + gFIurl, true);
              //xhttp.responseType = 'json';
              xhttp.open('GET', gFIurl, true);
              xhttp.send();
            }
   
        }
        
        
    var contextmenuItems = [
  {
    text: 'Info Particella',
    classname: 'bold',
    //icon: centerIcon,
    callback: infoParticella
  },
   {
    text: 'Info Coordinate',
    classname: 'bold',
    //icon: centerIcon,
    callback: infoParticella
  },
  {
    text: 'Urbanistica',
    //icon: listIcon,
    items: [
      {
        text: 'Info Urbanistica',
      //  icon: centerIcon,
        callback: infoParticella
      },
      {
        text: 'Scheda Urbanistica',
       // icon: pinIcon,
        callback: infoParticella
      }
    ]
  },
  {
    text: 'Centra Qui',
  //  icon: pinIcon,
    callback: infoParticella
  },
  '-' // this is a separator
];
//console.log(`Menu Contestuale ${ETRS89proj} è stata registrata o aggiunta a OpenLayers.`);
var contextmenu = new ContextMenu({
  width: 180,
  items: contextmenuItems
  });
map.addControl(contextmenu);

//var removeMarkerItem = {
//  text: 'Remove this Marker',
//  classname: 'marker',
//  callback: removeMarker
//};

contextmenu.on('open', function (evt) {
  var feature =	map.forEachFeatureAtPixel(evt.pixel, ft => ft);
  
  if (feature && feature.get('type') === 'removable') {
    contextmenu.clear();
//    removeMarkerItem.data = { marker: feature };
//    contextmenu.push(removeMarkerItem);
  } else {
    contextmenu.clear();
    contextmenu.extend(contextmenuItems);
    contextmenu.extend(contextmenu.getDefaultItems());
  }
});
    
//for (i = 0; i < map.getLayers().length; i++) {
//  //console.log(numbers[i]);
//  console.log(map.getLayers(i).getProjection().getCode());       
//}         

//map.getLayers().forEach(function(layer) {
//    console.log(layer.getProjection().getCode()); 
//});
//
// console.log(mapview.getProjection().getCode()); 
  
        
        
// *********************************************************************//
// info = new OpenLayers.Control.WMSGetFeatureInfo({
//        url: 'http://localhost:8090/geoserver/Layers/wms', 
//        title: 'Identify features by clicking',
//        layers: [layer1],
//        infoFormat: 'text/html',
//        queryVisible: true,
//        eventListeners: {
//            getfeatureinfo: function(event) {
//                map.addPopup(new OpenLayers.Popup.FramedCloud(
//                    "chicken", 
//                    map.getLonLatFromPixel(event.xy),
//                    null,
//                    event.text,
//                    null,
//                    true
//                ));
//            }
//        }
//    });
/**
 * Add a click handler to the map to render the popup.
 */
//map.on('click', function (evt) {
////  const coordinate = evt.coordinate;
////  const hdms = ol.coordinate.toStringHDMS(ol.proj.toLonLat(coordinate));
////
////  content.innerHTML = '<p>Hai cliccato qui:</p><code>' + hdms + '</code>';
////  overlay.setPosition(coordinate);
//const feature = map.forEachFeatureAtPixel(evt.pixel, function (feature) {
//    return feature;
//  });
//  disposePopover();
//  if (!feature) {
//    return;
//  }
//  popup.setPosition(evt.coordinate);
//  popover = new bootstrap.Popover(element, {
//    placement: 'top',
//    html: true,
//    content: feature.get('id_titolo'),
//  });
//  popover.show();
//});
//// change mouse cursor when over marker
//map.on('pointermove', function (e) {
//  const pixel = map.getEventPixel(e.originalEvent);
//  const hit = map.hasFeatureAtPixel(pixel);
//  map.getTarget().style.cursor = hit ? 'pointer' : '';
//});
//// Close the popup when the map is moved
//map.on('movestart', disposePopover);




</script>



