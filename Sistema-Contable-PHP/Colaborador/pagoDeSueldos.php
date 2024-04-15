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
        <title>Pago de Sueldos</title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/php-login/assets/css/style.css">
    </head>

    <body>

        <h1>Pago De Sueldos</h1>

        <form>
            <input type="buttom" value="Pagar Sueldo" onclick="location.href='pagarSueldoEmpleado.php'">
        </form>

        <form>
            <input type="buttom" value="Bonos de Sueldos" onclick="location.href='bonos.php'">
        </form>

        <form>
            <input type="buttom" value="Pagos Anteriores" onclick="location.href='pagosAnteriores.php'">
        </form>

        <form>
            <input type="buttom" value="Empleados" onclick="location.href='empleados.php'">
        </form>

        <form>
            <input type="buttom" value="Puestos" onclick="location.href='puesto.php'">
        </form>

        <form>
            <input type ="buttom" value="Atras" OnClick= "location.href='/php-login/Colaborador/colaborador.php'">
        </form>

    </body>
    
</html>