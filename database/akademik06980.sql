-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 20, 2025 at 05:56 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `akademik06980`
--

-- --------------------------------------------------------

--
-- Table structure for table `gambar_thumbnail`
--

CREATE TABLE `gambar_thumbnail` (
  `id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `filepath` varchar(255) NOT NULL,
  `thumbpath` varchar(255) NOT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gambar_thumbnail`
--

INSERT INTO `gambar_thumbnail` (`id`, `filename`, `filepath`, `thumbpath`, `width`, `height`, `uploaded_at`) VALUES
(5, 'Screenshot (227).png', 'uploads/Screenshot (227).png', 'thumbs/thumb_Screenshot (227).png', 1366, 768, '2025-04-10 03:50:37'),
(6, 'Screenshot (226).png', 'uploads/Screenshot (226).png', 'thumbs/thumb_Screenshot (226).png', 1366, 768, '2025-04-10 04:02:35'),
(7, 'a.png', 'uploads/a.png', 'thumbs/thumb_a.png', 750, 1334, '2025-04-20 03:53:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gambar_thumbnail`
--
ALTER TABLE `gambar_thumbnail`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gambar_thumbnail`
--
ALTER TABLE `gambar_thumbnail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
