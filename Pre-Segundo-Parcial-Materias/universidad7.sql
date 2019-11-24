-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-11-2019 a las 02:30:52
-- Versión del servidor: 10.4.6-MariaDB
-- Versión de PHP: 7.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `universidad`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materias`
--

CREATE TABLE `materias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(128) COLLATE utf8_spanish2_ci NOT NULL,
  `cuatrimestre` varchar(128) COLLATE utf8_spanish2_ci NOT NULL,
  `cupos` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `materias`
--

INSERT INTO `materias` (`id`, `nombre`, `cuatrimestre`, `cupos`, `created_at`, `updated_at`) VALUES
(1, 'Labo', 'Primer', 5, '2019-11-16 16:58:12', '2019-11-16 16:58:12'),
(2, 'Prog', 'Primer', 8, '2019-11-16 16:58:12', '2019-11-17 05:12:40'),
(3, 'Mate', 'Segundo', 2, '2019-11-16 16:58:12', '2019-11-17 05:14:41'),
(4, 'Estadistica', 'Cuarto', 12, '2019-11-16 21:10:49', '2019-11-16 21:10:49'),
(6, 'Practica Profesional', 'Quinto', 12, '2019-11-16 22:47:03', '2019-11-16 22:47:03'),
(9, 'Contable', 'Quinto', 10, '2019-11-17 05:24:19', '2019-11-17 05:24:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos`
--

CREATE TABLE `tipos` (
  `id` int(11) NOT NULL,
  `tipo` varchar(64) COLLATE utf8_spanish2_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `tipos`
--

INSERT INTO `tipos` (`id`, `tipo`, `created_at`, `updated_at`) VALUES
(1, 'alumno', '2019-11-16 14:58:38', '2019-11-16 14:58:38'),
(2, 'profesor', '2019-11-16 14:58:38', '2019-11-16 14:58:38'),
(3, 'admin', '2019-11-16 14:58:38', '2019-11-16 14:58:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `legajo` varchar(128) COLLATE utf8_spanish2_ci NOT NULL,
  `email` varchar(128) COLLATE utf8_spanish2_ci NOT NULL,
  `clave` varchar(128) COLLATE utf8_spanish2_ci NOT NULL,
  `tipo_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `legajo`, `email`, `clave`, `tipo_id`, `created_at`, `updated_at`) VALUES
(2, '19156', 'alumno@gmail.com', 'pass', 1, '2019-11-16 20:15:05', '2019-11-17 05:21:24'),
(5, '50917', 'adm@gmail.com', 'pass', 3, '2019-11-16 20:53:59', '2019-11-17 05:21:04'),
(6, '15257', 'profe@gmail.com', 'pass', 2, '2019-11-17 00:55:02', '2019-11-17 05:21:53');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_materias`
--

CREATE TABLE `usuarios_materias` (
  `usuario_id` int(11) NOT NULL,
  `materia_id` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `usuarios_materias`
--

INSERT INTO `usuarios_materias` (`usuario_id`, `materia_id`, `updated_at`, `created_at`) VALUES
(2, 2, '2019-11-17 05:12:40', '2019-11-17 05:12:40'),
(2, 3, '2019-11-17 05:14:41', '2019-11-17 05:14:41'),
(6, 1, '2019-11-17 01:47:36', '2019-11-17 01:47:36'),
(6, 2, '2019-11-17 01:59:05', '2019-11-17 01:59:05'),
(6, 4, '2019-11-17 01:47:36', '2019-11-17 01:47:36');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `materias`
--
ALTER TABLE `materias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `tipos`
--
ALTER TABLE `tipos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tipo` (`tipo`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `legajo` (`legajo`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `usuarios_materias`
--
ALTER TABLE `usuarios_materias`
  ADD PRIMARY KEY (`usuario_id`,`materia_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `materias`
--
ALTER TABLE `materias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `tipos`
--
ALTER TABLE `tipos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
