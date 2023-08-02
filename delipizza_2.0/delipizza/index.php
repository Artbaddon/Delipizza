<?php
include 'components/connect.php';
include 'components/queries.php';
session_start();

$user_id = "";
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
  $warning_msg[] = 'Inicie sesion para continuar';
  header('location:views/user-login.php');
}
$informacionProductosR = hacerConsulta('', "traerProductosRecomendados");
$categoriaProductos = hacerConsulta("", "traerCategorias");
$consultarUsuario = hacerConsulta("2", "consultarUsuario");


?>


<style>
  <?php

  include 'css/style.css';
  ?>
</style>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />


  <title>foods store</title>
</head>

<body class="index">
  <!-- Nav section stars here -->
  <header class="navbar">
    <div class="container-nav">
      <div class="logo-nav">
        <a href="index.php">
          <img src="image/Delipizza-logo-final.jpg" alt="" class="img-responsive" />
        </a>
      </div>
      <nav class="menu text-left">

        <ul>
          <li>
            <a href="index.php">Inicio</a>
          </li>
          <li>
            <a href="views/menu.php">Menu</a>
          </li>
          <li>
            <a href="views/profile.php">Perfil</a>
          </li>
          <li>
            <div class="icon" id="user-btn">
              <img src="image/user-solid-24.png" alt="">
            </div>
          </li>
          <li>
            <div class=" icon">
              <a href="views/cart.php">
                <img src="image/bxs-shopping-bags.png" alt="">
              </a>

            </div>
          </li>
        </ul>
      </nav>
      <div class="profile-detail">
        <?php
        $select_profile = $pdo->prepare("SELECT * FROM usuario WHERE ID_Usuario = ?");
        $select_profile->execute([$user_id]);
        if ($select_profile->rowCount() > 0) {
          $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

        ?>
          <div class="profile">
            <img src="uploaded-img/clientes/<?= $fetch_profile['foto']; ?>" alt="" class="logo-img" width="100">
            <p><?= $fetch_profile['nombre_Usuario'];   ?></p>
          </div>
          <div class="flex-btn">
            <a href="views/profile.php
                    " class="btn-profile">Ver Perfil</a>
            <a href="components/user-logout.php" class="btn-profile" onclick="return confirm('Logout from the website' )">Logout</a>
          </div>
        <?php } ?>
      </div>

      <div class="clearfix"></div>

    </div>

  </header>
  <!-- Nav section ends here -->

  <!-- Search section stars here -->
  <section class="food-search text-center" style="background-image: url(image/pizza_Mh3H4eanyBKEsStv1YclPWTf9OUqIi-1024x683.png.webp);">
    <div class="container">
      <form action="" class="food-search-container">
        <input type="search" name="producto" id="producto" placeholder="Buscar comidas..." />
        <input type="submit" name="submit" value="Buscar" class="btn btn-primary search-btn" />
      </form>
    </div>
  </section>
  </section>
  <?php
  include 'components/connect.php';
  // aca lo que hace es una consulta a la tabla nombre productos y lo muestra en pantalla
  if (isset($_GET['submit']) and $_GET['producto'] == "") {
    $warning_msg[] = "Ingrese un producto para buscar";
  } else
