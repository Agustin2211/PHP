<!DOCTYPE html>
	<head>
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/php-login/assets/css/style.css">
		<h2>Administración de Usuarios Registrados</h2>	
		<div class="well well-small">
		<div class="row-fluid">
		<title>Administrar Roles</title>
	</head>

	<body>
	
		<?php
			include("funciones.php");
		?>

		<table>
			<tr>
				<th width="30%">Email</th>
				<th width="30%">Rol</th>
				<th width="30%">Dar Privilegios</th>
				<th width="30%">Eliminar Usuario</th>
			</tr>
	
		<?php 
			$sql = "select * from users";
			$result = db_query($sql);
			while($row = mysqli_fetch_object($result)){
		?>
	
			<tr>
				<td><?php echo $row->email;?></td>
				<td><?php echo $row->rol_id;?></td>
				<td>
					<a href="editarUsuario.php?id=<?php echo $row->id;?>"><img src='/php-login/images/actualizar.gif' class='img-rounded'/></a>
        		</td>
				<td>
					<a href="borrarUsuario.php?id=<?php echo $row->id;?>"><img src='/php-login/images/eliminar.png' class='img-rounded'/></a>
        		</td>
			</tr>
		<?php } ?>

		</table>

		<form>
			<input type="buttom" value="Atras" OnClick = "location.href='admin.php'">
		</form>
	
	</body>
	
</html>
