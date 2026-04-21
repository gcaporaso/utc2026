/**
 * ctr2020.js
 * Gestione lazy loading dei layer CTR 2020 (6 fogli × 3 tipi = 18 layer).
 *
 * Ottimizzazioni performance:
 *  - Renderer Canvas (non SVG): gestisce migliaia di feature senza rallentare il DOM
 *  - Lazy loading: i dati vengono scaricati solo alla prima attivazione del layer
 *  - Soglia di zoom: sotto zoom 14 il caricamento viene posticipato
 *    (il CTR è leggibile solo ad alta risoluzione)
 *  - Dati riutilizzati: disattivare/riattivare un layer non ri-scarica i dati
 */

var CTR2020_TILES = ['431081', '431082', '431121', '432053', '432054', '432094'];

// Stili di default per tipo (fallback)
var CTR2020_STYLES = {
    LIN: { color: '#888888', weight: 0.5, opacity: 0.8 },
    POL: { color: '#a09070', weight: 0.5, fillColor: '#ede5cc', fillOpacity: 0.5, opacity: 0.8 },
    POI: { radius: 2, color: '#555', weight: 0.5, fillColor: '#aaa', fillOpacity: 0.8 }
};

// ---------------------------------------------------------------------------
// Palette stili LIN per valore Descr (CTR Campania)
// ---------------------------------------------------------------------------
var _CTR_LIN_STYLES = {
    // Curve di livello
    'curva di livello ordinaria certa':          { color: '#9b5a1e', weight: 0.5, opacity: 0.8 },
    'curva di livello ordinaria certa inv':       { color: '#9b5a1e', weight: 0.5, opacity: 0.5, dashArray: '2,3' },
    'curva di livello direttrice certa':          { color: '#9b5a1e', weight: 1.0, opacity: 0.9 },
    'curva di livello direttrice certa invisibile':{ color: '#9b5a1e', weight: 1.0, opacity: 0.5, dashArray: '3,4' },
    // Strade
    'Strada asfaltata':                           { color: '#333333', weight: 1.8, opacity: 1.0 },
    'Strada asfaltata con muro in calce':         { color: '#333333', weight: 1.8, opacity: 1.0 },
    'Strada asfaltata con muro di sostegno':      { color: '#333333', weight: 1.8, opacity: 1.0 },
    'Strada asfaltata su ponte':                  { color: '#333333', weight: 1.8, opacity: 1.0 },
    'Strada non asfaltata':                       { color: '#a07840', weight: 1.2, opacity: 0.9, dashArray: '5,3' },
    'Strada non asfaltata con muro':              { color: '#a07840', weight: 1.2, opacity: 0.9, dashArray: '5,3' },
    'Strada non asfaltata su ponte':              { color: '#a07840', weight: 1.2, opacity: 0.9, dashArray: '5,3' },
    'Strada pertinenziale asfaltata/Accesso':     { color: '#555555', weight: 1.0, opacity: 0.85 },
    'Strada pertinenziale non asfaltata':         { color: '#b09060', weight: 0.9, opacity: 0.8, dashArray: '4,3' },
    'ASSE restituito asfaltato':                  { color: '#333333', weight: 0.6, opacity: 0.6, dashArray: '2,2' },
    'Viale':                                      { color: '#333333', weight: 1.4, opacity: 0.9 },
    'Sentiero':                                   { color: '#a07840', weight: 0.7, opacity: 0.8, dashArray: '3,4' },
    'Mulattiera':                                 { color: '#a07840', weight: 0.7, opacity: 0.8, dashArray: '3,4' },
    'Vialetto pedonale':                          { color: '#888888', weight: 0.6, opacity: 0.7, dashArray: '2,3' },
    'Rampa':                                      { color: '#666666', weight: 0.9, opacity: 0.8 },
    'Gradinata':                                  { color: '#777777', weight: 0.7, opacity: 0.8 },
    'Scale':                                      { color: '#777777', weight: 0.7, opacity: 0.8 },
    'Marciapiede':                                { color: '#aaaaaa', weight: 0.5, opacity: 0.7 },
    'Isola di traffico':                          { color: '#888888', weight: 0.6, opacity: 0.7 },
    'Spartitraffico':                             { color: '#888888', weight: 0.6, opacity: 0.7 },
    'Aiuola':                                     { color: '#88aa60', weight: 0.5, opacity: 0.7 },
    // Idrografia
    'Fiume rappresentabile':                      { color: '#4a82c8', weight: 1.4, opacity: 0.9 },
    'Fiume rappresentabile in sotterranea':       { color: '#4a82c8', weight: 1.0, opacity: 0.7, dashArray: '4,3' },
    'Fiume rappresentabile su sede pensile':      { color: '#4a82c8', weight: 1.4, opacity: 0.9 },
    'Fiume non rappresentabile':                  { color: '#4a82c8', weight: 0.8, opacity: 0.8 },
    'Fiume non rappresentabile in sotterranea':   { color: '#4a82c8', weight: 0.7, opacity: 0.6, dashArray: '3,3' },
    'Fiume non rappresentabile in sottopasso':    { color: '#4a82c8', weight: 0.7, opacity: 0.6, dashArray: '3,3' },
    'Freccia fiume':                              { color: '#4a82c8', weight: 0.6, opacity: 0.8 },
    'Scolina':                                    { color: '#7aaced', weight: 0.6, opacity: 0.7, dashArray: '3,3' },
    'Scolina in sotterranea':                     { color: '#7aaced', weight: 0.5, opacity: 0.6, dashArray: '2,4' },
    'Scolina in calcestruzzo':                    { color: '#7aaced', weight: 0.6, opacity: 0.7 },
    'Linea lago':                                 { color: '#4a82c8', weight: 0.8, opacity: 0.8 },
    'Linea lago artificiale':                     { color: '#4a82c8', weight: 0.8, opacity: 0.8 },
    'Briglia':                                    { color: '#5888b0', weight: 1.2, opacity: 0.9 },
    'Tombino':                                    { color: '#888888', weight: 0.7, opacity: 0.7 },
    'Ponte generico':                             { color: '#555555', weight: 1.0, opacity: 0.8 },
    'Ponte generico in FERRO':                    { color: '#555555', weight: 1.0, opacity: 0.8 },
    // Manufatti
    'Muro di sostegno':                           { color: '#555555', weight: 1.0, opacity: 0.85 },
    'Muro in calce':                              { color: '#666666', weight: 0.7, opacity: 0.8 },
    'Muro a secco':                               { color: '#777777', weight: 0.6, opacity: 0.75 },
    'Rete metallica':                             { color: '#888888', weight: 0.5, opacity: 0.7, dashArray: '2,3' },
    'Palizzata':                                  { color: '#8b6040', weight: 0.6, opacity: 0.75 },
    'Siepe':                                      { color: '#60a040', weight: 0.6, opacity: 0.75 },
    'Filare di alberi':                           { color: '#60a040', weight: 0.5, opacity: 0.7 },
    'filare di frutteto':                         { color: '#70a838', weight: 0.5, opacity: 0.7 },
    'filare di uliveto':                          { color: '#7aaa50', weight: 0.5, opacity: 0.7 },
    'filare di vigneto':                          { color: '#9878a0', weight: 0.5, opacity: 0.7 },
    'Cancello':                                   { color: '#555555', weight: 0.8, opacity: 0.8 },
    'Diagonali di traliccio':                     { color: '#e08030', weight: 0.7, opacity: 0.8 },
    'linea elettrica aerea nuda':                 { color: '#d4900a', weight: 0.7, opacity: 0.8 },
    // Limiti
    'limite di coltura':                          { color: '#90aa60', weight: 0.4, opacity: 0.7, dashArray: '4,3' },
    'Limite di Comune':                           { color: '#9050a0', weight: 1.4, opacity: 0.85, dashArray: '6,3,2,3' },
    // Scarpate
    'ciglio scarpata naturale':                   { color: '#8b5c2e', weight: 0.6, opacity: 0.75 },
    'ciglio scarpata artificiale':                { color: '#777777', weight: 0.6, opacity: 0.75 },
    'piede scarpata naturale':                    { color: '#8b5c2e', weight: 0.5, opacity: 0.65 },
    'piede scarpata artificiale':                 { color: '#777777', weight: 0.5, opacity: 0.65 },
    'Linea roccia affiorante':                    { color: '#a09080', weight: 0.6, opacity: 0.75 },
    'Linea scoglio':                              { color: '#a09080', weight: 0.6, opacity: 0.75 },
    // Altro
    'campitura (diagonali) tettoia':              { color: '#c8783c', weight: 0.5, opacity: 0.6, dashArray: '2,2' },
    'campitura (diagonali)  baracca':             { color: '#c8783c', weight: 0.5, opacity: 0.6, dashArray: '2,2' },
    'simbolo lineare croce cimitero':             { color: '#555555', weight: 0.8, opacity: 0.8 },
    'simbolo lineare croce chiesa':               { color: '#555555', weight: 0.8, opacity: 0.8 },
    'campo da gioco':                             { color: '#60a050', weight: 0.8, opacity: 0.8 },
    'impianto sportivo generico':                 { color: '#60a050', weight: 0.8, opacity: 0.8 },
    'tribune':                                    { color: '#888888', weight: 0.6, opacity: 0.75 }
};

