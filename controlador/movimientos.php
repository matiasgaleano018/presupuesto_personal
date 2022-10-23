<?php
    require_once 'conexion.php';
    class Movimientos{
        public function rea_movimiento($tipo, $cuenta_id, $monto){
            $conn = new conexion;
            if($tipo == 'ingreso'){
                $saldo_cuenta = $conn->consultar("SELECT saldo FROM cuentas WHERE id = $cuenta_id");
                $saldo_anterior = !empty($saldo_cuenta[0][0]) ? $saldo_cuenta[0][0] : 0;
                $saldo_nuevo = $saldo_anterior + $monto;

                if($conn->ejecutar("UPDATE cuentas SET saldo = $saldo_nuevo WHERE id = $cuenta_id") == 'ok'){
                    return 'ok';
                }else{
                    return 'not';
                }
            }else if($tipo == 'egreso'){
                $saldo_cuenta = $conn->consultar("SELECT saldo FROM cuentas WHERE id = $cuenta_id");
                $saldo_anterior = !empty($saldo_cuenta[0][0]) ? $saldo_cuenta[0][0] : 0;
                $saldo_nuevo = $saldo_anterior - $monto;

                if($conn->ejecutar("UPDATE cuentas SET saldo = $saldo_nuevo WHERE id = $cuenta_id") == 'ok'){
                    return 'ok';
                }else{
                    return 'not';
                }
            }
        }

        public function eli_movimiento($tipo, $cuenta_id, $monto){
            $conn = new conexion;
            if($tipo == 'ingreso'){
                $saldo_cuent = $conn->consultar("SELECT saldo FROM cuentas WHERE id = $cuenta_id");
                $saldo = !empty($saldo_cuent[0][0]) ? $saldo_cuent[0][0] : 0;

                $saldo_nuevo = $saldo - $monto;

                if($conn->ejecutar("UPDATE cuentas SET saldo = $saldo_nuevo WHERE id = $cuenta_id") == 'ok'){
                    return 'ok';
                }else{
                    return 'not';
                }
            }else if($tipo == 'egreso'){
                $saldo_cuent = $conn->consultar("SELECT saldo FROM cuentas WHERE id = $cuenta_id");
                $saldo = !empty($saldo_cuent[0][0]) ? $saldo_cuent[0][0] : 0;

                $saldo_nuevo = $saldo + $monto;

                if($conn->ejecutar("UPDATE cuentas SET saldo = $saldo_nuevo WHERE id = $cuenta_id") == 'ok'){
                    return 'ok';
                }else{
                    return 'not';
                }
            }
        }

        public function edi_movimiento($tipo, $cuenta_id, $monto_nuevo, $movi_id){
            $conn = new conexion;
            if($tipo == 'ingreso'){
                $saldo_cuent = $conn->consultar("SELECT saldo FROM cuentas WHERE id = $cuenta_id");
                $saldo = !empty($saldo_cuent[0][0]) ? $saldo_cuent[0][0] : 0; //saldo de la cuenta

                $monto_v = $conn->consultar("SELECT monto FROM movimientos WHERE id = $movi_id");
                $monto_viejo = !empty($monto_v[0][0]) ? $monto_v[0][0] : 0;

                if($monto_nuevo != $monto_viejo){

                    $diferencia = $monto_nuevo - $monto_viejo;

                    $saldo_nuevo = $saldo + ($diferencia);

                    if($conn->ejecutar("UPDATE cuentas SET saldo = $saldo_nuevo WHERE id = $cuenta_id") == 'ok'){
                        return 'ok';
                    }else{
                        return 'not';
                    }
                }else{
                    return 'ok';
                }
                
            }else if($tipo == 'egreso'){
                $saldo_cuent = $conn->consultar("SELECT saldo FROM cuentas WHERE id = $cuenta_id");
                $saldo = !empty($saldo_cuent[0][0]) ? $saldo_cuent[0][0] : 0; //saldo de la cuenta

                $monto_v = $conn->consultar("SELECT monto FROM movimientos WHERE id = $movi_id");
                $monto_viejo = !empty($monto_v[0][0]) ? $monto_v[0][0] : 0;

                if($monto_nuevo != $monto_viejo){

                    $diferencia = $monto_nuevo - $monto_viejo;

                    $saldo_nuevo = $saldo - ($diferencia);

                    if($conn->ejecutar("UPDATE cuentas SET saldo = $saldo_nuevo WHERE id = $cuenta_id") == 'ok'){
                        return 'ok';
                    }else{
                        return 'not';
                    }
                }else{
                    return 'ok';
                }
                
            }
        }

        public function tran_movimiento($emi_id, $recep_id, $monto){
            $conn = new conexion;
            $saldo_e = $conn->consultar("SELECT saldo FROM cuentas WHERE id = $emi_id");
            $saldo_emi = !empty($saldo_e[0][0]) ? $saldo_e[0][0] : 0; //saldo del emisor

            $saldo_r = $conn->consultar("SELECT saldo FROM cuentas WHERE id = $recep_id");
            $saldo_recep = !empty($saldo_r[0][0]) ? $saldo_r[0][0] : 0; //saldo del receptor

            if($conn->ejecutar("UPDATE cuentas SET saldo = ($saldo_emi - $monto) WHERE id = $emi_id") == 'ok'){
                if($conn->ejecutar("UPDATE cuentas SET saldo = ($saldo_recep + $monto) WHERE id = $recep_id") == 'ok'){
                    return 'ok';
                }else{
                    return 'not';
                }
            }else{
                return 'not';
            }
        }
    }

?>