if (isset($_GET['producto']) and $_GET['producto'] != "") {
    $product = $_GET['producto'];
    $select_product = $pdo->prepare("SELECT * FROM producto WHERE nombre_producto LIKE ?");

    $select_product->execute(["%$product%"]);
    $fetch_product = $select_product->fetchAll(PDO::FETCH_ASSOC);


    if ($select_product->rowCount() > 0) {
      foreach ($fetch_product as $product) {
  ?>
        <div class="box-menu">
          <form action="cart.php" method="post">
            <input type="hidden" name="product_id" value="<?= $product['ID_producto']; ?>">
            <input type="hidden" name="product_name" value="<?= $product['nombre_Producto']; ?>">
            <input type="hidden" name="product_img" value="<?= $product['img_Producto'] ?>">

            <a href="views/view-product.php?product_id=<?= $product['ID_producto']; ?>">
              <img src="uploaded-img/productos/<?= $product['img_Producto']; ?>" alt="" name="img_Producto" class="img-product img-curve">
            </a>
            <div class="title-container">
              <h4 class="title-product"><?= $product['nombre_Producto'] ?></h4>
            </div>

            <p class="food-price-menu">$<?= $product['precio_Producto'] ?></p>

            <div class="cart-container">


              <a href="views/view-product.php?product_id=<?= $product['ID_producto']; ?>" value="Agregar al carrito" class="add-cart">Ver producto</a>
            </div>

          </form>

        </div>
  <?php
      }
    } else {
      $warning_msg[] = "No se encontraron resultados";
    }
  }
  ?>

  <!-- Search section ends here -->

  <!-- Categories section stars here -->
  <section class="categories">
    <div class="container-cat">
      <h2 class="text-center">Categorias</h2>
      <?php
      foreach ($categoriaProductos as $categoria) { ?>

        <a href="views/menu.php#<?= $categoria['ID']; ?>">
          <form action="" method="post" class="box-4 float-container">
            <input type="hidden" name="category_id" value="<?= $categoria['ID']; ?>">
            <?php if ($categoria['foto'] != '') { ?>
              <img src="uploaded-img/categorias/<?= $categoria['foto']; ?>" alt="" class="img-cat img-curve">
              <div class="title-container-cat">
                <h3 class=""><?= $categoria['Nombre'] ?></h3>
              </div>
              <div class="cat-desc-container">
                <p class="cat-desc"><?= $categoria['Descripcion'] ?></p>
              </div>
            <?php } ?>
          </form>
        </a>
      <?php
      }



      ?>

    </div>
  </section>
  <!-- Categories section stars here -->

  <!-- Suggested section stars here -->
  <section class="food-catalog">
    <div class="container">
      <h2 class="text-center">Productos Recomendados</h2>
      <div class="float-container">
        <h2>Productos Recomendados</h2>
        <?php

        foreach ($informacionProductosR as $product) {
        ?>


          <div class="box-menu">

            <iframe name="dummyframe" id="dummyframe" style="display: none;"></iframe>
            <form action="views/cart.php" method="post" target="dummyframe" onsubmit="alert('Producto añadido al carrito');">
              <input type="hidden" name="product_id" value="<?= $product['ID']; ?>">
              <input type="hidden" name="product_name" value="<?= $product['Nombre']; ?>">
              <input type="hidden" name="product_price" value="<?= $product['Precio']; ?>">
              <a href="view-product.php?product_id=<?= $product['ID']; ?>">
                <img src="uploaded-img/productos/<?= $product['Img']; ?>" alt="" class="img-product img-curve">
              </a>
              <div class="title-container">
                <h4 class="title-product"><?= $product['Nombre'] ?></h4>
              </div>
              <div class="cart-container">

                <input type="submit" name="add_to_cart" value="Agregar al carrito" class="add-cart">
              </div>

              <input type="number" name="quantity" id="quantity" value="1" min="1" max="10" class="quantity">
              <p class="food-price-menu">$<?= number_format($product['Precio']);  ?></p>
            </form>
          </div>
        <?php

        }

        ?>
      </div>
    </div>
  </section>
  <!-- Menu section stars here -->

  <!-- Social section stars here -->
  <section class="social">
    <div class="container text-center">
      <ul>
        <li>
          <a href=""><img src="https://img.icons8.com/color/48/null/facebook-new.png" /></a>
        </li>
        <li>
          <a href=""><img src="https://img.icons8.com/fluency/48/null/instagram-new.png" /></a>
        </li>
        <li>
          <a href=""><img src="https://img.icons8.com/color/48/null/twitter--v1.png" /></a>
        </li>
      </ul>
    </div>
  </section>
  <!-- Social section stars here -->

  <!-- Footer section stars here -->
  <section class="footer">
    <div class="container text-center">
      <p>All rights reserved. Designed By <a href="#">Artbaddon</a></p>
    </div>
  </section>
  <!-- Footer section stars here -->
</body>

</html>


<<script src="js/script1.js">
  </>

  </script>
  <!-- Sweet alert script -->
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <?php include 'components/alert.php'; ?>