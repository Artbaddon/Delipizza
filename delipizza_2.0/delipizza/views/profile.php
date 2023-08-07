<?php
include '../components/connect.php';
include '../components/queries.php';


session_start();
$user_id = '';
$user_id = $_SESSION['user_id'];
if (!isset($_SESSION['user_id'])) {
    header('location:user-login.php');
    exit();
}


$info_direc = hacerConsulta($user_id, "consultarDireccion");
$datosDir = hacerConsulta($user_id, "traerDireccion");
$datosUsuario = hacerConsulta($user_id, "traerUsuario");

include '../components/profile-controller.php';

?>

<style>
    <?php include '../css/style.css'; ?>
</style>
<!DOCTYPE html>
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
        include '../components/general-header.php';
        ?>
        <aside class="aside-bar">
            <div class="side-container">
                <div class="sidebar">
                    <?php

                    $select_profile = $pdo->prepare("SELECT * FROM usuario WHERE ID_Usuario = ?");
                    $select_profile->execute([$user_id]);

                    if ($select_profile->rowCount() > 0) {
                        $fetch_profile = $select_profile->fetch();

                    ?>
                        <div class="profile">
                            <img src="../uploaded-img/Clientes/<?= $fetch_profile['foto'] ?>" alt="" class="logo-img" width="100">
                            <p><?= $fetch_profile['nombre_Usuario'];   ?></p>
                        </div>
                    <?php } ?>
                    <h5>Menu</h5>
                    <div class="navbar">
                        <ul>
                            <li><a href="profile.php"><i class="bx bxs-home-smile"></i>Editar Datos perfil</a></li>
                            <li><a href="orders.php"><i class="bx bxs-home-smile"></i>Historial Pedidos</a></li>


                            <li><a href="../components/admin-logout.php" onclick="return confirm('¿Salir del sitio?')"><i class="bx bx-log-out"></i> Salir</a></li>
                        </ul>
                    </div>

                </div>
        </aside>

        <section class="container-profile-data" id="editar-perfil-usuario">
            <div class="form-container" id="admin_login">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="profile container-profile-p">
                        <img src="../uploaded-img/clientes/<?= $fetch_profile['foto']; ?>" alt="" class="logo-img profile-p">
                    </div>
                    <h3>Actualizar Datos</h3>
                    <input type="hidden" name="old_img" value="<?= $fetch_profile['foto']; ?>">
                    <div class="input-field">
                        <label for="name">Nombre Completo <sup>*</sup></label>
                        <input type="text" name="name" maxlength="30" placeholder="Ingrese nombre completo" oninput="this.value.replace(/\s/g,'') " pattern="^[a-zA-Z ]+$" value="<?= $fetch_profile['nombre_Usuario']; ?>">
                    </div>


                    <div class="input-field">
                        <label for="email"> Email <sup>*</sup></label>
                        <input type="email" name="email" maxlength="25" placeholder="Ingrese su email" oninput="this.value.replace(/\s/g,'')" value="<?= $fetch_profile['email_Usuario']; ?>">
                    </div>
                    <div class="input-field">
                        <label for="phone"> Telefono <sup>*</sup></label>
                        <input type="number" name="phone" maxlength="10" placeholder="Ingrese su telefono" oninput="this.value.replace(/\s/g,'')" minlength="10" value="<?= $fetch_profile['telefono_Usuario'] ?>">
                    </div>
                    <div class="input-field">
                        <label for="address">Direccion <sup>*</sup></label>
                        <input type="text" name="address" maxlength="60" placeholder="Ingrese su Direccion" oninput="this.value.replace(/\s/g,'') " value="<?php if ($datosDir != null) {
                                                                                                                                                                echo $datosDir['localidad'];
                                                                                                                                                            } ?>" id="direccion">
                    </div>
                    <div class="input-field">
                        <label for="barrio">Barrio <sup>*</sup></label>
                        <input type="text" name="barrio" maxlength="60" placeholder="Ingrese su Barrio" oninput="this.value.replace(/\s/g,'') " value="<?php if ($datosDir != null) {
                                                                                                                                                            echo $datosDir['barrio'];
                                                                                                                                                        } ?>" id="barrio">
                    </div>
                    <div class="input-field">
                        <label for="localidad">localidad <sup>*</sup></label>
                        <input type="text" name="localidad" maxlength="60" placeholder="Ingrese su Localidad" oninput="this.value.replace(/\s/g,'') " value="<?php if ($datosDir != null) {
                                                                                                                                                                    echo $datosDir['localidad'];
                                                                                                                                                                } ?>" id="localidad">
                    </div>


                    <div class="input-field">
                        <label for="payment-method"> Metodo pago</label>
                        <select name="payment-method" id="payment-method">

                            <option selected value="">Anterior metodo de pago: <?= $fetch_profile['metodo_pago']; ?></option>
                            <option value="nequi">nequi</option>
                            <option value="efectivo">efectivo</option>
                        </select>

                    </div>
                    <div class="input-field">
                        <label for="pass">Contraseña Actual<sup>*</sup></label>
                        <input type="password" name="old_pass" maxlength="20" required placeholder="Ingrese Su Actual Contraseña" oninput="this.value.replace(/\s/g,'')">
                    </div>
                    <div class="input-field">
                        <label for="pass">Contraseña Nueva<sup>*</sup></label>
                        <input type="password" name="new_pass" maxlength="20" placeholder="Ingrese Su Nueva Contraseña" oninput="this.value.replace(/\s/g,'')">
                    </div>
                    <div class="input-field">
                        <label for="cpass">Confirme la Contraseña Nueva<sup>*</sup></label>
                        <input type="password" name="cpass" maxlength="20" placeholder="Confirme Su Contraseña" oninput="this.value.replace(/\s/g,'')">
                    </div>
                    <div class="input-field">
                        <label for="profile-p">Imagen De Perfil</label>
                        <input type="file" name="profile-p" accept=".png, .jpg, .jpeg" title="Debe insertar una imagen" value="<?= $fetch_profile['foto']; ?>">

                    </div>
                    <input type="submit" name="submit" value="Actualizar Datos" class="btn">


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