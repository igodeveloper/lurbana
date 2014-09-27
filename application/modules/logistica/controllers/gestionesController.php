<?php

class logistica_gestionesController extends Zend_Controller_Action {

     public function init() {
        $parametrosLogueo = new Zend_Session_Namespace ( 'logueo' );
        $parametrosLogueo->unlock ();   
        if(!$parametrosLogueo->username){
                $r = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
                $r->gotoUrl('/login/login')->redirectAndExit();
            }
        $parametrosLogueo->lock();    
       
      
    }

    public function indexAction() {
    // $this->_helper->layout->disableLayout(false);
    //    $this->_helper->viewRenderer->setNoRender ( true );
        
    }

     public function buscarAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender ( true );

        $filtros = json_decode($this->getRequest()->getParam("filtros"));

        $parametrosNamespace = new Zend_Session_Namespace('parametros');
        $parametrosNamespace->unlock();
        $cantidadFilas = $this->getRequest()->getParam("rows");
        if (!isset($cantidadFilas)) {
            $cantidadFilas = 10;
        }
        $parametrosNamespace->cantidadFilas = $cantidadFilas;
        $page = $this->getRequest()->getParam("page");
        if (!isset($page)) {
            $page = 1;
        }
        $db = Zend_Db_Table::getDefaultAdapter();
       $select = $db->select()
                ->from(array('G'=>'LOG_GESTIONES'),  array(
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
                             'PL.DESCRIPCION_PLAN'))
                   ->join(array('C' => 'ADM_CLIENTES'), 'G.CODIGO_CLIENTE  = C.CODIGO_CLIENTE')
                   ->join(array('P' => 'ADM_PERSONAS'), 'P.CODIGO_PERSONA  = C.CODIGO_PERSONA')
                   ->joinLeft(array('GP' => 'LOG_GESTORES'), 'G.CODIGO_GESTOR  = GP.CODIGO_GESTOR')
                   ->joinLeft(array('PG' => 'ADM_PERSONAS'), 'PG.CODIGO_PERSONA  = GP.CODIGO_PERSONA')
                   ->joinLeft(array('PL' => 'ADM_PLANES'), 'PL.CODIGO_PLAN  = G.CODIGO_PLAN')
                   ->order(array('G.NUMERO_GESTION DESC'));
                   
         if ($filtros != null) {
            // if ($filtros->DESCRIPCION_PERSONA != null) {
            //     $select->where("upper(P.DESCRIPCION_PERSONA) like upper('%".$filtros->DESCRIPCION_PERSONA."%')");
            // }
            // if ($filtros->NRO_DOCUMENTO_PERSONA != null) {
            //     $select->where("P.NRO_DOCUMENTO_PERSONA = ?", $filtros->NRO_DOCUMENTO_PERSONA);
            // }
            // if ($filtros->TELEFONO_PERSONA != null) {
            //     $select->where("P.TELEFONO_PERSONA = ?", $filtros->TELEFONO_PERSONA);
            // }
            // if ($filtros->ESTADO_CLIENTE != -1) {
            //     $select->where("C.ESTADO_CLIENTE = ?", $filtros->ESTADO_CLIENTE);
            // }
            $result = $db->fetchAll($select);
        } else {
            $result = $db->fetchAll($select);
        }

