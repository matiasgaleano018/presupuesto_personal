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

?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Parametros - Finanzas Personales</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="../css/styles.css" rel="stylesheet" />
        <link href="../css/styles2.css" rel="stylesheet" />
        <link rel="shortcut icon" href="../img/favicon.png" type="image/x-icon">
        <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        
    </head>
    <body class="sb-nav-fixed">
        <?php
            // Actualizar parametros
            if(isset($_POST['simbolo']) && isset($_POST['decimal'])){
                $simbolo = $_POST['simbolo'];
                $decimal = $_POST['decimal'];
                $sql_cedit = "UPDATE `parametros` SET `simbolo_monetario`='$simbolo',`decimales`=$decimal,`modificadoel`= NOW() WHERE `usuario_id` =  $usuario_id";
                
                if($selec->insertar($sql_cedit) == 'ok'){
                    header("location:parametros.php");
                }else{
                    echo '<script type="text/javascript">sweetAlert("??Atenci??n!","No se han podido actualizar los registros","error")</script>';
                }
            }

            $param = $selec->seleccionar("SELECT `simbolo_monetario`, `decimales` FROM `parametros` WHERE `usuario_id` = $usuario_id");
            $simbolo_mone = !empty($param[0][0]) ? $param[0][0] : "$";
            $decimales_mone = (!empty($param[0][1]) || $param[0][1] == 0) ? $param[0][1] : 2;
            
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
                        <li><a class="dropdown-item" href="contrasenha.php">Cambiar Contrase??a</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="../controlador/cerrar_session.php"><b><i class="fas fa-sign-out-alt"></i> Cerrar Sesi??n</b></a></li>
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
                <h1 class="p-2">Parametros</h1>
                <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-12">
                                <div class="card border-0 ">
                                    <div class="card-body">
                                        <form action="parametros.php" method="post">
                                            <div class="row mb-3">
                                                <div class="col-lg-6 col-sm-12">
                                                    <div class="form-floating mb-3 mb-md-0">
                                                        <input required class="form-control" id="inputFirstName" type="text" placeholder="simbolo" name="simbolo" value="<?php echo $simbolo_mone;?>"/>
                                                        <label for="inputFirstName">Simbolo monetario</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-sm-12">
                                                    <div class="form-floating mb-3 mb-md-0">
                                                        <input required class="form-control" type="text" placeholder="decimal" name="decimal" value="<?php echo $decimales_mone;?>"/>
                                                        <label for="inputFirstName">Decimales</label>
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
            </main>
<?php require_once 'pie.php'; ?>
