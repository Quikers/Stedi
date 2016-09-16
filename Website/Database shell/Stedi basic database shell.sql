-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 16, 2016 at 01:08 AM
-- Server version: 10.1.16-MariaDB
-- PHP Version: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Database: `stedi`
--
CREATE DATABASE IF NOT EXISTS `stedi` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `stedi`;

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `id` int(16) NOT NULL,
  `name` varchar(255) NOT NULL,
  `activated` int(2) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `genre` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `name`, `activated`, `created`, `genre`, `author`, `description`) VALUES
(1, 'Gerwin''s Machtig Leuke Game', 1, '2016-09-16 00:59:25', 'Awesomeness Maximized', 'Gerwin the awesome', 'Dit spel is duidelijk de beste ooit gemaakt. Niets zal dit spel overtreffen\r\n\r\n\r\nOok niet die van jou, ego Cheesus.'),
(2, 'Test game met een normale titel lengte', 1, '2016-09-16 00:59:25', 'Action Shooter', 'Test', 'Dit is een test voor zowel de applicatie als de website.\r\nDit moet op een nieuwe regel staan.');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `userid` int(16) NOT NULL,
  `gameid` int(16) NOT NULL,
  `rating` decimal(2,1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`userid`, `gameid`, `rating`) VALUES
(1, 1, '3.5'),
(2, 1, '5.0');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(16) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(42) NOT NULL,
  `accountType` int(2) NOT NULL,
  `email` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `insertion` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `accountType`, `email`, `firstname`, `insertion`, `lastname`) VALUES
(1, 'admin', '*4ACFE3202A5FF5CF467898FC58AAB1D615029441', 0, 'temp@admin.com', 'Admin', 'von', 'Administrator'),
(2, 'luuk', '*F9E3024109D953ED1646144D7EE39A9E146E0042', 1, 'luuk@luuk.luuk', 'Luuk', '', 'Diederik');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD UNIQUE KEY `userid_2` (`userid`,`gameid`),
  ADD KEY `userid` (`userid`),
  ADD KEY `gameid` (`gameid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;--

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
