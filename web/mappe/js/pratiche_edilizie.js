

////////////////////////////////////////
//// MARKER PRATICHE EDILIZIE /////////
///////////////////////////////////////
// Marker Icon
// Shepe = 'circle', 'square', 'star', or 'penta'


var sca = L.icon({
iconUrl: '/var/www/ufficiotecnico/web/img/icons8.png', // posizione
iconSize: [48, 48], // dimensioni
});

// definizione immagine/icona 
 var pin_red = L.icon({
 iconUrl: 'img/pin_red.svg', // posizione
 iconSize: [48, 48], // dimensioni
 });

var scia = L.icon({
iconUrl: 'img/marker1.png', 
iconSize: [48, 48], // dimensioni
});



var ipc = L.ExtraMarkers.icon({
    icon: 'fa-dot-circle-o',
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
    icon: 'fa-thumbs-o-up',
    markerColor: 'green',
    iconRotation:20,
    shape: 'penta',
    prefix: 'fa'
  });
  
var icila = L.ExtraMarkers.icon({
    icon: 'fa-compass',
    markerColor: 'green',
    shape: 'square',
    prefix: 'fa'
  });

var icond = L.ExtraMarkers.icon({
    icon: 'fa-dot-circle-o',
    markerColor: 'cian',
    shape: 'circle',
    prefix: 'fa'
  });


var i;

var jpratiche = " . json_encode($pratiche) . "
    
