-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 12, 2025 at 08:08 AM
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
-- Database: `isocorp`
--

-- --------------------------------------------------------

--
-- Table structure for table `ap_deliverables`
--

CREATE TABLE `ap_deliverables` (
  `ap_del_id` int(11) NOT NULL,
  `ap_del_phase_id` int(11) NOT NULL,
  `ap_del_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ap_deliverables`
--

INSERT INTO `ap_deliverables` (`ap_del_id`, `ap_del_phase_id`, `ap_del_name`) VALUES
(1, 1, 'Del 1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ap_deliverables`
--
ALTER TABLE `ap_deliverables`
  ADD PRIMARY KEY (`ap_del_id`),
  ADD KEY `ap_del_phase_id` (`ap_del_phase_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ap_deliverables`
--
ALTER TABLE `ap_deliverables`
  MODIFY `ap_del_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ap_deliverables`
--
ALTER TABLE `ap_deliverables`
  ADD CONSTRAINT `ap_deliverables_ibfk_1` FOREIGN KEY (`ap_del_phase_id`) REFERENCES `ap_phases` (`ap_phases_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
