-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 30, 2024 at 06:39 AM
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
-- Table structure for table `deceasedpersoninfo`
--

CREATE TABLE `deceasedpersoninfo` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
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

INSERT INTO `deceasedpersoninfo` (`id`, `fullname`, `address`, `born`, `died`, `plot`, `block`, `funeralday`, `datecreated`) VALUES
(4, 'Xi Jinping', 'China', '2024-09-14', '2024-09-14', '2', '2', '2024-10-11', '2024-09-27'),
(6, 'Gou Hua Ping', 'China', '2024-09-14', '2024-09-14', '2', '7', '2024-09-14', '2024-09-14'),
(10, 'VLADIMIR PUTIN', 'BOGO CITY', '2024-09-20', '2024-09-20', '2', '3', '2024-09-20', '2024-09-20'),
(14, 'bill gates', 'USA', '2024-09-20', '2024-09-20', '3', '4', '2024-09-20', '2024-09-20'),
(16, 'XIAO PAU', 'china', '2024-09-20', '2024-09-20', '1', '4', '2024-09-20', '2024-09-20'),
(17, 'John Doe', 'USA', '2024-09-23', '2024-09-23', '1', '2', '2024-12-12', '2024-09-23');

-- --------------------------------------------------------

--
-- Table structure for table `plots`
--

