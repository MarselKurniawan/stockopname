-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 30, 2024 at 07:01 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stock_opname`
--

-- --------------------------------------------------------

--
-- Table structure for table `bahan`
--

CREATE TABLE `bahan` (
  `id_bahan` int(11) NOT NULL,
  `nama_bahan` varchar(100) NOT NULL,
  `satuan` varchar(50) DEFAULT NULL,
  `stok_awal` decimal(10,2) DEFAULT 0.00,
  `stok_terakhir` decimal(10,2) DEFAULT 0.00,
  `harga_beli` decimal(10,2) DEFAULT NULL,
  `harga_jual` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bahan`
--

INSERT INTO `bahan` (`id_bahan`, `nama_bahan`, `satuan`, `stok_awal`, `stok_terakhir`, `harga_beli`, `harga_jual`, `created_at`) VALUES
(1, 'Tepung Terigu', 'kg', 100.00, 150.00, 9000.00, 13000.00, '2024-10-21 16:59:18'),
(2, 'Gula Pasir', 'kg', 200.00, 180.00, 12000.00, 15000.00, '2024-10-21 16:59:18'),
(3, 'Mentega', 'kg', 50.00, 75.00, 50000.00, 70000.00, '2024-10-21 16:59:18');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `nama_customer` varchar(100) NOT NULL,
  `kontak_customer` varchar(50) DEFAULT NULL,
  `alamat_customer` text DEFAULT NULL,
  `email_customer` varchar(100) DEFAULT NULL,
  `kota_id` int(11) DEFAULT NULL,
  `level` enum('supplier','toko','customer','reseller','loyal_customer') NOT NULL DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `nama_customer`, `kontak_customer`, `alamat_customer`, `email_customer`, `kota_id`, `level`, `created_at`) VALUES
(1, 'Customer 1', '081234567890', 'Alamat A', 'customer1@example.com', 1, 'loyal_customer', '2024-10-20 07:06:44'),
(2, 'Customer 2', '081234567891', 'Alamat B', 'customer2@example.com', 2, 'reseller', '2024-10-20 07:06:44');

-- --------------------------------------------------------

--
-- Table structure for table `kota`
--

