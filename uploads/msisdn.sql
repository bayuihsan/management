-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 23, 2018 at 09:17 AM
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
-- Table structure for table `msisdn`
--

CREATE TABLE `msisdn` (
  `id_haloinstan` int(50) NOT NULL,
  `msisdn` varchar(20) NOT NULL,
  `tipe` varchar(100) NOT NULL,
  `id_users` int(11) NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `branch_id` int(11) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `msisdn`
--

INSERT INTO `msisdn` (`id_haloinstan`, `msisdn`, `tipe`, `id_users`, `status`, `branch_id`, `tanggal`) VALUES
(5, '62811678456', 'HALO INSTAN', 85, '', 0, '2018-01-09 02:58:34'),
(6, '62811887766', 'HALO INSTAN', 170, '', 0, '2018-01-09 02:58:34'),
(8, '628111678679', 'HALO INSTAN', 266, '', 0, '2018-01-09 02:58:34'),
(9, '628111445589', 'HALO INSTAN', 80, '', 0, '2018-01-09 02:58:34'),
(10, '626871638716', 'HALO INSTAN', 39, '', 0, '2018-01-09 02:58:34'),
(11, '62811666777', 'HALO INSTAN', 61, '', 0, '2018-01-09 03:13:44'),
(12, '62811343457', 'HALO INSTAN', 69, '', 0, '2018-01-10 02:20:54'),
(13, '62811444555', 'HALO INSTAN', 62, '', 0, '2018-01-18 11:01:48'),
(14, '6281120566807', 'HALO INSTAN', 362, NULL, 0, '2018-02-14 02:41:20'),
(15, '628112056808', 'HALO INSTAN', 362, NULL, 0, '2018-02-14 02:51:33'),
(16, '62811287698', 'HALO INSTAN', 362, NULL, 0, '2018-02-15 02:59:12'),
(17, '62811289859', 'Halo Instan', 362, '1', 0, '2018-02-15 03:00:12'),
(18, '62811268741', 'Halo Instan', 360, '1', 0, '2018-02-15 03:00:12'),
(19, '62811290112', 'Halo Instan', 350, '1', 0, '2018-02-15 03:00:12'),
(24, '62811810923812', 'HaloReguler', 107, NULL, 0, '2018-02-21 08:41:41'),
(26, '62811810923813', 'HaloReguler', 356, NULL, 0, '2018-02-21 08:56:16'),
(27, '62811810923814', 'HaloReguler', 356, NULL, 0, '2018-02-21 08:56:16'),
(28, '62811566577', 'HaloInstan', 356, NULL, 0, '2018-02-21 09:09:01'),
(31, '62811176080', 'HaloInstan', 260, NULL, 0, '2018-02-21 16:36:05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `msisdn`
--
ALTER TABLE `msisdn`
  ADD PRIMARY KEY (`id_haloinstan`),
  ADD UNIQUE KEY `msisdn` (`msisdn`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `msisdn`
--
ALTER TABLE `msisdn`
  MODIFY `id_haloinstan` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
