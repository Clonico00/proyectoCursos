<?php

namespace Models;

use Lib\BaseDatos;
use PDOException;

class Ponente extends BaseDatos
{

    private string $id;
    private string $nombre;
    private string $apellidos;
    private string $imagen;
    private string $tags;
    private string $redes;

    public function __construct()
    {
        parent::__construct();
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

    public function getImagen()
    {
        return $this->imagen;
    }

    public function setImagen($imagen)
    {
        $this->imagen = $imagen;
        return $this;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function setTags($tags)
    {
        $this->tags = $tags;
        return $this;
    }

    public function getRedes()
    {
        return $this->redes;
    }

    public function setRedes($redes)
    {
        $this->redes = $redes;
        return $this;
    }

    //Método para obtener todos los ponentes

    public function getAll()
    {
        try {
            $sql = "SELECT * FROM proyectocursos.ponentes";
            $query = $this->conexion->prepare($sql);
            $query->execute();
            $result = $query->fetchAll();
            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    //Método para obtener un ponente por su id

    public function getById($id)
    {
        try {
            $sql = "SELECT * FROM proyectocursos.ponentes WHERE id = :id";
            $query = $this->conexion->prepare($sql);
            $query->execute(['id' => $id]);
            $result = $query->fetch();
            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    //Método para insertar un ponente

    public function insert()
    {
        try {
            $sql = "INSERT INTO proyectocursos.ponentes (nombre, apellidos, imagen, tags, redes) VALUES (:nombre, :apellidos, :imagen, :tags, :redes)";
            $query = $this->conexion->prepare($sql);
            $query->execute([
                'nombre' => $this->nombre,
                'apellidos' => $this->apellidos,
                'imagen' => $this->imagen,
                'tags' => $this->tags,
                'redes' => $this->redes
            ]);
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    //Método para modificar un ponente

    public function update($ponenteid)
    {
        try {
            $sql = "UPDATE proyectocursos.ponentes SET nombre = :nombre, apellidos = :apellidos, imagen = :imagen, tags = :tags, redes = :redes WHERE id = :id";
            $query = $this->conexion->prepare($sql);
            $query->execute([
                'nombre' => $this->nombre,
                'apellidos' => $this->apellidos,
                'imagen' => $this->imagen,
                'tags' => $this->tags,
                'redes' => $this->redes,
                'id' => $ponenteid
            ]);
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    //Método para borrar los datos del ponente

    public function delete($ponenteid)
    {
        try {
            $sql = "DELETE FROM proyectocursos.ponentes WHERE id = :id";
            $query = $this->conexion->prepare($sql);
            $query->execute(['id' => $ponenteid]);
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public static function validarDatos($data): mixed
    {
        //haciendo uso de las validaciones y sanitaziones validamos todos los datos dentro de $data y devolvemos true si todo es
        // correcto y el error si no lo es
        $data->nombre = filter_var($data->nombre, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z0-9\s]+$/")));
        if (htmlspecialchars($data->nombre, ENT_QUOTES, 'UTF-8') && $data->nombre) {
           $data->apellidos = filter_var($data->apellidos, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z0-9\s]+$/")));
            if (htmlspecialchars($data->apellidos, ENT_QUOTES, 'UTF-8') && $data->apellidos) {
                $data->imagen = filter_var($data->imagen, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z0-9\s]+$/")));
                if (htmlspecialchars($data->imagen, ENT_QUOTES, 'UTF-8') && $data->imagen) {
                    $data->tags = filter_var($data->tags, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z0-9\s]+$/")));
                    if (htmlspecialchars($data->tags, ENT_QUOTES, 'UTF-8') && $data->tags) {
                        $data->redes = filter_var($data->redes, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z0-9\s]+$/")));
                        if (htmlspecialchars($data->redes, ENT_QUOTES, 'UTF-8') && $data->redes) {
                            return true;
                        } else {
                            return "Error en el campo redes";
                        }
                    } else {
                        return "Error en el campo tags";
                    }
                } else {
                    return "Error en el campo imagen";
                }
            } else {
                return "Error en el campo apellidos";
            }
        } else {
            return "Error en el campo nombre";
        }
    }


    public static function fromArray(array $data): Ponente
    {
        return new Ponente (
            $data['id'],
            $data['nombre'],
            $data['apellidos'],
            $data['imagen'],
            $data['tags'],
            $data['redes']
        );
    }
}