var pathname = window.location.pathname;
var table = pathname;
$().ready(function() {    
    limpiarFormulario();
	$('#signin_submit').click(function() {
            if($('#username').val() == ''){
                alert("Debe ingresar un usuario!!");
                $("#username").focus();
                retunr;
            }
            if($('#password').val() == ''){
                alert("Debe ingresar una contrasena!!");
                $("#password").focus();
                retunr;
            }    
            var data = obtenerJsonFormulario();
            if(data != null){
                enviarParametrosRegistro(data);
            }
	 });        
});

function obtenerJsonFormulario() {
    var jsonObject = new Object();
    jsonObject.username = $('#username').attr("value");
    jsonObject.password = $('#password').attr("value");
    return jsonObject;
}
function limpiarFormulario(){
        
        $("#username").attr("value",null);
        $("#password").attr("value",null);
}

function enviarParametrosRegistro(data){
    $.blockUI({
        message: "Aguarde un momento por favor"
    });

    var dataString = JSON.stringify(data);
    $.ajax({
        url: table+'/usuariodata',
        type: 'post',
        data: {"parametros":dataString},
        dataType: 'json',
        async : true,
        success: function(respuesta){
            console.log((respuesta.success));
            if(respuesta.success){
                    var url = table;
                    url = url.replace('login/login','menu/menu');
                    $(location).attr('href',url);                               
            }else{
                alert("fallo la autenticacion");
            }            
               $.unblockUI();
            
        },
        error: function(event, request, settings){
           alert("Ha ocurrido un error");
            $.unblockUI();
        }
    });
}