<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
?>

<!DOCTYPE html>
<html>
<head>
  <title>Random AJAX Welcome</title>

  <script>
function getWelcome(){
var MyUrl = "https://wms.cartografia.agenziaentrate.gov.it/inspire/ajax/ajax.php?op=getGeomPart&prov=BN&cod_com=B542&foglio=4&num_part=1005"; 
var proxy = 'https://cors-anywhere.herokuapp.com/';

 var gFIurl = 'https://wms.cartografia.agenziaentrate.gov.it/inspire/ajax/ajax.php?op=getGeomPart&prov=BN&cod_com=B542&foglio=4&num_part=1005';

 //alert(gFIurl);
 if (gFIurl) {
	var xhttp;
        //istanza di una richiesta XHTTP
	xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
	if (this.readyState == 4 && this.status == 200) {
        //alert(gFIurl);
        var obj = JSON.parse(xhttp.responseText);

        alert(obj);
//	   popup
//           .setLatLng(coord)
//           .setContent(xhttp.responseText)
//           .openOn(map);
	}
  };
  //bypass CORS policy
  //xhttp.open('GET', 'https://cors-anywhere.herokuapp.com/' + gFIurl, true);
  xhttp.open('GET', gFIurl, true);
  xhttp.send();
 }






//
//$.ajax({
//            type: "GET",
//            url: MyUrl,
//            data:"op=getGeomPart&prov=BN&cod_com=B542&foglio=4&num_part=1005",
//            dataType:"json",
////            cors: true ,
////            headers: {'Access-Control-Allow-Origin': '*'},
//            success: function(data) {
//                alert("Data from Server"+JSON.stringify(data));
////            var article = $(response).find('article').first().html();
////            $('#welcome div').html(article);
//          })
////            .done(function( data ) {
////                    $("#IdDivLoading").html("");
////                    if(data)
////                    {
////                      console.log(data);
////                      var MyGeometria=data;
////                      data = JSON.parse(data);
////                      if (MyGeometria!="")
////                      {     
////                        //var messagesArray = JSON.parse(ajaxRequest.responseText);
////                            //get random object from array
////                        var randomIndex = Math.floor(Math.random()*data.length);
////                        var messageObj = data[randomIndex];
////
////                        //use that object to set content and color
////                        var welcomeDiv = document.getElementById("welcome");
////                        welcomeDiv.innerHTML = messageObj.text;
////                        welcomeDiv.style.color = messageObj.color;	
////                      }
////                    }
////            })
//            .fail(function( ) {
//                alert("fallito!");
//          }
//          });
  }







//var request = new XMLHttpRequest();
//    request.onreadystatechange = function() {
//        // Check if the request is compete and was successful
//        if (this.readyState === 4 && this.status === 200) {
//            // Inserting the response from server into an HTML element
//        //    document.getElementById("result").innerHTML = this.responseText;
//            // console.log("Stato cambiato!");
//            var messagesArray = JSON.parse(ajaxRequest.responseText);
//
//            //get random object from array
//            var randomIndex = Math.floor(Math.random()*messagesArray.length);
//            var messageObj = messagesArray[randomIndex];
//
//            //use that object to set content and color
//            var welcomeDiv = document.getElementById("welcome");
//            welcomeDiv.innerHTML = messageObj.text;
//            welcomeDiv.style.color = messageObj.color;	
//        };
//    };
//
// // Sending the request to the server
//request.open("GET", MyUrl);
//request.send();
//};
</script>	
</head>
<body onload="getWelcome()">
  <div id="welcome"></div>
  <p>This is an example website.</p>
</body>
</html>