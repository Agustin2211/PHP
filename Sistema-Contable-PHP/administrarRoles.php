<!DOCTYPE html>

        <link href="https://fonts.googleapis.com/css2?family=Russo+One&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="assets/css/style.css">
        <h2> Administración de Usuarios Registrados</h2>	
		<div class="well well-small">
		<hr class="soft"/>
		<h4>Tabla de Usuarios</h4>
		<div class="row-fluid">

<?php

	$message = ' ';

    require("database.php");
	$sql=("SELECT * FROM users");
    $mysqli=new MySQLI('localhost','root','','php_login_database');

	$query=mysqli_query($mysqli,$sql);

	echo "<center><table border='1'; class='table table-hover';color = #white>";
		echo "<tr class='warning'>";
			echo "<td>Id</td>";
			echo "<td>Usuario</td>";
			echo "<td>Rol</td>";
			echo "<td>Editar</td>";
			echo "<td>Borrar</td>";
		echo "</tr>";

?>
			  
<?php 
				 while($arreglo=mysqli_fetch_array($query)){
				  	echo "<tr class='success'>";
				    	echo "<td>$arreglo[0]</td>";
				    	echo "<td>$arreglo[1]</td>";
				    	echo "<td>$arreglo[3]</td>";
				    	
				    	echo "<td><a href='actualizar.php?id=$arreglo[0]'><img src='images/actualizar.gif' class='img-rounded'></td>";
						echo "<td><a href='administrarRoles.php?id=$arreglo[3]&idborrar=2'><img src='images/eliminar.png' class='img-rounded'/></a></td>";
						

						
					echo "</tr>";
				}

				echo "</table>";
					extract($_GET);
					if(@$idborrar==2){
		
						$sqlborrar="DELETE FROM 'users' WHERE 'users'.'rol_id'=$id";
						$resborrar=mysqli_query($mysqli,$sqlborrar);
						echo '<script>alert("Usuario Eliminado")</script> ';

					}else{
						echo '<script>alert("No se Pudo Eliminar al Usuario")</script> ';
					}

			?>

<body>
	
	<form>
		<input type="buttom" value="Atras" OnClick = "location.href='admin.php'">
	</form>
	
</body>


</html>