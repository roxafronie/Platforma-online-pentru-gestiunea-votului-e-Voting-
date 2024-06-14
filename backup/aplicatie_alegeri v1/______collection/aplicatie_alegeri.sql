-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 16, 2024 at 10:43 AM
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
-- Table structure for table `tbl_content_pages`
--

CREATE TABLE `tbl_content_pages` (
  `content_id` smallint(5) UNSIGNED NOT NULL,
  `content_layout_id` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `parent_id` smallint(5) UNSIGNED NOT NULL,
  `constant` varchar(64) NOT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '1 for active/0 for inactive (in front-end)',
  `sort_order` smallint(5) UNSIGNED DEFAULT '0',
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 73728 kB';

--
-- Dumping data for table `tbl_content_pages`
--

INSERT INTO `tbl_content_pages` (`content_id`, `content_layout_id`, `parent_id`, `constant`, `status`, `sort_order`, `date_added`, `last_modified`) VALUES
(0, 0, 0, '[GENERAL]', 1, 0, '2024-05-07 22:51:20', '0000-00-00 00:00:00'),
(1, 0, 0, '[INDEX]', 1, 1000, '2024-05-07 22:51:25', '0000-00-00 00:00:00'),
(2, 0, 0, '[REGISTER]', 1, 900, '2024-05-07 22:51:30', '0000-00-00 00:00:00'),
(3, 0, 0, '[FORGET_PASSWORD]', 1, 800, '2024-05-07 22:51:35', '0000-00-00 00:00:00'),
(4, 0, 0, '[LOGIN]', 1, 700, '2024-05-07 22:51:40', '0000-00-00 00:00:00'),
(5, 0, 0, '[ACCOUNT_PROFILE]', 1, 600, '2024-05-07 22:51:45', '0000-00-00 00:00:00'),
(6, 0, 0, '[ACCOUNT_CHANGE_PASSWORD]', 1, 500, '2024-05-07 22:51:50', '0000-00-00 00:00:00'),
(7, 0, 0, '[ACCOUNT_ELECTIONS]', 1, 400, '2024-05-07 22:51:55', '0000-00-00 00:00:00'),
(8, 0, 0, '[ACCOUNT_ELECTIONS_HISTORY]', 1, 300, '2024-05-07 22:52:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_content_pages_localization`
--

CREATE TABLE `tbl_content_pages_localization` (
  `content_id` smallint(5) UNSIGNED NOT NULL,
  `language_id` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `is_built_in` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `is_in_sitemap` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `is_account_page` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `visible_in_menu` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `title` tinytext NOT NULL,
  `content` mediumtext,
  `url_slug` varchar(255) CHARACTER SET ascii NOT NULL,
  `sitemap_priority` float(3,1) UNSIGNED DEFAULT '0.5',
  `sitemap_changefreq` varchar(8) CHARACTER SET ascii DEFAULT 'monthly' COMMENT 'always, hourly, daily, weekly, monthly, yearly, never',
  `seo_title` tinytext,
  `seo_meta_description` tinytext,
  `seo_meta_keywords` tinytext,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AVG_ROW_LENGTH=5957 DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 73728 kB; InnoDB free: 86016 kB';

--
-- Dumping data for table `tbl_content_pages_localization`
--

INSERT INTO `tbl_content_pages_localization` (`content_id`, `language_id`, `is_built_in`, `is_in_sitemap`, `is_account_page`, `visible_in_menu`, `title`, `content`, `url_slug`, `sitemap_priority`, `sitemap_changefreq`, `seo_title`, `seo_meta_description`, `seo_meta_keywords`, `date_added`, `last_modified`) VALUES
(1, 1, 1, 1, 0, 1, 'Alegeri BaroulDolj.ro', '', 'index.html', 0.5, 'monthly', '', '', '', '2024-05-08 01:11:05', '0000-00-00 00:00:00'),
(4, 1, 1, 0, 1, 1, 'Contul meu', '', 'contul-meu', 0.5, 'monthly', '', '', '', '2024-05-08 01:11:10', '0000-00-00 00:00:00'),
(7, 1, 1, 0, 1, 0, 'Cont - Sesiuni alegeri active', '', 'contul-meu/alegeri', 0.5, 'monthly', '', '', '', '2024-05-08 01:11:15', '0000-00-00 00:00:00'),
(8, 1, 1, 0, 1, 0, 'Cont - Istoric alegeri', '', 'contul-meu/istoric-alegeri', 0.5, 'monthly', '', '', '', '2024-05-08 01:11:20', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_content_pages_url_slugs`
--

CREATE TABLE `tbl_content_pages_url_slugs` (
  `id` int(10) UNSIGNED NOT NULL,
  `url_slug_category_id` smallint(5) UNSIGNED NOT NULL DEFAULT '1' COMMENT 'ID from define_url_slugs_categories',
  `category_id` mediumint(8) UNSIGNED NOT NULL COMMENT 'The ID of categories (defined in tbl_define_url_slugs_categories). Eg. ID of tbl_content_structure, tbl_products etc.',
  `language_id` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `is_built_in` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `is_in_sitemap` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `is_account_page` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `title` tinytext NOT NULL,
  `content` mediumtext,
  `url_slug` varchar(255) CHARACTER SET ascii NOT NULL,
  `sitemap_priority` float(3,1) UNSIGNED DEFAULT '0.5',
  `sitemap_changefreq` varchar(8) CHARACTER SET ascii DEFAULT 'monthly' COMMENT 'always, hourly, daily, weekly, monthly, yearly, never',
  `seo_title` tinytext,
  `seo_meta_description` tinytext,
  `seo_meta_keywords` tinytext
) ENGINE=InnoDB AVG_ROW_LENGTH=5957 DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 73728 kB' ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `tbl_content_pages_url_slugs`
--

INSERT INTO `tbl_content_pages_url_slugs` (`id`, `url_slug_category_id`, `category_id`, `language_id`, `is_built_in`, `is_in_sitemap`, `is_account_page`, `title`, `content`, `url_slug`, `sitemap_priority`, `sitemap_changefreq`, `seo_title`, `seo_meta_description`, `seo_meta_keywords`) VALUES
(1, 1, 1, 1, 1, 1, 0, 'Alegeri BaroulDolj.ro', '', 'index.html', 0.5, 'monthly', '', '', ''),
(4, 1, 4, 1, 1, 0, 1, 'Contul meu', '', 'contul-meu', 0.5, 'monthly', '', '', ''),
(7, 1, 7, 1, 1, 0, 1, 'Cont - Sesiuni alegeri active', '', 'contul-meu/alegeri', 0.5, 'monthly', '', '', ''),
(8, 1, 8, 1, 1, 0, 1, 'Cont - Istoric alegeri', NULL, 'contul-meu/istoric-alegeri', 0.5, 'monthly', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_define_url_slugs_categories`
--

CREATE TABLE `tbl_define_url_slugs_categories` (
  `url_slug_category_id` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(32) NOT NULL DEFAULT '',
  `get_slug` varchar(32) CHARACTER SET ascii NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 0 kB' ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `tbl_define_url_slugs_categories`
--

INSERT INTO `tbl_define_url_slugs_categories` (`url_slug_category_id`, `name`, `get_slug`) VALUES
(1, 'Pagini site', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_elections`
--

CREATE TABLE `tbl_elections` (
  `election_id` smallint(5) UNSIGNED NOT NULL,
  `election_type_id` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `admin_user_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
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

--
-- Dumping data for table `tbl_elections`
--

INSERT INTO `tbl_elections` (`election_id`, `election_type_id`, `admin_user_id`, `election_name`, `election_description`, `number_of_users`, `status`, `sort_order`, `starting_date`, `closing_date`, `date_added`, `date_last_modified`) VALUES
(1, 1, 1, 'Sesiune demo 1', '', 5, 1, 0, '2024-05-09 09:40:00', '2024-05-09 12:35:00', '2024-05-09 09:38:38', '1970-01-01 00:00:00'),
(2, 1, 1, 'Sesiune demo 2', '', 20, 1, 0, '2024-05-09 12:30:00', '2024-05-09 12:35:00', '2024-05-09 12:24:42', '1970-01-01 00:00:00'),
(3, 1, 1, 'Sesiune demo 3', '', 20, 1, 0, '2024-05-09 15:30:00', '2024-05-09 15:35:00', '2024-05-09 12:25:34', '1970-01-01 00:00:00'),
(4, 1, 1, 'Sesiune demo 4', '<p>Descriere Sesiune demo 4</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>', 20, 1, 0, '2024-05-13 00:00:00', '2024-05-19 00:00:00', '2024-05-13 00:57:52', '2024-05-13 01:01:55');

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
  `max_allowed_options` tinyint(3) UNSIGNED NOT NULL DEFAULT '1' COMMENT 'Maximum number of options a voter can select',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '1 for active/0 for inactive (in front-end)',
  `sort_order` smallint(5) UNSIGNED DEFAULT '0',
  `date_added` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `date_last_modified` datetime NOT NULL DEFAULT '1970-01-01 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 0 kB' ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `tbl_elections_ballots`
--

INSERT INTO `tbl_elections_ballots` (`ballot_id`, `election_id`, `ballot_type_id`, `ballot_name`, `ballot_description`, `ballot_hint`, `max_allowed_options`, `status`, `sort_order`, `date_added`, `date_last_modified`) VALUES
(1, 1, 1, 'Intrebare 1', '', NULL, 0, 1, 0, '2024-05-09 09:38:46', '1970-01-01 00:00:00'),
(2, 1, 2, 'Intrebare 2', '', NULL, 2, 1, 0, '2024-05-09 09:38:56', '1970-01-01 00:00:00'),
(3, 2, 1, 'Intrebare 1', '', NULL, 0, 1, 0, '2024-05-09 12:25:02', '1970-01-01 00:00:00'),
(4, 3, 2, 'Intrebare 1', '', NULL, 2, 1, 0, '2024-05-09 12:25:50', '1970-01-01 00:00:00'),
(5, 3, 1, 'Intrebare 3', '', NULL, 0, 1, 0, '2024-05-09 14:43:41', '1970-01-01 00:00:00'),
(6, 3, 1, 'Intrebare 5', '', NULL, 0, 1, 0, '2024-05-09 14:43:48', '1970-01-01 00:00:00'),
(7, 4, 1, 'Intrebare 1', '<p>descriere intrebare 1</p>', NULL, 0, 1, 0, '2024-05-13 00:58:12', '2024-05-13 01:02:51'),
(8, 4, 1, 'Intrebare 2', '', NULL, 0, 1, 0, '2024-05-13 00:58:15', '1970-01-01 00:00:00'),
(9, 4, 2, 'Intrebare 3', '', NULL, 2, 1, 0, '2024-05-13 00:58:19', '2024-05-13 00:58:29'),
(10, 4, 2, 'Intrebare 4', '', NULL, 3, 1, 0, '2024-05-13 00:58:35', '1970-01-01 00:00:00');

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

--
-- Dumping data for table `tbl_elections_ballots_options`
--

INSERT INTO `tbl_elections_ballots_options` (`ballot_option_id`, `ballot_id`, `ballot_option_name`, `ballot_option_description`, `status`, `sort_order`, `date_added`, `date_last_modified`) VALUES
(1, 1, 'Varianta 1', '', 1, 0, '2024-05-09 09:39:06', '1970-01-01 00:00:00'),
(2, 1, 'Varianta 2', '', 1, 0, '2024-05-09 09:39:09', '1970-01-01 00:00:00'),
(3, 2, 'Optiune 1', '', 1, 0, '2024-05-09 09:39:21', '1970-01-01 00:00:00'),
(4, 2, 'Optiune 2', '', 1, 0, '2024-05-09 09:39:24', '1970-01-01 00:00:00'),
(5, 2, 'Optiune 3', '', 1, 0, '2024-05-09 09:39:28', '1970-01-01 00:00:00'),
(6, 3, 'Varianta 1', '', 1, 0, '2024-05-09 12:25:07', '1970-01-01 00:00:00'),
(7, 3, 'Varianta 2', '', 1, 0, '2024-05-09 12:25:12', '1970-01-01 00:00:00'),
(8, 4, 'Optiune 1', '', 1, 0, '2024-05-09 12:25:57', '1970-01-01 00:00:00'),
(9, 4, 'Optiune 2', '', 1, 0, '2024-05-09 12:26:03', '1970-01-01 00:00:00'),
(10, 4, 'Optiune 3', '', 1, 0, '2024-05-09 12:26:07', '1970-01-01 00:00:00'),
(11, 5, 'Varianta 1', '', 1, 0, '2024-05-09 14:43:57', '1970-01-01 00:00:00'),
(12, 5, 'Varianta 2', '', 1, 0, '2024-05-09 14:44:02', '1970-01-01 00:00:00'),
(13, 6, 'Varianta 3', '', 1, 0, '2024-05-09 14:44:26', '1970-01-01 00:00:00'),
(14, 7, 'Varianta 1', '', 1, 0, '2024-05-13 00:58:44', '1970-01-01 00:00:00'),
(15, 7, 'Varianta 2', '', 1, 0, '2024-05-13 00:58:48', '2024-05-13 01:00:48'),
(16, 8, 'Varianta 1', '', 1, 0, '2024-05-13 00:58:56', '1970-01-01 00:00:00'),
(17, 8, 'Varianta 2', '', 1, 0, '2024-05-13 00:58:59', '1970-01-01 00:00:00'),
(18, 8, 'Varianta 3', '', 1, 0, '2024-05-13 00:59:06', '1970-01-01 00:00:00'),
(19, 9, 'Optiune 1', '', 1, 0, '2024-05-13 00:59:13', '2024-05-13 00:59:31'),
(20, 9, 'Optiune 2', '', 1, 0, '2024-05-13 00:59:16', '1970-01-01 00:00:00'),
(21, 9, 'Optiune 3', '', 1, 0, '2024-05-13 00:59:22', '1970-01-01 00:00:00'),
(22, 10, 'Optiune 1', '', 1, 0, '2024-05-13 00:59:38', '1970-01-01 00:00:00'),
(23, 10, 'Optiune 2', '', 1, 0, '2024-05-13 00:59:41', '1970-01-01 00:00:00'),
(24, 10, 'Optiune 3', '', 1, 0, '2024-05-13 00:59:45', '1970-01-01 00:00:00'),
(25, 10, 'Optiune 4', '', 1, 0, '2024-05-13 00:59:52', '1970-01-01 00:00:00');

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

--
-- Dumping data for table `tbl_elections_ballots_options_to_users`
--

INSERT INTO `tbl_elections_ballots_options_to_users` (`record_id`, `election_id`, `ballot_id`, `ballot_option_id`, `user_id`) VALUES
(1, 1, 1, 1, 1),
(4, 1, 1, 2, 2),
(7, 1, 1, 1, 3),
(11, 1, 1, 2, 4),
(14, 1, 1, 2, 5),
(2, 1, 2, 4, 1),
(3, 1, 2, 5, 1),
(5, 1, 2, 3, 2),
(6, 1, 2, 4, 2),
(8, 1, 2, 3, 3),
(9, 1, 2, 4, 3),
(10, 1, 2, 5, 3),
(12, 1, 2, 3, 4),
(13, 1, 2, 4, 4),
(15, 1, 2, 3, 5),
(16, 1, 2, 5, 5),
(17, 2, 3, 6, 1),
(40, 4, 7, 14, 1),
(41, 4, 8, 18, 1),
(42, 4, 9, 19, 1),
(43, 4, 9, 20, 1),
(44, 4, 10, 24, 1),
(45, 4, 10, 25, 1);

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

--
-- Dumping data for table `tbl_elections_ballots_to_users`
--

INSERT INTO `tbl_elections_ballots_to_users` (`record_id`, `election_id`, `ballot_id`, `user_id`, `status`) VALUES
(1, 1, 1, 1, 1),
(2, 1, 2, 1, 1),
(3, 1, 1, 2, 1),
(4, 1, 2, 2, 1),
(5, 1, 1, 3, 1),
(6, 1, 2, 3, 0),
(7, 1, 1, 4, 1),
(8, 1, 2, 4, 1),
(9, 1, 1, 5, 1),
(10, 1, 2, 5, 1),
(11, 2, 3, 1, 1),
(28, 4, 7, 1, 1),
(29, 4, 8, 1, 1),
(30, 4, 9, 1, 1),
(31, 4, 10, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_elections_ballots_types`
--

CREATE TABLE `tbl_elections_ballots_types` (
  `ballot_type_id` tinyint(3) UNSIGNED NOT NULL,
  `ballot_type_code` varchar(32) NOT NULL,
  `ballot_type_name` varchar(255) NOT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '1 for active/0 for inactive (in front-end)',
  `sort_order` smallint(5) UNSIGNED DEFAULT '0',
  `date_added` datetime NOT NULL DEFAULT '1970-01-01 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 0 kB' ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `tbl_elections_ballots_types`
--

INSERT INTO `tbl_elections_ballots_types` (`ballot_type_id`, `ballot_type_code`, `ballot_type_name`, `status`, `sort_order`, `date_added`) VALUES
(1, 'SINGLE_OPTION', 'O singură variantă permisă (1)', 1, 100, '2024-04-03 01:15:55'),
(2, 'MULTIPLE_OPTIONS', 'Una sau mai multe variante permise (1+)', 1, 95, '2024-04-03 01:16:10');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_elections_results_actions`
--

CREATE TABLE `tbl_elections_results_actions` (
  `record_id` int(10) UNSIGNED NOT NULL,
  `election_id` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `user_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `action_id` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '1/minutes BAR;\r\n2/minutes CAA;\r\n3/download ballots list;',
  `date_added` datetime NOT NULL DEFAULT '1970-01-01 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 0 kB' DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_elections_to_users`
--

CREATE TABLE `tbl_elections_to_users` (
  `record_id` int(10) UNSIGNED NOT NULL,
  `election_id` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `user_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `is_canceled` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `code` varchar(64) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT '1970-01-01 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 0 kB' ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `tbl_elections_to_users`
--

INSERT INTO `tbl_elections_to_users` (`record_id`, `election_id`, `user_id`, `is_canceled`, `code`, `date_added`) VALUES
(1, 1, 1, 0, 'hpj9bdu5y1e8qr9v.1715236863.7216', '2024-05-09 09:41:03'),
(2, 1, 2, 0, 'hpj9bo1lj978fg1k.1715236880.0387', '2024-05-09 09:41:20'),
(3, 1, 3, 0, 'hpj9bznn719394tw.1715236898.6687', '2024-05-09 09:41:38'),
(4, 1, 4, 0, 'hpj9cb63kgfvbdfj.1715236917.0826', '2024-05-09 09:41:57'),
(5, 1, 5, 0, 'hpj9clubprb05c24.1715236934.1832', '2024-05-09 09:42:14'),
(6, 2, 1, 0, 'hpje7v0u0wdxsn5e.1715247040.2752', '2024-05-09 12:30:40'),
(12, 4, 1, 0, '6642835dcaf0c', '2024-05-14 00:17:17');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_elections_types`
--

CREATE TABLE `tbl_elections_types` (
  `election_type_id` tinyint(3) UNSIGNED NOT NULL,
  `election_type_code` varchar(32) NOT NULL,
  `election_type_name` varchar(255) NOT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '1 for active/0 for inactive (in front-end)',
  `sort_order` smallint(5) UNSIGNED DEFAULT '0',
  `date_added` datetime NOT NULL DEFAULT '1970-01-01 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 0 kB' ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `tbl_elections_types`
--

INSERT INTO `tbl_elections_types` (`election_type_id`, `election_type_code`, `election_type_name`, `status`, `sort_order`, `date_added`) VALUES
(1, 'GENERAL', 'Alegeri generale', 1, 0, '2024-04-03 11:10:25');

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

--
-- Dumping data for table `tbl_elections_validation_commissions`
--

INSERT INTO `tbl_elections_validation_commissions` (`record_id`, `election_id`, `user_id`, `date_added`) VALUES
(1, 1, 10, '2024-05-09 09:43:06'),
(2, 2, 1, '2024-05-09 12:24:50'),
(3, 3, 10, '2024-05-09 12:26:16'),
(4, 4, 10, '2024-05-13 00:58:02');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_languages`
--

CREATE TABLE `tbl_languages` (
  `language_id` tinyint(3) UNSIGNED NOT NULL,
  `is_default` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(32) NOT NULL DEFAULT '',
  `native_name` varchar(32) NOT NULL,
  `code` varchar(2) NOT NULL DEFAULT '',
  `direction` char(3) NOT NULL DEFAULT 'ltr' COMMENT 'ltr[Left to Right], rtl[Right to Left]',
  `image` varchar(255) DEFAULT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '1 for active/0 for inactive (in front-end)',
  `sort_order` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_last_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 0 kB';

--
-- Dumping data for table `tbl_languages`
--

INSERT INTO `tbl_languages` (`language_id`, `is_default`, `name`, `native_name`, `code`, `direction`, `image`, `status`, `sort_order`, `date_added`, `date_last_modified`) VALUES
(1, 1, 'romana', 'română', 'ro', 'ltr', 'ro.png', 1, 255, '2018-10-06 20:12:37', '2018-10-06 20:16:30');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sessions`
--

CREATE TABLE `tbl_sessions` (
  `session` varchar(255) CHARACTER SET ascii NOT NULL,
  `session_expires` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `session_data` longtext COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_sessions`
--

INSERT INTO `tbl_sessions` (`session`, `session_expires`, `session_data`) VALUES
('tqi61ntnieu88uikajsnnfjkh2', 1715806386, 'session_id|s:26:"tqi61ntnieu88uikajsnnfjkh2";admin_username|s:5:"admin";admin_password|s:32:"e10adc3949ba59abbe56e057f20f883e";');

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
  `date_last_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_last_login` datetime NOT NULL DEFAULT '1970-01-01 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 0 kB';

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`user_id`, `user_name`, `user_email`, `user_password`, `user_first_name`, `user_last_name`, `user_phones`, `status`, `date_added`, `date_last_modified`, `date_last_login`) VALUES
(1, 'user1', 'user1@domain.com', 'e10adc3949ba59abbe56e057f20f883e', 'User 1 (nume)', 'User 1 (prenume)', '', 1, '2024-05-09 09:24:28', '2024-05-16 01:02:36', '1970-01-01 00:00:00'),
(2, 'user2', 'user2@domain.com', 'e10adc3949ba59abbe56e057f20f883e', 'User 2 (nume)', 'User 2 (prenume)', NULL, 1, '2024-05-09 09:24:28', '0000-00-00 00:00:00', '1970-01-01 00:00:00'),
(3, 'user3', 'user3@domain.com', 'e10adc3949ba59abbe56e057f20f883e', 'User 3 (nume)', 'User 3 (prenume)', NULL, 1, '2024-05-09 09:24:28', '0000-00-00 00:00:00', '1970-01-01 00:00:00'),
(4, 'user4', 'user4@domain.com', 'e10adc3949ba59abbe56e057f20f883e', 'User 4 (nume)', 'User 4 (prenume)', NULL, 1, '2024-05-09 09:24:28', '0000-00-00 00:00:00', '1970-01-01 00:00:00'),
(5, 'user5', 'user5@domain.com', 'e10adc3949ba59abbe56e057f20f883e', 'User 5 (nume)', 'User 5 (prenume)', NULL, 1, '2024-05-09 09:24:28', '0000-00-00 00:00:00', '1970-01-01 00:00:00'),
(6, 'user6', 'user6@domain.com', 'e10adc3949ba59abbe56e057f20f883e', 'User 6 (nume)', 'User 6 (prenume)', NULL, 1, '2024-05-09 09:24:28', '0000-00-00 00:00:00', '1970-01-01 00:00:00'),
(7, 'user7', 'user7@domain.com', 'e10adc3949ba59abbe56e057f20f883e', 'User 7 (nume)', 'User 7 (prenume)', NULL, 1, '2024-05-09 09:24:28', '0000-00-00 00:00:00', '1970-01-01 00:00:00'),
(8, 'user8', 'user8@domain.com', 'e10adc3949ba59abbe56e057f20f883e', 'User 8 (nume)', 'User 8 (prenume)', NULL, 1, '2024-05-09 09:24:28', '0000-00-00 00:00:00', '1970-01-01 00:00:00'),
(9, 'user9', 'user9@domain.com', 'e10adc3949ba59abbe56e057f20f883e', 'User 9 (nume)', 'User 9 (prenume)', NULL, 1, '2024-05-09 09:24:28', '0000-00-00 00:00:00', '1970-01-01 00:00:00'),
(10, 'user10', 'user10@domain.com', 'e10adc3949ba59abbe56e057f20f883e', 'User 10 (nume)', 'User 10 (prenume)', '1234567890', 1, '2024-05-09 09:24:28', '2024-05-09 12:26:41', '1970-01-01 00:00:00'),
(11, 'user11', 'user11@domain.com', 'e10adc3949ba59abbe56e057f20f883e', 'User 11 (nume)', 'User 11 (prenume)', NULL, 1, '2024-05-09 09:24:28', '0000-00-00 00:00:00', '1970-01-01 00:00:00'),
(12, 'user12', 'user12@domain.com', 'e10adc3949ba59abbe56e057f20f883e', 'User 12 (nume)', 'User 12 (prenume)', NULL, 1, '2024-05-09 09:24:28', '0000-00-00 00:00:00', '1970-01-01 00:00:00'),
(13, 'user13', 'user13@domain.com', 'e10adc3949ba59abbe56e057f20f883e', 'User 13 (nume)', 'User 13 (prenume)', NULL, 1, '2024-05-09 09:24:28', '0000-00-00 00:00:00', '1970-01-01 00:00:00'),
(14, 'user14', 'user14@domain.com', 'e10adc3949ba59abbe56e057f20f883e', 'User 14 (nume)', 'User 14 (prenume)', NULL, 1, '2024-05-09 09:24:28', '0000-00-00 00:00:00', '1970-01-01 00:00:00'),
(15, 'user15', 'user15@domain.com', 'e10adc3949ba59abbe56e057f20f883e', 'User 15 (nume)', 'User 15 (prenume)', NULL, 1, '2024-05-09 09:24:28', '0000-00-00 00:00:00', '1970-01-01 00:00:00'),
(16, 'user16', 'user16@domain.com', 'e10adc3949ba59abbe56e057f20f883e', 'User 16 (nume)', 'User 16 (prenume)', NULL, 1, '2024-05-09 09:24:28', '0000-00-00 00:00:00', '1970-01-01 00:00:00'),
(17, 'user17', 'user17@domain.com', 'e10adc3949ba59abbe56e057f20f883e', 'User 17 (nume)', 'User 17 (prenume)', NULL, 1, '2024-05-09 09:24:28', '0000-00-00 00:00:00', '1970-01-01 00:00:00'),
(18, 'user18', 'user18@domain.com', 'e10adc3949ba59abbe56e057f20f883e', 'User 18 (nume)', 'User 18 (prenume)', NULL, 1, '2024-05-09 09:24:28', '0000-00-00 00:00:00', '1970-01-01 00:00:00'),
(19, 'user19', 'user19@domain.com', 'e10adc3949ba59abbe56e057f20f883e', 'User 19 (nume)', 'User 19 (prenume)', NULL, 1, '2024-05-09 09:24:28', '0000-00-00 00:00:00', '1970-01-01 00:00:00'),
(20, 'user20', 'user20@domain.com', 'e10adc3949ba59abbe56e057f20f883e', 'User 20 (nume)', 'User 20 (prenume)', NULL, 1, '2024-05-09 09:24:28', '0000-00-00 00:00:00', '1970-01-01 00:00:00'),
(29, '33', '44@r44.ro', 'e10adc3949ba59abbe56e057f20f883e', '11', '22', '', 1, '2024-05-16 00:57:27', '0000-00-00 00:00:00', '1970-01-01 00:00:00'),
(33, '333', '444@r444.ro', 'e10adc3949ba59abbe56e057f20f883e', '111', '222', '1234567890', 1, '2024-05-16 01:11:45', '0000-00-00 00:00:00', '1970-01-01 00:00:00'),
(34, '33334', '444@r444.ro', '123456', '11111', '22222', '1234567890', 1, '2024-05-16 01:14:41', '2024-05-16 01:18:48', '1970-01-01 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users_admin`
--

CREATE TABLE `tbl_users_admin` (
  `user_id` tinyint(3) UNSIGNED NOT NULL,
  `user_role_id` tinyint(3) UNSIGNED NOT NULL,
  `user_name` varchar(32) NOT NULL DEFAULT '',
  `user_password` char(32) CHARACTER SET ascii NOT NULL DEFAULT '',
  `user_email` varchar(64) CHARACTER SET ascii NOT NULL DEFAULT '',
  `user_full_name` varchar(32) NOT NULL DEFAULT '',
  `ip_address` varchar(15) CHARACTER SET ascii NOT NULL DEFAULT '',
  `host_name` varchar(60) CHARACTER SET ascii NOT NULL DEFAULT '',
  `web_browser` varchar(64) NOT NULL DEFAULT '',
  `login_counter` smallint(6) UNSIGNED NOT NULL DEFAULT '0',
  `date_last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_expire_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_last_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 0 kB';

--
-- Dumping data for table `tbl_users_admin`
--

INSERT INTO `tbl_users_admin` (`user_id`, `user_role_id`, `user_name`, `user_password`, `user_email`, `user_full_name`, `ip_address`, `host_name`, `web_browser`, `login_counter`, `date_last_login`, `date_expire_login`, `date_added`, `date_last_modified`) VALUES
(1, 1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', '', 'Administrator', '127.0.0.1', 'localhost', 'Google Chrome', 1, '2024-05-06 22:13:36', '0000-00-00 00:00:00', '2024-05-01 10:25:50', '0000-00-00 00:00:00'),
(2, 3, 'user10', 'e10adc3949ba59abbe56e057f20f883e', 'user10@domain.com', 'User 10 (nume) User 10 (prenume)', '::1', 'DESKTOP-3JETSNK', 'Google Chrome', 0, '0000-00-00 00:00:00', '2024-06-09 09:45:00', '2024-05-09 09:43:06', '0000-00-00 00:00:00'),
(3, 3, 'user1', 'e10adc3949ba59abbe56e057f20f883e', 'user1@domain.com', 'User 1 (nume) User 1 (prenume)', '::1', 'DESKTOP-3JETSNK', 'Google Chrome', 0, '0000-00-00 00:00:00', '2024-06-09 12:35:00', '2024-05-09 12:24:50', '0000-00-00 00:00:00'),
(4, 3, 'user10', 'e10adc3949ba59abbe56e057f20f883e', 'user10@domain.com', 'User 10 (nume) User 10 (prenume)', '::1', 'DESKTOP-3JETSNK', 'Google Chrome', 0, '0000-00-00 00:00:00', '2024-06-09 12:35:00', '2024-05-09 12:26:16', '0000-00-00 00:00:00'),
(5, 3, 'user10', 'e10adc3949ba59abbe56e057f20f883e', 'user10@domain.com', 'User 10 (nume) User 10 (prenume)', '::1', 'DESKTOP-3JETSNK', 'Google Chrome', 0, '0000-00-00 00:00:00', '2024-06-19 00:00:00', '2024-05-13 00:58:02', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users_admin_roles`
--

CREATE TABLE `tbl_users_admin_roles` (
  `user_role_id` tinyint(3) UNSIGNED NOT NULL,
  `user_role` varchar(32) NOT NULL DEFAULT '',
  `user_role_description` text,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_last_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_users_admin_roles`
--

INSERT INTO `tbl_users_admin_roles` (`user_role_id`, `user_role`, `user_role_description`, `date_added`, `date_last_modified`) VALUES
(1, 'superadmin', 'Administrator cu drepturi depline\r\n', '2024-05-06 19:45:55', '0000-00-00 00:00:00'),
(2, 'admin', 'Administrator site', '2024-05-06 19:45:45', '0000-00-00 00:00:00'),
(3, 'validator', 'Membru comise validare', '2024-05-06 19:45:50', '0000-00-00 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_content_pages`
--
ALTER TABLE `tbl_content_pages`
  ADD PRIMARY KEY (`content_id`);

--
-- Indexes for table `tbl_content_pages_localization`
--
ALTER TABLE `tbl_content_pages_localization`
  ADD PRIMARY KEY (`content_id`,`language_id`),
  ADD UNIQUE KEY `unique_url_slug` (`url_slug`);

--
-- Indexes for table `tbl_content_pages_url_slugs`
--
ALTER TABLE `tbl_content_pages_url_slugs`
  ADD UNIQUE KEY `id_entry` (`id`),
  ADD UNIQUE KEY `unique_url_slug` (`url_slug`),
  ADD UNIQUE KEY `unique_id_content_id_language` (`url_slug_category_id`,`category_id`,`language_id`);

--
-- Indexes for table `tbl_define_url_slugs_categories`
--
ALTER TABLE `tbl_define_url_slugs_categories`
  ADD PRIMARY KEY (`url_slug_category_id`),
  ADD UNIQUE KEY `get_slug` (`get_slug`);

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
  ADD KEY `ballot_type_id_election_id_ballot_id_idx` (`ballot_type_id`,`election_id`,`ballot_id`) USING BTREE;

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
  ADD UNIQUE KEY `election_id_ballot_id_ballot_option_id_user_id_idx` (`election_id`,`ballot_id`,`user_id`,`ballot_option_id`) USING BTREE;

--
-- Indexes for table `tbl_elections_ballots_to_users`
--
ALTER TABLE `tbl_elections_ballots_to_users`
  ADD PRIMARY KEY (`record_id`) USING BTREE,
  ADD UNIQUE KEY `election_id_ballot_id_user_id_idx` (`election_id`,`ballot_id`,`user_id`);

--
-- Indexes for table `tbl_elections_ballots_types`
--
ALTER TABLE `tbl_elections_ballots_types`
  ADD PRIMARY KEY (`ballot_type_id`) USING BTREE;

--
-- Indexes for table `tbl_elections_results_actions`
--
ALTER TABLE `tbl_elections_results_actions`
  ADD PRIMARY KEY (`record_id`) USING BTREE;

--
-- Indexes for table `tbl_elections_to_users`
--
ALTER TABLE `tbl_elections_to_users`
  ADD PRIMARY KEY (`record_id`) USING BTREE,
  ADD UNIQUE KEY `election_id_user_id_idx` (`election_id`,`user_id`),
  ADD UNIQUE KEY `unique_code` (`code`);

--
-- Indexes for table `tbl_elections_types`
--
ALTER TABLE `tbl_elections_types`
  ADD PRIMARY KEY (`election_type_id`) USING BTREE;

--
-- Indexes for table `tbl_elections_validation_commissions`
--
ALTER TABLE `tbl_elections_validation_commissions`
  ADD PRIMARY KEY (`record_id`) USING BTREE,
  ADD UNIQUE KEY `election_id_to_user_id_new` (`election_id`,`user_id`) USING BTREE;

--
-- Indexes for table `tbl_languages`
--
ALTER TABLE `tbl_languages`
  ADD PRIMARY KEY (`language_id`);

--
-- Indexes for table `tbl_sessions`
--
ALTER TABLE `tbl_sessions`
  ADD PRIMARY KEY (`session`);

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
-- Indexes for table `tbl_users_admin_roles`
--
ALTER TABLE `tbl_users_admin_roles`
  ADD PRIMARY KEY (`user_role_id`),
  ADD UNIQUE KEY `admin_login_role` (`user_role`),
  ADD UNIQUE KEY `admin_login_role_2` (`user_role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_content_pages`
--
ALTER TABLE `tbl_content_pages`
  MODIFY `content_id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `tbl_content_pages_url_slugs`
--
ALTER TABLE `tbl_content_pages_url_slugs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `tbl_define_url_slugs_categories`
--
ALTER TABLE `tbl_define_url_slugs_categories`
  MODIFY `url_slug_category_id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tbl_elections`
--
ALTER TABLE `tbl_elections`
  MODIFY `election_id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `tbl_elections_ballots`
--
ALTER TABLE `tbl_elections_ballots`
  MODIFY `ballot_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `tbl_elections_ballots_options`
--
ALTER TABLE `tbl_elections_ballots_options`
  MODIFY `ballot_option_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT for table `tbl_elections_ballots_options_to_users`
--
ALTER TABLE `tbl_elections_ballots_options_to_users`
  MODIFY `record_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;
--
-- AUTO_INCREMENT for table `tbl_elections_ballots_to_users`
--
ALTER TABLE `tbl_elections_ballots_to_users`
  MODIFY `record_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT for table `tbl_elections_ballots_types`
--
ALTER TABLE `tbl_elections_ballots_types`
  MODIFY `ballot_type_id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tbl_elections_results_actions`
--
ALTER TABLE `tbl_elections_results_actions`
  MODIFY `record_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_elections_to_users`
--
ALTER TABLE `tbl_elections_to_users`
  MODIFY `record_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `tbl_elections_types`
--
ALTER TABLE `tbl_elections_types`
  MODIFY `election_type_id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tbl_elections_validation_commissions`
--
ALTER TABLE `tbl_elections_validation_commissions`
  MODIFY `record_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `tbl_languages`
--
ALTER TABLE `tbl_languages`
  MODIFY `language_id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
--
-- AUTO_INCREMENT for table `tbl_users_admin`
--
ALTER TABLE `tbl_users_admin`
  MODIFY `user_id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `tbl_users_admin_roles`
--
ALTER TABLE `tbl_users_admin_roles`
  MODIFY `user_role_id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
