$().ready(function() {

	// programamos los botones
 	$("#muestramodal").click(function() {
 		limpiarFormulario()
            $("#modalNuevo").show();
           
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

	if($('#descripcionplan-modal').attr("value") == null || $('#descripcionplan-modal').attr("value").length == 0){
        mensaje+= ' | Descripci\u00F3n ';
    	focus++;
    	addrequiredattr('descripcionplan-modal',focus); 
	}
	if($('#cantidadplan-modal').attr("value") == null || $('#cantidadplan-modal').attr("value").length == 0){
        mensaje+= ' | Cantidad ';
    	focus++;
    	addrequiredattr('cantidadplan-modal',focus); 
	}
	if($('#costoplan-modal').attr("value") == null || $('#costoplan-modal').attr("value").length == 0){
        mensaje+= ' | Costo ';
    	focus++;
    	addrequiredattr('costoplan-modal',focus); 
	}
    if($("#estadoplan-modal" ).val() == -1){
        mensaje+= ' | Estado ';
    	focus++;
    	addrequiredattr('estadoplan-modal',focus);    
	}
	if (mensaje != 'Ingrese los campos: '){
		mensaje+= ' |';
		mostarVentana("warning-modal", mensaje);
		return null;
	}else {
		jsonObject.CODIGO_PLAN = $('#codigoplan-modal').attr("value");
		jsonObject.DESCRIPCION_PLAN = $('#descripcionplan-modal').attr("value");
		jsonObject.CANTIDAD_PLAN = $('#cantidadplan-modal').attr("value");
		jsonObject.COSTO_PLAN = $('#costoplan-modal').attr("value");
		jsonObject.ESTADO_PLAN = $("#estadoplan-modal").val();
		
		return jsonObject
	}
}
function enviarParametros(data){
	$.blockUI({
        message: "Aguarde un momento por favor"
    });
	console.log(data);
	var urlenvio = '';
	if(data.CODIGO_PLAN !== null && data.CODIGO_PLAN.length !== 0){
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

	$("#descripcionplan-filtro").attr("value",null);

	}

function limpiarFormulario(){

	$("#codigoplan-modal").attr("value",null);
	$("#descripcionplan-modal").attr("value",null);
	$("#cantidadplan-modal").attr("value",null);
	$("#costoplan-modal").attr("value",null);
	$("#estadoplan-modal").attr("value",null);


}

