<?php

class administracion_usuariosController extends Zend_Controller_Action {

    public function init() {
		$parametrosLogueo = new Zend_Session_Namespace ( 'logueo' );
        $parametrosLogueo->unlock ();   
        if(!$parametrosLogueo->username){
                $r = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
                $r->gotoUrl('/login/login')->redirectAndExit();
            }
		if($parametrosLogueo->rol != 'ADM'){
                $r = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
                $r->gotoUrl('/menu/menu')->redirectAndExit();
            }
		
        $parametrosLogueo->lock();  

    }

    public function indexAction() {
	//$this->_helper->layout->disableLayout(false);
      //  $this->_helper->viewRenderer->setNoRender ( true );
		
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
                ->from(array('C'=>'CONF_USUARIO'),  array(
                             'C.COD_USUARIO',
                             'C.NOMBRE_APELLIDO',
                             'C.ID_USUARIO',
                             'C.ROL'))
                    ->order(array('C.COD_USUARIO DESC'));
                   
         if ($filtros != null) {
            if ($filtros->DESTINO != null) {
                $select->where("upper(C.NOMBRE_APELLIDO) like upper('%".$filtros->NOMBRE_APELLIDO."%')");
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
               
                $item['COD_USUARIO'],
                $item['NOMBRE_APELLIDO'],
                $item['ID_USUARIO'],
                $item['ID_USUARIO'],
                $item['ROL']
            );
            $arrayDatos ['columns'] = array(
                
                'COD_USUARIO',
                'NOMBRE_APELLIDO',
                'ID_USUARIO',
                'ID_USUARIO',
                'ROL'
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
            if(!$parametros->COD_USUARIO)
                $parametros->COD_USUARIO = 0;
            $data_personas = array(
                'NOMBRE_APELLIDO' => (trim($parametros->NOMBRE_APELLIDO)),
                'ID_USUARIO' => (trim($parametros->ID_USUARIO)),
                'ROL' => (trim($parametros->ROL)),
                'USUARIO_PASSWORD' => md5(strtoupper(trim($parametros->USUARIO_PASSWORD)))
            );
            $insert_personas = $db->insert('CONF_USUARIO', $data_personas);
           

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
                'COD_USUARIO' => ($parametros->COD_USUARIO),
                'NOMBRE_APELLIDO' => (trim($parametros->NOMBRE_APELLIDO)),
                'ID_USUARIO' => (trim($parametros->ID_USUARIO)),
                'ROL' => (trim($parametros->ROL)),
                'USUARIO_PASSWORD' => md5(strtoupper(trim($parametros->USUARIO_PASSWORD)))
            );
            $where_personas = array(
                'COD_USUARIO = ?' => $parametros->COD_USUARIO
            );
            $update_personas = $db->update('CONF_USUARIO', $data_personas, $where_personas);
            $db->commit();
           echo json_encode(array("success" => true));
        } catch (Exception $e) {
            echo json_encode(array("success" => false, "code" => $e->getCode(), "mensaje" => $e->getMessage()));
            $db->rollBack();
        }
    }

   
    
  
}