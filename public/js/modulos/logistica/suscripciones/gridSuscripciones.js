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
    jQuery("#grillaSuscripciones").jqGrid({
        
                url: table+"/buscar",
                datatype: "local",
                mtype : "POST",
                height: 280,
                rowNum: 15,
                autowith: false,
                rowList: [],
                
                colModel:[
                    {
                        name: 'CODIGO_SUSCRIPCION',
                        index: 'CODIGO_SUSCRIPCION',
                        label: 'CODIGO',
                        hidden :false,
                        width: 50,
                        align: 'right'
                    },
                    {   
                        name: 'CODIGO_CLIENTE',
                        index: 'CODIGO_CLIENTE',
                        width: 100,
                        hidden : true
                    },
                        { name: 'DESCRIPCION_CLIENTE',
                        index: 'DESCRIPCION_CLIENTE',
                        label: 'CLIENTE',
                        width: 170,
                        hidden : false,
                        align: 'left'
                    },
                    { 
                        name: 'CODIGO_PLAN',
                        index: 'CODIGO_PLAN',
                        width: 70,
                        hidden : true
                    }, 
                    { 
                        name: 'DESCRIPCION_PLAN',
                        index: 'DESCRIPCION_PLAN',
                        label: 'PLAN',
                        width: 140,
                        hidden : false,
                        align: 'left'
                    }, 
                    { 
                        name: 'CANTIDAD_PLAN',
                        index: 'CANTIDAD_PLAN',
                        label: 'PLAN',
                        width: 140,
                        hidden : true,
                        align: 'left'
                    }, 
                    { 
                        name: 'TIPO_PLAN',
                        index: 'TIPO_PLAN',
                        label: 'PLAN',
                        width: 140,
                        hidden : true,
                        align: 'left'
                    },{
                        name: 'FECHA_SUSCRIPCION',
                        index: 'FECHA_SUSCRIPCION',
                        label: 'FECHA',
                        hidden :false,
                        width: 100,
                        align: 'center'
                    },{
                        name: 'FECHA_VENCIMIENTO',
                        index: 'FECHA_VENCIMIENTO',
                        label: 'VENCIMIENTO',
                        hidden :false,
                        width: 140,
                        align: 'center'
                    },{ 
                        name: 'FECHA_ACREDITACION',
                        index: 'FECHA_ACREDITACION',
                        label: 'ACREDITACION',
                        width: 140,
                        hidden : false,
                        align: 'center'    
                    },{ 
                        name: 'IMPORTE_GESTION',
                        index: 'IMPORTE_GESTION',
                        label: 'IMPORTE',
                        width: 70,
                        hidden : false,
                        align: 'right',
                        formatter: 'number',
                formatoptions:{thousandsSeparator: ".", decimalPlaces:0}
                    },{ 
                        name: 'ESTADO_SUSCRIPCION',
                        index: 'ESTADO_SUSCRIPCION',
                        label: 'ESTADO',
                        width: 120,
                        hidden : false
                    }
                ],
                emptyrecords: "Sin Datos",
                shrinkToFit:true,
                pager:"#paginadorSuscripciones",
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
    $("#grillaSuscripciones").setGridWidth(widthOfGrid());   
}

function buscar(){
    
    $("#grillaSuscripciones").jqGrid('setGridParam', { datatype: "json", postData: {
                   filtros: JSON.stringify({ "DESCRIPCION_PERSONA": $("#descripcionpersona-filtro").val(), 
                                             "ESTADO_SUSCRIPCION": $("#estadosuscripcion-filtro").val(),
                                             "DESCRIPCION_PLAN": $("#planactivo-filtro").val(),
                                            "FECHA_VENCIMIENTO": $("#fechavencimiento-filtro").val()}),
                }}).trigger("reloadGrid");
}

function modalModificar(rowData){
cargarCliente();
cargarPlanesActivos(rowData.TIPO_PLAN,rowData.CANTIDAD_PLAN);

 $('#codigosuscripcion-modal').attr("value",rowData.CODIGO_SUSCRIPCION);
 $('#codigosuscripcion-modal').attr("disabled",true);
 $("#cliente-modal").select2("val", rowData.CODIGO_CLIENTE);
 
 $("#importegestion-modal").val(rowData.IMPORTE_GESTION);
 $("#estadosuscripcion-modal").val(rowData.ESTADO_SUSCRIPCION);
 $("#planactivo-modal").select2("val",rowData.CODIGO_PLAN);
    if(rowData.TIPO_PLAN == 'M'){
        $("#planactivo-modal").attr("disabled",false);
        seteaFechas();
    }else{
        $('#fechasuscripcion-modal').attr("value",rowData.FECHA_SUSCRIPCION);
        $('#fechavencimiento-modal').attr("value",rowData.FECHA_VENCIMIENTO);
        $('#fechaacreditacion-modal').attr("value",rowData.FECHA_ACREDITACION);
        bloqueardatos(true);
    }
$("#modalNuevo").show();


}
