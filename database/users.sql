-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 08, 2025 at 11:03 AM
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
-- Database: `login`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','administrator','event_advisor','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'Izzuddin', '12345', 'student');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- Table structure for table `event`
--

CREATE TABLE `event` (
  `event_id` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `approval_letter` varchar(255) DEFAULT NULL,
  `event_advisor_id` int(11) DEFAULT NULL,
  `qr_code` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`event_id`, `title`, `description`, `location`, `event_date`, `status`, `approval_letter`, `event_advisor_id`, `qr_code`) VALUES
(101, 'Tech Talk', 'Talk on AI trends', 'Auditorium A', '2024-05-10', 'approved', 'letter101.pdf', 2, 'QR101'),
(102, 'Workshop on Web Dev', 'Hands-on session on building websites', 'Lab 2', '2024-06-01', 'approved', 'letter102.pdf', 2, 'QR102'),
(103, 'Career Fair', 'Meet recruiters from top companies', 'Main Hall', '2024-07-15', 'pending', 'letter103.pdf', 2, 'QR103'),
(104, 'Hackathon', '24-hour coding competition', 'Innovation Hub', '2024-08-20', 'approved', 'letter104.pdf', 2, 'QR104'),
(105, 'Leadership Camp', '3-day leadership training camp', 'Resort Center', '2024-09-05', 'rejected', 'letter105.pdf', 2, 'QR105'),
(106, 'Volunteer Program', 'Community service activity', 'Community Hall', '2024-10-01', 'approved', 'letter106.pdf', 2, 'QR106'),
(107, 'Sports Day', 'Annual university-wide sports day', 'Stadium', '2024-11-20', 'approved', 'letter107.pdf', 2, 'QR107');

