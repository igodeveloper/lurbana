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

              $db = Zend_Db_Table::getDefaultAdapter();
             $select = $db->select()
                ->from(array('C'=>'ADM_CLIENTES'),  array(
                             'C.CODIGO_CLIENTE',
                             'P.DESCRIPCION_PERSONA',
                             'P.NRO_DOCUMENTO_PERSONA, CONCAT(P.TELEFONO_PERSONA, \' / \', P.CELULAR_PERSONA) AS TELEFONO',
                             'P.DIRECCION_PERSONA, IF((SELECT COUNT(*) AS PLAN FROM VLOG_SALDOS_PLANES WHERE VLOG_SALDOS_PLANES.CODIGO_CLIENTE= '.$parametros->CODIGO_CLIENTE.' AND VLOG_SALDOS_PLANES.TIPO_PLAN = \'M\' AND VLOG_SALDOS_PLANES.SALDO > 0) > 0, \'Mensual\', \'Casual\') AS TIPO_CLIENTE',
                             
                             ))
                     ->join(array('P' => 'ADM_PERSONAS'), 'C.CODIGO_PERSONA = P.CODIGO_PERSONA')
                     ->where('C.CODIGO_CLIENTE = ?', $parametros->CODIGO_CLIENTE);
                
            $result = $db->fetchAll($select);
            // print_r($result);die();
            $arr = array();
            foreach ($result as $row) {
              array_push($arr, $row);
            }
            if(count($arr)>0){
                 echo json_encode($arr);    
            }else{
                echo json_encode(array('success' => false ));
            }
           
        }

    public function gestionesclienteAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $parametros = json_decode($this->getRequest()->getParam("parametros"));
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
            ->from(array('LG'=>'LOG_GESTIONES'),  array(
                         'LG.FECHA_GESTION',
                         'LG.FECHA_FIN',
                         'LG.CODIGO_GESTOR',
                         'P.DESCRIPCION_PERSONA',
                         'LG.ESTADO',
                         'LG.CANTIDAD_GESTIONES',
                         'LG.OBSERVACION'))
                 ->join(array('G' => 'LOG_GESTORES'), 'LG.CODIGO_GESTOR = G.CODIGO_GESTOR')
                 ->join(array('P' => 'ADM_PERSONAS'), 'P.CODIGO_PERSONA= G.CODIGO_PERSONA')
                 ->where('LG.CODIGO_CLIENTE = ?', $parametros->CODIGO_CLIENTE)
                 ->order(array('LG.FECHA_GESTION DESC'))
                 ->limit(0, 10);
            
        // print_r($select);die();
        $result = $db->fetchAll($select);

        $arr = array();
        foreach ($result as $row) {
          array_push($arr, $row);
        }
        if(count($arr)>0){
             echo json_encode($arr);    
        }else{
            echo json_encode(array('success' => false ));
        }           
    }

    public function saldosclienteAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $parametros = json_decode($this->getRequest()->getParam("parametros"));
        $db = Zend_Db::factory('Pdo_Mysql', array(
            'host'     => '192.185.21.196',
            'username' => 'activa11_sistema',
            'password' => 'sansolucionlurbana11',
            'dbname'   => 'activa11_sistema_bck'
        ));

        $result = $db->query("select F_TRAE_SALDOS(".$parametros->CODIGO_CLIENTE.",'".$parametros->FECHA_SALDO."',1) AS MENS_ACT, 
                                     F_TRAE_SALDOS(".$parametros->CODIGO_CLIENTE.",'".$parametros->FECHA_SALDO."',2) AS MENS_ANT, 
                                     F_TRAE_SALDOS(".$parametros->CODIGO_CLIENTE.",'".$parametros->FECHA_SALDO."',3) AS CASUAL_ACT, 
                                     F_TRAE_SALDOS(".$parametros->CODIGO_CLIENTE.",'".$parametros->FECHA_SALDO."',4) AS CASUAL_ANT")->fetchAll();
        if(count($result)>0){
             echo json_encode($result);    
        }else{
            echo json_encode(array('success' => false ));
        }           
    }



}

     