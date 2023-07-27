-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 27-07-2023 a las 15:58:48
-- Versión del servidor: 8.0.31
-- Versión de PHP: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: delipizza-db
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla administrador
--

DROP TABLE IF EXISTS administrador;
CREATE TABLE IF NOT EXISTS administrador (
  ID_Administrador int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'En esta casilla se guardara la ID del administrador, la cual se incrementa automaticamente.',
  nombre_Admin varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL COMMENT 'Se guarda el nombre del administrador ',
  email_Admin varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL COMMENT 'Se guarda el email del administrador para las credenciales de ingreso',
  contraseña_Admin varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL COMMENT 'Se guarda la contraseña del administrador la cual esta cifrada usando sha1 de php',
  foto varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL COMMENT 'Se guarda el nombre y el tipo de archivo de la foto subida por el administrador',
  PRIMARY KEY (ID_Administrador)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci COMMENT='Se guardan las credenciales de acceso del administrador para que este mismo pueda acceder al aplicativo con la vista de administrador.';

--
-- Volcado de datos para la tabla administrador
--

INSERT INTO administrador (ID_Administrador, nombre_Admin, email_Admin, contraseña_Admin, foto) VALUES
(1, 'admin134565', 'admin12346@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'Zombatar_1.jpg'),
(3, 'Pepe C SA', 'admin@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'ima1.png'),
(4, 'JULIANSEL MANOTAS', 'julian@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'XaDe.gif'),
(5, 'Joan David', 'j.stiven.avila@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'ima1.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla categoria
--

DROP TABLE IF EXISTS categoria;
CREATE TABLE IF NOT EXISTS categoria (
  ID_Categoria int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Se guarda el numero de identificador de la categoria, la cual es de incremento automaticamente',
  nombre_Categoria varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL COMMENT 'Se guarda el nombre de la categoria el cual es un string',
  desc_Categoria varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL COMMENT 'Se guarda la descripcion del producto con tipo string, de maximo 255 caracteres',
  img_Categoria varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL COMMENT 'Se almacena el nombre y tipo de archivo de la imagen',
  PRIMARY KEY (ID_Categoria)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci COMMENT='Se guardan la informacion relacionada con la categoria';

--
-- Volcado de datos para la tabla categoria
--

INSERT INTO categoria (ID_Categoria, nombre_Categoria, desc_Categoria, img_Categoria) VALUES
(1, 'Destacados', '¿Te gustan las hamburguesas, las papas fritas y los refrescos? Entonces te encantarán los productos ', ''),
(2, 'Pizzas', 'La pizza es una de las opciones más populares y deliciosas de la comida rápida. En nuestra tienda virtual, podrás encontrar una gran variedad de pizzas para todos los gustos y ocasiones.', 'pizza5.jpeg'),
(3, 'Hamburguesas', '¿Te apetece una hamburguesa deliciosa y jugosa? En nuestra tienda online encontrarás las mejores opc', 'pexels-dana-tentis-552056.jpg'),
(4, 'Salchipapas', 'Las salchipapas son un plato típico de la comida rápida que consiste en papas fritas y salchichas co', 'salchipapa.jpeg'),
(5, 'Acompañamientos', 'Los acompañamientos son una parte esencial de cualquier comida rápida, ya que complementan el sabor ', 'empanada.jpg'),
(6, 'Perros Calientes', 'Los perros calientes son un plato típico de la comida rápida que consiste en una salchicha cocida o ', 'perro1.jpg'),
(7, 'Panzerottis', 'Los panzerotti son una delicia italiana que consisten en una masa frita rellena de queso y otros ing', 'panzerotti1.jpg'),
(8, 'Lasaña', 'La lasaña es un plato típico de la cocina italiana que consiste en capas de pasta rellenas de carne,', 'lasaña1.jpg'),
(9, 'Mazorcadas', 'La categoría mazorcada ofrece una variedad de platos elaborados con maíz tierno, un ingrediente típi', 'Mazorcada.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla detalles_orden
--

DROP TABLE IF EXISTS detalles_orden;
CREATE TABLE IF NOT EXISTS detalles_orden (
  ID_Orden int UNSIGNED NOT NULL COMMENT 'LLave foranea del id de orden correspondiente a la tabla orden',
  ID_producto int UNSIGNED NOT NULL,
  cantidad tinyint NOT NULL,
  precio_unitario float NOT NULL COMMENT 'Precio unitario de cada producto',
  KEY Orden_User (ID_Orden),
  KEY ID_producto_detalle (ID_producto)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci COMMENT='Detalles de la orden el cual depende de la tabla orden para poder ingresar la informacion';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla direccion
--

DROP TABLE IF EXISTS direccion;
CREATE TABLE IF NOT EXISTS direccion (
  ID_direccion int UNSIGNED NOT NULL AUTO_INCREMENT,
  ID_usuario int UNSIGNED NOT NULL,
  direccion varchar(30) NOT NULL,
  barrio varchar(30) NOT NULL,
  localidad varchar(20) NOT NULL,
  PRIMARY KEY (ID_direccion),
  KEY ID_usuario_direccion (ID_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla orden
--

DROP TABLE IF EXISTS orden;
CREATE TABLE IF NOT EXISTS orden (
  ID_orden int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador unico de la orden el cual es un int',
  ID_pedido_usuario int UNSIGNED NOT NULL,
  estado_Orden varchar(30) COLLATE utf8mb3_spanish_ci NOT NULL,
  fecha timestamp NOT NULL,
  PRIMARY KEY (ID_orden),
  KEY IDPerdido_usuario (ID_pedido_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci COMMENT='En esta tabla se guardan las ordenes  que hace cada cliente';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla producto
--

DROP TABLE IF EXISTS producto;
CREATE TABLE IF NOT EXISTS producto (
  ID_producto int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Numero de identificacion unico por cada producto, de formato integer',
  CategoriaID int UNSIGNED NOT NULL COMMENT 'Llave foranea correspondiente a la categoria a la que pertenece cada producto',
  nombre_Producto varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL COMMENT 'Se guarda el nombre de cada producto, es un string de maximo 30 valores y acepta caracteres extraños',
  img_Producto varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL COMMENT 'Se guarda la imagen de cada producto, nombre y tipo de archivo, maximo 100 caracteres',
  descripcion_Producto varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL COMMENT 'La descripcion correspondiente al producto, de maximo 100 caracteres',
  precio_Producto float NOT NULL COMMENT 'El precio unitario de cada producto',
  estado varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL COMMENT 'Se tienen 2 estados, activo e inactivo, no se borra sino que se pone en inactivo',
  PRIMARY KEY (ID_producto),
  KEY IDCategoria_Producto (CategoriaID)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci COMMENT='Se guardan los productos y la informacion relacionada a ellos.';

--
-- Volcado de datos para la tabla producto
--

INSERT INTO producto (ID_producto, CategoriaID, nombre_Producto, img_Producto, descripcion_Producto, precio_Producto, estado) VALUES
(2, 6, 'PERRO Sencillo', 'hotdog slider.jpg', 'Es un perro con queso, jamon, salsa bbq y jamon', 482, 'activo'),
(3, 7, 'Cupidatat aliquip voluptas est', 'hamburguesa2.jfif', 'Aliquam in asperiore', 830, 'inactivo'),
(4, 3, 'Lorem 10', 'pizza_Mh3H4eanyBKEsStv1YclPWTf9OUqIi-1024x683.png.webp', '  Lorem ipsum doloraaaaaaaaaaaaaaaaaaaa', 1234, 'inactivo'),
(16, 1, 'Pizza Carbonara', 'carbonara.jpeg', ' La clásica pizza con salsa de tomate, queso mozzarella y albahaca fresca. Ideal para los amantes de', 40000, 'activo'),
(17, 1, 'Pizza Barbacoa', 'R.jpeg', ' Una pizza con mucho sabor, cubierta de salsa barbacoa, carne picada, bacon, cebolla y queso cheddar', 1234, 'activo'),
(18, 1, 'Hamburguesa Clasica', 'R (1).jpeg', 'la más sencilla y tradicional, con lechuga, tomate, queso y salsa de tomate. Ideal para los que busc', 4111, 'activo'),
(19, 3, ' Hamburguesa BBQ', 'bbq burger.jpeg', 'una hamburguesa con un toque ahumado y picante, con bacon, cebolla caramelizada, queso cheddar y sal', 123123, 'activo'),
(20, 3, 'Hamburguesa vegana', 'vegan burger.jpeg', 'una hamburguesa 100% vegetal, con una jugosa y sabrosa hamburguesa de soja, lechuga, tomate, pepinil', 55555, 'activo'),
(21, 1, 'Hamburguesa mexicana', 'mex b.jpeg', 'Una hamburguesa con un toque picante y exótico, con carne de vacuno especiada, guacamole, jalapeños,', 44441, 'activo'),
(22, 2, 'Pizza jaon QUeso', 'pizza.jpeg', 'Una deliciosa combinación de queso mozzarella fundido y jamón cocido sobre una masa crujiente y fina', 555, 'activo'),
(23, 2, ' Pizza de cuatro quesos', 'pizza_Mh3H4eanyBKEsStv1YclPWTf9OUqIi-1024x683.png.webp', 'Una pizza para los más queseros, con cuatro variedades de queso diferentes: mozzarella, parmesano, g', 14141, 'activo'),
(24, 4, 'Salchipapa Clasica', 'salchipapa.jpeg', 'La salchipapa clásica lleva salchichas de carne de res y cerdo, papas fritas crocantes, salsa de tom', 45141, 'activo'),
(25, 4, 'Salchipapa Especial', 'salchipapa2.jpg', 'La salchipapa especial lleva salchichas de pollo y pavo, papas fritas rústicas, salsa de ají, salsa ', 142412, 'activo'),
(26, 7, 'Panzerotti Especial', 'panzerotti-italiens.jpg', 'Consiste en una masa de harina rellena de queso, tomate y otros ingredientes al gusto, que se fríe o', 12312, 'activo'),
(27, 5, 'Empanada', 'empanada.jpg', 'Una empanada es un delicioso bocado que consiste en una masa rellena de carne, queso, verduras o fru', 12331, 'activo'),
(28, 8, 'Lasagna Clasica', 'lasaña1.jpg', 'Es ideal para compartir con l za familia o los amigos, ya que se puede hacer en grandes cantidades y s', 123123, 'activo'),
(29, 9, 'Mazorcada Sencilla', 'Mazorcada.jpg', 'La mazorcada sencilla consiste en una mazorca de maíz cocida y cubierta con queso, mantequilla, sal ', 123123, 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla usuario
--

DROP TABLE IF EXISTS usuario;
CREATE TABLE IF NOT EXISTS usuario (
  ID_Usuario int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Numero de identificador unico de cada usuario, es autoincremental y de formato int',
  nombre_Usuario varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL COMMENT 'nombre del usuario, no se aceptan numeros y es de formato string, tiene como maximo 100 caracteres  ',
  email_Usuario varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL COMMENT 'El email correspondiente al usuario, tipo string y solo 30 caracteres maximo                                       ',
  telefono_Usuario varchar(12) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL COMMENT 'Telefono del usuario, maximo 12 caracteres y solo tipo numerico',
  contraseña_Usuario varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL COMMENT 'contraseña del usuario la cual tiene encriptacion sha1 para evitar filtraciones de datos',
  foto varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL COMMENT 'Se guarda la imagen de cada clientes, nombre y tipo de archivo, maximo 100 caracteres',
  metodo_pago varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL COMMENT 'metodo de pago de cada cliente, hay 2 tipos: efectivo y nequi',
  PRIMARY KEY (ID_Usuario)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci COMMENT='Se guardan las credenciales de los usuarios, asi como los datos correspondientes a facturacion y envio.';

--
-- Volcado de datos para la tabla usuario
--

INSERT INTO usuario (ID_Usuario, nombre_Usuario, email_Usuario, telefono_Usuario, contraseña_Usuario, foto, metodo_pago) VALUES
(2, 'Pepe Pistola', 'sebarsomo@gmail.com', '3215123123', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'ima1.png', 'efectivo'),
(3, 'aaaaaaaaaa', 'aaaa@gmail.com', '555555555555', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'california.png', ''),
(4, 'Victor', 'juan@gmail.com', '1231231231', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Zombatar_1.jpg', ''),
(6, 'Marco Leon Mora', 'mlmora@sena.edu.co', '1231412412', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'ima1.png', 'nequi');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla detalles_orden
--
ALTER TABLE detalles_orden
  ADD CONSTRAINT ID_orden_detalle FOREIGN KEY (ID_Orden) REFERENCES orden (ID_orden) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT ID_producto_detalle FOREIGN KEY (ID_producto) REFERENCES producto (ID_producto) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Filtros para la tabla direccion
--
ALTER TABLE direccion
  ADD CONSTRAINT ID_usuario FOREIGN KEY (ID_usuario) REFERENCES usuario (ID_Usuario) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Filtros para la tabla orden
--
ALTER TABLE orden
  ADD CONSTRAINT IDPerdido_usuario FOREIGN KEY (ID_pedido_usuario) REFERENCES usuario (ID_Usuario) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Filtros para la tabla producto
--
ALTER TABLE producto
  ADD CONSTRAINT IDCategoria_Producto FOREIGN KEY (CategoriaID) REFERENCES categoria (ID_Categoria) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
