$().ready(function() {
	cargarCliente();
	cargarSeries();
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

    $('#facturar').click(function() {
		 var data = obtenerDatos();
		if(data != null){
			enviarParametros(data);
			// console.log(data);
			// enviarParametros(data);
		}
	 });
	
});

function buscarDatos(){
	buscaDatosCliente();
	buscarSaldos();
	cargaDetalleFactura();
	cargaFacturasCliente();
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
function cargarSeries(){
	
	// alert('Tipo Producto');
	$.ajax({
        url: table+'/getseries',
        type: 'post',
        dataType: 'html',
        async : false,
        success: function(respuesta){
        	if(respuesta== 'error'){
        		// mostarVentana("error-title",mostrarError("OcurrioError"));
        	}else{
            	$("#series-factura").html(respuesta);       		
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

	var output = d.getFullYear() + '-' +
	    ((''+month).length<2 ? '0' : '') + month + '-' +
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
		// alert(m);
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
	// $('#table_tbody_facturas tr').remove();
	$("#total-factura").text("");
	$("#iva-factura").text("");
	$("#totaliva-factura").text("");

}

function obtenerDatos(){

	var jsonObject = new Object();

	var mensaje = 'Ingrese los campos: ';
    var focus = 0;

	if($('#series-factura').attr("value") == null || $('#series-factura').attr("value").length == 0){
        mensaje+= ' | Serie ';
	}	
	if($('#controlfiscal-factura').attr("value") == null || $('#controlfiscal-factura').attr("value").length == 0){
        mensaje+= ' | control fiscal ';
	} 
	var detalle = jQuery("#grillaDetalle").jqGrid('getGridParam','selarrrow');
	if (detalle.length<1) {
		mensaje+= ' | seleccione un item ';	
	};
	
	if (mensaje != 'Ingrese los campos: '){
		alert(mensaje);
		return null;
	}else {
		var datos = recuperaDatosGrilla();
		var grillaDetalle = recuperaDetalles();

		jsonObject.CODIGO_CLIENTE = parseInt($("#cliente-modal").val());
		jsonObject.NOMBRE_CLIENTE = ($("#cliente-factura").val());
		jsonObject.DOCUMENTO_CLIENTE = ($("#documentocliente-factura").val());
		jsonObject.COD_TALONARIO = parseInt($('#series-factura').val());
		jsonObject.SER_COMPROBANTE = $("#series-factura :selected").text();
		jsonObject.FECHA = $("#fecha-factura").val();
		jsonObject.NRO_COMPROBANTE = parseInt($('#controlfiscal-factura').attr("value"));
		jsonObject.NRO_TIMBRADO = 1
		jsonObject.TOTAL = parseInt(datos.TOT_GRAVADAS);
		jsonObject.TOT_GRAVADAS = parseInt(datos.IVA_10);
		jsonObject.TOT_EXENTAS = 0;
		jsonObject.SALDO = parseInt(datos.TOT_GRAVADAS);
		jsonObject.detalle = grillaDetalle;


		return jsonObject;
	}

}

function recuperaDatosGrilla(){
	var detalle = jQuery("#grillaDetalle").jqGrid('getGridParam','selarrrow');
	var retorna = new Object();
	retorna.IVA_10 = 0;
	retorna.TOT_GRAVADAS = 0;
	$.each(detalle, function( index, value ) {
		var grilla = new Object();
		grilla = jQuery('#grillaDetalle').jqGrid ('getRowData', value);
		retorna.TOT_GRAVADAS = (parseInt(retorna.TOT_GRAVADAS)+parseInt(grilla.IMPORTE_SALDO)).toFixed(0);
		retorna.IVA_10 = (parseInt(retorna.IVA_10)+parseFloat(parseInt(grilla.IMPORTE_SALDO)*10/110)).toFixed(0);
	});

	return retorna;
}
function recuperaDetalles(){
	var detalle = jQuery("#grillaDetalle").jqGrid('getGridParam','selarrrow');
	var fruits = [];

	$.each(detalle, function( index, value ) {
		var grilla = new Object();
		grilla = jQuery('#grillaDetalle').jqGrid ('getRowData', value);
		fruits.push(grilla);		
	});

	return fruits;
}

function enviarParametros(data){
	$.blockUI({
        message: "Aguarde un momento por favor"
    });

	var dataString = JSON.stringify(data);

	$.ajax({
        url: table+'/guardar',
        type: 'post',
        data: {"parametros":dataString},
        dataType: 'json',
        async : true,
        success: function(respuesta){
        		if(respuesta.success){
	      			 $.unblockUI();
    			 mostarVentana();    			
        		window.open(table+'/printpdf','_blank');
        		cargaFacturasCliente();
        		}

        	
              
        },
        error: function(event, request, settings){
//        	mostarVentana("error-registro-listado","Ha ocurrido un error");
    		$.unblockUI();
        }
    });
}
function reimpresion(id){
	// alert(id);
	$.blockUI({
        message: "Aguarde un momento por favor"
    });

	var jsonReporte = new Object();	
	jsonReporte.ID_COMPROBANTE = id;
	jsonReporte.DOCUMENTO_CLIENTE = ($("#documentocliente-factura").val());

	var dataString = JSON.stringify(jsonReporte); 

	$.ajax({
        url: table+'/reimpresion',
        type: 'get',
        data: {"parametros":dataString},
        dataType: 'json',
        async : true,
        success: function(respuesta){
        		if(respuesta.success){
	      			 $.unblockUI();
    			 mostarVentana();    			
        		window.open(table+'/printpdf','_blank');
        		}

        	
              
        },
        error: function(event, request, settings){
//        	mostarVentana("error-registro-listado","Ha ocurrido un error");
    		$.unblockUI();
        }
    });
}

function cargaFacturasCliente(){
	var jsonReporte = new Object();	
	jsonReporte.CODIGO_CLIENTE = $("#cliente-modal").val();
	var dataString = JSON.stringify(jsonReporte); 

	$.ajax({
	         url: table+'/facturas',
	        type: 'GET',
	        data: {"parametros":dataString},
	        dataType: 'json',
	        async : false,
	        success: function(respuesta){
	        	if(typeof respuesta.success == 'undefined' && !respuesta.success){
	        		$('#table_tbody_facturas tr').remove();
		        	$.each(respuesta, function( index, value ) {
					  // alert( index + ": " + value.FECHA_GESTION );
					  $('#facturas > tbody:last').append('<tr><td>'+value.FECHA+'</td><td>'+value.ID_COMPROBANTE+'</td><td>'+value.SER_COMPROBANTE+'-'+value.NRO_COMPROBANTE+'</td><td>'+value.TOTAL+'</td><td>'+value.SALDO+'</td><td>'+value.ESTADO+'</td><td><button type="button" class="btn btn-success" onclick="reimpresion('+value.ID_COMPROBANTE+')">Reimprimir</button></td></tr>');
					});
					// $("#collapseResumenGestiones").addClass("in");
				}
	        },
	        error: function(event, request, settings){
	         //   $.unblockUI();
	        	 // alert(mostrarError("OcurrioError"));
	        }
    	});	
}

function mostarVentana(){
	$("#facturado").removeClass("hide");
	limpiarDetalle();
	cargaDetalleFactura();
	setTimeout("ocultarWarningModal()",5000);
	
}

function ocultarWarningModal(){
	$("#facturado").addClass("hide");
}

function controlfiscal(){
	var jsonReporte = new Object();	
	jsonReporte.COD_TALONARIO = $("#series-factura").val();
	var dataString = JSON.stringify(jsonReporte); 

	$.ajax({
	         url: table+'/getcontrolfiscal',
	        type: 'GET',
	        data: {"parametros":dataString},
	        dataType: 'json',
	        async : false,
	        success: function(respuesta){
	        	if(respuesta.success){
	        		$("#controlfiscal-factura").val(respuesta.numero);
				}
	        },
	        error: function(event, request, settings){
	         //   $.unblockUI();
	        	 // alert(mostrarError("OcurrioError"));
	        }
    	});	
}