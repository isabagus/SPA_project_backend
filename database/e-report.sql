-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.14.0.7165
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping data for table e-report.academic_years: ~3 rows (approximately)
REPLACE INTO `academic_years` (`academic_year`) VALUES
	('2023/2024'),
	('2024/2025'),
	('2025/2026');

-- Dumping data for table e-report.cache: ~4 rows (approximately)
REPLACE INTO `cache` (`key`, `value`, `expiration`) VALUES
	('laravel-cache-1b6453892473a467d07372d45eb05abc2031647a', 'i:4;', 1779201442),
	('laravel-cache-1b6453892473a467d07372d45eb05abc2031647a:timer', 'i:1779201442;', 1779201442),
	('laravel-cache-77de68daecd823babbb58edb1c8e14d7106e83bb', 'i:3;', 1779201573),
	('laravel-cache-77de68daecd823babbb58edb1c8e14d7106e83bb:timer', 'i:1779201573;', 1779201573);

-- Dumping data for table e-report.cache_locks: ~0 rows (approximately)

-- Dumping data for table e-report.categories_subject: ~13 rows (approximately)
REPLACE INTO `categories_subject` (`category_subject`) VALUES
	('Aesthetics Domain'),
	('Affective Domain'),
	('Affective Domain RS & PKN'),
	('Bahasa Indonesia'),
	('Chinese Language'),
	('English'),
	('Mathematics'),
	('PKN'),
	('Religion (Catholicism)'),
	('Religion (Christianity)'),
	('Religion (Islam)'),
	('Religious Studies / Agama'),
	('Science');

-- Dumping data for table e-report.classes: ~2 rows (approximately)
REPLACE INTO `classes` (`class_id`, `level_name`, `section_name`, `level_class`, `mentor_id`) VALUES
	(1, 'Year 1', '-', 'Year 1', 1),
	(2, 'Year 2', '-', 'Year 2', 2);

-- Dumping data for table e-report.details_report: ~9 rows (approximately)
REPLACE INTO `details_report` (`id`, `report_id`, `rubric_id`, `criteria_id`, `score`, `description_subject`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 1, 3.00, 'edit dari mentor', '2026-05-14 09:04:21', '2026-05-14 09:23:55'),
	(2, 1, 1, 2, 3.00, 'edit dari mentor', '2026-05-14 09:04:21', '2026-05-14 09:23:52'),
	(3, 1, 1, 3, 2.00, 'EDIT MENTOR 123', '2026-05-14 09:04:21', '2026-05-14 09:23:54'),
	(4, 2, 2, 4, 2.00, 'agar cokalt', '2026-05-14 09:04:54', '2026-05-14 09:04:54'),
	(5, 2, 2, 5, 3.00, 'agar', '2026-05-14 09:04:54', '2026-05-14 09:04:54'),
	(8, 4, 31, 61, 3.00, 'sdadsa', '2026-05-14 13:27:46', '2026-05-14 14:05:43'),
	(9, 4, 31, 62, 2.00, 'dsadada', '2026-05-14 13:27:46', '2026-05-14 14:05:46'),
	(10, 5, NULL, 57, 0.00, 'weewe', '2026-05-14 14:06:33', '2026-05-14 14:06:33'),
	(11, 5, NULL, 58, 0.00, 'weweqewqewqewq', '2026-05-14 14:06:35', '2026-05-14 14:06:35');

-- Dumping data for table e-report.failed_jobs: ~0 rows (approximately)

-- Dumping data for table e-report.job_batches: ~0 rows (approximately)

-- Dumping data for table e-report.jobs: ~0 rows (approximately)

-- Dumping data for table e-report.mentors: ~2 rows (approximately)
REPLACE INTO `mentors` (`mentor_id`, `user_id`, `name_mentor`, `nip`, `phone_number`, `created_at`, `updated_at`) VALUES
	(1, 3, 'Mas Fuad', '231312321', '9923929233', '2026-05-14 07:20:28', '2026-05-14 07:20:28'),
	(2, 4, 'Pak Hambali', '1231231231', '293132912', '2026-05-14 07:39:31', '2026-05-14 07:39:31');

