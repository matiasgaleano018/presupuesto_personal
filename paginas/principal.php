<?php 
include 'cabecera.php'; 

$ingresos_sum = $selec->seleccionar("SELECT SUM(monto) FROM movimientos WHERE usuario_id = $usuario_id AND tipo = 'ingreso' AND DATE_FORMAT(fecha, 'Y/m') = DATE_FORMAT(CURDATE(), 'Y/m')");
$egresos_sum = $selec->seleccionar("SELECT SUM(monto) FROM movimientos WHERE usuario_id = $usuario_id AND tipo = 'egreso' AND DATE_FORMAT(fecha, 'Y/m') = DATE_FORMAT(CURDATE(), 'Y/m')");
$capital_sum = $selec->seleccionar("SELECT SUM(saldo) FROM cuentas WHERE usuario_id = $usuario_id");

$ingresos = !empty($ingresos_sum[0][0]) ? $ingresos_sum[0][0] : 0;
$egresos  = !empty($egresos_sum[0][0])  ? $egresos_sum[0][0]  : 0;
$capital =  !empty($capital_sum[0][0])  ? $capital_sum[0][0]  : 0;

$balance = $ingresos - $egresos;
$param = $selec->seleccionar("SELECT `simbolo_monetario`, `decimales` FROM `parametros` WHERE `usuario_id` = $usuario_id");
$simbolo_mone = !empty($param[0][0]) ? $param[0][0] : "$";
$decimales_mone = (!empty($param[0][1]) || $param[0][1] == 0) ? $param[0][1] : 2;

$meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

$nro_mes = date('m');
$anho = date('Y');

$fecha_cabe = $meses[$nro_mes-1]." ".$anho;

?>
<!--Parte principal-->
<div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Estadisticas</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active"><?php echo $fecha_cabe; ?></li>
                        </ol>
                        <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-success text-white mb-4 card-ingresos">

                                    <div class="card-body"><b>Ingresos: </b><?php echo "$simbolo_mone ".number_format($ingresos, $decimales_mone, ',', '.');?></div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="ingresos.php">Ver detalles</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-danger text-white mb-4 card-egresos">
                                    <div class="card-body"><b>Egresos: </b><?php echo "$simbolo_mone ".number_format($egresos, $decimales_mone, ',', '.');?></div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="egresos.php">Ver detalles</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4 card-balance">
                                    <div class="card-body"><b>Balance: </b><?php echo "$simbolo_mone ".number_format($balance, $decimales_mone, ',', '.');?></div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="#">Ver detalles</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-warning text-white mb-4 card-capital">
                                    <div class="card-body"><b>Capital: </b><?php echo "$simbolo_mone ".number_format($capital, $decimales_mone, ',', '.');?></div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="cuentas.php">Ver detalles</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-pie me-1"></i>
                                        Ingresos por categoria
                                    </div>
                                    <div class="card-body"><canvas id="ingreso_graf" width="100%" height="50"></canvas></div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-pie me-1"></i>
                                        Egresos por categoria
                                    </div>
                                    <div class="card-body"><canvas id="egreso_graf" width="100%" height="50"></canvas></div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-bar me-1"></i>
                                        Ingresos mensuales
                                    </div>
                                    <div class="card-body"><canvas id="ingre_mes" width="100%" height="40"></canvas></div>
                                </div>
                            </div>    
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-bar me-1"></i>
                                        Egresos Mensuales
                                    </div>
                                    <div class="card-body"><canvas id="egre_mes" width="100%" height="40"></canvas></div>
                                </div>
                            </div>   
                        </div>
                    </div>
                </main>

<?php include 'pie.php'; ?>