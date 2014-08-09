<?php

class logistica_gestionesController extends Zend_Controller_Action {

     public function init() {
        // $parametrosLogueo = new Zend_Session_Namespace ( 'logueo' );
        // $parametrosLogueo->unlock ();   
        // if(!$parametrosLogueo->username){
        //         $r = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
        //         $r->gotoUrl('/login/login')->redirectAndExit();
        //     }
        // $parametrosLogueo->lock();    
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
                             'P.DESCRIPCION_PERSONA',
                             'G.CODIGO_GESTOR',
                             'G.CODIGO_USUARIO',
                             'G.ESTADO',
                             'G.CANTIDAD_GESTIONES',
                             'G.CANTIDAD_ADICIONALES',
                             'G.OBSERVACION'))
                   ->join(array('C' => 'ADM_CLIENTES'), 'G.CODIGO_CLIENTE  = C.CODIGO_CLIENTE')
                   ->join(array('P' => 'ADM_PERSONAS'), 'P.CODIGO_PERSONA  = C.CODIGO_PERSONA')
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
                $item['DESCRIPCION_PERSONA'],
                $item['CODIGO_GESTOR'],
                $item['CODIGO_USUARIO'],
                $item['ESTADO'],
                $item['CANTIDAD_GESTIONES'],
                $item['CANTIDAD_ADICIONALES'],
                $item['OBSERVACION']
            );
            $arrayDatos ['columns'] = array(
                    'NUMERO_GESTION',
                    'FECHA_GESTION',
                    'FECHA_INICIO',
                    'FECHA_FIN',
                    'CODIGO_CLIENTE',
                    'DESCRIPCION_PERSONA',
                    'CODIGO_GESTOR',
                    'CODIGO_USUARIO',
                    'ESTADO',
                    'CANTIDAD_GESTIONES',
                    'CANTIDAD_ADICIONALES',
                    'OBSERVACION'
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
                $parametros->CODIGO_GESTOR = 0;
            $data_personas = array(
                'NUMERO_GESTION' => $parametros->NUMERO_GESTION,
                'CODIGO_CLIENTE' => $parametros->CODIGO_CLIENTE,
                'FECHA_GESTION' => $parametros->FECHA_GESTION,
                'OBSERVACION' => $parametros->OBSERVACION,
                'FECHA_INICIO' =>$parametros->FECHA_INICIO,
                'FECHA_FIN' => $parametros->FECHA_FIN,
                'CANTIDAD_GESTIONES' => $parametros->CANTIDAD_GESTIONES,
                'CANTIDAD_ADICIONALES'=> $parametros->CANTIDAD_ADICIONALES,
                'CODIGO_GESTOR'=> $parametros->CODIGO_GESTOR,
                'CODIGO_USUARIO'=> $parametrosLogueo->cod_usuario,
                'ESTADO'=> $parametros->ESTADO     
            );
            $insert_personas = $db->insert('LOG_GESTIONES', $data_personas);
            $parametrosLogueo->lock(); 
            $db->commit();
           echo json_encode(array("success" => true));
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
                'CANTIDAD_ADICIONALES'=> $parametros->CANTIDAD_ADICIONALES,
                'CODIGO_GESTOR'=> $parametros->CODIGO_GESTOR,
                'ESTADO'=> $parametros->ESTADO     
            );
            $where_personas = array(
                'NUMERO_GESTION = ?' => $parametros->NUMERO_GESTION
            );
            $update_personas = $db->update('LOG_GESTIONES', $data_personas, $where_personas);
            $db->commit();
           echo json_encode(array("success" => true));
        } catch (Exception $e) {
            echo json_encode(array("success" => false, "code" => $e->getCode(), "mensaje" => $e->getMessage()));
            $db->rollBack();
        }
    }

  
}