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

// Insertar una nueva categoria
if(isset($_POST['nombre'])){
    $cat_nombre = $_POST['nombre'];
    $cat_tipo   = $_POST['movimiento'];
    $cat_color  = !empty($_POST['color']) ? $_POST['color'] : "";
    $cat_notas  = !empty($_POST['notas']) ? $_POST['notas'] : "";

    $sql_cat = "INSERT INTO `categorias`(`nombre`, `tipo`, `color`, `descripcion`, `creadoel`, `usuario_id`) VALUES ('$cat_nombre', '$cat_tipo', '$cat_color', '$cat_notas', NOW(), $usuario_id)";

    if($selec->insertar($sql_cat) == 'ok'){
        header("location:categorias.php");
    }else{
        echo '<script type="text/javascript">sweetAlert("¡Atención!","No se han podido insertar los archivos","error")</script>';
    }
}

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

            // Actualizar una nueva categoria
            if(isset($_POST['nombre_edit'])){
                $cat_nombre = $_POST['nombre_edit'];
                $cat_color  = !empty($_POST['color_edit']) ? $_POST['color_edit'] : "";
                $cat_notas  = !empty($_POST['notas_edit']) ? $_POST['notas_edit'] : "";
                $cat_cod    = $_POST['cod_edit'];

                $sql_cedit = "UPDATE `categorias` SET `nombre`='$cat_nombre',`color`='$cat_color',`descripcion`='$cat_notas',`modificadoel`=NOW() WHERE id = $cat_cod";

                if($selec->insertar($sql_cedit) == 'ok'){
                    header("location:categorias.php");
                }else{
                    echo '<script type="text/javascript">sweetAlert("¡Atención!","No se han podido actualizar los archivos","error")</script>';
                }
            }

            // Eliminar una nueva categoria
            if(isset($_POST['cod_eli'])){
                $cat_cod = $_POST['cod_eli'];
                $cat_eli = $selec->seleccionar("SELECT COUNT(*) FROM movimientos WHERE categoria_id = $cat_cod");
                if(empty($cat_eli[0][0])){
                    $selec->eliminar("DELETE FROM `categorias` WHERE id = $cat_cod");

                }else{
                    echo '<script type="text/javascript">sweetAlert("¡Atención!","No se ha podido eliminar la categoria, tiene '.$cat_eli[0][0].' movimientos asociados","error")</script>';
                }
            }

            
            $categorias = $selec->seleccionar("SELECT `nombre`, `tipo`, `color`, `descripcion`, `creadoel`, `modificadoel`, `id` FROM `categorias` WHERE `usuario_id` = $usuario_id ORDER BY creadoel DESC");
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
                <h1 class="p-2">Categorias</h1>
                <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-12">
                                <div class="card border-0 ">
                                    <div class="card-body">
                                        <form action="categorias.php" method="post">
                                            <div class="row mb-3">
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="form-floating mb-3 mb-md-0">
                                                        <input required class="form-control" id="inputFirstName" type="text" placeholder="Nombre" name="nombre" value="" autofocus/>
                                                        <label for="inputFirstName">Nombre</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-sm-12">
                                                    <div class="form-floating">
                                                        Tipo
                                                        <div class="row">
                                                            <div class="form-check col-6">
                                                                <input class="form-check-input" type="radio" name="movimiento" value="ingreso" id="flexRadioDefault1">
                                                                <label class="form-check-label" for="flexRadioDefault1">
                                                                    Ingreso
                                                                </label>
                                                            </div>
                                                            <div class="form-check col-6">
                                                                <input class="form-check-input" type="radio" name="movimiento" id="flexRadioDefault2" value="egreso" checked>
                                                                <label class="form-check-label" for="flexRadioDefault2">
                                                                    Egreso
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-sm-12">
                                                    <div class="row">
                                                        <div class="col-3">
                                                            Color
                                                        </div>
                                                        <div class="col-9">
                                                        <input type="color" class="form-control form-control-color" id="exampleColorInput" value="#563d7c" title="Choose your color" name="color">
                                                        </div>
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
                                                <input type="text" value="categorias.php" name="redir" style="display: none;">
                                                <li><input type="submit" value="Eliminar Seleccionados" id="enviar" class="dropdown-item" name="eliminar_lote"></li>
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
                                        <th>Tipo</th>
                                        <th>Color</th>
                                        <th>Descripción</th>
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
                                            <th>Tipo</th>
                                            <th>Color</th>
                                            <th>Descripción</th>
                                            <th>Creado el</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                            if(!empty($categorias)){
                                                foreach($categorias as $cate){
                                        ?>
                                        <tr>
                                            <td><div class="form-check"><input class="form-check-input" type="checkbox" value="<?php echo $cate[6];?>" id="flexCheckDefault" name="ids[]"></div></td>
                                            </form><!-- Fin del formulario lote -->
                                            <td><?php echo $cate[0]; ?></td>
                                            <td><?php echo $cate[1]; ?></td>
                                            <td style="background: <?php echo $cate[2]; ?>;"><?php echo $cate[2]; ?></td>
                                            <td><?php echo $cate[3]; ?></td>
                                            <td><?php echo $cate[4]; ?></td>
                                            <td><?php echo $cate[5]; ?></td>
                                            <td><button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo $cate[6];?>"><i class="fas fa-trash-alt"></i></button></td>
                                            <td><button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#staticBackdrop<?php echo $cate[6];?>"><i class="fas fa-pencil-alt"></i></button></td>

                                            <!-- Modal ELIMINAR -->
                                            <div class="modal fade" id="exampleModal<?php echo $cate[6];?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <form action="categorias.php" method="post">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Eliminar categoria</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <h6>¿Estas seguro/a que deseas eliminar esta categoria?</h6>
                                                                <input type="number" value="<?php echo $cate[6]; ?>" name="cod_eli" style="display:none;">
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
                                            <div class="modal fade" id="staticBackdrop<?php echo $cate[6];?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="staticBackdropLabel">Editar categoria</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="card border-0 ">
                                                        <div class="card-body">

                                                            <!-- /////////// Inicio de formulario de editar (modal) ///////////////-->

                                                            <form action="categorias.php" method="post">
                                                                <div class="row">
                                                                    <div class="col-md-12 col-sm-12">
                                                                        <div class="form-floating mb-3 mb-md-0">
                                                                            <input required class="form-control" id="inputFirstName" type="text" placeholder="Nombre" name="nombre_edit" value="<?php echo $cate[0]; ?>" autofocus/>
                                                                            <label for="inputFirstName">Nombre</label>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="col-7 my-3">
                                                                        <div class="row">
                                                                            <div class="col-3">
                                                                                Color
                                                                            </div>
                                                                            <div class="col-9">
                                                                            <input type="text" value="<?php echo $cate[6]; ?>" name="cod_edit" style="display:none;">
                                                                            <input type="color" class="form-control form-control-color" id="exampleColorInput" value="<?php echo $cate[2]; ?>" title="Choose your color" name="color_edit">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <div class="col-12">
                                                                        <div class="form-floating">
                                                                            <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea2" name="notas_edit" style="height: 70px"><?php echo $cate[3]; ?></textarea>
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
