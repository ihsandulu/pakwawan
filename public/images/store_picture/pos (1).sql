-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 31, 2022 at 11:43 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pos`
--

-- --------------------------------------------------------

--
-- Table structure for table `positionm`
--

CREATE TABLE `positionm` (
  `positionm_id` int(11) NOT NULL,
  `positionm_name` varchar(255) NOT NULL,
  `store_id` int(11) NOT NULL,
  `positionm_datetime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `positionm`
--

INSERT INTO `positionm` (`positionm_id`, `positionm_name`, `store_id`, `positionm_datetime`) VALUES
(1, 'Silver', 1, '2022-12-31 06:05:33'),
(2, 'Gold', 1, '2022-12-31 06:05:37'),
(3, 'Platinum', 1, '2022-12-31 06:05:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `positionm`
--
ALTER TABLE `positionm`
  ADD PRIMARY KEY (`positionm_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `positionm`
--
ALTER TABLE `positionm`
  MODIFY `positionm_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
