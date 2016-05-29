-- Adminer 4.2.3 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `address`;
CREATE TABLE `address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `street` varchar(256) NOT NULL,
  `city` varchar(256) NOT NULL,
  `zip` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `address` (`id`, `street`, `city`, `zip`) VALUES
(2,	'U      Soukolí, 80',	'Pardubice',	'123 33'),
(6,	'Utoan 23',	'HK',	'351 12'),
(7,	'Stara 88',	'Brno',	'45424'),
(8,	'Stara 88',	'Brno',	'454 25'),
(9,	'Street 2',	'Test',	'544 45'),
(10,	'Ulice 50',	'Mesto',	'123 45'),
(11,	'Uwd 20',	'Hradec Králové',	'502 06'),
(12,	'WADAW 522',	'Brno',	'123 45');

DROP TABLE IF EXISTS `article`;
CREATE TABLE `article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `headline` varchar(500) NOT NULL,
  `content` text NOT NULL,
  `state` enum('published','waiting','deleted') NOT NULL DEFAULT 'waiting',
  `user_id` int(11) DEFAULT NULL,
  `created` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `article_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `article` (`id`, `headline`, `content`, `state`, `user_id`, `created`) VALUES
(1,	'AWD',	'<ol> <li>awd<strong>a</strong></li> <li>awd</li> </ol> <p>d</p> <p>aw</p> <p>d</p> <p>&nbsp;</p>',	'published',	NULL,	'2016-05-29'),
(2,	'AWD',	'<ol> <li>awd<strong>a</strong></li> <li>awd</li> </ol> <p>d</p> <p>aw</p> <p>d</p> <p>&nbsp;</p>',	'published',	5,	'2016-05-29'),
(3,	'AWD',	'<ol> <li>awd<strong>a</strong></li> <li>awd</li> </ol> <p>d</p> <p>aw</p> <p>d</p> <p>&nbsp;</p>',	'published',	5,	'2016-05-29'),
(4,	'Bolek a lolek',	'<p>Lorem</p> <p>&nbsp;</p> <p>Ipsum</p> <p>Dolor</p> <ol> <li>korek</li> <li>lolek</li> <li>bolek</li> </ol>',	'published',	5,	'2016-05-29');

DROP TABLE IF EXISTS `cat`;
CREATE TABLE `cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `castrated` tinyint(1) NOT NULL DEFAULT '0',
  `depozitum_id` int(11) DEFAULT NULL,
  `born` date DEFAULT NULL,
  `died` date DEFAULT NULL,
  `created` date DEFAULT NULL,
  `isDeleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `depozitum_id` (`depozitum_id`),
  CONSTRAINT `cat_ibfk_2` FOREIGN KEY (`depozitum_id`) REFERENCES `depozitum` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `cat` (`id`, `name`, `gender`, `castrated`, `depozitum_id`, `born`, `died`, `created`, `isDeleted`) VALUES
(1,	'Eliška',	'female',	1,	1,	'2011-12-27',	NULL,	NULL,	0),
(2,	'Pepa',	'male',	0,	1,	NULL,	NULL,	NULL,	0),
(3,	'Bila kocka',	'male',	0,	2,	NULL,	NULL,	NULL,	0),
(4,	'ModroFialka',	'male',	1,	NULL,	NULL,	NULL,	NULL,	0),
(5,	'Meow',	'male',	1,	1,	'2016-05-22',	NULL,	NULL,	0),
(7,	'Ohniva smradlavka',	'female',	1,	NULL,	NULL,	NULL,	NULL,	0),
(23,	'ImageCat',	'male',	0,	15,	'2016-04-29',	NULL,	NULL,	0);

DROP TABLE IF EXISTS `cat_x_color`;
CREATE TABLE `cat_x_color` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) NOT NULL,
  `color_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cat_id_color_id` (`cat_id`,`color_id`),
  KEY `color_id` (`color_id`),
  CONSTRAINT `cat_x_color_ibfk_1` FOREIGN KEY (`cat_id`) REFERENCES `cat` (`id`),
  CONSTRAINT `cat_x_color_ibfk_2` FOREIGN KEY (`color_id`) REFERENCES `color` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `cat_x_color` (`id`, `cat_id`, `color_id`) VALUES
(1,	1,	1),
(17,	1,	4),
(18,	1,	8),
(3,	3,	9),
(4,	4,	4),
(5,	4,	11),
(7,	5,	1),
(6,	5,	9),
(10,	7,	12),
(11,	7,	13);

DROP TABLE IF EXISTS `cat_x_handicap`;
CREATE TABLE `cat_x_handicap` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) NOT NULL,
  `handicap_id` int(11) NOT NULL,
  `created` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cat_id_handicap_id` (`cat_id`,`handicap_id`),
  KEY `handicap_id` (`handicap_id`),
  CONSTRAINT `cat_x_handicap_ibfk_1` FOREIGN KEY (`cat_id`) REFERENCES `cat` (`id`),
  CONSTRAINT `cat_x_handicap_ibfk_2` FOREIGN KEY (`handicap_id`) REFERENCES `handicap` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `cat_x_handicap` (`id`, `cat_id`, `handicap_id`, `created`) VALUES
