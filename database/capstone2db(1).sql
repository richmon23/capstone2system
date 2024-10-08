-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 15, 2024 at 05:23 AM
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
(2, 'John Wick1', 'Bogo City', '2024-08-06', '2024-08-13', '1', '1', '2024-08-20', '2024-08-29'),
(4, 'w', 'w', '2024-09-14', '2024-09-14', '2', '2', '2024-10-11', '2024-09-14'),
(5, 'r', 'r', '2024-09-14', '2024-09-14', '3', '5', '2024-09-14', '2024-09-14'),
(6, 'wee', 'ewe', '2024-09-14', '2024-09-14', 'w', '7', '2024-09-14', '2024-09-14'),
(7, 'wee', 'ewe', '2024-09-14', '2024-09-14', 'w', '7', '2024-09-14', '2024-09-14'),
(8, 'wee', 'ewe', '2024-09-14', '2024-09-14', 'w', '7', '2024-09-14', '2024-09-14'),
(9, 'wee', 'ewe', '2024-09-14', '2024-09-14', 'w', '7', '2024-09-14', '2024-09-14');

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
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservation`
--

INSERT INTO `reservation` (`id`, `fullname`, `package`, `plotnumber`, `blocknumber`, `email`, `contact`, `time`) VALUES
(6, 'Bill Jugan', 'lawn', 2, 3, 'bill@x.com', 123456789, '2024-08-19 16:00:00'),
(7, 'mort', 'family_state', 3, 2, 'mort@x.com', 123456789, '2024-08-15 04:26:00'),
(65, 'w', 'garden', 2, 2, 'kohaco2217@fuzitea.com', 8787431, '2024-09-13 16:00:00'),
(66, 'w', 'garden', 2, 2, 'kohaco2217@fuzitea.com', 8787431, '2024-09-13 16:00:00'),
(68, 'q', 'family_state', 1, 1, 'kohaco2217@fuzitea.com', 4656487, '2024-09-13 16:05:00');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `userfeedback` text DEFAULT NULL,
  `rating` float NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `fullname`, `userfeedback`, `rating`, `time`) VALUES
(1, 'Bill Gates ', 'shesshhhhhhhhhhhhhhhhhhhhh!!! \r\npangit inyong ui!!!\r\n\r\nhahahahahahhaah!!', 3.5, '2024-08-19 02:30:21'),
(2, 'Elon X', 'nice ui mga gwapo mga frontend ug backend programmer ani nga system!!!\r\n\r\n\r\nkeep it up guys! ', 5, '2024-08-19 03:11:02');

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
(1, 'richmon', 'retiza', 'mort@x.com', '$2y$10$rgGX1IJKaoApi5arTQ.8vOZbF.0kLCzOvbTR9b0MKCiJI4XlTXA0m', '2024-08-07', 'Male', 'customer', '2024-09-15 01:53:48'),
(2, 'mort', 'mort', 'mark@x.com', '$2y$10$rXARfGr2I5wcZTUejYjOJOQbBKr4RmogdBJIdMCtFDJ48ZeKB0d3m', '2024-08-16', 'Male', 'customer', '2024-09-15 01:53:48'),
(3, 'crisjay', 'crisjay', 'crisjay@x.com', '$2y$10$wJ4Oai5U/w063UyZKEs95O3kjB/aIUQ.6rv/feV/Dt1ZvXvIykyJO', '2024-08-08', 'Male', 'admin', '2024-09-15 01:53:48'),
(4, 'bill', 'bill', 'bill@x.com', '$2y$10$1zdZ2kuGtECnq5S/jiBuQuWnI45mAScRk9hFWxeSeL9a7D8EfkQR6', '2024-07-31', 'Male', 'customer', '2024-09-15 01:53:48'),
(13, 'mort', 'mort', 'mort1@x.com', '$2y$10$4z8ExAc3l2Is7ZlvYLKCmOnn8fmmkdnwEc6V39Ciq8rri3I5y4kcO', '2024-08-08', 'Male', 'admin', '2024-09-15 01:53:48'),
(15, 'akp', 'akp', 'akp@x.com', '$2y$10$6KbPTorXJtvGd9QW/C2xPuR3XH5/4sY7iLX4yO.9xeNaBh/zfryGq', '2024-08-16', 'Male', 'customer', '2024-09-15 01:53:48'),
(16, 'karl20', 'karl', 'karl2024@x.com', '$2y$10$82xGLnQMCPWL45ZeC0PqlOwrwF1KR785kdKiLj.1jUu1T16V8Dxhe', '2024-08-29', 'Male', 'customer', '2024-09-15 01:53:48'),
(17, 'Desiree', 'Desiree', 'des@x.com', '$2y$10$yECsQ/H17Mk5wbOdi0MdTOTlgCzFFOXbCirpXSMhXeR9anMSE8xnO', '2024-08-22', 'Female', 'admin', '2024-09-15 01:53:48'),
(19, 'bill', 'gates', 'gates@x.com', '$2y$10$XUiusfIQjcEalJxOX.tqQeLf5kQ1WH/hgkt8HQvelsNLCHF.3KhKC', '2024-09-06', 'Male', 'customer', '2024-09-15 02:01:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `deceasedpersoninfo`
--
ALTER TABLE `deceasedpersoninfo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
