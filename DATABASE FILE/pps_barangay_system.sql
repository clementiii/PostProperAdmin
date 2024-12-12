-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 12, 2024 at 02:30 AM
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
(4, 'Quirino Saruno', 'sarunoquirino', 'password', 'uploads/profile_pictures/1733966300_a79b3e17-90b7-49d0-98ea-e3208f8dd1ef.png'),
(8, 'Admin 2', 'admin2', 'password', '');

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
(34, 'Nagsagawa ng pagti-trim ng puno sa Agila Street,G2 Village sina Kagawad on Clean and Green', 'Bilang tugon sa concerned citizen ay nagsagawa ng pagti-trim ng puno sa Agila Street,G2 Village sina Kagawad on Clean and Green, Kagawad Elmer Baldonado kasama ang ating masisipag na Environmental Police at Barangay Enforcers.\r\nAng nasabing puno ang nakaharang na sa mga wire ng kuryente kung kaya\'t kinakailangan na itong bawasan upang maiwasan ang anumang hindi magandang pwedeng mangyari.\r\nMula sa Pamunuan ng ating Punong Barangay Quirino Sarono ay nais naming laging maging ligtas ang bawat mamamayan ng Barangay Post Proper Southside.\r\n.\r\n.\r\n#ᴋᴀᴘQꜱ\r\n#ꜱᴇʀʙɪꜱʏᴏɴɢꜱᴀʀᴏɴᴏ\r\n#ɪLOVEꜱᴏᴜᴛʜꜱɪᴅᴇ\r\n#ꜱᴏᴜᴛʜꜱɪᴅᴇ2024', '[\"uploads\\/announcements\\/1733500679_469533685_960707952771984_4186303642133741615_n.jpg\",\"uploads\\/announcements\\/1733500679_469545587_960707896105323_7268418601890999976_n.jpg\",\"uploads\\/announcements\\/1733500679_469465618_960707826105330_8388280861290474215_n.jpg\",\"uploads\\/announcements\\/1733500679_469547374_960707966105316_7787706121310746654_n.jpg\"]', '2024-12-11 18:22:53', '2024-12-12 02:22:53'),
(35, 'Ongoing FREE Eye Check -up', 'Ongoing FREE Eye Check -up\r\n@Fox Satellite office', '[\"uploads\\/announcements\\/1733924287_469710194_962336132609166_5394994954942165739_n.jpg\",\"uploads\\/announcements\\/1733924287_469863653_962336075942505_1188672549198688813_n.jpg\",\"uploads\\/announcements\\/1733924287_469793291_962336029275843_1649697623784366509_n.jpg\"]', '2024-12-11 06:38:07', '2024-12-11 14:38:07'),
(36, 'FREE EYE CHECKUP', '@FOX SATELITE OFFICE\r\nDEC. 9, 2024 8am - 4pm\r\n(Eye check-up only)', '[]', '2024-12-11 06:39:33', '2024-12-11 14:39:33'),
(37, 'TAGUIG BARANGAY NUTRITION ACTION OFFICER QUARTERLY MEETING ANDOATH-TAKING CEREMONY FOR NEWLY ELECTED OFFICERS', 'TAGUIG BARANGAY NUTRITION ACTION OFFICER QUARTERLY MEETING ANDOATH-TAKING CEREMONY FOR NEWLY ELECTED OFFICERS\r\nIsinagawa ang Taguig Barangay Nutrition Action Officer Quarterly Meeting and Oath-Taking Ceremony for Newly Elected Officers ngayong araw, Disyembre 5 sa Training Plaza, Multipurpose Building, Purok 2, Barangay Upper Bicutan.\r\nPinangunahan ni Mayor Ate Lani Cayetano  ang pagbubukas ng programa kasama sina Congressman Ricardo Ading Cruz, Jr.  (Representative 1st District, Taguig City),\r\nKonsehal Rodil \"Tikboy\" Marcelino (Chair, Committee on Health), Konsehala Marisse Balina-Eron  (Co-Chair, Committee on Health), Dr. Norena R. Osano (City Health Officer), at Ms. Julie S. Bernabe (City Nutrition Action Officer).\r\nNanumpa rin ang mga bagong halal na opisyales ng Taguig City Barangay Nutrition Action Officers Association kasama ang ating Kagawad on Health at BNAO Jobert Quiambao  matapos ang naganap na special election noong Setyembre 27 upang mapunan ang mga bakanteng posisyong naiwan ng mga opisyales na nagtapos na ang termino sa kanilang mga barangay.\r\nNagkaroon din ng lecture tungkol sa kahalagahan ng pagpapatupad ng Nutrition in Emergencies (NiE) sa mga barangay at kung ano ang tungkulin ng mga Barangay Nutrition Action Officers (BNAO) sa panahon ng kalamidad.\r\nCtto: Taguig Nutrition Office', '[\"uploads\\/announcements\\/1733924411_469223371_960587896117323_5318530719560085577_n.jpg\",\"uploads\\/announcements\\/1733924411_469340425_960587886117324_1436298450426928014_n.jpg\",\"uploads\\/announcements\\/1733924411_469165537_960587669450679_3822209838079120184_n.jpg\",\"uploads\\/announcements\\/1733924411_469409440_960587579450688_8226622029199845635_n.jpg\"]', '2024-12-11 06:43:10', '2024-12-11 14:43:10'),
(39, 'Nagsagawa ng pagti-trim ng puno sa Agila Street,G2 Village sina Kagawad on Clean and Green', 'Bilang tugon sa concerned citizen ay nagsagawa ng pagti-trim ng puno sa Agila Street,G2 Village sina Kagawad on Clean and Green, Kagawad Elmer Baldonado kasama ang ating masisipag na Environmental Police at Barangay Enforcers.\r\nAng nasabing puno ang nakaharang na sa mga wire ng kuryente kung kaya\'t kinakailangan na itong bawasan upang maiwasan ang anumang hindi magandang pwedeng mangyari.\r\nMula sa Pamunuan ng ating Punong Barangay Quirino Sarono ay nais naming laging maging ligtas ang bawat mamamayan ng Barangay Post Proper Southside.\r\n.\r\n.\r\n#ᴋᴀᴘQꜱ\r\n#ꜱᴇʀʙɪꜱʏᴏɴɢꜱᴀʀᴏɴᴏ\r\n#ɪLOVEꜱᴏᴜᴛʜꜱɪᴅᴇ\r\n#ꜱᴏᴜᴛʜꜱɪᴅᴇ2024', '[\"uploads\\/announcements\\/1733966654_469223371_960587896117323_5318530719560085577_n.jpg\",\"uploads\\/announcements\\/1733966654_469340425_960587886117324_1436298450426928014_n.jpg\",\"uploads\\/announcements\\/1733966654_469165537_960587669450679_3822209838079120184_n.jpg\",\"uploads\\/announcements\\/1733966654_469409440_960587579450688_8226622029199845635_n.jpg\",\"uploads\\/announcements\\/1733966654_469710194_962336132609166_5394994954942165739_n.jpg\"]', '2024-12-11 18:24:14', '2024-12-12 02:24:14');

