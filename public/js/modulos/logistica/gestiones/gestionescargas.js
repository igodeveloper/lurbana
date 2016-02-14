$().ready(function() {
    // alert(table);
    getZonas();
    $("#form").hide();
    $("#grid").show();
    //$("body").css("overflow", "hidden");
    
 	$("#muestramodal").click(function() {
            cargarModalNuevo();
            // $("#asistenteservicios-modal").val(3);
            // console.log($("#asistenteservicios-modal").val());
           
    });

     $("#close-modal").click(function() {
            $("#modalNuevo").hide();
           
    }); 
     $("#cancelar-modal").click(function() {
            $("#modalNuevo").hide();
           
    });
	
	$('#add-actividad').click(function() {
        guardarActividad();
     });

	$('#mostrar-modal-actividad').click(function() {
       mostrarFormularioActividad();
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
        if(tiempo > 41){
        cantidad_gestion = roundnumber(cantidad_gestion);
        }else{
            cantidad_gestion = 1;
        }
		
		$("#cantidadgestion-modal").attr("value", cantidad_gestion);
	});

    $("#cliente-modal").change(function() {
        getClienteSuscripcion();
        getClienteSaldo();
    });

     $("#close-modal-suscripcion").click(function() {
            $("#modalNuevo-suscripcion").hide();
           
    }); 
     $("#cancelar-modal-suscripcion").click(function() {
            $("#modalNuevo-suscripcion").hide();
           
    });
     $("#close-modal-track").click(function() {
            $("#modalNuevo-track").hide();
           
    }); 
     $("#cancelar-modal-track").click(function() {
            $("#modalNuevo-track").hide();
           
    });
    
    $('#guardar-modal-suscripcion').click(function() {
         var data = obtenerJsonModalsuscripcion();
        if(data != null){
            enviarParametrossuscripcion(data);
        }
     });

    $("#tiempoestimado-modal-suscripcion").blur(function() {
        var tiempo = $("#tiempoestimado-modal-suscripcion").val();
        var cantidad_gestion = parseFloat(tiempo/40);
        cantidad_gestion = roundnumber(cantidad_gestion);
        $("#cantidadgestion-modal-suscripcion").attr("value", cantidad_gestion);
    });




    // $('#cantidadgestion-modal').live('keyup', function(){
    //     $(this).val(format.call($(this).val().split(' ').join(''),' ','.'));
    // });

});
function mostrarFormularioActividad(){
    $("#form").show();
    $("#grid").hide();
    $("#mostrar-modal-actividad").hide();
}
function cargarModalNuevo(block){
       $("#planactivo-modal").children("li").remove();
        limpiarFormulario();
        cargarCliente();
        cargarAsistenteServicios();
        // cargarPlanesActivos();
        bloqueardatos(false);
        
        $("#fechagestion-modal").datepicker();
        $("#fechagestion-modal").datepicker("option", "dateFormat", "yy-mm-dd");
        $("#fechagestion-modal").datepicker("setDate", new Date());
        $("#iniciogestion-modal").datepicker();
        $("#iniciogestion-modal").datepicker("option", "dateFormat", "yy-mm-dd");
        $("#iniciogestion-modal").datepicker("setDate", new Date());
        $("#fingestion-modal").datepicker();
        $("#fingestion-modal").datepicker("option", "dateFormat", "yy-mm-dd");
        $("#fingestion-modal").datepicker("setDate", new Date());
        $("#tiempoestimado-modal").attr("value",40);
        $("#cantidadgestion-modal").attr("value",1);
        $("#estado-modal").val('P');
        $("#gentileza-modal").prop('checked', false);
        
        $("#saldogestion-modal").attr("disabled",true);
        $("#codigogestion-modal").attr("disabled",true);
        $("#tipocliente-modal").attr("disabled",true);
        $("#guardar-modal").attr("disabled",false);
        $("#modalNuevo").show();
        $("#ui-datepicker-div").css('display','none');
}

