var pathname = window.location.pathname;
var table = pathname;
jQuery(document).ready(function(){
    // cargarGrillaRegistro();
    cargarGrillaRegistro();
    // cargarGrillaDetalle();


   
});

/**
 * Carga la tabla visual con el listado de registros. La estructura de la tabla es especificada.
 */
function cargarGrillaRegistro() {
    jQuery("#grillaDetalle").jqGrid({
                datatype: "local",
                mtype : "POST",
                autowith: true,
                // height: 300,
                rowNum: 1000,
                rowList: [],
                multiselect: true,
                
                colModel:[
                           {
                            name: 'CODIGO_SALDO',
                            index: 'CODIGO_SALDO',
                            label: 'CODIGO',
                            hidden :false,
                            width: 100,
                            align: 'right'
                        },{
                            name: 'CODIGO_SUSCRIPCION',
                            index: 'CODIGO_SUSCRIPCION',
                            label: 'CODIGO_SUSCRIPCION',
                            hidden :true,
                            // width: 70,
                            align: 'right'
                        },{
                            name: 'CODIGO_CLIENTE',
                            index: 'CODIGO_CLIENTE',
                            label: 'CODIGO_CLIENTE',
                            hidden :true,
                            // width: 70,
                            align: 'right'
                        },{
                            name: 'NOMBRE',
                            index: 'NOMBRE',
                            label: 'NOMBRE',
                            hidden :true,
                            // width: 300,
                            align: 'right'
                        },{
                            name: 'DESCRIPCION_PLAN',
                            index: 'DESCRIPCION_PLAN',
                            label: 'DESCRIPCION_PLAN',
                            hidden :false,
                            width: 300,
                            align: 'left'
                        },{
                            name: 'TIPO_PLAN',
                            index: 'TIPO_PLAN',
                            label: 'TIPO_PLAN',
                            hidden :true,
                            // width: 70,
                            align: 'right'
                        },{
                            name: 'FECHA_SALDO',
                            index: 'FECHA_SALDO',
                            label: 'FECHA_SALDO',
                            hidden :false,
                            width: 120,
                            align: 'right'
                        },{
                            name: 'IMPORTE_SALDO',
                            index: 'IMPORTE_SALDO',
                            label: 'IMPORTE_SALDO',
                            hidden :false,
                            width: 200,
                            align: 'right'
                        }
                        

                ],
                emptyrecords: "Sin Datos",
                shrinkToFit:false,
                // pager:"#paginadorDetalle",
                viewrecords: true,
                onSelectRow: function (id, status, e) {
                    
                },
                loadError: function(xhr, status, error ){
                    if (xhr && xhr.status===404) {
                        // $scope.solicitudAjustesGrid.clearGridData();
                    } else{
                        // $scope.alertErrorService.addSimpleAlert("operationFailure", 0, error.toString());
                        // $scope.$apply();
                    }
                  }});  
}

function cargarGrillaDetalle() {
    jQuery("#grillaDetalleFactura").jqGrid({
                datatype: "local",
                mtype : "POST",
                autowith: false,
                // height: 300,
                rowList: [],
                multiselect: true,
                
                colModel:[
                           {
                            name: 'CODIGO_SALDO',
                            index: 'CODIGO_SALDO',
                            label: 'CODIGO',
                            hidden :false,
                            // width: 100,
                            align: 'right'
                        },{
                            name: 'CODIGO_SUSCRIPCION',
                            index: 'CODIGO_SUSCRIPCION',
                            label: 'CODIGO_SUSCRIPCION',
                            hidden :true,
                            // width: 70,
                            align: 'right'
                        },{
                            name: 'CODIGO_CLIENTE',
                            index: 'CODIGO_CLIENTE',
                            label: 'CODIGO_CLIENTE',
                            hidden :true,
                            // width: 70,
                            align: 'right'
                        },{
                            name: 'NOMBRE',
                            index: 'NOMBRE',
                            label: 'NOMBRE',
                            // hidden :true,
                            // width: 300,
                            align: 'right'
                        },{
                            name: 'DESCRIPCION_PLAN',
                            index: 'DESCRIPCION_PLAN',
                            label: 'DESCRIPCION_PLAN',
                            hidden :false,
                            // width: 300,
                            align: 'left'
                        },{
                            name: 'TIPO_PLAN',
                            index: 'TIPO_PLAN',
                            label: 'TIPO_PLAN',
                            hidden :true,
                            // width: 70,
                            align: 'right'
                        },{
                            name: 'FECHA_SALDO',
                            index: 'FECHA_SALDO',
                            label: 'FECHA_SALDO',
                            hidden :false,
                            // width: 150,
                            align: 'right'
                        },{
                            name: 'IMPORTE_SALDO',
                            index: 'IMPORTE_SALDO',
                            label: 'IMPORTE_SALDO',
                            hidden :false,
                            // width: 200,
                            align: 'right'
                        }
                        

                ],
                emptyrecords: "Sin Datos",
                shrinkToFit:false,
                // pager:"#paginadorDetalle",
                viewrecords: true,
                onSelectRow: function (id, status, e) {
                    
                },
                loadError: function(xhr, status, error ){
                    if (xhr && xhr.status===404) {
                        // $scope.solicitudAjustesGrid.clearGridData();
                    } else{
                        // $scope.alertErrorService.addSimpleAlert("operationFailure", 0, error.toString());
                        // $scope.$apply();
                    }
                  }});
}

