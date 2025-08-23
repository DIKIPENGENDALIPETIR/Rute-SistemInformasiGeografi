-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 23 Agu 2025 pada 09.24
-- Versi server: 8.0.30
-- Versi PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `crud_stk`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `stks`
--

CREATE TABLE `stks` (
  `id` int NOT NULL,
  `nama_stk` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `keterangan` text,
  `latitude` double NOT NULL DEFAULT '0',
  `longitude` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `stks`
--

INSERT INTO `stks` (`id`, `nama_stk`, `alamat`, `foto`, `keterangan`, `latitude`, `longitude`) VALUES
(3, '\" STK \" Sedulur Tunggal Kopi Tambaksari', 'Jl. Tambaksari No.25', '1750402855_WhatsApp Image 2025-06-20 at 13.59.06_5479f8c1.jpg', 'Jl. Tambaksari No.25, RT.002/RW.11, Tambaksari, Kec. Tambaksari, Surabaya, Jawa Timur 60136', -7.252994499562791, 112.75485277187472),
(4, '\" STK \" Sedulur Tunggal Kopi Ngagel', 'Jl. Ngagel Madya', '1750406687_WhatsApp Image 2025-06-20 at 15.04.27_b5d8c95f.jpg', 'Baratajaya, Kec. Gubeng, Surabaya, Jawa Timur 60284', -7.290242055982871, 112.75993285617386);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `stks`
--
ALTER TABLE `stks`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `stks`
--
ALTER TABLE `stks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
