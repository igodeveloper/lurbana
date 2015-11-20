<?php

class reportes_gestionclientesController extends Zend_Controller_Action {

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
                ->from(array('C'=>'ADM_CLIENTES'),  array(
                             'C.CODIGO_CLIENTE',
                             'C.CODIGO_PERSONA',
                             'P.DESCRIPCION_PERSONA',
                             'P.NRO_DOCUMENTO_PERSONA',
                             'P.RUC_PERSONA',
                             'P.TELEFONO_PERSONA',
                             'P.CELULAR_PERSONA',
                             'P.EMAIL_PERSONA',
                             'P.ENVIAR_EMAIL',
                             'P.DIRECCION_PERSONA',
                             'P.REFERENCIA_PERSONA',
                             'P.CODIGO_GESTOR_ASIGNADO',
                             'P.CODIGO_CIUDAD',
                             'P.CODIGO_BARRIO',
                             'C.ESTADO_CLIENTE'))
                   ->join(array('P' => 'ADM_PERSONAS'), 'P.CODIGO_PERSONA  = C.CODIGO_PERSONA')
                    ->order(array('C.CODIGO_CLIENTE DESC'));
                   
         if ($filtros != null) {
            if ($filtros->DESCRIPCION_PERSONA != null) {
                $select->where("upper(P.DESCRIPCION_PERSONA) like upper('%".$filtros->DESCRIPCION_PERSONA."%')");
            }
            if ($filtros->NRO_DOCUMENTO_PERSONA != null) {
                $select->where("P.NRO_DOCUMENTO_PERSONA = ?", $filtros->NRO_DOCUMENTO_PERSONA);
            }
            if ($filtros->TELEFONO_PERSONA != null) {
                $select->where("P.TELEFONO_PERSONA = ?", $filtros->TELEFONO_PERSONA);
            }
            if ($filtros->ESTADO_CLIENTE != -1) {
                $select->where("C.ESTADO_CLIENTE = ?", $filtros->ESTADO_CLIENTE);
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
               
                $item['CODIGO_CLIENTE'],
                $item['CODIGO_PERSONA'],
                $item['DESCRIPCION_PERSONA'],
                $item['NRO_DOCUMENTO_PERSONA'],
                $item['RUC_PERSONA'],
                $item['TELEFONO_PERSONA'],
                $item['CELULAR_PERSONA'],
                $item['EMAIL_PERSONA'],
                $item['ENVIAR_EMAIL'],
                $item['DIRECCION_PERSONA'],
                $item['REFERENCIA_PERSONA'],
                $item['CODIGO_GESTOR_ASIGNADO'],
                $item['CODIGO_CIUDAD'],
                $item['CODIGO_BARRIO'],
                $item['ESTADO_CLIENTE']
            );
            $arrayDatos ['columns'] = array(
                
                'CODIGO_CLIENTE',
                'CODIGO_PERSONA',
                'DESCRIPCION_PERSONA',
                'NRO_DOCUMENTO_PERSONA',
                'RUC_PERSONA',
                'TELEFONO_PERSONA',
                'CELULAR_PERSONA',
                'EMAIL_PERSONA',
                'ENVIAR_EMAIL',
                'DIRECCION_PERSONA',
                'REFERENCIA_PERSONA',
                'CODIGO_GESTOR_ASIGNADO',
                'CODIGO_CIUDAD',
                'CODIGO_BARRIO',
                'ESTADO_CLIENTE'
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
            if(!$parametros->CODIGO_PERSONA)
                $parametros->CODIGO_PERSONA = 0;
            $data_personas = array(
                'CODIGO_PERSONA' => ($parametros->CODIGO_PERSONA),
                'DESCRIPCION_PERSONA' => (trim($parametros->DESCRIPCION_PERSONA)),
                'NRO_DOCUMENTO_PERSONA' => (trim($parametros->NRO_DOCUMENTO_PERSONA)),
                'RUC_PERSONA' => (trim($parametros->RUC_PERSONA)),
                'TELEFONO_PERSONA' => (trim($parametros->TELEFONO_PERSONA)),
                'CELULAR_PERSONA' => (trim($parametros->CELULAR_PERSONA)),
                'EMAIL_PERSONA' => (trim($parametros->EMAIL_PERSONA)),
                'ENVIAR_EMAIL' => (trim($parametros->ENVIAR_EMAIL)),
                'DIRECCION_PERSONA' => (trim($parametros->DIRECCION_PERSONA)),
                'REFERENCIA_PERSONA' => (trim($parametros->REFERENCIA_PERSONA)),
                'CODIGO_GESTOR_ASIGNADO' => $parametros->CODIGO_GESTOR,
                'CODIGO_CIUDAD'=> (int)(trim($parametros->CODIGO_CIUDAD)),
                'CODIGO_BARRIO'=> (int)(trim($parametros->CODIGO_BARRIO))
                
            );
            $insert_personas = $db->insert('ADM_PERSONAS', $data_personas);
            $codigo_persona = $db->lastInsertId();

            if(!$parametros->CODIGO_CLIENTE)
                $parametros->CODIGO_CLIENTE = 0;
            $data_clientes = array(
                'CODIGO_CLIENTE' => ($parametros->CODIGO_CLIENTE),
                'CODIGO_PERSONA' =>  $codigo_persona,
                'ESTADO_CLIENTE' => (trim($parametros->ESTADO_CLIENTE))                
            );
            $insert_clientes = $db->insert('ADM_CLIENTES', $data_clientes);

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
                
                'DESCRIPCION_PERSONA' => (trim($parametros->DESCRIPCION_PERSONA)),
                'NRO_DOCUMENTO_PERSONA' => (trim($parametros->NRO_DOCUMENTO_PERSONA)),
                'RUC_PERSONA' => (trim($parametros->RUC_PERSONA)),
                'TELEFONO_PERSONA' => (trim($parametros->TELEFONO_PERSONA)),
                'CELULAR_PERSONA' => (trim($parametros->CELULAR_PERSONA)),
                'EMAIL_PERSONA' => (trim($parametros->EMAIL_PERSONA)),
                'ENVIAR_EMAIL' => (trim($parametros->ENVIAR_EMAIL)),
                'DIRECCION_PERSONA' => (trim($parametros->DIRECCION_PERSONA)),
                'REFERENCIA_PERSONA' => (trim($parametros->REFERENCIA_PERSONA)),
                'CODIGO_GESTOR_ASIGNADO' => $parametros->CODIGO_GESTOR,
                'CODIGO_CIUDAD'=> (int)(trim($parametros->CODIGO_CIUDAD)),
                'CODIGO_BARRIO'=> (int)(trim($parametros->CODIGO_BARRIO))
                
            );
            $where_personas = array(
                'CODIGO_PERSONA = ?' => $parametros->CODIGO_PERSONA
            );
            $update_personas = $db->update('ADM_PERSONAS', $data_personas, $where_personas);
           

            $data_clientes = array(
                'ESTADO_CLIENTE' => (trim($parametros->ESTADO_CLIENTE))                
            );
             $where_clientes = array(
                'CODIGO_CLIENTE = ?' => $parametros->CODIGO_CLIENTE
            );
            $update_clientes = $db->update('ADM_CLIENTES', $data_clientes, $where_clientes);

            $db->commit();
           echo json_encode(array("success" => true));
        } catch (Exception $e) {
            echo json_encode(array("success" => false, "code" => $e->getCode(), "mensaje" => $e->getMessage()));
            $db->rollBack();
        }
    }
    public function imprimirreporteAction() {
           $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $json_rowData = $this->getRequest ()->getParam ( "parametros" );

            // print_r($json_rowData); die();

            $var_nombrearchivo = 'clientes';
            $path_tmp = '../public/reportes_pdf/clientes/';
            $orientation='P';
            $unit='mm';
            $format='A4';
            
            if(!isset($pdf))
            $pdf= new PDFReporteclientes($orientation,$unit,$format,$json_rowData);
            $pdf->AliasNbPages();
            $pdf->AddPage();
            $pdf->Body($json_rowData);

            $file = basename($var_nombrearchivo."_".date('Ymdhis'));
            $file .= '.pdf';
            //Guardar el PDF en un fichero
            $pdf->Output($path_tmp.$file, 'F');
            $pdf->close();
            unset($pdf);
            echo json_encode(array("success" => true,"archivo" => $file));                 
    }
    //cargamos lista de asistentes de sercixios
    public function getasistenteserviciosAction()
    {
     $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $result = '';
        try {
             $db = Zend_Db_Table::getDefaultAdapter();
             $select = $db->select()
                ->from(array('C'=>'LOG_GESTORES'),  array(
                             'C.CODIGO_GESTOR',
                             'P.DESCRIPCION_PERSONA'))
                    ->join(array('P' => 'ADM_PERSONAS'), 'P.CODIGO_PERSONA  = C.CODIGO_PERSONA')
                    ->order(array('C.CODIGO_GESTOR DESC'))
                    ->distinct(true);
                
            $result = $db->fetchAll($select);
            $htmlResultado = '<option value="0">Seleccione</option>';
            foreach ($result as $arr) {
                $htmlResultado .= '<option value="' . $arr["CODIGO_GESTOR"] . '">' .$arr["CODIGO_GESTOR"].' - '.
                trim(($arr["DESCRIPCION_PERSONA"])) . '</option>';
            }

        } catch (Exception $e) {
            echo json_encode(array("success" => false, "code" => $e->getCode(), "mensaje" => $e->getMessage()));
        }
        echo $htmlResultado;
    }

    //cargamos lista de clientes
    public function getclienteAction()
    {
     $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $result = '';
        try {
             $db = Zend_Db_Table::getDefaultAdapter();
             $select = $db->select()
                ->from(array('C'=>'ADM_CLIENTES'),  array(
                             'C.CODIGO_CLIENTE',
                             'P.DESCRIPCION_PERSONA',
                             'P.NRO_DOCUMENTO_PERSONA'))
                    ->join(array('P' => 'ADM_PERSONAS'), 'P.CODIGO_PERSONA  = C.CODIGO_PERSONA')
                    ->order(array('C.CODIGO_CLIENTE DESC'))
                    ->distinct(true);
                
            $result = $db->fetchAll($select);
             $arrAux = array();
            foreach ($result as $arr) {
                 array_push($arrAux,array(
                "CODIGO_CLIENTE"=>$arr["CODIGO_CLIENTE"],
                 "DESCRIPCION_PERSONA"=>$arr["DESCRIPCION_PERSONA"]
                ));
                
            }
             echo json_encode($arrAux);    
        } catch (Exception $e) {
            echo json_encode(array("success" => false, "code" => $e->getCode(), "mensaje" => $e->getMessage()));
        }
    }

//modificado mayuscula  
}