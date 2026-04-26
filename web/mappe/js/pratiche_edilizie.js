
////////////////////////////////////////
//// MARKER PRATICHE EDILIZIE /////////
///////////////////////////////////////

var ipc = L.ExtraMarkers.icon({
    icon: 'fa-home',
    markerColor: 'blue',
    shape: 'circle',
    prefix: 'fa'
});

var iscia = L.ExtraMarkers.icon({
    icon: 'fa-bookmark',
    markerColor: 'orange',
    shape: 'star',
    prefix: 'fa'
});

var isca = L.ExtraMarkers.icon({
    icon: 'fa-check',
    markerColor: 'green',
    shape: 'penta',
    prefix: 'fa'
});

var icila = L.ExtraMarkers.icon({
    icon: 'fa-pencil',
    markerColor: 'cyan',
    shape: 'square',
    prefix: 'fa'
});

var icond = L.ExtraMarkers.icon({
    icon: 'fa-exclamation',
    markerColor: 'red',
    shape: 'circle',
    prefix: 'fa'
});

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
            default:
                msg = '<b>Pratica edilizia</b><br>' + Richiedente +
                      '<br>' + p.DescrizioneIntervento;
                L.marker(latlng).bindPopup(msg).addTo(layerAltro);
        }
    }
}
