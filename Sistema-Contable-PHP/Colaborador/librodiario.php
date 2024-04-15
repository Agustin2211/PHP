<!DOCTYPE html>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Libro Diario</title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/php-login/assets/css/style.css">
        <h1>Libro Diario</h1>
        <h2>Asientos Cargados</h2>
    </head>

    <body>

        <?php
            include("funciones.php");
        ?>

        <table>
            <tr>
                <th width="30%">Asiento</th>
                <th width="30%">Fecha</th>
                <th width="30%">Detalle</th>
                <th width="30%">Ver</th>
            </tr>

            <?php 
                $sql = "select * from asiento";
                $result = db_query($sql);
                while($row = mysqli_fetch_object($result)){
                    $fecha = $row->fecha;
                    $fecha = date("d/m/Y", strtotime($fecha));
            ?>

            <tr>
                <td><?php echo $row->id;?></td>
                <td><?php echo $fecha;?></td>
                <td><?php echo $row->detalle;?></td>
                <td>
                    <a href="buscarAsiento.php?id=<?php echo $row->id; clearstatcache(); ?>">Ver</a>
        	    </td>

            </tr>
            <?php } ?>

        </table>

        <p>
            <form>
                <input type="buttom" value="Registrar Nuevo Asiento" onclick="location.href = 'nuevoAsiento.php'">
            </form>
    
	        <form>
		        <input type="buttom" value ="Atras" onclick="location.href = 'principalLibroDiario.php'">
	        </form>
        </p>
        
    </body>

</html>