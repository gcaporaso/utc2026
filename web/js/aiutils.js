/**
 * UTC-BIM AI Chat — Frontend utility
 * Gestisce la chat con il backend Ollama e le azioni Leaflet.
 */

/* global L */

// ---------------------------------------------------------------------------
// Riferimenti Leaflet (impostati da initAiChat se la mappa è presente)
// ---------------------------------------------------------------------------
let _leafletMap       = null;
let _leafletLayers    = {};   // { nome_layer: L.Layer }
let _highlightGroup   = null; // layer group per evidenziazioni temporanee

/**
 * Inizializza il modulo chat con un'istanza Leaflet esistente.
 * @param {L.Map} map - istanza Leaflet
 * @param {Object} layers - mappa { nome: L.Layer }
 */
function initAiChat(map, layers) {
    _leafletMap    = map;
    _leafletLayers = layers || {};
    _highlightGroup = L.layerGroup().addTo(map);
}

// ---------------------------------------------------------------------------
// Formattazione timestamp
// ---------------------------------------------------------------------------
function dataoggi() {
    const d = new Date();
    return [d.getDate(), d.getMonth() + 1, d.getFullYear()].map(n => String(n).padStart(2, '0')).join('/') +
        ' ' + [d.getHours(), d.getMinutes(), d.getSeconds()].map(n => String(n).padStart(2, '0')).join(':');
}

// ---------------------------------------------------------------------------
// Aggiunge un messaggio utente nella chat
// ---------------------------------------------------------------------------
function addusrmsg(msg, utente) {
    const cmsgs = document.getElementById('chatbody');
    const div   = document.createElement('div');
    div.innerHTML = `
        <div class="direct-chat-msg right">
            <div class="direct-chat-infos clearfix">
                <span class="direct-chat-name float-right">${escHtml(utente)}</span>
                <span class="direct-chat-timestamp float-left">${dataoggi()}</span>
            </div>
            <img class="direct-chat-img float-right" src="img/user3-128x128.jpg" alt="utente">
            <div class="direct-chat-text left">${escHtml(msg)}</div>
        </div>`;
    cmsgs.appendChild(div);
    cmsgs.scrollTop = cmsgs.scrollHeight;
}

// ---------------------------------------------------------------------------
// Aggiunge un messaggio AI nella chat con pulsanti feedback
// ---------------------------------------------------------------------------
function addassmsg(text, extra) {
    const cmsgs  = document.getElementById('chatbody');
    const div    = document.createElement('div');
    const html   = String(text).replace(/\n/g, '<br>');
    const logId  = extra && extra.log_id ? extra.log_id : 0;

    let extraHtml = '';
    if (extra && extra.count !== undefined && extra.count !== null) {
        extraHtml += `<div class="chat-meta text-muted"><small>Righe restituite: <strong>${extra.count}</strong></small></div>`;
    }
    if (extra && extra.query) {
        extraHtml += `<details class="chat-query mt-1"><summary><small>Mostra query SQL</small></summary><pre class="mt-1" style="font-size:11px">${escHtml(extra.query)}</pre></details>`;
    }
    if (extra && extra.map_action) {
        extraHtml += `<div class="chat-map-badge mt-1"><small><i class="fas fa-map-marked-alt"></i> Azione eseguita sulla mappa</small></div>`;
    }

    // Pulsanti feedback
    const feedbackHtml = logId ? `
        <div class="chat-feedback mt-1" id="fb-${logId}">
            <small class="text-muted">Risposta utile?</small>
            <button class="btn btn-xs btn-outline-success ml-1" onclick="sendFeedback(${logId},1,this)" title="Sì, risposta corretta">👍</button>
            <button class="btn btn-xs btn-outline-danger ml-1"  onclick="sendFeedback(${logId},0,this)" title="No, risposta errata">👎</button>
        </div>` : '';

    div.innerHTML = `
        <div class="direct-chat-msg">
            <div class="direct-chat-infos clearfix">
                <span class="direct-chat-name float-left">AI-UTC-BIM</span>
                <span class="direct-chat-timestamp float-right">${dataoggi()}</span>
            </div>
            <img class="direct-chat-img" src="img/user2-160x160.jpg" alt="AI">
            <div class="direct-chat-text">${html}${extraHtml}${feedbackHtml}</div>
        </div>`;
    cmsgs.appendChild(div);
    cmsgs.scrollTop = cmsgs.scrollHeight;
}

