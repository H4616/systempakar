-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 11 Jun 2025 pada 04.43
-- Versi server: 10.4.27-MariaDB
-- Versi PHP: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sistem_pakar_ginjal`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `aturan`
--

CREATE TABLE `aturan` (
  `id_aturan` int(11) NOT NULL,
  `id_gejala` int(11) DEFAULT NULL,
  `id_penyakit` int(11) DEFAULT NULL,
  `belief` decimal(3,2) DEFAULT NULL,
  `disbelief` decimal(3,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `aturan`
--

INSERT INTO `aturan` (`id_aturan`, `id_gejala`, `id_penyakit`, `belief`, `disbelief`) VALUES
(31, 1, 1, '0.90', '0.10'),
(32, 2, 1, '0.80', '0.20'),
(33, 3, 2, '0.70', '0.30'),
(34, 4, 3, '0.60', '0.40'),
(35, 5, 4, '0.75', '0.25'),
(36, 6, 1, '0.80', '0.20'),
(37, 7, 2, '0.65', '0.35'),
(38, 8, 1, '0.85', '0.15'),
(39, 9, 5, '0.70', '0.30'),
(40, 10, 1, '0.80', '0.20'),
(41, 11, 3, '0.65', '0.35'),
(42, 12, 2, '0.70', '0.30'),
(43, 13, 4, '0.80', '0.20'),
(44, 14, 2, '0.75', '0.25'),
(45, 15, 4, '0.70', '0.30'),
(46, 16, 2, '0.65', '0.35'),
(47, 17, 1, '0.85', '0.15'),
(48, 18, 3, '0.70', '0.30'),
(49, 19, 4, '0.90', '0.10'),
(50, 20, 5, '0.70', '0.30'),
(51, 21, 1, '0.80', '0.20'),
(52, 22, 4, '0.75', '0.25'),
(53, 23, 2, '0.70', '0.30'),
(54, 24, 1, '0.85', '0.15'),
(55, 25, 3, '0.60', '0.40'),
(56, 26, 4, '0.75', '0.25'),
(57, 27, 2, '0.80', '0.20'),
(58, 28, 1, '0.90', '0.10'),
(59, 29, 2, '0.70', '0.30'),
(60, 30, 5, '0.80', '0.20');

-- --------------------------------------------------------

--
-- Struktur dari tabel `gejala`
--

CREATE TABLE `gejala` (
  `id_gejala` int(11) NOT NULL,
  `nama_gejala` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `gejala`
--

INSERT INTO `gejala` (`id_gejala`, `nama_gejala`) VALUES
(1, 'Sakit Pinggang'),
(2, 'Mual'),
(3, 'Sesak Napas'),
(4, 'Kelelahan'),
(5, 'Kehilangan Nafsu Makan'),
(6, 'Peningkatan Tekanan Darah'),
(7, 'Pembengkakan Kaki'),
(8, 'Kencing Berdarah'),
(9, 'Urine Berbusa'),
(10, 'Nyeri Saat Buang Air Kecil'),
(11, 'Kesulitan Bernapas'),
(12, 'Sakit Kepala'),
(13, 'Berkeringat Dingin'),
(14, 'Pusing'),
(15, 'Kulit Kering dan Gatal'),
(16, 'Bengkak pada Wajah dan Mata'),
(17, 'Perubahan Warna Urine'),
(18, 'Sering Buang Air Kecil di Malam Hari'),
(19, 'Peningkatan Berat Badan Mendadak'),
(20, 'Nyeri pada Perut Bagian Bawah'),
(21, 'Rasa Sesak di Dada'),
(22, 'Rasa Tidak Nyaman di Perut'),
(23, 'Mulut Kering atau Haus Berlebihan'),
(24, 'Mudah Lelah atau Kelelahan Ekstrem'),
(25, 'Diare atau Konstipasi'),
(26, 'Bercak Darah pada Urine'),
(27, 'Pucat pada Kulit'),
(28, 'Sakit pada Punggung Bawah'),
(29, 'Pendarahan Gusi'),
(30, 'Kaki atau Pergelangan Kaki Bengkak');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penyakit`
--

CREATE TABLE `penyakit` (
  `id_penyakit` int(11) NOT NULL,
  `nama_penyakit` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `penyakit`
--

INSERT INTO `penyakit` (`id_penyakit`, `nama_penyakit`) VALUES
(1, 'Gagal Ginjal Akut'),
(2, 'Gagal Ginjal Kronis'),
(3, 'Infeksi Saluran Kemih'),
(4, 'Dehidrasi'),
(5, 'Penyakit Ginjal Polikistik'),
(6, 'Nefritis');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `aturan`
--
ALTER TABLE `aturan`
  ADD PRIMARY KEY (`id_aturan`),
  ADD KEY `id_gejala` (`id_gejala`),
  ADD KEY `id_penyakit` (`id_penyakit`);

--
-- Indeks untuk tabel `gejala`
--
ALTER TABLE `gejala`
  ADD PRIMARY KEY (`id_gejala`);

--
-- Indeks untuk tabel `penyakit`
--
ALTER TABLE `penyakit`
  ADD PRIMARY KEY (`id_penyakit`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `aturan`
--
ALTER TABLE `aturan`
  MODIFY `id_aturan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT untuk tabel `gejala`
--
ALTER TABLE `gejala`
  MODIFY `id_gejala` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT untuk tabel `penyakit`
--
ALTER TABLE `penyakit`
  MODIFY `id_penyakit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `aturan`
--
ALTER TABLE `aturan`
  ADD CONSTRAINT `aturan_ibfk_1` FOREIGN KEY (`id_gejala`) REFERENCES `gejala` (`id_gejala`),
  ADD CONSTRAINT `aturan_ibfk_2` FOREIGN KEY (`id_penyakit`) REFERENCES `penyakit` (`id_penyakit`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
