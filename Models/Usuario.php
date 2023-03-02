<?php

namespace Models;

use Lib\BaseDatos;
use Lib\Security;
use PDO;
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

    //-----------------------------------------------------------------------------------------------------------------

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

    public function getById($usuarioid): array
    {
        try {
            $sql = "SELECT * FROM proyectocursos.usuarios WHERE id = :id";
            $consulta = $this->conexion->prepare($sql);
            $consulta->bindParam(':id', $usuarioid);
            $consulta->execute();
            $usuario = $consulta->fetch();
            return $usuario;
        } catch (PDOException $e) {
            echo "Error al obtener el usuario: " . $e->getMessage();
            return [];
        }
    }

    public function getDBToken(): array
    {
        $sql = "SELECT token FROM proyectocursos.usuarios WHERE email = :email";
        $consulta = $this->conexion->prepare($sql);
        $consulta->bindParam(':email', $this->email);
        $consulta->execute();
        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll(): array
    {
        try {
            $sql = "SELECT * FROM proyectocursos.usuarios";
            $consulta = $this->conexion->prepare($sql);
            $consulta->execute();
            $usuarios = $consulta->fetchAll();
            return $usuarios;
        } catch (PDOException $e) {
            echo "Error al obtener los usuarios: " . $e->getMessage();
            return [];
        }
    }

    public function getByEmail($usuarioemail): bool|object
    {
        try {
            $sql = "SELECT * FROM proyectocursos.usuarios WHERE email = :email";
            $consulta = $this->conexion->prepare($sql);
            $consulta->bindParam(':email', $usuarioemail);
            $consulta->execute();
            if ($consulta->rowCount() > 0) {
                return $consulta->fetch(PDO::FETCH_OBJ);
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Error al obtener el usuario por email: " . $e->getMessage();
            return false;
        }
    }

    public function insert(): bool
    {
        try {
            $this->confirmado = 0;

            $sql = "INSERT INTO proyectocursos.usuarios (nombre, apellidos, email, password, rol, confirmado) VALUES (:nombre, :apellidos, :email, :password, :rol, :confirmado)";
            $consulta = $this->conexion->prepare($sql);
            $consulta->bindParam(':nombre', $this->nombre);
            $consulta->bindParam(':apellidos', $this->apellidos);
            $consulta->bindParam(':email', $this->email);
            $consulta->bindParam(':password', $this->password);
            $consulta->bindParam(':rol', $this->rol);
            $consulta->bindParam(':confirmado', $this->confirmado);

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

    public function update($usuarioid)
    {
        try {
            $sql = "UPDATE proyectocursos.usuarios SET nombre = :nombre, apellidos = :apellidos, email = :email, password = :password, rol = :rol, confirmado = :confirmado, token = :token, token_exp = :token_exp WHERE id = :id";
            $consulta = $this->conexion->prepare($sql);
            $consulta->bindParam(':id', $usuarioid);
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
            echo "Error al actualizar el usuario: " . $e->getMessage();
            return false;
        }
    }

    public function delete($usuarioid): bool
    {
        try {
            $sql = "DELETE FROM proyectocursos.usuarios WHERE id = :id";
            $consulta = $this->conexion->prepare($sql);
            $consulta->bindParam(':id', $usuarioid);
            if ($consulta->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Error al eliminar el usuario: " . $e->getMessage();
            return false;
        }
    }

    public function login($data): bool
    {
        try {
            $usuario = $this->getByEmail($data->email);
            if ($usuario and $usuario->confirmado == 1) {
                $password = Security::validaPassw($data->password, $usuario->password);
                if ($password) {
                    return true;
                }
            } else {
                return false;
            }
            return false;
        } catch (PDOException $e) {
            echo "Error al loguear el usuario: " . $e->getMessage();
            return false;
        }

    }

    public function guardaToken($fechaExp): bool
    {

        try {
            $sql = "UPDATE proyectocursos.usuarios SET token = :token, token_exp = :token_exp WHERE email = :email";
            $consulta = $this->conexion->prepare($sql);
            $consulta->bindParam(':token', $this->token);
            $consulta->bindParam(':email', $this->email);
            $consulta->bindParam(':token_exp', $fechaExp);
            if ($consulta->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Error al guardar el token: " . $e->getMessage();
            return false;
        }

    }

    public function confirmarCuenta($token): bool
    {
        try {
            $sql = "UPDATE proyectocursos.usuarios SET confirmado = 1 WHERE token = :token";
            $consulta = $this->conexion->prepare($sql);
            $consulta->bindParam(':token', $token);
            if ($consulta->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Error al confirmar la cuenta: " . $e->getMessage();
            return false;
        }
    }

    public function checkTokenUser(){
        $sql = "SELECT * FROM proyectocursos.usuarios WHERE token = :token";
        $consulta = $this->conexion->prepare($sql);
        $consulta->bindParam(':token', $this->token);
        $consulta->execute();
        if ($consulta->rowCount() > 0) {
            return $consulta->fetch(PDO::FETCH_OBJ);
        } else {
            return false;
        }
    }

    public function checkToken($token): bool
    {
        return $this->getDBToken()["token"] == $token;
    }

    public function comprobarEmail($usuarioemail): bool
    {
        try {
            $sql = "SELECT * FROM proyectocursos.usuarios WHERE email = :email";
            $consulta = $this->conexion->prepare($sql);
            $consulta->bindParam(':email', $usuarioemail);
            $consulta->execute();
            $usuario = $consulta->fetch();
            if ($usuario) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Error al comprobar el email: " . $e->getMessage();
            return false;
        }
    }

    public function validarDatos($data): bool
    {
        if (empty($data->nombre) || !preg_match('/^[a-zA-Z0-9]+$/', $data->nombre)) {
            return false;
        }
        if (empty($data->apellidos) || !preg_match('/^[a-zA-Z0-9]+$/', $data->apellidos)) {
            return false;
        }
        if (empty($data->email) || !preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $data->email)) {
            return false;
        }
        if (empty($data->rol) || !preg_match('/^[a-zA-Z0-9]+$/', $data->rol)) {
            return false;
        }
        return true;
    }

    public function validarDatosLogin($data): bool
    {
        if (empty($data->email) || !preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $data->email)) {
            return false;
        }
        //la contraseña debe tener:
        //    Al menos una letra minúscula (a-z)
        //    Al menos una letra mayúscula (A-Z)
        //    Al menos un número (\d)
        //    Al menos un caracter especial común ([!@#$%^&*()-_=+\[]{}|;:,.<>/?])
        if (empty($data->password) || !preg_match('/^[a-zA-Z0-9!@#$%^&*()_+{}[\]:;<>,.?\/\\-]+$/', $data->password)) {
            return false;
        }
        return true;
    }



}