<?php
include '../components/connect.php';


session_start();

$user_id = $_SESSION['user_id'];
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $name = htmlspecialchars($name);

    $email = $_POST['email'];
    $email = htmlspecialchars($email);

    $payment_method = $_POST['payment_method'];
    $payment_method = htmlspecialchars($payment_method);

    $address = $_POST['address'];
    $address = htmlspecialchars($address);

    $barrio = $_POST['barrio'];
    $barrio = htmlspecialchars($barrio);

    $phone = $_POST['phone'];
    $phone = htmlspecialchars($phone);
    $pass = $_POST['pass'];

    $pass = sha1($_POST['pass']);
    $pass = htmlspecialchars($pass);

    $cpass = $_POST['cpass'];

    $cpass = sha1($_POST['cpass']);
    $cpass = htmlspecialchars($cpass);



    $image = $_FILES['profile-p']['name'];
    $image = htmlspecialchars($image);
    $image_tmp_name = $_FILES['profile-p']['tmp_name'];
    $image_folder = '../uploaded-img/clientes/
    ' . $image;

    $select_admin = $conn->prepare("SELECT * FROM usuario WHERE email_Usuario  = ?");
    $select_admin->execute([$email]);
    if ($select_admin->rowCount() > 0) {
        $warning_msg[] = 'El email del usuario ya existe';
    } else {
        if ($pass != $cpass) {
            $warning_msg[] = 'Las contraseñas no coinciden';
        } else {
            $insert_admin = $conn->prepare("INSERT INTO usuario (nombre_Usuario, email_Usuario, telefono_Usuario, contraseña_Usuario, direccion_Usuario, metodo_pago, barrio_Usuario, foto) VALUES (?, ?, ?, ?,?,?,?,?)");
            $insert_admin->execute([$name, $email, $phone, $cpass, $address, $payment_method, $barrio, $image]);
            move_uploaded_file($image_tmp_name, $image_folder);
            $success_msg[] = 'Registro de Usuario exitoso';
        }
    }
}

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

        <section class="register-container">
            <div>
                <div class="form-container" id="admin_login">
                    <form action="" method="post" enctype="multipart/form-data">
                        <h3>Registro de Persona</h3>
                        <div class="input-field">
                            <label for="name">Nombre Completo <sup>*</sup></label>
                            <input type="text" name="name" maxlength="30" required placeholder="Ingrese nombre completo" oninput="this.value.replace(/\s/g,'') " pattern="^[a-zA-Z ]+$">
                        </div>
                        <div class="input-field">
                            <label for="email"> Email <sup>*</sup></label>
                            <input type="email" name="email" maxlength="25" required placeholder="Ingrese su email" oninput="this.value.replace(/\s/g,'')">
                        </div>
                        <div class="input-field">
                            <label for="phone"> Telefono <sup>*</sup></label>
                            <input type="tel" name="phone" maxlength="10" required placeholder="XXX-XXX-XXXX" oninput="this.value.replace(/\s/g,'')" minlength="10">
                        </div>
                        <div class="input-field">
                            <label for="address">Direccion <sup>*</sup></label>
                            <input type="text" name="address" maxlength="60" required placeholder="Ingrese su Direccion" oninput="this.value.replace(/\s/g,'') ">
                        </div>
                        <div class="input-field">
                            <label for="barrio">Barrio <sup>*</sup></label>
                            <input type="text" name="barrio" maxlength="30" required placeholder="Ingrese su barrio" oninput="this.value.replace(/\s/g,'') ">
                        </div>
                        <div class="input-field">
                            <label for="payment-method">
                                <select name="payment_method" id="payment_method">Metodo de pago </label>
                            <option value="" disabled selected>---Seleccione un metodo de pago------</option>
                            <option value="nequi">nequi</option>
                            <option value="efectivo">efectivo</option>
                            </select>

                        </div>
                        <div class="input-field">
                            <label for="pass">Contraseña<sup>*</sup></label>
                            <input type="password" name="pass" maxlength="20" required placeholder="Ingrese su Contraseña" oninput="this.value.replace(/\s/g,'')">
                        </div>

                        <div class="input-field">
                            <label for="cpass">Confirme la Contraseña<sup>*</sup></label>
                            <input type="password" name="cpass" maxlength="20" required placeholder="Confirme su Contraseña" oninput="this.value.replace(/\s/g,'')">
                        </div>
                        <div class="input-field">
                            <label for="profile-p">Imagen De Perfil</label>
                            <input type="file" name="profile-p" accept="image/*" required title="Debe insertar una imagen">

                        </div>

                        <input type="submit" name="submit" value="Registrarse Ahora" class="btn">
                        <p>¿ya tiene cuenta? logueese <a href="user-login.php">aqui</a>
                        </p>

                    </form>
                </div>
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