<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Iniciamos la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Argentina/Buenos_Aires');
//date_default_timezone_set("America/Bogota");

class Config {
    const BBDD_HOST = "localhost";
    const BBDD_PORT= "3606";
    const BBDD_USUARIO = "u188377931_Sistemaventas";
    const BBDD_CLAVE = "Namreg2025$";
    const BBDD_NOMBRE = "u188377931_Sistemaventas";
}

?>