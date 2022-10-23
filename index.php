<?php

    if($_SESSION){
        header("location:paginas/principal.php");
    }else{
        header("location:paginas/login.php");
    }

?>