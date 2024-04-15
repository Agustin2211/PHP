<?php

	require 'database.php';

	//COMO PIJA PREGUNTO QUE QUIERO LOS DATOS DEL USUARIO QUE ESTA ACTUALMENTE EN EL PROGRAMA? UwU
	$sql=("SELECT * FROM users WHERE id = :id");
    $mysqli=new MySQLI('localhost','root','','php_login_database');
	$query=mysqli_query($mysqli,$sql);
	$arreglo=mysqli_fetch_array($query);

	$rol = $arreglo[1]; 
	echo $rol;
	
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    	<meta http-equiv="X-UA-Compatible" content="ie=edge">
    	<title>Ingresar Asiento</title>
    	<link href="https://fonts.googleapis.com/css2?family=Russo+One&display=swap" rel="stylesheet">
    	<link rel="stylesheet" href="assets/css/style.css">
	<head>
	
	<body>

		<form action="asientoContable.php" method="POST">
			<input type="submit" value ="Atras">
		</form>

	</body>
</html>