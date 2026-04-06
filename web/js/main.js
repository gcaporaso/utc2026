/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// listen click, open modal and .load content
/*
 * Quando viene cliccato il pulsante "modalButton"
 * mostra il "modal" con il contenuto "modalContent"
 * preso dal rendering di modal.value
 */
$(document).ready(function(){
//$('#modalButton').click(function (){
$('a#modalButton').click(function (){
    //alert('ale! finalmente')
    event.preventDefault();
    $('#modal').modal('show')
        .find('#modalContent')
        //.html(data);
        //.load($(this).attr('value'));
        .load($(this).attr('href'));
// alert('presenze=>' + '" . $presenti . "');
    
});

});