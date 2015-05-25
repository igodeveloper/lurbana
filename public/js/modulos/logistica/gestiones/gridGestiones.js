var pathname = window.location.pathname;
var table = pathname;
jQuery(document).ready(function(){
    cargarGrillaRegistro();
    $('#modalNuevo-track').bind('hidden.bs.modal', function () {
        $("html").css("margin-right", "0px");
    });
    $('#modalNuevo-track').bind('show.bs.modal', function () {
          $("html").css("margin-right", "-15px");
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
                        width: 180,
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
                    }, { 
                        name: 'GENTILEZA',
                        index: 'GENTILEZA',
                        label: 'GENTILEZA',
                        width: 120,
                        hidden : true
                    }, {
                        title: false,
                        name: '',
                        label: "",
                        id: 'modificar',
                        align: 'center',
                        edittype: 'link',
                        width: 40,
                        hidden: false,
                        classes: 'linkjqgrid',
                        sortable: false,
                        formatter: cargarLinkModificar
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

function cargarLinkModificar(cellvalue, options, rowObject)
{
    var parametros = new Object();
    parametros.NUMERO_GESTION = rowObject[0];

    json = JSON.stringify(parametros);
    return "<button type='button' class='btn btn-success' onclick='track("+json+")'><i class='icon icon-list'></i></button>";
}

function buscar(){
    
    $("#grillaGestiones").jqGrid('setGridParam', { datatype: "json", postData: {
                   filtros: JSON.stringify({ "DESCRIPCION_CLIENTE": $("#cliente-filtro").val(), 
                                             "NRO_DOCUMENTO_PERSONA": $("#documentocliente-filtro").val(),
                                             "ESTADO_GESTION": $("#estadogestion-filtro").val(),
                                            "ASISTENTE": $("#gestor-filtro").val()}),
                }}).trigger("reloadGrid");
}

function track(param){
    $("#modalNuevo-track").show();
    var jsonReporte = new Object(); 
    jsonReporte.NUMERO_GESTION = param.NUMERO_GESTION;
    var dataString = JSON.stringify(jsonReporte); 

    $.ajax({
        url: table+'/getlistatrack',
        type: 'GET',
        data: {"parametros":dataString},
        dataType: 'json',
        async : false,
        success: function(respuesta){
            if(typeof respuesta.success == 'undefined' && !respuesta.success){
                $('#table_tbody_track tr').remove();
                $.each(respuesta, function( index, value ) {
                  // alert( index + ": " + value.FECHA_GESTION );
                  $('#track > tbody:last').append('<tr><td id="orden-'+value.ORDEN+'">'+value.ORDEN+'</td><td id="codigo-zona-'+value.ORDEN+'">'
                    +value.CODIGO_ZONA+'</td><td id="descripcion-zona-'+value.ORDEN+'">'+value.DESCRIPCION_ZONA+'</td><td id="descripcion-'+value.ORDEN+'">'
                    +value.DESCRIPCION+'</td><td id="realizado-'+value.ORDEN+'">'+value.REALIZADO+'</td><td id="fec-hora-'+value.ORDEN+'">'
                    +value.FEC_HORA_REALIZ+
                    '</td><td><div class="btn-group"><button type="button" class="btn btn-warning" onclick="editarTrack('+value.ORDEN+','+value.CODIGO_ZONA+','+value.DESCRIPCION_ZONA+','+value.REALIZADO+','+value.FEC_HORA_REALIZ+')">Editar</button><button type="button" class="btn btn-danger" onclick="borrartrack('+value.ORDEN+')">Borrar</button><div></td></tr>');
                });
            }
        },
        error: function(event, request, settings){
         //   $.unblockUI();
             // alert(mostrarError("OcurrioError"));
        }
    }); 
    
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
 // $("#gentileza-modal").val(rowData.GENTILEZA);

// $("#planactivo-modal").select2("val",rowData.CODIGO_PLAN);
if (rowData.ESTADO == 'A') {
    $("#guardar-modal").attr("disabled",true);
}else{
    $("#guardar-modal").attr("disabled",false);
}
if (rowData.GENTILEZA == 'S') {
    $( "#gentileza-modal" ).prop( "checked", true );
}else{
    $( "#gentileza-modal" ).prop( "checked", false );
}
// getClienteSuscripcion();

$("#modalNuevo").show();
}
