<?php

class logistica_suscripcionesController extends Zend_Controller_Action {

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
                ->from(array('G'=>'ADM_SUSCRIPCIONES'),  array(
                             'G.CODIGO_SUSCRIPCION',
                             'G.CODIGO_CLIENTE',
                             'PC.DESCRIPCION_PERSONA AS DESCRIPCION_CLIENTE',
                             'G.CODIGO_PLAN',
                             'PL.DESCRIPCION_PLAN',
                             'G.FECHA_SUSCRIPCION',
                             'G.FECHA_VENCIMIENTO',
                             'G.FECHA_ACREDITACION',
                             'G.IMPORTE_GESTION',
                             'G.ESTADO_SUSCRIPCION'))
                   ->join(array('C' => 'ADM_CLIENTES'), 'G.CODIGO_CLIENTE  = C.CODIGO_CLIENTE')
                   ->join(array('PC' => 'ADM_PERSONAS'), 'PC.CODIGO_PERSONA  = C.CODIGO_PERSONA')
                   ->join(array('PL' => 'ADM_PLANES'), 'PL.CODIGO_PLAN  = G.CODIGO_PLAN')
                   ->order(array('G.CODIGO_SUSCRIPCION DESC'));
                   
         if ($filtros != null) {
            if ($filtros->DESCRIPCION_PERSONA != null) {
                $select->where("upper(PC.DESCRIPCION_PERSONA) like upper('%".$filtros->DESCRIPCION_PERSONA."%')");
            }
            if ($filtros->FECHA_VENCIMIENTO != null) {
                $select->where("G.FECHA_VENCIMIENTO = ?", $filtros->FECHA_VENCIMIENTO);
            }
            if ($filtros->DESCRIPCION_PLAN != null) {
                $select->where("upper(PL.DESCRIPCION_PLAN) like upper('%".$filtros->DESCRIPCION_PLAN."%')");
            }
            if ($filtros->ESTADO_SUSCRIPCION != -1) {
                $select->where("G.ESTADO_SUSCRIPCION = ?", $filtros->ESTADO_SUSCRIPCION);
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
                $item['CODIGO_SUSCRIPCION'],
                $item['CODIGO_CLIENTE'],
                $item['DESCRIPCION_CLIENTE'],
                $item['CODIGO_PLAN'],
                $item['DESCRIPCION_PLAN'],
                $item['FECHA_SUSCRIPCION'],
                $item['FECHA_VENCIMIENTO'],
                $item['FECHA_ACREDITACION'],
                $item['IMPORTE_GESTION'],
                $item['ESTADO_SUSCRIPCION']
            );
            $arrayDatos ['columns'] = array(
                    'CODIGO_SUSCRIPCION',
                    'CODIGO_CLIENTE',
                    'DESCRIPCION_CLIENTE',
                    'CODIGO_PLAN',
                    'DESCRIPCION_PLAN',
                    'FECHA_SUSCRIPCION',
                    'FECHA_VENCIMIENTO',
                    'FECHA_ACREDITACION',
                    'IMPORTE_GESTION',
                    'ESTADO_SUSCRIPCION'
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

        public function  modificarAction(){
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $parametros = json_decode($this->getRequest()->getParam("parametros"));
        try {

            $db = Zend_Db_Table::getDefaultAdapter();
            $db->beginTransaction();
            
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
            $where_personas = array(
                'CODIGO_SUSCRIPCION = ?' => $parametros->CODIGO_SUSCRIPCION
            );
            $update_personas = $db->update('ADM_SUSCRIPCIONES', $data_personas, $where_personas);
            $db->commit();
           echo json_encode(array("success" => true));
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
                trim(($arr["DESCRIPCION_PERSONA"])) . '</option>';
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
                if($arr["TIPO_PLAN"] == 'C'){
                    $TIPO_PLAN = 'CASUAL';
                }else if($arr["TIPO_PLAN"] == 'A'){
                    $TIPO_PLAN = 'ABIERTO';
                }else{
                    $TIPO_PLAN = 'MENSUAL';
                }
                
                $htmlResultado .= '<option value="' . $arr["CODIGO_PLAN"] . '">' .$arr["CODIGO_PLAN"].' - '.
                trim(($arr["DESCRIPCION_PLAN"]))." - ".$TIPO_PLAN. '</option>';
            }

        } catch (Exception $e) {
            echo json_encode(array("success" => false, "code" => $e->getCode(), "mensaje" => $e->getMessage()));
        }
        echo $htmlResultado;
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
                ->where('PL.TIPO_PLAN = ?', 'M')
                ->where('C.ESTADO_SUSCRIPCION = ?', 'A')
                ->where('LS.CANTIDAD_SALDO > ?', 0); // esta linea comentar para que solo permita cargar un plan mensual
                
                $result = $db->fetchAll($select);   
            }
             
             // print_r($result);
            if($result[0]['CANTIDAD'] == 0){
                 return true;    
            }else{
                return false;
            }
    }
  //modificado mayuscula
}