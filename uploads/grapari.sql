-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 01 Mei 2018 pada 11.34
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
-- Struktur dari tabel `grapari`
--

CREATE TABLE `grapari` (
  `id_grapari` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `nama_grapari` varchar(200) NOT NULL,
  `tgl_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `username` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `grapari`
--

INSERT INTO `grapari` (`id_grapari`, `branch_id`, `nama_grapari`, `tgl_update`, `username`) VALUES
(1, 5, 'grapari jaksel', '2018-05-01 09:31:13', 'bayu'),
(3, 11, 'gra banten', '2018-05-01 09:33:15', 'bayu');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `grapari`
--
ALTER TABLE `grapari`
  ADD PRIMARY KEY (`id_grapari`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `grapari`
--
ALTER TABLE `grapari`
  MODIFY `id_grapari` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
