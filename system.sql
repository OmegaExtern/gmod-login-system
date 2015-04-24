-- system.sql
-- License: The MIT License (MIT) < http://opensource.org/licenses/MIT >
-- Author: OmegaExtern < https://github.com/OmegaExtern > < omegaextern@live.com >
--
-- phpMyAdmin SQL Dump
-- version 4.4.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 24, 2015 at 10:27 PM
-- Server version: 5.6.21
-- PHP Version: 5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `system`
--
CREATE DATABASE IF NOT EXISTS `system`
  DEFAULT CHARACTER SET utf8
  COLLATE utf8_general_ci;
USE `system`;

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

DROP TABLE IF EXISTS `players`;
CREATE TABLE IF NOT EXISTS `players` (
  `banned`                  ENUM('NO', 'YES')                                                                                                          NOT NULL DEFAULT 'NO',
  `banned_date_time`        DATETIME                                                                                                                   NOT NULL,
  `banned_expire_date_time` DATETIME                                                                                                                   NOT NULL,
  `banned_reason`           CHAR(255)                                                                                                                  NOT NULL DEFAULT '',
  `community_identifier`    BIGINT(17) UNSIGNED                                                                                                        NOT NULL DEFAULT '76561197960265729',
  `date_time`               DATETIME                                                                                                                   NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `experience`              BIGINT(20) UNSIGNED                                                                                                        NOT NULL DEFAULT '0',
  `identifier`              BIGINT(20) UNSIGNED                                                                                                        NOT NULL,
  `joined_date_time`        DATETIME                                                                                                                   NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `joined_name`             CHAR(24)
                            CHARACTER SET utf8
                            COLLATE utf8_bin                                                                                                           NOT NULL DEFAULT '',
  `level`                   TINYINT(1) UNSIGNED                                                                                                        NOT NULL DEFAULT '1',
  `name`                    CHAR(24)
                            CHARACTER SET utf8
                            COLLATE utf8_bin                                                                                                           NOT NULL DEFAULT '',
  `old_name`                CHAR(24)
                            CHARACTER SET utf8
                            COLLATE utf8_bin                                                                                                           NOT NULL DEFAULT '',
  `online`                  ENUM('NO', 'YES')                                                                                                          NOT NULL DEFAULT 'YES',
  `points`                  BIGINT(20) UNSIGNED                                                                                                        NOT NULL DEFAULT '0',
  `rank`                    ENUM('UNKNOWN', 'MEMBER', 'SUPER_MEMBER', 'MODERATOR', 'SUPER_MODERATOR', 'ADMINISTRATOR', 'SUPER_ADMINISTRATOR', 'OWNER') NOT NULL DEFAULT 'MEMBER',
  `steam_identifier`        CHAR(19)                                                                                                                   NOT NULL DEFAULT 'STEAM_0:0:0',
  `warning_percentage`      TINYINT(3) UNSIGNED                                                                                                        NOT NULL DEFAULT '0'
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

--
-- Triggers `players`
--
DROP TRIGGER IF EXISTS `AutoBanOnCentWarning`;
DELIMITER $$
CREATE TRIGGER `AutoBanOnCentWarning` BEFORE UPDATE ON `players`
FOR EACH ROW BEGIN
  IF NEW.warning_percentage > 99
  THEN
    SET NEW.banned = 'YES';
    SET NEW.banned_reason = 'Exceeded warning percentage.';
  END IF;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `players`
--
ALTER TABLE `players`
ADD PRIMARY KEY (`identifier`),
ADD UNIQUE KEY `community_identifier` (`community_identifier`),
ADD UNIQUE KEY `joined_name` (`joined_name`),
ADD UNIQUE KEY `name` (`name`),
ADD UNIQUE KEY `steam_identifier` (`steam_identifier`),
ADD KEY `identifier` (`identifier`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `players`
--
ALTER TABLE `players`
MODIFY `identifier` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;
