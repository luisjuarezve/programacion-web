-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-07-2025 a las 16:50:39
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `plataforma_educativa`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `contrasena`, `fecha_registro`) VALUES
(1, 'luis', '123@gmail.com', '$2y$10$Sltzga5ZWWjMoTcWdFF9Q.ET1yV12xesX8pXoiasXrw2ji5/ZoLm6', '2025-07-07 14:36:42'),
(2, 'asdas', 'ASDASD@MAIL.VOM', '$2y$10$kto66/kxwHkC4uG6zKlksez0v3vUWtevRFBKiylVmAFiMKwG5khV2', '2025-07-07 14:40:02'),
(5, 'LUIS JUAREZ V', 'aaa@MAIL.VOM', '$2y$10$Ac9jGKTpKRZT1yH4P9sW9u6O0ryyF.uRj1/KuQOg/4nnPR65y4PTK', '2025-07-07 14:41:54'),
(7, 'jesus', 'jesusesmarico@gmail.com', '$2y$10$x0r1GLt1aq4jYruJECFiD.PuxBooVlZGkZ6wIOd1WfcnghPFR/nxa', '2025-07-07 14:44:52'),
(9, 'aa', 'aa@gmail.com', '$2y$10$oOaOG4/Y26FB.FuF.bqV7.N7YVtBGo0rW6HtA8HI0O4Cx9b0clTWS', '2025-07-07 14:46:48');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
