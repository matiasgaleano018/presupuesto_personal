<?php 
session_start();
require_once '../controlador/access.php';

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
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Reportes</div>
                                <a class="nav-link" href="principal.php">
                                    <div class="sb-nav-link-icon"><i class="fa-solid fa-chart-pie"></i></div>
                                    Estadisticas
                                </a>
                                
                            <div class="sb-sidenav-menu-heading">Movimientos</div>
                                <a class="nav-link" href="principal.php">
                                    <div class="sb-nav-link-icon"><i class="far fa-chart-line"></i></div>
                                    Ingresos
                                </a>
                                <a class="nav-link" href="principal.php">
                                    <div class="sb-nav-link-icon"><i class="far fa-chart-line-down"></i></div>
                                    Egresos
                                </a>
                                
                            <div class="sb-sidenav-menu-heading">Definiciones</div>
                                <a class="nav-link" href="principal.php">
                                    <div class="sb-nav-link-icon"><i class="fas fa-shapes"></i></div>
                                    Categorias
                                </a>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Bienvenido/a</div>
                        <?php echo $usuario_nombre; ?>
                    </div>
                </nav>
            </div>
            <!--Fin del menu de navegacion-->
<?php
    require_once '../controlador/cambiar_contr.php';
    if(!empty($_POST['contr_anterior']) && !empty($_POST['contr_nueva'])){
        $cmb_pass = new CambiarContr($_POST['contr_anterior'], $_POST['contr_nueva'], "contrasenha.php");
        $res = $cmb_pass->cambiar($usuario_id) == 'ok';
        if($res == 'ok'){
            echo '<script type="text/javascript">sweetAlert("¡Bien hecho!","Se ha modificado la contraseña","success")</script>';
        }else if($res == 'cNc'){
            echo '<script type="text/javascript">sweetAlert("¡Atención!","La contraseña no pudo ser cambiada","error")</script>';
        }else{
            echo '<script type="text/javascript">sweetAlert("¡Atención!","Contraseña anterior incorrecta","error")</script>';
        }
    }

    unset($_POST);

?>
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
                            <img class="img-fluid rounded-circle mb-4 " src="<?php echo $usuario_perfil; ?>" alt="foto de perfil" style="width: 120px; height: 120px;"/>
                            <h1 class="fs-3 fw-bolder"><?php echo $usuario_nombre; ?></h1>
                        </div>
                    </header>
                </div>
                <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-12">
                                <div class="card border-0 ">
                                    <div class="card-body">
                                        <form action="contrasenha.php" method="post">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3 mb-md-0">
                                                        <input required class="form-control" id="inputFirstName" type="password" placeholder="Enter your first name" name="contr_anterior" value="" autofocus/>
                                                        <label for="inputFirstName">Contraseña Anterior</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <input required class="form-control" id="inputLastName" type="password" placeholder="Enter your last name" name="contr_nueva" value=""/>
                                                        <label for="inputLastName">Nueva Contraseña</label>
                                                    </div>
                                                </div>
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