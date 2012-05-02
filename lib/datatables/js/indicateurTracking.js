$(document).ready(function() {
   var reg = new RegExp("\n","g");
   $('textarea').keyup(function(event) {
        var str = $(this).val();
        var nb = 0;
        var array = str.match(reg);
        if(array!=null)
             nb = array.length;
        
        $('span.cpt').text(nb);
   
    });
});