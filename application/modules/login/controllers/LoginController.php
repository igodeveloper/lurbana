<?php

class Login_loginController extends Zend_Controller_Action {

    public function init() {
        $parametrosLogueo = new Zend_Session_Namespace ( 'logueo' );
        $parametrosLogueo->unlock ();
        if (Zend_Session::namespaceIsset('logueo')) {
          $p = Zend_Session::namespaceUnset('logueo');
        }
        $parametrosLogueo->lock();


    }

    public function indexAction() {
	//$this->_helper->layout->disableLayout(false);
      //  $this->_helper->viewRenderer->setNoRender ( true );
		
    }

    public function usuariodataAction(){

    	$this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender ( true );
        $parametros = json_decode($this->getRequest ()->getParam ("parametros"));
         $db = Zend_Db_Table::getDefaultAdapter();
   	 	$select = $db->select()
                ->from(array('C' => 'CONF_USUARIO'), 
                       array('C.COD_USUARIO',
                             'C.NOMBRE_APELLIDO','C.ROL'));
        
        if ($parametros->username != null && $parametros->password != null ) {

            $select->where("upper(C.ID_USUARIO)= ?", strtoupper(trim($parametros->username)));
			$pass = strtoupper ($parametros->password);
            $select->where("C.USUARIO_PASSWORD = ?", md5($pass));
            $result = $db->fetchAll($select);
           
            $parametrosLogueo = new Zend_Session_Namespace ( 'logueo' );
        	$parametrosLogueo->unlock ();        
        	foreach ($result as $arr) {      
                               
	            $cod_usuario = trim(($arr["COD_USUARIO"]));
	            $parametrosLogueo->username = trim($parametros->username);
	            $parametrosLogueo->cod_usuario = trim(($arr["COD_USUARIO"]));
                $parametrosLogueo->desc_usuario = trim(($arr["NOMBRE_APELLIDO"]));         
                $parametrosLogueo->rol = trim(($arr["ROL"]));         
                $parametrosLogueo->id = "";         
        	}
	        $parametrosLogueo->lock(); 
	                    
        }else{
        	
        }
        if($cod_usuario){
	       	echo json_encode(array("success" => true));
	    }  else{
	    	echo json_encode(array("success" => false));
	    }       
    }  

//modificado mayuscula  
}