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
            $query = "SELECT * FROM direccion WHERE ID_usuario=?";

            $stmt = $pdo->prepare($query);

            $stmt->execute([$datos]);
            $total_dir = $stmt->rowCount();

            return  $total_dir;

        case "consultarUsuarios":
            $query = "SELECT * FROM usuario";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $total_user = $stmt->rowCount();
            return $total_user;

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
            $direccion = null;
            $query = "SELECT * FROM direccion WHERE ID_usuario=?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$datos]);

            if ($stmt->rowCount() > 0) {
                $direccion = $stmt->fetch();
            }
            return $direccion;


        case "traerProductosID":
            $query = "SELECT * FROM producto WHERE ID_producto = ?";
            $stmt = $pdo->prepare($query);

            $stmt->execute([$datos]);

            if ($stmt->rowCount() > 0) {
                $productos = $stmt->fetch();
            
            }
            return $productos;
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


        case 'traerUsuario':
            $query = "SELECT * FROM USUARIO WHERE ID_Usuario=?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$datos]);
            if ($stmt->rowCount() > 0) {
                $usuario = $stmt->fetch();
            }
            return $usuario;

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

        case 'traerOrden':
            $query = "SELECT * FROM detalles_orden INNER JOIN orden ON detalles_orden.ID_Orden= orden.ID_orden INNER JOIN producto ON detalles_orden.ID_producto = producto.ID_producto INNER JOIN usuario ON orden.ID_usuario = usuario.ID_Usuario WHERE orden.ID_usuario=?;";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$datos]);
            if ($stmt->rowCount() > 0) {
                $ordenes = $stmt->fetchAll();
                $ordenesArray = array();
                foreach ($ordenes as $orden) {
                    $ordenesArray[] = array(
                        'id' => $orden['ID_orden'],
                        'fecha' => $orden['fecha'],
                        'ID_Producto' => $orden['ID_producto'],
                        'cantidad' => $orden['cantidad'],
                        'precio' => $orden['precio_unitario'],
                        'nombre_producto' => $orden['nombre_Producto'],
                        'img_producto' => $orden['img_Producto'],
                        'nombre_usuario' => $orden['nombre_Usuario'],
                        'correo_usuario' => $orden['email_Usuario'],
                        'descripcion_producto' => $orden['descripcion_Producto'],
                        'estado_producto' => $orden['estado'],
                        'categoria_producto' => $orden['CategoriaID']
                    );
                }
            }
            return $ordenesArray;
    }
}
