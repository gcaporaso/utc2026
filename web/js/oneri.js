/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


//$('#myModal').on('show.bs.modal', function (event) {
//        alert('OK');
//    var button = $(event.relatedTarget); // Button that triggered the modal
//    var recipient = button.data('whatever'); // Extract info from data-* attributes
//    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
//    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
//    var modal = $(this);
//    $('#efrom').val('ing@comune.campolidelmontetaburno.bn.it');
//  modal.find('.modal-title').text('Email a ');
//  modal.find('.modal-body input').val('ing@comune.campolidelmontetaburno.bn.it');
//        $('#efrom').focus();
//});        
        

    
$('#modalButton').on('click',function (event) {
//alert('OK');
event.preventDefault();
    $.ajax({
        url : '/index.php?r=edilizia/oneriajax',
        type:"post",
        data: { 'id': $idpratica,
               _csrf : "' . Yii::$app->request->getCsrfToken() . '"
               },
        dataType:"json",
        success : function(data) {      
                    $('#efrom').val('ing@comune.campolidelmontetaburno.bn.it');
                    $("#eto").val(data.eto);
                    $("#esubject").val(data.subject);
                    $("#ebody").val(data.ebody);
                },
        error : function(xhr, status, error){
                    var errorMessage = xhr.status + " : " + xhr.statusText
                    alert("Error - " + errorMessage);
                }
     });
        
 });