<header>
    <div class="logo">
        <a href="dashboard.php"> <img src="../image/Delipizza-logo-final.jpg" alt="Logo de la tienda Delipizza " width="200"></a>
    </div>
    <div class="right">
        <div class="" id="user-btn"><img src="../image/user-solid-24.png" alt=""></div>
        <div class="toggle-btn"><i class="bx bx-menu"></i></div>
    </div>
    <div class="profile-detail">
        <?php
        $select_profile = $conn->prepare("SELECT * FROM administrador WHERE ID_Administrador = ?");
        $select_profile->execute([$admin_id]);
        if ($select_profile->rowCount() > 0) {
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

        ?>
            <div class="profile">
                <img src="../uploaded-img/admin/<?= $fetch_profile['foto']; ?>" alt="" class="logo-img" width="100">
                <p><?= $fetch_profile['nombre_Admin'];   ?></p>
            </div>
            <div class="flex-btn">
                <a href="update-profile.php" class="btn">Actualizar Datos</a>
                <a href="../components/user-logout.php" class="btn" onclick="return confirm('Logout from the website' )">Logout</a>
            </div>
        <?php } ?>
    </div>
</header>
<div class="side-container">
    <div class="sidebar">
        <?php

        $select_profile = $conn->prepare("SELECT * FROM administrador WHERE ID_Administrador = ?");
        $select_profile->execute([$admin_id]);

        if ($select_profile->rowCount() > 0) {
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

        ?>
            <div class="profile">
                <img src="../uploaded-img/admin/<?= $fetch_profile['foto'] ?>" alt="" class="logo-img" width="100">
                <p><?= $fetch_profile['nombre_Admin'];   ?></p>
            </div>
        <?php } ?>
        <h5>Menu</h5>
        <div class="navbar">
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="add-products.php">Añadir productos</a></li>
                <li><a href="add-category.php">Añadir Categorias</a></li>
                <li><a href="view-products.php">Ver productos</a></li>
                <li><a href="view-category.php">Ver Categorias</a></li>
              

                <li><a href="../components/admin-logout.php" onclick="return confirm('¿Salir del sitio?')"><i class="bx bx-log-out"></i> Salir</a></li>
            </ul>
        </div>
    
    </div>
</div>