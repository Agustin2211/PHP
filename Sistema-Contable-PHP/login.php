<?php

  session_start();

  if (isset($_SESSION['user_id'])) {
    header('Location: /php-login');
  }
  
  require 'database.php';

  if (!empty($_POST['email']) && !empty($_POST['password'])) {
    $records = $conn->prepare('SELECT id, email, password, rol_id FROM users WHERE email = :email');
    $records->bindParam(':email', $_POST['email']);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);
    $rol = $results['rol_id'];

    $message = '';

    if (count($results) > 0 && password_verify($_POST['password'], $results['password'])) { 
      $_SESSION['user_id'] = $results['id'];
      if($rol == '1'){
          header('location: /php-login/Administrador/admin.php');
      }else{
        header('location: /php-login/Colaborador/colaborador.php');  
      }

    } else {
      $message = 'Credenciales Invalidas, reviselas y vuelva a intentarlo';
    }
  }

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Ingresar</title>
    <link href="https://fonts.googleapis.com/css2?family=Russo+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
  </head>
  <body>

    <?php if(!empty($message)): ?>
      <p> <?= $message ?></p>
    <?php endif; ?>

    <h1>Ingresar</h1>

    <form action="login.php" method="POST">
      <input name="email" type="text" placeholder="Ingrese su Usuario" required>
      <input name="password" type="password" placeholder="Ingrese su Contraseña" required>
      <input type="submit" value="Ingresar">
    </form>

    <form>
      <input type="buttom" value="Atrás" OnClick = "location.href='index.php'">
    </form>
    
  </body>

</html>