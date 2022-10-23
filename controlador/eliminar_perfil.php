<?php
    require "access.php";
    if($_GET['id']){
        $conn = new Access;

        $conn->insertar("UPDATE usuarios SET foto = '', modificadoel = NOW() WHERE id = ".$_GET['id']);
        header("location:../paginas/perfil.php");
        exit();
    }
    
?>