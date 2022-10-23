<?php
    session_start();
    require '../../controlador/access.php';
    $usuario_id     = !empty($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 0;

    $selec = new Access;

    $param = $selec->seleccionar("SELECT `simbolo_monetario`, `decimales` FROM `parametros` WHERE `usuario_id` = $usuario_id");
    $simbolo_mone = !empty($param[0][0]) ? $param[0][0] : "$";
    $decimales_mone = (!empty($param[0][1]) || $param[0][1] == 0) ? $param[0][1] : 2;

    $ingre_json = array();

    class NodoArbolDHTML{
        var $total;
        var $color;
        function __construct($total,$color){
             $this->total = $total;
             $this->color = $color;
        }
        function anadirHijo($nodoHijo, $total){        //añadir un hijo
             if (!isset($this->hijos)){
                 $this->hijos = array();
             }
             $this->hijos[$total] = $nodoHijo;
        }
    }
    $ingresos = $selec->seleccionar("SELECT SUM(monto), categoria_id FROM `movimientos` WHERE `usuario_id` = $usuario_id AND `tipo` = 'ingreso' AND DATE_FORMAT(fecha, 'Y/m') = DATE_FORMAT(CURDATE(), 'Y/m') GROUP BY categoria_id");
    
    foreach($ingresos as $ingre){
        $categoria = $selec->seleccionar("SELECT nombre, color FROM categorias WHERE id = $ingre[1]");
        $cate  = !empty($categoria[0][0]) ? $categoria[0][0] : "Otro";
        $color = !empty($categoria[0][1]) ? $categoria[0][1] : "#C7C6C6";
        $valores = array($ingre[0], $color);
        $ingre_json[$cate] = new NodoArbolDHTML(number_format($ingre[0], $decimales_mone, ',', ''), $color);
    }

    echo json_encode($ingre_json);
?>