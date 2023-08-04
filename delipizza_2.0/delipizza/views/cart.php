<?php
include '../components/connect.php';
include '../components/queries.php';


session_start();
if (isset($_SESSION)) {
  $user_id = $_SESSION['user_id'];
  $info_direc = hacerConsulta($user_id, "consultarDireccion");

  $datosDir = hacerConsulta($user_id, "traerDireccion");

}

if (!isset($user_id)) {
  $warning_msg[] = 'Inicie sesion para continuar';
  header('location:user-login.php');
}




$time = time();

// Add item to cart 
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $user_id = $_POST['user_id'];
  $product_name = $_POST['product_name'];
  $product_price = $_POST['product_price'];
  $quantity = $_POST['quantity'];

  $select_img = $pdo->prepare("SELECT img_Producto FROM producto WHERE ID_producto = ?");
  $select_img->execute([$product_id]);
  $product_img = $select_img->fetchColumn();

  // Check if item already exists in cart
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $product_price,
      'quantity' => $quantity,
      'img' => $product_img
    );
  }
}

// Remove item from cart
if (isset($_POST['remove_from_cart'])) {
  $product_id = $_POST['product_id'];
  unset($_SESSION['cart'][$product_id]);
  $warning_msg[] = "Producto eliminado del carrito";
}

