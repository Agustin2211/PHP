<?php
    require 'database.php';
    session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Sueldos Anteriores</title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/php-login/assets/css/style.css">
    </head>

    <body>

        <h1>Pagos de Sueldos Anteriores</h1>
        
        <form>
            <input type="buttom" value="Ver Por Fecha" OnClick = "location.href='buscarPagoFecha.php'">
        </form>
    
        <form>
            <input type="buttom" value="Ver Por Empleado" onclick = "location.href='pagosanterioresporempleado.php'">
        </form>

        <form>
            <input type="buttom" value="Atras" onclick="location.href='/php-login/Administrador/pagodesueldos.php'">
        </form>

    </body>
    
</html>