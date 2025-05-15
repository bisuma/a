-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 15 Bulan Mei 2025 pada 15.21
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_beasiswa`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `id_admin` varchar(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `level` enum('Admin','Kepala Bagian Akademik','Rektor') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `tbl_admin`
--

INSERT INTO `tbl_admin` (`id_admin`, `username`, `password`, `level`) VALUES
('1221', 'bisuma', 'bisuma', 'Admin'),
('2211', 'vincent', 'vincent', 'Rektor');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_kriteria`
--

CREATE TABLE `tbl_kriteria` (
  `id_kriteria` varchar(20) NOT NULL,
  `nim` varchar(20) DEFAULT NULL,
  `penghasilan_ortu` int(11) NOT NULL,
  `nilai_ipk` int(11) NOT NULL,
  `semester` int(11) NOT NULL,
  `tanggungan_ortu` int(11) NOT NULL,
  `saudara_kandung` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `tbl_kriteria`
--

INSERT INTO `tbl_kriteria` (`id_kriteria`, `nim`, `penghasilan_ortu`, `nilai_ipk`, `semester`, `tanggungan_ortu`, `saudara_kandung`) VALUES
('KRT001', '12', 20, 10, 10, 10, 10),
('KRT002', '21', 100, 20, 10, 10, 10);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_mahasiswa`
--

CREATE TABLE `tbl_mahasiswa` (
  `nim` varchar(20) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `program_studi` varchar(100) NOT NULL,
  `kelas` varchar(20) NOT NULL,
  `tempat_lahir` varchar(50) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('Laki-Laki','Perempuan') NOT NULL,
  `agama` varchar(20) NOT NULL,
  `alamat` text NOT NULL,
  `no_telepon` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `tbl_mahasiswa`
--

INSERT INTO `tbl_mahasiswa` (`nim`, `nama_lengkap`, `program_studi`, `kelas`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `alamat`, `no_telepon`) VALUES
('12', 'M Ruslan Iskandar Hans', 'muhammad ruslan', '7B', 'ambon', '2012-01-01', 'Laki-Laki', 'Islam', 'ambon', '12345678'),
('21', 'rajbi', 'mundir', '7A', 'sukabumi', '2025-05-14', 'Laki-Laki', 'Islam', 'ambon', '85156329861'),
('3', 'arief bisuma', 'bisuma', '7A', 'ambon', '2012-01-10', 'Perempuan', 'Islam', 'ambon', '12345');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_matriks_perbandingan`
--

CREATE TABLE `tbl_matriks_perbandingan` (
  `id_kriteria_1` varchar(20) NOT NULL,
  `id_kriteria_2` varchar(20) NOT NULL,
  `nilai_perbandingan` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_normalisasi`
--

CREATE TABLE `tbl_normalisasi` (
  `id_normalisasi` varchar(20) NOT NULL,
  `id_kriteria` varchar(20) DEFAULT NULL,
  `n_penghasilan_ortu` float NOT NULL,
  `n_nilai_ipk` float NOT NULL,
  `n_semester` float NOT NULL,
  `n_tanggungan_ortu` float NOT NULL,
  `n_saudara_kandung` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `tbl_normalisasi`
--

INSERT INTO `tbl_normalisasi` (`id_normalisasi`, `id_kriteria`, `n_penghasilan_ortu`, `n_nilai_ipk`, `n_semester`, `n_tanggungan_ortu`, `n_saudara_kandung`) VALUES
('NRM001', 'KRT001', 1, 1, 1, 1, 1),
('NRM002', 'KRT002', 0.2, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_pembobotan`
--

CREATE TABLE `tbl_pembobotan` (
  `id_pembobotan` varchar(20) NOT NULL,
  `id_normalisasi` varchar(20) DEFAULT NULL,
  `p_penghasilan_ortu` float NOT NULL,
  `p_nilai_ipk` float NOT NULL,
  `p_semester` float NOT NULL,
  `p_tanggungan_ortu` float NOT NULL,
  `p_saudara_kandung` float NOT NULL,
  `hasil_pembobotan` float NOT NULL,
  `status` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `tbl_pembobotan`
--

INSERT INTO `tbl_pembobotan` (`id_pembobotan`, `id_normalisasi`, `p_penghasilan_ortu`, `p_nilai_ipk`, `p_semester`, `p_tanggungan_ortu`, `p_saudara_kandung`, `hasil_pembobotan`, `status`) VALUES
('PBN001', 'NRM001', 25, 30, 20, 15, 10, 100, NULL),
('PBN002', 'NRM002', 5, 30, 20, 15, 10, 80, NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indeks untuk tabel `tbl_kriteria`
--
ALTER TABLE `tbl_kriteria`
  ADD PRIMARY KEY (`id_kriteria`),
  ADD KEY `nim` (`nim`);

--
-- Indeks untuk tabel `tbl_mahasiswa`
--
ALTER TABLE `tbl_mahasiswa`
  ADD PRIMARY KEY (`nim`);

--
-- Indeks untuk tabel `tbl_matriks_perbandingan`
--
ALTER TABLE `tbl_matriks_perbandingan`
  ADD PRIMARY KEY (`id_kriteria_1`,`id_kriteria_2`),
  ADD KEY `id_kriteria_2` (`id_kriteria_2`);

--
-- Indeks untuk tabel `tbl_normalisasi`
--
ALTER TABLE `tbl_normalisasi`
  ADD PRIMARY KEY (`id_normalisasi`),
  ADD KEY `tbl_normalisasi_ibfk_1` (`id_kriteria`);

--
-- Indeks untuk tabel `tbl_pembobotan`
--
ALTER TABLE `tbl_pembobotan`
  ADD PRIMARY KEY (`id_pembobotan`),
  ADD KEY `tbl_pembobotan_ibfk_1` (`id_normalisasi`);

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `tbl_kriteria`
--
ALTER TABLE `tbl_kriteria`
  ADD CONSTRAINT `tbl_kriteria_ibfk_1` FOREIGN KEY (`nim`) REFERENCES `tbl_mahasiswa` (`nim`);

--
-- Ketidakleluasaan untuk tabel `tbl_matriks_perbandingan`
--
ALTER TABLE `tbl_matriks_perbandingan`
  ADD CONSTRAINT `tbl_matriks_perbandingan_ibfk_1` FOREIGN KEY (`id_kriteria_1`) REFERENCES `tbl_kriteria` (`id_kriteria`),
  ADD CONSTRAINT `tbl_matriks_perbandingan_ibfk_2` FOREIGN KEY (`id_kriteria_2`) REFERENCES `tbl_kriteria` (`id_kriteria`);

--
-- Ketidakleluasaan untuk tabel `tbl_normalisasi`
--
ALTER TABLE `tbl_normalisasi`
  ADD CONSTRAINT `tbl_normalisasi_ibfk_1` FOREIGN KEY (`id_kriteria`) REFERENCES `tbl_kriteria` (`id_kriteria`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tbl_pembobotan`
--
ALTER TABLE `tbl_pembobotan`
  ADD CONSTRAINT `tbl_pembobotan_ibfk_1` FOREIGN KEY (`id_normalisasi`) REFERENCES `tbl_normalisasi` (`id_normalisasi`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
