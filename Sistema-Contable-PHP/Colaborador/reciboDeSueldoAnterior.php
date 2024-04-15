<?php
     include('funciones.php');

     session_start();
 
     clearstatcache();
 
     require('database.php');
     
     include('../Administrador/pdf.php');
 
     date_default_timezone_set('America/Argentina/Buenos_Aires');
    

            $id = $_GET['id'];

            $sql = ("SELECT * FROM pagosanteriores WHERE id like '$id'");
            $result = db_query($sql);
            $rowPagoAnterior = mysqli_fetch_array($result);
    
            $importeDeHorasExtras = $rowPagoAnterior[4];
            $cantidadDeHorasTrabajadas = $rowPagoAnterior[5];
            $importeFeriadosTrabajados = $rowPagoAnterior[6];
            $cantidadDeFeriadosTrabajados = $rowPagoAnterior[7];
            $bono = $rowPagoAnterior[8];
    
            $sql = ("SELECT * FROM empleado WHERE id like '$rowPagoAnterior[1]'");
            $result = db_query($sql);
            $row = mysqli_fetch_array($result);

            $puesto = $row[11];

            $sql2 = ("SELECT * FROM puestoempleado WHERE id like '$puesto'");
            $result2 = db_query($sql2);
            $row2 = mysqli_fetch_array($result2);
    
            $sueldo = $rowPagoAnterior[2];
    
            $sueldoEmpleadoConHaberes = $sueldo + $importeDeHorasExtras + $importeFeriadosTrabajados + $bono;
    
            /*Aporte Personal Jubilación: 11%*/
                $jubilacion = (($sueldoEmpleadoConHaberes * 11)/100);
            /*Aporte Personal O. Social: 3%*/
                $obraSocial = (($sueldoEmpleadoConHaberes * 3)/100);
            /*Aporte Personal O. Social: 3%*/
                $ley = (($sueldoEmpleadoConHaberes * 3)/100);
            /*Aporte Personal Sindicato: 2.5%*/
                $sindicato = (($sueldoEmpleadoConHaberes * 2.5)/100);
            /*Contribución Patronal O. Social: 5.4%*/
                $regulacionSindicato = (($sueldoEmpleadoConHaberes * 5.4)/100);
            /*Ley de Riesgo de Trabajo (A.R.T.): 1,5%*/
                $art = (($sueldoEmpleadoConHaberes * 1.5)/100);
    
                $descuentos = $jubilacion + $obraSocial + $ley + $sindicato + $regulacionSindicato + $art;
    
                $sueldo = $sueldoEmpleadoConHaberes - $descuentos;

    
            $tipoPago = $rowPagoAnterior[10];
    
            if($tipoPago == "caja"){
                $pdf = new PDF();
                $pdf ->AliasNbPages();
                $pdf->AddPage();
    
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->SetFillColor(200,200,200);
                $pdf->Cell(90,7,utf8_decode("Apellido y Nombre"),1,0,'C',1);
                $pdf->Cell(50,7,utf8_decode("Legajo"),1,0,'C',1);
                $pdf->Cell(50,7,utf8_decode("C.U.I.L."),1,0,'C',1);
    
                $pdf->Ln(7);
    
                $nombreEmpleado = $row[2] . " " . $row[1];
                $pdf->Cell(90,7,$nombreEmpleado,1,0,'C');
    
                $legajoEmpleado = $row[0];
                $pdf->Cell(50,7,$legajoEmpleado,1,0,'C',);
    
                $cuilEmpleado = $row[10];
                $pdf->Cell(50,7,$cuilEmpleado,1,0,'C',);
    
                $pdf->Ln(7);
    
                $pdf->Cell(190,7,utf8_decode("Categoria"),1,0,'C',1);
                
                $pdf->Ln(7);
    
                $categoriaEmpleado = $row2[1];
                $pdf->Cell(190,7,$categoriaEmpleado,1,0,'C');
    
                $pdf->Ln(7);
    
                $pdf->Cell(45,7,utf8_decode("Fecha de Ingreso"),1,0,'C',1); 
                $pdf->Cell(50,7,utf8_decode("Sueldo"),1,0,'C',1);
                $pdf->Cell(50,7,utf8_decode("Periodo"),1,0,'C',1);
                $pdf->Cell(45,7,utf8_decode("Fecha"),1,0,'C',1);
                
                $pdf->Ln(7);
    
                $fechaDeIngreso = $row[6];
                $fechaDeIngreso = date("d/m/Y", strtotime($fechaDeIngreso));
                $pdf->Cell(45,7,$fechaDeIngreso,1,0,'C');

                $sueldoEmpleado = $rowPagoAnterior[2];
                $pdf->Cell(50,7,$sueldoEmpleado,1,0,'C');

                $fecha = $rowPagoAnterior[9];
                $periodo = date("M Y", strtotime($fecha));
                $pdf->Cell(50,7,$periodo,1,0,'C');
    
                $fecha = $rowPagoAnterior[9];
                $fecha = date("d/m/Y", strtotime($fecha));
                $pdf->Cell(45,7,$fecha,1,0,'C');
    
                $pdf->Ln(7);
                
                $pdf->Cell(20,7,utf8_decode("Codigo"),1,0,'C',1);
                $pdf->Cell(60,7,utf8_decode("Detalle"),1,0,'C',1);
                $pdf->Cell(20,7,utf8_decode("Cantidad"),1,0,'C',1);
                $pdf->Cell(30,7,utf8_decode("Hab.C/Desc."),1,0,'C',1);
                $pdf->Cell(30,7,utf8_decode("Hab.S/Desc."),1,0,'C',1);
                $pdf->Cell(30,7,utf8_decode("Deducciones"),1,0,'C',1);
    
                $pdf->Ln(10);
                $pdf->Cell(8);
                $pdf->Cell(20, 10, utf8_decode("3"));
                $pdf->Cell(60, 10, utf8_decode("Sueldo"));
                $pdf->Cell(20, 10, utf8_decode("1.0"));
                settype($sueldoEmpleado, "float");
                $sueldoEmpleado2 = number_format($sueldoEmpleado, 2, ",", ".");
                $pdf->Cell(30, 10, $sueldoEmpleado2);
    
                if($importeDeHorasExtras != 0){
                    $pdf->Ln(10);
                    $pdf->Cell(8);
                    $pdf->Cell(20, 10, utf8_decode("701"));
                    $pdf->Cell(60, 10, utf8_decode("Horas Extras"));
                    settype($cantidadDeHorasTrabajadas, "float");
                    $cantidadDeHorasTrabajadas2 = number_format($cantidadDeHorasTrabajadas, 1, ".", ",");
                    $pdf->Cell(20, 10, $cantidadDeHorasTrabajadas2);
                    settype($importeDeHorasExtras, "float");
                    $importeDeHorasExtras2 = number_format($importeDeHorasExtras, 2, ",", ".");
                    $pdf->Cell(60, 10, $importeDeHorasExtras2);
                }
    
                if($importeFeriadosTrabajados != 0){
                    $pdf->Ln(10);
                    $pdf->Cell(8);
                    $pdf->Cell(20, 10, utf8_decode("702"));
                    $pdf->Cell(60, 10, utf8_decode("Feriados Trabajados"));
                    settype($cantidadDeFeriadosTrabajados, "float");
                    $cantidadDeFeriadosTrabajados2 = number_format($cantidadDeFeriadosTrabajados, 1, ".", ",");
                    $pdf->Cell(20, 10, $cantidadDeFeriadosTrabajados2);
                    settype($importeFeriadosTrabajados, "float");
                    $importeDeFeriadosTrabajados2 = number_format($mporteFeriadosTrabajados, 2, ",", ".");
                    $pdf->Cell(60, 10, $importeDeFeriadosTrabajados2);
                }

                if($bono != 0){
                    $pdf->Ln(10);
                    $pdf->Cell(8);
                    $pdf->Cell(20, 10, utf8_decode("703"));
                    $pdf->Cell(60, 10, utf8_decode("Bono"));
                    $pdf->Cell(20, 10, utf8_decode(" "));
                    settype($bono, "float");
                    $bono2 = number_format($bono, 2, ",", ".");
                    $pdf->Cell(60, 10, $bono2);
                }

                $pdf->Ln(10);
                $pdf->Cell(8);
                $pdf->Cell(20, 10, utf8_decode("501"));
                $pdf->Cell(60, 10, utf8_decode("Jubilacion"));
                $pdf->Cell(20, 10, utf8_decode("11.0"));
                $pdf->Cell(60, 10, utf8_decode(" "));
                $jubilacion = - (($sueldoEmpleadoConHaberes * 11)/100);
                setType($jubilacion, "float");
                $jubilacion2 = number_format($jubilacion, 2, ",", ".");
                $pdf->Cell(30, 10, $jubilacion2);
                
                $pdf->Ln(10);
                $pdf->Cell(8);
                $pdf->Cell(20, 10, utf8_decode("505"));
                $pdf->Cell(60, 10, utf8_decode("Ley 19032"));
                $pdf->Cell(20, 10, utf8_decode("3.0"));
                $pdf->Cell(60, 10, utf8_decode(" "));
                $ley = - (($sueldoEmpleadoConHaberes * 3)/100);
                setType($ley, "float");
                $ley2 = number_format($ley, 2, ",", ".");
                $pdf->Cell(30, 10, $ley2);
                
                $pdf->Ln(10);            
                $pdf->Cell(8);
                $pdf->Cell(20, 10, utf8_decode("505"));
                $pdf->Cell(60, 10, utf8_decode("Obra Social"));
                $pdf->Cell(20, 10, utf8_decode("3.0"));
                $pdf->Cell(60, 10, utf8_decode(" "));
                $obra = - (($sueldoEmpleadoConHaberes * 3)/100);
                setType($obra, "float");
                $obra2 = number_format($obra, 2, ",", ".");
                $pdf->Cell(30, 10, $obra2);
                
                $pdf->Ln(10);
                $pdf->Cell(8);
                $pdf->Cell(20, 10, utf8_decode("600"));
                $pdf->Cell(60, 10, utf8_decode("Sindicato"));
                $pdf->Cell(20, 10, utf8_decode("2.5"));
                $pdf->Cell(60, 10, utf8_decode(" "));
                $sindicato = - (($sueldoEmpleadoConHaberes * 2.5)/100);
                setType($sindicato, "float");
                $sindicato2 = number_format($sindicato, 2, ",", ".");
                $pdf->Cell(30,10, $sindicato2);
    
                $pdf->Ln(10);
                $pdf->Cell(8);
                $pdf->Cell(20, 10, utf8_decode("601"));
                $pdf->Cell(60, 10, utf8_decode("Regulacion de Sindicato"));
                $pdf->Cell(20, 10, utf8_decode("5.4"));
                $pdf->Cell(60, 10, utf8_decode(" "));
                $sindicato2 = - (($sueldoEmpleadoConHaberes * 5.4)/100);
                setType($sindicato2, "float");
                $sindicato3 = number_format($sindicato2, 2, ",", ".");
                $pdf->Cell(30, 10, $sindicato3);
    
                $pdf->Ln(10);
                $pdf->Cell(8);
                $pdf->Cell(20, 10, utf8_decode("670"));
                $pdf->Cell(60, 10, utf8_decode("Ley de Riesgo de Trabajo"));
                $pdf->Cell(20, 10, utf8_decode("1.5"));
                $pdf->Cell(60, 10, utf8_decode(" "));
                $art = - (($sueldoEmpleadoConHaberes * 1.5)/100);
                setType($art, "float");
                $art2 = number_format($art, 2, ",", ".");
                $pdf->Cell(30, 10, $art2);
    
                $pdf->Ln(20);
    
                $pdf->Cell(130,7,utf8_decode("Lugar y Fecha de Cobro"),1,0,'C',1);
                $pdf->Cell(30,7,utf8_decode("Tot. Remun."),1,0,'C',1);
                $pdf->Cell(30,7,utf8_decode("Deducciones"),1,0,'C',1);
    
                $pdf->Ln(7);
                $fecha = $rowPagoAnterior[9];
                $fecha = date("d/m/Y", strtotime($fecha));
                $lugaryfecha = "Rosario. " . $fecha;
                $pdf->Cell(130,7, $lugaryfecha,1,0,'C');
    
                $totalRemuneraciones2 = number_format($sueldoEmpleadoConHaberes, 2, ",", ".");
                $pdf->Cell(30,7,$totalRemuneraciones2,1,0,'C');
    
                $totalDeducciones = $jubilacion + $ley + $obra + $sindicato + $sindicato2 + $art;
                $totalDeducciones2 = number_format($totalDeducciones, 2, ",", ".");
                $pdf->Cell(30,7,$totalDeducciones2,1,0,'C');
    
                $pdf->Ln(7);
    
                $pdf->Cell(190,7,utf8_decode("Total Neto: "),1,0,'L',1);
                
                $pdf->Ln(7);
    
                $totalNeto = $sueldoEmpleadoConHaberes + $totalDeducciones;
                $totalNeto2 = number_format($totalNeto, 2, ",", ".");
                $pdf->Cell(190,7,$totalNeto2,1,0,'L');
    
                $pdf->Ln(7);
                $valorEscrito = convertir($totalNeto);
                $pdf->MultiCell(190,7,utf8_decode("Son Pesos: \n$valorEscrito"),1,"L");
    
                $pdf->MultiCell(190,7,utf8_decode("Recibí de conformidad el importe neto en el presente recibo en concepto de haberes correspondiente \nal período arriba indicado quedando en mi poder un duplicado del mismo debidamente \nfirmado por el empleador."),1,'l');
                $pdf->Cell(150);
                $pdf->Cell(40,7,utf8_decode("Firma del Empleado"),1,0,'C');
    
                $pdf->AddPage();
    
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->SetFillColor(200,200,200);
                $pdf->Cell(90,7,utf8_decode("Apellido y Nombre"),1,0,'C',1);
                $pdf->Cell(50,7,utf8_decode("Legajo"),1,0,'C',1);
                $pdf->Cell(50,7,utf8_decode("C.U.I.L."),1,0,'C',1);
    
                $pdf->Ln(7);
    
                $nombreEmpleado = $row[2] . " " . $row[1];
                $pdf->Cell(90,7,$nombreEmpleado,1,0,'C');
    
                $legajoEmpleado = $row[0];
                $pdf->Cell(50,7,$legajoEmpleado,1,0,'C',);
    
                $cuilEmpleado = $row[10];
                $pdf->Cell(50,7,$cuilEmpleado,1,0,'C',);
    
                $pdf->Ln(7);
    
                $pdf->Cell(190,7,utf8_decode("Categoria"),1,0,'C',1);
                
                $pdf->Ln(7);
    
                $categoriaEmpleado = $row2[1];
                $pdf->Cell(190,7,$categoriaEmpleado,1,0,'C');
    
                $pdf->Ln(7);
    
                $pdf->Cell(45,7,utf8_decode("Fecha de Ingreso"),1,0,'C',1); 
                $pdf->Cell(50,7,utf8_decode("Sueldo"),1,0,'C',1);
                $pdf->Cell(50,7,utf8_decode("Periodo"),1,0,'C',1);
                $pdf->Cell(45,7,utf8_decode("Fecha"),1,0,'C',1);
                
                $pdf->Ln(7);
    
                $fechaDeIngreso = $row[6];
                $fechaDeIngreso = date("d/m/Y", strtotime($fechaDeIngreso));
                $pdf->Cell(45,7,$fechaDeIngreso,1,0,'C');

                $sueldoEmpleado = $rowPagoAnterior[2];
                $pdf->Cell(50,7,$sueldoEmpleado,1,0,'C');

                $fecha = $rowPagoAnterior[9];
                $periodo = date("M Y", strtotime($fecha));
                $pdf->Cell(50,7,$periodo,1,0,'C');
    
                $fecha = $rowPagoAnterior[9];
                $fecha = date("d/m/Y", strtotime($fecha));
                $pdf->Cell(45,7,$fecha,1,0,'C');
        
                $pdf->Ln(7);

                $pdf->Cell(20,7,utf8_decode("Codigo"),1,0,'C',1);
                $pdf->Cell(60,7,utf8_decode("Detalle"),1,0,'C',1);
                $pdf->Cell(20,7,utf8_decode("Cantidad"),1,0,'C',1);
                $pdf->Cell(30,7,utf8_decode("Hab.C/Desc."),1,0,'C',1);
                $pdf->Cell(30,7,utf8_decode("Hab.S/Desc."),1,0,'C',1);
                $pdf->Cell(30,7,utf8_decode("Deducciones"),1,0,'C',1);
    
                $pdf->Ln(10);
                $pdf->Cell(8);
                $pdf->Cell(20, 10, utf8_decode("3"));
                $pdf->Cell(60, 10, utf8_decode("Sueldo"));
                $pdf->Cell(20, 10, utf8_decode("1.0"));
                settype($sueldoEmpleado, "float");
                $sueldoEmpleado2 = number_format($sueldoEmpleado, 2, ",", ".");
                $pdf->Cell(30, 10, $sueldoEmpleado2);
    
                if($bono != 0){
                    $pdf->Ln(10);
                    $pdf->Cell(8);
                    $pdf->Cell(20, 10, utf8_decode("703"));
                    $pdf->Cell(60, 10, utf8_decode("Bono"));
                    $pdf->Cell(20, 10, utf8_decode(" "));
                    settype($bono, "float");
                    $bono2 = number_format($bono, 2, ",", ".");
                    $pdf->Cell(60, 10, $bono2);
                }
    
                if($importeDeHorasExtras != 0){
                    $pdf->Ln(10);
                    $pdf->Cell(8);
                    $pdf->Cell(20, 10, utf8_decode("701"));
                    $pdf->Cell(60, 10, utf8_decode("Horas Extras"));
                    settype($cantidadDeHorasTrabajadas, "float");
                    $cantidadDeHorasTrabajadas2 = number_format($cantidadDeHorasTrabajadas, 1, ".", ",");
                    $pdf->Cell(20, 10, $cantidadDeHorasTrabajadas2);
                    settype($importeDeHorasExtras, "float");
                    $importeDeHorasExtras2 = number_format($importeDeHorasExtras, 2, ",", ".");
                    $pdf->Cell(60, 10, $importeDeHorasExtras2);
                }
    
                if($importeFeriadosTrabajados != 0){
                    $pdf->Ln(10);
                    $pdf->Cell(8);
                    $pdf->Cell(20, 10, utf8_decode("702"));
                    $pdf->Cell(60, 10, utf8_decode("Feriados Trabajados"));
                    settype($cantidadDeFeriadosTrabajados, "float");
                    $cantidadDeFeriadosTrabajados2 = number_format($cantidadDeFeriadosTrabajados, 1, ".", ",");
                    $pdf->Cell(20, 10, $cantidadDeFeriadosTrabajados2);
                    settype($importeFeriadosTrabajados, "float");
                    $importeDeFeriadosTrabajados2 = number_format($importeFeriadosTrabajados, 2, ",", ".");
                    $pdf->Cell(60, 10, $importeDeFeriadosTrabajados2);
                }

                $pdf->Ln(10);
                $pdf->Cell(8);
                $pdf->Cell(20, 10, utf8_decode("501"));
                $pdf->Cell(60, 10, utf8_decode("Jubilacion"));
                $pdf->Cell(20, 10, utf8_decode("11.0"));
                $pdf->Cell(60, 10, utf8_decode(" "));
                $jubilacion = - (($sueldoEmpleadoConHaberes * 11)/100);
                setType($jubilacion, "float");
                $jubilacion2 = number_format($jubilacion, 2, ",", ".");
                $pdf->Cell(30, 10, $jubilacion2);
                
                $pdf->Ln(10);
                $pdf->Cell(8);
                $pdf->Cell(20, 10, utf8_decode("505"));
                $pdf->Cell(60, 10, utf8_decode("Ley 19032"));
                $pdf->Cell(20, 10, utf8_decode("3.0"));
                $pdf->Cell(60, 10, utf8_decode(" "));
                $ley = - (($sueldoEmpleadoConHaberes * 3)/100);
                setType($ley, "float");
                $ley2 = number_format($ley, 2, ",", ".");
                $pdf->Cell(30, 10, $ley2);
                
                $pdf->Ln(10);            
                $pdf->Cell(8);
                $pdf->Cell(20, 10, utf8_decode("505"));
                $pdf->Cell(60, 10, utf8_decode("Obra Social"));
                $pdf->Cell(20, 10, utf8_decode("3.0"));
                $pdf->Cell(60, 10, utf8_decode(" "));
                $obra = - (($sueldoEmpleadoConHaberes * 3)/100);
                setType($obra, "float");
                $obra2 = number_format($obra, 2, ",", ".");
                $pdf->Cell(30, 10, $obra2);
                
                $pdf->Ln(10);
                $pdf->Cell(8);
                $pdf->Cell(20, 10, utf8_decode("600"));
                $pdf->Cell(60, 10, utf8_decode("Sindicato"));
                $pdf->Cell(20, 10, utf8_decode("2.5"));
                $pdf->Cell(60, 10, utf8_decode(" "));
                $sindicato = - (($sueldoEmpleadoConHaberes * 2.5)/100);
                setType($sindicato, "float");
                $sindicato2 = number_format($sindicato, 2, ",", ".");
                $pdf->Cell(30,10, $sindicato2);
    
                $pdf->Ln(10);
                $pdf->Cell(8);
                $pdf->Cell(20, 10, utf8_decode("601"));
                $pdf->Cell(60, 10, utf8_decode("Regulacion de Sindicato"));
                $pdf->Cell(20, 10, utf8_decode("5.4"));
                $pdf->Cell(60, 10, utf8_decode(" "));
                $sindicato2 = - (($sueldoEmpleadoConHaberes * 5.4)/100);
                setType($sindicato2, "float");
                $sindicato3 = number_format($sindicato2, 2, ",", ".");
                $pdf->Cell(30, 10, $sindicato3);
    
                $pdf->Ln(10);
                $pdf->Cell(8);
                $pdf->Cell(20, 10, utf8_decode("670"));
                $pdf->Cell(60, 10, utf8_decode("Ley de Riesgo de Trabajo"));
                $pdf->Cell(20, 10, utf8_decode("1.5"));
                $pdf->Cell(60, 10, utf8_decode(" "));
                $art = - (($sueldoEmpleadoConHaberes * 1.5)/100);
                setType($art, "float");
                $art2 = number_format($art, 2, ",", ".");
                $pdf->Cell(30, 10, $art2);
    
                $pdf->Ln(20);
    
                $pdf->Cell(130,7,utf8_decode("Lugar y Fecha de Cobro"),1,0,'C',1);
                $pdf->Cell(30,7,utf8_decode("Tot. Remun."),1,0,'C',1);
                $pdf->Cell(30,7,utf8_decode("Deducciones"),1,0,'C',1);
    
                $pdf->Ln(7);
                $fecha = $rowPagoAnterior[9];
                $fecha = date("d/m/Y", strtotime($fecha));
                $lugaryfecha = "Rosario. " . $fecha;
                $pdf->Cell(130,7, $lugaryfecha,1,0,'C');
    
                $totalRemuneraciones2 = number_format($sueldoEmpleadoConHaberes, 2, ",", ".");
                $pdf->Cell(30,7,$totalRemuneraciones2,1,0,'C');
    
                $totalDeducciones = $jubilacion + $ley + $obra + $sindicato + $sindicato2 + $art;
                $totalDeducciones2 = number_format($totalDeducciones, 2, ",", ".");
                $pdf->Cell(30,7,$totalDeducciones2,1,0,'C');
    
                $pdf->Ln(7);
    
                $pdf->Cell(190,7,utf8_decode("Total Neto: "),1,0,'L',1);
                
                $pdf->Ln(7);
    
                $totalNeto = $sueldoEmpleadoConHaberes + $totalDeducciones;
                $totalNeto2 = number_format($totalNeto, 2, ",", ".");
                $pdf->Cell(190,7,$totalNeto2,1,0,'L');
    
                $pdf->Ln(7);
                $valorEscrito = convertir($totalNeto);
                $pdf->MultiCell(190,7,utf8_decode("Son Pesos: \n$valorEscrito"),1,"L");
    
                $pdf->Cell(190,7,utf8_decode("El presente es duplicado del recibo original que obra en nuestro poder firmado por el empleado."),1,0,'L');
                $pdf->Ln(7);
                $pdf->Cell(150);
                $pdf->Cell(40,7,utf8_decode("Firma del Empleador"),1,0,'C');    
    
                $pdf->Output();
    
                $stmt->execute();
    
                }else{
                    $pdf = new PDF();
                    $pdf ->AliasNbPages();
                    $pdf->AddPage();
    
                    $pdf = new PDF();
                    $pdf ->AliasNbPages();
                    $pdf->AddPage();
                    $pdf->SetFont('Arial', 'B', 10);
                    $pdf->SetFillColor(200,200,200);
                    $pdf->Cell(90,7,utf8_decode("Apellido y Nombre"),1,0,'C',1);
                    $pdf->Cell(50,7,utf8_decode("Legajo"),1,0,'C',1);
                    $pdf->Cell(50,7,utf8_decode("C.U.I.L."),1,0,'C',1);
        
                    $pdf->Ln(7);
        
                    $nombreEmpleado = $row[2] . " " . $row[1];
                    $pdf->Cell(90,7,$nombreEmpleado,1,0,'C');
        
                    $legajoEmpleado = $row[0];
                    $pdf->Cell(50,7,$legajoEmpleado,1,0,'C',);
        
                    $cuilEmpleado = $row[10];
                    $pdf->Cell(50,7,$cuilEmpleado,1,0,'C',);
        
                    $pdf->Ln(7);
        
                    $pdf->Cell(190,7,utf8_decode("Categoria"),1,0,'C',1);
                    
                    $pdf->Ln(7);
        
                    $categoriaEmpleado = $row2[1];
                    $pdf->Cell(190,7,$categoriaEmpleado,1,0,'C');
        
                    $pdf->Ln(7);
        
                    $pdf->Cell(40,7,utf8_decode("Fecha de Ingreso"),1,0,'C',1);
                    $pdf->Cell(40,7,utf8_decode("Sueldo"),1,0,'C',1);
                    $pdf->Cell(40,7,utf8_decode("Periodo"),1,0,'C',1);
                    $pdf->Cell(40,7,utf8_decode("Fecha"),1,0,'C',1);
                    $pdf->Cell(30, 7, utf8_decode("Banco"),1,0,'C',1);
                    
                    $pdf->Ln(7);

                    $fechaDeIngreso = $row[6];
                    $fechaDeIngreso = date("d/m/Y", strtotime($fechaDeIngreso));
                    $pdf->Cell(40,7,$fechaDeIngreso,1,0,'C');

                    $sueldoEmpleado = $rowPagoAnterior[2];
                    $pdf->Cell(40,7,$sueldoEmpleado,1,0,'C');

                    $fecha = $rowPagoAnterior[9];
                    $periodo = date("M Y", strtotime($fecha));
                    $pdf->Cell(40,7,$periodo,1,0,'C');
        
                    $fecha = $rowPagoAnterior[9];
                    $fecha = date("d/m/Y", strtotime($fecha));
                    $pdf->Cell(40,7,$fecha,1,0,'C');
    
                    $pdf->Cell(30,7,utf8_decode("Santander Rio"),1,0,'C');

                    $pdf->Ln(7);
        
                    $pdf->Cell(20,7,utf8_decode("Codigo"),1,0,'C',1);
                    $pdf->Cell(60,7,utf8_decode("Detalle"),1,0,'C',1);
                    $pdf->Cell(20,7,utf8_decode("Cantidad"),1,0,'C',1);
                    $pdf->Cell(30,7,utf8_decode("Hab.C/Desc."),1,0,'C',1);
                    $pdf->Cell(30,7,utf8_decode("Hab.S/Desc."),1,0,'C',1);
                    $pdf->Cell(30,7,utf8_decode("Deducciones"),1,0,'C',1);
        
                    $pdf->Ln(10);
                    $pdf->Cell(8);
                    $pdf->Cell(20, 10, utf8_decode("3"));
                    $pdf->Cell(60, 10, utf8_decode("Sueldo"));
                    $pdf->Cell(20, 10, utf8_decode("1.0"));
                    settype($sueldoEmpleado, "float");
                    $sueldoEmpleado2 = number_format($sueldoEmpleado, 2, ",", ".");
                    $pdf->Cell(30, 10, $sueldoEmpleado2);

        
                    if($importeDeHorasExtras != 0){
                        $pdf->Ln(10);
                        $pdf->Cell(8);
                        $pdf->Cell(20, 10, utf8_decode("701"));
                        $pdf->Cell(60, 10, utf8_decode("Horas Extras"));
                        settype($cantidadDeHorasTrabajadas, "float");
                        $cantidadDeHorasTrabajadas2 = number_format($cantidadDeHorasTrabajadas, 1, ".", ",");
                        $pdf->Cell(20, 10, $cantidadDeHorasTrabajadas2);
                        settype($importeDeHorasExtras, "float");
                        $importeDeHorasExtras2 = number_format($importeDeHorasExtras, 2, ",", ".");
                        $pdf->Cell(60, 10, $importeDeHorasExtras2);
                    }
        
                    if($importeFeriadosTrabajados != 0){
                        $pdf->Ln(10);
                        $pdf->Cell(8);
                        $pdf->Cell(20, 10, utf8_decode("702"));
                        $pdf->Cell(60, 10, utf8_decode("Feriados Trabajados"));
                        settype($cantidadDeFeriadosTrabajados, "float");
                        $cantidadDeFeriadosTrabajados2 = number_format($cantidadDeFeriadosTrabajados, 1, ".", ",");
                        $pdf->Cell(20, 10, $cantidadDeFeriadosTrabajados2);
                        settype($importeFeriadosTrabajados, "float");
                        $importeDeFeriadosTrabajados2 = number_format($importeFeriadosTrabajados, 2, ",", ".");
                        $pdf->Cell(60, 10, $importeDeFeriadosTrabajados2);
                    }

                    if($bono != 0){
                        $pdf->Ln(10);
                        $pdf->Cell(8);
                        $pdf->Cell(20, 10, utf8_decode("703"));
                        $pdf->Cell(60, 10, utf8_decode("Bono"));
                        $pdf->Cell(20, 10, utf8_decode(" "));
                        settype($bono, "float");
                        $bono2 = number_format($bono, 2, ",", ".");
                        $pdf->Cell(60, 10, $bono2);
                    }

                    $pdf->Ln(10);
                    $pdf->Cell(8);
                    $pdf->Cell(20, 10, utf8_decode("501"));
                    $pdf->Cell(60, 10, utf8_decode("Jubilacion"));
                    $pdf->Cell(20, 10, utf8_decode("11.0"));
                    $pdf->Cell(60, 10, utf8_decode(" "));
                    $jubilacion = - (($sueldoEmpleadoConHaberes * 11)/100);
                    setType($jubilacion, "float");
                    $jubilacion2 = number_format($jubilacion, 2, ",", ".");
                    $pdf->Cell(30, 10, $jubilacion2);
                    
                    $pdf->Ln(10);
                    $pdf->Cell(8);
                    $pdf->Cell(20, 10, utf8_decode("505"));
                    $pdf->Cell(60, 10, utf8_decode("Ley 19032"));
                    $pdf->Cell(20, 10, utf8_decode("3.0"));
                    $pdf->Cell(60, 10, utf8_decode(" "));
                    $ley = - (($sueldoEmpleadoConHaberes * 3)/100);
                    setType($ley, "float");
                    $ley2 = number_format($ley, 2, ",", ".");
                    $pdf->Cell(30, 10, $ley2);
                    
                    $pdf->Ln(10);            
                    $pdf->Cell(8);
                    $pdf->Cell(20, 10, utf8_decode("505"));
                    $pdf->Cell(60, 10, utf8_decode("Obra Social"));
                    $pdf->Cell(20, 10, utf8_decode("3.0"));
                    $pdf->Cell(60, 10, utf8_decode(" "));
                    $obra = - (($sueldoEmpleadoConHaberes * 3)/100);
                    setType($obra, "float");
                    $obra2 = number_format($obra, 2, ",", ".");
                    $pdf->Cell(30, 10, $obra2);
                    
                    $pdf->Ln(10);
                    $pdf->Cell(8);
                    $pdf->Cell(20, 10, utf8_decode("600"));
                    $pdf->Cell(60, 10, utf8_decode("Sindicato"));
                    $pdf->Cell(20, 10, utf8_decode("2.5"));
                    $pdf->Cell(60, 10, utf8_decode(" "));
                    $sindicato = - (($sueldoEmpleadoConHaberes * 2.5)/100);
                    setType($sindicato, "float");
                    $sindicato2 = number_format($sindicato, 2, ",", ".");
                    $pdf->Cell(30,10, $sindicato2);
        
                    $pdf->Ln(10);
                    $pdf->Cell(8);
                    $pdf->Cell(20, 10, utf8_decode("601"));
                    $pdf->Cell(60, 10, utf8_decode("Regulacion de Sindicato"));
                    $pdf->Cell(20, 10, utf8_decode("5.4"));
                    $pdf->Cell(60, 10, utf8_decode(" "));
                    $sindicato2 = - (($sueldoEmpleadoConHaberes * 5.4)/100);
                    setType($sindicato2, "float");
                    $sindicato3 = number_format($sindicato2, 2, ",", ".");
                    $pdf->Cell(30, 10, $sindicato3);
        
                    $pdf->Ln(10);
                    $pdf->Cell(8);
                    $pdf->Cell(20, 10, utf8_decode("670"));
                    $pdf->Cell(60, 10, utf8_decode("Ley de Riesgo de Trabajo"));
                    $pdf->Cell(20, 10, utf8_decode("1.5"));
                    $pdf->Cell(60, 10, utf8_decode(" "));
                    $art = - (($sueldoEmpleadoConHaberes * 1.5)/100);
                    setType($art, "float");
                    $art2 = number_format($art, 2, ",", ".");
                    $pdf->Cell(30, 10, $art2);
        
                    $pdf->Ln(20);
        
                    $pdf->Cell(130,7,utf8_decode("Lugar y Fecha de Cobro"),1,0,'C',1);
                    $pdf->Cell(30,7,utf8_decode("Tot. Remun."),1,0,'C',1);
                    $pdf->Cell(30,7,utf8_decode("Deducciones"),1,0,'C',1);
        
                    $pdf->Ln(7);
                    $fecha = $rowPagoAnterior[9];
                    $fecha = date("d/m/Y", strtotime($fecha));
                    $lugaryfecha = "Rosario. " . $fecha;
                    $pdf->Cell(130,7, $lugaryfecha,1,0,'C');
        
        
                    $totalRemuneraciones2 = number_format($sueldoEmpleadoConHaberes, 2, ",", ".");
                    $pdf->Cell(30,7,$totalRemuneraciones2,1,0,'C');
        
                    $totalDeducciones = $jubilacion + $ley + $obra + $sindicato + $sindicato2 + $art;
                    $totalDeducciones2 = number_format($totalDeducciones, 2, ",", ".");
                    $pdf->Cell(30,7,$totalDeducciones2,1,0,'C');
        
                    $pdf->Ln(7);
                    $pdf->Cell(80,7,utf8_decode("Banco Acreditacion"),1,0,'L',1);
                    $pdf->Cell(60,7,utf8_decode("Cuenta"),1,0,'L',1);
                    $pdf->Cell(50,7,utf8_decode("Total Neto: "),1,0,'L',1);
                    
                    $pdf->Ln(7);
        
                    $pdf->Cell(80,7,utf8_decode("Santander Rio"),1,0,'L');
                    $pdf->Cell(60,7,utf8_decode("022164969"),1,0,'L');
                    $totalNeto = $sueldoEmpleadoConHaberes + $totalDeducciones;
                    $totalNeto2 = number_format($totalNeto, 2, ",", ".");
                    $pdf->Cell(50,7,$totalNeto2,1,'L');
    
                    $pdf->Ln(7);
                    $valorEscrito = convertir($totalNeto);
                    $pdf->MultiCell(190,7,utf8_decode("Son Pesos: \n$valorEscrito"),1,"L");
        
                    $pdf->MultiCell(190,7,utf8_decode("Recibí de conformidad el importe neto en el presente recibo en concepto de haberes correspondiente \nal período arriba indicado quedando en mi poder un duplicado del mismo debidamente \nfirmado por el empleador."),1,'l');
                    $pdf->Cell(150);
                    $pdf->Cell(40,7,utf8_decode("Firma del Empleado"),1,0,'C');
    
                    $pdf->AddPage();
    
                    $pdf->SetFont('Arial', 'B', 10);
                    $pdf->SetFillColor(200,200,200);
                    $pdf->Cell(90,7,utf8_decode("Apellido y Nombre"),1,0,'C',1);
                    $pdf->Cell(50,7,utf8_decode("Legajo"),1,0,'C',1);
                    $pdf->Cell(50,7,utf8_decode("C.U.I.L."),1,0,'C',1);
        
                    $pdf->Ln(7);
        
                    $nombreEmpleado = $row[2] . " " . $row[1];
                    $pdf->Cell(90,7,$nombreEmpleado,1,0,'C');
        
                    $legajoEmpleado = $row[0];
                    $pdf->Cell(50,7,$legajoEmpleado,1,0,'C',);
        
                    $cuilEmpleado = $row[10];
                    $pdf->Cell(50,7,$cuilEmpleado,1,0,'C',);
        
                    $pdf->Ln(7);
        
                    $pdf->Cell(190,7,utf8_decode("Categoria"),1,0,'C',1);
                    
                    $pdf->Ln(7);
        
                    $categoriaEmpleado = $row2[1];
                    $pdf->Cell(190,7,$categoriaEmpleado,1,0,'C');
        
                    $pdf->Ln(7);
        
                    $pdf->Cell(40,7,utf8_decode("Fecha de Ingreso"),1,0,'C',1);
                    $pdf->Cell(40,7,utf8_decode("Sueldo"),1,0,'C',1);
                    $pdf->Cell(40,7,utf8_decode("Periodo"),1,0,'C',1);
                    $pdf->Cell(40,7,utf8_decode("Fecha"),1,0,'C',1);
                    $pdf->Cell(30, 7, utf8_decode("Banco"),1,0,'C',1);
                    
                    $pdf->Ln(7);

                    $fechaDeIngreso = $row[6];
                    $fechaDeIngreso = date("d/m/Y", strtotime($fechaDeIngreso));
                    $pdf->Cell(40,7,$fechaDeIngreso,1,0,'C');

                    $sueldoEmpleado = $rowPagoAnterior[2];
                    $pdf->Cell(40,7,$sueldoEmpleado,1,0,'C');

                    $fecha = $rowPagoAnterior[9];
                    $periodo = date("M Y", strtotime($fecha));
                    $pdf->Cell(40,7,$periodo,1,0,'C');
        
                    $fecha = $rowPagoAnterior[9];
                    $fecha = date("d/m/Y", strtotime($fecha));
                    $pdf->Cell(40,7,$fecha,1,0,'C');
    
                    $pdf->Cell(30,7,utf8_decode("Santander Rio"),1,0,'C');
    
                    $pdf->Ln(7);
        
                    $pdf->Cell(20,7,utf8_decode("Codigo"),1,0,'C',1);
                    $pdf->Cell(60,7,utf8_decode("Detalle"),1,0,'C',1);
                    $pdf->Cell(20,7,utf8_decode("Cantidad"),1,0,'C',1);
                    $pdf->Cell(30,7,utf8_decode("Hab.C/Desc."),1,0,'C',1);
                    $pdf->Cell(30,7,utf8_decode("Hab.S/Desc."),1,0,'C',1);
                    $pdf->Cell(30,7,utf8_decode("Deducciones"),1,0,'C',1);
        
                    $pdf->Ln(10);
                    $pdf->Cell(8);
                    $pdf->Cell(20, 10, utf8_decode("3"));
                    $pdf->Cell(60, 10, utf8_decode("Sueldo"));
                    $pdf->Cell(20, 10, utf8_decode("1.0"));
                    settype($sueldoEmpleado, "float");
                    $sueldoEmpleado2 = number_format($sueldoEmpleado, 2, ",", ".");
                    $pdf->Cell(30, 10, $sueldoEmpleado2);
        
                    if($importeDeHorasExtras != 0){
                        $pdf->Ln(10);
                        $pdf->Cell(8);
                        $pdf->Cell(20, 10, utf8_decode("701"));
                        $pdf->Cell(60, 10, utf8_decode("Horas Extras"));
                        settype($cantidadDeHorasTrabajadas, "float");
                        $cantidadDeHorasTrabajadas2 = number_format($cantidadDeHorasTrabajadas, 1, ".", ",");
                        $pdf->Cell(20, 10, $cantidadDeHorasTrabajadas2);
                        settype($importeDeHorasExtras, "float");
                        $importeDeHorasExtras2 = number_format($importeDeHorasExtras, 2, ",", ".");
                        $pdf->Cell(60, 10, $importeDeHorasExtras2);
                    }
        
                    if($importeFeriadosTrabajados != 0){
                        $pdf->Ln(10);
                        $pdf->Cell(8);
                        $pdf->Cell(20, 10, utf8_decode("702"));
                        $pdf->Cell(60, 10, utf8_decode("Feriados Trabajados"));
                        settype($cantidadDeFeriadosTrabajados, "float");
                        $cantidadDeFeriadosTrabajados2 = number_format($cantidadDeFeriadosTrabajados, 1, ".", ",");
                        $pdf->Cell(20, 10, $cantidadDeFeriadosTrabajados2);
                        settype($importeFeriadosTrabajados, "float");
                        $importeDeFeriadosTrabajados2 = number_format($importeFeriadosTrabajados, 2, ",", ".");
                        $pdf->Cell(60, 10, $importeDeFeriadosTrabajados2);
                    }

                    if($bono != 0){
                        $pdf->Ln(10);
                        $pdf->Cell(8);
                        $pdf->Cell(20, 10, utf8_decode("703"));
                        $pdf->Cell(60, 10, utf8_decode("Bono"));
                        $pdf->Cell(20, 10, utf8_decode(" "));
                        settype($bono, "float");
                        $bono2 = number_format($bono, 2, ",", ".");
                        $pdf->Cell(60, 10, $bono2);
                    }

                    $pdf->Ln(10);
                    $pdf->Cell(8);
                    $pdf->Cell(20, 10, utf8_decode("501"));
                    $pdf->Cell(60, 10, utf8_decode("Jubilacion"));
                    $pdf->Cell(20, 10, utf8_decode("11.0"));
                    $pdf->Cell(60, 10, utf8_decode(" "));
                    $jubilacion = - (($sueldoEmpleadoConHaberes * 11)/100);
                    setType($jubilacion, "float");
                    $jubilacion2 = number_format($jubilacion, 2, ",", ".");
                    $pdf->Cell(30, 10, $jubilacion2);
                    
                    $pdf->Ln(10);
                    $pdf->Cell(8);
                    $pdf->Cell(20, 10, utf8_decode("505"));
                    $pdf->Cell(60, 10, utf8_decode("Ley 19032"));
                    $pdf->Cell(20, 10, utf8_decode("3.0"));
                    $pdf->Cell(60, 10, utf8_decode(" "));
                    $ley = - (($sueldoEmpleadoConHaberes * 3)/100);
                    setType($ley, "float");
                    $ley2 = number_format($ley, 2, ",", ".");
                    $pdf->Cell(30, 10, $ley2);
                    
                    $pdf->Ln(10);            
                    $pdf->Cell(8);
                    $pdf->Cell(20, 10, utf8_decode("505"));
                    $pdf->Cell(60, 10, utf8_decode("Obra Social"));
                    $pdf->Cell(20, 10, utf8_decode("3.0"));
                    $pdf->Cell(60, 10, utf8_decode(" "));
                    $obra = - (($sueldoEmpleadoConHaberes * 3)/100);
                    setType($obra, "float");
                    $obra2 = number_format($obra, 2, ",", ".");
                    $pdf->Cell(30, 10, $obra2);
                    
                    $pdf->Ln(10);
                    $pdf->Cell(8);
                    $pdf->Cell(20, 10, utf8_decode("600"));
                    $pdf->Cell(60, 10, utf8_decode("Sindicato"));
                    $pdf->Cell(20, 10, utf8_decode("2.5"));
                    $pdf->Cell(60, 10, utf8_decode(" "));
                    $sindicato = - (($sueldoEmpleadoConHaberes * 2.5)/100);
                    setType($sindicato, "float");
                    $sindicato2 = number_format($sindicato, 2, ",", ".");
                    $pdf->Cell(30,10, $sindicato2);
        
                    $pdf->Ln(10);
                    $pdf->Cell(8);
                    $pdf->Cell(20, 10, utf8_decode("601"));
                    $pdf->Cell(60, 10, utf8_decode("Regulacion de Sindicato"));
                    $pdf->Cell(20, 10, utf8_decode("5.4"));
                    $pdf->Cell(60, 10, utf8_decode(" "));
                    $sindicato2 = - (($sueldoEmpleadoConHaberes * 5.4)/100);
                    setType($sindicato2, "float");
                    $sindicato3 = number_format($sindicato2, 2, ",", ".");
                    $pdf->Cell(30, 10, $sindicato3);
        
                    $pdf->Ln(10);
                    $pdf->Cell(8);
                    $pdf->Cell(20, 10, utf8_decode("670"));
                    $pdf->Cell(60, 10, utf8_decode("Ley de Riesgo de Trabajo"));
                    $pdf->Cell(20, 10, utf8_decode("1.5"));
                    $pdf->Cell(60, 10, utf8_decode(" "));
                    $art = - (($sueldoEmpleadoConHaberes * 1.5)/100);
                    setType($art, "float");
                    $art2 = number_format($art, 2, ",", ".");
                    $pdf->Cell(30, 10, $art2);
        
                    $pdf->Ln(20);
        
                    $pdf->Cell(130,7,utf8_decode("Lugar y Fecha de Cobro"),1,0,'C',1);
                    $pdf->Cell(30,7,utf8_decode("Tot. Remun."),1,0,'C',1);
                    $pdf->Cell(30,7,utf8_decode("Deducciones"),1,0,'C',1);
        
                    $pdf->Ln(7);
                    $fecha = $rowPagoAnterior[8];
                    $fecha = date("d/m/Y", strtotime($fecha));
                    $lugaryfecha = "Rosario. " . $fecha;
                    $pdf->Cell(130,7, $lugaryfecha,1,0,'C');
        
                    $totalRemuneraciones2 = number_format($sueldoEmpleadoConHaberes, 2, ",", ".");
                    $pdf->Cell(30,7,$totalRemuneraciones2,1,0,'C');
        
                    $totalDeducciones = $jubilacion + $ley + $obra + $sindicato + $sindicato2 + $art;
                    $totalDeducciones2 = number_format($totalDeducciones, 2, ",", ".");
                    $pdf->Cell(30,7,$totalDeducciones2,1,0,'C');
        
                    $pdf->Ln(7);
                    $pdf->Cell(80,7,utf8_decode("Banco Acreditacion"),1,0,'L',1);
                    $pdf->Cell(60,7,utf8_decode("Cuenta"),1,0,'L',1);
                    $pdf->Cell(50,7,utf8_decode("Total Neto: "),1,0,'L',1);
                    
                    $pdf->Ln(7);
        
                    $pdf->Cell(80,7,utf8_decode("Santander Rio"),1,0,'L');
                    $pdf->Cell(60,7,utf8_decode("022164969"),1,0,'L');
                    $totalNeto = $sueldoEmpleadoConHaberes + $totalDeducciones;
                    $totalNeto2 = number_format($totalNeto, 2, ",", ".");
                    $pdf->Cell(50,7,$totalNeto2,1,'L');
    
                    $pdf->Ln(7);
                    $valorEscrito = convertir($totalNeto);
                    $pdf->MultiCell(190,7,utf8_decode("Son Pesos: \n$valorEscrito"),1,"L");
        
                    $pdf->Cell(190,7,utf8_decode("El presente es duplicado del recibo original que obra en nuestro poder firmado por el empleado."),1,0,'L');
                    $pdf->Ln(7);
                    $pdf->Cell(150);
                    $pdf->Cell(40,7,utf8_decode("Firma del Empleador"),1,0,'C');    
    
                    $pdf->Output();
        
                    $stmt->execute();
                }
?>