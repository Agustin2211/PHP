  
<?php
  
  session_start();
  
  require 'database.php';

  if (isset($_SESSION['user_id'])) {
    $records = $conn->prepare('SELECT id, email, password FROM users WHERE id = :id');
    $records->bindParam(':id', $_SESSION['user_id']);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);

    $user = null;

    if (count($results) > 0) {
      $user = $results;
    }
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Sistema Contable</title>
    <link href="https://fonts.googleapis.com/css2?family=Russo+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
  </head>
  
  <body>

    <?php if(!empty($user)): ?>
      <br> Bienvenido <?= $user['email']; ?>
      <br>Has ingresado correctamente
      <a href="logout.php">
        Logout
      </a>
    <?php else: ?>
      <h1>¡Bienvenido!</h1>

      <form>
        <input type="buttom" value="Ingresar" OnClick = "location.href='login.php'">
      </form>


      <form>
        <input type="buttom" value="Registrarse" OnClick = "location.href='signup.php'">
      </form>

    <?php endif; ?>
  </body>

</html>