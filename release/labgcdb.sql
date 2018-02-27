-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 13, 2018 at 06:48 AM
-- Server version: 5.7.14
-- PHP Version: 7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `labgcdb`
--
CREATE DATABASE IF NOT EXISTS `labgcdb` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `labgcdb`;

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

DROP TABLE IF EXISTS `activities`;
CREATE TABLE IF NOT EXISTS `activities` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'An identifier used to track the type of activity.',
  `occurred_at` timestamp NULL DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `activities_user_id_index` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=140 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `drones`
--

DROP TABLE IF EXISTS `drones`;
CREATE TABLE IF NOT EXISTS `drones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `drone_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `drone_slug` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `occurred_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `drones`
--

INSERT INTO `drones` (`id`, `drone_name`, `drone_slug`, `occurred_at`) VALUES
(1, 'Drone 1', 'drone1', '2018-01-14 15:00:00'),
(2, 'Drone 2', 'drone2', '2018-01-11 15:00:00'),
(3, 'Car 1', 'sdcar1', '2018-01-15 15:00:00'),
(4, 'Car 2', 'sdcar2', '2018-01-16 15:00:00'),
(5, 'Car 3', 'sdcar3', '2018-01-14 15:00:00'),
(6, 'Car 4', 'sdcar4', '2018-01-14 15:00:00'),
(7, 'Drone 3', 'drone3', '2018-01-11 15:00:00'),
(8, 'ROV 1', 'rov1', '2018-01-11 15:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `icon` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'fa fa-user' COMMENT 'The icon representing users in this group.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `groups_slug_unique` (`slug`),
  KEY `groups_slug_index` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `slug`, `name`, `description`, `icon`, `created_at`, `updated_at`) VALUES
(1, 'public', 'Public', 'The terrans are a young species with psionic potential. The terrans of the Koprulu sector descend from the survivors of a disastrous 23rd century colonization mission from Earth.', 'fa fa-user', '2017-06-20 18:45:51', '2017-06-20 18:45:51');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sprinkle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `sprinkle`, `migration`, `batch`, `created_at`, `updated_at`) VALUES
(1, 'core', '\\UserFrosting\\Sprinkle\\Core\\Database\\Migrations\\v400\\SessionsTable', 1, '2017-06-20 18:42:50', '2017-06-20 18:42:50'),
(2, 'core', '\\UserFrosting\\Sprinkle\\Core\\Database\\Migrations\\v400\\ThrottlesTable', 1, '2017-06-20 18:42:50', '2017-06-20 18:42:50'),
(3, 'account', '\\UserFrosting\\Sprinkle\\Account\\Database\\Migrations\\v400\\ActivitiesTable', 1, '2017-06-20 18:42:50', '2017-06-20 18:42:50'),
(4, 'account', '\\UserFrosting\\Sprinkle\\Account\\Database\\Migrations\\v400\\UsersTable', 1, '2017-06-20 18:42:50', '2017-06-20 18:42:50'),
(5, 'account', '\\UserFrosting\\Sprinkle\\Account\\Database\\Migrations\\v400\\RolesTable', 1, '2017-06-20 18:42:50', '2017-06-20 18:42:50'),
(6, 'account', '\\UserFrosting\\Sprinkle\\Account\\Database\\Migrations\\v400\\RoleUsersTable', 1, '2017-06-20 18:42:51', '2017-06-20 18:42:51'),
(7, 'account', '\\UserFrosting\\Sprinkle\\Account\\Database\\Migrations\\v400\\CreateAdminUser', 1, '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(8, 'account', '\\UserFrosting\\Sprinkle\\Account\\Database\\Migrations\\v400\\GroupsTable', 1, '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(9, 'account', '\\UserFrosting\\Sprinkle\\Account\\Database\\Migrations\\v400\\PasswordResetsTable', 1, '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(10, 'account', '\\UserFrosting\\Sprinkle\\Account\\Database\\Migrations\\v400\\PermissionRolesTable', 1, '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(11, 'account', '\\UserFrosting\\Sprinkle\\Account\\Database\\Migrations\\v400\\PermissionsTable', 1, '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(12, 'account', '\\UserFrosting\\Sprinkle\\Account\\Database\\Migrations\\v400\\PersistencesTable', 1, '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(13, 'account', '\\UserFrosting\\Sprinkle\\Account\\Database\\Migrations\\v400\\VerificationsTable', 1, '2017-06-20 18:45:51', '2017-06-20 18:45:51');

-- --------------------------------------------------------

--
-- Table structure for table `operations`
--

DROP TABLE IF EXISTS `operations`;
CREATE TABLE IF NOT EXISTS `operations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `operation_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `drone_id` int(11) NOT NULL COMMENT 'drone assigned',
  `status` int(11) NOT NULL,
  `occurred_at` timestamp NULL DEFAULT NULL COMMENT 'last activity time',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `operations`
--

INSERT INTO `operations` (`id`, `operation_name`, `drone_id`, `status`, `occurred_at`) VALUES
(1, 'Operation 1', 1, 1, '2018-01-07 15:00:00'),
(2, 'Operation 2', 1, 1, '2018-01-14 15:00:00'),
(3, 'Operation 3', 2, 1, '2018-01-07 15:00:00'),
(4, 'Operation 4', 2, 1, '2018-01-01 15:00:00'),
(5, 'Taxi Operation 1', 3, 1, '2018-01-01 15:00:00'),
(6, 'Taxi Operation 1', 4, 1, '2018-01-01 15:00:00'),
(7, 'Taxi Operation 1', 5, 1, '2018-01-01 15:00:00'),
(8, 'Taxi Operation 1', 6, 1, '2018-01-01 15:00:00'),
(9, 'Surveiying 1', 7, 1, '2018-01-01 15:00:00'),
(10, 'Boat inspection 1', 8, 1, '2018-01-01 15:00:00'),
(11, 'Taxi Operation 2', 3, 1, '2018-01-01 15:00:00'),
(12, 'Taxi Operation 2', 4, 1, '2018-01-01 15:00:00'),
(13, 'Taxi Operation 2', 5, 1, '2018-01-01 15:00:00'),
(14, 'Taxi Operation 2', 6, 1, '2018-01-01 15:00:00'),
(15, 'Surveiying 2', 7, 1, '2018-01-01 15:00:00'),
(16, 'Boat inspection 2', 8, 1, '2018-01-01 15:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  `hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT '0',
  `expires_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `password_resets_user_id_index` (`user_id`),
  KEY `password_resets_hash_index` (`hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'A code that references a specific action or URI that an assignee of this permission has access to.',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `conditions` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'The conditions under which members of this group have access to this hook.',
  `description` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `slug`, `name`, `conditions`, `description`, `created_at`, `updated_at`) VALUES
