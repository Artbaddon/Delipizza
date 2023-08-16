<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function

require_once 'MPDF/vendor/autoload.php';
include 'connect.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMAILER/Exception.php';
require 'PHPMAILER/PHPMailer.php';
require 'PHPMAILER/SMTP.php';

$ID_usuario = $user_id;

// Obtener los datos del usuario
$query = "SELECT * FROM usuario WHERE ID_usuario = $ID_usuario";
$stmt = $pdo->prepare($query);
$stmt->execute();
$usuario = $stmt->fetch();
$fecha = date('Y-m-d H:i:s');

$nombreUsuario = $usuario['nombre_Usuario'];
$emailUsuario = $usuario['email_Usuario'];

$ID_orden = rand(1000, 20000);

// Generate the HTML code for the bill
$html = "
<!DOCTYPE html>
<html>
<head>
  <meta charset='UTF-8'>
  <title>Factura #$ID_orden</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 14px;
    }
    table {
      border-collapse: collapse;
      width: 100%;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }
    th {
      background-color: #f2f2f2;
    }
    .total {
      font-weight: bold;
    }
  </style>
</head>
<body>
  <h1>Factura #$ID_orden</h1>
  <p>Fecha de la orden: $fecha</p>
  <p>Cliente: $nombreUsuario ($emailUsuario)</p>
  <table>
    <thead>
      <tr>
        <th>Producto</th>
        <th>Cantidad</th>
        <th>Precio unitario</th>
      </tr>
    </thead>
    <tbody>";
foreach ($cart_items as $product) {
  $name = $product['name'];
  $price = $product['price'];
  $quantity = $product['quantity'];

  $html .= "
      <tr>
        <td>$name</td>
        <td>$quantity</td>
        <td>$$price</td>
      </tr>";
}
$html .= "
      <tr>
        <td colspan='2' class='total'>Total</td>
        <td>$$total_price</td>
      </tr>
    </tbody>
  </table>
</body>
</html>";

// Generar el PDF utilizando mPDF
$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);
$pdfContent = $mpdf->Output('', 'S'); // Obtener el contenido del PDF como una cadena

// Enviar el PDF por correo electrónico
$mail = new PHPMailer(true);

try {
  // Configuración del servidor SMTP de Gmail
  $mail->isSMTP();
  $mail->Host = 'smtp.gmail.com';
  $mail->SMTPAuth = true;
  $mail->Username = 'delipizza50@gmail.com';
  $mail->Password = 'jutiaufzmbocsiyh';
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  $mail->Port = 587;

  // Destinatario y contenido del correo
  $mail->setFrom('delipizza50@gmail.com', 'delipizza');
  $mail->addAddress($emailUsuario, 'Destinatario');
  $mail->isHTML(true);
  $mail->Subject = 'delipizza facturacion';
  $mail->Body = 'Factura de orden #' . $ID_orden . ' Gracias por su compra!';

  // Adjuntar el PDF generado
  $mail->addStringAttachment($pdfContent, 'factura orden #' . $ID_orden . '.pdf');

  $mail->send();
  echo 'El correo ha sido enviado exitosamente.';
} catch (Exception $e) {
  echo 'Error al enviar el correo: ', $mail->ErrorInfo;
}
