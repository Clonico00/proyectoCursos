<?php

namespace Lib;

use Dotenv\Dotenv;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PDOException;

class Security{

    final public static function clavesecreta(){
        $dotenv=Dotenv::createImmutable(dirname(__DIR__.'/'));
        $dotenv->load();
        return $_ENV['SECRET_KEY'];
    }

    final public static function encriptaPassw(string $passw): string {
        $passw= password_hash($passw, PASSWORD_DEFAULT);
        return $passw;
    }

    final public static function validaPassw(string $passw, string $passwash): bool {
        if (password_verify($passw, $passwash)) {
            return true;
        }
        else {
            echo "contraseña incorrecta";
            return false;
        }
    }

    final public static function crearToken(string $key, array $data) {
        $time= strtotime("now");
        $token= array(
            "iat"=>$time,
            "exp"=>$time + 3600,
            "data"=>$data
        );
        return JWT::encode($token, $key, 'HS256') ;
    }



}
?>