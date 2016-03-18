$().ready(function() {
    $("#zona-modal").select2();
    getZonas();
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
        if (data != null) {
            enviarParametros(data);
        }
    });

});

function mostarVentana(box, mensaje) {
    if (box == "warning-modal") {
        $("#warning-message-modal").text(mensaje);
        $("#warning-modal").show();
        setTimeout("ocultarWarningModal()", 2000);
    } else if (box == "success-modal") {
        $("#success-message-modal").text(mensaje);
        $("#success-modal").show();
        setTimeout("ocultarSuccessmodal()", 2000);
    }
}

function ocultarWarningModal() {
    $("#warning-modal").hide(500);

}

function ocultarSuccessmodal() {
    $("#success-modal").hide(500);
}

function addrequiredattr(id, focus) {
    // $('#'+id).attr("has-warning", "required");
    // $('#'+id).addClass( "has-warning" );
    $('#' + id).parent().addClass('has-warning');
    if (focus == 1)
        $('#' + id).focus();
}

function obtenerJsonModal() {
    var jsonObject = new Object();

    var mensaje = 'Ingrese los campos: ';
    var focus = 0;

    if ($('#descripcion-modal').attr("value") == null || $('#descripcion-modal').attr("value").length == 0) {
        mensaje += ' | Descripci\u00F3n ';
        focus++;
        addrequiredattr('descripcion-modal', focus);
    }
    if ($('#zona-modal').attr("value") == -1) {
        mensaje += ' | Tipo Plan ';
        focus++;
        addrequiredattr('zona-modal', focus);
    }
    if ($('#ubicacion-modal').attr("value") == null || $('#ubicacion-modal').attr("value").length == 0) {
        mensaje += ' | Ubicaci\u00F3n ';
        focus++;
        addrequiredattr('ubicacion-modal', focus);
    }

    if (mensaje != 'Ingrese los campos: ') {
        mensaje += ' |';
        mostarVentana("warning-modal", mensaje);
        return null;
    } else {
        jsonObject.COD_DESTINO = $('#codigodestino-modal').attr("value");
        jsonObject.DESCRIPCION = $('#descripcion-modal').attr("value");
        jsonObject.CODIGO_ZONA = $('#zona-modal').attr("value");
        jsonObject.UBICACION = $("#ubicacion-modal").val();

        return jsonObject
    }
}

function enviarParametros(data) {
    $.blockUI({
        message: "Aguarde un momento por favor"
    });
    console.log(data);
    var urlenvio = '';
    if (data.COD_DESTINO !== null && data.COD_DESTINO !== "") {
        urlenvio = table + '/modificar';
    } else {
        urlenvio = table + '/guardar';
    }
    var dataString = JSON.stringify(data);

    $.ajax({
        url: urlenvio,
        type: 'post',
        data: { "parametros": dataString },
        dataType: 'json',
        async: true,
        success: function(respuesta) {
            if (respuesta.success) {
                mostarVentana("success-modal", "Se ingreso el registro con exito");
                limpiarFormulario();
                buscar();
            } else {
                mostarVentana("warning-modal", "Verifique sus datos, ocurrio un error");
            }
            $.unblockUI();
        },
        error: function(event, request, settings) {
            //        	mostarVentana("error-registro-listado","Ha ocurrido un error");
            $.unblockUI();
        }
    });
}


function limpiarFiltos() {

    $("#descripcion-filtro").attr("value", null);

}

function limpiarFormulario() {

    $("#codigodestino-modal").attr("value", null);
    $("#descripcion-modal").attr("value", null);
    $("#zona-modal").select2("val", -1);
    $("#ubicacion-modal").attr("value", null);
}

function getZonas(){
    
    $.ajax({
        url: '../logistica/gestionescargas/getzonas',
        type: 'post',
        dataType: 'html',
        async : false,
        success: function(respuesta){
            if(respuesta== 'error'){
                // mostrarVentana("error-title",mostrarError("OcurrioError"));
            }else{
                $("#zona-modal").html(respuesta);            
            }
        },
        error: function(event, request, settings){
        }
    }); 
}
