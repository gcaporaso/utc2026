/*
 * function_collection.js
 * Raccolta di funzioni per la mappa catastale/urbanistica.
 * Autore: UTC - Comune di Bisacquino
 */

/**
 * Escape di caratteri HTML pericolosi (utility per output futuro).
 * @param {string} str
 * @returns {string}
 */
function escapeHtml(str) {
    if (str == null) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

/**
 * Individua la particella catastale alle coordinate indicate,
 * verifica che appartenga al comune (B542) e avvia la stampa
 * della scheda urbanistica tramite form Yii2.
 * @param {L.LatLng} coordinate
 */
function infocatasto(coordinate) {
    var gFIurl = 'https://wms.cartografia.agenziaentrate.gov.it/inspire/ajax/ajax.php?op=getDatiOggetto&lon='
        + coordinate.lng.toString() + '&lat=' + coordinate.lat.toString();

    if (gFIurl) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                var obj = xhttp.response;
                if (obj.COD_COMUNE !== 'B542') {
                    alert('La particella sta fuori comune!');
                    return;
                }
                var foglio = obj.FOGLIO;
                var particella = obj.NUM_PART;
                var url = 'index.php?r=mappe/schedaurbanistica';
                url += '&foglio=' + foglio;
                url += '&particella=' + particella;
                $('#urb-form').attr('action', url);
                $('#urb-form').yiiActiveForm('validate', true);
                $('#urb-form').yiiActiveForm('submitForm');
            }
        };
        xhttp.open('GET', gFIurl, true);
        xhttp.responseType = 'json';
        xhttp.send();
    }
}

/**
 * Gestore evento click: avvia la ricerca catastale sulla particella cliccata.
 * @param {L.MouseEvent} evt
 */
function showInfoCatasto(evt) {
    infocatasto(evt.latlng);
}

/**
 * Mostra le coordinate geografiche del punto cliccato.
 * @param {L.MouseEvent} e
 */
function showCoordinates(e) {
    alert('Latitudine: ' + e.latlng.lat.toFixed(6) + '\nLongitudine: ' + e.latlng.lng.toFixed(6));
}

/**
 * Centra la mappa sul punto cliccato.
 * @param {L.MouseEvent} e
 */
function centerMap(e) {
    map.panTo(e.latlng);
}

/**
 * Zoom in sulla mappa.
 */
function zoomIn() {
    map.zoomIn();
}

/**
 * Zoom out sulla mappa.
 */
function zoomOut() {
    map.zoomOut();
}

/**
 * Mostra le informazioni urbanistiche (PRG, PTP, borghi, idrogeologia, frane)
 * per il punto cliccato sulla mappa.
 * @param {L.MouseEvent} evt
 */
function showInfo(evt) {
    var coord = evt.latlng;
    var prg = leafletPip.pointInLayer(coord, prgLayer, true);
    var ptp = leafletPip.pointInLayer(coord, ptpLayer, true);
    var borghi = leafletPip.pointInLayer(coord, borghiLayer, true);
    var vidro = leafletPip.pointInLayer(coord, vidroLayer, true);
    var vfrane = leafletPip.pointInLayer(coord, pabLayer, true);
    var inperimetro = leafletPip.pointInLayer(coord, PerimetroLayer, true);

    if (Object.keys(inperimetro).length === 0) {
        alert('Attenzione: ' + '\n' +
              'Il punto che hai selezionato è fuori' + '\n' +
              'del territorio comunale!');
    } else {
        var zonaPRG = prg.length > 0 ? prg[0].feature.properties.Z : 'Non zonizzato';
        var zonaPTP = ptp.length > 0 ? ptp[0].feature.properties.zonizzazio : 'Non classificato';

        var isborgo = 'SI';
        var isIdro = 'SI';
        var isFrana = 'NO';

        if (Object.keys(borghi).length === 0) {
            isborgo = 'NO';
        }
        if (Object.keys(vidro).length === 0) {
            isIdro = 'NO';
        }
        if (Object.keys(vfrane).length > 0) {
            isFrana = vfrane[0].feature.properties.rischio;
        }

        alert('Zona PRG: ' + zonaPRG + '\n' +
              'Zona PTP: ' + zonaPTP + '\n' +
              'BORGHI AGRICOLI: ' + isborgo + '\n' +
              'Vincolo Idrogeologico: ' + isIdro + '\n' +
              'Rischio Frana: ' + isFrana);
    }
}

