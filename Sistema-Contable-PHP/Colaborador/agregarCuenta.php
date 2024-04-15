<?php

 require('database.php');

$message = '';

if (!empty($_POST)){
    $stmt = $conn->prepare("INSERT INTO cuentas (cuenta, codigo, tipo, recibeSaldo, saldoActual) VALUES (:cuenta, :codigo, :tipo, :recibeSaldo, 0)");
    $stmt->bindParam(':cuenta', $_POST['cuenta']);
    $stmt->bindParam(':codigo', $_POST['codigo']);
    $stmt->bindParam(':tipo', $_POST['tipo']);
    $stmt->bindParam(':recibeSaldo', $_POST['recibeSaldo']);
    if ($stmt->execute()) {
        $message = 'Cuenta agregada correctamente';
      }
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Agregar Cuenta</title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/php-login/assets/css/style.css">
        <h1>Agregar Cuenta</h1>
    </head>

    <body>
    
    <?php if (!empty($message)) : ?>
        <p> <?= $message ?></p>
    <?php endif; ?>

        <div class="container">
            
            <form action="agregarCuenta.php" class="form-inline" role="form" method="POST">
            
                <p>
                    <label>Nombre de la Cuenta: </label><input name="cuenta" type="text" id="cuenta" required pattern="[a-z A-Z]{1,}" title="El nombre de la cuenta solamente debe contener letras.">
                </p>

                <p>
                    <label>Codigo: </label><input name="codigo" type="number" min='0' required>
                </p>

                <p>
                    <label>Tipo de Cuenta: </label><select name="tipo" this.options[this.selectedIndex].innerHTML required>
                                                        <option value='Ac'>Activo</option>
                                                        <option value='Pa'>Pasivo</option>
                                                        <option value='Pm'>Patrimonio Neto</option>
                                                        <option value='R+'>Resultado Positivo</option>
                                                        <option value='R-'>Resultado Negativo</option>
                                                    </select>
                </p>

                <p>
                    <label>Recibe Saldo: </label><input name="recibeSaldo" type="number" min='0' max='1' required pattern="(0|1)">
                </p>

                <input type="submit" value="Agregar Cuenta">
        
            </form>

        </div>

    </body>

    <footer>
        <form>
            <input type="buttom" value="Atras" onclick="location.href='plandecuenta.php'">
        </form>
    </footer>

</html>