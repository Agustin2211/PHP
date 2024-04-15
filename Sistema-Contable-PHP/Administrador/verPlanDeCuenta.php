<!DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title>Plan de Cuenta</title>
            <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
            <link rel="stylesheet" href="/php-login/assets/css/style.css">
            <h1>Plan de Cuenta</h1>
        </head>

    <body>
        <?php
	        include("funciones.php");
	    ?>

	    <table>
		    <tr>
			    <th width="30%">Cuenta</th>
			    <th width="30%">Codigo</th>
			    <th width="30%">Tipo</th>
                <th width="30%">Visibilidad</th>
                <th width="30%">Cambiar Visibilidad</th>
		    </tr>
	
	    <?php 
		    $sql = "select * from cuentas order by codigo";
		    $result = db_query($sql);
		    while($row = mysqli_fetch_object($result)){
        ?>
	
		    <tr>
			    <td><?php echo $row->cuenta;?></td>
                <td><?php echo $row->codigo;?></td>
                <td><?php echo $row->tipo;?></td>
                <td><?php echo $row->recibeSaldo;?></td>
			    <td>
			    <a href="editarCuenta.php?id=<?php echo $row->id;?>"><img src='/php-login/images/actualizar.gif' class='img-rounded'/></a>
        	    </td>
		    </tr>
	    <?php } ?>

        </table>
    
        </form action="" metod="POST">
            <input type="buttom" value="Buscar Cuenta" onclick="location.href='buscarCuenta.php'"><label>     </label><input type="buttom" value="Agregar Cuenta" onclick="location.href='agregarCuenta.php'">
        </form>

        <form>
            <input type="buttom" value="Atras" OnClick = "location.href='plandecuenta.php'">
        </form>

    </body>
</html>