// ---------------------------------------------------------------------------
// Palette stili POL per valore Descr (CTR Campania)
// ---------------------------------------------------------------------------
var _CTR_POL_STYLES = {
    // Edifici
    'Edificio civile':                    { color: '#7a4010', weight: 1.8, fillColor: '#c87d3e', fillOpacity: 0.85, opacity: 1.0 },
    'Edificio generico ad uso COMMERCIALE':{ color: '#7a4010', weight: 1.2, fillColor: '#c87d3e', fillOpacity: 0.85, opacity: 1.0 },
    'Edificio generico ad uso AGRICOLO':  { color: '#8b5820', weight: 1.0, fillColor: '#d4a060', fillOpacity: 0.8,  opacity: 0.9 },
    'Edificio generico ad uso INDUSTRIALE':{ color: '#707070', weight: 1.0, fillColor: '#c0c0b8', fillOpacity: 0.8, opacity: 0.9 },
    'capannone agricolo':                 { color: '#8b5820', weight: 1.0, fillColor: '#d4a060', fillOpacity: 0.8,  opacity: 0.9 },
    'Capannone':                          { color: '#707070', weight: 1.0, fillColor: '#c0bfb0', fillOpacity: 0.8,  opacity: 0.9 },
    'baracca':                            { color: '#8b5820', weight: 0.8, fillColor: '#d4a060', fillOpacity: 0.6,  opacity: 0.8 },
    'tettoia':                            { color: '#8b5820', weight: 0.7, fillColor: '#d4b888', fillOpacity: 0.5,  opacity: 0.8 },
    'edificio diroccato':                 { color: '#9b7050', weight: 0.8, fillColor: '#d0b090', fillOpacity: 0.5,  opacity: 0.8, dashArray: '4,3' },
    'edificio in costruzione':            { color: '#9b7050', weight: 0.8, fillColor: '#e0c8a0', fillOpacity: 0.5,  opacity: 0.8, dashArray: '3,3' },
    'edificio interrato':                 { color: '#888888', weight: 0.7, fillColor: '#c8c8c0', fillOpacity: 0.5,  opacity: 0.7, dashArray: '3,3' },
    'edificio scuola':                    { color: '#7a4010', weight: 1.2, fillColor: '#e8a060', fillOpacity: 0.85, opacity: 1.0 },
    'edificio municipio':                 { color: '#7a4010', weight: 1.4, fillColor: '#e08040', fillOpacity: 0.9,  opacity: 1.0 },
    'edificio hotel':                     { color: '#7a4010', weight: 1.2, fillColor: '#d89060', fillOpacity: 0.85, opacity: 1.0 },
    'Chiesa':                             { color: '#7a4010', weight: 1.2, fillColor: '#d09060', fillOpacity: 0.85, opacity: 1.0 },
    'cappella cimitero':                  { color: '#7a4010', weight: 1.0, fillColor: '#d09060', fillOpacity: 0.8,  opacity: 0.9 },
    'campanile':                          { color: '#7a4010', weight: 1.0, fillColor: '#c07840', fillOpacity: 0.85, opacity: 1.0 },
    'castello':                           { color: '#7a4010', weight: 1.2, fillColor: '#b06030', fillOpacity: 0.9,  opacity: 1.0 },
    'Garage':                             { color: '#7a4010', weight: 0.8, fillColor: '#c88850', fillOpacity: 0.75, opacity: 0.9 },
    'sylos':                              { color: '#8b5820', weight: 0.8, fillColor: '#c8b090', fillOpacity: 0.75, opacity: 0.85 },
    'traliccio rappresentabile':          { color: '#888888', weight: 0.7, fillColor: '#d8d0c0', fillOpacity: 0.6,  opacity: 0.8 },
    'serbatoio':                          { color: '#5888b0', weight: 0.8, fillColor: '#b8d0e8', fillOpacity: 0.7,  opacity: 0.8 },
    'piscina':                            { color: '#3878b8', weight: 0.8, fillColor: '#70a8d8', fillOpacity: 0.8,  opacity: 0.9 },
    'vasca rappresentabile':              { color: '#4a82c8', weight: 0.7, fillColor: '#88b8e8', fillOpacity: 0.7,  opacity: 0.8 },
    'pozzo rappresentabile':              { color: '#4a82c8', weight: 0.7, fillColor: '#a8c8e0', fillOpacity: 0.7,  opacity: 0.8 },
    'serra':                              { color: '#90b8c8', weight: 0.8, fillColor: '#d0e8f0', fillOpacity: 0.65, opacity: 0.85 },
    'cabina elettrica di trasformazione': { color: '#888888', weight: 0.7, fillColor: '#e0d8c0', fillOpacity: 0.7,  opacity: 0.8 },
    // Viabilità — superfici
    'Tronco pav.':                        { color: '#b0a898', weight: 0.4, fillColor: '#d8d4c8', fillOpacity: 0.7,  opacity: 0.7 },
    'Tronco non pav.':                    { color: '#b09060', weight: 0.4, fillColor: '#d4c090', fillOpacity: 0.65, opacity: 0.7 },
    'Incrocio pav.':                      { color: '#b0a898', weight: 0.4, fillColor: '#d8d4c8', fillOpacity: 0.7,  opacity: 0.7 },
    'Incrocio non pav.':                  { color: '#b09060', weight: 0.4, fillColor: '#d4c090', fillOpacity: 0.65, opacity: 0.7 },
    'Rotatoria pav.':                     { color: '#b0a898', weight: 0.4, fillColor: '#d8d4c8', fillOpacity: 0.7,  opacity: 0.7 },
    'Area pert. pav.':                    { color: '#b0a898', weight: 0.4, fillColor: '#d8d4c8', fillOpacity: 0.6,  opacity: 0.7 },
    'Vialetto NON stradale pav.':         { color: '#b0a898', weight: 0.4, fillColor: '#d0c8b8', fillOpacity: 0.6,  opacity: 0.7 },
    'Gradinate NON stradale pav.':        { color: '#aaaaaa', weight: 0.4, fillColor: '#d0ccc8', fillOpacity: 0.6,  opacity: 0.7 },
    'Marciapiede stradale pav.':          { color: '#b0a898', weight: 0.3, fillColor: '#dcdad0', fillOpacity: 0.6,  opacity: 0.7 },
    'Area ped. NON stradale pav.':        { color: '#b0a898', weight: 0.3, fillColor: '#d8d4c8', fillOpacity: 0.6,  opacity: 0.7 },
    // Idrografia
    'Fiume rappresentabile':              { color: '#3870b8', weight: 0.8, fillColor: '#88b8e8', fillOpacity: 0.8,  opacity: 0.9 },
    'Lago artificiale':                   { color: '#3870b8', weight: 0.8, fillColor: '#88b8e8', fillOpacity: 0.8,  opacity: 0.9 },
    'laguna':                             { color: '#3870b8', weight: 0.8, fillColor: '#88b8e8', fillOpacity: 0.8,  opacity: 0.9 },
    'area briglia':                       { color: '#5888b0', weight: 0.7, fillColor: '#a0c0d8', fillOpacity: 0.7,  opacity: 0.8 },
    'vasca di impioanti di depurazione':  { color: '#5888b0', weight: 0.7, fillColor: '#a8c8d8', fillOpacity: 0.7,  opacity: 0.8 },
    // Vegetazione
    'area Seminativi':   { color: '#c8b860', weight: 0.4, fillColor: '#f5efc0', fillOpacity: 0.65, opacity: 0.7 },
    'area Frutteti':     { color: '#8aaa50', weight: 0.4, fillColor: '#cce890', fillOpacity: 0.65, opacity: 0.7 },
    'area Uliveti':      { color: '#7a9840', weight: 0.4, fillColor: '#bec878', fillOpacity: 0.65, opacity: 0.7 },
    'area Vigneti':      { color: '#b07898', weight: 0.4, fillColor: '#e0b8c8', fillOpacity: 0.65, opacity: 0.7 },
    'area prato':        { color: '#88b868', weight: 0.4, fillColor: '#c8e8a0', fillOpacity: 0.65, opacity: 0.7 },
    'area incolto':      { color: '#b0a870', weight: 0.4, fillColor: '#e0d498', fillOpacity: 0.6,  opacity: 0.7 },
    'area giardino non qualificato': { color: '#78a858', weight: 0.4, fillColor: '#b8d890', fillOpacity: 0.65, opacity: 0.7 },
    'area pascolo':      { color: '#90b870', weight: 0.4, fillColor: '#d0e8a0', fillOpacity: 0.65, opacity: 0.7 },
    'area pascolo arborato':   { color: '#88b068', weight: 0.4, fillColor: '#c8e0a0', fillOpacity: 0.65, opacity: 0.7 },
    'area pascolo cespugliato':{ color: '#88aa60', weight: 0.4, fillColor: '#c8d890', fillOpacity: 0.65, opacity: 0.7 },
    'area canneto':      { color: '#60a890', weight: 0.4, fillColor: '#a0d0c0', fillOpacity: 0.65, opacity: 0.7 },
    'area arborato':     { color: '#78a858', weight: 0.4, fillColor: '#a8c880', fillOpacity: 0.65, opacity: 0.7 },
    'bosco di latifoglie':       { color: '#508840', weight: 0.5, fillColor: '#90c878', fillOpacity: 0.7,  opacity: 0.75 },
    'bosco di leccio-ceduo':     { color: '#407830', weight: 0.5, fillColor: '#78b068', fillOpacity: 0.7,  opacity: 0.75 },
    'bosco macchia mediterranea':{ color: '#488038', weight: 0.5, fillColor: '#80b870', fillOpacity: 0.7,  opacity: 0.75 },
    'pineta':            { color: '#306830', weight: 0.5, fillColor: '#60a860', fillOpacity: 0.7,  opacity: 0.75 },
    // Aree
    'Area antropizzata': { color: '#a89880', weight: 0.5, fillColor: '#d8ccc0', fillOpacity: 0.6,  opacity: 0.75 },
    'area rocce/scogli': { color: '#a09080', weight: 0.5, fillColor: '#d8d0c0', fillOpacity: 0.55, opacity: 0.75 },
    'aree prive di vegetazione': { color: '#b0a890', weight: 0.4, fillColor: '#e8e0d0', fillOpacity: 0.55, opacity: 0.7 },
    // Sport
    'campo sportivo':    { color: '#60a050', weight: 0.8, fillColor: '#a0d888', fillOpacity: 0.7,  opacity: 0.85 },
    'campo calcio':      { color: '#60a050', weight: 0.8, fillColor: '#a0d888', fillOpacity: 0.7,  opacity: 0.85 },
    'campo calcetto':    { color: '#60a050', weight: 0.7, fillColor: '#a0d888', fillOpacity: 0.7,  opacity: 0.85 },
    'campo tennis':      { color: '#c88030', weight: 0.8, fillColor: '#e8b870', fillOpacity: 0.7,  opacity: 0.85 },
    'tribuna campo sportivo': { color: '#888888', weight: 0.6, fillColor: '#d0c8c0', fillOpacity: 0.6, opacity: 0.8 },
    'gradinata':         { color: '#888888', weight: 0.6, fillColor: '#d0c8c0', fillOpacity: 0.6,  opacity: 0.8 },
    // Varie
    'Isola di traffico': { color: '#b0a898', weight: 0.4, fillColor: '#d8d4c8', fillOpacity: 0.6,  opacity: 0.7 },
    'Cortile':           { color: '#b0a070', weight: 0.4, fillColor: '#e8e0c8', fillOpacity: 0.4,  opacity: 0.7 },
    'atrio (cavedio)':   { color: '#b0a070', weight: 0.4, fillColor: '#e8e0c8', fillOpacity: 0.4,  opacity: 0.7 },
    'rampa':             { color: '#b0a898', weight: 0.5, fillColor: '#d8d4c8', fillOpacity: 0.6,  opacity: 0.7 },
    'Pagghiara':         { color: '#8b5820', weight: 0.8, fillColor: '#d4a060', fillOpacity: 0.7,  opacity: 0.85 }
};

