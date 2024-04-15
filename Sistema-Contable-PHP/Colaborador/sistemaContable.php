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
        <title>Sistema Contable</title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/php-login/assets/css/style.css">
    </head>

    <body>

        <h1>Sistema Contable</h1>
        
        <form>
            <input type="buttom" value="Ingresar Asiento" OnClick = "location.href='nuevoAsiento.php'">
        </form>
    
        <form>
            <input type="buttom" value="Ver Libro Diario" onclick = "location.href='principalLibroDiario.php'">
        </form>
    
        <form>
            <input type="buttom" value="Ver Libro Mayor" onclick="location.href='libromayor.php'">
        </form>

        <form>
            <input type="buttom" value="Plan de Cuentas" onclick="location.href='plandecuenta.php'">
        </form>

        <form>
            <input type="buttom" value="Atras" onclick="location.href='/php-login/Colaborador/colaborador.php'">
        </form>

    </body>
    
</html>