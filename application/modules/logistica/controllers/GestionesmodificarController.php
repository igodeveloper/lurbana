<?php

class logistica_gestionesmodificarController extends Zend_Controller_Action
{
    
    public function init()
    {
        $parametrosLogueo = new Zend_Session_Namespace('logueo');
        $parametrosLogueo->unlock();
        if (!$parametrosLogueo->username) {
            $r = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
            $r->gotoUrl('/login/login')->redirectAndExit();
        }
        $parametrosLogueo->lock();
        
        
    }
    
    public function indexAction()
    {
        // $this->_helper->layout->disableLayout(false);
        //    $this->_helper->viewRenderer->setNoRender ( true );   
        $id           = $this->getRequest()->getParam("id");
        $parametrosId = new Zend_Session_Namespace('id');
        $parametrosId->unlock();
        $parametrosId->id = $id;
        $parametrosId->lock();
        
    }
    
    public function getgestionAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $parametrosId = new Zend_Session_Namespace('id');
        $parametrosId->unlock();
        $id = $parametrosId->id;
        $parametrosId->lock();
        $db     = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()->from(array(
            'G' => 'LOG_GESTIONES'
        ), array(
            'G.NUMERO_GESTION',
            'G.FECHA_GESTION',
            'G.FECHA_INICIO',
            'G.FECHA_FIN',
            'G.CODIGO_CLIENTE',
            'P.DESCRIPCION_PERSONA AS CLIENTE',
            'G.CODIGO_GESTOR',
            'PG.DESCRIPCION_PERSONA AS GESTOR',
            'G.CODIGO_USUARIO',
            'G.ESTADO',
            'G.CANTIDAD_GESTIONES',
            'G.CANTIDAD_MINUTOS',
            'G.OBSERVACION',
            'G.CODIGO_PLAN',
            'G.GENTILEZA',
            'PL.DESCRIPCION_PLAN'
        ))->join(array(
            'C' => 'ADM_CLIENTES'
        ), 'G.CODIGO_CLIENTE  = C.CODIGO_CLIENTE')->join(array(
            'P' => 'ADM_PERSONAS'
        ), 'P.CODIGO_PERSONA  = C.CODIGO_PERSONA')->joinLeft(array(
            'GP' => 'LOG_GESTORES'
        ), 'G.CODIGO_GESTOR  = GP.CODIGO_GESTOR')->joinLeft(array(
            'PG' => 'ADM_PERSONAS'
        ), 'PG.CODIGO_PERSONA  = GP.CODIGO_PERSONA')->joinLeft(array(
            'PL' => 'ADM_PLANES'
        ), 'PL.CODIGO_PLAN  = G.CODIGO_PLAN')->where("G.NUMERO_GESTION = ?", $id)->order(array(
            'G.NUMERO_GESTION DESC'
        ));
        
        $result                   = $db->fetchAll($select);
        $resultado['gestion']     = array();
        $resultado['actividades'] = array();
        foreach ($result as $item) {
            $arrayDatos = array(
                'NUMERO_GESTION' => $item['NUMERO_GESTION'],
                'FECHA_GESTION' => $item['FECHA_GESTION'],
                'FECHA_INICIO' => $item['FECHA_INICIO'],
                'FECHA_FIN' => $item['FECHA_FIN'],
                'CODIGO_CLIENTE' => $item['CODIGO_CLIENTE'],
                'CLIENTE' => $item['CLIENTE'],
                'CODIGO_GESTOR' => $item['CODIGO_GESTOR'],
                'GESTOR' => $item['GESTOR'],
                'CODIGO_USUARIO' => $item['CODIGO_USUARIO'],
                'ESTADO' => $item['ESTADO'],
                'CANTIDAD_GESTIONES' => $item['CANTIDAD_GESTIONES'],
                'CANTIDAD_MINUTOS' => $item['CANTIDAD_MINUTOS'],
                'OBSERVACION' => $item['OBSERVACION'],
                'CODIGO_PLAN' => $item['CODIGO_PLAN'],
                'DESCRIPCION_PLAN' => $item['DESCRIPCION_PLAN'],
                'GENTILEZA' => $item['GENTILEZA']
            );
            
            array_push($resultado['gestion'], $arrayDatos);
            
        }
        