(1, 'create_group', 'Create group', 'always()', 'Create a new group.', '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(2, 'create_user', 'Create user', 'always()', 'Create a new user in your own group and assign default roles.', '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(3, 'create_user_field', 'Set new user group', 'subset(fields,[\'group\'])', 'Set the group when creating a new user.', '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(4, 'delete_group', 'Delete group', 'always()', 'Delete a group.', '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(5, 'delete_user', 'Delete user', '!has_role(user.id,2) && !is_master(user.id)', 'Delete users who are not Site Administrators.', '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(6, 'update_account_settings', 'Edit user', 'always()', 'Edit your own account settings.', '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(7, 'update_group_field', 'Edit group', 'always()', 'Edit basic properties of any group.', '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(8, 'update_user_field', 'Edit user', '!has_role(user.id,2) && subset(fields,[\'name\',\'email\',\'locale\',\'group\',\'flag_enabled\',\'flag_verified\',\'password\'])', 'Edit users who are not Site Administrators.', '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(9, 'update_user_field', 'Edit group user', 'equals_num(self.group_id,user.group_id) && !is_master(user.id) && !has_role(user.id,2) && (!has_role(user.id,3) || equals_num(self.id,user.id)) && subset(fields,[\'name\',\'email\',\'locale\',\'flag_enabled\',\'flag_verified\',\'password\'])', 'Edit users in your own group who are not Site or Group Administrators, except yourself.', '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(10, 'uri_account_settings', 'Account settings page', 'always()', 'View the account settings page.', '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(11, 'uri_activities', 'Activity monitor', 'always()', 'View a list of all activities for all users.', '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(12, 'uri_dashboard', 'Admin dashboard', 'always()', 'View the administrative dashboard.', '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(13, 'uri_group', 'View group', 'always()', 'View the group page of any group.', '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(14, 'uri_group', 'View own group', 'equals_num(self.group_id,group.id)', 'View the group page of your own group.', '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(15, 'uri_groups', 'Group management page', 'always()', 'View a page containing a list of groups.', '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(16, 'uri_user', 'View user', 'always()', 'View the user page of any user.', '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(17, 'uri_user', 'View user', 'equals_num(self.group_id,user.group_id) && !is_master(user.id) && !has_role(user.id,2) && (!has_role(user.id,3) || equals_num(self.id,user.id))', 'View the user page of any user in your group, except the master user and Site and Group Administrators (except yourself).', '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(18, 'uri_users', 'User management page', 'always()', 'View a page containing a table of users.', '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(19, 'view_group_field', 'View group', 'in(property,[\'name\',\'icon\',\'slug\',\'description\',\'users\'])', 'View certain properties of any group.', '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(20, 'view_group_field', 'View group', 'equals_num(self.group_id,group.id) && in(property,[\'name\',\'icon\',\'slug\',\'description\',\'users\'])', 'View certain properties of your own group.', '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(21, 'view_user_field', 'View user', 'in(property,[\'user_name\',\'name\',\'email\',\'locale\',\'theme\',\'roles\',\'group\',\'activities\'])', 'View certain properties of any user.', '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(22, 'view_user_field', 'View user', 'equals_num(self.group_id,user.group_id) && !is_master(user.id) && !has_role(user.id,2) && (!has_role(user.id,3) || equals_num(self.id,user.id)) && in(property,[\'user_name\',\'name\',\'email\',\'locale\',\'roles\',\'group\',\'activities\'])', 'View certain properties of any user in your own group, except the master user and Site and Group Administrators (except yourself).', '2017-06-20 18:45:51', '2017-06-20 18:45:51');

-- --------------------------------------------------------

--
-- Table structure for table `permission_roles`
--

DROP TABLE IF EXISTS `permission_roles`;
CREATE TABLE IF NOT EXISTS `permission_roles` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `permission_roles_permission_id_index` (`permission_id`),
  KEY `permission_roles_role_id_index` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `permission_roles`
--

INSERT INTO `permission_roles` (`permission_id`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 2, '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(2, 2, '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(2, 3, '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(3, 2, '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(4, 2, '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(5, 2, '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(6, 1, '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(7, 2, '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(8, 2, '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(9, 3, '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(10, 1, '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(11, 2, '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(12, 2, '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(13, 2, '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(14, 3, '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(15, 2, '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(16, 2, '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(17, 3, '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(18, 2, '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(19, 2, '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(20, 3, '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(21, 2, '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(22, 3, '2017-06-20 18:45:51', '2017-06-20 18:45:51');

-- --------------------------------------------------------

--
-- Table structure for table `persistences`
--

DROP TABLE IF EXISTS `persistences`;
CREATE TABLE IF NOT EXISTS `persistences` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  `token` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `persistent_token` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `persistences_user_id_index` (`user_id`),
  KEY `persistences_token_index` (`token`),
  KEY `persistences_persistent_token_index` (`persistent_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_slug_unique` (`slug`),
  KEY `roles_slug_index` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `slug`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'user', 'User', 'This role provides basic user functionality.', '2017-06-20 18:42:50', '2017-06-20 18:42:50'),
(2, 'site-admin', 'Site Administrator', 'This role is meant for "site administrators", who can basically do anything except create, edit, or delete other administrators.', '2017-06-20 18:42:50', '2017-06-20 18:42:50'),
(3, 'group-admin', 'Group Administrator', 'This role is meant for "group administrators", who can basically do anything with users in their own group, except other administrators of that group.', '2017-06-20 18:42:50', '2017-06-20 18:42:50');

-- --------------------------------------------------------

--
-- Table structure for table `role_users`
--

DROP TABLE IF EXISTS `role_users`;
CREATE TABLE IF NOT EXISTS `role_users` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `role_users_user_id_index` (`user_id`),
  KEY `role_users_role_id_index` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `role_users`
--

INSERT INTO `role_users` (`user_id`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(1, 2, '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(1, 3, '2017-06-20 18:45:51', '2017-06-20 18:45:51'),
(2, 1, '2017-06-22 07:39:19', '2017-06-22 07:39:19'),
(2, 2, '2017-06-22 07:39:19', '2017-06-22 07:39:19');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8_unicode_ci,
  `payload` text COLLATE utf8_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL,
  UNIQUE KEY `sessions_id_unique` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `throttles`
--

DROP TABLE IF EXISTS `throttles`;
CREATE TABLE IF NOT EXISTS `throttles` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `request_data` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `throttles_type_index` (`type`),
  KEY `throttles_ip_index` (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(254) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `locale` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'en_US' COMMENT 'The language and locale to use for this user.',
  `theme` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'The user theme.',
  `group_id` int(10) UNSIGNED NOT NULL DEFAULT '1' COMMENT 'The id of the user group.',
  `flag_verified` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Set to 1 if the user has verified their account via email, 0 otherwise.',
  `flag_enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Set to 1 if the user account is currently enabled, 0 otherwise.  Disabled accounts cannot be logged in to, but they retain all of their data and settings.',
  `last_activity_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'The id of the last activity performed by this user.',
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_user_name_unique` (`user_name`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_user_name_index` (`user_name`),
  KEY `users_email_index` (`email`),
  KEY `users_group_id_index` (`group_id`),
  KEY `users_last_activity_id_index` (`last_activity_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_name`, `email`, `first_name`, `last_name`, `locale`, `theme`, `group_id`, `flag_verified`, `flag_enabled`, `last_activity_id`, `password`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'root', 'labelimage.manager@gmail.com', 'Admin', 'GC', 'en_US', NULL, 1, 1, 1, 2, '$2y$10$VctK6bcKnRoNFlcfRNudJuuhAT5k5qtTPztClRrV1QT9T3uXVmQuG', NULL, '2017-06-20 18:45:50', '2017-06-22 07:35:11'),
(2, 'admin_sh_gc', 'shaun.mermet@enroutelab.com', 'Admin', 'Sh', 'en_US', NULL, 1, 1, 1, 111, '$2y$10$FSvE9Lqsi1m8401VNlCPxejU93OJX/XtOJ/vCIf1ntdC11YQdLYQS', NULL, '2017-06-22 07:39:19', '2018-02-12 13:04:19');

-- --------------------------------------------------------

--
-- Table structure for table `verifications`
--

DROP TABLE IF EXISTS `verifications`;
CREATE TABLE IF NOT EXISTS `verifications` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  `hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT '0',
  `expires_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `verifications_user_id_index` (`user_id`),
  KEY `verifications_hash_index` (`hash`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `verifications`
--

INSERT INTO `verifications` (`id`, `user_id`, `hash`, `completed`, `expires_at`, `completed_at`, `created_at`, `updated_at`) VALUES
(2, 2, 'b4039277d6ffdc05489fdc26719c37ddba853ec961961b9417732c3cb55058a000fe5d8566f1fe618e2b4f4e588bd52978c9320b83886249af9b95c67e863ff8', 1, '2017-06-22 10:45:37', '2017-06-22 07:46:33', '2017-06-22 07:45:37', '2017-06-22 07:46:33');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
