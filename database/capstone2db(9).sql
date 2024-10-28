-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 23, 2024 at 06:19 PM
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
  `datecreated` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `deceasedpersoninfo`
--

INSERT INTO `deceasedpersoninfo` (`id`, `firstname`, `surname`, `address`, `born`, `died`, `plot`, `block`, `funeralday`, `datecreated`) VALUES
(33, 'bill', 'george', 'USA', '2024-10-17', '2024-10-01', '4', '1', '2024-10-03', '2024-10-23'),
(34, 'John ', 'Doe', 'USA', '2024-10-08', '2024-10-01', '3', '1', '2024-10-03', '2024-10-23');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `name`, `latitude`, `longitude`, `description`) VALUES
(1, 'bill gates', 11.04371000, 123.99014000, 'BILL GATES'),
(2, 'XI JINPING', 11.04373098, 123.99018977, 'XI JINPING');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL,
  `approvedaccount_id` int(11) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `amount_paid` decimal(10,2) DEFAULT NULL,
  `terms` int(11) DEFAULT NULL,
  `payment_status` varchar(50) DEFAULT NULL,
  `receipt_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `contact` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_id` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `status` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservation`
--

INSERT INTO `reservation` (`id`, `firstname`, `surname`, `package`, `plotnumber`, `blocknumber`, `email`, `contact`, `time`, `user_id`, `address`, `status`) VALUES
(185, 'mort', 'retiza', 'garden', 1, 1, 'mort@x.com', 22332322, '2024-10-20 16:00:00', 0, '', 'Approved'),
(186, 'mark', 'pedroza', 'garden', 2, 1, 'mark@yahoo.com', 23332232, '2024-10-23 15:08:38', 0, '', 'Approved');

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
(1, 'Bill Gates ', 'shesshhhhhhhhhhhhhhhhhhhhh!!! \r\npangit inyong ui!!!\r\n\r\nhahahahahahhaah!!', 3.5, '2024-08-19 02:30:21', NULL),
(2, 'Elon X', 'nice ui mga gwapo mga frontend ug backend programmer ani nga system!!!\r\n\r\n\r\nkeep it up guys! ', 5, '2024-08-19 03:11:02', NULL),
(3, 'Steph Curry', 'nice mga lodi!!!', 2, '2024-09-18 14:03:20', NULL),
(4, 'Bill Mark', 'thank you guys!!! ', 5, '2024-09-18 14:03:20', NULL),
(7, 'John Doe', 'Great service!', 5, '2024-09-18 15:01:12', 1),
(8, 'richmon', 'weeweewew', 2, '2024-09-18 15:11:20', 1),
(9, 'richmon', 'dshjdhdjdhjdhjhdjddss', 4, '2024-09-18 15:11:32', 1),
(10, 'mark', 'hello guys salamat kaayo sa inyo system! ', 4, '2024-09-18 15:14:43', 20),
(11, 'richmon', 'hello  mga kowyaaaaaaaaaaaaaaaaaaaaaaa!!!', 5, '2024-09-24 14:03:53', 1),
(12, 'josh', 'hello  kuya egg world! ', 5, '2024-10-04 13:24:09', 23),
(13, 'richmon', 'hello', 2, '2024-10-09 16:40:01', 1),
(14, 'richmon', 'helllooo mga kowyaaaaaaaaaaaaaaaaa!!', 5, '2024-10-13 13:33:27', 1),
(15, 'richmon', 'kgjfkgfjkgf', 1, '2024-10-13 13:36:39', 1),
(16, 'richmon', 'jkgjgjj', 5, '2024-10-13 13:50:21', 1),
(17, 'richmon', 'hoy', 5, '2024-10-13 13:50:26', 1);

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
  `contact` varchar(20) NOT NULL,
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

INSERT INTO `users` (`id`, `firstname`, `surname`, `email`, `address`, `contact`, `password`, `birthdate`, `gender`, `profile_pic`, `role`, `created_at`) VALUES
(1, 'richmon', 'retiza', 'mort@x.com', 'sogod,cebu', '2147483647', '$2y$10$K6E/tlco0rSas9LRM/wNm.lwEs64tJF9KvHgqNIo9Eg4hl8xemeue', '2024-08-07', 'Male', '', 'customer', '2024-09-15 01:53:48'),
(20, 'mark', 'mark', 'mark2024@x.com', 'Bogo City', '09653384884', '$2y$10$4h8XYRgb1cxCQsDW/BMqtONbNb4XT1y/n75YtBZ4uJ8H4KKV4nKDm', '2024-09-18', 'Male', '', 'customer', '2024-09-18 15:13:41'),
(23, 'josh', 'pedroza', 'josh@x.com', 'bogo city', '09653384884', '$2y$10$.yLM4mgZoXo1VeoWd/LeFO5QVFRhdSwLMBY84R3rNED/iVnotNGUC', '2024-10-04', 'Male', '', 'customer', '2024-10-04 13:15:47'),
(26, 'Desiree', 'Leal', 'des@x.com', 'Nailon Bogo City', '09653384884', '$2y$10$LYnABs.dAajXvjdosCGoC.Tt1BPexFkSmRsJt5QuFR41XkzdU/t4m', '2024-10-04', 'Female', '', 'admin', '2024-10-07 04:11:43'),
(27, 'mark', 'pedroza', 'retizarichmon84@gmail.com', 'Suba 1', '09653384884', '$2y$10$0Nazj19k6jdhhCX0zWNs7.hIVRY6jPAm5VYm0.2DJaiXxoy8ffQJ6', '2024-10-15', 'Male', '', 'customer', '2024-10-15 03:37:59'),
(28, 'richmort', 'retiza', 'mort2024@x.com', 'USA', '33223', '$2y$10$A3eVhUU1ouCvl5lrBdh1muVjqrXmK6kz8cr6CxWhEOaEXCixPbAe.', '0000-00-00', 'Male', 'uploads/profile_pics/25739612.jpg', 'customer', '2024-10-23 15:55:36');

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
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `approvedaccount_id` (`approvedaccount_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plots`
--
ALTER TABLE `plots`
  MODIFY `plot_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201;

--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=187;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

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
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`approvedaccount_id`) REFERENCES `approvedaccount` (`approvedaccount_id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_user_review` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
