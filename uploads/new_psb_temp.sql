-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 01 Mar 2018 pada 10.32
-- Versi Server: 10.1.25-MariaDB
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
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
-- Struktur dari tabel `new_psb_temp`
--

CREATE TABLE `new_psb_temp` (
  `id_temp` int(11) NOT NULL,
  `nama_table` varchar(100) NOT NULL,
  `bulan` varchar(10) NOT NULL,
  `tahun` varchar(10) NOT NULL,
  `status` varchar(10) NOT NULL,
  `id_users` int(11) NOT NULL,
  `tanggal_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `new_psb_temp`
--

INSERT INTO `new_psb_temp` (`id_temp`, `nama_table`, `bulan`, `tahun`, `status`, `id_users`, `tanggal_update`) VALUES
(4, 'sales_psb_07_2017', '7', '2017', 'Aktif', 10, '2018-03-01 08:21:55'),
(5, 'sales_psb_06_2017', '6', '2017', 'Aktif', 10, '2018-03-01 09:00:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `new_psb_temp`
--
ALTER TABLE `new_psb_temp`
  ADD PRIMARY KEY (`id_temp`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `new_psb_temp`
--
ALTER TABLE `new_psb_temp`
  MODIFY `id_temp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
