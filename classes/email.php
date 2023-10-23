<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email{

    public $email;
    public $nombre;
    public $token;

    public function __construct($nombre, $email, $token)
    {   
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;

    }

    public function enviarConfirmacion(){
        //CREAR OBJETO DE EMAIL
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('cuentas@Appsalon.com');
        $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');
        $mail->Subject = 'Correo de confirmacion de cuenta AppSalon';

        //set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola" . $this->nombre . ".</strong> Has credo una cuenta en AppSalon.</p>";
        $contenido .= "<p>Confirma tu cuenta, dando click en el enlace siguiente.</p>";
        $contenido .= "<p>Da clic aqui!: <a href='". $_ENV['APP_URL'] ."/confirmar-cuenta?token=". $this->token ."'>CONFIRMAR CUENTA</a></p>";
        $contenido .= "<p>Si tu no realizaste ningun movimiento en AppSalon, puedes ignorar el mensaje.</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        //Enviar email
        $mail->send();


    }

    public function enviarInstruccion(){
        //CREAR OBJETO DE EMAIL
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('cuentas@Appsalon.com');
        $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');
        $mail->Subject = 'Instrucciones de recuperacion de password - AppSalon';

        //set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola" . $this->nombre . ".</strong> Has solicitado resetear tu password en AppSalon.</p>";
        $contenido .= "<p>Para crear una nueva password, da click en el enlace siguiente.</p>";
        $contenido .= "<p>Da clic aqui!: <a href='". $_ENV['APP_URL'] ."/recuperar?token=". $this->token ."'>Reestablecer password</a></p>";
        $contenido .= "<p>Si tu no realizaste ningun movimiento en AppSalon, puedes ignorar el mensaje.</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        //Enviar email
        $mail->send();

    }
}


