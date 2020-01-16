-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 13, 2019 at 11:48 AM
-- Server version: 10.1.26-MariaDB
-- PHP Version: 7.1.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `baba_hrms`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notification`
--

CREATE TABLE `tbl_notification` (
  `notification_id` int(11) NOT NULL,
  `notification_service_id` int(11) NOT NULL,
  `notify_to` text,
  `service_type` varchar(255) NOT NULL,
  `notification_added_by` int(11) NOT NULL,
  `notification_added_to` varchar(256) DEFAULT NULL,
  `applied_id` varchar(50) NOT NULL,
  `notification_text` varchar(255) NOT NULL,
  `submit_date` date NOT NULL,
  `read_by` longtext,
  `code_title` varchar(256) DEFAULT NULL,
  `by_personn` varchar(256) DEFAULT NULL,
  `updated_date` date NOT NULL,
  `read_status` enum('0','1') NOT NULL DEFAULT '0',
  `read_date` date DEFAULT NULL,
  `read_status_team_member` varchar(256) NOT NULL,
  `read_date_team_member` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_notification`
--

INSERT INTO `tbl_notification` (`notification_id`, `notification_service_id`, `notify_to`, `service_type`, `notification_added_by`, `notification_added_to`, `applied_id`, `notification_text`, `submit_date`, `read_by`, `code_title`, `by_personn`, `updated_date`, `read_status`, `read_date`, `read_status_team_member`, `read_date_team_member`) VALUES
(1, 34, '1', 'Jost Assigned', 2, 'Sumit madne', '1', 'Web Dev  assigned to Sumit madne By Sumit madne', '2019-10-24', NULL, NULL, NULL, '2019-10-24', '0', '2019-10-24', '2', '2019-10-24'),
(2, 35, '1', 'Jost Assigned', 2, 'Sumit madne', '1', 'Web Dev  assigned to Sumit madne By Sumit madne', '2019-10-24', NULL, NULL, NULL, '2019-10-24', '0', '2019-10-24', '2', '2019-10-24'),
(3, 36, '1', 'Jost Assigned', 2, 'Sumit madne', '1', 'Web Dev  assigned to Sumit madne By Sumit madne', '2019-10-24', NULL, NULL, NULL, '2019-10-24', '0', '2019-10-24', '2', '2019-10-24'),
(4, 37, '6', 'Jost Assigned', 2, 'prem Toppo', '6', 'Web Dev  assigned to prem Toppo By Sumit madne', '2019-10-24', NULL, NULL, NULL, '2019-10-24', '0', '2019-10-24', '2', '2019-10-24'),
(5, 38, '5', 'Jost Assigned', 2, 'Ravi lal Teli', '5', 'Web Dev  assigned to Ravi lal Teli By Sumit madne', '2019-10-24', NULL, NULL, NULL, '2019-10-24', '0', '2019-10-24', '2', '2019-10-24'),
(6, 286, NULL, 'Post Job', 5, NULL, ' ', 'Web DevThis  job Is Posted By Amit', '2019-11-07', NULL, NULL, NULL, '2019-11-07', '0', '2019-11-07', '1', '2019-11-07'),
(7, 287, NULL, 'Post Job', 1, NULL, ' ', 'testForCareerspageThis  job Is Posted By Sumit madne', '2019-11-08', NULL, NULL, NULL, '2019-11-08', '0', '2019-11-08', '1', '2019-11-08'),
(8, 288, NULL, 'Post Job', 2, NULL, ' ', 'this  public jobThis  job Is Posted By Jashraj Kumar', '2019-11-08', NULL, NULL, NULL, '2019-11-08', '0', '2019-11-08', '1', '2019-11-08'),
(9, 288, NULL, 'Update Job', 2, 'anubhav', ' ', 'this  public jobgh66767This  job Is Update By Sumit madne', '2019-11-09', NULL, NULL, NULL, '2019-11-09', '0', '2019-11-09', '1', '2019-11-09'),
(10, 289, NULL, 'Post Job', 1, NULL, ' ', 'Web DevThis  job Is Posted By Sumit madne', '2019-11-12', NULL, NULL, NULL, '2019-11-12', '0', '2019-11-12', '1', '2019-11-12'),
(11, 39, '2', 'Jost Assigned', 2, 'Jashraj Kumar', '2', 'Web Dev  assigned to Jashraj Kumar By Sumit madne', '2019-11-12', NULL, NULL, NULL, '2019-11-12', '0', '2019-11-12', '2', '2019-11-12'),
(12, 290, NULL, 'Post Job', 1, NULL, ' ', 'java DevThis  job Is Posted By Sumit madne', '2019-11-12', NULL, NULL, NULL, '2019-11-12', '0', '2019-11-12', '1', '2019-11-12'),
(13, 51, '3', 'Jost Assigned', 2, 'Sanjay Mandi', '3', 'QA Automation  assigned to Sanjay Mandi By Sumit madne', '2019-11-12', NULL, NULL, NULL, '2019-11-12', '0', '2019-11-12', '2', '2019-11-12'),
(14, 244, NULL, 'Update Candidate', 2, 'TESTmohitkumar', ' ', 'TESTmohitkumarThis  Candidate Is Updated By Sumit madne', '2019-11-12', NULL, NULL, NULL, '2019-11-12', '0', '2019-11-12', '1', '2019-11-12'),
(15, 52, '1', 'Jost Assigned', 2, 'Sumit madne', '1', 'QA Automation  assigned to Sumit madne By Sumit madne', '2019-11-13', NULL, NULL, NULL, '2019-11-13', '0', '2019-11-13', '2', '2019-11-13'),
(16, 53, '1', 'Jost Assigned', 2, 'Sumit madne', '1', 'Devops Engineer  assigned to Sumit madne By Sumit madne', '2019-11-13', NULL, NULL, NULL, '2019-11-13', '0', '2019-11-13', '2', '2019-11-13'),
(17, 54, '1', 'Jost Assigned', 2, 'Sumit madne', '1', 'Java Developer  assigned to Sumit madne By Sumit madne', '2019-11-13', NULL, NULL, NULL, '2019-11-13', '0', '2019-11-13', '2', '2019-11-13'),
(18, 55, '1', 'Jost Assigned', 2, 'Sumit madne', '1', '14 :- Python Developer  assigned to Sumit madne By Sumit madne', '2019-11-13', NULL, NULL, NULL, '2019-11-13', '0', '2019-11-13', '2', '2019-11-13'),
(19, 56, '1', 'Jost Assigned', 2, 'Sumit madne', '1', '18 :- Teradata Developer  assigned to Sumit madne By Sumit madne', '2019-11-13', NULL, NULL, NULL, '2019-11-13', '0', '2019-11-13', '2', '2019-11-13'),
(20, 57, '1', 'Jost Assigned', 2, 'Sumit madne', '1', '45612-1 :- Devops Engineer  assigned to Sumit madne By Sumit madne', '2019-11-13', NULL, NULL, NULL, '2019-11-13', '0', '2019-11-13', '2', '2019-11-13'),
(21, 58, '1', 'Jost Assigned', 2, 'Sumit madne', '1', '  assigned to Sumit madne By Sumit madne', '2019-11-13', NULL, 'Zen- 15 : Python Developer', NULL, '2019-11-13', '0', '2019-11-13', '2', '2019-11-13'),
(22, 59, '1', 'Jost Assigned', 2, 'Sumit madne', '1', '  assigned to Sumit madne By Sumit madne', '2019-11-13', NULL, '45612-1 : QA Automation', NULL, '2019-11-13', '0', '2019-11-13', '2', '2019-11-13'),
(23, 60, '1', 'Jost Assigned', 2, 'Sumit madne', '1', '  ASSIGNED TO Sumit madne BY Sumit madne', '2019-11-13', NULL, 'PR007 : Onsite Coordinator', NULL, '2019-11-13', '0', '2019-11-13', '2', '2019-11-13'),
(24, 61, '1', 'Jost Assigned', 2, 'Sumit madne', '1', '  ASSIGNED TO Sumit madne BY Sumit madne', '2019-11-13', NULL, 'PR007 : Onsite Coordinator', NULL, '2019-11-13', '0', '2019-11-13', '2', '2019-11-13'),
(25, 115, NULL, 'Delete Job', 2, '{\"job_title\":\"UI Developer\"}', ' ', 'UI DeveloperThis Job is Delete  By Sumit madne', '2019-11-13', NULL, NULL, NULL, '2019-11-13', '0', '2019-11-13', '1', '2019-11-13'),
(26, 62, '1', 'Jost Assigned', 2, 'Sumit madne', '1', '  Assigned to Sumit madne By Sumit madne', '2019-11-13', NULL, 'PR007 : Python Developer', NULL, '2019-11-13', '0', '2019-11-13', '2', '2019-11-13'),
(27, 63, '1', 'Jost Assigned', 2, 'Sumit madne', '1', '  Assigned to Sumit madne By Sumit madne', '2019-11-13', NULL, '00007-1 : JAVA API Automation', NULL, '2019-11-13', '0', '2019-11-13', '2', '2019-11-13'),
(28, 163, '1', 'Jost Assigned', 2, 'Sumit madne', '1', '  Assigned to Sumit madne By Sumit madne', '2019-11-13', NULL, '45612-1 : QA Automation', NULL, '2019-11-13', '0', '2019-11-13', '2', '2019-11-13'),
(29, 244, '1', 'Jost Assigned', 2, 'Sumit madne', '1', '  Assigned to Sumit madne By Sumit madne', '2019-11-13', NULL, 'Zen-13 : Technical Writer', NULL, '2019-11-13', '0', '2019-11-13', '2', '2019-11-13'),
(30, 212, '1', 'Jost Assigned', 2, 'Sumit madne', '1', '  Assigned to Sumit madne', '2019-11-13', NULL, '181097-1 : Java Developer', ' By Sumit madne', '2019-11-13', '0', '2019-11-13', '2', '2019-11-13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_notification`
--
ALTER TABLE `tbl_notification`
  ADD UNIQUE KEY `notification_id` (`notification_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_notification`
--
ALTER TABLE `tbl_notification`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
