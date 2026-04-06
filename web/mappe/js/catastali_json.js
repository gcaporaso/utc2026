function layer_catastali(map, cartella){
    map.createPane('pane_PIANOREGOLATOREGENERALE_0');
    map.getPane('pane_PIANOREGOLATOREGENERALE_0').style.zIndex = 400;
    map.getPane('pane_PIANOREGOLATOREGENERALE_0').style['mix-blend-mode'] = 'normal';

            function getSimboloIcon(tipo) {
                switch (tipo) {
                    case '1': return L.divIcon({ html: '+', className: 'symb', iconSize: [20,20] });
                    case '9': return L.divIcon({ html: '{', className: 'symb', iconSize: [20,20] });
                    // aggiungi mapping con immagini o font
                    default: return L.divIcon({ html: '?', className: 'symb', iconSize: [20,20] });
                }
            }
            function getLineaStyle(tipolinea) {
                switch (tipolinea) {
                    case '1': return { color: 'black', weight: 1, dashArray: null };       // continua
                    case '5': return { color: 'black', weight: 1, dashArray: '5,5' };      // tratteggiata
                    case '6': return { color: 'black', weight: 1, dashArray: '1,6' };      // puntinata
                    default: return { color: 'gray', weight: 1 };
                }
            }
    var catastali = L.geoJSON(null, {
        filter: function (feature) {
            return feature.properties && feature.properties.LIVELLO === 'PARTICELLE';
        }
    });
    // ****************** FOGLIO 1 ******************************************************
    $.getJSON(cartella+'/B542_000100.geojson', function (data) {
            particelleLayer = L.Proj.geoJson(data, {
            filter: function (feature) {
                return feature.properties && feature.properties.LIVELLO === 'PARTICELLE';
            },
            style: function (feature) {
                return {
                    color: 'cyan',
                    weight: 1,
                    fillColor: 'cyan',
                    fillOpacity: 0
                };
            },
            onEachFeature: function (feature, layer) {
                if (feature.properties && feature.properties.CODICE) {
                    layer.bindTooltip(feature.properties.CODICE, {
                        permanent: true,
                        direction: 'center',
                        className: 'etichetta-particelle'
                    });
                }
            }
            });
            catastali.addLayer(particelleLayer);

            // layer fabbricati
            var fabbricatiLayer = L.Proj.geoJson(data, {
                filter: function (feature) {
                    return feature.properties && feature.properties.LIVELLO === 'FABBRICATI';
                },
                style: function (feature) {
                    return {
                        color: 'red',
                        weight: 2,
                        fillColor: 'red',
                        fillOpacity: 0.3
                    };
                },
                onEachFeature: function (feature, layer) {
                    if (feature.properties && feature.properties.codice) {
                        layer.bindTooltip('Fabbricato: ' + feature.properties.codice, {
                            permanent: false,
                            direction: 'top'
                        });
                    }
                }
            });

            var stradeLayer = L.Proj.geoJson(data, {
                filter: function (feature) {
                    return feature.properties && feature.properties.LIVELLO === 'STRADE';
                },
                style: function (feature) {
                    return {
                        color: 'green',
                        weight: 1,
                        fillColor: 'grey',
                        fillOpacity: 0.3
                    };
                }
            });
            var acqueLayer = L.Proj.geoJson(data, {
                filter: function (feature) {
                    return feature.properties && feature.properties.LIVELLO === 'ACQUE';
                },
                style: function (feature) {
                    return {
                        color: 'blue',
                        weight: 1,
                        fillColor: '#5AC5EC',
                        fillOpacity: 0.3
                    };
                }
            });

        


         //console.log('CRS MAPPA =',map.options.crs);
            // var testiLayer = L.geoJSON(data, {
            //     filter: f => f.properties.LIVELLO === 'TESTI',
            //     pointToLayer: function (feature, latlng) {
            //         let testo   = feature.properties.TESTO || '';
            //         let angolo  = feature.properties.ANGOLO || 0;
            //         let altezza = feature.properties.ALTEZZA || 12;
            //         //console.log('Creo testo:', testo, 'angolo:', angolo, 'altezza:', altezza, latlng);
            //         //console.log('Valore LIVELLO:', feature.properties.LIVELLO);
            //     //     console.log('LatLng:', latlng, 'Orig:', feature.geometry.coordinates);
            //     //     var coords = feature.geometry.coordinates; // [x, y] EPSG:7792
            //     //     var latlng = proj4('EPSG:7792','EPSG:6706', coords); // restituisce [x', y']
            //     //     latlng = [latlng[1], latlng[0]]; 
            //     //    console.log('nuove LatLng:', latlng);
            //         let icon = L.divIcon({
            //             className: 'text-label', // definita in CSS
            //             html: '<div style=\"transform: rotate(' + angolo + 'deg); white-space:nowrap;\">' + testo + '</div>',
            //             iconSize: [5,1] // lascia che la dimensione si adatti al contenuto
            //         });
            //         return L.marker(latlng, { icon: icon, interactive: false });
            //     }
            // });


            

            // var simboliLayer = L.geoJSON(null, {
            //     filter: f => f.properties.LIVELLO === 'SIMBOLI',
            //     pointToLayer: function (feature, latlng) {
            //         return L.marker(latlng, { icon: getSimboloIcon(feature.properties.TIPO) });
            //     }
            // });

          
            

            // var lineevarieLayer = L.geoJSON(null, {
            //     filter: f => f.properties.LIVELLO === 'LINEEVARIE',
            //     style: f => getLineaStyle(f.properties.TIPOLINEA)
            // });


        // stradeLayer.addData(data);
        // acqueLayer.addData(data);
        // testiLayer.addData(data);
        // simboliLayer.addData(data);
        // fiducialiLayer.addData(data);
        // lineevarieLayer.addData(data);



         // aggiungo i due layer al nodo JSON Catasto
            overlaysTree.children[1].children[0].children.push({
                label: 'Particelle',
                layer: particelleLayer
            });

            overlaysTree.children[1].children[0].children.push({
                label: 'Fabbricati',
                layer: fabbricatiLayer
            });
            overlaysTree.children[1].children[0].children.push({
                label: 'Strade',
                layer: stradeLayer
            });
            overlaysTree.children[1].children[0].children.push({
                label: 'Acque',
                layer: acqueLayer
            });
            // overlaysTree.children[1].children[0].children.push({
            //     label: 'Testi',
            //     layer: testiLayer
            // });
            
            // overlaysTree.children[1].children[0].children.push({
            //     label: 'Simboli',
            //     layer: simboliLayer
            // });
            // overlaysTree.children[1].children[0].children.push({
            //     label: 'Linee Varie',
            //     layer: lineevarieLayer
            // });



             // aggiorno il controllo
        myTree.setOverlayTree(overlaysTree);
        // particelleLayer.addTo(map);
        // fabbricatiLayer.addTo(map);
        //testiLayer.addTo(map);
        // fiducialiLayer.addTo(map);
        // simboliLayer.addTo(map);
        // lineevarieLayer.addTo(map);
        //map.fitBounds(testiLayer.getBounds());
    });
    // ***************** layer del foglio 2 ***********************************************************************
    $.getJSON(cartella +'/B542_000200.geojson', function (data) {
            var particelle2Layer = L.Proj.geoJson(data, {
            filter: function (feature) {
                return feature.properties && feature.properties.LIVELLO === 'PARTICELLE';
            },
            style: function (feature) {
                return {
                    color: 'cyan',
                    weight: 1,
                    fillColor: 'cyan',
                    fillOpacity: 0
                };
            },
            onEachFeature: function (feature, layer) {
                if (feature.properties && feature.properties.CODICE) {
                    layer.bindTooltip(feature.properties.CODICE, {
                        permanent: true,
                        direction: 'center',
                        className: 'etichetta-particelle'
                    });
                }
            }
            });

            catastali.addLayer(particelle2Layer);
            // layer fabbricati
            var fabbricati2Layer = L.Proj.geoJson(data, {
                filter: function (feature) {
                    return feature.properties && feature.properties.LIVELLO === 'FABBRICATI';
                },
                style: function (feature) {
                    return {
                        color: 'red',
                        weight: 2,
                        fillColor: 'red',
                        fillOpacity: 0.3
                    };
                },
                
            });

            var strade2Layer = L.Proj.geoJson(data, {
                filter: function (feature) {
                    return feature.properties && feature.properties.LIVELLO === 'STRADE';
                },
                style: function (feature) {
                    return {
                        color: 'green',
                        weight: 1,
                        fillColor: 'grey',
                        fillOpacity: 0.3
                    };
                }
            });
            var acque2Layer = L.Proj.geoJson(data, {
                filter: function (feature) {
                    return feature.properties && feature.properties.LIVELLO === 'ACQUE';
                },
                style: function (feature) {
                    return {
                        color: 'blue',
                        weight: 1,
                        fillColor: '#5AC5EC',
                        fillOpacity: 0.3
                    };
                }
            });

            // var testi2Layer = L.geoJSON(data, {
            //     filter: f => f.properties.LIVELLO === 'TESTI',
            //     pointToLayer: function (feature, latlng) {
            //         let testo   = feature.properties.TESTO || '';
            //         let angolo  = feature.properties.ANGOLO || 0;
            //         let altezza = feature.properties.ALTEZZA/3|| 12;
            //         console.log('testo = ',testo,' angolo = ',angolo, 'altezza = ',altezza);
            //         //console.log('Testo Coordinate Originarie:',latlng);
            //         var tlatlng = proj4(S7792,S6706, [latlng.lng,latlng.lat]);
            //         var leafletLatLng = L.latLng(tlatlng[1], tlatlng[0]);
            //         //console.log('Testo Coordinate Trasformate:',tlatlng);
            //         return L.marker(leafletLatLng, {
            //             icon: L.divIcon({
            //                 className: 'label-testo',
            //                 html: '<div style="transform: rotate(' + angolo + 'deg); font-size:' + altezza + 'px;">' + testo + '</div>',
            //                 iconSize: null,
            //                 interactive: false
            //             })
            //         });
            //     }
            // });

            

            // var simboli2Layer = L.geoJSON(null, {
            //     filter: f => f.properties.LIVELLO === 'SIMBOLI',
            //     pointToLayer: function (feature, latlng) {
            //         return L.marker(latlng, { icon: getSimboloIcon(feature.properties.TIPO) });
            //     }
            // });

          

            
           

            // var lineevarie2Layer = L.geoJSON(null, {
            //     filter: f => f.properties.LIVELLO === 'LINEEVARIE',
            //     style: f => getLineaStyle(f.properties.TIPOLINEA)
            // });

        // acque2Layer.addData(data);
        // strade2Layer.addData(data);    
        // testi2Layer.addData(data);
        // simboli2Layer.addData(data);
        // fiduciali2Layer.addData(data);
        // lineevarie2Layer.addData(data);
        
             
        



            // aggiungo i due layer al nodo JSON Catasto
            overlaysTree.children[1].children[1].children.push({
                label: 'Particelle',
                layer: particelle2Layer
            });

            overlaysTree.children[1].children[1].children.push({
                label: 'Fabbricati',
                layer: fabbricati2Layer
            });
            overlaysTree.children[1].children[1].children.push({
                label: 'Acque',
                layer: acque2Layer
            });
            overlaysTree.children[1].children[1].children.push({
                label: 'Strade',
                layer: strade2Layer
            });

            // overlaysTree.children[1].children[1].children.push({
            //     label: 'Testi',
            //     layer: testi2Layer
            // });
            
            // overlaysTree.children[1].children[1].children.push({
            //     label: 'Simboli',
            //     layer: simboli2Layer
            // });
            // overlaysTree.children[1].children[1].children.push({
            //     label: 'Linee Varie',
            //     layer: lineevarie2Layer
            // });


         // aggiorno il controllo
        myTree.setOverlayTree(overlaysTree);
        // particelle2Layer.addTo(map);
        // fabbricati2Layer.addTo(map);
        // testi2Layer.addTo(map);
        // fiduciali2Layer.addTo(map);
        // simboli2Layer.addTo(map);
        // lineevarie2Layer.addTo(map);
    });
    // ************************ layer del foglio 3 ******************************************************
    $.getJSON(cartella +'/B542_000300.geojson', function (data) {
            var particelle3Layer = L.Proj.geoJson(data, {
            filter: function (feature) {
                return feature.properties && feature.properties.LIVELLO === 'PARTICELLE';
            },
            style: function (feature) {
                return {
                    color: 'cyan',
                    weight: 1,
                    fillColor: 'cyan',
                    fillOpacity: 0
                };
            },
            onEachFeature: function (feature, layer) {
                if (feature.properties && feature.properties.CODICE) {
                    layer.bindTooltip(feature.properties.CODICE, {
                        permanent: true,
                        direction: 'center',
                        className: 'etichetta-particelle'
                    });
                }
            }
            });

            catastali.addLayer(particelle3Layer);

           
            // layer fabbricati
            var fabbricati3Layer = L.Proj.geoJson(data, {
                filter: function (feature) {
                    return feature.properties && feature.properties.LIVELLO === 'FABBRICATI';
                },
                style: function (feature) {
                    return {
                        color: 'red',
                        weight: 2,
                        fillColor: 'red',
                        fillOpacity: 0.3
                    };
                },
                
            });

            var strade3Layer = L.Proj.geoJson(data, {
                filter: function (feature) {
                    return feature.properties && feature.properties.LIVELLO === 'STRADE';
                },
                style: function (feature) {
                    return {
                        color: 'green',
                        weight: 1,
                        fillColor: 'grey',
                        fillOpacity: 0.3
                    };
                }
            });
            var acque3Layer = L.Proj.geoJson(data, {
                filter: function (feature) {
                    return feature.properties && feature.properties.LIVELLO === 'ACQUE';
                },
                style: function (feature) {
                    return {
                        color: 'blue',
                        weight: 1,
                        fillColor: '#5AC5EC',
                        fillOpacity: 0.3
                    };
                }
            });

            // var testi3Layer = L.geoJSON(data, {
            //     filter: f => f.properties.LIVELLO === 'TESTI',
            //     pointToLayer: function (feature, latlng) {
            //         let testo   = feature.properties.TESTO || '';
            //         let angolo  = feature.properties.ANGOLO || 0;
            //         let altezza = feature.properties.ALTEZZA || 12;
            //         let icon = L.divIcon({
            //             className: 'text-label', // definita in CSS
            //             html: '<div style=\"transform: rotate(' + angolo + 'deg); white-space:nowrap;\">' + testo + '</div>',
            //             iconSize: [5,1] // lascia che la dimensione si adatti al contenuto
            //         });
            //         return L.marker(latlng, { icon: icon, interactive: false });
            //     }
            // });

            // var simboli3Layer = L.geoJSON(null, {
            //     filter: f => f.properties.LIVELLO === 'SIMBOLI',
            //     pointToLayer: function (feature, latlng) {
            //         return L.marker(latlng, { icon: getSimboloIcon(feature.properties.TIPO) });
            //     }
            // });

           

            

            // var lineevarie3Layer = L.geoJSON(null, {
            //     filter: f => f.properties.LIVELLO === 'LINEEVARIE',
            //     style: f => getLineaStyle(f.properties.TIPOLINEA)
            // });


       

            overlaysTree.children[1].children[2].children.push({
                label: 'Particelle',
                layer: particelle3Layer
            });
            overlaysTree.children[1].children[2].children.push({
                label: 'Fabbricati',
                layer: fabbricati3Layer
            });
            overlaysTree.children[1].children[2].children.push({
                label: 'Acque',
                layer: acque3Layer
            });
            overlaysTree.children[1].children[2].children.push({
                label: 'Strade',
                layer: strade3Layer
            });

            // overlaysTree.children[1].children[2].children.push({
            //     label: 'Testi',
            //     layer: testi3Layer
            // });
           
            // overlaysTree.children[1].children[2].children.push({
            //     label: 'Simboli',
            //     layer: simboli3Layer
            // });
            // overlaysTree.children[1].children[2].children.push({
            //     label: 'Linee Varie',
            //     layer: lineevarie3Layer
            // });


            myTree.setOverlayTree(overlaysTree);
        });

        // *************************** FOGLIO 4 ******************************************************************
        $.getJSON(cartella +'/B542_000400.geojson', function (data) {
            var particelle4Layer = L.Proj.geoJson(data, {
            filter: function (feature) {
                return feature.properties && feature.properties.LIVELLO === 'PARTICELLE';
            },
            style: function (feature) {
                return {
                    color: 'cyan',
                    weight: 1,
                    fillColor: 'cyan',
                    fillOpacity: 0
                };
            },
            onEachFeature: function (feature, layer) {
                if (feature.properties && feature.properties.CODICE) {
                    layer.bindTooltip(feature.properties.CODICE, {
                        permanent: true,
                        direction: 'center',
                        className: 'etichetta-particelle'
                    });
                }
            }
            });
            // layer fabbricati
            var fabbricati4Layer = L.Proj.geoJson(data, {
                filter: function (feature) {
                    return feature.properties && feature.properties.LIVELLO === 'FABBRICATI';
                },
                style: function (feature) {
                    return {
                        color: 'red',
                        weight: 2,
                        fillColor: 'red',
                        fillOpacity: 0.3
                    };
                },
             });

            var strade4Layer = L.Proj.geoJson(data, {
                filter: function (feature) {
                    return feature.properties && feature.properties.LIVELLO === 'STRADE';
                },
                style: function (feature) {
                    return {
                        color: 'green',
                        weight: 1,
                        fillColor: 'grey',
                        fillOpacity: 0.3
                    };
                }
            });
            var acque4Layer = L.Proj.geoJson(data, {
                filter: function (feature) {
                    return feature.properties && feature.properties.LIVELLO === 'ACQUE';
                },
                style: function (feature) {
                    return {
                        color: 'blue',
                        weight: 1,
                        fillColor: '#5AC5EC',
                        fillOpacity: 0.3
                    };
                }
            });

            // var testi4Layer = L.geoJSON(data, {
            //     filter: f => f.properties.LIVELLO === 'TESTI',
            //     pointToLayer: function (feature, latlng) {
            //         let testo   = feature.properties.TESTO || '';
            //         let angolo  = feature.properties.ANGOLO || 0;
            //         let altezza = feature.properties.ALTEZZA || 12;
            //         let icon = L.divIcon({
            //             className: 'text-label', // definita in CSS
            //             html: '<div style=\"transform: rotate(' + angolo + 'deg); white-space:nowrap;\">' + testo + '</div>',
            //             iconSize: [5,1] // lascia che la dimensione si adatti al contenuto
            //         });
            //         return L.marker(latlng, { icon: icon, interactive: false });
            //     }
            // });

            // var simboli4Layer = L.geoJSON(null, {
            //     filter: f => f.properties.LIVELLO === 'SIMBOLI',
            //     pointToLayer: function (feature, latlng) {
            //         return L.marker(latlng, { icon: getSimboloIcon(feature.properties.TIPO) });
            //     }
            // });

           

            

            // var lineevarie4Layer = L.geoJSON(null, {
            //     filter: f => f.properties.LIVELLO === 'LINEEVARIE',
            //     style: f => getLineaStyle(f.properties.TIPOLINEA)
            // });





            catastali.addLayer(particelle4Layer);
            overlaysTree.children[1].children[3].children.push({
                label: 'Particelle',
                layer: particelle4Layer
            });
             overlaysTree.children[1].children[3].children.push({
                label: 'Fabbricati',
                layer: fabbricati4Layer
            });
            overlaysTree.children[1].children[3].children.push({
                label: 'Acque',
                layer: acque4Layer
            });
            overlaysTree.children[1].children[3].children.push({
                label: 'Strade',
                layer: strade4Layer
            });

            // overlaysTree.children[1].children[3].children.push({
            //     label: 'Testi',
            //     layer: testi4Layer
            // });
            
            // overlaysTree.children[1].children[3].children.push({
            //     label: 'Simboli',
            //     layer: simboli4Layer
            // });
            // overlaysTree.children[1].children[3].children.push({
            //     label: 'Linee Varie',
            //     layer: lineevarie4Layer
            // });


            myTree.setOverlayTree(overlaysTree);
        });

$.getJSON(cartella +'/B542_000500.geojson', function (data) {
            var particelle5Layer = L.Proj.geoJson(data, {
            filter: function (feature) {
                return feature.properties && feature.properties.LIVELLO === 'PARTICELLE';
            },
            style: function (feature) {
                return {
                    color: 'cyan',
                    weight: 1,
                    fillColor: 'cyan',
                    fillOpacity: 0
                };
            },
            onEachFeature: function (feature, layer) {
                if (feature.properties && feature.properties.CODICE) {
                    layer.bindTooltip(feature.properties.CODICE, {
                        permanent: true,
                        direction: 'center',
                        className: 'etichetta-particelle'
                    });
                }
            }
            });
            // layer fabbricati
            var fabbricati5Layer = L.Proj.geoJson(data, {
                filter: function (feature) {
                    return feature.properties && feature.properties.LIVELLO === 'FABBRICATI';
                },
                style: function (feature) {
                    return {
                        color: 'red',
                        weight: 2,
                        fillColor: 'red',
                        fillOpacity: 0.3
                    };
                },
                
            });
            var strade5Layer = L.Proj.geoJson(data, {
                filter: function (feature) {
                    return feature.properties && feature.properties.LIVELLO === 'STRADE';
                },
                style: function (feature) {
                    return {
                        color: 'green',
                        weight: 1,
                        fillColor: 'grey',
                        fillOpacity: 0.3
                    };
                }
            });
            var acque5Layer = L.Proj.geoJson(data, {
                filter: function (feature) {
                    return feature.properties && feature.properties.LIVELLO === 'ACQUE';
                },
                style: function (feature) {
                    return {
                        color: 'blue',
                        weight: 1,
                        fillColor: '#5AC5EC',
                        fillOpacity: 0.3
                    };
                }
            });

            // var testi5Layer = L.geoJSON(data, {
            //     filter: f => f.properties.LIVELLO === 'TESTI',
            //     pointToLayer: function (feature, latlng) {
            //         let testo   = feature.properties.TESTO || '';
            //         let angolo  = feature.properties.ANGOLO || 0;
            //         let altezza = feature.properties.ALTEZZA || 12;
            //         let icon = L.divIcon({
            //             className: 'text-label', // definita in CSS
            //             html: '<div style=\"transform: rotate(' + angolo + 'deg); white-space:nowrap;\">' + testo + '</div>',
            //             iconSize: [5,1] // lascia che la dimensione si adatti al contenuto
            //         });
            //         return L.marker(latlng, { icon: icon, interactive: false });
            //     }
            // });

            // var simboli5Layer = L.geoJSON(null, {
            //     filter: f => f.properties.LIVELLO === 'SIMBOLI',
            //     pointToLayer: function (feature, latlng) {
            //         return L.marker(latlng, { icon: getSimboloIcon(feature.properties.TIPO) });
            //     }
            // });

            

            

            // var lineevarie5Layer = L.geoJSON(null, {
            //     filter: f => f.properties.LIVELLO === 'LINEEVARIE',
            //     style: f => getLineaStyle(f.properties.TIPOLINEA)
            // });





            catastali.addLayer(particelle5Layer);
            overlaysTree.children[1].children[4].children.push({
                label: 'Particelle',
                layer: particelle5Layer
            });
             overlaysTree.children[1].children[4].children.push({
                label: 'Fabbricati',
                layer: fabbricati5Layer
            });
            overlaysTree.children[1].children[4].children.push({
                label: 'Acque',
                layer: acque5Layer
            });
            overlaysTree.children[1].children[4].children.push({
                label: 'Strade',
                layer: strade5Layer
            });

            // overlaysTree.children[1].children[4].children.push({
            //     label: 'Testi',
            //     layer: testi5Layer
            // });
            
            // overlaysTree.children[1].children[4].children.push({
            //     label: 'Simboli',
            //     layer: simboli5Layer
            // });
            // overlaysTree.children[1].children[4].children.push({
            //     label: 'Linee Varie',
            //     layer: lineevarie5Layer
            // });


            myTree.setOverlayTree(overlaysTree);
        });


        $.getJSON(cartella +'/B542_000600.geojson', function (data) {
            var particelle6Layer = L.Proj.geoJson(data, {
            filter: function (feature) {
                return feature.properties && feature.properties.LIVELLO === 'PARTICELLE';
            },
            style: function (feature) {
                return {
                    color: 'cyan',
                    weight: 1,
                    fillColor: 'cyan',
                    fillOpacity: 0
                };
            },
            onEachFeature: function (feature, layer) {
                if (feature.properties && feature.properties.CODICE) {
                    layer.bindTooltip(feature.properties.CODICE, {
                        permanent: true,
                        direction: 'center',
                        className: 'etichetta-particelle'
                    });
                }
            }
            });
            // layer fabbricati
            var fabbricati6Layer = L.Proj.geoJson(data, {
                filter: function (feature) {
                    return feature.properties && feature.properties.LIVELLO === 'FABBRICATI';
                },
                style: function (feature) {
                    return {
                        color: 'red',
                        weight: 2,
                        fillColor: 'red',
                        fillOpacity: 0.3
                    };
                },
                
            });
            var strade6Layer = L.Proj.geoJson(data, {
                filter: function (feature) {
                    return feature.properties && feature.properties.LIVELLO === 'STRADE';
                },
                style: function (feature) {
                    return {
                        color: 'green',
                        weight: 1,
                        fillColor: 'grey',
                        fillOpacity: 0.3
                    };
                }
            });
            var acque6Layer = L.Proj.geoJson(data, {
                filter: function (feature) {
                    return feature.properties && feature.properties.LIVELLO === 'ACQUE';
                },
                style: function (feature) {
                    return {
                        color: 'blue',
                        weight: 1,
                        fillColor: '#5AC5EC',
                        fillOpacity: 0.3
                    };
                }
            });

            // var testi6Layer = L.geoJSON(data, {
            //     filter: f => f.properties.LIVELLO === 'TESTI',
            //     pointToLayer: function (feature, latlng) {
            //         let testo   = feature.properties.TESTO || '';
            //         let angolo  = feature.properties.ANGOLO || 0;
            //         let altezza = feature.properties.ALTEZZA || 12;
            //         let icon = L.divIcon({
            //             className: 'text-label', // definita in CSS
            //             html: '<div style=\"transform: rotate(' + angolo + 'deg); white-space:nowrap;\">' + testo + '</div>',
            //             iconSize: [5,1] // lascia che la dimensione si adatti al contenuto
            //         });
            //         return L.marker(latlng, { icon: icon, interactive: false });
            //     }
            // });

            // var simboli6Layer = L.geoJSON(null, {
            //     filter: f => f.properties.LIVELLO === 'SIMBOLI',
            //     pointToLayer: function (feature, latlng) {
            //         return L.marker(latlng, { icon: getSimboloIcon(feature.properties.TIPO) });
            //     }
            // });

           

            

            // var lineevarie6Layer = L.geoJSON(null, {
            //     filter: f => f.properties.LIVELLO === 'LINEEVARIE',
            //     style: f => getLineaStyle(f.properties.TIPOLINEA)
            // });




            catastali.addLayer(particelle6Layer);
            overlaysTree.children[1].children[5].children.push({
                label: 'Particelle',
                layer: particelle6Layer
            });
             overlaysTree.children[1].children[5].children.push({
                label: 'Fabbricati',
                layer: fabbricati6Layer
            });
            overlaysTree.children[1].children[5].children.push({
                label: 'Acque',
                layer: acque6Layer
            });
            overlaysTree.children[1].children[5].children.push({
                label: 'Strade',
                layer: strade6Layer
            });

            // overlaysTree.children[1].children[5].children.push({
            //     label: 'Testi',
            //     layer: testi6Layer
            // });
            
            // overlaysTree.children[1].children[5].children.push({
            //     label: 'Simboli',
            //     layer: simboli6Layer
            // });
            // overlaysTree.children[1].children[5].children.push({
            //     label: 'Linee Varie',
            //     layer: lineevarie6Layer
            // });



            myTree.setOverlayTree(overlaysTree);
        });

        $.getJSON(cartella +'/B542_000700.geojson', function (data) {
            var particelle7Layer = L.Proj.geoJson(data, {
            filter: function (feature) {
                return feature.properties && feature.properties.LIVELLO === 'PARTICELLE';
            },
            style: function (feature) {
                return {
                    color: 'cyan',
                    weight: 1,
                    fillColor: 'cyan',
                    fillOpacity: 0
                };
            },
            onEachFeature: function (feature, layer) {
                if (feature.properties && feature.properties.CODICE) {
                    layer.bindTooltip(feature.properties.CODICE, {
                        permanent: true,
                        direction: 'center',
                        className: 'etichetta-particelle'
                    });
                }
            }
            });
            // layer fabbricati
            var fabbricati7Layer = L.Proj.geoJson(data, {
                filter: function (feature) {
                    return feature.properties && feature.properties.LIVELLO === 'FABBRICATI';
                },
                style: function (feature) {
                    return {
                        color: 'red',
                        weight: 2,
                        fillColor: 'red',
                        fillOpacity: 0.3
                    };
                },
                
            });
            var strade7Layer = L.Proj.geoJson(data, {
                filter: function (feature) {
                    return feature.properties && feature.properties.LIVELLO === 'STRADE';
                },
                style: function (feature) {
                    return {
                        color: 'green',
                        weight: 1,
                        fillColor: 'grey',
                        fillOpacity: 0.3
                    };
                }
            });
            var acque7Layer = L.Proj.geoJson(data, {
                filter: function (feature) {
                    return feature.properties && feature.properties.LIVELLO === 'ACQUE';
                },
                style: function (feature) {
                    return {
                        color: 'blue',
                        weight: 1,
                        fillColor: '#5AC5EC',
                        fillOpacity: 0.3
                    };
                }
            });

            // var testi7Layer = L.geoJSON(data, {
            //     filter: f => f.properties.LIVELLO === 'TESTI',
            //     pointToLayer: function (feature, latlng) {
            //         let testo   = feature.properties.TESTO || '';
            //         let angolo  = feature.properties.ANGOLO || 0;
            //         let altezza = feature.properties.ALTEZZA || 12;
            //         let icon = L.divIcon({
            //             className: 'text-label', // definita in CSS
            //             html: '<div style=\"transform: rotate(' + angolo + 'deg); white-space:nowrap;\">' + testo + '</div>',
            //             iconSize: [5,1] // lascia che la dimensione si adatti al contenuto
            //         });
            //         return L.marker(latlng, { icon: icon, interactive: false });
            //     }
            // });

            // var simboli7Layer = L.geoJSON(null, {
            //     filter: f => f.properties.LIVELLO === 'SIMBOLI',
            //     pointToLayer: function (feature, latlng) {
            //         return L.marker(latlng, { icon: getSimboloIcon(feature.properties.TIPO) });
            //     }
            // });

            

            

            // var lineevarie7Layer = L.geoJSON(null, {
            //     filter: f => f.properties.LIVELLO === 'LINEEVARIE',
            //     style: f => getLineaStyle(f.properties.TIPOLINEA)
            // });



            catastali.addLayer(particelle7Layer);
            overlaysTree.children[1].children[6].children.push({
                label: 'Particelle',
                layer: particelle7Layer
            });
             overlaysTree.children[1].children[6].children.push({
                label: 'Fabbricati',
                layer: fabbricati7Layer
            });
            overlaysTree.children[1].children[6].children.push({
                label: 'Acque',
                layer: acque7Layer
            });
            overlaysTree.children[1].children[6].children.push({
                label: 'Strade',
                layer: strade7Layer
            });

            // overlaysTree.children[1].children[6].children.push({
            //     label: 'Testi',
            //     layer: testi7Layer
            // });
            
            // overlaysTree.children[1].children[6].children.push({
            //     label: 'Simboli',
            //     layer: simboli7Layer
            // });
            // overlaysTree.children[1].children[6].children.push({
            //     label: 'Linee Varie',
            //     layer: lineevarie7Layer
            // });


            myTree.setOverlayTree(overlaysTree);
        });

        $.getJSON(cartella +'/B542_000800.geojson', function (data) {
            var particelle8Layer = L.Proj.geoJson(data, {
            filter: function (feature) {
                return feature.properties && feature.properties.LIVELLO === 'PARTICELLE';
            },
            style: function (feature) {
                return {
                    color: 'cyan',
                    weight: 1,
                    fillColor: 'cyan',
                    fillOpacity: 0
                };
            },
            onEachFeature: function (feature, layer) {
                if (feature.properties && feature.properties.CODICE) {
                    layer.bindTooltip(feature.properties.CODICE, {
                        permanent: true,
                        direction: 'center',
                        className: 'etichetta-particelle'
                    });
                }
            }
            });
            // layer fabbricati
            var fabbricati8Layer = L.Proj.geoJson(data, {
                filter: function (feature) {
                    return feature.properties && feature.properties.LIVELLO === 'FABBRICATI';
                },
                style: function (feature) {
                    return {
                        color: 'red',
                        weight: 2,
                        fillColor: 'red',
                        fillOpacity: 0.3
                    };
                },
                
            });
            var strade8Layer = L.Proj.geoJson(data, {
                filter: function (feature) {
                    return feature.properties && feature.properties.LIVELLO === 'STRADE';
                },
                style: function (feature) {
                    return {
                        color: 'green',
                        weight: 1,
                        fillColor: 'grey',
                        fillOpacity: 0.3
                    };
                }
            });
            var acque8Layer = L.Proj.geoJson(data, {
                filter: function (feature) {
                    return feature.properties && feature.properties.LIVELLO === 'ACQUE';
                },
                style: function (feature) {
                    return {
                        color: 'blue',
                        weight: 1,
                        fillColor: '#5AC5EC',
                        fillOpacity: 0.3
                    };
                }
            });

            // var testi8Layer = L.geoJSON(data, {
            //     filter: f => f.properties.LIVELLO === 'TESTI',
            //     pointToLayer: function (feature, latlng) {
            //         let testo   = feature.properties.TESTO || '';
            //         let angolo  = feature.properties.ANGOLO || 0;
            //         let altezza = feature.properties.ALTEZZA || 12;
            //         let icon = L.divIcon({
            //             className: 'text-label', // definita in CSS
            //             html: '<div style=\"transform: rotate(' + angolo + 'deg); white-space:nowrap;\">' + testo + '</div>',
            //             iconSize: [5,1] // lascia che la dimensione si adatti al contenuto
            //         });
            //         return L.marker(latlng, { icon: icon, interactive: false });
            //     }
            // });

            // var simboli8Layer = L.geoJSON(null, {
            //     filter: f => f.properties.LIVELLO === 'SIMBOLI',
            //     pointToLayer: function (feature, latlng) {
            //         return L.marker(latlng, { icon: getSimboloIcon(feature.properties.TIPO) });
            //     }
            // });

                     

            // var lineevarie8Layer = L.geoJSON(null, {
            //     filter: f => f.properties.LIVELLO === 'LINEEVARIE',
            //     style: f => getLineaStyle(f.properties.TIPOLINEA)
            // });



            catastali.addLayer(particelle8Layer);
            overlaysTree.children[1].children[7].children.push({
                label: 'Particelle',
                layer: particelle8Layer
            });
             overlaysTree.children[1].children[7].children.push({
                label: 'Fabbricati',
                layer: fabbricati8Layer
            });
            overlaysTree.children[1].children[7].children.push({
                label: 'Acque',
                layer: acque8Layer
            });
            overlaysTree.children[1].children[7].children.push({
                label: 'Strade',
                layer: strade8Layer
            });

            // overlaysTree.children[1].children[7].children.push({
            //     label: 'Testi',
            //     layer: testi8Layer
            // });
            
            // overlaysTree.children[1].children[7].children.push({
            //     label: 'Simboli',
            //     layer: simboli8Layer
            // });
            // overlaysTree.children[1].children[7].children.push({
            //     label: 'Linee Varie',
            //     layer: lineevarie8Layer
            // });



            myTree.setOverlayTree(overlaysTree);
        });

        $.getJSON(cartella +'/B542_000900.geojson', function (data) {
            var particelle9Layer = L.Proj.geoJson(data, {
            filter: function (feature) {
                return feature.properties && feature.properties.LIVELLO === 'PARTICELLE';
            },
            style: function (feature) {
                return {
                    color: 'cyan',
                    weight: 1,
                    fillColor: 'cyan',
                    fillOpacity: 0
                };
            },
            onEachFeature: function (feature, layer) {
                if (feature.properties && feature.properties.CODICE) {
                    layer.bindTooltip(feature.properties.CODICE, {
                        permanent: true,
                        direction: 'center',
                        className: 'etichetta-particelle'
                    });
                }
            }
            });
            // layer fabbricati
            var fabbricati9Layer = L.Proj.geoJson(data, {
                filter: function (feature) {
                    return feature.properties && feature.properties.LIVELLO === 'FABBRICATI';
                },
                style: function (feature) {
                    return {
                        color: 'red',
                        weight: 2,
                        fillColor: 'red',
                        fillOpacity: 0.3
                    };
                },
                
            });
            var strade9Layer = L.Proj.geoJson(data, {
                filter: function (feature) {
                    return feature.properties && feature.properties.LIVELLO === 'STRADE';
                },
                style: function (feature) {
                    return {
                        color: 'green',
                        weight: 1,
                        fillColor: 'grey',
                        fillOpacity: 0.3
                    };
                }
            });
            var acque9Layer = L.Proj.geoJson(data, {
                filter: function (feature) {
                    return feature.properties && feature.properties.LIVELLO === 'ACQUE';
                },
                style: function (feature) {
                    return {
                        color: 'blue',
                        weight: 1,
                        fillColor: '#5AC5EC',
                        fillOpacity: 0.3
                    };
                }
            });

            // var testi9Layer = L.geoJSON(data, {
            //     filter: f => f.properties.LIVELLO === 'TESTI',
            //     pointToLayer: function (feature, latlng) {
            //         let testo   = feature.properties.TESTO || '';
            //         let angolo  = feature.properties.ANGOLO || 0;
            //         let altezza = feature.properties.ALTEZZA || 12;
            //         let icon = L.divIcon({
            //             className: 'text-label', // definita in CSS
            //             html: '<div style=\"transform: rotate(' + angolo + 'deg); white-space:nowrap;\">' + testo + '</div>',
            //             iconSize: [5,1] // lascia che la dimensione si adatti al contenuto
            //         });
            //         return L.marker(latlng, { icon: icon, interactive: false });
            //     }
            // });

            // var simboli9Layer = L.geoJSON(null, {
            //     filter: f => f.properties.LIVELLO === 'SIMBOLI',
            //     pointToLayer: function (feature, latlng) {
            //         return L.marker(latlng, { icon: getSimboloIcon(feature.properties.TIPO) });
            //     }
            // });

                       

            // var lineevarie9Layer = L.geoJSON(null, {
            //     filter: f => f.properties.LIVELLO === 'LINEEVARIE',
            //     style: f => getLineaStyle(f.properties.TIPOLINEA)
            // });



            catastali.addLayer(particelle9Layer);
            overlaysTree.children[1].children[8].children.push({
                label: 'Particelle',
                layer: particelle9Layer
            });
             overlaysTree.children[1].children[8].children.push({
                label: 'Fabbricati',
                layer: fabbricati9Layer
            });
            overlaysTree.children[1].children[8].children.push({
                label: 'Acque',
                layer: acque9Layer
            });
            overlaysTree.children[1].children[8].children.push({
                label: 'Strade',
                layer: strade9Layer
            });

            // overlaysTree.children[1].children[8].children.push({
            //     label: 'Testi',
            //     layer: testi9Layer
            // });
            
            // overlaysTree.children[1].children[8].children.push({
            //     label: 'Simboli',
            //     layer: simboli9Layer
            // });
            // overlaysTree.children[1].children[8].children.push({
            //     label: 'Linee Varie',
            //     layer: lineevarie9Layer
            // });


            myTree.setOverlayTree(overlaysTree);
        });

        $.getJSON(cartella +'/B542_001000.geojson', function (data) {
            var particelle10Layer = L.Proj.geoJson(data, {
            filter: function (feature) {
                return feature.properties && feature.properties.LIVELLO === 'PARTICELLE';
            },
            style: function (feature) {
                return {
                    color: 'cyan',
                    weight: 1,
                    fillColor: 'cyan',
                    fillOpacity: 0
                };
            },
            onEachFeature: function (feature, layer) {
                if (feature.properties && feature.properties.CODICE) {
                    layer.bindTooltip(feature.properties.CODICE, {
                        permanent: true,
                        direction: 'center',
                        className: 'etichetta-particelle'
                    });
                }
            }
            });
            // layer fabbricati
            var fabbricati10Layer = L.Proj.geoJson(data, {
                filter: function (feature) {
                    return feature.properties && feature.properties.LIVELLO === 'FABBRICATI';
                },
                style: function (feature) {
                    return {
                        color: 'red',
                        weight: 2,
                        fillColor: 'red',
                        fillOpacity: 0.3
                    };
                },
                
            });
            var strade10Layer = L.Proj.geoJson(data, {
                filter: function (feature) {
                    return feature.properties && feature.properties.LIVELLO === 'STRADE';
                },
                style: function (feature) {
                    return {
                        color: 'green',
                        weight: 1,
                        fillColor: 'grey',
                        fillOpacity: 0.3
                    };
                }
            });
            var acque10Layer = L.Proj.geoJson(data, {
                filter: function (feature) {
                    return feature.properties && feature.properties.LIVELLO === 'ACQUE';
                },
                style: function (feature) {
                    return {
                        color: 'blue',
                        weight: 1,
                        fillColor: '#5AC5EC',
                        fillOpacity: 0.3
                    };
                }
            });

            // var testi10Layer = L.geoJSON(data, {
            //     filter: f => f.properties.LIVELLO === 'TESTI',
            //     pointToLayer: function (feature, latlng) {
            //         let testo   = feature.properties.TESTO || '';
            //         let angolo  = feature.properties.ANGOLO || 0;
            //         let altezza = feature.properties.ALTEZZA || 12;
            //         let icon = L.divIcon({
            //             className: 'text-label', // definita in CSS
            //             html: '<div style=\"transform: rotate(' + angolo + 'deg); white-space:nowrap;\">' + testo + '</div>',
            //             iconSize: [5,1] // lascia che la dimensione si adatti al contenuto
            //         });
            //         return L.marker(latlng, { icon: icon, interactive: false });
            //     }
            // });

            // var simboli10Layer = L.geoJSON(null, {
            //     filter: f => f.properties.LIVELLO === 'SIMBOLI',
            //     pointToLayer: function (feature, latlng) {
            //         return L.marker(latlng, { icon: getSimboloIcon(feature.properties.TIPO) });
            //     }
            // });

            
            // var lineevarie10Layer = L.geoJSON(null, {
            //     filter: f => f.properties.LIVELLO === 'LINEEVARIE',
            //     style: f => getLineaStyle(f.properties.TIPOLINEA)
            // });




            catastali.addLayer(particelle10Layer);
            overlaysTree.children[1].children[9].children.push({
                label: 'Particelle',
                layer: particelle10Layer
            });
             overlaysTree.children[1].children[9].children.push({
                label: 'Fabbricati',
                layer: fabbricati10Layer
            });
            overlaysTree.children[1].children[9].children.push({
                label: 'Acque',
                layer: acque10Layer
            });
            overlaysTree.children[1].children[9].children.push({
                label: 'Strade',
                layer: strade10Layer
            });

            // overlaysTree.children[1].children[9].children.push({
            //     label: 'Testi',
            //     layer: testi10Layer
            // });
            
            // overlaysTree.children[1].children[9].children.push({
            //     label: 'Simboli',
            //     layer: simboli10Layer
            // });
            // overlaysTree.children[1].children[9].children.push({
            //     label: 'Linee Varie',
            //     layer: lineevarie10Layer
            // });




            myTree.setOverlayTree(overlaysTree);
        });

        $.getJSON(cartella +'/B542_001100.geojson', function (data) {
            var particelle11Layer = L.Proj.geoJson(data, {
            filter: function (feature) {
                return feature.properties && feature.properties.LIVELLO === 'PARTICELLE';
            },
            style: function (feature) {
                return {
                    color: 'cyan',
                    weight: 1,
                    fillColor: 'cyan',
                    fillOpacity: 0
                };
            },
            onEachFeature: function (feature, layer) {
                if (feature.properties && feature.properties.CODICE) {
                    layer.bindTooltip(feature.properties.CODICE, {
                        permanent: true,
                        direction: 'center',
                        className: 'etichetta-particelle'
                    });
                }
            }
            });
            // layer fabbricati
            var fabbricati11Layer = L.Proj.geoJson(data, {
                filter: function (feature) {
                    return feature.properties && feature.properties.LIVELLO === 'FABBRICATI';
                },
                style: function (feature) {
                    return {
                        color: 'red',
                        weight: 2,
                        fillColor: 'red',
                        fillOpacity: 0.3
                    };
                },
                
            });
            var strade11Layer = L.Proj.geoJson(data, {
                filter: function (feature) {
                    return feature.properties && feature.properties.LIVELLO === 'STRADE';
                },
                style: function (feature) {
                    return {
                        color: 'green',
                        weight: 1,
                        fillColor: 'grey',
                        fillOpacity: 0.3
                    };
                }
            });
            var acque11Layer = L.Proj.geoJson(data, {
                filter: function (feature) {
                    return feature.properties && feature.properties.LIVELLO === 'ACQUE';
                },
                style: function (feature) {
                    return {
                        color: 'blue',
                        weight: 1,
                        fillColor: '#5AC5EC',
                        fillOpacity: 0.3
                    };
                }
            });

            // var testi11Layer = L.geoJSON(data, {
            //     filter: f => f.properties.LIVELLO === 'TESTI',
            //     pointToLayer: function (feature, latlng) {
            //         let testo   = feature.properties.TESTO || '';
            //         let angolo  = feature.properties.ANGOLO || 0;
            //         let altezza = feature.properties.ALTEZZA || 12;
            //         let icon = L.divIcon({
            //             className: 'text-label', // definita in CSS
            //             html: '<div style=\"transform: rotate(' + angolo + 'deg); white-space:nowrap;\">' + testo + '</div>',
            //             iconSize: [5,1] // lascia che la dimensione si adatti al contenuto
            //         });
            //         return L.marker(latlng, { icon: icon, interactive: false });
            //     }
            // });

            // var simboli11Layer = L.geoJSON(null, {
            //     filter: f => f.properties.LIVELLO === 'SIMBOLI',
            //     pointToLayer: function (feature, latlng) {
            //         return L.marker(latlng, { icon: getSimboloIcon(feature.properties.TIPO) });
            //     }
            // });

                       

            // var lineevarie11Layer = L.geoJSON(null, {
            //     filter: f => f.properties.LIVELLO === 'LINEEVARIE',
            //     style: f => getLineaStyle(f.properties.TIPOLINEA)
            // });



            catastali.addLayer(particelle11Layer);
            overlaysTree.children[1].children[10].children.push({
                label: 'Particelle',
                layer: particelle11Layer
            });
             overlaysTree.children[1].children[10].children.push({
                label: 'Fabbricati',
                layer: fabbricati11Layer
            });
            overlaysTree.children[1].children[10].children.push({
                label: 'Acque',
                layer: acque11Layer
            });
            overlaysTree.children[1].children[10].children.push({
                label: 'Strade',
                layer: strade11Layer
            });

            // overlaysTree.children[1].children[10].children.push({
            //     label: 'Testi',
            //     layer: testi11Layer
            // });
            
            // overlaysTree.children[1].children[10].children.push({
            //     label: 'Simboli',
            //     layer: simboli11Layer
            // });
            // overlaysTree.children[1].children[10].children.push({
            //     label: 'Linee Varie',
            //     layer: lineevarie11Layer
            // });


            myTree.setOverlayTree(overlaysTree);
        });

        $.getJSON(cartella +'/B542_001200.geojson', function (data) {
            var particelle12Layer = L.Proj.geoJson(data, {
            filter: function (feature) {
                return feature.properties && feature.properties.LIVELLO === 'PARTICELLE';
            },
            style: function (feature) {
                return {
                    color: 'cyan',
                    weight: 1,
                    fillColor: 'cyan',
                    fillOpacity: 0
                };
            },
            onEachFeature: function (feature, layer) {
                if (feature.properties && feature.properties.CODICE) {
                    layer.bindTooltip(feature.properties.CODICE, {
                        permanent: true,
                        direction: 'center',
                        className: 'etichetta-particelle'
                    });
                }
            }
            });
            // layer fabbricati
            var fabbricati12Layer = L.Proj.geoJson(data, {
                filter: function (feature) {
                    return feature.properties && feature.properties.LIVELLO === 'FABBRICATI';
                },
                style: function (feature) {
                    return {
                        color: 'red',
                        weight: 2,
                        fillColor: 'red',
                        fillOpacity: 0.3
                    };
                },
                
            });
            var strade12Layer = L.Proj.geoJson(data, {
                filter: function (feature) {
                    return feature.properties && feature.properties.LIVELLO === 'STRADE';
                },
                style: function (feature) {
                    return {
                        color: 'green',
                        weight: 1,
                        fillColor: 'grey',
                        fillOpacity: 0.3
                    };
                }
            });
            var acque12Layer = L.Proj.geoJson(data, {
                filter: function (feature) {
                    return feature.properties && feature.properties.LIVELLO === 'ACQUE';
                },
                style: function (feature) {
                    return {
                        color: 'blue',
                        weight: 1,
                        fillColor: '#5AC5EC',
                        fillOpacity: 0.3
                    };
                }
            });

            // var testi12Layer = L.geoJSON(data, {
            //     filter: f => f.properties.LIVELLO === 'TESTI',
            //     pointToLayer: function (feature, latlng) {
            //         let testo   = feature.properties.TESTO || '';
            //         let angolo  = feature.properties.ANGOLO || 0;
            //         let altezza = feature.properties.ALTEZZA || 12;
            //         let icon = L.divIcon({
            //             className: 'text-label', // definita in CSS
            //             html: '<div style=\"transform: rotate(' + angolo + 'deg); white-space:nowrap;\">' + testo + '</div>',
            //             iconSize: [5,1] // lascia che la dimensione si adatti al contenuto
            //         });
            //         return L.marker(latlng, { icon: icon, interactive: false });
            //     }
            // });

            // var simboli12Layer = L.geoJSON(null, {
            //     filter: f => f.properties.LIVELLO === 'SIMBOLI',
            //     pointToLayer: function (feature, latlng) {
            //         return L.marker(latlng, { icon: getSimboloIcon(feature.properties.TIPO) });
            //     }
            // });

                      

            // var lineevarie12Layer = L.geoJSON(null, {
            //     filter: f => f.properties.LIVELLO === 'LINEEVARIE',
            //     style: f => getLineaStyle(f.properties.TIPOLINEA)
            // });



            catastali.addLayer(particelle12Layer);
            overlaysTree.children[1].children[11].children.push({
                label: 'Particelle',
                layer: particelle12Layer
            });
             overlaysTree.children[1].children[11].children.push({
                label: 'Fabbricati',
                layer: fabbricati12Layer
            });
            overlaysTree.children[1].children[11].children.push({
                label: 'Acque',
                layer: acque12Layer
            });
            overlaysTree.children[1].children[11].children.push({
                label: 'Strade',
                layer: strade12Layer
            });

            // overlaysTree.children[1].children[11].children.push({
            //     label: 'Testi',
            //     layer: testi12Layer
            // });
            
            // overlaysTree.children[1].children[11].children.push({
            //     label: 'Simboli',
            //     layer: simboli12Layer
            // });
            // overlaysTree.children[1].children[11].children.push({
            //     label: 'Linee Varie',
            //     layer: lineevarie12Layer
            // });


            myTree.setOverlayTree(overlaysTree);
        });
}