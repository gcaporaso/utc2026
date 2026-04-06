/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// definisce la forma del retino
var Retino1ZonaA = new L.PatternPath({
        d: 'M0 30 L30 0 M0 60 L60 0 M0 90 L90 0 M30 90 L90 30 M60 90 L90 60 Z',
        // fill: {boolean} (default: false) - Should the shape be filled.
        fill: true,
        // stroke: {boolean} (default: true) - Whether to draw along the path or not.
        stroke:true,
        // color: {color} (default: 3388ff) - Color of the stroke.
        color:'rgb(255,159,127)',
        // weight: {number} (default: 3) - Width of the stroke.
        weight:2,
        // opacity: {0.0 - 1.0} (default: 1.0) - Opacity of the stroke.
        // lineCap: {butt | round | square | inherit} (default: round) - Defines how the stroke looks at its ends
        lineCap: 'round',
        // lineJoin: {butt | round | square | inherit} (default: round) - Defines how the stroke looks at its corners.
        lineJoin: 'round',
        // dashArray: {dashArray} (default: null) - Defines the strokes dash pattern. ex: '5, 5'
        //dashArray: '2,2',
        // dashOffset: {number} (default: null) -
        //dashOffset:5,
        // fillColor: {color} (default: same as color) - Color of the fill.
        //fillOpacity: {0.0 - 1.0} (default: 0.2) - Opacity of the fill.
        fillOpacity:1.0,
        // fillRule: {nonzero | evenodd | inherit} (default: evenodd) -
        fillRule: 'evenodd',
        // fillPattern: {L.Pattern} (default: null) - The pattern to fill the Shape with.

    });


var Retino2ZonaA = new L.PatternPath({
        d: 'M0 15 L15 0 M0 45 L45 0 M0 75 L75 0 M15 90 L90 15 M45 90 L90 45 M75 90 L90 75 Z',
        // fill: {boolean} (default: false) - Should the shape be filled.
        fill: true,
        // stroke: {boolean} (default: true) - Whether to draw along the path or not.
        stroke:true,
        // color: {color} (default: 3388ff) - Color of the stroke.
        color:'rgb(255,159,127)',
        // weight: {number} (default: 3) - Width of the stroke.
        weight:1.5,
        // opacity: {0.0 - 1.0} (default: 1.0) - Opacity of the stroke.
        // lineCap: {butt | round | square | inherit} (default: round) - Defines how the stroke looks at its ends
        lineCap: 'round',
        // lineJoin: {butt | round | square | inherit} (default: round) - Defines how the stroke looks at its corners.
        lineJoin: 'round',
        // dashArray: {dashArray} (default: null) - Defines the strokes dash pattern. ex: '5, 5'
        dashArray: '0.2,5',
        // dashOffset: {number} (default: null) -
        //dashOffset:5,
        // fillColor: {color} (default: same as color) - Color of the fill.
        //fillOpacity: {0.0 - 1.0} (default: 0.2) - Opacity of the fill.
        fillOpacity:1.0,
        // fillRule: {nonzero | evenodd | inherit} (default: evenodd) -
        fillRule: 'evenodd',
        // fillPattern: {L.Pattern} (default: null) - The pattern to fill the Shape with.

    });
    
    
    var Retino1ZonaCI1 = new L.PatternPath({
        d: 'M0 30 L90 30 M0 60 L90 60 M0 90 L90 90 Z',
        // fill: {boolean} (default: false) - Should the shape be filled.
        fill: true,
        // stroke: {boolean} (default: true) - Whether to draw along the path or not.
        stroke:true,
        // color: {color} (default: 3388ff) - Color of the stroke.
        color:'rgb(127,255,223)',
        // weight: {number} (default: 3) - Width of the stroke.
        weight:1.5,
        // opacity: {0.0 - 1.0} (default: 1.0) - Opacity of the stroke.
        // lineCap: {butt | round | square | inherit} (default: round) - Defines how the stroke looks at its ends
        lineCap: 'round',
        // lineJoin: {butt | round | square | inherit} (default: round) - Defines how the stroke looks at its corners.
        lineJoin: 'round',
        // dashArray: {dashArray} (default: null) - Defines the strokes dash pattern. ex: '5, 5'
        //dashArray: '0.2,5',
        // dashOffset: {number} (default: null) -
        //dashOffset:5,
        // fillColor: {color} (default: same as color) - Color of the fill.
        //fillOpacity: {0.0 - 1.0} (default: 0.2) - Opacity of the fill.
        fillOpacity:1.0,
        // fillRule: {nonzero | evenodd | inherit} (default: evenodd) -
        fillRule: 'evenodd',
        // fillPattern: {L.Pattern} (default: null) - The pattern to fill the Shape with.

    });
    
    var Retino1ZonaCI2 = new L.PatternPath({
        d: 'M0 15 L90 15 M0 45 L90 45 M0 75 L90 75 Z',
        // fill: {boolean} (default: false) - Should the shape be filled.
        fill: true,
        // stroke: {boolean} (default: true) - Whether to draw along the path or not.
        stroke:true,
        // color: {color} (default: 3388ff) - Color of the stroke.
        color:'rgb(127,255,223)',
        // weight: {number} (default: 3) - Width of the stroke.
        weight:1.5,
        // opacity: {0.0 - 1.0} (default: 1.0) - Opacity of the stroke.
        // lineCap: {butt | round | square | inherit} (default: round) - Defines how the stroke looks at its ends
        lineCap: 'round',
        // lineJoin: {butt | round | square | inherit} (default: round) - Defines how the stroke looks at its corners.
        lineJoin: 'round',
        // dashArray: {dashArray} (default: null) - Defines the strokes dash pattern. ex: '5, 5'
        dashArray: '10,5',
        // dashOffset: {number} (default: null) -
        //dashOffset:5,
        // fillColor: {color} (default: same as color) - Color of the fill.
        //fillOpacity: {0.0 - 1.0} (default: 0.2) - Opacity of the fill.
        fillOpacity:1.0,
        // fillRule: {nonzero | evenodd | inherit} (default: evenodd) -
        fillRule: 'evenodd',
        // fillPattern: {L.Pattern} (default: null) - The pattern to fill the Shape with.

    });
    

