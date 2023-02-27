<?php

namespace Models;

use Lib\BaseDatos;
use PDOException;

class Usuario extends BaseDatos
{

    private string $id;
    private string $nombre;
    private string $apellidos;
    private string $email;
    private string $password;
    private string $rol;
    private string $confirmado;
    private string $token;
    private string $token_exp;

    //Constructor que inicia la conexión
    public function __construct()
    {
        parent::__construct();
    }

    public static function fromArray(array $data): Usuario
    {
        return new Usuario (
            $data['id'],
            $data['nombre'],
            $data['apellidos'],
            $data['email'],
            $data['password'],
            $data['rol'],
            $data['confirmado']
        );
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
        return $this;
    }

    public function getApellidos()
    {
        return $this->apellidos;
    }

    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function getRol()
    {
        return $this->rol;
    }

    public function setRol($rol)
    {
        $this->rol = $rol;
        return $this;
    }

    public function getConfirmado()
    {
        return $this->confirmado;
    }

    public function setConfirmado($confirmado)
    {
        $this->confirmado = $confirmado;
        return $this;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    public function getToken_exp()
    {
        return $this->token_exp;
    }

    public function setToken_exp($token_exp)
    {
        $this->token_exp = $token_exp;
        return $this;
    }

    //Método para registrar un usuario

    public function registrarUsuario(): bool
    {
        try {
            $sql = "INSERT INTO usuarios (nombre, apellidos, email, password, rol, confirmado, token, token_exp) VALUES (:nombre, :apellidos, :email, :password, :rol, :confirmado, :token, :token_exp)";
            $consulta = $this->conexion->prepare($sql);
            $consulta->bindParam(':nombre', $this->nombre);
            $consulta->bindParam(':apellidos', $this->apellidos);
            $consulta->bindParam(':email', $this->email);
            $consulta->bindParam(':password', $this->password);
            $consulta->bindParam(':rol', $this->rol);
            $consulta->bindParam(':confirmado', $this->confirmado);
            $consulta->bindParam(':token', $this->token);
            $consulta->bindParam(':token_exp', $this->token_exp);
            if ($consulta->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Error al registrar el usuario: " . $e->getMessage();
            return false;
        }


    }

    //Método para comprobar si el email ya existe en la base de datos
    public function comprobarEmail(): bool
    {
        try {
            $sql = "SELECT * FROM usuarios WHERE email = :email";
            $consulta = $this->conexion->prepare($sql);
            $consulta->bindParam(':email', $this->email);
            $consulta->execute();
            if ($consulta->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Error al comprobar el email: " . $e->getMessage();
            return false;
        }
    }

    //Método para loguear un usuario
    public function loginUsuario(): bool
    {
        try {
            $sql = "SELECT * FROM usuarios WHERE email = :email AND password = :password";
            $consulta = $this->conexion->prepare($sql);
            $consulta->bindParam(':email', $this->email);
            $consulta->bindParam(':password', $this->password);
            $consulta->execute();
            if ($consulta->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Error al loguear el usuario: " . $e->getMessage();
            return false;
        }
    }

    //Método para obtener los datos de un usuario por su email
    public function obtenerUsuarioPorEmail(): bool | Usuario
    {
        try {
            $sql = "SELECT * FROM usuarios WHERE email = :email";
            $consulta = $this->conexion->prepare($sql);
            $consulta->bindParam(':email', $this->email);
            $consulta->execute();
            $usuario = $consulta->fetch();
            return Usuario::fromArray($usuario);
        } catch (PDOException $e) {
            echo "Error al obtener el usuario por email: " . $e->getMessage();
            return false;
        }
    }
}