(2,	7,	1,	NULL),
(3,	7,	3,	NULL),
(5,	1,	2,	NULL),
(8,	1,	3,	NULL),
(12,	2,	2,	NULL),
(13,	2,	3,	NULL);

DROP TABLE IF EXISTS `cat_x_image`;
CREATE TABLE `cat_x_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) NOT NULL,
  `image_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cat_id_image_id` (`cat_id`,`image_id`),
  KEY `image_id` (`image_id`),
  CONSTRAINT `cat_x_image_ibfk_1` FOREIGN KEY (`cat_id`) REFERENCES `cat` (`id`),
  CONSTRAINT `cat_x_image_ibfk_2` FOREIGN KEY (`image_id`) REFERENCES `image` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `cat_x_image` (`id`, `cat_id`, `image_id`) VALUES
(30,	1,	31),
(26,	2,	27),
(27,	2,	28),
(28,	2,	29),
(29,	2,	30),
(24,	3,	25),
(25,	3,	26),
(21,	5,	22),
(22,	5,	23),
(23,	5,	24),
(12,	23,	13),
(13,	23,	14),
(14,	23,	15);

DROP TABLE IF EXISTS `cat_x_vaccination`;
CREATE TABLE `cat_x_vaccination` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) NOT NULL,
  `vaccination_id` int(11) NOT NULL,
  `created` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cat_id` (`cat_id`),
  KEY `vaccination_id` (`vaccination_id`),
  CONSTRAINT `cat_x_vaccination_ibfk_1` FOREIGN KEY (`cat_id`) REFERENCES `cat` (`id`),
  CONSTRAINT `cat_x_vaccination_ibfk_2` FOREIGN KEY (`vaccination_id`) REFERENCES `vaccination` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `cat_x_vaccination` (`id`, `cat_id`, `vaccination_id`, `created`) VALUES
(7,	1,	1,	NULL),
(10,	1,	4,	NULL);

DROP TABLE IF EXISTS `color`;
CREATE TABLE `color` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `color` (`id`, `name`) VALUES
(1,	'černá'),
(4,	'modrá'),
(8,	'rudá'),
(9,	'bílá'),
(10,	'žlutá'),
(11,	'fialová'),
(12,	'rudá'),
(13,	'ohnivá');

DROP TABLE IF EXISTS `contact`;
CREATE TABLE `contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(256) NOT NULL,
  `lastname` varchar(256) NOT NULL,
  `email` varchar(256) DEFAULT NULL,
  `phone` varchar(256) DEFAULT NULL,
  `address_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `address_id` (`address_id`),
  CONSTRAINT `contact_ibfk_1` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `contact` (`id`, `firstname`, `lastname`, `email`, `phone`, `address_id`) VALUES
(2,	'Marie',	'Novakova',	's@adwc.cz',	'123456789',	NULL),
(7,	'Josef',	'Stary',	'joe@stary.vul',	'999874123',	NULL),
(10,	'Hanka',	'S.',	NULL,	NULL,	NULL),
(11,	'Depozitum',	'Hradec',	NULL,	NULL,	NULL);

