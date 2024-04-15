-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-03-2021 a las 21:02:59
-- Versión del servidor: 10.4.17-MariaDB
-- Versión de PHP: 7.4.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `php_login_database`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asiento`
--

CREATE TABLE `asiento` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `detalle` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `asiento`
--

INSERT INTO `asiento` (`id`, `fecha`, `detalle`) VALUES
(179, '2021-02-22', 'Pago de sueldo al empleado Kowalski'),
(222, '2021-03-04', 'Pago de sueldo al empleado Kowalski'),
(250, '2021-03-07', 'Pago de sueldo al empleado Quito');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bono`
--

CREATE TABLE `bono` (
  `id` int(11) NOT NULL,
  `nombre` varchar(250) NOT NULL,
  `descripcion` varchar(250) NOT NULL,
  `importe` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentaasiento`
--

CREATE TABLE `cuentaasiento` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `debe` float NOT NULL,
  `haber` float NOT NULL,
  `idCuenta` int(11) NOT NULL,
  `idAsiento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `cuentaasiento`
--

INSERT INTO `cuentaasiento` (`id`, `fecha`, `debe`, `haber`, `idCuenta`, `idAsiento`) VALUES
(288, '2021-02-22', 36800, 0, 530, 179),
(289, '2021-03-22', 0, 36800, 113, 179),
(290, '2021-03-04', 36800, 0, 530, 222),
(291, '2021-03-04', 0, 36800, 111, 222),
(320, '2021-03-07', 56800, 0, 530, 250),
(321, '2021-03-07', 0, 56800, 113, 250);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentas`
--

CREATE TABLE `cuentas` (
  `id` int(11) NOT NULL,
  `cuenta` varchar(250) NOT NULL,
  `codigo` int(11) NOT NULL,
  `tipo` varchar(2) NOT NULL,
  `recibeSaldo` int(11) NOT NULL,
  `saldoActual` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `cuentas`
--

INSERT INTO `cuentas` (`id`, `cuenta`, `codigo`, `tipo`, `recibeSaldo`, `saldoActual`) VALUES
(1, 'Activo', 100, 'Ac', 0, 0),
(2, 'Caja', 111, 'Ac', 1, 0),
(3, 'Banco Plazo Fijo', 112, 'Ac', 1, 0),
(4, 'Banco Cuenta Corriente', 113, 'Ac', 1, 0),
(6, 'Deudores por Venta', 121, 'Ac', 1, 0),
(7, 'Documentos a Cobrar', 122, 'Ac', 1, 0),
(8, 'Valores a Depositar', 123, 'Ac', 1, 0),
(10, 'Mercaderias', 131, 'Ac', 1, 0),
(12, 'Inmuebles', 141, 'Ac', 1, 0),
(13, 'Rodados', 142, 'Ac', 1, 0),
(14, 'Instalaciones', 143, 'Ac', 1, 0),
(15, 'Deudas Comerciales', 210, 'Pa', 0, 0),
(16, 'Proveedores', 211, 'Pa', 1, 0),
(17, 'Sueldos a Pagar', 212, 'Pa', 1, 0),
(18, 'Deudas Fiscales', 220, 'Pa', 0, 0),
(19, 'Impuestos a Pagar', 221, 'Pa', 1, 0),
(20, 'Moratorias', 222, 'Pa', 1, 0),
(21, 'Prestamos Bancarios', 230, 'Pa', 1, 0),
(22, 'Capital', 310, 'Pm', 1, 0),
(23, 'Resultados', 320, 'Pm', 1, 0),
(24, 'Ventas', 410, 'R+', 0, 0),
(25, 'Ventas', 411, 'R+', 1, 0),
(26, 'Otros Intereses', 420, 'R+', 0, 0),
(27, 'Intereses Ganados', 430, 'R+', 1, 0),
(28, 'Costo de Mercadería Vendida (C.M.V)', 510, 'R-', 1, 0),
(29, 'Impuestos', 520, 'R-', 1, 0),
(30, 'Sueldos', 530, 'R-', 1, 0),
(31, 'Intereses', 540, 'R-', 1, 0),
(32, 'Alquileres', 550, 'R-', 1, 0),
(41, 'Caja y Banco', 110, 'Ac', 0, 0),
(48, 'Creditos', 120, 'Ac', 0, 0),
(49, 'Bienes de Cambio', 130, 'Ac', 0, 0),
(50, 'Bienes de Uso', 140, 'Ac', 0, 0),
(51, 'Pasivo', 200, 'Pa', 0, 0),
(52, 'Patrimonio', 300, 'Pm', 0, 0),
(53, 'Egresos', 500, 'R-', 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `id` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `apellido` varchar(200) NOT NULL,
  `direccion` varchar(200) NOT NULL,
  `telefono` varchar(200) NOT NULL,
  `fechadenacimiento` date NOT NULL,
  `fechaDeIngreso` date NOT NULL DEFAULT '2020-12-01',
  `dni` int(11) NOT NULL,
  `estadocivil` varchar(200) NOT NULL,
  `cantidadhijos` int(11) NOT NULL,
  `cuil` bigint(11) NOT NULL,
  `puesto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`id`, `nombre`, `apellido`, `direccion`, `telefono`, `fechadenacimiento`, `fechaDeIngreso`, `dni`, `estadocivil`, `cantidadhijos`, `cuil`, `puesto`) VALUES
