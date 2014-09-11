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
console.log('hola'+ $("#estadopersona-modal").val());
function cargarGrillaRegistro() {
    jQuery("#grillaPlanes").jqGrid({
        
                url: table+"/buscar" ,
                datatype: "local",
                mtype : "POST",
                autowith: true,
                rowNum: 8,
                rowList: [],
                
                colModel:[
                           {
                            name: 'CODIGO_PLAN',
                            index: 'CODIGO_PLAN',
                            label: 'CODIGO',
                            hidden :false,
                            width: 100,
                            align: 'right'
                            },
                            { name: 'DESCRIPCION_PLAN',
                            index: 'DESCRIPCION_PLAN',
                            label: 'PLAN',
                            width: 400,
                            hidden : false,
                            align: 'left'
                        }, { 
                            name: 'CANTIDAD_PLAN',
                            index: 'CANTIDAD_PLAN',
                            label: 'CANTIDAD',
                            width: 150,
                            hidden : false,
                        formatter: 'number',
                formatoptions:{thousandsSeparator: ".", decimalPlaces:0},
                align: 'right'

                        }, { 
                            name: 'COSTO_PLAN',
                            index: 'COSTO_PLAN',
                            label: 'COSTO',
                            width: 150,
                            hidden : false,
                        formatter: 'number',
                formatoptions:{thousandsSeparator: ".", decimalPlaces:0},align: 'right'
                        }, { 
                            name: 'ESTADO_PLAN',
                            index: 'ESTADO_PLAN',
                            label: 'ESTADO',
                            width: 150,
                            hidden : false,align: 'center'
                        }
                ],
                emptyrecords: "Sin Datos",
                shrinkToFit:false,
                pager:"#paginadorPlanes",
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
    $("#grillaPlanes").setGridWidth(widthOfGrid());   
}

function buscar(){
    
    $("#grillaPlanes").jqGrid('setGridParam', { datatype: "json", postData: {
                   filtros: JSON.stringify({ "DESCRIPCION_PLAN": $("#descripcionplan-filtro").val()}),
                }}).trigger("reloadGrid");
}

function modalModificar(rowData){

    $('#codigoplan-modal').attr("value", rowData.CODIGO_PLAN);
    $('#descripcionplan-modal').attr("value", rowData.DESCRIPCION_PLAN);
    $("#cantidadplan-modal").attr("value",rowData.CANTIDAD_PLAN);
    $("#costoplan-modal").attr("value",rowData.COSTO_PLAN);
    $("#estadoplan-modal").val(rowData.ESTADO_PLAN);
    $("#modalNuevo").show();


}
