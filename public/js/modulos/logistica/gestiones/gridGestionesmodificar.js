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
                        width: 200,
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
                        width: 150,
                        align: 'center'
                    },{
                        name: 'FEC_HORA_REALIZ',
                        index: 'FEC_HORA_REALIZ',
                        label: 'FECHA - HORA',
                        hidden :false,
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

function cargarLinkBorrar(cellvalue, options, rowObject)
{   
    json = JSON.stringify(rowObject);
    return "<button type='button' class='btn btn-warning' onclick='editarActividad(\""+options.rowId+"\")'><i class='icon icon-edit'></i></button>&nbsp;&nbsp;<button type='button' class='btn btn-danger' onclick='borrarActividad(\""+options.rowId+"\")'><i class='icon icon-remove-circle'></i></button>";
}


function borrarActividad(rowid){
   $('#grillaGestionesTrack').jqGrid('delRowData',rowid);   
}
function editarActividad(rowid){

    var param = $('#grillaGestionesTrack').jqGrid('getRowData', rowid);
    var realizado = (param.REALIZADO == 'Si' ? 1 : 0);
    $("#gestion-acti").attr("value", param.CODIGO_GESTION);
    $("#orden-acti").attr("value", param.ORDEN);
    $("#zona-acti").select2("val", param.CODIGO_ZONA);
    $("#proceso-acti").attr("value", param.PROCESO);
    $("#destino-acti").attr("value", param.DESTINO);
    $("#descripcion-acti").attr("value", param.DESCRIPCION);
    $("#realizado-acti").attr("value", realizado);
    $("#hora-acti").attr("value", param.FEC_HORA_REALIZ);
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
        jsonReporte.NUMERO_GESTION = $("#gestion-acti").val();
        jsonReporte.ORDEN = $("#orden-acti").val();
        jsonReporte.PROCESO = $("#proceso-acti").val();
        jsonReporte.CODIGO_ZONA = $("#zona-acti").select2('data').id;
        jsonReporte.DESCRIPCION_ZONA = $("#zona-acti").select2('data').text;;
        jsonReporte.DESTINO = $("#destino-acti").val();
        jsonReporte.DESCRIPCION = $("#descripcion-acti").val();
        jsonReporte.REALIZADO = $("#realizado-acti").val();
        jsonReporte.FEC_HORA_REALIZ = $("#hora-acti").val();
        return jsonReporte;
    }else{
        return false;
        mostrarVentana("warning-modal-acti","Ocurrio un error inesperado, verifique los datos a ingresar");
    }




}
function guardarActividad(){
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
