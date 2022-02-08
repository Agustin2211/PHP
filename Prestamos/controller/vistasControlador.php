<?php
    require "./models/vistasModelo.php";

    class vistasControlador extends vistasModelo{
    
        /* CONTROLADOR PARA OBTENER LAS PLANTILLAS */
        public function obtenerPlantillaControlador(){
            return require "./views/plantilla.php";
        }

        /*CONTROLADOR PARA OBTENER LAS VISTAS */
        public function obtenerVistasControlador(){
            if(isset($_GET['views'])){
                $ruta = explode("/", $_GET['views']);
                $respuesta = vistasModelo::obtenerVistasModelo($ruta[0]);
            }else{
                $respuesta = "";
            }return $respuesta;
        }
    }
?>