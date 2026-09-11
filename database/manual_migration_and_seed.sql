-- =============================================================================
-- ESIAM: MANUAL MIGRATION & SEEDER SQL SCRIPT
-- 1. Migration : database/migrations/2026_01_21_073058_create_permission_tables.php
-- 2. Seeder 1  : database/seeds/RoleMigrationSeeder.php
-- 3. Seeder 2  : database/seeds/YayasanRoleSeeder.php
-- 4. Seeder 3  : database/seeds/YayasanUserSeeder.php
-- =============================================================================

SET FOREIGN_KEY_CHECKS = 0;

-- -------------------------------------------------------------
-- 1. BUAT TABEL-TABEL SPATIE PERMISSION
-- -------------------------------------------------------------

-- Tabel: permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(191) NOT NULL,
  `guard_name` VARCHAR(191) NOT NULL DEFAULT 'web',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`, `guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(191) NOT NULL,
  `guard_name` VARCHAR(191) NOT NULL DEFAULT 'web',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`, `guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: model_has_permissions
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` BIGINT UNSIGNED NOT NULL,
  `model_type` VARCHAR(191) NOT NULL,
  `model_id` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`, `model_id`, `model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`, `model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` 
    FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: model_has_roles
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` BIGINT UNSIGNED NOT NULL,
  `model_type` VARCHAR(191) NOT NULL,
  `model_id` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`, `model_id`, `model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`, `model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` 
    FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: role_has_permissions
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` BIGINT UNSIGNED NOT NULL,
  `role_id` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`, `role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` 
    FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` 
    FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- -------------------------------------------------------------
-- 2. SEEDER: DAFTAR ROLE (RoleMigrationSeeder + YayasanRoleSeeder)
-- -------------------------------------------------------------

INSERT IGNORE INTO `roles` (`name`, `guard_name`, `created_at`, `updated_at`) VALUES
('sadmin', 'web', NOW(), NOW()),
('dosen', 'web', NOW(), NOW()),
('mhs', 'web', NOW(), NOW()),
('no_mhs', 'web', NOW(), NOW()),
('dosen_luar', 'web', NOW(), NOW()),
('kaprodi', 'web', NOW(), NOW()),
('wadir1', 'web', NOW(), NOW()),
('bauk', 'web', NOW(), NOW()),
('admin_prodi', 'web', NOW(), NOW()),
('wadir3', 'web', NOW(), NOW()),
('prausta', 'web', NOW(), NOW()),
('gugus_mutu', 'web', NOW(), NOW()),
('yayasan', 'web', NOW(), NOW());

-- -------------------------------------------------------------
-- 3. SEEDER: MIGRASI ROLE USER LAMA (RoleMigrationSeeder)
-- Memetakan user.role (integer 1-12) ke tabel model_has_roles
-- -------------------------------------------------------------

-- 1 -> sadmin
INSERT IGNORE INTO `model_has_roles` (`role_id`, `model_type`, `model_id`)
SELECT r.id, 'App\\User', u.id
FROM `users` u
JOIN `roles` r ON r.name = 'sadmin' AND r.guard_name = 'web'
WHERE u.role = 1;

-- 2 -> dosen
INSERT IGNORE INTO `model_has_roles` (`role_id`, `model_type`, `model_id`)
SELECT r.id, 'App\\User', u.id
FROM `users` u
JOIN `roles` r ON r.name = 'dosen' AND r.guard_name = 'web'
WHERE u.role = 2;

-- 3 -> mhs
INSERT IGNORE INTO `model_has_roles` (`role_id`, `model_type`, `model_id`)
SELECT r.id, 'App\\User', u.id
FROM `users` u
JOIN `roles` r ON r.name = 'mhs' AND r.guard_name = 'web'
WHERE u.role = 3;

-- 4 -> no_mhs
INSERT IGNORE INTO `model_has_roles` (`role_id`, `model_type`, `model_id`)
SELECT r.id, 'App\\User', u.id
FROM `users` u
JOIN `roles` r ON r.name = 'no_mhs' AND r.guard_name = 'web'
WHERE u.role = 4;

-- 5 -> dosen_luar
INSERT IGNORE INTO `model_has_roles` (`role_id`, `model_type`, `model_id`)
SELECT r.id, 'App\\User', u.id
FROM `users` u
JOIN `roles` r ON r.name = 'dosen_luar' AND r.guard_name = 'web'
WHERE u.role = 5;

-- 6 -> kaprodi
INSERT IGNORE INTO `model_has_roles` (`role_id`, `model_type`, `model_id`)
SELECT r.id, 'App\\User', u.id
FROM `users` u
JOIN `roles` r ON r.name = 'kaprodi' AND r.guard_name = 'web'
WHERE u.role = 6;

-- 7 -> wadir1
INSERT IGNORE INTO `model_has_roles` (`role_id`, `model_type`, `model_id`)
SELECT r.id, 'App\\User', u.id
FROM `users` u
JOIN `roles` r ON r.name = 'wadir1' AND r.guard_name = 'web'
WHERE u.role = 7;

-- 8 -> bauk
INSERT IGNORE INTO `model_has_roles` (`role_id`, `model_type`, `model_id`)
SELECT r.id, 'App\\User', u.id
FROM `users` u
JOIN `roles` r ON r.name = 'bauk' AND r.guard_name = 'web'
WHERE u.role = 8;

-- 9 -> admin_prodi
INSERT IGNORE INTO `model_has_roles` (`role_id`, `model_type`, `model_id`)
SELECT r.id, 'App\\User', u.id
FROM `users` u
JOIN `roles` r ON r.name = 'admin_prodi' AND r.guard_name = 'web'
WHERE u.role = 9;

-- 10 -> wadir3
INSERT IGNORE INTO `model_has_roles` (`role_id`, `model_type`, `model_id`)
SELECT r.id, 'App\\User', u.id
FROM `users` u
JOIN `roles` r ON r.name = 'wadir3' AND r.guard_name = 'web'
WHERE u.role = 10;

-- 11 -> prausta
INSERT IGNORE INTO `model_has_roles` (`role_id`, `model_type`, `model_id`)
SELECT r.id, 'App\\User', u.id
FROM `users` u
JOIN `roles` r ON r.name = 'prausta' AND r.guard_name = 'web'
WHERE u.role = 11;

-- 12 -> gugus_mutu
INSERT IGNORE INTO `model_has_roles` (`role_id`, `model_type`, `model_id`)
SELECT r.id, 'App\\User', u.id
FROM `users` u
JOIN `roles` r ON r.name = 'gugus_mutu' AND r.guard_name = 'web'
WHERE u.role = 12;

-- -------------------------------------------------------------
-- 4. SEEDER: USER YAYASAN (YayasanUserSeeder)
-- Buat user username='yayasan', password='yayasan123'
-- lalu assign role 'yayasan'
-- -------------------------------------------------------------

-- Hash bcrypt untuk 'yayasan123': $2y$12$.fncwvWBY49K5NVYSJaJDekQiGwsr/Yti7Sz98Yxngl6.uvPryUfa
INSERT IGNORE INTO `users` (`name`, `username`, `password`, `role`, `id_user`, `created_at`, `updated_at`)
VALUES (
  'Yayasan',
  'yayasan',
  '$2y$12$.fncwvWBY49K5NVYSJaJDekQiGwsr/Yti7Sz98Yxngl6.uvPryUfa',
  0,
  0,
  NOW(),
  NOW()
);

-- Assign role 'yayasan' ke user yayasan
INSERT IGNORE INTO `model_has_roles` (`role_id`, `model_type`, `model_id`)
SELECT r.id, 'App\\User', u.id
FROM `users` u
JOIN `roles` r ON r.name = 'yayasan' AND r.guard_name = 'web'
WHERE u.username = 'yayasan';

-- -------------------------------------------------------------
-- 5. PENCATATAN MIGRATION (Opsional agar artisan migrate tidak re-run)
-- -------------------------------------------------------------
INSERT IGNORE INTO `migrations` (`migration`, `batch`)
SELECT '2026_01_21_073058_create_permission_tables', COALESCE(MAX(`batch`), 0) + 1
FROM `migrations`
WHERE NOT EXISTS (
  SELECT 1 FROM `migrations` WHERE `migration` = '2026_01_21_073058_create_permission_tables'
);
