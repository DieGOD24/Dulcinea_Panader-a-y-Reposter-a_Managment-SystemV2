-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-04-2024 a las 17:59:41
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
-- Base de datos: `bsms_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `category_list`
--

CREATE TABLE `category_list` (
  `category_id` int(30) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `delete_flag` tinyint(1) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `category_list`
--

INSERT INTO `category_list` (`category_id`, `name`, `description`, `status`, `delete_flag`, `date_created`, `date_updated`) VALUES
(1, 'Sample 101', 'This is a sample Category 101', 1, 1, '2022-02-14 09:16:23', '2024-04-29 00:56:36'),
(2, 'Sample 102', 'This is a sample Category 102', 1, 1, '2022-02-14 09:19:04', '2024-04-29 00:56:39'),
(3, 'Sample 103', 'This is a sample Category 103', 1, 1, '2022-02-14 09:19:11', '2024-04-29 00:56:41'),
(4, 'Sample 104', 'This is a sample Category 104', 1, 1, '2022-02-14 09:19:18', '2024-04-29 00:56:43'),
(5, 'Sample 105', 'This is a sample Category 105', 1, 1, '2022-02-14 09:19:24', '2024-04-29 00:56:45'),
(6, 'Sample 106', 'This is a sample Category 106', 1, 1, '2022-02-14 09:19:30', '2024-04-29 00:56:49'),
(7, 'Sample 107', 'This is a sample Category 107', 1, 1, '2022-02-14 09:19:37', '2024-04-29 00:56:51'),
(8, 'Sample 108', 'This is a sample Category 108', 1, 1, '2022-02-14 09:19:43', '2024-04-29 00:56:54'),
(9, 'Sample 109', 'This is a sample Category 109', 1, 1, '2022-02-14 09:19:49', '2024-04-29 00:56:56'),
(10, 'Sample 110', 'This is a sample Category 110', 1, 1, '2022-02-14 09:19:55', '2024-04-29 00:56:58'),
(11, 'Sample 111', 'This is a sample Category 111', 0, 1, '2022-02-14 09:20:11', '2022-02-14 09:23:14'),
(12, 'Alfajor', 'INGREDIENTES: 1. Maicena ->150 gramos -> $ 600 | \r\n2. Harina -> 100 gramos -> $ 400 | \r\n3. Yemas -> 2 -> $ 933 | \r\n4. Azucar -> 75 gramos -> $ 375 | \r\n5. Margarina -> 100 gramos ->$ 1.400 | \r\n6. Vainilla -> C/N -> $ 600 | \r\n7. Rayadura de limon -> C/N -> $ 600 | \r\n8. Bicarbonato -> 1/2 cucharadita -> $ 300 | \r\n9. Levadura -> 1/2 cucharadita -> $ 500 | \r\n10. Rayadura de coco -> 1/2 cucharadita -> $ 600 | \r\n11. Base -> 1/2 cucharadita -> $ 800 |', 1, 0, '2024-04-29 01:18:41', '2024-04-29 01:20:21'),
(13, 'Ganache', 'INGREDIENTES: \r\n1. Crema de leche -> 250 ml -> $ 5.833 | \r\n2. Chocolate -> 500 gramos -> $ 16.500.', 1, 0, '2024-04-29 01:28:46', '2024-04-29 01:48:34'),
(14, 'Arequipe', 'INGREDIENTES:\r\n1. Arequipe -> 500 gramos -> $ 6.500 | \r\n2. Leche -> 1 litro -> $ 4.400 | \r\n3. Gelatina -> 2 gramos -> $ 112.', 1, 0, '2024-04-29 01:35:59', NULL),
(15, 'Ganlleta', 'INGREDIENTES: \r\n1. Harina -> 1071 gramos -> $ 4.284 |	\r\n2. Huevos -> 6 -> $ 2.800 | \r\n3. Azucar -> 360 gramos -> $ 1.800 | \r\n4. Margarina -> 300 gramos -> $ 4.200	| \r\n5. Sal -> 3 gramos -> $ 100.', 1, 0, '2024-04-29 01:45:50', '2024-04-29 02:04:02'),
(16, 'Brownie', 'INGREDIENTES: \r\nHarina -> 72 gramos -> $ 288 | \r\nHuevos -> 6 -> $ 2.800 | \r\nAzucar -> 540 gramos -> $ 2.700 | \r\nPolvo de hornear -> 2 cucharadas -> $ 1.240 | \r\nVainilla -> 2 cucharada -> $ 1.500	| \r\nCocoa -> 150 gramos -> $ 4.800	| \r\nMargarina -> 336 gramos -> $ 4.704 | \r\nSal -> 3 gramos -> $ 100.', 1, 0, '2024-04-29 01:53:38', NULL),
(17, 'Biscocho', 'INGREDIENTES: \r\n1. Aceite -> 160 ml -> $ 1.501 | \r\n2. Harina -> 250 gramos -> $ 1.000 | \r\n3. Huevos -> 3 -> $ 1.400 | \r\n4. Azucar -> 180 gramos -> $ 900.', 1, 0, '2024-04-29 02:03:46', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_list`
--

