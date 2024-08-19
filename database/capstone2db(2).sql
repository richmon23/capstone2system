-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 19, 2024 at 03:55 AM
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
(2, 'John Wick', 'Bogo City', '2024-08-06', '2024-08-13', '1', '1', '2024-08-20', '2024-08-29');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `username`, `email`, `password`) VALUES
(2, 'mort', 'morttest@gmail.com', '$2y$10$z/l2lGbV3Azkov6/LP0uCuCncZ/3BddSHHTVzrmfuPeeK7oWTnG46'),
(3, 'mark', 'test@x.com', '$2y$10$5t7Iwinu5CjxZYFRhzJcNuZoyVkjvX6TDnxOGt7n1KPhvo0Wu.TXG');

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
(8, 'Vince', 'family_state', 1, 1, 'vince@x.com', 123456789, '2024-08-05 23:45:00');

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
  `gender` enum('Male','Female','Other') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `surname`, `email`, `password`, `birthdate`, `gender`) VALUES
(1, 'richmon', 'retiza', 'mort@x.com', '$2y$10$rgGX1IJKaoApi5arTQ.8vOZbF.0kLCzOvbTR9b0MKCiJI4XlTXA0m', '2024-08-07', 'Male'),
(2, 'mort', 'mort', 'mark@x.com', '$2y$10$rXARfGr2I5wcZTUejYjOJOQbBKr4RmogdBJIdMCtFDJ48ZeKB0d3m', '2024-08-16', 'Male'),
(3, 'crisjay', 'crisjay', 'crisjay@x.com', '$2y$10$wJ4Oai5U/w063UyZKEs95O3kjB/aIUQ.6rv/feV/Dt1ZvXvIykyJO', '2024-08-08', 'Male'),
(4, 'bill', 'bill', 'bill@x.com', '$2y$10$1zdZ2kuGtECnq5S/jiBuQuWnI45mAScRk9hFWxeSeL9a7D8EfkQR6', '2024-07-31', 'Male'),
(13, 'mort', 'mort', 'mort1@x.com', '$2y$10$4z8ExAc3l2Is7ZlvYLKCmOnn8fmmkdnwEc6V39Ciq8rri3I5y4kcO', '2024-08-08', 'Male'),
(15, 'akp', 'akp', 'akp@x.com', '$2y$10$6KbPTorXJtvGd9QW/C2xPuR3XH5/4sY7iLX4yO.9xeNaBh/zfryGq', '2024-08-16', 'Male'),
(16, 'karl20', 'karl', 'karl2024@x.com', '$2y$10$82xGLnQMCPWL45ZeC0PqlOwrwF1KR785kdKiLj.1jUu1T16V8Dxhe', '2024-08-29', 'Male'),
(17, 'Desiree', 'Desiree', 'des@x.com', '$2y$10$yECsQ/H17Mk5wbOdi0MdTOTlgCzFFOXbCirpXSMhXeR9anMSE8xnO', '2024-08-22', 'Female');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `deceasedpersoninfo`
--
ALTER TABLE `deceasedpersoninfo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservation`
--
ALTER TABLE `reservation`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
