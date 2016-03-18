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
// console.log('hola'+ $("#estadopersona-modal").val());
function cargarGrillaRegistro() {
    jQuery("#grillaDestinos").jqGrid({
        
                url: table+"/buscar" ,
                datatype: "local",
                mtype : "POST",
                height: 280,
                rowNum: 15,
                autowith: true,
                rowList: [],
                
                colModel:[
                           {
                            name: 'COD_DESTINO',
                            index: 'COD_DESTINO',
                            label: 'CODIGO DESTINO',
                            hidden :false,
                            width: 100,
                            align: 'right'
                            },
                            { name: 'DESTINO',
                            index: 'DESTINO',
                            label: 'DESCRIPCIÓN',
                            width: 400,
                            hidden : false,
                            align: 'left'
                        },
                            { name: 'CODIGO_ZONA',
                            index: 'CODIGO_ZONA',
                            label: 'CÓD ZONA',
                            width: 150,
                            hidden : false,
                            align: 'center'
                        }, { 
                            name: 'DESCRIPCION_ZONA',
                            index: 'DESCRIPCION_ZONA',
                            label: 'DESCRIPCIÓN ZONA',
                            width: 150,
                            hidden : false

                        },{ 
                            name: 'UBICACION',
                            index: 'UBICACION',
                            label: 'UBICACION',
                            width: 300,
                            hidden : false,
                            align: 'center'
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
    $("#grillaDestinos").setGridWidth(widthOfGrid());   
}

function buscar(){
    
    $("#grillaDestinos").jqGrid('setGridParam', { datatype: "json", postData: {
                   filtros: JSON.stringify({ "DESTINO": $("#destino-filtro").val()}),
                }}).trigger("reloadGrid");
}

function modalModificar(rowData){

    $("#codigodestino-modal").attr("value", rowData.COD_DESTINO);
    $("#descripcion-modal").attr("value", rowData.DESTINO);
     $("#zona-modal").select2("val", rowData.CODIGO_ZONA);
    $("#ubicacion-modal").attr("value", rowData.UBICACION);
    $("#modalNuevo").show();


}
