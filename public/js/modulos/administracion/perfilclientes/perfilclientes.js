$().ready(function() {

	// programamos los botones
 	$("#muestramodal").click(function() {
 		limpiarFormulario()
            $("#modalNuevo").show();
            cargarAsistenteServicios();
           
    });
    $("#reporte").click(function() {
            $("#modalReportes").show();
           
    }); 
     $("#close-modal").click(function() {
            $("#modalNuevo").hide();
           
    }); 
     $("#cancelar-modal").click(function() {
            $("#modalNuevo").hide();

           
    }); 
     $("#close-reporte").click(function() {
            $("#modalReportes").hide();
           limpiarReporte();
    }); 
     $("#cancelar-reporte").click(function() {
            $("#modalReportes").hide();
           limpiarReporte();
    }); 
     $("#imprimir-reporte").click(function() {
            imprimirReporte();
           
    });


	
	$('#guardar-modal').click(function() {
		 var data = obtenerJsonModal();
		if(data != null){
			enviarParametros(data);
		}
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

function addrequiredattr(id,focus){
	// $('#'+id).attr("has-warning", "required");
	// $('#'+id).addClass( "has-warning" );
	$('#'+id).parent().addClass('has-warning');
	if(focus == 1)
		$('#'+id).focus();
}

function obtenerJsonModal() {
	var jsonObject = new Object();

	var mensaje = 'Ingrese los campos: ';
    var focus = 0;

	if($('#descripcionpersona-modal').attr("value") == null || $('#descripcionpersona-modal').attr("value").length == 0){
        mensaje+= ' | Descripci\u00F3n ';
    	focus++;
    	addrequiredattr('descripcionpersona-modal',focus); 
	}
	if($('#numerodocumentopersona-modal').attr("value") == null || $('#numerodocumentopersona-modal').attr("value").length == 0){
        mensaje+= ' | Documento ';
    	focus++;
    	addrequiredattr('numerodocumentopersona-modal',focus); 
	}
	if($('#rucpersona-modal').attr("value") == null || $('#rucpersona-modal').attr("value").length == 0){
        mensaje+= ' | RUC ';
    	focus++;
    	addrequiredattr('rucpersona-modal',focus); 
	}
	if($('#direccionpersona-modal').attr("value") == null || $('#direccionpersona-modal').attr("value").length == 0){
        mensaje+= ' | Direcci\u00F3n ';
    	focus++;
    	addrequiredattr('direccionpersona-modal',focus); 
	}
	if($('#telefonopersona-modal').attr("value") == null || $('#telefonopersona-modal').attr("value").length == 0){
        mensaje+= ' | Telefono ';
    	focus++;
    	addrequiredattr('telefonopersona-modal',focus); 
	}
	// if($('#emailpersona-modal').attr("value") == null || $('#emailpersona-modal').attr("value").length == 0){
	if($("#emailpersona-modal").val().indexOf('@', 0) == -1 || $("#emailpersona-modal").val().indexOf('.', 0) == -1){
        mensaje+= ' | Email incorrecto';
    	focus++;
    	addrequiredattr('emailpersona-modal',focus); 
	}
	
    if($("#ciudadpersona-modal" ).val() == -1){
        mensaje+= ' | Ciudad ';
    	focus++;
    	addrequiredattr('ciudadpersona-modal',focus);    
	}
	    if($("#barriopersona-modal" ).val() == -1){
        mensaje+= ' | Barrio ';
    	focus++;
    	addrequiredattr('barriopersona-modal',focus);    
	}


	if (mensaje != 'Ingrese los campos: '){
		mensaje+= ' |';
		mostarVentana("warning-modal", mensaje);
		return null;
	}else {
		jsonObject.CODIGO_CLIENTE = $('#codigocliente-modal').attr("value");
		jsonObject.CODIGO_PERSONA = $('#codigopersona-modal').attr("value");
		jsonObject.DESCRIPCION_PERSONA = $('#descripcionpersona-modal').attr("value");
		jsonObject.NRO_DOCUMENTO_PERSONA = $('#numerodocumentopersona-modal').attr("value");
		jsonObject.RUC_PERSONA = $("#rucpersona-modal").val();
		jsonObject.TELEFONO_PERSONA = $("#telefonopersona-modal").val();
		jsonObject.CELULAR_PERSONA = $("#celularpersona-modal").val();
		jsonObject.EMAIL_PERSONA = $("#emailpersona-modal").val();
		jsonObject.DIRECCION_PERSONA = $("#direccionpersona-modal").val();
		jsonObject.REFERENCIA_PERSONA = $("#referenciapersona-modal").val();
		jsonObject.CODIGO_CIUDAD = $("#ciudadpersona-modal").val();
		jsonObject.CODIGO_BARRIO = $("#barriopersona-modal").val();
		jsonObject.ESTADO_CLIENTE = $("#estadocliente-modal").val();
		jsonObject.ENVIAR_EMAIL = $("#enviaremail-modal").val();
		if($("#asistenteservicios-modal").val() != -1){jsonObject.CODIGO_GESTOR = $("#asistenteservicios-modal").val() } else {jsonObject.CODIGO_GESTOR = 0};
		return jsonObject;
	}
}
function enviarParametros(data){
	$.blockUI({
        message: "Aguarde un momento por favor"
    });

	var urlenvio = '';
	if(data.CODIGO_CLIENTE !== null && data.CODIGO_CLIENTE.length !== 0){
		urlenvio = table+'/modificar';
	}else {
		urlenvio = table+'/guardar';
	}
	var dataString = JSON.stringify(data);

	$.ajax({
        url: urlenvio,
        type: 'post',
        data: {"parametros":dataString},
        dataType: 'json',
        async : true,
        success: function(respuesta){
        	 if(respuesta.success){
               mostarVentana("success-modal","Se ingreso el registro con exito");
               limpiarFormulario();
                buscar();                               
            }else{
                mostarVentana("warning-modal","Verifique sus datos, ocurrio un error");  
            }            
               $.unblockUI();
        },
        error: function(event, request, settings){
//        	mostarVentana("error-registro-listado","Ha ocurrido un error");
    		$.unblockUI();
        }
    });
}


function limpiarFiltos(){
	$("#descripcionpersona-filtro").attr("value",null);
	$("#numerodocumentopersona-filtro").attr("value",null);
	$("#estado-filtro").attr("value",null);
	$("#telefonopersona-filtro").attr("value",null);
}

function limpiarFormulario(){
	$("#codigopersona-modal").attr("value",null);
	$("#codigocliente-modal").attr("value",null);
	$("#descripcionpersona-modal").attr("value",null);
	$("#numerodocumentopersona-modal").attr("value",null);
	$("#rucpersona-modal").attr("value",null);
	$("#direccionpersona-modal").attr("value",null);
	$("#referenciapersona-modal").attr("value",null);
	$("#telefonopersona-modal").attr("value",null);
	$("#celularpersona-modal").attr("value",null);
	$("#emailpersona-modal").attr("value",null);
	$("#ciudadpersona-modal").attr("value",null);
	$("#barriopersona-modal").attr("value",null);
	$("#estadocliente-modal").attr("value",null);
	$("#asistenteservicios-modal").select2("val",null);
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
function cargarAsistenteServicios(){
	
//	alert('Tipo Producto');
	$.ajax({
        url: table+'/getasistenteservicios',
        type: 'post',
        dataType: 'html',
        async : false,
        success: function(respuesta){
        	if(respuesta== 'error'){
        		// mostarVentana("error-title",mostrarError("OcurrioError"));
        	}else{
            	$("#asistenteservicios-modal").html(respuesta);       		
        	}
        },
        error: function(event, request, settings){
         //   $.unblockUI();
        	 alert(mostrarError("OcurrioError"));
        }
    });	
}