<?php

include '../components/connect.php';
include '../components/queries.php';
session_start();

$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('location:admin-login.php');
}
$get_id = $_GET['product_id'];
//Delete product from Database
if (isset($_POST['delete'])) {
    $p_id = $_POST['product_id'];
    $p_id = htmlspecialchars($p_id);

    $delete_img = $pdo->prepare("SELECT img_Producto FROM producto WHERE ID_producto=?");
    $delete_img->execute([$p_id]);
    $fetch_delete_img = $delete_img->fetch(PDO::FETCH_ASSOC);
    if ($fetch_delete_img['img_Producto'] != '') {
        unlink('../uploaded-img/productos/' . $fetch_delete_img['img_Producto']);
    }



    $delete_product = $pdo->prepare("DELETE FROM producto WHERE ID_producto=?");
    $delete_product->execute([$p_id]);

    $success_msg[] = 'Producto borrado exitosamente';
    header('location:view-products.php');
}
?>


<style>
    <?php include '../css/admin-style.css'; ?>
</style>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Box Icon CDN list  -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Admin - Dashboard - Delipizza</title>
</head>

<body>
    <div class="main-container">
        <?php include '../components/admin-header.php'; ?>
        <section class="show-product">
            <h1 class="heading">Tu Producto</h1>

            <div class="box-container">
                <?php
                $select_product = $pdo->prepare("SELECT * FROM producto WHERE ID_producto=?");
                $select_product->execute([$get_id]);
                if ($select_product->rowCount() > 0) {
                    while ($fetch_product = $select_product->fetch(PDO::FETCH_ASSOC)) {

                ?>
                        <form action="" method="post" class="box">
                            <input type="hidden" name="product_id" value="<?= $fetch_product['ID_producto']; ?>">
                            <?php if ($fetch_product['img_Producto'] != '') { ?>
                                <div class="status" style="color: <?php if ($fetch_product['estado'] == 'activo') {
                                                                        echo "limegreen";
                                                                    } else {
                                                                        echo "coral";
                                                                    } ?>;"><?= $fetch_product['estado']; ?></div>
                                <img src="../uploaded-img/productos/<?= $fetch_product['img_Producto']; ?>" alt="" class="image">
                            <?php  } ?>

                            <div class="price">$<?= $fetch_product['precio_Producto']; ?></div>
                            <div class="title"><?= $fetch_product['nombre_Producto']; ?></div>
                            <div class="desc"><?= $fetch_product['descripcion_Producto']; ?></div>
                            <div class="flex-btn">
                                <a href="edit-product.php?id=<?= $fetch_product['ID_producto']; ?>" class="btn">Editar</a>
                                <button type="submit" name="delete" class="btn" onclick="return confirm('Desea borrar este producto?');">Borrar</button>

                            </div>


                        </form>



                <?php
                    }
                } else {
                    echo '  <div class="empty">
                <p>No hay productos añadidos aún <br><a href="add-products.php" class="btn" style="margin-top: 1.5rem;">Añadir producto</a></p>
            </div>';
                }


                ?>
            </div>
        </section>
    </div>




 
    <script src="../js/script.js"></script>
    <!-- Sweet alert script -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <?php include '../components/alert.php'; ?>

</body>

</html>