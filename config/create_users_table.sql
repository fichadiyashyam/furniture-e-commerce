-- ============================================================
-- Table: users
-- Run this SQL in your MySQL / phpMyAdmin for the `furniture` DB
-- ============================================================

CREATE TABLE IF NOT EXISTS `users` (
    `id`                   INT          UNSIGNED NOT NULL AUTO_INCREMENT,
    `first_name`           VARCHAR(100) NOT NULL,
    `last_name`            VARCHAR(100) NOT NULL,
    `email`                VARCHAR(255) NOT NULL UNIQUE,
    `phone`                VARCHAR(15)  NOT NULL,
    `gender`               ENUM('male','female','other') NOT NULL,
    `address`              VARCHAR(255) NOT NULL,
    `city`                 VARCHAR(100) NOT NULL,
    `state`                VARCHAR(100) NOT NULL,
    `pincode`              VARCHAR(6)   NOT NULL,
    `country`              VARCHAR(100) NOT NULL,
    `password`             VARCHAR(255) NOT NULL,          -- bcrypt hash
    `profile_photo`        VARCHAR(500) DEFAULT NULL,      -- relative path only
    `verification_token`   VARCHAR(64)  DEFAULT NULL,
    `token_expiry`         DATETIME     DEFAULT NULL,
    `is_verified`          TINYINT(1)   NOT NULL DEFAULT 0,
    `reset_token`          VARCHAR(64)  DEFAULT NULL,      -- forgot password token
    `reset_token_expiry`   DATETIME     DEFAULT NULL,
    `created_at`           DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_email`       (`email`),
    INDEX `idx_token`       (`verification_token`),
    INDEX `idx_reset_token` (`reset_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- If the users table ALREADY EXISTS, run these ALTER statements
-- to add the forgot-password columns (skip if creating fresh):
-- ============================================================
ALTER TABLE `users`
    ADD COLUMN IF NOT EXISTS `reset_token`        VARCHAR(64) DEFAULT NULL AFTER `is_verified`,
    ADD COLUMN IF NOT EXISTS `reset_token_expiry` DATETIME    DEFAULT NULL AFTER `reset_token`;

