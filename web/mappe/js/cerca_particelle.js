// Ricerca Particelle Catastali
jQuery(function($) {
    $('._cercap').on('click', function(event){
    
            event.preventDefault(); // stopping submitting
//            var data = {};
//            data.foglio = $('#ifoglio').val();
//            data.particella = $('#iparticella').val();
            //var foglio = 'foglio ='+$('#particella-foglio').val();
            
            //alert(foglio);
//            var success = function(data){
//                console.log('Success!', data);
//                alert('OK');
//            }
//            var error = function(data){
//                console.log('Error!', data);
//                alert('Error');
//            }
            
            //alert('path ='+MAP_PATH);
            $.ajax({
                //url:'cercaparticella.php',
                url: 'index.php?r=mappe/cercaparticella',
                type:'GET',
                dataType:'json',
                data:{
                    foglio:$('#particella-foglio').val(),
                    particella:$('#particella-particella').val()
                    }
                }).done(function(result) {
                    //alert(result);
                   // var obj = JSON.parse(result);
//                    var json = JSON.stringify(obj);
//                    alert(json);
//                    alert(obj);
                    //map.setView(new L.LatLng(lat, lng), 12);
                    //var dt=JSON.parse(result);
                    //alert(result);
                    //var value=result.coords[1];
                    
                    //alert(value);
                    //map.setView(dt, 25);
                    
                    // controllo se è stata trovata la particella
                    //const res = JSON.parse(result);
                    //alert(JSON.stringify(result));
                    if (result.ok ===1) {
                        // particella non trovata
                        //document.getElementById('_messaggio').innerHTML = 'particella non trovata'; 
                        alert('mi dispiace, ma non ho trovato la particella che hai specificato!');
                    } else {
                        // particella trovata
                        // OK con centroid
                        //alert(result.errmsg);
                        //map.setView(new L.LatLng(obj.coords[1], obj.coords[0]), 20);
                        map.setView(new L.LatLng(result.Latitudine, result.Longitudine), 20);
                        //document.getElementById('_messaggio').innerHTML = ''; 
                    }
                    
                    // Particella
                    //map.setView(new L.LatLng(result.coords[1], result.coords[0]), 20);



                });
});
});