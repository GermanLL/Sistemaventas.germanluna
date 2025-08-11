<?php
// Iniciar la sesión solo si no hay una sesión activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir archivos de configuración y clases
include_once "config.php";
include_once "entidades/usuario.php";

$msg = "";

// Verificar si se ha enviado el formulario por POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = trim($_POST["txtUsuario"]);
    $clave = trim($_POST["txtClave"]);

    // Instanciar la clase de Usuario
    $entidadUsuario = new Usuario();

    // Obtener el usuario por su nombre de usuario
    $usuarioEncontrado = $entidadUsuario->obtenerPorUsuario($usuario);

    // Verificar si se encontró el usuario y si la clave es correcta
    if ($usuarioEncontrado && $entidadUsuario->verificarClave($clave, $entidadUsuario->clave)) {
        // La clave es correcta, iniciar sesión
        $_SESSION["nombre"] = $entidadUsuario->nombre;
        header("Location: index.php");
        exit;
    } else {
        // Usuario o clave incorrecto
        $msg = "Usuario o clave incorrecto";
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login</title>

    <!-- Estilos y fuentes -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image">
                                <img src="img/DogHounds.png" alt="" class="w-100 h-100 object-cover rounded-l-lg">
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Bienvenido</h1>
                                    </div>
                                    <form action="" method="POST" class="user">
                                        <?php if (!empty($msg)): ?>
                                            <div class="alert alert-danger" role="alert">
                                                <?php echo $msg; ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" id="txtUsuario" name="txtUsuario" aria-describedby="emailHelp" placeholder="Usuario">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" id="txtClave" name="txtClave" placeholder="Clave">
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="customCheck">
                                                <label class="custom-control-label" for="customCheck">Recordarme</label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Entrar
                                        </button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="forgot-password.php">¿Olvidaste la clave?</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="register.php">¡Crear una cuenta!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Scripts -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>

</html>

<!-- value="admin"
value="admin123" -->
