$().ready(function() {
	cargarCliente();
	
});

function buscarDatos(){
	buscaDatosCliente();
	buscarSaldos();
}

function cargarCliente(){
	
	// alert('Tipo Producto');
	$.ajax({
        url: '../logistica/suscripciones/getcliente',
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

function buscaDatosCliente() {
	// alert($("#cliente-modal").val());
 	var jsonReporte = new Object();	
	jsonReporte.CODIGO_CLIENTE = $("#cliente-modal").val();
	var dataString = JSON.stringify(jsonReporte); 
	if(jsonReporte.CODIGO_CLIENTE < 0){
		$("#collapseDatosPersonales").removeClass("in");
	}else{
		$("#collapseDatosPersonales").addClass("in");
		$.ajax({
	        // url: '../../../../../sansolucion/library/externos/index.php?type=PERFIL&CLIENTE='+CODIGO_CLIENTE,
	        url: table+'/perfilcliente',
	        type: 'GET',
	        data: {"parametros":dataString},
	        dataType: 'json',
	        async : false,
	        success: function(respuesta){
	        	if(typeof respuesta.success == 'undefined' && !respuesta.success){
	        		$("#cliente-info").val(respuesta[0].DESCRIPCION_PERSONA);
		        	$("#documentocliente-info").val(respuesta[0].NRO_DOCUMENTO_PERSONA);
		        	$("#telefonocliente-info").val(respuesta[0].TELEFONO);
		        	$("#direccioncliente-info").val(respuesta[0].DIRECCION_PERSONA);
		        	$("#tipocliente-info").val(respuesta[0].TIPO_CLIENTE);
		        	$("#fecha-factura").val(new Date());
					$("#cliente-factura").val(respuesta[0].DESCRIPCION_PERSONA);
					$("#documentocliente-factura").val(respuesta[0].NRO_DOCUMENTO_PERSONA);
					$("#collapseDatosPersonales").addClass("in");
					buscaGestionesCleinte();
	        	}else{
	        		alert("No se recuperaron los datos para este cliente.")
	        	}
	        	
		
	        },
	        error: function(event, request, settings){
	         //   $.unblockUI();
	        	 // alert(mostrarError("OcurrioError"));
	        }
    	});	
	}
}

function buscaGestionesCleinte(){
	var jsonReporte = new Object();	
	jsonReporte.CODIGO_CLIENTE = $("#cliente-modal").val();
	var dataString = JSON.stringify(jsonReporte); 

	$.ajax({
	        // url: '../../../../../ivan/index.php?type=GESTIONES&CLIENTE='+CODIGO_CLIENTE,
	         url: table+'/gestionescliente',
	        type: 'GET',
	        data: {"parametros":dataString},
	        dataType: 'json',
	        async : false,
	        success: function(respuesta){
	        	if(typeof respuesta.success == 'undefined' && !respuesta.success){
		        	$.each(respuesta, function( index, value ) {
					  // alert( index + ": " + value.FECHA_GESTION );
					  $('#gestiones > tbody:last').append('<tr><td>'+value.FECHA_GESTION+'</td><td>'+value.FECHA_FIN+'</td><td>'+value.DESCRIPCION_PERSONA+'</td><td>'+value.CANTIDAD_GESTIONES+'</td><td>'+value.OBSERVACION+'</td></tr>');
					});
					$("#collapseResumenGestiones").addClass("in");
				}
	        },
	        error: function(event, request, settings){
	         //   $.unblockUI();
	        	 // alert(mostrarError("OcurrioError"));
	        }
    	});	
}
	
function buscarSaldos() {
	

	var f = new Date();
	var m = f.getMonth(); 
	var y = f.getFullYear();
	if(m.length<2){
		m='0'+m;
	}
 	var jsonReporte = new Object();	
	jsonReporte.CODIGO_CLIENTE = $("#cliente-modal").val();
	jsonReporte.FECHA_SALDO = y+'-'+m;
	var dataString = JSON.stringify(jsonReporte); 
	if(jsonReporte.CODIGO_CLIENTE < 0){
		$("#collapseInformacionSaldos").removeClass("in");
	}else{
		$("#collapseInformacionSaldos").addClass("in");
		$.ajax({
	        // url: '../../../../../sansolucion/library/externos/index.php?type=PERFIL&CLIENTE='+CODIGO_CLIENTE,
	        url: table+'/saldoscliente',
	        type: 'GET',
	        data: {"parametros":dataString},
	        dataType: 'json',
	        async : false,
	        success: function(respuesta){
	        	if(typeof respuesta.success == 'undefined' && !respuesta.success){
		        	$.each(respuesta, function( index, value ) {
		        		$("#mensual-actual").val(value.MENS_ACT);
		        		$("#mensual-ante").val(value.MENS_ANT);
		        		$("#casual-actual").val(value.CASUAL_ACT);
		        		$("#casual-ante").val(value.CASUAL_ANT);			  
					});
				}
	        	
		
	        },
	        error: function(event, request, settings){
	         //   $.unblockUI();
	        	 // alert(mostrarError("OcurrioError"));
	        }
    	});	
	}
}