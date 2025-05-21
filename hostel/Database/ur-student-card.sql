-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Nov 22, 2024 at 06:26 PM
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
-- Database: `ur-student-card`
--

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `regnumber` int(15) NOT NULL,
  `image` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `regnumber`, `image`) VALUES
(1, 220006746, '220006746_1732137418.png'),
(2, 221017990, 'uploads/cropped_image_221017990_1728547015.png'),
(3, 224009995, 'uploads/cropped_image_224009995_1730365482.png');

-- --------------------------------------------------------

--
-- Table structure for table `info`
--

CREATE TABLE `info` (
  `id` int(11) NOT NULL,
  `regnumber` varchar(100) NOT NULL,
  `campus` varchar(100) DEFAULT NULL,
  `college` varchar(100) DEFAULT NULL,
  `names` varchar(255) DEFAULT NULL,
  `school` varchar(100) DEFAULT NULL,
  `program` varchar(100) DEFAULT NULL,
  `yearofstudy` int(11) DEFAULT NULL,
  `expireddate` varchar(10) DEFAULT NULL,
  `email` varchar(30) NOT NULL,
  `picture` varchar(200) NOT NULL,
  `token` varchar(300) NOT NULL,
  `status` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `info`
--

INSERT INTO `info` (`id`, `regnumber`, `campus`, `college`, `names`, `school`, `program`, `yearofstudy`, `expireddate`, `email`, `picture`, `token`, `status`) VALUES
(1, '220006746', 'HUYE', 'College of business and economics', 'HAKUZIMANA CEDRICK muk', 'Business', 'BSC (HONS) IN BIT', 1, '1/1/2025', 'cedrotech1@gmail.com', 'cropped_image_220006746_1731418127.png', '', ''),
(2, '222000700', 'HUYE', 'CMHS', 'TWARAYIBONYE ONESME                                  ', '3', 'BSC WITH HON IN CLINICAL PSYCHOLOGY', 0, '1/1/2025', 'janvier.ruberanziza@gmail.com', '', '', ''),
(3, '222001203', 'HUYE', 'CMHS', 'HABINEZA VALENTIN                                ', '3', 'BSC WITH HON IN PHARMACY', 0, '1/1/2025', 'janvier.ruberanziza@gmail.com', '', '', ''),
(4, '223006899', 'HUYE', 'CBE', 'INGABIRE Aline                                   ', '2', 'BBA (HONS) IN TRANSPORT & LOGIST. MANAG', 0, '1/1/2025', 'janvier.ruberanziza@gmail.com', '', '', ''),
(5, '224005486', 'HUYE', 'CBE', 'NSHIMIYIMANA PATRICK                                 ', '1', 'BSC (HONS) IN BUSINESS INFORMATION TECHN', 0, '1/1/2025', 'ppatcreator@gmail.com', '', '', ''),
(7, '224006138', 'NYARUGENGE', 'CST', 'NSHIMIYIMANA JEAN BAPTISTE', '1', 'BSC (HONS) IN INFORMATION SYSTEMS', 0, '1/1/2025', 'nshimiyimanajean1000@gmail.com', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `privilages`
--

CREATE TABLE `privilages` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `title` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `privilages`
--

INSERT INTO `privilages` (`id`, `uid`, `title`) VALUES
(2, 18, 'View Product'),
(3, 18, 'Add User'),
(5, 18, 'Report');

-- --------------------------------------------------------

--
-- Table structure for table `school_emails`
--

CREATE TABLE `school_emails` (
  `id` int(11) NOT NULL,
  `school` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `app_password` varchar(255) NOT NULL,
  `sended` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `school_emails`
--

INSERT INTO `school_emails` (`id`, `school`, `email`, `app_password`, `sended`) VALUES
(9, '1', 'urhuyecards@gmail.com', 'gpfh jaae vcac ywpf', 13);

-- --------------------------------------------------------

--
-- Table structure for table `system`
--

CREATE TABLE `system` (
  `id` int(11) NOT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `system`
--

INSERT INTO `system` (`id`, `status`) VALUES
(1, 'live');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `names` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `image` varchar(200) NOT NULL,
  `about` varchar(150) NOT NULL,
  `role` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL,
  `active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `names`, `email`, `phone`, `image`, `about`, `role`, `password`, `active`) VALUES
(1, 'admin', 'admin@gmail.com', '0788308413', 'upload/icon1.png', '                                                                                            ', 'admin', '12345', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `info`
--
ALTER TABLE `info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `privilages`
--
ALTER TABLE `privilages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `school_emails`
--
ALTER TABLE `school_emails`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `system`
--
ALTER TABLE `system`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `info`
--
ALTER TABLE `info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `privilages`
--
ALTER TABLE `privilages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `school_emails`
--
ALTER TABLE `school_emails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `system`
--
ALTER TABLE `system`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
