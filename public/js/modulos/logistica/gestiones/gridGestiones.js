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
    var gridWidth = ((90 * 94 * windowsWidth) / (100 * 100));
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
                rowNum: 8,
                rowList: [],
                
                colModel:[
                    {
                        name: 'NUMERO_GESTION',
                        index: 'NUMERO_GESTION',
                        label: 'NRO',
                        hidden :false,
                        width: 50,
                        align: 'right'
                    },{
                        name: 'FECHA_GESTION',
                        index: 'FECHA_GESTION',
                        label: 'FECHA',
                        hidden :false,
                        width: 120,
                        align: 'right'
                    },{
                        name: 'FECHA_INICIO',
                        index: 'FECHA_INICIO',
                        label: 'INICIO',
                        hidden :false,
                        width: 140,
                        align: 'right'
                    },{ 
                        name: 'FECHA_FIN',
                        index: 'FECHA_FIN',
                        label: 'FIN',
                        width: 140,
                        hidden : false
                    },
                        { name: 'CODIGO_CLIENTE',
                        index: 'CODIGO_CLIENTE',
                        width: 100,
                        hidden : true
                    },
                        { name: 'DESCRIPCION_PERSONA_CLIENTE',
                        index: 'DESCRIPCION_PERSONA_CLIENTE',
                        label: 'CLIENTE',
                        width: 200,
                        hidden : false
                    },
                    { 
                        name: 'CODIGO_GESTOR',
                        index: 'CODIGO_GESTOR',
                        label: 'GESTOR',
                        width: 70,
                        hidden : true
                    }, 
                    // { 
                    //     name: 'DESCRIPCION_PERSONA_GESTOR',
                    //     index: 'DESCRIPCION_PERSONA_GESTOR',
                    //     label: 'GESTOR',
                    //     width: 100,
                    //     hidden : false
                    // }, 
                    { 
                        name: 'CODIGO_USUARIO',
                        index: 'CODIGO_USUARIO',
                        label: 'USUARIO',
                        width: 100,
                        hidden : false
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
                        width: 80,
                        hidden : false
                    }, { 
                        name: 'CANTIDAD_GESTIONES',
                        index: 'CANTIDAD_GESTIONES',
                        label: 'TIEMPO',
                        width: 100,
                        hidden : false
                    }, { 
                        name: 'CANTIDAD_ADICIONALES',
                        index: 'CANTIDAD_ADICIONALES',
                        label: 'GESTION',
                        width: 100,
                        hidden : false
                    }, { 
                        name: 'OBSERVACION',
                        index: 'OBSERVACION',
                        label: 'OBSERVACION',
                        width: 65,
                        hidden : true
                    }
                ],
                emptyrecords: "Sin Datos",
                shrinkToFit:false,
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
                   filtros: JSON.stringify({ "DESCRIPCION_PERSONA": $("#descripcionpersona-filtro").val(), 
                                             "ESTADO_CLIENTE": $("#estadopersona-filtro").val(),
                                             "TELEFONO_PERSONA": $("#telefonopersona-filtro").val(),
                                            "NRO_DOCUMENTO_PERSONA": $("#numerodocumentopersona-filtro").val()}),
                }}).trigger("reloadGrid");
}

function modalModificar(rowData){

 $('#codigogestion-modal').attr("value",rowData.NUMERO_GESTION);
 $('#codigocliente-modal').attr("value",rowData.CODIGO_CLIENTE);
 $('#nombrecliente-modal').attr("value",rowData.DESCRIPCION_PERSONA_CLIENTE);
 $('#fechagestion-modal').attr("value",rowData.FECHA_GESTION);
 $('#tarea-modal').attr("value",rowData.OBSERVACION);
 $("#iniciogestion-modal").attr("value",rowData.FECHA_INICIO);
 $("#fingestion-modal").attr("value",rowData.FECHA_FIN);
 $("#tiempoestimado-modal").val(rowData.CANTIDAD_GESTIONES);
 $("#cantidadgestion-modal").val(rowData.CANTIDAD_ADICIONALES);
 $("#gestor-modal").val(rowData.CODIGO_GESTOR);
 $("#estado-modal").val(rowData.ESTADO);
    $("#modalNuevo").show();


}
