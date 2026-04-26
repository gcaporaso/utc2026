function layers_def(map){
 
 var ctr_curve = new L.geoJson(json_CTRCURVEDILIVELLOESCARPATE_1, {
            attribution: '',
            interactive: true,
            dataVar: 'json_CTRCURVEDILIVELLOESCARPATE_1',
            //layerName: 'layer_CTRCURVEDILIVELLOESCARPATE_1',
            //pane: 'pane_CTRCURVEDILIVELLOESCARPATE_1',
            //onEachFeature: pop_CTRCURVEDILIVELLOESCARPATE_1,
            style: style_curve,
            onEachFeature: function (feature, layer) {
            var label = String(feature.properties.elevation);
            // Tooltip non permanente: visibile solo al passaggio del mouse,
            // evita la creazione di centinaia di elementi DOM sempre attivi
            layer.bindTooltip(label, {permanent: false, opacity: 0.7, direction: 'center',
                                      className: 'curvelivello', sticky: true});
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

// Piano Regolatore Generale
function onEachFeaturePRG(feature, layer) {
    // does this feature have a property named popupContent?
    if (feature.properties && feature.properties.Z) {
        layer.bindPopup(feature.properties.Z);
    }
    // if (feature.properties && feature.properties.zonizzazio) {
    //     layer.bindPopup(feature.properties.zonizzazio);
    // }
    if (feature.style) {
    var style = feature.style;
        layer.setStyle(style);
    }
    // if (feature.properties && feature.properties.rischio) {
    //     layer.bindPopup(feature.properties.rischio);
    // }
    
}


function onEachFeaturePTP(feature, layer) {
    // does this feature have a property named popupContent?
   
    if (feature.properties && feature.properties.zonizzazio) {
        layer.bindPopup(feature.properties.zonizzazio);
    }
    if (feature.style) {
    var style = feature.style;
        layer.setStyle(style);
    }
       
}
function onEachFeatureRischio(feature, layer) {
    // does this feature have a property named popupContent?
   
    if (feature.properties && feature.properties.rischio) {
        layer.bindPopup(feature.properties.rischio);
    }
       
}
// Piano Regolatore Generale
prgLayer = L.geoJSON(prg,{onEachFeature: onEachFeaturePRG});
//prgLayer.addData(prg);


// Piano Paesaggistico
ptpLayer = L.geoJSON(ptpjson,{style:style_ptp, onEachFeature: onEachFeaturePTP});
//ptpLayer.addData(ptpjson);

// Borghi agricoli PRG
borghiLayer = L.geoJSON(json_borghi_agricoli);
//ptpLayer.addData(json_borghi_agricoli);

// Vincolo Idrogeologico
vidroLayer = L.geoJSON(vidro);

// CTR
//var ctrp = L.geoJSON(ctrpunti);
//var ctrc = L.geoJSON(curve);

// Area a Rischio Frane Autorità di Bacino
pabLayer = L.geoJSON(pfraneab,{onEachFeature: onEachFeatureRischio});

// Catastale Vettoriale
// var vcatLayer = L.geoJSON(vcataslate);

// Perimetor
PerimetroLayer = L.geoJSON(perimetro);






layerPermessi = L.layerGroup();
layerSCIA = L.layerGroup();
layerSCA = L.layerGroup();
layerCILA = L.layerGroup();
layerConcessioni = L.layerGroup();
layerAltro = L.layerGroup();
//var pratiche = L.layerGroup([layerPermessi,layerSCIA,layerSCA,layerAltro]).addTo(map);

// file vettoriali catastali
// var vfoglio1 = L.geoJSON().addTo(map);
// $.getJSON('/mappe/b542/V2025-09-22/B542_000100.geojson',function (data) {
//     vfoglio1.addData(data);
// });

overlaysTree = {
    label:'Mappe',
    collapsed:false,
    children: [ 
        
        //label: 'Mappe',
        //selectAllCheckbox: 'Un/select all',
        {label: 'wms Catasto', layer: catasto }, 
        {label: 'json Catasto',
        collapsed:true,
        children: [
                {label: 'foglio 01',
                collapsed:true,
                children: [
                    ],
                    }, 
                {label: 'foglio 02',
                collapsed:true,
                children: [
                    ],
                    }, 
                {label: 'foglio 03',
                collapsed:true,
                children: [
                    ],
                    }, 
                {label: 'foglio 04',
                collapsed:true,
                children: [
                    ],
                    }, 
                {label: 'foglio 05',
                collapsed:true,
                children: [
                    ],
                    }, 
                {label: 'foglio 06',
                collapsed:true,
                children: [
                    ],
                    }, 
                {label: 'foglio 07',
                collapsed:true,
                children: [
                    ],
                    }, 
                {label: 'foglio 08',
                collapsed:true,
                children: [
                    ],
                    }, 
                {label: 'foglio 09',
                collapsed:true,
                children: [
                    ],
                    }, 
                {label: 'foglio 10',
                collapsed:true,
                children: [
                    ],
                    }, 
                {label: 'foglio 11',
                collapsed:true,
                children: [
                    ],
                    }, 
                {label: 'foglio 12',
                collapsed:true,
                children: [
                    ],
                    }, 
            ],
        }, {   
        label: 'PRG',
        collapsed:true,
            children: [
                        { label: 'Zonizzazione', layer: prgLayer},
                        // { label: 'Zonizzazione', layer: layer_PIANOREGOLATOREGENERALE_0}, 
                        { label: 'Borghi Agricoli', layer: borghiLayer }
                ],
        }, {          
        label: 'PTP', layer: ptpLayer,
        }, {      
        label: 'Pratiche Edilizie',
        collapsed:true,
            children: [
                        { label: 'Permessi', layer: layerPermessi },
                        { label: 'SCIA', layer: layerSCIA },
                        { label: 'CILA', layer: layerCILA },
                        { label: 'Agibilità', layer: layerSCA },
                        { label: 'Concessioni', layer: layerConcessioni },
                        { label: 'Altro', layer: layerAltro },
            ],
        }, {
        label: 'Progetti',
        collapsed:true,
            children: [
                    {label: 'Acquedotto Grieci', layer: layerCILA},
                ]
        },
        buildCtr2020TreeNode(),
        buildMinosxTreeNode()
        ]
    }


    //return overlaysTree;
}

        
 