(1, 'Agustin', 'Kowalski', 'Piedras 853', '2474455903', '1998-11-22', '2020-12-01', 41645590, 'Soltero', 0, 20416455903, 1),
(5, 'Esteban', 'Quito', 'Calle Falsa 123', '2477155365', '1997-10-12', '2020-12-23', 40321654, 'Soltero', 1, 2040321654, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagosanteriores`
--

CREATE TABLE `pagosanteriores` (
  `id` int(11) NOT NULL,
  `idEmpleado` int(11) NOT NULL,
  `sueldoMinimo` int(11) NOT NULL,
  `sueldoCobrado` int(11) NOT NULL,
  `horasExtras` int(11) NOT NULL,
  `feriadosTrabajados` int(11) NOT NULL,
  `bono` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `tipoPago` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Con esta tabla se genera devuelta el Recibo de Sueldo';

--
-- Volcado de datos para la tabla `pagosanteriores`
--

INSERT INTO `pagosanteriores` (`id`, `idEmpleado`, `sueldoMinimo`, `sueldoCobrado`, `horasExtras`, `feriadosTrabajados`, `bono`, `fecha`, `tipoPago`) VALUES
(2, 1, 50000, 36800, 0, 0, 0, '2021-02-22', 'Banco'),
(15, 1, 50000, 36800, 0, 0, 0, '2021-03-04', 'caja'),
(16, 5, 50000, 56800, 20000, 0, 0, '2021-03-07', 'banco');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `puestoempleado`
--

CREATE TABLE `puestoempleado` (
  `id` int(11) NOT NULL,
  `nombre` varchar(250) NOT NULL,
  `descripcion` varchar(250) NOT NULL,
  `sueldoMinimo` int(11) NOT NULL,
  `visibilidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `puestoempleado`
--

INSERT INTO `puestoempleado` (`id`, `nombre`, `descripcion`, `sueldoMinimo`, `visibilidad`) VALUES
(1, 'Gerente de Ventas', 'Encargado de manejar todas las ventas de la empresa', 50000, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tablapost`
--

CREATE TABLE `tablapost` (
  `id` int(11) NOT NULL,
  `cuenta` varchar(250) NOT NULL,
  `debe` float NOT NULL,
  `haber` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `rol_id` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `rol_id`) VALUES
(2, 'administrador', '$2y$10$7Sv4C085k5pPBUi8DWh6rOqV4cVj2CbxvcDQr2V7tahW9BEu06Ihq', 1),
(14, 'AGUSTIN', '$2y$10$SnNlV/crm2GkC0givU77G.1VIYUXfZftME9oPGBkThoPc1b7hZZ6q', 2),
(15, 'carlos', '$2y$10$ub9yV06JZZGhKEWOIjvHYOspa2AbXsUhlz53bTAfDhOkU6pF4qiLW', 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asiento`
--
ALTER TABLE `asiento`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `bono`
--
ALTER TABLE `bono`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cuentaasiento`
--
ALTER TABLE `cuentaasiento`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `cuentas`
--
ALTER TABLE `cuentas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pagosanteriores`
--
ALTER TABLE `pagosanteriores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `puestoempleado`
--
ALTER TABLE `puestoempleado`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tablapost`
--
ALTER TABLE `tablapost`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asiento`
--
ALTER TABLE `asiento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=251;

--
-- AUTO_INCREMENT de la tabla `bono`
--
ALTER TABLE `bono`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cuentaasiento`
--
ALTER TABLE `cuentaasiento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=322;

--
-- AUTO_INCREMENT de la tabla `cuentas`
--
ALTER TABLE `cuentas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT de la tabla `empleado`
--
ALTER TABLE `empleado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `pagosanteriores`
--
ALTER TABLE `pagosanteriores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `puestoempleado`
--
ALTER TABLE `puestoempleado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tablapost`
--
ALTER TABLE `tablapost`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
