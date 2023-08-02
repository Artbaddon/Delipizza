<?php

function hacerConsulta($datos, $consulta)
{
    global $pdo;

    switch ($consulta) {

        case "insertarCategoria":

            $query = "SELECT *  FROM categoria WHERE nombre_Categoria=? AND desc_Categoria=? AND img_Categoria=?";
            $stmt = $pdo->prepare($query);
            for ($i = 0; $i < count($datos); $i++) {
                $stmt->bindValue($i + 1, $datos[$i]);
            }
            $stmt->execute();

            if ($stmt->rowCount() > 0) {

                echo "<script>alert('Categoria duplicada, inserte otra')</script>";
            } else {
                $query = "INSERT INTO categoria (nombre_Categoria, desc_Categoria, img_Categoria) VALUES (?,?,?)";
                $stmt = $pdo->prepare($query);
                for ($i = 0; $i < count($datos); $i++) {
                    $stmt->bindValue($i + 1, $datos[$i]);
                }

                $stmt->execute();

                echo "<script>alert('Categoria Agregada exitosamente')</script>";
            }
            break;
        case "insertarProducto":

            $query = "SELECT * FROM producto WHERE nombre_Producto=? AND precio_Producto=? AND descripcion_Producto=? AND CategoriaID=? AND estado=? AND img_Producto=?";
            $stmt = $pdo->prepare($query);
            for ($i = 0; $i < count($datos); $i++) {
                $stmt->bindValue($i + 1, $datos[$i]);
            }
            $stmt->execute();

            if ($stmt->rowCount() > 0) {

                echo "<script>alert('Producto duplicado, inserte otro')</script>";
            } else {
                $query = "INSERT INTO producto (nombre_Producto, precio_Producto, descripcion_Producto, CategoriaID, estado,img_Producto) VALUES (?,?,?,?,?,?)";
                $stmt = $pdo->prepare($query);
                for ($i = 0; $i < count($datos); $i++) {
                    $stmt->bindValue($i + 1, $datos[$i]);
                }

                $stmt->execute();

                echo "<script>alert('Categoria Agregada exitosamente')</script>";
            }
            break;


        case "consultarEstadoProductos":
            $query = "SELECT * FROM producto WHERE estado=?";

            $stmt = $pdo->prepare($query);

            $stmt->execute([$datos]);
            $total_active_post = $stmt->rowCount();


            return  $total_active_post;

        case "consultarProductos":
            $query = "SELECT * FROM producto";

            $stmt = $pdo->prepare($query);

            $stmt->execute();
            $total_post = $stmt->rowCount();
            return  $total_post;

        case "consultarDireccion":
            $query = "SELECT * FROM direccion WHERE id_usuario=?";

            $stmt = $pdo->prepare($query);

            $stmt->execute([$datos]);
            $total_dir = $stmt->rowCount();
            return  $total_dir;

        case "consultarCategorias":
            $query = "SELECT * FROM Categoria";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $total_post = $stmt->rowCount();

            return  $total_post;
        case 'traerProductosRecomendados':
            $query = "SELECT * FROM producto WHERE CategoriaID=? AND estado=? LIMIT 4";
            $stmt = $pdo->prepare($query);
            $stmt->execute([1, "activo"]);
            if ($stmt->rowCount() > 0) {
                $productos = $stmt->fetchAll();
                $productosArray = array();
                foreach ($productos as $producto) {
                    $productosArray[] = array(
                        'ID' => $producto['ID_producto'],
                        'Nombre' => $producto['nombre_Producto'],
                        'Precio' => $producto['precio_Producto'],
                        'Img' => $producto['img_Producto'],
                        'Descripcion' => $producto['descripcion_Producto'],
                        'Estado' => $producto['estado'],
                        'Categoria' => $producto['CategoriaID']
                    );
                }
            }
            return $productosArray;

        case "traerDireccion":
            $query = "SELECT * FROM direccion WHERE ID_usuario=?";
            $stmt = $pdo->prepare($query);

            $stmt->execute([$datos]);

            if ($stmt->rowCount() > 0) {
                $direccion = $stmt->fetchAll();
                $direccionArray = array();
                foreach ($direccion as $dir) {
                    $direccionArray[] = array(
                        'direccion' => $dir['direccion'],
                        'barrio' => $dir['barrio'],
                        'localidad' => $dir['localidad']

                    );
                }
            }
            return $direccionArray;


        case "traerProductosPrincipales":
            $query = "SELECT * FROM producto WHERE CategoriaID>? AND estado=?";
            $stmt = $pdo->prepare($query);

            $stmt->execute([$datos, "activo"]);

            if ($stmt->rowCount() > 0) {
                $productos = $stmt->fetchAll();
                $productosArray = array();
                foreach ($productos as $producto) {
                    $productosArray[] = array(
                        'ID' => $producto['ID_producto'],
                        'Nombre' => $producto['nombre_Producto'],
                        'Precio' => $producto['precio_Producto'],
                        'Img' => $producto['img_Producto'],
                        'Descripcion' => $producto['descripcion_Producto'],
                        'Estado' => $producto['estado'],
                        'Categoria' => $producto['CategoriaID']
                    );
                }
            }
            return $productosArray;




        case "traerCategorias";

            $query = "SELECT * FROM categoria WHERE ID_Categoria>?";

            $stmt = $pdo->prepare($query);
            $stmt->execute([1]);
            if ($stmt->rowCount() > 0) {
                $categorias = $stmt->fetchAll();
                $categoriasArray = array();
                foreach ($categorias as $categoria) {
                    $categoriasArray[] = array(
                        'ID' => $categoria['ID_Categoria'],
                        'Nombre' => $categoria['nombre_Categoria'],
                        'Descripcion' => $categoria['desc_Categoria'],
                        'foto' => $categoria['img_Categoria'],

                    );
                }
            }
            return $categoriasArray;

        case 'traerUsuarioPerfil':
            $query = "SELECT * FROM usuario WHERE ID_Usuario=?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$datos]);
            if ($stmt->rowCount() > 0) {
                $usuario = $stmt->fetchAll();
                $usuarioArray = array();
                foreach ($usuario as $user) {
                    $usuarioArray[] = array(
                        'ID' => $user['ID_Usuario'],
                        'Nombre' => $user['nombre_Usuario'],
                        'Apellido' => $user['apellido_Usuario'],
                        'Correo' => $user['correo_Usuario'],
                        'Telefono' => $user['telefono_Usuario'],
                        'Direccion' => $user['direccion_Usuario'],

                    );
                }
            }
            return $usuarioArray;

        case 'traerAdmin':
            $query = "SELECT * FROM administrador";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {

                $administrador = $stmt->fetchAll();
                $adminArray = array();
                foreach ($administrador as $admin) {
                    $adminArray[] = array(
                        'id' => $admin['ID_Administrador'],
                        'nombre' => $admin['nombre_Admin'],
                        'email' => $admin['email_Admin'],
                        'foto' => $admin['foto']

                    );
                }
            }
            return $adminArray;
    }
}
