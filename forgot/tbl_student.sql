-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 08, 2024 at 10:52 AM
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
-- Database: `college_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_student`
--

CREATE TABLE `tbl_student` (
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_student`
--

INSERT INTO `tbl_student` (`username`, `password`, `email`) VALUES
('abhiram', '1111', 'stablesterling@gmail.com'),
('abhiram', '1111', 'stablesterling@gmail.com'),
('stablesterling', '1111', 'stablesterling@gmail.com'),
('stablesterling', '1111', 'stablesterling@gmail.com'),
('stablesterling', '1111', 'stablesterling@gmail.com'),
('stablesterling', '1111', 'stablesterling@gmail.com'),
('stablesterling', '1111', 'stablesterling@gmail.com'),
('stablesterling@gmail.com', '1111', 'stablesterling@gmail.com'),
('pp', '11', 'pp@gmail.com'),
('pop', 'qwq', 'pop@gmail.com'),
('lol@gmail.com', '333', 'lol@gmail.com'),
('lo', '333', 'lol@gmail.com'),
('lol', 'pop', 'al@gmail.com'),
('lol', 'pop', 'al@gmail.com'),
('lol', 'pop', 'al@gmail.com'),
('ror', 'ppp', 'ror@gmail.com'),
('stablesterling', '22', 'w@gmail.com');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
