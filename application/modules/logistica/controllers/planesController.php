<?php

class logistica_planesController extends Zend_Controller_Action {

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
                ->from(array('C'=>'ADM_PLANES'),  array(
                             'C.CODIGO_PLAN',
                             'C.DESCRIPCION_PLAN',
                             'C.TIPO_PLAN',
                             'C.CANTIDAD_PLAN',
                             'C.COSTO_PLAN',
                             'C.ESTADO_PLAN'))
                   
                    ->order(array('C.CODIGO_PLAN DESC'));
                   
         if ($filtros != null) {
            if ($filtros->DESCRIPCION_PLAN != null) {
                $select->where("upper(C.DESCRIPCION_PLAN) like upper('%".$filtros->DESCRIPCION_PLAN."%')");
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
               
                $item['CODIGO_PLAN'],
                $item['DESCRIPCION_PLAN'],
                $item['TIPO_PLAN'],
                $item['CANTIDAD_PLAN'],
                $item['COSTO_PLAN'],
                $item['ESTADO_PLAN']            );
            $arrayDatos ['columns'] = array(
                
                'CODIGO_PLAN',
                'DESCRIPCION_PLAN',
                'TIPO_PLAN',
                'CANTIDAD_PLAN',
                'COSTO_PLAN',
                'ESTADO_PLAN'
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

            $db = Zend_Db_Table::getDefaultAdapter();
            $db->beginTransaction();
            if(!$parametros->CODIGO_PLAN)
                $parametros->CODIGO_PLAN = 0;
            $data_personas = array(
                'CODIGO_PLAN' => ($parametros->CODIGO_PLAN),
                'DESCRIPCION_PLAN' => (trim($parametros->DESCRIPCION_PLAN)),
                'TIPO_PLAN' => (trim($parametros->TIPO_PLAN)),
                'CANTIDAD_PLAN' => (trim($parametros->CANTIDAD_PLAN)),
                'COSTO_PLAN' => (trim($parametros->COSTO_PLAN)),
                'ESTADO_PLAN' => (trim($parametros->ESTADO_PLAN))
            );
            $insert_personas = $db->insert('ADM_PLANES', $data_personas);
           

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
            $data_personas = array(
                'CODIGO_PLAN' => ($parametros->CODIGO_PLAN),
                'DESCRIPCION_PLAN' => (trim($parametros->DESCRIPCION_PLAN)),
                'TIPO_PLAN' => (trim($parametros->TIPO_PLAN)),
                'CANTIDAD_PLAN' => (trim($parametros->CANTIDAD_PLAN)),
                'COSTO_PLAN' => (trim($parametros->COSTO_PLAN)),
                'ESTADO_PLAN' => (trim($parametros->ESTADO_PLAN))
            );
            $where_personas = array(
                'CODIGO_PLAN = ?' => $parametros->CODIGO_PLAN
            );
            $update_personas = $db->update('ADM_PLANES', $data_personas, $where_personas);
            $db->commit();
           echo json_encode(array("success" => true));
        } catch (Exception $e) {
            echo json_encode(array("success" => false, "code" => $e->getCode(), "mensaje" => $e->getMessage()));
            $db->rollBack();
        }
    }

//modificado mayuscula  
}