/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var googleOptions = {
    transparent:true,
    format:'image/png',
    version:'1.3.0',
    crs: L.CRS.EPSG3857, //crs_25833,
    width:'1024',
    height:'1024',
    maxZoom:22,
    edgeBufferTiles: 1,
    };

const googleSat = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}',{
    subdomains:['mt0','mt1','mt2','mt3'],
    maptype:'satellite',
     maxZoom:22,
    key: typeof GOOGLE_MAPS_KEY !== 'undefined' ? GOOGLE_MAPS_KEY : ''
});


const googleHybrid = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}',{
    subdomains:['mt0','mt1','mt2','mt3'],
    maptype:'hybrid',
     maxZoom:22,
    key: typeof GOOGLE_MAPS_KEY !== 'undefined' ? GOOGLE_MAPS_KEY : ''
});

const googleTerrain = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}',{
    subdomains:['mt0','mt1','mt2','mt3'],
    maptype:'terrain',
     maxZoom:22,
    key: typeof GOOGLE_MAPS_KEY !== 'undefined' ? GOOGLE_MAPS_KEY : ''
});


const googleRoadMap = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}',{
    subdomains:['mt0','mt1','mt2','mt3'],
    maptype:'roadmap',
     maxZoom:22,
    key: typeof GOOGLE_MAPS_KEY !== 'undefined' ? GOOGLE_MAPS_KEY : ''
});


