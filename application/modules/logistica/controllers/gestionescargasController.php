<?php

class logistica_gestionescargasController extends Zend_Controller_Action {

     public function init() {
        //$this->view->status = $this->_getParam('id');
        $parametrosLogueo = new Zend_Session_Namespace ( 'logueo' );
        $parametrosLogueo->unlock (); 
        if(!$parametrosLogueo->username){
                $r = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
                $r->gotoUrl('/login/login')->redirectAndExit();
            }              
        $parametrosLogueo->lock();  

        $userProfileNamespace = new Zend_Session_Namespace('userProfileNamespace');
        $userProfileNamespace->unlock();
        $gola = "1";
        $gola.= $this->_getParam('id');
        echo $this->_getParam('id');
        $userProfileNamespace->id =200;
        $userProfileNamespace->id2 =trim(utf8_decode($gola));
        $userProfileNamespace->lock();
       
    }

    public function indexAction() {
           
    }

     public function buscarAction() {

        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender ( true );
        $userProfileNamespace = new Zend_Session_Namespace('userProfileNamespace');
        $userProfileNamespace->unlock();
        
         echo $userProfileNamespace->id2 ;
    }

    


  
}