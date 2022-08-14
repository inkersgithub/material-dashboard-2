-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 14, 2022 at 09:24 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `a`
--

-- --------------------------------------------------------

--
-- Table structure for table `bills`
--

CREATE TABLE `bills` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item` varchar(250) NOT NULL,
  `amount` float NOT NULL,
  `is_deleted` int(11) NOT NULL DEFAULT 0,
  `added_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `added_by` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bills`
--

INSERT INTO `bills` (`id`, `group_id`, `user_id`, `item`, `amount`, `is_deleted`, `added_time`, `added_by`) VALUES
(1, 1, 2, 'iasuhg', 12, 0, '2022-07-27 15:43:17', 1),
(2, 1, 2, 'asdfa', 12, 0, '2022-07-27 15:43:17', 1),
(3, 2, 2, 'Test', 12, 0, '2022-07-27 15:45:23', 1),
(4, 2, 2, 'Test', 12, 0, '2022-07-27 15:45:23', 1),
(5, 3, 2, 'Test', 12, 0, '2022-07-27 15:46:36', 1),
(6, 3, 2, 'TEst', 12, 0, '2022-07-27 15:46:36', 1),
(7, 4, 2, 'Alpha', 1234, 0, '2022-07-27 16:02:11', 1),
(8, 4, 2, 'Gamam', 123, 0, '2022-07-27 16:02:11', 1),
(9, 5, 2, 'Alpha', 12, 0, '2022-07-27 16:09:21', 1),
(10, 5, 2, 'Gamma', 10, 0, '2022-07-27 16:09:21', 1),
(11, 6, 2, 'Test', 123, 0, '2022-07-27 16:22:11', 1),
(12, 6, 2, 'Alpha', 123, 0, '2022-07-27 16:22:11', 1),
(13, 7, 2, 'test1', 10, 0, '2022-08-05 13:25:23', 2),
(14, 7, 2, 'test2', 12, 0, '2022-08-05 13:25:23', 2),
(15, 8, 2, 'jandf', 12, 0, '2022-08-05 13:34:19', 2),
(16, 8, 2, 'gamma', 8, 0, '2022-08-05 13:34:19', 2);

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `path` text NOT NULL,
  `is_featured` int(11) NOT NULL DEFAULT 0,
  `is_deleted` int(11) NOT NULL DEFAULT 0,
  `added_by` int(11) NOT NULL,
  `added_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `removed_by` int(11) NOT NULL,
  `removed_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `title`, `path`, `is_featured`, `is_deleted`, `added_by`, `added_time`, `removed_by`, `removed_time`) VALUES
(1, 'This is a featured document', 'uploads/18431658812503.pdf', 1, 0, 1, '2022-07-26 05:15:03', 1, '2022-07-26 05:15:03'),
(2, 'This is a unfeatured link', 'uploads/35651658812524.pdf', 0, 0, 1, '2022-07-26 05:15:24', 1, '2022-07-26 05:15:24'),
(3, 'Remove file link', 'uploads/89811658812541.pdf', 0, 1, 1, '2022-07-26 05:15:41', 1, '2022-07-26 05:15:47');

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

CREATE TABLE `links` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `link` text NOT NULL,
  `is_featured` int(11) NOT NULL,
  `is_deleted` int(11) NOT NULL DEFAULT 0,
  `added_by` int(11) NOT NULL,
  `added_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `removed_by` int(11) NOT NULL,
  `removed_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `links`
--

INSERT INTO `links` (`id`, `title`, `link`, `is_featured`, `is_deleted`, `added_by`, `added_time`, `removed_by`, `removed_time`) VALUES
(1, 'Featured - This link redirect you to google', 'https://google.com', 1, 0, 1, '2022-07-26 05:11:54', 1, '2022-07-26 05:11:54'),
(2, 'This link redirects you to gmail', 'https://gmail.com', 0, 0, 1, '2022-07-26 05:12:18', 1, '2022-07-26 05:12:18'),
(3, 'Remove Test Link', 'https://remove', 0, 1, 1, '2022-07-26 05:14:02', 1, '2022-07-26 05:14:08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `uid` varchar(15) NOT NULL,
  `type` int(11) NOT NULL DEFAULT 0,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `password` varchar(100) NOT NULL,
  `address_line1` varchar(250) NOT NULL,
  `address_line2` varchar(250) NOT NULL,
  `address_line3` varchar(250) NOT NULL,
  `pincode` varchar(7) NOT NULL,
  `is_deleted` int(11) NOT NULL DEFAULT 0,
  `is_approved` int(11) NOT NULL DEFAULT 0,
  `approved_by` int(11) NOT NULL,
  `approved_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `added_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `uid`, `type`, `name`, `email`, `phone`, `password`, `address_line1`, `address_line2`, `address_line3`, `pincode`, `is_deleted`, `is_approved`, `approved_by`, `approved_time`, `added_time`) VALUES
(1, 'admin', 1, 'Admin', 'admin', 'admin', 'admin', 'admin', 'admin', 'admin', 'admin', 0, 1, 1, '2022-07-26 05:09:29', '2022-07-26 05:09:29'),
(2, 'uid', 0, 'Anoop', 'anoopkrishna157@gmail.com', '123451234', 'admin', 'address1', 'address2', 'address3', '12345', 0, 1, 1, '2022-07-26 12:21:57', '2022-07-26 05:20:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `links`
--
ALTER TABLE `links`
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
-- AUTO_INCREMENT for table `bills`
--
ALTER TABLE `bills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `links`
--
ALTER TABLE `links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
