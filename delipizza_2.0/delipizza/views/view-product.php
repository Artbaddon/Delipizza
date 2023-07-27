<?php
include '../components/connect.php';
session_start();

$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
    $warning_msg[] = 'Inicie sesion para continuar';
    header('location:user-login.php');
}



$_GET['product_id'];




?>


<style>
    <?php include '../css/style.css'; ?>
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

<body>
    <!-- Nav section stars here -->
    <?php
    include '../components/general-header.php';
    ?>
    <!-- Nav section ends here -->




    <!-- Menu section stars here -->

    <section class="view-product">

        <div class="container-menu">

            <div class="container-product">
                <?php
                $select_product = $conn->prepare("SELECT * FROM producto WHERE ID_producto=?");
                $select_product->execute([$_GET['product_id']]);
                if ($select_product->rowCount() > 0) {
                    while ($fetch_product = $select_product->fetch(PDO::FETCH_ASSOC)) {

                ?>

                        <form action="cart.php" method="post" class="form-container-product" onsubmit="alert('Producto añadido al carrito');">
                            <h2 class="text-center"><?= $fetch_product['nombre_Producto'] ?></h2>
                            <div class="box-product">

                                <div class="product-container">
                                    <input type="hidden" name="user_id" value="<?= $user_id; ?>">
                                    <input type="hidden" name="product_id" value="<?= $fetch_product['ID_producto']; ?>">
                                    <input type="hidden" name="product_name" value="<?= $fetch_product['nombre_Producto']; ?>">
                                    <input type="hidden" name="product_price" value="<?= $fetch_product['precio_Producto']; ?>">
                                    <input type="hidden" name="product_img" value="<?= $fetch_product['img_Producto']; ?>">


                                    <?php if ($fetch_product['img_Producto'] != '') { ?>

                                        <img src="../uploaded-img/productos/<?= $fetch_product['img_Producto']; ?>" alt="" class="image">
                                    <?php  } ?>

                                </div>
                                <div class="product-container-desc text-center">


                                    <div class="desc"><?= $fetch_product['descripcion_Producto']; ?></div>

                                </div>

                                <div class="flex-btn">

                                    <button type="submit" name="add_to_cart" class="btn-cart">Agregar al carrito</button>
                                    <select name="quantity" id="quantity" class="quantity">
                                        <?php for ($i = 1; $i <= 10; $i++) { ?>
                                            <option value="<?= $i; ?>"><?= $i; ?></option>
                                        <?php } ?>
                                    </select>




                                </div>

                            </div>

                        </form>

                <?php
                    }
                } else {
                    echo "<h2 class='text-center'>No hay productos</h2>";
                }


                ?>

            </div>

        </div>
    </section>

    <!-- Menu section ends here -->

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

<script src="../js/script1.js">


</script>
<!-- Sweet alert script -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<?php include '../components/alert.php'; ?>