-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 21, 2025 at 11:30 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `truck2carier`
--

-- --------------------------------------------------------

--
-- Table structure for table `farmers`
--

CREATE TABLE `farmers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `farm_location` varchar(255) DEFAULT NULL,
  `produce_type` varchar(255) DEFAULT NULL,
  `years_of_experiece` int(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `farm_size` decimal(20,0) NOT NULL,
  `equipment_needed` varchar(100) NOT NULL,
  `additional_information` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `farmers`
--

INSERT INTO `farmers` (`id`, `user_id`, `name`, `farm_location`, `produce_type`, `years_of_experiece`, `phone`, `farm_size`, `equipment_needed`, `additional_information`, `created_at`) VALUES
(1, 7, 'Jafar zakariya', 'Yobe, dauri', 'vegetables', 6, '08108111162', '6', 'tractors', 'sdfsdfsdfsdfsdf', '2025-08-18 11:37:14'),
(3, 4, 'Abbas Zakariya', 'Yobe, dauri', 'livestock', 8, '08163207429', '7', 'fghfghfghfgfh', 'fgfhfghf', '2025-08-19 14:28:44');

-- --------------------------------------------------------

--
-- Table structure for table `matches`
--

CREATE TABLE `matches` (
  `id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `truck_id` int(11) NOT NULL,
  `farmers_id` int(11) NOT NULL,
  `matched_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `matches`
--

INSERT INTO `matches` (`id`, `request_id`, `truck_id`, `farmers_id`, `matched_at`) VALUES
(10, 3, 1, 4, '2025-08-20 15:15:56'),
(11, 4, 1, 4, '2025-08-20 15:18:49');

-- --------------------------------------------------------

--
-- Table structure for table `transport_requests`
--

CREATE TABLE `transport_requests` (
  `id` int(11) NOT NULL,
  `farmer_id` int(11) NOT NULL,
  `produce_type` varchar(100) NOT NULL,
  `quantity` varchar(50) DEFAULT NULL,
  `pickup_location` varchar(255) NOT NULL,
  `destination` varchar(255) NOT NULL,
  `urgency_level` varchar(100) NOT NULL,
  `additional_information` varchar(255) NOT NULL,
  `status` enum('pending','matched','completed','cancelled') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transport_requests`
--

INSERT INTO `transport_requests` (`id`, `farmer_id`, `produce_type`, `quantity`, `pickup_location`, `destination`, `urgency_level`, `additional_information`, `status`, `created_at`) VALUES
(1, 4, 'Sorghum', '1', 'Fika', 'Daura', 'High', 'yes please', 'pending', '2025-08-15 12:51:11'),
(2, 4, 'Groundnuts', '1', 'Gujba', 'daura', 'High', 'yes', 'pending', '2025-08-15 12:55:20'),
(3, 4, 'Tomatoes', '1', 'Damaturu', 'daura', 'Low', 'dsadasdas', 'completed', '2025-08-15 13:03:24'),
(4, 4, 'Peppers', '1', 'Gashua', 'daura', 'Medium', 'yes please', 'matched', '2025-08-15 16:02:53');

-- --------------------------------------------------------

--
-- Table structure for table `trucks`
--

CREATE TABLE `trucks` (
  `id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `truck_type` varchar(100) NOT NULL,
  `capacity` varchar(50) DEFAULT NULL,
  `plate_number` varchar(50) NOT NULL,
  `Manufacturer` varchar(70) NOT NULL,
  `Model` varchar(100) NOT NULL,
  `years_of_manufacture` int(11) NOT NULL,
  `hourly_rate` decimal(10,0) NOT NULL,
  `status` enum('available','unavailable') DEFAULT 'available',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trucks`
--

INSERT INTO `trucks` (`id`, `owner_id`, `truck_type`, `capacity`, `plate_number`, `Manufacturer`, `Model`, `years_of_manufacture`, `hourly_rate`, `status`, `created_at`) VALUES
(1, 5, 'specialty', 'light', 'ABC123', 'JON DEERE', '5075E', 2018, '2000', 'available', '2025-08-20 11:40:30');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('farmer','truck_owner','admin') NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `phone`, `password`, `role`, `location`, `created_at`) VALUES
(4, 'Abbas Nasiru', '08163207429', '$2y$10$gPqax5elCBEzX4TZVvaWA.8H0lF87KjYCRykyYL4fPa6kiKcseC0W', 'farmer', 'Karasuwa', '2025-08-13 14:32:26'),
(5, 'web elite', '08118788258', '$2y$10$OakcdD1Ca65UMOKNN5OMXu5SO1xhmeIq5eNp/0PEZxPMWWCvpRO5G', 'truck_owner', 'Jakusko', '2025-08-16 15:47:42'),
(7, 'jafar zakariya', '08108111162', '$2y$10$izoim0QsbF0SLyqOKzTxteowdnpSqENwFqgAKo6/zyDmnTZA1tLlq', 'farmer', 'Machina', '2025-08-18 12:29:30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `farmers`
--
ALTER TABLE `farmers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `matches`
--
ALTER TABLE `matches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_id` (`request_id`),
  ADD KEY `truck_id` (`truck_id`);

--
-- Indexes for table `transport_requests`
--
ALTER TABLE `transport_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `farmer_id` (`farmer_id`);

--
-- Indexes for table `trucks`
--
ALTER TABLE `trucks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `plate_number` (`plate_number`),
  ADD KEY `owner_id` (`owner_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `farmers`
--
ALTER TABLE `farmers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `matches`
--
ALTER TABLE `matches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `transport_requests`
--
ALTER TABLE `transport_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `trucks`
--
ALTER TABLE `trucks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `farmers`
--
ALTER TABLE `farmers`
  ADD CONSTRAINT `farmers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `matches`
--
ALTER TABLE `matches`
  ADD CONSTRAINT `matches_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `transport_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `matches_ibfk_2` FOREIGN KEY (`truck_id`) REFERENCES `trucks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transport_requests`
--
ALTER TABLE `transport_requests`
  ADD CONSTRAINT `transport_requests_ibfk_1` FOREIGN KEY (`farmer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `trucks`
--
ALTER TABLE `trucks`
  ADD CONSTRAINT `trucks_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
