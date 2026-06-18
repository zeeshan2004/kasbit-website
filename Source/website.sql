-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 18, 2026 at 11:16 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `website`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `footer_settings`
--

CREATE TABLE `footer_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_1` text COLLATE utf8mb4_unicode_ci,
  `address_2` text COLLATE utf8mb4_unicode_ci,
  `address_3` text COLLATE utf8mb4_unicode_ci,
  `useful_links` json DEFAULT NULL,
  `facebook_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkedin_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gallery_images` json DEFAULT NULL,
  `map_embed_url` text COLLATE utf8mb4_unicode_ci,
  `map_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `copyright_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `background_color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#2756a5',
  `bottom_bar_color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#064f80',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `footer_settings`
--

INSERT INTO `footer_settings` (`id`, `logo`, `address_1`, `address_2`, `address_3`, `useful_links`, `facebook_url`, `instagram_url`, `linkedin_url`, `gallery_images`, `map_embed_url`, `map_title`, `copyright_text`, `background_color`, `bottom_bar_color`, `is_active`, `created_at`, `updated_at`) VALUES
(1, NULL, '84-B, S.M.C.H.S, Off Shahrah-e-Faisal, Karachi-74400, Pakistan. SMCHS', 'D-15, Block D, Hyderi, North Nazimabad, Karachi, Pakistan. Hyderi', 'B-257, Block 5, Scheme No. 24, Gulshan-e-Iqbal, Karachi, Pakistan. Gulshan', '[]', 'https://www.facebook.com/KASBIT/', 'https://www.instagram.com/kasbit_official/', 'https://www.linkedin.com/school/khadim-ali-shah-bukhari-institute-of-technology/', '[\"uploads/footer/1780963596_0_footer_gallery.jpg\", \"uploads/footer/1780963596_1_footer_gallery.jpg\", \"uploads/footer/1780963596_2_footer_gallery.jpg\", \"uploads/footer/1780963596_3_footer_gallery.jpg\", \"uploads/footer/1780963596_4_footer_gallery.jpg\", \"uploads/footer/1781042011_0_6a288b5be6985_footer_gallery.jpg\", \"uploads/footer/1781042011_1_6a288b5be7019_footer_gallery.jpg\", \"uploads/footer/1781042011_2_6a288b5be7617_footer_gallery.jpg\", \"uploads/footer/1781042036_0_6a288b74403b1_footer_gallery.jpg\"]', 'https://www.google.com/maps?q=KASBIT%20Karachi&output=embed', 'Location Map', '© 2026 KASB Institute of Technology (PVT) Ltd. All Rights Reserved', '#2756a5', '#064f80', 1, '2026-06-08 19:02:47', '2026-06-18 17:48:00');

-- --------------------------------------------------------

--
-- Table structure for table `header_menus`
--

