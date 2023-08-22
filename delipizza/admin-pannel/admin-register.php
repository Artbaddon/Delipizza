<?php
include '../components/connect.php';
include '../components/queries.php';


session_start();

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $name = htmlspecialchars($name);
    $email = $_POST['email'];
    $email = htmlspecialchars($email);

    $pass = $_POST['pass'];
    $pass = sha1($_POST['pass']);
    $pass = htmlspecialchars($pass);
    $cpass = $_POST['cpass'];
    $cpass = sha1($_POST['cpass']);
    $cpass = htmlspecialchars($cpass);

    $image = $_FILES['profile-p']['name'];
    $image = htmlspecialchars($image);
    $image_tmp_name = $_FILES['profile-p']['tmp_name'];
    $image_folder = '../uploaded-img/admin/' . $image;

    $select_admin = $pdo->prepare("SELECT * FROM administrador WHERE nombre_Admin = ?");
    $select_admin->execute([$name]);
    if ($select_admin->rowCount() > 0) {
        $warning_msg[] = 'El nombre de usuario ya existe';
    } else {
        if ($pass != $cpass) {
            $warning_msg[] = 'Las contraseñas no coinciden';
        } else {
            $insert_admin = $pdo->prepare("INSERT INTO administrador (nombre_Admin, email_Admin, contraseña_Admin, foto) VALUES (?, ?, ?, ?)");
            $insert_admin->execute([$name, $email, $cpass, $image]);
            move_uploaded_file($image_tmp_name, $image_folder);
            $success_msg[] = 'Registro de Administrador exitoso';
        }
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
    <title>Registro - Admin - Delipizza</title>
</head>

<body>

    <div class="main-container">

        <section>
            <div class="form-container" id="admin_login">
                <form action="" method="post" enctype="multipart/form-data">
                    <h3>Registro de admin</h3>
                    <div class="input-field">
                        <label for="name">Nombre Completo <sup>*</sup></label>
                        <input type="text" name="name" maxlength="30" required placeholder="Ingrese nombre completo" oninput="this.value.replace(/\s/g,'') " pattern="^[a-zA-Z ]+$">
                    </div>
                    <div class="input-field">
                        <label for="email"> Email <sup>*</sup></label>
                        <input type="email" name="email" maxlength="25" required placeholder="Ingrese su email" oninput="this.value.replace(/\s/g,'')">
                    </div>
                    <div class="input-field">
                        <label for="password">Contraseña<sup>*</sup></label>
                        <input type="password" name="pass" maxlength="20" required placeholder="Ingrese su Contraseña" oninput="this.value.replace(/\s/g,'')">
                    </div>
                    <div class="input-field">
                        <label for="password">Confirme la Contraseña<sup>*</sup></label>
                        <input type="password" name="cpass" maxlength="20" required placeholder="Confirme su Contraseña" oninput="this.value.replace(/\s/g,'')">
                    </div>
                    <div class="input-field">
                        <label for="profile-p">Imagen De Perfil</label>
                        <input type="file" name="profile-p" accept="image/*" required title="Debe insertar una imagen">

                    </div>
                    <input type="submit" name="submit" value="Registrarse Ahora" class="btn">
                    <p>¿ya tiene cuenta? logueese <a href="admin-login.php">aqui</a>
                    </p>

                </form>
            </div>
        </section>
    </div>
   
    <!-- Sweet alert script -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <!-- Custom JS -->
    <script src="../js/script.js"></script>
    <?php include '../components/alert.php'; ?>

</body>

</html>