var pattern_A = new L.Pattern({
    width:90, 
    height:90,
    x:1,
    y:2
});
pattern_A.addShape(Retino1ZonaA);
pattern_A.addShape(Retino2ZonaA);


var pattern_CI = new L.Pattern({
    width:90, 
    height:90,
    x:1,
    y:2
});
pattern_CI.addShape(Retino1ZonaCI1);
pattern_CI.addShape(Retino1ZonaCI2);





        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
            weight: 1.2,
            spaceWeight: 2.0,
            color: '#440154',
            opacity: 1,
            spaceOpacity: 0,
            angle: 315
        });
        var pattern_zona_B = new L.StripePattern({
            weight: 0.3,
            spaceWeight: 2.0,
            color: 'rgb(127,223,255)',
            //color: 'blue',
            opacity: 1.0,
            spaceOpacity: 0.5,
            angle: 0
        });


        var pattern_zona_CI1 = new L.StripePattern({
            weight: 0.3,
            spaceWeight: 2.0,
            color: 'rgb(127,255,223)',
            //color: 'blue',
            opacity: 1.0,
            spaceOpacity: 0.5,
            angle: 0
        });



        var pattern_area_sportiva = new L.StripePattern({
            weight: 1.2,
            spaceWeight: 1.5,
            color: '#481567',
            opacity: 1.0,
            spaceOpacity: 0,
            angle: 225
        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#481e70',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#472778',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#462f7e',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#443882',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#414086',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#3f4889',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#3c508a',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#38588c',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#345f8d',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#32668e',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#2e6d8e',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#2b728e',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#29798e',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#27808e',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#24868e',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#218d8d',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#1f938c',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#1e9a8a',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#1fa188',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#22a784',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#26ad81',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#2eb47c',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#38ba77',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#44c070',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#52c569',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#60ca60',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#70cf57',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#81d34d',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#92d842',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#a4db36',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#b7de2a',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#c9e120',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#dce319',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#ede51b',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#fde725',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#440154',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#460b5f',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#481567',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
            weight: 0.2,
            spaceWeight: 3.5,
            color: '#481e70',
            opacity: 1.0,
            spaceOpacity: 0,
            angle: 225
        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#472778',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#462f7e',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#443882',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#414086',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#3f4889',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#3c508a',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#38588c',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#345f8d',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#32668e',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#2e6d8e',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#2b728e',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#29798e',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#27808e',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#24868e',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#218d8d',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#1f938c',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#1e9a8a',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#1fa188',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#22a784',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#26ad81',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#2eb47c',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#38ba77',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#44c070',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#52c569',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#60ca60',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#70cf57',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#81d34d',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#92d842',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#a4db36',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#b7de2a',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#c9e120',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#dce319',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#ede51b',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#fde725',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#440154',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#460b5f',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#481567',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#481e70',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#472778',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#462f7e',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#443882',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#414086',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#3f4889',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#3c508a',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#38588c',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#345f8d',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#32668e',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#2e6d8e',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#2b728e',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#29798e',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#27808e',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#24868e',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#218d8d',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#1f938c',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#1e9a8a',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#1fa188',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#22a784',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#26ad81',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#2eb47c',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#38ba77',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#44c070',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#52c569',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#60ca60',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#70cf57',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#81d34d',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#92d842',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#a4db36',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#b7de2a',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#c9e120',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#dce319',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#ede51b',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#fde725',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#440154',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#460b5f',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#481567',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#481e70',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#472778',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#462f7e',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#443882',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#414086',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#3f4889',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#3c508a',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#38588c',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#345f8d',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#32668e',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#2e6d8e',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#2b728e',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#29798e',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#27808e',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#24868e',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#218d8d',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#1f938c',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#1e9a8a',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#1fa188',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#22a784',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#26ad81',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#2eb47c',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#38ba77',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#44c070',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#52c569',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#60ca60',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#70cf57',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#81d34d',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#92d842',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#a4db36',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#b7de2a',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#c9e120',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#dce319',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#ede51b',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_1 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 1.5,
//            color: '#fde725',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 225
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_1.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#440154',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#460b5f',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#481567',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#481e70',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#472778',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#462f7e',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#443882',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#414086',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#3f4889',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#3c508a',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#38588c',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#345f8d',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#32668e',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#2e6d8e',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#2b728e',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#29798e',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#27808e',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#24868e',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#218d8d',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#1f938c',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#1e9a8a',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#1fa188',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#22a784',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#26ad81',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#2eb47c',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#38ba77',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#44c070',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#52c569',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#60ca60',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#70cf57',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#81d34d',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#92d842',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#a4db36',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#b7de2a',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#c9e120',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#dce319',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#ede51b',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
//        var pattern_PIANOREGOLATOREGENERALE_0_0 = new L.StripePattern({
//            weight: 0.2,
//            spaceWeight: 2.0,
//            color: '#fde725',
//            opacity: 1.0,
//            spaceOpacity: 0,
//            angle: 315
//        });
//        pattern_PIANOREGOLATOREGENERALE_0_0.addTo(map);
        function style_PRG(feature) {
            switch(String(feature.properties['Z'])) {
                case 'A':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_A, //pattern_zona_A,
                fill:true,
                interactive: true,
            }
                    break;
                case 'AR':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_0,
                interactive: true,
            }
                    break;
                case 'AREA SPORTIVA':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_area_sportiva,
                interactive: true,
            }
                    break;
                case 'ASILO - SCUOLA':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_0,
                interactive: true,
            }
                    break;
                case 'B':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_zona_B,
                interactive: true,
            }
                    break;
                case 'B1':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_0,
                interactive: true,
            }
                    break;
                case 'C1':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_0,
                interactive: true,
            }
                    break;
                case 'C10':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_0,
                interactive: true,
            }
                    break;
                case 'C11':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_0,
                interactive: true,
            }
                    break;
                case 'C12':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_0,
                interactive: true,
            }
                    break;
                case 'C1I':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_CI, //pattern_PIANOREGOLATOREGENERALE_0_0,
                interactive: true,
            }
                    break;
                case 'C2':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_0,
                interactive: true,
            }
                    break;
                case 'C2I':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_CI, // pattern_PIANOREGOLATOREGENERALE_0_0,
                interactive: true,
            }
                    break;
                case 'C3':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_0,
                interactive: true,
            }
                    break;
                case 'C3I':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_CI, //pattern_PIANOREGOLATOREGENERALE_0_0,
                interactive: true,
            }
                    break;
                case 'C4':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_0,
                interactive: true,
            }
                    break;
                case 'C4I':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_CI, //pattern_PIANOREGOLATOREGENERALE_0_0,
                interactive: true,
            }
                    break;
                case 'C5':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_0,
                interactive: true,
            }
                    break;
                case 'C6':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_0,
                interactive: true,
            }
                    break;
                case 'C7':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_0,
                interactive: true,
            }
                    break;
                case 'C8':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_0,
                interactive: true,
            }
                    break;
                case 'C9':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_0,
                interactive: true,
            }
                    break;
                case 'CAMPING':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_0,
                interactive: true,
            }
                    break;
                case 'CIMITERO':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_0,
                interactive: true,
            }
                    break;
                case 'CIMITERO - FASCIA RISPETTO':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_0,
                interactive: true,
            }
                    break;
                case 'Ct':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_0,
                interactive: true,
            }
                    break;
                case 'D':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_0,
                interactive: true,
            }
                    break;
                case 'DEPURATORE':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_0,
                interactive: true,
            }
                    break;
                case 'DEPURATORE - FASCIA RISPETTO':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_0,
                interactive: true,
            }
                    break;
                case 'DISCARICA':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_0,
                interactive: true,
            }
                    break;
                case 'DISCARICA - FASCIA RISPETTO':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_0,
                interactive: true,
            }
                    break;
                case 'E':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_0,
                interactive: true,
            }
                    break;
                case 'F':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_0,
                interactive: true,
            }
                    break;
                case 'H':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_0,
                interactive: true,
            }
                    break;
                case 'P':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_0,
                interactive: true,
            }
                    break;
                case 'VERDE PRIVATO':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_0,
                interactive: true,
            }
                    break;
                case 'VERDE PUBBLICO':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_0,
                interactive: true,
            }
                    break;
                default:
