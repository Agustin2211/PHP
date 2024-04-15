<?php
    require 'database.php';
    session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Asientos Contables</title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/php-login/assets/css/style.css">
    </head>

    <body>

        <h1>Asientos Contables</h1>
        
        <form action="buscarAsientoFecha.php" method="POST">
            <p>
                <label>Ingrese la Fecha: </label><input type="date" id="date" name="date">
            </p>

            <p>
                <input type="submit" name="search" id="search" value="Buscar por Fecha">
            </p>
        </form>

        <body>

        <?php

            include("funciones.php");

            if (isset($_POST['search'])) {

                $date = $_POST['date'];
                $connection = mysqli_connect("localhost", "root", "", "php_login_database");
                $sql = "SELECT * FROM asiento WHERE fecha like '$date'";
                $result = db_query($sql);

                                if (!empty($result)) {
                //Si no hay registros encontrados
                ?>

                    <table>
		                <tr>
			                <th width="30%">Cuenta</th>
			                <th width="30%">Fecha</th>
			                <th width="30%">Detalle</th>
                            <th width="30%">Ver</th>
		                </tr>
	
                        <?php while($row = mysqli_fetch_object($result)){ 
                                                $fecha = $row->fecha;
                                                $fecha = date("d/m/Y", strtotime($fecha));?>

                        <tr>
			                <td><?php echo $row->id;?></td>
                            <td><?php echo $fecha;?></td>
                            <td><?php echo $row->detalle;?></td>
                            <td>
                                <a href="buscarAsiento.php?id=<?php echo $row->id; clearstatcache(); ?>">Ver</a>
        	                </td>
                        </tr>
 
                    <?php }
                }else{
                        echo '<h2>No se ha encontrado la Cuenta.</h2>';
                    }
            }?>
    
        </table>

        <form>
            <input type="buttom" value="Atras" onclick="location.href='/php-login/Administrador/principallibroDiario.php'">
        </form>

    </body>
    
</html>