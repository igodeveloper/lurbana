var pathname = window.location.pathname;
var table = pathname;
$().ready(function() {

	// programamos los botones
 	
     $("#imprimir-reporte").click(function() {
            imprimirReporte();
           
    });
    $("#fechadesde-reporte").datepicker();
    $("#fechadesde-reporte").datepicker("option", "dateFormat", "yy-mm-dd");
    $("#fechadesde-reporte").datepicker("setDate", new Date());    
    $("#fechahasta-reporte").datepicker();
    $("#fechahasta-reporte").datepicker("option", "dateFormat", "yy-mm-dd");
    $("#fechahasta-reporte").datepicker("setDate", new Date());
    $("#ui-datepicker-div").css('display','none');

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
                window.open('../../reportes_pdf/asistentes/'+respuesta.archivo);
 				$("#modalReportes").hide();
                limpiarReporte();
            }else{
            	alet("Ocurrio un error en la generacion del reporte");
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
	jsonReporte.GESTOR = $("#asistente-reporte").val();
	jsonReporte.FECHA_DESDE = $("#fechadesde-reporte").val();
	jsonReporte.FECHA_HASTA = $("#fechahasta-reporte").val();	
	return jsonReporte;
}	

function limpiarReporte(){
	$("#asistente-reporte").val(null);
	$("#fechadesde-reporte").val(null);
	$("#fechahasta-reporte").val(null);
}