//                    return {
//                pane: 'pane_PIANOREGOLATOREGENERALE_0',
//                stroke: false,
//                fillOpacity: 1,
//                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_0,
//                interactive: true,
//            }
                    break;
            }
        }
        function style_PIANOREGOLATOREGENERALE_0_1(feature) {
            switch(String(feature.properties['Z'])) {
                case 'A':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: true,
                fillOpacity: 1,
                fillPattern: pattern_A, //pattern_PIANOREGOLATOREGENERALE_0_1,
                fill:false,
                fillRule:'evenodd',
                interactive: true,
            }
                    break;
                case 'AR':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: true,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_1,
                interactive: true,
            }
                    break;
                case 'AREA SPORTIVA':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: true,
                fillOpacity: 1,
                fillPattern: pattern_area_sportiva,
                interactive: true,
            }
                    break;
                case 'ASILO - SCUOLA':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: true,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_1,
                interactive: true,
            }
                    break;
                case 'B':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: true,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_1,
                interactive: true,
            }
                    break;
                case 'B1':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_1,
                interactive: true,
            }
                    break;
                case 'C1':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_1,
                interactive: true,
            }
                    break;
                case 'C10':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_1,
                interactive: true,
            }
                    break;
                case 'C11':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_1,
                interactive: true,
            }
                    break;
                case 'C12':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_1,
                interactive: true,
            }
                    break;
                case 'C1I':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_1,
                interactive: true,
            }
                    break;
                case 'C2':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_1,
                interactive: true,
            }
                    break;
                case 'C2I':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_1,
                interactive: true,
            }
                    break;
                case 'C3':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_1,
                interactive: true,
            }
                    break;
                case 'C3I':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_1,
                interactive: true,
            }
                    break;
                case 'C4':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_1,
                interactive: true,
            }
                    break;
                case 'C4I':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_1,
                interactive: true,
            }
                    break;
                case 'C5':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_1,
                interactive: true,
            }
                    break;
                case 'C6':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_1,
                interactive: true,
            }
                    break;
                case 'C7':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_1,
                interactive: true,
            }
                    break;
                case 'C8':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_1,
                interactive: true,
            }
                    break;
                case 'C9':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_1,
                interactive: true,
            }
                    break;
                case 'CAMPING':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_1,
                interactive: true,
            }
                    break;
                case 'CIMITERO':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_1,
                interactive: true,
            }
                    break;
                case 'CIMITERO - FASCIA RISPETTO':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_1,
                interactive: true,
            }
                    break;
                case 'Ct':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_1,
                interactive: true,
            }
                    break;
                case 'D':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_1,
                interactive: true,
            }
                    break;
                case 'DEPURATORE':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_1,
                interactive: true,
            }
                    break;
                case 'DEPURATORE - FASCIA RISPETTO':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_1,
                interactive: true,
            }
                    break;
                case 'DISCARICA':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_1,
                interactive: true,
            }
                    break;
                case 'DISCARICA - FASCIA RISPETTO':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_1,
                interactive: true,
            }
                    break;
                case 'E':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_1,
                interactive: true,
            }
                    break;
                case 'F':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_1,
                interactive: true,
            }
                    break;
                case 'H':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_1,
                interactive: true,
            }
                    break;
                case 'P':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_1,
                interactive: true,
            }
                    break;
                case 'VERDE PRIVATO':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_1,
                interactive: true,
            }
                    break;
                case 'VERDE PUBBLICO':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                stroke: false,
                fillOpacity: 1,
                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_1,
                interactive: true,
            }
                    break;
                default:
