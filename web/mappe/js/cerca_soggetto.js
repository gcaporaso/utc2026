// Ricerca soggetto catastale (Cognome/Denominazione, Nome, Codice Fiscale, Data di nascita)
// ed evidenziazione sulla mappa delle particelle/unità catastali (terreni e fabbricati)
// a lui intestate.

var _soggettoHighlightGroup = null;

function _soggettoToast(message) {
    var body = document.getElementById('soggetto-toast-body');
    if (body) { body.textContent = message; }
    if (window.jQuery) { jQuery('#soggetto-toast').toast('show'); }
    else { alert(message); }
}

function cercaSoggettoCatasto() {
    var soggetto  = (document.getElementById('input-soggetto') || {}).value || '';
    var nome      = (document.getElementById('input-nome') || {}).value || '';
    var codFisc   = (document.getElementById('input-cf') || {}).value || '';
    var dataNasc  = (document.getElementById('input-data-nascita') || {}).value || '';

    soggetto = soggetto.trim();
    nome     = nome.trim();
    codFisc  = codFisc.trim();
    dataNasc = dataNasc.trim();

    if (!soggetto && !codFisc) {
        _soggettoToast('Inserire almeno Cognome/Denominazione o Codice Fiscale.');
        return;
    }

    $.ajax({
        url: 'index.php?r=mappe/cerca-soggetto',
        type: 'POST',
        dataType: 'json',
        data: {
            soggetto:        soggetto,
            nome:            nome,
            codice_fiscale:  codFisc,
            data_nascita:    dataNasc
        }
    }).done(function (result) {
        if (!result || !result.ok) {
            _soggettoToast(result && result.error ? result.error : 'Nessuna particella trovata.');
            return;
        }
        _soggettoToast('Trovate ' + result.count + ' unità catastali intestate a ' + result.label + '.');
        evidenziaParticelleSoggetto(result.parcelle);
    }).fail(function () {
        _soggettoToast('Errore di comunicazione con il server durante la ricerca.');
    });
}

/**
 * Evidenzia sulla mappa le particelle indicate (array di {foglio, numero}),
 * caricando i GeoJSON versionati per foglio dalla cartella catastale corrente
 * (CARTELLA_CATASTALE, esposta globalmente in mappe.php).
 * Riutilizzata anche da cerca_particelle.js per la ricerca diretta foglio+particella.
 */
function evidenziaParticelleSoggetto(parcelle) {
    if (typeof map === 'undefined' || !parcelle || !parcelle.length) return;

    if (!_soggettoHighlightGroup) {
        _soggettoHighlightGroup = L.layerGroup().addTo(map);
    }
    _soggettoHighlightGroup.clearLayers();

    var byFoglio = {};
    parcelle.forEach(function (p) {
        var f = parseInt(p.foglio, 10);
        if (!byFoglio[f]) byFoglio[f] = [];
        byFoglio[f].push(String(p.numero));
    });

    var fogli   = Object.keys(byFoglio).map(Number);
    var pending = fogli.length;
    var trovate = []; // { bounds, superficie } per ogni particella evidenziata

    function onFoglioLoaded() {
        pending--;
        if (pending === 0) { _zoomAiRisultatiSoggetto(trovate); }
    }

    fogli.forEach(function (foglio) {
        var numeri     = byFoglio[foglio];
        var foglioCode = String(foglio * 100).padStart(6, '0');
        var url        = CARTELLA_CATASTALE + '/B542_' + foglioCode + '.geojson';
        var projFn     = (L.Proj && L.Proj.geoJson) ? L.Proj.geoJson : L.geoJSON;

        $.getJSON(url, function (data) {
            if (!data || !data.features) return;
            projFn(data, {
                filter: function (feature) {
                    return feature.properties &&
                           feature.properties.LIVELLO === 'PARTICELLE' &&
                           numeri.indexOf(String(feature.properties.CODICE)) !== -1;
                },
                style: { color: '#e74c3c', weight: 3, fillColor: '#e74c3c', fillOpacity: 0.4 },
                onEachFeature: function (feature, featLayer) {
                    _soggettoHighlightGroup.addLayer(featLayer);
                    try {
                        var b = featLayer.getBounds ? featLayer.getBounds() : null;
                        if (b && b.isValid()) {
                            trovate.push({
                                bounds:     b,
                                superficie: parseFloat(feature.properties.SUPERFICIE) || 0
                            });
                        }
                    } catch (e) { /* geometria senza bounds calcolabili */ }
                }
            });
        }).fail(function () { /* geojson foglio non disponibile */ })
          .always(onFoglioLoaded);
    });
}

/** Centra ed effettua lo zoom sulla zona in cui si trova l'immobile con la superficie maggiore. */
function _zoomAiRisultatiSoggetto(trovate) {
    if (!trovate.length) {
        _soggettoToast('Particelle trovate ma non presenti nella cartografia vettoriale corrente.');
        return;
    }
    trovate.sort(function (a, b) { return b.superficie - a.superficie; });
    map.fitBounds(trovate[0].bounds, { padding: [60, 60], maxZoom: 20 });
}

jQuery(function () {
    ['input-soggetto', 'input-nome', 'input-cf', 'input-data-nascita'].forEach(function (id) {
        var el = document.getElementById(id);
        if (el) {
            el.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') cercaSoggettoCatasto();
            });
        }
    });
});
