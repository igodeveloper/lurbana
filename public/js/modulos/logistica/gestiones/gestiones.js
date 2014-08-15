$().ready(function() {

 	$("#muestramodal").click(function() {
 		limpiarFormulario();
 		cargarCliente();
 		cargarAsistenteServicios();
			$("#fechagestion-modal").datepicker();
		    $("#fechagestion-modal").datepicker("option", "dateFormat", "yy-mm-dd");
		    $("#fechagestion-modal").datepicker("setDate", new Date());
		    $("#iniciogestion-modal").datepicker();
		    $("#iniciogestion-modal").datepicker("option", "dateFormat", "yy-mm-dd");
		    // $("#iniciogestion-modal").datepicker("setDate", null);
		    $("#fingestion-modal").datepicker();
		    $("#fingestion-modal").datepicker("option", "dateFormat", "yy-mm-dd");
		    // $("#fingestion-modal").datepicker("setDate", null);
            $("#modalNuevo").show();
            $("#ui-datepicker-div").css('display','none');
           
    }); 
     $("#close-modal").click(function() {
            $("#modalNuevo").hide();
           
    }); 
     $("#cancelar-modal").click(function() {
            $("#modalNuevo").hide();
           
    });
	
	$('#guardar-modal').click(function() {
		 var data = obtenerJsonModal();
		if(data != null){
			enviarParametros(data);
		}
	 });

	$("#tiempoestimado-modal").blur(function() {
		var tiempo = $("#tiempoestimado-modal").val();
		var cantidad_gestion = parseFloat(tiempo/40);
		cantidad_gestion = roundnumber(cantidad_gestion);
		$("#cantidadgestion-modal").attr("value", cantidad_gestion);
	});

});
function roundnumber(number){
	var number_int = parseInt(number);
	var number_float = number-number_int;
	var number_round = 0;
	if(number_float>0 && number_float<=0.5){
		number_round = parseFloat(number_int+0.5);
	}else if(number_float<1 && number_float>0.5){
		number_round = parseFloat(number_int+1);
	} else {
		number_round = number
	}
	return number_round;

}
function mostarVentana(box,mensaje){
	if(box == "warning-modal") {
		$("#warning-message-modal").text(mensaje);
		$("#warning-modal").show();
		setTimeout("ocultarWarningModal()",1000);
	} else if(box == "success-modal") {
		$("#success-message-modal").text(mensaje);
		$("#success-modal").show();
		setTimeout("ocultarSuccessmodal()",1000);
	} 
}

function ocultarWarningModal(){
	$("#warning-modal").hide(500);

}function ocultarSuccessmodal(){
	$("#success-modal").hide(500);
}

function addrequiredattr(id,focus){
	$('#'+id).attr("required", "required");
	if(focus == 1)
		$('#'+id).focus();
}

function obtenerJsonModal() {
	var jsonObject = new Object();

	var mensaje = 'Ingrese los campos: ';
    var focus = 0;

	if($('#cliente-modal').val() == -1){
        mensaje+= ' | Cliente ';
    	focus++;
    	addrequiredattr('codigocliente-modal',focus); 
	}
	if($('#fechagestion-modal').attr("value") == null || $('#fechagestion-modal').attr("value").length == 0){
        mensaje+= ' | Fecha ';
    	focus++;
    	addrequiredattr('fechagestion-modal',focus); 
	}
	// if($('#iniciogestion-modal').attr("value") == null || $('#iniciogestion-modal').attr("value").length == 0){
 //        mensaje+= ' | Fecha inicio ';
 //    	focus++;
 //    	addrequiredattr('iniciogestion-modal',focus); 
	// }
	// if($('#fingestion-modal').attr("value") == null || $('#fingestion-modal').attr("value").length == 0){
 //        mensaje+= ' | Fecha fin ';
 //    	focus++;
 //    	addrequiredattr('fingestion-modal',focus); 
	// }
	if($('#tarea-modal').attr("value") == null || $('#tarea-modal').attr("value").length == 0){
        mensaje+= ' | Tarea ';
    	focus++;
    	addrequiredattr('tarea-modal',focus); 
	}
	if($('#tiempoestimado-modal').attr("value") == null || $('#tiempoestimado-modal').attr("value").length == 0){
        mensaje+= ' | Tiempo estimado ';
    	focus++;
    	addrequiredattr('tiempoestimado-modal',focus); 
	}
	if($('#cantidadgestion-modal').attr("value") == null || $('#cantidadgestion-modal').attr("value").length == 0){
        mensaje+= ' | Cantidad gestión ';
    	focus++;
    	addrequiredattr('cantidadgestion-modal',focus); 
	}
	if($('#estado-modal').attr("value") == -1){
        mensaje+= ' | Estado ';
    	focus++;
    	addrequiredattr('estado-modal',focus); 
	}
	if (mensaje != 'Ingrese los campos: '){
		mensaje+= ' |';
		mostarVentana("warning-modal", mensaje);
		return null;
	}else {
		jsonObject.NUMERO_GESTION = $('#codigogestion-modal').attr("value");
		jsonObject.CODIGO_CLIENTE = $('#cliente-modal').val();
		jsonObject.FECHA_GESTION = $('#fechagestion-modal').attr("value");
		jsonObject.OBSERVACION = $('#tarea-modal').attr("value");
		jsonObject.FECHA_INICIO = $("#iniciogestion-modal").val();
		jsonObject.FECHA_FIN = $("#fingestion-modal").val();
		jsonObject.CANTIDAD_MINUTOS = $("#tiempoestimado-modal").val();
		jsonObject.CANTIDAD_GESTIONES = $("#cantidadgestion-modal").val();
		if($("#asistenteservicios-modal").val() != -1){jsonObject.CODIGO_GESTOR = $("#asistenteservicios-modal").val() } else {jsonObject.CODIGO_GESTOR = 0};
		jsonObject.ESTADO = $("#estado-modal").val();
		return jsonObject
	}
}
function enviarParametros(data){
	$.blockUI({
        message: "Aguarde un momento por favor"
    });

	var urlenvio = '';
	if(data.NUMERO_GESTION !== null && data.NUMERO_GESTION.length !== 0){
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
	$("#estado-filtro").val(null);
	$("#telefonopersona-filtro").attr("value",null);
	}

function limpiarFormulario(){

	
 $('#codigogestion-modal').attr("value",null);
 // $('#codigocliente-modal').attr("value",null);
 $('#cliente-modal').val(null);
 $('#fechagestion-modal').attr("value",null);
 $('#tarea-modal').attr("value",null);
 $("#iniciogestion-modal").attr("value",null);
 $("#fingestion-modal").attr("value",null);
 $("#tiempoestimado-modal").val(null);
 $("#cantidadgestion-modal").val(null);
 $("#asistenteservicios-modal").val(null);
 $("#estado-modal").val(null);

}
function cargarCliente(){
	
//	alert('Tipo Producto');
	$.ajax({
        url: table+'/getcliente',
        type: 'post',
        dataType: 'html',
        async : false,
        success: function(respuesta){
        	if(respuesta== 'error'){
        		// mostarVentana("error-title",mostrarError("OcurrioError"));
        	}else{
            	$("#cliente-modal").html(respuesta);       		
        	}
        },
        error: function(event, request, settings){
         //   $.unblockUI();
        	 alert(mostrarError("OcurrioError"));
        }
    });	
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

