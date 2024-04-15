<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Buscar Bono</title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/php-login/assets/css/style.css">
    </head>

    <h1>Buscar Bono</h1>

    <form action="buscarBono.php" method="POST">

        <p>
            <label>Buscar Bono: </label><input type="text" id="keywords" name="keywords" size="30" maxlength="30">
        </p>

        <p>
            <input type="submit" name="search" value="Buscar">
        </p>    
    </form>

    <body>
        <?php

            include("funciones.php");

            if(!empty($_POST)){
                $keywords = $_POST['keywords'];
                $connection = mysqli_connect("localhost", "root", "", "php_login_database");
                $sql = "select * from bono where nombre LIKE '%$keywords%'";
                $result = db_query($sql);
 
            //Si ha resultados
            if (!empty($result)) {
            //Si no hay registros encontrados
    ?>
        <table>
		    <tr>
                <th width="30%">Nombre</th>
                <th width="30%">Descripcion</th>
                <th width="30%">Importe</th>
		    </tr>
	
        <?php 
            while($row = mysqli_fetch_object($result)){
        ?>
            <tr>
                <td><?php echo $row->nombre;?></td>
                <td><?php echo $row->descripcion;?></td>
			    <td><?php echo $row->importe;?></td>
            </tr>
 
        <?php }
            }else{
            echo "No se ha encontrado el Bono.";
        }}
        ?>
    
        </table>
    
        <form>
            <input type="buttom" value="Atras" OnClick = "location.href='bonos.php'">
        </form>

    </body>

</html>