-- Dumping data for table e-report.migrations: ~25 rows (approximately)
REPLACE INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2026_01_02_152820_create_roles_table', 1),
	(5, '2026_03_01_145255_create_religions_table', 1),
	(6, '2026_04_24_154406_create_categories_subject_table', 1),
	(7, '2026_04_25_113900_create_mentors_table', 1),
	(8, '2026_04_25_114552_create_classes_table', 1),
	(9, '2026_04_27_112001_create_academic_years_table', 1),
	(10, '2026_04_27_113902_create_students_table', 1),
	(11, '2026_04_27_114420_create_terms_table', 1),
	(12, '2026_04_27_114721_create_teachers_table', 1),
	(13, '2026_04_27_114756_create_subjects_table', 1),
	(14, '2026_04_27_114941_create_reports_table', 1),
	(15, '2026_04_27_114942_create_rubric_categories_table', 1),
	(16, '2026_04_27_115011_create_details_report_table', 1),
	(17, '2026_04_27_162627_create_personal_access_tokens_table', 1),
	(18, '2026_04_30_043435_create_parents_table', 1),
	(19, '2026_05_11_084106_add_teacher_id_to_subjects_table', 1),
	(20, '2026_05_11_153240_add_report_group_key_to_subjects_table', 1),
	(21, '2026_05_11_160714_add_subject_name_to_subjects_table', 1),
	(22, '2026_05_11_184000_create_rubric_criteria_table', 1),
	(23, '2026_05_11_184001_update_details_report_table', 1),
	(24, '2026_05_11_203000_add_id_to_details_report_table', 1),
	(25, '2026_05_14_184029_make_teacher_id_nullable_in_subjects_and_rubrics', 2);

-- Dumping data for table e-report.parents: ~1 rows (approximately)
REPLACE INTO `parents` (`parent_id`, `user_id`, `student_id`, `name_parent`, `created_at`, `updated_at`) VALUES
	(1, 11, 1, 'Joko', '2026-05-14 08:13:08', '2026-05-14 08:13:08');

-- Dumping data for table e-report.password_reset_tokens: ~0 rows (approximately)

-- Dumping data for table e-report.personal_access_tokens: ~0 rows (approximately)

-- Dumping data for table e-report.religions: ~7 rows (approximately)
REPLACE INTO `religions` (`religion_name`) VALUES
	('Buddhism'),
	('Catholic'),
	('Christian'),
	('Confucianism'),
	('Hinduism'),
	('Islam'),
	('Non-religious');

-- Dumping data for table e-report.reports: ~4 rows (approximately)
REPLACE INTO `reports` (`report_id`, `student_id`, `academic_year`, `class_id`, `level_class`, `subject_id`, `average_value`, `mentor_note`, `attendance`, `created_at`, `updated_at`) VALUES
	(1, 1, '2024/2025', 1, 'Year 1', 1, 2.67, NULL, 0, '2026-05-14 09:04:21', '2026-05-14 09:04:21'),
	(2, 1, '2024/2025', 1, 'Year 1', 2, 2.50, NULL, 0, '2026-05-14 09:04:54', '2026-05-14 09:04:54'),
	(4, 1, '2024/2025', 1, 'Year 1', 29, 2.50, NULL, 0, '2026-05-14 13:27:46', '2026-05-14 13:27:46'),
	(5, 1, '2024/2025', 1, 'Year 1', 27, 0.00, NULL, 0, '2026-05-14 14:03:19', '2026-05-14 14:03:19');

-- Dumping data for table e-report.roles: ~0 rows (approximately)