//   alert(JSON.stringify(jpratiche));
for (i = 0; i < ". ($numero-1) ."; i++) {
    // alert('ID='+jpratiche[i]['id_titolo']);
    
    if (!(jpratiche[i].length && jpratiche[i].val().length === 0 )) {
           // alert('OK length');
            var _date =jpratiche[i]['DataTitolo'];
            var _dateP =jpratiche[i]['DataProtocollo'];
            var dateItems=_date.split('-');
            var dateItemsP=_dateP.split('-');
            var DataTitolo= dateItems[2]+'-'+dateItems[1]+'-'+dateItems[0];
            var DataProtocollo= dateItemsP[2]+'-'+dateItemsP[1]+'-'+dateItemsP[0];
            var Richiedente='';
            if (parseInt(jpratiche[i]['RegimeGiuridico_id'])==1) {
            Richiedente = jpratiche[i]['Cognome']+ ' ' +jpratiche[i]['Nome']
            } else {
            Richiedente = jpratiche[i]['Denominazione']
            };
            //alert('idtitolo='+parseInt(jpratiche[i]['id_titolo']));
        switch(parseInt(jpratiche[i]['id_titolo'])) {
          case 1:
            // CILA
            var msg='<div><p>CILA!'+
            ' <br/>' + Richiedente + 
            ' <br/>' + 'Prot. ' + jpratiche[i]['NumeroProtocollo'] + ' del '+ DataProtocollo +
            ' <br/>' + jpratiche[i]['DescrizioneIntervento'] + '</p></div>';
            markerCILA=L.marker([jpratiche[i]['Latitudine'] ,jpratiche[i]['Longitudine']], {icon: icila}).bindPopup(msg);
            layerCILA.addLayer(markerCILA);
            break;
          case 2:
            // SCIA
            //alert('SCIA');
            var msg='<div><p>SCIA'+
            ' <br/>' + Richiedente + 
            ' <br/>' + 'Prot. ' + jpratiche[i]['NumeroProtocollo'] + ' del '+ DataProtocollo +
            ' <br/>' + jpratiche[i]['DescrizioneIntervento'] + '</p></div>';
            //definizione marker con icona personalizzata
            markerSCIA=L.marker([jpratiche[i]['Latitudine'] ,jpratiche[i]['Longitudine']], {icon: iscia}).bindPopup(msg);
            layerSCIA.addLayer(markerSCIA);
            break;
          case 3:
            // SuperSCIA
            var msg='<div><p>SuperSCIA'+
            ' <br/>' + Richiedente + 
            ' <br/>' + 'Prot. ' + jpratiche[i]['NumeroProtocollo'] + ' del '+ DataProtocollo +
            ' <br/>' + jpratiche[i]['DescrizioneIntervento'] + '</p></div>';
            markerAltro=L.marker([jpratiche[i]['Latitudine'] ,jpratiche[i]['Longitudine']], {icon: iscia}).bindPopup(msg);
            layerAltro.addLayer(markerAltro);
            break;
            
          case 4:
            // PC

            var msg='<div><p>Permesso Costruire ' + jpratiche[i]['NumeroTitolo'] + ' del ' + DataTitolo + 
                    ' <br/>' + Richiedente + 
                    ' <br/>' + jpratiche[i]['DescrizioneIntervento'] + '</p></div>';
            //L.marker([jpratiche[i]['Latitudine'] ,jpratiche[i]['Longitudine']], {icon: ipc}).bindPopup(msg).addTo(map);
            markerPC = L.marker([jpratiche[i]['Latitudine'] ,jpratiche[i]['Longitudine']], {icon: ipc}).bindPopup(msg);
            layerPermessi.addLayer(markerPC);
            break;
          case 5:
            // SCA
            //alert('SCA');
            var msg='<div><p>SCA'+
                    ' <br/>' + Richiedente + 
                    ' <br/>' + 'Prot. ' + jpratiche[i]['NumeroProtocollo'] + ' del '+ DataProtocollo +
                    ' <br/>' + jpratiche[i]['DescrizioneIntervento'] + '</p></div>';
            markerSCA = L.marker([jpratiche[i]['Latitudine'] ,jpratiche[i]['Longitudine']], {icon: isca}).bindPopup(msg);
            layerSCA.addLayer(markerSCA);
            break;
          case 6:
            // CIL
            break;
            var msg='<div><p>CIL!' +
            ' <br/>' + Richiedente + 
            ' <br/>' + 'Prot. ' + jpratiche[i]['NumeroProtocollo'] + ' del '+ DataProtocollo +
            ' <br/>' + jpratiche[i]['DescrizioneIntervento'] + '</p></div>';
            markerCIL = L.marker([jpratiche[i]['Latitudine'] ,jpratiche[i]['Longitudine']], {icon: isca}).bindPopup(msg);
            layerAltro.addLayer(markerCIL);

          case 7:
            // Autorizzazione Sismica
            break;
            var msg='<div><p>Autorizzazione Sismica!<br />This is a nice popup.</p></div>';
          case 8:
            // Condono Legge 47/85
            break;
            var msg='<div><p>Condono Edilizio Legge 47/85!<br />.</p></div>';
            markerCondono= L.marker([jpratiche[i]['Latitudine'] ,jpratiche[i]['Longitudine']], {icon: icond}).bindPopup(msg);
            layerAltro.addLayer(markerCondono);

          case 9:
            // Condono 724/94
            
            var msg='<div><p>Condono Edilizio Legge 724/94!'+
            ' <br/>' + 'Permesso Costruire ' + jpratiche[i]['NumeroTitolo'] + ' del ' + DataTitolo +
            ' <br/>' + Richiedente + 
            ' <br/>' + jpratiche[i]['DescrizioneIntervento'] + '</p></div>';
            markerCN=L.marker([jpratiche[i]['Latitudine'] ,jpratiche[i]['Longitudine']], {icon: icond}).bindPopup(msg);
            layerAltro.addLayer(markerCN);
            break;
          case 10:
            // Autorizzazione Paesaggistica
            break;
            var msg='<div><p>Autorizzazione Paesaggistica</p></div>';
        default:
            // code block
            var msg='<div><p>Altro!<br /></p></div>';
            markerA = L.marker([jpratiche[i]['Latitudine'] ,jpratiche[i]['Longitudine']]).bindPopup(msg);    
            layerAltro.addLayer(markerA);
        } 
            //var msg='<div><p>Hello world!<br />This is a nice popup.</p></div>';
    }        
}
