-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 28, 2025 at 12:43 PM
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
-- Database: `avmsdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `room_number` varchar(20) DEFAULT NULL,
  `room_type` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `status` enum('available','booked') DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_number`, `room_type`, `price`, `status`) VALUES
(1, '101', 'Single', 1200.00, 'booked'),
(2, '102', 'Double', 1800.00, 'booked'),
(3, '103', 'Deluxe', 2500.00, 'booked');

-- --------------------------------------------------------

--
-- Table structure for table `room_bookings`
--

CREATE TABLE `room_bookings` (
  `id` int(11) NOT NULL,
  `visitor_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `till_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_bookings`
--

INSERT INTO `room_bookings` (`id`, `visitor_id`, `room_id`, `booking_date`, `till_date`, `created_at`) VALUES
(1, 68, 1, '2025-06-28', '2025-06-30', '2025-06-28 10:14:50'),
(2, 68, 2, '2025-06-28', '2025-07-02', '2025-06-28 10:14:54'),
(3, 66, 2, '2025-07-10', '2025-07-28', '2025-06-28 10:30:55'),
(4, 66, 2, '2025-07-10', '2025-07-28', '2025-06-28 10:31:09'),
(5, 66, 1, '2025-07-11', '2025-07-12', '2025-06-28 10:31:43'),
(6, 66, 1, '2025-07-11', '2025-07-12', '2025-06-28 10:31:47'),
(7, 63, 3, '2025-07-11', '2025-07-18', '2025-06-28 10:32:01'),
(8, 63, 3, '2025-07-11', '2025-07-18', '2025-06-28 10:32:05');

-- --------------------------------------------------------

--
-- Table structure for table `tbladmin`
--

CREATE TABLE `tbladmin` (
  `ID` int(5) NOT NULL,
  `AdminName` varchar(45) DEFAULT NULL,
  `UserName` char(45) DEFAULT NULL,
  `MobileNumber` bigint(11) DEFAULT NULL,
  `Email` varchar(120) DEFAULT NULL,
  `Password` varchar(120) DEFAULT NULL,
  `AdminRegdate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbladmin`
--

INSERT INTO `tbladmin` (`ID`, `AdminName`, `UserName`, `MobileNumber`, `Email`, `Password`, `AdminRegdate`) VALUES
(1, 'Admin user', 'admin', 7898799797, 'admin@gmail.com', 'f925916e2754e5e03f75dd58a5733251', '2022-12-05 06:26:14'),
(2, 'Kuldip Jha', '7028688690', 7028688690, 'kuldipjha1995@gmail.com', '25d55ad283aa400af464c76d713c07ad', '2025-06-26 17:34:27');

-- --------------------------------------------------------

--
-- Table structure for table `tblcategory`
--

CREATE TABLE `tblcategory` (
  `id` int(11) NOT NULL,
  `categoryName` varchar(120) DEFAULT NULL,
  `creationDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblcategory`
--

INSERT INTO `tblcategory` (`id`, `categoryName`, `creationDate`) VALUES
(10, 'Guest', '2022-12-04 18:14:40');

-- --------------------------------------------------------

--
-- Table structure for table `tblvisitor`
--

CREATE TABLE `tblvisitor` (
  `ID` int(5) NOT NULL,
  `categoryName` varchar(120) DEFAULT NULL,
  `VisitorName` varchar(120) DEFAULT NULL,
  `MobileNumber` bigint(11) DEFAULT NULL,
  `Address` varchar(250) DEFAULT NULL,
  `Apartment` varchar(120) NOT NULL,
  `Floor` varchar(120) NOT NULL,
  `WhomtoMeet` varchar(120) DEFAULT NULL,
  `ReasontoMeet` varchar(120) DEFAULT NULL,
  `EnterDate` timestamp NULL DEFAULT current_timestamp(),
  `remark` varchar(255) DEFAULT NULL,
  `outtime` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `idCardType` varchar(50) DEFAULT NULL,
  `idCardNumber` varchar(100) DEFAULT NULL,
  `idCardImage` varchar(255) DEFAULT NULL,
  `visitorPhoto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblvisitor`
--

INSERT INTO `tblvisitor` (`ID`, `categoryName`, `VisitorName`, `MobileNumber`, `Address`, `Apartment`, `Floor`, `WhomtoMeet`, `ReasontoMeet`, `EnterDate`, `remark`, `outtime`, `idCardType`, `idCardNumber`, `idCardImage`, `visitorPhoto`) VALUES
(4, 'Guest', 'Juhi Jha', 8456525652, 'Virar', '100', '4th', 'Business meet', 'Business ', '2025-06-26 17:29:41', 'paid all due', '2025-06-26 17:30:34', NULL, NULL, NULL, NULL),
(5, 'Guest', 'Juhi Jha', 8456525652, 'Virar', '100', '4th', 'Business meet', 'Business ', '2025-06-26 17:44:37', 'paid all due', NULL, NULL, NULL, NULL, NULL),
(10, 'fdsd', 'fdsf', 5522552255, 'fdsf', 'fds', 'fdsf', 'fdsf', 'fdsf', '2025-06-26 17:57:27', 'fdsf', NULL, NULL, NULL, NULL, NULL),
(11, 'fdsd', 'fdsf', 5522552255, 'fdsf', 'fds', 'fdsf', 'fdsf', 'fdsf', '2025-06-26 17:58:42', 'fdsf', NULL, NULL, NULL, NULL, NULL),
(19, 'fd', 'dfsf', 4565435, 'fdsf', 'fdf', 'fsdf', 'fsdf', 'fds', '2025-06-26 18:22:12', 'fds', NULL, NULL, NULL, NULL, NULL),
(20, 'fd', 'dfsf', 4565435, 'fdsf', 'fdf', 'fsdf', 'fsdf', 'fds', '2025-06-26 18:22:44', 'fds', NULL, NULL, NULL, NULL, NULL),
(41, 'rerwe', 'ewqe', 5525412412, 'rerw', 'rerw', 'rer', 'rewr', 'rewr', '2025-06-27 04:59:03', 'ok', '2025-06-27 05:00:21', 'Passport', 'rewr', 'uploads/1751000343_girl.jpeg', NULL),
(42, 'rerwe', 'ewqe', 5525412412, 'rerw', 'rerw', 'rer', 'rewr', 'rewr', '2025-06-27 05:04:35', 'rewr', NULL, 'Passport', 'rewr', 'uploads/1751000675_girl.jpeg', NULL),
(43, 'fdgj', 'hghf', 6546564556, 'fghfhg', 'fhf', 'hf', 'hfh', 'fh', '2025-06-27 05:05:30', 'No Selfie photo uploaded.\r\n', '2025-06-27 05:05:57', 'Aadhar', 'fdsfds', 'uploads/1751000730_girl.jpeg', NULL),
(58, 'fdsf', 'fds', 5656554444, 'fds', 'fds', 'fds', 'fsd', 'fds', '2025-06-27 07:30:41', 'fds', NULL, '', '', '', NULL),
(59, 'Guest', 'Kuldip Jha', 7028688690, 'Pune', 'Bapuji Kripa', '3rd', 'Job', 'Job', '2025-06-27 08:15:46', '', NULL, '', '', '', NULL),
(60, 'Guest', 'Kuldip Jha', 7028688690, 'Pune', 'Bapuji Kripa', '3rd', 'Job', 'Job', '2025-06-27 08:24:26', '', NULL, '', '', '', NULL),
(61, 'sad', 'dsa', 4252522222, 'dsad', 'dsa', 'dsa', 'dsad', 'dsa', '2025-06-27 08:37:55', '', NULL, '', '', '', NULL),
(62, 'Guest', 'Kuldip Jha', 7028688690, 'Pune', 'Bapuji Kripa', '3rd', 'Job', 'Job', '2025-06-27 11:14:45', '', NULL, '', '', '', NULL),
(63, 'Guest', 'Kuldip Jha', 7028688690, 'Pune', 'Bapuji Kripa', '3rd', 'Job', 'Job', '2025-06-28 09:52:04', '', NULL, 'Passport', '64643456765434', '', NULL),
(64, 'Guest', 'hkjgf', 5456456566, 'dhghd', 'hfh', 'tg', 'hfh', 'fhg', '2025-06-28 09:52:41', NULL, NULL, NULL, NULL, NULL, NULL),
(65, 'dfghjk', 'ghgf', 5655664433, 'dgfd', 'dgfd', 'rdgf', 'fgf', 'gdgd', '2025-06-28 09:53:22', '', NULL, 'Aadhar', '6454456545', '', NULL),
(66, 'Guest', 'Kuldip Jha', 7028688690, 'Pune', 'Bapuji Kripa', '3rd', 'Job', 'Job', '2025-06-28 10:01:44', '', NULL, '', '', '', NULL),
(67, 'Guest', 'Kuldip Jha', 7028688690, 'Pune', 'Bapuji Kripa', '3rd', 'Job', 'Job', '2025-06-28 10:08:49', '', NULL, '', '', '', NULL),
(68, 'Guest', 'Kuldip Jha', 7028688690, 'Pune', 'Bapuji Kripa', '3rd', 'Job', 'Job', '2025-06-28 10:11:58', '', NULL, '', '', '', NULL),
(69, 'Guest', 'Kuldip Jha', 7028688690, 'Pune', 'Bapuji Kripa', '3rd', 'Job', 'Job', '2025-06-28 10:20:33', '', NULL, '', '', '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblvisitorpass`
--

CREATE TABLE `tblvisitorpass` (
  `ID` int(5) NOT NULL,
  `passnumber` bigint(20) DEFAULT NULL,
  `categoryName` varchar(120) DEFAULT NULL,
  `VisitorName` varchar(120) DEFAULT NULL,
  `MobileNumber` bigint(11) DEFAULT NULL,
  `Address` varchar(250) DEFAULT NULL,
  `Apartment` varchar(120) NOT NULL,
  `Floor` varchar(120) NOT NULL,
  `passDetails` varchar(120) DEFAULT NULL,
  `creationDate` timestamp NULL DEFAULT current_timestamp(),
  `fromDate` date DEFAULT NULL,
  `toDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblvisitorpass`
--

INSERT INTO `tblvisitorpass` (`ID`, `passnumber`, `categoryName`, `VisitorName`, `MobileNumber`, `Address`, `Apartment`, `Floor`, `passDetails`, `creationDate`, `fromDate`, `toDate`) VALUES
(4, 12189696, 'Guest', 'gdgfd', 0, 'ghfhj', 'hjfh', 'gdgf', 'yfhfgh', '2025-06-27 03:28:48', '2025-06-27', '2025-06-30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `room_bookings`
--
ALTER TABLE `room_bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbladmin`
--
ALTER TABLE `tbladmin`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblcategory`
--
ALTER TABLE `tblcategory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblvisitor`
--
ALTER TABLE `tblvisitor`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblvisitorpass`
--
ALTER TABLE `tblvisitorpass`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID` (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `room_bookings`
--
ALTER TABLE `room_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbladmin`
--
ALTER TABLE `tbladmin`
  MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblcategory`
--
ALTER TABLE `tblcategory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tblvisitor`
--
ALTER TABLE `tblvisitor`
  MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `tblvisitorpass`
--
ALTER TABLE `tblvisitorpass`
  MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
