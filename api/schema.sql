-- =========================================================
-- Poc Poc Gourmet — MySQL para Hostinger
-- 1) Crie o banco no hPanel
-- 2) Abra phpMyAdmin → Importar → este arquivo
-- 3) Preencha api/config.php
-- 4) Abra https://SEU-DOMINIO/api/install.php uma vez
-- =========================================================

SET NAMES utf8mb4;
SET time_zone = '-03:00';

CREATE TABLE IF NOT EXISTS `store_state` (
  `id` TINYINT UNSIGNED NOT NULL DEFAULT 1,
  `payload` LONGTEXT NOT NULL,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` TINYINT UNSIGNED NOT NULL DEFAULT 1,
  `email` VARCHAR(190) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_admin_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Login inicial (troque depois):
-- e-mail: admin@pocpocgourmet.com.br
-- senha:  pocpoc123
INSERT INTO `admin_users` (`id`, `email`, `password_hash`)
VALUES (
  1,
  'admin@pocpocgourmet.com.br',
  '$2b$10$x.V83k9.3/lvRdEanH8zAuZ86Cn/UV5XVF0FnHObepHcarSd4/8Gu'
) ON DUPLICATE KEY UPDATE
  `email` = VALUES(`email`),
  `password_hash` = VALUES(`password_hash`);

INSERT INTO `store_state` (`id`, `payload`)
VALUES (1, '{"version":2,"settings":{},"auth":{},"categories":[],"products":[],"orders":[],"clients":[],"inventoryItems":[],"coupons":[],"finance":[],"reviews":[],"faq":[],"gallery":[]}')
ON DUPLICATE KEY UPDATE `id` = `id`;
