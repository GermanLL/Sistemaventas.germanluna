<?php
// Archivo: send-password-reset.php

// Incluir las clases necesarias
include_once "config.php";
include_once "entidades/usuario.php";
// Asumiendo que usas PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/phpmailer/src/PHPMailer.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    $entidadUsuario = new Usuario();
    $usuario = $entidadUsuario->obtenerPorCorreo($email);

    if ($usuario) { // Solo si el usuario existe en la base de datos
        // Generar un token seguro
        $token = bin2hex(random_bytes(16));
        $token_hash = hash("sha256", $token);
        $expiry = date("Y-m-d H:i:s", time() + 3600); // El token expira en 1 hora

        // Guardar el token en la base de datos
        $entidadUsuario->guardarResetToken($token_hash, $expiry); // Método a crear en tu clase Usuario

        // Enviar el correo electrónico
        $mail = new PHPMailer(true);
        try {
            // Configuración del servidor SMTP (usar la que te proporcioné antes)
            // ...
            $mail->setFrom("german@germanluna.com", "Mi sitio online");
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Restablecer contraseña';
            $mail->Body    = 'Haz clic en el siguiente enlace para restablecer tu clave: <br> <a href="https://darkturquoise-monkey-960711.hostingersite.com/reset-password.php?token=' . $token . '">Restablecer clave</a>';
            $mail->send();
            echo 'Mensaje de restablecimiento enviado.';
        } catch (Exception $e) {
            echo "El mensaje no pudo ser enviado. Error: {$mail->ErrorInfo}";
        }
    } else {
        // Por seguridad, no decimos si el email existe o no.
        echo "Si la dirección de correo electrónico está en nuestra base de datos, recibirás un correo para restablecer tu contraseña.";
    }
}
?>