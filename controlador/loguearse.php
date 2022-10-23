<?php
    require_once 'access.php';
    class Loguearse{
        public function acceder($usuario, $contrasenha){
            $selec = new Access;

            $res = $selec->seleccionar("SELECT contrasenha, usuario FROM usuarios WHERE usuario = '$usuario'");

            $contrasenha_enc = md5($contrasenha);
            if(!empty($res[0][1])){
                if($res[0][0] == $contrasenha_enc){
                    return "ok";
                }else{
                    return "pass_inco";
                }
            }else{
                return "us_inex";
            }
        }
    }
?>