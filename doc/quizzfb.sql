-- phpMyAdmin SQL Dump
-- version 3.3.7deb7
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Jeu 23 Mai 2013 à 22:27
-- Version du serveur: 5.1.66
-- Version de PHP: 5.3.24-1~dotdeb.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `quizzfb`
--

-- --------------------------------------------------------

--
-- Structure de la table `answer`
--

DROP TABLE IF EXISTS `answer`;
CREATE TABLE IF NOT EXISTS `answer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_correct` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_DADD4A251E27F6BF` (`question_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=71 ;

--
-- Contenu de la table `answer`
--

INSERT INTO `answer` (`id`, `question_id`, `title`, `is_correct`) VALUES
(1, 50, 'Lost, les disparus', 0),
(2, 50, '24 heures chrono', 1),
(3, 50, 'NCIS', 0),
(4, 51, 'Robert Sean Leonard', 0),
(5, 51, 'Jesse Spencer', 0),
(6, 51, 'Hugh Laurie', 1),
(7, 52, 'Nip/Tuck', 1),
(9, 52, 'Scrubs', 0),
(10, 52, 'Grey''s anatomy', 0),
(11, 53, 'Horatio Caine', 0),
(12, 53, 'Gil Grissom', 0),
(13, 53, 'Mack Taylor', 1),
(14, 54, 'Liza Weil', 0),
(15, 54, 'Alexis Bledel', 1),
(16, 54, 'Lauren Graham', 0),
(17, 55, 'Prison Break', 1),
(18, 55, 'Le Prisonnier', 0),
(19, 55, 'Oz', 0),
(20, 56, 'Chirurgien', 0),
(21, 56, 'Pédiatre', 0),
(22, 56, 'Médecin légiste', 1),
(23, 57, 'New Criminal Independent Service', 0),
(24, 57, 'Naval Criminal Investigative Service', 1),
(25, 57, 'National Coalition of Independent Service', 0),
(26, 58, 'Bones', 0),
(27, 58, 'The Unit', 1),
(28, 58, 'Angela''s Eyes', 0),
(29, 59, '2 200', 0),
(30, 59, '3 300', 0),
(31, 59, '4 400', 1),
(32, 60, 'Washington', 0),
(33, 60, 'Le Caire', 0),
(34, 60, 'Paris', 1),
(35, 61, 'Serbie', 1),
(36, 61, 'Turquie', 0),
(37, 61, 'Nigeria', 0),
(38, 62, 'Lisbonne', 0),
(39, 62, 'Madrid', 1),
(40, 62, 'Rome', 0),
(41, 63, 'Nathalie Portman', 1),
(42, 63, 'Audrey Hepburn', 0),
(43, 63, 'Jane Fonda', 0),
(44, 64, '9 octobre 2009', 0),
(45, 64, '20 janvier 2009', 1),
(46, 64, '4 novembre 2008', 0),
(47, 65, 'James Cameron', 0),
(48, 65, 'Woody Allen', 1),
(49, 65, 'Clint Eastwood', 0),
(50, 66, 'Nicholas Sarkozy', 0),
(51, 66, 'Nicolas Sarkozy', 1),
(52, 66, 'Nicholas Sarkôzy', 0),
(53, 67, 'Arnold Schwarzenegger', 0),
(54, 67, 'Sylvester Stallone', 1),
(55, 67, 'The Rock', 0),
(56, 68, 'Michel Drucker', 1),
(57, 68, 'Julien Lepers', 0),
(58, 68, 'André Manoukian', 0),
(59, 69, 'Patrick Dewaere', 0),
(60, 69, 'Coluche', 1),
(61, 69, 'Elie Semoun', 0),
(62, 70, 'Djamel Debouzze', 1),
(63, 70, 'Elie Semoun', 0),
(64, 70, 'Gad Elmaleh', 0),
(65, 71, 'Sophie Duez', 0),
(66, 71, 'Véronique Genest', 0),
(67, 71, 'Lorie', 1),
(68, 72, 'Franck Duboscq', 0),
(69, 72, 'Elie Semoun', 1),
(70, 72, 'Pascal Sevran', 0);

-- --------------------------------------------------------

--
-- Structure de la table `question`
--

