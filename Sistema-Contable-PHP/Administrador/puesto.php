<?php
    require 'database.php';
    session_start();
?>

<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Puesto</title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/php-login/assets/css/style.css">
    </head>

    <body>

        <h1>Puestos</h1>

        <form>
            <input type="buttom" value="Registrar Puesto" onclick="location.href='registrarPuesto.php'">
        </form>

        <form>
            <input type="buttom" value="Lista de Puestos" onclick="location.href='listaPuesto.php'">
        </form>

        <form>
            <input type="buttom" value="Buscar Puesto" onclick="location.href='buscarPuesto.php'">
        </form>

        <form>
            <input type="buttom" value="Atras" onclick="location.href='pagoDeSueldos.php'">
        </form>
    
    </body>

</html>