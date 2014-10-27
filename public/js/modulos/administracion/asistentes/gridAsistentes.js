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
    jQuery("#grillaAsistente").jqGrid({
        
                url: table+"/buscar" ,
                datatype: "local",
                mtype : "POST",
                autowith: true,
                height: 280,
                rowNum: 15,
                rowList: [],
                
                colModel:[
                           {
                            name: 'CODIGO_GESTOR',
                            index: 'CODIGO_GESTOR',
                            label: 'CODIGO',
                            hidden :false,
                            width: 70,
                            align: 'right'
                            },
                            { name: 'CODIGO_PERSONA',
                            index: 'CODIGO_PERSONA',
                            width: 50,
                            hidden : true
                        },
                            { name: 'DESCRIPCION_PERSONA',
                            index: 'DESCRIPCION_PERSONA',
                            label: 'DESCRIPCION',
                            width: 180,
                            hidden : false
                        },
                            { name: 'NRO_DOCUMENTO_PERSONA',
                            index: 'NRO_DOCUMENTO_PERSONA',
                            label: 'DOCUMENTO',
                            width: 100,
                            hidden : false
                        },
                            { name: 'RUC_PERSONA',
                            index: 'RUC_PERSONA',
                            label: 'RUC',
                            width: 90,
                            hidden : false
                        },
                        { 
                            name: 'TELEFONO_PERSONA',
                            index: 'TELEFONO_PERSONA',
                            label: 'TELEFONO',
                            width: 110,
                            hidden : false
                        }, { 
                            name: 'EMAIL_PERSONA',
                            index: 'EMAIL_PERSONA',
                            label: 'EMAIL',
                            width: 130,
                            hidden : false
                        }, { 
                            name: 'DIRECCION_PERSONA',
                            index: 'DIRECCION_PERSONA',
                            label: 'DIRECCION',
                            width: 200,
                            hidden : false
                        }, { 
                            name: 'CODIGO_CIUDAD',
                            index: 'CODIGO_CIUDAD',
                            label: 'CIUDAD',
                            width: 55,
                            hidden : false
                        }, { 
                            name: 'CODIGO_BARRIO',
                            index: 'CODIGO_BARRIO',
                            label: 'BARRIO',
                            width: 60,
                            hidden : false
                        }, { 
                            name: 'ESTADO_GESTOR',
                            index: 'ESTADO_GESTOR',
                            label: 'ESTADO',
                            width: 65,
                            hidden : false
                        }
                        

                ],
                emptyrecords: "Sin Datos",
                shrinkToFit:false,
                pager:"#paginadorAsistente",
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
    $("#grillaAsistente").setGridWidth(widthOfGrid());   
}

function buscar(){
    
    $("#grillaAsistente").jqGrid('setGridParam', { datatype: "json", postData: {
                   filtros: JSON.stringify({ "DESCRIPCION_PERSONA": $("#descripcionpersona-filtro").val(), 
                                             "ESTADO_GESTOR": $("#estadoasistente-filtro").val(),
                                             "TELEFONO_PERSONA": $("#telefonopersona-filtro").val(),
                                            "NRO_DOCUMENTO_PERSONA": $("#numerodocumentopersona-filtro").val()}),
                }}).trigger("reloadGrid");
}

function modalModificar(rowData){

    $('#codigoasistente-modal').attr("value", rowData.CODIGO_GESTOR);
    $('#codigopersona-modal').attr("value", rowData.CODIGO_PERSONA);
    $("#descripcionpersona-modal").attr("value",rowData.DESCRIPCION_PERSONA);
    $("#numerodocumentopersona-modal").attr("value",rowData.NRO_DOCUMENTO_PERSONA);
    $("#rucpersona-modal").attr("value",rowData.RUC_PERSONA);
    $("#direccionpersona-modal").attr("value",rowData.DIRECCION_PERSONA);
    $("#telefonopersona-modal").attr("value",rowData.TELEFONO_PERSONA);
    $("#emailpersona-modal").attr("value",rowData.EMAIL_PERSONA);
    $("#ciudadpersona-modal").attr("value",rowData.CODIGO_CIUDAD);
    $("#barriopersona-modal").attr("value",rowData.CODIGO_BARRIO);
    $("#estadoasistente-modal").attr("value",rowData.ESTADO_GESTOR);
    $("#modalNuevo").show();


}
