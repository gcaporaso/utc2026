/**
 * minosx.js — Layer pubblica illuminazione (MinosX / UMPI)
 *
 * Dipendenze: Leaflet, jQuery, layers.js (overlaysTree via buildMinosxTreeNode())
 * La variabile MINOSX_PROXY_URL deve essere definita dalla view PHP prima di
 * includere questo file.
 */

// ---------------------------------------------------------------------------
// Colori per stato lampada (STATUS field)
// ---------------------------------------------------------------------------
var MINOSX_STATUS_COLOR = {
    '1':  '#00cc44',   // accesa / funzionante
    '2':  '#00cc44',   // accesa / funzionante
    '0':  '#3399ff',   // spenta
    '-1': '#ffcc00',   // allarme
    '-2': '#aaaaaa',   // non raggiungibile
    '-3': '#ffcc00',   // allarme
    '8':  '#ffcc00',
    '9':  '#ffcc00',
    '10': '#ffcc00'
};

var MINOSX_STATUS_LABEL = {
    '1':  'Funzionante',
    '2':  'Funzionante',
    '0':  'Spenta',
    '-1': 'Allarme',
    '-2': 'Non raggiungibile',
    '-3': 'Allarme',
    '8':  'Allarme',
    '9':  'Allarme',
    '10': 'Allarme'
};

// ---------------------------------------------------------------------------
// Descrizioni stato da codice FAILURE (campo restituito da MinosX)
// ---------------------------------------------------------------------------
// Colori badge popup per codici FAILURE specifici (sovrascrivono il colore STATUS)
var MINOSX_FAILURE_COLORS = {
    '0313': '#cc0000'   // Lampada non funzionante — rosso
};

var MINOSX_FAILURE_LABELS = {
    '0301': 'Stato non acquisito',
    '0302': 'Lampada OK',
    '0305': 'Dimming non eseguito',
    // Altri codici da mappare quando si verificano:
    // '0303': '...',
    // '0304': '...',
    // '0307': '...',
    // '0312': '...',
    '0313': 'Lampada non funzionante',
    // '0314': '...',
    // '0315': '...',
    // '0319': '...'
};

// ---------------------------------------------------------------------------
// Layer interni (tenuti in closure per poterli aggiornare)
// ---------------------------------------------------------------------------
var _minoxLampsLayer    = null;
var _minoxCabinetsLayer = null;
var _minoxLoaded        = { lamps: false, cabinets: false };

// ---------------------------------------------------------------------------
// Helper: stile cerchio lampada
// ---------------------------------------------------------------------------
function _minoxLampStyle(status, failureCode) {
    var color = MINOSX_FAILURE_COLORS[String(failureCode)]
             || MINOSX_STATUS_COLOR[String(status)]
             || '#888888';
    return {
        radius:      5,
        fillColor:   color,
        color:       '#333333',
        weight:      1,
        opacity:     1,
        fillOpacity: 0.9
    };
}

// ---------------------------------------------------------------------------
// Helper: icona quadrata cabina (DivIcon)
// ---------------------------------------------------------------------------
function _minoxCabinetIcon(status) {
    var color = MINOSX_STATUS_COLOR[String(status)] || '#888888';
    var size = 14;
    return L.divIcon({
        className: '',
        html: '<div style="width:' + size + 'px;height:' + size + 'px;'
            + 'background:' + color + ';'
            + 'border:2px solid #222;'
            + 'box-sizing:border-box;"></div>',
        iconSize:   [size, size],
        iconAnchor: [size / 2, size / 2],
        popupAnchor:[0, -(size / 2)]
    });
}

// ---------------------------------------------------------------------------
// Crea popup HTML per una lampada
// ---------------------------------------------------------------------------
function _minoxLampPopup(props) {
    var status = String(props.STATUS);
    var label  = MINOSX_STATUS_LABEL[status] || ('Stato ' + status);
    var color  = MINOSX_STATUS_COLOR[status]  || '#888888';

    var div = document.createElement('div');
    div.style.minWidth = '170px';

    var title = document.createElement('b');
    title.textContent = 'Palo n. ' + props.name;
    div.appendChild(title);
    div.appendChild(document.createElement('br'));

    var badge = document.createElement('span');
    badge.style.cssText = 'display:inline-block;padding:2px 8px;border-radius:4px;'
        + 'background:' + color + ';color:#fff;font-size:0.85em;margin:3px 0 4px;';
    badge.textContent = label;
    div.appendChild(badge);

    var table = document.createElement('table');
    table.style.cssText = 'font-size:0.85em;width:100%;border-collapse:collapse;';

    var failureCode  = String(props.FAILURE || '');
    var failureText  = MINOSX_FAILURE_LABELS[failureCode] || '';
    var failureColor = MINOSX_FAILURE_COLORS[failureCode];
    if (failureColor) color = failureColor;

    var rows = [];
    if (failureText) {
        rows.push(['Stato', failureText]);
    }
    rows.push(['Quadro', props.QUADRO || '—']);

    rows.forEach(function(r) {
        var tr = document.createElement('tr');
        var th = document.createElement('td');
        th.style.cssText = 'color:#555;padding-right:6px;white-space:nowrap;vertical-align:top;';
        th.textContent = r[0];
        var td = document.createElement('td');
        td.textContent = r[1];
        tr.appendChild(th);
        tr.appendChild(td);
        table.appendChild(tr);
    });
    div.appendChild(table);

    return div;
}