// ---------------------------------------------------------------------------
// Palette stili POI per valore Descr (CTR Campania)
// ---------------------------------------------------------------------------
var _CTR_POI_STYLES = {
    'quota al suolo':          { radius: 1, color: '#9b5a1e', weight: 0.5, fillColor: '#9b5a1e', fillOpacity: 1.0 },
    'albero isolato':          { radius: 3, color: '#408030', weight: 0.6, fillColor: '#70b050', fillOpacity: 0.9 },
    'simbolo uliveto':         { radius: 2, color: '#7a9840', weight: 0.5, fillColor: '#bec878', fillOpacity: 0.9 },
    'simbolo vigneto':         { radius: 2, color: '#b07898', weight: 0.5, fillColor: '#e0b8c8', fillOpacity: 0.9 },
    'simbolo frutteto':        { radius: 2, color: '#8aaa50', weight: 0.5, fillColor: '#cce890', fillOpacity: 0.9 },
    'simbolo seminativo':      { radius: 2, color: '#c8b860', weight: 0.5, fillColor: '#f5efc0', fillOpacity: 0.9 },
    'simbolo incolto':         { radius: 2, color: '#b0a870', weight: 0.5, fillColor: '#e0d498', fillOpacity: 0.9 },
    'simbolo giardino-vivaio': { radius: 2, color: '#78a858', weight: 0.5, fillColor: '#b8d890', fillOpacity: 0.9 },
    'simbolo bosco fitto':     { radius: 3, color: '#508840', weight: 0.6, fillColor: '#90c878', fillOpacity: 0.9 },
    'simbolo bosco rado':      { radius: 3, color: '#508840', weight: 0.5, fillColor: '#a8d890', fillOpacity: 0.8 },
    'simbolo pascolo':         { radius: 2, color: '#90b870', weight: 0.5, fillColor: '#d0e8a0', fillOpacity: 0.9 },
    'simbolo pascolo arborato':{ radius: 2, color: '#88b068', weight: 0.5, fillColor: '#c8e0a0', fillOpacity: 0.9 },
    'simbolo macchia mediterranea':{ radius: 2, color: '#488038', weight: 0.5, fillColor: '#80b870', fillOpacity: 0.9 },
    'simbolo leccio-ceduo':    { radius: 2, color: '#407830', weight: 0.5, fillColor: '#78b068', fillOpacity: 0.9 },
    'simbolo latifoglia':      { radius: 3, color: '#508840', weight: 0.6, fillColor: '#90c878', fillOpacity: 0.9 },
    'simbolo pino':            { radius: 3, color: '#306830', weight: 0.6, fillColor: '#60a860', fillOpacity: 0.9 },
    'simbolo canneto':         { radius: 2, color: '#60a890', weight: 0.5, fillColor: '#a0d0c0', fillOpacity: 0.9 },
    'simbolo arborato':        { radius: 2, color: '#78a858', weight: 0.5, fillColor: '#a8c880', fillOpacity: 0.9 },
    'simbolo sorgente':        { radius: 3, color: '#3870b8', weight: 0.7, fillColor: '#88b8e8', fillOpacity: 0.9 },
    'simbolo fontana':         { radius: 3, color: '#3870b8', weight: 0.7, fillColor: '#88b8e8', fillOpacity: 0.9 },
    'simbolo pozzo':           { radius: 3, color: '#4a82c8', weight: 0.6, fillColor: '#a8c8e0', fillOpacity: 0.9 },
    'simbolo sabbia':          { radius: 2, color: '#c8b878', weight: 0.5, fillColor: '#f0e8c0', fillOpacity: 0.9 },
    'simbolo freccia fiume':   { radius: 2, color: '#4a82c8', weight: 0.6, fillColor: '#88b8e8', fillOpacity: 0.9 },
    'simbolo monumento non rappresentabile':{ radius: 3, color: '#7a4010', weight: 0.7, fillColor: '#c87d3e', fillOpacity: 0.9 },
    'palo enel':               { radius: 3, color: '#d4900a', weight: 0.8, fillColor: '#f0c040', fillOpacity: 0.9 },
    'Sylos puntuale':          { radius: 3, color: '#8b5820', weight: 0.7, fillColor: '#c8b090', fillOpacity: 0.9 },
    'Punto Rete Geometrica di Raffittimento (VTR)': { radius: 4, color: '#cc0000', weight: 1.0, fillColor: '#ff4444', fillOpacity: 0.9 },
    'Sede di Municipio':       { radius: 4, color: '#7a4010', weight: 1.0, fillColor: '#e08040', fillOpacity: 0.9 },
    'Simbolo Istruzione':      { radius: 3, color: '#7a4010', weight: 0.8, fillColor: '#e8a060', fillOpacity: 0.9 },
    'Simbolo cimitero':        { radius: 3, color: '#555555', weight: 0.8, fillColor: '#bbbbbb', fillOpacity: 0.9 },
    'simbolo puntuale croce chiesa':{ radius: 3, color: '#555555', weight: 0.8, fillColor: '#cccccc', fillOpacity: 0.9 },
    'Simbolo parcheggio':      { radius: 3, color: '#3366cc', weight: 0.7, fillColor: '#8ab0f0', fillOpacity: 0.9 },
    'Simbolo di Tabernacolo':  { radius: 3, color: '#7a4010', weight: 0.7, fillColor: '#d09060', fillOpacity: 0.9 }
};

