-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 12, 2025 at 11:23 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `socialnetwork`
--

-- --------------------------------------------------------

--
-- Table structure for table `friendship`
--

CREATE TABLE `friendship` (
  `user1_id` int NOT NULL,
  `user2_id` int NOT NULL,
  `friendship_status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `friendship`
--

INSERT INTO `friendship` (`user1_id`, `user2_id`, `friendship_status`) VALUES
(1, 2, 1),
(1, 3, 1),
(2, 4, 1),
(3, 5, 1),
(1, 5, 1),
(4, 5, 0),
(2, 5, 0);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int NOT NULL,
  `post_caption` text NOT NULL,
  `post_time` timestamp NOT NULL,
  `post_public` char(1) NOT NULL,
  `post_by` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `post_caption`, `post_time`, `post_public`, `post_by`) VALUES
(100, 'Hallo allemaal! Mijn eerste bericht op Pynch.', '2025-03-03 09:12:00', 'Y', 1),
(101, 'Vandaag mijn eerste Laravel-project opgezet. Migraties zijn echt handig.', '2025-03-04 16:45:00', 'Y', 5),
(102, 'Wie gaat er zaterdag mee naar de sportschool?', '2025-03-05 19:30:00', 'N', 2),
(103, 'Net terug uit Griekenland, wat een mooi weer daar.', '2025-03-07 12:05:00', 'Y', 3),
(104, 'Tip: gebruik altijd prepared statements, dan kan niemand SQL in je zoekveld typen.', '2025-03-08 10:20:00', 'Y', 5),
(105, 'Eindelijk mijn stageplek rond!', '2025-03-10 14:42:00', 'N', 1),
(106, 'Heeft iemand een goede pizzeria in Ede?', '2025-03-12 18:10:00', 'Y', 4),
(107, 'Weekend! Tijd om te gamen.', '2025-03-14 20:00:00', 'N', 3),
(108, 'Mijn portfolio staat online, laat maar weten wat jullie ervan vinden.', '2025-03-16 11:15:00', 'Y', 5),
(109, 'Koffie en code, het perfecte begin van de dag.', '2025-03-18 08:30:00', 'Y', 2),
(110, 'Iemand zin om samen te leren voor het examen?', '2025-03-19 15:50:00', 'N', 4),
(111, 'Wat een mooie zonsondergang vanavond in Veenendaal.', '2025-03-20 21:05:00', 'Y', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `user_firstname` varchar(20) NOT NULL,
  `user_lastname` varchar(20) NOT NULL,
  `user_nickname` varchar(20) DEFAULT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_gender` char(1) NOT NULL,
  `user_birthdate` date NOT NULL,
  `user_status` char(1) DEFAULT NULL,
  `user_about` text,
  `user_hometown` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_firstname`, `user_lastname`, `user_nickname`, `user_password`, `user_email`, `user_gender`, `user_birthdate`, `user_status`, `user_about`, `user_hometown`) VALUES
(1, 'Sanne', 'de Vries', 'sanne', '$2y$10$lTHgOoi6KGk213U1tv/XWOluD8rDCYN0/3YG.QD3.X9RjPblw7a36', 'sanne@voorbeeld.nl', 'F', '2003-04-12', 'S', 'Ik studeer marketing en hou van fotografie.', 'Veenendaal'),
(2, 'Daan', 'Bakker', 'daanb', '$2y$10$lTHgOoi6KGk213U1tv/XWOluD8rDCYN0/3YG.QD3.X9RjPblw7a36', 'daan@voorbeeld.nl', 'M', '2002-09-23', 'E', 'Fanatiek sporter en koffieliefhebber.', 'Ede'),
(3, 'Youssef', 'El Amrani', 'youssef', '$2y$10$lTHgOoi6KGk213U1tv/XWOluD8rDCYN0/3YG.QD3.X9RjPblw7a36', 'youssef@voorbeeld.nl', 'M', '2004-01-30', 'S', 'Gamer en reiziger.', 'Utrecht'),
(4, 'Lisa', 'Jansen', 'lisaj', '$2y$10$lTHgOoi6KGk213U1tv/XWOluD8rDCYN0/3YG.QD3.X9RjPblw7a36', 'lisa@voorbeeld.nl', 'F', '2003-11-05', 'M', 'Op zoek naar de beste pizza van Ede.', 'Ede'),
(5, 'Paris', 'Angelopoulos', 'paris', '$2y$10$lTHgOoi6KGk213U1tv/XWOluD8rDCYN0/3YG.QD3.X9RjPblw7a36', 'paris@voorbeeld.nl', 'M', '2005-06-15', 'S', 'Web developer in opleiding. Ik heb deze site gebouwd.', 'Veenendaal');

-- --------------------------------------------------------

--
-- Table structure for table `user_phone`
--

CREATE TABLE `user_phone` (
  `user_id` int DEFAULT NULL,
  `user_phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_phone`
--

INSERT INTO `user_phone` (`user_id`, `user_phone`) VALUES
(1, '0612345678'),
(5, '0687654321');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `friendship`
--
ALTER TABLE `friendship`
  ADD KEY `user1_id` (`user1_id`),
  ADD KEY `user2_id` (`user2_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `post_by` (`post_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_phone`
--
ALTER TABLE `user_phone`
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `friendship`
--
ALTER TABLE `friendship`
  ADD CONSTRAINT `friendship_ibfk_1` FOREIGN KEY (`user1_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `friendship_ibfk_2` FOREIGN KEY (`user2_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`post_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `user_phone`
--
ALTER TABLE `user_phone`
  ADD CONSTRAINT `user_phone_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
