var pathname = window.location.pathname;
var table = pathname;
$().ready(function() {
 
     $("#imprimir-reporte").click(function() {
            
            imprimirReportePDF();
            // imprimirReporteTXT();   
    });
     $("#fechadesde-reporte").datepicker();
    $("#fechadesde-reporte").datepicker("option", "dateFormat", "yy-mm-dd");
    $("#fechadesde-reporte").datepicker("setDate", new Date());    
    $("#fechahasta-reporte").datepicker();
    $("#fechahasta-reporte").datepicker("option", "dateFormat", "yy-mm-dd");
    $("#fechahasta-reporte").datepicker("setDate", new Date());
    $("#reporte-txt").hide();
    var textoBase = "Total de Gestiones\n";
    textoBase = textoBase+"Saldo a favor del mes de\n";
    textoBase = textoBase+"Saldo a favor del mes de \n";
    textoBase = textoBase+"Suscripcion del mes de \n";
    textoBase = textoBase+"Total de Gestiones\n";
    textoBase = textoBase+"Saldo a favor del mes de\n";
    textoBase = textoBase+"Gentileza\n";
    textoBase = textoBase+"Adicional\n";
    textoBase = textoBase+"Total a abonar\n";
    $("#reporte-txt").attr("value",textoBase);


    $.getJSON(table+'/getcliente', function(data) {
        var nombreCliente = [];

        $(data).each(function(key, value) {
            nombreCliente.push(value.DESCRIPCION_PERSONA);
        });

        $("#cliente-reporte").autocomplete({
            source: nombreCliente
        });
       
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

function imprimirReportePDF(){   
    var dataString1 = obtenerJsonReporte();
	if(dataString1){
		var dataString = JSON.stringify(dataString1);
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
	               	
}
// function imprimirReporteTXT(){           
// 	var dataString = JSON.stringify(obtenerJsonReporte());
// 	$.ajax({
// 		url: table+'/imprimirreporte',
// 		type: 'post',
// 		data: {"parametros":dataString},
// 		dataType: 'json',
// 		async: false,
// 		success: function(respuesta) {
// 			if (respuesta.success) {
//                 window.open('../reportes_pdf/clientes/'+respuesta.archivo);
//  				$("#modalReportes").hide();
//                 limpiarReporte();
//             }else{
//             	mostarVentana("warning", "Ocurrio un error en la generacion del reporte");
// 			}                                            
// 			$.unblockUI();
// 		},
// 		error: function(event, request, settings) {
// 			$.unblockUI();
// 			mostarVentana("warning", "Ocurrio un error en la generacion del reporte");
// 		}        
// 	});                  	
// }

function obtenerJsonReporte(){
	var jsonReporte = new Object();	
	jsonReporte.CLIENTE = $("#cliente-reporte").val();
	jsonReporte.FECHA_DESDE = $("#fechadesde-reporte").val();
	jsonReporte.FECHA_HASTA = $("#fechahasta-reporte").val();
	jsonReporte.RESUMEN = $("#resumen-reporte").is(':checked') ? "S" : "N";	
	jsonReporte.RESUMENTXT = $("#reporte-txt").val();
	if(jsonReporte.CLIENTE.length < 1 && jsonReporte.RESUMEN == "S"){
		alert("Seleccione un cliente");
		return false;
	}else{
		return jsonReporte;	
	}
	
}	

function limpiarReporte(){
	$("#cliente-reporte").val(null);
	$("#fechadesde-reporte").val(null);
	$("#fechahasta-reporte").val(null);
}

function muestraText(){
var muestra = $("#resumen-reporte").is(':checked') ? true : false;
$('#reporte-txt').toggle(muestra);  
}