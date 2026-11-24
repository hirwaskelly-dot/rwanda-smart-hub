-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 28, 2026 at 08:53 AM
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
-- Database: `rwanda_smart_hub`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$agNTFIzYaL/vR9ERZc/iyezWLc3.zfqZBjKwGJ4lJ0/wLkd9UNxi2', 'admin', '2026-09-23 07:53:59'),
(2, 'kelly', '$2y$10$77hdsq25e18d7XrnDrZx.OWWdKQ1R7j3lt6SXqH6POFut5otBhK3W', 'admin', '2026-10-09 10:37:34');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Health', 'Health facilities and health-related information', '2026-09-23 06:28:19'),
(2, 'Education', 'Schools, training and education information', '2026-09-23 06:28:19'),
(3, 'Jobs', 'Job opportunities and employment information', '2026-09-23 06:28:19'),
(4, 'Services', 'Useful public and private services', '2026-09-23 06:28:19'),
(5, 'Transport', 'Transportation-related information', '2026-09-23 06:28:19'),
(6, 'Businesses', 'Businesses and local services', '2026-09-23 06:28:19'),
(7, 'Emergency', 'Emergency services and contacts', '2026-09-23 06:28:19'),
(8, 'Places', 'Important places and locations', '2026-09-23 06:28:19');

-- --------------------------------------------------------

--
-- Table structure for table `information`
--

CREATE TABLE `information` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `location_id` int(11) DEFAULT NULL,
  `name` varchar(200) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `verification_status` varchar(50) DEFAULT 'unverified',
  `last_verified_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `information`
--

INSERT INTO `information` (`id`, `category_id`, `location_id`, `name`, `phone`, `address`, `description`, `verification_status`, `last_verified_at`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'Health Center', '0780000000', 'Kayonza, Mukarange', 'This is demonstration information for testing the Rwanda Smart Hub prototype.', 'verified', NULL, '2026-09-23 06:50:03', '2026-09-24 12:24:05');

-- --------------------------------------------------------

--
-- Table structure for table `information_translations`
--

CREATE TABLE `information_translations` (
  `id` int(11) NOT NULL,
  `information_id` int(11) NOT NULL,
  `language_code` varchar(10) NOT NULL,
  `translated_name` varchar(200) DEFAULT NULL,
  `translated_description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `province` varchar(100) NOT NULL,
  `district` varchar(100) NOT NULL,
  `sector` varchar(100) DEFAULT NULL,
  `cell` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `province`, `district`, `sector`, `cell`, `created_at`) VALUES
(1, 'Kigali City', 'Gasabo', 'Kimironko', 'Kibagabaga', '2026-09-23 06:42:55'),
(2, 'Eastern Province', 'Kayonza', 'NYAMIRAMA', 'RURAMBI', '2026-09-23 06:42:55'),
(3, 'Northern Province', 'Musanze', 'Muhoz', 'Cyuve', '2026-09-23 06:42:55'),
(4, 'Southern Province', 'Huye', 'Ngoma', 'Ngoma', '2026-09-23 06:42:55'),
(5, 'Western Province', 'Rubavu', 'Gisenyi', 'Gisenyi', '2026-09-23 06:42:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `information`
--
ALTER TABLE `information`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `location_id` (`location_id`);

--
-- Indexes for table `information_translations`
--
ALTER TABLE `information_translations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `information_id` (`information_id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `information`
--
ALTER TABLE `information`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `information_translations`
--
ALTER TABLE `information_translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `information`
--
ALTER TABLE `information`
  ADD CONSTRAINT `information_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `information_ibfk_2` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`);

--
-- Constraints for table `information_translations`
--
ALTER TABLE `information_translations`
  ADD CONSTRAINT `information_translations_ibfk_1` FOREIGN KEY (`information_id`) REFERENCES `information` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
