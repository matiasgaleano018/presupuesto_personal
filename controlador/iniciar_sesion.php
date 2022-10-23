<?php
    session_start();
    require_once 'conexion.php';
    class IniciarSesion{
        public function iniciar($id, $nombre, $usuario){

            $selec = new conexion;
            $us_foto = $selec->consultar("SELECT `foto` FROM `usuarios` WHERE `id` = $id");

            $_SESSION['usuario_id']     = $id;
            $_SESSION['usuario_nombre'] = $nombre;
            $_SESSION['usuario_nick']   = $usuario;
            $_SESSION['usuario_foto']   = !empty($us_foto[0][0]) ? $us_foto[0][0] : '';
            
            header("location:principal.php");
        }
    }
?>