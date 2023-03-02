<?php

namespace Lib;
use Exception;
use PHPMailer\PHPMailer\PHPMailer;

// Clase para enviar correos electrónicos
// Hace uso de la libreria PHPMailer, con las credenciales de Mailtrap
class Email
{
    private string $email;
    private string $token;

    public function __construct($email, $token)
    {
        $this->email = $email;
        $this->token = $token;
    }

    public function sendConfirmation($email){
        try {
            $phpmailer = new PHPMailer();
            $phpmailer->isSMTP();
            $phpmailer->Host = 'sandbox.smtp.mailtrap.io';
            $phpmailer->SMTPAuth = true;
            $phpmailer->Port = 2525;
            $phpmailer->Username = '8588877ce125cc';
            $phpmailer->Password = 'cc4af6038b6038';

            $phpmailer->setFrom('proyectoscursos@gmail.com','Proyecto Cursos');
            $phpmailer->addAddress("$email");

            $phpmailer->isHTML(TRUE);
            $phpmailer->CharSet="UTF-8";
            $phpmailer->Subject="Correo de confirmación";

            //Definimos el contenido del correo, con un enlace hacía el login
            $contenido="<html>";
            $contenido.="<p><Has>Hola has creado tu cuenta en Proyecto Cursos, solo debes confirmarla presionando el siguiente enlace</p>";
            $contenido.="<p>Presiona aquí: <a href="."http://localhost/proyectoCursos/public/usuario/confirmarCuenta/{$this->token}>Confirmar Cuenta</a></p>";
            $contenido.="</html>";

            $phpmailer->Body=$contenido;
            $phpmailer->send();
        } catch (Exception $e) {
            echo "Error al enviar el correo: {$phpmailer->ErrorInfo}";
        }

    }

}