function bloqueardatos(block){
        $("#codigogestion-modal").attr("disabled",block);
        $("#saldogestion-modal").attr("disabled",block);
        // $("#planactivo-modal").attr("disabled",block);
        $("#cliente-modal").attr("disabled",block);
        $("#fechagestion-modal").attr("disabled",block);
        $("#enviaremail-modal").prop('checked', !block);
        $("#gentileza-modal").prop('checked', !block);
}
function format(comma, period) {
  comma = comma || ',';
  period = period || '.';
  var split = this.toString().split('.');
  var numeric = split[0];
  var decimal = split.length > 1 ? period + split[1] : '';
  var reg = /(\d+)(\d{3})/;
  while (reg.test(numeric)) {
    numeric = numeric.replace(reg, '$1' + comma + '$2');
  }
  return numeric + decimal;
}

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
function mostrarVentana(box,mensaje){
	if(box == "warning-modal") {
		$("#warning-message-modal").text(mensaje);
		$("#warning-modal").show();
		setTimeout("ocultarWarningModal()",1000);
	} else if(box == "success-modal") {
        $("#success-message-modal").text(mensaje);
        $("#success-modal").show();
        setTimeout("ocultarSuccessmodal()",1000);
    } else if(box == "success-modal-suscripcion") {
        $("#success-message-modal-suscripcion").text(mensaje);
        $("#success-modal-suscripcion").show();
        setTimeout("ocultarSuccessmodalSuscripcion()",1000);
    } else if(box == "warning-modal-suscripcion") {
        $("#warning-message-modal-suscripcion").text(mensaje);
        $("#warning-modal-suscripcion").show();
        setTimeout("ocultarWarningModalSuscripcion()",1000);
	} else if(box == "success-modal-track") {
        $("#success-message-modal-track").text(mensaje);
        $("#success-modal-track").show();
        setTimeout("ocultarSuccessmodalTrack()",2000);
    } else if(box == "warning-modal-track") {
        $("#warning-message-modal-track").text(mensaje);
        $("#warning-modal-track").show();
        setTimeout("ocultarWarningModalTrack()",2000);
    } 
}

function ocultarWarningModal(){
    $("#warning-modal").hide(500);

}function ocultarSuccessmodal(){
    $("#success-modal").hide(500);
}
function ocultarWarningModalSuscripcion(){
	$("#warning-modal-suscripcion").hide(500);

}function ocultarSuccessmodalSuscripcion(){
	$("#success-modal-suscripcion").hide(500);
}

function ocultarWarningModalTrack(){
    $("#warning-modal-track").hide(500);

}function ocultarSuccessmodalTrack(){
    $("#success-modal-track").hide(500);
}

function addrequiredattr(id,focus){
	$('#'+id).attr("required", "required");
	if(focus == 1)
		$('#'+id).focus();
}

function obtenerJsonModal() {
	var jsonObject = new Object();

	var mensaje = 'Atención: ';
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
    var validacionSalfo = validaSaldo();
    if(validacionSalfo){
        mensaje+= ' | '+validacionSalfo+' ';
    }
    
	if (mensaje != 'Atención: '){
		mensaje+= ' |';
		mostrarVentana("warning-modal", mensaje);
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
		// if($("#planactivo-modal").val() != -1){jsonObject.CODIGO_PLAN = $("#planactivo-modal").val() } else {jsonObject.CODIGO_PLAN = 0};
        jsonObject.ESTADO = $("#estado-modal").val();
        jsonObject.ENVIAREMAIL = $("#enviaremail-modal").is(':checked') ? "SI" : "NO";
		jsonObject.GENTILEZA = $("#gentileza-modal").is(':checked') ? "S" : "N";
		return jsonObject
	}
}

function validaSaldo(){
    if($('#tipoclientecodigo-modal').attr("value") === 'C'){
        if(parseFloat($('#saldogestion-modal').attr("value")) == 0){
        return ' | No tiene saldo, para cargar gestión '; 
        }
        if(parseFloat($('#saldogestion-modal').attr("value")) < parseFloat($('#cantidadgestion-modal').attr("value"))){
            return ' | La cantidad de gestión supera el saldo disponible'; 
        }    
        if(parseFloat($('#saldogestion-modal').attr("value")) < parseFloat($('#cantidadgestion-modal').attr("value"))){
            return ' | La cantidad de gestión supera el saldo disponible'; 
        }
    }else{
        return false;
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
               mostrarVentana("success-modal","Se ingreso el registro con exito");
               limpiarFormulario();
                cargarModalNuevo();
                //buscar();                               
            }else{
                mostrarVentana("warning-modal","Verifique sus datos, ocurrio un error");  
            }            
               $.unblockUI();
        },
        error: function(event, request, settings){
//        	mostrarVentana("error-registro-listado","Ha ocurrido un error");
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
 $('#cliente-modal').select2("val",null);
 $('#fechagestion-modal').attr("value",null);
 $('#tipoclientecodigo-modal').attr("value",null);
 $('#tipocliente-modal').attr("value",null);
 $('#tarea-modal').attr("value",null);
 $("#iniciogestion-modal").attr("value",null);
 $("#fingestion-modal").attr("value",null);
 $("#tiempoestimado-modal").val(null);
 $("#cantidadgestion-modal").val(null);
 $("#asistenteservicios-modal").select2("val",null);
 $("#planactivo-modal").children("li").remove();
 $("#estado-modal").val(null);
 $("#saldogestion-modal").val(null);
 $("#planactivo-modal").children("li").remove();

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
        		// mostrarVentana("error-title",mostrarError("OcurrioError"));
        	}else{
            	$("#cliente-modal").html(respuesta);       		
        	}
        },
        error: function(event, request, settings){
         //   $.unblockUI();
        	 alert("OcurrioError");
        }
    });	
}