CREATE TABLE `header_menus` (
  `id` bigint UNSIGNED NOT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#',
  `icon` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `show_in_admin_sidebar` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int UNSIGNED NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `header_menus`
--

INSERT INTO `header_menus` (`id`, `parent_id`, `name`, `link`, `icon`, `show_in_admin_sidebar`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(3, NULL, 'Programs', '#', 'fa-solid fa-book-open', 1, 3, 1, '2026-06-08 13:28:22', '2026-06-18 11:19:24'),
(4, NULL, 'Admissions', '#', 'fa-solid fa-file-signature', 1, 4, 1, '2026-06-08 13:28:22', '2026-06-18 10:57:23'),
(5, NULL, 'Gallery', '#', NULL, 0, 5, 1, '2026-06-08 13:28:22', '2026-06-08 13:28:22'),
(6, NULL, 'Contact', '#', NULL, 0, 6, 0, '2026-06-08 13:28:22', '2026-06-18 12:23:42'),
(13, NULL, 'About us', '/about', 'fa-solid fa-circle-info', 1, 2, 1, '2026-06-18 10:43:07', '2026-06-18 13:47:52'),
(34, NULL, 'Academics', '#', 'fa-solid fa-building-columns', 1, 5, 1, '2026-06-18 10:57:23', '2026-06-18 10:57:23'),
(43, NULL, 'Life @ Kasbit', '#', 'fa-solid fa-circle-nodes', 1, 6, 1, '2026-06-18 10:57:23', '2026-06-18 10:57:23'),
(48, NULL, 'QEC', '#', 'fa-solid fa-shield-halved', 1, 7, 1, '2026-06-18 10:57:23', '2026-06-18 10:57:23'),
(65, NULL, 'ORIC', '#', 'fa-solid fa-flask', 1, 8, 1, '2026-06-18 10:57:23', '2026-06-18 10:57:23'),
(82, NULL, 'Login', '#', 'fa-solid fa-right-to-bracket', 1, 9, 1, '2026-06-18 10:57:23', '2026-06-18 11:13:15'),
(87, NULL, 'Alumni', '#', 'fa-solid fa-user-graduate', 1, 10, 1, '2026-06-18 10:57:23', '2026-06-18 11:27:35'),
(90, NULL, 'E Library', '#', 'fa-solid fa-book', 1, 11, 1, '2026-06-18 10:57:23', '2026-06-18 11:27:42'),
(94, 13, 'Message', '/pages/message', 'fa-solid fa-message', 0, 2, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:27'),
(95, 13, 'International Board of Advisors', '/pages/international-board-of-advisors', 'fa-solid fa-globe', 0, 3, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:27'),
(96, 3, 'Associate Degree Program 2 Years', '/pages/associate-degree-program-2-years', 'fa-solid fa-book-open', 0, 1, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:27'),
(97, 3, 'Undergraduate Program', '/pages/undergraduate-program', 'fa-solid fa-book-open', 0, 2, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:27'),
(98, 3, 'Graduate Program', '/pages/graduate-program', 'fa-solid fa-book-open', 0, 3, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:27'),
(99, 3, 'Postgraduate', '/pages/postgraduate', 'fa-solid fa-book-open', 0, 4, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:27'),
(100, 3, 'Fee Structure', '/pages/fee-structure', 'fa-solid fa-circle', 0, 5, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:27'),
(101, 3, 'Program Profile', '/pages/program-profile', 'fa-solid fa-book-open', 0, 6, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:27'),
(102, 4, 'Admission Policy', '/pages/admission-policy', 'fa-solid fa-shield-halved', 0, 1, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:27'),
(103, 4, 'Online Admission Portal', '/pages/online-admission-portal', 'fa-solid fa-circle', 0, 2, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:27'),
(104, 34, 'Dean\'s Message', '/pages/deans-message', 'fa-solid fa-message', 0, 1, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:27'),
(105, 34, 'Faculty', '/pages/faculty', 'fa-solid fa-users', 0, 2, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:27'),
(106, 34, 'Academic Calendar', '/pages/academic-calendar', 'fa-solid fa-calendar-days', 0, 3, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:27'),
(107, 34, 'Academic Departments', '/pages/academic-departments', 'fa-solid fa-circle', 0, 4, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:27'),
(108, 34, 'Academic Scholarship', '/pages/academic-scholarship', 'fa-solid fa-circle', 0, 5, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:27'),
(109, 34, 'Rules & Regulations', '/pages/rules-regulations', 'fa-solid fa-shield-halved', 0, 6, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:27'),
(110, 43, 'Facilities & Services', '/pages/facilities-services', 'fa-solid fa-circle', 0, 1, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:27'),
(111, 43, 'Life on Premises', '/pages/life-on-premises', 'fa-solid fa-circle', 0, 2, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:27'),
(112, 43, 'Student Societies', '/pages/student-societies', 'fa-solid fa-circle', 0, 3, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:27'),
(113, 43, 'Event Gallery', '/pages/event-gallery', 'fa-solid fa-circle', 0, 4, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:27'),
(114, 48, 'Quality Enhancement Cell Message', '/pages/quality-enhancement-cell-message', 'fa-solid fa-message', 0, 1, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:27'),
(115, 48, 'Quality Policy Statement', '/pages/quality-policy-statement', 'fa-solid fa-shield-halved', 0, 2, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:27'),
(116, 48, 'QEC Structure', '/pages/qec-structure', 'fa-solid fa-circle', 0, 3, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:27'),
(117, 48, 'QEC Staff Details', '/pages/qec-staff-details', 'fa-solid fa-circle', 0, 4, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:27'),
(118, 48, 'Functions of QEC', '/pages/functions-of-qec', 'fa-solid fa-circle', 0, 5, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:27'),
(119, 48, 'Student Survey Forms', '/pages/student-survey-forms', 'fa-solid fa-circle', 0, 6, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:27'),
(120, 48, 'QEC Activity Calender', '/pages/qec-activity-calender', 'fa-solid fa-calendar-days', 0, 7, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:27'),
(121, 48, 'QEC Activities', '/pages/qec-activities', 'fa-solid fa-circle', 0, 8, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:27'),
(122, 48, 'Yearly Progress Report', '/pages/yearly-progress-report', 'fa-solid fa-circle', 0, 9, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:27'),
(123, 48, 'Self Assessment Report', '/pages/self-assessment-report', 'fa-solid fa-circle', 0, 10, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:27'),
(124, 48, 'Memberships', '/pages/memberships', 'fa-solid fa-circle', 0, 11, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:27'),
(125, 48, 'AT / PT Notification', '/pages/at-pt-notification', 'fa-solid fa-circle', 0, 12, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:27'),
(126, 48, 'SDG', '/pages/sdg', 'fa-solid fa-circle', 0, 13, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:27'),
(127, 65, 'Introduction', '/pages/introduction', 'fa-solid fa-circle', 0, 1, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:27'),
(128, 65, 'Research Journals', '/pages/research-journals', 'fa-solid fa-circle', 0, 2, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:27'),
(129, 65, 'Conferences', '/pages/conferences', 'fa-solid fa-circle', 0, 3, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:27'),
(130, 65, 'Trainings & Workshops', '/pages/trainings-workshops', 'fa-solid fa-circle', 0, 4, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:27'),
(131, 65, 'Research Project / Thesis', '/pages/research-project-thesis', 'fa-solid fa-circle', 0, 5, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:27'),
(132, 65, 'Industrial Linkage', '/pages/industrial-linkage', 'fa-solid fa-circle', 0, 6, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:28'),
(133, 82, 'Faculty Login', '/pages/faculty-login', 'fa-solid fa-users', 0, 1, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:28'),
(134, 82, 'Student Login', '/pages/student-login', 'fa-solid fa-circle', 0, 2, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:28'),
(135, 82, 'Results', '/pages/results', 'fa-solid fa-chart-line', 0, 3, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:28'),
(136, 82, 'Convocation Registration', '/pages/convocation-registration', 'fa-solid fa-circle', 0, 4, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:28'),
(137, 87, 'Office of Alumni', '/pages/office-of-alumni', 'fa-solid fa-users', 0, 1, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:28'),
(138, 87, 'Alumni Login', '/pages/alumni-login', 'fa-solid fa-users', 0, 2, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:28'),
(139, 90, 'Kasbit E Library', '/pages/kasbit-e-library', 'fa-solid fa-book-open', 0, 1, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:28'),
(140, 90, 'E Library Resources', '/pages/e-library-resources', 'fa-solid fa-book-open', 0, 2, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:28'),
(142, NULL, 'Home', '/', 'fa-solid fa-book-open', 1, 1, 1, '2026-06-18 12:08:54', '2026-06-18 12:59:26');

-- --------------------------------------------------------

--
-- Table structure for table `header_menu_pages`
--

CREATE TABLE `header_menu_pages` (
  `id` bigint UNSIGNED NOT NULL,
  `header_menu_id` bigint UNSIGNED NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `eyebrow` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `accent_color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#07559d',
  `show_image` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `header_menu_pages`
--

INSERT INTO `header_menu_pages` (`id`, `header_menu_id`, `slug`, `eyebrow`, `title`, `subtitle`, `content`, `image`, `accent_color`, `show_image`, `created_at`, `updated_at`) VALUES
(1, 94, 'message', 'About us', 'Message', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:37:51'),
(2, 95, 'international-board-of-advisors', 'About us', 'International Board of Advisors', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(3, 96, 'associate-degree-program-2-years', 'Programs', 'Associate Degree Program 2 Years', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(4, 97, 'undergraduate-program', 'Programs', 'Undergraduate Program', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(5, 98, 'graduate-program', 'Programs', 'Graduate Program', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(6, 99, 'postgraduate', 'Programs', 'Postgraduate', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(7, 100, 'fee-structure', 'Programs', 'Fee Structure', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(8, 101, 'program-profile', 'Programs', 'Program Profile', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(9, 102, 'admission-policy', 'Admissions', 'Admission Policy', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(10, 103, 'online-admission-portal', 'Admissions', 'Online Admission Portal', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(11, 104, 'deans-message', 'Academics', 'Dean\'s Message', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(12, 105, 'faculty', 'Academics', 'Faculty', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(13, 106, 'academic-calendar', 'Academics', 'Academic Calendar', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(14, 107, 'academic-departments', 'Academics', 'Academic Departments', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(15, 108, 'academic-scholarship', 'Academics', 'Academic Scholarship', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(16, 109, 'rules-regulations', 'Academics', 'Rules & Regulations', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(17, 110, 'facilities-services', 'Life @ Kasbit', 'Facilities & Services', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(18, 111, 'life-on-premises', 'Life @ Kasbit', 'Life on Premises', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(19, 112, 'student-societies', 'Life @ Kasbit', 'Student Societies', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(20, 113, 'event-gallery', 'Life @ Kasbit', 'Event Gallery', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(21, 114, 'quality-enhancement-cell-message', 'QEC', 'Quality Enhancement Cell Message', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(22, 115, 'quality-policy-statement', 'QEC', 'Quality Policy Statement', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(23, 116, 'qec-structure', 'QEC', 'QEC Structure', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(24, 117, 'qec-staff-details', 'QEC', 'QEC Staff Details', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(25, 118, 'functions-of-qec', 'QEC', 'Functions of QEC', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(26, 119, 'student-survey-forms', 'QEC', 'Student Survey Forms', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(27, 120, 'qec-activity-calender', 'QEC', 'QEC Activity Calender', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(28, 121, 'qec-activities', 'QEC', 'QEC Activities', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(29, 122, 'yearly-progress-report', 'QEC', 'Yearly Progress Report', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(30, 123, 'self-assessment-report', 'QEC', 'Self Assessment Report', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(31, 124, 'memberships', 'QEC', 'Memberships', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(32, 125, 'at-pt-notification', 'QEC', 'AT / PT Notification', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(33, 126, 'sdg', 'QEC', 'SDG', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(34, 127, 'introduction', 'ORIC', 'Introduction', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(35, 128, 'research-journals', 'ORIC', 'Research Journals', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(36, 129, 'conferences', 'ORIC', 'Conferences', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(37, 130, 'trainings-workshops', 'ORIC', 'Trainings & Workshops', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(38, 131, 'research-project-thesis', 'ORIC', 'Research Project / Thesis', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(39, 132, 'industrial-linkage', 'ORIC', 'Industrial Linkage', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:28', '2026-06-18 13:04:28'),
(40, 133, 'faculty-login', 'Login', 'Faculty Login', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:28', '2026-06-18 13:04:28'),
(41, 134, 'student-login', 'Login', 'Student Login', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:28', '2026-06-18 13:04:28'),
(42, 135, 'results', 'Login', 'Results', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:28', '2026-06-18 13:04:28'),
(43, 136, 'convocation-registration', 'Login', 'Convocation Registration', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:28', '2026-06-18 13:04:28'),
(44, 137, 'office-of-alumni', 'Alumni', 'Office of Alumni', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:28', '2026-06-18 13:04:28'),
(45, 138, 'alumni-login', 'Alumni', 'Alumni Login', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:28', '2026-06-18 13:04:28'),
(46, 139, 'kasbit-e-library', 'E Library', 'Kasbit E Library', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:28', '2026-06-18 13:04:28'),
(47, 140, 'e-library-resources', 'E Library', 'E Library Resources', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:28', '2026-06-18 13:04:28'),
(48, 13, 'about-us', 'About KASBIT', 'About us', NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:47:52', '2026-06-18 13:47:52');

-- --------------------------------------------------------

--
-- Table structure for table `header_menu_page_slides`
--

CREATE TABLE `header_menu_page_slides` (
  `id` bigint UNSIGNED NOT NULL,
  `header_menu_page_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_position` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'left',
  `sort_order` int UNSIGNED NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `header_menu_page_slides`
--

INSERT INTO `header_menu_page_slides` (`id`, `header_menu_page_id`, `title`, `description`, `image`, `image_position`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 1, 'CHANCELLOER’S MESSAGE Mubashir Ali Shah Bukhari', 'KASBIT’S Millennial undertaking is to provide higher education, scholarship, training, and outreach services through exemplary teaching, research, innovation and extension support for sustainable national and international development. We nurture an intelligent, inclusive culture that integrates robust theory with global best practices to produce graduates with relevant knowledge, skills and responsible citizenry. In this regard, KASBIT is guided by passion for excellence, integrity, transparency, professionalism, devotion to duty and good corporate governance.', 'uploads/page-slides/1781813366_6a3450769806a_page_slide.png', 'left', 0, 1, '2026-06-18 13:37:51', '2026-06-18 15:09:26'),
(3, 48, 'The History of KASBIT', 'KASB Institute of Technology Private Limited is the parent body of KASB Institute of Technology (KASBIT) that was established in September 1999, through Registration with Securities & Exchange Commission of Pakistan. It is the first Private Sector Institute of Higher Education that was registered as a Corporate body. Since its inception, KASBIT has achieved many mile-stones that advocate its high standard, excellence and quality recognition.', 'uploads/page-slides/1781808807_0_6a343ea711fb7_page_slide.jpg', 'left', 0, 1, '2026-06-18 13:53:27', '2026-06-18 13:53:27'),
(6, 48, 'Chartered by Government of Sindh', 'KASBIT is Chartered by the Government of Sindh and recognized by the Higher Education Commission of Pakistan, which has also awarded the highest category W(4) rating to KASBIT in recognition of the high educational standards that it maintains.', 'uploads/page-slides/1781813018_0_6a344f1a4585c_page_slide.jpg', 'right', 0, 1, '2026-06-18 15:03:38', '2026-06-18 15:03:38'),
(7, 48, 'Group Introduction', 'The continuous success and growth of our Group Companies is a reflection of the innovative approach and commitment of over 50 years upon the tenet, “Tradition of Trust” that was envisaged by the founding father of the Group. The Group Companies play leading roles in Real Estate and Construction of Commercial and Residential Complexes, Land Development, Higher Education, Medical Services and Equipment, Commodity Trading, Import-Export, Media Network, Civil and Defense Purpose Technology and even Philanthropy.', 'uploads/page-slides/1781813067_0_6a344f4bb3e6e_page_slide.jpg', 'left', 0, 1, '2026-06-18 15:04:27', '2026-06-18 15:04:37'),
(8, 48, 'HEC Recognition', 'KASBIT is recognized by the Higher Education Commission of Pakistan and has been awarded the highest ranking of W(4) under whom the standards of educational institutions are scrutinized and evaluated in Pakistan.', 'uploads/page-slides/1781813127_0_6a344f877c46d_page_slide.jpg', 'right', 0, 1, '2026-06-18 15:05:27', '2026-06-18 15:05:27'),
(9, 48, 'Member of AACSB', '(Association to Advance Collegiate Schools of Business)\r\nKASBIT became a member of the Association to Advance Collegiate Schools of Business (AACSB), which is based in the US to ensure the quality and continuous improvements in collegiate management education. AACSB International produces and publishes a wide range of knowledge service publications and special reports on the trends and issues within management education. AACSB also plans to conduct extensive array of professional development programs for students and professionals and its membership ascertains the current standing of KASBIT.', 'uploads/page-slides/1781813160_0_6a344fa8f37fb_page_slide.jpg', 'left', 0, 1, '2026-06-18 15:06:00', '2026-06-18 15:06:00'),
(10, 48, 'ISO Certified', 'KASBIT became Pakistan’s first ISO 9001 certified private-sector degree awarding institute in 2002, reflecting its commitment to quality education, academic excellence, and high standards in Management Sciences.', 'uploads/page-slides/1781813175_0_6a344fb71b47c_page_slide.jpg', 'right', 0, 1, '2026-06-18 15:06:15', '2026-06-18 17:51:18'),
(11, 1, 'Dr. Fahim Qazi', 'At KASBIT, we are committed to fostering a culture of excellence in education that empowers students to become innovative leaders and changemakers. Our vision is to provide a holistic learning environment that inspires our students to achieve their full potential.\r\n\r\nWe believe that education should be transformative and innovative, enabling students to develop the skills and knowledge they need to succeed in a rapidly changing world. We strive to cultivate value-based growth in our students, fostering the spirit of national development, promoting creativity and encouraging entrepreneurship.\r\n\r\nWe are committed to providing our students with a world-class education that prepares them for a successful future.\r\n\r\nJoin us in our journey to create a brighter future for our students and our nation', 'uploads/page-slides/1781813966_0_6a3452ce5a6b2_page_slide.png', 'left', 0, 1, '2026-06-18 15:19:26', '2026-06-18 17:12:55'),
(12, 2, 'NASIR ALI SHAH BUKHARI (Chairman Board of Advisors)', 'Chairman\r\n\r\nKASB Group', 'uploads/page-slides/1781816643_6a345d4306906_page_slide.jpg', 'left', 1, 1, '2026-06-18 15:58:50', '2026-06-18 17:34:39'),
(14, 2, 'Ali Farid Khwaja (Member)', 'Chairman KTrade\r\nCEO Oxford Frontier Capital\r\n\r\nAli Farid Khwaja is the Chairman of KASB Securities, a leading stock brokerage in Pakistan and the CEO of OXford Frontier Capital, a UK-based investment and consulting company focused on fintech for capital markets.', 'uploads/page-slides/1781821340_6a346f9c5b2f8_page_slide.jpg', 'left', 2, 1, '2026-06-18 17:07:53', '2026-06-18 17:34:44'),
(18, 2, 'Humza Tabani (Member)', 'CEO/Vice Chairman\r\nTabani Group\r\n\r\nHumza Tabani is the Entrepreneur and businessman. He is the CEO/Vice Chairman of Tabani Group and directing 10 companies at a time. With diversity, he has made possible for Tabani Group to form big ventures in mega projects.', 'uploads/page-slides/1781821920_0_6a3471e04b01b_page_slide.jpg', 'left', 5, 1, '2026-06-18 17:32:00', '2026-06-18 17:35:19'),
(19, 2, 'Yasmin Hyder (Member)', 'Founder & President\r\n\r\nPakistan Women Entrepreneurs Network for Trade', 'uploads/page-slides/1781821957_0_6a34720544626_page_slide.jpg', 'left', 3, 1, '2026-06-18 17:32:37', '2026-06-18 17:34:53'),
(20, 2, 'Dr. Jalil ur Rehman (Member)', 'Chief Executive Officer\r\n\r\nBenthan Science Publishers Ltd.', 'uploads/page-slides/1781822009_0_6a3472395e7fd_page_slide.jpg', 'left', 4, 1, '2026-06-18 17:33:29', '2026-06-18 17:34:54'),
(21, 2, 'Bilal Maqsood (Member)', 'Bilal Maqsood is a Pakistani singer-songwriter, composer, music video director and painter better known for being a founding member of the pop-rock band Strings', 'uploads/page-slides/1781822026_0_6a34724a11b32_page_slide.jpg', 'left', 6, 1, '2026-06-18 17:33:46', '2026-06-18 17:35:20'),
(22, 2, 'DR. CYRUS F. GIBSON (Member)', 'Senior Lecturer\r\n\r\nMassachusetts Institute of Technology\r\nSloan School of Management, (U.S.A)', 'uploads/page-slides/1781822049_0_6a34726116b58_page_slide.jpg', 'left', 7, 1, '2026-06-18 17:34:09', '2026-06-18 17:35:24'),
(23, 2, 'PROFESSOR TANG MENGSHENG (Member)', 'Director Center for Pakistan Studies,\r\n\r\nPeking University in China', 'uploads/page-slides/1781822062_0_6a34726ec6076_page_slide.jpg', 'left', 8, 1, '2026-06-18 17:34:22', '2026-06-18 17:35:27');

-- --------------------------------------------------------

--
-- Table structure for table `hero_slides`
--

CREATE TABLE `hero_slides` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtitle` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `button_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#',
  `sort_order` int UNSIGNED NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hero_slides`
--

INSERT INTO `hero_slides` (`id`, `title`, `subtitle`, `image`, `button_text`, `button_link`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(5, NULL, NULL, '1781032195_0_hero_slide.jpg', NULL, '#', 0, 1, '2026-06-09 14:09:55', '2026-06-09 14:09:55'),
(6, NULL, NULL, '1781032207_0_hero_slide.jpg', NULL, '#', 0, 1, '2026-06-09 14:10:07', '2026-06-09 14:10:07'),
(7, NULL, NULL, '1781032212_0_hero_slide.jpg', NULL, '#', 0, 1, '2026-06-09 14:10:12', '2026-06-09 14:10:12'),
(8, NULL, NULL, '1781032216_0_hero_slide.jpg', NULL, '#', 0, 1, '2026-06-09 14:10:16', '2026-06-09 14:10:16'),
(9, NULL, NULL, '1781032225_0_hero_slide.jpg', NULL, '#', 0, 1, '2026-06-09 14:10:25', '2026-06-09 14:10:25'),
(10, NULL, NULL, '1781032239_0_hero_slide.jpg', NULL, '#', 0, 1, '2026-06-09 14:10:39', '2026-06-09 14:10:39'),
(11, NULL, NULL, '1781032247_0_hero_slide.jpg', NULL, '#', 0, 1, '2026-06-09 14:10:47', '2026-06-09 14:10:47'),
(12, NULL, NULL, '1781032252_0_hero_slide.jpg', NULL, '#', 0, 1, '2026-06-09 14:10:52', '2026-06-09 14:10:52');

-- --------------------------------------------------------

--
-- Table structure for table `home_pages`
--

CREATE TABLE `home_pages` (
  `id` bigint UNSIGNED NOT NULL,
  `hero_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hero_subtitle` text COLLATE utf8mb4_unicode_ci,
  `hero_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `about_label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `about_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `about_description` text COLLATE utf8mb4_unicode_ci,
  `about_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vision_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vision` text COLLATE utf8mb4_unicode_ci,
  `mission_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mission` text COLLATE utf8mb4_unicode_ci,
  `news_bg` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `location_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_description` text COLLATE utf8mb4_unicode_ci,
  `location1_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location1_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location2_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location2_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location3_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location3_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `header_logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `header_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `header_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `top_location_1_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'SMCHS',
  `top_location_1_url` text COLLATE utf8mb4_unicode_ci,
  `top_location_2_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'HYDERI',
  `top_location_2_url` text COLLATE utf8mb4_unicode_ci,
  `top_location_3_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'GULSHAN',
  `top_location_3_url` text COLLATE utf8mb4_unicode_ci,
  `header_facebook_url` text COLLATE utf8mb4_unicode_ci,
  `header_twitter_url` text COLLATE utf8mb4_unicode_ci,
  `header_instagram_url` text COLLATE utf8mb4_unicode_ci,
  `top_header_is_active` tinyint(1) NOT NULL DEFAULT '1',
  `location1_map_url` text COLLATE utf8mb4_unicode_ci,
  `location2_map_url` text COLLATE utf8mb4_unicode_ci,
  `location3_map_url` text COLLATE utf8mb4_unicode_ci,
  `video_tour_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_tour_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_tour_url` text COLLATE utf8mb4_unicode_ci,
  `video_tour_poster` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_tour_is_active` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `home_pages`
--

INSERT INTO `home_pages` (`id`, `hero_title`, `hero_subtitle`, `hero_image`, `about_label`, `about_title`, `about_description`, `about_image`, `vision_title`, `vision`, `mission_title`, `mission`, `news_bg`, `created_at`, `updated_at`, `location_title`, `location_description`, `location1_name`, `location1_image`, `location2_name`, `location2_image`, `location3_name`, `location3_image`, `header_logo`, `header_phone`, `header_email`, `top_location_1_name`, `top_location_1_url`, `top_location_2_name`, `top_location_2_url`, `top_location_3_name`, `top_location_3_url`, `header_facebook_url`, `header_twitter_url`, `header_instagram_url`, `top_header_is_active`, `location1_map_url`, `location2_map_url`, `location3_map_url`, `video_tour_title`, `video_tour_file`, `video_tour_url`, `video_tour_poster`, `video_tour_is_active`) VALUES
(1, 'WELCOME TO KASB INSTITUTE OF TECHNOLOGY', 'KASB Institute of Technology Private Limited is the parent body of KASB Institute of Technology (KASBIT) that was established in September 1999, through Registration with Securities and Exchange Commission of Pakistan. It is the first Private Sector Institute of Higher Education that was registered as a Corporate body. Since its inception, KASBIT has achieved many mile-stones that advocate its high standard, excellence and quality recognition…', 'uploads/home/1780511982_hero.jpg', NULL, 'KASB INSTITUTE OF TECHNOLOGY', 'KASB Institute of Technology Private Limited is the parent body of KASB Institute of Technology (KASBIT) that was established in September 1999, through Registration with Securities and Exchange Commission of Pakistan. It is the first Private Sector Institute of Higher Education that was registered as a Corporate body. Since its inception, KASBIT has achieved many mile-stones that advocate its high standard, excellence and quality recognition…', NULL, 'The vision of KASBIT', 'Promoting excellence in education through holistic, transformative and innovative learning to develop entrepreneurial innovators, responsible leader and change masters.', 'The mission of KASBIT', 'To cultivate value-based growth by leveraging on high quality research, fostering the spirit of national development, promoting creativity and encouraging entrepreneurship.', 'uploads/home/1780954018_news.jpg', '2026-06-03 13:00:36', '2026-06-18 17:39:58', 'Locations', 'Absolute Location: Pinpoints a spot on Earth using exact systems like latitude and longitude (e.g., coordinates) or a street address.Relative Location: Describes where something is in relation to other known places, using directional terms (north, south, east, west) and proximity (near, adjacent, 5 miles from).', 'SMCHS', 'uploads/home/1780512372_location1.jpg', 'Hyderi', 'uploads/home/1780512372_location2.jpg', 'Gulshan', 'uploads/home/1780512372_location3.jpg', '1780512372_logo.png', '(021) 36634355', 'makozagif@mailinator.com', 'SMCHS', NULL, 'HYDERI', NULL, 'GULSHAN', NULL, NULL, 'https://x.com/kasbitofficial', 'https://www.instagram.com/kasbit_official/', 1, NULL, NULL, NULL, 'VIDEO TOUR OF KASBIT', NULL, 'https://youtu.be/QvJF1YH2KCM', 'uploads/video-tour/1780962847_kasbit_tour_poster.png', 1);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_06_03_171208_create_home_pages_table', 1),
(5, '2026_06_03_180823_add_locations_to_home_pages_table', 1),
(6, '2026_06_03_181645_add_header_to_home_pages_table', 1),
(7, '2026_06_03_184143_add_location_maps_to_home_pages_table', 1),
(8, '2026_06_08_181900_create_header_menus_table', 2),
(9, '2026_06_08_190500_create_hero_slides_table', 3),
(10, '2026_06_09_000100_add_about_titles_to_home_pages_table', 4),
(11, '2026_06_09_001000_create_programs_table', 5),
(12, '2026_06_09_002000_add_programs_background_to_home_pages_table', 6),
(13, '2026_06_09_003000_add_programs_text_to_home_pages_table', 7),
(14, '2026_06_09_004000_create_news_items_table', 8),
(15, '2026_06_09_005000_remove_homepage_programs_feature', 9),
(16, '2026_06_09_006000_expand_location_map_urls', 10),
(17, '2026_06_09_007000_add_video_tour_to_home_pages', 11),
(18, '2026_06_09_008000_create_footer_settings_table', 12),
(19, '2026_06_10_000100_make_hero_slide_title_nullable', 13),
(20, '2026_06_10_010000_add_top_header_fields_to_home_pages_table', 14),
(21, '2026_06_18_210000_add_admin_sidebar_fields_to_header_menus_table', 15),
(22, '2026_06_18_220000_sync_website_sections_to_header_menus', 16),
(23, '2026_06_18_230000_correct_header_menu_subcategories', 17),
(24, '2026_06_18_240000_connect_about_header_links', 18),
(25, '2026_06_18_250000_create_header_menu_pages_table', 19),
(26, '2026_06_18_260000_normalize_about_menu_links', 20),
(27, '2026_06_18_270000_create_header_menu_page_slides_table', 21),
(28, '2026_06_18_280000_move_page_content_into_section_history', 22),
(29, '2026_06_18_290000_add_layout_to_page_sections_and_create_about_parent_page', 23);

-- --------------------------------------------------------

--
-- Table structure for table `news_items`
--

CREATE TABLE `news_items` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#',
  `sort_order` int UNSIGNED NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `news_items`
--

INSERT INTO `news_items` (`id`, `title`, `description`, `image`, `link`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(3, 'News & Updates', 'Notice for Prospective Students\r\nAdmissions Open Fall 2023\r\n\r\n\r\nMerit Scholarship\r\n75% and above 25% Scholarship\r\nSiblings 20% Scholarship\r\nMonthly Financial Plan\r\nNeed based Scholarship\r\nEntry test will be held on 21st September 2024\r\nResult Awaiting Students can also apply\r\n\r\n\r\n111-KASBIT (527248)', '1780954439_2_6a2735476b769_news.jpg', '#', 0, 1, '2026-06-08 16:33:59', '2026-06-18 17:39:11');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('29lzdawRPaTVVdI2ju4WkNB4DZf0zC29udf95nvC', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', 'eyJfdG9rZW4iOiJONkhieXhVY2MzQURSajdrMU5KVGs3NTlhc0FOQ3hlYjhoZDRxQ0V3IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pblwvbG9naW4iLCJyb3V0ZSI6ImxvZ2luIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2FkbWluXC9sb2dpbiJ9fQ==', 1781820382),
('69eNo2yqvO17Le3hmfVESwjQCBzz9hQgrdH7kD6Q', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', 'eyJfdG9rZW4iOiJadXR1eE5RMDQ4RkxUNlNKUE1QdGZJb21uTjV5dUNZdHFuekNwTGZ1IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9wYWdlc1wvaW50ZXJuYXRpb25hbC1ib2FyZC1vZi1hZHZpc29ycyIsInJvdXRlIjoicGFnZXMuc2hvdyJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1781820889),
('78mrBkOykNfJkIqFLNQle9GAB2a0OWZsZ4eMwJpe', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', 'eyJfdG9rZW4iOiJRVnZSR28yZ3N5STdwT2VwVVlzd1E0Y3gxQXRucW5jZU9yQXZ0UGkyIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9wYWdlc1wvbWVzc2FnZSIsInJvdXRlIjoicGFnZXMuc2hvdyJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1781813637),
('GsXIA435FrieFQ1EVnar6JIS0s8xyGnjeHgkbNDs', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', 'eyJfdG9rZW4iOiIxeWJDd2t3U3NhTERQNTcwZHpNNkJUaVZ0RDBpNmxmVW1udEhYT2JmIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9wYWdlc1wvaW50ZXJuYXRpb25hbC1ib2FyZC1vZi1hZHZpc29ycyIsInJvdXRlIjoicGFnZXMuc2hvdyJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1781816445),
('Kfo1W6Cf9f9ZVNTLFh4gn8jDDlvpOJGsWlyFsUkO', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', 'eyJfdG9rZW4iOiJjWUdnblpSWTluMjdBSjhBM3RXRHBlN2JHQzg0NnJXa1M0ME9lT1B0IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hYm91dCIsInJvdXRlIjoiYWJvdXQifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1781813843),
('kuNyeaVKKbbvvTLMzxZIUU6IPgWGNxmbZsA3Y1J9', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', 'eyJfdG9rZW4iOiI2YTNTVjlFNkVHTWd6TnJUVmw5dGRBSkJ1Q3dmdUdLSUVWTjRuQXB6IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9wYWdlc1wvaW50ZXJuYXRpb25hbC1ib2FyZC1vZi1hZHZpc29ycyIsInJvdXRlIjoicGFnZXMuc2hvdyJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1781817263),
('ON8K5DJ1GfUJzXRH77orv7tnQsrIS8RrUyjRe5yr', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', 'eyJfdG9rZW4iOiJKaWI2Z3BHQk8xekgyTzVKUU9paDRBOHVZN0EzWWFKN1RTNU01SHJ0IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9wYWdlc1wvaW50ZXJuYXRpb25hbC1ib2FyZC1vZi1hZHZpc29ycyIsInJvdXRlIjoicGFnZXMuc2hvdyJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1781816522),
('svXf27pEadvdx2VjSamdagQ7SbgYvuVb5HlPK74d', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', 'eyJfdG9rZW4iOiJRV0FSTzRiNWN0OExWeE1UVnYzNXhOUmNmS2k3QW5yVVdjbXBNOXZiIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hYm91dCIsInJvdXRlIjoiYWJvdXQifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1781813639),
('TWDfLflyve25f5QJfP9V2i6Rqy95jzdVzqaTeNMP', 1, '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', 'eyJfdG9rZW4iOiI2UHNCRzlrTllSWWZsS25mcDU2bU1jQm5BY3JoNWVSUmRndWxiMEtYIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxfQ==', 1781823373);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'KASBIT Admin', 'admin@kasbit.com', NULL, '$2y$12$Iyu6BpZyVYOVJh785rqBpeTfp4gxJFCj730gcBRbUeXvRFmX1G8iW', NULL, '2026-06-08 13:30:51', '2026-06-08 13:30:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indexes for table `footer_settings`
--
ALTER TABLE `footer_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `header_menus`
--
ALTER TABLE `header_menus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `header_menus_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `header_menu_pages`
--
ALTER TABLE `header_menu_pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `header_menu_pages_header_menu_id_unique` (`header_menu_id`),
  ADD UNIQUE KEY `header_menu_pages_slug_unique` (`slug`);

--
-- Indexes for table `header_menu_page_slides`
--
ALTER TABLE `header_menu_page_slides`
  ADD PRIMARY KEY (`id`),
  ADD KEY `header_menu_page_slides_header_menu_page_id_foreign` (`header_menu_page_id`);

--
-- Indexes for table `hero_slides`
--
ALTER TABLE `hero_slides`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `home_pages`
--
ALTER TABLE `home_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news_items`
--
ALTER TABLE `news_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `footer_settings`
--
ALTER TABLE `footer_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `header_menus`
--
ALTER TABLE `header_menus`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;

--
-- AUTO_INCREMENT for table `header_menu_pages`
--
ALTER TABLE `header_menu_pages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `header_menu_page_slides`
--
ALTER TABLE `header_menu_page_slides`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `hero_slides`
--
ALTER TABLE `hero_slides`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `home_pages`
--
ALTER TABLE `home_pages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `news_items`
--
ALTER TABLE `news_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `header_menus`
--
ALTER TABLE `header_menus`
  ADD CONSTRAINT `header_menus_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `header_menus` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `header_menu_pages`
--
ALTER TABLE `header_menu_pages`
  ADD CONSTRAINT `header_menu_pages_header_menu_id_foreign` FOREIGN KEY (`header_menu_id`) REFERENCES `header_menus` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `header_menu_page_slides`
--
ALTER TABLE `header_menu_page_slides`
  ADD CONSTRAINT `header_menu_page_slides_header_menu_page_id_foreign` FOREIGN KEY (`header_menu_page_id`) REFERENCES `header_menu_pages` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