var CTR2020_LABELS = {
    LIN: 'Linee',
    POL: 'Poligoni',
    POI: 'Punti'
};

// Renderer canvas condiviso — inizializzato al primo uso (Leaflet potrebbe
// non essere ancora caricato al momento dell'esecuzione del modulo).
var _ctr2020Renderer = null;
function _getCtr2020Renderer() {
    if (!_ctr2020Renderer) _ctr2020Renderer = L.canvas({ padding: 0.5 });
    return _ctr2020Renderer;
}

var CTR2020_MIN_ZOOM = 14; // sotto questo zoom il CTR non è leggibile

/**
 * Scarica il GeoJSON e lo aggiunge al layer; chiama onSuccess() al termine.
 */
function _fetchCtr2020(layer, tile, type, onSuccess) {
    var baseUrl = (typeof CTR2020_BASE_URL !== 'undefined')
        ? CTR2020_BASE_URL
        : 'ctr2020geojson';
    var url = baseUrl + '/' + tile + '/' + type + '.geojson';

    $.ajax({
        url: url,
        dataType: 'json',
        success: function (data) {
            layer.addData(data);
            onSuccess();
        },
        error: function (_xhr, _status, err) {
            console.error('CTR2020 caricamento fallito [' + tile + '/' + type + ']:', err);
        }
    });
}

