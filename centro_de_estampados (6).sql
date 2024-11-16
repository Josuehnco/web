-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-11-2024 a las 01:58:07
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `centro de estampados`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE `compras` (
  `id_compras` int(50) NOT NULL,
  `id_tipo_pago` int(225) DEFAULT NULL,
  `precio` int(225) DEFAULT NULL,
  `cantidad` int(225) DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `id_proveedores` int(50) DEFAULT NULL,
  `id_personal` int(50) DEFAULT NULL,
  `id_productos` int(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `compras`
--

INSERT INTO `compras` (`id_compras`, `id_tipo_pago`, `precio`, `cantidad`, `hora`, `id_proveedores`, `id_personal`, `id_productos`) VALUES
(1, 1, 10, 5, '10:00:00', 1, 1, 1),
(2, 2, 500, 10, '11:00:00', 2, 2, NULL),
(3, 3, 300, 15, '12:00:00', 3, 3, NULL),
(4, 4, 400, 20, '13:00:00', 4, 4, NULL),
(5, 5, 700, 25, '14:00:00', 5, 5, NULL),
(6, 6, 600, 30, '15:00:00', 6, 6, NULL),
(7, 2, 900, 12, '09:30:00', 2, 1, NULL),
(8, 1, 1200, 25, '14:15:00', 3, 3, NULL),
(9, 3, 750, 18, '11:45:00', 1, 4, NULL),
(10, 4, 1450, 22, '16:30:00', 5, 2, NULL),
(11, 5, 850, 14, '08:45:00', 6, 5, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `correo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `login`
--

INSERT INTO `login` (`id`, `usuario`, `password`, `correo`) VALUES
(63, 'adalid12345678', '12345678', 'cgnxgfn@gmail.com'),
(64, 'adalid12345678', '12345678', 'cgnxgfn@gmail.com'),
(65, 'adalid12345678', '12345678', 'cgnxgfn@gmail.com'),
(66, 'adalid12345678', '12345678', 'cgnxgfn@gmail.com'),
(67, 'adalid12345678', '12345678', 'cvcqQq@gmail.com'),
(68, 'adalid12345678', '12345678', 'cvcqQq@gmail.com'),
(69, 'adalid12345678', '12345678', 'cgnxgfn@gmail.com'),
(70, 'adalid12345678', '12345678', 'cvcqQq@gmail.com'),
(71, 'adalid12345678', '12345678', 'cgnxgfn@gmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos_estampados`
--

CREATE TABLE `pedidos_estampados` (
  `id_pedido` int(11) NOT NULL,
  `id_usuarios` int(11) NOT NULL,
  `imagen` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `tamano` varchar(50) NOT NULL,
  `fecha_subida` datetime NOT NULL,
  `estado` enum('pendiente','en_proceso','completado','cancelado') DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos_estampados`
--

INSERT INTO `pedidos_estampados` (`id_pedido`, `id_usuarios`, `imagen`, `descripcion`, `tamano`, `fecha_subida`, `estado`) VALUES
(1, 8, 'uploads/estampados/673620cdbac64.jpeg', '', '', '2024-11-14 12:09:49', 'pendiente'),
(2, 8, 'uploads/estampados/67362126894c4.jpeg', '', '', '2024-11-14 12:11:18', 'pendiente'),
(3, 8, 'uploads/estampados/673623d791d8d.jpeg', 'hola', '60cm x 70cm', '2024-11-14 12:22:47', 'pendiente'),
(4, 8, 'uploads/estampados/6736240db32e3.jpeg', 'Un estampado que sea de color amarillo y el dibujo degradado', '60cm x 70cm', '2024-11-14 12:23:41', 'pendiente'),
(5, 8, 'uploads/estampados/673897c760b4b.png', 'de', '60cm x 70cm', '2024-11-16 09:01:59', 'pendiente'),
(6, 1, 'uploads/estampados/67390d536d761.jpg', 'jhgiufoyg', '60cm x 70cm', '2024-11-16 17:23:31', 'pendiente'),
(7, 16, 'uploads/estampados/673bd1dd043bb.jpeg', 'jnkjb', '60cm x 70cm', '2024-11-18 19:46:37', 'pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal`
--

CREATE TABLE `personal` (
  `id_personal` int(225) NOT NULL,
  `ci` int(20) DEFAULT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `telefono` int(50) DEFAULT NULL,
  `gmail` varchar(110) DEFAULT NULL,
  `apellido_p` varchar(50) DEFAULT NULL,
  `apellido_m` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `personal`
--

INSERT INTO `personal` (`id_personal`, `ci`, `nombre`, `telefono`, `gmail`, `apellido_p`, `apellido_m`) VALUES
(1, 13848907, 'josue', 62537809, 'josue@gmail.com', 'huanco', 'huanca'),
(2, 13848907, 'josue', 62537809, 'josue@gmail.com', 'huanco', 'huanca'),
(3, 17652488, 'yugos', 13846734, 'yugos@gmail.com', 'renji', 'alka');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_productos` int(50) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `enlace` varchar(255) DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(50) DEFAULT NULL,
  `id_proveedores` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_productos`, `nombre`, `descripcion`, `imagen`, `enlace`, `precio`, `stock`, `id_proveedores`) VALUES
(1, 'logro', 'logo', 'libreria_imagenes/673a8a02a6a36.png', 'https://www.amazon.com/Jurisdicci%C3%B3n-proceso-civil-Registro-Civil/dp/8430987096/ref=sr_1_1?crid=11GAU3JKL3AVL&dib=eyJ2IjoiMSJ9.GfiDvM9S0Mh5GSsGuIpDwd3vhKZA1OMDNBYMqvl-XugRRY_M5Z_MbytSlITpU1rx.MJXjIeMIpvPqBwS05RuEgU8S-YXx-vSk-S6rI3eSBYI&dib_tag=se&keyw', 150.00, 10, 2),
(17, 'logo', 'logo', 'libreria_imagenes/673a8a64c85fe.png', 'https://www.canva.com/', 33.99, 30, 3),
(18, 'logo', 'logo', 'libreria_imagenes/673a8a4563292.png', 'https://www.canva.com/', 40.00, 150, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `idprov` int(11) NOT NULL COMMENT 'Identificador de producto.',
  `nombre` varchar(100) NOT NULL COMMENT 'Nombre de producto.',
  `direccion` varchar(100) NOT NULL COMMENT 'Stock del producto.',
  `celular` varchar(10) NOT NULL COMMENT 'Numero de celular'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabla de proveedor.';

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`idprov`, `nombre`, `direccion`, `celular`) VALUES
(1, 'PROVEEDOR UNO', ' DIRECCION UNO', '7777777'),
(2, 'PROVEEDOR DOS', ' DIRECCION DOS', '8888888'),
(3, 'PROVEEDOR TRES', ' DIRECCION TRES', '99999999');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registrarse`
--

CREATE TABLE `registrarse` (
  `id_registrarse` int(250) NOT NULL,
  `usuario` text NOT NULL,
  `password` text NOT NULL,
  `correo` varchar(110) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registrarse`
--

INSERT INTO `registrarse` (`id_registrarse`, `usuario`, `password`, `correo`) VALUES
(64, 'adalid12345678', '12345678', 'cgnxgfn@gmail.com'),
(65, 'Josue', '2141521616', 'cvcqQq@gmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_pago`
--

CREATE TABLE `tipo_pago` (
  `idtipo` int(11) NOT NULL COMMENT 'Identificador del tipo de pago.',
  `nombre` varchar(50) NOT NULL COMMENT 'Nombre del tipo de pago.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabla de tipos de pago.';

--
-- Volcado de datos para la tabla `tipo_pago`
--

INSERT INTO `tipo_pago` (`idtipo`, `nombre`) VALUES
(1, 'EFECTIVO'),
(2, 'TARJETA CREDITO'),
(3, 'TARJETA DE DEBITO'),
(4, 'CHEQUE'),
(5, 'NOTA DE CREDITO'),
(6, 'BONO EFECTIVO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuarios` int(250) NOT NULL,
  `usuario` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `id_cliente` int(250) DEFAULT NULL,
  `rol` enum('admin','usuario') DEFAULT 'usuario',
  `correo` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuarios`, `usuario`, `password`, `id_cliente`, `rol`, `correo`) VALUES
(1, 'josue', '1234', NULL, 'admin', 'josue@gmail.com'),
(2, 'yugos', '12345', NULL, 'usuario', 'yugos@gmail.com'),
(3, 'yugos', '12345', NULL, 'usuario', 'yugos@gmail.com'),
(8, 'fernando', '123456', NULL, 'usuario', 'fernando@gmail.com'),
(9, 'adalid12345678', '12345678', NULL, 'usuario', 'josuehuanco15@gmail.com'),
(12, 'adalid12345678', '12345678', NULL, 'usuario', 'josuehuanco15@gmail.com'),
(16, 'josuehnco', '12345', NULL, 'usuario', 'josuehuanc18@gmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

CREATE TABLE `venta` (
  `idventa` int(11) NOT NULL COMMENT 'Identificador de venta.',
  `idemp` int(11) NOT NULL COMMENT 'Identificador del empleado.',
  `cliente` varchar(100) NOT NULL COMMENT 'Nombre del cliente.',
  `fecha` date NOT NULL COMMENT 'Fecha de venta.',
  `importe` decimal(10,2) NOT NULL COMMENT 'Importe de la venta.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabla de ventas.';

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedidos_estampados`
--
ALTER TABLE `pedidos_estampados`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `id_usuario` (`id_usuarios`);

--
-- Indices de la tabla `personal`
--
ALTER TABLE `personal`
  ADD PRIMARY KEY (`id_personal`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_productos`),
  ADD KEY `id_proveedores` (`id_proveedores`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`idprov`);

--
-- Indices de la tabla `registrarse`
--
ALTER TABLE `registrarse`
  ADD PRIMARY KEY (`id_registrarse`);

--
-- Indices de la tabla `tipo_pago`
--
ALTER TABLE `tipo_pago`
  ADD PRIMARY KEY (`idtipo`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuarios`);

--
-- Indices de la tabla `venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`idventa`),
  ADD KEY `FK_VENTA_USUARIO` (`idemp`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT de la tabla `pedidos_estampados`
--
ALTER TABLE `pedidos_estampados`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `personal`
--
ALTER TABLE `personal`
  MODIFY `id_personal` int(225) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_productos` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `idprov` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador de producto.', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `registrarse`
--
ALTER TABLE `registrarse`
  MODIFY `id_registrarse` int(250) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuarios` int(250) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
  MODIFY `idventa` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador de venta.';

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `pedidos_estampados`
--
ALTER TABLE `pedidos_estampados`
  ADD CONSTRAINT `pedidos_estampados_ibfk_1` FOREIGN KEY (`id_usuarios`) REFERENCES `usuarios` (`id_usuarios`);

--
-- Filtros para la tabla `venta`
--
ALTER TABLE `venta`
  ADD CONSTRAINT `FK_VENTA_USUARIO` FOREIGN KEY (`idemp`) REFERENCES `usuario` (`idemp`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