DROP TABLE IF EXISTS `depozitum`;
CREATE TABLE `depozitum` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `capacity` int(11) NOT NULL DEFAULT '0',
  `state` enum('open','full') NOT NULL DEFAULT 'open',
  `address_id` int(11) DEFAULT NULL,
  `isDeleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `address_id` (`address_id`),
  CONSTRAINT `depozitum_ibfk_1` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `depozitum` (`id`, `name`, `capacity`, `state`, `address_id`, `isDeleted`) VALUES
(1,	'Hradec Králové',	20,	'open',	11,	0),
(2,	'Pribram',	2,	'full',	12,	0),
(15,	'V brne neco',	20,	'open',	8,	0),
(16,	'Test',	1,	'open',	9,	0);

DROP TABLE IF EXISTS `depozitum_x_contact`;
CREATE TABLE `depozitum_x_contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `depozitum_id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  `created` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `contact_id_depozitum_id` (`contact_id`,`depozitum_id`),
  KEY `depozitum_id` (`depozitum_id`),
  KEY `contact_id` (`contact_id`),
  CONSTRAINT `depozitum_x_contact_ibfk_1` FOREIGN KEY (`depozitum_id`) REFERENCES `depozitum` (`id`),
  CONSTRAINT `depozitum_x_contact_ibfk_2` FOREIGN KEY (`contact_id`) REFERENCES `contact` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `depozitum_x_contact` (`id`, `depozitum_id`, `contact_id`, `created`) VALUES
(13,	15,	7,	NULL);

DROP TABLE IF EXISTS `handicap`;
CREATE TABLE `handicap` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `severity` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `handicap` (`id`, `name`, `severity`) VALUES
(1,	'Nemá oko',	5),
(2,	'Ukousnutý ocas',	4),
(3,	'Šourá nohama',	5),
(4,	'Kulhá',	1),
(5,	'Špatně vidí',	6);

DROP TABLE IF EXISTS `image`;
CREATE TABLE `image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `size` decimal(60,0) NOT NULL,
  `dir` varchar(256) NOT NULL,
  `type` varchar(32) NOT NULL,
  `hash` varchar(1024) NOT NULL,
  `created` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `image` (`id`, `name`, `size`, `dir`, `type`, `hash`, `created`) VALUES
