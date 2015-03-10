<?php

class administracion_perfilclientesController extends Zend_Controller_Action {

     public function init() {
        $parametrosLogueo = new Zend_Session_Namespace ( 'logueo' );
        $parametrosLogueo->unlock ();   
        // $p = Zend_Session::namespaceUnset('factura');
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
        $p = Zend_Session::namespaceUnset('factura');
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
                                     F_TRAE_SALDOS(".$parametros->CODIGO_CLIENTE.",'".$parametros->FECHA_SALDO."',4) AS CASUAL_ANT,
                                     F_TRAE_SALDOS(".$parametros->CODIGO_CLIENTE.",'".$parametros->FECHA_SALDO."',5) AS MENS_UTI,
                                     F_TRAE_SALDOS(".$parametros->CODIGO_CLIENTE.",'".$parametros->FECHA_SALDO."',6) AS CASUAL_UTI,
                                     F_TRAE_SALDOS(".$parametros->CODIGO_CLIENTE.",'".$parametros->FECHA_SALDO."',7) AS DISPONIBLE,
                                     F_TRAE_SALDOS(".$parametros->CODIGO_CLIENTE.",'".$parametros->FECHA_SALDO."',8) AS TOTAL_ABON"
                                     )->fetchAll();
        if(count($result)>0){
             echo json_encode($result);    
        }else{
            echo json_encode(array('success' => false ));
        }           
    }

    public function detallefacturarAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $parametros = json_decode($this->getRequest()->getParam("parametros"));
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
            ->from(array('LG'=>'VADM_SALDOS_CLIENTE'),  array(
                         'LG.CODIGO_SALDO',
                         'LG.CODIGO_SUSCRIPCION',
                         'LG.CODIGO_CLIENTE',
                         'LG.NOMBRE',
                         'LG.DESCRIPCION_PLAN',
                         'LG.TIPO_PLAN',
                         'LG.IMPORTE_SALDO',
                         'LG.FECHA_SALDO'))
                 ->where('LG.CODIGO_CLIENTE = ?', $parametros->CODIGO_CLIENTE)
                 ->where('LG.IMPORTE_SALDO > ?', 0)
                 ->order(array('LG.FECHA_SALDO DESC'));
                 // ->limit(0, 10);
            
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

    public function getseriesAction(){
     $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $result = '';
        try {
             $db = Zend_Db_Table::getDefaultAdapter();
             $select = $db->select()
                ->from(array('C'=>'VADM_SGTE_NRO_TALO'),  array(
                             'C.COD_TALONARIO',
                             'C.SERIE'))
                    ->order(array('C.COD_TALONARIO DESC'))
                    ->distinct(true);
                
            $result = $db->fetchAll($select);
            $htmlResultado = '<option value="-1"></option>';
            foreach ($result as $arr) {
                $htmlResultado .= '<option value="' . $arr["COD_TALONARIO"] . '">' .$arr["SERIE"] . '</option>';
            }

        } catch (Exception $e) {
            echo json_encode(array("success" => false, "code" => $e->getCode(), "mensaje" => $e->getMessage()));
        }
        echo $htmlResultado;
    }

     public function guardarAction(){
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $parametros = json_decode($this->getRequest()->getParam("parametros"));
        // print_r($parametros);
        // print_r($parametros->detalle);
        // die();

         $paramFact = new Zend_Session_Namespace ( 'factura' );
        $paramFact->unlock ();   
        // $paramFact->factura = $parametros;
        $paramFact->factura = $parametros;

        try {
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->beginTransaction();
            $cabecera = array(
                'COD_TALONARIO' => (int)($parametros->COD_TALONARIO),
                'SER_COMPROBANTE' => $parametros->SER_COMPROBANTE,
                'NRO_COMPROBANTE' => (int)($parametros->NRO_COMPROBANTE),
                'CONDICION' => $parametros->CONDICION,
                'NRO_TIMBRADO' => (int)($parametros->NRO_TIMBRADO),
                'CODIGO_CLIENTE' => (int)($parametros->CODIGO_CLIENTE),
                'TOTAL' => (float)($parametros->TOTAL),
                'TOT_GRAVADAS' => (float)($parametros->TOT_GRAVADAS),
                'TOT_EXENTAS' => (float)($parametros->TOT_EXENTAS),
                'SALDO' => (float)($parametros->TOTAL),
                'FECHA' => date("Y-m-d", strtotime($parametros->FECHA)),
                'TIP_COMPROBANTE' => ('FACT')
            );


            $insert_cabecera = $db->insert('ADM_FACTURA_VENTA_CAB', $cabecera);
            
            $codFactura = $db->lastInsertId();
            $i =1;
            $paramFact->factura->ID_COMPROBANTE = $codFactura;
            $paramFact->lock(); 
        // print_r($codFactura); die();
            foreach ($parametros->detalle as $fila) {
                // print_r($fila);
                $data_detalle = array(
                    'ID_COMPROBANTE' => (int)($codFactura),
                    'NRO_LINEA' => ($i),
                    'CODIGO_SALDO' => (int)($fila->CODIGO_SALDO),
                    'CODIGO_SUSCRIPCION' => (int)($fila->CODIGO_SUSCRIPCION),
                    'DESCRIPCION' => $fila->NOMBRE,
                    'CANTIDAD' => (float)($fila->CANTIDAD),
                    'IMPORTE' => (float)($fila->IMPORTE_SALDO),
                    'COD_IVA' => 1,
                    'FECHA_SALDO' => date("Y-m-d", strtotime($fila->FECHA_SALDO))
                );
                $detalle = $db->insert('ADM_FACTURA_VENTA_DET', $data_detalle);
                $i++;
            }
                 
            $db->commit();
           echo json_encode(array("success" => true));
        } catch (Exception $e) {
            $db->rollBack();
            echo json_encode(array("success" => false, "code" => $e->getCode(), "mensaje" => $e->getMessage()));
            
        }
    }

    public function getcontrolfiscalAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $parametros = json_decode($this->getRequest()->getParam("parametros"));
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                ->from(array('C'=>'VADM_SGTE_NRO_TALO'),  array(
                             'C.NRO_SGTE'))
                    ->where('C.COD_TALONARIO = ?', $parametros->COD_TALONARIO)
                    ->distinct(true);
                
            $result = $db->fetchAll($select);

       
        if(count($result)>0){
             echo json_encode(array('success' => true, "numero"=>$result[0]["NRO_SGTE"] ));    
        }else{
            echo json_encode(array('success' => false ));
        }         

    }




       public function printpdfAction() {
            // $this->_helper->viewRenderer->setNoRender(true);
            $this->_helper->layout->disableLayout();
            $this->_helper->viewRenderer->setNoRender(true);
             $paramFact = new Zend_Session_Namespace ( 'factura' );
             $paramFact->unlock ();   
             $facturaPDF = $paramFact->factura;
             if($paramFact->reimpresion){
                $facturaPDF->detalle = json_decode($facturaPDF->detalle);
             }
             
             $paramFact->lock();
             $p = Zend_Session::namespaceUnset('factura');
            try {
                // create PDF
                $pdf = new Zend_Pdf();
                
                // create A4 page
                // $page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);
                $page = new Zend_Pdf_Page('609:963:');
                
                // define font resource
                $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
                
                //prueba ancho y largo
                $width  = $page->getWidth();
                $height = $page->getHeight();
                
                //Color de la linea
                $page->setLineColor(new Zend_Pdf_Color_Rgb(0,0,0));
                //imprime fecha
                $page->setFont($font, 8);
            
                //fecha hoja 1, 2, 3
                $fecha_W_1=($height-132);
                $fecha_W_2=($height-440);
                $fecha_W_3=($height-750);

                $page->drawText($facturaPDF->DOCUMENTO_CLIENTE, 141,$fecha_W_1);
                $page->drawText($facturaPDF->DOCUMENTO_CLIENTE, 141,$fecha_W_2);
                $page->drawText($facturaPDF->DOCUMENTO_CLIENTE, 141,$fecha_W_3);

                //cliente
                $facturaPDF->NOMBRE_CLIENTE = utf8_decode($facturaPDF->NOMBRE_CLIENTE);
                $page->drawText($facturaPDF->CODIGO_CLIENTE." - ".$facturaPDF->NOMBRE_CLIENTE, 140,$fecha_W_1+14);
                $page->drawText($facturaPDF->CODIGO_CLIENTE." - ".$facturaPDF->NOMBRE_CLIENTE, 140,$fecha_W_2+14);
                $page->drawText($facturaPDF->CODIGO_CLIENTE." - ".$facturaPDF->NOMBRE_CLIENTE, 140,$fecha_W_3+14);

                //ruc
                $page->drawText($facturaPDF->FECHA, 140,($fecha_W_1+28));
                $page->drawText($facturaPDF->FECHA, 140,($fecha_W_2+28));
                $page->drawText($facturaPDF->FECHA, 140,($fecha_W_3+28));

                // contado
                $con_cre = 515;
                if ($facturaPDF->CONDICION == "CRE") {
                    $con_cre = $con_cre + 30;
                }
                $page->drawText('X', $con_cre,($fecha_W_1));
                $page->drawText('X', $con_cre,($fecha_W_2));
                $page->drawText('X', $con_cre,($fecha_W_3));

                // numero interno
                $page->drawText(utf8_decode('Código interno: '.$facturaPDF->ID_COMPROBANTE), 500,($fecha_W_1+20));
                $page->drawText(utf8_decode('Código interno: '.$facturaPDF->ID_COMPROBANTE), 500,($fecha_W_2+20));
                $page->drawText(utf8_decode('Código interno: '.$facturaPDF->ID_COMPROBANTE), 500,($fecha_W_3+20));



                $hoja_W_P= 70;
                $hoja_W_M= 532;

                $hoja1_H= 165;
                $hoja2_H= 473;
                $hoja3_H= 769+14;


                foreach ($facturaPDF->detalle as $fila) {
                    // print_r($fila);die();
                    // $fila->IMPORTE_SALDO = number_format($fila->IMPORTE_SALDO);
                    
                    $fila->IMPORTE_SALDO = number_format($fila->IMPORTE_SALDO, 0, '', '.'); 
                    $fila->DESCRIPCION_PLAN = utf8_decode($fila->DESCRIPCION_PLAN);

                    $page->drawText($fila->DESCRIPCION_PLAN, $hoja_W_P, $height-$hoja1_H);
                    $page->drawText($fila->IMPORTE_SALDO, $hoja_W_M, $height-$hoja1_H);
                    // $hoja1_H= $hoja1_H + 14;
                    $page->drawText($fila->DESCRIPCION_PLAN, $hoja_W_P, $height-$hoja2_H);
                    $page->drawText($fila->IMPORTE_SALDO, $hoja_W_M, $height-$hoja2_H);

                    $page->drawText($fila->DESCRIPCION_PLAN, $hoja_W_P, $height-$hoja3_H);
                    $page->drawText($fila->IMPORTE_SALDO, $hoja_W_M, $height-$hoja3_H);
                    
                    $hoja1_H= $hoja1_H + 14;
                    $hoja2_H= $hoja2_H + 14;
                    $hoja3_H= $hoja3_H + 14;
                }
                


                // IVA 10
                // $facturaPDF->TOT_GRAVADAS = number_format($facturaPDF->TOT_GRAVADAS);
                $facturaPDF->TOT_GRAVADAS = number_format($facturaPDF->TOT_GRAVADAS, 0, '', '.'); 

                $page->drawText($facturaPDF->TOT_GRAVADAS, 269,$height-280); 
                $page->drawText($facturaPDF->TOT_GRAVADAS, 269,$height-594); 
                $page->drawText($facturaPDF->TOT_GRAVADAS, 269,$height-900); 
                // TOTAL IVA
                $page->drawText($facturaPDF->TOT_GRAVADAS, 410,$height-280); 
                $page->drawText($facturaPDF->TOT_GRAVADAS, 410,$height-594); 
                $page->drawText($facturaPDF->TOT_GRAVADAS, 410,$height-900); 
                
                //calculamos el total en letras
                $V = new EnLetras(); 
                $total_letra=utf8_decode(strtoupper($V->ValorEnLetras($facturaPDF->TOTAL,"guaranies"))); 


                // TOTAL
                // $facturaPDF->TOTAL = number_format($facturaPDF->TOTAL);
                $facturaPDF->TOTAL = number_format($facturaPDF->TOTAL, 0, '', '.'); 

                $page->drawText($facturaPDF->TOTAL, 495,$height-270);
                $page->drawText($facturaPDF->TOTAL, 495,$height-584);
                $page->drawText($facturaPDF->TOTAL, 495,$height-890); 
                      
                // Total letras
                $page->drawText($total_letra, 135,$height-270);
                $page->drawText($total_letra, 135,$height-584);
                $page->drawText($total_letra, 135,$height-890); 
               
                // add page to document
                $pdf->pages[] = $page;

                 echo $pdf->render();
                header('Content-type: application/pdf');
                // $pdf->save($name);
             
                echo json_encode(array("result" => "EXITO","url" => $name));
            } catch (Zend_Pdf_Exception $e) {
                die ('PDF error: ' . $e->getMessage());  
            } catch (Exception $e) {
                die ('Application error: ' . $e->getMessage());    
            }
    }

   public function reimpresionAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $parametros = json_decode($this->getRequest()->getParam("parametros"));
        $db = Zend_Db_Table::getDefaultAdapter();
        // echo "hola"; die();
        $p = Zend_Session::namespaceUnset('factura');
        $select = $db->select()
            ->from(array('A'=>'ADM_FACTURA_VENTA_CAB'),  array(
                         'A.ID_COMPROBANTE',
                         'A.FECHA',
                         'D.NRO_LINEA',
                         'A.CODIGO_CLIENTE',
                         'A.NRO_COMPROBANTE',
                         'A.CONDICION',
                         'A.CODIGO_CLIENTE',
                         'VS.NOMBRE',
                         'VS.DESCRIPCION_PLAN',
                         'D.IMPORTE',
                         'A.TOT_GRAVADAS',
                         'A.TOTAL'))
                 ->join(array('D' => 'ADM_FACTURA_VENTA_DET'), 'A.ID_COMPROBANTE = D.ID_COMPROBANTE')
                 ->join(array('VS' => 'VADM_SALDOS_CLIENTE'), 'VS.CODIGO_SALDO = D.CODIGO_SALDO')
                 ->where('A.ID_COMPROBANTE = ?',  $parametros->ID_COMPROBANTE);
            
       
        $result = $db->fetchAll($select);
         // $arr = array();
        $p = Zend_Session::namespaceUnset('factura');
        $obj = new Zend_Session_Namespace ( 'factura' );
        $obj->unlock();   

        $obj->factura->detalle = array();
        $obj->factura->FECHA = $result[0]["FECHA"];
        $obj->factura->ID_COMPROBANTE = $result[0]["ID_COMPROBANTE"];
        $obj->factura->NOMBRE_CLIENTE = $result[0]["NOMBRE"];
        $obj->factura->CODIGO_CLIENTE = $result[0]["CODIGO_CLIENTE"];
        $obj->factura->TOT_GRAVADAS = $result[0]["TOT_GRAVADAS"];
        $obj->factura->TOTAL =$result[0]["TOTAL"];
        $obj->factura->CONDICION =$result[0]["CONDICION"];
        $obj->factura->DOCUMENTO_CLIENTE = $parametros->DOCUMENTO_CLIENTE;
       
        foreach ($result as $row) {           
            array_push($obj->factura->detalle, array('DESCRIPCION_PLAN' => $row["DESCRIPCION_PLAN"], "IMPORTE_SALDO"=>$row["IMPORTE"]));


        }
        
        $obj->factura->detalle = json_encode($obj->factura->detalle);
        $obj->reimpresion = true;
        $obj->lock();
        if(count($obj)>0){
             echo json_encode(array('success' => true )); 
        }else{
            echo json_encode(array('success' => false ));
        }           
    }

    public function facturasAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $parametros = json_decode($this->getRequest()->getParam("parametros"));
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
            ->from(array('A'=>'ADM_FACTURA_VENTA_CAB'),  array(
                         'A.ID_COMPROBANTE',
                         'A.FECHA',
                         'A.NRO_COMPROBANTE',
                         'A.SER_COMPROBANTE',
                         'A.TOT_GRAVADAS',
                         'A.SALDO',
                         'A.TOTAL',
                         'A.ESTADO'))
                 ->where('A.CODIGO_CLIENTE = ?',  $parametros->CODIGO_CLIENTE);     
        $result = $db->fetchAll($select);
        $arr = array();
        foreach ($result as $row) {     
            $row["TOT_GRAVADAS"] = number_format( $row["TOT_GRAVADAS"], 0, '', '.');        
            $row["SALDO"] = number_format( $row["SALDO"], 0, '', '.');        
            $row["TOTAL"] = number_format( $row["TOTAL"], 0, '', '.');        
            array_push($arr,$row);
        }
        if(count($arr)>0){
             echo json_encode($arr); 
        }else{
            echo json_encode(array('success' => false ));
        }           
    }

    

}

     