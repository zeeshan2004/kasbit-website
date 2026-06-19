-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 19, 2026 at 10:33 PM
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
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
(3, NULL, 'Programs', NULL, 'fa-solid fa-book-open', 1, 3, 1, '2026-06-08 13:28:22', '2026-06-19 13:31:45'),
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
(96, 3, 'Associate Degree Program 2 Years', NULL, 'fa-solid fa-book-open', 0, 1, 1, '2026-06-18 11:13:15', '2026-06-19 13:28:27'),
(97, 3, 'Undergraduate', NULL, 'fa-solid fa-book-open', 0, 2, 1, '2026-06-18 11:13:15', '2026-06-19 14:56:07'),
(98, 3, 'Graduate Programs', NULL, 'fa-solid fa-book-open', 0, 3, 1, '2026-06-18 11:13:15', '2026-06-19 15:11:01'),
(99, 3, 'Postgraduate', '/pages/postgraduate', 'fa-solid fa-book-open', 0, 4, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:27'),
(100, 3, 'Fee Structure', '/pages/fee-structure', 'fa-solid fa-circle', 0, 5, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:27'),
(101, 3, 'Program Profile', '/pages/program-profile', 'fa-solid fa-book-open', 0, 6, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:27'),
(102, 4, 'Admission Policy', '/pages/admission-policy', 'fa-solid fa-shield-halved', 0, 1, 1, '2026-06-18 11:13:15', '2026-06-18 13:04:27'),
(103, 4, 'Online Admission Portal', 'https://onlineadmission.kasbit.edu.pk/', 'fa-solid fa-circle', 0, 2, 1, '2026-06-18 11:13:15', '2026-06-19 16:08:24'),
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
(142, NULL, 'Home', '/', 'fa-solid fa-book-open', 1, 1, 1, '2026-06-18 12:08:54', '2026-06-18 12:59:26'),
(143, 96, 'Associate Degree in Computer Science', '/pages/associate-degree-program-2-years', 'fa-solid fa-folder', 0, 2, 1, '2026-06-19 12:33:13', '2026-06-19 13:16:04'),
(144, 96, 'Associate Degree In Digital Marketing', '/pages/associate-degree-in-digital-marketing', 'fa-solid fa-folder', 0, 3, 1, '2026-06-19 12:39:06', '2026-06-19 12:56:03'),
(145, 96, 'Associate Degree In Commerce (Previous B.COM)', '/pages/associate-degree-in-commerce-previous-bcom', 'fa-solid fa-graduation-cap', 0, 1, 1, '2026-06-19 12:54:56', '2026-06-19 13:25:40'),
(148, 97, 'BBA', '/pages/bba', 'fa-solid fa-graduation-cap', 0, 1, 1, '2026-06-19 12:54:56', '2026-06-19 14:56:07'),
(149, 97, 'BS (AF)', '/pages/bs-accounting-finance', 'fa-solid fa-graduation-cap', 0, 2, 1, '2026-06-19 12:54:56', '2026-06-19 14:56:07'),
(150, 97, 'BS Computer Science', '/pages/bs-computer-science', 'fa-solid fa-graduation-cap', 0, 3, 1, '2026-06-19 12:54:56', '2026-06-19 14:56:07'),
(151, 97, 'BBA 2 Years (After 14 Years of Education)', '/pages/bba-2-years', 'fa-solid fa-graduation-cap', 0, 4, 1, '2026-06-19 12:54:56', '2026-06-19 14:56:07'),
(152, 98, 'MBA (36) after 4 years Bachelors', '/pages/mba-36-after-4-years-bachelors', 'fa-solid fa-graduation-cap', 0, 1, 1, '2026-06-19 12:54:56', '2026-06-19 15:11:01'),
(153, 98, 'MBA (66) After 16 Year Non Business Schooling', '/pages/mba-66-after-16-years-non-business', 'fa-solid fa-graduation-cap', 0, 2, 1, '2026-06-19 12:54:56', '2026-06-19 15:11:01'),
(154, 98, 'MS', '/pages/ms', 'fa-solid fa-graduation-cap', 0, 3, 1, '2026-06-19 12:54:56', '2026-06-19 15:11:01'),
(155, 99, 'Ph.D', '/pages/phd', 'fa-solid fa-graduation-cap', 0, 1, 1, '2026-06-19 12:54:56', '2026-06-19 12:54:56');

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
  `pdf_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pdf_original_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `accent_color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#07559d',
  `show_image` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `header_menu_pages`
--

INSERT INTO `header_menu_pages` (`id`, `header_menu_id`, `slug`, `eyebrow`, `title`, `subtitle`, `content`, `image`, `pdf_file`, `pdf_original_name`, `accent_color`, `show_image`, `created_at`, `updated_at`) VALUES
(1, 94, 'message', 'About us', 'Message', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:37:51'),
(2, 95, 'international-board-of-advisors', 'About us', 'International Board of Advisors', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(3, 96, 'associate-degree-program-2-years', 'Programs', 'Associate Degree Program 2 Years', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(4, 97, 'undergraduate-program', 'Programs', 'Undergraduate', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-19 14:56:07'),
(5, 98, 'graduate-program', 'Programs', 'Graduate Programs', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-19 15:11:01'),
(6, 99, 'postgraduate', 'Programs', 'Postgraduate', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(7, 100, 'fee-structure', 'Programs', 'Fee Structure', NULL, NULL, NULL, 'uploads/page-pdfs/b50c9133-e7e0-4c41-8cfe-080f957958c5.pdf', 'Fee-Revision-2026-29-Jan-26-1.pdf', '#07559d', 1, '2026-06-18 13:04:27', '2026-06-19 16:05:38'),
(8, 101, 'program-profile', 'Programs', 'Program Profile', NULL, NULL, NULL, 'uploads/page-pdfs/c25d5b85-2c60-47db-81c8-421d343da454.pdf', 'Program-profile.-all-program-1.pdf', '#07559d', 1, '2026-06-18 13:04:27', '2026-06-19 16:06:28'),
(9, 102, 'admission-policy', 'Admissions', 'Admission Policy', NULL, NULL, NULL, 'uploads/page-pdfs/e85c7c09-42b6-4d2a-99c1-3a12c4985fa0.pdf', 'Admission-policy-Final.pdf', '#07559d', 1, '2026-06-18 13:04:27', '2026-06-19 16:19:20'),
(10, 103, 'online-admission-portal', 'Admissions', 'Online Admission Portal', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(11, 104, 'deans-message', 'Academics', 'Dean\'s Message', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(12, 105, 'faculty', 'Academics', 'Faculty', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(13, 106, 'academic-calendar', 'Academics', 'Academic Calendar', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(14, 107, 'academic-departments', 'Academics', 'Academic Departments', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(15, 108, 'academic-scholarship', 'Academics', 'Academic Scholarship', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(16, 109, 'rules-regulations', 'Academics', 'Rules & Regulations', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(17, 110, 'facilities-services', 'Life @ Kasbit', 'Facilities & Services', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(18, 111, 'life-on-premises', 'Life @ Kasbit', 'Life on Premises', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(19, 112, 'student-societies', 'Life @ Kasbit', 'Student Societies', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(20, 113, 'event-gallery', 'Life @ Kasbit', 'Event Gallery', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(21, 114, 'quality-enhancement-cell-message', 'QEC', 'Quality Enhancement Cell Message', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(22, 115, 'quality-policy-statement', 'QEC', 'Quality Policy Statement', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(23, 116, 'qec-structure', 'QEC', 'QEC Structure', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(24, 117, 'qec-staff-details', 'QEC', 'QEC Staff Details', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(25, 118, 'functions-of-qec', 'QEC', 'Functions of QEC', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(26, 119, 'student-survey-forms', 'QEC', 'Student Survey Forms', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(27, 120, 'qec-activity-calender', 'QEC', 'QEC Activity Calender', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(28, 121, 'qec-activities', 'QEC', 'QEC Activities', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(29, 122, 'yearly-progress-report', 'QEC', 'Yearly Progress Report', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(30, 123, 'self-assessment-report', 'QEC', 'Self Assessment Report', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(31, 124, 'memberships', 'QEC', 'Memberships', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(32, 125, 'at-pt-notification', 'QEC', 'AT / PT Notification', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(33, 126, 'sdg', 'QEC', 'SDG', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(34, 127, 'introduction', 'ORIC', 'Introduction', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(35, 128, 'research-journals', 'ORIC', 'Research Journals', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(36, 129, 'conferences', 'ORIC', 'Conferences', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(37, 130, 'trainings-workshops', 'ORIC', 'Trainings & Workshops', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(38, 131, 'research-project-thesis', 'ORIC', 'Research Project / Thesis', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:27', '2026-06-18 13:04:27'),
(39, 132, 'industrial-linkage', 'ORIC', 'Industrial Linkage', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:28', '2026-06-18 13:04:28'),
(40, 133, 'faculty-login', 'Login', 'Faculty Login', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:28', '2026-06-18 13:04:28'),
(41, 134, 'student-login', 'Login', 'Student Login', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:28', '2026-06-18 13:04:28'),
(42, 135, 'results', 'Login', 'Results', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:28', '2026-06-18 13:04:28'),
(43, 136, 'convocation-registration', 'Login', 'Convocation Registration', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:28', '2026-06-18 13:04:28'),
(44, 137, 'office-of-alumni', 'Alumni', 'Office of Alumni', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:28', '2026-06-18 13:04:28'),
(45, 138, 'alumni-login', 'Alumni', 'Alumni Login', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:28', '2026-06-18 13:04:28'),
(46, 139, 'kasbit-e-library', 'E Library', 'Kasbit E Library', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:28', '2026-06-18 13:04:28'),
(47, 140, 'e-library-resources', 'E Library', 'E Library Resources', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:04:28', '2026-06-18 13:04:28'),
(48, 13, 'about-us', 'About KASBIT', 'About us', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-18 13:47:52', '2026-06-18 13:47:52'),
(49, 3, 'programs', 'Website Page', 'Programs', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-19 11:47:38', '2026-06-19 11:47:38'),
(50, 143, 'associate-degree-in-computer-science', 'Programs', 'Associate Degree in Computer Science', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-19 12:33:13', '2026-06-19 12:33:13'),
(51, 144, 'associate-degree-in-digital-marketing', 'Programs', 'Associate Degree In Digital Marketing', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-19 12:39:06', '2026-06-19 12:39:06'),
(52, 145, 'associate-degree-in-commerce-previous-bcom', 'Programs', 'Associate Degree In Commerce (Previous B.COM)', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-19 12:54:56', '2026-06-19 12:54:56'),
(55, 148, 'bba', 'Undergraduate', 'BBA', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-19 12:54:56', '2026-06-19 14:56:07'),
(56, 149, 'bs-accounting-finance', 'Undergraduate', 'BS (AF)', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-19 12:54:56', '2026-06-19 14:56:07'),
(57, 150, 'bs-computer-science', 'Undergraduate', 'BS Computer Science', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-19 12:54:56', '2026-06-19 14:56:07'),
(58, 151, 'bba-2-years', 'Undergraduate', 'BBA 2 Years (After 14 Years of Education)', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-19 12:54:56', '2026-06-19 14:56:07'),
(59, 152, 'mba-36-after-4-years-bachelors', 'Graduate Programs', 'MBA (36) after 4 years Bachelors', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-19 12:54:56', '2026-06-19 15:11:01'),
(60, 153, 'mba-66-after-16-years-non-business', 'Graduate Programs', 'MBA (66) After 16 Year Non Business Schooling', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-19 12:54:56', '2026-06-19 15:11:01'),
(61, 154, 'ms', 'Graduate Programs', 'MS', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-19 12:54:56', '2026-06-19 15:11:01'),
(62, 155, 'phd', 'Programs', 'Ph.D', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-19 12:54:56', '2026-06-19 12:54:56'),
(67, 4, 'admissions', 'Website Page', 'Admissions', NULL, NULL, NULL, NULL, NULL, '#07559d', 1, '2026-06-19 16:07:36', '2026-06-19 16:07:36');

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
(23, 2, 'PROFESSOR TANG MENGSHENG (Member)', 'Director Center for Pakistan Studies,\r\n\r\nPeking University in China', 'uploads/page-slides/1781822062_0_6a34726ec6076_page_slide.jpg', 'left', 8, 1, '2026-06-18 17:34:22', '2026-06-18 17:35:27'),
(27, 3, 'Course Work and Duration', '2-Year, 4-Semester, (26 Courses), 74 Credit Hours\r\n\r\nELIGIBILITY\r\n• For admission in the Associate Degree Program in Computer Science (ADCS), the applicant must have completed 12 Years of Education with atleast 50% marks in (HSC) Pre-Engineering or Pre-Medical examination. An applicant having a combination of Physics, Mathematics and Computer Science is also eligible.\r\n\r\n• Or, GCE (A levels) in Mathematics, Physics and Chemistry. Applicant having A levels or other foreign qualifications must provide an equivalence certificate with at least 50% marks, issued by Inter Boards Coordination Commission (IBCC) or an equivalent, qualification from a recognized Board .\r\n\r\n• Or, At least 50% marks in Diploma of Associate Engineering Examination, for admission\r\n\r\n• The applicant has to take an institute based admission test\r\n\r\n• On successfully qualifying the Admission Test, the applicant shall be called for a Final Interview, in which his/her Admission shall be confirmed.', NULL, 'left', 0, 1, '2026-06-19 11:49:01', '2026-06-19 11:49:01'),
(28, 50, 'Course Work and Duration', '2-Year, 4-Semester, (26 Courses), 74 Credit Hours\r\n\r\nELIGIBILITY\r\n• For admission in the Associate Degree Program in Computer Science (ADCS), the applicant must have completed 12 Years of Education with atleast 50% marks in (HSC) Pre-Engineering or Pre-Medical examination. An applicant having a combination of Physics, Mathematics and Computer Science is also eligible.\r\n\r\n• Or, GCE (A levels) in Mathematics, Physics and Chemistry. Applicant having A levels or other foreign qualifications must provide an equivalence certificate with at least 50% marks, issued by Inter Boards Coordination Commission (IBCC) or an equivalent, qualification from a recognized Board .\r\n\r\n• Or, At least 50% marks in Diploma of Associate Engineering Examination, for admission\r\n\r\n• The applicant has to take an institute based admission test\r\n\r\n• On successfully qualifying the Admission Test, the applicant shall be called for a Final Interview, in which his/her Admission shall be confirmed.', NULL, 'left', 0, 1, '2026-06-19 12:34:58', '2026-06-19 12:34:58'),
(29, 51, 'Course Work and Duration', 'Based on 2-Year, 4-Semester, 24 Courses, 68 Credit Hours\r\n\r\nELIGIBILITY\r\n• For admission in the Associate Degree Program in Digital Marketing, the applicant must have completed 12 Years of Education or A level with Minimum two C’s / excluding General paper & Urdu) or an equivalent, qualification from a recognized Board.\r\n• The applicant has to take an institute based Admission Test\r\n• On successfully qualifying the Admission Test, the applicant shall be called for a final interview, in which his/her Admission shall be confirmed\r\n• Student seeking credit transfer may also apply for admission', NULL, 'left', 0, 1, '2026-06-19 12:39:53', '2026-06-19 12:39:53'),
(30, 52, 'Course Work and Duration', '2-Year, 4-Semester, 23 Courses, 65 Credit Hours\r\n\r\nELIGIBILITY\r\n• For admission in the Associate Degree Program (ADP), the applicant must have completed 12 Years of Education or A level with Minimum two C’s / excluding General paper & Urdu) or an equivalent, qualification from a recognized Board .\r\n\r\n• The applicant has to take an institute based Admission Test\r\n\r\n• On successfully qualifying the Admission Test, the applicant shall be called for a final interview, in which his/her Admission shall be confirmed\r\n\r\n• Student seeking credit transfer may also apply for admission', NULL, 'left', 0, 1, '2026-06-19 14:43:23', '2026-06-19 14:43:23'),
(31, 55, 'Course Work and Duration', '4-Year, 8-Semester, 47 Courses, 140 Credit Hours\r\n\r\nELIGIBILITY\r\n• For admission in the BBA Program, the applicant must have completed 12 Years of Education with Minimum 2nd Division or A level with Minimum two C’s / (excluding General paper & Urdu) or an equivalent, qualification from a recognized Board.\r\n• The applicant has to take an institute based Admission Test\r\n• On successfully qualifying the Admission Test, the applicant shall be called for a final interview, in which his/her Admission shall be confirmed\r\n• Student seeking credit transfer may also apply for admission', NULL, 'left', 0, 1, '2026-06-19 15:04:38', '2026-06-19 15:04:38'),
(32, 56, 'Course Work and Duration', '4-Year, 8-Semester, (48 Courses), 143 CH Degree Program\r\n\r\nELIGIBILITY\r\n• For admission in the BS(AF) Program, the applicant must have completed 12 Years of Education with minimum 2nd  Division or A Level with minimum two C’s / (Excluding General Paper & Urdu) or equivalent qualification from recognized board.\r\n\r\n• The applicant has to take an institute based admission test\r\n\r\n• On successfully qualifying the Admission Test, the applicant shall be called for a Final Interview, in which his/her Admission shall be confirmed.', NULL, 'left', 0, 1, '2026-06-19 15:05:15', '2026-06-19 15:05:15'),
(33, 57, 'Course Work and Duration', '4-Year, 8-Semester, (42 Courses + 2 FYP), 130 CH Degree Program\r\n\r\nELIGIBILITY\r\n• For admission in the BS(CS) Program, the applicant must have completed 12 Years of Education with atleast 50% marks in (HSC) Pre-Engineering or Pre-Medical examination. An applicant having a combination of Physics, Mathematics and Computer Science is also eligible.\r\n\r\n• Or, GCE (A levels) in Mathematics, Physics and Chemistry. Applicant having A levels or other foreign qualifications must provide an equivalence certificate with at least 50% marks, issued by Inter Boards Coordination Commission (IBCC) or an equivalent, qualification from a recognized Board .\r\n\r\n• The applicant has to take an institute based admission test\r\n\r\n• On successfully qualifying the Admission Test, the applicant shall be called for a Final Interview, in which his/her Admission shall be confirmed.', NULL, 'left', 0, 1, '2026-06-19 15:06:27', '2026-06-19 15:06:27'),
(34, 58, 'Course Work and Duration', '2-Year, 4-Semester, 24 Courses, 75 Credit Hours\r\n\r\nEligibility\r\n• For admission in the BBA 2 years’ program, the application must have completed 14 years of education B.COM. BA, BSC or ADP with minimum 2nd Division and other equivalent qualification. \r\n\r\n• The applicant has to take an institute based Admission Test\r\n• On successfully qualifying the Admission Test, the applicant shall be called for a final interview, in which his/her Admission shall be confirmed\r\n• Student seeking credit transfer may also apply for admission', NULL, 'left', 0, 1, '2026-06-19 15:07:46', '2026-06-19 15:07:46'),
(35, 59, 'Course Work and Duration', 'Two years degree program\r\n\r\nIntake: Twice a year (Spring & Fall)\r\n\r\nTotal Courses: 10 Courses + (1 Project / 1 Thesis / 2 Courses)\r\n\r\nTotal Credit Hours : 36\r\n\r\nEligibility\r\n• For admission in the MBA (36 Credit Hours) program, the applicant must have completed 16 years of education in relevant field with minimum 2nd Division (Annual System) / 2.5 CGPA (Semester System).\r\n\r\n• The applicant has to take an institute based Admission Test\r\n\r\n• On successfully qualifying the Admission Test, the applicant shall be called for a final interview, in which his/her Admission shall be confirmed\r\n\r\n• Student seeking credit transfer may also apply for admission', NULL, 'left', 0, 1, '2026-06-19 15:13:29', '2026-06-19 15:13:29'),
(36, 59, 'Course Work and Duration', '2.5 years degree program\r\nIntake: Twice a year (Spring & Fall)\r\nTotal Courses : 20 courses  + 1 Project of 6 Credit Hours\r\nTotal Credit Hours : 66 \r\n\r\nEligibility\r\n• For admission in the MBA (66 Credit Hours) program, the applicant must have completed 16 years of non-business schooling education with minimum 2nd Division / 2.5 CGPA preferred (Semester System)\r\n\r\n• The applicant has to take an institute based Admission Test\r\n\r\n• On successfully qualifying the Admission Test, the applicant shall be called for a final interview, in which his/her Admission shall be confirmed\r\n\r\n• A student seeking credit transfer may also apply for admission', NULL, 'left', 0, 1, '2026-06-19 15:14:20', '2026-06-19 15:14:20'),
(37, 60, 'Course Work and Duration', '2.5 years degree program\r\nIntake: Twice a year (Spring & Fall)\r\nTotal Courses : 20 courses  + 1 Project of 6 Credit Hours\r\nTotal Credit Hours : 66 \r\n\r\nEligibility\r\n• For admission in the MBA (66 Credit Hours) program, the applicant must have completed 16 years of non-business schooling education with minimum 2nd Division / 2.5 CGPA preferred (Semester System)\r\n\r\n• The applicant has to take an institute based Admission Test\r\n\r\n• On successfully qualifying the Admission Test, the applicant shall be called for a final interview, in which his/her Admission shall be confirmed\r\n\r\n• A student seeking credit transfer may also apply for admission', NULL, 'left', 0, 1, '2026-06-19 15:48:42', '2026-06-19 15:48:42'),
(38, 61, 'Course Work and Duration', 'Based on 03 Semesters of 05 months each\r\n\r\nIntake: Twice a year (Spring & Fall)\r\n\r\nTotal Courses: 10 Courses + 1 Thesis\r\n\r\nTotal Credit Hours: 36\r\n\r\nMaximum Load: 04 Courses per Semester\r\n\r\nTime Duration: 1.5 till 4 years\r\n\r\nEligibility\r\n• For admission in the MS program, the applicant must have completed 16 years education in relevant field with minimum 1st Division (Annual System) / 2.5 CGPA (Semester System) from recognized Institute / University.\r\n\r\n• All students seeking admission to MS Program will have to qualify institute based admission test or GRE/NTS.\r\n\r\n• On successfully qualifying the Admission Test, the applicant shall be called for a final interview, in which his/her Admission shall be confirmed\r\n\r\n• Student seeking credit transfer may also apply for admission\r\n\r\nProgram Duration\r\n● Minimum: 1.5 years\r\n● Maximum: 4 years', NULL, 'left', 0, 1, '2026-06-19 15:51:22', '2026-06-19 15:51:22'),
(39, 62, 'Course Work and Duration', 'Based on semesters 5 months each\r\nIntake: Twice a year (Spring & Fall)\r\nTotal Courses : 6 Courses + 01 Dissertation\r\nTotal Credit Hours : 48\r\nMaximum Load: 3 Courses Per Semester \r\n\r\nEligibility\r\nTo apply for the PhD program, candidates must meet the following\r\nrequirements:\r\n\r\n● A minimum CGPA of 3.00 on a 4.00 scale (or equivalent) from an HEC-recognized\r\ninstitution.\r\n● At least 18 years of formal education (MS/MPhil or equivalent) in a relevant discipline.\r\n● A minimum of 60% marks in the GAT Subject Test (or 70% in the KASBIT entrance\r\ntest).\r\n● Successful completion of an interview conducted by the admissions committee.\r\n● Fulfillment of any additional requirements set by HEC.\r\n● Candidates with academic gaps may need to complete prerequisite courses.\r\n\r\nProgram Duration\r\n● Minimum: 3 years\r\n● Maximum: 7 years', NULL, 'left', 0, 1, '2026-06-19 15:51:57', '2026-06-19 15:51:57');

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
  `top_location_4_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'LMS',
  `top_location_4_url` text COLLATE utf8mb4_unicode_ci,
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

INSERT INTO `home_pages` (`id`, `hero_title`, `hero_subtitle`, `hero_image`, `about_label`, `about_title`, `about_description`, `about_image`, `vision_title`, `vision`, `mission_title`, `mission`, `news_bg`, `created_at`, `updated_at`, `location_title`, `location_description`, `location1_name`, `location1_image`, `location2_name`, `location2_image`, `location3_name`, `location3_image`, `header_logo`, `header_phone`, `header_email`, `top_location_1_name`, `top_location_1_url`, `top_location_2_name`, `top_location_2_url`, `top_location_3_name`, `top_location_3_url`, `top_location_4_name`, `top_location_4_url`, `header_facebook_url`, `header_twitter_url`, `header_instagram_url`, `top_header_is_active`, `location1_map_url`, `location2_map_url`, `location3_map_url`, `video_tour_title`, `video_tour_file`, `video_tour_url`, `video_tour_poster`, `video_tour_is_active`) VALUES
(1, 'WELCOME TO KASB INSTITUTE OF TECHNOLOGY', 'KASB Institute of Technology Private Limited is the parent body of KASB Institute of Technology (KASBIT) that was established in September 1999, through Registration with Securities and Exchange Commission of Pakistan. It is the first Private Sector Institute of Higher Education that was registered as a Corporate body. Since its inception, KASBIT has achieved many mile-stones that advocate its high standard, excellence and quality recognition…', 'uploads/home/1780511982_hero.jpg', NULL, 'KASB INSTITUTE OF TECHNOLOGY', 'KASB Institute of Technology Private Limited is the parent body of KASB Institute of Technology (KASBIT) that was established in September 1999, through Registration with Securities and Exchange Commission of Pakistan. It is the first Private Sector Institute of Higher Education that was registered as a Corporate body. Since its inception, KASBIT has achieved many mile-stones that advocate its high standard, excellence and quality recognition…', NULL, 'The vision of KASBIT', 'Promoting excellence in education through holistic, transformative and innovative learning to develop entrepreneurial innovators, responsible leader and change masters.', 'The mission of KASBIT', 'To cultivate value-based growth by leveraging on high quality research, fostering the spirit of national development, promoting creativity and encouraging entrepreneurship.', 'uploads/home/1780954018_news.jpg', '2026-06-03 13:00:36', '2026-06-19 16:28:27', 'Locations', 'Absolute Location: Pinpoints a spot on Earth using exact systems like latitude and longitude (e.g., coordinates) or a street address.Relative Location: Describes where something is in relation to other known places, using directional terms (north, south, east, west) and proximity (near, adjacent, 5 miles from).', 'SMCHS', 'uploads/home/1780512372_location1.jpg', 'Hyderi', 'uploads/home/1780512372_location2.jpg', 'Gulshan', 'uploads/home/1780512372_location3.jpg', '1780512372_logo.png', '(021) 36634355', 'makozagif@mailinator.com', 'SMCHS', NULL, 'HYDERI', NULL, 'GULSHAN', NULL, 'LMS', NULL, NULL, 'https://x.com/kasbitofficial', 'https://www.instagram.com/kasbit_official/', 1, NULL, NULL, NULL, 'VIDEO TOUR OF KASBIT', NULL, 'https://youtu.be/QvJF1YH2KCM', 'uploads/video-tour/1780962847_kasbit_tour_poster.png', 1);

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
(29, '2026_06_18_290000_add_layout_to_page_sections_and_create_about_parent_page', 23),
(30, '2026_06_19_010000_create_program_schema_tables', 24),
(31, '2026_06_19_020000_normalize_program_schema_table_order', 25),
(32, '2026_06_19_030000_add_nested_program_menu_items', 26),
(33, '2026_06_19_040000_merge_existing_adp_program_menu_items', 27),
(34, '2026_06_19_050000_allow_empty_header_menu_links', 28),
(35, '2026_06_20_010000_sync_undergraduate_program_hierarchy', 29),
(36, '2026_06_20_020000_sync_graduate_program_hierarchy', 30),
(37, '2026_06_20_030000_add_pdf_to_header_menu_pages', 31),
(38, '2026_06_20_040000_add_lms_top_header_item', 32);

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
-- Table structure for table `program_schema_rows`
--

CREATE TABLE `program_schema_rows` (
  `id` bigint UNSIGNED NOT NULL,
  `program_schema_table_id` bigint UNSIGNED NOT NULL,
  `semester` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `credit_hours` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_total` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `program_schema_rows`
--

INSERT INTO `program_schema_rows` (`id`, `program_schema_table_id`, `semester`, `subject`, `credit_hours`, `is_total`, `sort_order`, `created_at`, `updated_at`) VALUES
(29, 3, NULL, 'Programming Fundamentals (Computing Core Course)', '2 + 1', 0, 0, '2026-06-19 12:06:55', '2026-06-19 12:06:55'),
(30, 3, NULL, 'Application of Information and Communication Technologies (General Education)', '2 + 1', 0, 1, '2026-06-19 12:06:55', '2026-06-19 12:06:55'),
(31, 3, NULL, 'Discrete Structures (Core)', '3 + 0', 0, 2, '2026-06-19 12:06:55', '2026-06-19 12:06:55'),
(32, 3, NULL, 'Calculus and Analytical Geometry (Maths)', '3 + 0', 0, 3, '2026-06-19 12:06:55', '2026-06-19 12:06:55'),
(33, 3, NULL, 'Functional English (General Education)', '3 + 0', 0, 4, '2026-06-19 12:06:55', '2026-06-19 12:06:55'),
(34, 3, NULL, 'Social Science (Psychology)', '2', 0, 5, '2026-06-19 12:06:55', '2026-06-19 12:06:55'),
(35, 3, NULL, 'Semester Credit Hours', '17', 0, 6, '2026-06-19 12:06:55', '2026-06-19 12:06:55'),
(101, 4, NULL, 'Object Oriented Programming (Core)', '2 + 1', 0, 0, '2026-06-19 12:22:31', '2026-06-19 12:22:31'),
(102, 4, NULL, 'Database Systems (Core)', '2 + 1', 0, 1, '2026-06-19 12:22:31', '2026-06-19 12:22:31'),
(103, 4, NULL, 'Digital Logic Design (Core)', '2 + 1', 0, 2, '2026-06-19 12:22:31', '2026-06-19 12:22:31'),
(104, 4, NULL, 'Linear Algebra (Computing Support Course)', '3 + 0', 0, 3, '2026-06-19 12:22:31', '2026-06-19 12:22:31'),
(105, 4, NULL, 'Expository writing (General)', '3 + 0', 0, 4, '2026-06-19 12:22:31', '2026-06-19 12:22:31'),
(106, 4, NULL, 'Arts and Humanities (Creative Arts and Techniques)', '2 + 0', 0, 5, '2026-06-19 12:22:31', '2026-06-19 12:22:31'),
(107, 4, NULL, 'Semester Credit Hours', '17', 0, 6, '2026-06-19 12:22:31', '2026-06-19 12:22:31'),
(125, 8, NULL, 'Data Structures (Core)', '3 + 0', 0, 0, '2026-06-19 12:28:13', '2026-06-19 12:28:13'),
(126, 8, NULL, 'Information Security (Core)', '3 + 0', 0, 1, '2026-06-19 12:28:13', '2026-06-19 12:28:13'),
(127, 8, NULL, 'Artificial Intelligence (Core)', '3 + 0', 0, 2, '2026-06-19 12:28:13', '2026-06-19 12:28:13'),
(128, 8, NULL, 'Computer Networks (Core)', '3 + 0', 0, 3, '2026-06-19 12:28:13', '2026-06-19 12:28:13'),
(129, 8, NULL, 'Software Engineering (Core)', '3 + 0', 0, 4, '2026-06-19 12:28:13', '2026-06-19 12:28:13'),
(130, 8, NULL, 'Computer Organization and Assembly Language', '3 + 0', 0, 5, '2026-06-19 12:28:13', '2026-06-19 12:28:13'),
(131, 8, NULL, 'Semester Credit Hours', '18', 0, 6, '2026-06-19 12:28:13', '2026-06-19 12:28:13'),
(159, 9, NULL, 'Domain Elective 1 (Advanced Database Lab)', '3', 0, 0, '2026-06-19 12:31:22', '2026-06-19 12:31:22'),
(160, 9, NULL, 'Domain Elective 2 (Web Technologies Lab)', '3', 0, 1, '2026-06-19 12:31:22', '2026-06-19 12:31:22'),
(161, 9, NULL, 'Domain Elective 3 (Mobile Applications Development Lab)', '3', 0, 2, '2026-06-19 12:31:22', '2026-06-19 12:31:22'),
(162, 9, NULL, 'Domain Elective 4 (Advanced Programming Lab)', '3', 0, 3, '2026-06-19 12:31:22', '2026-06-19 12:31:22'),
(163, 9, NULL, 'Domain Elective 5 (Cyber Security Lab)', '3', 0, 4, '2026-06-19 12:31:22', '2026-06-19 12:31:22'),
(164, 9, NULL, 'Islamic Studies /Ethics (General)', '2', 0, 5, '2026-06-19 12:31:22', '2026-06-19 12:31:22'),
(165, 9, NULL, 'Ideology and Constitution of Pakistan', '2', 0, 6, '2026-06-19 12:31:22', '2026-06-19 12:31:22'),
(166, 9, NULL, 'Entrepreneurship', '2', 0, 7, '2026-06-19 12:31:22', '2026-06-19 12:31:22'),
(167, 9, NULL, 'Semester Credit Hours', '21', 0, 8, '2026-06-19 12:31:22', '2026-06-19 12:31:22'),
(206, 10, NULL, 'Digital Marketing Fundamentals', '3', 0, 0, '2026-06-19 12:43:43', '2026-06-19 12:43:43'),
(207, 10, NULL, 'Business Mathematics', '3', 0, 1, '2026-06-19 12:43:43', '2026-06-19 12:43:43'),
(208, 10, NULL, 'Functional English', '3', 0, 2, '2026-06-19 12:43:43', '2026-06-19 12:43:43'),
(209, 10, NULL, 'Fundamentals of Management', '3', 0, 3, '2026-06-19 12:43:43', '2026-06-19 12:43:43'),
(210, 10, NULL, 'Islamic Studies/Religious Education', '2', 0, 4, '2026-06-19 12:43:43', '2026-06-19 12:43:43'),
(211, 10, NULL, 'Ideology and Constitution of Pakistan', '2', 0, 5, '2026-06-19 12:43:43', '2026-06-19 12:43:43'),
(212, 10, NULL, 'Semester Credit Hours', '16', 0, 6, '2026-06-19 12:43:43', '2026-06-19 12:43:43'),
(213, 11, NULL, 'Social Media, Content, and Strategy', '3', 0, 0, '2026-06-19 12:43:46', '2026-06-19 12:43:46'),
(214, 11, NULL, 'Marketing Automation', '3', 0, 1, '2026-06-19 12:43:46', '2026-06-19 12:43:46'),
(215, 11, NULL, 'Functional English', '3', 0, 2, '2026-06-19 12:43:47', '2026-06-19 12:43:47'),
(216, 11, NULL, 'Applications of Information and Communication Technologies', '3', 0, 3, '2026-06-19 12:43:47', '2026-06-19 12:43:47'),
(217, 11, NULL, 'Business Statistics', '3', 0, 4, '2026-06-19 12:43:47', '2026-06-19 12:43:47'),
(218, 11, NULL, 'Expository Writing', '3', 0, 5, '2026-06-19 12:43:47', '2026-06-19 12:43:47'),
(219, 11, NULL, 'Microeconomics Principles', '3', 0, 6, '2026-06-19 12:43:47', '2026-06-19 12:43:47'),
(220, 11, NULL, 'Semester Credit Hours', '18', 0, 7, '2026-06-19 12:43:47', '2026-06-19 12:43:47'),
(221, 12, NULL, 'Marketing Research', '3', 0, 0, '2026-06-19 12:44:50', '2026-06-19 12:44:50'),
(222, 12, NULL, 'Search Engine Optimization (SEO)', '3', 0, 1, '2026-06-19 12:44:50', '2026-06-19 12:44:50'),
(223, 12, NULL, 'Macroeconomics Principles', '3', 0, 2, '2026-06-19 12:44:50', '2026-06-19 12:44:50'),
(224, 12, NULL, 'Fundamentals of Marketing', '3', 0, 3, '2026-06-19 12:44:50', '2026-06-19 12:44:50'),
(225, 12, NULL, 'Civics and Community Engagement', '2', 0, 4, '2026-06-19 12:44:50', '2026-06-19 12:44:50'),
(226, 12, NULL, 'Creative Arts and Technology', '2', 0, 5, '2026-06-19 12:44:50', '2026-06-19 12:44:50'),
(227, 12, NULL, 'Semester Credit Hours', '16', 0, 6, '2026-06-19 12:44:50', '2026-06-19 12:44:50'),
(228, 13, NULL, 'Integrated Digital Marketing Strategies', '3', 0, 0, '2026-06-19 12:45:59', '2026-06-19 12:45:59'),
(229, 13, NULL, 'Web Analytics', '3', 0, 1, '2026-06-19 12:45:59', '2026-06-19 12:45:59'),
(230, 13, NULL, 'Character and Moral Development', '3', 0, 2, '2026-06-19 12:45:59', '2026-06-19 12:45:59'),
(231, 13, NULL, 'Accounting Fundamentals', '3', 0, 3, '2026-06-19 12:45:59', '2026-06-19 12:45:59'),
(232, 13, NULL, 'Environmental Science', '3', 0, 4, '2026-06-19 12:45:59', '2026-06-19 12:45:59'),
(233, 13, NULL, 'Introduction to Entrepreneurship', '3', 0, 5, '2026-06-19 12:45:59', '2026-06-19 12:45:59'),
(234, 13, NULL, 'Semester Credit Hours', '18', 0, 6, '2026-06-19 12:45:59', '2026-06-19 12:45:59'),
(235, 14, NULL, 'Functional English', '3', 0, 0, '2026-06-19 14:45:37', '2026-06-19 14:45:37'),
(236, 14, NULL, 'Business Mathematics', '3', 0, 1, '2026-06-19 14:45:37', '2026-06-19 14:45:37'),
(237, 14, NULL, 'Character and Moral Development', '3', 0, 2, '2026-06-19 14:45:37', '2026-06-19 14:45:37'),
(238, 14, NULL, 'Fundamentals of Management', '2', 0, 3, '2026-06-19 14:45:37', '2026-06-19 14:45:37'),
(239, 14, NULL, 'Islamic Studies/Religious Education', '2', 0, 4, '2026-06-19 14:45:37', '2026-06-19 14:45:37'),
(240, 14, NULL, 'Semester Credit Hours', '16', 0, 5, '2026-06-19 14:45:37', '2026-06-19 14:45:37'),
(248, 16, NULL, 'Accounting Fundamentals', '3', 0, 0, '2026-06-19 14:47:21', '2026-06-19 14:47:21'),
(249, 16, NULL, 'Macroeconomics Principles', '3', 0, 1, '2026-06-19 14:47:21', '2026-06-19 14:47:21'),
(250, 16, NULL, 'Fundamentals of Marketing', '3', 0, 2, '2026-06-19 14:47:21', '2026-06-19 14:47:21'),
(251, 16, NULL, 'Rhetoric and Communication Skills', '3', 0, 3, '2026-06-19 14:47:21', '2026-06-19 14:47:21'),
(252, 16, NULL, 'Civics and Community Engagement', '2', 0, 4, '2026-06-19 14:47:21', '2026-06-19 14:47:21'),
(253, 16, NULL, 'Creative Arts and Technology', '2', 0, 5, '2026-06-19 14:47:21', '2026-06-19 14:47:21'),
(254, 16, NULL, 'Semester Credit Hours', '16', 0, 6, '2026-06-19 14:47:21', '2026-06-19 14:47:21'),
(255, 17, NULL, 'E-Commerce and Digital Business', '3', 0, 0, '2026-06-19 14:48:01', '2026-06-19 14:48:01'),
(256, 17, NULL, 'Introduction to Entrepreneurship', '3', 0, 1, '2026-06-19 14:48:01', '2026-06-19 14:48:01'),
(257, 17, NULL, 'Legal Environment of Business', '3', 0, 2, '2026-06-19 14:48:01', '2026-06-19 14:48:01'),
(258, 17, NULL, 'Cybersecurity for Business', '3', 0, 3, '2026-06-19 14:48:01', '2026-06-19 14:48:01'),
(259, 17, NULL, 'Environmental Science', '3', 0, 4, '2026-06-19 14:48:01', '2026-06-19 14:48:01'),
(260, 17, NULL, 'Semester Credit Hours', '15', 0, 5, '2026-06-19 14:48:01', '2026-06-19 14:48:01'),
(268, 15, NULL, 'Expository Writing', '3', 0, 0, '2026-06-19 17:08:10', '2026-06-19 17:08:10'),
(269, 15, NULL, 'Microeconomics Principles', '3', 0, 1, '2026-06-19 17:08:10', '2026-06-19 17:08:10'),
(270, 15, NULL, 'Applications of Information and Communication Technologies', '3', 0, 2, '2026-06-19 17:08:10', '2026-06-19 17:08:10'),
(271, 15, NULL, 'Business Statistics', '3', 0, 3, '2026-06-19 17:08:10', '2026-06-19 17:08:10'),
(272, 15, NULL, 'Mind Sciences', '3', 0, 4, '2026-06-19 17:08:10', '2026-06-19 17:08:10'),
(273, 15, NULL, 'Professional Branding', '3', 0, 5, '2026-06-19 17:08:10', '2026-06-19 17:08:10'),
(274, 15, NULL, 'Semester Credit Hours', '18', 0, 6, '2026-06-19 17:08:10', '2026-06-19 17:08:10');

-- --------------------------------------------------------

--
-- Table structure for table `program_schema_tables`
--

CREATE TABLE `program_schema_tables` (
  `id` bigint UNSIGNED NOT NULL,
  `header_menu_page_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Program Schema',
  `sort_order` int UNSIGNED NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `program_schema_tables`
--

INSERT INTO `program_schema_tables` (`id`, `header_menu_page_id`, `title`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(3, 3, 'Semester I', 1, 1, '2026-06-19 12:01:34', '2026-06-19 12:06:55'),
(4, 3, 'Semester II', 901, 1, '2026-06-19 12:01:34', '2026-06-19 12:21:57'),
(8, 3, 'Semester III', 902, 1, '2026-06-19 12:26:46', '2026-06-19 12:26:46'),
(9, 3, 'Semester IV', 903, 1, '2026-06-19 12:28:57', '2026-06-19 12:28:57'),
(10, 51, 'Semester I', 1, 1, '2026-06-19 12:41:17', '2026-06-19 12:43:43'),
(11, 51, 'Semester II', 2, 1, '2026-06-19 12:42:44', '2026-06-19 12:43:46'),
(12, 51, 'Semester III', 3, 1, '2026-06-19 12:44:50', '2026-06-19 12:44:50'),
(13, 51, 'Semester IV', 4, 1, '2026-06-19 12:45:59', '2026-06-19 12:45:59'),
(14, 52, 'Semester I', 1, 1, '2026-06-19 14:45:37', '2026-06-19 14:45:37'),
(15, 52, 'Semester II', 2, 1, '2026-06-19 14:46:21', '2026-06-19 14:46:21'),
(16, 52, 'Semester III', 3, 1, '2026-06-19 14:47:21', '2026-06-19 14:47:21'),
(17, 52, 'Semester IV', 4, 1, '2026-06-19 14:48:01', '2026-06-19 14:48:01');

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
('1NqVgHHzBZnAMRED5hDDY9V2KT57TEoE3ashuYka', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', 'eyJfdG9rZW4iOiJjQWFlaHliTnp2YW1jTWlMdXNmeVBUWG9IRkIzZkZlSlJKYVU5MkJGIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1781899923),
('2sZAZlXYfBNswfugN0RzS4tXMArWd3QndhGVmKRR', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', 'eyJfdG9rZW4iOiJmbVV4dW1rd0gxY1k1OXZDemtWRVdTVEIzdHJ6dkw0MGlDWFJwejJvIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9wYWdlc1wvZmVlLXN0cnVjdHVyZSIsInJvdXRlIjoicGFnZXMuc2hvdyJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1781902611),
('7pk3dKTHMOtNYJb55OPRwfqzoWxCIZCfLbcVhsb2', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', 'eyJfdG9rZW4iOiJYUXJ1UlpqVXJWNXVIRjJlNTF6ejNIbnA2ckw1RkpFOWMxVnpuUUVEIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1781906239),
('9mAhTwe6Ikc2gOItK17AR0M2YtEKIgL7XYZkh8d8', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', 'eyJfdG9rZW4iOiJGNmVpTDFYUEhVek5DVHhTN0xtaU81dXlDTGc5bXVmM25sYlBOemg4IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1781905206),
('A493Plza9H8M3iB4LWw4yPR5OcDPRBAaiY2KQHZZ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', 'eyJfdG9rZW4iOiJQQUVNRmNmWXd4Y25UZmlxVXc5bzg1eTVzQlV2WHgzSWNPcE9vcmh3IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9wYWdlc1wvbWJhLTY2LWFmdGVyLTE2LXllYXJzLW5vbi1idXNpbmVzcyIsInJvdXRlIjoicGFnZXMuc2hvdyJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1781899925),
('bSWerxP3caL1GLCVtgH4HTv9O9HPDdZaXYqZLS3R', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', 'eyJfdG9rZW4iOiJVNG9YM3RLc2VSMkkyQzZLRk1GcXZMZ0czYUJrMGhVMVFhNkppT3BQIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9wYWdlc1wvcHJvZ3JhbS1wcm9maWxlIiwicm91dGUiOiJwYWdlcy5zaG93In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1781902614),
('bw93BIt1U1792vsMSGnAGO2dl96jIHPMteCiX7fY', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', 'eyJfdG9rZW4iOiJrSWg2bml4UTdHWHlkSmRWTndJell3UlZIMWRrSnY1a1NLSENwcHRXIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9wYWdlc1wvbWJhLTM2LWFmdGVyLTQteWVhcnMtYmFjaGVsb3JzIiwicm91dGUiOiJwYWdlcy5zaG93In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1781899924),
('f19YVyX9hX063LR7yeNmIIi33i1UEPFlegcWg99x', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', 'eyJfdG9rZW4iOiJ3Z2RWRlFZaWZFbnZ6U21zYzNQQkRIb1VwNm0ybXc2aWtWa3JNT1IyIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1781905586),
('ggfmDQfaHWaPpJdsAHxCDKSQSkabukOmse1MkYx2', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', 'eyJfdG9rZW4iOiJrN1k2b09KaUsxWkRBY1lyVkxtM1Q0VnlORGFSalFuTDJueHlVeDFtIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2FkbWluXC9oZWFkZXItbWVudSJ9LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2FkbWluXC9oZWFkZXItbWVudSIsInJvdXRlIjoiaGVhZGVyLW1lbnUuaW5kZXgifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1781905006),
('GJNktIxrzBb4WODtr3GU9hSbhxv7DfllbOdeJwlG', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', 'eyJfdG9rZW4iOiJSQktBeG9NTlpIaFFqY0R5dDNGUjM1ZjJrTDBwSXBtVHUyWXJWa3lSIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1781906395),
('kFjeLjXUGyFnkl60gZj5UG5rrKVioW5oI9t58K8Z', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', 'eyJfdG9rZW4iOiIyVVd5cDlRaUE2dFZxWkE5bkxnUTk0SDZpTGtBTU8ya2JHeFVJVFN0IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9wYWdlc1wvYnMtY29tcHV0ZXItc2NpZW5jZSIsInJvdXRlIjoicGFnZXMuc2hvdyJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1781899022),
('NHzaBUamCViCul9NbFuHkgXtEfprqFaWS3d09Ehg', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', 'eyJfdG9rZW4iOiJrMlZTd3A3MWg3blVDYktxUTVlOXJBbHROTHlwUDZ3VmpzN0t5SnpWIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9wYWdlc1wvYWRtaXNzaW9uLXBvbGljeSIsInJvdXRlIjoicGFnZXMuc2hvdyJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1781903344),
('NRDnkzgyWRcUYUeHZAuoxdA4ZN79uXn81MCvt9z7', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', 'eyJfdG9rZW4iOiJYS3E2N3RzeUFINXp0Sjc1MHp3NEk0UG04NDJLb2hNVXkyWFRvelpmIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9wYWdlc1wvYmJhIiwicm91dGUiOiJwYWdlcy5zaG93In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1781899020),
('NVmP6ApDqIUNbEUWH1b5QASYqqx2AblVxnhgoIwt', NULL, '127.0.0.1', '', 'eyJfdG9rZW4iOiJaNXpobmRJbERCMHhYcGJnaWpxMnkyeng5cWRkU0prdFBJNm5EVm82IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1781904469),
('OLW61cHgjfKLyzyyAGXlbCvyMIgvgvhq98IRle0b', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', 'eyJfdG9rZW4iOiIzcXVCVFBzY3NYdlFCTlo2aDNXa3V2STBXUWRsTkNUeEZiMHJGSlc4IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1781905374),
('oqAnF2yJpCbV8kKjhLwrCY6lxGOxQCqIQwNo1kIP', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', 'eyJfdG9rZW4iOiJQWHl3eGk2Z2I3SVhrWTl4Tnp2QWs5VUk2cFgydFU3U3N0UlM2SE9MIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9wYWdlc1wvYmJhIiwicm91dGUiOiJwYWdlcy5zaG93In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1781906750),
('RAwlB05u22r7DQOR2yUMJELwJrU6l1wOl0PN8ckk', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', 'eyJfdG9rZW4iOiJSVEZTU1IwMlU1bmZxU29HNUlRUzFxZG85bVpFMElzRFZhekk5OXJ5IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9wYWdlc1wvYnMtYWNjb3VudGluZy1maW5hbmNlIiwicm91dGUiOiJwYWdlcy5zaG93In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1781899022),
('RII9OuzYTBKfm6zu16dZWlYzYlwgRyMqilV5QALN', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', 'eyJfdG9rZW4iOiJ6Z2kyRTRaYWNoTFJqSmtNa3J6T3FIZVVRVFB5ZWcxb1IxUVQ4OWpWIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9wYWdlc1wvbXMiLCJyb3V0ZSI6InBhZ2VzLnNob3cifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1781899927),
('RsMlXVtT43cUy3R6KoaVVvnKN170lMFuwR5upGGi', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', 'eyJfdG9rZW4iOiJhNDZGRFRGQWJlVm9zRlNZZ0RWcU9CY29QcndLZ3VOU2Qza05pNTlLIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1781905509),
('SjAuOkHRBqD6u3r4EN3R524W2FVAfcXuFdMwTekm', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', 'eyJfdG9rZW4iOiJKYVVYNFlid1VpU05HalNWZHVpRTBKSTZtTjc3aW9CWjRURHlQZXBFIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1781905756),
('tMnHrLypj3RTSlHthsvGzq9zGLNHlVePRKyEvB8y', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', 'eyJfdG9rZW4iOiJWU3h3c1hxaGliQXAzWDlzODFNeGdPcHdibVhrQ2g5RmN1VzNxOU1QIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1781899021),
('UJ8P6yCAfC3MIC2qScgZMVViuJODjQWUw1zGLVOG', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', 'eyJfdG9rZW4iOiJWT0JWNjRuUndRT0F4Zm95U3NLMHZFSTRUR2FjaGRscXIzRjE2UTkxIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9wYWdlc1wvYmJhLTIteWVhcnMiLCJyb3V0ZSI6InBhZ2VzLnNob3cifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1781899023),
('vRdYr36zSV0aZcYjNoxrg3HWPcYNEyYb8YdbAXjM', NULL, '127.0.0.1', '', 'eyJfdG9rZW4iOiJVSmdRVERobXFVbUFBdHJsNVU3SkhZeHFER0FkcDVQam5tN0FWZ1JVIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1781904507),
('Xbv0Ejcn8hpLqr6VkagqoXDQxxKC4cgwwQxoEovZ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', 'eyJfdG9rZW4iOiJPeTc2cDdBZ0xtY3VnRnRQbnJrSUd2NFVKWEhNRFpocjhjM1prMkU0IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1781906005),
('xFgwDRd06MEIJA9u9KrPmzoKct4uKA5PywQmOCgC', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJVOE9uRnNjNjdTN1ROcnNpRFZLa2NVZ0ZpN3FlSlFEM1BFV0FXd1ZoIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9wYWdlc1wvYXNzb2NpYXRlLWRlZ3JlZS1pbi1jb21tZXJjZS1wcmV2aW91cy1iY29tIiwicm91dGUiOiJwYWdlcy5zaG93In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwidXJsIjpbXSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjF9', 1781908286);

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
-- Indexes for table `program_schema_rows`
--
ALTER TABLE `program_schema_rows`
  ADD PRIMARY KEY (`id`),
  ADD KEY `program_schema_rows_program_schema_table_id_foreign` (`program_schema_table_id`);

--
-- Indexes for table `program_schema_tables`
--
ALTER TABLE `program_schema_tables`
  ADD PRIMARY KEY (`id`),
  ADD KEY `program_schema_tables_header_menu_page_id_foreign` (`header_menu_page_id`);

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=160;

--
-- AUTO_INCREMENT for table `header_menu_pages`
--
ALTER TABLE `header_menu_pages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `header_menu_page_slides`
--
ALTER TABLE `header_menu_page_slides`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `news_items`
--
ALTER TABLE `news_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `program_schema_rows`
--
ALTER TABLE `program_schema_rows`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=289;

--
-- AUTO_INCREMENT for table `program_schema_tables`
--
ALTER TABLE `program_schema_tables`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

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

--
-- Constraints for table `program_schema_rows`
--
ALTER TABLE `program_schema_rows`
  ADD CONSTRAINT `program_schema_rows_program_schema_table_id_foreign` FOREIGN KEY (`program_schema_table_id`) REFERENCES `program_schema_tables` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `program_schema_tables`
--
ALTER TABLE `program_schema_tables`
  ADD CONSTRAINT `program_schema_tables_header_menu_page_id_foreign` FOREIGN KEY (`header_menu_page_id`) REFERENCES `header_menu_pages` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
