<?php

    include('funciones.php');

    session_start();

    clearstatcache();

    require('database.php');

    $message = '';

    $id = $_GET['id'];

    $connection = mysqli_connect("localhost", "root", "", "php_login_database");

        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $sueldoMinimo = $_POST['sueldoMinimo'];
        $sql1 = ("UPDATE puestoempleado
                SET 'nombre' = '$nombre', 'descripcion' = '$descripcion', 'sueldoMinimo' = '$sueldoMinimo'
                WHERE id = '$id'");
        $result1 = db_query($sql1);

?>