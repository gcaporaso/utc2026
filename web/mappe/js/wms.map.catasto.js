function layer_wms(){
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */





var wmsOptions = {
    format:'image/png',
    version:'1.1.1',
    crs: crs_6706,
    transparent:true,
    width:'1024',
    height:'1024',
    maxZoom:22,
    opacity:0.35,
    //fillOpacity:.35,
    edgeBufferTiles: 1,
    layers:'province,CP.CadastralZoning,CP.CadastralParcel,fabbricati,strade,acque,vestizioni',
    // layers:'CP.CadastralZoning,CP.CadastralParcel',
    attribution: "© Agenzia della Entrate"
    };




var wms130Options = {
    format:'image/png',
    version:'1.3.0',
    crs: crs_6706,
    transparent:true,
    width:'1024',
    height:'1024',
    tileSize: 512,
    detectRetina: false,
    uppercase: true,
    maxZoom:22,
    opacity:0.35,
    style:'default',
   // DPI:96,
    //MAP_RESOLUTION:96,
    //fillOpacity:.35,
    //edgeBufferTiles: 1,
    layers:'province,CP.CadastralZoning,CP.CadastralParcel,fabbricati,strade,acque,vestizioni',
    // layers:'CP.CadastralZoning,CP.CadastralParcel',
    attribution: "© Agenzia della Entrate"
    };


//console.log(L.myTileLayer);
// Layer mappe catastali RASTER
//map.setView([43.731739, 10.401401], 17);
catasto = L.myTileLayer.wms('https://wms.cartografia.agenziaentrate.gov.it/inspire/wms/ows01.php?', wms130Options);
catasto.on('tileerror', function(e) {
    console.warn("Errore tile:", e);
});


};