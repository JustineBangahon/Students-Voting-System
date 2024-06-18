-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 30, 2023 at 12:31 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `a_voting_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbladmin`
--

CREATE TABLE `tbladmin` (
  `admin_id` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `username` varchar(60) NOT NULL,
  `password` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbladmin`
--

INSERT INTO `tbladmin` (`admin_id`, `name`, `username`, `password`) VALUES
(1, 'admin', 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `tblcandidate`
--

CREATE TABLE `tblcandidate` (
  `candidate_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `candidateposition_id` int(11) NOT NULL,
  `party_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblcourse`
--

CREATE TABLE `tblcourse` (
  `course_id` int(11) NOT NULL,
  `course_initial` varchar(10) NOT NULL,
  `course_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblparty`
--

CREATE TABLE `tblparty` (
  `party_id` int(11) NOT NULL,
  `party_name` varchar(100) NOT NULL,
  `electiondate_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblstudent`
--

CREATE TABLE `tblstudent` (
  `student_id` int(11) NOT NULL,
  `student_usn` varchar(30) NOT NULL,
  `last_name` varchar(60) NOT NULL,
  `first_name` varchar(60) NOT NULL,
  `middle_name` varchar(60) NOT NULL,
  `voting_code` varchar(20) NOT NULL,
  `vote_status` char(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblvote`
--

CREATE TABLE `tblvote` (
  `vote_id` int(11) NOT NULL,
  `candidate_id` int(11) NOT NULL,
  `date_recorder` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_candidate_position`
--

CREATE TABLE `tbl_candidate_position` (
  `candidate_position_id` int(11) NOT NULL,
  `position_name` varchar(60) NOT NULL,
  `votes_allowed` int(5) NOT NULL,
  `allow_per_party` int(5) NOT NULL,
  `sort_order` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_election_date`
--

CREATE TABLE `tbl_election_date` (
  `election_date_id` int(11) NOT NULL,
  `election_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_student_description`
--

CREATE TABLE `tbl_student_description` (
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `year_level_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_vote_status`
--

CREATE TABLE `tbl_vote_status` (
  `vote_status_id` int(11) NOT NULL,
  `election_date_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_year_level`
--

CREATE TABLE `tbl_year_level` (
  `year_level_id` int(11) NOT NULL,
  `year_level_initial` varchar(11) NOT NULL,
  `year_level_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbladmin`
--
ALTER TABLE `tbladmin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `tblcandidate`
--
ALTER TABLE `tblcandidate`
  ADD PRIMARY KEY (`candidate_id`),
  ADD KEY `candidateposition_id` (`candidateposition_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `party_id` (`party_id`);

--
-- Indexes for table `tblcourse`
--
ALTER TABLE `tblcourse`
  ADD PRIMARY KEY (`course_id`);

--
-- Indexes for table `tblparty`
--
ALTER TABLE `tblparty`
  ADD PRIMARY KEY (`party_id`),
  ADD KEY `electiondate_id` (`electiondate_id`);

--
-- Indexes for table `tblstudent`
--
ALTER TABLE `tblstudent`
  ADD PRIMARY KEY (`student_id`);

--
-- Indexes for table `tblvote`
--
ALTER TABLE `tblvote`
  ADD PRIMARY KEY (`vote_id`),
  ADD KEY `candidate_id` (`candidate_id`);

--
-- Indexes for table `tbl_candidate_position`
--
ALTER TABLE `tbl_candidate_position`
  ADD PRIMARY KEY (`candidate_position_id`);

--
-- Indexes for table `tbl_election_date`
--
ALTER TABLE `tbl_election_date`
  ADD PRIMARY KEY (`election_date_id`),
  ADD UNIQUE KEY `unq_tbl_election_date_election_date` (`election_date`);

--
-- Indexes for table `tbl_student_description`
--
ALTER TABLE `tbl_student_description`
  ADD KEY `course_id` (`course_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `year_level_id` (`year_level_id`);

--
-- Indexes for table `tbl_vote_status`
--
ALTER TABLE `tbl_vote_status`
  ADD PRIMARY KEY (`vote_status_id`),
  ADD KEY `election_date_id` (`election_date_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `tbl_year_level`
--
ALTER TABLE `tbl_year_level`
  ADD PRIMARY KEY (`year_level_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbladmin`
--
ALTER TABLE `tbladmin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblcandidate`
--
ALTER TABLE `tblcandidate`
  MODIFY `candidate_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `tblcourse`
--
ALTER TABLE `tblcourse`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tblparty`
--
ALTER TABLE `tblparty`
  MODIFY `party_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `tblstudent`
--
ALTER TABLE `tblstudent`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tblvote`
--
ALTER TABLE `tblvote`
  MODIFY `vote_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_candidate_position`
--
ALTER TABLE `tbl_candidate_position`
  MODIFY `candidate_position_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `tbl_election_date`
--
ALTER TABLE `tbl_election_date`
  MODIFY `election_date_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `tbl_vote_status`
--
ALTER TABLE `tbl_vote_status`
  MODIFY `vote_status_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_year_level`
--
ALTER TABLE `tbl_year_level`
  MODIFY `year_level_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblcandidate`
--
ALTER TABLE `tblcandidate`
  ADD CONSTRAINT `tblcandidate_ibfk_1` FOREIGN KEY (`candidateposition_id`) REFERENCES `tbl_candidate_position` (`candidate_position_id`),
  ADD CONSTRAINT `tblcandidate_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `tblstudent` (`student_id`),
  ADD CONSTRAINT `tblcandidate_ibfk_3` FOREIGN KEY (`party_id`) REFERENCES `tblparty` (`party_id`);

--
-- Constraints for table `tblparty`
--
ALTER TABLE `tblparty`
  ADD CONSTRAINT `tblparty_ibfk_1` FOREIGN KEY (`electiondate_id`) REFERENCES `tbl_election_date` (`election_date_id`);

--
-- Constraints for table `tblvote`
--
ALTER TABLE `tblvote`
  ADD CONSTRAINT `tblvote_ibfk_1` FOREIGN KEY (`candidate_id`) REFERENCES `tblcandidate` (`candidate_id`);

--
-- Constraints for table `tbl_student_description`
--
ALTER TABLE `tbl_student_description`
  ADD CONSTRAINT `tbl_student_description_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `tblcourse` (`course_id`),
  ADD CONSTRAINT `tbl_student_description_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `tblstudent` (`student_id`),
  ADD CONSTRAINT `tbl_student_description_ibfk_3` FOREIGN KEY (`year_level_id`) REFERENCES `tbl_year_level` (`year_level_id`);

--
-- Constraints for table `tbl_vote_status`
--
ALTER TABLE `tbl_vote_status`
  ADD CONSTRAINT `tbl_vote_status_ibfk_1` FOREIGN KEY (`election_date_id`) REFERENCES `tbl_election_date` (`election_date_id`),
  ADD CONSTRAINT `tbl_vote_status_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `tblstudent` (`student_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
