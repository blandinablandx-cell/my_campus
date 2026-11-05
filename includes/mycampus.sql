-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 05, 2026 at 07:16 AM
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
-- Database: `my_campus`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role` varchar(50) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `action_description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `curriculums`
--

CREATE TABLE `curriculums` (
  `id` int(11) NOT NULL,
  `academic_year` varchar(50) NOT NULL,
  `course_code` varchar(50) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `semester` varchar(20) NOT NULL,
  `program_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `curriculums`
--

INSERT INTO `curriculums` (`id`, `academic_year`, `course_code`, `course_name`, `semester`, `program_name`) VALUES
(1, 'Year I', 'ISNE 111', 'Introduction to Computer Programming', 'Semester I', 'Information Systems and Network Engineering'),
(2, 'Year I', 'ISNE 112', 'Computer Hardware and Basic Electronics', 'Semester I', 'Information Systems and Network Engineering'),
(3, 'Year I', 'ISNE 121', 'Object-Oriented Programming (Java/Python)', 'Semester II', 'Information Systems and Network Engineering'),
(4, 'Year I', 'ISNE 122', 'Computer Networks Fundamentals', 'Semester II', 'Information Systems and Network Engineering'),
(5, 'Year II', 'ISNE 211', 'Web Application Development (PHP)', 'Semester I', 'Information Systems and Network Engineering'),
(6, 'Year II', 'ISNE 212', 'Database Management Systems (MySQL)', 'Semester I', 'Information Systems and Network Engineering'),
(7, 'Year II', 'ISNE 221', 'Advanced Data Structures and Algorithms', 'Semester II', 'Information Systems and Network Engineering'),
(8, 'Year II', 'ISNE 222', 'Routing and Switching Essentials', 'Semester II', 'Information Systems and Network Engineering'),
(9, 'Year III', 'ISNE 311', 'Enterprise Network Security', 'Semester I', 'Information Systems and Network Engineering'),
(10, 'Year III', 'ISNE 312', 'Mobile Application Development', 'Semester I', 'Information Systems and Network Engineering'),
(11, 'Year III', 'ISNE 321', 'System Analysis, Design and Project', 'Semester II', 'Information Systems and Network Engineering'),
(12, 'Year III', 'ISNE 322', 'Cloud Computing and Administration', 'Semester II', 'Information Systems and Network Engineering'),
(13, 'Year I', 'CE', 'Engineering Mathematics', 'Semester I', 'Civil Engineering (CE)');

-- --------------------------------------------------------

--
-- Table structure for table `fees`
--

CREATE TABLE `fees` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `semester` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` date NOT NULL,
  `fee_status` varchar(20) DEFAULT 'Pending',
  `hall_ticket_access` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lecturer_assignments`
--

CREATE TABLE `lecturer_assignments` (
  `id` int(11) NOT NULL,
  `lecturer_id` int(11) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `module_name` varchar(255) NOT NULL,
  `target_group` varchar(100) NOT NULL,
  `assigned_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lecturer_assignments`
--

INSERT INTO `lecturer_assignments` (`id`, `lecturer_id`, `course_name`, `module_name`, `target_group`, `assigned_date`) VALUES
(3, 6, 'Information Systems and Network Engineering', 'Introduction to Computer Programming', 'Year I Students', '2026-10-22 10:16:31'),
(4, 10, 'Computer Science and Software Engineering', 'Computer Hardware and Basic Electronics', 'Year I Students', '2026-10-22 10:41:04');

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `id` int(11) NOT NULL,
  `program_name` varchar(255) NOT NULL,
  `program_code` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`id`, `program_name`, `program_code`) VALUES
(1, 'Information Systems and Network Engineering', 'ISNE'),
(2, 'Computer Science and Software Engineering', 'CSSE'),
(3, 'Bachelor of Business Administration', 'BBA'),
(4, 'Civil Engineering', 'CE'),
(5, 'Electrical and Electronics Engineering', 'EEE');

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `marks` int(11) NOT NULL,
  `module` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `subject_code` varchar(20) NOT NULL,
  `subject_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `subject_code`, `subject_name`) VALUES
