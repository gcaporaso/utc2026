/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


//import Map from 'js/ol/Map.js';
//import View from 'js/ol/View.js';
//import OSM from 'js/ol/source/OSM.js';
//import TileLayer from 'js/ol/layer/Tile.js';   

//// Aggiunge un controllo per il menu a discesa attivabile con il pulsante destro del mouse
//const contextMenuInteraction = new ol.interaction.ContextMenu({
//  coordinateFormat: (coordinate) => {
//    return ol.coordinate.format(coordinate, '{x}, {y}', 4);
//  },
//  items: [{
//    text: 'Visualizza coordinate',
//    callback: function (evt) {
//      console.log(evt.coordinate);
//    }
//  }]
//});

var baselayer = new ol.layer.Tile({
    source: new ol.source.OSM(),
    //type: 'base'
    //visible: false,
    title: 'OSM'
    });
var stateslayer = new ol.layer.Image({
      source: new ol.source.ImageWMS({
        extent: [-13884991, 2870341, -7455066, 6338219],
        url: 'http://192.168.1.49:8082/geoserver/topp/wms',
        //params: {'LAYERS': 'PROVE:GE.CARTAGEOLOGICA'},
        params: {'LAYERS': 'topp:states'},
        ratio: 1,
        serverType: 'geoserver'
      })
    });
    


var map = new ol.Map({
  target: 'mapgis',
  layers: [
//    ctr1,
//    ctr2,
    baselayer,
    stateslayer,
    catastolayer
  ],
  view: new ol.View({
    center: ol.proj.fromLonLat([1638014.651, 5015561.131]),
    zoom: 16
  }),
  // interactions: ol.interaction.defaults().extend([contextMenuInteraction])
});

//// Aggiunge un controllo per la visualizzazione delle coordinate geografiche
//var mousePositionControl = new ol.control.MousePosition({
//  coordinateFormat: ol.coordinate.createStringXY(4),
//  //projection: 'EPSG:4326',
//  target: document.getElementById('coordinates'),
//  undefinedHTML: '&nbsp;'
//});
//map.addControl(mousePositionControl);




//  tipLabel: 'Layer switcher' // aggiungiamo una label al controllo
//});
var layerSwitcher = new ol.control.LayerSwitcher({
//const layerSwitcher = new LayerSwitcher({
activationMode: 'click',
    //startActive: true,
    tipLabel: 'Show layer list', // Optional label for button
    collapseTipLabel: 'Hide layer list', // Optional label for button
    groupSelectStyle: 'children' // Can be 'children' [default], 'group' or 'none'
//
//  reverse: true,
//groupSelectStyle: 'group'
});
map.addControl(layerSwitcher);
