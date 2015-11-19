<?php

class reportes_resumensaldosclientesController extends Zend_Controller_Action {

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

    
    
   
    public function imprimirreporteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $filtros = json_decode($this->getRequest ()->getParam ( "parametros" ));

        // $parametrosReporte = new Zend_Session_Namespace ( 'reporte' );
        if($filtros->GENTILEZA == 'S'){
            $table = 'VLOG_GESTIONES_GENTILEZA_RESUM';
        }else{
             $table = 'VLOG_GEST_CLI_RESUM';
        }
            $db = Zend_Db_Table::getDefaultAdapter();
            $select = $db->select()
                ->from(array('C'=>$table),  array(
                             'C.CLIENTE',
                             'C.ANHO',
                             'C.MES',
                             'C.TOTAL'));
            if ($filtros != null) {
                if ($filtros->CLIENTE != null) {
                    $select->where("upper(C.CLIENTE) like upper('%".$filtros->CLIENTE."%')");
                }
                if ($filtros->ANO > 0) {
                    $select->where("C.ANHO = ".$filtros->ANO);
                }
                if ($filtros->MES > 0) {
                    $select->where("C.MES = ".$filtros->MES);
                }
               // print_r($select->where);die();
                $result = $db->fetchAll($select);
            } else {
                $result = $db->fetchAll($select);
            }
            
            $output = "Cliente;Año;Mes;Consumo;\n";
            $total_acumulado = 0;
            foreach ($result as $fila)  {
                if(strlen($fila["CLIENTE"])!= 100){
                    
                    $CLIENTE = str_pad($fila["CLIENTE"],2,' ', STR_PAD_RIGHT);
                }else{                  
                    $CLIENTE = $fila["CLIENTE"];                    
                }

                if(strlen($fila["MES"])!= 2){
                    
                    $MES = str_pad($fila["MES"],2,'0', STR_PAD_LEFT);
                }else{                  
                     $MES = $fila["MES"];

                }
                $ANO = $fila["ANHO"];                    
                $TOTAL = $fila["TOTAL"];                    
                $total_acumulado = $total_acumulado+$TOTAL;
                $output.= $CLIENTE.";".$ANO.";".$MES.";".$TOTAL.";\n";
            }
            $output.= "TOTAL GESTIONES: ".";;;".$total_acumulado.";\n";

        
         echo json_encode(array("success" => true, "valor"=> $output));
         // echo json_encode(array("success" => true));
                            
    }


//modificado mayuscula     
}