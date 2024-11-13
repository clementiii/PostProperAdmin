-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 13, 2024 at 03:29 AM
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
-- Database: `pps_barangay_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_accounts`
--

CREATE TABLE `admin_accounts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_accounts`
--

INSERT INTO `admin_accounts` (`id`, `name`, `username`, `password`, `profile_picture`) VALUES
(1, 'Rannie Camba', 'rannie', 'password', 'assets/admin_profile_pictures/testprof1.jpg\n'),
(2, 'Era ganaban', 'eraganaban', 'password', 'assets/admin_profile_pictures/testprof2.jpg'),
(4, 'Kap Saruno', 'saruno', 'password', 'assets/admin_profile_pictures/testprof3.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `barangay_announcements`
--

CREATE TABLE `barangay_announcements` (
  `id` int(11) NOT NULL,
  `announcement_title` varchar(255) NOT NULL,
  `description_text` text NOT NULL,
  `announcement_images` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `posted_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barangay_announcements`
--

INSERT INTO `barangay_announcements` (`id`, `announcement_title`, `description_text`, `announcement_images`, `created_at`, `posted_at`) VALUES
(1, 'test title', 'test description', '[\"uploads\\/announcements\\/1731434289_images.jpg\"]', '2024-11-12 10:58:09', '2024-11-12 18:58:09'),
(2, 'New Internet', 'Bagong internet sa southside', '[\"uploads\\/announcements\\/1731434683_elden-ring-8k-h4.jpg\"]', '2024-11-12 11:04:43', '2024-11-12 19:04:43'),
(3, 'clement', 'clement', '[]', '2024-11-12 11:28:36', '2024-11-12 19:28:36');

-- --------------------------------------------------------

--
-- Table structure for table `document_requests`
--

CREATE TABLE `document_requests` (
  `Id` int(11) NOT NULL,
  `DocumentType` varchar(100) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `TIN_No` varchar(50) DEFAULT NULL,
  `CTC_No` varchar(50) DEFAULT NULL,
  `Alias` varchar(100) DEFAULT NULL,
  `Age` int(11) DEFAULT NULL,
  `LengthOfStay` int(11) DEFAULT NULL,
  `Citizenship` varchar(100) DEFAULT NULL,
  `Gender` varchar(10) DEFAULT NULL,
  `CivilStatus` varchar(50) DEFAULT NULL,
  `Purpose` text DEFAULT NULL,
  `Status` varchar(50) DEFAULT NULL,
  `Quantity` int(11) DEFAULT NULL,
  `DateRequested` date DEFAULT NULL,
  `valid_id` varchar(255) NOT NULL,
  `request_picture` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `document_requests`
--

INSERT INTO `document_requests` (`Id`, `DocumentType`, `Name`, `Address`, `TIN_No`, `CTC_No`, `Alias`, `Age`, `LengthOfStay`, `Citizenship`, `Gender`, `CivilStatus`, `Purpose`, `Status`, `Quantity`, `DateRequested`, `valid_id`, `request_picture`) VALUES
(1, 'Cedula', 'Ronald Gumalo', 'Southside Taguig City', '123123123414514', '124124124124124', 'Ron-ron', 30, 9, 'Filipino', 'Male', 'Married', 'Cedula', 'Approved', 2, '2024-11-20', '', ''),
(2, 'Barangay Clearance', 'Dante Gomez', 'Southside, Taguig City', '000-123-456-001', '000-123-456-001', 'Donet', 43, 13, 'Filipino', 'Male', 'Single', 'Barangay Clearance', 'Approved', 1, '2024-11-01', '', ''),
(3, 'Barangay Clearance', 'Dante Gomez', 'Southside, Taguig City', '000-123-456-001', '000-123-456-001', 'Donet', 43, 13, 'Filipino', 'Male', 'Single', 'Barangay Clearance', 'Approved', 1, '2024-11-01', '', ''),
(4, 'Cedula', 'Arnel Lasino', 'Southside Taguig City', '000-123-456-001', '000-123-456-001', 'Renel', 30, 9, 'Filipino', 'Male', 'Married', 'Cedula', 'Pending', 2, '2024-11-20', '', ''),
(5, 'Cedula', 'Ronald Gumalo', 'Southside Taguig City', '000-123-456-001', '000-123-456-001', 'Ron-ron', 30, 9, 'Filipino', 'Male', 'Married', 'Cedula', 'Pending', 2, '2024-11-20', '', ''),
(6, 'Barangay Clearance', 'Dante Gomez', 'Southside, Taguig City', '000-123-456-001', '000-123-456-001', 'Donet', 43, 13, 'Filipino', 'Male', 'Single', 'Barangay Clearance', 'Approved', 1, '2024-11-01', '', ''),
(7, 'Barangay Clearance', 'Dante Gomez', 'Southside, Taguig City', '000-123-456-001', '000-123-456-001', 'Donet', 43, 13, 'Filipino', 'Male', 'Single', 'Barangay Clearance', 'Approved', 1, '2024-11-01', '', ''),
(8, 'Cedula', 'Arnel Lasino', 'Southside Taguig City', '000-123-456-001', '000-123-456-001', 'Renel', 30, 9, 'Filipino', 'Male', 'Married', 'Cedula', 'Pending', 2, '2024-11-20', '', ''),
(9, 'Cedula', 'Ronald Gumalo', 'Southside Taguig City', '000-123-456-001', '000-123-456-001', 'Ron-ron', 30, 9, 'Filipino', 'Male', 'Married', 'Cedula', 'Pending', 2, '2024-11-20', '', ''),
(10, 'Barangay Clearance', 'Dante Gomez', 'Southside, Taguig City', '000-123-456-001', '000-123-456-001', 'Donet', 43, 13, 'Filipino', 'Male', 'Single', 'Barangay Clearance', 'Rejected', 1, '2024-11-01', '', ''),
(11, 'Barangay Clearance', 'Dante Gomez', 'Southside, Taguig City', '000-123-456-001', '000-123-456-001', 'Donet', 43, 13, 'Filipino', 'Male', 'Single', 'Barangay Clearance', 'Pending', 1, '2024-11-01', '', ''),
(12, 'Cedula', 'Arnel Lasino', 'Southside Taguig City', '000-123-456-001', '000-123-456-001', 'Renel', 30, 9, 'Filipino', 'Male', 'Married', 'Cedula', 'Pending', 2, '2024-11-20', '', ''),
(13, 'Cedula', 'Ronald Gumalo', 'Southside Taguig City', '000-123-456-001', '000-123-456-001', 'Ron-ron', 30, 9, 'Filipino', 'Male', 'Married', 'Cedula', 'Pending', 2, '2024-11-20', '', ''),
(14, 'Barangay Clearance', 'Dante Gomez', 'Southside, Taguig City', '000-123-456-001', '000-123-456-001', 'Donet', 43, 13, 'Filipino', 'Male', 'Single', 'Barangay Clearance', 'Approved', 1, '2024-11-01', '', ''),
(15, 'Barangay Clearance', 'Dante Gomez', 'Southside, Taguig City', '000-123-456-001', '000-123-456-001', 'Donet', 43, 13, 'Filipino', 'Male', 'Single', 'Barangay Clearance', 'Approved', 1, '2024-11-01', '', ''),
(16, 'Cedula', 'Arnel Lasino', 'Southside Taguig City', '000-123-456-001', '000-123-456-001', 'Renel', 30, 9, 'Filipino', 'Male', 'Married', 'Cedula', 'Pending', 2, '2024-11-20', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `incident_reports`
--

CREATE TABLE `incident_reports` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `incident_picture` varchar(255) NOT NULL,
  `date_submitted` datetime NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `incident_reports`
--

INSERT INTO `incident_reports` (`id`, `name`, `title`, `description`, `incident_picture`, `date_submitted`, `status`) VALUES
(1, 'Robert Youngstown', 'Noise Disturbance', 'Maingay pa dito banda sa Sampaguita St.', '', '2024-11-01 22:29:27', 'resolved'),
(2, 'Clement Cabus', 'Mabaho', 'pahingi po ng tulong ang baho ng kapitbahay namin na si dj tempra', '', '2024-11-06 15:37:08', 'pending'),
(3, 'Jed Masterson', 'Noise Disturbance', 'Maingay pa dito banda sa Sampaguita St.', '', '2024-11-01 22:29:27', 'pending'),
(4, 'Danny Sulaiman', 'Mabaho', 'pahingi po ng tulong ang baho ng kapitbahay namin na si dj tempra', '', '2024-11-06 15:37:08', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `user_accounts`
--

CREATE TABLE `user_accounts` (
  `id` int(11) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` enum('male','female') NOT NULL,
  `adrHouseNo` varchar(10) DEFAULT NULL,
  `adrZone` varchar(20) DEFAULT NULL,
  `adrStreet` varchar(100) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `user_profile_picture` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_accounts`
--

INSERT INTO `user_accounts` (`id`, `firstName`, `lastName`, `username`, `age`, `gender`, `adrHouseNo`, `adrZone`, `adrStreet`, `birthday`, `password`, `user_profile_picture`) VALUES
(1, 'Clement Harold Miguel', 'Cabus', 'clementcabs', 20, 'male', '497- A', NULL, 'Kalaw Street', '0000-00-00', 'clempassword11', ''),
(2, 'Diosdado', 'Tempra', 'djtempra', 20, 'male', '497- A', NULL, 'Kalaw Street', '0000-00-00', 'password', ''),
(5, 'Joshua', 'Fernandez', 'Jferns', 20, 'male', '497- A', NULL, 'Kalaw Street', '0000-00-00', 'clempassword11', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_accounts`
--
ALTER TABLE `admin_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `barangay_announcements`
--
ALTER TABLE `barangay_announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `document_requests`
--
ALTER TABLE `document_requests`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `incident_reports`
--
ALTER TABLE `incident_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_accounts`
--
ALTER TABLE `user_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_accounts`
--
ALTER TABLE `admin_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `barangay_announcements`
--
ALTER TABLE `barangay_announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `document_requests`
--
ALTER TABLE `document_requests`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `incident_reports`
--
ALTER TABLE `incident_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_accounts`
--
ALTER TABLE `user_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
