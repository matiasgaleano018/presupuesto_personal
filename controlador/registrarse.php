<?php
    require 'conexion.php';
    class Controlador{
        public function ctrl_ejecutar($sql){
            $conn = new Conexion();
            return $conn->ejecutar($sql);
        }

        public function ctrl_seleccionar($sql){
            $conn = new Conexion();
            return $conn->consultar($sql);
        }
    }
?>