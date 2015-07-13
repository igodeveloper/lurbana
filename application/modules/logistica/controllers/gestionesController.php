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
                             'G.GENTILEZA',
                             'PL.DESCRIPCION_PLAN'))
                   ->join(array('C' => 'ADM_CLIENTES'), 'G.CODIGO_CLIENTE  = C.CODIGO_CLIENTE')
                   ->join(array('P' => 'ADM_PERSONAS'), 'P.CODIGO_PERSONA  = C.CODIGO_PERSONA')
                   ->joinLeft(array('GP' => 'LOG_GESTORES'), 'G.CODIGO_GESTOR  = GP.CODIGO_GESTOR')
                   ->joinLeft(array('PG' => 'ADM_PERSONAS'), 'PG.CODIGO_PERSONA  = GP.CODIGO_PERSONA')
                   ->joinLeft(array('PL' => 'ADM_PLANES'), 'PL.CODIGO_PLAN  = G.CODIGO_PLAN')
                   ->order(array('G.NUMERO_GESTION DESC'));
                   
         if ($filtros != null) {
            if ($filtros->DESCRIPCION_CLIENTE != null) {
                $select->where("upper(P.DESCRIPCION_PERSONA) like upper('%".$filtros->DESCRIPCION_CLIENTE."%')");
            }
            if ($filtros->NRO_DOCUMENTO_PERSONA != null) {
                $select->where("P.NRO_DOCUMENTO_PERSONA = ?", $filtros->NRO_DOCUMENTO_PERSONA);
            }
            if ($filtros->ASISTENTE != null) {
                $select->where("upper(PG.DESCRIPCION_PERSONA) like upper('%".$filtros->ASISTENTE."%')");
            }
            if ($filtros->ESTADO_GESTION != -1) {
                $select->where("G.ESTADO = ?", $filtros->ESTADO_GESTION);
            }
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
                $item['DESCRIPCION_PLAN'],
                $item['GENTILEZA']
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
                    'DESCRIPCION_PLAN',
                    'GENTILEZA'
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
                'ESTADO'=> $parametros->ESTADO,
                'GENTILEZA'=> $parametros->GENTILEZA
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
                'ESTADO'=> $parametros->ESTADO,
                'GENTILEZA'=> $parametros->GENTILEZA
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
                trim($arr["DESCRIPCION_PERSONA"]) . '</option>';
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
                trim($arr["DESCRIPCION_PERSONA"]) . '</option>';
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
                             'C.DESCRIPCION_PLAN', 'C.TIPO_PLAN'))
                     ->where('C.ESTADO_PLAN = ?', 'A')
                    ->order(array('C.CODIGO_PLAN DESC'))
                    ->distinct(true);
                
            $result = $db->fetchAll($select);
            $htmlResultado = '<option value="-1"></option>';
            foreach ($result as $arr) {
              $TIPO_PLAN = ($arr["TIPO_PLAN"]=='M')?'Mensual':'Casual';
                $htmlResultado .= '<option value="' . $arr["CODIGO_PLAN"] . '">' .$arr["CODIGO_PLAN"].' - '.
                trim($arr["DESCRIPCION_PLAN"]).' - '.$TIPO_PLAN. '</option>';
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
                             'C.SALDO','C.TIPO_CLIENTE'))
                    ->where('C.CODIGO_CLIENTE = ?', $parametros->CODIGO_CLIENTE);
                
            $result = $db->fetchAll($select);
            // print_r($result);
            if($result[0]['SALDO'] != null){
                 echo json_encode(array(
                    'SALDO' => $result[0]['SALDO'], 'TIPO_CLIENTE' => $result[0]['TIPO_CLIENTE'] 
                 ));    
            }else{
                echo json_encode(array('success' => false ));
            }
        }

    public function enviaremail($emailDestino,$nombre,$bodyTexto,$asunto){
        try{
            // $config = array('ssl' => 'tls', 'port' => 587, 'auth' => 'login', 'username' => '', 'password' => '');
            $config = array( 'port' => 25, 'auth' => 'login', 'username' => 'pedido@sansolucion.com', 'password' => 'pedido123.');
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
                ->from(array('C'=>'VLOG_SALDOS_PLANES'),  array(
                             'C.CODIGO_CLIENTE',
                             'C.CODIGO_SUSCRIPCION',
                             'C.SALDO',
                             'C.DESCRIPCION_PLAN','C.TIPO_PLAN'))
                     ->where('C.CODIGO_CLIENTE = ?', $parametros->CODIGO_CLIENTE)
                     ->order(array('C.SALDO ASC'));
                
            $result = $db->fetchAll($select);
            // $htmlResultado = '<option value="-1"></option>';
            // print_r($result);
            $resultado = array();
            foreach ($result as $arr) {
                $plan = "Plan: ".$arr['DESCRIPCION_PLAN']." - Tipo: ".$arr['TIPO_PLAN']." - Saldo: ".$arr['SALDO'];
                // $objeto = array('plan' => $plan);
                // array_push($resultado, $plan);
                array_push($resultado, array("plan" => $plan, "tipo" => $arr['TIPO_PLAN']));
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

    public function getnotificacionesAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
             $db = Zend_Db_Table::getDefaultAdapter();
             $select = $db->select()
                ->from(array('C'=>'records'),  array(
                             'COUNT(*) AS PENDIENTES'))
                    ->where('C.CHECKED = 0 or C.CHECKED = ""');
                
            $result = $db->fetchAll($select);
            // print_r($result);
            if($result[0]['PENDIENTES'] != null){
                 echo json_encode(array(
                    'PENDIENTES' => $result[0]['PENDIENTES']
                 ));    
            }else{
                echo json_encode(array('success' => false ));
            }
        }

    public function guardarsuscripcionAction(){
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $parametros = json_decode($this->getRequest()->getParam("parametros"));
        try {
            $parametrosLogueo = new Zend_Session_Namespace ( 'logueo' );
            $parametrosLogueo->unlock ();
            $suscripcion = self::verificasuscripcion($parametros->CODIGO_CLIENTE, $parametros->CODIGO_PLAN);
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->beginTransaction();
            // print_r($parametros);die();
            if(!$parametros->CODIGO_SUSCRIPCION)
                $parametros->CODIGO_SUSCRIPCION = 0;

            $data_personas = array(
                'CODIGO_SUSCRIPCION' => $parametros->CODIGO_SUSCRIPCION,
                'CODIGO_CLIENTE' => $parametros->CODIGO_CLIENTE,
                'CODIGO_PLAN' => $parametros->CODIGO_PLAN,
                'FECHA_SUSCRIPCION' => $parametros->FECHA_SUSCRIPCION,
                'FECHA_VENCIMIENTO' =>$parametros->FECHA_VENCIMIENTO,
                'FECHA_ACREDITACION' => $parametros->FECHA_ACREDITACION,
                'IMPORTE_GESTION' => $parametros->IMPORTE_GESTION,
                'ESTADO_SUSCRIPCION'=> $parametros->ESTADO_SUSCRIPCION
            );
            $parametrosLogueo->lock(); 
            if($suscripcion){
                $insert_personas = $db->insert('ADM_SUSCRIPCIONES', $data_personas);
                $db->commit();
                echo json_encode(array("success" => true));
            }else{
                $db->rollBack();
                echo json_encode(array("success" => false, "code" => 1, "mensaje" => "No puede suscribirse a mas de un plan mensual"));
            }
                       
        } catch (Exception $e) {
            echo json_encode(array("success" => false, "code" => $e->getCode(), "mensaje" => $e->getMessage()));
            $db->rollBack();
        }
    }
    public function verificasuscripcion($codigo_cliente,$codigo_plan){

             $db = Zend_Db_Table::getDefaultAdapter();
             
              $select_plan = $db->select()
                ->from(array('C'=>'ADM_PLANES'),  array(
                             'C.TIPO_PLAN'))
                     ->where('C.ESTADO_PLAN = ?', 'A')
                     ->where('C.CODIGO_PLAN = ?', $codigo_plan);
            $result_plan = $db->fetchAll($select_plan);


            if($result_plan[0]['TIPO_PLAN'] == 'M'){
                $select = $db->select()
                ->from(array('C'=>'ADM_SUSCRIPCIONES'),  array(
                             'COUNT(*) AS CANTIDAD'
                             ))
                ->join(array('LS' => 'LOG_SALDO'), 'LS.CODIGO_SUSCRIPCION  = C.CODIGO_SUSCRIPCION')
                ->join(array('PL' => 'ADM_PLANES'), 'PL.CODIGO_PLAN  = C.CODIGO_PLAN')                   
                ->where('C.CODIGO_CLIENTE = ?', $codigo_cliente)
                ->where('PL.TIPO_PLAN IN ("M","A")')
                ->where('C.ESTADO_SUSCRIPCION = ?', 'A');
                // ->where('LS.CANTIDAD_SALDO > ?', 0);
                
                $result = $db->fetchAll($select);   
            }
             
             // print_r($result);
            if($result[0]['CANTIDAD'] == 0){
                 return true;    
            }else{
                return false;
            }
    }

    public function getimportesuscripcionAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $parametros = json_decode($this->getRequest()->getParam("parametros"));
        // print_r ($parametros);
        // die();
             $db = Zend_Db_Table::getDefaultAdapter();
             $select = $db->select()
                ->from(array('C'=>'ADM_PLANES'),  array(
                             'C.COSTO_PLAN',
                            'C.CANTIDAD_PLAN'
                             ))
                    
                     ->where('C.CODIGO_PLAN = ?', $parametros->CODIGO_PLAN);
                
            $result = $db->fetchAll($select);
            // print_r($result);
            if($result[0]['COSTO_PLAN'] != null){
                $costo = $result[0]['COSTO_PLAN'];
                $cantidad = $result[0]['CANTIDAD_PLAN'];
                $importe = $costo/$cantidad;
                 echo json_encode(array('IMPORTE_GESTION' => $importe));    
            }else{
                echo json_encode(array('success' => false ));
            }
        }

    public function getlistatrackAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $parametros = json_decode($this->getRequest()->getParam("parametros"));
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
            ->from(array('A'=>'LOG_GESTIONES_ACT'),  array(
                         'A.CODIGO_GESTION',
                         'A.ORDEN',
                         'A.PROCESO',
                         'A.CODIGO_ZONA',
                         'B.DESCRIPCION AS DESCRIPCION_ZONA',
                         'A.DESTINO',
                         'A.DESCRIPCION AS GESTIONES',
                         'A.REALIZADO',
                         'A.FEC_HORA_REALIZ'))
                ->join(array('B' => 'LOG_ZONAS'), 'A.CODIGO_ZONA  = B.CODIGO_ZONA')
                 ->where('A.CODIGO_GESTION = ?',  $parametros->NUMERO_GESTION)     
                 // ->where('A.CODIGO_GESTION = ?',  1113)
                 ->order(array('A.ORDEN ASC'));
        $result = $db->fetchAll($select);
        $arr = array();
        foreach ($result as $row) {     
            array_push($arr,array(
                                  'CODIGO_GESTION' => $row["CODIGO_GESTION"],
                                  'ORDEN' => $row["ORDEN"],
                                  'PROCESO' => $row["PROCESO"],
                                  'CODIGO_ZONA' => $row["CODIGO_ZONA"],
                                  'DESCRIPCION_ZONA' => $row["DESCRIPCION_ZONA"],
                                  'DESTINO' => $row["DESTINO"],
                                  'DESCRIPCION' => $row["GESTIONES"],
                                  'REALIZADO' => ($row["REALIZADO"] == 1 ? 'Si' : 'No'),
                                  'FEC_HORA_REALIZ' => $row["FEC_HORA_REALIZ"]));
        }
       
             echo json_encode($arr); 
       
    }

    public function getzonasAction(){
     $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $result = '';
        try {
             $db = Zend_Db_Table::getDefaultAdapter();
             $select = $db->select()
                ->from(array('C'=>'LOG_ZONAS'),  array(
                             'C.CODIGO_ZONA',
                             'C.DESCRIPCION'))
                    ->order(array('C.DESCRIPCION DESC'))
                    ->distinct(true);
                
            $result = $db->fetchAll($select);
            $htmlResultado = '<option value="-1">Zonas</option>';
            foreach ($result as $arr) {
                $htmlResultado .= '<option value="' . $arr["CODIGO_ZONA"] . '">'.$arr["DESCRIPCION"].'</option>';
            }

        } catch (Exception $e) {
            echo json_encode(array("success" => false, "code" => $e->getCode(), "mensaje" => $e->getMessage()));
        }
        echo $htmlResultado;
    }

    public function deteletrackAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $parametros = json_decode($this->getRequest()->getParam("parametros"));
        try {
             $db = Zend_Db_Table::getDefaultAdapter();
             $db->delete('LOG_GESTIONES_ACT', array(
                  'CODIGO_GESTION = ?' => $parametros->CODIGO_GESTION,
                  'ORDEN = ?' => $parametros->ORDEN
              ));
            echo json_encode(array('success' => true ));
        } catch (Exception $e) {
            echo json_encode(array("success" => false, "code" => $e->getCode(), "mensaje" => $e->getMessage()));
        }
    }

    public function updatetrackAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $parametros = json_decode($this->getRequest()->getParam("parametros"));
        try {
             $db = Zend_Db_Table::getDefaultAdapter();
             $select = $db->select()
                ->from(array('C'=>'LOG_GESTIONES_ACT'),  array(
                             'MAX(C.ORDEN) AS ORDEN_ULTIMO'))
                ->where('C.CODIGO_GESTION = ?',  $parametros->NUMERO_GESTION);                
              $orden_insertar = $db->fetchAll($select);

            
             if(empty($parametros->ORDEN)){
                 $data = array(
                    'CODIGO_GESTION' => $parametros->NUMERO_GESTION,
                    'ORDEN' => $orden_insertar[0]['ORDEN_ULTIMO']+ 1,
                    'PROCESO' => $parametros->PROCESO,
                    'CODIGO_ZONA' => $parametros->CODIGO_ZONA,
                    'DESTINO' => $parametros->DESTINO,
                    'DESCRIPCION' => $parametros->DESCRIPCION,
                    'REALIZADO' => $parametros->REALIZADO,
                    'FEC_HORA_REALIZ' => $parametros->FEC_HORA_REALIZ
                );
                $insert = $db->insert('LOG_GESTIONES_ACT', $data);
             }else{
                $data = array(
                    'PROCESO' => $parametros->PROCESO,
                    'CODIGO_ZONA' => $parametros->CODIGO_ZONA,
                    'DESCRIPCION' => $parametros->DESCRIPCION,
                    'DESTINO' => $parametros->DESTINO,
                    'REALIZADO' => $parametros->REALIZADO,
                    'FEC_HORA_REALIZ' => $parametros->FEC_HORA_REALIZ
                );
                $where = array(
                  'CODIGO_GESTION = ?' => $parametros->NUMERO_GESTION,
                  'ORDEN = ?' => $parametros->ORDEN
                );
                $udate = $db->update('LOG_GESTIONES_ACT', $data, $where);
             }
            
             

            echo json_encode(array('success' => true ));
        } catch (Exception $e) {
            echo json_encode(array("success" => false, "code" => $e->getCode(), "mensaje" => $e->getMessage()));
        }
    }


  
}