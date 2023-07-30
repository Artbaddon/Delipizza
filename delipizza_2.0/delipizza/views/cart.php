<?php
session_start();
include '../components/connect.php';
include '../components/queries.php';

$time = time();

// Add item to cart 
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $user_id = $_POST['user_id'];
  $product_name = $_POST['product_name'];
  $product_price = $_POST['product_price'];
  $quantity = $_POST['quantity'];



  // Check if item already exists in cart
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $product_price,
      'quantity' => $quantity
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

// Calculate total price
$total_price = 0;


if (isset($_POST['make_order'])) {

  $user_id = $_SESSION['user_id'];
  $cart_items = $_SESSION['cart'];

  foreach ($cart_items as $product_id => $product) {
    $name = $product['name'];
    $price = $product['price'];
    $quantity = $product['quantity'];

    $insert_order = $pdo->prepare("INSERT INTO orden (ID_Usuario_pedido , ProductoID, nombre_Producto, precio, cantidad) VALUES (?, ?, ?, ?, ?)");
    $insert_order->execute([$user_id, $product_id, $name, $price, $quantity]);
    $select_order = $pdo->prepare("SELECT * FROM orden WHERE ID_Usuario_pedido = ? ");
    $select_order->execute([$user_id]);
    $fetch_order = $select_order->fetch(PDO::FETCH_ASSOC);
    $order_id = $fetch_order['ID_orden'];
    if ($insert_order->rowCount() > 0) {
      $total_price += $product['price'] * $product['quantity'];

      $insert_order_details = $pdo->prepare("INSERT INTO detalles_orden (ID_Usuario_pedido , ID_orden, fecha_Orden, estado, total) VALUES (?, ?, ?, ?, ?)");
      $insert_order_details->execute([$user_id, $order_id, date("Y-m-d h:i:sa, $time"), "En preparacion", $total_price]);
      if ($insert_order_details->rowCount() > 0) {
        $success_msg[] = "Pedido realizado con éxito";
        $_SESSION['cart'] = array();
        $total_price = 0;
      } else {
        $warning_msg[] = "Error al realizar el pedido";
      }
    }
  }

  // Clear cart after saving to database


}
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
          echo "<form action='' method='post'>
          <input type='submit' name='make_order' value='Hacer pedido'>
        </form>";
          echo "Precio total: $" . number_format($total_price, 2);
        }else{
          echo "El carrito está vacío.";
        }
      } else {
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