/**
 * Rimuove le righe di una tabella mantenendo le prime keepRows righe.
 * @param {HTMLTableElement} table
 * @param {number} keepRows  numero di righe da mantenere (le prime)
 */
function _clearTableRows(table, keepRows) {
    while (table.rows.length > keepRows) {
        table.deleteRow(keepRows);
    }
}

/**
 * Mostra la visura catastale (terreni o fabbricati) per il punto cliccato.
 * Chiama il proxy locale per ottenere foglio/particella, poi interroga
 * i servizi interni visuraterreni / visurafabbricati.
 * @param {L.MouseEvent} evt
 */
function showInfoX(evt) {
    var url = 'index.php?r=mappe/proxy';

    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        data: { lon: evt.latlng.lng, lat: evt.latlng.lat }
    }).done(function (result) {

        var Denominazione = result.DENOM;
        var foglio = result.FOGLIO;
        var particella = result.NUM_PART;
        var Comune = result.COD_COMUNE;

        if (Comune !== 'B542') {
            alert(Denominazione + '\n' +
                  'Foglio = ' + foglio + '  Particella = ' + particella);
            return;
        }

        if (!(foglio > 0 && particella > 0)) {
            alert('non ho individuato particella' + '\n' +
                  'mi dispiace!' + '\n' +
                  'foglio = ' + foglio + '\n' +
                  'particella = ' + particella);
            return;
        }

        // Ricerca dati terreni
        $.ajax({
            url: 'index.php?r=mappe%2Fvisuraterreni',
            type: 'POST',
            dataType: 'json',
            data: { foglio: foglio, particella: particella }
        }).done(function (result) {

            var datit = JSON.parse(result);

            if (datit['esito'] !== 'OK') {
                alert(datit['esito']);
                return;
            }

            if (datit['redditoDom']) {
                // ── TERRENI ──────────────────────────────────────────────
                var Titolari = datit.titolari;
                var btncl = document.getElementById('btnclose');
                var wmodt = document.getElementById('modal-terreni');
                var table = document.getElementById('tblvisura');

                wmodt.style.display = 'block';

                document.getElementById('idcomune').textContent = Denominazione;
                document.getElementById('idfoglio').textContent = foglio;
                document.getElementById('idparticella').textContent = particella;
                document.getElementById('idqualita').textContent = datit.Qualita;
                document.getElementById('idclasse').textContent = datit.Classe;
                document.getElementById('idsuperficie').textContent = datit.superficie;
                document.getElementById('idredditodom').textContent = datit.redditoDom;
                document.getElementById('idredditoagr').textContent = datit.redditoAgr;

                _clearTableRows(table, 5);

                for (var i = 0; i < Titolari.length; i++) {
                    table.insertRow(-1);
                    var row = table.rows[i + 5];

                    var cell1, cell2, cell3, cell4;
                    if (row.cells.length < 4) {
                        cell1 = row.insertCell(0);
                        cell1.style.border = 'border: 1px solid #ddd !important;';
                        cell2 = row.insertCell(1);
                        cell2.colSpan = 3;
                        cell2.style.fontSize = '14px';
                        cell3 = row.insertCell(2);
                        cell3.style.fontSize = '14px';
                        cell4 = row.insertCell(3);
                        cell4.colSpan = 2;
                        cell4.style.fontSize = '14px';
                    } else {
                        cell1 = row.cells[0];
                        cell2 = row.cells[1];
                        cell3 = row.cells[2];
                        cell4 = row.cells[3];
                    }

                    cell1.textContent = String(i + 1);

                    if (Titolari[i]['tipo'] === 'P') {
                        if ((Titolari[i]['comune_nascita'] === null) && (Titolari[i]['data_nascita'] === null)) {
                            cell2.textContent = Titolari[i]['denom'];
                        } else if (Titolari[i]['comune_nascita'] === null) {
                            cell2.textContent = Titolari[i]['denom'] + ' nato il ' + Titolari[i]['data_nascita'];
                        } else if (Titolari[i]['data_nascita'] === null) {
                            cell2.textContent = Titolari[i]['denom'] + ' nato/a a ' + Titolari[i]['comune_nascita'];
                        } else {
                            cell2.textContent = Titolari[i]['denom'] + ' nato/a a ' + Titolari[i]['comune_nascita'] + ' il ' + Titolari[i]['data_nascita'];
                        }
                    } else {
                        cell2.textContent = Titolari[i]['denom'];
                    }

                    cell3.textContent = Titolari[i]['codice_fiscale'];

                    if (Titolari[i]['quota'] === '0/0') {
                        cell4.textContent = Titolari[i]['diritto'];
                    } else {
                        cell4.textContent = Titolari[i]['diritto'] + Titolari[i]['quota'];
                    }

                    cell1.style = 'border: 1px solid #ddd !important;text-align: center;vertical-align: middle;';
                    cell2.style = 'border: 1px solid #ddd !important;vertical-align: middle;';
                    cell3.style = 'border: 1px solid #ddd !important;text-align: center;vertical-align: middle;';
                    cell4.style = 'border: 1px solid #ddd !important;text-align: center;vertical-align: middle;';
                }

                btncl.onclick = function () { wmodt.style.display = 'none'; };

            } else {
                // ── FABBRICATI ───────────────────────────────────────────
                var btnclf = document.getElementById('btnclosef');
                var wmodf = document.getElementById('modal-fabbricati');
                var tableu = document.getElementById('tblvisurau');

                _clearTableRows(tableu, 1);

                $.ajax({
                    url: 'index.php?r=mappe%2Fvisurafabbricati',
                    type: 'POST',
                    dataType: 'json',
                    data: { foglio: foglio, particella: particella }
                }).done(function (result) {

                    var datif = JSON.parse(result);
                    var nsub = datif.esub.length;

                    wmodf.style.display = 'block';

                    for (var s = 0; s < nsub; s++) {
                        // riga dati censuari
                        var rowi = tableu.insertRow(-1);
                        rowi.classList.add('DatiCensuari');
                        rowi.id = 'Censuari' + s;

                        var cell1 = rowi.insertCell(-1);
                        cell1.textContent = foglio;
                        cell1.style = 'border: 1px solid #ddd !important;text-align: center;vertical-align: middle';

                        var cell2 = rowi.insertCell(-1);
                        cell2.textContent = particella;
                        cell2.style = 'border: 1px solid #ddd !important;text-align: center;vertical-align: middle';

                        var cell3 = rowi.insertCell(-1);
                        cell3.textContent = datif.esub[s]['sub'];
                        cell3.style = 'border: 1px solid #ddd !important;text-align: center;vertical-align: middle';

                        var cell4 = rowi.insertCell(-1);
                        cell4.textContent = datif.esub[s]['categoria'];
                        cell4.style = 'border: 1px solid #ddd !important;text-align: center;vertical-align: middle';

                        var cell5 = rowi.insertCell(-1);
                        cell5.textContent = datif.esub[s]['classe'];
                        cell5.style = 'border: 1px solid #ddd !important;text-align: center;vertical-align: middle';

                        var cell6 = rowi.insertCell(-1);
                        cell6.textContent = datif.esub[s]['consistenza'];
                        cell6.style = 'border: 1px solid #ddd !important;text-align: center;vertical-align: middle';

                        var cell7 = rowi.insertCell(-1);
                        cell7.textContent = datif.esub[s]['superficie'];
                        cell7.style = 'border: 1px solid #ddd !important;text-align: center;vertical-align: middle';

                        var cell8 = rowi.insertCell(-1);
                        cell8.textContent = datif.esub[s]['rendita'];
                        cell8.style = 'border: 1px solid #ddd !important;text-align: center;vertical-align: middle';

                        var cell9 = rowi.insertCell(-1);
                        cell9.textContent = datif.esub[s]['decodifica'] + ' ' + datif.esub[s]['indirizzo'] + ' ' + datif.esub[s]['civico1'];
                        cell9.style = 'border: 1px solid #ddd !important;text-align: center;vertical-align: middle';
                        cell9.style.fontSize = '10px';

                        var cell10 = rowi.insertCell(-1);
                        cell10.textContent = datif.esub[s]['piano1'];
                        cell10.style = 'border: 1px solid #ddd !important;text-align: center;vertical-align: middle';
                        cell10.style.fontSize = '12px';

                        // righe intestatari per questo sub
                        for (var g = 0; g < datif.esub[s]['intestatari'].length; g++) {
                            var rowsxi = tableu.insertRow(-1);
                            rowsxi.classList.add('Intestati');
                            rowsxi.id = 'Intestati' + s + '.' + g;
                            rowsxi.class = 'expandable-body';

                            var cellsx1 = rowsxi.insertCell(-1);
                            var intestatario = datif.esub[s].intestatari[g];

                            if (intestatario.condice_fiscale.length > 0) {
                                if (intestatario.tipo === 'P') {
                                    var cfanno = intestatario.condice_fiscale.substring(9, 11);
                                    var anno = parseInt(cfanno.replace(/^0+/, ''));
                                    var snato = anno > 40 ? ' nata ' : ' nato ';

                                    if ((intestatario.comune_nascita === null) && (intestatario.data_nascita === null)) {
                                        cellsx1.textContent = intestatario.denominazione;
                                    } else if (intestatario.comune_nascita === null) {
                                        cellsx1.textContent = intestatario.denominazione + snato + 'il ' + intestatario.data_nascita;
                                    } else if (intestatario.data_nascita === null) {
                                        cellsx1.textContent = intestatario.denominazione + snato + 'a ' + intestatario.comune_nascita;
                                    } else {
                                        cellsx1.textContent = intestatario.denominazione + snato + 'a ' + intestatario.comune_nascita + ' il ' + intestatario.data_nascita;
                                    }
                                } else {
                                    // persona giuridica
                                    cellsx1.textContent = intestatario.denominazione;
                                }
                            } else {
                                cellsx1.textContent = intestatario.denominazione + ' nato a ' + intestatario.comune_nascita + ' il ' + intestatario.data_nascita;
                            }

                            cellsx1.colSpan = 6;
                            cellsx1.style = 'border: 1px solid #ddd !important;vertical-align: middle';
                            cellsx1.style.fontSize = '14px';

                            var cellsx4 = rowsxi.insertCell(-1);
                            cellsx4.textContent = intestatario.condice_fiscale;
                            cellsx4.colSpan = 2;
                            cellsx4.style.fontSize = '10px';
                            cellsx4.style = 'border: 1px solid #ddd !important;text-align: center;vertical-align: middle';

                            var cellsx5 = rowsxi.insertCell(-1);
                            if (intestatario.quota === '0/0') {
                                cellsx5.textContent = intestatario.diritto;
                            } else {
                                cellsx5.textContent = intestatario.diritto + ' ' + intestatario.quota;
                            }
                            cellsx5.colSpan = 3;
                            cellsx5.style.fontSize = '11px';
                            cellsx5.style = 'border: 1px solid #ddd !important;text-align: center;vertical-align: middle';
                        }
                    }

                    // Nascondi righe intestatari
                    var lst = document.getElementsByClassName('Intestati');
                    for (var ii = 0; ii < lst.length; ++ii) {
                        lst[ii].style.display = 'none';
                    }

                    // Bind click su righe DatiCensuari per espandere/collassare intestatari
                    var rowsds = tableu.getElementsByClassName('DatiCensuari');
                    Array.prototype.forEach.call(rowsds, function (currentRow) {
                        currentRow.onclick = function () {
                            var currRowID = this.id;
                            var cID = currRowID.substring(8);
                            var rowsints = tableu.getElementsByClassName('Intestati');
                            for (var tri = 0; tri < rowsints.length; tri++) {
                                var iID = rowsints[tri].id;
                                var cIntID = iID.split('.')[0].substring(9);
                                if (cID === cIntID) {
                                    rowsints[tri].style.display = rowsints[tri].style.display === 'none' ? '' : 'none';
                                }
                            }
                        };
                    });

                    btnclf.onclick = function () { wmodf.style.display = 'none'; };

                }); // fine ajax visurafabbricati

            } // fine blocco fabbricati

        }); // fine ajax visuraterreni

    }).fail(function (_xhr, _status, error) {
        console.error('Errore proxy:', error);
        alert('Errore nel recupero dati: ' + error);
    }); // fine ajax proxy

}

