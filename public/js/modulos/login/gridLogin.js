var pathname = window.location.pathname;
var table = pathname;
$(document).ready(function(){
	cargarGrillaRegistro();
});

function setTooltipsOnColumnHeader(grid, iColumn, text){
    var thd = jQuery("thead:first", grid[0].grid.hDiv)[0];
    jQuery("tr.ui-jqgrid-labels th:eq(" + iColumn + ")", thd).attr("title", text);
}
/**
 * Bloquea la pantalla a trav�s de un contenedor de tal manera que el usuario no pueda realizar ninguna acci�n
 */
function bloquearPantalla() {
	$.blockUI({message: "Aguarde un momento por favor"});
}
/**
 * Desbloquea la pantalla de tal manera que el usuario pueda realizar acci�nes o invocar eventos en la vista
 */
function desbloquearPantalla() {
    $.unblockUI();
}
/**
 * Carga la tabla visual con el listado de registros. La estructura de la tabla es especificada.
 */
function cargarGrillaRegistro() {
	jQuery("#grillaCliente").jqGrid({
        "url":table+'/listar',
        datatype: "local",
        
       	"refresh": true,
       	"datatype" :"json",
       	"height" : "auto",
       	"rownumbers" : false,
        "ExpandColumn": "menu",
        "autowidth": true,
       	"gridview" : true,
       	"multiselect" : false,
       	"viewrecords" : true,
       	"rowNum":10,
       	"formatter": null,
       	"rowList":[10,20,30],
       	"pager": '#paginadorCliente',
        "viewrecords": true,
        "beforeRequest" : bloquearPantalla,
        //"colNames":['modificar','nombre', 'sigla', 'porcentaje','montofijo','tipoaplicacion','empresa','sucursal'],
        "loadComplete": desbloquearPantalla,
       	"colModel":
       	[{
       		"title" : false,
       		"name" : "modificar",
       		"label" : " ",
       		"id" : "modificar",
       		"align":"right",
       		"search" : false,
       		"sortable" : false,
       		"width" : 5,
       		"edittype" :'link',
       		"remove" : false,
       		"hidden" : false,
       		"classes" : "linkjqgrid",
       		"formatter" :cargarLinkModificar
       },
       {
	       		"title" : false,
	       		"name" : "COD_CLIENTE",
	       		"label" :"CODIGO",
	       		"id" : "COD_CLIENTE",
	       		"width" : 10,
	       		"sortable" : false,
	       		"align":"right",
	       		"search" : false,
	       		"remove" : false,
	       		"hidden" : false
       	  },
       	  {
	       		"title" : false,
	       		"name" : "CLIENTE_DES",
	       		"label" : "DESCRIPCION",
	       		"id" : "CLIENTE_DES",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 40,
	       		"align":"left",
	       		"sortable" : false,
	       		"hidden" : false
       		},
       	  {
	       		"title" : false,
	       		"name" : "CLIENTE_RUC",
	       		"label" : "RUC - CED",
	       		"id" : "CLIENTE_RUC",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 18,
	       		"align":"right",
	       		"sortable" : false,
	       		"hidden" : false
       		},
       	  {
	       		"title" : false,
	       		"name" : "CLIENTE_DIRECCION",
	       		"label" : "DIRECCION",
	       		"id" : "CLIENTE_DIRECCION",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 30,
	       		"align":"left",
	       		"sortable" : false,
	       		"hidden" : false
       		},
       	  {
	       		"title" : false,
	       		"name" : "CLIENTE_TELEFONO",
	       		"label" : "TELEFONO",
	       		"id" : "CLIENTE_TELEFONO",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 10,
	       		"align":"right",
	       		"sortable" : false,
	       		"hidden" : true
       		},
       	  {
	       		"title" : false,
	       		"name" : "CLIENTE_EMAIL",
	       		"label" : "EMAIL",
	       		"id" : "CLIENTE_EMAIL",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 20,
	       		"align":"left",
	       		"sortable" : false,
	       		"hidden" : false
       		},
       	  {
	       		"title" : false,
	       		"name" : "COD_EMPRESA",
	       		"label" : "COD_EMPRESA",
	       		"id" : "COD_EMPRESA",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 20,
	       		"align":"right",
	       		"sortable" : false,
	       		"hidden" : true
       		},
       	  {
	       		"title" : false,
	       		"name" : "DES_EMPRESA",
	       		"label" : "EMPRESA",
	       		"id" : "DES_EMPRESA",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 20,
	       		"align":"left",
	       		"sortable" : false,
	       		"hidden" : false
       		}]
    }).navGrid('#paginadorRegistro',{
        add:false,
        edit:false,
        del:false,
        view:true,
        search:false,
        refresh:false});
	$("#grillaRegistro").setGridWidth($('#contenedor').width());

	$("#grillaRegistro").jqGrid('navButtonAdd','#paginadorRegistro',{
		buttonicon :'ui-icon-trash',
		caption: "",
		title: "Eliminar fila seleccionada",
		onClickButton : function (){
			borrar();	//Funcion de borrar
		}
	});
}
/**
 * Elimina una fila de la tabla visual de registros
 */
function borrar(){
	var id = $("#grillaRegistro").jqGrid('getGridParam','selrow');
	id = $("#grillaRegistro").jqGrid('getCell', id, 'COD_CLIENTE');
	if( id == false ){
		alert("Para eliminar un registro debe seleccionarlo previamente.");
	}else{
		if(!confirm("¿Esta seguro de que desea eliminar el registro seleccionado?"))
			return;

		$.ajax({
	        url: table+'/eliminar',
	        type: 'post',
	        data: {"id":id},
	        dataType: 'json',
	        async : false,
	        success: function(data){
	        	if(data.result == "ERROR"){
                                if(data.mensaje == 23504) {
                                        mostarVentana("warning-registro-listado","No se puede eliminar el Registro por que esta siendo utilizado");
			        } else {
			        	mostarVentana("warning-registro-listado","Ha ocurrido un error");
				    }
				} else {
					mostarVentana("success-registro-listado","Los datos han sido eliminados exitosamente");
				    $("#grillaRegistro").trigger("reloadGrid");
				}
	        },
	        error: function(event, request, settings){
	            $.unblockUI();
	            alert("Ha ocurrido un error");
	        }
	    });
	}
	return false;
}
/**
 * M�todo que carga la funcionalidad de Edici�n de filas de la tabla visual del registro
 */
function cargarLinkModificar ( cellvalue, options, rowObject )
{
	var parametros = new Object();
	parametros.COD_CLIENTE = rowObject[1];
	parametros.CLIENTE_DES = rowObject[2];
	parametros.CLIENTE_RUC = rowObject[3];
	parametros.CLIENTE_DIRECCION = rowObject[4];
	parametros.CLIENTE_TELEFONO = rowObject[5];
	parametros.CLIENTE_EMAIL = rowObject[6];
	parametros.COD_EMPRESA = rowObject[7];
	parametros.DES_EMPRESA = rowObject[8];
	json = JSON.stringify(parametros);
	return "<a><img title='Editar' src='../../css/images/edit.png' data-toggle='modal'  onclick='editarRegistro("+json+");'/></a>";
}
