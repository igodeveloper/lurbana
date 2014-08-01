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
        
                url: table+"/buscar" ,
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
                        width: 70,
                        align: 'right'
                    },
                        {name: 'FECHA_INICIO',
                        index: 'FECHA_INICIO',
                        label: 'INICIO',
                        hidden :false,
                        width: 70,
                        align: 'right'
                    },
                        { name: 'FECHA_FIN',
                        index: 'FECHA_FIN',
                        label: 'FIN',
                        width: 180,
                        hidden : false
                    },
                        { name: 'CODIGO_CLIENTE',
                        index: 'CODIGO_CLIENTE',
                        label: '',
                        width: 100,
                        hidden : false
                    },
                        { name: 'DESCRIPCION_PERSONA_CLIENTE',
                        index: 'DESCRIPCION_PERSONA_CLIENTE',
                        label: 'CLIENE',
                        width: 90,
                        hidden : false
                    },
                    { 
                        name: 'CODIGO_GESTOR',
                        index: 'CODIGO_GESTOR',
                        width: 110,
                        hidden : false
                    }, { 
                        name: 'DESCRIPCION_PERSONA_GESTOR',
                        index: 'DESCRIPCION_PERSONA_GESTOR',
                        label: 'GESTOR',
                        width: 130,
                        hidden : false
                    }, { 
                        name: 'CODIGO_USUARIO',
                        index: 'CODIGO_USUARIO',
                        label: 'CODIGO_USUARIO',
                        width: 200,
                        hidden : false
                    }, { 
                        name: 'ID_USUARIO',
                        index: 'ID_USUARIO',
                        label: 'USUARIO',
                        width: 55,
                        hidden : false
                    }, { 
                        name: 'ESTADO',
                        index: 'ESTADO',
                        label: 'ESTADO',
                        width: 60,
                        hidden : false
                    }, { 
                        name: 'CANTIDAD_GESTIONES',
                        index: 'CANTIDAD_GESTIONES',
                        label: 'CANTIDAD_GESTIONES',
                        width: 65,
                        hidden : false
                    }, { 
                        name: 'CANTIDAD_ADICIONALES',
                        index: 'CANTIDAD_ADICIONALES',
                        label: 'CANTIDAD_ADICIONALES',
                        width: 65,
                        hidden : false
                    }, { 
                        name: 'OBSERVACION',
                        index: 'OBSERVACION',
                        label: 'OBSERVACION',
                        width: 65,
                        hidden : false
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
    $("#grillaClientes").setGridWidth(widthOfGrid());   
}

function buscar(){
    
    $("#grillaClientes").jqGrid('setGridParam', { datatype: "json", postData: {
                   filtros: JSON.stringify({ "DESCRIPCION_PERSONA": $("#descripcionpersona-filtro").val(), 
                                             "ESTADO_CLIENTE": $("#estadopersona-filtro").val(),
                                             "TELEFONO_PERSONA": $("#telefonopersona-filtro").val(),
                                            "NRO_DOCUMENTO_PERSONA": $("#numerodocumentopersona-filtro").val()}),
                }}).trigger("reloadGrid");
}

function modalModificar(rowData){

    $('#codigocliente-modal').attr("value", rowData.CODIGO_CLIENTE);
    $('#codigopersona-modal').attr("value", rowData.CODIGO_PERSONA);
    $("#descripcionpersona-modal").attr("value",rowData.DESCRIPCION_PERSONA);
    $("#numerodocumentopersona-modal").attr("value",rowData.NRO_DOCUMENTO_PERSONA);
    $("#rucpersona-modal").attr("value",rowData.RUC_PERSONA);
    $("#direccionpersona-modal").attr("value",rowData.TELEFONO_PERSONA);
    $("#telefonopersona-modal").attr("value",rowData.EMAIL_PERSONA);
    $("#emailpersona-modal").attr("value",rowData.DIRECCION_PERSONA);
    $("#ciudadpersona-modal").attr("value",rowData.CODIGO_CIUDAD);
    $("#barriopersona-modal").attr("value",rowData.CODIGO_BARRIO);
    $("#estadocliente-modal").attr("value",rowData.ESTADO_CLIENTE);
    $("#modalNuevo").show();


}
