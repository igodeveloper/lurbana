$().ready(function() {

});

function mostrarSuscripcion(){
 		limpiarFormularioModal();
 		cargarClienteModal();
		cargarPlanesActivosModal();
        bloqueardatosModal(false);
        alert(table);
            $("#fechasuscripcion-modal-suscripcion").datepicker({
                dateFormat: "yy-mm-dd",
                onSelect: function(dateText, instance) {
                    date = $.datepicker.parseDate(instance.settings.dateFormat, dateText, instance.settings);
                     $("#fechaacreditacion-modal-suscripcion").datepicker("setDate", date);
                    date.setMonth(date.getMonth() + 2);
                    $("#fechavencimiento-modal-suscripcion").datepicker("setDate", date);
                   
                }
            });
            $("#fechasuscripcion-modal-suscripcion").datepicker("setDate", new Date());
            
            $("#fechavencimiento-modal-suscripcion").datepicker({
                dateFormat: "yy-mm-dd"
            }); 
            $("#fechaacreditacion-modal-suscripcion").datepicker({
                dateFormat: "yy-mm-dd"
            });
            $("#fechaacreditacion-modal-suscripcion").datepicker("setDate", new Date());

            var date = new Date(), 
            y = date.getFullYear(), 
            m = date.getMonth();
            d = date.getDate(); 
            var lastDay = new Date(y, m + 2, d);
            $("#fechavencimiento-modal-suscripcion").attr("disabled", true);
            $("#fechavencimiento-modal-suscripcion").datepicker("setDate", lastDay);

            $("#codigosuscripcion-modal-suscripcion").attr("disabled",true);
		    $("#importegestion-modal-suscripcion").attr("disabled",true);
		    $("#estadosuscripcion-modal-suscripcion").val('A');
            $("#modalNuevo-suscripcion").show();
            $("#ui-datepicker-div").css('display','none');

}

function obtenerJsonModalsuscripcion() {
	var jsonObject = new Object();

	var mensaje = 'Ingrese los campos: ';
    var focus = 0;

	if($('#cliente-modal-suscripcion').attr("value") == -1){
        mensaje+= ' | Cliente ';
        focus++;
        addrequiredattr('cliente-modal-suscripcion',focus); 
    }
	if($('#fechasuscripcion-modal-suscripcion').attr("value") == null || $('#fechasuscripcion-modal-suscripcion').attr("value").length == 0){
        mensaje+= ' | Fecha ';
    	focus++;
    	addrequiredattr('fechasuscripcion-modal-suscripcion',focus); 
	}
	

	
	if($('#planactivo-modal-suscripcion').attr("value") == -1){
        mensaje+= ' | Plan ';
    	focus++;
    	addrequiredattr('estado-modal-suscripcion',focus); 
	}
	if (mensaje != 'Ingrese los campos: '){
		mensaje+= ' |';
		mostarVentana("warning-modal-suscripcion", mensaje);
		return null;
	}else {
		jsonObject.CODIGO_SUSCRIPCION = $('#codigosuscripcion-modal-suscripcion').attr("value");
		jsonObject.CODIGO_CLIENTE = $('#cliente-modal-suscripcion').val();
		jsonObject.CODIGO_PLAN = $('#planactivo-modal-suscripcion').attr("value");
		jsonObject.FECHA_SUSCRIPCION = $('#fechasuscripcion-modal-suscripcion').attr("value");
		jsonObject.FECHA_VENCIMIENTO = $("#fechavencimiento-modal-suscripcion").val();
		jsonObject.FECHA_ACREDITACION = $("#fechaacreditacion-modal-suscripcion").val();
		jsonObject.IMPORTE_GESTION = $("#importegestion-modal-suscripcion").val();
		jsonObject.ESTADO_SUSCRIPCION = $("#estadosuscripcion-modal-suscripcion").val();
		return jsonObject
	}
}
function enviarParametrossuscripcion(data){
	$.blockUI({
        message: "Aguarde un momento por favor"
    });

	var urlenvio = '';
	urlenvio = table+'/guardarsuscripcion';
	var dataString = JSON.stringify(data);

	$.ajax({
        url: urlenvio,
        type: 'post',
        data: {"parametros":dataString},
        dataType: 'json',
        async : true,
        success: function(respuesta){
        	 if(respuesta.success){
               mostarVentana("success-modal-suscripcion","Se ingreso el registro con exito");
               limpiarFormularioModal();
			   $("#modalNuevo-suscripcion").hide();
			   limpiarFormulario();
                buscar();                               
            }else if(respuesta.code == 1){
                mostarVentana("warning-modal-suscripcion",respuesta.mensaje);
            }else{
                mostarVentana("warning-modal-suscripcion","Verifique sus datos, ocurrio un error");  
            }            
               $.unblockUI();
        },
        error: function(event, request, settings){
//        	mostarVentana("error-registro-listado","Ha ocurrido un error");
    		$.unblockUI();
        }
    });
}

function limpiarFormularioModal(){

	 $('#codigosuscripcion-modal-suscripcion').attr("value",null);
	 $('#cliente-modal-suscripcion').select2("val",null);
	 $('#planactivo-modal-suscripcion').select2("val",null);
	 $('#fechasuscripcion-modal-suscripcion').attr("value",null);
	 $('#fechaacreditacion-modal-suscripcion').attr("value",null);
	 $('#fechavencimiento-modal-suscripcion').attr("value",null);
	 $('#importegestion-modal-suscripcion').attr("value",null);
	 $('#estadosuscripcion-modal-suscripcion').attr("value",null);
 
}

function cargarClienteModal(){
	
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
            	$("#cliente-modal-suscripcion").html(respuesta);       		
        	}
        },
        error: function(event, request, settings){
         //   $.unblockUI();
        	 alert(mostrarError("OcurrioError"));
        }
    });	
}

function cargarPlanesActivosModal(){
	
//	alert('Tipo Producto');
	$.ajax({
        url: table+'/getplanesactivos',
        type: 'post',
        dataType: 'html',
        async : false,
        success: function(respuesta){
        	if(respuesta== 'error'){
        		// mostarVentana("error-title",mostrarError("OcurrioError"));
        	}else{
            	$("#planactivo-modal-suscripcion").html(respuesta);       		
        	}
        },
        error: function(event, request, settings){
         //   $.unblockUI();
        	 alert(mostrarError("OcurrioError"));
        }
    });	
}

function bloqueardatosModal(block){
        $("#fechasuscripcion-modal-suscripcion").attr("disabled",block);
        $("#cliente-modal-suscripcion").attr("disabled",block);
        $("#planactivo-modal-suscripcion").attr("disabled",block);
        $("#importegestion-modal-suscripcion").attr("disabled",block);
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

function calculaImporte(){
	var data = new Object();
 	data.CODIGO_PLAN = $("#planactivo-modal-suscripcion").val();

	var dataString = JSON.stringify(data); 	

	// alert($("#cliente-modal").val());
	$.ajax({
        url: table+'/getimportesuscripcion',
        type: 'post',
        data: {"parametros":dataString},
        dataType: 'json',
        async : false,
        success: function(respuesta){
        	console.log(respuesta);
        	if(respuesta.success == false){
        		mostarVentana("warning-modal","No hay plan");  

        	}else{		
        		$("#importegestion-modal-suscripcion").attr("value",respuesta.IMPORTE_GESTION);
        		$("#importegestion-modal-suscripcion").attr("disabled",true);
        	}
        },
        error: function(event, request, settings){
         //   $.unblockUI();
        	 // alert(mostrarError("OcurrioError"));
        }
    });	
}