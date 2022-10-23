<?php
    require_once 'access.php';
    $usuario_id     = !empty($_POST['usuario']) ? $_POST['usuario'] : 0;
    if(isset($_POST['select'])){
        header("Content-Type: application/xls");    
	
        header("Content-Disposition: attachment; filename=egresos_".date('Y:m:d:h:m:s').".xls");
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
        $egresos = $selec->seleccionar($sql);

        $param = $selec->seleccionar("SELECT `simbolo_monetario`, `decimales` FROM `parametros` WHERE `usuario_id` = $usuario_id");
        $simbolo_mone = !empty($param[0][0]) ? $param[0][0] : "$";
        $decimales_mone = (!empty($param[0][1]) || $param[0][1] == 0) ? $param[0][1] : 2;

        foreach($egresos as $egre){
            $cuenta = $selec->seleccionar("SELECT `nombre` FROM `cuentas` WHERE `id` = $egre[2]");
            $categoria = $selec->seleccionar("SELECT `nombre`, `color` FROM `categorias` WHERE `id` = $egre[3]");
            $salida .= '<tr>
                <td>'.$egre[0].'</td>
                <td>'."$simbolo_mone ".number_format($egre[1], $decimales_mone, ',', '.').'</td>
                <td>'.$cuenta[0][0].'</td>
                <td>'.$categoria[0][0].'</td>
                <td>'.$egre[4].'</td>
                <td>'.$egre[6].'</td></tr>';
        }

        $salida .= '</tbody></table>';
        echo $salida;
    }
?>