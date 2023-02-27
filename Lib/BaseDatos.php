<?php

namespace Lib;
use PDO;
use PDOException;
use PDOStatement;

class BaseDatos{
    public PDO $conexion;
    private mixed $resultado;
    private string $servidor;
    private string $usuario;
    private string $pass;
    private string $base_datos;


    function __construct(
    ){
        $this->servidor = $_ENV['DB_HOST'];
        $this->usuario = $_ENV['DB_USER'];
        $this->pass = $_ENV['DB_PASS'];
        $this->base_datos = $_ENV['DB_NAME'];
        $this->conexion = $this->conectar();
    }

    private function conectar(): PDO {
        try{
            $opciones = array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                PDO::MYSQL_ATTR_FOUND_ROWS => true
            );

            return new PDO("mysql:host={$this->servidor};dbname={$this->base_datos};charset=utf8",$this->usuario, $this->pass, $opciones);

        }catch(PDOException $e){

            echo 'Ha surgido Un error y no se puede conectar a la base de datOs. Detalle: '.$e->getMessage();
            exit;
        }
    }

    public function consulta(string $consultaSQL): void
    {
        $this->resultado=$this->conexion->query($consultaSQL);
    }

    public function consultaPrep(string $consultaSQL, array $parametros = null) : bool {
        $this->resultado = $this->conexion->prepare($consultaSQL);

        try {
            if ($parametros != null) {
                $this->resultado->execute($parametros);
            } else {
                $this->resultado->execute();
            }
            return true;
        } catch (PDOException $e) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }
            var_dump($e->getMessage());
            return false;
        }
    }

    public function transactionBegin() : void {
        $this->conexion->beginTransaction();
    }

    public function transactionCommit() : void {
        $this->conexion->commit();
    }

    public function extraer_registro(): false|array
    {
        return ($fila=$this->resultado->fetch(PDO::FETCH_ASSOC)) ? $fila:false;
    }

    public function extraer_todos(): array
    {
        return $this->resultado->fetchAll(PDO::FETCH_ASSOC);
    }

    public function filasAfectadas(): int
    {
        return $this->resultado->rowCount();
    }


    public function prepara($pre): false|PDOStatement
    {
        return $this -> conexion -> prepare($pre);
    }

}