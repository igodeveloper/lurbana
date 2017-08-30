$().ready(function() {
	contador();
	setInterval('contador()',60000);
});

function contador(){
	$.getJSON( "/logistica/gestiones/getnotificaciones")
  		.done(function( data ) {
  	$("#pedidos").text(data.PENDIENTES);
  });
 }