//                    return {
//                pane: 'pane_PIANOREGOLATOREGENERALE_0',
//                stroke: false,
//                fillOpacity: 1,
//                fillPattern: pattern_PIANOREGOLATOREGENERALE_0_1,
//                interactive: true,
//            }
                    break;
            }
        }
        function style_PIANOREGOLATOREGENERALE_0_2(feature) {
            switch(String(feature.properties['Z'])) {
                case 'A':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                opacity: 1,
                color: 'rgba(0,0,0,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fillOpacity: 0,
                interactive: true,
            }
                    break;
                case 'AR':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                opacity: 1,
                color: 'rgba(0,0,0,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fillOpacity: 0,
                interactive: true,
            }
                    break;
                case 'AREA SPORTIVA':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                opacity: 1,
                color: 'rgba(0,0,0,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fillOpacity: 0,
                interactive: true,
            }
                    break;
                case 'ASILO - SCUOLA':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                opacity: 1,
                color: 'rgba(0,0,0,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fillOpacity: 0,
                interactive: true,
            }
                    break;
                case 'B':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                opacity: 1,
                color: 'rgb(127,223,255)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fillOpacity: 0,
                interactive: true,
            }
                    break;
                case 'B1':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                opacity: 1,
                color: 'rgba(0,0,0,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fillOpacity: 0,
                interactive: true,
            }
                    break;
                case 'C1':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                opacity: 1,
                color: 'rgba(0,0,0,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fillOpacity: 0,
                interactive: true,
            }
                    break;
                case 'C10':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                opacity: 1,
                color: 'rgba(0,0,0,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fillOpacity: 0,
                interactive: true,
            }
                    break;
                case 'C11':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                opacity: 1,
                color: 'rgba(0,0,0,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fillOpacity: 0,
                interactive: true,
            }
                    break;
                case 'C12':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                opacity: 1,
                color: 'rgba(0,0,0,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fillOpacity: 0,
                interactive: true,
            }
                    break;
                case 'C1I':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                opacity: 1,
                color: 'rgba(0,0,0,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fillOpacity: 0,
                interactive: true,
            }
                    break;
                case 'C2':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                opacity: 1,
                color: 'rgba(0,0,0,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fillOpacity: 0,
                interactive: true,
            }
                    break;
                case 'C2I':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                opacity: 1,
                color: 'rgba(0,0,0,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fillOpacity: 0,
                interactive: true,
            }
                    break;
                case 'C3':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                opacity: 1,
                color: 'rgba(0,0,0,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fillOpacity: 0,
                interactive: true,
            }
                    break;
                case 'C3I':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                opacity: 1,
                color: 'rgba(0,0,0,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fillOpacity: 0,
                interactive: true,
            }
                    break;
                case 'C4':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                opacity: 1,
                color: 'rgba(0,0,0,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fillOpacity: 0,
                interactive: true,
            }
                    break;
                case 'C4I':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                opacity: 1,
                color: 'rgba(0,0,0,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fillOpacity: 0,
                interactive: true,
            }
                    break;
                case 'C5':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                opacity: 1,
                color: 'rgba(0,0,0,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fillOpacity: 0,
                interactive: true,
            }
                    break;
                case 'C6':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                opacity: 1,
                color: 'rgba(0,0,0,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fillOpacity: 0,
                interactive: true,
            }
                    break;
                case 'C7':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                opacity: 1,
                color: 'rgba(0,0,0,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fillOpacity: 0,
                interactive: true,
            }
                    break;
                case 'C8':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                opacity: 1,
                color: 'rgba(0,0,0,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fillOpacity: 0,
                interactive: true,
            }
                    break;
                case 'C9':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                opacity: 1,
                color: 'rgba(0,0,0,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fillOpacity: 0,
                interactive: true,
            }
                    break;
                case 'CAMPING':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                opacity: 1,
                color: 'rgba(0,0,0,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fillOpacity: 0,
                interactive: true,
            }
                    break;
                case 'CIMITERO':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                opacity: 1,
                color: 'rgba(0,0,0,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fillOpacity: 0,
                interactive: true,
            }
                    break;
                case 'CIMITERO - FASCIA RISPETTO':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                opacity: 1,
                color: 'rgba(0,0,0,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fillOpacity: 0,
                interactive: true,
            }
                    break;
                case 'Ct':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                opacity: 1,
                color: 'rgba(0,0,0,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fillOpacity: 0,
                interactive: true,
            }
                    break;
                case 'D':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                opacity: 1,
                color: 'rgba(0,0,0,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fillOpacity: 0,
                interactive: true,
            }
                    break;
                case 'DEPURATORE':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                opacity: 1,
                color: 'rgba(0,0,0,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fillOpacity: 0,
                interactive: true,
            }
                    break;
                case 'DEPURATORE - FASCIA RISPETTO':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                opacity: 1,
                color: 'rgba(0,0,0,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fillOpacity: 0,
                interactive: true,
            }
                    break;
                case 'DISCARICA':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                opacity: 1,
                color: 'rgba(0,0,0,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fillOpacity: 0,
                interactive: true,
            }
                    break;
                case 'DISCARICA - FASCIA RISPETTO':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                opacity: 1,
                color: 'rgba(0,0,0,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fillOpacity: 0,
                interactive: true,
            }
                    break;
                case 'E':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                opacity: 1,
                color: 'rgba(0,0,0,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fillOpacity: 0,
                interactive: true,
            }
                    break;
                case 'F':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                opacity: 1,
                color: 'rgba(0,0,0,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fillOpacity: 0,
                interactive: true,
            }
                    break;
                case 'H':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                opacity: 1,
                color: 'rgba(0,0,0,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fillOpacity: 0,
                interactive: true,
            }
                    break;
                case 'P':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                opacity: 1,
                color: 'rgba(0,0,0,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fillOpacity: 0,
                interactive: true,
            }
                    break;
                case 'VERDE PRIVATO':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                opacity: 1,
                color: 'rgba(0,0,0,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fillOpacity: 0,
                interactive: true,
            }
                    break;
                case 'VERDE PUBBLICO':
                    return {
                pane: 'pane_PIANOREGOLATOREGENERALE_0',
                opacity: 1,
                color: 'rgba(0,0,0,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fillOpacity: 0,
                interactive: true,
            }
                    break;
                default:
