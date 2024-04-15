<?php

    include('funciones.php');

    session_start();

    clearstatcache();

    require('database.php');
    
    include('../Administrador/pdf.php');

    date_default_timezone_set('America/Argentina/Buenos_Aires');

    if(!empty($_POST)){

        /*LO PRIMERO QUE SE HACE ES REGISTRAR LAS HORAS EXTRAS Y FERIADOS QUE TRABAJO EL EMPLEADO*/

        $idEmpleado = $_POST['id'];

        $importeDeHorasExtras = $_POST['horasExtras'] * $_POST['ValorDeHorasExtras'];
        $cantidadDeHorasTrabajadas = $_POST['horasExtras'];
        $importeFeriadosTrabajados = $_POST['feriadosTrabajados'] * $_POST['valorDeFeriadosTrabajados'];
        $cantidadDeFeriadosTrabajados = $_POST['feriadosTrabajados'];
        $bono = $_POST['bono'];

        $sql = ("SELECT * FROM empleado WHERE id like '$idEmpleado'");
        $result = db_query($sql);
        $row = mysqli_fetch_array($result);

        $puesto = $row[11];

        $sql2 = ("SELECT * FROM puestoempleado WHERE id like '$puesto'");
        $result2 = db_query($sql2);
        $row2 = mysqli_fetch_array($result2);

        $sueldoMinimo = $row2[3];

        /*UNA VEZ QUE SE TIENE EL SUELDO, SE HACEN TODOS LAS RESTAS Y SUMAS A SU SUELDO*/

        $sueldoEmpleadoConHaberes = $sueldoMinimo + $importeDeHorasExtras + $importeFeriadosTrabajados + $bono;

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

        /*SE REALIZAN LOS ASIENTOS CORRESPONDIENTES A LA TABLAPOST*/
        $cuenta = 530;
        $haber  = 0;
        $stmt = $conn->prepare("INSERT INTO tablapost (cuenta, debe, haber) VALUES ('$cuenta', '$sueldo', '$haber')");
        $stmt->bindParam('cuenta', $cuenta);
        $stmt->bindParam('debe', $sueldo);
        $stmt->bindParam('haber', $haber);
        $stmt->execute();

        $tipoPago = $_POST['formaPago'];

        /*SI SE PAGA TODO CON DINERO, SE ENTRA A ESTE IF*/
        if($tipoPago == 'caja'){
            $cuenta = 111;
            $haber = $sueldo;
            $debe = 0;  
            $stmt = $conn->prepare("INSERT INTO tablapost (cuenta, debe, haber) VALUES ('$cuenta', '$debe', '$haber')");
            $stmt->bindParam('cuenta', $cuenta);
            $stmt->bindParam('debe', $debe);
            $stmt->bindParam('haber', $haber);
            $stmt->execute();
        
        /*SI SE PAGA TODO CON LO DEL BANCO, SE ENTRA A ESTE IF*/
        }elseif ($tipoPago == 'banco') {
            $cuenta = 113;
            $haber = $sueldo;
            $debe = 0;  
            $stmt = $conn->prepare("INSERT INTO tablapost (cuenta, debe, haber) VALUES ('$cuenta', '$debe', '$haber')");
            $stmt->bindParam('cuenta', $cuenta);
            $stmt->bindParam('debe', $debe);
            $stmt->bindParam('haber', $haber);
            $stmt->execute();
        }

        $saldo1 = 0;
        $saldo2 = 0;
        $sql = "SELECT * FROM tablapost";
        $result= db_query($sql);

        while($ver=mysqli_fetch_row($result)){
            $saldo1 = $saldo1 + ($ver[2]);
            $saldo2 = $saldo2 + ($ver[3]);
            $saldo = ($saldo1) - ($saldo2);
        }

            if($saldo == 0){
                /*ASIENTO INTRODUCIDO EN LIBRO DIARIO*/
                $fecha = date("Y-m-d");
                $detalle = $_POST['detalle'];
                $records = $conn->prepare("INSERT INTO asiento (fecha, detalle) VALUES ('$fecha', '$detalle')");
                $records->bindParam('fecha', $fecha);
                $records->bindParam('detalle', $detalle);
                $records->execute();

                $sqlAsiento = "SELECT * FROM tablapost";
                $resultAsiento= db_query($sqlAsiento);
                
                while($ver=mysqli_fetch_row($resultAsiento)){
                    $saldo1 = $ver[2];
                    $saldo2 = $ver[3];
                    $idcuenta = $ver[1];
                    
                    /*DE ACA OBTENGO EL IDASIENTO DE LA TABLA ASIENTO*/
                    $sql2 = "SELECT MAX(id) FROM asiento";
                    $result2 = db_query($sql2);
                    $ver2=mysqli_fetch_array($result2);
                    $idasiento = $ver2[0];
                    
                    if($saldo1 != 0){
                        
                        $cero = 0;

                        $records3 = $conn->prepare("INSERT INTO cuentaasiento (fecha, debe, haber, idCuenta, idAsiento) VALUES ('$fecha', '$saldo1', '$cero', '$idcuenta', '$idasiento')");
                        $records3->bindParam('fecha', $fecha);
                        $records3->bindParam('debe', $saldo1);
                        $records3->bindParam('haber', $cero);
                        $records3->bindParam('idCuenta', $idcuenta);
                        $records3->bindParam('idAsiento', $idasiento);  
                        $records3->execute();

                    }else{

                            $cero = 0;

                            $records3 = $conn->prepare("INSERT INTO cuentaasiento (fecha, debe, haber, idCuenta, idAsiento) VALUES ('$fecha', '$cero', '$saldo2', '$idcuenta', '$idasiento')");
                            $records3->bindParam('fecha', $fecha);
                            $records3->bindParam('debe', $cero);
                            $records3->bindParam('haber', $saldo2);
                            $records3->bindParam('idCuenta', $idcuenta);
                            $records3->bindParam('idAsiento', $idasiento);  
                            $records3->execute();

                        }

                /*UNA VEZ REALIZADA TODA ESTA PARTE, LO QUE SE DEBE HACER ES EL CONTRA ASIENTO EN CUESTION*/
                }
            
                $stmt = $conn->prepare("TRUNCATE TABLE tablapost");
                $stmt->execute();

                $fecha = date("Y-m-d");
                $sindicato2 = (($sueldoEmpleadoConHaberes * 5.4)/100);
                $obra = (($sueldoEmpleadoConHaberes * 3)/100);
    
                $cuenta = 530;
                $debe = $sueldoEmpleadoConHaberes;
                $haber = 0;
                $stmt = $conn->prepare("INSERT INTO tablapost (cuenta, debe, haber) VALUES ('$cuenta', '$debe', '$haber')");
                $stmt->bindParam('cuenta', $cuenta);
                $stmt->bindParam('debe', $debe);
                $stmt->bindParam('haber', $haber);
                $stmt->execute();
    
    
                $cuenta = 212;
                $debe = 0;
                $haber = $sueldoEmpleadoConHaberes - ($jubilacion + $ley + $obra + $art + $sindicato + $sindicato2);
                $stmt = $conn->prepare("INSERT INTO tablapost (cuenta, debe, haber) VALUES ('$cuenta', '$debe', '$haber')");
                $stmt->bindParam('cuenta', $cuenta);
                $stmt->bindParam('debe', $debe);
                $stmt->bindParam('haber', $haber);
                $stmt->execute();
    
                $cuenta = 240;
                $debe = 0;
                $haber = ($jubilacion + $ley + $obra + $art);
                $stmt = $conn->prepare("INSERT INTO tablapost (cuenta, debe, haber) VALUES ('$cuenta', '$debe', '$haber')");
                $stmt->bindParam('cuenta', $cuenta);
                $stmt->bindParam('debe', $debe);
                $stmt->bindParam('haber', $haber);
                $stmt->execute();
    
                $cuenta = 250;
                $debe = 0;
                $haber = $sindicato + $sindicato2;
                $stmt = $conn->prepare("INSERT INTO tablapost (cuenta, debe, haber) VALUES ('$cuenta', '$debe', '$haber')");
                $stmt->bindParam('cuenta', $cuenta);
                $stmt->bindParam('debe', $debe);
                $stmt->bindParam('haber', $haber);
                $stmt->execute();
                
                $detalle = "ContraAsiento de pago de sueldo";
                $records = $conn->prepare("INSERT INTO asiento (fecha, detalle) VALUES ('$fecha', '$detalle')");
                $records->bindParam('fecha', $fecha);
                $records->bindParam('detalle', $detalle);
                $records->execute();
    
                $sqlAsiento = "SELECT * FROM tablapost";
                $resultAsiento= db_query($sqlAsiento);
                    
                while($ver=mysqli_fetch_row($resultAsiento)){
                    $saldo1 = $ver[2];
                    $saldo2 = $ver[3];
                    $idcuenta = $ver[1];
                        
                    /*DE ACA OBTENGO EL IDASIENTO DE LA TABLA ASIENTO*/
                    $sql2 = "SELECT MAX(id) FROM asiento";
                    $result2 = db_query($sql2);
                    $ver2=mysqli_fetch_array($result2);
                    $idasiento = $ver2[0];
                        
                        if($saldo1 != 0){
                            
                            $cero = 0;
    
                            $records3 = $conn->prepare("INSERT INTO cuentaasiento (fecha, debe, haber, idCuenta, idAsiento) VALUES ('$fecha', '$saldo1', '$cero', '$idcuenta', '$idasiento')");
                            $records3->bindParam('fecha', $fecha);
                            $records3->bindParam('debe', $saldo1);
                            $records3->bindParam('haber', $cero);
                            $records3->bindParam('idCuenta', $idcuenta);
                            $records3->bindParam('idAsiento', $idasiento);  
                            $records3->execute();
    
                        }else{
    
                                $cero = 0;
    
                                $records3 = $conn->prepare("INSERT INTO cuentaasiento (fecha, debe, haber, idCuenta, idAsiento) VALUES ('$fecha', '$cero', '$saldo2', '$idcuenta', '$idasiento')");
                                $records3->bindParam('fecha', $fecha);
                                $records3->bindParam('debe', $cero);
                                $records3->bindParam('haber', $saldo2);
                                $records3->bindParam('idCuenta', $idcuenta);
                                $records3->bindParam('idAsiento', $idasiento);  
                                $records3->execute();
    
                            }
                }

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

                $sueldoEmpleado = $row2[3];
                $pdf->Cell(50,7,$sueldoEmpleado,1,0,'C');

                $periodo = date("M Y");
                $pdf->Cell(50,7,$periodo,1,0,'C');

                $fecha = date("d/m/Y");
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
                    $importeFeriadosTrabajados2 = number_format($importeFeriadosTrabajados, 2, ",", ".");
                    $pdf->Cell(60, 10, $importeFeriadosTrabajados2);
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
                $lugaryfecha = "Rosario. " . date("d/m/Y");
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

                $sueldoEmpleado = $row2[3];
                $pdf->Cell(50,7,$sueldoEmpleado,1,0,'C');

                $periodo = date("M Y");
                $pdf->Cell(50,7,$periodo,1,0,'C');

                $fecha = date("d/m/Y");
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
                    $importeFeriadosTrabajados2 = number_format($importeFeriadosTrabajados, 2, ",", ".");
                    $pdf->Cell(60, 10, $importeFeriadosTrabajados2);
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
                $lugaryfecha = "Rosario. " . date("d/m/Y");
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
                $pdf->Cell(150);
                $pdf->Cell(40,7,utf8_decode("Firma del Empleador"),1,0,'C');
                
                $pdf->Output();

                $stmt = $conn->prepare("TRUNCATE TABLE tablapost");
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

                $sueldoEmpleado = $row2[3];
                $pdf->Cell(40,7,$sueldoEmpleado,1,0,'C');

                $periodo = date("M Y", strtotime($fecha));
                $pdf->Cell(40,7,$periodo,1,0,'C');
    
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
                    $importeFeriadosTrabajados2 = number_format($importeFeriadosTrabajados, 2, ",", ".");
                    $pdf->Cell(60, 10, $importeFeriadosTrabajados2);
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
                $lugaryfecha = "Rosario. " . date("d/m/Y");
                $pdf->Cell(130,7, $lugaryfecha,1,0,'C');
    
                $totalRemuneraciones2 = number_format($sueldoEmpleadoConHaberes, 2, ",", ".");
                $pdf->Cell(30,7,$sueldoRemuneraciones2,1,0,'C');
    
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
                $pdf->ln(7);
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
                $pdf->Cell(50,7,$legajoEmpleado,1,0,'C');
    
                $cuilEmpleado = $row[10];
                $pdf->Cell(50,7,$cuilEmpleado,1,0,'C');
    
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

                $sueldoEmpleado = $row2[3];
                $pdf->Cell(40,7,$sueldoEmpleado,1,0,'C');

                $periodo = date("M Y", strtotime($fecha));
                $pdf->Cell(40,7,$periodo,1,0,'C');
    
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
                    $ImporteDeFeriadosTrabajados2 = number_format($importeFeriadosTrabajados, 2, ",", ".");
                    $pdf->Cell(60, 10, $ImporteDeFeriadosTrabajados2);
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
                $art = - (($sueldoEmpleado * 1.5)/100);
                setType($art, "float");
                $art2 = number_format($art, 2, ",", ".");
                $pdf->Cell(30, 10, $art2);
    
                $pdf->Ln(20);
    
                $pdf->Cell(130,7,utf8_decode("Lugar y Fecha de Cobro"),1,0,'C',1);
                $pdf->Cell(30,7,utf8_decode("Tot. Remun."),1,0,'C',1);
                $pdf->Cell(30,7,utf8_decode("Deducciones"),1,0,'C',1);
    
                $pdf->Ln(7);
                $lugaryfecha = "Rosario. " . date("d/m/Y");
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

                $stmt = $conn->prepare("TRUNCATE TABLE tablapost");
                $stmt->execute();


            }
    }

        $fecha = date("Y-m-d");
        $stmt = $conn->prepare("INSERT INTO pagosanteriores (idEmpleado, sueldoMinimo, sueldoCobrado, horasExtras, horasExtrasTrabajados, feriadosTrabajados, cantidadDeFeriadosTrabajados,bono, fecha, tipoPago) VALUES ('$idEmpleado', '$sueldoMinimo', $sueldo,'$importeDeHorasExtras', '$cantidadDeHorasTrabajadas',  '$importeFeriadosTrabajados', '$cantidadDeFeriadosTrabajados','$bono', '$fecha',  '$tipoPago')");
        $stmt->bindParam('idEmpleado', $idEmpleado);
        $stmt->bindParam('sueldoMinimo', $sueldoMinimo);
        $stmt->bindParam('sueldoCobrado', $sueldo);
        $stmt->bindParam('horasExtras', $importeDeHorasExtras);
        $stmt->bindParam('horasExtrasTrabajadas', $cantidadDeHorasTrabajadas);
        $stmt->bindParam('feriadosTrabajados', $importeFeriadosTrabajados);
        $stmt->bindParam('cantidadDeFeriadosTrabajos', $cantidadDeFeriadosTrabajados);
        $stmt->bindParam('bono', $bono);
        $stmt->bindParam('fecha', $fecha);
        $stmt->bindParam('tipoPago', $tipoPago);
        $stmt->execute();
    }
