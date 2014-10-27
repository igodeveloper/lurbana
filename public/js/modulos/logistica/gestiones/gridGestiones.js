var pathname = window.location.pathname;
var table = pathname;
jQuery(document).ready(function(){
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
function widthOfGrid() {
    var windowsWidth = $(window).width();
    var gridWidth = ((97 * 97 * windowsWidth) / (100 * 100));
    return gridWidth;
}

/**
 * Carga la tabla visual con el listado de registros. La estructura de la tabla es especificada.
 */
function cargarGrillaRegistro() {
    jQuery("#grillaGestiones").jqGrid({
        
                url: table+"/buscar",
                datatype: "local",
                mtype : "POST",
                autowith: true,
                height: 280,
                rowNum: 15,
                rowList: [],
                
                colModel:[
                    {
                        name: 'NUMERO_GESTION',
                        index: 'NUMERO_GESTION',
                        label: 'NRO',
                        hidden :false,
                        width: 40,
                        align: 'right'
                    },{
                        name: 'FECHA_GESTION',
                        index: 'FECHA_GESTION',
                        label: 'FECHA',
                        hidden :false,
                        width: 80,
                        align: 'center'
                    },{
                        name: 'FECHA_INICIO',
                        index: 'FECHA_INICIO',
                        label: 'INICIO',
                        hidden :false,
                        width: 110,
                        align: 'center'
                    },{ 
                        name: 'FECHA_FIN',
                        index: 'FECHA_FIN',
                        label: 'FIN',
                        width: 110,
                        hidden : false,
                        align: 'center'    
                    },
                        { name: 'CODIGO_CLIENTE',
                        index: 'CODIGO_CLIENTE',
                        width: 100,
                        hidden : true
                    },
                        { name: 'CLIENTE',
                        index: 'CLIENTE',
                        label: 'CLIENTE',
                        width: 170,
                        hidden : false,
                        align: 'left'
                    },
                    { 
                        name: 'CODIGO_GESTOR',
                        index: 'CODIGO_GESTOR',
                        label: 'GESTOR',
                        width: 70,
                        hidden : true
                    }, 
                    { 
                        name: 'GESTOR',
                        index: 'GESTOR',
                        label: 'GESTOR',
                        width: 110,
                        hidden : false,
                        align: 'left'
                    }, 
                    { 
                        name: 'CODIGO_USUARIO',
                        index: 'CODIGO_USUARIO',
                        label: 'USUARIO',
                        width: 100,
                        hidden : true
                    }, 
                    // { 
                    //     name: 'ID_USUARIO',
                    //     index: 'ID_USUARIO',
                    //     label: 'USUARIO',
                    //     width: 55,
                    //     hidden : false
                    // }, 
                    { 
                        name: 'ESTADO',
                        index: 'ESTADO',
                        label: 'ESTADO',
                        width: 70,
                        hidden : false,
                        align: 'center'
                    }, { 
                        name: 'CANTIDAD_GESTIONES',
                        index: 'CANTIDAD_GESTIONES',
                        label: 'GESTION',
                        width: 50,
                        hidden : false,
                        align: 'right',
                        formatter: 'number',
                formatoptions:{thousandsSeparator: ".", decimalPlaces:2},
                    }, { 
                        name: 'CANTIDAD_MINUTOS',
                        index: 'CANTIDAD_MINUTOS',
                        label: 'MINUTOS',
                        width: 50,
                        hidden : false,
                        align: 'right',
                        formatter: 'number',
                formatoptions:{thousandsSeparator: ".", decimalPlaces:0},
                    }, { 
                        name: 'OBSERVACION',
                        index: 'OBSERVACION',
                        label: 'OBSERVACION',
                        width: 185,
                        hidden : false,
                        classes:'wrapColumnText'
                    }
                    , { 
                        name: 'CODIGO_PLAN',
                        index: 'CODIGO_PLAN',
                        // label: 'OBSERVACION',
                        width: 65,
                        hidden : true
                    }
                    , { 
                        name: 'DESCRIPCION_PLAN',
                        index: 'DESCRIPCION_PLAN',
                        label: 'PLAN',
                        width: 120,
                        hidden : true
                    }
                ],
                emptyrecords: "Sin Datos",
                shrinkToFit:true,
                pager:"#paginadorClientes",
                viewrecords: true,
                gridview: false,
                hidegrid: false,
                altRows: true,
                ondblClickRow: function(rowid) {
                       var rowdata=  jQuery(this).jqGrid('getRowData', rowid);
                        modalModificar(rowdata);
                },
                onSelectRow: function (id, status, e) {
                    // $scope.$apply($scope.secOrdSeleccionado= id);
                    // $scope.buscarDetalleSolicitud();
                    // $scope.buscarFacturaReferenciada();
                },
                loadError: function(xhr, status, error ){
                    if (xhr && xhr.status===404) {
                        // $scope.solicitudAjustesGrid.clearGridData();
                    } else{
                        // $scope.alertErrorService.addSimpleAlert("operationFailure", 0, error.toString());
                        // $scope.$apply();
                    }
                  }});
    $("#grillaGestiones").setGridWidth(widthOfGrid());   
}

function buscar(){
    
    $("#grillaGestiones").jqGrid('setGridParam', { datatype: "json", postData: {
                   filtros: JSON.stringify({ "DESCRIPCION_CLIENTE": $("#cliente-filtro").val(), 
                                             "NRO_DOCUMENTO_PERSONA": $("#documentocliente-filtro").val(),
                                             "ESTADO_GESTION": $("#estadogestion-filtro").val(),
                                            "ASISTENTE": $("#gestor-filtro").val()}),
                }}).trigger("reloadGrid");
}

function modalModificar(rowData){
    

cargarCliente();
cargarAsistenteServicios();
  bloqueardatos(true);
 $('#codigogestion-modal').attr("value",rowData.NUMERO_GESTION);
 // $('#cliente-modal').select2(rowData.CODIGO_CLIENTE);
 $("#cliente-modal").select2("val", rowData.CODIGO_CLIENTE);
 // $('#cliente-modal').select2("3");
 // $('#nombrecliente-modal').attr("value",rowData.DESCRIPCION_PERSONA_CLIENTE);
 $('#fechagestion-modal').attr("value",rowData.FECHA_GESTION);
 $('#tarea-modal').attr("value",rowData.OBSERVACION);
 $("#iniciogestion-modal").attr("value",rowData.FECHA_INICIO);
 $("#fingestion-modal").attr("value",rowData.FECHA_FIN);
 $("#tiempoestimado-modal").val(rowData.CANTIDAD_MINUTOS);
 $("#cantidadgestion-modal").val(rowData.CANTIDAD_GESTIONES);
 $("#asistenteservicios-modal").select2("val",rowData.CODIGO_GESTOR);
 $("#estado-modal").val(rowData.ESTADO);
// $("#planactivo-modal").select2("val",rowData.CODIGO_PLAN);
if (rowData.ESTADO == 'A') {
    $("#guardar-modal").attr("disabled",true);
}else{
    $("#guardar-modal").attr("disabled",false);
}
getClienteSuscripcion();

$("#modalNuevo").show();


}
