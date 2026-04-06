/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$( document ).ready(function() {
        alert("JQuery Attiva!");
        // Bind to the submit event of our form
        $("#numpermesso").click(function(){
               // event.preventDefault();
               alert("Click Event!");
                $.ajax({
                    type: "POST",
                    url:' .Yii::app()->createAbsoluteUrl("edilizia/emetti"). ',
                    cache: false,
                    data: {_csrf : ' . Yii::$app->request->getCsrfToken() . '}, 
                    success: function(result) {
                        $("#edilizia-numerotitolo").val(result.numero);
                        //$("#edilizia-datatitolo-disp").val()=new Date();
                        //$.pjax.reload({container: "#construccion-evaluacion"});
                        
                          alert("Nuovo numero è: " + data);
                          
                    }, 
                    error: function(result) {
                        //console.log(\"server error\");
                        alert("Errore!"+result);
                    }
                });
                
            }
       );
       
       
       
       
      
    
    

 
 

       
       
       
       
       
       
       
       
       
       
});