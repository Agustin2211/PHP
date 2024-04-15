<?php

    include('funciones.php');

    session_start();

    clearstatcache();

    require('database.php');

    date_default_timezone_set('America/Argentina/Buenos_Aires');

    if(!empty($_POST)){

        $saldo1 = 0;
        $saldo2 = 0;
        $sql = "SELECT * FROM tablapost";
        $result= db_query($sql);

        /*REVISAR ESTE WHILE QUE NO ANDA COMO DEBERIA HACERLO*/
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
                
                }

                $stmt = $conn->prepare("TRUNCATE TABLE tablapost");
                $stmt->execute();

                echo "ASIENTOS CARGADOS EXITOSAMENTE";
            }else{
                echo "EL METODO DE LA PARTIDA DOBLE NO SE CUMPLE";
            }    
    }
?>

<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Registrar Asiento</title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/php-login/assets/css/style.css">
    </head>

    <body>

        <form action="registrarAsientos.php" class="form-inline" role="form" method="POST">            
            <p>
                <label>Fecha: </label> <input type="datetime" name="fecha" required readonly value="<?php echo date("Y-m-d");?>">
            </p>

            <p>
                <label>Detalle: </label><input name="detalle" type="text" id="detalle" required>
            </p>
            <input type="submit" value="Terminar Carga de Asiento">
        </form>

        <form>
            <input type="buttom" value="Atras" onclick="location.href='nuevoAsiento.php'">
        </form>

        <form>
            <input type="buttom" value="Menu Principal" onclick="location.href='admin.php'">
        </form>

    </body>

</html>