CREATE TABLE `product_list` (
  `product_id` int(30) NOT NULL,
  `product_code` text NOT NULL,
  `category_id` int(30) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `price` double NOT NULL DEFAULT 0,
  `alert_restock` double NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `delete_flag` tinyint(1) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `product_list`
--

INSERT INTO `product_list` (`product_id`, `product_code`, `category_id`, `name`, `description`, `price`, `alert_restock`, `status`, `delete_flag`, `date_created`, `date_updated`) VALUES
(1, '23141506', 1, 'Product 101', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 10, 20, 1, 1, '2022-02-14 09:42:00', '2024-04-29 00:57:50'),
(2, '123456', 2, 'Product 102', 'Cras eget maximus nunc, id hendrerit dui. Donec auctor mauris ac augue aliquam gravida vel sit amet libero. Proin tempor eu augue id aliquet.', 15, 20, 1, 1, '2022-02-14 09:42:00', '2024-04-29 00:57:54'),
(3, '231415', 2, 'Product 103', 'Vivamus commodo purus a dolor pretium interdum. Pellentesque bibendum lacus sed tortor mollis varius. Etiam sed odio felis. Nam nec erat eu metus feugiat aliquam. Aenean id semper ex. Nulla nec euismod tellus. Maecenas pellentesque ipsum sit amet augue scelerisque ultrices.', 45, 50, 1, 1, '2022-02-14 09:42:00', '2024-04-29 00:57:58'),
(4, '123654789', 3, 'Product 104', 'Sed sit amet pharetra metus, sed posuere nibh. Nam at sapien enim. Pellentesque pretium scelerisque turpis, in rhoncus sapien tempor sed.', 20, 30, 1, 1, '2022-02-14 09:42:00', '2024-04-29 00:58:01'),
(5, '987545', 3, 'Product 105', 'Curabitur imperdiet cursus auctor. Donec tristique nulla non porta lobortis. Nulla malesuada sapien lacus, nec rhoncus leo porta vitae. Pellentesque semper rhoncus tellus a pulvinar. Morbi nec tortor ut lorem laoreet vulputate', 50, 30, 1, 1, '2022-02-14 09:46:59', '2024-04-29 00:58:04'),
(6, '5489879', 6, 'Product 105', 'Fusce dui augue, porttitor at est a, commodo lacinia mauris. Etiam quis nulla maximus, fermentum tortor quis, suscipit neque. Curabitur leo ligula, tristique eu placerat sit amet, euismod non ligula.', 50, 0, 1, 1, '2022-02-14 09:47:22', '2022-02-14 09:48:32'),
(7, 'AC_04-24_T2', 12, 'A_Clasico', 'Empaque -> $ 400.', 3000, 12, 1, 0, '2024-04-29 01:21:44', '2024-04-29 01:31:40'),
(8, 'GC_04-24_T2', 13, 'G_Clasico', '', 2481, 15, 1, 0, '2024-04-29 01:33:18', NULL),
(9, 'ArC_04-24_T2', 14, 'Ar_C', '', 688, 11, 1, 0, '2024-04-29 01:40:56', '2024-04-29 01:49:09'),
(10, 'GanC_04-24_T2', 15, 'Gan_C', 'Empaque -> $ 300.', 300, 16, 1, 0, '2024-04-29 01:47:53', '2024-04-29 01:49:37'),
(11, 'BrC_04-24_T2', 16, 'Br_C', 'Empaque -> $ 300.', 4000, 8, 1, 0, '2024-04-29 02:00:15', NULL),
(12, 'BisC-01_04-24_T2', 17, 'Bis_C_Limon', 'Empaque -> $ 800. \r\n| Extras: Polvo de hornear -> 2,5 cucharadas -> $ 1.550 | \r\nRayadura de limon -> C/N -> $ 1.000 | Yogurt (Kumis) -> 125 ml -> $ 1.038.', 6000, 12, 1, 0, '2024-04-29 02:08:26', '2024-04-29 02:14:09'),
(13, 'BisC-02_04-24_T2', 17, 'Bis_C_Vainilla', 'Empaque -> $ 800. \r\n| Extras: Vainilla -> 1 cucharada -> $ 1.000 | \r\nSal -> C/N -> $ 75 | \r\nLeche -> 160 ml -> $ 704.', 6000, 7, 1, 0, '2024-04-29 02:11:36', '2024-04-29 02:13:50'),
(14, 'BisC-03_04-24_T2', 17, 'Bis_C_Chocolate', 'Empaque -> $ 800. \r\n| Extras: Vainilla -> 1 cucharada -> $ 1.000 | \r\nCocoa -> 60 gramos -> $ 1.920 | \r\nAgua caliente -> 100 ml -> $ 0 | \r\nSal -> C/N -> $ 75 | \r\nLeche -> 160 ml -> $ 704.', 6000, 10, 1, 0, '2024-04-29 02:15:03', '2024-04-29 02:17:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock_list`
--

CREATE TABLE `stock_list` (
  `stock_id` int(30) NOT NULL,
  `product_id` int(30) NOT NULL,
  `quantity` double NOT NULL DEFAULT 0,
  `expiry_date` datetime NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `stock_list`
--

INSERT INTO `stock_list` (`stock_id`, `product_id`, `quantity`, `expiry_date`, `date_added`) VALUES
(12, 7, 7, '2024-05-06 00:00:00', '2024-04-29 06:23:21'),
(14, 9, 16, '2024-05-03 00:00:00', '2024-04-29 06:41:20'),
(16, 8, 10, '2024-05-22 00:00:00', '2024-04-29 06:42:18'),
(17, 10, 450, '2024-05-13 00:00:00', '2024-04-29 06:54:02'),
(19, 11, 16, '2024-05-08 00:00:00', '2024-04-29 07:01:06'),
(20, 12, 6, '2024-05-06 00:00:00', '2024-04-29 07:09:12'),
(21, 13, 6, '2024-05-11 00:00:00', '2024-04-29 07:17:51'),
(22, 14, 6, '2024-05-12 00:00:00', '2024-04-29 07:18:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transaction_items`
--

CREATE TABLE `transaction_items` (
  `transaction_id` int(30) NOT NULL,
  `product_id` int(30) NOT NULL,
  `quantity` double NOT NULL DEFAULT 0,
  `price` double NOT NULL DEFAULT 0,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `transaction_items`
--

INSERT INTO `transaction_items` (`transaction_id`, `product_id`, `quantity`, `price`, `date_added`) VALUES
(10, 9, 1, 688, '2024-04-29 07:18:57'),
(10, 7, 1, 3000, '2024-04-29 07:18:57'),
(10, 14, 1, 6000, '2024-04-29 07:18:57'),
(10, 12, 1, 6000, '2024-04-29 07:18:57'),
(10, 13, 1, 6000, '2024-04-29 07:18:57'),
(10, 11, 1, 4000, '2024-04-29 07:18:57'),
(10, 10, 1, 300, '2024-04-29 07:18:57'),
(10, 8, 1, 2481, '2024-04-29 07:18:57'),
(11, 8, 3, 2481, '2024-04-29 07:21:25'),
(11, 10, 5, 300, '2024-04-29 07:21:25'),
(11, 13, 1, 6000, '2024-04-29 07:21:25'),
(11, 14, 1, 6000, '2024-04-29 07:21:25'),
(11, 9, 1, 688, '2024-04-29 07:21:25'),
(12, 12, 6, 6000, '2024-04-29 14:40:20'),
(12, 8, 3, 2481, '2024-04-29 14:40:20'),
(12, 11, 6, 4000, '2024-04-29 14:40:20'),
(13, 14, 1, 6000, '2024-04-29 15:07:16'),
(13, 11, 1, 4000, '2024-04-29 15:07:16'),
(13, 13, 1, 6000, '2024-04-29 15:07:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transaction_list`
--

CREATE TABLE `transaction_list` (
  `transaction_id` int(30) NOT NULL,
  `receipt_no` text NOT NULL,
  `total` double NOT NULL DEFAULT 0,
  `tendered_amount` double NOT NULL DEFAULT 0,
  `change` double NOT NULL DEFAULT 0,
  `user_id` int(30) DEFAULT 1,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `transaction_list`
--

INSERT INTO `transaction_list` (`transaction_id`, `receipt_no`, `total`, `tendered_amount`, `change`, `user_id`, `date_added`) VALUES
(10, '1714375137', 28469, 30000, 1531, 1, '2024-04-29 07:18:57'),
(11, '1714375285', 21631, 22000, 369, 5, '2024-04-29 07:21:25'),
(12, '1714401620', 67443, 75000, 7557, 3, '2024-04-29 14:40:20'),
(13, '1714403236', 16000, 20000, 4000, 3, '2024-04-29 15:07:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_list`
--

CREATE TABLE `user_list` (
  `user_id` int(30) NOT NULL,
  `fullname` text NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `type` int(30) NOT NULL DEFAULT 1,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `user_list`
--

INSERT INTO `user_list` (`user_id`, `fullname`, `username`, `password`, `type`, `status`, `date_created`) VALUES
(1, 'ADMIN-BD', 'admin', '0192023a7bbd73250516f069df18b500', 1, 1, '2022-02-14 00:44:30'),
(2, 'Sebastian Gaviria', 'Segato24', 'cd74fae0a3adf459f73bbf187607ccea', 1, 1, '2022-02-14 02:29:23'),
(3, 'Diego Giraldo', 'DieCode24', '4dc398073770c8350f94dd2e77dbbc3a', 1, 1, '2022-02-14 02:29:58'),
(5, 'POS-1', 'POS-1_UTP', '70198a60ea7a69166e3a845ff561f517', 0, 1, '2024-04-29 04:34:56'),
(6, 'POS-2', 'POS-2_UTP', '1e4adfa5465de63d599072827bb00fcd', 0, 1, '2024-04-29 14:45:40');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `category_list`
--
ALTER TABLE `category_list`
  ADD PRIMARY KEY (`category_id`);

--
-- Indices de la tabla `product_list`
--
ALTER TABLE `product_list`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indices de la tabla `stock_list`
--
ALTER TABLE `stock_list`
  ADD PRIMARY KEY (`stock_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indices de la tabla `transaction_items`
--
ALTER TABLE `transaction_items`
  ADD KEY `product_id` (`product_id`),
  ADD KEY `transaction_id` (`transaction_id`);

--
-- Indices de la tabla `transaction_list`
--
ALTER TABLE `transaction_list`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `user_list`
--
ALTER TABLE `user_list`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `category_list`
--
ALTER TABLE `category_list`
  MODIFY `category_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `product_list`
--
ALTER TABLE `product_list`
  MODIFY `product_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `stock_list`
--
ALTER TABLE `stock_list`
  MODIFY `stock_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `transaction_list`
--
ALTER TABLE `transaction_list`
  MODIFY `transaction_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `user_list`
--
ALTER TABLE `user_list`
  MODIFY `user_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `product_list`
--
ALTER TABLE `product_list`
  ADD CONSTRAINT `product_list_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category_list` (`category_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `stock_list`
--
ALTER TABLE `stock_list`
  ADD CONSTRAINT `stock_list_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product_list` (`product_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `transaction_items`
--
ALTER TABLE `transaction_items`
  ADD CONSTRAINT `transaction_items_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product_list` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaction_items_ibfk_2` FOREIGN KEY (`transaction_id`) REFERENCES `transaction_list` (`transaction_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `transaction_list`
--
ALTER TABLE `transaction_list`
  ADD CONSTRAINT `transaction_list_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_list` (`user_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
