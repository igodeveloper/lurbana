var pathname = window.location.pathname;
var table = pathname;
$().ready(function () {
	$("#rol-modal").select2();
	cargarGrillaRegistro();
	// programamos los botones
	$("#muestramodal").click(function () {
		limpiarFormulario()
		$("#modalNuevo").show();


	});
	$("#close-modal").click(function () {
		$("#modalNuevo").hide();

	});
	$("#cancelar-modal").click(function () {
		$("#modalNuevo").hide();

	});



	$('#guardar-modal').click(function () {
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

	if ($('#nombreapellido-modal').attr("value") == null || $('#nombreapellido-modal').attr("value").length == 0) {
		mensaje += ' | Nombre y Apellido ';
		focus++;
		addrequiredattr('nombreapellido-modal', focus);
	}
	if ($('#identificador-modal').attr("value") == null || $('#identificador-modal').attr("value").length == 0) {
		mensaje += ' | ID usuario ';
		focus++;
		addrequiredattr('identificador-modal', focus);
	}
	if ($('#rol-modal').attr("value") == -1) {
		mensaje += ' | Rol ';
		focus++;
		addrequiredattr('rol-modal', focus);
	}

	if ($("#cambiar-modal").is(':checked')) {
		if ($('#pass-modal').attr("value") == null || $('#pass-modal').attr("value").length == 0) {
			mensaje += ' | Password ';
			focus++;
			addrequiredattr('pass-modal', focus);
		}
		if ($('#pass2-modal').attr("value") == null || $('#pass2-modal').attr("value").length == 0) {
			mensaje += ' | Password ';
			focus++;
			addrequiredattr('pass2-modal', focus);
		}
		if ($('#pass2-modal').attr("value") != $('#pass-modal').attr("value")) {
			mensaje += ' | Passwords no coinciden ';
			focus++;
			addrequiredattr('pass2-modal', focus);
		}
	}

	if ($("#codigousuario-modal").attr("value") == null || $('#codigousuario-modal').attr("value").length == 0) {
		if ($('#pass-modal').attr("value") == null || $('#pass-modal').attr("value").length == 0) {
			mensaje += ' | Password ';
			focus++;
			addrequiredattr('pass-modal', focus);
		}
		if ($('#pass2-modal').attr("value") == null || $('#pass2-modal').attr("value").length == 0) {
			mensaje += ' | Password ';
			focus++;
			addrequiredattr('pass2-modal', focus);
		}
		if ($('#pass2-modal').attr("value") != $('#pass-modal').attr("value")) {
			mensaje += ' | Passwords no coinciden ';
			focus++;
			addrequiredattr('pass2-modal', focus);
		}
	}


	if (mensaje != 'Ingrese los campos: ') {
		mensaje += ' |';
		mostarVentana("warning-modal", mensaje);
		return null;
	} else {
		jsonObject.COD_USUARIO = $('#codigousuario-modal').attr("value");
		jsonObject.NOMBRE_APELLIDO = $('#nombreapellido-modal').attr("value");
		jsonObject.ID_USUARIO = $('#identificador-modal').attr("value");
		jsonObject.ROL = $('#rol-modal').attr("value");
		jsonObject.USUARIO_PASSWORD = $("#pass-modal").val();
		jsonObject.CAMBIAR = $("#cambiar-modal").is(':checked') ? "SI" : "NO";

		return jsonObject
	}
}

function enviarParametros(data) {
	$.blockUI({
		message: "Aguarde un momento por favor"
	});
	console.log(data);
	var urlenvio = '';
	if (data.COD_USUARIO !== null && data.COD_USUARIO !== "") {
		urlenvio = table + '/modificar';
	} else {
		urlenvio = table + '/guardar';
	}
	var dataString = JSON.stringify(data);

	$.ajax({
		url: urlenvio
		, type: 'post'
		, data: {
			"parametros": dataString
		}
		, dataType: 'json'
		, async: true
		, success: function (respuesta) {
			if (respuesta.success) {
				mostarVentana("success-modal", "Se ingreso el registro con exito");
				limpiarFormulario();
				buscar();
			} else {
				mostarVentana("warning-modal", "Verifique sus datos, ocurrio un error");
			}
			$.unblockUI();
		}
		, error: function (event, request, settings) {
			//        	mostarVentana("error-registro-listado","Ha ocurrido un error");
			$.unblockUI();
		}
	});
}


function limpiarFiltos() {

	$("#nombreapellido-filtro").attr("value", null);

}

function limpiarFormulario() {

	$("#codigousuario-modal").attr("value", null);
	$("#nombreapellido-modal").attr("value", null);
	$("#identificador-modal").attr("value", null);
	$("#rol-modal").select2("val", -1);
	$("#pass-modal").attr("value", null);
	$("#pass2-modal").attr("value", null);
}


function setTooltipsOnColumnHeader(grid, iColumn, text) {
	var thd = jQuery("thead:first", grid[0].grid.hDiv)[0];
	jQuery("tr.ui-jqgrid-labels th:eq(" + iColumn + ")", thd).attr("title", text);
}
/**
 * Bloquea la pantalla a trav�s de un contenedor de tal manera que el usuario no pueda realizar ninguna acci�n
 */
function bloquearPantalla() {
	$.blockUI({
		message: "Aguarde un momento por favor"
	});
}
/**
 * Desbloquea la pantalla de tal manera que el usuario pueda realizar acci�nes o invocar eventos en la vista
 */
function desbloquearPantalla() {
	$.unblockUI();
}

function widthOfGrid() {
	var windowsWidth = $(window).width();
	var gridWidth = ((90 * 94 * windowsWidth) / (100 * 100));
	return gridWidth;
}

/**
 * Carga la tabla visual con el listado de registros. La estructura de la tabla es especificada.
 */
// console.log('hola'+ $("#estadopersona-modal").val());
function cargarGrillaRegistro() {
	jQuery("#grilla").jqGrid({

		url: table + "/buscar"
		, datatype: "local"
		, mtype: "POST"
		, height: 280
		, rowNum: 15
		, autowith: true
		, rowList: [],

		colModel: [{
			name: 'COD_USUARIO'
			, index: 'COD_USUARIO'
			, label: 'CODIGO'
			, hidden: false
			, width: 150
			, align: 'right'
        }, {
			name: 'NOMBRE_APELLIDO'
			, index: 'NOMBRE_APELLIDO'
			, label: 'NOMBRE APELLIDO'
			, width: 600
			, hidden: false
			, align: 'left'
        }, {
			name: 'ID_USUARIO'
			, index: 'ID_USUARIO'
			, label: 'IDENTIFICADOR'
			, width: 300
			, hidden: false
			, align: 'left'
        }, {
			name: 'ROL'
			, index: 'ROL'
			, label: 'ROL'
			, width: 200
			, hidden: false
			, align: 'left'
        }]
		, emptyrecords: "Sin Datos"
		, shrinkToFit: false
		, pager: "#paginador"
		, viewrecords: true
		, gridview: false
		, hidegrid: false
		, altRows: true
		, ondblClickRow: function (rowid) {
			var rowdata = jQuery(this).jqGrid('getRowData', rowid);
			modalModificar(rowdata);
		}
	});
	$("#grilla").setGridWidth(widthOfGrid());
}

function buscar() {

	$("#grilla").jqGrid('setGridParam', {
		datatype: "json"
		, postData: {
			filtros: JSON.stringify({
				"NOMBRE_APELLIDO": $("#nombreapellido-filtro").val()
			})
		, }
	}).trigger("reloadGrid");
}

function modalModificar(rowData) {

	$("#codigousuario-modal").attr("value", rowData.COD_USUARIO);
	$("#nombreapellido-modal").attr("value", rowData.NOMBRE_APELLIDO);
	$("#identificador-modal").attr("value", rowData.ID_USUARIO);
	$("#rol-modal").select2("val", rowData.ROL);
	$("#modalNuevo").show();


}