-- Dumping data for table e-report.rubric_categories: ~14 rows (approximately)
REPLACE INTO `rubric_categories` (`rubric_id`, `teacher_id`, `subject_id`, `term`, `rubric_name`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 'Term 1', 'JMK Understanding', '2026-05-14 07:49:45', '2026-05-14 07:49:45'),
	(2, 1, 2, 'Term 2', 'JMK Understanding Term 2', '2026-05-14 07:50:38', '2026-05-14 07:50:38'),
	(5, 2, 5, 'Term 1', 'JMK Understanding', '2026-05-14 11:32:45', '2026-05-14 11:32:45'),
	(6, 2, 5, 'Term 1', 'Writing', '2026-05-14 11:32:45', '2026-05-14 11:32:45'),
	(23, NULL, 24, 'Term 1', 'Religious Studies / Agama', '2026-05-14 12:12:35', '2026-05-14 12:12:35'),
	(24, NULL, 24, 'Term 1', 'PKN', '2026-05-14 12:12:35', '2026-05-14 12:12:35'),
	(25, NULL, 25, 'Term 2', 'Religious Studies / Agama', '2026-05-14 12:22:31', '2026-05-14 12:22:31'),
	(26, 4, 25, 'Term 2', 'PKN', '2026-05-14 12:22:31', '2026-05-14 12:22:31'),
	(27, 5, 26, 'Term 3', 'Religious Studies / Agama', '2026-05-14 12:42:31', '2026-05-14 12:50:42'),
	(28, 4, 26, 'Term 3', 'PKN', '2026-05-14 12:42:31', '2026-05-14 12:42:31'),
	(29, 4, 27, 'Term 1', 'PKN', '2026-05-14 13:02:55', '2026-05-14 13:25:10'),
	(30, 5, 28, 'Term 1', 'Religious Studies / Agama', '2026-05-14 13:02:55', '2026-05-14 13:25:10'),
	(31, 3, 29, 'Term 1', 'Religious Studies / Agama', '2026-05-14 13:02:55', '2026-05-14 13:25:10'),
	(32, 3, 30, 'Term 1', 'Religious Studies / Agama', '2026-05-14 13:02:55', '2026-05-14 13:25:10');

