<!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Libro Mayor</title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/php-login/assets/css/style.css">
        <h1>Libro Mayor</h1>
    </head>

    <body>
        <?php
	        include("funciones.php");
	    ?>

	    <table>
		    <tr>
			    <th width="30%">Cuenta</th>
			    <th width="30%">Codigo</th>
			    <th width="30%">Ver</th>

		    </tr>
	
	    <?php 
		    $sql = "SELECT * 
                    FROM cuentas
                    WHERE recibeSaldo = '1'
                    ORDER BY codigo";
		    $result = db_query($sql);
		    while($row = mysqli_fetch_object($result)){
        ?>
	
		    <tr>
			    <td><?php echo $row->cuenta;?></td>
                <td><?php echo $row->codigo;?></td>
			    <td>
			    <a href="mostrarCuenta.php?cuenta=<?php echo $row->codigo;?>">Ver</a>
        	    </td>
		    </tr>
	    <?php } ?>

        </table>

        <form>
            <input type="buttom" value="Atras" OnClick = "location.href='sistemaContable.php'">
        </form>

    </body>

</html>