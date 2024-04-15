
<!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Lista de Bonos</title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/php-login/assets/css/style.css">
        <h1>Lista de Bonoos</h1>
    </head>

    <body>
        <?php
	        include("funciones.php");
	    ?>

	    <table>
		    <tr>
			    <th width="30%">Nombre</th>
			    <th width="30%">Descripcion</th>
                <th width="30%">Importe</th>
		    </tr>
	
	    <?php 
		    $sql = "SELECT * 
                    FROM bono";
		    $result = db_query($sql);
		    while($row = mysqli_fetch_object($result)){
        ?>
	
		    <tr>
			    <td><?php echo $row->nombre;?></td>
                <td><?php echo $row->descripcion;?></td>
                <td><?php echo $row->importe;?></td>
		    </tr>
	    <?php } ?>

        </table>

        <form>
            <input type="buttom" value="Atras" OnClick = "location.href='bonos.php'">
        </form>

    </body>

</html>