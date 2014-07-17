<?php

class administracion_gestoresController extends Zend_Controller_Action {

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
	//$this->_helper->layout->disableLayout(false);
      //  $this->_helper->viewRenderer->setNoRender ( true );
		
    }

   
    

  
}