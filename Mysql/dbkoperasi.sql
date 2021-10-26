-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 17, 2020 at 11:21 PM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbkoperasi`
--

-- --------------------------------------------------------

--
-- Table structure for table `anggota`
--

CREATE TABLE `anggota` (
  `nokta` char(15) NOT NULL,
  `nama` varchar(55) NOT NULL,
  `jenkel` enum('laki-laki','perempuan') DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `notelepon` char(13) NOT NULL,
  `pekerjaan` varchar(30) DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT NULL,
  `waktudaftar` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `anggota`
--

INSERT INTO `anggota` (`nokta`, `nama`, `jenkel`, `alamat`, `notelepon`, `pekerjaan`, `status`, `waktudaftar`) VALUES
('06202000001', 'fauzan ahmad', 'laki-laki', 'jalan sinduadi mlati sleman ', '082292292289', 'tata usaha', 'aktif', '2020-06-12 14:12:27'),
('06202000002', 'muthia', 'perempuan', 'jalan lati mojong nangro aceh darusalam', '08299558855', 'wirausaha', 'aktif', '2020-06-12 15:05:37'),
('06202000003', 'yayat', 'laki-laki', 'tambea', '082292297665', 'karyawan', 'aktif', '2020-06-16 11:17:36'),
('06202000004', 'jefri', 'laki-laki', 'jalan an', '085556558', 'driver', 'aktif', '2020-06-16 11:29:16'),
('06202000005', 'saldi', 'laki-laki', 'kolaka', '082225689856', 'petani', 'aktif', '2020-06-16 11:37:30');

--
-- Triggers `anggota`
--
DELIMITER $$
CREATE TRIGGER `daftaranggota` AFTER INSERT ON `anggota` FOR EACH ROW BEGIN
	insert into `riwayatanggota` SET `idriwayatanggota`='', nokta=new.nokta, nama=new.nama, waktu=now(), keterangan='Daftar Anggota Baru';
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `keluaranggota` AFTER UPDATE ON `anggota` FOR EACH ROW BEGIN
    if new.status = 'nonaktif' THEN
	INSERT INTO `riwayatanggota` SET `idriwayatanggota`='', nokta=new.nokta, nama=new.nama, waktu=NOW(), keterangan='keluar keanggotaan';
    end if;
    END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `angsuran`
--

CREATE TABLE `angsuran` (
  `idangsuran` int(11) NOT NULL,
  `idpinjaman` int(11) NOT NULL,
  `notransaksi` char(15) NOT NULL,
  `idpetugas` int(11) NOT NULL,
  `nokta` char(15) NOT NULL,
  `angsuranbunga` int(11) DEFAULT NULL,
  `totalbunga` int(11) NOT NULL,
  `angsuranpokok` int(11) DEFAULT NULL,
  `totalpokok` int(11) NOT NULL,
  `saldokredit` int(11) NOT NULL,
  `status` enum('aktif','nonaktif') DEFAULT NULL,
  `waktutransaksi` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Triggers `angsuran`
--
DELIMITER $$
CREATE TRIGGER `transaksiangsuran` AFTER UPDATE ON `angsuran` FOR EACH ROW BEGIN
    INSERT INTO riwayattransaksi SET idriwayattransaksi='', nokta=new.nokta, notransaksi=new.notransaksi, idpetugas=new.idpetugas,
    namatransaksi='angsuran pinjaman', total=new.totalbunga+new.totalpokok, waktu=NOW(), keterangan='kredit';
    END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `idpetugas` int(11) NOT NULL,
  `username` char(16) NOT NULL,
  `password` varchar(260) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`idpetugas`, `username`, `password`) VALUES
(4, 'naruto', '$2y$10$MOTq80AihMX5QtUyoyedOe7fJkz0RlR9bx05I272h7WMiO3X29lY6'),
(3, 'fauzan', '$2y$10$MOTq80AihMX5QtUyoyedOe7fJkz0RlR9bx05I272h7WMiO3X29lY6'),
(6, 'jefri', '$2y$10$MOTq80AihMX5QtUyoyedOe7fJkz0RlR9bx05I272h7WMiO3X29lY6');

-- --------------------------------------------------------

--
-- Table structure for table `masterbunga`
--

CREATE TABLE `masterbunga` (
  `idbunga` int(11) NOT NULL,
  `idpetugas` int(11) NOT NULL,
  `namabunga` enum('bunga pinjaman','bunga sianggota') NOT NULL,
  `keterangan` varchar(25) DEFAULT NULL,
  `total` float NOT NULL,
  `waktudaftar` datetime DEFAULT NULL,
  `waktuubah` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `masterbunga`
--

INSERT INTO `masterbunga` (`idbunga`, `idpetugas`, `namabunga`, `keterangan`, `total`, `waktudaftar`, `waktuubah`) VALUES
(29, 4, 'bunga sianggota', '1 tahun', 0.08, '2020-06-17 08:55:45', '0000-00-00 00:00:00'),
(30, 4, 'bunga sianggota', '6 bulan', 0.06, '2020-06-17 08:56:06', '0000-00-00 00:00:00'),
(31, 4, 'bunga sianggota', '3 bulan', 0.05, '2020-06-17 08:56:33', '0000-00-00 00:00:00'),
(32, 4, 'bunga sianggota', '1 bulan', 0.04, '2020-06-17 08:56:57', '0000-00-00 00:00:00'),
(33, 4, 'bunga pinjaman', '2 tahun', 1.5, '2020-06-17 08:57:36', '0000-00-00 00:00:00'),
(34, 4, 'bunga pinjaman', '10 bulan', 0.01, '2020-06-17 08:57:53', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `masterharga`
--

CREATE TABLE `masterharga` (
  `idharga` int(11) NOT NULL,
  `idpetugas` int(11) NOT NULL,
  `nama` enum('simpanan pokok','simpanan wajib','simpanan sukarela','simpanan masadepan','simpanan anggota','pinjaman') NOT NULL,
  `max` int(10) NOT NULL,
  `min` int(10) NOT NULL,
  `waktudaftar` datetime DEFAULT NULL,
  `waktuubah` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `masterharga`
--

INSERT INTO `masterharga` (`idharga`, `idpetugas`, `nama`, `max`, `min`, `waktudaftar`, `waktuubah`) VALUES
(13, 4, 'simpanan wajib', 25000000, 10000, '2020-05-08 10:30:26', '2020-06-16 09:49:33'),
(14, 4, 'simpanan pokok', 25000000, 10000, '2020-05-08 10:30:26', '2020-06-16 09:49:33'),
(15, 4, 'simpanan masadepan', 25000000, 100000, '2020-05-08 10:30:26', '2020-06-16 09:49:33'),
(16, 4, 'simpanan sukarela', 25000000, 250000, '2020-05-08 10:30:26', '2020-06-16 09:49:33'),
(17, 4, 'simpanan anggota', 25000000, 5000000, '2020-05-08 10:33:23', '2020-06-16 09:49:33'),
(18, 4, 'pinjaman', 5000000, 1000000, '2020-05-08 10:33:23', '2020-06-16 09:49:33');

-- --------------------------------------------------------

--
-- Table structure for table `petugas`
--

CREATE TABLE `petugas` (
  `idpetugas` int(11) NOT NULL,
  `noktp` char(16) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `jenkel` enum('laki-laki','perempuan') DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `jabatan` enum('pembina','ketua','sekretaris','bendahara','pengawas','pengelola') DEFAULT NULL,
  `tgllahir` date DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT NULL,
  `waktudaftar` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `petugas`
--

INSERT INTO `petugas` (`idpetugas`, `noktp`, `nama`, `jenkel`, `alamat`, `jabatan`, `tgllahir`, `status`, `waktudaftar`) VALUES
(3, '7401072407980001', 'tendriana hameng', 'perempuan', 'jimbung, kalikotes klaten', 'ketua', '6546-04-05', 'aktif', '2020-03-18 20:41:46'),
(4, '7401072407980002', 'fauzan ahmad', 'laki-laki', 'jakarta selatan', 'pengelola', '1970-01-02', 'aktif', '2020-03-18 20:42:06'),
(6, '7401072407980003', 'sdgsdg', 'laki-laki', 'jimbung, kalikotes klaten', 'bendahara', '0333-03-22', 'aktif', '2020-03-18 21:04:40'),
(7, '7401072407980004', 'tendriana hameng', 'laki-laki', 'jimbung, kalikotes klaten', 'bendahara', '1991-02-11', 'aktif', '2020-03-25 12:13:22');

-- --------------------------------------------------------

--
-- Table structure for table `pinjaman`
--

CREATE TABLE `pinjaman` (
  `idpinjaman` int(11) NOT NULL,
  `notransaksi` char(15) NOT NULL,
  `nokta` char(15) NOT NULL,
  `idharga` int(11) NOT NULL,
  `idpetugas` int(11) NOT NULL,
  `idbunga` int(11) DEFAULT NULL,
  `totalpinjam` int(11) NOT NULL,
  `jangkawaktu` varchar(15) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `t_pokok` int(11) NOT NULL,
  `t_bunga` int(11) NOT NULL,
  `jumlah_setor` int(11) NOT NULL,
  `tgl_mulai_a` date NOT NULL,
  `tgl_selesai_a` date NOT NULL,
  `status` enum('aktif','nonaktif') DEFAULT NULL,
  `waktutransaksi` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Triggers `pinjaman`
--
DELIMITER $$
CREATE TRIGGER `transakpinjaman` AFTER UPDATE ON `pinjaman` FOR EACH ROW BEGIN
    INSERT INTO riwayattransaksi SET idriwayattransaksi='', nokta=new.nokta, notransaksi=new.notransaksi, idpetugas=new.idpetugas,
    namatransaksi='pengajuan pinjaman', total=new.jumlah_setor, waktu=NOW(), keterangan='debit';
    END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `riwayatanggota`
--

CREATE TABLE `riwayatanggota` (
  `idriwayatanggota` int(11) NOT NULL,
  `nokta` char(15) NOT NULL,
  `nama` varchar(55) NOT NULL,
  `waktu` datetime NOT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `riwayatanggota`
--

INSERT INTO `riwayatanggota` (`idriwayatanggota`, `nokta`, `nama`, `waktu`, `keterangan`) VALUES
(22, '06202000001', 'fauzan ahmad', '2020-06-12 14:12:27', 'Daftar Anggota Baru'),
(23, '06202000002', 'muthia anak pungut', '2020-06-12 15:05:37', 'Daftar Anggota Baru'),
(24, '06202000003', 'yayat', '2020-06-16 11:17:36', 'Daftar Anggota Baru'),
(25, '06202000004', 'jefri', '2020-06-16 11:29:16', 'Daftar Anggota Baru'),
(26, '06202000005', 'saldi', '2020-06-16 11:37:30', 'Daftar Anggota Baru');

-- --------------------------------------------------------

--
-- Table structure for table `riwayattransaksi`
--

CREATE TABLE `riwayattransaksi` (
  `idriwayattransaksi` int(11) NOT NULL,
  `nokta` char(15) NOT NULL,
  `notransaksi` char(15) NOT NULL,
  `idpetugas` int(11) NOT NULL,
  `namatransaksi` varchar(30) NOT NULL,
  `total` int(11) NOT NULL,
  `waktu` datetime NOT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `riwayattransaksi`
--

INSERT INTO `riwayattransaksi` (`idriwayattransaksi`, `nokta`, `notransaksi`, `idpetugas`, `namatransaksi`, `total`, `waktu`, `keterangan`) VALUES
(45, '06202000001', '13062000001', 4, 'beli simapan', 100000, '2020-06-13 18:02:06', 'debit'),
(46, '06202000002', '13062000002', 4, 'beli simapan', 100000, '2020-06-13 18:59:09', 'debit'),
(47, '06202000001', '13062000001', 4, 'jual simapan', 100000, '2020-06-13 18:59:09', 'debit'),
(48, '06202000002', '15062000001', 4, 'sisukarela', 250000, '2020-06-15 15:04:22', 'debit'),
(49, '06202000002', '15062000001', 4, 'siwajib', 10000, '2020-06-15 15:04:22', 'debit siwajib'),
(50, '06202000002', '15062000001', 4, 'sipokok', 10000, '2020-06-15 15:04:22', 'debit sipokok'),
(51, '06202000002', '15062000001', 4, 'beli simapan', 100000, '2020-06-15 15:04:22', 'debit'),
(52, '06202000002', '15062000002', 4, 'siwajib', 50000, '2020-06-15 16:24:59', 'debit siwajib'),
(53, '06202000002', '15062000002', 4, 'sipokok', 20000, '2020-06-15 16:24:59', 'debit sipokok'),
(54, '06202000001', '15062000003', 4, 'siwajib', 50000, '2020-06-15 16:26:15', 'debit siwajib'),
(55, '06202000001', '15062000003', 4, 'sipokok', 50000, '2020-06-15 16:26:15', 'debit sipokok'),
(56, '06202000002', '15062000004', 4, 'sisukarela', 10000, '2020-06-15 17:36:36', 'debit'),
(57, '06202000002', '15062000005', 4, 'sisukarela', 10000, '2020-06-15 17:50:07', 'debit'),
(58, '06202000001', '15062000006', 4, 'siwajib', 100000, '2020-06-15 18:25:37', 'debit siwajib'),
(59, '06202000001', '15062000007', 4, 'sipokok', 50000, '2020-06-15 18:25:47', 'debit sipokok'),
(60, '06202000003', '16062000001', 4, 'siwajib', 10000, '2020-06-16 11:18:52', 'debit siwajib'),
(61, '06202000003', '16062000001', 4, 'sipokok', 10000, '2020-06-16 11:18:52', 'debit sipokok'),
(62, '06202000004', '16062000002', 4, 'siwajib', 10000, '2020-06-16 11:30:11', 'debit siwajib'),
(63, '06202000004', '16062000002', 4, 'sipokok', 10000, '2020-06-16 11:30:11', 'debit sipokok'),
(64, '06202000005', '16062000003', 4, 'siwajib', 10000, '2020-06-16 11:38:22', 'debit siwajib'),
(65, '06202000005', '16062000003', 4, 'sipokok', 10000, '2020-06-16 11:38:22', 'debit sipokok'),
(66, '06202000005', '16062000004', 4, 'sisukarela', 250000, '2020-06-16 11:39:37', 'debit'),
(67, '06202000002', '17062000001', 4, 'sianggota', 5025000, '2020-06-17 10:43:32', 'debit');

-- --------------------------------------------------------

--
-- Table structure for table `sianggota`
--

CREATE TABLE `sianggota` (
  `idsianggota` int(11) NOT NULL,
  `notransaksi` char(15) NOT NULL,
  `nokta` char(15) NOT NULL,
  `idharga` int(11) NOT NULL,
  `idpetugas` int(11) NOT NULL,
  `idbunga` int(11) NOT NULL,
  `tgl_masuk` date NOT NULL,
  `tgl_keluar` date NOT NULL,
  `dana` int(15) NOT NULL,
  `bunga` float NOT NULL,
  `totalbunga` int(15) NOT NULL,
  `status` enum('aktif','nonaktif') DEFAULT NULL,
  `waktutransaksi` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sianggota`
