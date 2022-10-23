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
//require_once 'cabecera.php';
require_once '../controlador/cambiar_perfil.php';
require_once '../controlador/editar_usuario.php';

        if($_FILES){
            if($_FILES['perfil']['size'] > 0){
                $cmb_perfil = new CambiarPerfil($_FILES['perfil'], $usuario_id);
                $cmb_perfil->cambiar();
            }else{
                echo '<script type="text/javascript">sweetAlert("¡Atención!","No se a adjuntado ninguna imagen","error")</script>';
            }
        }
        $usr_datos = $selec->seleccionar("SELECT `nombre`, `apellido`, `usuario` FROM `usuarios` WHERE id = $usuario_id");
        
        $usr_nombre   = !empty($usr_datos[0][0]) ? $usr_datos[0][0] : "";
        $usr_apellido = !empty($usr_datos[0][1]) ? $usr_datos[0][1] : "";
        $usr_nick     = !empty($usr_datos[0][2]) ? $usr_datos[0][2] : "";
        
?>
<!DOCTYPE html>
<html lang="en">
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
            /*Mensajes de validación */
            if(isset($_GET['men'])){
                if($_GET['men'] == 'edUs'){
                    echo '<script type="text/javascript">sweetAlert("¡Bien hecho!","Se han actualizado los datos","success")</script>';
                }
            }
            //---------------------------
            
            if(!empty($_POST['nombre']) && !empty($_POST['apellido']) && !empty($_POST['usuario'])){
                $nuevo_nick = $_POST['usuario'];
                $usr_nick_veri = $selec->seleccionar("SELECT `id` FROM `usuarios` WHERE usuario = '$nuevo_nick' AND id <> $usuario_id");
                if(!empty($usr_nick_veri)){
                    echo '<script type="text/javascript">sweetAlert("¡Atención!","El nombre de usuario '.$usr_nick.' ya existe","error")</script>';
                }else{
                    $edit = new EditarUsuario($_POST['nombre'], $_POST['apellido'], $_POST['usuario']);
                    if($edit->actualizar($usuario_id) == 'ok'){
                        header("location:../paginas/perfil.php?men=edUs");
                    }else{
                        echo '<script type="text/javascript">sweetAlert("¡Atención!","No se han podido actualizar los datos","error")</script>';
                    }
                }
            }
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
                <div>
                    <!-- Header - set the background image for the header in the line below-->
                    <header class="py-1 bg-image-full" style="background-image: url('../img/portada-login.jpg'); heigth: 15%;">
                        <div class="text-center my-5">
                            <!-- Prueba modal foto de perfil-->
                            <!-- Button trigger modal -->
                            <button type="button" class="btn border-0" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                <img class="img-fluid rounded-circle mb-4 " src="<?php echo $usuario_perfil; ?>" alt="foto de perfil" style="width: 120px; height: 120px;"/><div class="btn btn-warning rounded-circle" style="position: relative; left: -10%; top: 6%;"><i class="fas fa-pencil-alt"></i></div>
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="perfil.php" method="post" enctype="multipart/form-data">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Cambiar foto de perfil</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <input required class="form-control" type="file" id="formFile" name="perfil" accept="image/*">                            
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <a href="../controlador/eliminar_perfil.php?id=<?php echo $usuario_id;?>" class="btn btn-danger">Eliminar foto</a>
                                                <input type="submit" class="btn btn-primary" value="Guardar cambios">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Fin prueba modal 
                            <img class="img-fluid rounded-circle mb-4 " src="../img/perfil.jpg" alt="foto de perfil" style="width: 120px;"/>-->
                            <h1 class="fs-3 fw-bolder"><?php echo $usuario_nombre; ?></h1>
                        </div>
                    </header>
                </div>
                <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-12">
                                <div class="card border-0 ">
                                    <div class="card-body">
                                        <form action="perfil.php" method="post">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    
                                                    <div class="form-floating mb-3 mb-md-0">
                                                        <input required class="form-control" id="inputFirstName" type="text" placeholder="Enter your first name" name="nombre" value="<?php echo $usr_nombre; ?>"/>
                                                        <label for="inputFirstName">Nombre</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <input required class="form-control" id="inputLastName" type="text" placeholder="Enter your last name" name="apellido" value="<?php echo $usr_apellido; ?>"/>
                                                        <label for="inputLastName">Apellido</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input required class="form-control" id="inputEmail" type="text" placeholder="juanperez123" name="usuario" value="<?php echo $usr_nick; ?>" />
                                                <label for="inputEmail">Nombre de Usuario</label>
                                            </div>
                                            <div class="mt-4 mb-0">
                                                <div class="d-grid">
                                                    <input class="btn btn-primary" type="submit" value="Guardar cambios">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </main>

            <?php require 'pie.php'; ?>




