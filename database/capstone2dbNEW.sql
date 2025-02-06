-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 06, 2025 at 12:24 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

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

--
-- Dumping data for table `deceasedpersoninfo`
--

INSERT INTO `deceasedpersoninfo` (`id`, `firstname`, `surname`, `address`, `born`, `died`, `plot`, `block`, `funeralday`, `datecreated`, `province`, `municipality`, `completeaddress`) VALUES
(67, 'Mark', 'Zuckerberg', '', '2024-12-02', '2024-12-02', '1', '1', '2024-12-05', '2024-12-10 12:07:12', 'Cebu', 'Sogod', '73rd street Poblacion');

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
  `installment_plan` enum('installment','fullpayment','6months','9months') DEFAULT 'installment',
  `fullpayment_amount` decimal(10,2) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `reservation_id`, `client_name`, `payment_date`, `payment_method`, `payment_status`, `total_amount`, `duration`, `installment_amount`, `amount_paid`, `payment_proof`, `created_at`, `updated_at`, `package`, `installment_plan`, `fullpayment_amount`, `status`) VALUES
(20, 248, 'Bill Gates', '2024-12-10', 'cash', 'paid', 30000.00, '', 0.00, 30000.00, NULL, '2024-12-10 12:11:31', '2024-12-10 12:11:31', 'garden', 'fullpayment', 30000.00, 'Pending'),
(25, 248, 'Bill Gates', '2024-12-18', 'gcash', 'paid', 30000.00, '', 0.00, 30000.00, NULL, '2024-12-18 07:10:55', '2024-12-18 07:10:55', 'garden', 'fullpayment', 30000.00, 'Pending'),
(26, 249, ' ', '2024-12-20', 'cash', 'paid', 30000.00, '', 0.00, 30000.00, NULL, '2024-12-20 05:05:09', '2024-12-20 05:05:09', 'garden', 'fullpayment', 30000.00, 'Pending'),
(36, 260, ' ', '2024-12-28', 'gcash', 'paid', 50000.00, '', 0.00, 50000.00, '../uploads/payment_proofs/1735394113_471587591_122131384898476246_5326188550833341380_n.jpg', '2024-12-28 13:55:13', '2024-12-28 13:55:13', 'family state', 'fullpayment', 50000.00, 'Pending'),
(48, 267, 'wrere erere', '2025-02-06', 'gcash', 'paid', 20000.00, '6months', 3333.33, 20000.00, '../uploads/payment_proofs/1738831760_Screenshot (32).png', '2025-02-06 08:49:20', '2025-02-06 08:49:20', 'lawn', 'installment', 20000.00, 'Pending'),
(49, 262, 'Jose  Manalo', '2025-02-06', 'gcash', 'paid', 20000.00, '6months', 3333.33, 20000.00, '../uploads/payment_proofs/1738832855_Screenshot (32).png', '2025-02-06 09:07:35', '2025-02-06 09:07:35', 'lawn', 'installment', 20000.00, 'Pending'),
(54, 249, 'Richmon Retiza', '2025-02-06', 'gcash', 'paid', 30000.00, '6months', 5000.00, 30000.00, '../uploads/payment_proofs/1738833599_Screenshot (32).png', '2025-02-06 09:19:59', '2025-02-06 09:19:59', 'garden', 'installment', 30000.00, 'Pending'),
(55, 249, 'Richmon Retiza', '2025-02-06', 'cash', 'paid', 30000.00, '6months', 5000.00, 30000.00, NULL, '2025-02-06 09:25:34', '2025-02-06 09:25:34', 'garden', 'installment', 30000.00, 'Pending'),
(56, 262, 'Jose  Manalo', '2025-02-06', 'gcash', 'paid', 20000.00, '9months', 2222.22, 20000.00, '../uploads/payment_proofs/1738834066_Screenshot (33).png', '2025-02-06 09:27:46', '2025-02-06 09:27:46', 'lawn', 'installment', 20000.00, 'Pending'),
(57, 258, 'jose manalo', '2025-02-06', 'gcash', 'paid', 50000.00, '6months', 8333.33, 50000.00, '../uploads/payment_proofs/1738836443_Screenshot (32).png', '2025-02-06 10:07:23', '2025-02-06 10:07:23', 'family state', 'installment', 50000.00, 'Pending'),
(58, 267, 'wrere erere', '2025-02-06', 'gcash', 'paid', 20000.00, '9months', 2222.22, 20000.00, '../uploads/payment_proofs/1738840954_Screenshot (35).png', '2025-02-06 11:22:34', '2025-02-06 11:22:34', 'lawn', 'installment', 20000.00, 'Pending');

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
(3, 1, 3, 0),
(4, 1, 4, 0),
(5, 1, 5, 0),
(6, 1, 6, 0),
(7, 1, 7, 0),
(8, 1, 8, 0),
(9, 1, 9, 0),
(10, 1, 10, 0),
(11, 1, 11, 0),
(12, 1, 12, 0),
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
(53, 2, 3, 0),
(54, 2, 4, 0),
(55, 2, 5, 0),
(56, 2, 6, 0),
(57, 2, 7, 0),
(58, 2, 8, 0),
(59, 2, 9, 0),
(60, 2, 10, 0),
(61, 2, 11, 0),
(62, 2, 12, 0),
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
(103, 3, 3, 0),
(104, 3, 4, 0),
(105, 3, 5, 0),
(106, 3, 6, 0),
(107, 3, 7, 0),
(108, 3, 8, 0),
(109, 3, 9, 0),
(110, 3, 10, 0),
(111, 3, 11, 0),
(112, 3, 12, 0),
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
(153, 4, 3, 0),
(154, 4, 4, 0),
(155, 4, 5, 0),
(156, 4, 6, 0),
(157, 4, 7, 0),
(158, 4, 8, 0),
(159, 4, 9, 0),
(160, 4, 10, 0),
(161, 4, 11, 0),
(162, 4, 12, 0),
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
(248, 'Bill', 'Gates', 'garden', 2, 1, 'bill@gmail.com', '09653384884', '2024-12-10 12:11:31', 0, '', 'success', 'Cebu', '', '73rd street Poblacion'),
(249, 'Richmon', 'Retiza', 'garden', 3, 1, 'retizarichmon84@gmail.com', '09653384884', '2024-12-20 05:05:09', 68, 'Sogod,Cebu', 'success', '', '', ''),
(258, 'jose', 'manalo', 'family State', 3, 1, 'josemanalo@gmail.com', '09653384884', '2024-12-28 13:39:57', 68, 'danao city', 'success', '', '', ''),
(259, 'jose', 'joe', 'family State', 4, 1, 'josemanalo@gmail.com', '09653384884', '2024-12-28 13:46:32', 68, 'danao', 'success', '', '', ''),
(260, 'jose', 'jose', 'family State', 5, 1, 'josemanalo@gmail.com', '09653384884', '2024-12-28 13:55:13', 68, 'suba1', 'success', '', '', ''),
(261, 'Jose', 'Manalo', 'family State', 6, 1, 'josemanalo@gmail.com', '09653384884', '2024-12-28 14:01:06', 68, 'Bogo City', 'success', '', '', ''),
(262, 'Jose ', 'Manalo', 'lawn', 7, 1, 'josemanalo@outlook.com', '09653384884', '2025-02-06 08:40:16', 68, 'Bogo City', 'success', '', '', ''),
(267, 'wrere', 'erere', 'lawn', 12, 1, 'ako@x.com', '09653384884', '2025-02-06 08:35:46', 71, 'USA', 'success', '', '', '');

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
(63, 'crisjay', 'juevesano', 'crisjayjuevesano@gmail.com', '', 'Isabela', 'Cordon', 'Medellin', '09653384884', '$2y$10$N2lC8cHRJXyYmTT1hKJmducl/IwJp0fWfJf0XIP6g5zvPW3JNk5oS', '0000-00-00', 'Male', 'Untitled.jpg', 'customer', '2024-11-14 03:09:54'),
(70, 'Desiree', 'Leal', 'desiree12345@x.com', '', 'Cebu', 'Sogod', 'nailon', '09653384884', '$2y$10$vTBgd63nXM4wBrxjWaZeP.iOdDMZFrj6lFmBcuFAoo5DbV.Yg2x12', '0000-00-00', 'Female', '473030512_10162285586430619_6457142119647684809_n.jpg', 'admin', '2025-02-02 09:07:18'),
(71, 'Richmon', 'Retiza', 'retizarichmon84@gmail.com', '', 'Cebu', 'Sogod', 'Suba 1', '09653384884', '$2y$10$ZVE8ay3CukBMYWab0kqXguiAC5zWnOzLmA0fXbLhE0kA0WL8j/0G2', '0000-00-00', 'Male', '455024802_519563923944266_2037721697047009913_n.jpg', 'customer', '2025-02-02 09:19:52');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `plots`
--
ALTER TABLE `plots`
  MODIFY `plot_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201;

--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=268;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

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