// ---------------------------------------------------------------------------
// Spinner "sta elaborando"
// ---------------------------------------------------------------------------
function showTyping() {
    const cmsgs = document.getElementById('chatbody');
    const div   = document.createElement('div');
    div.id = 'ai-typing';
    div.innerHTML = `
        <div class="direct-chat-msg">
            <img class="direct-chat-img" src="img/user2-160x160.jpg" alt="AI">
            <div class="direct-chat-text"><em>in elaborazione…</em></div>
        </div>`;
    cmsgs.appendChild(div);
    cmsgs.scrollTop = cmsgs.scrollHeight;
}
function hideTyping() {
    const el = document.getElementById('ai-typing');
    if (el) el.remove();
}

// ---------------------------------------------------------------------------
// Escape HTML
// ---------------------------------------------------------------------------
function escHtml(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

// ---------------------------------------------------------------------------
// Invio richiesta principale
// ---------------------------------------------------------------------------
function airequest() {
    const msginp = document.getElementById('msguser');
    const msg    = msginp.value.trim();
    if (!msg) return;

    addusrmsg(msg, 'Utente');
    msginp.value = '';
    showTyping();

    $.ajax({
        url:      'index.php?r=chat/chat',
        type:     'POST',
        dataType: 'json',
        data:     { msguser: msg },
    }).done(function (result) {
        hideTyping();
        if (result.error) {
            addassmsg('Errore: ' + result.error);
            return;
        }
        addassmsg(result.response || 'Nessuna risposta.', {
            query:      result.query      || null,
            count:      result.count      !== undefined ? result.count : null,
            map_action: result.map_action || null,
        });
        if (result.map_action) {
            handleMapAction(result.map_action);
        }
    }).fail(function (xhr, status, error) {
        hideTyping();
        addassmsg('Errore di rete: ' + error);
    });
}

// ---------------------------------------------------------------------------
// Gestione azioni Leaflet
// ---------------------------------------------------------------------------
function handleMapAction(action) {
    if (!_leafletMap) return;

    const params = action.params || {};

    switch (action.action) {
        case 'highlight_particelle':
            highlightParticelle(params.foglio, params.particelle);
            break;
        case 'zoom_foglio':
            zoomToFoglio(params.foglio);
            break;
        case 'zoom_particella':
            zoomToParticella(params.foglio, params.particella);
            break;
        case 'highlight_edilizia':
            loadEdiliziaMarkers(params);
            break;
        case 'show_layer':
            toggleLayer(params.layer, true);
            break;
        case 'hide_layer':
            toggleLayer(params.layer, false);
            break;
        case 'reset_map':
            resetMap();
            break;
        default:
            console.warn('Azione mappa non gestita:', action.action);
    }
}

/**
 * Evidenzia le particelle di un foglio sulla mappa Leaflet.
 */
function highlightParticelle(foglio, particelle) {
    if (!foglio || !_leafletMap) return;
    if (_highlightGroup) _highlightGroup.clearLayers();

    const foglioStr = String(foglio).padStart(2, '0');
    const layerName = 'foglio' + foglioStr + '_Particelle';

    const srcLayer = _leafletLayers[layerName] || window[layerName] || null;
    if (!srcLayer) {
        addassmsg('Layer ' + layerName + ' non disponibile. Assicurarsi che sia caricato nella mappa.');
        return;
    }

    const bounds = [];
    srcLayer.eachLayer(function (l) {
        const props  = l.feature && l.feature.properties;
        const partNo = props && (props.PARTICELLA || props.NUMPARTIC || props.particella || props.numero);
        const match  = !particelle ||
                       (Array.isArray(particelle) ? particelle.includes(String(partNo)) : String(partNo) === String(particelle));
        if (match) {
            L.geoJSON(l.feature, {
                style: { color: '#e74c3c', weight: 3, fillColor: '#e74c3c', fillOpacity: 0.4 },
            }).addTo(_highlightGroup);
            if (l.getBounds) bounds.push(l.getBounds());
        }
    });

    if (bounds.length > 0) {
        const combined = bounds.reduce(function (acc, b) { return acc.extend(b); });
        _leafletMap.fitBounds(combined, { padding: [40, 40] });
    }
}

function zoomToFoglio(foglio) {
    highlightParticelle(foglio, null);
}

function zoomToParticella(foglio, particella) {
    highlightParticelle(foglio, [String(particella)]);
}

/**
 * Carica marker pratiche edilizie con coordinate GPS via API.
 */
function loadEdiliziaMarkers(params) {
    if (!_leafletMap) return;
    if (_highlightGroup) _highlightGroup.clearLayers();

    let url = 'index.php?r=chat/edilizia-geojson';
    if (params.anno)   url += '&anno='   + encodeURIComponent(params.anno);
    if (params.foglio) url += '&foglio=' + encodeURIComponent(params.foglio);

    $.getJSON(url, function (geojson) {
        if (!geojson || !geojson.features || geojson.features.length === 0) {
            addassmsg('Nessuna pratica con coordinate trovata per i filtri selezionati.');
            return;
        }
        const layer = L.geoJSON(geojson, {
            pointToLayer: function (f, latlng) {
                return L.circleMarker(latlng, {
                    radius: 8, color: '#2980b9', fillColor: '#3498db', fillOpacity: 0.8, weight: 2,
                });
            },
            onEachFeature: function (f, l) {
                l.bindPopup(
                    '<b>Pratica ' + (f.properties.NumeroProtocollo || '') + '</b><br>' +
                    (f.properties.DataProtocollo || '') + '<br>' +
                    escHtml(f.properties.DescrizioneIntervento || '')
                );
            },
        });
        layer.addTo(_highlightGroup);
        _leafletMap.fitBounds(layer.getBounds(), { padding: [30, 30] });
    });
}

function toggleLayer(layerName, visible) {
    const layer = _leafletLayers[layerName] || window[layerName] || null;
    if (!layer || !_leafletMap) return;
    if (visible) {
        if (!_leafletMap.hasLayer(layer)) _leafletMap.addLayer(layer);
    } else {
        if (_leafletMap.hasLayer(layer)) _leafletMap.removeLayer(layer);
    }
}

function resetMap() {
    if (_highlightGroup) _highlightGroup.clearLayers();
}

// ---------------------------------------------------------------------------
// Invio feedback 👍 / 👎
// ---------------------------------------------------------------------------
function sendFeedback(logId, value, btn) {
    btn.disabled = true;
    var note = '';
    if (value === 0) {
        note = prompt('(Facoltativo) Cosa c\'era di sbagliato nella risposta?') || '';
    }
    $.ajax({
        url:  'index.php?r=chat/feedback',
        type: 'POST',
        data: { log_id: logId, value: value, note: note },
    }).done(function (r) {
        if (r && r.ok) {
            var container = document.getElementById('fb-' + logId);
            if (container) {
                container.innerHTML = value === 1
                    ? '<small class="text-success">👍 Grazie per il feedback!</small>'
                    : '<small class="text-danger">👎 Annotato — lo useremo per migliorare il modello.</small>';
            }
        } else {
            btn.disabled = false;
        }
    }).fail(function () { btn.disabled = false; });
}
