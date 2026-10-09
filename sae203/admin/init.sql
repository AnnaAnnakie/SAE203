-- Adminer 4.8.1 MySQL 11.4.10-MariaDB-deb12-log dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `sae203_question`;
CREATE TABLE `sae203_question` (
                                   `id` int(11) NOT NULL AUTO_INCREMENT,
                                   `quiz` int(11) NOT NULL,
                                   `question` varchar(255) NOT NULL,
                                   PRIMARY KEY (`id`),
                                   KEY `quizz` (`quiz`),
                                   CONSTRAINT `sae203_question_ibfk_1` FOREIGN KEY (`quiz`) REFERENCES `sae203_quiz` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `sae203_question` (`id`, `quiz`, `question`) VALUES
                                                             (1,	1,	'Que signifie l\'acronyme HTTP ?'),
                                                             (2,	1,	'Ou est éxecute le code PHP dans une architecture web standard ?'),
                                                             (3,	1,	'Quel protocole est utilisé pour sécuriser les échanges sur le web ?'),
                                                             (4,	2,	'Que signifie PDO en PHP ?'),
                                                             (5,	2,	'Quelle methode de PDO permet d\'éviter efficacement les injections SQL ?'),
                                                             (6,	2,	'Quelle méthode de PDO utilise-t-on pour éxecuter une requête preparée ?'),
                                                             (7,	3,	'Que signifie l\'acronyme DOM ?'),
                                                             (8,	3,	'Quelle méthode permet de sélectionner le PREMIER élément correspondant à un sélecteur CSS ?'),
                                                             (9,	3,	'Quelle est la différence principale entre let et const ?');

DROP TABLE IF EXISTS `sae203_quiz`;
CREATE TABLE `sae203_quiz` (
                               `id` int(11) NOT NULL AUTO_INCREMENT,
                               `name` varchar(100) NOT NULL,
                               `creator` int(11) NOT NULL,
                               PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `sae203_quiz` (`id`, `name`, `creator`) VALUES
                                                        (1,	'Bases du Développement Web',	1),
                                                        (2,	'PHP et Connexion PDO',	1),
                                                        (3,	'JavaScript et Manipulation du DOM',	1);

DROP TABLE IF EXISTS `sae203_reponse`;
CREATE TABLE `sae203_reponse` (
                                  `id` int(11) NOT NULL AUTO_INCREMENT,
                                  `question` int(11) NOT NULL,
                                  `content` varchar(255) NOT NULL,
                                  `bonne_reponse` tinyint(1) NOT NULL,
                                  PRIMARY KEY (`id`),
                                  KEY `question` (`question`),
                                  CONSTRAINT `sae203_reponse_ibfk_1` FOREIGN KEY (`question`) REFERENCES `sae203_question` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `sae203_reponse` (`id`, `question`, `content`, `bonne_reponse`) VALUES
                                                                                (1,	1,	'HyperText Transfer Protocol',	1),
                                                                                (2,	1,	'High Technical Transfer Program',	0),
                                                                                (3,	1,	'Hyper Text Terminal Process',	0),
                                                                                (4,	1,	'Hosting Text Transfer Protocol',	0),
                                                                                (5,	2,	'Dans le navigateur de l\'utilisateur',	0),
                                                                                (6,	2,	'Sur le serveur web',	1),
                                                                                (7,	2,	'Dans la base de données directement',	0),
                                                                                (8,	2,	'Sur un serveur DNS',	0),
                                                                                (9,	3,	'FTP',	0),
                                                                                (10,	3,	'SSH',	0),
                                                                                (11,	3,	'HTTPS',	1),
                                                                                (12,	3,	'SMTP',	0),
                                                                                (13,	4,	'PHP Data Objects',	1),
                                                                                (14,	4,	'Php Database Organizer',	0),
                                                                                (15,	4,	'Program Data Object',	0),
                                                                                (16,	4,	'Process Data Optimization',	0),
                                                                                (17,	5,	'Les requêtes preparées',	1),
                                                                                (18,	5,	'La methode query()',	0),
                                                                                (19,	5,	'La fonction strip_tags()',	0),
                                                                                (20,	5,	'Le hachage MD5',	0),
                                                                                (21,	6,	'run()',	0),
                                                                                (22,	6,	'execute()',	1),
                                                                                (23,	6,	'query()',	0),
                                                                                (24,	6,	'fetch()',	0),
                                                                                (25,	7,	'Document Object Model',	1),
                                                                                (26,	7,	'Data On Memory',	0),
                                                                                (27,	7,	'Digital Object Management',	0),
                                                                                (28,	7,	'Direct Output Method',	0),
                                                                                (29,	8,	'document.getElementById()',	0),
                                                                                (30,	8,	'document.getElementsByClassName()',	0),
                                                                                (31,	8,	'document.querySelector()',	1),
                                                                                (32,	8,	'document.findAll()',	0),
                                                                                (33,	9,	'let est global et const est local',	0),
                                                                                (34,	9,	'Une variable const ne peut pas être réassignée après sa création',	1),
                                                                                (35,	9,	'let n\'existe pas en JavaScript, c\'est du PHP',	0),
                                                                                (36,	9,	'const consomme moins de mémoire que let',	0);

DROP TABLE IF EXISTS `sae203_resultat`;
CREATE TABLE `sae203_resultat` (
                                   `user` int(11) NOT NULL,
                                   `quiz` int(11) NOT NULL,
                                   `score` int(11) NOT NULL,
                                   PRIMARY KEY (`user`,`quiz`),
                                   KEY `question_user` (`quiz`,`user`),
                                   CONSTRAINT `sae203_resultat_ibfk_2` FOREIGN KEY (`user`) REFERENCES `sae203_user` (`id`),
                                   CONSTRAINT `sae203_resultat_ibfk_3` FOREIGN KEY (`quiz`) REFERENCES `sae203_quiz` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

INSERT INTO `sae203_resultat` (`user`, `quiz`, `score`) VALUES
    (1,	1,	0);

DROP TABLE IF EXISTS `sae203_user`;
CREATE TABLE `sae203_user` (
                               `id` int(11) NOT NULL AUTO_INCREMENT,
                               `username` varchar(100) NOT NULL,
                               `email` varchar(255) NOT NULL,
                               `password` varchar(255) NOT NULL,
                               `profilepic` blob DEFAULT NULL,
                               `admin` tinyint(1) NOT NULL,
                               PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

INSERT INTO `sae203_user` (`id`, `username`, `email`, `password`, `profilepic`, `admin`) VALUES
                                                                                             (1,	'admin',	'toto@admin.com',	'$2y$12$U9xm/KKdp3kzJJCirxjf6.Rukf4Pa7ULXXpE91AvzmVphKyG1Y/Ra', NULL,1),
                                                                                              (2,	'Annakie',	'anna.marchetti@gmail.com',	'$2y$12$ffYx/76VRo65tOdCRscW2.Na/k/aBtUCEukYczu42KjXxn1oktld.',	NULL,	0),
                                                                                              (3, 'Ernest', 'test@soutenance.uga', '$2y$12$U9xm/KKdp3kzJJCirxjf6.Rukf4Pa7ULXXpE91AvzmVphKyG1Y/Ra', NULL, 0);

-- 2026-05-18 15:26:24