var pathname = window.location.pathname;
var table = pathname;
jQuery(document).ready(function(){
    cargarGrillaRegistroTrack();   
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


function cargarGrillaRegistroTrack() {
    jQuery("#grillaGestionesTrack").jqGrid({
                datatype: "local",
                mtype : "POST",
                autowith: false,
                height: 250,
                rowNum: 1000,
                rowList: [],
                
                 colModel:[
                    {
                        name: 'CODIGO_GESTION',
                        index: 'CODIGO_GESTION',
                        label: 'CODIGO_GESTION',
                        hidden :true,
                        width: 100,
                        align: 'right'
                    },{
                        name: 'ORDEN',
                        index: 'ORDEN',
                        label: 'ORDEN',
                        hidden :true,
                        width: 100,
                        align: 'right'
                    },{
                        name: 'PROCESO',
                        index: 'PROCESO',
                        label: 'PROCESO',
                        hidden :false,
                        width: 100,
                        align: 'right'
                    },{
                        name: 'CODIGO_ZONA',
                        index: 'CODIGO_ZONA',
                        label: 'CODIGO ZONA',
                        hidden :true,
                        width: 100,
                        align: 'center'
                    },{
                        name: 'DESCRIPCION_ZONA',
                        index: 'DESCRIPCION_ZONA', 
                        label: 'ZONA',
                        hidden :false,
                        width: 150,
                        align: 'left'
                    },{
                        name: 'DESTINO',
                        index: 'DESTINO',
                        label: 'DESTINO',
                        hidden :false,
                        width: 100,
                        align: 'left'
                    },{
                        name: 'DESCRIPCION',
                        index: 'DESCRIPCION',
                        label: 'DESCRIPCION',
                        hidden :false,
                        width: 350,
                        align: 'left'
                    },{
                        name: 'REALIZADO',
                        index: 'REALIZADO',
                        label: 'REALIZADO',
                        hidden :false,
                        width: 100,
                        align: 'center'
                    },{
                        name: 'FEC_HORA_REALIZ',
                        index: 'FEC_HORA_REALIZ',
                        label: 'FECHA - HORA',
                        hidden :false,
                        width: 140,
                        align: 'right'
                    },{
                        name: 'HORA_ESTIMADA',
                        index: 'HORA_ESTIMADA',
                        label: 'HORA',
                        hidden :false,
                        width: 120,
                        align: 'right'
                    },{
                        name: 'MOTIVO_CANCEL',
                        index: 'MOTIVO_CANCEL',
                        label: 'MOTIVO_CANCEL',
                        hidden :true,
                        width: 150,
                        align: 'right'
                    },{
                        name: 'SYNC',
                        index: 'SYNC',
                        label: 'SYNC',
                        hidden :true,
                        width: 150,
                        align: 'right'
                    },{
                        name: 'LATITUD',
                        index: 'LATITUD',
                        label: 'LATITUD',
                        hidden :true,
                        width: 150,
                        align: 'right'
                    },{
                        name: 'LONGITUD',
                        index: 'LONGITUD',
                        label: 'LONGITUD',
                        hidden :true,
                        width: 150,
                        align: 'right'
                    },{
                        name: 'CODIGO_GESTOR',
                        index: 'CODIGO_GESTOR',
                        label: 'CODIGO_GESTOR',
                        hidden :true,
                        width: 150,
                        align: 'right'
                    },{
                        name: 'INICIO_ACTIVIDAD',
                        index: 'INICIO_ACTIVIDAD',
                        label: 'INICIO_ACTIVIDAD',
                        hidden :true,
                        width: 150,
                        align: 'right'
                    },{
                        name: 'FIN_ACTIVIDAD',
                        index: 'FIN_ACTIVIDAD',
                        label: 'FIN_ACTIVIDAD',
                        hidden :true,
                        width: 150,
                        align: 'right'
                    }, {
                        title: false,
                        name: '',
                        label: "",
                        id: 'modificar',
                        align: 'center',
                        edittype: 'link',
                        width: 100,
                        hidden: false,
                        classes: 'linkjqgrid',
                        sortable: false,
                        formatter: cargarLinkBorrar
                    }
                ],
                emptyrecords: "Sin Datos",
                shrinkToFit:true,
                viewrecords: true,
                gridview: false,
                hidegrid: false,
                altRows: true,
                ondblClickRow: function(rowid) {
                       var rowdata=  jQuery(this).jqGrid('getRowData', rowid);
                        //modalModificar(rowdata);
                }
              });  


}

function cargarLinkModificar(cellvalue, options, rowObject)
{
    var parametros = new Object();

    parametros.NUMERO_GESTION = rowObject[0];

    json = JSON.stringify(parametros);
    return "<button type='button' class='btn btn-success' onclick='track("+json+")'><i class='icon icon-list'></i></button>";
}

function cargarLinkBorrar(cellvalue, options, rowObject)
{   
    console.log(options);
    console.log(options.rowId);
    json = JSON.stringify(rowObject);
    return "<button type='button' class='btn btn-warning' onclick='editarActividad(\""+options.rowId+"\")'><i class='icon icon-edit'></i></button>&nbsp;&nbsp;<button type='button' class='btn btn-danger' onclick='borrarActividad(\""+options.rowId+"\")'><i class='icon icon-remove-circle'></i></button>";
}

function buscar(){
    
    $("#grillaGestiones").jqGrid('setGridParam', { datatype: "json", postData: {
                   filtros: JSON.stringify({ "DESCRIPCION_CLIENTE": $("#cliente-filtro").val(), 
                                             "NRO_DOCUMENTO_PERSONA": $("#documentocliente-filtro").val(),
                                             "ESTADO_GESTION": $("#estadogestion-filtro").val(),
                                            "ASISTENTE": $("#gestor-filtro").val()}),
                }}).trigger("reloadGrid");
}

function borrarActividad(rowid){
   console.log(rowid);
    $('#grillaGestionesTrack').jqGrid('delRowData',rowid);
}
function editarActividad(rowid){
   var param = $('#grillaGestionesTrack').jqGrid('getRowData', rowid);
    var realizado = (param.REALIZADO == 'Si' ? 1 : 0);
    $("#codigo-gestion-acti").attr("value", param.CODIGO_GESTION);
    $("#orden-acti").attr("value", param.ORDEN);
    $("#zona-acti").select2("val", param.CODIGO_ZONA);
    $("#proceso-acti").attr("value", param.PROCESO);
    $("#destino-acti").attr("value", param.DESTINO);
    $("#hora-estimada-acti").attr("value", param.HORA_ESTIMADA);
    $("#descripcion-acti").attr("value", param.DESCRIPCION);
    $("#realizado-acti").attr("value", realizado);
    $("#motivo-cancel-acti").attr("value", param.MOTIVO_CANCEL);
    $("#sync-acti").attr("value", param.SYNC);
    $("#latitud-acti").attr("value", param.LATITUD);
    $("#longitud-acti").attr("value", param.LONGITUD);
    $("#asistente-acti").attr("value", param.CODIGO_GESTOR);
    $("#inicio-actividad-acti").attr("value", param.INICIO_ACTIVIDAD);
    $("#hora-acti").attr("value", param.FEC_HORA_REALIZ);
    $("#fin-actividad-acti").attr("value", param.FIN_ACTIVIDAD);
    $("#modificar-acti").attr("value", rowid);
    mostrarFormularioActividad();
}
function ObtenerActi(){

    var jsonReporte = new Object();     
    var verificacion = true;
    if($("#proceso-acti").val().length < 1){
        mostrarVentana("warning-modal-acti","El numero de proceso no esta seteado");
        verificacion = false;
    }else if($("#zona-acti").val() < 0 ){
        mostrarVentana("warning-modal-acti","Seleccione una zona");
        verificacion = false;
    }else if($("#descripcion-acti").val().length < 1){
        mostrarVentana("warning-modal-acti","Ingrese una descripción de la actividad");
        verificacion = false;
    }else if($("#destino-acti").val().length < 1){
        mostrarVentana("warning-modal-acti","Ingrese un destino");
        verificacion = false;
    }else if($("#realizado-acti").val() < 0){
        mostrarVentana("warning-modal-acti","Seleccione si se realizo o no");
        verificacion = false;
    }
    if(verificacion){
        var realizado = ($("#realizado-acti").val() == 1 ? 'Si' :'No');
        jsonReporte.CODIGO_GESTION = $("#codigo-gestion-acti").val();
        jsonReporte.ORDEN = $("#orden-acti").val();
        jsonReporte.PROCESO = $("#proceso-acti").val();
        jsonReporte.CODIGO_ZONA = $("#zona-acti").select2('data').id;
        jsonReporte.DESCRIPCION_ZONA = $("#zona-acti").select2('data').text;
        jsonReporte.DESTINO = $("#destino-acti").val();
        jsonReporte.HORA_ESTIMADA = $("#hora-estimada-acti").val();
        jsonReporte.DESCRIPCION = $("#descripcion-acti").val();
        jsonReporte.REALIZADO = realizado;
        jsonReporte.FEC_HORA_REALIZ = $("#hora-acti").val();
        jsonReporte.MOTIVO_CANCEL = $("#motivo-cancel-acti").val();
        jsonReporte.SYNC = $("#sync-acti").val();
        jsonReporte.LATITUD = $("#latitud-acti").val();
        jsonReporte.LONGITUD = $("#longitud-acti").val();
        jsonReporte.CODIGO_GESTOR = $("#asistenteservicios-modal").select2('data').id;
        jsonReporte.INICIO_ACTIVIDAD = $("#inicio-actividad-acti").val();
        jsonReporte.FIN_ACTIVIDAD = $("#fin-actividad-acti").val();
        return jsonReporte;
    }else{
        return false;
        mostrarVentana("warning-modal-acti","Ocurrio un error inesperado, verifique los datos a ingresar");
    }




}
function guardarActividad(){
    //$("#modalNuevo-track").show();

    var objetoActi = ObtenerActi();
    if(objetoActi){ 
        var myarray = new Array();
        myarray.push(objetoActi);
        if($("#modificar-acti").val()){
            borrarActividad($("#modificar-acti").val());
        }
        addRowDataGrilla("grillaGestionesTrack",myarray);
        $("#form").hide();
        $("#grid").show();
        $("#mostrar-modal-actividad").show();
        
    }
    
}


function addRowDataGrilla(grilla,arrayObjetos){
        var grid = jQuery("#"+grilla);
        for (i=0;i<arrayObjetos.length;i++) {
            var id;
            grid.jqGrid('addRowData', id, arrayObjetos[i]);
            
        }
}

function track(param){
    limpiarTrack();
    $("#modalNuevo-track").show();
      

    if(typeof param != "undefined"){
        $("#gestion-track").attr("value", param.NUMERO_GESTION);
        var jsonReporte = new Object(); 
        jsonReporte.NUMERO_GESTION = param.NUMERO_GESTION;
        var dataString = JSON.stringify(jsonReporte); 
    }else{
        var jsonReporte = new Object(); 
        jsonReporte.NUMERO_GESTION = $("#gestion-track").val();
        var dataString = JSON.stringify(jsonReporte); 
    }
       
}
