<?php
require_once ('config.php');
if ($_REQUEST['type'] == 'GESTIONES')
{
$result = mysql_query("SELECT 
    LOG_GESTIONES.FECHA_GESTION, 
    LOG_GESTIONES.FECHA_FIN, 
    LOG_GESTIONES.CODIGO_GESTOR, 
    ADM_PERSONAS.DESCRIPCION_PERSONA , 
    LOG_GESTIONES.ESTADO, 
    LOG_GESTIONES.CANTIDAD_GESTIONES,
    LOG_GESTIONES.OBSERVACION 
    FROM LOG_GESTIONES 
    INNER JOIN LOG_GESTORES ON LOG_GESTIONES.CODIGO_GESTOR = LOG_GESTORES.CODIGO_GESTOR 
    INNER JOIN ADM_PERSONAS ON ADM_PERSONAS.CODIGO_PERSONA= LOG_GESTORES.CODIGO_PERSONA 
    WHERE LOG_GESTIONES.CODIGO_CLIENTE = ".$_REQUEST['CLIENTE']." ORDER BY LOG_GESTIONES.FECHA_GESTION LIMIT 0, 5");
$arr = array();
    while ($row = mysql_fetch_assoc($result))
    {
        
       array_push($arr, $row);
    }

    echo utf8_decode(json_encode($arr));
}

if ($_REQUEST['type'] == 'CLIENTES')
{

    $result = mysql_query("SELECT 
                ADM_CLIENTES.CODIGO_CLIENTE, 
                ADM_PERSONAS.DESCRIPCION_PERSONA 
                FROM ADM_CLIENTES 
                INNER JOIN ADM_PERSONAS ON ADM_CLIENTES.CODIGO_PERSONA = ADM_PERSONAS.CODIGO_PERSONA 
                ORDER BY ADM_PERSONAS.DESCRIPCION_PERSONA");
	$arr = array();
    while ($row = mysql_fetch_assoc($result))
    {
        
       array_push($arr, $row);
    }

    echo (json_encode($arr));
}

if ($_REQUEST['type'] == 'PERFIL')
{

    $result = mysql_query(
        "SELECT 
            ADM_CLIENTES.CODIGO_CLIENTE, 
            ADM_PERSONAS.DESCRIPCION_PERSONA, 
            ADM_PERSONAS.NRO_DOCUMENTO_PERSONA, 
            CONCAT(ADM_PERSONAS.TELEFONO_PERSONA, \' / \', ADM_PERSONAS.CELULAR_PERSONA) AS TELEFONO, 
            ADM_PERSONAS.DIRECCION_PERSONA, 
            IF((SELECT COUNT(*) AS PLAN FROM VLOG_SALDOS_PLANES WHEREs 
                VLOG_SALDOS_PLANES.CODIGO_CLIENTE= ".$_REQUEST['CLIENTE']." 
                AND VLOG_SALDOS_PLANES.TIPO_PLAN = 'M' 
                AND VLOG_SALDOS_PLANES.SALDO > 0) > 0, 'Mensual', 'Casual') AS TIPO_CLIENTE 
        FROM ADM_CLIENTES, ADM_PERSONAS 
        WHERE ADM_CLIENTES.CODIGO_CLIENTE = ".$_REQUEST['CLIENTE']." 
            AND ADM_CLIENTES.CODIGO_PERSONA = ADM_PERSONAS.CODIGO_PERSONA"
            );
	$arr = array();
    while ($row = mysql_fetch_assoc($result))
    {
        
       array_push($arr, $row);
    }

    echo (json_encode($arr));
}

?>