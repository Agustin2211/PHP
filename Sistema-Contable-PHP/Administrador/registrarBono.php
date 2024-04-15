<?php

    session_start();

require('database.php');

    include("funciones.php"); 

    $message = '';

    date_default_timezone_set('America/Argentina/Buenos_Aires');

    if (!empty($_POST)){
        $stmt = $conn->prepare("INSERT INTO bono (nombre, descripcion, importe) VALUES (:nombre, :descripcion, :importe)");
        $stmt->bindParam(':nombre', $_POST['nombre']);
        $stmt->bindParam(':descripcion', $_POST['descripcion']);
        $stmt->bindParam(':importe', $_POST['importe']);
        if ($stmt->execute()) {
            $message = 'Bono agregado correctamente';
        }else{
            $message = 'Ups, algo a fallado';
        }
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Cargar Bono</title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/php-login/assets/css/style.css">
        <h1>Cargar Bono</h1>
    </head>


<?php if (!empty($message)) : ?>
    <p> <?= $message ?></p>
<?php endif; ?>

    <body>
        <div class="container">
            <form action="registrarBono.php" class="form-inline" role="form" method="POST">

                <p>
                    <label>Nombre: </label><input name="nombre" type="text" required pattern="[a-z A-Z]{1,}" title="El nombre del bono solamente debe contener letras.">
                </p>

                <p>
                    <label>Descripcion: </label><input name="descripcion" type="text" required pattern="[a-z A-Z]{1,}" title="La descripcion del bono solamente debe contener letras.">
                </p>

                <p>
                    <label>Importe: </label><input step="any" type="number" step="0.01" name="importe" min='0' required>
                </p>

                <p>
                    <input type="Submit" value="Cargar Bono">
                </p>

            </form>

            <form>
          
                <p>
                    <input type="buttom" value="Atras" onclick="location.href='bonos.php'">
                </p>
            </form>

        </div>
    
    </body>

</html>