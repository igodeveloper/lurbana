<?php

class administracion_perfilclientesController extends Zend_Controller_Action {

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

   

    public function perfilclienteAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $parametros = json_decode($this->getRequest()->getParam("parametros"));
        // die("entre");
              $select_plan = $db->select()
                ->from(array('C'=>'ADM_PLANES'),  
                  array(
                             'C.TIPO_PLAN'))
                     ->where('C.ESTADO_PLAN = ?', 'A')
                     ->where('C.CODIGO_PLAN = ?', '5');
            $result_plan = $db->fetchAll($select_plan);
            // print_r($result);
            $arr = array();
            $row = mysql_result($result_plan, 0, 0);
            print_r($row); die();
            while ($row = mysql_result($result, 0, 0)){
                    
                   array_push($arr, $row);
            }
            if($arr){
                 echo json_encode($arr);    
            }else{
                echo json_encode(array('success' => false ));
            }
        }

}

     