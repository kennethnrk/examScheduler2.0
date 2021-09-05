-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 05, 2021 at 01:06 PM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `exam_scheduler`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`) VALUES
(1, 'kenneth', 'kenneth123');

-- --------------------------------------------------------

--
-- Table structure for table `course_list`
--

CREATE TABLE `course_list` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `Semester` int(4) NOT NULL,
  `Duration` int(4) NOT NULL,
  `Marks` int(4) NOT NULL,
  `Scheduled_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `course_list`
--

INSERT INTO `course_list` (`id`, `name`, `Semester`, `Duration`, `Marks`, `Scheduled_date`) VALUES
(1, 'Art.', 1, 1, 100, '2021-08-18'),
(2, 'Geography.', 2, 1, 100, '2021-08-18'),
(3, 'English.', 2, 1, 100, '2021-08-19'),
(4, 'Literacy.', 2, 1, 100, '2021-08-20'),
(5, 'Music.', 3, 1, 100, '2021-08-18'),
(6, 'Science.', 3, 1, 100, '2021-08-19'),
(7, 'Arithmetic.', 3, 1, 100, '2021-08-20'),
(8, 'Mathematics', 3, 1, 100, '2021-08-21'),
(9, 'Computers', 3, 1, 100, '2021-08-22');

-- --------------------------------------------------------

--
-- Table structure for table `exams`
--

