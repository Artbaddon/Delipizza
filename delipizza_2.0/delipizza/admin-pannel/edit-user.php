<?php
include '../components/connect.php';


session_start();
$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('location:admin-login.php');
}

$user_id = $_GET['id'];



if (isset($_POST['submit'])) {
    //Actualizar nombre
    $name = $_POST['name'];
    $name = htmlspecialchars($name);
    if (!empty($name)){
        $select_name = $conn->prepare("SELECT * FROM usuario WHERE email_Usuario = ?");
        $select_name->execute([$name]);

        if ($select_name->rowCount() > 0) {
            $warning_msg[] = 'El email de usuario ya existe';
        } else {
            $update_admin = $conn->prepare("UPDATE usuario SET nombre_Usuario = ? WHERE ID_Usuario = ?");
            $update_admin->execute([$name, $user_id]);
            $success_msg[] = 'Nombre de usuario actualizado';
        }
    }
    //Actualizar email
    $email = $_POST['email'];
    $email= htmlspecialchars($email);
    if (!empty($email) and $email != $email) {
        $select_name = $conn->prepare("SELECT * FROM usuario WHERE email_Usuario = ?");
        $select_name->execute([$email]);

        if ($select_name->rowCount() > 0) {
            $warning_msg[] = 'El email ya existe';
        } else {
            $update_user = $conn->prepare("UPDATE usuario SET email_Usuario = ? WHERE ID_Usuario = ?");
            $update_user->execute([$email, $user_id]);
            $success_msg[] = 'Email actualizado';
        }
    }
    //Actualizar foto
    if (!empty($_POST['old_img']) and $_FILES['profile-p']['name'] != '') {
        $old_image = $_POST['old_img'];
        $image = $_FILES['profile-p']['name'];
        $image = htmlspecialchars($image);
        $image_tmp_name = $_FILES['profile-p']['tmp_name'];
        $image_folder = '../uploaded-img/clientes/' . $image;

        $update_image = $conn->prepare("UPDATE usuario SET foto = ? WHERE ID_Usuario = ?");
        $update_image->execute([$image, $user_id]);
        move_uploaded_file($image_tmp_name, $image_folder);
        if ($old_image != $image && $old_image != '') {
            unlink('../uploaded-img/clientes/' . $old_image);
        }
    }

    //Actualizar contraseña
    $empty_password = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
    $select_old_pass = $conn->prepare("SELECT contraseña_Usuario FROM usuario WHERE ID_Usuario = ?");
    $select_old_pass->execute([$user_id]);

    $fetch_prev_pass = $select_old_pass->fetch(PDO::FETCH_ASSOC);
    $prev_pass = $fetch_prev_pass['contraseña_Usuario'];



    $new_pass = sha1($_POST['new_pass']);
    $new_pass = htmlspecialchars($new_pass);

    $cpass = sha1($_POST['cpass']);
    $cpass = htmlspecialchars($cpass);



    if ($prev_pass != $empty_password and $cpass != $empty_password and $new_pass != $empty_password) {

      if ($new_pass != $cpass) {
            $warning_msg[] = 'Las contraseñas no coinciden';
        } else {
            if ($new_pass != $empty_password) {
                $update_pass = $conn->prepare("UPDATE usuario SET contraseña_Usuario = ? WHERE ID_Usuario = ?");
                $update_pass->execute([$new_pass, $user_id]);
                $success_msg[] = 'Contraseña actualizada correctamente';
            } else {
                $warning_msg[] = 'Por favor ingrese una nueva contraseña';
            }
        }
    }

    //Actualizar metodo pago
    $payment_method = $_POST['payment-method'];
    $payment_method = htmlspecialchars($payment_method);
    if (!empty($payment_method) and $_POST['submit']) {
        $update_user = $conn->prepare("UPDATE usuario SET metodo_pago = ? WHERE ID_Usuario = ?");
        $update_user->execute([$payment_method, $user_id]);
        $success_msg[] = 'metodo de pago actualizado';
    }
}
// Actualizar direccion
if (isset($_POST['address'])) {
    $select_old_address = $conn->prepare("SELECT direccion_Usuario FROM usuario WHERE ID_Usuario = ?");
    $select_old_address->execute([$user_id]);
    $fetch_prev_address = $select_old_address->fetch(PDO::FETCH_ASSOC);
    $prev_address = $fetch_prev_address['direccion_Usuario'];
    var_dump($prev_address);

    $address = $_POST['address'];
    $address = htmlspecialchars($address);
    if (!empty($address) and $address != $prev_address) {
        $update_user = $conn->prepare("UPDATE usuario SET direccion_Usuario = ? WHERE ID_Usuario = ?");
        $update_user->execute([$address, $user_id]);
        $success_msg[] = 'Direccion actualizada';
    }
}



// Actualizar Barrio
if (isset($_POST['barrio'])) {

    $barrio = $_POST['barrio'];
    $barrio = htmlspecialchars($barrio);
    $select_old_barrio = $conn->prepare("SELECT barrio_Usuario FROM usuario WHERE ID_Usuario = ?");
    $select_old_barrio->execute([$user_id]);
    $fetch_prev_barrio = $select_old_barrio->fetch(PDO::FETCH_ASSOC);
    $prev_barrio = $fetch_prev_barrio['barrio_Usuario'];
    if (!empty($barrio) and $barrio != $prev_barrio) {
        $update_user = $conn->prepare("UPDATE usuario SET barrio_Usuario = ? WHERE ID_Usuario = ?");
        $update_user->execute([$barrio, $user_id]);
        $success_msg[] = 'Barrio actualizado';
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

    <title>Actualizar Datos - Usuario</title>
</head>

<body>

    <div class="main-container">
        <?php
        include '../components/admin-header.php'; ?>


        <section class="container-profile-data" id="editar-perfil-usuario">

            <div class="form-container" id="admin_login">
                <form action="" method="post" enctype="multipart/form-data">
                    <?php
                    $select_profile = $conn->prepare("SELECT * FROM usuario WHERE ID_Usuario = ?");
                    $select_profile->execute([$user_id]);

                    if ($select_profile->rowCount() > 0) {
                        $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
                    }

                    ?>
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
                        <input type="number" name="phone" maxlength="10" placeholder="Ingrese su telefono" oninput="this.value.replace(/\s/g,'')" minlength="10" value="<?= $fetch_profile['telefono_Usuario']; ?>">
                    </div>
                    <div class="input-field">
                        <label for="address">Direccion <sup>*</sup></label>
                        <input type="text" name="address" maxlength="60" placeholder="Ingrese su Direccion" oninput="this.value.replace(/\s/g,'') " value="<?= $fetch_profile['direccion_Usuario']; ?>">
                    </div>
                    <div class="input-field">
                        <label for="barrio">Barrio <sup>*</sup></label>
                        <input type="text" name="barrio" maxlength="30" placeholder="Ingrese su barrio" oninput="this.value.replace(/\s/g,'') " value="<?= $fetch_profile['barrio_Usuario']; ?>">
                    </div>
                    <div class="input-field">
                        <label for="payment-method">
                            <select name="payment-method" id="">

                                <option selected value="">Anterior metodo de pago: <?= $fetch_profile['metodo_pago']; ?></option>
                                <option value="nequi">nequi</option>
                                <option value="efectivo">efectivo</option>
                            </select>
                        </label>
                    </div>
               <!--  -->
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
    <?php include '../components/alert.php'; ?>

</body>

</html>