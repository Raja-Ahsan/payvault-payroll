-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Jan 14, 2026 at 06:33 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `payvault_payroll`
--

-- --------------------------------------------------------

--
-- Table structure for table `ach_transactions`
--

CREATE TABLE `ach_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payroll_run_id` bigint(20) UNSIGNED NOT NULL,
  `bank_account_id` bigint(20) UNSIGNED NOT NULL,
  `transaction_type` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `ach_batch_id` varchar(255) DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `status` enum('pending','processing','completed','failed','reversed') NOT NULL DEFAULT 'pending',
  `status_message` text DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `processor_response` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ach_transactions`
--

INSERT INTO `ach_transactions` (`id`, `payroll_run_id`, `bank_account_id`, `transaction_type`, `amount`, `ach_batch_id`, `transaction_id`, `status`, `status_message`, `processed_at`, `completed_at`, `processor_response`, `created_at`, `updated_at`) VALUES
(1, 2, 3, 'credit', 1539.17, NULL, 'SIM-6966c67564b17', 'completed', NULL, '2026-01-13 17:25:57', '2026-01-13 17:25:57', '{\"status\":\"completed\",\"message\":\"Transaction processed successfully\"}', '2026-01-13 17:25:57', '2026-01-13 17:25:57');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text DEFAULT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `ip_address` varchar(255) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bank_accounts`
--

CREATE TABLE `bank_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `accountable_type` varchar(255) NOT NULL,
  `accountable_id` bigint(20) UNSIGNED NOT NULL,
  `account_type` varchar(255) NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `account_holder_name` varchar(255) NOT NULL,
  `routing_number` varchar(255) NOT NULL,
  `account_number` varchar(255) NOT NULL,
  `verification_status` enum('pending','verified','failed') NOT NULL DEFAULT 'pending',
  `verified_at` timestamp NULL DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bank_accounts`
--