// ---------------------------------------------------------------------------
// Crea popup HTML per una cabina
// ---------------------------------------------------------------------------
function _minoxCabinetPopup(props) {
    var status = String(props.STATUS);
    var label  = MINOSX_STATUS_LABEL[status] || ('Stato ' + status);
    var color  = MINOSX_STATUS_COLOR[status]  || '#888888';

    var div = document.createElement('div');

    var title = document.createElement('b');
    title.textContent = 'Cabina ' + props.name;
    div.appendChild(title);
    div.appendChild(document.createElement('br'));

    var badge = document.createElement('span');
    badge.style.cssText = 'display:inline-block;padding:2px 8px;border-radius:4px;'
        + 'background:' + color + ';color:#fff;font-size:0.85em;margin:3px 0 2px;';
    badge.textContent = label;
    div.appendChild(badge);

    return div;
}

// ---------------------------------------------------------------------------
// Costruisce (o resituisce già costruito) il layer lampade
// ---------------------------------------------------------------------------
function _getMinoxLampsLayer() {
    if (_minoxLampsLayer) return _minoxLampsLayer;

    _minoxLampsLayer = L.geoJSON(null, {
        pointToLayer: function(feature, latlng) {
            return L.circleMarker(latlng, _minoxLampStyle(feature.properties.STATUS, feature.properties.FAILURE));
        },
        onEachFeature: function(feature, layer) {
            layer.bindPopup(function() {
                return _minoxLampPopup(feature.properties);
            });
            layer.bindTooltip('Palo ' + feature.properties.name, {
                sticky: true, opacity: 0.9,
                className: 'minosx-tooltip'
            });
        }
    });

    _minoxLampsLayer.on('add', function() {
        if (_minoxLoaded.lamps) return;
        _minoxLoaded.lamps = true;
        var url = (typeof MINOSX_PROXY_URL !== 'undefined' ? MINOSX_PROXY_URL : 'index.php?r=mappe/minosx-lamps')
                  + '&type=lamps';
        $.ajax({
            url: url,
            dataType: 'json',
            success: function(data) {
                _minoxLampsLayer.addData(data);
            },
            error: function(_xhr, _status, err) {
                console.error('MinosX lamps error:', err);
                _minoxLoaded.lamps = false;
            }
        });
    });

    return _minoxLampsLayer;
}

// ---------------------------------------------------------------------------
// Costruisce (o resituisce già costruito) il layer cabine
// ---------------------------------------------------------------------------
function _getMinoxCabinetsLayer() {
    if (_minoxCabinetsLayer) return _minoxCabinetsLayer;

    _minoxCabinetsLayer = L.geoJSON(null, {
        pointToLayer: function(feature, latlng) {
            return L.marker(latlng, { icon: _minoxCabinetIcon(feature.properties.STATUS) });
        },
        onEachFeature: function(feature, layer) {
            layer.bindPopup(function() {
                return _minoxCabinetPopup(feature.properties);
            });
            layer.bindTooltip('Cabina ' + feature.properties.name, {
                sticky: true, opacity: 0.85
            });
        }
    });

    _minoxCabinetsLayer.on('add', function() {
        if (_minoxLoaded.cabinets) return;
        _minoxLoaded.cabinets = true;
        var url = (typeof MINOSX_PROXY_URL !== 'undefined' ? MINOSX_PROXY_URL : 'mappe/minosx-lamps')
                  + '&type=cabinets';
        $.ajax({
            url: url,
            dataType: 'json',
            success: function(data) {
                _minoxCabinetsLayer.addData(data);
            },
            error: function(_xhr, _status, err) {
                console.error('MinosX cabinets error:', err);
                _minoxLoaded.cabinets = false;
            }
        });
    });

    return _minoxCabinetsLayer;
}

// ---------------------------------------------------------------------------
// Nodo per L.Control.Layers.Tree
// ---------------------------------------------------------------------------
function buildMinosxTreeNode() {
    return {
        label: 'Pubblica Illuminazione',
        collapsed: true,
        children: [
            { label: 'Pali / Lampade',  layer: _getMinoxLampsLayer() },
            { label: 'Cabine elettriche', layer: _getMinoxCabinetsLayer() }
        ]
    };
}
