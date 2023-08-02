<?php

include '../components/connect.php';
include '../components/queries.php';
session_start();

$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('location:admin-login.php');
}
$product_id = $_GET['id'];
print_r($_POST);
if (isset($_POST['save_product'])) {
    $title = $_POST['title'];
    $title = htmlspecialchars($title);
    $description = $_POST['description'];
    $description = htmlspecialchars($description);
    $category_id = $_POST['category_id'];



    $update_product = $pdo->prepare("UPDATE categoria SET nombre_Categoria= ?, desc_Categoria= ?  WHERE ID_Categoria = ?");
    $update_product->execute([$title, $description, $category_id]);
    $success_msg[] = 'Producto actualizado';

    $old_image = $_POST['old_img'];
    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../uploaded-img/categorias/' . $image;
    $select_image = $pdo->prepare("SELECT img_Categoria FROM categoria WHERE ID_Categoria = ?");
    $select_image->execute([$category_id]);

    if (!empty($image)) {
        if ($image_size > 10000000) {
            $warning_msg[] = 'La imagen es muy grande';
        } else {
            $update_image = $pdo->prepare("UPDATE categoria SET img_Categoria = ? WHERE ID_Categoria = ?");
            $update_image->execute([$image, $product_id]);
            move_uploaded_file($image_tmp_name, $image_folder);
            if ($old_image != $image and $old_image != '') {
                unlink('../uploaded-img/categorias/' . $old_image);
            }
            $success_msg[] = 'Imagen actualizada';
        }
    }
    //Delete product
    if (isset($_POST['delete_product'])) {
        $delete_product = $pdo->prepare("DELETE FROM categoria WHERE ID_Categoria = ?");
        $delete_product->execute([$_POST['product_id']]);
        unlink('../uploaded-img/categorias/' . $old_image);
        header('location:admin-dashboard.php');
        $success_msg[] = 'Categoria eliminada';
    }
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
        <section class="post-editor">
            <h1 class="heading">Editar producto</h1>
            <div class="box-container">
                <?php
                $category_id = $_GET['id'];
                $get_category = $pdo->prepare("SELECT * FROM categoria WHERE ID_Categoria = ?");
                $get_category->execute([$category_id]);
                if ($get_category->rowCount() > 0) {
                    while ($fetch_category = $get_category->fetch(PDO::FETCH_ASSOC)) {


                ?>
                        <div class="form-container">
                            <form action="" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="old_img" value="<?= $fetch_category['img_Categoria']; ?>">
                                <input type="hidden" name="category_id" value="<?= $fetch_category['ID_Categoria']; ?>">
                                <div class="input-field">
                                    <label for="title">Nombre la categoria <sup>*</sup></label>
                                    <input type="text" name="title" id="title" pattern="^[a-zA-Z ]+$" value="<?= $fetch_category['nombre_Categoria']; ?>" >

                                </div>
                                <div class="input-field">
                                    <label for="description">Descripción del Producto <sup>*</sup></label>
                                    <textarea name="description" id="description" cols="30" rows="10" required><?= $fetch_category['desc_Categoria']; ?></textarea>

                                </div>
                                <div class="input-field">
                                    <label for="image">Imagen de la categoria <sup>*</sup></label>
                                    <input type="file" name="image" id="image">
                                    <?php if ($fetch_category['img_Categoria'] != '') { ?>
                                        <img src="../uploaded-img/categorias/<?= $fetch_category['img_Categoria']; ?>" alt="" class="image">
                                        <div class="flex-btn">

                                            <a href="view-categorys.php" class="btn" style="width:49%; text-align:center; height: 3rem;margin-top:.7rem;">volver</a>
                                        </div>
                                    <?php } ?>
                                    <div class="flex-btn">
                                        <input type="submit" name="save" value="guardar categoria">
                                        <input type="submit" name="delete_category" value="borrar categoria">


                                    </div>
                                </div>

                            </form>
                        </div>
            </div>
    <?php
                    }
                }

    ?>



        </section>
    </div>


    <script src="../js/script.js"></script>
    <!-- Sweet alert script -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <?php include '../components/alert.php'; ?>

</body>

</html>