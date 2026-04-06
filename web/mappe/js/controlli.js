function addMyControls(map,baselayers,overlaysTree) {
myTree = L.control.layers.tree(baselayers, overlaysTree);

//OpacityControl
var myCop = new L.control.opacity(
    //overlayers,
    opacitylayers,
    {
       //label: 'Opacita',
       collapsed: true,
       default:35
    }
);

// Control Misurazioni
var measureControl = new L.Control.Measure({
            position: 'topleft',
            primaryLengthUnit: 'meters',
            secondaryLengthUnit: 'kilometers',
            primaryAreaUnit: 'sqmeters',
            secondaryAreaUnit: 'hectares',
            localization:'it'
        });

// Control Stampa — apre il modal di selezione scala
var PrintControl = L.Control.extend({
    options: { position: 'topleft' },
    onAdd: function() {
        var container = L.DomUtil.create('div', 'leaflet-bar leaflet-control');
        var btn = L.DomUtil.create('a', 'leaflet-control-print', container);
        btn.innerHTML = '<i class="fas fa-print"></i>';
        btn.title = 'Stampa mappa (A3)';
        btn.href = '#';
        btn.style.cssText = 'font-size:16px;line-height:26px;';
        L.DomEvent.on(btn, 'click', function(e) {
            L.DomEvent.preventDefault(e);
            L.DomEvent.stopPropagation(e);
            $('#modal-print').modal('show');
        });
        return container;
    }
});

    myTree.addTo(map);
    myCop.addTo(map);
    measureControl.addTo(map);
    new PrintControl().addTo(map);

    // Aggiunge scala bar permanente (visibile in stampa)
    L.control.scale({ imperial: false, position: 'bottomleft' }).addTo(map);

    // Bottone conferma nel modal di stampa
    document.getElementById('btn-do-print').addEventListener('click', function() {
        $('#modal-print').modal('hide');
        var scaleDenom = parseInt(document.getElementById('print-scale-select').value, 10);
        setTimeout(function() { printMap(map, scaleDenom); }, 300);
    });
}

/**
 * Stampa la mappa in formato A3 landscape alla scala cartografica indicata.
 * Ridimensiona il container a 1587×1122px (A3 a 96dpi CSS), imposta lo zoom
 * corrispondente alla scala, attende il caricamento di tutti i tile via eventi,
 * poi chiama window.print(). Ripristina tutto su afterprint.
 *
 * @param {L.Map} map
 * @param {number} scaleDenom  denominatore della scala (es. 2000 per 1:2000)
 */
function printMap(map, scaleDenom) {
    // Dimensioni A3 landscape a 96 dpi CSS
    var PRINT_W = 1587, PRINT_H = 1122;

    var container  = document.getElementById('mapid');
    var origWidth  = container.style.width;
    var origHeight = container.style.height;
    var origCenter = map.getCenter();
    var origZoom   = map.getZoom();

    // Calcola lo zoom corrispondente alla scala scelta alla latitudine corrente.
    // Formula: scaleDenom = (156543.034 * cos(lat) / 2^zoom) * (dpi / 0.0254)
    // Con dpi=96 (CSS pixel): zoom = log2(156543.034 * cos(lat) * 96 / (0.0254 * scaleDenom))
    var latRad    = origCenter.lat * Math.PI / 180;
    var targetZoom = Math.round(
        Math.log2(156543.034 * Math.cos(latRad) * 96 / (0.0254 * scaleDenom))
    );
    targetZoom = Math.max(1, Math.min(targetZoom, 21));

    // Mostra overlay di attesa
    var overlay = document.getElementById('print-waiting-overlay');
    overlay.classList.add('active');

    // Ridimensiona il container alle dimensioni esatte di stampa
    container.style.width  = PRINT_W + 'px';
    container.style.height = PRINT_H + 'px';
    map.invalidateSize({ animate: false });
    map.setView(origCenter, targetZoom, { animate: false });

    // Conta i tile in caricamento tramite eventi
    var pending = 0;
    var printFired = false;

    function onTileStart()  { pending++; }
    function onTileEnd()    {
        pending--;
        if (pending <= 0 && !printFired) tryPrint();
    }

    function tryPrint() {
        if (printFired) return;
        printFired = true;
        map.off('tileloadstart', onTileStart);
        map.off('tileload',      onTileEnd);
        map.off('tilerror',      onTileEnd);
        overlay.classList.remove('active');
        window.print();
    }

    map.on('tileloadstart', onTileStart);
    map.on('tileload',      onTileEnd);
    map.on('tilerror',      onTileEnd);

    // Fallback: se entro 4s i tile non si caricano (tutti da cache o nessun tile layer),
    // stampa comunque
    setTimeout(function() {
        if (!printFired) tryPrint();
    }, 4000);

    // Imposta etichetta scala
    var scaleLabel = document.getElementById('print-scale-label');
    scaleLabel.textContent = 'Scala 1:' + scaleDenom.toLocaleString('it-IT');

    // Ripristino dopo che il dialogo di stampa si chiude
    window.addEventListener('afterprint', function cleanup() {
        window.removeEventListener('afterprint', cleanup);
        scaleLabel.textContent = '';
        container.style.width  = origWidth;
        container.style.height = origHeight;
        map.invalidateSize({ animate: false });
        map.setView(origCenter, origZoom, { animate: false });
    });
}