-- --------------------------------------------------------

--
-- Table structure for table `document_requests`
--

CREATE TABLE `document_requests` (
  `Id` int(11) NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `DocumentType` varchar(100) NOT NULL DEFAULT 'Barangay Clearance',
  `Name` varchar(255) NOT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `TIN_No` varchar(50) DEFAULT NULL,
  `CTC_No` varchar(50) DEFAULT NULL,
  `Alias` varchar(100) DEFAULT NULL,
  `Age` int(11) DEFAULT NULL,
  `birthday` varchar(10) DEFAULT NULL,
  `PlaceOfBirth` varchar(255) DEFAULT NULL,
  `Occupation` varchar(255) DEFAULT NULL,
  `LengthOfStay` int(11) DEFAULT NULL,
  `Citizenship` varchar(100) DEFAULT NULL,
  `Gender` varchar(10) DEFAULT NULL,
  `CivilStatus` varchar(50) DEFAULT NULL,
  `Purpose` text DEFAULT NULL,
  `Status` enum('pending','approved','rejected','cancelled','OVERDUE') DEFAULT 'pending',
  `Quantity` int(11) DEFAULT NULL,
  `DateRequested` varchar(255) DEFAULT NULL,
  `valid_id` longtext NOT NULL,
  `valid_id_front` longtext DEFAULT NULL,
  `valid_id_back` longtext DEFAULT NULL,
  `request_picture` longtext NOT NULL,
  `rejection_reason` varchar(255) NOT NULL,
  `cancellation_reason` varchar(255) DEFAULT NULL,
  `pickup_status` enum('pending','picked_up') DEFAULT 'pending',
  `date_approved` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `document_requests`
--

INSERT INTO `document_requests` (`Id`, `userId`, `DocumentType`, `Name`, `Address`, `TIN_No`, `CTC_No`, `Alias`, `Age`, `birthday`, `PlaceOfBirth`, `Occupation`, `LengthOfStay`, `Citizenship`, `Gender`, `CivilStatus`, `Purpose`, `Status`, `Quantity`, `DateRequested`, `valid_id`, `valid_id_front`, `valid_id_back`, `request_picture`, `rejection_reason`, `cancellation_reason`, `pickup_status`, `date_approved`) VALUES
(40, 22, 'Barangay Clearance', 'Raul Menendez', '123  Street Zone', '123456789012', '123456789012', 'UPLOADREQUIREMENTS', 25, '12-05-99', 'asd', 'awdawd', 12, 'awdaw', 'Male', 'Single', '123456789012', 'approved', 2, '2024-12-06', '', 'uploads/valid_ids/1733483666_6752dc92ad244_front.jpg', 'uploads/valid_ids/1733483666_6752dc92ad774_back.jpg', '', '', NULL, 'picked_up', NULL),
(41, 25, 'Cedula', 'Harry Potter', '123  Street Zone', '123456789012', '123456789012', 'Harry', 25, '12-06-99', 'awdawd', 'awdawd', 23, 'awdaw', 'Male', 'Single', 'awdawd', 'approved', 3, '2024-12-06', '', 'uploads/valid_ids/1733483974_6752ddc6d3171_front.jpg', 'uploads/valid_ids/1733483974_6752ddc6d3511_back.jpg', '', '', NULL, 'picked_up', NULL),
(42, 25, 'Barangay Certification', 'Harry Potter', '123  Street Zone', '123456789012', '123456789012', 'Potter', 25, '12-06-99', 'cedula', 'cedula', 21, 'cedula', 'Male', 'Single', 'cedula', 'approved', 5, '2024-12-06', '', 'uploads/valid_ids/1733484041_6752de09f37fa_front.jpg', 'uploads/valid_ids/1733484041_6752de09f3a18_back.jpg', '', '', NULL, 'pending', NULL),
(43, 25, 'Certificate of Indigency', 'Harry Potter', '123  Street Zone', '123456789012', '123456789012', 'COI', 25, '12-06-99', 'COI', 'COI', 12, 'COI', 'Male', 'Single', 'COI', 'approved', 4, '2024-12-06', '', 'uploads/valid_ids/1733484166_6752de8625cd0_front.jpg', 'uploads/valid_ids/1733484166_6752de8625f6b_back.jpg', '', '', NULL, 'pending', '2024-12-11 22:25:17'),
(44, 25, 'Barangay Clearance', 'Harry Potter', '123  Street Zone', '123456789012', '123456789012', 'photo', 25, '12-06-99', 'photo', 'photo', 12, 'photo', 'Male', 'Single', 'photo', 'pending', 4, '2024-12-06', '', 'uploads/valid_ids/1733484241_6752ded1488bd_front.jpg', 'uploads/valid_ids/1733484241_6752ded148af3_back.jpg', '', '', NULL, 'pending', NULL),
(45, 23, 'Barangay Clearance', 'Justine Case', '123  Street Zone', '123456789012', '123456789012', 'JSUTINECASE', 25, '07-16-99', 'case', 'case', 12, 'case', 'Male', 'Single', 'case', 'pending', 5, '2024-12-06', '', 'uploads/valid_ids/1733485197_6752e28d83df8_front.jpg', 'uploads/valid_ids/1733485197_6752e28d840e8_back.jpg', '', '', NULL, 'pending', NULL),
(46, 22, 'Barangay Clearance', 'Raul Menendez', '123  Street Zone', '123456789012', '123456789012', 'cancel', 25, '12-05-99', 'cancel', 'cancel', 8, 'cancel', 'Male', 'Single', 'cancel', 'OVERDUE', 3, '2024-12-07', '', 'uploads/valid_ids/1733552883_6753eaf39253a_front.jpg', 'uploads/valid_ids/1733552883_6753eaf39291e_back.jpg', '', '', NULL, 'pending', NULL),
(47, 22, 'Barangay Clearance', 'Raul Menendez', '123  Street Zone', '123456789012', '123456789012', '2', 25, '12-05-99', '2', '2', 6, '2', 'Male', 'Single', '2', 'cancelled', 2, '2024-12-07 14:55:21', '', 'uploads/valid_ids/1733553111_6753ebd76ea04_front.jpg', 'uploads/valid_ids/1733553111_6753ebd76ec26_back.jpg', '', '', '', 'pending', NULL),
(48, 22, 'Certificate of Indigency', 'Raul Menendez', '123  Street Zone', '123456789012', '123456789012', '3', 25, '12-05-99', '3', '3', 7, '3', 'Male', 'Single', '3', 'cancelled', 4, '2024-12-07 14:34:36', '', 'uploads/valid_ids/1733553310_6753ec9e91eac_front.jpg', 'uploads/valid_ids/1733553310_6753ec9e92880_back.jpg', '', '', NULL, 'pending', NULL),
(49, 22, 'Barangay Certification', 'Raul Menendez', '123  Street Zone', '123456789012', '123456789012', 'y', 25, '12-05-99', 'y', '7', 7, 'y', 'Male', 'Single', 'y', 'cancelled', 2, '2024-12-07 15:00:53', '', 'uploads/valid_ids/1733554220_6753f02ccacd7_front.jpg', 'uploads/valid_ids/1733554220_6753f02ccaf2f_back.jpg', '', '', 'foyditdit', 'pending', NULL),
(50, 22, 'Barangay Clearance', 'Raul Menendez', '123  Street Zone', '921586239123', '921586239123', 'joy', 25, '12-05-99', 'tacloban  city', 'govt employee', 23, 'filipino', 'Male', 'Single', 'for nbi clearance', 'cancelled', 2, '2024-12-07 20:44:08', '', 'uploads/valid_ids/1733575501_6754434dd82a9_front.jpg', 'uploads/valid_ids/1733575501_6754434dd8c4a_back.jpg', '', '', 'No longer needed', 'pending', NULL),
(51, 27, 'Barangay Certification', 'John Marston', '123  Street Zone', '123456789012', '123456789012', 'john', 34, '12-07-90', 'asfddf', 'Student', 15, 'dasfsdf', 'Male', 'Single', 'For my lani scholarship', 'rejected', 2, '2024-12-11 19:17:16', '', 'uploads/valid_ids/1733915849_675974c96b286_front.jpg', 'uploads/valid_ids/1733915849_675974c96bb50_back.jpg', '', 'You have inputted the wrong information', NULL, 'pending', NULL),
(52, 30, 'Barangay Clearance', 'Clement Harold Miguel Cabus', '497-A  Street Zone', '123456789012', '123456789012', 'chummy', 20, '12-22-03', 'Makati Medical Center', 'Student', 15, 'Filipino', 'Male', 'Single', 'For my business transaction', 'approved', 1, '2024-12-12 09:09:42', '', 'uploads/valid_ids/1733965821_675a37fd13db9_front.jpg', 'uploads/valid_ids/1733965821_675a37fd14085_back.jpg', '', '', NULL, 'pending', '2024-12-12 09:27:05');

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
(11, 'John Marston', 'Accident', 'HASJKDHKAJSFsdasdasdJKAHSfa', '[\"uploads\\/incident_reports\\/1733817959_1431350a7596aef4.jpg\",\"uploads\\/incident_reports\\/1733817959_bf4fed8deb39d340.jpg\",\"uploads\\/incident_reports\\/1733817959_ddfda2616110f9d9.jpg\"]', '2024-12-10 09:05:59', 'resolved'),
(12, 'Raul Menendez', 'Property Damage', 'hnfjksdjkasdfm,asndfansdfjkdfasd', '[\"uploads\\/incident_reports\\/1733818104_c2c55a86659d9d09.jpg\"]', '2024-12-10 09:08:24', 'pending'),
(13, 'Clement Harold Miguel Cabus', 'Theft', 'ninakaw po Yung mga paninda naming chichirya sa kalaw Street 497-A', '[\"uploads\\/incident_reports\\/1733966063_4437ff679464f3e2.jpg\"]', '2024-12-12 02:14:23', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `admin_id`, `message`, `timestamp`, `is_admin`) VALUES
(1, 2, NULL, 'test', '2024-11-25 13:38:13', 0),
(2, 2, NULL, 'test', '2024-11-25 13:55:19', 1),
(3, 2, NULL, 'yessir', '2024-11-25 14:00:26', 1),
(4, 2, NULL, 'test', '2024-11-25 14:13:27', 0),
(5, 2, NULL, 'hello!', '2024-11-25 14:13:34', 1),
(7, 2, NULL, 'HELL NAH!!!', '2024-11-25 14:14:33', 1),
(9, 12, NULL, 'despanto test', '2024-11-25 14:19:42', 0),
(10, 12, NULL, 'test', '2024-11-25 14:19:49', 1),
(11, 9, NULL, 'Dave chappele test', '2024-11-25 14:22:33', 0),
(12, 9, NULL, 'hello dave chappelle', '2024-11-25 14:22:43', 1),
(21, 11, NULL, 'Michael me', '2024-11-25 14:24:09', 0),
(22, 11, NULL, 'test', '2024-11-25 14:29:06', 0),
(23, 12, NULL, 'test', '2024-11-25 14:30:18', 0),
(24, 12, NULL, 'te', '2024-11-25 14:30:20', 0),
(25, 12, NULL, 's', '2024-11-25 14:30:22', 0),
(26, 12, NULL, 'test', '2024-11-25 14:30:24', 0),
(27, 12, NULL, 'yes', '2024-11-25 14:30:27', 0),
(28, 12, NULL, 'hello', '2024-11-25 14:30:31', 0),
(29, 12, NULL, 'what', '2024-11-25 14:30:35', 0),
(30, 12, NULL, 'are you ok?', '2024-11-25 14:30:47', 1),
(31, 12, NULL, 'probably bro', '2024-11-25 14:30:53', 0),
(32, 12, NULL, 'do I not look fine?', '2024-11-25 14:31:00', 0),
(33, 12, NULL, 'yea ur probably fine', '2024-11-25 14:31:27', 1),
(34, 12, NULL, 'aight ty dawg', '2024-11-25 14:31:34', 0),
(35, 12, NULL, 'test', '2024-11-25 14:31:38', 0),
(36, 12, NULL, 't', '2024-11-25 14:31:41', 0),
(37, 11, NULL, 'wow', '2024-11-25 14:50:31', 1),
(40, 14, NULL, 'THIS IS CLEMENT!', '2024-11-25 14:58:03', 0),
(42, 14, NULL, 'HELLO CELEMTN!', '2024-11-25 15:12:32', 1),
(43, 2, NULL, 'Hello Admin', '2024-11-26 07:23:07', 0),
(44, 2, NULL, 'how can I get a clearance', '2024-11-26 07:23:17', 0),
(45, 2, NULL, 'just fill up the form', '2024-11-26 07:23:46', 1),
(46, 2, NULL, 'Hello', '2024-12-03 08:27:13', 1),
(47, 11, NULL, 'hello', '2024-12-03 08:32:55', 1),
(48, 14, NULL, 'clement', '2024-12-03 08:33:10', 1),
(49, 12, NULL, 'clement', '2024-12-03 08:33:14', 1),
(50, 14, NULL, 'clement', '2024-12-03 08:35:40', 1),
(51, 14, NULL, 'hello', '2024-12-03 08:39:24', 1),
(52, 12, NULL, 'hello', '2024-12-03 08:39:29', 1),
(53, 2, NULL, 'hello', '2024-12-03 08:42:25', 1),
(54, 14, NULL, 'test', '2024-12-03 08:43:02', 1),
(55, 9, NULL, 'test', '2024-12-03 08:43:45', 1),
(57, 9, 1, 'asd', '2024-12-03 09:01:12', 1),
(58, 9, 4, 'asdas', '2024-12-03 09:01:33', 1),
(59, 9, 4, 'hello', '2024-12-03 10:21:52', 1),
(60, 20, NULL, 'ge', '2024-12-04 09:36:37', 1),
(61, 20, NULL, 'heas', '2024-12-04 09:38:37', 1),
(62, 20, NULL, 'dawd', '2024-12-04 09:40:54', 0),
(63, 20, NULL, 'its me Hank schrader', '2024-12-04 09:44:06', 0),
(64, 20, 4, 'asd', '2024-12-04 09:45:02', 1),
(65, 20, 1, 'i am rannie bruh', '2024-12-04 09:46:00', 1),
(66, 20, NULL, 'what', '2024-12-04 09:46:12', 0),
(67, 9, 1, 'awd', '2024-12-04 12:42:15', 1),
(68, 2, 1, 'wdawd', '2024-12-04 12:42:19', 1),
(69, 2, 1, 'awd', '2024-12-04 12:42:19', 1),
(70, 2, 4, 'awd', '2024-12-04 12:44:30', 1),
(71, 2, 4, 'sdf.msbndfkhsdgfghjkagfhjkgshdfkgj', '2024-12-04 12:44:32', 1),
(72, 2, 2, 'asdawd', '2024-12-04 12:46:31', 1),
(73, 2, 2, 'sasa', '2024-12-04 12:48:41', 1),
(74, 2, NULL, 'awesome', '2024-12-04 12:49:36', 0),
(75, 21, NULL, 'babaooey', '2024-12-05 10:59:41', 0),
(76, 21, 2, 'shashumga', '2024-12-05 10:59:56', 1),
(77, 23, NULL, 'babaooe', '2024-12-06 16:02:44', 0),
(78, 22, NULL, 'hola DEA', '2024-12-07 05:39:18', 0),
(79, 22, 4, 'hola', '2024-12-06 05:39:25', 1),
(80, 22, NULL, 'hello', '2024-12-07 12:40:04', 0),
(81, 22, NULL, 'hello', '2024-12-07 12:40:07', 0),
(82, 22, NULL, 'hello', '2024-12-07 12:40:07', 0),
(83, 22, 4, 'hi', '2024-12-08 02:48:34', 1),
(84, 22, NULL, 'hh', '2024-12-08 04:57:13', 0),
(85, 22, NULL, 'hello men', '2024-12-11 12:45:14', 0),
(86, 22, 4, 'hi', '2024-12-11 12:45:19', 1),
(87, 22, NULL, 'hello', '2024-12-11 12:55:18', 0),
(88, 22, 4, 'hi', '2024-12-11 12:55:24', 1),
(89, 22, NULL, 'heusisjs', '2024-12-11 12:55:31', 0),
(90, 22, 4, 'dhfljasdfjkashdfkjasdfla', '2024-12-11 12:55:35', 1),
(91, 22, 4, '\"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.\"', '2024-12-11 12:56:05', 1),
(92, 30, NULL, 'Hello po paano po mag request ng document na barangay clearance', '2024-12-12 01:11:48', 0),
(93, 30, 4, 'Kailangan nyo lang po sundin yung instruction yada yada', '2024-12-12 01:12:30', 1);

-- --------------------------------------------------------

--
-- Table structure for table `status_change_logs`
--

CREATE TABLE `status_change_logs` (
  `id` int(11) NOT NULL,
  `document_id` int(11) NOT NULL,
  `old_status` varchar(50) NOT NULL,
  `new_status` varchar(50) NOT NULL,
  `change_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `status_change_logs`
--

INSERT INTO `status_change_logs` (`id`, `document_id`, `old_status`, `new_status`, `change_date`, `remarks`) VALUES
(1, 46, 'approved', 'OVERDUE', '2024-12-11 11:12:57', NULL),
(2, 40, 'approved', 'OVERDUE', '2024-12-11 11:13:10', NULL);

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
  `last_active` timestamp NULL DEFAULT NULL,
  `full_name` varchar(255) GENERATED ALWAYS AS (concat(`firstName`,' ',`lastName`)) STORED,
  `status` enum('pending','verified','rejected') NOT NULL DEFAULT 'pending',
  `user_valid_id` longtext DEFAULT NULL,
  `user_valid_id_back` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_accounts`
--

INSERT INTO `user_accounts` (`id`, `firstName`, `lastName`, `username`, `age`, `gender`, `adrHouseNo`, `adrZone`, `adrStreet`, `birthday`, `password`, `user_profile_picture`, `last_active`, `status`, `user_valid_id`, `user_valid_id_back`) VALUES
(22, 'Raul', 'Menendez', 'walt', 25, 'male', '123', '12', 'asda', '1999-12-05', '1IdXOTyMch/yApTuQoriJvEFXv01l0HTxEPvvwk6w0g=', 'uploads/user_profile_pictures/1733909661_67595c9d43914.jpg', '2024-12-11 17:57:44', 'verified', 'uploads/valid_ids/67519f205a3c4_front_valid_id_3658849476003197956.jpg', 'uploads/valid_ids/67519f205ab49_back_valid_id_back_3791201348274179855.jpg'),
(23, 'Justine', 'Case', 'justinecase', 25, 'male', '123', '2', '321', '1999-07-16', 'UUjgCjXQOvgO2rU4BeVxDpNJbbNq+bIlgmGN44pt2/0=', '', '2024-12-07 04:21:43', 'rejected', 'uploads/valid_ids/6751a0b246966_front_valid_id_2850338238797243267.jpg', 'uploads/valid_ids/6751a0b246f2b_back_valid_id_back_3692865936835824683.jpg'),
(24, 'James', 'Charles', 'james', 25, 'male', '123', '12', '123', '1999-12-06', 'kKov9QAModWIO8WVQRy7UK8pToZNkIdumwOCF2wY6iQ=', '', NULL, 'rejected', 'uploads/valid_ids/675298f5c2048_front_valid_id_1425142091750092768.jpg', 'uploads/valid_ids/675298f5c263b_back_valid_id_back_1578866002197435821.jpg'),
(25, 'Harry', 'Potter', 'harry', 25, 'male', '123', '123', '123', '1999-12-06', '1IdXOTyMch/yApTuQoriJvEFXv01l0HTxEPvvwk6w0g=', '', '2024-12-06 23:24:26', 'verified', 'uploads/valid_ids/67529943c7234_front_valid_id_960509828584422992.jpg', 'uploads/valid_ids/67529943c7f68_back_valid_id_back_4268192904904854930.jpg'),
(27, 'John', 'Marston', 'john', 34, 'male', '123', '123', '123', '1990-12-07', '+GWRDCz+w/V2Vx3dwBDMMT+QIYR5k9J7iEL5+fFpBKk=', 'uploads/user_profile_pictures/1733915875_675974e393a46.jpg', '2024-12-11 08:08:42', 'verified', 'uploads/valid_ids/675436666a43b_front_valid_id_55441369582974318.jpg', 'uploads/valid_ids/675436666a901_back_valid_id_back_2213364385378847230.jpg'),
(30, 'Clement Harold Miguel', 'Cabus', 'clementcabus', 20, 'male', '497-A', 'ISU Village', 'Kalaw', '2003-12-22', '+GWRDCz+w/V2Vx3dwBDMMT+QIYR5k9J7iEL5+fFpBKk=', 'uploads/user_profile_pictures/1733965649_675a37512b1c9.jpg', '2024-12-11 18:15:51', 'verified', 'uploads/valid_ids/675a36276edb5_front_valid_id_821736209743354799.jpg', 'uploads/valid_ids/675a36276f409_back_valid_id_back_7021366835071120687.jpg');

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
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sender_timestamp` (`sender_id`,`timestamp`);

--
-- Indexes for table `status_change_logs`
--
ALTER TABLE `status_change_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `document_id` (`document_id`);

--
-- Indexes for table `user_accounts`
--
ALTER TABLE `user_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `idx_full_name` (`full_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_accounts`
--
ALTER TABLE `admin_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `barangay_announcements`
--
ALTER TABLE `barangay_announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `document_requests`
--
ALTER TABLE `document_requests`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `incident_reports`
--
ALTER TABLE `incident_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `status_change_logs`
--
ALTER TABLE `status_change_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_accounts`
--
ALTER TABLE `user_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `status_change_logs`
--
ALTER TABLE `status_change_logs`
  ADD CONSTRAINT `status_change_logs_ibfk_1` FOREIGN KEY (`document_id`) REFERENCES `document_requests` (`Id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
