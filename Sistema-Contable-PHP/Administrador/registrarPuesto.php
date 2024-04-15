<?php

    require('database.php');

    $message = '';

    if (!empty($_POST)){
        $stmt = $conn->prepare("INSERT INTO puestoempleado (nombre, descripcion, sueldoMinimo) VALUES (:nombre, :descripcion, :sueldoMinimo)");
        $stmt->bindParam(':nombre', $_POST['nombre']);
        $stmt->bindParam(':descripcion', $_POST['descripcion']);
        $stmt->bindParam(':sueldoMinimo', $_POST['sueldoMinimo']);
        if ($stmt->execute()) {
            $message = 'Puesto agregado correctamente';
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
        <title>Cargar Puesto</title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/php-login/assets/css/style.css">
        <h1>Cargar Puesto</h1>
    </head>


<?php if (!empty($message)) : ?>
    <p> <?= $message ?></p>
<?php endif; ?>

    <body>
        <div class="container">
            <form action="registrarPuesto.php" class="form-inline" role="form" method="POST">

                <p>
                    <label>Nombre: </label><input name="nombre" type="text" required pattern="[a-z A-Z]{1,}" title="El nombre del puesto solamente debe contener letras.">
                </p>

                <p>
                    <label>Descripcion: </label><input name="descripcion" type="text" required pattern="[a-z A-Z]{1,}" title="La descripcion del puesto solamente debe contener letras.">
                </p>

                <p>
                    <label>Sueldo Minimo: </label><input step="any" type="number" step="0.01" name="sueldoMinimo" min='0' required>
                </p>

                <p>
                    <input type="Submit" value="Cargar Puesto">
                </p>

            </form>

            <form>
          
                <p>
                    <input type="buttom" value="Atras" onclick="location.href='puesto.php'">
                </p>
            </form>

        </div>
    
    </body>

</html>