-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 25, 2018 at 02:58 AM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_hvcares`
--

-- --------------------------------------------------------

--
-- Table structure for table `churn`
--

CREATE TABLE `churn` (
  `id_churn` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `tanggal_churn` date NOT NULL,
  `nilai_churn` bigint(20) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `tanggal_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `churn`
--

INSERT INTO `churn` (`id_churn`, `branch_id`, `tanggal_churn`, `nilai_churn`, `updated_by`, `tanggal_update`) VALUES
(2, 14, '2018-05-01', 14, 362, '2018-07-24 07:41:43'),
(3, 11, '2018-05-02', 2, 362, '2018-07-24 07:42:00'),
(4, 8, '2018-07-03', 4, 362, '2018-07-24 07:42:13'),
(5, 5, '2018-05-04', 4, 362, '2018-07-24 07:42:27'),
(6, 18, '2018-05-05', 2, 362, '2018-07-24 07:42:47'),
(7, 6, '2018-07-06', 3, 362, '2018-07-24 07:43:05'),
(8, 16, '2018-07-07', 4, 362, '2018-07-24 07:43:20'),
(9, 9, '2018-05-08', 3, 362, '2018-07-24 07:43:39'),
(10, 19, '2018-05-09', 2, 362, '2018-07-24 07:43:57'),
(11, 12, '2018-07-09', 3, 362, '2018-07-24 07:44:11'),
(12, 11, '2018-07-10', 13, 362, '2018-07-24 07:44:39'),
(13, 14, '2017-05-01', 10, 362, '2018-07-24 07:50:50'),
(14, 11, '2017-05-02', 9, 362, '2018-07-24 07:51:20'),
(15, 8, '2017-05-03', 4, 362, '2018-07-24 07:51:39'),
(16, 5, '2017-05-04', 4, 362, '2018-07-24 07:52:08'),
(17, 18, '2017-05-05', 3, 362, '2018-07-24 07:52:28'),
(18, 6, '2018-05-07', 6, 362, '2018-07-24 07:52:44'),
(19, 16, '2017-05-07', 12, 362, '2018-07-24 07:53:05'),
(20, 9, '2017-05-04', 2, 362, '2018-07-24 07:53:25'),
(21, 19, '2017-05-08', 2, 362, '2018-07-24 07:53:49'),
(22, 12, '2017-05-10', 2, 362, '2018-07-24 07:54:05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `churn`
--
ALTER TABLE `churn`
  ADD PRIMARY KEY (`id_churn`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `churn`
--
ALTER TABLE `churn`
  MODIFY `id_churn` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
