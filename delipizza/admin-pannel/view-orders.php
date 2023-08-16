<?php

include '../components/connect.php';
include '../components/queries.php';
session_start();

$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('location:admin-login.php');
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
                        $select_orders = $pdo->prepare("SELECT * FROM orden");
                        $select_orders->execute();

                        if ($select_orders->rowCount() > 0) {
                            $select_order_detail = $pdo->prepare("SELECT * FROM detalles_orden INNER JOIN orden ON detalles_orden.ID_Orden = orden.ID_Orden INNER JOIN producto ON detalles_orden.ID_producto = producto.ID_producto JOIN usuario ON orden.ID_pedido_usuario = usuario.ID_Usuario");
                            $select_order_detail->execute();
                            $fetch_order_detail = $select_order_detail->fetchAll();
                            foreach ($fetch_order_detail as $order_detail) {
                        ?>

                                <tr>
                                    <td><?= $order_detail['ID_orden'];?></td>
                                    <td><?= $order_detail['fecha']; ?></td>
                                    <td><?= $order_detail['estado_Orden']; ?></td>
                                    <td><?= $order_detail['nombre_Producto']; ?></td>
                                    <td><?= $order_detail['cantidad']; ?></td>
                                    <td>$<?= $order_detail['precio_Producto']; ?></td>
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




  
    <script src="../js/script.js"></script>
    <!-- Sweet alert script -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <?php include '../components/alert.php'; ?>

</body>

</html>