CREATE TABLE `exams` (
  `id` int(11) NOT NULL,
  `no_courses` int(11) NOT NULL,
  `no_students` int(11) NOT NULL,
  `no_examhalls` int(11) NOT NULL,
  `no_seats` int(11) NOT NULL,
  `startdate` date NOT NULL,
  `enddate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `exams`
--

INSERT INTO `exams` (`id`, `no_courses`, `no_students`, `no_examhalls`, `no_seats`, `startdate`, `enddate`) VALUES
(1, 9, 38, 2, 10, '2021-08-18', '2021-08-30');

-- --------------------------------------------------------

--
-- Table structure for table `seats`
--

CREATE TABLE `seats` (
  `id` int(11) NOT NULL,
  `slot_id` int(11) NOT NULL,
  `seat_no` int(11) NOT NULL,
  `student_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `seats`
--

INSERT INTO `seats` (`id`, `slot_id`, `seat_no`, `student_id`) VALUES
(1, 1, 1, 1),
(2, 1, 2, 5),
(3, 1, 3, 9),
(4, 1, 4, 13),
(5, 1, 5, 17),
(6, 1, 6, 20),
(7, 1, 7, 25),
(8, 1, 8, 29),
(9, 1, 9, 33),
(10, 1, 10, 36),
(11, 2, 1, 38),
(12, 3, 1, 2),
(13, 3, 2, 6),
(14, 3, 3, 10),
(15, 3, 4, 14),
(16, 3, 5, 18),
(17, 3, 6, 23),
(18, 3, 7, 26),
(19, 3, 8, 30),
(20, 3, 9, 37),
(21, 4, 1, 2),
(22, 4, 2, 6),
(23, 4, 3, 10),
(24, 4, 4, 14),
(25, 4, 5, 18),
(26, 4, 6, 23),
(27, 4, 7, 26),
(28, 4, 8, 30),
(29, 4, 9, 37),
(30, 5, 1, 2),
(31, 5, 2, 6),
(32, 5, 3, 10),
(33, 5, 4, 14),
(34, 5, 5, 18),
(35, 5, 6, 23),
(36, 5, 7, 26),
(37, 5, 8, 30),
(38, 5, 9, 37),
(39, 6, 1, 3),
(40, 6, 2, 7),
(41, 6, 3, 11),
(42, 6, 4, 15),
(43, 6, 5, 19),
(44, 6, 6, 22),
(45, 6, 7, 27),
(46, 6, 8, 31),
(47, 6, 9, 34),
(48, 7, 1, 3),
(49, 7, 2, 7),
(50, 7, 3, 11),
(51, 7, 4, 15),
(52, 7, 5, 19),
(53, 7, 6, 22),
(54, 7, 7, 27),
(55, 7, 8, 31),
(56, 7, 9, 34),
(57, 8, 1, 3),
(58, 8, 2, 7),
(59, 8, 3, 11),
(60, 8, 4, 15),
(61, 8, 5, 19),
(62, 8, 6, 22),
(63, 8, 7, 27),
(64, 8, 8, 31),
(65, 8, 9, 34),
(66, 9, 1, 3),
(67, 9, 2, 7),
(68, 9, 3, 11),
(69, 9, 4, 15),
(70, 9, 5, 19),
(71, 9, 6, 22),
(72, 9, 7, 27),
(73, 9, 8, 31),
(74, 9, 9, 34),
(75, 10, 1, 3),
(76, 10, 2, 7),
(77, 10, 3, 11),
(78, 10, 4, 15),
(79, 10, 5, 19),
(80, 10, 6, 22),
(81, 10, 7, 27),
(82, 10, 8, 31),
(83, 10, 9, 34);

-- --------------------------------------------------------

--
-- Table structure for table `slots`
--

CREATE TABLE `slots` (
  `id` int(11) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `course_id` int(11) NOT NULL,
  `Course_name` varchar(255) NOT NULL,
  `hall_no` int(11) NOT NULL,
  `slot_date` date NOT NULL,
  `exam_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `slots`
--

INSERT INTO `slots` (`id`, `start_time`, `end_time`, `course_id`, `Course_name`, `hall_no`, `slot_date`, `exam_id`) VALUES
(1, '11:00:00', '12:00:00', 1, 'Art.', 1, '2021-08-18', 1),
(2, '11:00:00', '12:00:00', 1, 'Art.', 2, '2021-08-18', 1),
(3, '12:00:00', '13:00:00', 2, 'Geography.', 1, '2021-08-18', 1),
(4, '11:00:00', '12:00:00', 3, 'English.', 1, '2021-08-19', 1),
(5, '11:00:00', '12:00:00', 4, 'Literacy.', 1, '2021-08-20', 1),
(6, '12:00:00', '13:00:00', 5, 'Music.', 2, '2021-08-18', 1),
(7, '11:00:00', '12:00:00', 6, 'Science.', 2, '2021-08-19', 1),
(8, '11:00:00', '12:00:00', 7, 'Arithmetic.', 2, '2021-08-20', 1),
(9, '11:00:00', '12:00:00', 8, 'Mathematics', 1, '2021-08-21', 1),
(10, '11:00:00', '12:00:00', 9, 'Computers', 1, '2021-08-22', 1);

-- --------------------------------------------------------

--
-- Table structure for table `student_list`
--

CREATE TABLE `student_list` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `Semester` int(4) NOT NULL,
  `enrollment_no` varchar(55) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student_list`
--

INSERT INTO `student_list` (`id`, `name`, `Semester`, `enrollment_no`) VALUES
(1, 'Aayansh.', 1, 'FCOG19101'),
(2, 'Avyukt.', 2, 'FCOG19102'),
(3, 'Kiyansh.', 3, 'FCOG19103'),
(4, 'Atharv.', 4, 'FCOG19104'),
(5, 'Ivaan.', 1, 'FCOG19106'),
(6, 'Sriansh.', 2, 'FCOG19107'),
(7, 'Aayansh', 3, 'FCOG19108'),
(8, 'Avyukt', 4, 'FCOG19109'),
(9, 'Aarav', 1, 'FCOG19110'),
(10, 'Kiyansh', 2, 'FCOG19111'),
(11, 'Yuvaan', 3, 'FCOG19112'),
(12, 'Atharv', 4, 'FCOG19113'),
(13, 'Rihaan', 1, 'FCOG19114'),
(14, 'Ivaan', 2, 'FCOG19115'),
(15, 'Sriansh', 3, 'FCOG19116'),
(16, 'Vivaan', 4, 'FCOG19117'),
(17, 'Prisha', 1, 'FCOG19118'),
(18, 'Divisha', 2, 'FCOG19119'),
(19, 'Aashvi', 3, 'FCOG19120'),
(20, 'Anaya', 1, 'FCOG19121'),
(21, 'Anvika', 4, 'FCOG19122'),
(22, 'Rutvi', 3, 'FCOG19123'),
(23, 'Aarvi', 2, 'FCOG19124'),
(24, 'Kayra', 4, 'FCOG19125'),
(25, 'Trishika', 1, 'FCOG19126'),
(26, 'Aadvika', 2, 'FCOG19127'),
(27, 'aaa', 3, 'FCOG19128'),
(28, 'aab', 4, 'FCOG19129'),
(29, 'aaa', 1, 'FCOG19130'),
(30, 'aab', 2, 'FCOG19131'),
(31, 'aaa', 3, 'FCOG19132'),
(32, 'aab', 4, 'FCOG19133'),
(33, 'aaa', 1, 'FCOG19134'),
(34, 'aab', 3, 'FCOG19135'),
(35, 'aaa', 4, 'FCOG19136'),
(36, 'aab', 1, 'FCOG19137'),
(37, 'aaa', 2, 'FCOG19138'),
(38, 'aab', 1, 'FCOG19139');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `course_list`
--
ALTER TABLE `course_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exams`
--
ALTER TABLE `exams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seats`
--
ALTER TABLE `seats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `slots`
--
ALTER TABLE `slots`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_list`
--
ALTER TABLE `student_list`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `course_list`
--
ALTER TABLE `course_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `exams`
--
ALTER TABLE `exams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `seats`
--
ALTER TABLE `seats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `slots`
--
ALTER TABLE `slots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `student_list`
--
ALTER TABLE `student_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
