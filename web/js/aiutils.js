// @ts-nocheck
/**
 * UTC-BIM AI Chat — Frontend utility
 * Gestisce la chat con il backend Ollama e le azioni Leaflet.
 */

/* global L */

// ---------------------------------------------------------------------------
// Path immagini avatar (usa UTCBIM_BASE iniettato dal layout PHP)
// ---------------------------------------------------------------------------
var _base = (window.UTCBIM_BASE || '');
var _aiAvatar   = _base + '/img/AI-uman.png';
var _userAvatar = _base + '/img/pino2.png';

// ---------------------------------------------------------------------------
// Riferimenti Leaflet (impostati da initAiChat se la mappa è presente)
// ---------------------------------------------------------------------------
let _leafletMap       = null;
let _leafletLayers    = {};   // { nome_layer: L.Layer }
let _highlightGroup   = null; // layer group per evidenziazioni temporanee
let _geojsonBasePath  = null; // path versioned da dati_mappe.folder_path (es. mappe/b542/V2025-10-02)

// IDs degli elementi DOM — sovrascrivibili via setAiContainerIds()
let _chatbodyId = 'chatbody';
let _msginputId = 'msguser';

function setAiContainerIds(chatbodyId, msginputId) {
    _chatbodyId = chatbodyId;
    _msginputId = msginputId;
}

/**
 * Inizializza il modulo chat con un'istanza Leaflet esistente.
 * @param {L.Map} map - istanza Leaflet
 * @param {Object} layers - mappa { nome: L.Layer }
 */
function initAiChat(map, layers, geojsonBasePath) {
    _leafletMap      = map;
    _leafletLayers   = layers || {};
    _highlightGroup  = L.layerGroup().addTo(map);
    _geojsonBasePath = geojsonBasePath || null;
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
    const cmsgs = document.getElementById(_chatbodyId);
    const div   = document.createElement('div');
    div.innerHTML = `
        <div class="direct-chat-msg right">
            <div class="direct-chat-infos clearfix">
                <span class="direct-chat-name float-right">${escHtml(utente)}</span>
                <span class="direct-chat-timestamp float-left">${dataoggi()}</span>
            </div>
            <img class="direct-chat-img float-right" src="${_userAvatar}" alt="utente">
            <div class="direct-chat-text left">${escHtml(msg)}</div>
        </div>`;
    cmsgs.appendChild(div);
    cmsgs.scrollTop = cmsgs.scrollHeight;
}

