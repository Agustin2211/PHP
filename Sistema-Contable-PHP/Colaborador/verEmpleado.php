<?php

    include('funciones.php');

    session_start();

    clearstatcache();

    require('database.php');

    $message = '';

    $id = $_GET['id'];

    $connection = mysqli_connect("localhost", "root", "", "php_login_database");
    $sql = ("SELECT * FROM empleado WHERE id like '$id'");
    $result = db_query($sql);
    $row = mysqli_fetch_object($result);
    $sql2 = "SELECT * FROM puestoempleado WHERE id = '$row->puesto'";
    $result2 = db_query($sql2);
    $ver2=mysqli_fetch_object($result2);

?>

<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Empleado</title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/php-login/assets/css/style.css">
    </head>

    <body>
        <form action="verEmpleado.php" method="POST">
                <p>
                    <label>Nombre: </label><input name="nombre" type="text" readonly value="<?php echo $row->nombre; ?>">
                <p>
                    <label>Apellido: </label><input name="apellido" type="text" readonly value="<?php echo $row->apellido; ?>">
                </p>

                <p>
                    <label>Puesto de Trabajo: </label><input name="puesto" type="text"readonly value="<?php echo $ver2->nombre; ?>">
                </p>

                <p>
                    <label>Fecha de Ingreso: </label><input step="any" type="date" readonly name="fechadeingreso" value="<?php echo $row->fechadeingreso; ?>">
                </p>

                <p>
                    <label>D.N.I: </label><input step="any" type="number" readonly value="<?php echo $row->dni; ?>">
                </p>

                <p>
                    <label>C.U.I.L: </label><input step="any" type="number" readonly step="0.01" name="cuil" value="<?php echo $row->cuil; ?>">
                </p>

                <p>
                    <label>Direccion: </label><input name="direccion" type="text" readonly value="<?php echo $row->direccion; ?>">
                </p>

                <p>
                    <label>Telefono: </label><input step="any" type="number" readonly step="0.01" name="telefono" min='0' value="<?php echo $row->telefono; ?>">
                </p>

                <p>
                    <label>Fecha de Nacimiento: </label><input step="any" type="date" readonly name="fechadenacimiento" value="<?php echo $row->fechadenacimiento; ?>">
                </p>

                <p>
                    <label>Estado Civil: </label><input step="any" type="text" name="estadocivil" readonly value="<?php echo $row->estadocivil; ?>">
                </p>

                <p>
                    <label>Cantidad de Hijos: </label><input step="any" type="number" name="cantidadhijos" readonly value="<?php echo $row->cantidadhijos; ?>">
                </p>
        </form>

        <form>
            <input type="buttom" value="Atras" onclick="location.href='empleados.php'">
        </form>

    </body>
</html>