        $pagina = self::obtenerPaginas($result, $cantidadFilas, $page);
        echo $this->_helper->json($pagina);
    }

    private function obtenerPaginas($result,$cantidadFilas,$page){
        $this->_paginator = Zend_Paginator::factory($result);
        $this->_paginator->setItemCountPerPage($cantidadFilas);
        $this->_paginator->setCurrentPageNumber($page);
        $pagina ['rows'] = array();
        foreach ($this->_paginator as $item) {
        // $cod_receta = ($item['COD_RECETA'] == null)?0:$item['COD_RECETA'];
        // $cod_receta_desc = ($item['RECETA_DESCRIPCION'] == null)?' - ':$item['RECETA_DESCRIPCION'];
            $arrayDatos ['cell'] = array(
                $item['NUMERO_GESTION'],
                $item['FECHA_GESTION'],
                $item['FECHA_INICIO'],
                $item['FECHA_FIN'],
                $item['CODIGO_CLIENTE'],
                $item['CLIENTE'],
                $item['CODIGO_GESTOR'],
                $item['GESTOR'],
                $item['CODIGO_USUARIO'],
                $item['ESTADO'],
                $item['CANTIDAD_GESTIONES'],
                $item['CANTIDAD_MINUTOS'],
                $item['OBSERVACION'],
                $item['CODIGO_PLAN'],
                $item['DESCRIPCION_PLAN']
            );
            $arrayDatos ['columns'] = array(
                    'NUMERO_GESTION',
                    'FECHA_GESTION',
                    'FECHA_INICIO',
                    'FECHA_FIN',
                    'CODIGO_CLIENTE',
                    'CLIENTE',
                    'CODIGO_GESTOR',
                    'GESTOR',
                    'CODIGO_USUARIO',
                    'ESTADO',
                    'CANTIDAD_GESTIONES',
                    'CANTIDAD_MINUTOS',
                    'OBSERVACION',
                    'CODIGO_PLAN',
                    'DESCRIPCION_PLAN'
            );
            array_push($pagina ['rows'], $arrayDatos);
        }

        if ($cantidadFilas == 0)
            $cantidadFilas = 10;

        $pagina ['records'] = count($result);
        $pagina ['page'] = $page;
        $pagina ['total'] = ceil($pagina ['records'] / $cantidadFilas);

        if ($pagina['records'] == 0) {
            $pagina ['mensajeSinFilas'] = true;
        }

        return $pagina;
    }
    
    public function guardarAction(){
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $parametros = json_decode($this->getRequest()->getParam("parametros"));
        try {
            $parametrosLogueo = new Zend_Session_Namespace ( 'logueo' );
            $parametrosLogueo->unlock (); 

            $db = Zend_Db_Table::getDefaultAdapter();
            $db->beginTransaction();
            // print_r($parametros);die();
            if(!$parametros->NUMERO_GESTION)
                $parametros->NUMERO_GESTION = 0;
            if(!$parametros->CODIGO_GESTOR)
                $parametros->CODIGO_GESTOR = 0;
            if($parametros->FECHA_INICIO == date("Y-m-d")){
                $parametros->FECHA_INICIO = date("Y-m-d H:i:s");
            }else{
                $parametros->FECHA_INICIO = '0000-00-00 00:00:00';
            }
            if($parametros->FECHA_FIN == date("Y-m-d")){
                $parametros->FECHA_FIN = date("Y-m-d H:i:s");
            }else{
                $parametros->FECHA_FIN = '0000-00-00 00:00:00';
            }
                
            $data_personas = array(
                'NUMERO_GESTION' => $parametros->NUMERO_GESTION,
                'CODIGO_CLIENTE' => $parametros->CODIGO_CLIENTE,
                'FECHA_GESTION' => $parametros->FECHA_GESTION,
                'OBSERVACION' => $parametros->OBSERVACION,
                'FECHA_INICIO' =>$parametros->FECHA_INICIO,
                'FECHA_FIN' => $parametros->FECHA_FIN,
                'CANTIDAD_GESTIONES' => $parametros->CANTIDAD_GESTIONES,
                'CANTIDAD_MINUTOS'=> $parametros->CANTIDAD_MINUTOS,
                'CODIGO_GESTOR'=> $parametros->CODIGO_GESTOR,
                'CODIGO_USUARIO'=> $parametrosLogueo->cod_usuario,
                'ESTADO'=> $parametros->ESTADO
                // 'CODIGO_PLAN'=> $parametros->CODIGO_PLAN
            );
            $insert_personas = $db->insert('LOG_GESTIONES', $data_personas);
              $comotermina = true;
            $estados = "";
            if($parametros->ENVIAREMAIL == "SI"){
             
            if ($parametros->ESTADO == 'E' && $parametros->CODIGO_GESTOR != 0) {
                $estados .= "Entrro a enviar email";
                $asistentes =  json_decode(self::obtenerasistente($parametros->CODIGO_GESTOR));
                $clientes =  json_decode(self::obtenercliente($parametros->CODIGO_CLIENTE));

                if($asistentes){
                    $estados .= "- Recupero asistente";
                  $nombre_gestor= $asistentes->DESCRIPCION_PERSONA;
                  $emailDestino= $asistentes->EMAIL_PERSONA;
                  $asunto = "Nueva Tarea";
                  $bodyTexto = "Cliente: ".$clientes->DESCRIPCION_PERSONA."\n\nTarea: ".$parametros->OBSERVACION."\n \n Tiempo estimado: ".$parametros->CANTIDAD_MINUTOS." mins. \n\nGestiones estimadas:".$parametros->CANTIDAD_GESTIONES;
                  $email = self::enviaremail($emailDestino,$nombre_gestor,$bodyTexto,$asunto);
                  $estados .= "- resultado de email".$email;
                    if(!$email){
                        $comotermina = false;
                        // echo json_encode(array("success" => false,"email" => $email));
                    }
                } else {
                    // echo json_encode(array("success" => false,"mensaje" => $asistentes));
                    $comotermina = false;
                }
                if($clientes){
                    $estados .= "- Recupero cliente";
                  $nombre_cliente= $clientes->DESCRIPCION_PERSONA;
                  $emailDestino= $clientes->EMAIL_PERSONA;
                  $asunto = "Su gestión se encuentra en proceso.";
                  $bodyTexto = "Su asistente de servicios es: ".$asistentes->DESCRIPCION_PERSONA."\n\nTarea: ".$parametros->OBSERVACION."\n\nTiempo estimado: ".$parametros->CANTIDAD_MINUTOS." mins.\n\nGestiones estimadas:".$parametros->CANTIDAD_GESTIONES."\n\n\n\nSi usted no desea recibir estas notificaciones, responda este correo con la frase desvincular de notificaciones.";
                  if($clientes->ENVIAR_EMAIL == "S"){$email = self::enviaremail($emailDestino,$nombre_cliente,$bodyTexto,$asunto);}
                     $estados .= "- resultado de email".$email;
                    if(!$email && $clientes->ENVIAR_EMAIL == "S"){
                        $comotermina = false;
                        // echo json_encode(array("success" => false,"email" => $email));
                    }
                } else {
                    $comotermina = false;
                    // echo json_encode(array("success" => false,"mensaje" => $asistentes));
                }           
            }
            if ($parametros->ESTADO == 'F') {
                $clientes =  json_decode(self::obtenercliente($parametros->CODIGO_CLIENTE));
                 if($clientes){
                   
                  $nombre_cliente= $clientes->DESCRIPCION_PERSONA;
                  $emailDestino= $clientes->EMAIL_PERSONA;
                  $asunto = "Notificación de gestión realizada.";
                  $bodyTexto = "Estimado : ".$clientes->DESCRIPCION_PERSONA."\nHemos culminado la gestión solicitada\n\nTarea: ".$parametros->OBSERVACION."\n\nTiempo empleado: ".$parametros->CANTIDAD_MINUTOS." mins.\n\nGestiones utilizadas:".$parametros->CANTIDAD_GESTIONES."\nGracias por confiar en San Solución\n\nCira Leon\nCoordinadora de Servicios\nSan Solución\n\n\nSi usted no desea recibir estas notificaciones, responda este correo con la frase desvincular de notificaciones.";
                  if($clientes->ENVIAR_EMAIL == "S"){$email = self::enviaremail($emailDestino,$nombre_cliente,$bodyTexto,$asunto);}
                    if(!$email && $clientes->ENVIAR_EMAIL == "S"){
                        $comotermina = false;
                        // echo json_encode(array("success" => false,"email" => $email));
                    }
                } else {
                    $comotermina = false;
                    // echo json_encode(array("success" => false,"mensaje" => $asistentes));
                }           
             }
            }
            

            $db->commit();
           echo json_encode(array("success" => $comotermina, "estado" => $estados));
        } catch (Exception $e) {
            echo json_encode(array("success" => false, "code" => $e->getCode(), "mensaje" => $e->getMessage()));
            $db->rollBack();
        }
    }

        public function  modificarAction(){
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $parametros = json_decode($this->getRequest()->getParam("parametros"));
        try {

            $db = Zend_Db_Table::getDefaultAdapter();
            $db->beginTransaction();
            if($parametros->FECHA_INICIO == date("Y-m-d")){
                $parametros->FECHA_INICIO = date("Y-m-d H:i:s");
            }
            if($parametros->FECHA_FIN == date("Y-m-d")){
                $parametros->FECHA_FIN = date("Y-m-d H:i:s");
            }
             $data_personas = array(
                'NUMERO_GESTION' => $parametros->NUMERO_GESTION,
                'CODIGO_CLIENTE' => $parametros->CODIGO_CLIENTE,
                'FECHA_GESTION' => $parametros->FECHA_GESTION,
                'OBSERVACION' => $parametros->OBSERVACION,
                'FECHA_INICIO' => $parametros->FECHA_INICIO,
                'FECHA_FIN' => $parametros->FECHA_FIN,
                'CANTIDAD_GESTIONES' => $parametros->CANTIDAD_GESTIONES,
                'CANTIDAD_MINUTOS'=> $parametros->CANTIDAD_MINUTOS,
                'CODIGO_GESTOR'=> $parametros->CODIGO_GESTOR,
                'ESTADO'=> $parametros->ESTADO
                // 'CODIGO_PLAN'=> $parametros->CODIGO_PLAN
            );
            $where_personas = array(
                'NUMERO_GESTION = ?' => $parametros->NUMERO_GESTION
            );
            $update_personas = $db->update('LOG_GESTIONES', $data_personas, $where_personas);

            $comotermina = true;
            $estados = "";
            if($parametros->ENVIAREMAIL == "SI"){
            if ($parametros->ESTADO == 'E' && $parametros->CODIGO_GESTOR != 0) {
                $estados .= "Entrro a enviar email";
                $asistentes =  json_decode(self::obtenerasistente($parametros->CODIGO_GESTOR));
                $clientes =  json_decode(self::obtenercliente($parametros->CODIGO_CLIENTE));

                if($asistentes){
                    $estados .= "- Recupero asistente";
                  $nombre_gestor= $asistentes->DESCRIPCION_PERSONA;
                  $emailDestino= $asistentes->EMAIL_PERSONA;
                  $asunto = "Nueva Tarea";
                  $bodyTexto = "Cliente: ".$clientes->DESCRIPCION_PERSONA."\n\nTarea: ".$parametros->OBSERVACION."\n \n Tiempo estimado: ".$parametros->CANTIDAD_MINUTOS." mins. \n\nGestiones estimadas:".$parametros->CANTIDAD_GESTIONES;
                  $email = self::enviaremail($emailDestino,$nombre_gestor,$bodyTexto,$asunto);
                  $estados .= "- resultado de email".$email;
                    if(!$email){
                        $comotermina = false;
                        // echo json_encode(array("success" => false,"email" => $email));
                    }
                } else {
                    // echo json_encode(array("success" => false,"mensaje" => $asistentes));
                    $comotermina = false;
                }
                if($clientes){
                    $estados .= "- Recupero cliente";
                  $nombre_cliente= $clientes->DESCRIPCION_PERSONA;
                  $emailDestino= $clientes->EMAIL_PERSONA;
                  $asunto = "Su gestin se encuentra en proceso.";
                  $bodyTexto = "Su asistente de servicios es: ".$asistentes->DESCRIPCION_PERSONA."\n\nTarea: ".$parametros->OBSERVACION."\n\nTiempo estimado: ".$parametros->CANTIDAD_MINUTOS." mins.\n\nGestiones estimadas:".$parametros->CANTIDAD_GESTIONES."\n\n\n\nSi usted no desea recibir estas notificaciones, responda este correo con la frase desvincular de notificaciones.";
                  if($clientes->ENVIAR_EMAIL == "S"){$email = self::enviaremail($emailDestino,$nombre_cliente,$bodyTexto,$asunto);}
                    
                    if(!$email && $clientes->ENVIAR_EMAIL == "S"){
                        $comotermina = false;
                        // echo json_encode(array("success" => false,"email" => $email));
                    }
                } else {
                    $comotermina = false;
                    // echo json_encode(array("success" => false,"mensaje" => $asistentes));
                }
            }
            if ($parametros->ESTADO == 'F'){
                $clientes =  json_decode(self::obtenercliente($parametros->CODIGO_CLIENTE));
                if($clientes){            
                  $nombre_cliente= $clientes->DESCRIPCION_PERSONA;
                  $emailDestino= $clientes->EMAIL_PERSONA;
                  $asunto = "Notificación de gestión realizada.";
                  $bodyTexto = "Estimado : ".$clientes->DESCRIPCION_PERSONA."\nHemos culminado la gestión solicitada\n\nTarea: ".$parametros->OBSERVACION."\n\nTiempo empleado: ".$parametros->CANTIDAD_MINUTOS." mins.\n\nGestiones utilizadas:".$parametros->CANTIDAD_GESTIONES."\nGracias por confiar en San Solución\n\nCira Leon\nCoordinadora de Servicios\nSan Solución\n\n\nSi usted no desea recibir estas notificaciones, responda este correo con la frase desvincular de notificaciones.";
                 if($clientes->ENVIAR_EMAIL == "S"){$email = self::enviaremail($emailDestino,$nombre_cliente,$bodyTexto,$asunto);}
                    if(!$email && $clientes->ENVIAR_EMAIL == "S"){
                        $comotermina = false;
                    }
                } else {
                    $comotermina = false;
                // echo json_encode(array("success" => false,"mensaje" => $asistentes));
                }           
            }
         }
            
            $db->commit();
           // echo json_encode(array("success" => true));
            echo json_encode(array("success" => $comotermina, "estado" => $estados));
        } catch (Exception $e) {
            echo json_encode(array("success" => false, "code" => $e->getCode(), "mensaje" => $e->getMessage()));
            $db->rollBack();
        }
    }

    //cargamos lista de clientes
    public function getclienteAction()
    {
     $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $result = '';
        try {
             $db = Zend_Db_Table::getDefaultAdapter();
             $select = $db->select()
                ->from(array('C'=>'ADM_CLIENTES'),  array(
                             'C.CODIGO_CLIENTE',
                             'P.DESCRIPCION_PERSONA',
                             'P.NRO_DOCUMENTO_PERSONA'))
                    ->join(array('P' => 'ADM_PERSONAS'), 'P.CODIGO_PERSONA  = C.CODIGO_PERSONA')
                    ->order(array('C.CODIGO_CLIENTE DESC'))
                    ->distinct(true);
                
            $result = $db->fetchAll($select);
            $htmlResultado = '<option value="-1"></option>';
            foreach ($result as $arr) {
                $htmlResultado .= '<option value="' . $arr["CODIGO_CLIENTE"] . '">' .$arr["NRO_DOCUMENTO_PERSONA"] .' - '.
                trim(utf8_encode($arr["DESCRIPCION_PERSONA"])) . '</option>';
            }

        } catch (Exception $e) {
            echo json_encode(array("success" => false, "code" => $e->getCode(), "mensaje" => $e->getMessage()));
        }
        echo $htmlResultado;
    }

    //cargamos lista de asistentes de sercixios
    public function getasistenteserviciosAction()
    {
     $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $result = '';
        try {
             $db = Zend_Db_Table::getDefaultAdapter();
             $select = $db->select()
                ->from(array('C'=>'LOG_GESTORES'),  array(
                             'C.CODIGO_GESTOR',
                             'P.DESCRIPCION_PERSONA'))
                    ->join(array('P' => 'ADM_PERSONAS'), 'P.CODIGO_PERSONA  = C.CODIGO_PERSONA')
                    ->order(array('C.CODIGO_GESTOR DESC'))
                    ->distinct(true);
                
            $result = $db->fetchAll($select);
            $htmlResultado = '<option value="-1"></option>';
            foreach ($result as $arr) {
                $htmlResultado .= '<option value="' . $arr["CODIGO_GESTOR"] . '">' .$arr["CODIGO_GESTOR"].' - '.
                trim(utf8_encode($arr["DESCRIPCION_PERSONA"])) . '</option>';
            }

        } catch (Exception $e) {
            echo json_encode(array("success" => false, "code" => $e->getCode(), "mensaje" => $e->getMessage()));
        }
        echo $htmlResultado;
    }
     //cargamos lista de planes
    public function getplanesactivosAction()
    {
     $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $result = '';
        try {
             $db = Zend_Db_Table::getDefaultAdapter();
             $select = $db->select()
                ->from(array('C'=>'ADM_PLANES'),  array(
                             'C.CODIGO_PLAN',
                             'C.DESCRIPCION_PLAN'))
                     ->where('C.ESTADO_PLAN = ?', 'A')
                    ->order(array('C.CODIGO_PLAN DESC'))
                    ->distinct(true);
                
            $result = $db->fetchAll($select);
            $htmlResultado = '<option value="-1"></option>';
            foreach ($result as $arr) {
                $htmlResultado .= '<option value="' . $arr["CODIGO_PLAN"] . '">' .$arr["CODIGO_PLAN"].' - '.
                trim(utf8_encode($arr["DESCRIPCION_PLAN"])) . '</option>';
            }

        } catch (Exception $e) {
            echo json_encode(array("success" => false, "code" => $e->getCode(), "mensaje" => $e->getMessage()));
        }
        echo $htmlResultado;
    }

    public function getsuscripcionesAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $parametros = json_decode($this->getRequest()->getParam("parametros"));
        // print_r ($parametros);
        // die();
             $db = Zend_Db_Table::getDefaultAdapter();
             $select = $db->select()
                ->from(array('C'=>'ADM_SUSCRIPCIONES'),  array(
                             'C.CODIGO_SUSCRIPCION',
                             'C.CODIGO_PLAN',
                             'C.IMPORTE_GESTION',
                             'S.CANTIDAD_SALDO'))
                    ->join(array('S' => 'LOG_SALDO'), 'C.CODIGO_SUSCRIPCION  = S.CODIGO_SUSCRIPCION')
                     ->where('C.CODIGO_CLIENTE = ?', $parametros->CODIGO_CLIENTE)
                     ->where('C.ESTADO_SUSCRIPCION = ?', 'A');
                
            $result = $db->fetchAll($select);
            // print_r($result);
            if($result[0]['CODIGO_SUSCRIPCION'] != null){
                 echo json_encode(array(
                    'CODIGO_SUSCRIPCION' => $result[0]['CODIGO_SUSCRIPCION'] ,
                    'CODIGO_PLAN' => $result[0]['CODIGO_PLAN'] ,
                    'IMPORTE_GESTION' => $result[0]['IMPORTE_GESTION'],
                    'CANTIDAD_SALDO' => $result[0]['CANTIDAD_SALDO']
                 ));    
            }else{
                echo json_encode(array('success' => false ));
            }
        }

        public function getsaldoclienteAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $parametros = json_decode($this->getRequest()->getParam("parametros"));
        // print_r ($parametros);
        // die();
             $db = Zend_Db_Table::getDefaultAdapter();
             $select = $db->select()
                ->from(array('C'=>'VLOG_SALDOS'),  array(
                             'C.SALDO'))
                    ->where('C.CODIGO_CLIENTE = ?', $parametros->CODIGO_CLIENTE);
                
            $result = $db->fetchAll($select);
            // print_r($result);
            if($result[0]['SALDO'] != null){
                 echo json_encode(array(
                    'SALDO' => $result[0]['SALDO']
                 ));    
            }else{
                echo json_encode(array('success' => false ));
            }
        }

    public function enviaremail($emailDestino,$nombre,$bodyTexto,$asunto){
        try{
            // $config = array('ssl' => 'tls', 'port' => 587, 'auth' => 'login', 'username' => '', 'password' => '');
            $config = array( 'port' => 25, 'auth' => 'login', 'username' => 'pedido@sansolucion.com', 'password' => 'Pedido123.');
            // $smtpConnection = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $config);
            // $smtpConnection = new Zend_Mail_Transport_Smtp('gator4081.hostgator.com', $config);
            $smtpConnection = new Zend_Mail_Transport_Smtp('mail.sansolucion.com', $config);

            $mail = new Zend_Mail('utf-8');
            $mail->setBodyText($bodyTexto);
            $mail->setFrom('pedido@sansolucion.com', 'Informe');
            $mail->addTo($emailDestino, $nombre);
            $mail->setSubject($asunto);
            print_r($email);
            $mail->send($smtpConnection);
            return true;
        } catch (Exception $e){
            return $e->getMessage();
        }
    }

    public function obtenerasistente($codigo){
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

    public function obtenercliente($codigo){
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
    public function getplanesclienteAction()
    {
     $this->_helper->layout->disableLayout();
        
        $this->_helper->viewRenderer->setNoRender(true);
        $parametros = json_decode($this->getRequest()->getParam("parametros"));
        $resultado = array();
        try {
             $db = Zend_Db_Table::getDefaultAdapter();
             $select = $db->select()
                ->from(array('C'=>'vlog_saldos_planes'),  array(
                             'C.codigo_cliente',
                             'C.codigo_suscripcion',
                             'C.saldo',
                             'C.descripcion_plan'))
                     ->where('C.codigo_cliente = ?', $parametros->CODIGO_CLIENTE)
                     ->order(array('C.saldo ASC'));
                
            $result = $db->fetchAll($select);
            // $htmlResultado = '<option value="-1"></option>';
            // print_r($result);
            foreach ($result as $arr) {
                $plan = "Plan: ".$arr['descripcion_plan']." - Saldo: ".$arr['saldo'];
                // $objeto = array('plan' => $plan);
                array_push($resultado, $plan);
            }
            if (count($resultado) == 0) {
                echo json_encode(array("success" => false, "mensaje" => "No se encontraron datos"));
                
            }else{
                echo json_encode($resultado);
    
            }
            
        } catch (Exception $e) {
            echo json_encode(array("success" => false, "code" => $e->getCode(), "mensaje" => $e->getMessage()));
        }
    }

  
}