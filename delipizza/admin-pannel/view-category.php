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

    $delete_product = $pdo->prepare("DELETE FROM Categoria WHERE ID_Categoria=?");
    $delete_product->execute([$p_id]);

    $success_msg[] = 'Categoria borrado exitosamente';
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
            <h1 class="heading">Ver Categorias</h1>

            <div class="box-container">
                <?php
                $select_product = $pdo->prepare("SELECT * FROM categoria WHERE ID_Categoria>1");
                $select_product->execute();
                if ($select_product->rowCount() > 0) {
                    while ($fetch_product = $select_product->fetch(PDO::FETCH_ASSOC)) {

                ?>
                        <form action="" method="post" class="box">
                            <input type="hidden" name="product_id" value="<?= $fetch_product['ID_Categoria']; ?>">
                            <?php if ($fetch_product['img_Categoria'] != '') { ?>
                                <img src="../uploaded-img/Categorias/<?= $fetch_product['img_Categoria']; ?>" alt="" class="image">
                            <?php  } ?>


                            <div class="title"><?= $fetch_product['nombre_Categoria']; ?></div>
                            <div class="flex-btn">
                                <a href="edit-category.php?id=<?= $fetch_product['ID_Categoria']; ?>" class="btn">Editar</a>
                                <button type="submit" name="delete" class="btn" onclick="return confirm('Desea borrar este Categoria?');">Borrar</button>

                            </div>

                        </form>



                <?php
                    }
                } else {
                    echo '  <div class="empty">
                <p>No hay Categorias añadidos aún <br><a href="add-products.php" class="btn" style="margin-top: 1.5rem;">Añadir Categoria</a></p>
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