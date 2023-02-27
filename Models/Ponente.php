<?php

namespace Models;

use Lib\BaseDatos;

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
    /*generamos getter and setter*/
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