/**
 * Mostra o nasconde tutti gli elementi con la classe CSS indicata.
 * @param {string} cls  nome della classe CSS
 * @param {boolean|string} on  se truthy mostra gli elementi, altrimenti li nasconde
 */
function toggle_by_class(cls, on) {
    var lst = document.getElementsByClassName(cls);
    for (var i = 0; i < lst.length; ++i) {
        lst[i].style.display = on ? '' : 'none';
    }
}

// ── Ricerca per coordinate WGS84 ──────────────────────────────────────────────

var _coordMarker = null;

/**
 * Legge i campi #input-lat e #input-lng (WGS84), valida le coordinate e
 * posiziona/sposta un marker a mirino rosso sulla mappa centrandola sul punto.
 */
function goToCoords() {
    var latRaw = document.getElementById('input-lat').value.trim().replace(',', '.');
    var lngRaw = document.getElementById('input-lng').value.trim().replace(',', '.');
    var lat = parseFloat(latRaw);
    var lng = parseFloat(lngRaw);

    if (isNaN(lat) || isNaN(lng) || lat < -90 || lat > 90 || lng < -180 || lng > 180) {
        alert('Coordinate non valide.\nLatitudine: -90 ... +90\nLongitudine: -180 ... +180');
        return;
    }

    var latlng = L.latLng(lat, lng);

    if (_coordMarker) {
        _coordMarker.setLatLng(latlng);
    } else {
        var crosshairIcon = L.divIcon({
            className: '',
            html: '<svg width="36" height="36" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">'
                + '<circle cx="18" cy="18" r="8" fill="none" stroke="red" stroke-width="2.5"/>'
                + '<line x1="18" y1="0"  x2="18" y2="10" stroke="red" stroke-width="2.5" stroke-linecap="round"/>'
                + '<line x1="18" y1="26" x2="18" y2="36" stroke="red" stroke-width="2.5" stroke-linecap="round"/>'
                + '<line x1="0"  y1="18" x2="10" y2="18" stroke="red" stroke-width="2.5" stroke-linecap="round"/>'
                + '<line x1="26" y1="18" x2="36" y2="18" stroke="red" stroke-width="2.5" stroke-linecap="round"/>'
                + '</svg>',
            iconSize:   [36, 36],
            iconAnchor: [18, 18],
        });
        _coordMarker = L.marker(latlng, { icon: crosshairIcon, zIndexOffset: 1000 }).addTo(map);
    }

    map.setView(latlng, 18);
}
