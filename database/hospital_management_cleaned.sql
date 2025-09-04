-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 26, 2025 at 07:50 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `hospital_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `mobile_number` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `role` varchar(50) DEFAULT 'admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `mobile_number`, `password`, `email`, `full_name`, `role`, `created_at`, `last_login`, `is_active`) VALUES
(1, 'admin', '9142426139', '$2y$10$pdAEwFlRJ6QhfX616GAeHOzSpXR01QwuFiaGFkwapnJcC1P0768iC', 'admin@maakalawati.com', 'System Administrator', 'admin', '2025-08-03 18:16:55', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `analytics_ip_exclusions`
--

CREATE TABLE `analytics_ip_exclusions` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `analytics_ip_exclusions`
--

INSERT INTO `analytics_ip_exclusions` (`id`, `ip_address`, `note`, `created_at`) VALUES
(1, 'YOUR.IP.ADDR.ESS', 'Admin exclusion', '2025-08-11 17:04:43');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `number` varchar(15) NOT NULL,
  `name` varchar(255) NOT NULL,
  `feedback` text NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `number`, `name`, `feedback`, `timestamp`) VALUES
(9, '9900786540', 'Mahatama', '😂😂😂', '2025-08-04 00:17:24'),
(13, '9988770012', 'ARYA R SHARMA', 'testing new dashboard', '2025-08-04 11:49:39'),
(14, '9988770012', 'ARYA R SHARMA', 'view new error', '2025-08-04 11:57:12'),
(15, '9572946107', 'Boss', 'hi', '2025-08-05 19:21:06'),
(16, '9572946107', 'Boss', 'do you like these website❤️❤️.', '2025-08-06 21:45:31'),
(17, '9911223344', 'Orange Bot', 'yes', '2025-08-06 22:30:20'),
(18, '9142704414', 'Megha Badmos', 'i m Megha Badmos', '2025-08-08 00:22:09');

-- --------------------------------------------------------

--
-- Table structure for table `feedback_likes`
--

CREATE TABLE `feedback_likes` (
  `id` int(11) NOT NULL,
  `feedback_id` int(11) NOT NULL,
  `user_ip` varchar(45) NOT NULL,
  `dislike_type` enum('like','dislike') DEFAULT 'like',
  `liked_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback_likes`
--

INSERT INTO `feedback_likes` (`id`, `feedback_id`, `user_ip`, `dislike_type`, `liked_at`) VALUES
(5, 9, '9900786540', 'like', '2025-08-03 18:47:27'),
(6, 9, '9900786549', 'dislike', '2025-08-03 18:49:32'),
(15, 9, '6850432100', 'dislike', '2025-08-04 04:28:24'),
(21, 9, '9988770012', 'dislike', '2025-08-04 06:19:30'),
(26, 13, '9988770012', 'like', '2025-08-04 06:27:01'),
(27, 14, '9988770012', 'dislike', '2025-08-04 06:27:14'),
(28, 14, '9572946107', 'dislike', '2025-08-05 13:51:01'),
(29, 15, '9572946107', 'like', '2025-08-05 13:51:09'),
(30, 9, '9572946107', 'dislike', '2025-08-06 16:01:57'),
(32, 16, '9572946107', 'like', '2025-08-06 16:15:35'),
(33, 17, '9911223344', 'like', '2025-08-06 17:00:22'),
(34, 16, '9911223344', 'like', '2025-08-06 17:00:24'),
(35, 18, '9142704414', 'like', '2025-08-07 18:52:12'),
(36, 17, '9142704414', 'like', '2025-08-07 18:52:13'),
(37, 16, '9142704414', 'like', '2025-08-07 18:52:15'),
(38, 15, '9142704414', 'like', '2025-08-07 18:52:16'),
(39, 14, '9142704414', 'like', '2025-08-07 18:52:17'),
(40, 13, '9142704414', 'dislike', '2025-08-07 18:52:18'),
(42, 9, '9142704414', 'dislike', '2025-08-07 18:52:21');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `name`, `email`, `phone`, `subject`, `message`, `timestamp`) VALUES
(4, 'ARYA R SHARMA', 'arya@gmail.com', '9988770012', 'testing body of message', 'when i come for body checkup', '2025-08-04 11:55:22'),
(6, 'ARYA R SHARMA', 'arya@gmail.com', '9988770012', 'bimari', 'i m ILL NOW', '2025-08-04 13:11:45'),
(7, 'Orange Bot', 'orangebot@gmail.com', '9911223344', 'not available', 'testing................................❤️💀🏥......', '2025-08-06 22:31:44'),
(8, 'Megha Badmos', 'test_message@gmail.com', '9142704414', 'not available', 'Badmos Entry 😁😁', '2025-08-08 00:23:12');

-- --------------------------------------------------------

--
-- Stand-in structure for view `patient_dashboard_view`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `patient_dashboard_view`;
-- --------------------------------------------------------

--
-- Table structure for table `patient_data`
--

CREATE TABLE `patient_data` (
  `id` int(11) NOT NULL,
  `contact` varchar(15) NOT NULL,
  `name` varchar(255) NOT NULL,
  `age` int(11) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `address` text NOT NULL,
  `submission_time` time DEFAULT curtime(),
  `submission_date` date DEFAULT curdate(),
  `medical_history` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient_data`