CREATE TABLE `plots` (
  `plotnumber` int(11) NOT NULL,
  `blocknumber` int(11) NOT NULL,
  `is_available` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

CREATE TABLE `reservation` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `package` varchar(100) NOT NULL,
  `plotnumber` int(11) NOT NULL,
  `blocknumber` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_id` int(11) NOT NULL,
  `address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservation`
--

INSERT INTO `reservation` (`id`, `fullname`, `package`, `plotnumber`, `blocknumber`, `email`, `contact`, `time`, `user_id`, `address`) VALUES
(116, 'Bill Gates', 'garden', 1, 1, 'kohaco2217@fuzitea.com', 2147483647, '2024-09-24 16:00:00', 0, ''),
(117, 'Bill Gates', 'garden', 2, 2, 'kohaco2217@fuzitea.com', 2147483647, '2024-09-26 18:22:00', 0, ''),
(125, 'Richmon Retiza', 'lawn', 1, 1, 'retizarichmon84@gmail.com', 8787431, '2024-09-30 03:09:17', 1, 'Suba 1'),
(126, 'Richmon Retiza', 'lawn', 1, 2, 'retizarichmon84@gmail.com', 8787431, '2024-09-30 03:09:47', 1, 'Suba 1'),
(127, 'Bill Gates', 'garden', 2, 3, 'asiong1@omail.edu.pl', 2147483647, '2024-09-30 03:13:00', 0, ''),
(128, 'John Doe', 'garden', 3, 4, 'henebap861@vidney.com', 2147483647, '2024-09-30 03:18:00', 0, ''),
(129, 'Gou Hua Ping', 'family_state', 1, 4, 'henebap861@vidney.com', 2147483647, '2024-09-30 03:18:00', 0, ''),
(130, 'Gou Hua Ping', 'family_state', 1, 4, 'henebap861@vidney.com', 2147483647, '2024-09-30 03:18:00', 0, ''),
(131, 'Gou Hua Ping', 'family_state', 1, 4, 'henebap861@vidney.com', 2147483647, '2024-09-30 03:18:00', 0, ''),
(132, 'Gou Hua Ping', 'family_state', 1, 4, 'henebap861@vidney.com', 2147483647, '2024-09-30 03:18:00', 0, ''),
(133, 'Gou Hua Ping', 'family_state', 1, 4, 'henebap861@vidney.com', 2147483647, '2024-09-30 03:18:00', 0, ''),
(134, 'Gou Hua Ping', 'family_state', 1, 4, 'henebap861@vidney.com', 2147483647, '2024-09-30 03:18:00', 0, ''),
(135, 'Gou Hua Ping', 'family_state', 1, 4, 'henebap861@vidney.com', 2147483647, '2024-09-30 03:18:00', 0, ''),
(136, 'Gou Hua Ping', 'family_state', 1, 4, 'henebap861@vidney.com', 2147483647, '2024-09-30 03:18:00', 0, ''),
(137, 'Gou Hua Ping', 'family_state', 1, 4, 'henebap861@vidney.com', 2147483647, '2024-09-30 03:18:00', 0, ''),
(138, 'Vladimir Putin', 'family_state', 44, 3, 'kohaco2217@fuzitea.com', 121212, '2024-10-26 04:14:00', 0, ''),
(139, 'Vladimir Putin', 'family_state', 44, 3, 'kohaco2217@fuzitea.com', 121212, '2024-10-26 04:14:00', 0, '');

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
(11, 'richmon', 'hello  mga kowyaaaaaaaaaaaaaaaaaaaaaaa!!!', 5, '2024-09-24 14:03:53', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `birthdate` date NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `role` enum('admin','customer') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `surname`, `email`, `password`, `birthdate`, `gender`, `role`, `created_at`) VALUES
(1, 'richmon', 'retiza', 'mort@x.com', '$2y$10$U0DUxH/5W79VdId7kf1OTOwDSeOO/yHg4cfqWplGm.hcE8fAxldjO', '2024-08-07', 'Male', 'customer', '2024-09-15 01:53:48'),
(2, 'mort', 'mort', 'mark@x.com', '$2y$10$rXARfGr2I5wcZTUejYjOJOQbBKr4RmogdBJIdMCtFDJ48ZeKB0d3m', '2024-08-16', 'Male', 'customer', '2024-09-15 01:53:48'),
(3, 'crisjay', 'crisjay', 'crisjay@x.com', '$2y$10$wJ4Oai5U/w063UyZKEs95O3kjB/aIUQ.6rv/feV/Dt1ZvXvIykyJO', '2024-08-08', 'Male', 'admin', '2024-09-15 01:53:48'),
(4, 'bill', 'bill', 'bill@x.com', '$2y$10$1zdZ2kuGtECnq5S/jiBuQuWnI45mAScRk9hFWxeSeL9a7D8EfkQR6', '2024-07-31', 'Male', 'customer', '2024-09-15 01:53:48'),
(13, 'mort', 'mort', 'mort1@x.com', '$2y$10$4z8ExAc3l2Is7ZlvYLKCmOnn8fmmkdnwEc6V39Ciq8rri3I5y4kcO', '2024-08-08', 'Male', 'admin', '2024-09-15 01:53:48'),
(15, 'akp', 'akp', 'akp@x.com', '$2y$10$6KbPTorXJtvGd9QW/C2xPuR3XH5/4sY7iLX4yO.9xeNaBh/zfryGq', '2024-08-16', 'Male', 'customer', '2024-09-15 01:53:48'),
(16, 'karl20', 'karl', 'karl2024@x.com', '$2y$10$82xGLnQMCPWL45ZeC0PqlOwrwF1KR785kdKiLj.1jUu1T16V8Dxhe', '2024-08-29', 'Male', 'customer', '2024-09-15 01:53:48'),
(17, 'Desiree', 'Desiree', 'des@x.com', '$2y$10$.7qKrX4BuXDLWebpHXCOzunCFrdNer2I/dK0rGq60u5pkjTXc5gCy', '2024-08-22', 'Female', 'admin', '2024-09-15 01:53:48'),
(19, 'bill', 'gates', 'gates@x.com', '$2y$10$XUiusfIQjcEalJxOX.tqQeLf5kQ1WH/hgkt8HQvelsNLCHF.3KhKC', '2024-09-06', 'Male', 'customer', '2024-09-15 02:01:33'),
(20, 'mark', 'mark', 'mark2024@x.com', '$2y$10$4h8XYRgb1cxCQsDW/BMqtONbNb4XT1y/n75YtBZ4uJ8H4KKV4nKDm', '2024-09-18', 'Male', 'customer', '2024-09-18 15:13:41'),
(21, 'Bill', 'Gates', 'admin@x.com', '$2y$10$FADJNaKI5VzWBRySXJcuyuH5mdfGp4zx.DHCBuvfelVHGxAZBEjAq', '2024-09-10', 'Male', 'admin', '2024-09-24 11:57:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `deceasedpersoninfo`
--
ALTER TABLE `deceasedpersoninfo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plots`
--
ALTER TABLE `plots`
  ADD PRIMARY KEY (`plotnumber`,`blocknumber`);

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
-- AUTO_INCREMENT for table `deceasedpersoninfo`
--
ALTER TABLE `deceasedpersoninfo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_user_review` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
