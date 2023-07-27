<?php
include '../components/connect.php';


session_start();

if (isset($_POST['submit'])) {

    $email = $_POST['email'];
    $email = htmlspecialchars($email);

    $pass = $_POST['pass'];
    $pass = sha1($_POST['pass']);
    $pass = htmlspecialchars($pass);


    $select_admin = $conn->prepare("SELECT * FROM usuario WHERE email_Usuario = ? AND contraseña_Usuario = ?");
    $select_admin->execute([$email, $pass]);
    if ($select_admin->rowCount() > 0) {
        $fetch_admin_id = $select_admin->fetch(PDO::FETCH_ASSOC);
        $_SESSION['user_id'] = $fetch_admin_id['ID_Usuario'];
        header('location:index.php');   
    } else {
        $warning_msg[] = 'El email   o la contraseña son incorrectos';
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
    <title>Admin - Delipizza</title>
</head>

<body>

    <div class="main-container">

        <section class="form-container" id="admin_login">
            <form action="" method="post" enctype="multipart/form-data">

                <h3>Bienvenido!</h3>

                <div class="input-field">
                    <label for="email"> Email <sup>*</sup></label>
                    <input type="email" name="email" maxlength="25" required placeholder="Ingrese su email" oninput="this.value.replace(/\s/g,'')">
                </div>
                <div class="input-field">
                    <label for="password">Contraseña<sup>*</sup></label>
                    <input type="password" name="pass" maxlength="20" required placeholder="Ingrese su Contraseña" oninput="this.value.replace(/\s/g,'')">

                    <input type="submit" name="submit" class="submit" value="Logueese Ahora" class="btn">
                    <p>¿No tienes cuenta? Registrate <a href="user-register.php">acá</a></p>
                </div>

            </form>

        </section>
    </div>
    <?php include '../components/dark.php'; ?>
    <!-- Sweet alert script -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <!-- Custom JS -->
    <script src="../js/script1.js"></script>
    <?php include '../components/alert.php'; ?>
    <!-- Sweet alert script -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


</body>

</html>