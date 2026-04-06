// scheda urbanistica
$('._surb').on('click', function(event){
    //$(form).attr('action', '/index.php?r=mappe/schedaurbanistica')
    //event.preventDefault();
    var foglio= $('#particella-foglio').val();
    var particella = $('#particella-particella').val();
    if ((foglio=='') && (particella=='')) {
    alert('devi specificare foglio e particella');
    brack;
    }
    if (foglio=='') {
    alert('devi specificare il foglio');
    brack;
    }
    if (particella=='') {
    alert('devi specificare la particella');
    brack;
    }
    var url = 'index.php?r=mappe/schedaurbanistica'
    url += '&foglio='+foglio;
    url += '&particella='+particella;
    //alert(url);
    $('#urb-form').attr('action', url);
    //alert($('#urb-form').attr('action'));
    //$('#urb-form').submit;
    $('#urb-form').yiiActiveForm('validate', true);
    $('#urb-form').yiiActiveForm('submitForm');
});