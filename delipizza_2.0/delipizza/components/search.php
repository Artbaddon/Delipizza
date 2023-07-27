<section class="food-search text-center">
  <div class="container">
    <form action="" class="food-search-container">
      <input type="search" name="producto" id="producto" placeholder="Buscar comidas..." />
      <input type="submit" name="submit" value="Buscar" class="btn btn-primary search-btn" />
    </form>
  </div>
</section>
</section>
<?php
include 'connect.php';
// aca lo que hace es una consulta a la tabla nombre productos y lo muestra en pantalla
if (isset($_GET['submit']) and $_GET['producto'] == "") {
  $warning_msg[] = "Ingrese un producto para buscar";
} else
if (isset($_GET['producto']) and $_GET['producto'] != "") {
  $product = $_GET['producto'];
  $select_product = $conn->prepare("SELECT * FROM producto WHERE nombre_producto LIKE ?");

  $select_product->execute(["%$product%"]);
  $fetch_product = $select_product->fetchAll(PDO::FETCH_ASSOC);


  if ($select_product->rowCount() > 0) {
    foreach ($fetch_product as $product) {
?>
      <div class="box-menu">
        <form action="cart.php" method="post">
          <input type="hidden" name="product_id" value="<?= $product['ID_producto']; ?>">
          <input type="hidden" name="product_name" value="<?= $product['nombre_Producto']; ?>">
        
          <a href="view-product.php?product_id=<?= $product['ID_producto']; ?>">
            <img src="../uploaded-img/productos/<?= $product['img_Producto']; ?>" alt="" class="img-product img-curve">
          </a>
          <div class="title-container">
            <h4 class="title-product"><?= $product['nombre_Producto'] ?></h4>
          </div>

          <p class="food-price-menu">$<?= $product['precio_Producto'] ?></p>

          <div class="cart-container">


            <a href="view-product.php?product_id=<?= $product['ID_producto']; ?>" value="Agregar al carrito" class="add-cart">Ver producto</a>
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