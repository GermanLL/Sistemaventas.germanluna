<?php
// Clase Usuario.php

class Usuario
{
    private $idusuario;
    private $usuario;
    private $clave;
    private $nombre;
    private $apellido;
    private $correo;
    private $reset_token_hash;
    private $reset_token_expires_at;

    public function __construct()
    {
    }

    public function __get($atributo)
    {
        return $this->$atributo;
    }

    public function __set($atributo, $valor)
    {
        $this->$atributo = $valor;
        return $this;
    }

    public function cargarFormulario($request)
    {
        $this->idusuario = isset($request["id"]) ? $request["id"] : "";
        $this->usuario = isset($request["txtUsuario"]) ? $request["txtUsuario"] : "";
        $this->clave = isset($request["txtClave"]) && $request["txtClave"] != "" ? password_hash($request["txtClave"], PASSWORD_DEFAULT) : "";
        $this->nombre = isset($request["txtNombre"]) ? $request["txtNombre"] : "";
        $this->apellido = isset($request["txtApellido"]) ? $request["txtApellido"] : "";
        $this->correo = isset($request["txtCorreo"]) ? $request["txtCorreo"] : "";
    }

   public function insertar()
    {
        //Instancia la clase mysqli con el constructor parametrizado
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        //Arma la query
        $sql = "INSERT INTO usuarios (
                    usuario,
                    clave,
                    nombre,
                    apellido,
                    correo
                ) VALUES (
                    '" . $this->usuario . "',
                    '" . $this->clave . "',
                    '" . $this->nombre . "',
                    '" . $this->apellido . "',
                    '" . $this->correo . "'
                );";
        //Ejecuta la query
        if (!$mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        //Obtiene el id generado por la inserción
        $this->idusuario = $mysqli->insert_id;
        //Cierra la conexión
        $mysqli->close();
    }

    public function actualizar()
    {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        
        if ($this->clave != "") {
            $sql = "UPDATE usuarios SET
                usuario = ?,
                nombre = ?,
                apellido = ?,
                clave = ?,
                correo = ?
                WHERE idusuario = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("sssssi",
                $this->usuario,
                $this->nombre,
                $this->apellido,
                $this->clave,
                $this->correo,
                $this->idusuario
            );
        } else {
            $sql = "UPDATE usuarios SET
                usuario = ?,
                nombre = ?,
                apellido = ?,
                correo = ?
                WHERE idusuario = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("ssssi",
                $this->usuario,
                $this->nombre,
                $this->apellido,
                $this->correo,
                $this->idusuario
            );
        }

        if (!$stmt->execute()) {
            printf("Error en query: %s\n", $stmt->error);
        }
        $stmt->close();
        $mysqli->close();
    }

   public function eliminar()
    {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        $sql = "DELETE FROM usuarios WHERE idusuario = " . $this->idusuario;
        //Ejecuta la query
        if (!$mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }


    public function obtenerPorId()
    {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        $sql = "SELECT idusuario,
                        usuario,
                        clave,
                        nombre,
                        apellido,
                        correo
                FROM usuarios
                WHERE idusuario = " . $this->idusuario;
        if (!$resultado = $mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }

        //Convierte el resultado en un array asociativo
        if ($fila = $resultado->fetch_assoc()) {
            $this->idusuario = $fila["idusuario"];
            $this->usuario = $fila["usuario"];
            $this->clave = $fila["clave"];
            $this->nombre = $fila["nombre"];
            $this->apellido = $fila["apellido"];
            $this->correo = $fila["correo"];
        }
        $mysqli->close();
    }

   public function obtenerPorUsuario($usuario, $idusuario = "")
    {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        $sql = "SELECT idusuario,
                        usuario,
                        clave,
                        nombre,
                        apellido,
                        correo
                FROM usuarios
                WHERE usuario = '$usuario' AND idusuario <> '$idusuario'";

        if (!$resultado = $mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }

        //Convierte el resultado en un array asociativo
        if ($fila = $resultado->fetch_assoc()) {
            $this->idusuario = $fila["idusuario"];
            $this->usuario = $fila["usuario"];
            $this->clave = $fila["clave"];
            $this->nombre = $fila["nombre"];
            $this->apellido = $fila["apellido"];
            $this->correo = $fila["correo"];
            return true;
        } else {
            return false;
        }
        $mysqli->close();

    }
    
    public function obtenerPorCorreo($correo, $idusuario = "")
    {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        $sql = "SELECT idusuario,
                        usuario,
                        clave,
                        nombre,
                        apellido,
                        correo
                FROM usuarios
                WHERE correo = '$correo' AND idusuario <> '$idusuario'";
        if (!$resultado = $mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }

        //Convierte el resultado en un array asociativo
        if ($fila = $resultado->fetch_assoc()) {
            $this->idusuario = $fila["idusuario"];
            $this->usuario = $fila["usuario"];
            $this->clave = $fila["clave"];
            $this->nombre = $fila["nombre"];
            $this->apellido = $fila["apellido"];
            $this->correo = $fila["correo"];
            return true;
        } else {
            return false;
        }
        $mysqli->close();

    }

    public function obtenerTodos()
    {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        $sql = "SELECT idusuario, usuario, clave, nombre, apellido, correo FROM usuarios";
        if (!$resultado = $mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }

        $aResultado = array();
        if ($resultado) {
            //Convierte el resultado en un array asociativo
            while ($fila = $resultado->fetch_assoc()) {
                $entidadAux = new Usuario();
                $entidadAux->idusuario = $fila["idusuario"];
                $entidadAux->usuario = $fila["usuario"];
                $entidadAux->clave = $fila["clave"];
                $entidadAux->nombre = $fila["nombre"];
                $entidadAux->apellido = $fila["apellido"];
                $entidadAux->correo = $fila["correo"];
                $aResultado[] = $entidadAux;
            }
        }
        return $aResultado;
    }

    public function encriptarClave($clave)
    {
        $claveEncriptada = password_hash($clave, PASSWORD_DEFAULT);
        return $claveEncriptada;
    }

    public function verificarClave($claveIngresada, $claveHashAlmacenada)
    {
        return password_verify($claveIngresada, $claveHashAlmacenada);
    }

    public function obtenerPorResetToken($resetTokenHash)
    {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        $sql = "SELECT idusuario, usuario, clave, reset_token_expires_at, nombre, apellido, correo FROM usuarios WHERE reset_token_hash = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $resetTokenHash);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $fila = $resultado->fetch_assoc();
        $stmt->close();
        $mysqli->close();
        return $fila;
    }

    public function actualizarPassword($idusuario, $password_hash)
    {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        $sql = "UPDATE usuarios SET clave = ? WHERE idusuario = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("si", $password_hash, $idusuario);
        $stmt->execute();
        $stmt->close();
        $mysqli->close();
    }

    public function guardarResetToken($token_hash, $expiry)
    {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        $sql = "UPDATE usuarios SET reset_token_hash = ?, reset_token_expires_at = ? WHERE idusuario = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ssi", $token_hash, $expiry, $this->idusuario);
        $stmt->execute();
        $stmt->close();
        $mysqli->close();
    }

    public function limpiarResetToken($idusuario)
    {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        $sql = "UPDATE usuarios SET reset_token_hash = NULL, reset_token_expires_at = NULL WHERE idusuario = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $idusuario);
        $stmt->execute();
        $stmt->close();
        $mysqli->close();
    }
}
