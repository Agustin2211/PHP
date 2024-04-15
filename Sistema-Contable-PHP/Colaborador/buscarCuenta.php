<html>
    <head>  

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Buscar Cuenta</title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/php-login/assets/css/style.css">
    
        <form action="buscarCuenta.php" method="POST">
            Buscar Cuenta: <input type="text" id="keywords" name="keywords" size="30" maxlength="30">
            <input type="submit" name="search" id="search" value="Buscar">
        </form>

    </head>

    <body>
 
        <?php

            include("funciones.php");

            if (isset($_POST['search'])) {

                $keywords = $_POST['keywords'];
                $connection = mysqli_connect("localhost", "root", "", "php_login_database");
                $sql = "select * from cuentas where cuenta = ";
                $result = db_query($sql);
 
                //Si ha resultados
                if (!empty($result)) {
                //Si no hay registros encontrados
                ?>

                    <table>
		                <tr>
			                <th width="30%">Cuenta</th>
			                <th width="30%">Codigo</th>
			                <th width="30%">Tipo</th>
                            <th width="30%">Recibe Saldo</th>
                            <th width="30%">Cambiar Visibilidad</th>
		                </tr>
	
                        <?php while($row = mysqli_fetch_object($result)){ ?>

                        <tr>
			                <td><?php echo $row->cuenta;?></td>
                            <td><?php echo $row->codigo;?></td>
                            <td><?php echo $row->tipo;?></td>
                            <td><?php echo $row->recibeSaldo;?></td>
			                <td>
                                <a href="editarCuenta.php?id=<?php echo $row->id;?>"><img src='/php-login/images/actualizar.gif' class='img-rounded'/></a>
        	                </td>
                        </tr>
 
                    <?php }
                }else{
                        echo '<h2>No se ha encontrado la Cuenta.</h2>';
                    }
            }?>
    
                    </table>
    
        <form>
            <input type="buttom" value="Atras" OnClick = "location.href='plandecuenta.php'">
        </form>

    </body>
    
</html>