INSERT INTO `bank_accounts` (`id`, `accountable_type`, `accountable_id`, `account_type`, `bank_name`, `account_holder_name`, `routing_number`, `account_number`, `verification_status`, `verified_at`, `is_primary`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'App\\Models\\Employee', 1, 'checking', 'Test Bank', 'John Doe', '123456789', '987654321', 'verified', NULL, 1, 1, NULL, NULL, NULL),
(2, 'App\\Models\\Employee', 7, 'savings', 'Kasper Booth', 'Samson Oliver', '349123123', '123123123123', 'verified', '2026-01-13 17:24:54', 0, 1, '2026-01-13 17:24:50', '2026-01-13 17:24:54', NULL),
(3, 'App\\Models\\Employee', 5, 'checking', 'Xaviera Collins', 'Russell Cameron', '123123123', '1231231231', 'verified', '2026-01-13 17:25:41', 1, 1, '2026-01-13 17:25:39', '2026-01-13 17:25:41', NULL),
(4, 'App\\Models\\Employee', 8, 'checking', '12312312331', '1232313123', '123123123', '12312312312', 'verified', '2026-01-13 17:41:21', 0, 1, '2026-01-13 17:41:18', '2026-01-13 17:41:21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `legal_name` varchar(255) DEFAULT NULL,
  `ein` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `zip_code` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `payroll_config` text DEFAULT NULL,
  `ach_enrolled` tinyint(1) NOT NULL DEFAULT 0,
  `ach_status` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `name`, `legal_name`, `ein`, `address`, `city`, `state`, `zip_code`, `phone`, `email`, `payroll_config`, `ach_enrolled`, `ach_status`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Isabella Richardson tes', 'Belle Morton', 'Minus nulla ut esse', 'Voluptatem qui dolor', 'Ut accusantium est e', 'Qui consequatur nul', '78017', '+1 (196) 568-5872', 'lirydyc@mailinator.com', NULL, 0, NULL, 1, '2026-01-13 16:25:18', '2026-01-13 16:25:38', NULL),
(2, 'Duncan Mccall', 'Blythe Nolan', 'Tenetur amet ea et', 'Veniam sapiente lib', 'Alias dolore tempori', 'Obcaecati dolore qui', '87760', '+1 (299) 204-4296', 'kugi@mailinator.com', NULL, 0, NULL, 2, '2026-01-13 17:05:14', '2026-01-13 17:05:14', NULL),
(3, 'Fay Raymond', 'Hanae Tillman', 'Alias magnam volupta', 'Sit in et et aut mi', 'Laboriosam tempore', 'Inventore maxime con', '46155', '+1 (771) 913-1847', 'noqime@mailinator.com', NULL, 0, NULL, 2, '2026-01-13 17:05:24', '2026-01-13 17:05:24', NULL),
(4, 'Emma Sosa', 'Wang Wilder', 'Reprehenderit consec', 'Consequat Nisi repu', 'Occaecat reprehender', 'Ratione est ipsa de', '35735', '+1 (927) 489-2183', 'tadowy@mailinator.com', NULL, 0, NULL, 2, '2026-01-13 17:05:33', '2026-01-13 17:05:33', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `employee_number` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `ssn` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `zip_code` varchar(255) DEFAULT NULL,
  `employment_type` enum('full_time','part_time','contractor') NOT NULL DEFAULT 'full_time',
  `hire_date` date DEFAULT NULL,
  `termination_date` date DEFAULT NULL,
  `pay_type` enum('salary','hourly') NOT NULL DEFAULT 'hourly',
  `salary` decimal(10,2) DEFAULT NULL,
  `hourly_rate` decimal(8,2) DEFAULT NULL,
  `standard_hours_per_week` int(11) NOT NULL DEFAULT 40,
  `filing_status` varchar(255) DEFAULT NULL,
  `federal_allowances` int(11) NOT NULL DEFAULT 0,
  `tax_information` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `company_id`, `user_id`, `employee_number`, `first_name`, `last_name`, `email`, `phone`, `date_of_birth`, `ssn`, `address`, `city`, `state`, `zip_code`, `employment_type`, `hire_date`, `termination_date`, `pay_type`, `salary`, `hourly_rate`, `standard_hours_per_week`, `filing_status`, `federal_allowances`, `tax_information`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, NULL, NULL, 'Holmes', 'Cochran', 'danyf@mailinator.com', '+1 (594) 155-2167', NULL, NULL, NULL, NULL, NULL, NULL, 'full_time', NULL, NULL, 'hourly', 120000.00, 25.00, 40, NULL, 0, NULL, 1, '2026-01-13 16:26:54', '2026-01-13 16:26:54', NULL),
(2, 1, NULL, NULL, 'Hyatt', 'Shannon', 'qobicivi@mailinator.com', '+1 (485) 261-6434', NULL, NULL, NULL, NULL, NULL, NULL, 'full_time', NULL, NULL, 'salary', 350000.00, 94.00, 40, NULL, 0, NULL, 1, '2026-01-13 16:27:19', '2026-01-13 16:27:19', NULL),
(3, 4, NULL, NULL, 'Julian', 'Prince', 'cavynid@mailinator.com', '+1 (237) 514-9065', NULL, NULL, NULL, NULL, NULL, NULL, 'full_time', NULL, NULL, 'salary', 100000.00, 21.00, 40, NULL, 0, NULL, 1, '2026-01-13 17:08:12', '2026-01-13 17:08:12', NULL),
(4, 4, NULL, NULL, 'Autumn', 'Reynolds', 'dinanoq@mailinator.com', '+1 (296) 864-7934', NULL, NULL, NULL, NULL, NULL, NULL, 'full_time', NULL, NULL, 'hourly', 100000.00, 51.00, 40, NULL, 0, NULL, 1, '2026-01-13 17:08:41', '2026-01-13 17:08:41', NULL),
(5, 2, NULL, NULL, 'Jocelyn', 'Terry', 'hexoxowi@mailinator.com', '+1 (898) 165-6928', NULL, NULL, NULL, NULL, NULL, NULL, 'full_time', NULL, NULL, 'salary', 20000.00, 58.00, 40, NULL, 0, NULL, 1, '2026-01-13 17:09:29', '2026-01-13 17:09:29', NULL),
(6, 2, NULL, NULL, 'Mannix', 'Avila', 'fidilulyho@mailinator.com', '+1 (982) 652-6523', NULL, NULL, NULL, NULL, NULL, NULL, 'full_time', NULL, NULL, 'hourly', 10000.00, 19.00, 40, NULL, 0, NULL, 1, '2026-01-13 17:09:47', '2026-01-13 17:10:15', NULL),
(7, 3, NULL, NULL, 'Yetta', 'Randall', 'dufam@mailinator.com', '+1 (303) 961-9424', NULL, NULL, NULL, NULL, NULL, NULL, 'full_time', NULL, NULL, 'hourly', 1000.00, 65.00, 40, NULL, 0, NULL, 1, '2026-01-13 17:10:52', '2026-01-13 17:10:52', NULL),
(8, 1, 3, 'EMP001', 'John', 'Doe', 'employee@payvault.com', '555-0100', NULL, NULL, NULL, NULL, NULL, NULL, 'full_time', NULL, NULL, 'hourly', NULL, 25.00, 40, NULL, 0, NULL, 1, '2026-01-13 17:39:14', '2026-01-13 17:39:14', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_01_13_203359_create_roles_table', 1),
(5, '2026_01_13_203402_create_companies_table', 1),
(6, '2026_01_13_203405_create_employees_table', 1),
(7, '2026_01_13_203409_create_payroll_runs_table', 1),
(8, '2026_01_13_203412_create_payroll_items_table', 1),
(9, '2026_01_13_203415_create_payroll_deductions_table', 1),
(10, '2026_01_13_203419_create_bank_accounts_table', 1),
(11, '2026_01_13_203423_create_ach_transactions_table', 1),
(12, '2026_01_13_203427_create_audit_logs_table', 1),
(13, '2026_01_13_203431_add_role_id_to_users_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll_deductions`
--

CREATE TABLE `payroll_deductions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payroll_item_id` bigint(20) UNSIGNED NOT NULL,
  `deduction_type` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `calculation_type` enum('fixed','percentage') NOT NULL DEFAULT 'fixed',
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `percentage` decimal(5,2) DEFAULT NULL,
  `is_pre_tax` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll_items`
--

CREATE TABLE `payroll_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payroll_run_id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `hours_worked` decimal(8,2) NOT NULL DEFAULT 0.00,
  `regular_hours` decimal(8,2) NOT NULL DEFAULT 0.00,
  `overtime_hours` decimal(8,2) NOT NULL DEFAULT 0.00,
  `regular_rate` decimal(8,2) NOT NULL DEFAULT 0.00,
  `overtime_rate` decimal(8,2) NOT NULL DEFAULT 0.00,
  `gross_pay` decimal(10,2) NOT NULL DEFAULT 0.00,
  `federal_tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `state_tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `local_tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `social_security` decimal(10,2) NOT NULL DEFAULT 0.00,
  `medicare` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_taxes` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_deductions` decimal(10,2) NOT NULL DEFAULT 0.00,
  `net_pay` decimal(10,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payroll_items`
--

INSERT INTO `payroll_items` (`id`, `payroll_run_id`, `employee_id`, `hours_worked`, `regular_hours`, `overtime_hours`, `regular_rate`, `overtime_rate`, `gross_pay`, `federal_tax`, `state_tax`, `local_tax`, `social_security`, `medicare`, `total_taxes`, `total_deductions`, `net_pay`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 40.00, 40.00, 0.00, 25.00, 37.50, 1000.00, 0.00, 0.00, 0.00, 62.00, 14.50, 76.50, 0.00, 923.50, NULL, '2026-01-13 16:28:32', '2026-01-13 16:28:32'),
(2, 1, 2, 40.00, 40.00, 0.00, 94.00, 141.00, 6730.77, 0.00, 0.00, 0.00, 417.31, 97.60, 514.90, 0.00, 6215.87, NULL, '2026-01-13 16:28:32', '2026-01-13 16:28:32'),
(3, 2, 5, 40.00, 40.00, 0.00, 58.00, 87.00, 1666.67, 0.00, 0.00, 0.00, 103.33, 24.17, 127.50, 0.00, 1539.17, NULL, '2026-01-13 17:14:30', '2026-01-13 17:14:30'),
(4, 2, 6, 40.00, 40.00, 0.00, 19.00, 28.50, 760.00, 0.00, 0.00, 0.00, 47.12, 11.02, 58.14, 0.00, 701.86, NULL, '2026-01-13 17:14:30', '2026-01-13 17:14:30');

-- --------------------------------------------------------

--
-- Table structure for table `payroll_runs`
--

CREATE TABLE `payroll_runs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `pay_period_type` varchar(255) NOT NULL,
  `pay_period_start` date NOT NULL,
  `pay_period_end` date NOT NULL,
  `pay_date` date NOT NULL,
  `status` enum('draft','preview','approved','finalized') NOT NULL DEFAULT 'draft',
  `total_gross` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_deductions` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_net` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `finalized_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payroll_runs`
--

INSERT INTO `payroll_runs` (`id`, `company_id`, `pay_period_type`, `pay_period_start`, `pay_period_end`, `pay_date`, `status`, `total_gross`, `total_deductions`, `total_net`, `created_by`, `approved_by`, `approved_at`, `finalized_at`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'weekly', '2026-01-01', '2026-01-14', '2026-01-31', 'finalized', 7730.77, 0.00, 7139.37, 1, 1, '2026-01-13 16:28:39', '2026-01-13 16:28:57', NULL, '2026-01-13 16:28:20', '2026-01-13 16:28:57', NULL),
(2, 2, 'monthly', '2026-01-01', '2026-01-31', '2026-02-01', 'finalized', 2426.67, 0.00, 2241.03, 2, 2, '2026-01-13 17:14:59', '2026-01-13 17:15:02', NULL, '2026-01-13 17:14:17', '2026-01-13 17:15:02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'System administrator with full access', '2026-01-13 15:41:51', '2026-01-13 15:41:51'),
(2, 'client', 'Client/Company owner with company management access', '2026-01-13 15:41:52', '2026-01-13 15:41:52'),
(3, 'employee', 'Employee with limited access to their own payroll information', '2026-01-13 15:41:52', '2026-01-13 15:41:52');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('ccDe6sFvNyR9nny1WsAU6WIPLTKWaY3b6s4Pxwwh', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiemRKZFBkMGtUVDEycnpDZmY3VmJrNm9HdjF2dkNPMWVTR2xhN1I3SSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9lbXBsb3llZS9wYXlyb2xsIjtzOjU6InJvdXRlIjtzOjIyOiJlbXBsb3llZS5wYXlyb2xsLmluZGV4Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mzt9', 1768347811),
('eK5jsPhN50xsUrpNVdNHhhFS6CQn3yzCSGwWcQ64', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibVoyYjNuTTFRbG8yOW5PbFJwbnk5cDRBTUxpUXEwMzFrTVB5MFlLcSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9hZG1pbi9jb21wYW5pZXMvY3JlYXRlIjtzOjU6InJvdXRlIjtzOjIyOiJhZG1pbi5jb21wYW5pZXMuY3JlYXRlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1768339266),
('QAhrcNLnVVdEWMq6JttLhvKD0JX2m8XSSXt6cJbu', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNm44bmFiRHh2WGUxMUE0RnNvYTFQYnJJb2xOaDBuZE5IYURJSmhQVSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9jb21wYW5pZXMiO3M6NToicm91dGUiO3M6MjE6ImFkbWluLmNvbXBhbmllcy5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1768410256);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 1, 'Admin User', 'admin@payvault.com', NULL, '$2y$12$mz4GBQh65JOtIgak/iX.Ju3wD3DWMJXHS8ST0h4OLGyKTNgNpf7H2', NULL, '2026-01-13 16:01:08', '2026-01-13 16:01:08'),
(2, 2, 'Client User', 'client@payvault.com', NULL, '$2y$12$C/7vB2AiRAiE.4Y2aZ0ImOpCzjqE89LXeIwAxZa7PRyd82nrAmsYm', NULL, '2026-01-13 16:01:08', '2026-01-13 16:01:08'),
(3, 3, 'Employee User', 'employee@payvault.com', NULL, '$2y$12$LrU3ru/7OIFtWEOW/a9GPeDZp6sYoMU3v8q29wEhAtI3rzeo1H7LK', NULL, '2026-01-13 16:01:08', '2026-01-13 16:01:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ach_transactions`
--
ALTER TABLE `ach_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ach_transactions_payroll_run_id_foreign` (`payroll_run_id`),
  ADD KEY `ach_transactions_bank_account_id_foreign` (`bank_account_id`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `audit_logs_user_id_foreign` (`user_id`),
  ADD KEY `audit_logs_model_type_model_id_index` (`model_type`,`model_id`);

--
-- Indexes for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bank_accounts_accountable_type_accountable_id_index` (`accountable_type`,`accountable_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `companies_ein_unique` (`ein`),
  ADD KEY `companies_created_by_foreign` (`created_by`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employees_email_unique` (`email`),
  ADD KEY `employees_company_id_foreign` (`company_id`),
  ADD KEY `employees_user_id_foreign` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payroll_deductions`
--
ALTER TABLE `payroll_deductions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payroll_deductions_payroll_item_id_foreign` (`payroll_item_id`);

--
-- Indexes for table `payroll_items`
--
ALTER TABLE `payroll_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payroll_items_payroll_run_id_foreign` (`payroll_run_id`),
  ADD KEY `payroll_items_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `payroll_runs`
--
ALTER TABLE `payroll_runs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payroll_runs_company_id_foreign` (`company_id`),
  ADD KEY `payroll_runs_created_by_foreign` (`created_by`),
  ADD KEY `payroll_runs_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

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
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ach_transactions`
--
ALTER TABLE `ach_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `payroll_deductions`
--
ALTER TABLE `payroll_deductions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payroll_items`
--
ALTER TABLE `payroll_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payroll_runs`
--
ALTER TABLE `payroll_runs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ach_transactions`
--
ALTER TABLE `ach_transactions`
  ADD CONSTRAINT `ach_transactions_bank_account_id_foreign` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ach_transactions_payroll_run_id_foreign` FOREIGN KEY (`payroll_run_id`) REFERENCES `payroll_runs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `companies`
--
ALTER TABLE `companies`
  ADD CONSTRAINT `companies_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `employees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `payroll_deductions`
--
ALTER TABLE `payroll_deductions`
  ADD CONSTRAINT `payroll_deductions_payroll_item_id_foreign` FOREIGN KEY (`payroll_item_id`) REFERENCES `payroll_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payroll_items`
--
ALTER TABLE `payroll_items`
  ADD CONSTRAINT `payroll_items_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payroll_items_payroll_run_id_foreign` FOREIGN KEY (`payroll_run_id`) REFERENCES `payroll_runs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payroll_runs`
--
ALTER TABLE `payroll_runs`
  ADD CONSTRAINT `payroll_runs_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payroll_runs_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payroll_runs_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
