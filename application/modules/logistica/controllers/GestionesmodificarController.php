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
        $p = Zend_Session::namespaceUnset('id');
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
            'A.HORA_ESTIMADA',
            'A.DESCRIPCION AS GESTIONES',
            'A.REALIZADO',
            'A.FEC_HORA_REALIZ',
            'A.FEC_HORA_REALIZ',
            'A.MOTIVO_CANCEL',
            'A.SYNC',
            'A.LATITUD',
            'A.LONGITUD',
            'A.CODIGO_GESTOR',
            'A.INICIO_ACTIVIDAD',
            'A.FIN_ACTIVIDAD' 
        ))->join(array(
            'B' => 'LOG_ZONAS'
        ), 'A.CODIGO_ZONA  = B.CODIGO_ZONA')->where('A.CODIGO_GESTION = ?', $id)
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
                'HORA_ESTIMADA' => $row["HORA_ESTIMADA"],
                'DESCRIPCION' => $row["GESTIONES"],
                'REALIZADO' => ($row["REALIZADO"] == 1 ? 'Si' : 'No'),
                'FEC_HORA_REALIZ' => $row["FEC_HORA_REALIZ"],
                'MOTIVO_CANCEL' => $row["MOTIVO_CANCEL"],
                'SYNC' => $row["SYNC"],
                'LATITUD' => $row["LATITUD"],
                'LONGITUD' => $row["LONGITUD"],
                'CODIGO_GESTOR' => $row["CODIGO_GESTOR"],
                'INICIO_ACTIVIDAD' => $row["INICIO_ACTIVIDAD"],
                'FIN_ACTIVIDAD' => $row["FIN_ACTIVIDAD"]
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
                         $bodyTexto      = "Estimado : " . $clientes->DESCRIPCION_PERSONA . "\nHemos culminado la gestión solicitada\n\nTarea: " . $parametros->OBSERVACION . "\n\nTiempo empleado: " . $parametros->CANTIDAD_MINUTOS . " mins.\n\nGestiones utilizadas:" . $parametros->CANTIDAD_GESTIONES . "\nGracias por confiar en San Solución\n\nCoordinadora de Servicios\nSan Solución.\n\n\n Por favor califique nuestro servicio en esta gestion de 1 al 5 (Siendo 1 la puntuación más baja y 5 la más alta) y suguiriendo las mejoras.\n\n\nSi usted no desea recibir estas notificaciones, responda este correo con la frase desvincular de notificaciones.";
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
            $n = $db->delete('LOG_GESTIONES_ACT', array(
                'CODIGO_GESTION = ?' => $parametros->NUMERO_GESTION
            ));

            if (!empty($parametros->NUMERO_GESTION)) {
                $i = 1;
                foreach ($param as $value) {
                   $realizado = ($value->REALIZADO == 'Si' ? 1 : 0);
                    $data   = array(
                        'CODIGO_GESTION' => $parametros->NUMERO_GESTION,
                        'ORDEN' => $i++,
                        'PROCESO' => $value->PROCESO,
                        'CODIGO_ZONA' => $value->CODIGO_ZONA,
                        'DESTINO' => $value->DESTINO,
                        'HORA_ESTIMADA'=> $value->HORA_ESTIMADA,
                        'DESCRIPCION' => $value->DESCRIPCION,
                        'REALIZADO' => $realizado,
                        'FEC_HORA_REALIZ'=> $value->FEC_HORA_REALIZ,
                        'MOTIVO_CANCEL'=> $value->MOTIVO_CANCEL,
                        'SYNC'=> $value->SYNC,
                        'LATITUD'=> $value->LATITUD,
                        'LONGITUD'=> $value->LONGITUD,
                        'CODIGO_GESTOR'=> $value->CODIGO_GESTOR
						/*,
                        'INICIO_ACTIVIDAD'=> $value->INICIO_ACTIVIDAD,
                        'FIN_ACTIVIDAD'=> $value->FIN_ACTIVIDAD*/
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

    private function enviaremail($emailDestino,$nombre,$bodyTexto,$asunto){
        try{
             // $config = array('ssl' => 'tls', 'port' => 587, 'auth' => 'login', 'username' => '', 'password' => '');
           /* $config = array( 'port' => 25, 'auth' => 'login', 'username' => 'pedido@sansolucion.com', 'password' => 'pedido123.');*/
			$email_envio = "informesansolucion@sansolucion.com";
            $config = array( 'port' => 25, 'auth' => 'login', 'username' => $email_envio, 'password' => '0102030405');
            // $smtpConnection = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $config);
            // $smtpConnection = new Zend_Mail_Transport_Smtp('gator4081.hostgator.com', $config);
            $smtpConnection = new Zend_Mail_Transport_Smtp('mail.sansolucion.com', $config);

            $mail = new Zend_Mail('utf-8');
            $mail->setBodyText($bodyTexto);
            $mail->setFrom($email_envio, 'Informe San Solución');
            $mail->addTo($emailDestino, $nombre);
            $mail->setSubject($asunto);
            print_r($email);
            $mail->send($smtpConnection);
            return true;
        } catch (Exception $e){
            return $e->getMessage();
        }
    }

    private function obtenerasistente($codigo){
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
        ->from(array('C'=>'LOG_GESTORES'),  array(
                     'C.CODIGO_GESTOR',
                     'P.DESCRIPCION_PERSONA',
                     'P.EMAIL_PERSONA'))
            ->join(array('P' => 'ADM_PERSONAS'), 'P.CODIGO_PERSONA  = C.CODIGO_PERSONA')
            ->where("C.CODIGO_GESTOR = ?", $codigo)
            ->distinct(true);
        
        $result = $db->fetchAll($select);
         if($result[0]['CODIGO_GESTOR'] != null){
                return json_encode(array(
                    'CODIGO_GESTOR' => $result[0]['CODIGO_GESTOR'],
                    'DESCRIPCION_PERSONA' => $result[0]['DESCRIPCION_PERSONA'],
                    'EMAIL_PERSONA' => $result[0]['EMAIL_PERSONA']
                    
                 ));    
            }else{
                return false;
            }


    }

    private function obtenercliente($codigo){
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                ->from(array('C'=>'ADM_CLIENTES'),  array(
                             'C.CODIGO_CLIENTE',
                             'P.DESCRIPCION_PERSONA',
                             'P.ENVIAR_EMAIL',
                             'P.EMAIL_PERSONA'))
                    ->join(array('P' => 'ADM_PERSONAS'), 'P.CODIGO_PERSONA  = C.CODIGO_PERSONA')
                    ->where("C.CODIGO_CLIENTE = ?", $codigo)
                    ->distinct(true);
                
                $result = $db->fetchAll($select);
         if($result[0]['CODIGO_CLIENTE'] != null){
                return json_encode(array(
                    'CODIGO_CLIENTE' => $result[0]['CODIGO_CLIENTE'],
                    'DESCRIPCION_PERSONA' => $result[0]['DESCRIPCION_PERSONA'],
                    'EMAIL_PERSONA' => $result[0]['EMAIL_PERSONA'],
                    'ENVIAR_EMAIL' => $result[0]['ENVIAR_EMAIL'],
                    
                 ));    
            }else{
                return false;
            }

    }


   
    
    
}