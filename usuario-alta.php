<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ... el resto de tu código
?>


<?php
// Asegúrate de incluir tu clase Usuario y tu archivo de configuración (Config.php)

require_once "entidades/usuario.php";
include_once "config.php";
 // Si tienes un archivo de configuración para la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibe los datos del formulario
    $usuario = new Usuario();
    $usuario->cargarFormulario($_REQUEST);

    // Valida que las claves coincidan
    $clave = $_REQUEST["txtClave"];
    $claveRepetida = $_REQUEST["txtClaveRepetida"];

   if ($clave === $claveRepetida) {
    if ($usuario->obtenerPorUsuario($usuario->usuario) || $usuario->obtenerPorCorreo($usuario->correo)) {
        header("Location: register.php?error=exists");
        exit;
    } else {
        // Redirige a la página de registro con un parámetro de éxito
        $usuario->insertar();
        header("Location: register.php?status=success");
        exit;
    }
} else {
    header("Location: register.php?error=password_mismatch");
    exit;
}
}
?>
