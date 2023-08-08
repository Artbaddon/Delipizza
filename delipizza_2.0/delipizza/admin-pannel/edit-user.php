<?php
include '../components/connect.php';
include '../components/queries.php';


session_start();
$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('location:admin-login.php');
}

$user_id = $_GET['id'];



$info_direc = hacerConsulta($user_id, "consultarDireccion");
$datosDir = hacerConsulta($user_id, "traerDireccion");
$datosUsuario = hacerConsulta($user_id, "traerUsuario");


include '../components/profile-controller.php';


?>

<style>
    <?php include '../css/admin-style.css'; ?>
</style>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Box Icon CDN list  -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <title>Actualizar Datos - Usuario</title>
</head>

<body>

    <div class="main-container">
        <?php
        include '../components/admin-header.php';
        $select_profile = $pdo->prepare("SELECT * FROM usuario WHERE ID_Usuario = ?");
        $select_profile->execute([$user_id]);

        if ($select_profile->rowCount() > 0) {
            $fetch_profile = $select_profile->fetch();
        }


        ?>


        <section class="container-profile-data" id="editar-perfil-usuario">
            <div class="form-container" id="admin_login">
                <form action="" method="post" enctype="multipart/form-data">
                    <?php

                    include '../components/form-update.php';

                    ?>
                </form>
            </div>
        </section>

    </div>

    <!-- Sweet alert script -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <!-- Custom JS -->
    <script src="../js/script1.js"></script>
    <?php include '../components/alert.php';


    ?>

</body>

</html>