<?php

class logistica_destinosController extends Zend_Controller_Action {

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
                ->from(array('C'=>'LOG_DESTINOS'),  array(
                             'C.COD_DESTINO',
                             'C.DESCRIPCION AS DESTINO',
                             'C.DIRECCION',
                             'C.CODIGO_ZONA',
                             'C.UBICACION',
                             'Z.DESCRIPCION AS DESCRIPCION_ZONA'))
                    ->join(array('Z' => 'LOG_ZONAS'), 'C.CODIGO_ZONA  = Z.CODIGO_ZONA')
                    ->order(array('C.COD_DESTINO DESC'));
                   
         if ($filtros != null) {
            if ($filtros->DESTINO != null) {
                $select->where("upper(C.DESCRIPCION) like upper('%".$filtros->DESTINO."%')");
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
            $arrayDatos ['cell'] = array(
               
                $item['COD_DESTINO'],
                $item['DESTINO'],
                $item['DIRECCION'],
                $item['CODIGO_ZONA'],
                $item['DESCRIPCION_ZONA'],
                $item['UBICACION']
            );
            $arrayDatos ['columns'] = array(
                
                'COD_DESTINO',
                'DESTINO',
                'DIRECCION',
                'CODIGO_ZONA',
                
                'DESCRIPCION_ZONA',
                'UBICACION'
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
            if(!$parametros->COD_DESTINO)
                $parametros->COD_DESTINO = 0;
            $data_personas = array(
                'DESCRIPCION' => (trim($parametros->DESCRIPCION)),
                'DIRECCION' => (trim($parametros->DIRECCION)),
                'CODIGO_ZONA' => (trim($parametros->CODIGO_ZONA)),
                'UBICACION' => (trim($parametros->UBICACION))
            );
            $insert_personas = $db->insert('LOG_DESTINOS', $data_personas);
           

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
                'COD_DESTINO' => ($parametros->COD_DESTINO),
                'DESCRIPCION' => (trim($parametros->DESCRIPCION)),
                'DIRECCION' => (trim($parametros->DIRECCION)),
                'CODIGO_ZONA' => (trim($parametros->CODIGO_ZONA)),
                'UBICACION' => (trim($parametros->UBICACION))
            );
            $where_personas = array(
                'COD_DESTINO = ?' => $parametros->COD_DESTINO
            );
            $update_personas = $db->update('LOG_DESTINOS', $data_personas, $where_personas);
            $db->commit();
           echo json_encode(array("success" => true));
        } catch (Exception $e) {
            echo json_encode(array("success" => false, "code" => $e->getCode(), "mensaje" => $e->getMessage()));
            $db->rollBack();
        }
    }

//modificado mayuscula  
}