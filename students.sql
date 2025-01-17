-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 17, 2025 at 03:15 AM
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
-- Database: `students`
--

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `class_id` int(6) NOT NULL,
  `subject_id` int(6) NOT NULL,
  `student_id` int(6) NOT NULL,
  `name` varchar(191) NOT NULL,
  `course` varchar(191) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`class_id`, `subject_id`, `student_id`, `name`, `course`) VALUES
(23, 1, 1, 'Mark Zuckerberg', 'BSIT'),
(24, 2, 1, 'Mark Zuckerberg', 'BSIT'),
(25, 5, 1, 'Mark Zuckerberg', 'BSIT'),
(26, 6, 1, 'Mark Zuckerberg', 'BSIT'),
(27, 1, 3, 'Draco Malfoy', 'BSIT'),
(28, 2, 3, 'Draco Malfoy', 'BSIT'),
(29, 5, 3, 'Draco Malfoy', 'BSIT'),
(30, 6, 3, 'Draco Malfoy', 'BSIT'),
(31, 1, 5, 'Jeff Bezos', 'BSIT'),
(32, 2, 5, 'Jeff Bezos', 'BSIT'),
(33, 5, 5, 'Jeff Bezos', 'BSIT'),
(34, 6, 5, 'Jeff Bezos', 'BSIT'),
(35, 3, 2, 'Elon Musk', 'BEED'),
(36, 4, 2, 'Elon Musk', 'BEED'),
(37, 3, 4, 'Ada Lovelace', 'BEED'),
(38, 4, 4, 'Ada Lovelace', 'BEED'),
(39, 2, 2, 'Elon Musk', 'BEED'),
(40, 2, 4, 'Ada Lovelace', 'BEED'),
(41, 1, 2, 'Elon Musk', 'BEED'),
(42, 1, 4, 'Ada Lovelace', 'BEED'),
(43, 5, 2, 'Elon Musk', 'BEED'),
(44, 5, 4, 'Ada Lovelace', 'BEED');

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `grade_id` int(6) NOT NULL,
  `class_id` int(6) NOT NULL,
  `student_id` int(6) NOT NULL,
  `subject_id` int(6) NOT NULL,
  `grade_value` decimal(5,2) NOT NULL,
  `term` varchar(20) NOT NULL,
  `academic_year` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `grades`
--

INSERT INTO `grades` (`grade_id`, `class_id`, `student_id`, `subject_id`, `grade_value`, `term`, `academic_year`, `created_at`, `updated_at`) VALUES
(2, 38, 3, 1, 1.00, 'First', '2024-2025', '2025-01-13 10:50:52', '2025-01-13 10:50:52'),
(3, 40, 4, 2, 1.00, 'First', '2024-2025', '2025-01-13 11:13:57', '2025-01-13 11:13:57');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `schedule_id` int(6) NOT NULL,
  `subject_id` int(6) NOT NULL,
  `teacher_id` int(6) NOT NULL,
  `day_of_week` int(1) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `room` varchar(50) NOT NULL,
  `academic_year` varchar(10) NOT NULL,
  `semester` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`schedule_id`, `subject_id`, `teacher_id`, `day_of_week`, `start_time`, `end_time`, `room`, `academic_year`, `semester`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 0, '02:00:00', '04:00:00', '101', '2024-2025', 'First', '2025-01-13 11:16:39', '2025-01-13 11:38:28');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(6) NOT NULL,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `phone` varchar(191) NOT NULL,
  `course` varchar(191) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `email`, `phone`, `course`) VALUES
(1, 'Mark Zuckerberg', 'mark@binalatongan.edu.ph', '092912345678', 'BSIT'),
(2, 'Elon Musk', 'elon@binalatongan.edu.ph', '09760367425', 'BEED'),
(3, 'Draco Malfoy', 'draco@binalatongan.edu.ph', '09732324123', 'BSIT'),
(4, 'Ada Lovelace', 'ada@binalatongan.edu.ph', '092311312312', 'BEED'),
(5, 'Jeff Bezos', 'jeff@binalatongan.edu.ph', '09987987987', 'BSIT');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `subject_id` int(6) NOT NULL,
  `subject_name` varchar(191) NOT NULL,
  `units` int(6) NOT NULL,
  `course` varchar(191) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`subject_id`, `subject_name`, `units`, `course`) VALUES