-- Dumping data for table e-report.rubric_criteria: ~28 rows (approximately)
REPLACE INTO `rubric_criteria` (`criteria_id`, `rubric_id`, `criteria_name`, `default_description`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Pudding coklat pak hambali', NULL, '2026-05-14 07:49:45', '2026-05-14 07:49:45'),
	(2, 1, 'Agar agar khas grobogan', NULL, '2026-05-14 07:49:45', '2026-05-14 07:49:45'),
	(3, 1, 'MMMMMMMMMMMMMonyet ijo banyumas', NULL, '2026-05-14 07:49:45', '2026-05-14 07:49:45'),
	(4, 2, 'Pudding coklat pak hambali', NULL, '2026-05-14 07:50:38', '2026-05-14 07:50:38'),
	(5, 2, 'Agar agar khas grobogan', NULL, '2026-05-14 07:50:38', '2026-05-14 07:50:38'),
	(10, 5, 'Fuad', NULL, '2026-05-14 11:32:45', '2026-05-14 11:32:45'),
	(11, 5, '123', NULL, '2026-05-14 11:32:45', '2026-05-14 11:32:45'),
	(12, 6, 'Write simple sentences', NULL, '2026-05-14 11:32:45', '2026-05-14 11:32:45'),
	(45, 23, 'Demonstrates good understanding of subject matter', NULL, '2026-05-14 12:12:35', '2026-05-14 12:12:35'),
	(46, 23, 'Participates actively in lessons', NULL, '2026-05-14 12:12:35', '2026-05-14 12:12:35'),
	(47, 24, 'Demonstrates good understanding of subject matter', NULL, '2026-05-14 12:12:35', '2026-05-14 12:12:35'),
	(48, 24, 'Participates actively in lessons', NULL, '2026-05-14 12:12:35', '2026-05-14 12:12:35'),
	(49, 25, 'Demonstrates good understanding of subject matter', NULL, '2026-05-14 12:22:31', '2026-05-14 12:22:31'),
	(50, 25, 'Participates actively in lessons', NULL, '2026-05-14 12:22:31', '2026-05-14 12:22:31'),
	(51, 26, 'Demonstrates good understanding of subject matter', NULL, '2026-05-14 12:22:31', '2026-05-14 12:22:31'),
	(52, 26, 'Participates actively in lessons', NULL, '2026-05-14 12:22:31', '2026-05-14 12:22:31'),
	(53, 27, 'Demonstrates good understanding of subject matter', NULL, '2026-05-14 12:42:31', '2026-05-14 12:42:31'),
	(54, 27, 'Participates actively in lessons', NULL, '2026-05-14 12:42:31', '2026-05-14 12:42:31'),
	(55, 28, 'Demonstrates good understanding of subject matter', NULL, '2026-05-14 12:42:31', '2026-05-14 12:42:31'),
	(56, 28, 'Participates actively in lessons', NULL, '2026-05-14 12:42:31', '2026-05-14 12:42:31'),
	(57, 29, 'Demonstrates good understanding of subject matter', NULL, '2026-05-14 13:02:55', '2026-05-14 13:02:55'),
	(58, 29, 'Participates actively in lessons', NULL, '2026-05-14 13:02:55', '2026-05-14 13:02:55'),
	(59, 30, 'Demonstrates good understanding of subject matter', NULL, '2026-05-14 13:02:55', '2026-05-14 13:02:55'),
	(60, 30, 'Participates actively in lessons', NULL, '2026-05-14 13:02:55', '2026-05-14 13:02:55'),
	(61, 31, 'Demonstrates good understanding of subject matter', NULL, '2026-05-14 13:02:55', '2026-05-14 13:02:55'),
	(62, 31, 'Participates actively in lessons', NULL, '2026-05-14 13:02:55', '2026-05-14 13:02:55'),
	(63, 32, 'Demonstrates good understanding of subject matter', NULL, '2026-05-14 13:02:55', '2026-05-14 13:02:55'),
	(64, 32, 'Participates actively in lessons', NULL, '2026-05-14 13:02:55', '2026-05-14 13:02:55');

-- Dumping data for table e-report.sessions: ~1 rows (approximately)
REPLACE INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('XwDYx5nt4FQgUyeFk9FbKiTgzPmj7J0hQ4NcR9uF', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJxbm5aM2JRUjR3WFF2U3ZocnhNQ0tWZzhCWFExb2ZWTFZ5TDNFaDA0IiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1779201549);

-- Dumping data for table e-report.students: ~1 rows (approximately)
REPLACE INTO `students` (`student_id`, `academic_year`, `class_id`, `level_class`, `religion_name`, `mentor_id`, `name_student`, `nis`, `gender`, `address`, `phone_number`, `created_at`, `updated_at`) VALUES
	(1, '2024/2025', 1, 'Year 1', 'Christian', 1, 'Albert', '23232322', 'Male', 'Lorem ipsum 12312 address', '123132132', '2026-05-14 08:13:08', '2026-05-14 08:13:08');

-- Dumping data for table e-report.subjects: ~7 rows (approximately)
REPLACE INTO `subjects` (`subject_id`, `category_subject`, `term`, `report_group_key`, `class_id`, `level_class`, `teacher_id`, `created_at`, `updated_at`) VALUES
	(1, 'English', 'Term 1', NULL, 1, 'Year 1', 1, '2026-05-14 07:49:45', '2026-05-14 09:21:09'),
	(2, 'English', 'Term 2', NULL, 1, 'Year 1', 1, '2026-05-14 07:50:38', '2026-05-14 09:21:09'),
	(5, 'Aesthetics Domain', 'Term 1', NULL, 1, 'Year 1', 2, '2026-05-14 11:32:45', '2026-05-14 11:32:45'),
	(27, 'PKN', 'Term 1', 'GRP_AF_RS_PKN_1_term1', 1, 'Year 1', 4, '2026-05-14 13:02:55', '2026-05-14 13:25:10'),
	(28, 'Religion (Islam)', 'Term 1', 'GRP_AF_RS_PKN_1_term1', 1, 'Year 1', 5, '2026-05-14 13:02:55', '2026-05-14 13:25:10'),
	(29, 'Religion (Christianity)', 'Term 1', 'GRP_AF_RS_PKN_1_term1', 1, 'Year 1', 3, '2026-05-14 13:02:55', '2026-05-14 13:25:10'),
	(30, 'Religion (Catholicism)', 'Term 1', 'GRP_AF_RS_PKN_1_term1', 1, 'Year 1', 3, '2026-05-14 13:02:55', '2026-05-14 13:25:10');

-- Dumping data for table e-report.teachers: ~6 rows (approximately)
REPLACE INTO `teachers` (`teacher_id`, `user_id`, `name`, `phone_number`, `created_at`, `updated_at`) VALUES
	(1, 2, 'Agar Agar Grobogan', '02302121312', '2026-05-14 07:19:31', '2026-05-14 07:19:31'),
	(2, 5, 'Mas Aldo', '923929323', '2026-05-14 07:41:53', '2026-05-14 07:41:53'),
	(3, 6, 'Mas Ryan', '1231321312', '2026-05-14 07:42:34', '2026-05-14 07:42:34'),
	(4, 12, 'Ibnu', '123123132', '2026-05-14 11:46:15', '2026-05-14 11:46:15'),
	(5, 3, 'Mas Fuad', '9923929233', '2026-05-14 12:50:19', '2026-05-14 12:50:19'),
	(6, 4, 'Pak Hambali', '293132912', '2026-05-14 12:50:19', '2026-05-14 12:50:19');

-- Dumping data for table e-report.terms: ~4 rows (approximately)
REPLACE INTO `terms` (`term`) VALUES
	('Term 1'),
	('Term 2'),
	('Term 3'),
	('Term 4');

-- Dumping data for table e-report.users: ~8 rows (approximately)
REPLACE INTO `users` (`user_id`, `username`, `email`, `email_verified_at`, `password`, `phone_number`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'admin_super', 'admin@gmail.com', NULL, '$2y$12$Sb6a7LPNmkYBEIEZaV95DOuTMFANXlIQfaTE0BD87S8BjLTLRQYei', '08111111111', 'admin', NULL, '2026-05-14 06:59:45', '2026-05-14 06:59:45'),
	(2, 'grobogan', 'grobogan@test.example', NULL, '$2y$12$.tn6dh6/RfgE.EaiFoyhGuumbbxFEYITUNqyCuDXzQwrplxmMFNC2', NULL, 'teacher', NULL, '2026-05-14 07:19:31', '2026-05-14 07:19:31'),
	(3, 'MasFuad', 'masfuad@test.example', NULL, '$2y$12$nnf7S4Ibm487rYsy1CHwJusC5rZCQEdZJiQXpoImA3x7oMn8YAyAK', NULL, 'mentor', NULL, '2026-05-14 07:20:28', '2026-05-14 07:20:28'),
	(4, 'PakHambali', 'pak-hambali@test.example', NULL, '$2y$12$KTojbtKEj4p07lW3WaHtPOQvLca808RCq/ujkLbWYXu6OC/FLDIsC', NULL, 'mentor', NULL, '2026-05-14 07:39:31', '2026-05-14 07:39:31'),
	(5, 'Mas Aldo', 'masaldo@example.test', NULL, '$2y$12$U93PXRug4I5knT7OY3SDWuRQY108IAH5VMiKFOeSiT0.QV2Y9Hli.', NULL, 'teacher', NULL, '2026-05-14 07:41:53', '2026-05-14 07:41:53'),
	(6, 'Mas Ryan', 'masryan@example.test', NULL, '$2y$12$QGr19XlpjWCoMoUvk5VQF.CDeGpJGC97TnTTE.8FtvnvGndyaMVCq', NULL, 'teacher', NULL, '2026-05-14 07:42:34', '2026-05-14 07:42:34'),
	(11, 'Joko', 'joko@test.example', NULL, '$2y$12$seqMc7oygynjusdqt5xIzeEfrGcoY4pFTKUjPieHiUgersNcFNZdG', NULL, 'parent', NULL, '2026-05-14 08:13:08', '2026-05-14 08:13:08'),
	(12, 'gurupkn', 'gurupkn@test.example', NULL, '$2y$12$qSayQBTZ7SMWinrMHWr7hOwu.eT1DR/A.oZpafS6TuXNjOW5aKev.', NULL, 'teacher', NULL, '2026-05-14 11:46:15', '2026-05-14 11:46:15');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
