
<!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Pagos Anteriores</title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/php-login/assets/css/style.css">
        <h1>Pagos de Sueldos Anteriores</h1>
    </head>

    <body>

	    <table>
		    <tr>
			    <th width="30%">Fecha</th>
			    <th width="30%">Metodo de Pago</th>
                <th width="30%">Sueldo Cobrado</th>
                <th width="30%">Generar Comprobante</th>
		    </tr>
	
	    <?php 
            include('funciones.php');
            $idEmpleado = $_GET['id'];
		    $sql = "SELECT * 
                    FROM pagosanteriores
                    WHERE idEmpleado = $idEmpleado";
		    $result = db_query($sql);
		    while($row = mysqli_fetch_object($result)){
                $fecha = $row->fecha;
                $fecha = date("d/m/Y", strtotime($fecha));
        ?>
	
		    <tr>
			    <td><?php echo $fecha;?></td>
                <td><?php echo $row->tipoPago;?></td>
                <td><?php echo $row->sueldoCobrado;?></td>
                <td><a href="reciboDeSueldoAnterior.php?id=<?php echo $row->id;?>">Generar</a></td>
		    </tr>
	    <?php } ?>

        </table>

        <form>
            <input type="buttom" value="Atras" OnClick = "location.href='pagosAnteriores.php'">
        </form>

    </body>

</html>