$().ready(function() {

//ocultamos el div de la fecha de acreditacion
 $("#divAcreditacion").hide();
    $("#fechavencimiento-filtro").datepicker();
    $("#fechavencimiento-filtro").datepicker("option", "dateFormat", "yy-mm-dd");

 	$("#muestramodal").click(function() {

 		limpiarFormulario();
 		cargarCliente();
		cargarPlanesActivos();
        bloqueardatos(false);
        seteaFechas();

            $("#codigosuscripcion-modal").attr("disabled",true);
		    $("#importegestion-modal").attr("disabled",true);
		    $("#estadosuscripcion-modal").val('A');
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
function seteaFechas(){

            $("#fechasuscripcion-modal").datepicker({
                dateFormat: "yy-mm-dd",
                onSelect: function(dateText, instance) {
                    date = $.datepicker.parseDate(instance.settings.dateFormat, dateText, instance.settings);
                     $("#fechaacreditacion-modal").datepicker("setDate", date);
                    // date.setMonth(date.getMonth() + 2);
                    y = date.getFullYear(), 
                    m = date.getMonth();
                    var ultimodia = new Date(y, m + 3, 0);
                    ulti = ultimodia.getDate();
                    var lastDay = new Date(y, m + 2, ulti);
                    $("#fechavencimiento-modal").datepicker("setDate", lastDay);
                   
                }
            });
            $("#fechasuscripcion-modal").datepicker("setDate", new Date());
            
            $("#fechavencimiento-modal").datepicker({
                dateFormat: "yy-mm-dd"
            }); 
            $("#fechaacreditacion-modal").datepicker({
                dateFormat: "yy-mm-dd"
            });
            $("#fechaacreditacion-modal").datepicker("setDate", new Date());

            var date = new Date(), 
            y = date.getFullYear(), 
            m = date.getMonth();
            var ultimodia = new Date(y, m + 3, 0);
            ulti = ultimodia.getDate();
            var lastDay = new Date(y, m + 2, ulti);
            $("#fechavencimiento-modal").attr("disabled", true);
            $("#fechavencimiento-modal").datepicker("setDate", lastDay);
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
function bloqueardatos(block){
        $("#fechasuscripcion-modal").attr("disabled",block);
        $("#cliente-modal").attr("disabled",block);
        $("#planactivo-modal").attr("disabled",block);
        $("#importegestion-modal").attr("disabled",block);
}
function mostarVentana(box,mensaje){
	if(box == "warning-modal") {
		$("#warning-message-modal").text(mensaje);
		$("#warning-modal").show();
		setTimeout("ocultarWarningModal()",700);
	} else if(box == "success-modal") {
		$("#success-message-modal").text(mensaje);
		$("#success-modal").show();
		setTimeout("ocultarSuccessmodal()",700);
	} 
}

function ocultarWarningModal(){
	$("#warning-modal").hide(500);

}
function ocultarSuccessmodal(){
    // alert("oculto");
	// $("#success-modal").hide(500);
    $("#success-modal").hide(400);
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

	if($('#cliente-modal').attr("value") == -1){
        mensaje+= ' | Cliente ';
        focus++;
        addrequiredattr('cliente-modal',focus); 
    }
	if($('#fechasuscripcion-modal').attr("value") == null || $('#fechasuscripcion-modal').attr("value").length == 0){
        mensaje+= ' | Fecha ';
    	focus++;
    	addrequiredattr('fechasuscripcion-modal',focus); 
	}
	

	
	if($('#planactivo-modal').attr("value") == -1){
        mensaje+= ' | Plan ';
    	focus++;
    	addrequiredattr('estado-modal',focus); 
	}
	if (mensaje != 'Ingrese los campos: '){
		mensaje+= ' |';
		mostarVentana("warning-modal", mensaje);
		return null;
	}else {
		jsonObject.CODIGO_SUSCRIPCION = $('#codigosuscripcion-modal').attr("value");
		jsonObject.CODIGO_CLIENTE = $('#cliente-modal').val();
		jsonObject.CODIGO_PLAN = $('#planactivo-modal').attr("value");
		jsonObject.FECHA_SUSCRIPCION = $('#fechasuscripcion-modal').attr("value");
		jsonObject.FECHA_VENCIMIENTO = $("#fechavencimiento-modal").val();
		jsonObject.FECHA_ACREDITACION = $("#fechaacreditacion-modal").val();
		jsonObject.IMPORTE_GESTION = $("#importegestion-modal").val();
		jsonObject.ESTADO_SUSCRIPCION = $("#estadosuscripcion-modal").val();
		return jsonObject
	}
}
function enviarParametros(data){
	$.blockUI({
        message: "Aguarde un momento por favor"
    });

	var urlenvio = '';
	if(data.CODIGO_SUSCRIPCION !== null && data.CODIGO_SUSCRIPCION.length !== 0){
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
               $("#modalNuevo").hide();
                buscar();                               
            }else if(respuesta.code == 1){
                mostarVentana("warning-modal",respuesta.mensaje);
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

    ocultarWarningModal();
    ocultarSuccessmodal();	
 $('#codigosuscripcion-modal').attr("value",null);
 $('#cliente-modal').select2("val",null);
 $('#planactivo-modal').select2("val",null);
 $('#fechasuscripcion-modal').attr("value",null);
 $('#fechaacreditacion-modal').attr("value",null);
 $('#fechavencimiento-modal').attr("value",null);
 $('#importegestion-modal').attr("value",null);
 $('#estadosuscripcion-modal').attr("value",null);
 

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
        	 alert("OcurrioError");
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
function cargarPlanesActivos(tipoPlan,cantidadPlan){
	console.log(tipoPlan,cantidadPlan);
    var data = new Object();
    data.TIPO_PLAN = (typeof tipoPlan != 'undefined')?tipoPlan:null;
    data.CANTIDAD_PLAN = (typeof cantidadPlan != 'undefined')?cantidadPlan:null;

    var dataString = JSON.stringify(data);
	$.ajax({
        url: table+'/getplanesactivos',
        type: 'post',
        data: {"parametros":dataString},
        dataType: 'html',
        async : false,
        success: function(respuesta){
        	if(respuesta== 'error'){
        		// mostarVentana("error-title",mostrarError("OcurrioError"));
        	}else{
            	$("#planactivo-modal").html(respuesta);       		
        	}
        },
        error: function(event, request, settings){
         //   $.unblockUI();
        	 alert(mostrarError("OcurrioError"));
        }
    });	
}

function calculaImporte(){
	var data = new Object();
 	data.CODIGO_PLAN = $("#planactivo-modal").val();

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
        		$("#importegestion-modal").attr("value",respuesta.IMPORTE_GESTION);
        		$("#importegestion-modal").attr("disabled",true);
        	}
        },
        error: function(event, request, settings){
         //   $.unblockUI();
        	 // alert(mostrarError("OcurrioError"));
        }
    });	
}

