<?php
    require_once 'access.php';
/*
    Hecho el: sa.01.oct.2022
    Objetivo: Concatenarle al nombre de la imagen subida la fecha y hora de modo a evitar archivos repetidos.
*/
    class CambiarPerfil{
        private $img = array();
        public $usuario_id;
        public function __construct($img, $usuario_id){
            $this->img = $img;
            $this->usuario_id = $usuario_id;
        }
        public function cambiar(){
            $ahora = date("YmdHis"); //fecha y hora

            $imagen      = $this->img['name']; 
            $tmp_imagen  = $this->img['tmp_name']; 
            $imagen_arr  = pathinfo($imagen);
            $imagen_ext  = $imagen_arr['extension'];

            //Formato del archivo guardado => example_202210011245.jpg
            $imagen_nombre = $imagen_arr['filename']."_".$ahora.".".$imagen_ext;
            
            //Formato de nombre de carpeta => user + id de usuario. Ej: user124
            $carpeta_nombre = "../img/usuarios/user".$this->usuario_id."/";
            if(!is_dir($carpeta_nombre)){
                mkdir($carpeta_nombre, 0777, true);
            }
            move_uploaded_file($tmp_imagen, $carpeta_nombre.$imagen_nombre);

            /* ----------- */
            $sql = "UPDATE `usuarios` SET `foto`='$imagen_nombre' WHERE id = $this->usuario_id";
            $acc = new Access;
            if($acc->insertar($sql) == 'ok'){
                $_SESSION['usuario_foto'] = $imagen_nombre;
                header("location:../paginas/perfil.php");
                exit();
            }
        }
    }

?>