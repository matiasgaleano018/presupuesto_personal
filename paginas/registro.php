<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Registro - Finanzas Personales</title>
        <link href="../css/styles.css" rel="stylesheet" />
        <link href="../css/styles2.css" rel="stylesheet" />
        <link rel="shortcut icon" href="../img/favicon.png" type="image/x-icon">
        <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    </head>
    <body class="bg-login">
    <?php
        require '../controlador/registrarse.php';
        require '../controlador/iniciar_sesion.php';
        /*
            Hecho el: ju.29.sep.2022
            Objetivo: mostrar mensajes de validación de registro de usuarios
        
        */
        if(!empty($_POST['nombre']) && !empty($_POST['apellido']) && !empty($_POST['usuario']) && !empty($_POST['contrasenha']) && !empty($_POST['confir_contrasenha'])){
            $patron_texto = "/^[a-zA-ZáéíóúÁÉÍÓÚäëïöüÄËÏÖÜàèìòùÀÈÌÒÙ\s]+$/";
            if(preg_match($patron_texto, $_POST['nombre'])){
                $nombre = trim($_POST['nombre']);
                if(preg_match($patron_texto, $_POST['apellido'])){
                    $apellido = trim($_POST['apellido']);
                    if(preg_match($patron_texto, $_POST['usuario'])){
                        $usuario = trim($_POST['usuario']);
                    }else{
                        echo '<script type="text/javascript">sweetAlert("¡Atención!","Caracteres invalidos en el campo Usuario","error")</script>';
                    }
                }else{
                    echo '<script type="text/javascript">sweetAlert("¡Atención!","Caracteres invalidos en el campo Apellido","error")</script>';
                }
            }else{
                echo '<script type="text/javascript">sweetAlert("¡Atención!","Caracteres invalidos en el campo Nombre","error")</script>';
            }
    
            if($_POST['contrasenha'] == $_POST['confir_contrasenha']){
                $contrasenha = md5($_POST['contrasenha']);
            }else{
                echo '<script type="text/javascript">sweetAlert("¡Atención!","La contraseña y su confirmación no coinciden","error")</script>';
            }
        }
        if(!empty($nombre) && !empty($apellido) && !empty($usuario) && !empty($contrasenha)){
            $ctrl = new Controlador();
            $sql = "SELECT id FROM usuarios WHERE usuario = '$usuario'";
            $us_existente = $ctrl->ctrl_seleccionar($sql);
            if(empty($us_existente)){
                $ctrl = new Controlador();
                $sql = "INSERT INTO `usuarios`(`nombre`, `apellido`, `usuario`, `contrasenha`, `creadoel`) VALUES ('$nombre', '$apellido','$usuario', '$contrasenha', NOW())";
                $resul = $ctrl->ctrl_ejecutar($sql);

                $sql = "SELECT id FROM usuarios WHERE usuario = '$usuario' AND contrasenha = '$contrasenha'";
                $us_id = $ctrl->ctrl_seleccionar($sql);

                if($resul == 'ok'){
                    //Insertar una cuenta por defecto => cuenta_[usuario]
                    $sql_param = "INSERT INTO `parametros`(`simbolo_monetario`, `decimales`, `usuario_id`, `creadoel`) VALUES ('USD', 2, $us_id, NOW())";
                    $resul = $ctrl->ctrl_ejecutar($sql_param);
                    //Iniciar sesion con el usuario recien creado
                    $ini = new IniciarSesion();
                    $ini->iniciar($us_id[0][0], $nombre." ".$apellido, $usuario);
                } 
            }else{
                echo '<script type="text/javascript">sweetAlert("¡Atención!","Ya existe en el sistema alguien con el nombre de usuario '.$usuario.'","error")</script>';
            }
        }
    ?>
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-7">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Registrarse</h3></div>
                                    
                                    <div class="card-body">
                                        <form action="registro.php" method="post">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    
                                                    <div class="form-floating mb-3 mb-md-0">
                                                        <input required class="form-control" id="inputFirstName" type="text" placeholder="Enter your first name" autofocus name="nombre"/>
                                                        <label for="inputFirstName">Nombre</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <input required class="form-control" id="inputLastName" type="text" placeholder="Enter your last name" name="apellido"/>
                                                        <label for="inputLastName">Apellido</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input required class="form-control" id="inputEmail" type="text" placeholder="juanperez123" name="usuario" />
                                                <label for="inputEmail">Nombre de Usuario</label>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3 mb-md-0">
                                                        <input class="form-control" id="inputPassword" type="password" placeholder="Create a password" name="contrasenha"/>
                                                        <label for="inputPassword">Contraseña</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3 mb-md-0">
                                                        <input required class="form-control" id="inputPasswordConfirm" type="password" placeholder="Confirm password" name="confir_contrasenha" />
                                                        <label for="inputPasswordConfirm">Confirmar contraseña</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-4 mb-0">
                                                <div class="d-grid"><input class="btn btn-primary btn-block" type="submit" value="Registrarse"></div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center py-3">
                                        <div class="small"><a href="login.php">¿Ya tienes una cuenta? Ir al login</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer">
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; finanzaspersonales.com 2022</div>
                            <div>
                                Desarrollado por
                                <a href="https://matias-galeano.netlify.app/" target="_blank">Matias Galeano</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="../js/scripts.js"></script>
        
    </body>
</html>
