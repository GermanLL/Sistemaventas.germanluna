<?php

$token = $_GET["token"] ?? "";

if (empty($token)) {
    // Redirige al usuario a una página de error o a la de inicio
    header("Location: login.php?error=token_missing");
    exit;
}

$token_hash = hash("sha256", $token);

require_once "config.php";
require_once "entidades/usuario.php";

$entidadUsuario = new Usuario();
$usuario = $entidadUsuario->obtenerPorResetToken($token_hash);

if ($usuario === null) {
    header("Location: login.php?error=token_invalid");
    exit;
}

if (strtotime($usuario["reset_token_expires_at"]) <= time()) {
    header("Location: login.php?error=token_expired");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Restablecer Contraseña</title>

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
    </head>

<body class="bg-gradient-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-password-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-2">Restablecer Contraseña</h1>
                                    </div>
                                    
                                    <form method="post" action="process-password-reset.php">
                                        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                                        <div class="form-group">
                                            <label for="password">Nueva contraseña</label>
                                            <input type="password" class="form-control form-control-user" id="password" name="password" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="password_confirmation">Repetir Contraseña</label>
                                            <input type="password" class="form-control form-control-user" id="password_confirmation" name="password_confirmation" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Enviar
                                        </button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="register.php">¡Crea una cuenta!</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="login.php">¿Ya tienes una cuenta? ¡Inicia sesión!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
       <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <script src="js/sb-admin-2.min.js"></script>
    </body>
</html>
