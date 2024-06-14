-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 21, 2024 at 06:58 PM
-- Server version: 5.7.15-log
-- PHP Version: 5.6.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aplicatie_alegeri`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_elections`
--

CREATE TABLE `tbl_elections` (
  `election_id` smallint(5) UNSIGNED NOT NULL,
  `election_name` varchar(255) NOT NULL,
  `election_description` text,
  `number_of_users` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '1 for active/0 for inactive (in front-end)',
  `sort_order` smallint(5) UNSIGNED DEFAULT '0',
  `starting_date` datetime DEFAULT '1970-01-01 00:00:00',
  `closing_date` datetime DEFAULT '1970-01-01 00:00:00',
  `date_added` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `date_last_modified` datetime NOT NULL DEFAULT '1970-01-01 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 0 kB' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_elections_ballots`
--

CREATE TABLE `tbl_elections_ballots` (
  `ballot_id` mediumint(8) UNSIGNED NOT NULL,
  `election_id` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `ballot_type_id` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `ballot_name` varchar(255) NOT NULL,
  `ballot_description` text,
  `ballot_hint` text,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '1 for active/0 for inactive (in front-end)',
  `sort_order` smallint(5) UNSIGNED DEFAULT '0',
  `date_added` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `date_last_modified` datetime NOT NULL DEFAULT '1970-01-01 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 0 kB' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_elections_ballots_options`
--

CREATE TABLE `tbl_elections_ballots_options` (
  `ballot_option_id` int(10) UNSIGNED NOT NULL,
  `ballot_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `ballot_option_name` varchar(255) NOT NULL,
  `ballot_option_description` text,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '1 for active/0 for inactive (in front-end)',
  `sort_order` smallint(5) UNSIGNED DEFAULT '0',
  `date_added` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `date_last_modified` datetime NOT NULL DEFAULT '1970-01-01 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 0 kB' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_elections_ballots_options_to_users`
--

CREATE TABLE `tbl_elections_ballots_options_to_users` (
  `record_id` int(10) UNSIGNED NOT NULL,
  `election_id` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `ballot_id` mediumint(8) UNSIGNED DEFAULT '0',
  `ballot_option_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `user_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 0 kB' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_elections_ballots_to_users`
--

CREATE TABLE `tbl_elections_ballots_to_users` (
  `record_id` int(10) UNSIGNED NOT NULL,
  `election_id` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `ballot_id` mediumint(8) UNSIGNED DEFAULT '0',
  `user_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '1 - All ballots are validated\r\n0 - Contains at least one not validated ballot'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 0 kB' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_elections_ballots_types`
--

CREATE TABLE `tbl_elections_ballots_types` (
  `ballot_type_id` tinyint(3) UNSIGNED NOT NULL,
  `ballot_type_name` varchar(255) NOT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '1 for active/0 for inactive (in front-end)',
  `sort_order` smallint(5) UNSIGNED DEFAULT '0',
  `date_added` datetime NOT NULL DEFAULT '1970-01-01 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 0 kB' ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `tbl_elections_ballots_types`
--

INSERT INTO `tbl_elections_ballots_types` (`ballot_type_id`, `ballot_type_name`, `status`, `sort_order`, `date_added`) VALUES
(1, 'O singură variantă permisă (1)', 1, 100, '2024-04-03 01:15:55'),
(2, 'Una sau mai multe variante permise (1+)', 1, 95, '2024-04-03 01:16:10');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_elections_to_users`
--

CREATE TABLE `tbl_elections_to_users` (
  `record_id` int(10) UNSIGNED NOT NULL,
  `election_id` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `user_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `date_added` datetime NOT NULL DEFAULT '1970-01-01 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 0 kB' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_elections_validation_commissions`
--

CREATE TABLE `tbl_elections_validation_commissions` (
  `record_id` int(10) UNSIGNED NOT NULL,
  `election_id` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `user_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `date_added` datetime NOT NULL DEFAULT '1970-01-01 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 0 kB' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `user_id` mediumint(8) UNSIGNED NOT NULL,
  `user_name` varchar(32) CHARACTER SET ascii NOT NULL,
  `user_email` varchar(64) CHARACTER SET ascii DEFAULT '',
  `user_password` char(32) NOT NULL DEFAULT '',
  `user_first_name` varchar(32) NOT NULL DEFAULT '',
  `user_last_name` varchar(32) NOT NULL DEFAULT '',
  `user_phones` varchar(64) CHARACTER SET ascii DEFAULT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_last_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 0 kB';

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`user_id`, `user_name`, `user_email`, `user_password`, `user_first_name`, `user_last_name`, `user_phones`, `status`, `date_added`, `date_last_modified`) VALUES
(1, 'user1', 'user1@domain.com', 'e10adc3949ba59abbe56e057f20f883e', 'User 1 (nume)', 'User 1 (prenume)', '', 1, '2024-05-09 09:24:28', '0000-00-00 00:00:00'),
(2, 'user2', 'user2@domain.com', 'e10adc3949ba59abbe56e057f20f883e', 'User 2 (nume)', 'User 2 (prenume)', NULL, 1, '2024-05-09 09:24:28', '0000-00-00 00:00:00'),
(3, 'user3', 'user3@domain.com', 'e10adc3949ba59abbe56e057f20f883e', 'User 3 (nume)', 'User 3 (prenume)', NULL, 1, '2024-05-09 09:24:28', '0000-00-00 00:00:00'),
(4, 'user4', 'user4@domain.com', 'e10adc3949ba59abbe56e057f20f883e', 'User 4 (nume)', 'User 4 (prenume)', NULL, 1, '2024-05-09 09:24:28', '0000-00-00 00:00:00'),
(5, 'user5', 'user5@domain.com', 'e10adc3949ba59abbe56e057f20f883e', 'User 5 (nume)', 'User 5 (prenume)', NULL, 1, '2024-05-09 09:24:28', '0000-00-00 00:00:00'),
(6, 'user6', 'user6@domain.com', 'e10adc3949ba59abbe56e057f20f883e', 'User 6 (nume)', 'User 6 (prenume)', NULL, 1, '2024-05-09 09:24:28', '0000-00-00 00:00:00'),
(7, 'user7', 'user7@domain.com', 'e10adc3949ba59abbe56e057f20f883e', 'User 7 (nume)', 'User 7 (prenume)', NULL, 1, '2024-05-09 09:24:28', '0000-00-00 00:00:00'),
(8, 'user8', 'user8@domain.com', 'e10adc3949ba59abbe56e057f20f883e', 'User 8 (nume)', 'User 8 (prenume)', NULL, 1, '2024-05-09 09:24:28', '0000-00-00 00:00:00'),
(9, 'user9', 'user9@domain.com', 'e10adc3949ba59abbe56e057f20f883e', 'User 9 (nume)', 'User 9 (prenume)', NULL, 1, '2024-05-09 09:24:28', '0000-00-00 00:00:00'),
(10, 'user10', 'user10@domain.com', 'e10adc3949ba59abbe56e057f20f883e', 'User 10 (nume)', 'User 10 (prenume)', '1234567890', 1, '2024-05-09 09:24:28', '0000-00-00 00:00:00'),
(11, 'user11', 'user11@domain.com', 'e10adc3949ba59abbe56e057f20f883e', 'User 11 (nume)', 'User 11 (prenume)', NULL, 1, '2024-05-09 09:24:28', '0000-00-00 00:00:00'),
(12, 'user12', 'user12@domain.com', 'e10adc3949ba59abbe56e057f20f883e', 'User 12 (nume)', 'User 12 (prenume)', NULL, 1, '2024-05-09 09:24:28', '0000-00-00 00:00:00'),
(13, 'user13', 'user13@domain.com', 'e10adc3949ba59abbe56e057f20f883e', 'User 13 (nume)', 'User 13 (prenume)', NULL, 1, '2024-05-09 09:24:28', '0000-00-00 00:00:00'),
(14, 'user14', 'user14@domain.com', 'e10adc3949ba59abbe56e057f20f883e', 'User 14 (nume)', 'User 14 (prenume)', NULL, 1, '2024-05-09 09:24:28', '0000-00-00 00:00:00'),
(15, 'user15', 'user15@domain.com', 'e10adc3949ba59abbe56e057f20f883e', 'User 15 (nume)', 'User 15 (prenume)', NULL, 1, '2024-05-09 09:24:28', '0000-00-00 00:00:00'),
(16, 'user16', 'user16@domain.com', 'e10adc3949ba59abbe56e057f20f883e', 'User 16 (nume)', 'User 16 (prenume)', NULL, 1, '2024-05-09 09:24:28', '0000-00-00 00:00:00'),
(17, 'user17', 'user17@domain.com', 'e10adc3949ba59abbe56e057f20f883e', 'User 17 (nume)', 'User 17 (prenume)', NULL, 1, '2024-05-09 09:24:28', '0000-00-00 00:00:00'),
(18, 'user18', 'user18@domain.com', 'e10adc3949ba59abbe56e057f20f883e', 'User 18 (nume)', 'User 18 (prenume)', NULL, 1, '2024-05-09 09:24:28', '0000-00-00 00:00:00'),
(19, 'user19', 'user19@domain.com', 'e10adc3949ba59abbe56e057f20f883e', 'User 19 (nume)', 'User 19 (prenume)', NULL, 1, '2024-05-09 09:24:28', '0000-00-00 00:00:00'),
(20, 'user20', 'user20@domain.com', 'e10adc3949ba59abbe56e057f20f883e', 'User 20 (nume)', 'User 20 (prenume)', NULL, 1, '2024-05-09 09:24:28', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users_admin`
--

CREATE TABLE `tbl_users_admin` (
  `user_id` tinyint(3) UNSIGNED NOT NULL,
  `user_role_id` tinyint(3) UNSIGNED NOT NULL COMMENT '1. admin;\r\n2. validator',
  `user_name` varchar(32) NOT NULL DEFAULT '',
  `user_password` char(32) CHARACTER SET ascii NOT NULL DEFAULT '',
  `user_email` varchar(64) CHARACTER SET ascii NOT NULL DEFAULT '',
  `user_full_name` varchar(32) NOT NULL DEFAULT '',
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_last_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 0 kB';

--
-- Dumping data for table `tbl_users_admin`
--

INSERT INTO `tbl_users_admin` (`user_id`, `user_role_id`, `user_name`, `user_password`, `user_email`, `user_full_name`, `date_added`, `date_last_modified`) VALUES
(1, 1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', '', 'Administrator', '2024-05-01 10:25:50', '0000-00-00 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_elections`
--
ALTER TABLE `tbl_elections`
  ADD PRIMARY KEY (`election_id`) USING BTREE;

--
-- Indexes for table `tbl_elections_ballots`
--
ALTER TABLE `tbl_elections_ballots`
  ADD PRIMARY KEY (`ballot_id`) USING BTREE,
  ADD KEY `ballot_id_election_id_idx` (`ballot_id`,`election_id`) USING BTREE,
  ADD KEY `ballot_type_id_election_id_ballot_id_idx` (`ballot_type_id`,`election_id`,`ballot_id`) USING BTREE,
  ADD KEY `fk_tbl_elections_ballots_election_id` (`election_id`);

--
-- Indexes for table `tbl_elections_ballots_options`
--
ALTER TABLE `tbl_elections_ballots_options`
  ADD PRIMARY KEY (`ballot_option_id`) USING BTREE,
  ADD KEY `ballot_option_id_ballot_id_idx` (`ballot_option_id`,`ballot_id`) USING BTREE;

--
-- Indexes for table `tbl_elections_ballots_options_to_users`
--
ALTER TABLE `tbl_elections_ballots_options_to_users`
  ADD PRIMARY KEY (`record_id`) USING BTREE,
  ADD UNIQUE KEY `election_id_ballot_id_ballot_option_id_user_id_idx` (`election_id`,`ballot_id`,`user_id`,`ballot_option_id`) USING BTREE,
  ADD KEY `fk_tbl_elections_ballots_options_to_users_ballot_id` (`ballot_id`),
  ADD KEY `fk_tbl_elections_ballots_options_to_users_user_id` (`user_id`);

--
-- Indexes for table `tbl_elections_ballots_to_users`
--
ALTER TABLE `tbl_elections_ballots_to_users`
  ADD PRIMARY KEY (`record_id`) USING BTREE,
  ADD UNIQUE KEY `election_id_ballot_id_user_id_idx` (`election_id`,`ballot_id`,`user_id`),
  ADD KEY `fk_tbl_elections_ballots_to_users_ballot_id` (`ballot_id`),
  ADD KEY `fk_tbl_elections_ballots_to_users_user_id` (`user_id`);

--
-- Indexes for table `tbl_elections_ballots_types`
--
ALTER TABLE `tbl_elections_ballots_types`
  ADD PRIMARY KEY (`ballot_type_id`) USING BTREE;

--
-- Indexes for table `tbl_elections_to_users`
--
ALTER TABLE `tbl_elections_to_users`
  ADD PRIMARY KEY (`record_id`) USING BTREE,
  ADD UNIQUE KEY `election_id_user_id_idx` (`election_id`,`user_id`),
  ADD KEY `fk_tbl_elections_to_users_user_id` (`user_id`);

--
-- Indexes for table `tbl_elections_validation_commissions`
--
ALTER TABLE `tbl_elections_validation_commissions`
  ADD PRIMARY KEY (`record_id`) USING BTREE,
  ADD UNIQUE KEY `election_id_to_user_id_new` (`election_id`,`user_id`) USING BTREE,
  ADD KEY `fk_tbl_elections_validation_commissions_user_id` (`user_id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_name` (`user_name`);

--
-- Indexes for table `tbl_users_admin`
--
ALTER TABLE `tbl_users_admin`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_elections`
--
ALTER TABLE `tbl_elections`
  MODIFY `election_id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_elections_ballots`
--
ALTER TABLE `tbl_elections_ballots`
  MODIFY `ballot_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_elections_ballots_options`
--
ALTER TABLE `tbl_elections_ballots_options`
  MODIFY `ballot_option_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_elections_ballots_options_to_users`
--
ALTER TABLE `tbl_elections_ballots_options_to_users`
  MODIFY `record_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_elections_ballots_to_users`
--
ALTER TABLE `tbl_elections_ballots_to_users`
  MODIFY `record_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_elections_ballots_types`
--
ALTER TABLE `tbl_elections_ballots_types`
  MODIFY `ballot_type_id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tbl_elections_to_users`
--
ALTER TABLE `tbl_elections_to_users`
  MODIFY `record_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_elections_validation_commissions`
--
ALTER TABLE `tbl_elections_validation_commissions`
  MODIFY `record_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `tbl_users_admin`
--
ALTER TABLE `tbl_users_admin`
  MODIFY `user_id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_elections_ballots`
--
ALTER TABLE `tbl_elections_ballots`
  ADD CONSTRAINT `fk_tbl_elections_ballots_ballot_type_id` FOREIGN KEY (`ballot_type_id`) REFERENCES `tbl_elections_ballots_types` (`ballot_type_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tbl_elections_ballots_election_id` FOREIGN KEY (`election_id`) REFERENCES `tbl_elections` (`election_id`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_elections_ballots_options_to_users`
--
ALTER TABLE `tbl_elections_ballots_options_to_users`
  ADD CONSTRAINT `fk_tbl_elections_ballots_options_to_users_ballot_id` FOREIGN KEY (`ballot_id`) REFERENCES `tbl_elections_ballots` (`ballot_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tbl_elections_ballots_options_to_users_election_id` FOREIGN KEY (`election_id`) REFERENCES `tbl_elections` (`election_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tbl_elections_ballots_options_to_users_user_id` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_elections_ballots_to_users`
--
ALTER TABLE `tbl_elections_ballots_to_users`
  ADD CONSTRAINT `fk_tbl_elections_ballots_to_users_ballot_id` FOREIGN KEY (`ballot_id`) REFERENCES `tbl_elections_ballots` (`ballot_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tbl_elections_ballots_to_users_election_id` FOREIGN KEY (`election_id`) REFERENCES `tbl_elections` (`election_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tbl_elections_ballots_to_users_user_id` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_elections_to_users`
--
ALTER TABLE `tbl_elections_to_users`
  ADD CONSTRAINT `fk_tbl_elections_to_users_election_id` FOREIGN KEY (`election_id`) REFERENCES `tbl_elections` (`election_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tbl_elections_to_users_user_id` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_elections_validation_commissions`
--
ALTER TABLE `tbl_elections_validation_commissions`
  ADD CONSTRAINT `fk_tbl_elections_validation_commissions_election_id` FOREIGN KEY (`election_id`) REFERENCES `tbl_elections` (`election_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tbl_elections_validation_commissions_user_id` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`user_id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
