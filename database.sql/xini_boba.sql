-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2026 at 06:14 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `xini_boba`
--

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `nama_menu` varchar(100) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `status_menu` varchar(20) DEFAULT NULL,
  `stok` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `nama_menu`, `harga`, `gambar`, `status_menu`, `stok`) VALUES
(1, 'Brown Sugar Boba', 15000, 'https://images.unsplash.com/photo-1558857563-b371033873b8?q=80&w=800&auto=format&fit=crop', 'Ready', 12),
(2, 'Matcha Milk', 18000, 'https://images.unsplash.com/photo-1515823064-d6e0c04616a7?q=80&w=800&auto=format&fit=crop', 'Ready', 12),
(3, 'Chocolate Boba', 17000, 'https://images.unsplash.com/photo-1525385133512-2f3bdd039054?q=80&w=800&auto=format&fit=crop', 'Ready', 12),
(4, 'moccalate', 13000, 'https://i.pinimg.com/1200x/b1/8d/50/b18d50fa62686644792732a3f609275f.jpg', 'Ready', 12),
(5, 'pop Ice', 5000, 'https://i.pinimg.com/736x/db/db/9b/dbdb9b40df786422e0b0a3e7745acfc6.jpg', 'Ready', 12);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `kode_order` varchar(20) NOT NULL,
  `total_harga` int(11) NOT NULL,
  `status` enum('Menunggu Pembayaran','Menunggu Verifikasi','Diproses','Selesai','Dibatalkan') DEFAULT 'Menunggu Pembayaran',
  `tanggal_order` datetime DEFAULT current_timestamp(),
  `voucher_user_id` int(11) DEFAULT NULL,
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `tanggal_bayar` datetime DEFAULT NULL,
  `catatan_admin` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `kode_order`, `total_harga`, `status`, `tanggal_order`, `voucher_user_id`, `bukti_pembayaran`, `tanggal_bayar`, `catatan_admin`) VALUES
(1, 4, 'XB260608113400', 66000, 'Selesai', '2026-06-08 16:34:00', 0, '1781000834_ChatGPT Image Jun 6, 2026, 11_04_38 PM.png', '2026-06-09 17:27:14', NULL),
(2, 4, 'XB260608113420', 36000, 'Selesai', '2026-06-08 16:34:20', 0, '1781000827_ChatGPT Image Jun 6, 2026, 11_10_43 PM.png', '2026-06-09 17:27:07', NULL),
(3, 4, 'XB260609085748', 65000, 'Selesai', '2026-06-09 13:57:48', 0, '1780988862_ChatGPT Image Jun 6, 2026, 11_04_38 PM.png', '2026-06-09 14:07:42', ''),
(4, 4, 'XB260609094503', 53600, 'Selesai', '2026-06-09 14:45:03', 4, '1780991114_WhatsApp Image 2026-06-06 at 22.39.28.jpeg', '2026-06-09 14:45:14', NULL),
(5, 4, 'XB260609180255', 36000, 'Dibatalkan', '2026-06-09 23:02:55', 0, NULL, NULL, NULL),
(6, 4, 'XB260609203218', 5000, 'Menunggu Pembayaran', '2026-06-10 01:32:18', 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `poin_rate` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `poin_rate`) VALUES
(1, 10000);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `poin` int(11) DEFAULT 0,
  `role` varchar(20) DEFAULT 'user',
  `nama` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `poin`, `role`, `nama`, `email`) VALUES
(1, 'admin', '$2y$10$pBLgAhOnRSoMT837f0gjauYYv1d2GEaAr0wczPhEaXZagfXccfa0O', 0, 'admin', NULL, NULL),
(3, 'andi', '$2y$10$pBLgAhOnRSoMT837f0gjauYYv1d2GEaAr0wczPhEaXZagfXccfa0O', 0, 'user', 'andi', 'yura94801@gmail.com'),
(4, 'kuy', '$2y$10$i4lcdCLAILaUTMaif1JT4e7gOVa3xdvwHWDECX6Mfn0YPcYpAlVXS', 230, 'user', 'kuy', 'yura94801@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `id` int(11) NOT NULL,
  `nama_voucher` varchar(100) DEFAULT NULL,
  `poin_dibutuhkan` int(11) DEFAULT NULL,
  `status_voucher` varchar(20) DEFAULT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `jenis_voucher` enum('persen','nominal','gratis_menu') NOT NULL DEFAULT 'nominal',
  `nilai_voucher` int(11) NOT NULL DEFAULT 0,
  `minimal_belanja` int(11) NOT NULL DEFAULT 0,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_berakhir` date DEFAULT NULL,
  `menu_gratis_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vouchers`
--

INSERT INTO `vouchers` (`id`, `nama_voucher`, `poin_dibutuhkan`, `status_voucher`, `stok`, `jenis_voucher`, `nilai_voucher`, `minimal_belanja`, `tanggal_mulai`, `tanggal_berakhir`, `menu_gratis_id`) VALUES
(3, 'Diskon 10%', 50, 'Aktif', 99, 'persen', 10, 20000, '2026-01-01', '2026-12-31', NULL),
(4, 'Diskon 20%', 100, 'Aktif', 49, 'persen', 20, 30000, '2026-01-01', '2026-12-31', NULL),
(5, 'Potongan Rp 5.000', 75, 'Aktif', 100, 'nominal', 5000, 25000, '2026-01-01', '2026-12-31', NULL),
(6, 'Potongan Rp 10.000', 150, 'Aktif', 29, 'nominal', 10000, 50000, '2026-01-01', '2026-12-31', NULL),
(7, 'Gratis Menu Spesial', 200, 'Aktif', 20, 'gratis_menu', 0, 0, '2026-01-01', '2026-12-31', 1),
(8, 'Card free Day', 200, 'Aktif', 11, 'gratis_menu', 0, 0, '2026-06-09', '2026-06-30', 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