// ---------------------------------------------------------------------------
// Aggiunge un messaggio AI nella chat con pulsanti feedback
// ---------------------------------------------------------------------------
function addassmsg(text, extra) {
    const cmsgs  = document.getElementById(_chatbodyId);
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
    if (extra && (extra.pdf_url || extra.f24_url)) {
        let btns = '';
        if (extra.pdf_url) {
            btns += `<a href="${escHtml(extra.pdf_url)}" target="_blank" class="btn btn-sm btn-outline-danger mr-1">
                <i class="fas fa-calculator"></i> Calcolo IMU
            </a>`;
        }
        if (extra.f24_url) {
            btns += `<a href="${escHtml(extra.f24_url)}" target="_blank" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-file-invoice"></i> Modello F24
            </a>`;
        }
        extraHtml += `<div class="mt-2">${btns}</div>`;
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
            <img class="direct-chat-img" src="${_aiAvatar}" alt="AI">
            <div class="direct-chat-text">${html}${extraHtml}${feedbackHtml}</div>
        </div>`;
    cmsgs.appendChild(div);
    cmsgs.scrollTop = cmsgs.scrollHeight;
}

// ---------------------------------------------------------------------------
// Spinner "sta elaborando"
// ---------------------------------------------------------------------------
function showTyping() {
    const cmsgs = document.getElementById(_chatbodyId);
    const div   = document.createElement('div');
    div.id = 'ai-typing';
    div.innerHTML = `
        <div class="direct-chat-msg">
            <img class="direct-chat-img" src="${_aiAvatar}" alt="AI">
            <div class="direct-chat-text">
                <em>in elaborazione…</em>
                <div id="ai-typing-timer" style="font-size:10px;color:#aaa;margin-top:3px">0s</div>
            </div>
        </div>`;
    let _sec = 0;
    window._aiTypingInterval = setInterval(function() {
        _sec++;
        const el = document.getElementById('ai-typing-timer');
        if (el) el.textContent = _sec + 's' + (_sec > 30 ? ' — il modello sta ragionando…' : '');
    }, 1000);
    cmsgs.appendChild(div);
    cmsgs.scrollTop = cmsgs.scrollHeight;
}
function hideTyping() {
    if (window._aiTypingInterval) { clearInterval(window._aiTypingInterval); window._aiTypingInterval = null; }
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
    const msginp = document.getElementById(_msginputId);
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
            pdf_url:    result.pdf_url    || null,
            f24_url:    result.f24_url    || null,
            log_id:     result.log_id     || 0,
        });
        if (result.map_action) {
            handleMapAction(result.map_action);
        }
        if (result.catasto_parcelle && result.catasto_parcelle.length) {
            highlightCatastoResult(result.catasto_parcelle);
        }
    }).fail(function (_xhr, _status, error) {
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
 * Prima cerca nel layer statico; se non trovato carica il GeoJSON versioned dal server.
 */
function highlightParticelle(foglio, particelle) {
    if (!foglio || !_leafletMap) return;
    if (_highlightGroup) _highlightGroup.clearLayers();

    const foglioStr = String(foglio).padStart(2, '0');
    const layerName = 'foglio' + foglioStr + '_Particelle';
    const srcLayer  = _leafletLayers[layerName] || window[layerName] || null;

    if (srcLayer) {
        // layer statico già in memoria: filtra direttamente
        const filter = particelle
            ? (Array.isArray(particelle) ? particelle.map(String) : [String(particelle)])
            : null;
        const bounds = [];
        srcLayer.eachLayer(function (l) {
            const props  = l.feature && l.feature.properties;
            const partNo = props && (props.NUMERO || props.CODICE || props.PARTICELLA || props.NUMPARTIC);
            if (!filter || filter.includes(String(partNo))) {
                L.geoJSON(l.feature, {
                    style: { color: '#e74c3c', weight: 3, fillColor: '#e74c3c', fillOpacity: 0.4 },
                }).addTo(_highlightGroup);
                if (l.getBounds) bounds.push(l.getBounds());
            }
        });
        if (bounds.length > 0) {
            _leafletMap.fitBounds(
                bounds.reduce(function (acc, b) { return acc.extend(b); }),
                { padding: [40, 40] }
            );
        }
        return;
    }

    // fallback: carica il GeoJSON versioned (EPSG:7792 → riproiettato con L.Proj)
    if (!_geojsonBasePath) {
        addassmsg('Layer ' + layerName + ' non disponibile e nessun percorso GeoJSON configurato.', {});
        return;
    }

    const foglioCode = String(parseInt(foglio, 10) * 100).padStart(6, '0');
    const url = _geojsonBasePath + '/B542_' + foglioCode + '.geojson';

    $.getJSON(url, function (data) {
        if (!data || !data.features) {
            addassmsg('Dati catastali del foglio ' + foglio + ' non validi.', {});
            return;
        }

        const filter = particelle
            ? (Array.isArray(particelle) ? particelle.map(String) : [String(particelle)])
            : null;

        const geoJsonOpts = {
            filter: function (feature) {
                if (!feature.properties || feature.properties.LIVELLO !== 'PARTICELLE') return false;
                return !filter || filter.includes(String(feature.properties.CODICE));
            },
            style: { color: '#e74c3c', weight: 3, fillColor: '#e74c3c', fillOpacity: 0.4 },
        };

        // L.Proj.geoJson gestisce la riproiezione da EPSG:7792 a WGS84
        const projFn = (L.Proj && L.Proj.geoJson) ? L.Proj.geoJson : L.geoJSON;
        const layer = projFn(data, geoJsonOpts).addTo(_highlightGroup);

        try {
            const bounds = layer.getBounds();
            if (bounds.isValid()) {
                _leafletMap.fitBounds(bounds, { padding: [40, 40] });
            } else {
                addassmsg('Particella non trovata nel foglio ' + foglio + '.', {});
            }
        } catch (_) {
            addassmsg('Impossibile calcolare i bounds per foglio ' + foglio + '.', {});
        }
    }).fail(function () {
        addassmsg('Impossibile caricare i dati catastali del foglio ' + foglio + '.', {});
    });
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
            pointToLayer: function (_f, latlng) {
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
// Evidenzia particelle catastali da risultato catasto.db (multi-foglio async)
// ---------------------------------------------------------------------------
function highlightCatastoResult(parcelle) {
    if (!_leafletMap || !_highlightGroup || !parcelle || !parcelle.length) return;
    _highlightGroup.clearLayers();

    // Raggruppa per foglio: { 1: ['12','13'], 2: ['593',...], ... }
    const byFoglio = {};
    parcelle.forEach(function (p) {
        const f = parseInt(p.foglio, 10);
        if (!byFoglio[f]) byFoglio[f] = [];
        byFoglio[f].push(String(p.numero));
    });

    const fogli   = Object.keys(byFoglio).map(Number);
    let   pending = fogli.length;
    const allBounds = [];

    function onFoglioLoaded() {
        pending--;
        if (pending === 0) {
            try {
                if (!allBounds.length) return;
                const combined = allBounds.reduce(function (acc, b) { return acc.extend(b); });
                if (combined.isValid()) _leafletMap.fitBounds(combined, { padding: [30, 30] });
            } catch (_) {}
        }
    }

    fogli.forEach(function (foglio) {
        const numeri   = byFoglio[foglio];
        const foglioStr = String(foglio).padStart(2, '0');
        const layerName = 'foglio' + foglioStr + '_Particelle';
        const srcLayer  = _leafletLayers[layerName] || window[layerName] || null;

        if (srcLayer) {
            srcLayer.eachLayer(function (l) {
                const props  = l.feature && l.feature.properties;
                const partNo = props && (props.NUMERO || props.CODICE || props.PARTICELLA);
                if (numeri.includes(String(partNo))) {
                    const hl = L.geoJSON(l.feature, {
                        style: { color: '#e74c3c', weight: 3, fillColor: '#e74c3c', fillOpacity: 0.4 },
                    }).addTo(_highlightGroup);
                    try { allBounds.push(hl.getBounds()); } catch (_) {}
                }
            });
            onFoglioLoaded();
            return;
        }

        if (!_geojsonBasePath) { onFoglioLoaded(); return; }

        const foglioCode = String(foglio * 100).padStart(6, '0');
        const url = _geojsonBasePath + '/B542_' + foglioCode + '.geojson';
        const projFn = (L.Proj && L.Proj.geoJson) ? L.Proj.geoJson : L.geoJSON;

        $.getJSON(url, function (data) {
            if (!data || !data.features) return;
            const layer = projFn(data, {
                filter: function (feature) {
                    return feature.properties &&
                           feature.properties.LIVELLO === 'PARTICELLE' &&
                           numeri.includes(String(feature.properties.CODICE));
                },
                style: { color: '#e74c3c', weight: 3, fillColor: '#e74c3c', fillOpacity: 0.4 },
            }).addTo(_highlightGroup);
            try {
                const b = layer.getBounds();
                if (b.isValid()) allBounds.push(b);
            } catch (_) {}
        }).always(onFoglioLoaded);
    });
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
