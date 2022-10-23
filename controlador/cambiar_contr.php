<?php
    require_once 'conexion.php';

    class CambiarContr{
        private $contr_anterior;
        private $contr_nueva;
        public  $redir;

        public function __construct($contr_anterior, $contr_nueva, $redir){
            $this->contr_anterior = $contr_anterior;
            $this->contr_nueva    = $contr_nueva;
            $this->redir          = $redir;
        }

        public function cambiar($usuario_id){
            $pass_ant = md5($this->contr_anterior);
            $pass_nue = md5($this->contr_nueva);
            $redir_pag= $this->redir;

            $conn = new conexion;
            $pass = $conn->consultar("SELECT contrasenha FROM usuarios WHERE id = $usuario_id");

            if($pass_ant == $pass[0][0]){
                $res = $conn->ejecutar("UPDATE usuarios SET contrasenha = '$pass_nue', modificadoel = NOW()");
                return $res;
            }else{
                return 'cNc';
            }
        }
    }
?>