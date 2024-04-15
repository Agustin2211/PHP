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
        <title>Libro Diario</title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/php-login/assets/css/style.css">
    </head>

    <body>

        <h1>Libro Diario</h1>
        
        <form>
            <input type="buttom" value="Buscar Asiento" OnClick = "location.href='buscarAsientoFecha.php'">
        </form>
    
        <form>
            <input type="buttom" value="Ver Libro Diario" onclick = "location.href='Librodiario.php'">
        </form>

        <form>
            <input type="buttom" value="Atras" onclick="location.href='/php-login/Colaborador/sistemacontable.php'">
        </form>

    </body>
    
</html>