$().ready(function() {
	cargarCliente();

	// $("#modalDetalle").hide();
	$("#muestramodal").click(function() {
        $("#modalDetalle").show();
        $("#grillaDetalle").setGridWidth($(".modal-body").width()); 

           
    });
   
     $("#close-modal").click(function() {
        $("#modalDetalle").hide();
           
    }); 
     $("#cancelar-modal").click(function() {
        $("#modalDetalle").hide();

           
    });
    $("#aceptar-modal").click(function() {
     	cargaGRillaFacturaDetalle();
     	$("#modalDetalle").hide();
       // console.log(jQuery("#grillaDetalle").jqGrid('getGridParam','selarrrow'));
           
    });  
    $("#limpiarDetalle").click(function() {
    	limpiarDetalle();
    	$("#grillaDetalle").jqGrid('resetSelection');	
           
    }); 
	
});

function buscarDatos(){
	buscaDatosCliente();
	buscarSaldos();
	cargaDetalleFactura();
	limpiarDetalle();
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
		        	$("#fecha-factura").val(fechaHoy());
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

function fechaHoy(){
	var d = new Date();

	var month = d.getMonth()+1;
	var day = d.getDate();

	var output = d.getFullYear() + '/' +
	    ((''+month).length<2 ? '0' : '') + month + '/' +
	    ((''+day).length<2 ? '0' : '') + day;
	    return output;
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
	var m = f.getMonth()+1; 
	var y = f.getFullYear();
	// var x = ''

 	var jsonReporte = new Object();	
	jsonReporte.CODIGO_CLIENTE = $("#cliente-modal").val();
		alert(m);
		if(m<10){
			jsonReporte.FECHA_SALDO = y+'-0'+m;
		}else{
			jsonReporte.FECHA_SALDO = y+'-'+m;
		}
	
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

function cargaDetalleFactura() {
	
 	var jsonReporte = new Object();	
	jsonReporte.CODIGO_CLIENTE = $("#cliente-modal").val();
			
	var dataString = JSON.stringify(jsonReporte); 
	$.ajax({
	    url: table+'/detallefacturar',
	    type: 'GET',
	    data: {"parametros":dataString},
	    dataType: 'json',
	    async : false,
	    success: function(respuesta){
	    	var grid = jQuery("#grillaDetalle");
	    	grid.jqGrid('clearGridData');
	    	if(typeof respuesta.success == 'undefined' && !respuesta.success){
	        	for (i=0;i<respuesta.length;i++) {
                	grid.jqGrid('addRowData', i+1, respuesta[i]);
            	}
			}
	    	

	    },
	    error: function(event, request, settings){
	     //   $.unblockUI();
	    	 // alert(mostrarError("OcurrioError"));
	    }
	});	
}

function cargaGRillaFacturaDetalle(){
	limpiarDetalle();
	var detalle = jQuery("#grillaDetalle").jqGrid('getGridParam','selarrrow');
	var suma = 0;
	var iva10 = 0;
	$.each(detalle, function( index, value ) {
		var grilla = new Object();
		grilla = jQuery('#grillaDetalle').jqGrid ('getRowData', value);
		 $('#detallefactura > tbody:last').append('<tr><td>1</td><td>'+grilla.DESCRIPCION_PLAN+'</td><td>'+grilla.IMPORTE_SALDO+'</td><td>-</td><td>-</td><td>'+grilla.IMPORTE_SALDO+'</td></tr>');
		suma = parseInt(suma)+parseInt(grilla.IMPORTE_SALDO);
		iva10 = (parseInt(iva10)+parseFloat(parseInt(grilla.IMPORTE_SALDO)*10/110)).toFixed(0);
	});
	$("#total-factura").text(suma);
	$("#iva-factura").text(iva10);
	$("#totaliva-factura").text(iva10);
}

function limpiarDetalle(){
	$('#table_tbody tr').remove();

}