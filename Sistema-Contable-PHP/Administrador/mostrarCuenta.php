<?php

    include('funciones.php');

    session_start();

    clearstatcache();

    require('database.php');

    $message = '';

    $cuenta = $_GET['cuenta'];

?>

<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Mostrar Cuenta</title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/php-login/assets/css/style.css">
        <h1>Mostrar Cuenta</h1>
    </head>

    <body>
        <table>
            <tr>
			    <th width="30%">Fecha</th>
			    <th width="30%">Debe</th>
			    <th width="30%">Haber</th>
		    </tr>
	
	        <?php 
                $sql = ("SELECT *
                        FROM cuentaasiento
                        WHERE idCuenta = '$cuenta'");
                $result = db_query($sql);
		        while($row = mysqli_fetch_object($result)){
                    $fecha = $row->fecha;
                    $fecha = date("d/m/Y", strtotime($fecha));  
            ?>
	
		    <tr>
                <td><?php echo $fecha;?></td>
                <td><?php echo $row->debe;?></td>
                <td><?php echo $row->haber;?></td>
		    </tr>
	        
            <?php } ?>

        </table>

        <form>
            <input type="buttom" value="Atras" OnClick = "location.href='libromayor.php'">
        </form>

    </body>

</html>