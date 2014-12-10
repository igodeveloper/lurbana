var pathname = window.location.pathname;
var table = pathname;
$().ready(function() {
 	limpiarReporte();

     $("#imprimir-reporte").click(function() {
            
            // imprimirReportePDF();
            imprimirReporteTXT();
           
    });
     $("#mes-reporte").datepicker();
    $("#mes-reporte").datepicker("option", "dateFormat", "mm");
    $("#mes-reporte").datepicker("setDate", new Date());    
    $("#ano-reporte").datepicker();
    $("#ano-reporte").datepicker("option", "dateFormat", "yy");
    $("#ano-reporte").datepicker("setDate", new Date());

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

function imprimirReportePDF(){           
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
function imprimirReporteTXT(){           
	var dataString = JSON.stringify(obtenerJsonReporte());
	$.ajax({
		url: table+'/imprimirreporte',
		type: 'post',
		data: {"parametros":dataString},
		dataType: 'json',
		async: false,
		success: function(respuesta) {
			$("#reporte-txt").attr("value",null);
			$("#reporte-txt").attr("value",respuesta.valor);
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
	jsonReporte.MES = $("#mes-reporte").val();
	jsonReporte.ANO = $("#ano-reporte").val();	
	return jsonReporte;
}	

function limpiarReporte(){
	$("#cliente-reporte").val(null);
	// $("#mes-reporte").val(null);
	// $("#ano-reporte").val(null);
	$("#reporte-txt").attr("value",null);
	var anho = (new Date).getFullYear();
	$("#ano-reporte").append(new Option(anho, anho));
	$("#ano-reporte").append(new Option(anho-1, anho-1));
	$("#mes-reporte").val(0);;
}