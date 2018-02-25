-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 25 Feb 2018 pada 12.01
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
-- Struktur dari tabel `bast_header`
--

CREATE TABLE `bast_header` (
  `id_header` int(11) NOT NULL,
  `no_bast` varchar(50) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `tanggal_masuk` datetime NOT NULL,
  `tanggal_terima` datetime DEFAULT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `id_users` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `bast_header`
--

INSERT INTO `bast_header` (`id_header`, `no_bast`, `branch_id`, `tanggal_masuk`, `tanggal_terima`, `keterangan`, `id_users`) VALUES
(3, 'T20180225051735', 14, '2018-02-25 05:17:35', NULL, NULL, 30),
(4, 'T20180225051905', 11, '2018-02-25 05:19:05', NULL, NULL, 357);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bast_header`
--
ALTER TABLE `bast_header`
  ADD PRIMARY KEY (`id_header`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bast_header`
--
ALTER TABLE `bast_header`
  MODIFY `id_header` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
