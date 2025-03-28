<?php
$sql = [];

$sql[] = "CREATE TABLE IF NOT EXISTS `ps_loyalty_points` (
    `id_loyalty_point` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `id_customer` INT(11) UNSIGNED NOT NULL,
    `points` INT(11) NOT NULL DEFAULT 0,
    `date_add` DATETIME NOT NULL,
    `date_upd` DATETIME NOT NULL,
    PRIMARY KEY (`id_loyalty_point`),
    UNIQUE KEY `id_customer` (`id_customer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

$sql[] = "CREATE TABLE IF NOT EXISTS `ps_loyalty_points_history` (
    `id_history` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `id_customer` INT(11) UNSIGNED NOT NULL,
    `points` INT(11) NOT NULL,
    `type` ENUM('earn', 'redeem', 'transfer') NOT NULL,
    `source` VARCHAR(255) NULL,
    `date_add` DATETIME NOT NULL,
    PRIMARY KEY (`id_history`),
    KEY `id_customer` (`id_customer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

$sql[] = "CREATE TABLE IF NOT EXISTS `ps_loyalty_social_shares` (
    `id_share` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `id_customer` INT(11) UNSIGNED NOT NULL,
    `id_product` INT(11) UNSIGNED NOT NULL,
    `platform` VARCHAR(50) NOT NULL,
    `social_account` VARCHAR(255) NOT NULL,
    `date_add` DATETIME NOT NULL,
    PRIMARY KEY (`id_share`),
    UNIQUE KEY `unique_share` (`id_customer`, `id_product`, `platform`, `social_account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";