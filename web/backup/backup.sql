-- Created at 6.3.2017 11:10 using David Grudl MySQL Dump Utility
-- Host: localhost
-- MySQL Server: 5.6.35
-- Database: skt_gestion_db

SET NAMES utf8;
SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
SET FOREIGN_KEY_CHECKS=0;
-- --------------------------------------------------------

DROP TABLE IF EXISTS `membre`;

CREATE TABLE `membre` (
  `id` int(11) NOT NULL,
  `firstName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lastName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'c',
  `like_as` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 's',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `membre` (`id`, `firstName`, `lastName`, `address`, `status`, `like_as`) VALUES
(2,	'Rajaonarimirana',	'Eliharintsoa',	'Ankazomanga',	'c',	's'),
(4,	'Rajaonarison',	'Alain Patrick',	'Lot IVV 31 Ter Ankazomanga',	'c',	'c');


-- --------------------------------------------------------

DROP TABLE IF EXISTS `presence`;

CREATE TABLE `presence` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `membre_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `lite_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6977C7A56A99F74A` (`membre_id`),
  CONSTRAINT `FK_6977C7A56A99F74A` FOREIGN KEY (`membre_id`) REFERENCES `membre` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



-- --------------------------------------------------------

DROP TABLE IF EXISTS `presence_b_c`;

CREATE TABLE `presence_b_c` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `membre_id` int(11) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_AAA4F1E06A99F74A` (`membre_id`),
  CONSTRAINT `FK_AAA4F1E06A99F74A` FOREIGN KEY (`membre_id`) REFERENCES `membre` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=140 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `presence_b_c` (`id`, `membre_id`, `date`) VALUES
(137,	4,	'2015-02-08'),
(138,	4,	'2012-05-05'),
(139,	4,	'2018-12-12');


-- THE END
