-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 27, 2023 at 03:52 PM
-- Server version: 5.7.31
-- PHP Version: 7.3.21

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

DROP TABLE IF EXISTS `class`;
CREATE TABLE IF NOT EXISTS `class` (
  `class_id` int(6) NOT NULL AUTO_INCREMENT,
  `subject_id` int(6) NOT NULL,
  `student_id` int(6) NOT NULL,
  `name` varchar(191) NOT NULL,
  `course` varchar(191) NOT NULL,
  PRIMARY KEY (`class_id`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`class_id`, `subject_id`, `student_id`, `name`, `course`) VALUES
(26, 10, 6, 'Luzviminda Pagulayan', 'BSIT'),
(21, 9, 6, 'Luzviminda Pagulayan', 'BSIT'),
(33, 11, 6, 'Luzviminda Pagulayan', 'BSIT'),
(31, 10, 8, 'Mark Donald', 'BEED'),
(34, 9, 6, 'Luzviminda Pagulayan', 'BSIT'),
(35, 9, 8, 'Mark Donald', 'BEED'),
(36, 1, 1, 'Mark Zuckerberg', 'BSIT'),
(37, 1, 2, 'Elon Musk', 'BEED'),
(38, 1, 3, 'Draco Malfoy', 'BSIT');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
CREATE TABLE IF NOT EXISTS `students` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `phone` varchar(191) NOT NULL,
  `course` varchar(191) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

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

DROP TABLE IF EXISTS `subjects`;
CREATE TABLE IF NOT EXISTS `subjects` (
  `subject_id` int(6) NOT NULL AUTO_INCREMENT,
  `subject_name` varchar(191) NOT NULL,
  `units` int(6) NOT NULL,
  `course` varchar(191) NOT NULL,
  PRIMARY KEY (`subject_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

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
-- Table structure for table `teachers`
--

DROP TABLE IF EXISTS `teachers`;
CREATE TABLE IF NOT EXISTS `teachers` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `phone` varchar(191) NOT NULL,
  `department` varchar(191) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

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

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_name`, `password`) VALUES
(1, 'admin', 'admin123');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