(1, 'Integrative Programming and Technologies 1', 3, 'BSIT'),
(2, 'Data Mining', 3, 'BSIT'),
(3, 'PROED 1', 3, 'BEED'),
(4, 'PE 2', 0, 'BEED'),
(5, 'Multimedia Technologies', 3, 'BSIT'),
(6, 'Software Engineering', 3, 'BSIT');

-- --------------------------------------------------------

--
-- Table structure for table `subject_teachers`
--

CREATE TABLE `subject_teachers` (
  `id` int(6) NOT NULL,
  `subject_id` int(6) NOT NULL,
  `teacher_id` int(6) NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `subject_teachers`
--

INSERT INTO `subject_teachers` (`id`, `subject_id`, `teacher_id`, `status`, `requested_at`) VALUES
(1, 1, 1, 'approved', '2025-01-17 01:59:24'),
(2, 1, 2, 'approved', '2025-01-17 01:59:24'),
(3, 2, 1, 'approved', '2025-01-17 01:59:24'),
(4, 2, 2, 'approved', '2025-01-17 01:59:24'),
(5, 3, 3, 'approved', '2025-01-17 01:59:24'),
(6, 3, 4, 'approved', '2025-01-17 01:59:24'),
(7, 4, 3, 'approved', '2025-01-17 01:59:24'),
(8, 4, 4, 'approved', '2025-01-17 01:59:24'),
(9, 5, 1, 'approved', '2025-01-17 01:59:24'),
(10, 5, 2, 'approved', '2025-01-17 01:59:24'),
(11, 6, 1, 'approved', '2025-01-17 01:59:24'),
(12, 6, 2, 'approved', '2025-01-17 01:59:24');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` int(6) NOT NULL,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `phone` varchar(191) NOT NULL,
  `department` varchar(191) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `name`, `email`, `phone`, `department`) VALUES
(1, 'Albert Einstein', 'albert@binalatongan.edu.ph', '09876876876', 'BSIT'),
(2, 'Isaac Newton', 'isaac@binalatongan.edu.ph', '0923424242342', 'BSIT'),
(3, 'Marie Curie', 'marie@binalatongan.edu.ph', '0987643276432', 'BEED'),
(4, 'Charles Darwin', 'charles@binalatongan.edu.ph', '092348723948', 'BEED');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(5) NOT NULL,
  `user_name` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `role` enum('admin','teacher','student') NOT NULL DEFAULT 'admin',
  `ref_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_name`, `password`, `role`, `ref_id`) VALUES
(1, 'admin', 'admin123', 'admin', NULL),
(2, 'albert', 'albert123', 'teacher', 1),
(3, 'isaac', 'isaac123', 'teacher', 2),
(4, 'marie', 'marie123', 'teacher', 3),
(5, 'charles', 'charles123', 'teacher', 4),
(6, 'mark', 'mark123', 'student', 1),
(7, 'elon', 'elon123', 'student', 2),
(8, 'draco', 'draco123', 'student', 3),
(9, 'ada', 'ada123', 'student', 4),
(10, 'jeff', 'jeff123', 'student', 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`class_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`grade_id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `idx_schedule_time` (`day_of_week`,`start_time`,`end_time`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subject_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `subject_teachers`
--
ALTER TABLE `subject_teachers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `class`
--
ALTER TABLE `class`
  MODIFY `class_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `grade_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `schedule_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subject_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `subject_teachers`
--
ALTER TABLE `subject_teachers`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `fk_grade_class` FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`),
  ADD CONSTRAINT `fk_grade_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`),
  ADD CONSTRAINT `fk_grade_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`);

--
-- Constraints for table `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `fk_schedule_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`),
  ADD CONSTRAINT `fk_schedule_teacher` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
