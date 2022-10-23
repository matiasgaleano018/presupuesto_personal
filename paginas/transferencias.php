<?php 
session_start();
require '../controlador/access.php';
require '../controlador/movimientos.php';

if(isset($_SESSION['usuario_id'])){
    
}else{
    header("location:login.php");
}

$usuario_nombre = !empty($_SESSION['usuario_nombre']) ? $_SESSION['usuario_nombre'] : "Usuario";
$usuario_id     = !empty($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 0;

$selec = new Access;

$foto_perfil = $selec->seleccionar("SELECT `foto` FROM `usuarios` WHERE id = $usuario_id");

if(!empty($foto_perfil[0][0])){
    $carpeta_perfil = '../img/usuarios/user'.$usuario_id.'/';
    $usuario_perfil = $carpeta_perfil.$foto_perfil[0][0];
}else{
    $usuario_perfil = '../img/perfil.jpg';
}
    $param = $selec->seleccionar("SELECT `simbolo_monetario`, `decimales` FROM `parametros` WHERE `usuario_id` = $usuario_id");
    $simbolo_mone = !empty($param[0][0]) ? $param[0][0] : "$";
    $decimales_mone = (!empty($param[0][1]) || $param[0][1] == 0) ? $param[0][1] : 2;
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Finanzas Personales</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="../css/styles.css" rel="stylesheet" />
        <link href="../css/styles2.css" rel="stylesheet" />
        <link rel="shortcut icon" href="../img/favicon.png" type="image/x-icon">
        <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        
    </head>
    <body class="sb-nav-fixed">
        <?php
            if(isset($_GET['tnf'])){
                if($_GET['tnf'] == 'suc'){
                    echo '<script type="text/javascript">sweetAlert("¡Bien hecho!","Se ha realizado la transferencia","success")</script>';
                }
            }
            if(!empty($_POST['emisor']) && !empty($_POST['receptor']) && !empty($_POST['monto'])){
                if($_POST['emisor'] != $_POST['receptor']){

                    $cuenta_emi_id = !empty($_POST['emisor']) ? $_POST['emisor'] : 0;
                    $cuenta_id = !empty($_POST['receptor']) ? $_POST['receptor'] : 0;
                    $notas  = !empty($_POST['notas']) ? $_POST['notas'] : "";
                    $monto  = !empty($_POST['monto']) ? $_POST['monto'] : 0;
                    $monto  = str_replace(".", "", $monto);
                    $monto  = str_replace(",", ".", $monto);

                    
                    if(is_numeric($monto)){
                        $saldo_e = $selec->seleccionar("SELECT saldo FROM cuentas WHERE id = $cuenta_emi_id");
                        $saldo_emi = !empty($saldo_e[0][0]) ? $saldo_e[0][0] : 0; //saldo del emisor
                        if($saldo_emi >= $monto){
                            $fecha  = !empty($_POST['fecha']) ? $_POST['fecha'] : date('Y-m-d');
                            $sql = "INSERT INTO `movimientos`(`monto`, `fecha`, `cuenta_id`, `cuenta_emi_id`, `categoria_id`, `usuario_id`, `descripcion`, `tipo`, `creadoel`) VALUES ($monto, '$fecha', $cuenta_id, $cuenta_emi_id, 0, $usuario_id, '$notas', 'transferencia', NOW())";

                            if($selec->insertar($sql) == 'ok'){
                                $movi = new Movimientos;
                                //tran_movimiento(id de la cuenta emisora, id de la cuenta receptora, monto)
                                if($movi->tran_movimiento($cuenta_emi_id, $cuenta_id, $monto) == 'ok'){
                                    header("location:transferencias.php?tnf=suc");
                                }
                            }else{
                                echo '<script type="text/javascript">sweetAlert("¡Atención!","No se han podido insertar los archivos","error")</script>';
                            }
                        }else{
                            echo '<script type="text/javascript">sweetAlert("¡Error!","El monto tipeado para la transferencia es superior al capital que posee la cuenta emisora","error")</script>';
                        }
                    }else{
                        echo '<script type="text/javascript">sweetAlert("¡Atención!","Daton invalidos en campo Monto","warning")</script>';
                    }
                }else{
                    echo '<script type="text/javascript">sweetAlert("¡Atención!","La cuenta emisora no puede ser la misma que la receptora","warning")</script>';
                }
            }

            $transferencias = $selec->seleccionar("SELECT `cuenta_id`, `cuenta_emi_id`, `monto`, `descripcion`, `fecha` FROM `movimientos` WHERE `usuario_id` = $usuario_id AND `tipo` = 'transferencia' ORDER BY creadoel DESC");
            
            unset($_POST);
        ?>
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3 d-flex justify-content-center" href="principal.php"><img src="../img/icono.png" alt=""></a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                <div class="input-group">
                    
                </div>
            </form>
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"><img src="<?php echo $usuario_perfil; ?>" alt="Foto perfil"  class="rounded-circle" style="width: 40px; height: 40px;"></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="perfil.php">Editar Usuario</a></li>
                        <li><a class="dropdown-item" href="contrasenha.php">Cambiar Contraseña</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="../controlador/cerrar_session.php"><b><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</b></a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <?php require_once 'items_menu.php'; ?>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Bienvenido/a</div>
                        <?php echo $usuario_nombre; ?>
                    </div>
                </nav>
            </div>
            <!--Fin del menu de navegacion-->
        <div id="layoutSidenav_content">
            <main>
                <h1 class="p-2">Transferencias</h1>
                <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-12">
                                <div class="card border-0 ">
                                    <div class="card-body">
                                        <form action="transferencias.php" method="post">
                                            <div class="row mb-3">
                                                <div class="col-lg-4 col-sm-12 my-2">
                                                    <div class="form-floating mb-3 mb-md-0">
                                                        <select class="form-select" aria-label="Default select example" name="emisor">
                                                            <?php
                                                                $cont_cuen = 0;
                                                                $cuentas = $selec->seleccionar("SELECT nombre, id, saldo FROM cuentas WHERE usuario_id = $usuario_id");
                                                                foreach($cuentas as $cuen){
                                                                    $cont_cuen++;
                                                                                            
                                                            ?>
                                                            <option <?php if($cont_cuen == 1){echo "selected";}?> value="<?php echo $cuen[1];?>">
                                                                <?php echo $cuen[0]." - $simbolo_mone ".number_format($cuen[2], $decimales_mone, ',', '.'); ?>
                                                            </option>
                                                                <?php }?>
                                                        </select>
                                                        <label for="inputFirstName">De:</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-12 my-2">
                                                    <div class="form-floating mb-3 mb-md-0">
                                                        <select class="form-select" aria-label="Default select example" name="receptor">
                                                            <?php
                                                                $cont_cuen = 0;
                                                                $cuentas = $selec->seleccionar("SELECT nombre, id, saldo FROM cuentas WHERE usuario_id = $usuario_id");
                                                                foreach($cuentas as $cuen){
                                                                    $cont_cuen++;
                                                                                            
                                                            ?>
                                                            <option <?php if($cont_cuen == 2){echo "selected";}?> value="<?php echo $cuen[1];?>">
                                                                <?php echo $cuen[0]." - $simbolo_mone ".number_format($cuen[2], $decimales_mone, ',', '.'); ?>
                                                            </option>
                                                                <?php }?>
                                                        </select>
                                                        <label for="inputFirstName">A:</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-12 my-2">
                                                    <div class="form-floating mb-3 mb-md-0">
                                                        <input type="date" class="form-control" name="fecha" id="fecha" value="<?php echo date('Y-m-d');?>" required>
                                                        <label for="inputFirstName">Fecha</label>
                                                    </div>
                                                </div>
                                                <div class="col-12 my-2">
                                                    <div class="form-floating mb-3 mb-md-0">
                                                        <input autofocus class="form-control" id="moneda" type="text" placeholder="Capital" name="monto" value="" required/>
                                                        <label for="inputFirstName">Monto</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-12">
                                                    <div class="form-floating">
                                                        <textarea class="form-control" placeholder="Escribir una nota" id="floatingTextarea2" name="notas" style="height: 60px"></textarea>
                                                        <label for="floatingTextarea2">Notas</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-4 mb-0">
                                                <div class="d-grid">
                                                    <input class="btn btn-primary" type="submit" value="Guardar">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                        <!-- Grilla -->
                    <form action="../controlador/lote.php" method="post">
                        <div class="card mb-4">
                            <div class="card-header">
                            </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead class="table-dark">
                                    <tr>
                                        <th>De</th>
                                        <th>A</th>
                                        <th>Monto</th>
                                        <th>Notas</th>
                                        <th>Fecha</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>De</th>
                                            <th>A</th>
                                            <th>Monto</th>
                                            <th>Notas</th>
                                            <th>Fecha</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                            if(!empty($transferencias)){
                                                foreach($transferencias as $transfer){
                                                    $cemi = $selec->seleccionar("SELECT nombre FROM cuentas WHERE id = $transfer[1]");
                                                    $crecep = $selec->seleccionar("SELECT nombre FROM cuentas WHERE id = $transfer[0]");
                                        ?>
                                        <tr>
                                            <td><?php echo $cemi[0][0]; ?></td>
                                            <td><?php echo $crecep[0][0]; ?></td>
                                            <td class="text-end"><?php echo "$simbolo_mone ".number_format($transfer[2], $decimales_mone, ',', '.');?></td>
                                            <td><?php echo $transfer[3]; ?></td>
                                            <td><?php echo $transfer[4]; ?></td>
                                        <?php 
                                                }
                                            }else{
                                                echo '<td class="text-center" colspan="5"><b>No hay datos que mostrar</b></td>';
                                            }
                                        ?>
                                        </tr>
                                    </tbody>
                                </table>
                                
                            </div>
                        </div>
                        <!-- Fin grilla-->
                    
            </main>
            <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; finanzaspersonales.com 2022</div>
                            <div>
                                Desarrollado por <a href="https://matias-galeano.netlify.app/" target="_blank">Matias Galeano</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="../js/scripts.js"></script>
        <script src="../js/monedas.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="../assets/demo/chart-area-demo.js"></script>
        <script src="../assets/demo/chart-bar-demo.js"></script>
        <script src="../assets/demo/chart-pie-demo.js"></script>
        <script src="../assets/demo/chart-pie-demo2.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script src="../js/datatables-simple-demo.js"></script>
    </body>
</html>