CREATE TABLE `kota` (
  `id` int(11) NOT NULL,
  `nama_kota` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kota`
--

INSERT INTO `kota` (`id`, `nama_kota`, `created_at`) VALUES
(1, 'Semarang', '2024-10-20 07:06:44'),
(2, 'Surabaya', '2024-10-20 07:06:44'),
(3, 'Lokseumawe', '2024-10-20 07:06:44');

-- --------------------------------------------------------

--
-- Table structure for table `pabrik`
--

CREATE TABLE `pabrik` (
  `id_pabrik` int(11) NOT NULL,
  `produk_id` int(11) NOT NULL,
  `toko_id` int(11) NOT NULL,
  `hasil_roti` int(11) NOT NULL,
  `nominal` decimal(10,2) NOT NULL,
  `tanggal_keluar` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pabrik`
--

INSERT INTO `pabrik` (`id_pabrik`, `produk_id`, `toko_id`, `hasil_roti`, `nominal`, `tanggal_keluar`) VALUES
(2, 38, 2, 25, 250000.00, '2024-10-29');

-- --------------------------------------------------------

--
-- Table structure for table `penitipan_nota`
--

CREATE TABLE `penitipan_nota` (
  `id` int(11) NOT NULL,
  `toko_id` int(11) DEFAULT NULL,
  `produk_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `tanggal_penitipan` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `perhitungan_stok`
--

CREATE TABLE `perhitungan_stok` (
  `id_perhitungan` int(11) NOT NULL,
  `id_bahan` int(11) DEFAULT NULL,
  `stok_akhir` decimal(10,2) NOT NULL,
  `HPP` decimal(10,2) NOT NULL,
  `tanggal_perhitungan` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` int(11) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `kemasan` enum('mika','stoples','paketan') DEFAULT NULL,
  `ukuran_stoples` enum('Bulat Kecil','Bulat Besar','Bulat Tanggung','Stoples Besar','Stoples Persegi') DEFAULT NULL,
  `ukuran_mika` enum('250 gram','350 gram') DEFAULT NULL,
  `ukuran_paket` enum('bulat isi 4','bulat isi 6','kotak isi 6','kotak isi 3') DEFAULT NULL,
  `harga` decimal(10,2) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `nama_produk`, `kemasan`, `ukuran_stoples`, `ukuran_mika`, `ukuran_paket`, `harga`, `stock`, `created_at`) VALUES
(18, 'Nastart ', 'stoples', 'Stoples Besar', NULL, NULL, NULL, NULL, '2024-10-26 08:18:30'),
(19, 'Kastengel ', 'stoples', 'Bulat Kecil', NULL, NULL, NULL, NULL, '2024-10-26 08:19:41'),
(20, 'Kastengel', 'stoples', 'Bulat Besar', NULL, NULL, NULL, NULL, '2024-10-26 08:20:44'),
(21, 'Kastengel', 'stoples', 'Bulat Tanggung', NULL, NULL, NULL, NULL, '2024-10-26 08:20:44'),
(22, 'Kastengel', 'stoples', 'Stoples Besar', NULL, NULL, NULL, NULL, '2024-10-26 08:20:55'),
(23, 'Assorted', 'stoples', 'Stoples Besar', NULL, NULL, NULL, NULL, '2024-10-26 08:27:04'),
(24, 'Roti Salju', 'stoples', 'Stoples Besar', NULL, NULL, NULL, NULL, '2024-10-26 08:27:04'),
(25, 'Roti Mente', 'stoples', 'Stoples Besar', NULL, NULL, NULL, NULL, '2024-10-26 08:27:04'),
(26, 'Nastart Keranjang', 'stoples', 'Stoples Besar', NULL, NULL, NULL, NULL, '2024-10-26 08:27:04'),
(27, 'Roti Salju', 'stoples', 'Bulat Tanggung', NULL, NULL, NULL, NULL, '2024-10-26 08:27:04'),
(28, 'Assorted', 'stoples', 'Bulat Tanggung', NULL, NULL, NULL, NULL, '2024-10-26 08:27:04'),
(29, 'Mente', 'stoples', 'Bulat Tanggung', NULL, NULL, NULL, NULL, '2024-10-26 08:27:04'),
(30, 'Nastart', 'stoples', 'Bulat Tanggung', NULL, NULL, NULL, NULL, '2024-10-26 08:27:04'),
(31, 'Nastart Keranjang', 'stoples', 'Bulat Tanggung', NULL, NULL, NULL, NULL, '2024-10-26 08:27:04'),
(32, 'Salju ', 'stoples', 'Bulat Besar', NULL, NULL, NULL, NULL, '2024-10-26 08:27:04'),
(33, 'Mente', 'stoples', 'Bulat Besar', NULL, NULL, NULL, NULL, '2024-10-26 08:36:17'),
(34, 'Nastart', 'stoples', 'Bulat Besar', NULL, NULL, NULL, NULL, '2024-10-26 08:36:17'),
(35, 'Assorted', 'stoples', 'Bulat Besar', NULL, NULL, NULL, NULL, '2024-10-26 08:36:17'),
(36, 'Mente', 'stoples', 'Bulat Kecil', NULL, NULL, NULL, NULL, '2024-10-26 08:36:17'),
(37, 'Crunchy Peanut', 'stoples', 'Bulat Kecil', NULL, NULL, NULL, NULL, '2024-10-26 08:36:17'),
(38, 'Chocolate Peanut', 'stoples', 'Bulat Kecil', NULL, NULL, NULL, NULL, '2024-10-26 08:36:17'),
(39, 'Almond Cookies', 'stoples', 'Bulat Kecil', NULL, NULL, NULL, NULL, '2024-10-26 08:36:17'),
(40, 'Lidah Kucing Keju', 'stoples', 'Stoples Persegi', NULL, NULL, NULL, NULL, '2024-10-26 08:36:17'),
(41, 'Lidah Kucing Coklat Keju', 'stoples', 'Stoples Persegi', NULL, NULL, NULL, NULL, '2024-10-26 08:36:17'),
(42, 'Crunchy Peanut', 'stoples', 'Bulat Tanggung', NULL, NULL, NULL, NULL, '2024-10-26 08:36:17'),
(43, 'Chocolate Peanut', 'stoples', 'Bulat Tanggung', NULL, NULL, NULL, NULL, '2024-10-26 08:36:17'),
(44, 'Paket Bulat', 'paketan', NULL, NULL, 'bulat isi 4', NULL, NULL, '2024-10-26 08:36:17'),
(45, 'Paket Bulat', 'paketan', NULL, NULL, 'bulat isi 6', NULL, NULL, '2024-10-26 08:36:17'),
(46, 'Paket Kotak', 'paketan', NULL, NULL, 'kotak isi 6', NULL, NULL, '2024-10-26 08:36:17'),
(47, 'Paket Kotak ', 'paketan', NULL, NULL, 'kotak isi 3', NULL, NULL, '2024-10-26 08:36:17'),
(48, 'Sagu Keju', 'stoples', 'Bulat Besar', NULL, NULL, NULL, NULL, '2024-10-26 08:37:55'),
(49, 'Kue Tanduk ', 'stoples', 'Stoples Persegi', NULL, NULL, NULL, NULL, '2024-10-26 08:37:55'),
(50, 'Kastengel', 'mika', NULL, '250 gram', NULL, NULL, NULL, '2024-10-26 08:44:13'),
(51, 'Nastart Keranjang ', 'mika', NULL, '350 gram', NULL, NULL, NULL, '2024-10-26 08:44:13'),
(52, 'Roti Cherry Keju', 'mika', NULL, '350 gram', NULL, NULL, NULL, '2024-10-26 08:44:13'),
(53, 'Roti Coklat Manis', 'mika', NULL, '350 gram', NULL, NULL, NULL, '2024-10-26 08:44:13'),
(54, 'Roti Salju', 'mika', NULL, '250 gram', NULL, NULL, NULL, '2024-10-26 08:44:13'),
(55, 'Roti Gendu', 'mika', NULL, '250 gram', NULL, NULL, NULL, '2024-10-26 08:44:13'),
(56, 'Roti Kacang', 'mika', NULL, '250 gram', NULL, NULL, NULL, '2024-10-26 08:44:13'),
(57, 'Nastart', 'mika', NULL, '250 gram', NULL, NULL, NULL, '2024-10-26 08:44:13'),
(58, 'Roti Valentine', 'mika', NULL, '350 gram', NULL, NULL, NULL, '2024-10-26 08:44:13'),
(59, 'Roti Strawbery ', 'mika', NULL, '350 gram', NULL, NULL, NULL, '2024-10-26 08:44:13'),
(60, 'Roti Coklat Kacang', 'mika', NULL, '250 gram', NULL, NULL, NULL, '2024-10-26 08:44:13'),
(61, 'Roti Mente', 'mika', NULL, '350 gram', NULL, NULL, NULL, '2024-10-26 08:44:13'),
(62, 'Roti Coklat Mente', 'mika', NULL, '350 gram', NULL, NULL, NULL, '2024-10-26 08:44:13'),
(63, 'Assorted', 'mika', NULL, '350 gram', NULL, NULL, NULL, '2024-10-26 08:44:13');

-- --------------------------------------------------------

--
-- Table structure for table `retur`
--

CREATE TABLE `retur` (
  `id` int(11) NOT NULL,
  `toko_id` int(11) DEFAULT NULL,
  `produk_id` int(11) DEFAULT NULL,
  `jumlah_retur` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `tanggal_retur` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `retur`
--

INSERT INTO `retur` (`id`, `toko_id`, `produk_id`, `jumlah_retur`, `customer_id`, `tanggal_retur`, `created_at`) VALUES
(3, 1, 52, 12, 2, '2024-10-01', '2024-10-28 14:54:05');

-- --------------------------------------------------------

--
-- Table structure for table `stok`
--

CREATE TABLE `stok` (
  `id` int(11) NOT NULL,
  `toko_id` int(11) DEFAULT NULL,
  `produk_id` int(11) DEFAULT NULL,
  `jumlah_stok` int(11) DEFAULT NULL,
  `harga_beli` decimal(10,2) DEFAULT NULL,
  `harga_jual` decimal(10,2) DEFAULT NULL,
  `tanggal_masuk` date DEFAULT NULL,
  `tanggal_kadaluarsa` date DEFAULT NULL,
  `laku` int(11) NOT NULL,
  `laku_nominal` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stok`
--

INSERT INTO `stok` (`id`, `toko_id`, `produk_id`, `jumlah_stok`, `harga_beli`, `harga_jual`, `tanggal_masuk`, `tanggal_kadaluarsa`, `laku`, `laku_nominal`, `created_at`) VALUES
(3, 1, 39, 132, 12300.00, 23000.00, '2024-10-03', '2024-10-30', 12, 120000.00, '2024-10-28 15:16:58');

-- --------------------------------------------------------

--
-- Table structure for table `stok_keluar`
--

CREATE TABLE `stok_keluar` (
  `id_stok_keluar` int(11) NOT NULL,
  `id_bahan` int(11) DEFAULT NULL,
  `jumlah` decimal(10,2) NOT NULL,
  `tanggal_keluar` date NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stok_keluar`
--

INSERT INTO `stok_keluar` (`id_stok_keluar`, `id_bahan`, `jumlah`, `tanggal_keluar`, `keterangan`) VALUES
(1, 1, 30.00, '2024-10-04', 'Digunakan untuk produksi'),
(2, 2, 50.00, '2024-10-05', 'Digunakan untuk penjualan'),
(3, 3, 10.00, '2024-10-06', 'Digunakan untuk produksi'),
(4, 1, 20.00, '2024-10-07', 'Digunakan untuk produksi');

-- --------------------------------------------------------

--
-- Table structure for table `stok_masuk`
--

CREATE TABLE `stok_masuk` (
  `id_stok_masuk` int(11) NOT NULL,
  `id_bahan` int(11) DEFAULT NULL,
  `jumlah` decimal(10,2) NOT NULL,
  `harga_beli` decimal(10,2) NOT NULL,
  `tanggal_masuk` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stok_masuk`
--

INSERT INTO `stok_masuk` (`id_stok_masuk`, `id_bahan`, `jumlah`, `harga_beli`, `tanggal_masuk`) VALUES
(1, 1, 50.00, 9000.00, '2024-10-02'),
(2, 2, 30.00, 12000.00, '2024-10-03'),
(3, 3, 25.00, 50000.00, '2024-10-03'),
(4, 1, 50.00, 9500.00, '2024-10-05');

-- --------------------------------------------------------

--
-- Table structure for table `toko`
--

CREATE TABLE `toko` (
  `id` int(11) NOT NULL,
  `nama_toko` varchar(100) NOT NULL,
  `alamat_toko` text NOT NULL,
  `telepon` varchar(15) DEFAULT NULL,
  `kota_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `toko`
--

INSERT INTO `toko` (`id`, `nama_toko`, `alamat_toko`, `telepon`, `kota_id`, `created_at`) VALUES
(1, 'Gaia Sendangguwo', 'Alamat Toko A1', '081234567892', 1, '2024-10-20 07:06:45'),
(2, 'Aneka Jaya Sambiroto', 'Alamat Toko B1', '081234567893', 2, '2024-10-20 07:06:45');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `toko_id` int(11) DEFAULT NULL,
  `kode_transaksi` varchar(50) NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `tanggal_transaksi` date DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bahan`
--
ALTER TABLE `bahan`
  ADD PRIMARY KEY (`id_bahan`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_customer` (`email_customer`),
  ADD KEY `kota_id` (`kota_id`);

--
-- Indexes for table `kota`
--
ALTER TABLE `kota`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pabrik`
--
ALTER TABLE `pabrik`
  ADD PRIMARY KEY (`id_pabrik`),
  ADD UNIQUE KEY `produk_id` (`produk_id`),
  ADD UNIQUE KEY `toko_id` (`toko_id`);

--
-- Indexes for table `penitipan_nota`
--
ALTER TABLE `penitipan_nota`
  ADD PRIMARY KEY (`id`),
  ADD KEY `toko_id` (`toko_id`),
  ADD KEY `produk_id` (`produk_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `perhitungan_stok`
--
ALTER TABLE `perhitungan_stok`
  ADD PRIMARY KEY (`id_perhitungan`),
  ADD KEY `id_bahan` (`id_bahan`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `retur`
--
ALTER TABLE `retur`
  ADD PRIMARY KEY (`id`),
  ADD KEY `toko_id` (`toko_id`),
  ADD KEY `produk_id` (`produk_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `stok`
--
ALTER TABLE `stok`
  ADD PRIMARY KEY (`id`),
  ADD KEY `toko_id` (`toko_id`),
  ADD KEY `produk_id` (`produk_id`);

--
-- Indexes for table `stok_keluar`
--
ALTER TABLE `stok_keluar`
  ADD PRIMARY KEY (`id_stok_keluar`),
  ADD KEY `id_bahan` (`id_bahan`);

--
-- Indexes for table `stok_masuk`
--
ALTER TABLE `stok_masuk`
  ADD PRIMARY KEY (`id_stok_masuk`),
  ADD KEY `id_bahan` (`id_bahan`);

--
-- Indexes for table `toko`
--
ALTER TABLE `toko`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kota_id` (`kota_id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `toko_id` (`toko_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bahan`
--
ALTER TABLE `bahan`
  MODIFY `id_bahan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `kota`
--
ALTER TABLE `kota`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pabrik`
--
ALTER TABLE `pabrik`
  MODIFY `id_pabrik` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `penitipan_nota`
--
ALTER TABLE `penitipan_nota`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `perhitungan_stok`
--
ALTER TABLE `perhitungan_stok`
  MODIFY `id_perhitungan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `retur`
--
ALTER TABLE `retur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `stok`
--
ALTER TABLE `stok`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `stok_keluar`
--
ALTER TABLE `stok_keluar`
  MODIFY `id_stok_keluar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `stok_masuk`
--
ALTER TABLE `stok_masuk`
  MODIFY `id_stok_masuk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `toko`
--
ALTER TABLE `toko`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_ibfk_1` FOREIGN KEY (`kota_id`) REFERENCES `kota` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `pabrik`
--
ALTER TABLE `pabrik`
  ADD CONSTRAINT `pabrik_ibfk_1` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pabrik_ibfk_2` FOREIGN KEY (`toko_id`) REFERENCES `toko` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `penitipan_nota`
--
ALTER TABLE `penitipan_nota`
  ADD CONSTRAINT `penitipan_nota_ibfk_1` FOREIGN KEY (`toko_id`) REFERENCES `toko` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `penitipan_nota_ibfk_2` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `penitipan_nota_ibfk_3` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `perhitungan_stok`
--
ALTER TABLE `perhitungan_stok`
  ADD CONSTRAINT `perhitungan_stok_ibfk_1` FOREIGN KEY (`id_bahan`) REFERENCES `bahan` (`id_bahan`) ON DELETE CASCADE;

--
-- Constraints for table `retur`
--
ALTER TABLE `retur`
  ADD CONSTRAINT `retur_ibfk_1` FOREIGN KEY (`toko_id`) REFERENCES `toko` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `retur_ibfk_2` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `retur_ibfk_3` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stok`
--
ALTER TABLE `stok`
  ADD CONSTRAINT `stok_ibfk_1` FOREIGN KEY (`toko_id`) REFERENCES `toko` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stok_ibfk_2` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stok_keluar`
--
ALTER TABLE `stok_keluar`
  ADD CONSTRAINT `stok_keluar_ibfk_1` FOREIGN KEY (`id_bahan`) REFERENCES `bahan` (`id_bahan`) ON DELETE CASCADE;

--
-- Constraints for table `stok_masuk`
--
ALTER TABLE `stok_masuk`
  ADD CONSTRAINT `stok_masuk_ibfk_1` FOREIGN KEY (`id_bahan`) REFERENCES `bahan` (`id_bahan`) ON DELETE CASCADE;

--
-- Constraints for table `toko`
--
ALTER TABLE `toko`
  ADD CONSTRAINT `toko_ibfk_1` FOREIGN KEY (`kota_id`) REFERENCES `kota` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`toko_id`) REFERENCES `toko` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