/**
 * Crea un layer GeoJSON vuoto con lazy loading.
 * I dati vengono richiesti al server solo al primo evento 'add'
 * (cioè quando l'utente spunta il layer nel pannello di controllo).
 */
function _createCtr2020Layer(tile, type) {
    var layer = L.geoJSON(null, {
        renderer: _getCtr2020Renderer(),
        style: function (feature) {
            var descr = feature.properties && feature.properties.Descr;
            if (type === 'LIN') return _CTR_LIN_STYLES[descr] || CTR2020_STYLES.LIN;
            if (type === 'POL') return _CTR_POL_STYLES[descr] || CTR2020_STYLES.POL;
            return CTR2020_STYLES[type];
        },
        pointToLayer: function (feature, latlng) {
            var descr = feature.properties && feature.properties.Descr;
            var s = _CTR_POI_STYLES[descr] || CTR2020_STYLES.POI;
            return L.circleMarker(latlng, s);
        }
    });

    var loaded = false;

    layer.on('add', function () {
        if (loaded) return; // dati già presenti, nulla da fare

        if (map.getZoom() < CTR2020_MIN_ZOOM) {
            // Zoom troppo basso: posticipa il caricamento al primo zoom adeguato
            var onZoomEnd = function () {
                if (!map.hasLayer(layer)) {
                    // L'utente ha rimosso il layer prima dello zoom: annulla
                    map.off('zoomend', onZoomEnd);
                    return;
                }
                if (map.getZoom() >= CTR2020_MIN_ZOOM) {
                    map.off('zoomend', onZoomEnd);
                    _fetchCtr2020(layer, tile, type, function () { loaded = true; });
                }
            };
            map.on('zoomend', onZoomEnd);
        } else {
            _fetchCtr2020(layer, tile, type, function () { loaded = true; });
        }
    });

    return layer;
}

/**
 * Costruisce il nodo 'CTR 2020' da inserire nell'overlaysTree di
 * L.control.layers.tree. Da chiamare dopo layers_def().
 *
 * @returns {Object}  nodo compatibile con L.Control.Layers.Tree
 */
function buildCtr2020TreeNode() {
    var tileNodes = CTR2020_TILES.map(function (tile) {
        var typeNodes = ['LIN', 'POI', 'POL'].map(function (type) {
            return {
                label: CTR2020_LABELS[type],
                layer: _createCtr2020Layer(tile, type)
            };
        });
        return {
            label: 'Foglio ' + tile,
            collapsed: true,
            children: typeNodes
        };
    });

    return {
        label: 'CTR 2020',
        collapsed: true,
        children: tileNodes
    };
}
