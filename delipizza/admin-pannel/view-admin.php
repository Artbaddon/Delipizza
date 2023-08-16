<?php

include '../components/connect.php';
include '../components/queries.php';
session_start();

$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('location:admin-login.php');
}
if (isset($_POST['delete'])) {
    $p_id = $_POST['admin_id'];
    $p_id = htmlspecialchars($p_id);

    $delete_admin = $pdo->prepare("DELETE FROM usuario WHERE ID_usuario=?");
    $delete_admin->execute([$p_id]);

    $success_msg[] = 'Producto borrado exitosamente';
    header('location:view-admin.php');
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
                $select_admin = $pdo->prepare("SELECT * FROM administrador");
                $select_admin->execute();

                if ($select_admin->rowCount() > 0) {
                    while ($fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC)) {

                ?>
                        <form action="" method="post" class="box">
                            <input type="hidden" name="admin_id" value="<?= $fetch_admin['ID_Administrador']; ?>">
                            <div class="id_admin"><?= $fetch_admin['ID_Administrador']; ?></div>
                            <div class="name_admin"><?= $fetch_admin['nombre_Admin']; ?></div>
                            <?php if ($fetch_admin['foto'] != '') { ?>
                                <img src="../uploaded-img/clientes/<?= $fetch_admin['foto']; ?>" alt="" class="image">
                            <?php  } ?>
                            <div class=""><?= $fetch_admin['nombre_Admin']; ?></div>
                            <div class="flex-btn">
                                <a href="edit-admin.php?id=<?= $fetch_admin['ID_Administrador']; ?>" class="btn">Editar</a>
                               

                            </div>

                        </form>



                <?php
                    }
                } else {
                    $error_msg[] = 'No hay administradores';
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