<?php

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Acceso denegado");
}

$token = $_POST["token"] ?? "";
$password = $_POST["password"] ?? "";
$password_confirmation = $_POST["password_confirmation"] ?? "";

if (empty($token) || empty($password) || empty($password_confirmation)) {
    die("Formulario incompleto.");
}

if ($password !== $password_confirmation) {
    die("Las contraseñas no coinciden.");
}

$password_hash = password_hash($password, PASSWORD_DEFAULT);
$token_hash = hash("sha256", $token);

require_once "config.php";
require_once "entidades/usuario.php";

$entidadUsuario = new Usuario();
$usuario = $entidadUsuario->obtenerPorResetToken($token_hash);

if ($usuario === null) {
    die("Token inválido.");
}

// Llama al nuevo método para actualizar la contraseña en tu clase Usuario
$entidadUsuario->actualizarPassword($usuario['idusuario'], $password_hash);

// Opcional pero recomendado: Limpia el token para que no pueda ser reutilizado
$entidadUsuario->limpiarResetToken($usuario['idusuario']);

// Redirige al usuario a la página de login con un mensaje de éxito
header("Location: login.php?status=password_updated");
exit;
?>