
////////////////////////////////////////
//// MARKER PRATICHE EDILIZIE /////////
///////////////////////////////////////

/**
 * Crea un'icona a pin SVG colorato stile location-marker.
 * @param {string} color  colore esadecimale (es. '#1565C0')
 * @returns {L.DivIcon}
 */
function makePinIcon(color) {
    var svg = '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="36" viewBox="0 0 28 36">'
        + '<path d="M14 0C6.268 0 0 6.268 0 14c0 5.523 3.14 10.312 7.75 12.75L14 36l6.25-9.25C24.86 24.312 28 19.523 28 14 28 6.268 21.732 0 14 0z"'
        + ' fill="' + color + '" stroke="rgba(0,0,0,0.3)" stroke-width="1"/>'
        + '<circle cx="14" cy="14" r="6" fill="white" opacity="0.85"/>'
        + '</svg>';
    return L.divIcon({
        className: '',
        html: svg,
        iconSize:   [28, 36],
        iconAnchor: [14, 36],
        popupAnchor:[0, -34],
    });
}

var ipc   = makePinIcon('#1565C0'); // Permessi di Costruire — blu
var iscia = makePinIcon('#E65100'); // SCIA               — arancione
var icila = makePinIcon('#00838F'); // CILA               — verde acqua
var isca  = makePinIcon('#2E7D32'); // Agibilità          — verde
var iconc = makePinIcon('#6A1B9A'); // Concessioni        — viola
var icond = makePinIcon('#B71C1C'); // Condoni            — rosso scuro
var ialt  = makePinIcon('#546E7A'); // Altro              — grigio blu

/**
 * Popola i layer pratiche edilizie con i marker.
 * Chiamare dopo layers_def(map).
 * @param {Array} data  array di oggetti pratica (da json_encode PHP)
 */
function populatePratiche(data) {
    for (var i = 0; i < data.length; i++) {
        var p = data[i];

        var lat = parseFloat(p.Latitudine);
        var lng = parseFloat(p.Longitudine);
        if (isNaN(lat) || isNaN(lng)) continue;

        var latlng = [lat, lng];

        var DataTitolo = '';
        if (p.DataTitolo) {
            var dt = p.DataTitolo.split('-');
            DataTitolo = dt[2] + '-' + dt[1] + '-' + dt[0];
        }
        var DataProtocollo = '';
        if (p.DataProtocollo) {
            var dp = p.DataProtocollo.split('-');
            DataProtocollo = dp[2] + '-' + dp[1] + '-' + dp[0];
        }

        var Richiedente = parseInt(p.RegimeGiuridico_id) === 1
            ? (p.Cognome + ' ' + p.Nome)
            : p.Denominazione;

        var msg;
        switch (parseInt(p.id_titolo)) {
            case 1: // CILA
                msg = '<b>CILA</b><br>' + Richiedente +
                      '<br>Prot. ' + p.NumeroProtocollo + ' del ' + DataProtocollo +
                      '<br>' + p.DescrizioneIntervento;
                L.marker(latlng, {icon: icila}).bindPopup(msg).addTo(layerCILA);
                break;
            case 2: // SCIA
                msg = '<b>SCIA</b><br>' + Richiedente +
                      '<br>Prot. ' + p.NumeroProtocollo + ' del ' + DataProtocollo +
                      '<br>' + p.DescrizioneIntervento;
                L.marker(latlng, {icon: iscia}).bindPopup(msg).addTo(layerSCIA);
                break;
            case 3: // SuperSCIA
                msg = '<b>SuperSCIA</b><br>' + Richiedente +
                      '<br>Prot. ' + p.NumeroProtocollo + ' del ' + DataProtocollo +
                      '<br>' + p.DescrizioneIntervento;
                L.marker(latlng, {icon: iscia}).bindPopup(msg).addTo(layerAltro);
                break;
            case 4: // Permesso di Costruire
                msg = '<b>Permesso di Costruire</b> ' + p.NumeroTitolo + ' del ' + DataTitolo +
                      '<br>' + Richiedente +
                      '<br>' + p.DescrizioneIntervento;
                L.marker(latlng, {icon: ipc}).bindPopup(msg).addTo(layerPermessi);
                break;
            case 5: // Agibilità
                msg = '<b>Agibilità</b><br>' + Richiedente +
                      '<br>Prot. ' + p.NumeroProtocollo + ' del ' + DataProtocollo +
                      '<br>' + p.DescrizioneIntervento;
                L.marker(latlng, {icon: isca}).bindPopup(msg).addTo(layerSCA);
                break;
            case 6: // CIL
                msg = '<b>CIL</b><br>' + Richiedente +
                      '<br>Prot. ' + p.NumeroProtocollo + ' del ' + DataProtocollo +
                      '<br>' + p.DescrizioneIntervento;
                L.marker(latlng, {icon: icila}).bindPopup(msg).addTo(layerAltro);
                break;
            case 8: // Condono L.47/85
                msg = '<b>Condono L.47/85</b><br>' + Richiedente +
                      '<br>' + p.DescrizioneIntervento;
                L.marker(latlng, {icon: icond}).bindPopup(msg).addTo(layerAltro);
                break;
            case 9: // Condono L.724/94
                msg = '<b>Condono L.724/94</b> ' + p.NumeroTitolo + ' del ' + DataTitolo +
                      '<br>' + Richiedente +
                      '<br>' + p.DescrizioneIntervento;
                L.marker(latlng, {icon: icond}).bindPopup(msg).addTo(layerAltro);
                break;
            case 11: // Concessione
                msg = '<b>Concessione</b> ' + p.NumeroTitolo + ' del ' + DataTitolo +
                      '<br>' + Richiedente +
                      '<br>' + p.DescrizioneIntervento;
                L.marker(latlng, {icon: iconc}).bindPopup(msg).addTo(layerConcessioni);
                break;
            default:
                msg = '<b>Pratica edilizia</b><br>' + Richiedente +
                      '<br>' + p.DescrizioneIntervento;
                L.marker(latlng, {icon: ialt}).bindPopup(msg).addTo(layerAltro);
        }
    }
}