?>

<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Pagar Sueldo</title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/php-login/assets/css/style.css">
    </head>

    <body>

        <form action="pagarSueldoEmpleado.php" class="form-inline" role="form" method="POST">       

            <p>
                <label>Pago de Sueldo al Empleado: </label><select class="form-control imput-sm" name="id" id="id" this.options[this.selectedIndex].innerHTML>
                                                                <option value=<?php echo 0?>>Seleccionar Empleado</option>
                                                                    <?php
                                                                        $sql = "SELECT * FROM empleado";
                                                                        $result = db_query($sql);
                                                                    ?>

                                                                <?php while($row=mysqli_fetch_row($result)): ?>
                                                                    <option value= <?php echo $row[0] ?> > <?php echo $row[1] . " " . $row[2];?> </option>
                                                                <?php endwhile ?>
                                                            </select>




            </p>

            <p>
                <label>Horas Extras Trabajadas: </label><input step="any" type="number" step="0.01" name="horasExtras" value='0' id="horasExtras" min='0' required><label> </label><label>Valor de 1 hora Trabajada: </label><input step="any" type="number" step="0.01" name="ValorDeHorasExtras" value='0' id="ValorDeHorasExtras" min='0' required>
            </p>

            <p>
                <label>Feriados Trabajados: </label><input step="any" type="number" step="0.01" name="feriadosTrabajados" value='0' id="feriadosTrabajados" min='0' required><label> </label><label>Valor de un Dia Trabajado: </label><input step="any" type="number" step="0.01" name="valorDeFeriadosTrabajados" value='0' id="valorDeFeriadosTrabajados" min='0' required>
            </p>

            <p>
                <label>Bono: </label><select class="form-control input-sm" name="bono" id="bono" this.options[this.selectedIndex].innerHTML>
                                                                    <option value= <?php echo 0 ?> >Ninguno</option>
                                                                <?php 
                                                                    $sql="SELECT * FROM bono";
                                                                    $result=db_query($sql);
                                                                ?>
        
                                                                <?php while($row=mysqli_fetch_row($result)): ?>
                                                                    <option value= <?php echo $row[3] ?> > <?php echo $row[1];?> </option>
                                                                <?php endwhile ?>
                                                            </select>
            </p>
            
            <textarea readonly>Los aportes y retenciones son los siguientes:
                o Aporte Personal Jubilación: 11%
                o Aporte Personal O. Social: 3%
                o Aporte Personal Sindicato: 2.5%
                o Contribución Patronal O. Social: 5.4%
                o Ley de Riesgo de Trabajo (A.R.T.): 1,5%
            </textarea>

            <p>
                <label>Detalle: </label><input name="detalle" type="text" id="detalle" required>
            </p>

            <p>
                <label>Fecha: </label> <input type="datetime" name="fecha" required readonly value="<?php echo date("d/m/Y");?>">
            </p>

            <p>
                <label>Forma de Pago: </label> <select class="form-control input-sm" name="formaPago" id="formaPago" this.options[this.selectedIndex].innerHTML>
                                                                    <option value="caja">Caja</option>
                                                                    <option value="banco">Banco Cuenta Corriente</option>
                                                            </select>
            </p>

            <input type="submit" value="Pagar Sueldo">

        </form>

        <form>
            <input type="buttom" value="Atras" onclick="location.href='pagoDeSueldos.php'">
        </form>

    </body>

</html>