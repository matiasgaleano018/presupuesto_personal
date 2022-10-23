<?php 
session_start();
require '../controlador/access.php';

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

            if(!empty($_POST['nombre'])){
                $nombre = $_POST['nombre'];
                $notas  = !empty($_POST['notas']) ? $_POST['notas'] : "";
                $saldo  = !empty($_POST['capital']) ? $_POST['capital'] : 0;
                $saldo  = str_replace(".", "", $saldo);
                $saldo  = str_replace(",", ".", $saldo);
                $sql = "INSERT INTO `cuentas`(`nombre`, `saldo`, `usuario_id`, `descripcion`, `creadoel`) VALUES ('$nombre', $saldo, $usuario_id, '$notas', NOW())";

                if($selec->insertar($sql) == 'ok'){
                    header("location:cuentas.php");
                }else{
                    echo '<script type="text/javascript">sweetAlert("¡Atención!","No se han podido insertar los archivos","error")</script>';
                }
            }

            // Actualizar una cuenta
            if(isset($_POST['nombre_edit'])){
                $cuen_nombre      = $_POST['nombre_edit'];
                $cuen_descripcion = !empty($_POST['descripcion_edit']) ? $_POST['descripcion_edit'] : "";
                $cuen_cod         = $_POST['cod_edit'];
                $sql_cedit        = "UPDATE `cuentas` SET `nombre`='$cuen_nombre',`descripcion`='$cuen_descripcion',`modificadoel`= NOW() WHERE id = $cuen_cod";

                echo $sql_cedit;
                if($selec->insertar($sql_cedit) == 'ok'){
                    header("location:cuentas.php");
                }else{
                    echo '<script type="text/javascript">sweetAlert("¡Atención!","No se han podido actualizar los archivos","error")</script>';
                }
            }

            // Eliminar una nueva cuenta
            if(isset($_POST['cod_eli'])){
                $cuen_cod = $_POST['cod_eli'];
                $cuen_eli = $selec->seleccionar("SELECT COUNT(*) FROM movimientos WHERE cuenta_id = $cuen_cod");
                if(empty($cuen_eli[0][0])){
                    $selec->eliminar("DELETE FROM `cuentas` WHERE id = $cuen_cod");

                }else{
                    echo '<script type="text/javascript">sweetAlert("¡Atención!","No se ha podido eliminar la cuenta, tiene '.$cuen_eli[0][0].' movimientos asociados. Primero debe eliminar los movimientos.","error")</script>';
                }
            }

            $cuentas = $selec->seleccionar("SELECT `nombre`, `saldo`, `descripcion`, `creadoel`, `modificadoel`, `id` FROM `cuentas` WHERE `usuario_id` = $usuario_id ORDER BY `creadoel` DESC");
            
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
                <h1 class="p-2">Cuentas</h1>
                <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-12">
                                <div class="card border-0 ">
                                    <div class="card-body">
                                        <form action="cuentas.php" method="post">
                                            <div class="row mb-3">
                                                <div class="col-lg-6 col-sm-12">
                                                    <div class="form-floating mb-3 mb-md-0">
                                                        <input required class="form-control" id="inputFirstName" type="text" placeholder="Nombre" name="nombre" value="" autofocus/>
                                                        <label for="inputFirstName">Nombre</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-sm-12">
                                                    <div class="form-floating mb-3 mb-md-0">
                                                        <input class="form-control" id="moneda" type="text" placeholder="Capital" name="capital" value=""/>
                                                        <label for="inputFirstName">Capital</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-12">
                                                    <div class="form-floating">
                                                        <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea2" name="notas" style="height: 70px"></textarea>
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
                                <div class="row">
                                    <div class="col-1">
                                        <div class="form-check"><input class="form-check-input" type="checkbox" value="1" id="selecTodoCat"></div>
                                    </div>
                                    <div class="col-2">
                                        <div class="btn-group">
                                            <button class="btn btn-dark dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Acciones">
                                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <input type="text" value="cuentas.php" name="redir" style="display: none;">
                                                <li><input type="submit" value="Eliminar Seleccionados" id="enviar" class="dropdown-item" name="eliminar_lote_cuen"></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead class="table-dark">
                                    <tr>
                                        <td></td>
                                        <th>Nombre</th>
                                        <th>Saldo</th>
                                        <th>Notas</th>
                                        <th>Creado el</th>
                                        <th>Modificado el</th>
                                        <th></th>
                                        <th></th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th></th>
                                            <th>Nombre</th>
                                            <th>Saldo</th>
                                            <th>Notas</th>
                                            <th>Creado el</th>
                                            <th>Modificado el</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                            if(!empty($cuentas)){
                                                foreach($cuentas as $cuen){
                                        ?>
                                        <tr>
                                            <td><div class="form-check"><input class="form-check-input" type="checkbox" value="<?php echo $cuen[5];?>" id="flexCheckDefault" name="ids[]"></div></td>
                                            </form><!-- Fin del formulario lote -->
                                            <td><?php echo $cuen[0]; ?></td>
                                            <td class="text-end"><?php echo "$simbolo_mone ".number_format($cuen[1], $decimales_mone, ',', '.');?></td>
                                            <td><?php echo $cuen[2]; ?></td>
                                            <td><?php echo $cuen[3]; ?></td>
                                            <td><?php echo $cuen[4]; ?></td>
                                            <td><button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo $cuen[5];?>"><i class="fas fa-trash-alt"></i></button></td>
                                            <td><button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#staticBackdrop<?php echo $cuen[5];?>"><i class="fas fa-pencil-alt"></i></button></td>

                                            <!-- Modal ELIMINAR -->
                                            <div class="modal fade" id="exampleModal<?php echo $cuen[5];?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <form action="cuentas.php" method="post">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Eliminar cuenta</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <h6>¿Estas seguro/a que deseas eliminar esta cuenta?</h6>
                                                                <input type="number" value="<?php echo $cuen[5]; ?>" name="cod_eli" style="display:none;">
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
                                            <div class="modal fade" id="staticBackdrop<?php echo $cuen[5];?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="staticBackdropLabel">Editar cuenta</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="card border-0 ">
                                                        <div class="card-body">
                                                            <!-- /////////// Inicio de formulario de editar (modal) ///////////////-->
                                                            <form action="cuentas.php" method="post">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <div class="form-floating mb-3 mb-md-0">
                                                                            <input required class="form-control" id="inputFirstName" type="text" placeholder="Nombre" name="nombre_edit" value="<?php echo $cuen[0]; ?>" autofocus/>
                                                                            <label for="inputFirstName">Nombre</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 my-2">
                                                                        <div class="form-floating mb-3 mb-md-0">
                                                                            <input type="text" value="<?php echo $cuen[5]; ?>" name="cod_edit" style="display:none;">
                                                                            <input class="form-control" id="inputFirstName" type="text" placeholder="Descripción" name="descripcion_edit" value="<?php echo $cuen[2]; ?>"/>
                                                                            <label for="inputFirstName">Descripción</label>
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
                                            </div>
                                        <?php 
                                                }
                                            }else{
                                                echo '<td></td><td><b>No hay datos que mostrar</b></td>';
                                            }
                                        ?>
                                        </tr>
                                    </tbody>
                                </table>
                                
                            </div>
                        </div>
                        <!-- Fin grilla-->
                    
            </main>
<?php require_once 'pie.php'; ?>
