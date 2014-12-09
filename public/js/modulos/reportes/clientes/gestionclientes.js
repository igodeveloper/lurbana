var pathname = window.location.pathname;
var table = pathname;
$().ready(function() {
 
     $("#imprimir-reporte").click(function() {
            imprimirReporte();
           
    });

});

function mostarVentana(box,mensaje){
	if(box == "warning-modal") {
		$("#warning-message-modal").text(mensaje);
		$("#warning-modal").show();
		setTimeout("ocultarWarningModal()",5000);
	} else if(box == "success-modal") {
		$("#success-message-modal").text(mensaje);
		$("#success-modal").show();
		setTimeout("ocultarSuccessmodal()",5000);
	} 
}

function ocultarWarningModal(){
	$("#warning-modal").hide(500);

}function ocultarSuccessmodal(){
	$("#success-modal").hide(500);
}

function imprimirReporte(){           
	var dataString = JSON.stringify(obtenerJsonReporte());      
	$.ajax({
		url: table+'/imprimirreporte',
		type: 'post',
		data: {"parametros":dataString},
		dataType: 'json',
		async: false,
		success: function(respuesta) {
			if (respuesta.success) {
                window.open('../reportes_pdf/clientes/'+respuesta.archivo);
 				$("#modalReportes").hide();
                limpiarReporte();
            }else{
            	mostarVentana("warning", "Ocurrio un error en la generacion del reporte");
			}                                            
			$.unblockUI();
		},
		error: function(event, request, settings) {
			$.unblockUI();
			mostarVentana("warning", "Ocurrio un error en la generacion del reporte");
		}        
	});                  	
}
function obtenerJsonReporte(){
	var jsonReporte = new Object();	
	jsonReporte.CLIENTE = $("#cliente-reporte").val();
	jsonReporte.FECHA_DESDE = $("#fechadesde-reporte").val();
	jsonReporte.FECHA_HASTA = $("#fechahasta-reporte").val();	
	return jsonReporte;
}	

function limpiarReporte(){
	$("#cliente-reporte").val(null);
	$("#fechadesde-reporte").val(null);
	$("#fechahasta-reporte").val(null);
}