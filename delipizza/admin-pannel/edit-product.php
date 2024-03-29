<?php

include '../components/connect.php';
include '../components/queries.php';


session_start();

$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('location:admin-login.php');
}
$product_id = "";
$product_id = $_GET['id'];

if (isset($_POST['save'])) {
    $old_product = hacerConsulta($product_id, "traerProductosID");

    $old_title = $old_product['nombre_Producto'];
    $title = $_POST['title'];
    $title = htmlspecialchars($title);

    $old_price = $old_product['precio_Producto'];
    $price = $_POST['price'];
    $price = htmlspecialchars($price);

    $old_desc = $old_product['descripcion_Producto'];
    $description = $_POST['description'];
    $description = htmlspecialchars($description);

    $old_category = $old_product['CategoriaID'];
    $category = $_POST['category'];
    $category = htmlspecialchars($category);

    $old_state = $old_product['estado'];
    $state = $_POST['status'];
    $state = htmlspecialchars($state);

    if (isset($title)) {
        if ($title != $old_title and $title != '') {
            $update_name = $pdo->prepare("UPDATE producto SET nombre_producto=? WHERE ID_producto =?");
            $update_name->execute([$title, $product_id]);
            if ($update_name->rowCount() > 0) {
                $success_msg[] = 'Nombre Actualizado correctamente';
            } else {
                $success_msg[] = 'Error al actualizar nombre';
            }
        }
    } else {
        $warning_msg[] = 'Error al actualizar, nombre repetido';
    }
    if (isset($price)) {
        if ($price != $old_price and $price != '') {
            $update_price = $pdo->prepare("UPDATE producto SET precio_Producto=? WHERE ID_producto =?");
            $update_price->execute([$price, $product_id]);
            if ($update_price->rowCount() > 0) {
                $success_msg[] = 'Precio actualizado correctamente';
            } else {
                $success_msg[] = 'Error al actualizar precio';
            }
        } else {
            $warning_msg[] = 'Error al actualizar, precio igual al anterior';
        }
    }
    if (isset($description)) {
        if ($description != $old_desc and $description != '') {
            $update_description = $pdo->prepare("UPDATE producto SET descripcion_Producto=? WHERE ID_producto =?");
            $update_description->execute([$description, $product_id]);
            if ($update_description->rowCount() > 0) {
                $success_msg[] = 'Descripcion Actualizada correctamente';
            } else {
                $success_msg[] = 'Error al actualizar descripcion';
            }
        } else {
            $warning_msg[] = 'Error al actualizar, la descripcion es igual a la anterior';
        }
    }
    if (isset($category)) {
        if ($category != $old_category and $category != '') {
            $update_category = $pdo->prepare("UPDATE producto SET categoria_Producto=? WHERE ID_producto=?");
            $update_category->execute([$category, $product_id]);
            if ($update_category->rowCount() > 0) {
                $success_msg[] = ' Categoria Actualizada correctamente';
            } else {
                $warning_msg[] = 'Error al actualizar categoria';
            }
        }
    }



    //Update image

    if (isset($_POST['old_img']) and $_FILES['image']['name'] != '') {

        $old_image = $_POST['old_img'];
        $image = $_FILES['image']['name'];
        $image = htmlspecialchars($image);
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = '../uploaded-img/productos/' . $image;
        $image_size = $_FILES['image']['size'];



        if ($image_size > 10000000) {
            $warning_msg[] = 'La imagen es muy grande';
        } else {
            $update_image = $pdo->prepare("UPDATE producto SET img_Producto = ? WHERE ID_Producto = ?");
            $update_image->execute([$image, $product_id]);
            if ($update_image->rowCount() > 0) {
                $success_msg[] = 'Imagen actualizada';
            } else {
                $warning_msg[] = 'Error al actualizar la imagen';
            }


            move_uploaded_file($image_tmp_name, $image_folder);
            if ($old_image != $image && $old_image != '') {
                unlink('../uploaded-img/productos/' . $old_image);
            }
        }
    }
}







//Delete product
if (isset($_POST['delete_product'])) {
    $delete_product = $pdo->prepare("UPDATE producto SET estado=? WHERE ID_Producto = ?");
    $delete_product->execute(['inactivo', $_POST['product_id']]);
    unlink('../uploaded-img/productos/' . $old_image);
    header('location:admin-dashboard.php');
    $success_msg[] = 'Producto eliminado';
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
                $get_product = $pdo->prepare("SELECT * FROM producto WHERE ID_Producto = ?");
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
                                        $get_category = $pdo->prepare("SELECT * FROM categoria");
                                        $get_category->execute();
                                        if ($get_category->rowCount() > 0) {
                                            while ($fetch_category = $get_category->fetch()) {
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
                                    <input type="file" name="image" id="image">
                                    <?php if ($fetch_product['img_Producto'] != '') { ?>
                                        <img src="../uploaded-img/productos/<?= $fetch_product['img_Producto']; ?>" alt="" class="image">
                                        <div class="flex-btn">

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


    <script src="../js/script.js"></script>
    <!-- Sweet alert script -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <?php include '../components/alert.php'; ?>

</body>

</html>