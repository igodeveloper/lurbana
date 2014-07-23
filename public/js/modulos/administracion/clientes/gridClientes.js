var pathname = window.location.pathname;
var table = pathname;
jQuery(document).ready(function(){
	console.log("cargue");
	cargarGrillaRegistro();
     $("#muestramodal").click(function() {
            $("#modalFormaPago").show();
            console.log("HOLA");
    }); 
     $("#close-modal").click(function() {
            $("#modalFormaPago").hide();
            console.log("HOLA");
    });
   
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
	jQuery("#grillaClientes").jqGrid({
        
                url: "/listar" ,
                datatype: "local",
                autowith: true,
                rowNum: 10,
                rowList: [],
                postData: {
                   cantidad: function() {
                       return $scope.solicitudAjustesGrid.getGridParam("rowNum");
                   },
                   inicio: function() {
                       return $scope.solicitudAjustesGrid.getRowsStart();
                   },
                },
                colModel:[
                            {
                            name: 'CODIGO_CLIENTE',
                            index: 'CODIGO_CLIENTE',
                            label: 'COD CLIENTE',
                            hidden :false,
                            width: 100,
                            align: 'right'
                            },
                            { name: 'CODIGO_PERSONA',
                            index: 'CODIGO_PERSONA',
                            width: 100,
                            hidden : true
                        },
                            { name: 'DESCRIPCION_PERSONA',
                            index: 'DESCRIPCION_PERSONA',
                            label: 'DESCRIPCION',
                            width: 300,
                            hidden : false
                        },
                            { name: 'NRO_DOCUMENTO_PERSONA',
                            index: 'NRO_DOCUMENTO_PERSONA',
                            label: 'DOCUMENTO',
                            width: 140,
                            hidden : false
                        },
                        { 
                            name: 'TELEFONO_PERSONA',
                            index: 'TELEFONO_PERSONA',
                            label: 'TELEFONO',
                            width: 150,
                            hidden : false
                        }, { 
                            name: 'EMAIL_PERSONA',
                            index: 'EMAIL_PERSONA',
                            label: 'EMAIL',
                            width: 200,
                            hidden : false
                        }, { 
                            name: 'DIRECCION_PERSONA',
                            index: 'DIRECCION_PERSONA',
                            label: 'DIRECCION',
                            width: 300,
                            hidden : false
                        },


                            { name: 'fecOrd',
                            index: 'fecOrd',
                            hidden: true,
                            align: 'center',
                            width: 100,
                            formatter: function(cellValue, options) {
                                    if (cellValue) {
                                        return $filter('date')(new Date(cellValue), 'dd/MM/yyyy');
                                    } else {
                                        return '';
                                    }
                                 } }
                            

                ],
                jsonReader:{
                    repeatitems: false,
                    id:"Id",
                    root:"lista",
                    total:function(data) {
                        var total = Math.ceil(data.totalDatos /$scope.solicitudAjustesGrid.getGridParam("rowNum"));

                        return total;
                    },
                    records: "totalDatos",
                    id:"secOrd"
                },
                emptyrecords: "Sin Datos",
                shrinkToFit:false,
                pager:"#paginadorClientes",
                viewrecords: true,
                gridview: false,
                hidegrid: false,
                altRows: true,
                onSelectRow: function (id, status, e) {
                    $scope.$apply($scope.secOrdSeleccionado= id);
                    $scope.buscarDetalleSolicitud();
                    $scope.buscarFacturaReferenciada();
                },
                loadError: function(xhr, status, error ){
                    if (xhr && xhr.status===404) {
                        $scope.solicitudAjustesGrid.clearGridData();
                    } else{
                        $scope.alertErrorService.addSimpleAlert("operationFailure", 0, error.toString());
                        $scope.$apply();
                    }
                  }});
            $("#grillaClientes").setGridWidth(widthOfGrid());	
}

