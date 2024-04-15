<?php
    include('funciones.php');

    session_start();

    clearstatcache();

    require('database.php');

    $message = '';
?>

<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Buscar Asiento</title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/php-login/assets/css/style.css">
        <h1>Asientos Cargados</h1>
    </head>

    <table class="table table-hover table-condensed table-bordered" style="text-align: center;">
        <tr>
		    <td>Cuenta</td>
            <td>Debe</td>
            <td>Haber</td>
        </tr>
                
        <?php
            $id = $_GET['id'];
            $sql = "SELECT *
                    FROM cuentaasiento 
                    WHERE idAsiento = '$id'";
            $result= db_query($sql);
            while($ver=mysqli_fetch_object($result)):
            $sql2 = "SELECT *
                    FROM cuentas
                    WHERE codigo = '$ver->idCuenta'";
            $result2 = db_query($sql2);
            $ver2=mysqli_fetch_object($result2)
        ?>

	    <tr>
		    <td><?php echo $ver2->cuenta; ?></td>
            <td><?php echo $ver->debe; ?></td>
            <td><?php echo $ver->haber; ?></td>
		</tr>
        
        <?php endwhile; ?>
        
    </table>

    <form>
        <input type="buttom" value="Atras" OnClick = "location.href='librodiario.php'">
    </form>

</html>