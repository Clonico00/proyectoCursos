<?php

namespace Controllers;

use Exception;
use Firebase\JWT\JWT;
use Lib\Email;
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
        $usuarios = $this->usuario->getById($usuarioid);
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
            if ($usuario->validarDatos($datos_usuario)) {

                $password = Security::encriptaPassw($datos_usuario->password);

                $usuario->setNombre($datos_usuario->nombre);
                $usuario->setApellidos($datos_usuario->apellidos);
                $usuario->setEmail($datos_usuario->email);
                $usuario->setPassword($password);
                $usuario->setRol($datos_usuario->rol);

                if ($usuario->insert()) {
                    $this->creartoken($usuario, $datos_usuario->email);
                    $email = new Email($datos_usuario->email, $usuario->getToken());
                    $email->sendConfirmation($datos_usuario->email);
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

    public function crearToken($usuario, $email)
    {

        $clave = Security::clavesecreta();
        $token = Security::crearToken($clave, [$email]);
        $token_seguro = JWT::encode($token, $clave, 'HS256');
        $usuario->setToken($token_seguro);
        echo $token["exp"];
        $usuario->guardaToken($token["exp"]);

        return $token_seguro;

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

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $usuario = new Usuario();
            $usuario_datos = json_decode(file_get_contents("php://input"));

            if ($usuario->validarDatosLogin($usuario_datos)) {
                if ($usuario->login($usuario_datos)) {
                    http_response_code(200);
                    $response = json_decode(ResponseHttp::statusMessage(200, "Usuario logeado correctamente"));
                } else {
                    http_response_code(404);
                    $response = json_decode(ResponseHttp::statusMessage(404, "Error en los datos del usuario, el usuario no existe o la contraseña es incorrecta o no esta confirmado"));
                }

            } else {
                http_response_code(404);
                $response = json_decode(ResponseHttp::statusMessage(404, "Error en los datos del usuario"));
            }


        } else {

            $response = json_decode(ResponseHttp::statusMessage(404, "Error el método de recogida de datos debe de ser POST"));
        }

        $this->pages->render("read", ['response' => json_encode($response)]);


    }

    public function confirmarCuenta(): void
    {
        $key = Security::claveSecreta();
        $usuario = new Usuario();
        $token = json_decode(file_get_contents("php://input"));

        try {
            $usuario->setToken($token->token);
            if ($usuario->checkTokenUser()) {
                $usuario->confirmarCuenta($token->token);
                echo json_encode([$usuario, ResponseHttp::statusMessage(200, "Cuenta confirmada correctamente. Ya puede iniciar sesión")]);

            } else {
                echo ResponseHttp::statusMessage(401, "Token invalidfddo o expirado. Registrese de nuevo.");
            }

        } catch (Exception $e) {
            echo ResponseHttp::statusMessage(401, "Token invalido o expirado. Registrese de nuevo.");
            echo $e->getMessage();
        }


    }

}