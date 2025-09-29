-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 28, 2025 at 02:33 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lost_and_found`
--

-- --------------------------------------------------------

--
-- Table structure for table `found_items`
--

CREATE TABLE `found_items` (
  `id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `found_date` date NOT NULL,
  `found_place` varchar(255) DEFAULT NULL,
  `contact_info` varchar(255) NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `found_items`
--

INSERT INTO `found_items` (`id`, `item_name`, `description`, `found_date`, `found_place`, `contact_info`, `status`, `image`, `created_at`) VALUES
(1, 'หัวใครคับ', '', '2025-09-09', 'หอประชุม', 'นายปูม้าแดง ปี 2 ไอที', 'เจอเจ้าของแล้ว', '1758998109_download (2).jpg', '2025-09-27 09:42:43'),
(5, 'พวงกุญแจตุ๊กตา', 'ตามในภาพ', '2025-09-15', 'ตึกคอม', 'miyun99', 'lost', 'Cogimyun Plush Keychain Mascot Polka Dot Bow Kawaii Cute Bag Keychains Charm Key Ring.jpg', '2025-09-28 08:22:22'),
(6, 'มือถือจอพับ', 'มือถือจอพับ ญี่ปุ่น ตามในญี่ปุ่น', '2025-09-12', 'สนามกีฬา', '0987654321', 'lost', 'download (3).jpg', '2025-09-28 10:44:31');

-- --------------------------------------------------------

--
-- Table structure for table `lost_items`
--

CREATE TABLE `lost_items` (
  `id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `lost_date` date NOT NULL,
  `lost_place` varchar(255) DEFAULT NULL,
  `contact_info` varchar(255) NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lost_items`
--

INSERT INTO `lost_items` (`id`, `item_name`, `description`, `lost_date`, `lost_place`, `contact_info`, `status`, `image`, `created_at`) VALUES
(8, 'หนังสือ Harry Potter', 'เล่มสีแดง', '2025-09-03', 'แถวๆโรงอาหาร', '0123456789', 'ยังไม่เจอ', 'download (1).jpg', '2025-09-27 17:06:13'),
(10, 'กระเป๋านักเรียน', 'สีดำ กระเป๋าทรงญี่ปุ่น', '2025-09-11', 'ตึก 19', 'minniemouse77', 'ยังไม่เจอ', 'TDLOSK Chiyo Yumehara Aesthetic.jpg', '2025-09-27 18:17:30'),
(11, 'กระเป๋าสตางค์', 'แบรนด์วิเวียน สีชมพู', '2025-09-14', 'บริเวณประตู 2', '0651839726', 'lost', 'ﾟ｡⌇🎧 dearwons _  (1).png', '2025-09-27 18:30:14'),
(12, 'กล่อง', 'กล่อง canon', '2025-09-19', 'ตึก 19', '0679872345', 'lost', 'download (4).jpg', '2025-09-28 11:27:41');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `found_items`
--
ALTER TABLE `found_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lost_items`
--
ALTER TABLE `lost_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `found_items`
--
ALTER TABLE `found_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `lost_items`
--
ALTER TABLE `lost_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
