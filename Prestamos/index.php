<?php 

    require "./config/APP.php";
    require "./controller/vistasControlador.php";

    $plantilla = new vistasControlador();
    $plantilla -> obtenerPlantillaControlador();

?>