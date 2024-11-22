-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 22, 2024 at 07:49 AM
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
(4, 'Kap Saruno', 'saruno', 'password', 'uploads/profile_pictures/1732195535_a79b3e17-90b7-49d0-98ea-e3208f8dd1ef.png');

-- --------------------------------------------------------

--
-- Table structure for table `barangay_announcements`
--

CREATE TABLE `barangay_announcements` (
  `id` int(11) NOT NULL,
  `announcement_title` varchar(255) NOT NULL,
  `description_text` text NOT NULL,
  `announcement_images` longtext DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `posted_at` varchar(255) DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barangay_announcements`
--

INSERT INTO `barangay_announcements` (`id`, `announcement_title`, `description_text`, `announcement_images`, `created_at`, `posted_at`) VALUES
(22, '3rd Quarter BNAO Meeting', '3rd Quarter BNAO Meeting held @Multipurpose Building, Upper Bicutan, Taguig attended by our very own Barangay Nutrition Action Officer himself Kagawad on Health Jobert Quiambao and eventually Voted as one of Taguig BNAO Officers spearheaded by City Nutrition Action Officer Ms Julic Bornabc.', '[\"uploads\\/announcements\\/1732108477_IMG_20201130_145352.jpg\",\"uploads\\/announcements\\/1732108477_IMG_20201130_145406.jpg\",\"uploads\\/announcements\\/1732108477_IMG_20210501_075448.jpg\",\"uploads\\/announcements\\/1732108477_IMG_20211221_140927.jpg\",\"uploads\\/', '2024-11-20 06:14:37', '2024-11-20 14:14:37'),
(23, 'Test Announcement', 'LOREM IPSUM TAE NA CHOCO NIGGA', '[]', '2024-11-20 07:15:16', '2024-11-20 15:15:16'),
(24, 'I love niggers', 'man heliotech niggas is some bullshit bruh', '[\"uploads\\/announcements\\/1732115394_419303608_332291319834527_7910766879232352090_n.jpg\"]', '2024-11-20 08:09:54', '2024-11-20 16:09:54'),
(25, 'Barangay Wins World Cup', 'TEST', '[\"uploads\\/announcements\\/1732191375_cropped_image 1.png\",\"uploads\\/announcements\\/1732191375_b1131726-e89e-47e0-9f90-9c9ca4507f9a.png\",\"uploads\\/announcements\\/1732191375_76754470-13bb-454c-b199-ebcee9ec5821.png\",\"uploads\\/announcements\\/1732191375_07fd3ab8-0331-43db-83dd-cbe4c6abad5f-modified 1.png\",\"uploads\\/announcements\\/1732191375_151aa3d6-f625-48be-84f4-efde232d4a7a.png\",\"uploads\\/announcements\\/1732191375_e24663d4-dbf0-4326-a32d-7e77750043c8.png\",\"uploads\\/announcements\\/1732191375_9cc8e3d9-7854-4ef3-a60d-8cc64fc49291.png\",\"uploads\\/announcements\\/1732191375_39cd52de-1f09-401d-9208-83cd5b68aa70.png\",\"uploads\\/announcements\\/1732191375_7e5bfd83-2f2d-4575-b134-f1037cf0fcc3.png\",\"uploads\\/announcements\\/1732191375_652b5e1e-1e9e-45b2-8f02-251a57d7de0c.png\"]', '2024-11-21 05:16:15', '2024-11-21 13:16:15');

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
  `DateRequested` varchar(255) DEFAULT NULL,
  `valid_id` longtext NOT NULL,
  `request_picture` longtext NOT NULL,
  `rejection_reason` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `document_requests`
--

INSERT INTO `document_requests` (`Id`, `DocumentType`, `Name`, `Address`, `TIN_No`, `CTC_No`, `Alias`, `Age`, `LengthOfStay`, `Citizenship`, `Gender`, `CivilStatus`, `Purpose`, `Status`, `Quantity`, `DateRequested`, `valid_id`, `request_picture`, `rejection_reason`) VALUES
(1, 'Cedula', 'Ronald Gumalo', 'Southside Taguig City', '123123123414514', '124124124124124', 'Ron-ron', 30, 9, 'Filipino', 'Male', 'Married', 'Cedula', 'Approved', 2, '2024-11-20', '', '', ''),
(2, 'Barangay Clearance', 'Dante Gomez', 'Southside, Taguig City', '000-123-456-001', '000-123-456-001', 'Donet', 43, 13, 'Filipino', 'Male', 'Single', 'Barangay Clearance', 'Approved', 1, '2024-11-01', '', '', ''),
(3, 'Barangay Clearance', 'Dante Gomez', 'Southside, Taguig City', '000-123-456-001', '000-123-456-001', 'Donet', 43, 13, 'Filipino', 'Male', 'Single', 'Barangay Clearance', 'Approved', 1, '2024-11-01', '', '', ''),
(4, 'Cedula', 'Arnel Lasino', 'Southside Taguig City', '000-123-456-001', '000-123-456-001', 'Renel', 30, 9, 'Filipino', 'Male', 'Married', 'Cedula', 'Pending', 2, '2024-11-20', '', '', ''),
(5, 'Cedula', 'Ronald Gumalo', 'Southside Taguig City', '000-123-456-001', '000-123-456-001', 'Ron-ron', 30, 9, 'Filipino', 'Male', 'Married', 'Cedula', 'Pending', 2, '2024-11-20', '', '', ''),
(6, 'Barangay Clearance', 'Dante Gomez', 'Southside, Taguig City', '000-123-456-001', '000-123-456-001', 'Donet', 43, 13, 'Filipino', 'Male', 'Single', 'Barangay Clearance', 'Approved', 1, '2024-11-01', '', '', ''),
(7, 'Barangay Clearance', 'Dante Gomez', 'Southside, Taguig City', '000-123-456-001', '000-123-456-001', 'Donet', 43, 13, 'Filipino', 'Male', 'Single', 'Barangay Clearance', 'Approved', 1, '2024-11-01', '', '', ''),
(8, 'Cedula', 'Arnel Lasino', 'Southside Taguig City', '000-123-456-001', '000-123-456-001', 'Renel', 30, 9, 'Filipino', 'Male', 'Married', 'Cedula', 'Pending', 2, '2024-11-20', '', '', ''),
(9, 'Cedula', 'Ronald Gumalo', 'Southside Taguig City', '000-123-456-001', '000-123-456-001', 'Ron-ron', 30, 9, 'Filipino', 'Male', 'Married', 'Cedula', 'Pending', 2, '2024-11-20', '', '', ''),
(10, 'Barangay Clearance', 'Dante Gomez', 'Southside, Taguig City', '000-123-456-001', '000-123-456-001', 'Donet', 43, 13, 'Filipino', 'Male', 'Single', 'Barangay Clearance', 'Rejected', 1, '2024-11-01', '', '', ''),
(11, 'Barangay Clearance', 'Dante Gomez', 'Southside, Taguig City', '000-123-456-001', '000-123-456-001', 'Donet', 43, 13, 'Filipino', 'Male', 'Single', 'Barangay Clearance', 'Pending', 1, '2024-11-01', '', '', ''),
(12, 'Cedula', 'Arnel Lasino', 'Southside Taguig City', '000-123-456-001', '000-123-456-001', 'Renel', 30, 9, 'Filipino', 'Male', 'Married', 'Cedula', 'Pending', 2, '2024-11-20', '', '', ''),
(13, 'Cedula', 'Ronald Gumalo', 'Southside Taguig City', '000-123-456-001', '000-123-456-001', 'Ron-ron', 30, 9, 'Filipino', 'Male', 'Married', 'Cedula', 'Pending', 2, '2024-11-20', '', '', ''),
(14, 'Barangay Clearance', 'Dante Gomez', 'Southside, Taguig City', '000-123-456-001', '000-123-456-001', 'Donet', 43, 13, 'Filipino', 'Male', 'Single', 'Barangay Clearance', 'Approved', 1, '2024-11-01', '', '', ''),
(15, 'Barangay Clearance', 'Dante Gomez', 'Southside, Taguig City', '000-123-456-001', '000-123-456-001', 'Donet', 43, 13, 'Filipino', 'Male', 'Single', 'Barangay Clearance', 'Approved', 1, '2024-11-01', '', '', ''),
(16, 'Cedula', 'Arnel Lasino', 'Southside Taguig City', '000-123-456-001', '000-123-456-001', 'Renel', 30, 9, 'Filipino', 'Male', 'Married', 'Cedula', 'Rejected', 2, '2024-11-20', '', '', 'Not enough images');

-- --------------------------------------------------------

--
-- Table structure for table `incident_reports`
--

CREATE TABLE `incident_reports` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `incident_picture` longtext NOT NULL,
  `date_submitted` datetime NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `incident_reports`
--

INSERT INTO `incident_reports` (`id`, `name`, `title`, `description`, `incident_picture`, `date_submitted`, `status`) VALUES
(1, 'Robert Youngstown', 'Noise Disturbance', 'Maingay pa dito banda sa Sampaguita St.', '', '2024-11-01 22:29:27', 'resolved'),
(4, 'Danny Sulaiman', 'Mabaho', 'pahingi po ng tulong ang baho ng kapitbahay namin na si dj tempra', '', '2024-11-06 15:37:08', 'pending'),
(6, 'Diosdado Tempra', 'test', 'test', '[\"uploads\\/incident_reports\\/1732255234_75af0316a0f27f7d.jpg\"]', '2024-11-22 07:00:34', 'pending'),
(7, 'Diosdado Tempra', 'TEST TECNO 5G', 'physical device test', '[\"uploads\\/incident_reports\\/1732255765_13dc8a2b529c12f1.jpg\"]', '2024-11-22 07:09:25', 'pending');

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
  `birthday` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `user_profile_picture` longtext NOT NULL,
  `last_active` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_accounts`
--

INSERT INTO `user_accounts` (`id`, `firstName`, `lastName`, `username`, `age`, `gender`, `adrHouseNo`, `adrZone`, `adrStreet`, `birthday`, `password`, `user_profile_picture`, `last_active`) VALUES
(1, 'Clement Harold Miguel', 'Cabus', 'clementcabs', 20, 'male', '497- A', 'zone 3', 'Kalaw Street', '2003-12-22', 'clempassword11', '', NULL),
(2, 'Diosdado', 'Tempra', 'djtempra', 20, 'male', '497- A', 'zone 4', 'Kalaw Street', '1990-02-12', 'password', '', '2024-11-21 23:47:43'),
(9, 'Joshua', 'Fernandez', 'jferns', 20, 'male', '497-A', 'zone 4', 'Kalaw Street', '2003-12-22', 'password22', '', NULL),
(10, 'Gabriel', 'Maglaya', 'Gabmaglaya', 20, 'male', '4783-B', 'Zone 15', 'Lawin Street', '2003-12-22', 'Gabmaglayapass', '', NULL),
(11, 'Michael Josh', 'Bargabino', 'mjbarbs', 21, 'male', '897-N', 'Zone 20', 'Lawin', '2003-01-04', 'mjbarbs', '', NULL),
(12, 'Daren', 'Espanto', 'despanto', 20, 'male', '872', 'Zone 3', 'Agila Street', '2003-08-05', 'despanto', '', NULL),
(13, 'Dave', 'Chappele', 'dchappele', 40, 'male', '723 - B', 'ZONE 22', 'Hiraya Street', '1988-11-22', 'December22@', '', NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `document_requests`
--
ALTER TABLE `document_requests`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `incident_reports`
--
ALTER TABLE `incident_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_accounts`
--
ALTER TABLE `user_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