--

INSERT INTO `sianggota` (`idsianggota`, `notransaksi`, `nokta`, `idharga`, `idpetugas`, `idbunga`, `tgl_masuk`, `tgl_keluar`, `dana`, `bunga`, `totalbunga`, `status`, `waktutransaksi`) VALUES
(3, '17062000001', '06202000002', 17, 4, 30, '2020-06-18', '2020-12-18', 5000000, 0.06, 25000, 'aktif', '2020-06-17 10:43:32');

--
-- Triggers `sianggota`
--
DELIMITER $$
CREATE TRIGGER `transaksisianggota` AFTER INSERT ON `sianggota` FOR EACH ROW BEGIN
    INSERT INTO riwayattransaksi SET idriwayattransaksi='', nokta=new.nokta, notransaksi=new.notransaksi, idpetugas=new.idpetugas,
    namatransaksi='sianggota', total=new.dana + new.totalbunga, waktu=NOW(), keterangan='debit';
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `transaksitariksianggota` AFTER INSERT ON `sianggota` FOR EACH ROW BEGIN
    if new.status = 'nonaktif' then
    INSERT INTO riwayattransaksi SET idriwayattransaksi='', nokta=new.nokta, notransaksi=new.notransaksi, idpetugas=new.idpetugas,
    namatransaksi='pencairan sianggota', total=new.dana + new.totalbunga, waktu=NOW(), keterangan='kredit';
    end if;
    END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `simapan`
