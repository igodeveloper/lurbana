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
                 // ->where('LG.CODIGO_CLIENTE = ?', 14)
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
        try {
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->beginTransaction();
            $cabecera = array(
                'COD_TALONARIO' => (int)($parametros->COD_TALONARIO),
                'SER_COMPROBANTE' => $parametros->SER_COMPROBANTE,
                'NRO_COMPROBANTE' => (int)($parametros->NRO_COMPROBANTE),
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
            foreach ($parametros->detalle as $fila) {
                // print_r($fila);
                $data_detalle = array(
                    'ID_COMPROBANTE' => (int)($codFactura),
                    'NRO_LINEA' => ($i),
                    'CODIGO_SALDO' => (int)($fila->CODIGO_SALDO),
                    'CODIGO_SUSCRIPCION' => (int)($fila->CODIGO_SUSCRIPCION),
                    'DESCRIPCION' => $fila->NOMBRE,
                    'CANTIDAD' => (float)($fila->CANTIDAD),
                    'IMPORTE' => (float)($fila->IMPORTE),
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


    public function pdfAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        //Para crear un nuevo documento PDF:
    $pdf = new Zend_Pdf();

    //Para crear una nueva página:
    $pdf->pages[] = ($page = $pdf->newPage('A4'));
    $pdf->pages[] = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);
    $pdf->pages[] = $pdf->newPage(Zend_Pdf_Page::SIZE_A4);

    //Obtener ancho y alto de la página:
    $ancho = $page->getWidth();
    $alto = $page->getHeight();

    //Usar estilos:
    $estilo = new Zend_Pdf_Style();
    $estilo->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
    $estilo->setFont($font, 10);
    $page->setStyle($estilo);

    //Escribir texto:$pdf->Ln();
    $page->drawText("ancho".$ancho." alto ".$alto, 585, 832);

    //Insertar imágenes:
    //$img = Zend_Pdf_ImageFactory::factory('sentidoweb.png');
    //$page->drawImage($img, $x, $y, $x+&ancho, $y+$alto);
    //Devolver la salida:
    echo $pdf->render();

    //Eso sí, antes hay que tener en cuenta que tenemos que devolver al inicio del script el Content-Type:

    header("Content-Type: application/pdf");
    // Si queremos que se devuelva como un fichero adjunto
    // header("Content-Disposition: attachment; filename=\"prueba.pdf\"");
    }


       public function printpdfAction() {
        // $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
    //  // include auto-loader class
    //     require_once 'Zend/Loader/Autoloader.php';
    // // register auto-loader
    //     $loader = Zend_Loader_Autoloader::getInstance();

        try {
            // create PDF
            $pdf = new Zend_Pdf();
            
            // create A4 page
            $page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);
            
            // define font resource
            $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
            
            //prueba ancho y largo
            $width  = $page->getWidth();
            $height = $page->getHeight();
            
            //Color de la linea
            $page->setLineColor(new Zend_Pdf_Color_Rgb(0,0,0));
            //Linea superior Horizontal
            $page->pathLine($width-10,$height-10);
            $page->setFont($font, 10);
            $page->drawText('INFOCOMEDOR', 10,$height-20);
            //Linea inferior Horizontal
            // $page->drawLine(38, 38, ($width-38), 38);
            // //left line vertical
            // $page->drawLine(38, 38, 38, $height-38);
            // //right line vertical
            // $page->drawLine($width-38, $height-38, $width-38, 38);
            
            // //Tamanho de letra, color, y titulo
            // $page->setFont($font, 14)
            // ->setFillColor(new Zend_Pdf_Color_Rgb(1, 0, 0))
            // ->drawText('INFOCOMEDOR', 250, $height-75);
            
            
            // Linea bajo el titulo
            $page->drawLine(50, $height-78, ($width-50), $height-78);
            
            // $listado = new Zend_Session_Namespace('listado');
            // $listado->unlock();
            $y = 100;
            $i=0;
            $codigo_inventario = 10;
            if( $codigo_inventario > 0){
                $page->setFont($font, 14)
                ->setFillColor(new Zend_Pdf_Color_Rgb(0, 0, 0))
                ->drawText( $codigo_inventario, 450, $height-75);
            }
            if( $codigo_inventario > 0){
                 $page->setFont($font, 14)
                    ->setFillColor(new Zend_Pdf_Color_Rgb(0, 0, 0))
                    ->drawText( $codigo_inventario, 450, $height-75);
            }

            // Hacemos la cabecera
            
            $page->setFont($font, 14)
                     ->setFillColor(new Zend_Pdf_Color_Rgb(0, 0, 0))
                     ->drawText('Item', 40, $height-$y)
                     ->drawText('Producto', 90, $height-$y)
                     ->drawText('Inventario', 400, $height-$y);
                     $y = $y+20;
                     
           
            // add page to document
            $pdf->pages[] = $page;
            $name = 'inventario'. date("Ymd").date("H").date("i").date("s").'.pdf';
    
                foreach($pdf->pages As $key => $page){
                    $page->drawText("Page " . ($key+1) . " of " . count($pdf->pages), 260, 50);                      
                }
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



}

     