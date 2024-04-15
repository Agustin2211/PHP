<?php

require 'database.php';

$message = '';

if (!empty($_POST['email']) && !empty($_POST['password']) && (!empty($_POST['confirm_password']))) {
  if ($_POST['password'] == $_POST['confirm_password']) {
    $sql = "INSERT INTO users (email, password, rol_id) VALUES (:email, :password, 2)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $stmt->bindParam(':password', $password);

    if ($stmt->execute()) {
      $message = 'Usuario creado correctamente';
    } else {
      $message = 'Perdon, hubo un error al crear su usuario';
    }
  } else {
    $message = 'Las contraseñas no coinciden';
  }
}

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Registrarse</title>
  <link href="https://fonts.googleapis.com/css2?family=Russo+One&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

  <?php if (!empty($message)) : ?>
    <p> <?= $message ?></p>
  <?php endif; ?>

  <h1>Registrarse</h1>

  <form action="signup.php" method="POST">
    <input name="email" type="text" placeholder="Usuario">
    <input name="password" type="password" placeholder="Ingrese la Contraseña">
    <input name="confirm_password" type="password" placeholder="Confirme la Contraseña">
    <input type="submit" value="Registrarse">
  </form>

  <form>

  </form>

  <form>
    <input type="buttom" value="Atrás" OnClick="location.href='index.php'">
  </form>

</body>

</html>