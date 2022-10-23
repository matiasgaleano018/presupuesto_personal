<?php
    require_once 'conexion.php';

    class GridEliminar{

        public function eliminar($tabla, $id, $pagina){
            $sql = "DELETE FROM `$tabla` WHERE id = $id";

            $conn = new conexion;

            if($conn->ejecutar($sql)){
                header("location:$pagina");
            }
        }
    }

?>