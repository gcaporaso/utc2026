/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/*
 * Style per layer CTR Curve di livello
 */
function style_ptp(feature) {
            switch(feature.properties.zonizzazio) {
                case 'R.U.A.':
                    return {
                //pane: 'pane_CTRCURVEDILIVELLOESCARPATE_1',
                fillColor: "#ff7800",
                opacity: 1,
                color: 'rgba(215,25,28,1.0)',
                dashArray: '',
                lineCap: 'square',
                lineJoin: 'bevel',
                weight: 2.0,
                fillOpacity: 0.3,
                interactive: true,
            };
                //    break;
                case 'C.I.P.':
                    return {
                //pane: 'pane_CTRCURVEDILIVELLOESCARPATE_1',
                opacity: 1,
                //color: 'rgba(84,176,74,1.0)',
                fillColor: "#e6e600",
                color: 'rgba(185, 239, 249,1.0)',
                dashArray: '',
                lineCap: 'square',
                lineJoin: 'bevel',
                weight: 1.0,
                fillOpacity: 0.3,
                interactive: true,
            };
                //    break;
                case 'C.A.F.':
                    return {
                //pane: 'pane_CTRCURVEDILIVELLOESCARPATE_1',
                opacity: 1,
                fillColor: "#0000ff",
                color: 'rgba(72,123,182,1.0)',
                dashArray: '',
                lineCap: 'round',
                lineJoin: 'round',
                weight: 1.5,
                fillOpacity: 0.2,
                interactive: true,
            };
            //        break;
                default:
                    return {
                //pane: 'pane_CTRCURVEDILIVELLOESCARPATE_1',
                opacity: 1,
                fillColor: "#e6e600",
                color: 'rgba(96,227,19,1.0)',
                dashArray: '',
                lineCap: 'square',
                lineJoin: 'bevel',
                weight: 1.0,
                fillOpacity: 0.3,
                interactive: true,
            };
              //      break;
            }
        } 