function getZonas(){
    
 // alert('Tipo Producto');
    $.ajax({
        url: table+'/getzonas',
        type: 'post',
        dataType: 'html',
        async : false,
        success: function(respuesta){
            if(respuesta== 'error'){
                // mostrarVentana("error-title",mostrarError("OcurrioError"));
            }else{
                $("#zona-acti").html(respuesta);            
            }
        },
        error: function(event, request, settings){
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
        		// mostrarVentana("error-title",mostrarError("OcurrioError"));
        	}else{
            	$("#asistenteservicios-modal").html(respuesta);       		
        	}
        },
        error: function(event, request, settings){
         //   $.unblockUI();
        	 alert("OcurrioError");
        }
    });	
}

function cargarPlanesActivos(){
	
//	alert('Tipo Producto');
	$.ajax({
        url: table+'/getplanesactivos',
        type: 'post',
        dataType: 'html',
        async : false,
        success: function(respuesta){
        	if(respuesta== 'error'){
        		// mostrarVentana("error-title",mostrarError("OcurrioError"));
        	}else{
            	$("#planactivo-modal").html(respuesta);       		
        	}
        },
        error: function(event, request, settings){
         //   $.unblockUI();
        	 alert("OcurrioError");
        }
    });	
}

function getClienteSuscripcion(){
	var data = new Object();
 	data.CODIGO_CLIENTE = $("#cliente-modal").val();

	var dataString = JSON.stringify(data); 	

	// alert($("#cliente-modal").val());
	$.ajax({
        url: table+'/getplanescliente',
        type: 'post',
        data: {"parametros":dataString},
        dataType: 'json',
        async : false,
        success: function(respuesta){
        	if(respuesta.success == false){
        		// $("#planactivo-modal:contains(\".list-group-item\")").remove();
                $("#planactivo-modal").children("li").remove();
                mostrarVentana("warning-modal","No hay suscripciones");  
               $( "#planactivo-modal" ).append( "<li class=\"list-group-item\"><button id=\"muestramodalsuscripcion\" onclick=\"mostrarSuscripcion()\">Realice una suscripcion para el cliente</button></li>" );
        		
        		$("#saldogestion-modal").attr("value",null);
        		$("#saldogestion-modal").attr("disabled",false);
        	}else{
                $("#planactivo-modal").children("li").remove();
                $("#tipoplanes-modal").children("input").remove();
                for (var i = 0; i < respuesta.length; i++ ) {
                      $( "#planactivo-modal" ).append( "<li class=\"list-group-item\">"+respuesta[i].plan+"</li>" );
                      // $( "#tipoplanes-modal" ).append( "<input id=\"plan-"+i+"\">"+respuesta[i].tipo+"</input>" );
                      $( "#tipoplanes-modal" ).append( "<input id=\"plan-"+i+"\" class=\"hide\"></input>" );
                    $("#plan-"+i).attr("value",respuesta[i].tipo);
                };
                
                               
                
        	}
        },
        error: function(event, request, settings){
         //   $.unblockUI();
        	 // alert(mostrarError("OcurrioError"));
        }
    });	


}

function getClienteSaldo(){
    var data = new Object();
    data.CODIGO_CLIENTE = $("#cliente-modal").val();

    var dataString = JSON.stringify(data);  

    // alert($("#cliente-modal").val());
    $.ajax({
        url: table+'/getsaldocliente',
        type: 'post',
        data: {"parametros":dataString},
        dataType: 'json',
        async : false,
        success: function(respuesta){
            console.log(respuesta);
            $("#saldogestion-modal").attr("disabled",true);
            if(respuesta.success == false){
                mostrarVentana("mensaje","No se pudo recuperar el saldo, intente de nuevo.")
            }else{
                $("#saldogestion-modal").attr("value",respuesta.SALDO);
                $("#tipoclientecodigo-modal").attr("value",respuesta.TIPO_CLIENTE);
                if(respuesta.TIPO_CLIENTE == 'C'){
                    $("#tipocliente-modal").attr("value",'Casual');
                }else if(respuesta.TIPO_CLIENTE == 'M'){
                    $("#tipocliente-modal").attr("value",'Mensual');
                }else{
                    $("#tipocliente-modal").attr("value",'Abierto');
                }
                
               
                
            }
        },
        error: function(event, request, settings){
         //   $.unblockUI();
             // alert(mostrarError("OcurrioError"));
        }
    }); 
}

function editarTrack(orden,codigoZona,descripcionZona){
   
}


