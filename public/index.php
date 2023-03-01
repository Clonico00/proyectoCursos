<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Controllers\ApiPonenteController;
use Controllers\ApiUsuarioController;
use Dotenv\Dotenv;
use Lib\BaseDatos;
use Lib\Router;

//Añadir Dotenv
$dotenv = Dotenv::createImmutable(dirname(__DIR__ . '/'));//para acceder el contenido de .env
$dotenv->safeLoad();

Router::add('GET', 'auth', function () {
    require '../views/auth.php';
});
//Ruta para obtener todos los ponentes
Router::add('GET', 'ponente', function () {
    (new ApiPonenteController())->getAll();
});
//Ruta para obtener un ponente
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

//Ruta para obtener todos los usuarios
Router::add('GET', 'usuario', function () {
    (new ApiUsuarioController())->getAll();
});

//Ruta para obtener un usuario
Router::add('GET', 'usuario/:id', function (int $usuarioid) {
    (new ApiUsuarioController())->getUsuario($usuarioid);
});

//Ruta para crear usuario
Router::add('POST', 'usuario/register', function () {
    (new ApiUsuarioController())->crearUsuario();
});

//Ruta para login usuario
Router::add('POST', 'usuario/login', function () {
    (new ApiUsuarioController())->login();
});

//Ruta para borrar usuario
Router::add('DELETE', 'usuario/borrar/:id', function (int $usuarioid) {
    (new ApiUsuarioController())->borrarUsuario($usuarioid);
});

Router::dispatch();