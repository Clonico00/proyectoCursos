<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Controllers\ApiPonenteController;
use Dotenv\Dotenv;
use Lib\BaseDatos;
use Lib\Router;

//Añadir Dotenv
$dotenv = Dotenv::createImmutable(dirname(__DIR__ . '/'));//para acceder el contenido de .env
$dotenv->safeLoad();

http_response_code(202);
$array = ["estado" => '202', "mensaje" => 'Estamos en el index principal'];
echo json_encode($array);

Router::add('GET', 'proyecto-cursos', function () {
    echo json_encode('saludo');
});

Router::add('GET', 'auth', function () {
    require '../views/auth.php';
});
Router::add('GET', 'ponente', function () {
    (new ApiPonenteController())->getAll();
});

Router::add('GET', 'ponente/:id', function (int $ponenteid) {
    (new ApiponenteController())->getPonente($ponenteid);
});

//Ruta para crear ponente
Router::add('POST', 'ponente/crear', function () {
    (new ApiponenteController())->crearPonente();
});

//Ruta para actualizar ponente
Router::add('PUT', 'ponente/actualizar/:id', function (int $ponenteid) {
    (new ApiponenteController())->actualizaPonente($ponenteid);
});


//Ruta para borrar ponente
Router::add('DELETE', 'ponente/borrar/:id', function (int $ponenteid) {
    (new ApiponenteController())->borrarPonente($ponenteid);
});


Router::dispatch();