(1, 'ICT101', 'Introduction to PHP & MySQL'),
(2, 'ICT102', 'Database Systems');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `reg_no` varchar(50) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','lecturer','student') NOT NULL,
  `age` int(11) DEFAULT NULL,
  `nationality` varchar(100) DEFAULT NULL,
  `education_level` varchar(100) DEFAULT NULL,
  `course` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Confirmed',
  `expire_date` date DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `year_of_study` varchar(50) DEFAULT NULL,
  `fee_status` varchar(20) DEFAULT 'Pending',
  `hall_ticket_access` int(11) DEFAULT 0,
  `total_fee` decimal(10,2) DEFAULT 1500000.00,
  `paid_amount` decimal(10,2) DEFAULT 0.00,
  `academic_year` varchar(50) DEFAULT 'Year I'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `reg_no`, `username`, `email`, `password`, `role`, `age`, `nationality`, `education_level`, `course`, `status`, `expire_date`, `salary`, `year_of_study`, `fee_status`, `hall_ticket_access`, `total_fee`, `paid_amount`, `academic_year`) VALUES
(4, 'james john', 'ECE2026001', 'james', 'nyalusijastin4@gmail.com', '$2y$10$UIW58edrBZL4pf0werzoVuOQ4t58dUn9GWx0jA2ElyTbTaiTc93re', 'student', 23, 'tanzanian', 'Form Six', 'ECE', 'Confirmed', '2029-10-21', NULL, NULL, 'Pending', 0, 1500000.00, 0.00, 'Year I'),
(6, 'suzy mlema', NULL, 'suzy', 'suzy255@gmail.com', '$2y$10$RUsnFHlW0X3FctzTQMpp8.8ZFpW7d4.rYg48zWSy.OClyeNpvT7wS', 'lecturer', 41, 'tanzanian', 'Bachelor', 'CS', 'Confirmed', NULL, NULL, NULL, 'Pending', 0, 1500000.00, 0.00, 'Year I'),
(8, 'System Administrator', NULL, 'admin255', 'admin@mycampus.com', 'admin123', 'admin', NULL, NULL, NULL, NULL, 'Confirmed', NULL, NULL, NULL, 'Pending', 0, 1500000.00, 0.00, 'Year I'),
(9, 'jastin nyalusi', 'IT2026001', 'jastin', 'nyalusijastin411@gmail.com', '$2y$10$hvyauGlGMLuAtpxDpkRv2eUhg2VR0HQkgHAMFJ8dng8lSp67gfbj6', 'student', 23, 'tanzanian', 'Form Six', 'IT', 'Confirmed', '2029-10-22', NULL, NULL, 'Pending', 0, 1500000.00, 0.00, 'Year I'),
(10, 'steven gwakisa', NULL, 'steven', 'steve11@gmail.com', '12345', 'lecturer', 33, 'tanzanian', 'Bachelor', 'CS', 'Confirmed', NULL, NULL, NULL, 'Pending', 0, 1500000.00, 0.00, 'Year I'),
(11, 'janeffer magwaya', 'NET2026001', 'jane', 'jay1@gmail.com', '123456', 'student', 23, 'tanzanian', 'Form Six', 'NET', 'Confirmed', '2029-10-22', NULL, NULL, 'Pending', 0, 1500000.00, 0.00, 'Year I');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `curriculums`
--
ALTER TABLE `curriculums`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fees`
--
ALTER TABLE `fees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `lecturer_assignments`
--
ALTER TABLE `lecturer_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lecturer_id` (`lecturer_id`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subject_code` (`subject_code`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `curriculums`
--
ALTER TABLE `curriculums`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `fees`
--
ALTER TABLE `fees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lecturer_assignments`
--
ALTER TABLE `lecturer_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fees`
--
ALTER TABLE `fees`
  ADD CONSTRAINT `fees_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lecturer_assignments`
--
ALTER TABLE `lecturer_assignments`
  ADD CONSTRAINT `lecturer_assignments_ibfk_1` FOREIGN KEY (`lecturer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `results`
--
ALTER TABLE `results`
  ADD CONSTRAINT `results_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
