-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 12 Okt 2026 pada 10.02
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `money_tracker_ben`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `money_tracker`
--

CREATE TABLE `money_tracker` (
  `id_transaction` int(11) NOT NULL,
  `amount` int(25) NOT NULL,
  `type` varchar(25) NOT NULL,
  `category` varchar(100) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `money_tracker`
--

INSERT INTO `money_tracker` (`id_transaction`, `amount`, `type`, `category`, `date`) VALUES
(31, 5000000, 'income', 'Gaji', '2026-03-01'),
(32, 50000, 'expense', 'Makanan', '2026-03-01'),
(33, 25000, 'expense', 'Transportasi', '2026-03-02'),
(34, 150000, 'expense', 'Belanja', '2026-03-02'),
(35, 300000, 'expense', 'Tagihan', '2026-03-03'),
(36, 75000, 'expense', 'Hiburan', '2026-03-03'),
(37, 35000, 'expense', 'Makanan', '2026-03-04'),
(38, 500000, 'income', 'Freelance', '2026-03-05'),
(39, 20000, 'expense', 'Transportasi', '2026-03-05'),
(40, 45000, 'expense', 'Kesehatan', '2026-03-06'),
(41, 120000, 'expense', 'Belanja', '2026-03-07'),
(42, 60000, 'expense', 'Makanan', '2026-03-08'),
(43, 250000, 'income', 'Bonus', '2026-03-10'),
(44, 15000, 'expense', 'Transportasi', '2026-03-10'),
(45, 85000, 'expense', 'Hiburan', '2026-03-11'),
(46, 200000, 'expense', 'Pendidikan', '2026-03-12'),
(47, 40000, 'expense', 'Makanan', '2026-03-13'),
(48, 50000, 'expense', 'Kesehatan', '2026-03-14'),
(49, 1000000, 'income', 'Investasi', '2026-03-15'),
(50, 30000, 'expense', 'Transportasi', '2026-03-15'),
(51, 175000, 'expense', 'Tagihan', '2026-03-16'),
(52, 90000, 'expense', 'Belanja', '2026-03-17'),
(53, 55000, 'expense', 'Makanan', '2026-03-18'),
(54, 65000, 'expense', 'Hiburan', '2026-03-19'),
(55, 300000, 'income', 'Freelance', '2026-03-20'),
(56, 22000, 'expense', 'Transportasi', '2026-03-21'),
(57, 80000, 'expense', 'Makanan', '2026-03-22'),
(58, 110000, 'expense', 'Belanja', '2026-03-23'),
(59, 45000, 'expense', 'Kesehatan', '2026-03-24'),
(60, 500000, 'expense', 'Lainnya', '2026-03-25'),
(61, 5000000, 'income', 'Gaji Oktober', '2026-10-01');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `money_tracker`
--
ALTER TABLE `money_tracker`
  ADD PRIMARY KEY (`id_transaction`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `money_tracker`
--
ALTER TABLE `money_tracker`
  MODIFY `id_transaction` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
