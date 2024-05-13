-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 13, 2024 at 05:42 PM
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
-- Database: `aa`
--

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `email` varchar(40) NOT NULL,
  `subject` varchar(40) NOT NULL,
  `feedback` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `name`, `phone`, `email`, `subject`, `feedback`) VALUES
(72, 'asdhjasd', 'dasdlkansd', 'asldknaslkdn', 'asdlknasdln', 'asldknasld'),
(73, 'p', 'o', 'p', 'p', 'p'),
(74, 'stable', '123', 'stablesterling@gmail.com', 'verification code', 'asd'),
(75, 'asdhjasd', 'dasdlkansd', 'asldknaslkdn', 'asdlknasdln', 'asldknasld'),
(76, 'p', 'o', 'p', 'p', 'p'),
(77, 'stable', '123', 'stablesterling@gmail.com', 'verification code', 'asd'),
(78, 'a', 'a', 'a', 'a', 'a'),
(79, 'w', 'w', 'w', 'w', 'w'),
(80, 'asdhjasd', 'dasdlkansd', 'asldknaslkdn', 'asdlknasdln', 'asldknasld'),
(81, 'p', 'o', 'p', 'p', 'p'),
(82, 'stable', '123', 'stablesterling@gmail.com', 'verification code', 'asd'),
(83, 'a', 'a', 'a', 'a', 'a'),
(84, 'w', 'w', 'w', 'w', 'w'),
(85, 'asdhjasd', 'dasdlkansd', 'asldknaslkdn', 'asdlknasdln', 'asldknasld'),
(86, 'p', 'o', 'p', 'p', 'p'),
(87, 'stable', '123', 'stablesterling@gmail.com', 'verification code', 'asd'),
(88, 'a', 'a', 'a', 'a', 'a'),
(89, 'w', 'w', 'w', 'w', 'w'),
(90, 'asdhjasd', 'dasdlkansd', 'asldknaslkdn', 'asdlknasdln', 'asldknasld'),
(91, 'p', 'o', 'p', 'p', 'p'),
(92, 'stable', '123', 'stablesterling@gmail.com', 'verification code', 'asd'),
(93, 'a', 'a', 'a', 'a', 'a'),
(94, 'w', 'w', 'w', 'w', 'w'),
(95, 'asdhjasd', 'dasdlkansd', 'asldknaslkdn', 'asdlknasdln', 'asldknasld'),
(96, 'p', 'o', 'p', 'p', 'p'),
(97, 'stable', '123', 'stablesterling@gmail.com', 'verification code', 'asd'),
(98, 'a', 'a', 'a', 'a', 'a'),
(99, 'w', 'w', 'w', 'w', 'w'),
(100, 'asdhjasd', 'dasdlkansd', 'asldknaslkdn', 'asdlknasdln', 'asldknasld'),
(101, 'p', 'o', 'p', 'p', 'p'),
(102, 'stable', '123', 'stablesterling@gmail.com', 'verification code', 'asd'),
(103, 'a', 'a', 'a', 'a', 'a'),
(104, 'w', 'w', 'w', 'w', 'w'),
(105, 'asdhjasd', 'dasdlkansd', 'asldknaslkdn', 'asdlknasdln', 'asldknasld'),
(106, 'p', 'o', 'p', 'p', 'p'),
(107, 'stable', '123', 'stablesterling@gmail.com', 'verification code', 'asd'),
(108, 'a', 'a', 'a', 'a', 'a'),
(109, 'w', 'w', 'w', 'w', 'w'),
(110, 'asdhjasd', 'dasdlkansd', 'asldknaslkdn', 'asdlknasdln', 'asldknasld'),
(111, 'p', 'o', 'p', 'p', 'p'),
(112, 'stable', '123', 'stablesterling@gmail.com', 'verification code', 'asd'),
(113, 'a', 'a', 'a', 'a', 'a'),
(114, 'w', 'w', 'w', 'w', 'w'),
(115, 'adsnvdlfjvnkdf dcd', '7012948534', 'stablesterling@gmail.com', 'verification code', 'qw'),
(116, '', '', '', '', ''),
(117, '', '', '', '', ''),
(118, '', '', '', '', ''),
(119, 'ddddddddd', 'dddddddddd', 'dddddddddddddddd', 'ddddddddddddddddddddd', 'ddddddddddddddddddddddddd');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