//                    return {
//                pane: 'pane_PIANOREGOLATOREGENERALE_0',
//                opacity: 1,
//                color: 'rgba(0,0,0,1.0)',
//                dashArray: '',
//                lineCap: 'butt',
//                lineJoin: 'miter',
//                weight: 5.0, 
//                fillOpacity: 0,
//                interactive: true,
//            }
                    break;
            }
        }


//    map.createPane('pane_PIANOREGOLATOREGENERALE_0');
//        map.getPane('pane_PIANOREGOLATOREGENERALE_0').style.zIndex = 400;
//        map.getPane('pane_PIANOREGOLATOREGENERALE_0').style['mix-blend-mode'] = 'normal';
        var layer_PIANOREGOLATOREGENERALE_0 = new L.geoJson.multiStyle(prg, {
            attribution: '',
            interactive: true,
            //dataVar: 'json_PIANOREGOLATOREGENERALE_0',
            dataVar: 'prg',
            layerName: 'layer_PIANOREGOLATOREGENERALE_0',
            pane: 'pane_PIANOREGOLATOREGENERALE_0',
            //onEachFeature: pop_PIANOREGOLATOREGENERALE_0,
            //styles: [style_PIANOREGOLATOREGENERALE_0_0,style_PIANOREGOLATOREGENERALE_0_1,style_PIANOREGOLATOREGENERALE_0_2,]
            styles: [style_PRG,style_PIANOREGOLATOREGENERALE_0_2]
        });
//        bounds_group.addLayer(layer_PIANOREGOLATOREGENERALE_0);
//        map.addLayer(layer_PIANOREGOLATOREGENERALE_0);