<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Buscar Empleado</title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/php-login/assets/css/style.css">
    </head>

    <h1>Buscar Empleado</h1>

    <form action="buscarEmpleado.php" method="POST">
        <p>
            <label>Metodo de Busqueda: </label><select name="metodoBusqueda" this.options[this.selectedIndex].innerHTML required>
                                                    <option value='nombre'>Nombre</option>
                                                    <option value='apellido'>Apellido</option>
                                                    <option value='dni'>D.N.I</option>
                                                </select>
        </p>

        <p>
            <label>Buscar Empleado: </label><input type="text" id="keywords" name="keywords" size="30" maxlength="30">
        </p>

        <p>
            <input type="submit" name="search" value="Buscar">
        </p>    
    </form>

    <body>
        <?php

            include("funciones.php");

            if (isset($_POST['search'])) {
                if($_POST['metodoBusqueda'] == "nombre"){
                    $keywords = $_POST['keywords'];
                    $connection = mysqli_connect("localhost", "root", "", "php_login_database");
                    $sql = "select * from empleado where nombre LIKE '%$keywords%'";
                    $result = db_query($sql);
                
                }elseif($_POST['metodoBusqueda'] == "apellido"){
                    $keywords = $_POST['keywords'];
                    $connection = mysqli_connect("localhost", "root", "", "php_login_database");
                    $sql = "select * from empleado where apellido LIKE '%$keywords%'";
                    $result = db_query($sql);
                
                }elseif($_POST['metodoBusqueda'] == "dni"){
                    $keywords = $_POST['keywords'];
                    $connection = mysqli_connect("localhost", "root", "", "php_login_database");
                    $sql = "select * from empleado where dni LIKE '%$keywords%'";
                    $result = db_query($sql);
                }
 
            //Si ha resultados
            if (!empty($result)) {
            //Si no hay registros encontrados
    ?>
        <table>
		    <tr>
                <th width="30%">Nombre</th>
                <th width="30%">Apellido</th>
                <th width="30%">D.N.I</th>
                <th width="30%">Informacion Empleado</th>

		    </tr>
	
        <?php 
            while($row = mysqli_fetch_object($result)){
        ?>
            <tr>
                <td><?php echo $row->nombre;?></td>
                <td><?php echo $row->apellido;?></td>
			    <td><?php echo $row->dni;?></td>
			    <td><a href="verEmpleado.php?id=<?php echo $row->id;?>">Ver</a></td>
            </tr>
 
        <?php }
            }else{
            echo "No se ha encontrado al Empleado.";
        }
        }?>
    
        </table>
    
        <form>
            <input type="buttom" value="Atras" OnClick = "location.href='empleados.php'">
        </form>

    </body>

</html>