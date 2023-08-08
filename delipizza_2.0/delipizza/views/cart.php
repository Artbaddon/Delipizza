<?php
include '../components/connect.php';
include '../components/queries.php';



session_start();
if (isset($_SESSION)) {
  $user_id = $_SESSION['user_id'];

  $datosDir = hacerConsulta($user_id, "traerDireccion");
}
if (isset($_SESSION['cart'])) {
  $cart_items = $_SESSION['cart'];
} else {
  $cart_items = array();
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
      $datos = array($order_id, $product_id, $quantity, $price);
      $query = "INSERT INTO detalles_orden ( ID_orden, ID_producto,cantidad,precio_unitario) VALUES (?, ?, ?, ?)";
      $stmt = $pdo->prepare($query);
      for ($i = 0; $i < count($datos); $i++) {
        $stmt->bindValue($i + 1, $datos[$i]);
      }
      $stmt->execute();
    }
    if ($stmt->rowCount() > 0) {
      $success_msg[] = "Pedido realizado con éxito";
      $_SESSION['cart'] = array();

      $total_price = 0;
    } else {
      $warning_msg[] = "Error al realizar el pedido";
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

          if ($datosDir == null) {
            echo "No hay direcciones registradas, por favor ingrese una direccion para continuar";

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
                <input type="hidden" value="<?= serialize($cart_items) ?>">
                <input type='submit' name='make_order' value='Hacer pedido'>
            </form>

          <?php
          } else {
          ?>

            <form action="" method="post" id="hacer-pedido">
              <div class="input-field">
                <label for="direccion">Direccion</label>
                <input type="text" name="direccion" maxlength="30" required placeholder="Ingrese su direccion" oninput="this.value.replace(/\s/g,'')" value="<?= isset($datosDir['direccion']) ? $datosDir['direccion'] : '' ?>" required>
              </div>
              <div class="input-field">
                <label for="barrio">Barrio</label>
                <input type="text" name="barrio" maxlength="30" required placeholder="Ingrese su barrio" oninput="this.value.replace(/\s/g,'')" value="<?= $datosDir['barrio']  ?>" required>
              </div>
              <div class="input-field">
                <label for="localidad">Localidad</label>
                <input type="text" name="localidad" maxlength="30" required placeholder="Ingrese su localidad" oninput="this.value.replace(/\s/g,'')" value="<?= $datosDir['localidad'] ?>" required>
              </div>
              <?php
              echo "Precio total: $" . number_format($total_price, 2); ?>
              <input type="hidden" name="cart_items" value="<?= serialize($cart_items) ?>">
              <input type='submit' name='make_order' value='Hacer pedido'>
            </form>

      <?php
          }
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