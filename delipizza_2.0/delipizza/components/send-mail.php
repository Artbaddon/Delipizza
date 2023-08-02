<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


require 'PHPMAILER/Exception.php';
require 'PHPMAILER/PHPMailer.php';
require 'PHPMAILER/SMTP.php';

function gmail()
  {
    $data =
    [
      'title' => 'Nuevo mensaje'
    ];
 
    View::render('gmail', $data);
  }
 
  function post_gmail()
  {
    try {
      // Contenido del correo
      $asunto    = clean($_POST["asunto"]);
      $contenido = clean($_POST["contenido"]);
      $para      = clean($_POST["destinatario"]);
 
      if (!filter_var($para, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Dirección de correo electrónico no válida.');
      }
 
      // Intancia de PHPMailer
      $mail                = new PHPMailer();
   
      // Es necesario para poder usar un servidor SMTP como gmail
      $mail->isSMTP();
   
      // Si estamos en desarrollo podemos utilizar esta propiedad para ver mensajes de error
      //SMTP::DEBUG_OFF    = off (for production use) 0
      //SMTP::DEBUG_CLIENT = client messages 1 
      //SMTP::DEBUG_SERVER = client and server messages 2
      $mail->SMTPDebug     = SMTP::DEBUG_SERVER;
   
      //Set the hostname of the mail server
      $mail->Host          = 'smtp.gmail.com';
      $mail->Port          = 465; // o 587
   
      // Propiedad para establecer la seguridad de encripción de la comunicación
      $mail->SMTPSecure    = PHPMailer::ENCRYPTION_SMTPS; // tls o ssl para gmail obligado
   
      // Para activar la autenticación smtp del servidor
      $mail->SMTPAuth      = true;
 
      // Credenciales de la cuenta
      $email              = 'tucorreo@gmail.com';
      $mail->Username     = $email;
      $mail->Password     = 'tucontraseña';
   
      // Quien envía este mensaje
      $mail->setFrom($email, 'Roberto Orozco');
 
      // Si queremos una dirección de respuesta
      $mail->addReplyTo('replyto@panchos.com', 'Pancho Doe');
   
      // Destinatario
      $mail->addAddress($para, 'John Doe');
   
      // Asunto del correo
      $mail->Subject = $asunto;
 
      // Contenido
      $mail->IsHTML(true);
      $mail->CharSet = 'UTF-8';
      $mail->Body    = sprintf('<h1>El mensaje es:</h1><br><p>%s</p>', $contenido);
   
      // Texto alternativo
      $mail->AltBody = 'No olvides suscribirte a nuestro canal.';
 
      // Agregar algún adjunto
      //$mail->addAttachment(IMAGES_PATH.'logo.png');
   
      // Enviar el correo
      if (!$mail->send()) {
        throw new Exception($mail->ErrorInfo);
      }
 
      Flasher::success(sprintf('Mensaje enviado con éxito a %s', $para));
      Redirect::back();
 
    } catch (Exception $e) {
      Flasher::error($e->getMessage());
      Redirect::back();
    }
  }


