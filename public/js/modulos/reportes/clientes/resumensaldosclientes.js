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

     $.getJSON('../reportes/gestionclientes/getcliente', function(data) {
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
			console.log(respuesta);
			$("#reporte-txt").attr("value",null);
			$("#reporte-txt").attr("value",respuesta.valor);
    	setFile(respuesta.valor);
        // window.open('data:text/csv;charset=utf-8,' + escape(respuesta.valor));

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
	jsonReporte.GENTILEZA = $("#gentileza-reporte").is(':checked') ? "S" : "N";
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
	$("#mes-reporte").val(0);
}
function setFile( data, fileName, fileType ) {
    // Set objects for file generation.
    var blob, url, a, extension;
    
    // Get time stamp for fileName.
    var stamp = new Date().getTime();
    
    // Set MIME type and encoding.
    fileType = ( fileType || "text/csv;charset=UTF-8" );
    extension = fileType.split( "/" )[1].split( ";" )[0];
    // Set file name.
    var name_reporte = $("#gentileza-reporte").is(':checked') ? "ResumenConsumoGentileza_":"ResumenConsumo_";
    fileName = ( fileName || name_reporte + stamp + "." + extension );
    
    // Set data on blob.
    blob = new Blob( [ data ], { type: fileType } );
    
    // Set view.
    if ( blob ) {
        // Read blob.
        url = window.URL.createObjectURL( blob );
    
        // Create link.
        a = document.createElement( "a" );
        // Set link on DOM.
        document.body.appendChild( a );
        // Set link's visibility.
        a.style = "display: none";
        // Set href on link.
        a.href = url;
        // Set file name on link.
        a.download = fileName;
    
        // Trigger click of link.
        a.click();
    
        // Clear.
        window.URL.revokeObjectURL( url );
    } else {
        // Handle error.
    }
}