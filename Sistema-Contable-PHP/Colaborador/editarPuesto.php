<?php

    include("funciones.php");
    $id = $_GET['id'];
    visibilidadPuesto('puestoempleado','id',$id);
    header("editarPuesto.php"); 
    
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Editar Puesto</title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/php-login/assets/css/style.css">
    </head>

    <body>

        <h1>Puesto Cambiado Exitosamente</h1>
        <h2>Haga click en el boton de abajo para poder continuar</h2>
        
        <form>
            <input type="buttom" value="Atras" OnClick = "location.href='listaPuesto.php'">
        </form>
 
    </body>
    
</html>