// Update item quantity in cart
if (isset($_POST['update_quantity'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  $_SESSION['cart'][$product_id]['quantity'] = $quantity;
}




// Add direccion to database
if (isset($_POST['make_order'])) {

  $cart_items = $_SESSION['cart'];
  $direccion = $_POST['direccion'];
  $barrio = $_POST['barrio'];
  $localidad = $_POST['localidad'];
  $datos = array($user_id, $direccion, $barrio, $localidad);

  $query1 = "SELECT * FROM direccion WHERE ID_usuario= ? AND direccion = ? AND barrio=? AND localidad=?";
  $stmt = $pdo->prepare($query1);
  for ($i = 0; $i < count($datos); $i++) {
    $stmt->bindValue($i + 1, $datos[$i]);
  }
  $stmt->execute();
  if ($stmt->rowCount() == 0) {
    $query1 = "INSERT INTO direccion (ID_usuario,direccion, barrio, localidad) VALUES (?,?,?,?)";
    $stmt1 = $pdo->prepare($query1);
    for ($i = 0; $i < count($datos); $i++) {
      $stmt1->bindValue($i + 1, $datos[$i]);
    }
    $stmt1->execute();
    if ($stmt1->rowCount() > 0) {
      $success_msg[] = "Direccion añadida con éxito";
    }
  } else {
    $warning_msg[] = "Error al añadir la direccion, ya existe";
  }
}

// Calculate total price
$total_price = 0;


if (isset($_POST['make_order'])) {

  foreach ($cart_items as $product_id => $product) {
    $name = $product['name'];
    $price = $product['price'];
    $quantity = $product['quantity'];

    $total_price += $product['price'] * $product['quantity'];
    $datos = array($user_id, date("Y-m-d h:i:sa, $time"), "En preparacion");
    $query = "INSERT INTO orden (ID_usuario, fecha, estado_Orden) VALUES (?,?,?)";
    $stmt = $pdo->prepare($query);
    for ($i = 0; $i < count($datos); $i++) {
      $stmt->bindValue($i + 1, $datos[$i]);
    }
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      $order_id = $pdo->lastInsertId();
      $datos2 = array($order_id, $product_id, $quantity, $price);
      $query2 = "INSERT INTO detalles_orden ( ID_orden, ID_producto,cantidad,precio_unitario) VALUES (?, ?, ?, ?)";
      $stmt2 = $pdo->prepare($query2);
      for ($i = 0; $i < count($datos2); $i++) {
        $stmt2->bindValue($i + 1, $datos2[$i]);
      }
      $stmt2->execute();
      if ($stmt2->rowCount() > 0) {
        $success_msg[] = "Pedido realizado con éxito";
        // $select_email = $pdo->prepare("SELECT email_Usuario FROM usuario WHERE ID_Usuario = ?");
        // $select_email->execute([$user_id]);
        // $fetch_order_detail = $pdo->prepare("SELECT * FROM detalles_orden INNER JOIN orden ON detalles_orden.ID_Orden = orden.ID_Orden INNER JOIN producto ON detalles_orden.ID_producto = producto.ID_producto JOIN usuario ON orden.ID_usuario = usuario.ID_Usuario WHERE usuario.ID_Usuario = ?");
        // $fetch_order_detail->execute([$user_id]);
        // $total_ventas = $pdo->prepare("SELECT SUM(precio_unitario * cantidad) AS total_ventas FROM detalles_orden WHERE ID_orden = ?");
        // $total_ventas->execute([$order_id]);
        // $total_ventas = $total_ventas->fetch();
        // $email = $select_email->fetchColumn();

        // Generate the bill as a string
        // $bill = "Here is your bill:\n\n";
        // foreach ($fetch_order_detail as $order_detail) {
        //   $bill .= $order_detail['nombre_Producto'] . " - $" . $order_detail['precio_unitario'] . "\n";
        // }
        // $bill .= "\nTotal: $" . $total_ventas['total_ventas'];


        $_SESSION['cart'] = array();
        $total_price = 0;
      } else {
        $warning_msg[] = "Error al realizar el pedido";
      }
    }
  }
}

// Clear cart after saving to database



?>

<style>
  <?php include '../css/style.css'; ?>
</style>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Box Icon CDN list  -->
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

  <title>Carrito de compra</title>
</head>

<body>


  <?php
  include '../components/general-header.php';
  ?>

  <section class="cart-container-shopping">
    <div class="main-container-cart">
      <?php
      if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $product_id => $product) {
          $total_price += $product['price'] * $product['quantity'];
        }
      }

      // Display cart items and total price
      if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $product_id => $product) {
      ?>
          <div class="cart-item">

            <h4><?= $product['name'] ?></h4>
            <img src="../uploaded-img/productos/<?= $product['img']; ?>" alt="<?= $product['name'] ?>" width="100" style="float:right;">
            <p>Precio: $<?= number_format($product['price'])  ?></p>


            <form action="" method="POST">
              <input type="hidden" name="product_id" value="<?= $product_id ?>">

              <input type="number" name="quantity" min="0" max="20" value="<?= $product['quantity'] ?>">
              <input type="submit" name="update_quantity" value="Actualizar cantidad">
              <input type="submit" name="remove_from_cart" value="Eliminar del carrito">


            </form>

          </div>


        <?php
        }

        if (!empty($_SESSION['cart'])) {

        ?>
          <form action='' method='post'>
            <div class="input-field">
              <label for="direccion">Direccion</label>
              <input type="text" name="direccion" maxlength="30" required placeholder="Ingrese su direccion" oninput="this.value.replace(/\s/g,'') " required>


            </div>
            <div class="input-field">
              <label for="barrio">Barrio</label>
              <input type="text" name="barrio" maxlength="30" required placeholder="Ingrese su barrio" oninput="this.value.replace(/\s/g,'') " required>


              <div class="input-field">
                <label for="localidad">Localidad</label>
                <input type="text" name="localidad" maxlength="30" required placeholder="Ingrese su localidad" oninput="this.value.replace(/\s/g,'') " required>
              </div>
              <?php
              echo "Precio total: $" . number_format($total_price, 2); ?>
              <input type='submit' name='make_order' value='Hacer pedido'>
          </form>

      <?php

        } elseif (isset($_SESSION['cart']) && empty($_SESSION['cart'])) {
          echo "El carrito está vacío.";
        }
      }

      //Make order 
      ?>


    </div>
  </section>



  <!-- Sweet alert script -->
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <!-- Custom JS -->
  <script src="../js/script1.js"></script>
  <?php include '../components/alert.php'; ?>

</body>

</html>