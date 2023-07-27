<?php

include '../components/connect.php';
session_start();

$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('location:admin-login.php');
}
$product_id = $_GET['id'];
if (isset($_POST['save'])) {
    $title = $_POST['title'];
    $title = htmlspecialchars($title);

    $price = $_POST['price'];
    $price = htmlspecialchars($price);

    $description = $_POST['description'];
    $description = htmlspecialchars($description);

    $category = $_POST['category'];
    $category = htmlspecialchars($category);

    $state = $_POST['status'];
    $state = htmlspecialchars($state);


    $update_product = $conn->prepare("UPDATE producto SET nombre_Producto = ?, precio_Producto = ?, descripcion_Producto = ?, CategoriaID = ?, estado = ?  WHERE ID_Producto = ?");
    $update_product->execute([$title, $price, $description, $category, $state, $_POST['product_id']]);
    $success_msg[] = 'Producto actualizado';

    $old_image = $_POST['old_img'];
    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../uploaded-img/productos/' . $image;
    $select_image = $conn->prepare("SELECT img_Producto FROM producto WHERE ID_Producto = ?");
    $select_image->execute([$_POST['product_id']]);

    if (!empty($image)) {
        if ($image_size > 10000000) {
            $warning_msg[] = 'La imagen es muy grande';
        } else {
            $update_image = $conn->prepare("UPDATE producto SET img_Producto = ? WHERE ID_Producto = ?");
            $update_image->execute([$image, $product_id]);
            move_uploaded_file($image_tmp_name, $image_folder);
            if ($old_image != $image and $old_image != '') {
                unlink('../uploaded-img/productos/' . $old_image);
            }
            $success_msg[] = 'Imagen actualizada';
        }
    }
    //Delete product
    if (isset($_POST['delete_product'])) {
        $delete_product = $conn->prepare("UPDATE producto SET estado='inactivo' WHERE ID_Producto = ?");
        $delete_product->execute([$_POST['product_id']]);
        unlink('../uploaded-img/productos/' . $old_image);
        header('location:admin-dashboard.php');
        $success_msg[] = 'Producto eliminado';
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
                $product_id = $_GET['id'];
                $get_product = $conn->prepare("SELECT * FROM producto WHERE ID_Producto = ?");
                $get_product->execute([$product_id]);
                if ($get_product->rowCount() > 0) {
                    while ($fetch_product = $get_product->fetch(PDO::FETCH_ASSOC)) {


                ?>
                        <div class="form-container">
                            <form action="" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="old_img" value="<?= $fetch_product['img_Producto']; ?>">
                                <input type="hidden" name="product_id" value="<?= $fetch_product['ID_producto']; ?>">
                                <div class="input-field">
                                    <label for="status">Estado del Producto <sup>*</sup></label>
                                    <select name="status" id="">
                                        <option value="<?= $fetch_product['estado']; ?>" selected>
                                            <?= $fetch_product['estado']; ?>
                                        </option>
                                        <option value="activo">activo</option>
                                        <option value="inactivo">inactivo</option>
                                    </select>
                                </div>
                                <div class="input-field">
                                    <label for="title">Nombre del Producto <sup>*</sup></label>
                                    <input type="text" name="title" id="title" value="<?= $fetch_product['nombre_Producto']; ?>" required>
                                </div>
                                <div class="input-field">
                                    <label for="price">Precio del Producto <sup>*</sup></label>
                                    <input type="number" name="price" id="price" value="<?= $fetch_product['precio_Producto']; ?>" required>
                                </div>
                                <div class="input-field">
                                    <label for="description">Descripción del Producto <sup>*</sup></label>
                                    <textarea name="description" id="description" cols="30" rows="10" required><?= $fetch_product['descripcion_Producto']; ?></textarea>
                                </div>
                                <div class="input-field">
                                    <label for="category">
                                        Categoría del Producto <sup>*</sup>
                                    </label>
                                    <select name="category" id="category">
                                        <option value="<?= $fetch_product['CategoriaID']; ?>" selected>
                                            <?= $fetch_product['CategoriaID']; ?>
                                        </option>
                                        <?php
                                        $get_category = $conn->prepare("SELECT * FROM categoria");
                                        $get_category->execute();
                                        if ($get_category->rowCount() > 0) {
                                            while ($fetch_category = $get_category->fetch(PDO::FETCH_ASSOC)) {
                                        ?>
                                                <option value="<?= $fetch_category['ID_Categoria']; ?>">
                                                    <?= $fetch_category['nombre_Categoria']; ?>
                                                </option>
                                        <?php
                                            }
                                        }
                                        ?>
                                </div>
                                <div class="input-field">
                                    <label for="image">Imagen del Producto <sup>*</sup></label>
                                    <input type="file" name="image" id="image" required>
                                    <?php if ($fetch_product['img_Producto'] != '') { ?>
                                        <img src="../uploaded-img/productos/<?= $fetch_product['img_Producto']; ?>" alt="" class="image">
                                        <div class="flex-btn">
                                            <input type="submit" name="delete_image" class="btn" value="borrar imagen">
                                            <a href="view-products.php" class="btn" style="width:49%; text-align:center; height: 3rem;margin-top:.7rem;">volver</a>
                                        </div>
                                    <?php } ?>
                                    <div class="flex-btn">
                                        <input type="submit" name="save" value="guardar producto">
                                        <input type="submit" name="delete_product" value="borrar producto">
                                    </div>
                                </div>

                            </form>
                        </div>
            </div>
        <?php
                    }
                } else {
                    echo '  <div class="empty">
                <p>No hay productos añadidos aún <br><a href="add-products.php" class="btn" style="margin-top: 1.5rem;">Añadir producto</a></p>
            </div>';

        ?>

    <?php } ?>

        </section>
    </div>

    <?php include '../components/dark.php'; ?>
    <script src="../js/script.js"></script>
    <!-- Sweet alert script -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <?php include '../components/alert.php'; ?>

</body>

</html>