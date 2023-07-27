<?php
include '../components/connect.php';


session_start();
$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
    header('location:user-login.php');
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
        <?php
        include '../components/general-header.php';
        ?>
        <aside class="aside-bar">
            <div class="side-container">
                <div class="sidebar">
                    <?php

                    $select_profile = $conn->prepare("SELECT * FROM usuario WHERE ID_Usuario = ?");
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

        <section id="historial-pedidos">
            <div class="table-container">
                <h3>Historial de Pedidos</h3>


                <table class="table">
                    <thead>
                        <tr>
                            <th>ID ORDEN</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $select_orders = $conn->prepare("SELECT * FROM orden WHERE ID_Usuario_pedido  = ? ");
                        $select_orders->execute([$user_id]);
                    


                        if ($select_orders->rowCount() > 0) {
                            while ($fetch_orders = $select_orders->fetch()) {
                                $select_product = $conn->prepare("SELECT * FROM producto WHERE ID_producto = ?");
                                $select_product->execute([$fetch_orders['ProductoID']]);
                                $fetch_product = $select_product->fetch(PDO::FETCH_ASSOC);

                                $select_order_detail = $conn->prepare("SELECT * FROM detalles_orden WHERE ID_Usuario_pedido = ?");
                                $select_order_detail->execute([$user_id]);
                                $fetch_order_detail = $select_order_detail->fetch(PDO::FETCH_ASSOC);
                               
                        ?>
                        
                                <tr>
                                    
                                    <td><?= $fetch_order_detail['ID_Orden']; ?></td>
                                    <td><?= $fetch_order_detail['fecha_Orden']; ?></td>
                                    <td><?= $fetch_order_detail['estado']; ?></td>
                                    <td><?= $fetch_product['nombre_Producto']; ?></td>
                                    <td><?= $fetch_orders['cantidad']; ?></td>
                                    <td>$<?= $fetch_orders['precio']; ?></td>


                                </tr>
                            <?php }
                        } else { ?>
                            <tr>
                                <td colspan="3">No hay pedidos</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

        </section>
    </div>
    <?php include '../components/dark.php'; ?>
    <!-- Sweet alert script -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <!-- Custom JS -->
    <script src="../js/script1.js"></script>
    <?php include '../components/alert.php'; ?>

</body>

</html>