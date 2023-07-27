<?php

include '../components/connect.php';
session_start();

$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('location:admin-login.php');
}
if (isset($_POST['delete'])) {
    $p_id = $_POST['user_id'];
    $p_id = htmlspecialchars($p_id);

    $delete_user = $conn->prepare("DELETE FROM usuario WHERE ID_usuario=?");
    $delete_user->execute([$p_id]);

    $success_msg[] = 'Producto borrado exitosamente';
    header('location:view-user.php');
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
                $select_user = $conn->prepare("SELECT * FROM usuario");
                $select_user->execute();

                if ($select_user->rowCount() > 0) {
                    while ($fetch_user = $select_user->fetch(PDO::FETCH_ASSOC)) {

                ?>
                        <form action="" method="post" class="box">
                            <input type="hidden" name="user_id" value="<?= $fetch_user['ID_Usuario']; ?>">
                            <div class="id_user"><?= $fetch_user['ID_Usuario']; ?></div>
                            <div class="name_user"><?= $fetch_user['nombre_Usuario']; ?></div>
                            <?php if ($fetch_user['foto'] != '') { ?>
                                <img src="../uploaded-img/clientes/<?= $fetch_user['foto']; ?>" alt="" class="image">
                            <?php  } ?>
                            <div class=""><?= $fetch_user['nombre_Usuario']; ?></div>
                            <div class="flex-btn">
                                <a href="edit-user.php?id=<?= $fetch_user['ID_Usuario']; ?>" class="btn">Editar</a>
                                <button type="submit" name="delete" class="btn" onclick="return confirm('Desea borrar este usuario?');">Borrar</button>

                            </div>

                        </form>



                <?php
                    }
                } else {
                    echo '  <div class="empty">
                <p>No hay  usuarios aún <br><a href="add-products.php" class="btn" style="margin-top: 1.5rem;">Añada mas</a></p>
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