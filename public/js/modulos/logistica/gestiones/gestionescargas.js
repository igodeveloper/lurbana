$().ready(function() {
    // alert(table);
   var pathname = window.location.pathname;
    var table = pathname;
    $("body").css("overflow", "hidden");
    $.get( table+'/buscar', function( data ) {
      console.log(data);
    });
    
});
