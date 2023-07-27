<?php

include '../components/connect.php';
session_start();

$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('location:admin-login.php');
}
if (isset($_POST['publish'])) {
    $title = $_POST['title'];
    $title = htmlspecialchars($title);

    $price = $_POST['price'];
    $price = htmlspecialchars($price);

    $description = $_POST['description'];
    $description = htmlspecialchars($description);

    $category = $_POST['category'];
    $category = htmlspecialchars($category);

    $state = $_POST['state'];
    $state = htmlspecialchars($state);

    $image = $_FILES['image']['name'];
    $image = htmlspecialchars($image);
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../uploaded-img/productos/' . $image;


    if ($image_size > 10000000) {
        $warning_msg[] = 'La imagen es muy grande';
    } else {
        move_uploaded_file($image_tmp_name, $image_folder);
        $insert_product = $conn->prepare("INSERT INTO producto (nombre_Producto, precio_Producto, descripcion_Producto, CategoriaID, estado,img_Producto) VALUES (?,?,?,?,?,?)");
        $insert_product->execute([$title, $price, $description, $category, $state, $image]);
        $success_msg[] = 'Producto añadido';
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

            <h1 class="heading">Añadir Producto</h1>
            <div class="form-container">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="input-field">
                        <label for="title">Nombre producto</label>
                        <input type="text" name="title" id="title" pattern="^[a-zA-Z ]+$" placeholder="Nombre producto" maxlength="30" required>
                    </div>


                    <div class="input-field">
                        <label for="price">Precio</label>
                        <input type="number" name="price" id="price" placeholder="Precio" required>
                    </div>
                    <div class="input-field">
                        <label for="description">Descripción</label>
                        <textarea name="description" id="description" cols="30" rows="10" placeholder="Descripción" required></textarea>
                    </div>
                    <div class="input-field">
                        <label for="category">Categoría</label>
                        <select name="category" id="category" required>
                            <option value="" selected disabled>Seleccione una categoría</option>
                            <option value="1">Destacados</option>
                            <option value="2">Pizzas</option>
                            <option value="3">Hamburguesas</option>
                            <option value="4">Salchipapas</option>
                            <option value="5">Acompañamientos</option>
                            <option value="6">Perros Calientes</option>
                            <option value="7">Panzerottis</option>
                            <option value="8">Lasañas</option>
                            <option value="9" s>Mazorcadas</option>
                        </select>

                    </div>
                    <div class="input-field">
                        <label for="state">Estado</label>
                        <select name="state" id="state" required>
                            <option value="" selected disabled>Seleccione un estado</option>
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>
                    <div class="input-field">
                        <label for="image">Imagen</label>
                        <input type="file" name="image" id="image" required>
                    </div>
                    <div class="input-field">
                        <input type="submit" name="publish" value="Publicar Producto" class="btn">
                     
                    </div>
                </form>
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