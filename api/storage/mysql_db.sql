# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.6.38)
# Database: tinder
# Generation Time: 2018-05-24 23:00:26 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table account_activation
# ------------------------------------------------------------

DROP TABLE IF EXISTS `account_activation`;

CREATE TABLE `account_activation` (
  `user_id` int(10) unsigned NOT NULL,
  `activation_key` varchar(40) NOT NULL,
  KEY `user_id` (`user_id`),
  CONSTRAINT `account_activation_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `account_activation` WRITE;
/*!40000 ALTER TABLE `account_activation` DISABLE KEYS */;

INSERT INTO `account_activation` (`user_id`, `activation_key`)
VALUES
	(62,'5b07424b9b1d0'),
	(63,'5b07426ad78f7'),
	(65,'5b0743839a3d1');

/*!40000 ALTER TABLE `account_activation` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table account_verification
# ------------------------------------------------------------

DROP TABLE IF EXISTS `account_verification`;

CREATE TABLE `account_verification` (
  `user_id` int(10) unsigned NOT NULL,
  `access_key` varchar(40) NOT NULL,
  KEY `user_id` (`user_id`),
  KEY `idx_access_key` (`access_key`),
  CONSTRAINT `account_verification_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `account_verification` WRITE;
/*!40000 ALTER TABLE `account_verification` DISABLE KEYS */;

INSERT INTO `account_verification` (`user_id`, `access_key`)
VALUES
	(62,'5b074286853bb'),
	(63,'5b074298e9284'),
	(62,'5b0742a47c75a'),
	(63,'5b0742aec2a41'),
	(62,'5b0742c2be481'),
	(65,'5b074395ea8a3'),
	(65,'5b07439f6c754'),
	(62,'5b0743aaf30dc'),
	(65,'5b0743b4c1fb0');

/*!40000 ALTER TABLE `account_verification` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table roles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `role` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;

INSERT INTO `roles` (`id`, `role`)
VALUES
	(1,'basic'),
	(2,'vip'),
	(3,'admin');

/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(40) NOT NULL,
  `last_name` varchar(40) NOT NULL,
  `email` varchar(40) NOT NULL,
  `pass` varchar(40) NOT NULL,
  `gender` int(1) DEFAULT NULL,
  `age` int(11) NOT NULL,
  `motto` varchar(255) NOT NULL DEFAULT '',
  `interest` int(1) DEFAULT NULL,
  `profile_image` varchar(255) NOT NULL,
  `role_id` int(11) unsigned DEFAULT NULL,
  `latitude` float DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  `access_token` varchar(40) DEFAULT NULL,
  `verified` tinyint(1) NOT NULL,
  `swipe_date` date DEFAULT NULL,
  `swipe_count` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `access_token` (`access_token`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `pass`, `gender`, `age`, `motto`, `interest`, `profile_image`, `role_id`, `latitude`, `longitude`, `access_token`, `verified`, `swipe_date`, `swipe_count`)
VALUES
	(62,'Tudor','Dascalu','tudor@gmail.com','pass',0,21,'I like sports!',1,'/assets/images/5b07424b9b162.jpg',1,55.6542,12.5916,'5b074286853bb',1,'2018-05-24',3),
	(63,'Dani','Verisanu','dani@gmail.com','pass',1,20,'I like art!',0,'/assets/images/5b07426ad789b.jpg',1,55.6542,12.5916,'5b074298e9284',1,'2018-05-24',2),
	(65,'Diana','Horea','dia@gmail.com','pass',1,22,'I like medicin!',0,'/assets/images/5b0743839a37d.jpg',1,55.6542,12.5915,'5b074395ea8a3',1,'2018-05-24',2);

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users_information
# ------------------------------------------------------------

DROP VIEW IF EXISTS `users_information`;

CREATE TABLE `users_information` (
   `first_name` VARCHAR(40) NOT NULL,
   `last_name` VARCHAR(40) NOT NULL,
   `email` VARCHAR(40) NOT NULL,
   `profile_image` VARCHAR(255) NOT NULL,
   `motto` VARCHAR(255) NOT NULL DEFAULT '',
   `role` VARCHAR(40) NOT NULL
) ENGINE=MyISAM;





# Replace placeholder table for users_information with correct view syntax
# ------------------------------------------------------------

DROP TABLE `users_information`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `users_information`
AS SELECT
   `users`.`first_name` AS `first_name`,
   `users`.`last_name` AS `last_name`,
   `users`.`email` AS `email`,
   `users`.`profile_image` AS `profile_image`,
   `users`.`motto` AS `motto`,
   `roles`.`role` AS `role`
FROM (`users` join `roles` on((`users`.`role_id` = `roles`.`id`))) where (`users`.`verified` = 1);

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
