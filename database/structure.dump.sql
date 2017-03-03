-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.1.21-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win32
-- HeidiSQL Version:             9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for ue4
CREATE DATABASE IF NOT EXISTS `ue4` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `ue4`;

-- Dumping structure for table ue4.players
CREATE TABLE IF NOT EXISTS `players` (
  `Id` binary(16) NOT NULL DEFAULT '0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0',
  `Email` varchar(255) NOT NULL,
  `InGameName` varchar(25) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `Name` (`InGameName`),
  KEY `Email` (`Email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table ue4.player_account_recovery
CREATE TABLE IF NOT EXISTS `player_account_recovery` (
  `PlayerId` binary(16) NOT NULL,
  `Code` varchar(10) CHARACTER SET latin1 NOT NULL,
  `Expiration` datetime NOT NULL,
  `GeneratedOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Type` enum('Activation','PasswordReset') NOT NULL,
  PRIMARY KEY (`PlayerId`),
  KEY `Code` (`Code`,`Expiration`),
  CONSTRAINT `FK_player_activations_players` FOREIGN KEY (`PlayerId`) REFERENCES `players` (`Id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table ue4.player_security_log
CREATE TABLE IF NOT EXISTS `player_security_log` (
  `Id` binary(16) NOT NULL,
  `PlayerId` binary(16) NOT NULL,
  `LogType` enum('Login','PasswordReset','AccountActivated') NOT NULL,
  `RemoteIp` varbinary(16) NOT NULL,
  `DateTime` datetime NOT NULL,
  `Message` varchar(255) DEFAULT NULL,
  KEY `PlayerId` (`PlayerId`),
  CONSTRAINT `FK_player_security_log_players` FOREIGN KEY (`PlayerId`) REFERENCES `players` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table ue4.player_sessions
CREATE TABLE IF NOT EXISTS `player_sessions` (
  `PlayerId` binary(16) NOT NULL,
  `SessionId` binary(16) NOT NULL,
  `Expiration` datetime NOT NULL,
  `RemoteIp` varbinary(16) NOT NULL,
  `Created` datetime NOT NULL,
  PRIMARY KEY (`PlayerId`),
  CONSTRAINT `FK__players` FOREIGN KEY (`PlayerId`) REFERENCES `players` (`Id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
