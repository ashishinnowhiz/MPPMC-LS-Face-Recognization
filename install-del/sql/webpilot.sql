-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 15, 2024 at 10:16 AM
-- Server version: 8.3.0
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test_vmsb`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities_log`
--

DROP TABLE IF EXISTS `activities_log`;
CREATE TABLE IF NOT EXISTS `activities_log` (
  `activity_id` int NOT NULL AUTO_INCREMENT,
  `activity_type` enum('Login','Logout','View','Create','Update','Delete','Import','Export','Click','Sync') NOT NULL,
  `activity_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `activity_ip` varchar(22) NOT NULL,
  `activity_detail` text NOT NULL,
  `user_id` int NOT NULL,
  `activity_url` varchar(200) NOT NULL,
  `activity_data` text NOT NULL,
  `sync_status` tinyint NOT NULL,
  PRIMARY KEY (`activity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `allocation_log`
--

DROP TABLE IF EXISTS `allocation_log`;
CREATE TABLE IF NOT EXISTS `allocation_log` (
  `allocation_id` int NOT NULL AUTO_INCREMENT,
  `allocation_type` enum('Allocation','Unallocation') NOT NULL,
  `allocation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `paper_code` varchar(40) NOT NULL,
  `center_code` varchar(22) NOT NULL,
  `allocation_quantity` int NOT NULL,
  `allocation_by` int NOT NULL,
  `allocation_code` int NOT NULL,
  `allocation_status` enum('Active','Inactive') NOT NULL,
  `allocation_file` varchar(400) NOT NULL,
  `downloaded` int NOT NULL,
  `downloaded_data` int NOT NULL,
  `downloaded_files` int NOT NULL,
  `allocation_log_details` text NOT NULL,
  `allocation_sync_status` enum('Pending','Synced','Error','Unallocated') NOT NULL,
  `allocation_synced_files` int NOT NULL,
  `allocation_mode` enum('Offline','Online') NOT NULL DEFAULT 'Offline',
  PRIMARY KEY (`allocation_id`),
  UNIQUE KEY `allocation_id` (`allocation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

DROP TABLE IF EXISTS `attendance`;
CREATE TABLE IF NOT EXISTS `attendance` (
  `attendance_id` int NOT NULL AUTO_INCREMENT,
  `attendance_user` varchar(40) NOT NULL,
  `attendance_date` date NOT NULL,
  `attendance_login` time DEFAULT NULL,
  `attendance_logout` time DEFAULT NULL,
  `attendance_checked` int NOT NULL,
  `under_checked` int NOT NULL,
  `attendance_rejected` int NOT NULL,
  `attendance_hours` time DEFAULT NULL,
  `sync_status` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`attendance_id`),
  UNIQUE KEY `attendance_user_date` (`attendance_user`,`attendance_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `centers`
--

DROP TABLE IF EXISTS `centers`;
CREATE TABLE IF NOT EXISTS `centers` (
  `center_id` int NOT NULL AUTO_INCREMENT,
  `region_code` varchar(40) NOT NULL,
  `center_code` varchar(40) NOT NULL,
  `center_name` varchar(100) NOT NULL,
  `center_address` varchar(400) NOT NULL,
  `center_phone` varchar(22) NOT NULL,
  `center_contact_person` varchar(100) NOT NULL,
  `center_email` varchar(100) NOT NULL,
  `center_contact_email` varchar(100) NOT NULL,
  `center_created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `center_updated_time` timestamp NULL DEFAULT NULL,
  `center_created_by` int NOT NULL,
  `center_updated_by` int NOT NULL,
  `center_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`center_code`),
  UNIQUE KEY `center_id` (`center_id`),
  KEY `region_code` (`region_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
CREATE TABLE IF NOT EXISTS `courses` (
  `course_id` bigint NOT NULL AUTO_INCREMENT,
  `course_name` varchar(500) NOT NULL,
  `course_code` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `parent` varchar(25) NOT NULL,
  `course_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `course_updated_time` timestamp NOT NULL,
  `course_updated_by` bigint NOT NULL,
  `course_status` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `course_created_by` bigint NOT NULL,
  `course_deleted` bigint NOT NULL,
  PRIMARY KEY (`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `eval`
--

DROP TABLE IF EXISTS `eval`;
CREATE TABLE IF NOT EXISTS `eval` (
  `eval_id` int NOT NULL AUTO_INCREMENT,
  `sheet_file` varchar(60) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `user_role` varchar(50) NOT NULL,
  `eval_role` varchar(50) NOT NULL,
  `eval_page` int NOT NULL,
  `eval_action` enum('check','cross','question','comment','score','rectangle','ellipse') NOT NULL,
  `eval_details` text NOT NULL,
  `eval_que_index` int NOT NULL,
  `eval_status` enum('Done','Undone','NA','') NOT NULL DEFAULT 'Done',
  `eval_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `eval_remarks` text NOT NULL,
  PRIMARY KEY (`eval_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `evaluation`
--

DROP TABLE IF EXISTS `evaluation`;
CREATE TABLE IF NOT EXISTS `evaluation` (
  `evaluation_id` int NOT NULL AUTO_INCREMENT,
  `sheet_file` varchar(200) NOT NULL,
  `sheet_json_marks` text NOT NULL,
  `evaluation_marks` varbinary(150) NOT NULL,
  `examiner_username` varchar(50) NOT NULL,
  `evaluator_username` varchar(40) NOT NULL,
  `evaluation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `evaluation_date` date DEFAULT NULL,
  `sheet_assign_time` timestamp NULL DEFAULT NULL,
  `sync_status` tinyint(1) NOT NULL DEFAULT '0',
  `evaluation_type` enum('Regular','NAVerification','NACorrection') NOT NULL DEFAULT 'Regular',
  `evaluation_na_verified` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`evaluation_id`),
  UNIQUE KEY `eval_file` (`sheet_file`,`evaluator_username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `evaluators`
--

DROP TABLE IF EXISTS `evaluators`;
CREATE TABLE IF NOT EXISTS `evaluators` (
  `evaluator_id` int NOT NULL AUTO_INCREMENT,
  `evaluator_email` varchar(100) NOT NULL,
  `evaluator_name` varchar(100) NOT NULL,
  `evaluator_designation` varchar(40) NOT NULL,
  `evaluator_phone` varchar(22) NOT NULL,
  `evaluator_address` varchar(500) NOT NULL,
  `center_code` varchar(100) NOT NULL,
  `subject_code` varchar(40) NOT NULL,
  `course_code` varchar(25) NOT NULL,
  `medium_code` varchar(40) NOT NULL,
  `evaluator_username` varchar(200) NOT NULL,
  `evaluator_password` varchar(100) NOT NULL,
  `center_id` int NOT NULL,
  `evaluator_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `evaluator_role` enum('Evaluation','Head') NOT NULL DEFAULT 'Evaluation',
  `examiner_username` varchar(100) NOT NULL,
  `evaluator_updated_time` timestamp NULL DEFAULT NULL,
  `evaluator_updated_by` int NOT NULL,
  `evaluator_created_by` int NOT NULL,
  `evaluator_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `evaluator_daily_limit` int NOT NULL,
  `evaluator_remarks` text NOT NULL,
  PRIMARY KEY (`evaluator_username`),
  UNIQUE KEY `evaluator_id` (`evaluator_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `examiners`
--

DROP TABLE IF EXISTS `examiners`;
CREATE TABLE IF NOT EXISTS `examiners` (
  `examiner_id` int NOT NULL AUTO_INCREMENT,
  `examiner_name` varchar(200) NOT NULL,
  `examiner_org` varchar(100) NOT NULL,
  `examiner_designation` varchar(40) NOT NULL,
  `examiner_email` varchar(100) NOT NULL,
  `examiner_phone` varchar(22) NOT NULL,
  `examiner_username` varchar(100) NOT NULL,
  `examiner_password` varchar(100) NOT NULL,
  `center_code` varchar(40) NOT NULL,
  `center_id` int NOT NULL,
  `examiner_created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `examiner_created_by` int NOT NULL,
  `examiner_updated_time` timestamp NULL DEFAULT NULL,
  `examiner_updated_by` int NOT NULL,
  `examiner_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `course_code` varchar(25) NOT NULL,
  `subject_code` varchar(25) NOT NULL,
  PRIMARY KEY (`examiner_username`),
  UNIQUE KEY `examiner_id` (`examiner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `examiner_course_subject`
--

DROP TABLE IF EXISTS `examiner_course_subject`;
CREATE TABLE IF NOT EXISTS `examiner_course_subject` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `examiner_username` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `course_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `subject_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `subject_status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `exams`
--

DROP TABLE IF EXISTS `exams`;
CREATE TABLE IF NOT EXISTS `exams` (
  `exam_id` int NOT NULL AUTO_INCREMENT,
  `exam_name` varchar(100) NOT NULL,
  `exam_code` varchar(40) NOT NULL,
  `exam_description` text NOT NULL,
  `exam_result_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `exam_status` enum('Active','Inactive') NOT NULL DEFAULT 'Inactive',
  `examiner_created_time` timestamp NULL DEFAULT NULL,
  `exam_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `exam_created_by` int NOT NULL,
  `exam_updated_time` timestamp NULL DEFAULT NULL,
  `exam_updated_by` int NOT NULL,
  PRIMARY KEY (`exam_id`),
  UNIQUE KEY `exam_code` (`exam_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `file_error_log`
--

DROP TABLE IF EXISTS `file_error_log`;
CREATE TABLE IF NOT EXISTS `file_error_log` (
  `file_error_log_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `allocation_id` int NOT NULL,
  `script_name` varchar(80) NOT NULL,
  `error_type` varchar(10) NOT NULL,
  `error_log` text NOT NULL,
  `status` varchar(20) NOT NULL,
  `error_time` datetime NOT NULL,
  `error_ip` varchar(20) NOT NULL,
  `error_url` varchar(255) NOT NULL,
  PRIMARY KEY (`file_error_log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `json_sync`
--

DROP TABLE IF EXISTS `json_sync`;
CREATE TABLE IF NOT EXISTS `json_sync` (
  `json_id` int NOT NULL AUTO_INCREMENT,
  `json_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `json_synced` tinyint NOT NULL DEFAULT '0',
  `json_added_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`json_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `limit_updates`
--

DROP TABLE IF EXISTS `limit_updates`;
CREATE TABLE IF NOT EXISTS `limit_updates` (
  `limit_update_id` int NOT NULL AUTO_INCREMENT,
  `limit_updated_value` int NOT NULL,
  `limit_update_remark` varchar(200) NOT NULL,
  `evaluator_username` varchar(40) NOT NULL,
  `limit_updated_by` int NOT NULL,
  `limit_update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `sync_status` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`limit_update_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `login_user`
--

DROP TABLE IF EXISTS `login_user`;
CREATE TABLE IF NOT EXISTS `login_user` (
  `login_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_time` timestamp NOT NULL,
  `ip_address` varchar(22) NOT NULL,
  `user_login_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`login_id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `marker_subjects`
--

DROP TABLE IF EXISTS `marker_subjects`;
CREATE TABLE IF NOT EXISTS `marker_subjects` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `evaluator_username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `subject_code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `course_code` varchar(25) NOT NULL,
  `subject_status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mediums`
--

DROP TABLE IF EXISTS `mediums`;
CREATE TABLE IF NOT EXISTS `mediums` (
  `medium_id` int NOT NULL AUTO_INCREMENT,
  `medium_name` varchar(100) NOT NULL,
  `medium_code` varchar(10) NOT NULL,
  `medium_created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `medium_created_by` int NOT NULL,
  `medium_updated_time` timestamp NULL DEFAULT NULL,
  `medium_updated_by` int NOT NULL,
  `medium_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`medium_id`),
  UNIQUE KEY `medium_code` (`medium_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `papers`
--

DROP TABLE IF EXISTS `papers`;
CREATE TABLE IF NOT EXISTS `papers` (
  `paper_id` int NOT NULL AUTO_INCREMENT,
  `paper_code` varchar(40) NOT NULL,
  `exam_id` int NOT NULL,
  `exam_code` varchar(40) NOT NULL,
  `paper_set` varchar(22) NOT NULL,
  `region_id` int NOT NULL,
  `region_code` varchar(100) NOT NULL,
  `subject_id` int NOT NULL,
  `subject_code` varchar(15) NOT NULL,
  `course_code` varchar(15) NOT NULL,
  `medium_code` varchar(11) NOT NULL,
  `medium_name` varchar(40) NOT NULL,
  `paper_sets` text NOT NULL,
  `paper_sheet_pages` int NOT NULL,
  `paper_supp_pages` int NOT NULL,
  `paper_model_question` varchar(100) NOT NULL,
  `paper_model_answer` varchar(100) NOT NULL,
  `paper_total_marks` int NOT NULL,
  `paper_all_marks` int NOT NULL,
  `paper_passing_marks` int NOT NULL,
  `paper_marking_scheme` varchar(200) NOT NULL,
  `paper_created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `paper_created_by` int NOT NULL,
  `paper_updated_time` timestamp NULL DEFAULT NULL,
  `paper_updated_by` int NOT NULL,
  `paper_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `marking_scheme_json` text NOT NULL,
  `min_eval_time` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`paper_code`),
  UNIQUE KEY `paper_code` (`paper_code`),
  UNIQUE KEY `paper_id` (`paper_id`),
  KEY `region_code` (`region_code`),
  KEY `subject_code` (`subject_code`),
  KEY `medium_code` (`medium_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `regions`
--

DROP TABLE IF EXISTS `regions`;
CREATE TABLE IF NOT EXISTS `regions` (
  `region_id` int NOT NULL AUTO_INCREMENT,
  `region_name` varchar(100) NOT NULL,
  `region_code` varchar(100) NOT NULL,
  `region_description` text NOT NULL,
  `region_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `region_created_by` int NOT NULL,
  `region_updated_time` timestamp NULL DEFAULT NULL,
  `region_updated_by` int NOT NULL,
  `region_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`region_code`),
  UNIQUE KEY `region_code` (`region_code`),
  UNIQUE KEY `region_id` (`region_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `results_log`
--

DROP TABLE IF EXISTS `results_log`;
CREATE TABLE IF NOT EXISTS `results_log` (
  `result_id` int NOT NULL AUTO_INCREMENT,
  `result_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `result_count` int NOT NULL,
  `result_by` int NOT NULL,
  `result_date` date NOT NULL,
  PRIMARY KEY (`result_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `scan_log`
--

DROP TABLE IF EXISTS `scan_log`;
CREATE TABLE IF NOT EXISTS `scan_log` (
  `scan_id` int NOT NULL AUTO_INCREMENT,
  `file` varchar(200) NOT NULL,
  `region` varchar(40) NOT NULL,
  `error` enum('error','duplicate','technical','none') NOT NULL DEFAULT 'none',
  `message` varchar(200) NOT NULL,
  `success` tinyint(1) NOT NULL DEFAULT '0',
  `log_id` int NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`scan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `setting_id` int NOT NULL AUTO_INCREMENT,
  `setting_for` varchar(40) NOT NULL,
  `setting_json` text NOT NULL,
  `setting_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `setting_updated` timestamp NULL DEFAULT NULL,
  `setting_updated_by` int NOT NULL,
  PRIMARY KEY (`setting_id`),
  UNIQUE KEY `setting_for` (`setting_for`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `settings` (`setting_id`, `setting_for`, `setting_json`, `setting_time`, `setting_updated`, `setting_updated_by`) VALUES
(8, 'evaluation', '{\"status\":\"1\"}', '2023-05-22 04:39:30', '2023-05-31 12:11:34', 1);

INSERT INTO `settings` (`setting_id`, `setting_for`, `setting_json`, `setting_time`, `setting_updated`, `setting_updated_by`) VALUES
(9, 'general', '{\"status\":\"ftp\"}', '2023-05-22 04:39:30', '2023-05-31 12:11:34', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sheets`
--

DROP TABLE IF EXISTS `sheets`;
CREATE TABLE IF NOT EXISTS `sheets` (
  `sheet_id` int NOT NULL AUTO_INCREMENT,
  `paper_id` int NOT NULL,
  `paper_set` varchar(20) NOT NULL,
  `sheet_file` varchar(40) NOT NULL,
  `sheet_url` varchar(600) NOT NULL,
  `paper_code` varchar(40) NOT NULL,
  `sheet_marks` int NOT NULL,
  `examiner_id` varchar(25) NOT NULL,
  `examiner_username` varchar(40) NOT NULL,
  `evaluator_code` varchar(40) NOT NULL,
  `center_id` int NOT NULL,
  `medium_code` varchar(40) NOT NULL,
  `region_code` varchar(40) NOT NULL,
  `subject_code` varchar(40) NOT NULL,
  `roll_no` int NOT NULL,
  `exam_code` int NOT NULL,
  `center_code` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `sheet_status` enum('Pending','Assigned','Rejected','Checked','Rechecked','Unallocated','ReMarking') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'Pending',
  `sheet_eval` varchar(10) DEFAULT NULL,
  `sheet_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sheet_updated_time` timestamp NULL DEFAULT NULL,
  `sheet_assign_time` timestamp NULL DEFAULT NULL,
  `sheet_updated_by` int NOT NULL,
  `sheet_json_marks` text NOT NULL,
  `evaluation_marks` varbinary(150) NOT NULL,
  `head_evaluation_marks` varbinary(150) NOT NULL,
  `final_marks` varbinary(150) NOT NULL,
  `head_json_marks` text NOT NULL,
  `re_evaluation_marks` varbinary(150) NOT NULL,
  `evaluation_time` timestamp NULL DEFAULT NULL,
  `recheck_time` timestamp NULL DEFAULT NULL,
  `evaluation_date` date DEFAULT NULL,
  `sheet_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `allocation_id` int NOT NULL,
  `sheet_remarks` text NOT NULL,
  `reject_reason` varchar(100) NOT NULL,
  `sheet_synced` tinyint(1) NOT NULL DEFAULT '0',
  `sheet_processed` tinyint(1) DEFAULT '0',
  `recheck` tinyint(1) NOT NULL,
  `recheck_assign_time` timestamp NULL DEFAULT NULL,
  `recheck_remarks` varchar(255) NOT NULL,
  `section_a` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'Pending' COMMENT 'section field',
  `section_b` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'Pending' COMMENT 'section field',
  `section_c` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'Pending' COMMENT 'section field',
  `marker_evaluation_marks` varbinary(150) NOT NULL COMMENT 'new marker',
  `remark_assign_time` time DEFAULT NULL COMMENT 'new marker',
  `remark_remarks` varchar(225) NOT NULL COMMENT 'new marker',
  `marker_username` varchar(50) NOT NULL COMMENT 'new marker',
  `remark_time` timestamp NULL DEFAULT NULL COMMENT 'new marker',
  `marker_json_marks` text NOT NULL COMMENT 'new marker',
  `remark` tinyint(1) NOT NULL COMMENT 'new marker',
  PRIMARY KEY (`sheet_id`),
  UNIQUE KEY `sheet_file` (`sheet_file`,`allocation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sheet_view`
--

DROP TABLE IF EXISTS `sheet_view`;
CREATE TABLE IF NOT EXISTS `sheet_view` (
  `sheet_view_id` bigint NOT NULL AUTO_INCREMENT,
  `sheet_file` varchar(40) NOT NULL,
  `sheet_checked` varchar(200) NOT NULL,
  `sheet_encode` varchar(200) NOT NULL,
  `sheet_view_count` int NOT NULL,
  `sheet_view_date` timestamp NOT NULL,
  PRIMARY KEY (`sheet_view_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

DROP TABLE IF EXISTS `subjects`;
CREATE TABLE IF NOT EXISTS `subjects` (
  `subject_id` int NOT NULL AUTO_INCREMENT,
  `course_code` varchar(50) NOT NULL,
  `subject_name` varchar(100) NOT NULL,
  `subject_code` varchar(50) NOT NULL,
  `subject_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `subject_updated_time` timestamp NULL DEFAULT NULL,
  `subject_updated_by` int NOT NULL,
  `subject_status` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active',
  `subject_created_by` int NOT NULL,
  `subject_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`subject_code`),
  UNIQUE KEY `subject_id` (`subject_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `user_name` varchar(100) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_password` varchar(200) NOT NULL,
  `user_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `ip_address` varchar(22) NOT NULL,
  `user_login_time` timestamp NULL DEFAULT NULL,
  `user_login_ip` varchar(22) NOT NULL,
  `user_login_attempts` int NOT NULL DEFAULT '0',
  `user_updated_time` timestamp NULL DEFAULT NULL,
  `user_role` enum('Admin','Coordinator','Head_Evaluator','Evaluator','Head_Marker') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `center_code` varchar(40) NOT NULL,
  `user_token` varchar(40) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_id` (`user_id`),
  KEY `center_code` (`center_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `user_login`
--

DROP TABLE IF EXISTS `user_login`;
CREATE TABLE IF NOT EXISTS `user_login` (
  `user_login_id` int NOT NULL AUTO_INCREMENT,
  `user_name` varchar(100) NOT NULL,
  `user_id` int NOT NULL,
  `user_role` enum('Admin','Coordinator','Head_Evaluator','Evaluator') DEFAULT NULL,
  `center_code` varchar(50) NOT NULL,
  `activity` varchar(50) NOT NULL,
  `user_time` timestamp NULL DEFAULT NULL,
  `login_at` varchar(20) DEFAULT NULL,
  `ip_address` varchar(100) NOT NULL,
  PRIMARY KEY (`user_login_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_work`
--

DROP TABLE IF EXISTS `user_work`;
CREATE TABLE IF NOT EXISTS `user_work` (
  `user_work_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `user_login` timestamp NULL DEFAULT NULL,
  `user_logout` timestamp NULL DEFAULT NULL,
  `date` timestamp NULL DEFAULT NULL,
  `hours_worked` time NOT NULL,
  PRIMARY KEY (`user_work_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `centers`
--
ALTER TABLE `centers`
  ADD CONSTRAINT `centers_ibfk_1` FOREIGN KEY (`region_code`) REFERENCES `regions` (`region_code`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
