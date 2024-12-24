-- -------------------------------------------------------------
-- TablePlus 6.1.6(570)
--
-- https://tableplus.com/
--
-- Database: banner
-- Generation Time: 2024-10-01 23:40:17.3450
-- -------------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL AUTO_INCREMENT,
  `course_type` text NOT NULL,
  `course_num` int(11) NOT NULL,
  `instructor` varchar(255) DEFAULT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `days` date NOT NULL,
  PRIMARY KEY (`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `employee` (
  `employee_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(30) DEFAULT NULL,
  `first_name` varchar(30) DEFAULT NULL,
  `middle_name` varchar(30) DEFAULT NULL,
  `last_name` varchar(30) DEFAULT NULL,
  `gender` varchar(15) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `staff_number` varchar(30) NOT NULL,
  `passport` varchar(255) DEFAULT NULL,
  `signature` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`employee_id`),
  UNIQUE KEY `staff_number` (`staff_number`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `registration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `semester_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `course_code` varchar(30) DEFAULT NULL,
  `course_title` varchar(255) DEFAULT NULL,
  `credit_units` int(11) DEFAULT NULL,
  `lecturer` varchar(255) DEFAULT NULL,
  `course_info` longtext DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`) USING BTREE,
  KEY `session_id` (`session_id`) USING BTREE,
  KEY `semester_id` (`semester_id`) USING BTREE,
  KEY `course_id` (`course_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `student` (
  `student_id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL,
  `matriculation_number` varchar(30) NOT NULL,
  `gender` varchar(15) DEFAULT NULL,
  `middle_name` varchar(30) DEFAULT NULL,
  `signature` varchar(255) DEFAULT NULL,
  `passport` varchar(255) DEFAULT NULL,
  `year_of_entry` int(11) DEFAULT NULL,
  `college_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `programme_id` int(11) DEFAULT NULL,
  `level_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`student_id`),
  UNIQUE KEY `matriculation_number` (`matriculation_number`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `student_registration` (
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `course_info` longtext DEFAULT NULL,
  `lecturer` varchar(255) DEFAULT NULL,
  `credit_units` int(11) DEFAULT NULL,
  `course_title` varchar(255) DEFAULT NULL,
  `course_code` varchar(30) DEFAULT NULL,
  `semester_id` int(11) DEFAULT NULL,
  `D0J` date DEFAULT NULL,
  PRIMARY KEY (`student_id`,`course_id`),
  KEY `course_id` (`course_id`),
  KEY `semester_id` (`semester_id`),
  CONSTRAINT `student_registration_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `registration` (`student_id`),
  CONSTRAINT `student_registration_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `registration` (`course_id`) ON DELETE CASCADE,
  CONSTRAINT `student_registration_ibfk_3` FOREIGN KEY (`semester_id`) REFERENCES `registration` (`semester_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `personnel_id` varchar(30) NOT NULL,
  `PWReset` tinyint(1) DEFAULT 1,
  `password` varchar(255) NOT NULL DEFAULT 'password',
  PRIMARY KEY (`personnel_id`),
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_addr` varchar(25) NOT NULL,
  `server_name` varchar(25) NOT NULL,
  `bits` int(11) DEFAULT NULL,
  `system` text NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `registration` (`id`, `student_id`, `session_id`, `semester_id`, `course_id`, `course_code`, `course_title`, `credit_units`, `lecturer`, `course_info`) VALUES
(1, 1, NULL, 1, 1, 'csc101', 'computer applicatios', 1, 'Hussein', 'M (8-9)');

INSERT INTO `student_registration` (`student_id`, `course_id`, `course_info`, `lecturer`, `credit_units`, `course_title`, `course_code`, `semester_id`, `D0J`) VALUES
(1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

INSERT INTO `user` (`user_id`, `personnel_id`, `PWReset`, `password`) VALUES
(1, 'A1B2C3', 0, '$2y$10$3whwH2BU0Vpc48JIRZAA..LL5aw3c88VH8vPFjFAuwpQyqYQnxa0m');



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;