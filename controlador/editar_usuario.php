<?php
    require_once 'conexion.php';
    class EditarUsuario{
        public $nombre;
        public $apellido;
        public $usuario;

        public function __construct($nombre, $apellido, $usuario){
            $this->nombre = $nombre;
            $this->apellido = $apellido;
            $this->usuario = $usuario;
        }

        public function actualizar($usuario_id){
            $conn = new conexion;
            $nom = $this->nombre;
            $ape = $this->apellido;
            $usu = $this->usuario;

            
            $resul = $conn->ejecutar("UPDATE `usuarios` SET `nombre`='$nom',`apellido`='$ape',`usuario`='$usu',`modificadoel`= NOW() WHERE id = $usuario_id");

            if($resul == 'ok'){
                $_SESSION['usuario_nombre'] = $nom." ".$ape;
                return $resul;
            }
        }
    }

?>