DROP TABLE IF EXISTS `question`;
CREATE TABLE IF NOT EXISTS `question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quizz_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `picture` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B6F7494EBA934BCD` (`quizz_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=73 ;

--
-- Contenu de la table `question`
--

INSERT INTO `question` (`id`, `quizz_id`, `title`, `picture`) VALUES
(50, 10, 'Dans quelle série Kiefer Shuterland joue-t-il le rôle principal ?', '3d1089e61d6af0a0dc41714c2aa5f5fa56483333.jpeg'),
(51, 10, 'Qui joue le rôle du Dr House dans la série du même nom ?', '5f4fb175b9c15aa3ec432fdaa951927ac7243b26.jpeg'),
(52, 10, 'Quelle série a pour décor une clinique de chirurgie esthétique ?', '55de19e65a65cf18471860a39af8b3c52b528860.jpeg'),
(53, 10, 'Qui est le chef de l''unité scientifique de la police de Manhattan ?', '2d2b0bf8a435313d6521737a0b0418c4a4266062.jpeg'),
(54, 10, 'Qui joue le rôle de Rory Gilmore ?', 'b93eecddcdf68de72cfbe6aacfda515e12502748.jpeg'),
(55, 16, 'Dans quel série Michael Scofield se fait-il incarcérer dans le but de faire évader son frère ?', '217de276847c0b62b8c59e53b67a95be4f952216.jpeg'),
(56, 16, 'Quel métier exerce le Dr Jordan Cavannaugh ?', '3aed6988c4fea46823f0a88ad7678d6455ef06bf.jpeg'),
(57, 16, 'Que veut dire NCIS ?', '56b6a6263c28ffeef68d7881675c1793b59b7ffd.jpeg'),
(58, 16, 'Comment s''appelle la nouvelle série dans laquelle Dennis Haysbert tient le rôle principal ?', '158445f06482622a1ffb5c2844407bc6486627d5.jpeg'),
(59, 16, 'Combien de personnes réapparaissent mystérieusement après plusieurs années, dans la série du même nom ?', 'fd4d98d15ba9dc9cbb08700f9e4b439af20096c3.jpeg'),
(60, 13, 'Trouver cette capitale', '091a15d8b5283a975ce1287e32e10c36123d6b4d.jpeg'),
(61, 13, 'Où se situe la ville de Belgrade ?', '0f9e29b331fa9e80b659605c9b05cc9465e81059.jpeg'),
(62, 13, 'Qu''elle est la capitale de L’Espagne ?', '624c43d52d949555db07725a5113f6b65b08e023.jpeg'),
(63, 17, 'Cette actrice fut révélée dans "Léon" avec Jean Reno !', '5a56458b3956cc5c44f3f15f937cd1d5be29bf53.jpeg'),
(64, 17, 'A quelle date Barack Obama remporta-t-il les élections présidentielles américaines ?', 'e88eba8860c497971c7203feb701d21f52495de5.jpeg'),
(65, 17, 'Né en 1935, immense réalisateur et scénariste américain !', '53716ab94f13110d153a77e377421809bdc393e6.png'),
(66, 17, 'Travailler plus pour gagner plus !', '6ce7166216f6e83d0bcd78bbb6308afb37f18abe.jpeg'),
(67, 17, 'Rambo ou Rocky !', 'e162ebca2e199814e5e6684d3f8e1c0d79acc836.jpeg'),
(68, 18, 'Cet enfant est . . .', '24d9d82ebb5dbdd44244e9a418bc6446d2d6799c.jpeg'),
(69, 18, 'Cet enfant est . . .', '5cb75070da7be16fa89118c2bd0081facac574fe.jpeg'),
(70, 18, 'Cet enfant est . . .', '3f52922bdf151bf2244bddc3d9a2446aa39d0084.jpeg'),
(71, 18, 'Cet enfant est . . .', '8d1b14af0fc3be13522d003d80008ab69ad3dd36.jpeg'),
(72, 18, 'Cet enfant est . . .', '712ca7027174c692178a0e296f2d983d6326c1b8.jpeg');

-- --------------------------------------------------------

--
-- Structure de la table `quizz`
--

DROP TABLE IF EXISTS `quizz`;
CREATE TABLE IF NOT EXISTS `quizz` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `theme_id` int(11) DEFAULT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `picture` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `short_desc` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `long_desc` longtext COLLATE utf8_unicode_ci,
  `win_points` int(11) NOT NULL,
  `average_time` int(11) NOT NULL,
  `txt_win_1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `txt_win_3` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `txt_win_2` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `txt_win_4` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `share_wall_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `share_wall_desc` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_promoted` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `state` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_7C77973D59027487` (`theme_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=19 ;

--
-- Contenu de la table `quizz`
--

INSERT INTO `quizz` (`id`, `theme_id`, `title`, `picture`, `short_desc`, `long_desc`, `win_points`, `average_time`, `txt_win_1`, `txt_win_3`, `txt_win_2`, `txt_win_4`, `share_wall_title`, `share_wall_desc`, `is_promoted`, `created_at`, `state`) VALUES
(10, 16, 'Séries Américaines', '87ce4d4195ce594ab28e8c9a1d4f4298369db408.jpeg', 'Regardez-vous les séries américaines ?', 'Différentes questions sur les séries telle que breking bad, games of thrones, dr house etc ...', 20, 100, 'Il n''y à pas que les séries françaises !', 'C''est presque ça !', 'C''est pas gagné !', 'Félicitations t''es un expert dans les séries américaines.', 'Quizz sur les séries américaines', 'Différentes questions portant sur les grandes séries américaines', 0, '2013-05-13 03:12:58', 1),
(13, 19, 'Capitale des pays', '38de98eb76c9233efaa34e2975ee4357cf324543.jpeg', 'Quizz pour tester vos connaissances sur les capitales des pays', 'Ce quizz sur la géographie va permettre de tester vos connaissances sur les capitales des différents continents', 40, 100, 'T''as eu ton bac dans une pochette surprise ?', 'Joli score', 'C''est pas encore ça', 'Félicitations !', 'Capitale des pays', 'Quizz de géographie sur les capitales des pays', 0, '2013-05-13 05:42:02', 1),
(16, 16, 'Séries Américaines 2', '97d1b239be424fa70024c88609d1fedca5f36e39.png', 'En avant pour cette deuxième version des séries américaines', 'Différentes questions sur les séries telle que NCIS, dr house etc ...', 50, 150, 'sois pas triste tu es nul c''est tout', 'Bien joué, mais je sais que tu peux mieux faire', 'j''ai connu des scores meilleur...', 'Extra, tu gères ;)', 'Séries Américaines 2', 'En avant pour cette deuxième version des séries américaines.\r\nDifférentes questions sur les séries telle que NCIS, dr house etc ...', 0, '2013-05-23 20:54:51', 1),
(17, 16, 'Quizz célébrités', '92d8e71a40ad7fa4c01b7b9884d3ad2e3962e552.jpeg', 'Ce Quizz vous permet de tester vos connaissances sur les people.', 'Ce Quizz vous permet de tester vos connaissances sur les people.', 20, 80, 'Tu sors jamais de chez toi ? achète toi une télé ;)', 'Tes connaissances people sont impressionnantes :)', 'Arf, tu aurais pu te dépasser un peu plus', 'Tu nages parmi les people avec facilité :p', 'Quizz célébrités', 'Ce Quizz vous permet de tester vos connaissances  people.', 0, '2013-05-23 21:32:28', 1),
(18, 16, 'Quizz célébrités 2', '54a13a8726e5e2bfcb5469b669903e1de707a3f5.png', 'En avant pour la deuxième version du Quizz célébrité, cette fois ils sont tous petits ;)', 'De nouvelle questions sur nos cher amis les people', 200, 200, 'tu n''as toujours pas acheté de télé il me semble', 'bravo, digne d''un paparazzi', 'encore un effort...', 'serais tu une star ? ;)', 'Quizz célébrités 2', 'En avant pour la deuxième version du Quizz célébrité.\r\nDe nouvelle questions sur nos cher amis les people', 1, '2013-05-23 21:36:37', 1);

-- --------------------------------------------------------

--
-- Structure de la table `quizz_result`
--

DROP TABLE IF EXISTS `quizz_result`;
CREATE TABLE IF NOT EXISTS `quizz_result` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `quizz_id` int(11) DEFAULT NULL,
  `date_start` datetime NOT NULL,
  `date_end` datetime DEFAULT NULL,
  `average` double DEFAULT NULL,
  `win_points` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_4995E702A76ED395` (`user_id`),
  KEY `IDX_4995E702BA934BCD` (`quizz_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Contenu de la table `quizz_result`
--


-- --------------------------------------------------------

--
-- Structure de la table `theme`
--

DROP TABLE IF EXISTS `theme`;
CREATE TABLE IF NOT EXISTS `theme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `picture` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `short_desc` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `long_desc` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=22 ;

--
-- Contenu de la table `theme`
--

INSERT INTO `theme` (`id`, `title`, `picture`, `short_desc`, `long_desc`) VALUES
(16, 'Séries TV', '687a8e3251556facd689176b8446706e652de003.jpeg', 'Tester vos connaissances en séries parmi les plus célèbres du moment', 'Vous êtes incollable sur les séries du moment ? alors ce thème est fait pour vous, des séries Américaines aux Françaises, tester votre culture et votre rapidité.'),
(19, 'Géographie', '2a44e5b8aa6d37fc8fa6587cb1045bb1c643f474.jpeg', 'venez tester vos connaissances géographiques. Incollable sur les pays, capitales, océans..? Ce thème est fait pour vous', 'Ce thème permet aux passionnés de géographie de tester leur connaissance et leur rapidité. Continents, pays, mer & océans... tout y est traité.'),
(20, 'Célébrités', '5be96ba8726302b81127eac9350b0faa7cf40f58.jpeg', 'Ce thème vous propose de tester vos connaissances people.', 'Incollable sur les célébrités du moment ? ce thème va vous surprendre'),
(21, 'Musique Françaises', '374f8fc4b4fbf15751079cb6097d6390a3820461.jpeg', 'fin connaisseur de musiques française ? vous allez apprécier ce thème', 'Retrouver dans ce thème les chanteurs Français d''hier et d''aujourd''hui.');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fb_uid` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `firstname` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `picture` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `points` double DEFAULT NULL,
  `average_time` double DEFAULT NULL,
  `nb_quizz` double DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `lastconnect_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`id`, `fb_uid`, `firstname`, `lastname`, `username`, `picture`, `points`, `average_time`, `nb_quizz`, `created_at`, `lastconnect_at`) VALUES
(1, '100003125315007', 'Mickaël', 'Chapusot', NULL, 'https://graph.facebook.com/100003125315007/picture', 8, 8, 1, '2013-05-21 11:05:00', NULL),
(2, '12', 'first', 'last', 'uname', 'jj.jpg', 300, 2, 2, '2013-05-23 15:31:00', NULL),
(3, '4', 'fgg', 'dfgdf', 'dfgfdg', 'fdgdf', 500, NULL, NULL, '0000-00-00 00:00:00', NULL),
(4, 'dfdg', 'dfgfdg', 'fgfg', 'fgfg', 'fgfd', 10, NULL, NULL, '0000-00-00 00:00:00', NULL),
(5, 'qdqdqs', 'sdqsd', 'sdqd', 'qsdqs', 'qdsdsq', 2, 23, NULL, '0000-00-00 00:00:00', NULL),
(6, 'dcsdv', 'svsdv', 'dvdv', 'sdv', 'svsd', 1, 0, 0, '0000-00-00 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `user_answer`
--

DROP TABLE IF EXISTS `user_answer`;
CREATE TABLE IF NOT EXISTS `user_answer` (
  `user_id` int(11) NOT NULL,
  `answer_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`answer_id`),
  KEY `IDX_BF8F5118A76ED395` (`user_id`),
  KEY `IDX_BF8F5118AA334807` (`answer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `user_answer`
--


--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `answer`
--
ALTER TABLE `answer`
  ADD CONSTRAINT `FK_DADD4A251E27F6BF` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`);

--
-- Contraintes pour la table `question`
--
ALTER TABLE `question`
  ADD CONSTRAINT `FK_B6F7494EBA934BCD` FOREIGN KEY (`quizz_id`) REFERENCES `quizz` (`id`);

--
-- Contraintes pour la table `quizz`
--
ALTER TABLE `quizz`
  ADD CONSTRAINT `FK_7C77973D59027487` FOREIGN KEY (`theme_id`) REFERENCES `theme` (`id`);

--
-- Contraintes pour la table `quizz_result`
--
ALTER TABLE `quizz_result`
  ADD CONSTRAINT `FK_4995E702A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_4995E702BA934BCD` FOREIGN KEY (`quizz_id`) REFERENCES `quizz` (`id`);

--
-- Contraintes pour la table `user_answer`
--
ALTER TABLE `user_answer`
  ADD CONSTRAINT `FK_BF8F5118A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_BF8F5118AA334807` FOREIGN KEY (`answer_id`) REFERENCES `answer` (`id`) ON DELETE CASCADE;
