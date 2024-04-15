<?php

    include('funciones.php');

    session_start();

    clearstatcache();

    require('database.php');

    $message = '';

    $id = $_GET['id'];

    $connection = mysqli_connect("localhost", "root", "", "php_login_database");
    $sql = ("SELECT * FROM puestoempleado WHERE id like '$id'");
    $result = db_query($sql);
    $row = mysqli_fetch_object($result);

?>

<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Puesto de Trabajo</title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/php-login/assets/css/style.css">
    </head>

    <body>
        <form action="verPuesto.php" method="POST">
                <p>
                    <label>Codigo del Puesto: </label><input name="id" type="text" readonly value="<?php echo $row->id; ?>">
                <p>

                <p>
                    <label>Nombre del Puesto: </label><input name="nombre" type="text" readonly value="<?php echo $row->nombre; ?>">

                <p>
                    <label>Descripcion: </label><input name="apellido" type="text" readonly value="<?php echo $row->descripcion; ?>">
                </p>

                <p>
                    <label>Sueldo Minimo: </label><input step="any" type="number" readonly value="<?php echo $row->sueldoMinimo; ?>">
                </p>

                <p>
                    <label>Visibilidad: </label><input name="Visibilidad" type="text" readonly value="<?php echo $row->visibilidad; ?>">
                </p>

                <a href="editarPuesto.php?id=<?php echo $row->id;?>">Editar Puesto</a>
        </form>

        <form>
            <input type="buttom" value="Atras" onclick="location.href='puesto.php'">
        </form>

    </body>
</html>