--

INSERT INTO `patient_data` (`id`, `contact`, `name`, `age`, `gender`, `address`, `submission_time`, `submission_date`, `medical_history`) VALUES
(2, '9876543244', 'Test Patient 2', 21, 'Other', 'Test Address, Test City 3', '09:12:00', '2025-08-04', ''),
(3, '646453453', 'raj kumar ', 55, 'Male', 'godaa', '09:16:18', '2025-08-04', NULL),
(7, '9988770012', 'ARYA R SHARMA', 33, 'Male', 'godda, jharkhand', '12:40:42', '2025-08-04', ''),
(9, '9944236826', 'monah', 22, 'Male', 'kolkata,bengal', '13:21:21', '2025-08-04', NULL),
(10, '6568746600', 'ARYA R SHARMA', 55, 'Male', 'goda, mahagama', '01:23:00', '2025-08-04', 'fever'),
(12, '9911223344', 'Orange Bot', 45, 'Other', 'goda', '22:29:29', '2025-08-06', 'yes'),
(13, '9142704414', 'Megha Badmos', 18, 'Female', 'Ramgarh, Jharkhand', '20:27:03', '2025-08-07', '');

-- --------------------------------------------------------

--
-- Table structure for table `patient_profile_pictures`
--

CREATE TABLE `patient_profile_pictures` (
  `id` int(11) NOT NULL,
  `patient_mobile` varchar(15) NOT NULL,
  `picture_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patient_register`
--

CREATE TABLE `patient_register` (
  `id` int(11) NOT NULL,
  `mobile_no` varchar(15) NOT NULL,
  `name` varchar(255) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `password` varchar(255) NOT NULL,
  `register_date` date DEFAULT curdate(),
  `register_time` time DEFAULT curtime(),
  `age` int(11) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `medical_history` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient_register`
--

INSERT INTO `patient_register` (`id`, `mobile_no`, `name`, `gender`, `password`, `register_date`, `register_time`, `age`, `address`, `medical_history`) VALUES
(6, '9900786540', 'Mahatama', 'Other', '$2y$10$aHA6/h6Btcb4xdV544sHx.L61nXGOihTbNBCunNPXEOyC5uT1uuOW', '2025-08-03', '19:55:49', NULL, NULL, NULL),
(7, '9900786549', 'nathuram', 'Male', '$2y$10$unykCVb5gpI2zo3QhJi24Ofd1vLm/LSEFvxLakbzhEPpJoYF7XfW2', '2025-08-03', '20:49:08', NULL, NULL, NULL),
(8, '9001234567', 'Aman', 'Male', '$2y$10$Ln/.h4dyF2FXRNZ/HhUDhOBuRY07suPr7cviq1EFQTSDEnWE3XN0W', '2025-08-04', '06:09:08', NULL, NULL, NULL),
(9, '6850432100', 'Sonu s ', 'Male', '$2y$10$C0DjF7WZmUyPBqyncKtz/upzHB2EaP08swcklXgnnGmts9HhiPEHS', '2025-08-04', '06:22:09', NULL, NULL, NULL),
(11, '9988770012', 'ARYA R SHARMA', 'Male', '$2y$10$8TZRtU/suQ5FmsQt0iNwGugwj6iVznHk46/5J2h3OHfVEj0xEN746', '2025-08-04', '06:40:13', NULL, NULL, NULL),
(12, '9572946107', 'Boss', 'Male', '$2y$10$LU5o4QufuIwBer3etlBAQ.EquzLtKP1xftvdC8Hy7MQcSR4HyK/Ly', '2025-08-04', '09:54:33', NULL, NULL, NULL),
(13, '9911223344', 'Orange Bot', 'Female', '$2y$10$MWansxN18f/YJRK7ZH4ZxeV8i4otAWQ.Nxfvzg5MdK1AE7B4DV0h.', '2025-08-06', '18:58:58', NULL, NULL, NULL),
(14, '9142704414', 'Megha Badmos', 'Female', '$2y$10$e8zHsiC6sMw0Xb2.Mekqr.3iPpEvh/J5KPQ7sHJjdhb887tDVqpkm', '2025-08-07', '20:48:48', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_visits`
--

CREATE TABLE `user_visits` (
  `id` int(11) NOT NULL,
  `user_ip` varchar(45) NOT NULL,
  `visit_date` date DEFAULT curdate(),
  `visit_time` time DEFAULT curtime(),
  `page_visited` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_visits`
--

INSERT INTO `user_visits` (`id`, `user_ip`, `visit_date`, `visit_time`, `page_visited`) VALUES
(1, '::1', '2025-08-03', '20:09:05', NULL),
(2, '::1', '2025-08-04', '20:09:05', NULL),
(3, '::1', '2025-08-04', '20:09:05', NULL),
(4, '::1', '2025-08-04', '20:09:05', NULL),
(5, '::1', '2025-08-04', '20:09:05', NULL),
(6, '::1', '2025-08-04', '20:09:05', NULL),
(7, '::1', '2025-08-04', '20:09:05', NULL),
(8, '::1', '2025-08-04', '20:09:05', NULL),
(9, '::1', '2025-08-04', '20:09:05', NULL),
(10, '::1', '2025-08-04', '20:09:05', NULL),
(11, '::1', '2025-08-04', '20:09:05', NULL),
(12, '::1', '2025-08-07', '20:09:05', NULL),
(13, '::1', '2025-08-07', '20:09:05', NULL),
(14, '::1', '2025-08-07', '20:09:05', NULL),
(15, '::1', '2025-08-08', '20:09:05', NULL),
(16, '::1', '2025-08-08', '20:09:05', NULL),
(17, '::1', '2025-08-08', '20:09:05', NULL),
(18, '::1', '2025-08-08', '20:09:05', NULL),
(19, '::1', '2025-08-08', '20:09:05', NULL),
(20, '::1', '2025-08-08', '20:09:05', NULL),
(21, '::1', '2025-08-08', '20:09:05', NULL),
(22, '::1', '2025-08-08', '20:09:05', NULL),
(23, '::1', '2025-08-08', '20:09:05', NULL),
(24, '::1', '2025-08-08', '20:09:05', NULL),
(25, '::1', '2025-08-08', '20:09:05', NULL),
(26, '::1', '2025-08-08', '20:09:05', NULL),
(27, '::1', '2025-08-08', '20:09:05', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `visited_users`
--

CREATE TABLE `visited_users` (
  `id` int(11) NOT NULL,
  `user_ip` varchar(45) NOT NULL,
  `visit_date` date DEFAULT curdate(),
  `visit_time` time DEFAULT curtime()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure for view `patient_dashboard_view`
--
DROP TABLE IF EXISTS `patient_dashboard_view`;

CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `patient_dashboard_view`  AS SELECT `pr`.`mobile_no` AS `mobile_no`, `pr`.`name` AS `name`, `pr`.`gender` AS `gender`, `pr`.`register_date` AS `register_date`, `pr`.`register_time` AS `register_time`, `pr`.`age` AS `age`, `pr`.`address` AS `address`, `pr`.`medical_history` AS `medical_history`, count(distinct `f`.`id`) AS `total_feedback`, count(distinct `m`.`id`) AS `total_messages` FROM ((`patient_register` `pr` left join `feedback` `f` on(`pr`.`mobile_no` = `f`.`number`)) left join `messages` `m` on(`pr`.`name` = `m`.`name`)) GROUP BY `pr`.`mobile_no`, `pr`.`name`, `pr`.`gender`, `pr`.`register_date`, `pr`.`register_time`, `pr`.`age`, `pr`.`address`, `pr`.`medical_history` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `mobile_number` (`mobile_number`),
  ADD KEY `idx_admins_username` (`username`),
  ADD KEY `idx_admins_mobile` (`mobile_number`),
  ADD KEY `idx_admins_active` (`is_active`);

--
-- Indexes for table `analytics_ip_exclusions`
--
ALTER TABLE `analytics_ip_exclusions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ip_address` (`ip_address`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_feedback_number` (`number`);

--
-- Indexes for table `feedback_likes`
--
ALTER TABLE `feedback_likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `feedback_id` (`feedback_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_messages_name` (`name`);

--
-- Indexes for table `patient_data`
--
ALTER TABLE `patient_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_patient_data_contact` (`contact`);

--
-- Indexes for table `patient_profile_pictures`
--
ALTER TABLE `patient_profile_pictures`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `patient_mobile` (`patient_mobile`);

--
-- Indexes for table `patient_register`
--
ALTER TABLE `patient_register`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mobile_no` (`mobile_no`);

--
-- Indexes for table `user_visits`
--
ALTER TABLE `user_visits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_visits_ip` (`user_ip`);

--
-- Indexes for table `visited_users`
--
ALTER TABLE `visited_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `analytics_ip_exclusions`
--
ALTER TABLE `analytics_ip_exclusions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `feedback_likes`
--
ALTER TABLE `feedback_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `patient_data`
--
ALTER TABLE `patient_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `patient_profile_pictures`
--
ALTER TABLE `patient_profile_pictures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patient_register`
--
ALTER TABLE `patient_register`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `user_visits`
--
ALTER TABLE `user_visits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `visited_users`
--
ALTER TABLE `visited_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feedback_likes`
--
ALTER TABLE `feedback_likes`
  ADD CONSTRAINT `feedback_likes_ibfk_1` FOREIGN KEY (`feedback_id`) REFERENCES `feedback` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `patient_profile_pictures`
--
ALTER TABLE `patient_profile_pictures`
  ADD CONSTRAINT `patient_profile_pictures_ibfk_1` FOREIGN KEY (`patient_mobile`) REFERENCES `patient_register` (`mobile_no`) ON DELETE CASCADE;
COMMIT;

