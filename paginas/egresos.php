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
            if(!empty($_GET)){
                if($_GET['su'] > 0 && $_GET['er'] > 0){
                    echo '<script type="text/javascript">sweetAlert("¡Atención!","Se han eliminado '.$_GET['su'].' elementos. Pero '.$_GET['er'].' elementos no se han podido eliminar debido a que tienen movimientos asociados.","warning")</script>';
                }else if($_GET['su'] > 0){
                    echo '<script type="text/javascript">sweetAlert("¡Bien hecho!","Se han eliminado '.$_GET['su'].' elementos.","success")</script>';
                }else{
                    echo '<script type="text/javascript">sweetAlert("¡Atención!","No se han podido eliminar los elementos, tienen movimientos asociados.","error")</script>';
                }
            }

            if(!empty($_POST['nombre']) && !empty($_POST['monto'])){
                $nombre = $_POST['nombre'];
                $notas  = !empty($_POST['notas']) ? $_POST['notas'] : "";
                $fecha  = !empty($_POST['fecha']) ? $_POST['fecha'] : date('Y-m-d');
                $monto  = !empty($_POST['monto']) ? $_POST['monto'] : 0;
                $monto  = str_replace(".", "", $monto);
                $monto  = str_replace(",", ".", $monto);

                $categoria_id = !empty($_POST['categoria']) ? $_POST['categoria'] : "";
                $cuenta_id    = !empty($_POST['cuenta'])    ? $_POST['cuenta'] : "";
                $sql = "INSERT INTO `movimientos`(`nombre`, `monto`, `cuenta_id`, `categoria_id`, `usuario_id`, `descripcion`, `tipo`, `creadoel`, `fecha`) VALUES ('$nombre', $monto, $cuenta_id, $categoria_id, $usuario_id, '$notas', 'egreso', NOW(), '$fecha')";

                if($selec->insertar($sql) == 'ok'){
                    $movi = new Movimientos;
                    if($movi->rea_movimiento('egreso', $cuenta_id, $monto) == 'ok'){
                        header("location:egresos.php");
                    }else{
                        echo '<script type="text/javascript">sweetAlert("¡Atención!","No se ha podido actualizar el saldo de la cuenta seleccionada","error")</script>';
                    }
                }else{
                    echo '<script type="text/javascript">sweetAlert("¡Atención!","No se han podido insertar los archivos","error")</script>';
                }
            }

            // Actualizar un egreso
            if(isset($_POST['cod_edit'])){
                
                $egre_nombre      = $_POST['nombre_edit'];
                $egre_notas       = !empty($_POST['notas_edit']) ? $_POST['notas_edit'] : "";
                $egre_cod         = $_POST['cod_edit'];
                $egre_monto       = !empty($_POST['monto']) ? $_POST['monto'] : "";
                $fecha             = !empty($_POST['fecha_edit']) ? $_POST['fecha_edit'] : date('Y-m-d');
                $cuenta_id         = $_POST['cuenta'];

                $monto  = str_replace(".", "", $egre_monto);
                $monto  = str_replace(",", ".", $monto);
                $sql_cedit         = "UPDATE `movimientos` SET `nombre`='$egre_nombre', `fecha`='$fecha',`monto`=$monto,`descripcion`='$egre_notas',`modificadoel`= NOW() WHERE id = $egre_cod";


                $movi = new Movimientos;

                if($movi->edi_movimiento('egreso', $cuenta_id, $monto, $egre_cod) == 'ok'){

                    if($selec->actualizar($sql_cedit) == 'ok'){
                        //header("location:egresos.php");
                    }else{
                        echo '<script type="text/javascript">sweetAlert("¡Atención!","No se han podido actualizar los archivos","error")</script>';
                    }
                }else{
                    echo '<script type="text/javascript">sweetAlert("¡Atención!","No se han podido actualizar la cuenta","error")</script>';
                }
            }

            // Eliminar un egreso
            if(isset($_POST['cod_eli'])){
                $egre_cod   = $_POST['cod_eli'];
                $egre_monto = $_POST['mont_eli'];
                $cuenta_id   = $_POST['cuenta'];
                if($selec->eliminar("DELETE FROM `movimientos` WHERE id = $egre_cod") == 'ok'){
                    $movi = new Movimientos;
                    if($movi->eli_movimiento('egreso', $cuenta_id, $egre_monto) == 'ok'){
                        header("location:egresos.php");
                    }else{
                        echo '<script type="text/javascript">sweetAlert("¡Atención!","No se ha podido actualizar la cuenta","error")</script>';
                    }
                }else{
                    echo '<script type="text/javascript">sweetAlert("¡Atención!","No se ha podido eliminar el movimiento","error")</script>';
                }
            }


            if(isset($_POST['filtro'])){
                if($_POST['filtro'] == 'Hoy'){
                    $query = "AND fecha = CURDATE()";
                }else if($_POST['filtro'] == 'Esta semana'){
                    $query = "AND DATE_FORMAT(fecha, '%u') = date_format(CURDATE(), '%u') AND DATE_FORMAT(fecha, '%Y') = date_format(CURDATE(), '%Y')";
                }else if($_POST['filtro'] == 'Este mes'){
                    $query = "AND DATE_FORMAT(fecha, '%m') = date_format(CURDATE(), '%m')";
                }
                else if($_POST['filtro'] == 'Este año'){
                    $query = "AND DATE_FORMAT(fecha, '%Y') = date_format(CURDATE(), '%Y')";
                }else{
                    $query = "";
                }
            }else{
                $query = "";
            }

            $sql_egresos = "SELECT `nombre`, `monto`, `cuenta_id`, `categoria_id`, `descripcion`, `tipo`, `fecha`, `modificadoel`, `id` FROM `movimientos` WHERE `usuario_id` = $usuario_id AND `tipo` = 'egreso' $query ORDER BY creadoel DESC";
            $egresos = $selec->seleccionar($sql_egresos);
            $query = "";
            
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
                <h1 class="p-2">Egresos</h1>
                    <div class="container">
                        <div class="col-lg-12">
                            <div class="card border-0 ">
                        <!-- Grilla -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <button type="button" class="btn btn-success d-inline mx-2 fw-bolder" data-bs-toggle="modal" data-bs-target="#modal-nuevo"><i class="fa-solid fa-plus"></i> Nuevo</button>
                                <div class="modal fade" id="modal-nuevo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel">Añadir egreso</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                            <div class="modal-body">
                                                <div class="card border-0 ">
                                                    <div class="card-body">
                                                        <!-- /////////// Inicio de formulario de editar (modal) ///////////////-->
                                                        <form action="egresos.php" method="post">
                                                            <div class="row">
                                                                <div class="col-lg-6 col-sm-12 my-2">
                                                                    <div class="form-floating mb-3 mb-md-0">
                                                                        <input autofocus required class="form-control" id="inputFirstName" type="text" placeholder="Nombre" name="nombre" />
                                                                        <label for="inputFirstName">Nombre</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 col-sm-12 my-2">
                                                                    <div class="form-floating mb-3 mb-md-0">
                                                                        <input class="form-control monto" id="moneda" type="text" placeholder="Monto" name="monto" value=""/>
                                                                        <label for="inputFirstName">Monto</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4 col-sm-12 my-2">
                                                                    <div class="form-floating mb-3 mb-md-0">
                                                                        <select class="form-select" aria-label="Default select example" name="cuenta">
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
                                                                        <label for="inputFirstName">Cuenta</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4 col-sm-12 my-2">
                                                                    <div class="form-floating mb-3 mb-md-0">
                                                                        <select class="form-select" aria-label="Default select example" name="categoria">
                                                                            <?php
                                                                                $cont_cate = 0;
                                                                                $categorias = $selec->seleccionar("SELECT nombre, id, color FROM categorias WHERE usuario_id = $usuario_id AND tipo = 'egreso'");
                                                                                foreach($categorias as $cate){
                                                                                    $cont_cate++;
                                                                                            
                                                                            ?>
                                                                                <option <?php if($cont_cate == 1){echo "selected";}?> value="<?php echo $cate[1];?>"><?php echo $cate[0];?></option>
                                                                            <?php }?>
                                                                        </select>
                                                                        <label for="inputFirstName">Categoria</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4 col-sm-12 my-2">
                                                                    <div class="form-floating mb-3 mb-md-0">
                                                                        <input type="date" class="form-control" name="fecha" id="fecha" value="<?php echo date('Y-m-d');?>" required>
                                                                        <label for="fecha">Fecha</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 my-2">
                                                                    <div class="form-floating mb-3 mb-md-0">
                                                                    <textarea class="form-control" id="floatingTextarea2" name="notas" style="height: 90px"></textarea>
                                                                    <label for="floatingTextarea2">Descripción</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                                        <div class="mt-4 mb-0">
                                                                <div class="d-grid">
                                                                    
                                                                    <button type="submit" class="btn btn-primary"><i class="fa-regular fa-floppy-disk fw-bolder"></i> Guardar</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <form action="egresos.php" method="post" class="d-inline">
                                    <div class="btn-group">
                                        <button class="btn btn-dark dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Filtro por fechas">
                                            <i class="fa-solid fa-calendar-days"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><input type="submit" value="Hoy" id="enviar" class="dropdown-item" name="filtro"></li>
                                            <li><input type="submit" value="Esta semana" id="enviar" class="dropdown-item" name="filtro"></li>
                                            <li><input type="submit" value="Este mes" id="enviar" class="dropdown-item" name="filtro"></li>
                                            <li><input type="submit" value="Este año" id="enviar" class="dropdown-item" name="filtro"></li>
                                            <li><input type="submit" value="Mostrar todo" id="enviar" class="dropdown-item" name="filtro"></li>
                                        </ul>
                                    </div>                                                        
                                </form>
                                <form action="../controlador/exportar_egresos.php" method="post" class="d-inline">
                                    <div class="btn-group">
                                        <input type="text" value="<?php echo $sql_egresos; ?>" name="select" style="display:none;">
                                        <input type="text" value="<?php echo $usuario_id; ?>" name="usuario" style="display:none;">
                                        <button class="btn btn-dark" type="submit" title="Exportar">
                                            <i class="fa-solid fa-download"></i>
                                        </button>
                                    </div>
                                </form><!-- Fin del formulario lote -->
                            </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Monto</th>
                                        <th>Cuenta</th>
                                        <th>Categoria</th>
                                        <th>Descripcion</th>
                                        <th>Fecha</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Monto</th>
                                            <th>Cuenta</th>
                                            <th>Categoria</th>
                                            <th>Descripcion</th>
                                            <th>Fecha</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                            if(!empty($egresos)){
                                                foreach($egresos as $egre){
                                                    $cuenta = $selec->seleccionar("SELECT `nombre` FROM `cuentas` WHERE `id` = $egre[2]");
                                                    $categoria = $selec->seleccionar("SELECT `nombre`, `color` FROM `categorias` WHERE `id` = $egre[3]");
                                                    
                                        ?>
                                        <tr>
                                            <td><?php echo $egre[0]; ?></td>
                                            <td class="text-end"><?php echo "$simbolo_mone ".number_format($egre[1], $decimales_mone, ',', '.');?></td>
                                            <td><?php echo $cuenta[0][0];?></td>
                                            <td style="background: <?php echo $categoria[0][1];?>;" class="text-center text-white"><b><?php echo $categoria[0][0];?></b></td>
                                            <td><?php echo $egre[4]; ?></td>
                                            <td><?php echo $egre[6]; ?></td>
                                            <td><button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo $egre[8];?>"><i class="fas fa-trash-alt"></i></button></td>
                                            <td><button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#staticBackdrop<?php echo $egre[8];?>"><i class="fas fa-pencil-alt"></i></button></td>

                                            <!-- Modal ELIMINAR -->
                                            <div class="modal fade" id="exampleModal<?php echo $egre[8];?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <form action="egresos.php" method="post">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Eliminar egreso</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <h6>¿Estas seguro/a que deseas eliminar este registro?</h6>
                                                                <input type="number" value="<?php echo $egre[8]; ?>" name="cod_eli" style="display:none;">
                                                                <input type="number" value="<?php echo $egre[1]; ?>" name="mont_eli" style="display:none;">
                                                                <input type="number" value="<?php echo $egre[2]; ?>" name="cuenta" style="display:none;">
                                        
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                                <input type="submit" class="btn btn-danger" value="Eliminar">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>

                                            <!-- Modal EDITAR-->
                                            <div class="modal fade" id="staticBackdrop<?php echo $egre[8];?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="staticBackdropLabel">Editar egreso</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                        <div class="modal-body">
                                                            <div class="card border-0 ">
                                                                <div class="card-body">
                                                                    <!-- /////////// Inicio de formulario de editar (modal) ///////////////-->
                                                                    <form action="egresos.php" method="post">
                                                                        <div class="row">
                                                                            <div class="col-lg-6 col-sm-12 my-2">
                                                                                <div class="form-floating mb-3 mb-md-0">
                                                                                    <input autofocus required class="form-control" id="inputFirstName" type="text" placeholder="Nombre" name="nombre_edit" value="<?php echo $egre[0]; ?>"/>
                                                                                    <label for="inputFirstName">Nombre</label>
                                                                                    <input type="number" value="<?php echo $egre[2]; ?>" name="cuenta" style="display:none;">
                                                                                    <input type="number" value="<?php echo $egre[8]; ?>" name="cod_edit" style="display:none;">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-6 col-sm-12 my-2">
                                                                                <div class="form-floating mb-3 mb-md-0">
                                                                                    <input class="form-control" id="moneda1" type="text" placeholder="Descripción" name="monto" value="<?php echo number_format($egre[1], $decimales_mone, ',', '.');?>"/>
                                                                                    <label for="inputFirstName">Monto</label>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-6 col-sm-12 my-2">
                                                                                <div class="form-floating mb-3 mb-md-0">
                                                                                    <select class="form-select" aria-label="Default select example" name="categoria_edit">
                                                                                        <?php
                                                                                            $cont_cate = 0;
                                                                                            $categorias = $selec->seleccionar("SELECT nombre, id, color FROM categorias WHERE usuario_id = $usuario_id AND tipo = 'egreso'");
                                                                                            foreach($categorias as $cate){
                                                                                                $cont_cate++;
                                                                                                        
                                                                                        ?>
                                                                                            <option <?php if($egre[3] == $cate[1]){echo "selected";}?> value="<?php echo $cate[1];?>"><?php echo $cate[0];?></option>
                                                                                        <?php }?>
                                                                                    </select>
                                                                                    <label for="inputFirstName">Categoria</label>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-6 col-sm-12 my-2">
                                                                                <div class="form-floating mb-3 mb-md-0">
                                                                                    <input type="date" class="form-control" name="fecha_edit" id="fecha" value="<?php echo $egre[6];?>" required>
                                                                                    <label for="fecha">Fecha</label>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-12 my-2">
                                                                                <div class="form-floating mb-3 mb-md-0">
                                                                                <textarea class="form-control" id="floatingTextarea2" name="notas_edit" style="height: 90px"><?php echo $egre[4];?></textarea>
                                                                                <label for="floatingTextarea2">Descripción</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="mt-4 mb-0">
                                                                            <div class="d-grid">
                                                                                
                                                                                <button type="submit" class="btn btn-primary"><i class="fa-regular fa-floppy-disk fw-bolder"></i> Guardar</button>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php 
                                                }
                                            }else{
                                                echo '<td class="text-center" colspan="10"><b>No hay datos que mostrar</b></td>';
                                            }
                                        ?>
                                        </tr>
                                    </tbody>
                                </table>
                                
                            </div>
                        </div>
                        <!-- Fin grilla-->
                    </div>
                </div>       
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
        <script src="../js/monedas.js"></script>
        <script src="../js/scripts.js"></script>
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