-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 21, 2024 at 05:35 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `capstone2db`
--

-- --------------------------------------------------------

--
-- Table structure for table `approvedaccount`
--

CREATE TABLE `approvedaccount` (
  `approvedaccount_id` int(11) NOT NULL,
  `reservation_id` int(11) DEFAULT NULL,
  `approval_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `package` varchar(100) DEFAULT NULL,
  `plotnumber` int(11) DEFAULT NULL,
  `blocknumber` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Approved'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deceasedpersoninfo`
--

CREATE TABLE `deceasedpersoninfo` (
  `id` int(11) NOT NULL,
  `firstname` varchar(250) NOT NULL,
  `surname` varchar(250) NOT NULL,
  `address` varchar(100) NOT NULL,
  `born` date NOT NULL,
  `died` date NOT NULL,
  `plot` varchar(100) NOT NULL,
  `block` varchar(100) NOT NULL,
  `funeralday` date NOT NULL,
  `datecreated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `province` varchar(255) NOT NULL,
  `municipality` varchar(255) NOT NULL,
  `completeaddress` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL,
  `reservation_id` int(11) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `payment_date` date DEFAULT curdate(),
  `payment_method` enum('cash','gcash') NOT NULL,
  `payment_status` enum('not_paid','paid') DEFAULT 'not_paid',
  `total_amount` decimal(10,2) NOT NULL,
  `duration` enum('full','6months','9months') NOT NULL,
  `installment_amount` decimal(10,2) DEFAULT NULL,
  `amount_paid` decimal(10,2) DEFAULT 0.00,
  `payment_proof` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `package` varchar(50) NOT NULL,
  `installment_plan` enum('fullpayment','6months','9months') DEFAULT 'fullpayment',
  `fullpayment_amount` decimal(10,2) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `reservation_id`, `client_name`, `payment_date`, `payment_method`, `payment_status`, `total_amount`, `duration`, `installment_amount`, `amount_paid`, `payment_proof`, `created_at`, `updated_at`, `package`, `installment_plan`, `fullpayment_amount`, `status`) VALUES
(18, 245, 'Richmon Retiza', '2024-11-21', 'cash', 'paid', 30000.00, '', 0.00, 30000.00, NULL, '2024-11-21 13:55:07', '2024-11-21 13:55:07', 'garden', 'fullpayment', 30000.00, 'Pending'),
(19, 246, 'Juan Flores', '2024-11-21', 'cash', 'paid', 30000.00, '', 0.00, 30000.00, NULL, '2024-11-21 14:21:36', '2024-11-21 14:21:36', 'garden', 'fullpayment', 30000.00, 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `plots`
--

CREATE TABLE `plots` (
  `plot_id` int(11) NOT NULL,
  `block` int(11) DEFAULT NULL,
  `plot_number` int(11) DEFAULT NULL,
  `is_available` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plots`
--

INSERT INTO `plots` (`plot_id`, `block`, `plot_number`, `is_available`) VALUES
(1, 1, 1, 0),
(2, 1, 2, 0),
(3, 1, 3, 1),
(4, 1, 4, 1),
(5, 1, 5, 1),
(6, 1, 6, 1),
(7, 1, 7, 1),
(8, 1, 8, 1),
(9, 1, 9, 1),
(10, 1, 10, 1),
(11, 1, 11, 1),
(12, 1, 12, 1),
(13, 1, 13, 1),
(14, 1, 14, 1),
(15, 1, 15, 1),
(16, 1, 16, 1),
(17, 1, 17, 1),
(18, 1, 18, 1),
(19, 1, 19, 1),
(20, 1, 20, 1),
(21, 1, 21, 1),
(22, 1, 22, 1),
(23, 1, 23, 1),
(24, 1, 24, 1),
(25, 1, 25, 1),
(26, 1, 26, 1),
(27, 1, 27, 1),
(28, 1, 28, 1),
(29, 1, 29, 1),
(30, 1, 30, 1),
(31, 1, 31, 1),
(32, 1, 32, 1),
(33, 1, 33, 1),
(34, 1, 34, 1),
(35, 1, 35, 1),
(36, 1, 36, 1),
(37, 1, 37, 1),
(38, 1, 38, 1),
(39, 1, 39, 1),
(40, 1, 40, 1),
(41, 1, 41, 1),
(42, 1, 42, 1),
(43, 1, 43, 1),
(44, 1, 44, 1),
(45, 1, 45, 1),
(46, 1, 46, 1),
(47, 1, 47, 1),
(48, 1, 48, 1),
(49, 1, 49, 1),
(50, 1, 50, 1),
(51, 2, 1, 1),
(52, 2, 2, 1),
(53, 2, 3, 1),
(54, 2, 4, 1),
(55, 2, 5, 1),
(56, 2, 6, 1),
(57, 2, 7, 1),
(58, 2, 8, 1),
(59, 2, 9, 1),
(60, 2, 10, 1),
(61, 2, 11, 1),
(62, 2, 12, 1),
(63, 2, 13, 1),
(64, 2, 14, 1),
(65, 2, 15, 1),
(66, 2, 16, 1),
(67, 2, 17, 1),
(68, 2, 18, 1),
(69, 2, 19, 1),
(70, 2, 20, 1),
(71, 2, 21, 1),
(72, 2, 22, 1),
(73, 2, 23, 1),
(74, 2, 24, 1),
(75, 2, 25, 1),
(76, 2, 26, 1),
(77, 2, 27, 1),
(78, 2, 28, 1),
(79, 2, 29, 1),
(80, 2, 30, 1),
(81, 2, 31, 1),
(82, 2, 32, 1),
(83, 2, 33, 1),
(84, 2, 34, 1),
(85, 2, 35, 1),
(86, 2, 36, 1),
(87, 2, 37, 1),
(88, 2, 38, 1),
(89, 2, 39, 1),
(90, 2, 40, 1),
(91, 2, 41, 1),
(92, 2, 42, 1),
(93, 2, 43, 1),
(94, 2, 44, 1),
(95, 2, 45, 1),
(96, 2, 46, 1),
(97, 2, 47, 1),
(98, 2, 48, 1),
(99, 2, 49, 1),
(100, 2, 50, 1),
(101, 3, 1, 1),
(102, 3, 2, 1),
(103, 3, 3, 1),
(104, 3, 4, 1),
(105, 3, 5, 1),
(106, 3, 6, 1),
(107, 3, 7, 1),
(108, 3, 8, 1),
(109, 3, 9, 1),
(110, 3, 10, 1),
(111, 3, 11, 1),
(112, 3, 12, 1),
(113, 3, 13, 1),
(114, 3, 14, 1),
(115, 3, 15, 1),
(116, 3, 16, 1),
(117, 3, 17, 1),
(118, 3, 18, 1),
(119, 3, 19, 1),
(120, 3, 20, 1),
(121, 3, 21, 1),
(122, 3, 22, 1),
(123, 3, 23, 1),
(124, 3, 24, 1),
(125, 3, 25, 1),
(126, 3, 26, 1),
(127, 3, 27, 1),
(128, 3, 28, 1),
(129, 3, 29, 1),
(130, 3, 30, 1),
(131, 3, 31, 1),
(132, 3, 32, 1),
(133, 3, 33, 1),
(134, 3, 34, 1),
(135, 3, 35, 1),
(136, 3, 36, 1),
(137, 3, 37, 1),
(138, 3, 38, 1),
(139, 3, 39, 1),
(140, 3, 40, 1),
(141, 3, 41, 1),
(142, 3, 42, 1),
(143, 3, 43, 1),
(144, 3, 44, 1),
(145, 3, 45, 1),
(146, 3, 46, 1),
(147, 3, 47, 1),
(148, 3, 48, 1),
(149, 3, 49, 1),
(150, 3, 50, 1),
(151, 4, 1, 1),
(152, 4, 2, 1),
(153, 4, 3, 1),
(154, 4, 4, 1),
(155, 4, 5, 1),
(156, 4, 6, 1),
(157, 4, 7, 1),
(158, 4, 8, 1),
(159, 4, 9, 1),
(160, 4, 10, 1),
(161, 4, 11, 1),
(162, 4, 12, 1),
(163, 4, 13, 1),
(164, 4, 14, 1),
(165, 4, 15, 1),
(166, 4, 16, 1),
(167, 4, 17, 1),
(168, 4, 18, 1),
(169, 4, 19, 1),
(170, 4, 20, 1),
(171, 4, 21, 1),
(172, 4, 22, 1),
(173, 4, 23, 1),
(174, 4, 24, 1),
(175, 4, 25, 1),
(176, 4, 26, 1),
(177, 4, 27, 1),
(178, 4, 28, 1),
(179, 4, 29, 1),
(180, 4, 30, 1),
(181, 4, 31, 1),
(182, 4, 32, 1),
(183, 4, 33, 1),
(184, 4, 34, 1),
(185, 4, 35, 1),
(186, 4, 36, 1),
(187, 4, 37, 1),
(188, 4, 38, 1),
(189, 4, 39, 1),
(190, 4, 40, 1),
(191, 4, 41, 1),
(192, 4, 42, 1),
(193, 4, 43, 1),
(194, 4, 44, 1),
(195, 4, 45, 1),
(196, 4, 46, 1),
(197, 4, 47, 1),
(198, 4, 48, 1),
(199, 4, 49, 1),
(200, 4, 50, 1);

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

CREATE TABLE `reservation` (
  `id` int(11) NOT NULL,
  `firstname` varchar(250) NOT NULL,
  `surname` varchar(250) NOT NULL,
  `package` varchar(100) NOT NULL,
  `plotnumber` int(11) NOT NULL,
  `blocknumber` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact` varchar(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_id` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `province` varchar(255) NOT NULL,
  `municipality` varchar(255) NOT NULL,
  `completeaddress` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservation`
--

INSERT INTO `reservation` (`id`, `firstname`, `surname`, `package`, `plotnumber`, `blocknumber`, `email`, `contact`, `time`, `user_id`, `address`, `status`, `province`, `municipality`, `completeaddress`) VALUES
(245, 'Richmon', 'Retiza', 'garden', 1, 1, 'richmonretiza@gmail.com', '09653384884', '2024-11-21 13:55:07', 0, '', 'success', 'Cebu', '', 'Suba 1 Tabunok'),
(246, 'Juan', 'Flores', 'garden', 2, 1, 'juanflores@gmail.com', '09653384884', '2024-11-21 14:21:36', 66, 'Tabunok,Sogod,Cebu', 'success', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `userfeedback` text DEFAULT NULL,
  `rating` float NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `fullname`, `userfeedback`, `rating`, `time`, `user_id`) VALUES
(18, 'crisjay', 'this is great', 5, '2024-11-14 03:11:52', 63);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `province` varchar(255) NOT NULL,
  `municipality` varchar(255) NOT NULL,
  `completeaddress` varchar(255) NOT NULL,
  `contact` varchar(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `birthdate` date NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `profile_pic` varchar(255) NOT NULL,
  `role` enum('admin','customer') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `surname`, `email`, `address`, `province`, `municipality`, `completeaddress`, `contact`, `password`, `birthdate`, `gender`, `profile_pic`, `role`, `created_at`) VALUES
(1, 'richmon', 'retiza', 'mort@x.com', 'sogod,cebu', '', '', '', '2147483647', '$2y$10$K6E/tlco0rSas9LRM/wNm.lwEs64tJF9KvHgqNIo9Eg4hl8xemeue', '2024-08-07', 'Male', '', 'customer', '2024-09-15 01:53:48'),
(20, 'mark', 'mark', 'mark2024@x.com', 'Bogo City', '', '', '', '2147483647', '$2y$10$4h8XYRgb1cxCQsDW/BMqtONbNb4XT1y/n75YtBZ4uJ8H4KKV4nKDm', '2024-09-18', 'Male', '', 'customer', '2024-09-18 15:13:41'),
(23, 'josh', 'pedroza', 'josh@x.com', 'bogo city', '', '', '', '2147483647', '$2y$10$.yLM4mgZoXo1VeoWd/LeFO5QVFRhdSwLMBY84R3rNED/iVnotNGUC', '2024-10-04', 'Male', '', 'customer', '2024-10-04 13:15:47'),
(37, 'richmort', 'retiza', 'richmort@x.com', 'sogod', '', '', '', '2147483647', '$2y$10$UFoXM9bt8gIxCbAKW6QG5u/VI53B5SU5eW5EtRzVh7nwav/m3Gn9C', '0000-00-00', 'Male', '462618897_558224533411538_1739645196218830309_n.jpg', 'customer', '2024-10-25 16:17:30'),
(42, 'josh', 'pedroza', 'josh24@x.com', 'bogo city', '', '', '', '12344', '$2y$10$vMrT609aJzSmXGoVbXkoB.DnhXLUn.jrqpANkYBzKGEUSUjJNd3O6', '0000-00-00', 'Male', '25739612.jpg', 'customer', '2024-10-25 17:06:42'),
(43, 'Richmon', 'Retiza', 'richmon24@x.com', 'Sogod Cebu', '', '', '', '12221', '$2y$10$zJwWdLEcEN7hfbnIe3Ts4OUhnXs/r/WONvxE4KLvplo0YPjqBF3iq', '0000-00-00', 'Male', '455024802_519563923944266_2037721697047009913_n.jpg', 'customer', '2024-10-25 17:09:12'),
(44, 'Desiree', 'Leal', 'des@x.com', 'Nailon Bogo City', '', '', '', '2147483647', '$2y$10$5yyRsCJpjxf15J3ci66ye.Z0c8xwyEtXdrL1PK4/wSXMu2AmgEroC', '0000-00-00', 'Female', '286135935_5763180570378578_323735913516917608_n.jpg', 'admin', '2024-10-28 03:27:21'),
(45, 'John', 'Doe', 'john@x.com', 'Tabunok,Sogod,Cebu', '', '', '', '1211133', '$2y$10$7kL00X1spAlrbQTW31mkJ.KPVWtw.zlAzY5y2usaCtdIeTAMR3r7i', '0000-00-00', 'Male', 'Untitled.jpg', 'customer', '2024-10-30 01:39:32'),
(47, ' John Kayden', 'Retiza', 'kayden@x.com', 'Tabunok,Sogod,Cebu', '', '', '', '2323322', '$2y$10$bgoHreRBUPqkez4S6cxLXOrsyEnV7RlXm2W5dfn9qfrM/jWWl7i1m', '0000-00-00', 'Male', '463745581_565075549393103_9068588271593894056_n.jpg', 'customer', '2024-10-30 01:56:07'),
(58, 'mort12', 'mort12', 'mort12@x.com', '', 'Cebu', 'City of Bogo', 'Purok Timbogan Nailon', '09653384884', '$2y$10$tvVKKn.d0leP0GCBTWODLecHAiO4fh.HVHjSLSXV1JGpPUQrXbABO', '0000-00-00', 'Male', '462618897_558224533411538_1739645196218830309_n.jpg', 'customer', '2024-11-04 13:20:23'),
(60, 'richmort', 'retiza', 'richmort2024@x.com', '', 'Cebu', 'Sogod', 'Suba 1 Tabunok', '09653385885', '$2y$10$27TM74zOJBdwKrhMk3JRsOvZKEisqztSZoouF0gA2fxUVdLzJBAQC', '0000-00-00', 'Male', '25739612.jpg', 'customer', '2024-11-04 13:47:55'),
(61, 'Desiree ', 'Leal ', 'desiree@x.com', '', 'Cebu', 'City of Bogo', 'Purok Timbogan Nailon', '09128745326', '$2y$10$kLgc0xtSabBZs1diS57caue4mWbPkAxcD3lJFa1KO/mvJaO95yz5W', '0000-00-00', 'Female', '286135935_5763180570378578_323735913516917608_n.jpg', 'admin', '2024-11-04 13:52:41'),
(62, 'mark', 'mark', 'mark@x.com', '', 'Cebu', 'City of Bogo', 'Purok Timbogan Nailon', '09653384884', '$2y$10$/n9tYyfDzdY74PR2wz6zYujtQVAOZ3RBHMSFpglL.CiThxR4BUPrS', '0000-00-00', 'Male', 'Untitled.jpg', 'customer', '2024-11-13 14:22:34'),
(63, 'crisjay', 'juevesano', 'crisjayjuevesano@gmail.com', '', 'Isabela', 'Cordon', 'Medellin', '09653384884', '$2y$10$N2lC8cHRJXyYmTT1hKJmducl/IwJp0fWfJf0XIP6g5zvPW3JNk5oS', '0000-00-00', 'Male', 'Untitled.jpg', 'customer', '2024-11-14 03:09:54'),
(64, 'Richmon', 'Retiza', 'retizarichmon@gmail.com', '', 'Cebu', 'Sogod', 'Poblacion 73rd Street', '09653384884', '$2y$10$cUWr1fG4veBB//TuEGWCXOORJIIAtmjC8SEXePvq/wXa.mFSbuxzm', '0000-00-00', 'Male', '455024802_519563923944266_2037721697047009913_n.jpg', 'customer', '2024-11-16 11:58:10'),
(65, 'Desiree', 'Leal', 'desireeleal@gmail.com', '', 'Cebu', 'City of Bogo', 'Purok Timbogan Nailon', '09653384884', '$2y$10$P6v1srLnuFiOdJh0KKeuS.aL.XDPditAx8BEQKdlvp8q8F4IKWkGO', '0000-00-00', 'Female', '286135935_5763180570378578_323735913516917608_n.jpg', 'admin', '2024-11-17 03:04:30'),
(66, 'Juan', 'Flores', 'juanflores@gmail.com', '', 'Cebu', 'Borbon', 'Lugo ', '09653384884', '$2y$10$ZxgStnkviiiStkvMO56KpuhQpxVx4NbRT92lMhKDscTgIMAMYq6Ge', '0000-00-00', 'Male', 'juan.jpg', 'customer', '2024-11-20 03:40:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `approvedaccount`
--
ALTER TABLE `approvedaccount`
  ADD PRIMARY KEY (`approvedaccount_id`),
  ADD KEY `reservation_id` (`reservation_id`);

--
-- Indexes for table `deceasedpersoninfo`
--
ALTER TABLE `deceasedpersoninfo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `reservation_id` (`reservation_id`);

--
-- Indexes for table `plots`
--
ALTER TABLE `plots`
  ADD PRIMARY KEY (`plot_id`);

--
-- Indexes for table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `approvedaccount`
--
ALTER TABLE `approvedaccount`
  MODIFY `approvedaccount_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deceasedpersoninfo`
--
ALTER TABLE `deceasedpersoninfo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `plots`
--
ALTER TABLE `plots`
  MODIFY `plot_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201;

--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=247;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `approvedaccount`
--
ALTER TABLE `approvedaccount`
  ADD CONSTRAINT `approvedaccount_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservation` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservation` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_user_review` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
