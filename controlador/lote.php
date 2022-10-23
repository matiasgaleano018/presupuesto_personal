<?php
    require_once 'conexion.php';
    $conn = new conexion;
    $redir = !empty($_POST['redir']) ? $_POST['redir'] : "principal.php";
    if($_POST['eliminar_lote'] != NULL){
        if(isset($_POST['ids'])){
            $contador_ok = 0;
            $contador_er = 0;
            foreach($_POST['ids'] as $id){
                $cant_mov = $conn->consultar("SELECT COUNT(*) FROM movimientos WHERE categoria_id = $id");
                if(empty($cant_mov) || $cant_mov[0][0] == 0){
                    $sql = "DELETE FROM categorias WHERE id = $id";
                    if($conn->ejecutar($sql) == 'ok'){
                        $contador_ok++;
                    }
                }else{
                    $contador_er++;
                }
                
            }
            header("location:../paginas/$redir");
        }
    }





    /*Cuentas.php */
    if($_POST['eliminar_lote_cuen'] != NULL){
        if(isset($_POST['ids'])){
            $contador_ok = 0;
            $contador_er = 0;
            foreach($_POST['ids'] as $id){
                $cant_mov = $conn->consultar("SELECT COUNT(*) FROM movimientos WHERE cuenta_id = $id");
                if(empty($cant_mov) || $cant_mov[0][0] == 0){
                    $sql = "DELETE FROM cuentas WHERE id = $id";
                    if($conn->ejecutar($sql) == 'ok'){
                        $contador_ok++;
                    }else{
                        $contador_er++;
                    }
                }else{
                    $contador_er++;
                } 
            }
            header("location:../paginas/$redir?su=$contador_ok&er=$contador_er");
        }
    }
?>