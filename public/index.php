<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Lib\BaseDatos;
use Lib\Router;
//Añadir Dotenv
$dotenv = Dotenv::createImmutable(dirname(__DIR__. '/'));//para acceder el contenido de .env
$dotenv->safeLoad();

/*1. Crea en el index.php una ruta POST para registrar usuarios llamada /usuarios/register
Tendremos más de una ruta POST para usuarios y por tanto es preciso diferenciar*/

Router::add('POST', 'usuarios/register', function () {

});