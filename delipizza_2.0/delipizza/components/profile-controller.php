<?php


if (isset($_POST['submit'])) {
    //Actualizar nombre
    $name = $_POST['name'];
    $name = htmlspecialchars($name);
    if (!empty($name) and $name != $name) {
        $select_name = $pdo->prepare("SELECT * FROM usuario WHERE email_Usuario = ?");
        $select_name->execute([$name]);

        if ($select_name->rowCount() > 0) {
            $warning_msg[] = 'El email de usuario ya existe';
        } else {
            $update_admin = $pdo->prepare("UPDATE usuario SET nombre_Usuario = ? WHERE ID_Usuario = ?");
            $update_admin->execute([$name, $user_id]);
            $success_msg[] = 'Nombre de usuario actualizado';
        }
    }
    //Actualizar email
    $email = $_POST['email'];
    $name = htmlspecialchars($email);
    if (!empty($email) and $email != $email) {
        $select_name = $pdo->prepare("SELECT * FROM usuario WHERE email_Usuario = ?");
        $select_name->execute([$email]);

        if ($select_name->rowCount() > 0) {
            $warning_msg[] = 'El email ya existe';
        } else {
            $update_user = $pdo->prepare("UPDATE usuario SET email_Usuario = ? WHERE ID_Usuario = ?");
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

        $update_image = $pdo->prepare("UPDATE usuario SET foto = ? WHERE ID_Usuario = ?");
        $update_image->execute([$image, $user_id]);
        move_uploaded_file($image_tmp_name, $image_folder);
        if ($old_image != $image && $old_image != '') {
            unlink('../uploaded-img/clientes/' . $old_image);
        }
    }

    //Actualizar contraseña
    $empty_password = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
    $select_old_pass = $pdo->prepare("SELECT contraseña_Usuario FROM usuario WHERE ID_Usuario = ?");
    $select_old_pass->execute([$user_id]);

    $fetch_prev_pass = $select_old_pass->fetch(PDO::FETCH_ASSOC);
    $prev_pass = $fetch_prev_pass['contraseña_Usuario'];

    $old_pass = sha1($_POST['old_pass']);
    $old_pass = htmlspecialchars($old_pass);

    $new_pass = sha1($_POST['new_pass']);
    $new_pass = htmlspecialchars($new_pass);

    $cpass = sha1($_POST['cpass']);
    $cpass = htmlspecialchars($cpass);



    if ($prev_pass != $empty_password and $cpass != $empty_password and $new_pass != $empty_password) {

        if ($old_pass != $prev_pass) {
            $warning_msg[] = 'Contraseña anterior incorrecta';
        } elseif ($new_pass != $cpass) {
            $warning_msg[] = 'Las contraseñas no coinciden';
        } else {
            if ($new_pass != $empty_password) {
                $update_pass = $pdo->prepare("UPDATE usuario SET contraseña_Usuario = ? WHERE ID_Usuario = ?");
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
        $update_user = $pdo->prepare("UPDATE usuario SET metodo_pago = ? WHERE ID_Usuario = ?");
        $update_user->execute([$payment_method, $user_id]);
        $success_msg[] = 'metodo de pago actualizado';
    }
}


// Insertar Direccion:

if (isset($_POST['localidad']) and isset($_POST['address']) and isset($_POST['barrio'])) {
    $address = $_POST['address'];
    $address = htmlspecialchars($address);
    $barrio = $_POST['barrio'];
    $barrio = htmlspecialchars($barrio);
    $localidad = $_POST['localidad'];
    $localidad = htmlspecialchars($localidad);
    if ($info_direc > 0) {
        $old_address = $datosDir['direccion'];
        $old_barrio = $datosDir['barrio'];
        $old_localidad = $datosDir['localidad'];
        // Actualizar Direccion
        if (isset($_POST['address']) and $address != $old_address) {
            $update_dir = $pdo->prepare("UPDATE direccion SET direccion = ? WHERE ID_usuario = ?");
            $update_dir->execute([$address, $user_id]);
            if ($update_dir->rowCount() > 0) {
                $success_msg[] = 'Direccion actualizada';
            } else {
                $warning_msg[] = 'fallo al actualizar direccion';
            }
        }
        // Actualizar Barrio
        if (isset($_POST['barrio']) and $barrio != $old_barrio) {
            $update_dir = $pdo->prepare("UPDATE direccion SET barrio = ? WHERE ID_usuario = ?");
            $update_dir->execute([$barrio, $user_id]);
            if ($update_dir->rowCount() > 0) {
                $success_msg[] = 'Barrio actualizado';
            } else {
                $warning_msg[] = 'fallo al actualizar barrio';
            }
        }
        // Actualizar Localidad
        if ($localidad != $old_localidad) {
            $update_dir = $pdo->prepare("UPDATE direccion SET localidad = ? WHERE ID_usuario = ?");
            $update_dir->execute([$old_localidad, $user_id]);
            if ($update_dir->rowCount() > 0) {
                $success_msg[] = 'Localidad actualizada';
            } else {
                $warning_msg[] = 'fallo al actualizar localidad';
            }
        }
    }



    if (empty($address)   or empty($barrio)  or empty($localidad)) {
        if ($info_direc == 0) {
            $insert_dir = $pdo->prepare("INSERT INTO direccion(ID_usuario,direccion,barrio,localidad) VALUES(?,?,?,?) ");
            $insert_dir->execute([$user_id, $address, $barrio, $localidad]);
            if ($insert_dir->rowCount() > 0) {

                $success_msg[] = 'direccion agregada a db';
            } else {
                $warning_msg[] = 'fallo all insertar direccion';
            }
        } else {
            $warning_msg[] = 'La direccion ya existe';
        }
    } elseif (empty($address)  or empty($barrio)  or empty($localidad)) {
        $warning_msg[] = 'Por favor ingrese todos los campos de direccion';
    }
}
