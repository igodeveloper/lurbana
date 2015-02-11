<?php
	error_reporting(0);
    class PDFReporteclientes extends FPDF{  
            var $Conn;
            var $parametros;
            var $B;
            var $I;
            var $U;
            var $HREF;
            var $fila = 1;

            function PDFReporteclientes($orientation='P',$unit='mm',$format='A4',$parametrosPDF){
                //Llama al constructor de la clase padre
                $this->FPDF($orientation,$unit,$format);
                $this->parametros = json_decode($parametrosPDF);  

                //Iniciaci�n de variables
                $this->Conn = new Conexion();

                $this->B=0;
                $this->I=0;
                $this->U=0;
                $this->HREF='';
                
            }
        //Cabecera de p�gina
        function Header(){              
            //Logo
            $x = 0;
            $y = 0;
            // $this->Image("./images/central.jpg",10,10,25,25);
            $this->Image("./menu/img/lurbana-login.png",10,5,50,30);
            $this->Ln(15);
            //Arial bold 15
            $this->SetFont('Arial','B',12);
            $this->SetX(80+$x);
            $this->Cell(80,10,utf8_decode("Reporte de Clientes"),0,0,'L');
            $this->Ln(10);//Salto de l�nea
            $this->SetX(10+$y);    
            $this->SetFont('Arial','B',9);
            $this->Cell(80,10,  utf8_decode("Fecha Reporte "),0,0,'L');
            $this->SetX(35+$y);   
            $this->SetFont('Arial','',9);
            $this->Cell(80,10,  utf8_decode(': '.date('d/m/Y H:i:s')),0,0,'L');                            
            $this->Ln(10);//Salto de l�nea   

         
        }

        function Body(){   
        // print_r($this->parametros);
        // die();   
            $sql="SELECT DISTINCT CLIENTE FROM VLOG_GESTIONES_CLIENTES";
                 // die($this->parametros->cod_producto_tipo);
            if($this->parametros->CLIENTE != null){
                $sql.=" where upper(CLIENTE) like upper('%".$this->parametros->CLIENTE."%')";
            }
           
            $sql.=" ORDER BY CLIENTE DESC";            
            // echo $sql."<br>";                              
            // die();
            $dtDatos = $this->Conn->query($sql);
            // print_r($dtDatos);die();
            $count = 1;
            
            while($row = mysql_fetch_assoc($dtDatos))
            {
                $CLIENTE_DES     = $row['CLIENTE'];
                $x=-2;
                $this->SetX(11+$x);
                $this->SetFont('Arial','B',9);
                $this->SetX(10);
                $this->Cell(24,10,'Cliente :',0,0,'L'); 
                $this->SetX(50);
                $this->Cell(24,10,$CLIENTE_DES,0,0,'L');                               
                $this->fila++;
                $count++;
                $this->Ln(7);       

                $x=-2;
                $this->SetX(11+$x);
                $this->SetFont('Arial','B',9);
                $this->SetX(10);
                $this->Cell(24,10,"Fecha",0,0,'L');                
                $this->SetX(30);
                $this->Cell(25,10,"Gestiones",0,0,'L');
                $this->SetX(60);
                $this->Cell(19,10,"Gestor",0,0,'L');
                $this->SetX(100);
                $this->Cell(19,10,"Obs.",0,0,'L');                        
                $this->Ln(10); 

                 $sql2="SELECT 
                            FECHA,
                            OBSERVACION,
                            CANTIDAD_GESTIONES,
                            GESTOR
                    FROM VLOG_GESTIONES_CLIENTES
                    WHERE CLIENTE = '".$CLIENTE_DES."'";
                if($this->parametros->FECHA_DESDE != null){
                    $sql2.=" AND FECHA >= '".$this->parametros->FECHA_DESDE."'";
                }
                if($this->parametros->FECHA_HASTA != null){
                    $sql2.=" AND FECHA <= '".$this->parametros->FECHA_HASTA."'";
                }

                $sql2.=" ORDER BY FECHA DESC";   

                // echo $sql2."<br>"; 
                // die();                             
                $dtDatos2 = $this->Conn->query($sql2);
            
                $count2 = 1;
            
                while($row2 = mysql_fetch_assoc($dtDatos2))
                {
                    $FECHA = $row2["FECHA"];
                    $OBSERVACION = $row2["OBSERVACION"];
                    $CANTIDAD_GESTIONES = $row2["CANTIDAD_GESTIONES"];
                    // $COD_UNIDAD_MEDIDA = $row2["COD_UNIDAD_MEDIDA"];
                    $GESTOR = $row2["gestor"];

                    $x=-2;
                    $this->SetX(11+$x);
                    $this->SetFont('Arial','',9);
                    $this->SetX(10);
                    $this->Cell(20,10,$FECHA,0,0,'L');                
                    $this->SetX(30);
                    $this->Cell(30,10,$CANTIDAD_GESTIONES.' Uni',0,0,'L');
                    $this->SetX(60);
                    $this->Cell(40,10,$GESTOR,0,1,'L');
                    $this->SetX(100);
                    $this->MultiCell(100,5,$OBSERVACION,0,1);      
                    $this->fila++;
                    $count2++;
                    $this->Ln(5);
                }
                $this->Ln(10);
                if($this->parametros->RESUMEN == "S"){
                    $this->SetFont('Arial','',12);
                    $this->SetX(10);
                    $this->MultiCell(400,5,$this->parametros->RESUMENTXT,0,1);    
                    $this->Ln(10);
                }

                  
            }            
        }
        //Pie de p�gina
        function Footer()
        {
            $this->SetY(-15);
            $this->Cell(-2);
            $this->SetFont('Arial','B',6); 
            $this->SetX(9);
            $this->printLine(290);
            $this->Ln(5);
            $this->Cell(-2);
            $this->SetX(9);
            $this->Cell(50,10,utf8_decode("San Solución - Sistema de Gestión"),0,0,'L');
            // $this->SetX(177);
            // $this->Cell(50,10,utf8_decode("Facultad Politecnica - UNA"),0,0,'L');
        }                
        function esqueleto(){
            $x = 0;
            $y = 0;
            $this->SetDrawColor(10);
            $this->SetLineWidth(0,5);
            //          x   y      x   y 
            $this->Line(10, 100, 10, 196);
            $this->Line(25, 100, 25, 196);
            $this->Line(55, 100, 55, 196);
            $this->Line(169, 100, 169, 196);
            $this->Line(180, 100, 180, 196);
            $this->Line(205, 100, 205, 196);
            
            $this->Line(10, 100, 205, 100);            
            $this->Line(10, 108, 205, 108);
            $this->Line(10, 116, 205, 116);
            $this->Line(10, 124, 205, 124);
            $this->Line(10, 132, 205, 132);
            $this->Line(10, 140, 205, 140);
            $this->Line(10, 148, 205, 148);
            $this->Line(10, 156, 205, 156);
            $this->Line(10, 164, 205, 164);
            $this->Line(10, 172, 205, 172);
            $this->Line(10, 180, 205, 180);
            $this->Line(10, 188, 205, 188);
            $this->Line(10, 196, 205, 196);
        }
        function printLine($y1)
        {
            $this->SetDrawColor(10);
            $this->SetLineWidth(0,5);
            $this->Line(10, $y1, 205, $y1);
        }
    }
?>