<?php

    /*Modelo para obtener las vistas*/

    class vistasModelo{
        
        protected static function obtenerVistasModelo($vistas){
            $listaBlanca = [];

            /*comprovacion de la lista*/
            if(in_array($vistas, $listaBlanca)){
                if(is_file("./vistas/contents/".$vistas."View.php")){
                    $contenido = "./vistas/contents/".$vistas."View.php";
                }
                else{
                    $contenido = "404";
                }

            }elseif($vistas == "login" || $vistas == "index"){
                $contenido="login";
            }else{
                $contenido = "404";
            }

            return $contenido;
        
        }

    }

?>