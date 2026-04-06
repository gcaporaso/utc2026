/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


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
