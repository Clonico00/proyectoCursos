<?php

namespace Controllers;

use Lib\Pages;
use Lib\ResponseHttp;
use Lib\Security;
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

    public function login()
    {

    }

    public function getAll()
    {
        $usuarios = $this->usuario->getAll();
        $UsuarioArr = [];
        if (!empty($usuarios)) {
            $UsuarioArr["message"] = json_decode(ResponseHttp::statusMessage(202, 'OK'));
            $UsuarioArr["Usuarios"] = [];
            foreach ($usuarios as $fila) {
                $UsuarioArr["Usuarios"][] = $fila;
            }
        } else {
            $UsuarioArr["message"] = json_decode(ResponseHttp::statusMessage(400, 'No hay usuarios'));
            $UsuarioArr["Usuarios"] = [];
        }
        if ($UsuarioArr == []) {
            $response = json_encode(ResponseHttp::statusMessage(400, 'No hay usuarios'));
        } else {
            $response = json_encode($UsuarioArr);
        }
        $this->pages->render('read', ['response' => $response]);

    }

    public function getUsuario(int $usuarioid)
    {
        $ponentes = $this->usuario->getById($usuarioid);
        $UsuarioArr = [];
        if (!empty($usuarios)) {
            $UsuarioArr["message"] = json_decode(ResponseHttp::statusMessage(202, 'OK'));
            $UsuarioArr["Usuarios"] = [];
            foreach ($usuarios as $fila) {
                $UsuarioArr["Usuarios"][] = $fila;
            }
        } else {
            $UsuarioArr["message"] = json_decode(ResponseHttp::statusMessage(400, 'No hay usuarios'));
            $UsuarioArr["Usuarios"] = [];
        }
        if ($UsuarioArr == []) {
            $response = json_encode(ResponseHttp::statusMessage(400, 'No hay usuarios'));
        } else {
            $response = json_encode($UsuarioArr);
        }
        $this->pages->render('read', ['response' => $response]);
    }

    public function crearUsuario()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuario = new Usuario();
            $datos_usuario = json_decode(file_get_contents("php://input"));

            if ($usuario->validarDatos($datos_usuario) ) {

                $password = Security::encriptaPassw($datos_usuario->password);

                $usuario->setNombre($datos_usuario->nombre);
                $usuario->setApellidos($datos_usuario->apellidos);
                $usuario->setEmail($datos_usuario->email);
                $usuario->setPassword($password);
                $usuario->setRol($datos_usuario->rol);
                $usuario->setConfirmado($datos_usuario->confirmado);

                if ($usuario->insert()) {
                    http_response_code(201);
                    $response = json_decode(ResponseHttp::statusMessage(201, "Usuario creado correctamente"));
                } else {
                    http_response_code(400);
                    $response = json_decode(ResponseHttp::statusMessage(400, "No se ha podido crear el usuario"));
                }


            } else {
                http_response_code(400);
                $response = json_decode(ResponseHttp::statusMessage(400, "Error en los datos del usuario"));
            }
        }

        $this->pages->render("read", ['response' => json_encode($response)]);
        
    }

    public function actualizaUsuario(int $usuarioid)
    {
    }

    public function borrarUsuario(int $usuarioid)
    {

        $usuario = new Usuario();
        if ($usuario->delete($usuarioid)) {
            http_response_code(200);
            $response = json_decode(ResponseHttp::statusMessage(200, "Usuario borrado correctamente"));
        } else {

            http_response_code(404);
            $response = json_decode(ResponseHttp::statusMessage(404, "No se ha podido borrar el usuario"));
        }

        $this->pages->render("read", ['response' => json_encode($response)]);
    }

}