/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/*
 * Style per layer CTR Curve di livello
 */
function style_curve(feature) {
            switch(String(feature.properties['desc'])) {
                case 'Curva di Livello Direttrice':
                    return {
                //pane: 'pane_CTRCURVEDILIVELLOESCARPATE_1',
                opacity: 1,
                color: 'rgba(215,25,28,1.0)',
                dashArray: '',
                lineCap: 'square',
                lineJoin: 'bevel',
                weight: 2.0,
                fillOpacity: 0,
                interactive: true,
            };
                    break;
                case 'Curva di Livello Ordinaria':
                    return {
                //pane: 'pane_CTRCURVEDILIVELLOESCARPATE_1',
                opacity: 1,
                //color: 'rgba(84,176,74,1.0)',
                color: 'rgba(185, 239, 249,1.0)',
                dashArray: '',
                lineCap: 'square',
                lineJoin: 'bevel',
                weight: 1.0,
                fillOpacity: 0,
                interactive: true,
            };
                    break;
                case 'Scarpata Naturale o Artificiale':
                    return {
                //pane: 'pane_CTRCURVEDILIVELLOESCARPATE_1',
                opacity: 1,
                color: 'rgba(72,123,182,1.0)',
                dashArray: '',
                lineCap: 'round',
                lineJoin: 'round',
                weight: 2.0,
                fillOpacity: 0,
                interactive: true,
            };
                    break;
                default:
                    return {
                //pane: 'pane_CTRCURVEDILIVELLOESCARPATE_1',
                opacity: 1,
                color: 'rgba(96,227,19,1.0)',
                dashArray: '',
                lineCap: 'square',
                lineJoin: 'bevel',
                weight: 1.0,
                fillOpacity: 0,
                interactive: true,
            };
                    break;
            }
        } 










/*
 * Style per layer CTR Edifici
 */
 function style_edifici(feature) {
            switch(String(feature.properties['desc'])) {
                case 'Baracca':
                    return {
                //pane: 'pane_CTREDIFICIPOLIGONI_5',
                opacity: 1,
                color: 'rgba(35,35,35,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fill: true,
                fillOpacity: 1,
                fillColor: 'rgba(154,96,209,1.0)',
                interactive: true,
            }
                    break;
                case 'Edificio Agricolo - Stalla - Ricovero An':
                    return {
                //pane: 'pane_CTREDIFICIPOLIGONI_5',
                opacity: 1,
                color: 'rgba(35,35,35,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fill: true,
                fillOpacity: 1,
                fillColor: 'rgba(219,81,203,1.0)',
                interactive: true,
            }
                    break;
                case 'Edificio di Culto - Cappella - Campanile':
                    return {
                //pane: 'pane_CTREDIFICIPOLIGONI_5',
                opacity: 1,
                color: 'rgba(35,35,35,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fill: true,
                fillOpacity: 1,
                fillColor: 'rgba(168,228,114,1.0)',
                interactive: true,
            }
                    break;
                case 'Edificio Diroccato o Rudere':
                    return {
                //pane: 'pane_CTREDIFICIPOLIGONI_5',
                opacity: 1,
                color: 'rgba(35,35,35,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fill: true,
                fillOpacity: 1,
                fillColor: 'rgba(81,91,200,1.0)',
                interactive: true,
            }
                    break;
                case 'Edificio Generico':
                    return {
                //pane: 'pane_CTREDIFICIPOLIGONI_5',
                opacity: 1,
                color: 'rgba(35,35,35,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fill: true,
                fillOpacity: 1,
                fillColor: 'rgba(119,181,210,1.0)',
                interactive: true,
            }
                    break;
                case 'Edificio in Costruzione':
                    return {
                //pane: 'pane_CTREDIFICIPOLIGONI_5',
                opacity: 1,
                color: 'rgba(35,35,35,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fill: true,
                fillOpacity: 1,
                fillColor: 'rgba(215,199,18,1.0)',
                interactive: true,
            }
                    break;
                case 'Edificio Industriale':
                    return {
                //pane: 'pane_CTREDIFICIPOLIGONI_5',
                opacity: 1,
                color: 'rgba(35,35,35,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fill: true,
                fillOpacity: 1,
                fillColor: 'rgba(200,143,117,1.0)',
                interactive: true,
            }
                    break;
                case 'Serra':
                    return {
                //pane: 'pane_CTREDIFICIPOLIGONI_5',
                opacity: 1,
                color: 'rgba(35,35,35,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fill: true,
                fillOpacity: 1,
                fillColor: 'rgba(15,229,40,1.0)',
                interactive: true,
            }
                    break;
                case 'Tettoia o Pensilina':
                    return {
                //pane: 'pane_CTREDIFICIPOLIGONI_5',
                opacity: 1,
                color: 'rgba(35,35,35,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fill: true,
                fillOpacity: 1,
                fillColor: 'rgba(224,129,156,1.0)',
                interactive: true,
            }
                    break;
                default:
                    return {
                //pane: 'pane_CTREDIFICIPOLIGONI_5',
                opacity: 1,
                color: 'rgba(247, 43, 43,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fill: true,
                fillOpacity: 1,
                fillColor: 'rgba(77,215,176,1.0)',
                interactive: true,
            }
                    break;
            }
        } 

/*
 * Catastale Style particelle
 * style="color: #000000; font-size: 6pt; font-family: \'MS Shell Dlg 2\', sans-serif;"
 */
 function style_Particelle() {
//     switch(String(feature.properties['desc'])) {
//                case 'Baracca':
            return {
                //pane: 'pane_Particelle_1',
                opacity: 1,
                color: 'rgba(35,35,35,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fill: true,
                fillOpacity: 0.5,
                fillColor: 'rgba(232,113,141,1.0)',
                interactive: true,
            }
        } 
        

/*
 * Catstale Style Fabbricati
 */

function style_Fabbricati() {
            return {
                //pane: 'pane_Fabbricati_5',
                opacity: 1,
                color: 'rgba(35,35,35,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fill: true,
                fillOpacity: 0.5,
                fillColor: 'rgba(164,113,88,1.0)',
                interactive: true,
            }
        } 