(2,	'obc.jpg',	1094874,	'cat8',	'image/jpeg',	'ed2a8b09a4d7dedc43483390ffcea83dfedb66a3',	'2016-05-14'),
(3,	'rid.jpg',	988209,	'cat9',	'image/jpeg',	'f2af71f9ac1cc34568444dde070624d35ab884ae',	'2016-05-14'),
(4,	'rid.jpg',	988209,	'cat10',	'image/jpeg',	'2ac3f530b7b82d6e1888778c5e6d910476e0b079',	'2016-05-15'),
(5,	'zadani.jpg',	265417,	'cat11',	'image/jpeg',	'934984b5801decdec4cba63a90d93ee0b937af54',	'2016-05-15'),
(6,	'obc.jpg',	1094874,	'cat14',	'image/jpeg',	'01092297d930e91f9f3e63b191815a29839eeaae',	'2016-05-15'),
(7,	'back.PNG',	115154,	'cat20',	'image/png',	'62dd99671aa7f36d52fe3e3d5d3e50313bd182b1',	'2016-05-17'),
(8,	'back2.png',	113536,	'cat20',	'image/png',	'9b4b100d640f1fd7b5bd760b16b28ddf071ef406',	'2016-05-17'),
(9,	'obc.jpg',	1094874,	'cat21',	'image/jpeg',	'faeb8c558325439f712e70ac0c2b8fd895595234',	'2016-05-17'),
(10,	'rid.jpg',	988209,	'cat21',	'image/jpeg',	'5f34f998136e7b828ac621da6d8f300fa9a5d93f',	'2016-05-17'),
(11,	'obc.jpg',	1094874,	'cat22',	'image/jpeg',	'98c022cae878c518b1e76b9c53ce4fcc58eda16f',	'2016-05-17'),
(12,	'rid.jpg',	988209,	'cat22',	'image/jpeg',	'5b3208a62575a55138afed410ea37045b1654c25',	'2016-05-17'),
(13,	'WDF-1565823.jpg',	216519,	'cat23',	'image/jpeg',	'2b1a63624d26e7728e8deb9d8256f8b151d39b3e',	'2016-05-17'),
(14,	'WDF-1566512.jpg',	248574,	'cat23',	'image/jpeg',	'671b27d9ed033e20dafa1272c2442672a8608b37',	'2016-05-17'),
(15,	'WDF-1567714.jpg',	1197643,	'cat23',	'image/jpeg',	'2b1195c0d692454ad63600415aca1e0114fe49af',	'2016-05-17'),
(16,	'WDF-1566512.jpg',	248574,	'cat5',	'image/jpeg',	'51453fae1d4eda6f551ecbf86f18e4d05026b346',	'2016-05-17'),
(17,	'WDF-1565823.jpg',	216519,	'cat5',	'image/jpeg',	'a522e346799c71e5de413d28402e63a4d1c9328a',	'2016-05-17'),
(18,	'WDF-1565823.jpg',	216519,	'cat5',	'image/jpeg',	'0c9c0db4143beaf3a5c01dabab6573c12e01fb7b',	'2016-05-17'),
(19,	'WDF-1565823.jpg',	216519,	'cat5',	'image/jpeg',	'354b38efc98f434dd5bc880b65e80ca19e8e7611',	'2016-05-17'),
(20,	'WDF-1565823.jpg',	216519,	'cat5',	'image/jpeg',	'9ca16056e697e918a9bf6019cf9d276b5118c737',	'2016-05-17'),
(21,	'WDF-1566512.jpg',	248574,	'cat5',	'image/jpeg',	'1b85b5b3c161e5b918079be5a78ac1a3d6e628c1',	'2016-05-17'),
(22,	'WDF-1565823.jpg',	216519,	'cat5',	'image/jpeg',	'17aa7e3195f8b760486592932587ac7704eb2a5f',	'2016-05-17'),
(23,	'WDF-1566512.jpg',	248574,	'cat5',	'image/jpeg',	'e3a4325c47c839ff61e0b0d1dbf21cb66954571a',	'2016-05-17'),
(24,	'WDF-1567714.jpg',	1197643,	'cat5',	'image/jpeg',	'dca9e2262685e9b702fa3bc79377971f6c7ae0c2',	'2016-05-17'),
(25,	'WDF-1566512.jpg',	248574,	'cat3',	'image/jpeg',	'3c2aed9245f5fcb15b83793d7fc37415167d3445',	'2016-05-17'),
(26,	'WDF-1567714.jpg',	1197643,	'cat3',	'image/jpeg',	'6bee61be23b008a71c4505c39cf36a100559a3c5',	'2016-05-17'),
(27,	'WDF-1566512.jpg',	248574,	'cat2',	'image/jpeg',	'304e8dd1218ba7862679210974d45f216a2fef5a',	'2016-05-17'),
(28,	'WDF-1567714.jpg',	1197643,	'cat2',	'image/jpeg',	'668467e0f687dc4a7097a0073df10ae0b0fdc83d',	'2016-05-17'),
(29,	'WDF-1565823.jpg',	216519,	'cat2',	'image/jpeg',	'114c7c2c069f2b9dffcb6119fd67441825810eb8',	'2016-05-17'),
(30,	'WDF-1566512.jpg',	248574,	'cat2',	'image/jpeg',	'cc8cc2d8da1fc1e376e4fb466d89bd9cc2dcd663',	'2016-05-17'),
(31,	'JWTFlow.png',	55717,	'cat1',	'image/png',	'bdc0e161dfb8e822e7dcd0ed39f088a0405486fc',	'2016-05-29'),
(32,	'13275418-940485392735829-664115668-o.jpg',	139532,	'cat27',	'image/jpeg',	'78a809488edb5dc211aea578c5835801421bee9f',	'2016-05-29');

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `password` varchar(2048) NOT NULL,
  `role` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_1483A5E9F85E0677` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `user` (`id`, `email`, `password`, `role`) VALUES
(4,	'test@test.cz',	'$2y$10$KuuAzqx1p3blXUPanGGEz.oMQV0rePEDYvj9DMKFm.3FIESOevX6m',	'user'),
(5,	'admin@admin.cz',	'$2y$10$zTRyLbD8Mci4UBSNygMRiOdxJ1XcxkAjVKyzVCdo8FjuwCVZ7Rvde',	'admin');

DROP TABLE IF EXISTS `vaccination`;
CREATE TABLE `vaccination` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `severity` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `vaccination` (`id`, `name`, `severity`) VALUES
(1,	'Vzteklina',	1),
(2,	'Mor',	10),
(3,	'Imunita',	1),
(4,	'Proti ohni',	1);

-- 2016-05-29 20:51:28