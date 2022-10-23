<?php
    require_once 'conexion.php';
    class Access{
        public function seleccionar($sql){
            $conn = new conexion;
            return $conn->consultar($sql);
        }
        public function insertar($sql){
            $conn = new conexion;
            return $conn->ejecutar($sql);
        }
        public function actualizar($sql){
            $conn = new conexion;
            return $conn->ejecutar($sql);
        }
        public function eliminar($sql){
            $conn = new conexion;
            return $conn->ejecutar($sql);
        }
    }
?>