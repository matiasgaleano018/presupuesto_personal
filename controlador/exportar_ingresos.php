<?php
    require_once 'access.php';
    $usuario_id     = !empty($_POST['usuario']) ? $_POST['usuario'] : 0;
    if(isset($_POST['select'])){
        header("Content-Type: application/xls");    
	
        header("Content-Disposition: attachment; filename=ingresos_".date('Y:m:d:h:m:s').".xls");
        header("Pragma: no-cache"); 
        header("Expires: 0");

        $salida = "";
        $salida .= "
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Monto</th>
                        <th>Cuenta</th>
                        <th>Categoria</th>
                        <th>Descripcion</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    
            ";
        $sql = $_POST['select'];
        $selec = new Access;
        $ingresos = $selec->seleccionar($sql);

        $param = $selec->seleccionar("SELECT `simbolo_monetario`, `decimales` FROM `parametros` WHERE `usuario_id` = $usuario_id");
        $simbolo_mone = !empty($param[0][0]) ? $param[0][0] : "$";
        $decimales_mone = (!empty($param[0][1]) || $param[0][1] == 0) ? $param[0][1] : 2;

        foreach($ingresos as $ingre){
            $cuenta = $selec->seleccionar("SELECT `nombre` FROM `cuentas` WHERE `id` = $ingre[2]");
            $categoria = $selec->seleccionar("SELECT `nombre`, `color` FROM `categorias` WHERE `id` = $ingre[3]");
            $salida .= '<tr>
                <td>'.$ingre[0].'</td>
                <td>'."$simbolo_mone ".number_format($ingre[1], $decimales_mone, ',', '.').'</td>
                <td>'.$cuenta[0][0].'</td>
                <td>'.$categoria[0][0].'</td>
                <td>'.$ingre[4].'</td>
                <td>'.$ingre[6].'</td></tr>';
        }

        $salida .= '</tbody></table>';
        echo $salida;
    }
?>