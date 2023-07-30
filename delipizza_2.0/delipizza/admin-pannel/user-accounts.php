<?php

include '../components/connect.php';
include '../components/queries.php';
session_start();

$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('location:admin-login.php');
}


//Delete product from Database
if (isset($_POST['delete'])) {
    $p_id = $_POST['product_id'];
    $p_id = htmlspecialchars($p_id);

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
            <h1 class="heading">Ver Usuarios</h1>

            <div class="box-container">
                <?php
                $select_user = $pdo->prepare("SELECT * FROM usuario");
                $select_user->execute();
                if ($select_user->rowCount() > 0) {
                    while ($fetch_user = $select_user->fetch(PDO::FETCH_ASSOC)) {

                ?>
                        <form action="" method="post" class="box">
                            <input type="hidden" name="ID_usuario" value="<?= $fetch_user['ID_usuario']; ?>">
                            <?php if ($fetch_product['img_Producto'] != '') { ?>
                                <img src="../uploaded-img/productos/<?= $fetch_product['img_Producto']; ?>" alt="" class="image">
                            <?php  } ?>
                            <div class="status" style="color: <?php if ($fetch_product['estado'] == 'activo') {
                                                                    echo "limegreen";
                                                                } else {
                                                                    echo "coral";
                                                                } ?>;"><?= $fetch_product['estado']; ?></div>
                            <div class="price">$<?= $fetch_product['precio_Producto']; ?></div>
                            <div class="title"><?= $fetch_product['nombre_Producto']; ?></div>
                            <div class="flex-btn">
                                <a href="edit-product.php?id=<?= $fetch_product['ID_producto']; ?>" class="btn">Editar</a>
                                <button type="submit" name="delete" class="btn" onclick="return confirm('Desea borrar este producto?');">Borrar</button>
                                <a href="read-product.php?product_id=<?= $fetch_product['ID_producto']; ?>" class="btn">Ver detalle producto</a>
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




    <?php include '../components/dark.php'; ?>
    <script src="../js/script.js"></script>
    <!-- Sweet alert script -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <?php include '../components/alert.php'; ?>

</body>

</html>