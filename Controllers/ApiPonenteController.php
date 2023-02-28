<?php

namespace Controllers;

use Lib\Pages;
use Lib\ResponseHttp;
use Models\Ponente;

class ApiPonenteController
{
    private Ponente $ponente;
    private Pages $pages;

    public function __construct()
    {
        $this->ponente = new Ponente();
        $this->pages = new Pages();
    }

    public function getAll()
    {
        $ponentes = $this->ponente->getAll();
        $PonenteArr = [];
        if (!empty($ponentes)) {
            $PonenteArr["message"] = json_decode(ResponseHttp::statusMessage(202, 'OK'));
            $PonenteArr["Ponentes"] = [];
            foreach ($ponentes as $fila) {
                $PonenteArr["Ponentes"][] = $fila;
            }
        } else {
            $PonenteArr["message"] = json_decode(ResponseHttp::statusMessage(400, 'No hay ponentes'));
            $PonenteArr["Ponentes"] = [];
        }
        if ($PonenteArr == []) {
            $response = json_encode(ResponseHttp::statusMessage(400, 'No hay ponentes'));
        } else {
            $response = json_encode($PonenteArr);
        }
        $this->pages->render('read', ['response' => $response]);

    }

    public function getPonente($ponenteid)
    {
        $ponentes = $this->ponente->getById($ponenteid);
        $PonenteArr = [];
        if (!empty($ponentes)) {
            $PonenteArr["message"] = json_decode(ResponseHttp::statusMessage(202, 'OK'));
            $PonenteArr["Ponentes"] = [];
            foreach ($ponentes as $fila) {
                $PonenteArr["Ponentes"][] = $fila;
            }
        } else {
            $PonenteArr["message"] = json_decode(ResponseHttp::statusMessage(400, 'No hay ponentes'));
            $PonenteArr["Ponentes"] = [];
        }
        if ($PonenteArr == []) {
            $response = json_encode(ResponseHttp::statusMessage(400, 'No hay ponentes'));
        } else {
            $response = json_encode($PonenteArr);
        }
        $this->pages->render('read', ['response' => $response]);
    }


    public function crearPonente()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $ponente = new Ponente();
            $datos_ponente = json_decode(file_get_contents("php://input"));

            if ($ponente->validarDatos($datos_ponente)) {

                $ponente->setNombre($datos_ponente->nombre);
                $ponente->setApellidos($datos_ponente->apellidos);
                $ponente->setImagen($datos_ponente->imagen);
                $ponente->setTags($datos_ponente->tags);
                $ponente->setRedes($datos_ponente->redes);

                if ($ponente->insert()) {
                    http_response_code(200);
                    $response = json_decode(ResponseHttp::statusMessage(200, "Ponente creado correctamente"));
                } else {
                    http_response_code(404);
                    $response = json_decode(ResponseHttp::statusMessage(404, "No se ha podido crear el ponente"));
                }

            } else {
                http_response_code(404);
                $response = json_decode(ResponseHttp::statusMessage(404, "Error al validar los datos"));
            }


        } else {

            $response = json_decode(ResponseHttp::statusMessage(404, "Error el método de recogida de datos debe de ser POST"));
        }

        $this->pages->render("read", ['response' => json_encode($response)]);

    }

    public function actualizaPonente($ponenteid): void
    {

        if ($_SERVER['REQUEST_METHOD'] == 'PUT') {

            $datos_ponente = $this->ponente->getById($ponenteid);

            if ($datos_ponente !== false) {

                $ponente = Ponente::fromArray($datos_ponente);
                $datos = json_decode(file_get_contents("php://input"));

                if ($ponente->validarDatos($datos)) {

                    //reescribimos los datos del ponente
                    $ponente->setNombre($datos->nombre);
                    $ponente->setApellidos($datos->apellidos);
                    $ponente->setImagen($datos->imagen);
                    $ponente->setTags($datos->tags);
                    $ponente->setRedes($datos->redes);

                    if ($ponente->update($ponenteid)) {
                        http_response_code(200);
                        $response = json_decode(ResponseHttp::statusMessage(200, "Ponente actualizado"));
                    } else {
                        http_response_code(404);
                        $response = json_decode(ResponseHttp::statusMessage(404, "No se ha podido actualizar el ponente"));
                    }
                } else {
                    http_response_code(400);
                    $response = json_decode(ResponseHttp::statusMessage(400, "Algo ha salido mal"));
                }
            } else {
                http_response_code(404);
                $response = json_decode(ResponseHttp::statusMessage(404, "No ha encontrado el ponente"));
            }
        } else {
            $response = json_decode(ResponseHttp::statusMessage(400, "Método no permitido, se debe usar PUT"));
        }

        $this->pages->render('read', ['response' => json_encode($response)]);
    }

    public function borrarPonente($ponenteid)
    {

        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {

            $ponente = new Ponente();
            if ($ponente->delete($ponenteid)) {
                http_response_code(200);
                $response = json_decode(ResponseHttp::statusMessage(200, "Ponente borrado correctamente"));
            } else {

                http_response_code(404);
                $response = json_decode(ResponseHttp::statusMessage(404, "No se ha podido borrar el ponente"));
            }

        }

        $this->pages->render("read", ['response' => json_encode($response)]);
    }

}