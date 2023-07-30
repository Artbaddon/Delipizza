<?php

include '../components/connect.php';
include '../components/queries.php';
session_start();

$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('location:admin-login.php');
}
//Añadir categoria a DB
if (isset($_POST['publish'])) {

    $title = $_POST['title'];
    $title = htmlspecialchars($title);
    $description = $_POST['description'];
    $description = htmlspecialchars($description);

    $image = $_FILES['image']['name'];
    $image = htmlspecialchars($image);
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../uploaded-img/categorias/' . $image;



    if ($image_size > 1000000) {
        $warning_msg[] = 'La imagen es muy grande';
    } else {
        move_uploaded_file($image_tmp_name, $image_folder);
        $datos = array();
        array_push($datos,$title, $description, $image);
        hacerConsulta($datos,"insertarCategoria");
       
    
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

            <h1 class="heading">Añadir Categoria</h1>
            <div class="form-container">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="input-field">
                        <label for="title">Nombre categoria</label>
                        <input type="text" name="title" id="title" pattern="^[a-zA-Z ]+$" placeholder="Nombre categoria" maxlength="30" required>
                    </div>

                    <div class="input-field">
                        <label for="description">Descripción</label>
                        <textarea name="description" id="description" cols="30" rows="10" placeholder="Descripción" required></textarea>
                    </div>

                    <div class="input-field">
                        <label for="image">Imagen</label>
                        <input type="file" name="image" id="image" required>
                    </div>
                    <div class="input-field">
                        <input type="submit" name="publish" value="añadir categoria" class="btn">

                    </div>
                </form>
            </div>
        </section>
    </div>

 
    <script src="../js/script.js"></script>
    <!-- Sweet alert script -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <?php include '../components/alert.php'; ?>

</body>

</html>