        $select2 = $db->select()->from(array(
            'A' => 'LOG_GESTIONES_ACT'
        ), array(
            'A.CODIGO_GESTION',
            'A.ORDEN',
            'A.PROCESO',
            'A.CODIGO_ZONA',
            'B.DESCRIPCION AS DESCRIPCION_ZONA',
            'A.DESTINO',
            'A.DESCRIPCION AS GESTIONES',
            'A.REALIZADO',
            'A.FEC_HORA_REALIZ'
        ))->join(array(
            'B' => 'LOG_ZONAS'
        ), 'A.CODIGO_ZONA  = B.CODIGO_ZONA')->where('A.CODIGO_GESTION = ?', $id)
        // ->where('A.CODIGO_GESTION = ?',  1113)
            ->order(array(
            'A.ORDEN ASC'
        ));
        $result2 = $db->fetchAll($select2);
        $arr     = array();
        foreach ($result2 as $row) {
            $arrayDatos = array(
                'CODIGO_GESTION' => $row["CODIGO_GESTION"],
                'ORDEN' => $row["ORDEN"],
                'PROCESO' => $row["PROCESO"],
                'CODIGO_ZONA' => $row["CODIGO_ZONA"],
                'DESCRIPCION_ZONA' => $row["DESCRIPCION_ZONA"],
                'DESTINO' => $row["DESTINO"],
                'DESCRIPCION' => $row["GESTIONES"],
                'REALIZADO' => ($row["REALIZADO"] == 1 ? 'Si' : 'No'),
                'FEC_HORA_REALIZ' => $row["FEC_HORA_REALIZ"]
            );
            
            array_push($resultado['actividades'], $arrayDatos);
            
        }
        if (empty($resultado['gestion'])) {
            $resultado['success'] = false;
        } else {
            $resultado['success'] = true;
        }
        echo json_encode($resultado);
        
    }
    
    public function modificarAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $parametros = json_decode($this->getRequest()->getParam("parametros"));
        try {
            
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->beginTransaction();
            if ($parametros->FECHA_INICIO == date("Y-m-d")) {
                $parametros->FECHA_INICIO = date("Y-m-d H:i:s");
            }
            if ($parametros->FECHA_FIN == date("Y-m-d")) {
                $parametros->FECHA_FIN = date("Y-m-d H:i:s");
            }
            $data_personas   = array(
                'NUMERO_GESTION' => $parametros->NUMERO_GESTION,
                'CODIGO_CLIENTE' => $parametros->CODIGO_CLIENTE,
                'FECHA_GESTION' => $parametros->FECHA_GESTION,
                'OBSERVACION' => $parametros->OBSERVACION,
                'FECHA_INICIO' => $parametros->FECHA_INICIO,
                'FECHA_FIN' => $parametros->FECHA_FIN,
                'CANTIDAD_GESTIONES' => $parametros->CANTIDAD_GESTIONES,
                'CANTIDAD_MINUTOS' => $parametros->CANTIDAD_MINUTOS,
                'CODIGO_GESTOR' => $parametros->CODIGO_GESTOR,
                'ESTADO' => $parametros->ESTADO,
                'GENTILEZA' => $parametros->GENTILEZA
            );
            $where_personas  = array(
                'NUMERO_GESTION = ?' => $parametros->NUMERO_GESTION
            );
            $update_personas = $db->update('LOG_GESTIONES', $data_personas, $where_personas);
            
            $comotermina = true;
            $estados     = "";
            if ($parametros->ENVIAREMAIL == "SI") {
                if ($parametros->ESTADO == 'E' && $parametros->CODIGO_GESTOR != 0) {
                    $estados .= "Entrro a enviar email";
                    $asistentes = json_decode(self::obtenerasistente($parametros->CODIGO_GESTOR));
                    $clientes   = json_decode(self::obtenercliente($parametros->CODIGO_CLIENTE));
                    
                    if ($asistentes) {
                        $estados .= "- Recupero asistente";
                        $nombre_gestor = $asistentes->DESCRIPCION_PERSONA;
                        $emailDestino  = $asistentes->EMAIL_PERSONA;
                        $asunto        = "Nueva Tarea";
                        $bodyTexto     = "Cliente: " . $clientes->DESCRIPCION_PERSONA . "\n\nTarea: " . $parametros->OBSERVACION . "\n \n Tiempo estimado: " . $parametros->CANTIDAD_MINUTOS . " mins. \n\nGestiones estimadas:" . $parametros->CANTIDAD_GESTIONES;
                        $email         = self::enviaremail($emailDestino, $nombre_gestor, $bodyTexto, $asunto);
                        $estados .= "- resultado de email" . $email;
                        if (!$email) {
                            $comotermina = false;
                            // echo json_encode(array("success" => false,"email" => $email));
                        }
                    } else {
                        // echo json_encode(array("success" => false,"mensaje" => $asistentes));
                        $comotermina = false;
                    }
                    if ($clientes) {
                        $estados .= "- Recupero cliente";
                        $nombre_cliente = $clientes->DESCRIPCION_PERSONA;
                        $emailDestino   = $clientes->EMAIL_PERSONA;
                        $asunto         = "Su gestin se encuentra en proceso.";
                        $bodyTexto      = "Su asistente de servicios es: " . $asistentes->DESCRIPCION_PERSONA . "\n\nTarea: " . $parametros->OBSERVACION . "\n\nTiempo estimado: " . $parametros->CANTIDAD_MINUTOS . " mins.\n\nGestiones estimadas:" . $parametros->CANTIDAD_GESTIONES . "\n\n\n\nSi usted no desea recibir estas notificaciones, responda este correo con la frase desvincular de notificaciones.";
                        if ($clientes->ENVIAR_EMAIL == "S") {
                            $email = self::enviaremail($emailDestino, $nombre_cliente, $bodyTexto, $asunto);
                        }
                        
                        if (!$email && $clientes->ENVIAR_EMAIL == "S") {
                            $comotermina = false;
                            // echo json_encode(array("success" => false,"email" => $email));
                        }
                    } else {
                        $comotermina = false;
                        // echo json_encode(array("success" => false,"mensaje" => $asistentes));
                    }
                }
                if ($parametros->ESTADO == 'F') {
                    $clientes = json_decode(self::obtenercliente($parametros->CODIGO_CLIENTE));
                    if ($clientes) {
                        $nombre_cliente = $clientes->DESCRIPCION_PERSONA;
                        $emailDestino   = $clientes->EMAIL_PERSONA;
                        $asunto         = "Notificación de gestión realizada.";
                        $bodyTexto      = "Estimado : " . $clientes->DESCRIPCION_PERSONA . "\nHemos culminado la gestión solicitada\n\nTarea: " . $parametros->OBSERVACION . "\n\nTiempo empleado: " . $parametros->CANTIDAD_MINUTOS . " mins.\n\nGestiones utilizadas:" . $parametros->CANTIDAD_GESTIONES . "\nGracias por confiar en San Solución\n\nCira Leon\nCoordinadora de Servicios\nSan Solución\n\n\nSi usted no desea recibir estas notificaciones, responda este correo con la frase desvincular de notificaciones.";
                        if ($clientes->ENVIAR_EMAIL == "S") {
                            $email = self::enviaremail($emailDestino, $nombre_cliente, $bodyTexto, $asunto);
                        }
                        if (!$email && $clientes->ENVIAR_EMAIL == "S") {
                            $comotermina = false;
                        }
                    } else {
                        $comotermina = false;
                        // echo json_encode(array("success" => false,"mensaje" => $asistentes));
                    }
                }
            }
            $param = $parametros->ACTIVIDADES;
            $n = $db->delete('log_gestiones_act', array(
                'CODIGO_GESTION = ?' => $parametros->NUMERO_GESTION
            ));
            
            if (!empty($parametros->NUMERO_GESTION)) {
                $i = 1;
                foreach ($param as $value) {
                    $data   = array(
                        'CODIGO_GESTION' => $parametros->NUMERO_GESTION,
                        'ORDEN' => $i++,
                        'PROCESO' => $value->PROCESO,
                        'CODIGO_ZONA' => $value->CODIGO_ZONA,
                        'DESTINO' => $value->DESTINO,
                        'DESCRIPCION' => $value->DESCRIPCION,
                        'REALIZADO' => $value->REALIZADO
                    );
                    $insert = $db->insert('LOG_GESTIONES_ACT', $data);
                    
                }
            }
            
            $db->commit();
            // echo json_encode(array("success" => true));
            echo json_encode(array(
                "success" => $comotermina,
                "estado" => $estados
            ));
        }
        catch (Exception $e) {
            echo json_encode(array(
                "success" => false,
                "code" => $e->getCode(),
                "mensaje" => $e->getMessage()
            ));
            $db->rollBack();
        }
    }


   
    
    
}