<?php

require_once 'MPDF/vendor/autoload.php';

include 'connect.php';
include 'queries.php';


// Generate the HTML code for the bill
$html = "
<!DOCTYPE html>
<html>
<head>
  <meta charset='UTF-8'>
  <title>Factura #$order_id</title>
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
  <h1>Factura #$order_id</h1>
  <p>Fecha de la orden: $order_date</p>
  <p>Cliente: $customer_name ($customer_email)</p>
  <table>
    <thead>
      <tr>
        <th>Producto</th>
        <th>Cantidad</th>
        <th>Precio unitario</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>";
foreach ($products as $product) {
  $name = $product['name'];
  $quantity = $product['quantity'];
  $price = $product['price'];
  $total_price = $quantity * $price;
  $html .= "
      <tr>
        <td>$name</td>
        <td>$quantity</td>
        <td>\$$price</td>
        <td>\$$total_price</td>
      </tr>";
}
$html .= "
      <tr>
        <td colspan='3' class='total'>Total</td>
        <td>\$$total</td>
      </tr>
    </tbody>
  </table>
</body>
</html>";

// Generate the PDF file using mpdf

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);
$mpdf->output("factura_$order_id.pdf","D");
?>