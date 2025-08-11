<?php

include_once "config.php";
include_once "entidades/usuario.php";

$usuario = new Usuario();
$usuario->usuario = "German";
$usuario->clave = $usuario->encriptarClave("admin123");
$usuario->nombre = "German";
$usuario->apellido = "Luna";
$usuario->correo = "gerluna99@gmail.com";
$usuario->insertar();
echo "Usuario insertado.";
?>