--

CREATE TABLE `simapan` (
  `idsimapan` int(11) NOT NULL,
  `notransaksi` char(15) NOT NULL,
  `nokta` char(15) NOT NULL,
  `idharga` int(11) NOT NULL,
  `idpetugas` int(11) NOT NULL,
  `nokartu` char(10) NOT NULL,
  `nilai` int(15) DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT NULL,
  `waktutransaksi` datetime DEFAULT NULL,
  `waktutransaksijual` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `simapan`
--

INSERT INTO `simapan` (`idsimapan`, `notransaksi`, `nokta`, `idharga`, `idpetugas`, `nokartu`, `nilai`, `status`, `waktutransaksi`, `waktutransaksijual`) VALUES
(8, '13062000001', '06202000001', 15, 4, '1', 100000, 'nonaktif', '2020-06-13 18:02:06', '2020-06-13 11:59:09'),
(9, '13062000002', '06202000002', 15, 4, '1', 100000, 'aktif', '2020-06-13 18:59:09', '0000-00-00 00:00:00'),
(10, '15062000001', '06202000002', 15, 4, '2', 100000, 'aktif', '2020-06-15 15:04:22', '0000-00-00 00:00:00');

--
-- Triggers `simapan`
--
DELIMITER $$
CREATE TRIGGER `transaksibelisimapan` AFTER INSERT ON `simapan` FOR EACH ROW BEGIN
    INSERT INTO riwayattransaksi SET idriwayattransaksi='', nokta=new.nokta, notransaksi=new.notransaksi, idpetugas=new.idpetugas,
    namatransaksi='beli simapan', total=new.nilai, waktu=NOW(), keterangan='debit';
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `transaksijualsimapan` AFTER UPDATE ON `simapan` FOR EACH ROW BEGIN
    INSERT INTO riwayattransaksi SET idriwayattransaksi='', nokta=new.nokta, notransaksi=new.notransaksi, idpetugas=new.idpetugas,
    namatransaksi='jual simapan', total=new.nilai, waktu=NOW(), keterangan='debit';
    END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `sisukarela`
--

CREATE TABLE `sisukarela` (
  `idsisukarela` int(11) NOT NULL,
  `notransaksi` char(15) NOT NULL,
  `nokta` char(15) NOT NULL,
  `idharga` int(11) NOT NULL,
  `idpetugas` int(11) NOT NULL,
  `debit` int(12) DEFAULT NULL,
  `kredit` int(12) DEFAULT NULL,
  `saldo` int(12) DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT NULL,
  `waktutransaksi` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sisukarela`
--

INSERT INTO `sisukarela` (`idsisukarela`, `notransaksi`, `nokta`, `idharga`, `idpetugas`, `debit`, `kredit`, `saldo`, `status`, `waktutransaksi`) VALUES
(18, '15062000001', '06202000002', 16, 4, 250000, 0, 250000, 'aktif', '2020-06-15 15:04:22'),
(19, '15062000004', '06202000002', 16, 4, 10000, 0, 260000, 'aktif', '2020-06-15 17:36:36'),
(20, '15062000005', '06202000002', 16, 4, 10000, 0, 260000, 'aktif', '2020-06-15 17:50:07'),
(21, '16062000004', '06202000005', 16, 4, 250000, 0, 250000, 'aktif', '2020-06-16 11:39:37');

--
-- Triggers `sisukarela`
--
DELIMITER $$
CREATE TRIGGER `transaksisisukarela` AFTER INSERT ON `sisukarela` FOR EACH ROW BEGIN
    IF new.kredit = 0 THEN
    INSERT INTO riwayattransaksi SET idriwayattransaksi='', nokta=new.nokta, notransaksi=new.notransaksi, idpetugas=new.idpetugas,
    namatransaksi='sisukarela', total=new.debit, waktu=NOW(), keterangan='debit';
    ELSE 
    INSERT INTO riwayattransaksi SET idriwayattransaksi='', nokta=new.nokta, notransaksi=new.notransaksi, idpetugas=new.idpetugas,
    namatransaksi='sisukarela', total=new.kredit, waktu=NOW(), keterangan='kredit';
    END IF;
    END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `siwapo`
--

CREATE TABLE `siwapo` (
  `idsiwapo` int(11) NOT NULL,
  `notransaksi` char(15) NOT NULL,
  `nokta` char(15) NOT NULL,
  `idharga` int(11) NOT NULL,
  `idpetugas` int(11) NOT NULL,
  `keterangan` varchar(20) NOT NULL,
  `subtotal` int(15) NOT NULL,
  `total` int(15) NOT NULL,
  `status` enum('aktif','nonaktif') DEFAULT NULL,
  `waktudaftar` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `siwapo`
--

INSERT INTO `siwapo` (`idsiwapo`, `notransaksi`, `nokta`, `idharga`, `idpetugas`, `keterangan`, `subtotal`, `total`, `status`, `waktudaftar`) VALUES
(30, '15062000001', '06202000002', 13, 4, 'debit siwajib', 10000, 10000, 'aktif', '2020-06-15 15:04:22'),
(31, '15062000001', '06202000002', 14, 4, 'debit sipokok', 10000, 10000, 'aktif', '2020-06-15 15:04:22'),
(32, '15062000002', '06202000002', 13, 4, 'debit siwajib', 50000, 50000, 'aktif', '2020-06-15 16:24:59'),
(33, '15062000002', '06202000002', 14, 4, 'debit sipokok', 20000, 20000, 'aktif', '2020-06-15 16:24:59'),
(34, '15062000003', '06202000001', 13, 4, 'debit siwajib', 50000, 50000, 'aktif', '2020-06-15 16:26:15'),
(35, '15062000003', '06202000001', 14, 4, 'debit sipokok', 50000, 50000, 'aktif', '2020-06-15 16:26:15'),
(36, '15062000006', '06202000001', 13, 4, 'debit siwajib', 100000, 100000, 'aktif', '2020-06-15 18:25:37'),
(37, '15062000007', '06202000001', 14, 4, 'debit sipokok', 50000, 50000, 'aktif', '2020-06-15 18:25:47'),
(38, '16062000001', '06202000003', 13, 4, 'debit siwajib', 10000, 10000, 'aktif', '2020-06-16 11:18:52'),
(39, '16062000001', '06202000003', 14, 4, 'debit sipokok', 10000, 10000, 'aktif', '2020-06-16 11:18:52'),
(40, '16062000002', '06202000004', 13, 4, 'debit siwajib', 10000, 10000, 'aktif', '2020-06-16 11:30:11'),
(41, '16062000002', '06202000004', 14, 4, 'debit sipokok', 10000, 10000, 'aktif', '2020-06-16 11:30:11'),
(42, '16062000003', '06202000005', 13, 4, 'debit siwajib', 10000, 10000, 'aktif', '2020-06-16 11:38:22'),
(43, '16062000003', '06202000005', 14, 4, 'debit sipokok', 10000, 10000, 'aktif', '2020-06-16 11:38:22');

--
-- Triggers `siwapo`
--
DELIMITER $$
CREATE TRIGGER `transaksisiwapo` AFTER INSERT ON `siwapo` FOR EACH ROW BEGIN
    IF new.keterangan = 'debit siwajib' THEN
    INSERT INTO riwayattransaksi SET idriwayattransaksi='', nokta=new.nokta, notransaksi=new.notransaksi, idpetugas=new.idpetugas, 
    namatransaksi='siwajib', total=new.subtotal, waktu=NOW(), keterangan=new.keterangan;
    END IF;
    
    IF new.keterangan = 'debit sipokok' THEN
    INSERT INTO riwayattransaksi SET idriwayattransaksi='', nokta=new.nokta, notransaksi=new.notransaksi, idpetugas=new.idpetugas,
    namatransaksi='sipokok', total=new.subtotal, waktu=NOW(), keterangan=new.keterangan;
    END IF;
    
    IF new.keterangan = 'kredit siwajib' THEN
    INSERT INTO riwayattransaksi SET idriwayattransaksi='', nokta=new.nokta, notransaksi=new.notransaksi, idpetugas=new.idpetugas,
    namatransaksi='siwajib', total=new.subtotal, waktu=NOW(), keterangan=new.keterangan;
    END IF;
    
    IF new.keterangan = 'kredit sipokok' THEN
    INSERT INTO riwayattransaksi SET idriwayattransaksi='', nokta=new.nokta, notransaksi=new.notransaksi, idpetugas=new.idpetugas,
    namatransaksi='sipokok', total=new.subtotal, waktu=NOW(), keterangan=new.keterangan;
    END IF;
    END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `view-simapan`
-- (See below for the actual view)
--
CREATE TABLE `view-simapan` (
`namapetugas` varchar(50)
,`nama` varchar(55)
,`idsimapan` int(11)
,`notransaksi` char(15)
,`nokta` char(15)
,`idharga` int(11)
,`idpetugas` int(11)
,`nokartu` char(10)
,`nilai` int(15)
,`status` enum('aktif','nonaktif')
,`waktutransaksi` datetime
,`waktutransaksijual` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view-sisukarela`
-- (See below for the actual view)
--
CREATE TABLE `view-sisukarela` (
`namapetugas` varchar(50)
,`nama` varchar(55)
,`idsisukarela` int(11)
,`notransaksi` char(15)
,`nokta` char(15)
,`idharga` int(11)
,`idpetugas` int(11)
,`debit` int(12)
,`kredit` int(12)
,`saldo` int(12)
,`status` enum('aktif','nonaktif')
,`waktutransaksi` datetime
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view-siwapo`
-- (See below for the actual view)
--
CREATE TABLE `view-siwapo` (
`namapetugas` varchar(50)
,`nama` varchar(55)
,`idsiwapo` int(11)
,`notransaksi` char(15)
,`nokta` char(15)
,`idharga` int(11)
,`idpetugas` int(11)
,`keterangan` varchar(20)
,`subtotal` int(15)
,`total` int(15)
,`status` enum('aktif','nonaktif')
,`waktudaftar` datetime
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view-transaksi`
-- (See below for the actual view)
--
CREATE TABLE `view-transaksi` (
`idriwayattransaksi` int(11)
,`nokta` char(15)
,`notransaksi` char(15)
,`idpetugas` int(11)
,`namatransaksi` varchar(30)
,`total` int(11)
,`waktu` datetime
,`keterangan` text
,`namapetugas` varchar(50)
,`namaanggota` varchar(55)
,`alamat` text
);

-- --------------------------------------------------------

--
-- Structure for view `view-simapan`
--
DROP TABLE IF EXISTS `view-simapan`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view-simapan`  AS  (select `petugas`.`nama` AS `namapetugas`,`anggota`.`nama` AS `nama`,`simapan`.`idsimapan` AS `idsimapan`,`simapan`.`notransaksi` AS `notransaksi`,`simapan`.`nokta` AS `nokta`,`simapan`.`idharga` AS `idharga`,`simapan`.`idpetugas` AS `idpetugas`,`simapan`.`nokartu` AS `nokartu`,`simapan`.`nilai` AS `nilai`,`simapan`.`status` AS `status`,`simapan`.`waktutransaksi` AS `waktutransaksi`,`simapan`.`waktutransaksijual` AS `waktutransaksijual` from ((`simapan` join `petugas` on(`petugas`.`idpetugas` = `simapan`.`idpetugas`)) join `anggota` on(`anggota`.`nokta` = `simapan`.`nokta`))) ;

-- --------------------------------------------------------

--
-- Structure for view `view-sisukarela`
--
DROP TABLE IF EXISTS `view-sisukarela`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view-sisukarela`  AS  (select `petugas`.`nama` AS `namapetugas`,`anggota`.`nama` AS `nama`,`sisukarela`.`idsisukarela` AS `idsisukarela`,`sisukarela`.`notransaksi` AS `notransaksi`,`sisukarela`.`nokta` AS `nokta`,`sisukarela`.`idharga` AS `idharga`,`sisukarela`.`idpetugas` AS `idpetugas`,`sisukarela`.`debit` AS `debit`,`sisukarela`.`kredit` AS `kredit`,`sisukarela`.`saldo` AS `saldo`,`sisukarela`.`status` AS `status`,`sisukarela`.`waktutransaksi` AS `waktutransaksi` from ((`sisukarela` join `petugas` on(`petugas`.`idpetugas` = `sisukarela`.`idpetugas`)) join `anggota` on(`anggota`.`nokta` = `sisukarela`.`nokta`))) ;

-- --------------------------------------------------------

--
-- Structure for view `view-siwapo`
--
DROP TABLE IF EXISTS `view-siwapo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view-siwapo`  AS  (select `petugas`.`nama` AS `namapetugas`,`anggota`.`nama` AS `nama`,`siwapo`.`idsiwapo` AS `idsiwapo`,`siwapo`.`notransaksi` AS `notransaksi`,`siwapo`.`nokta` AS `nokta`,`siwapo`.`idharga` AS `idharga`,`siwapo`.`idpetugas` AS `idpetugas`,`siwapo`.`keterangan` AS `keterangan`,`siwapo`.`subtotal` AS `subtotal`,`siwapo`.`total` AS `total`,`siwapo`.`status` AS `status`,`siwapo`.`waktudaftar` AS `waktudaftar` from ((`siwapo` join `petugas` on(`petugas`.`idpetugas` = `siwapo`.`idpetugas`)) join `anggota` on(`anggota`.`nokta` = `siwapo`.`nokta`))) ;

-- --------------------------------------------------------

--
-- Structure for view `view-transaksi`
--
DROP TABLE IF EXISTS `view-transaksi`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view-transaksi`  AS  (select `riwayattransaksi`.`idriwayattransaksi` AS `idriwayattransaksi`,`riwayattransaksi`.`nokta` AS `nokta`,`riwayattransaksi`.`notransaksi` AS `notransaksi`,`riwayattransaksi`.`idpetugas` AS `idpetugas`,`riwayattransaksi`.`namatransaksi` AS `namatransaksi`,`riwayattransaksi`.`total` AS `total`,`riwayattransaksi`.`waktu` AS `waktu`,`riwayattransaksi`.`keterangan` AS `keterangan`,`petugas`.`nama` AS `namapetugas`,`anggota`.`nama` AS `namaanggota`,`anggota`.`alamat` AS `alamat` from ((`riwayattransaksi` join `petugas` on(`petugas`.`idpetugas` = `riwayattransaksi`.`idpetugas`)) join `anggota` on(`anggota`.`nokta` = `riwayattransaksi`.`nokta`))) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anggota`
--
ALTER TABLE `anggota`
  ADD PRIMARY KEY (`nokta`);

--
-- Indexes for table `angsuran`
--
ALTER TABLE `angsuran`
  ADD PRIMARY KEY (`idangsuran`),
  ADD KEY `FK_angsuran` (`idpinjaman`),
  ADD KEY `FK_petugasangsuran` (`idpetugas`),
  ADD KEY `FK_anggotaangsuran` (`nokta`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD KEY `FK_petugas` (`idpetugas`);

--
-- Indexes for table `masterbunga`
--
ALTER TABLE `masterbunga`
  ADD PRIMARY KEY (`idbunga`),
  ADD KEY `FK_masterbunga` (`idpetugas`);

--
-- Indexes for table `masterharga`
--
ALTER TABLE `masterharga`
  ADD PRIMARY KEY (`idharga`);

--
-- Indexes for table `petugas`
--
ALTER TABLE `petugas`
  ADD PRIMARY KEY (`idpetugas`);

--
-- Indexes for table `pinjaman`
--
ALTER TABLE `pinjaman`
  ADD PRIMARY KEY (`idpinjaman`),
  ADD KEY `FK_pinjaman` (`nokta`),
  ADD KEY `FK_hargapinjaman` (`idharga`),
  ADD KEY `FK_masterbungapinjaman` (`idbunga`),
  ADD KEY `FK_petugaspinjaman` (`idpetugas`);

--
-- Indexes for table `riwayatanggota`
--
ALTER TABLE `riwayatanggota`
  ADD PRIMARY KEY (`idriwayatanggota`),
  ADD KEY `FK_riwayatanggota` (`nokta`);

--
-- Indexes for table `riwayattransaksi`
--
ALTER TABLE `riwayattransaksi`
  ADD PRIMARY KEY (`idriwayattransaksi`),
  ADD KEY `FK_riwayattransaksi` (`nokta`),
  ADD KEY `FK_petugasriwayattransaksi` (`idpetugas`);

--
-- Indexes for table `sianggota`
--
ALTER TABLE `sianggota`
  ADD PRIMARY KEY (`idsianggota`),
  ADD KEY `FK_petugassianggota` (`idpetugas`),
  ADD KEY `FK_sianggota` (`nokta`),
  ADD KEY `FK_hargasianggota` (`idharga`),
  ADD KEY `FK_masterbungasianggota` (`idbunga`);

--
-- Indexes for table `simapan`
--
ALTER TABLE `simapan`
  ADD PRIMARY KEY (`idsimapan`),
  ADD KEY `FK_petugassimapan` (`idpetugas`),
  ADD KEY `FK_hargasimapan` (`idharga`),
  ADD KEY `FK_simapan` (`nokta`);

--
-- Indexes for table `sisukarela`
--
ALTER TABLE `sisukarela`
  ADD PRIMARY KEY (`idsisukarela`),
  ADD KEY `FK_petugassisukarela` (`idpetugas`),
  ADD KEY `FK_hargasisukarela` (`idharga`),
  ADD KEY `FK_sisukarela` (`nokta`);

--
-- Indexes for table `siwapo`
--
ALTER TABLE `siwapo`
  ADD PRIMARY KEY (`idsiwapo`),
  ADD KEY `FK_petugassiwapo` (`idpetugas`),
  ADD KEY `FK_hargasiwapo` (`idharga`),
  ADD KEY `FK_siwapo` (`nokta`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `angsuran`
--
ALTER TABLE `angsuran`
  MODIFY `idangsuran` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `masterbunga`
--
ALTER TABLE `masterbunga`
  MODIFY `idbunga` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `masterharga`
--
ALTER TABLE `masterharga`
  MODIFY `idharga` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `petugas`
--
ALTER TABLE `petugas`
  MODIFY `idpetugas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pinjaman`
--
ALTER TABLE `pinjaman`
  MODIFY `idpinjaman` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `riwayatanggota`
--
ALTER TABLE `riwayatanggota`
  MODIFY `idriwayatanggota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `riwayattransaksi`
--
ALTER TABLE `riwayattransaksi`
  MODIFY `idriwayattransaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `sianggota`
--
ALTER TABLE `sianggota`
  MODIFY `idsianggota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `simapan`
--
ALTER TABLE `simapan`
  MODIFY `idsimapan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `sisukarela`
--
ALTER TABLE `sisukarela`
  MODIFY `idsisukarela` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `siwapo`
--
ALTER TABLE `siwapo`
  MODIFY `idsiwapo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `angsuran`
--
ALTER TABLE `angsuran`
  ADD CONSTRAINT `FK_anggotaangsuran` FOREIGN KEY (`nokta`) REFERENCES `anggota` (`nokta`),
  ADD CONSTRAINT `FK_angsuran` FOREIGN KEY (`idpinjaman`) REFERENCES `pinjaman` (`idpinjaman`),
  ADD CONSTRAINT `FK_petugasangsuran` FOREIGN KEY (`idpetugas`) REFERENCES `petugas` (`idpetugas`);

--
-- Constraints for table `login`
--
ALTER TABLE `login`
  ADD CONSTRAINT `FK_petugas` FOREIGN KEY (`idpetugas`) REFERENCES `petugas` (`idpetugas`);

--
-- Constraints for table `masterbunga`
--
ALTER TABLE `masterbunga`
  ADD CONSTRAINT `FK_masterbunga` FOREIGN KEY (`idpetugas`) REFERENCES `petugas` (`idpetugas`);

--
-- Constraints for table `pinjaman`
--
ALTER TABLE `pinjaman`
  ADD CONSTRAINT `FK_hargapinjaman` FOREIGN KEY (`idharga`) REFERENCES `masterharga` (`idharga`),
  ADD CONSTRAINT `FK_masterbungapinjaman` FOREIGN KEY (`idbunga`) REFERENCES `masterbunga` (`idbunga`),
  ADD CONSTRAINT `FK_petugaspinjaman` FOREIGN KEY (`idpetugas`) REFERENCES `petugas` (`idpetugas`),
  ADD CONSTRAINT `FK_pinjaman` FOREIGN KEY (`nokta`) REFERENCES `anggota` (`nokta`);

--
-- Constraints for table `riwayatanggota`
--
ALTER TABLE `riwayatanggota`
  ADD CONSTRAINT `FK_riwayatanggota` FOREIGN KEY (`nokta`) REFERENCES `anggota` (`nokta`) ON UPDATE CASCADE;

--
-- Constraints for table `riwayattransaksi`
--
ALTER TABLE `riwayattransaksi`
  ADD CONSTRAINT `FK_petugasriwayattransaksi` FOREIGN KEY (`idpetugas`) REFERENCES `petugas` (`idpetugas`),
  ADD CONSTRAINT `FK_riwayattransaksi` FOREIGN KEY (`nokta`) REFERENCES `anggota` (`nokta`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `sianggota`
--
ALTER TABLE `sianggota`
  ADD CONSTRAINT `FK_hargasianggota` FOREIGN KEY (`idharga`) REFERENCES `masterharga` (`idharga`),
  ADD CONSTRAINT `FK_masterbungasianggota` FOREIGN KEY (`idbunga`) REFERENCES `masterbunga` (`idbunga`),
  ADD CONSTRAINT `FK_petugassianggota` FOREIGN KEY (`idpetugas`) REFERENCES `petugas` (`idpetugas`),
  ADD CONSTRAINT `FK_sianggota` FOREIGN KEY (`nokta`) REFERENCES `anggota` (`nokta`);

--
-- Constraints for table `simapan`
--
ALTER TABLE `simapan`
  ADD CONSTRAINT `FK_hargasimapan` FOREIGN KEY (`idharga`) REFERENCES `masterharga` (`idharga`),
  ADD CONSTRAINT `FK_petugassimapan` FOREIGN KEY (`idpetugas`) REFERENCES `petugas` (`idpetugas`),
  ADD CONSTRAINT `FK_simapan` FOREIGN KEY (`nokta`) REFERENCES `anggota` (`nokta`);

--
-- Constraints for table `sisukarela`
--
ALTER TABLE `sisukarela`
  ADD CONSTRAINT `FK_hargasisukarela` FOREIGN KEY (`idharga`) REFERENCES `masterharga` (`idharga`),
  ADD CONSTRAINT `FK_petugassisukarela` FOREIGN KEY (`idpetugas`) REFERENCES `petugas` (`idpetugas`),
  ADD CONSTRAINT `FK_sisukarela` FOREIGN KEY (`nokta`) REFERENCES `anggota` (`nokta`);

--
-- Constraints for table `siwapo`
--
ALTER TABLE `siwapo`
  ADD CONSTRAINT `FK_hargasiwapo` FOREIGN KEY (`idharga`) REFERENCES `masterharga` (`idharga`),
  ADD CONSTRAINT `FK_petugassiwapo` FOREIGN KEY (`idpetugas`) REFERENCES `petugas` (`idpetugas`),
  ADD CONSTRAINT `FK_siwapo` FOREIGN KEY (`nokta`) REFERENCES `anggota` (`nokta`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
