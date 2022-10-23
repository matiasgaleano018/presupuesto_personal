<?php
    session_start();
    require '../../controlador/access.php';
    $usuario_id     = !empty($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 0;

    $selec = new Access;

    $param = $selec->seleccionar("SELECT `simbolo_monetario`, `decimales` FROM `parametros` WHERE `usuario_id` = $usuario_id");
    $simbolo_mone = !empty($param[0][0]) ? $param[0][0] : "$";
    $decimales_mone = (!empty($param[0][1]) || $param[0][1] == 0) ? $param[0][1] : 2;

    $egre_json = array();

    $egresos = $selec->seleccionar("SELECT SUM(monto), DATE_FORMAT(fecha, '%c') FROM `movimientos` WHERE `usuario_id` = $usuario_id AND `tipo` = 'egreso' AND DATE_FORMAT(fecha, 'Y') = DATE_FORMAT(CURDATE(), 'Y') GROUP BY DATE_FORMAT(fecha, '%m')");
    
    $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
    foreach($egresos as $egre){
        $mes = $meses[$egre[1]-1];
        $egre_json[$mes] = $egre[0];
    }

    echo json_encode($egre_json);
?>