<?php

namespace Controllers;

use Lib\Pages;
use Models\Usuario;

class ApiUsuarioController
{
    private Usuario $usuario;
    private Pages $pages;

    public function __construct()
    {
        $this->usuario = new Usuario();
        $this->pages = new Pages();
    }

    public function login(){

    }

    public function registro(){

    }

}