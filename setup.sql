-- --------------------------------------------------------
-- Vært:                         127.0.0.1
-- Server-version:               11.5.2-MariaDB - mariadb.org binary distribution
-- ServerOS:                     Win64
-- HeidiSQL Version:             12.6.0.6765
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for sasin91
CREATE DATABASE IF NOT EXISTS `sasin91` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci */;
USE `sasin91`;

-- Dumping structure for tabel sasin91.chats
CREATE TABLE IF NOT EXISTS `chats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `channel` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `live_streams_id` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Dumping data for table sasin91.chats: ~0 rows (tilnærmelsesvis)
DELETE FROM `chats`;

-- Dumping structure for tabel sasin91.live_streams
CREATE TABLE IF NOT EXISTS `live_streams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url_string` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `summary` varchar(255) DEFAULT NULL,
  `live` tinyint(1) DEFAULT NULL,
  `start_date_and_time` datetime DEFAULT NULL,
  `ingest` varchar(255) DEFAULT NULL,
  `playlist` LONGTEXT DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Dumping data for table sasin91.live_streams: ~0 rows (tilnærmelsesvis)
DELETE FROM `live_streams`;

-- Dumping structure for tabel sasin91.localizations
CREATE TABLE IF NOT EXISTS `localizations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(255) DEFAULT NULL,
  `locale` varchar(255) DEFAULT NULL,
  `key` varchar(255) DEFAULT NULL,
  `value` longtext DEFAULT NULL,
  `created` datetime DEFAULT current_timestamp(),
  `updated` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Dumping data for table sasin91.localizations: ~96 rows (tilnærmelsesvis)
DELETE FROM `localizations`;
INSERT INTO `localizations` (`id`, `module`, `locale`, `key`, `value`, `created`, `updated`) VALUES
	(1, 'welcome', 'da_DK', 'Experienced developer with expertise across the full stack', 'Erfaren udvikler med ekspertise på tværs af hele stakken', '2024-09-20 09:45:00', '2024-09-20 09:45:00'),
	(2, 'welcome', 'da_DK', 'welcome.headline_1', '👋 Jeg er Jonas, en webudvikler fra Slagelse. \r\nJeg har arbejdet med PHP og Laravel siden 2015, \r\nog har siden da været med til at udvikle et billet agentur, en video streaming platform og en rekruteringsplatform.', '2024-09-20 10:13:00', '2024-09-20 10:16:00'),
	(3, 'welcome', 'en_US', 'welcome.headline_1', '👋 I&#039;m Jonas, a web developer from Slagelse. \r\nI have been working with PHP and Laravel since 2015, \r\nand since then have helped develop a ticket agency, a video streaming platform and a recruiting platform.', '2024-09-20 11:34:00', '2024-09-20 11:34:00'),
	(4, 'welcome', 'da_DK', 'welcome.headline_2', 'Jeg trives med nye og spændende ideér og udfordringer, og er altid klar på at lære noget nyt.', '2024-09-20 12:44:00', '2024-09-20 12:44:00'),
	(5, 'welcome', 'en_US', 'welcome.headline_2', 'I thrive on new and exciting ideas and challenges, and am always ready to learn something new.', '2024-09-20 12:45:00', '2024-09-20 12:45:00'),
	(6, 'welcome', 'da_DK', 'welcome.headline_3', 'Og anerkender vigtigheden af at skrive ren og læsbar kode, som er nem at vedligeholde.', '2024-09-20 12:45:00', '2024-09-20 12:45:00'),
	(7, 'welcome', 'en_US', 'welcome.headline_3', 'And recognizes the importance of writing clean and readable code that is easy to maintain.', '2024-09-20 12:46:00', '2024-09-20 12:46:00'),
	(8, 'welcome', 'en_US', 'timeline.webintegrator.heading', 'Trained WebIntegrator 🎉', '2024-09-20 13:23:00', '2024-09-20 13:25:00'),
	(9, 'welcome', 'en_US', 'timeline.webintegrator.content', 'I passed my training as a WebIntegrator with a 12. \r\nI have learned how to make websites, webshops and much more.', '2024-09-20 13:24:00', '2024-09-20 13:25:00'),
	(10, 'welcome', 'da_DK', 'timeline.webintegrator.heading', 'Uddannet WebIntegrator 🎉', '2024-09-20 13:25:00', '2024-09-20 13:25:00'),
	(11, 'welcome', 'da_DK', 'timeline.webintegrator.content', 'Jeg bestod min uddannelse som WebIntegrator med et 12 tal. \r\nJeg har lært at lave hjemmesider, webshops og meget mere.', '2024-09-20 13:26:00', '2024-09-20 13:26:00'),
	(12, 'welcome', 'en_US', 'timeline.ghc_travel.heading', 'Web developer at GHC Travel ✈️', '2024-09-20 13:27:00', '2024-09-20 13:27:00'),
	(13, 'welcome', 'en_US', 'timeline.ghc_travel.content', 'After some internships elsewhere, in 2017 I got my first job as a web developer at GHC Travel. \r\nI was responsible for migrating and modernizing their existing website from pure PHP to Laravel 6', '2024-09-20 13:27:00', '2024-09-20 13:44:00'),
	(14, 'welcome', 'da_DK', 'timeline.ghc_travel.heading', 'Webudvikler hos GHC Travel ✈️', '2024-09-20 13:27:00', '2024-09-20 13:27:00'),
	(15, 'welcome', 'da_DK', 'timeline.ghc_travel.content', 'Efter nogle praktik forløb andre steder, fik jeg i 2017 mit første job som web udvikler hos GHC Travel. \r\nJeg stod for at migrere og modernisere deres eksiesterende hjemmeside fra ren PHP til Laravel 6 &amp; Vue 2. \r\nJeg har senere hen holdt Laravel opdateret og flyttet frontend koden ud i separate Nuxt projekter med delt kode imellem.', '2024-09-20 13:29:00', '2024-09-20 13:29:00'),
	(16, 'welcome', 'en_US', 'timeline.syncronet.heading', 'Developer at Syncronet 📺', '2024-09-20 13:31:00', '2024-09-20 13:31:00'),
	(17, 'welcome', 'en_US', 'timeline.syncronet.content', 'After COVID-19 hit Denmark, I unfortunately had to change jobs. \r\nI got a job at Syncronet where I have been around to help develop their video streaming platform. \r\nIn broad strokes, I handled most of it.', '2024-09-20 13:32:00', '2024-09-20 13:32:00'),
	(18, 'welcome', 'da_DK', 'timeline.syncronet.heading', 'Udvikler hos Syncronet 📺', '2024-09-20 13:32:00', '2024-09-20 13:32:00'),
	(19, 'welcome', 'da_DK', 'timeline.syncronet.content', 'Efter COVID-19 ramte Danmark, blev jeg desværre nød til at skifte job. \r\nJeg fik et job hos Syncronet, hvor jeg har været vidt omkring for at hjælpe med at udvikle deres video streaming platform. \r\nI store drag, tog jeg af det meste.', '2024-09-20 13:33:00', '2024-09-20 13:33:00'),
	(20, 'welcome', 'en_US', 'timeline.juice.heading', 'Web developer at JUICE 👊', '2024-09-20 13:34:00', '2024-09-20 13:34:00'),
	(21, 'welcome', 'en_US', 'timeline.juice.content', 'In 2023, I got the opportunity to be part of something new and exciting. \r\nI got a job at JUICE, where I have helped develop a platform that turns the job market upside down.', '2024-09-20 13:34:00', '2024-09-20 13:34:00'),
	(22, 'welcome', 'da_DK', 'timeline.juice.heading', 'Webudvikler hos JUICE 👊', '2024-09-20 13:35:00', '2024-09-20 13:35:00'),
	(23, 'welcome', 'da_DK', 'timeline.juice.content', 'I 2023 fik jeg muligheden for at være med til noget nyt og spændende. \r\nJeg fik et job hos JUICE, hvor jeg har været med til at udvikle en platform som vender job markedet på hovedet.', '2024-09-20 13:35:00', '2024-09-20 13:35:00'),
	(24, 'welcome', 'en_US', 'timeline.supeo.heading', 'Developer at Supeo', '2024-09-20 13:36:00', '2024-09-20 13:36:00'),
	(25, 'welcome', 'en_US', 'timeline.supeo.content', 'At Supeo, I have been given an exciting opportunity to work with primarily Laravel and React.\r\nWe work with several different domains, so there was a lot of context switching. \r\nIn short, no two days are alike and there are plenty of opportunities to learn new things.', '2024-09-20 13:39:00', '2024-09-20 13:39:00'),
	(26, 'welcome', 'da_DK', 'timeline.supeo.heading', 'Udvikler hos Supeo', '2024-09-20 13:39:00', '2024-09-20 13:39:00'),
	(27, 'welcome', 'da_DK', 'timeline.supeo.content', 'Hos Supeo har jeg fået en spændende mulighed for at arbejde med primært Laravel og React.\r\nVi arbejder med flere forskellige domæner i en omskiftelig hverdag. \r\nKort fortalt, det er sjældent 2 dage ligner hinanden og der er massere af muligheder for at lære nyt.', '2024-09-20 13:40:00', '2024-09-20 13:40:00'),
	(28, 'welcome', 'en_US', 'timeline.juice_1.heading', 'Web developer at JUICE 👊', '2024-09-20 13:41:00', '2024-09-20 13:41:00'),
	(29, 'welcome', 'en_US', 'timeline.juice_1.content', 'Like a boomerang, I&#039;m back at Juice again. :)', '2024-09-20 13:42:00', '2024-09-20 13:43:00'),
	(30, 'welcome', 'da_DK', 'timeline.juice_1.heading', 'Webudvikler hos JUICE 👊', '2024-09-20 13:42:00', '2024-09-20 13:42:00'),
	(31, 'welcome', 'da_DK', 'timeline.juice_1.content', 'Som en boomerang, er jeg tilbage hos Juice igen. :)', '2024-09-20 13:42:00', '2024-09-20 13:42:00'),
	(32, 'welcome', 'en_US', 'why_me.heading', 'Get in the air safely and quickly', '2024-09-20 13:52:00', '2024-09-20 13:52:00'),
	(33, 'welcome', 'en_US', 'why_me.subheading', 'With me on the team, you have a teammate who can lift a bit of everything', '2024-09-20 13:53:00', '2024-09-20 13:53:00'),
	(34, 'welcome', 'en_US', 'features.servers.heading', 'Servers', '2024-09-20 14:40:00', '2024-09-20 14:41:00'),
	(35, 'welcome', 'en_US', 'features.servers.content', 'I have experience with many different Linux distros, and can set up a server or a cluster from scratch.', '2024-09-20 14:41:00', '2024-09-20 14:41:00'),
	(36, 'welcome', 'da_DK', 'features.servers.heading', 'Servere', '2024-09-20 14:42:00', '2024-09-20 14:43:00'),
	(37, 'welcome', 'da_DK', 'features.servers.content', 'Jeg har erfaring med mange forskellige Linux distros, og kan sætte en server eller et cluster op fra bunden.', '2024-09-20 14:43:00', '2024-09-20 14:45:00'),
	(38, 'welcome', 'en_US', 'features.security.heading', 'Security 🛡️', '2024-09-20 15:35:00', '2024-09-20 15:35:00'),
	(39, 'welcome', 'en_US', 'features.security.content', 'I have, among other things, worked with setting up secured VPN networks and has experience in making secure web applications.', '2024-09-20 15:36:00', '2024-09-20 15:36:00'),
	(40, 'welcome', 'da_DK', 'features.security.heading', 'Sikkerhed 🛡️', '2024-09-20 15:37:00', '2024-09-20 15:37:00'),
	(41, 'welcome', 'da_DK', 'features.security.content', 'Jeg har bla. arbejdet med at sætte sikrede VPN netværk op og har erfaring med at lave sikre web applikationer.', '2024-09-20 15:37:00', '2024-09-20 15:37:00'),
	(42, 'welcome', 'en_US', 'features.backend_dev.heading', 'Backend API development ⚙️', '2024-09-20 15:42:00', '2024-09-20 15:42:00'),
	(43, 'welcome', 'en_US', 'features.backend_dev.content', 'I have experience in creating APIs in Laravel, and have, among other things, \r\nmade an API for a video streaming platform and a ticket booking platform.', '2024-09-20 15:43:00', '2024-09-20 15:43:00'),
	(44, 'welcome', 'da_DK', 'features.backend_dev.heading', 'Backend API udvikling ⚙️', '2024-09-20 15:43:00', '2024-09-20 15:43:00'),
	(45, 'welcome', 'da_DK', 'features.backend_dev.content', 'Jeg har erfaring med at lave APIer i Laravel, og har bla. lavet et API til en video streaming platform samt en billet booking platform.', '2024-09-20 15:44:00', '2024-09-20 15:44:00'),
	(46, 'welcome', 'en_US', 'features.automated_testing.heading', 'Automated testing', '2024-09-20 15:45:00', '2024-09-20 15:45:00'),
	(47, 'welcome', 'en_US', 'features.automated_testing.content', 'I have experience doing automated tests in Laravel and Symfony with PHPUnit, I make use of TDD as far and as possible.', '2024-09-20 15:45:00', '2024-09-20 15:45:00'),
	(48, 'welcome', 'da_DK', 'features.automated_testing.heading', 'Automatiseret testing', '2024-09-20 15:46:00', '2024-09-20 15:46:00'),
	(49, 'welcome', 'da_DK', 'features.automated_testing.content', 'Jeg har erfaring med at lave automatiserede tests i Laravel og Symfony med PHPUnit, jeg gør brug af TDD så vidt og når det er muligt.', '2024-09-20 15:46:00', '2024-09-20 15:46:00'),
	(50, 'welcome', 'en_US', 'features.databases.heading', 'Databases 🍩', '2024-09-20 15:47:00', '2024-09-20 15:47:00'),
	(51, 'welcome', 'en_US', 'features.databases.content', 'I have extensive experience with MySQL, MariaDB', '2024-09-20 15:48:00', '2024-09-20 15:49:00'),
	(52, 'welcome', 'da_DK', 'features.databases.heading', 'Databaser 🍩', '2024-09-20 15:48:00', '2024-09-20 15:48:00'),
	(53, 'welcome', 'da_DK', 'features.databases.content', 'Jeg har udbredt erfaring med MySQL, MariaDB &amp; PostgreSQL. Jeg har erfaring med ren SQL, Eloquent og Doctrine.', '2024-09-20 15:48:00', '2024-09-20 15:48:00'),
	(54, 'welcome', 'en_US', 'features.frontend_dev.heading', 'Frontend development ✨', '2024-09-20 15:54:00', '2024-09-20 15:54:00'),
	(55, 'welcome', 'en_US', 'features.frontend_dev.content', 'I have experience in creating frontends in Vue, React and Laravel blade Livewire.', '2024-09-20 16:02:00', '2024-09-20 16:02:00'),
	(56, 'welcome', 'da_DK', 'features.frontend_dev.heading', 'Frontend udvikling ✨', '2024-09-20 16:03:00', '2024-09-20 16:03:00'),
	(57, 'welcome', 'da_DK', 'features.frontend_dev.content', 'Jeg har erfaring med at lave frontend i Vue, React og Laravel blade + Livewire.', '2024-09-20 16:04:00', '2024-09-20 16:04:00'),
	(58, 'welcome', 'en_US', 'features.app_dev.heading', 'App development 📱', '2024-09-20 16:05:00', '2024-09-20 16:05:00'),
	(59, 'welcome', 'en_US', 'features.app_dev.content', 'I have experience in making apps in React Native incl. \\nnative modules in Java/Kotlin', '2024-09-20 16:06:00', '2024-09-20 16:06:00'),
	(60, 'welcome', 'da_DK', 'features.app_dev.heading', 'App udvikling 📱', '2024-09-20 16:06:00', '2024-09-20 16:06:00'),
	(61, 'welcome', 'da_DK', 'features.app_dev.content', 'Jeg har erfaring med at lave apps i React Native inkl. native modules i Java/Kotlin &amp; Objc/Swift.', '2024-09-20 16:06:00', '2024-09-20 16:06:00'),
	(62, 'welcome', 'en_US', 'features.vcs.heading', 'Version Control', '2024-09-20 16:06:00', '2024-09-20 16:06:00'),
	(63, 'welcome', 'en_US', 'features.vcs.content', 'I have experience with Git and have, among other things, \r\nused it to keep track of my own projects as well as projects at work.', '2024-09-20 16:07:00', '2024-09-20 16:20:00'),
	(64, 'welcome', 'da_DK', 'features.vcs.heading', 'Version Control', '2024-09-20 16:21:00', '2024-09-20 16:21:00'),
	(65, 'welcome', 'da_DK', 'features.vcs.content', 'Jeg har erfaring med Git og har bla. brugt det til at holde styr på mine egne projekter samt projekter på arbejde.', '2024-09-20 16:21:00', '2024-09-20 16:21:00'),
	(66, 'welcome', 'en_US', 'features.backups.heading', 'Backups 💾', '2024-09-20 16:21:00', '2024-09-20 16:21:00'),
	(67, 'welcome', 'en_US', 'features.backups.content', 'I have learned how important it is to have backups of your data, and have experience in making backups of databases and files.', '2024-09-20 16:21:00', '2024-09-20 17:17:00'),
	(68, 'welcome', 'da_DK', 'features.backups.heading', 'Backups 💾', '2024-09-20 16:22:00', '2024-09-20 16:22:00'),
	(69, 'welcome', 'da_DK', 'features.backups.content', 'Jeg har lært hvor vigtigt det er at have backups af ens data, og har erfaring med at lave backups af databaser og filer.', '2024-09-20 16:24:00', '2024-09-20 16:24:00'),
	(70, 'welcome', 'en_US', 'features.heading', 'Get in the air safely and quickly', '2024-09-20 17:24:00', '2024-09-20 17:24:00'),
	(71, 'welcome', 'en_US', 'features.subheading', 'With me on the team, you have a teammate who can lift a bit of everything', '2024-09-20 17:25:00', '2024-09-20 17:25:00'),
	(72, 'welcome', 'da_DK', 'features.heading', 'Kom sikkert og hurtigt i luften', '2024-09-20 18:36:00', '2024-09-20 18:36:00'),
	(73, 'welcome', 'da_DK', 'features.subheading', 'Med mig på holdet, har i en medspiller som kan løfte lidt af det hele', '2024-09-20 18:37:00', '2024-09-20 18:37:00'),
	(74, 'blog', 'en_US', 'headline', 'The latest posts', '2024-09-20 22:07:00', '2024-09-20 22:07:00'),
	(75, 'blog', 'da_DK', 'headline', 'De seneste indslag', '2024-09-20 22:08:00', '2024-09-20 22:08:00'),
	(78, 'blog', 'en_US', 'trongate.summary', 'Trongate is often misunderstood and gets a bad reputation because it breaks with common standards and takes a journey back to its roots. \n In this article, I will explore and highlight this rough diamond that deserves a spot in the limelight.', '2024-09-23 11:36:09', '2024-09-23 11:36:09'),
	(79, 'blog', 'en_US', 'trongate.intro', 'An exciting PHP framework that challenges the established standards and offers a simple yet effective approach to development.', '2024-09-23 11:36:09', '2024-09-23 11:36:09'),
	(80, 'blog', 'en_US', 'trongate.tagline', 'I have worked with PHP for {{phpExpYears}} years, and one recurring thing is <underline>unnecessary</underline> code that needs maintenance. \n Occam\'s razor is clearly visible when all you want is to tear <underline>everything</underline> out by the roots and start fresh, hoping to build something more simple.', '2024-09-23 11:36:09', '2024-09-23 11:36:09'),
	(81, 'blog', 'en_US', 'trongate.mhavc', 'This is where Trongate shines; you get a simple starting point with a solid starting architecture due to everything being divided into modules, including media and JavaScript files.', '2024-09-23 11:36:09', '2024-09-23 11:36:09'),
	(82, 'blog', 'en_US', 'trongate.missing_i18n', 'A criticism that could be made is that internationalization (i18n) hasn\'t been considered, and there is actually no real language support. \n This is true and something I have <pr-link>previously</pr-link> addressed. It\'s on the roadmap, and I look forward to seeing how it gets resolved. :)', '2024-09-23 11:36:09', '2024-09-23 11:36:09'),
	(83, 'blog', 'en_US', 'trongate.callback_validation', 'Another feature that often flies under the radar is <u>callback</u> based validation.', '2024-09-23 11:36:09', '2024-09-23 11:36:09'),
	(84, 'blog', 'en_US', 'trongate.hope', 'My hope with this article is to spark people\'s interest and highlight a rough diamond that unfortunately has gained a somewhat bad reputation.', '2024-09-23 11:36:09', '2024-09-23 11:36:09'),
	(85, 'blog', 'en_US', 'trongate.before_i_go', 'Before I go, I just want to shout out', '2024-09-23 11:36:09', '2024-09-23 11:36:09'),
	(86, 'blog', 'en_US', 'trongate.mx', '<div>A library based on <a href="https://htmx.org">HTMX</a> and in many ways has the same features, just using mx- instead of hx-.</div>', '2024-09-23 11:36:09', '2024-09-23 11:23:00'),
	(87, 'blog', 'en_US', 'trongate.mx_more', '<div>And yet it offers so much more, with close integration, built-in modal support, <a href="https://trongate.io/docs/trongate-mx/managing-ui-during-requests.html">"optimistic" UI</a>, and <a href="https://trongate.io/docs/trongate-mx/after-swap-operations.html">more</a>.</div>', '2024-09-23 11:36:09', '2024-09-23 11:30:00'),
	(88, 'blog', 'da_DK', 'trongate.summary', 'Trongate bliver ofte misforstået og får et dårligt ry, fordi det bryder med de gængse standarder og tager en rejse tilbage til rødderne. \n I dette indslag vil jeg udforske og fremhæve denne uslebne diamant, der fortjener en plads i rampelyset.', '2024-09-23 11:36:09', '2024-09-23 11:36:09'),
	(89, 'blog', 'da_DK', 'trongate.intro', 'Et spændende PHP framework, der udfordrer de etablerede standarder og tilbyder en enkel, men effektiv tilgang til udvikling.', '2024-09-23 11:36:09', '2024-09-23 11:36:09'),
	(90, 'blog', 'da_DK', 'trongate.tagline', 'Jeg har arbejdet med PHP i {{phpExpYears}} år og en ting der går igen, er <underline>unødvendig</underline> kode som skal vedligeholdes. \n Occam\'s razor kan nemt ses, når man aller helst vil rive <underline>alt</underline> op med roden og starte forfra, i håb om at bygge noget mere enkelt.', '2024-09-23 11:36:09', '2024-09-23 11:36:09'),
	(91, 'blog', 'da_DK', 'trongate.mhavc', 'Det er her hvor Trongate skinner, du får et enkelt starts  punkt med en solid starts arkitektur i kraft af at alt er indelt i moduler, inkl. media og JavaScript filer.', '2024-09-23 11:36:09', '2024-09-23 11:36:09'),
	(92, 'blog', 'da_DK', 'trongate.missing_i18n', 'En kritik der kunne gives, er at internationalisering (i18n) ikke er tænkt på og der faktisk ikke er nogen videre support for sprog. \n Det er korrekt og noget jeg <pr-link>tidligere</pr-link> har taklet, det er i planerne og jeg glæder mig til at se hvordan det løses. :)', '2024-09-23 11:36:09', '2024-09-23 11:36:09'),
	(93, 'blog', 'da_DK', 'trongate.callback_validation', 'En anden feature som tit flyver under radaren, er callback baseret validering', '2024-09-23 11:36:09', '2024-09-23 11:36:09'),
	(94, 'blog', 'da_DK', 'trongate.hope', 'Mit håb med denne artikel, er at vække folks interesse og fremhæve en usleben diamant som desværre har fået lidt et dårligt ry.', '2024-09-23 11:36:09', '2024-09-23 11:36:09'),
	(95, 'blog', 'da_DK', 'trongate.before_i_go', 'Før jeg går, vil jeg lige udpege', '2024-09-23 11:36:09', '2024-09-23 11:36:09'),
	(96, 'blog', 'da_DK', 'trongate.mx', '<div>Et bibliotek baseret på <a href="https://htmx.org">HTMX</a> og meget hen af vejen har de samme features, bare mx- istedet for hx-</div>', '2024-09-23 11:36:09', '2024-09-23 11:24:00'),
	(97, 'blog', 'da_DK', 'trongate.mx_more', '<div>Og dog tilbyder så meget mere, i form af en tæt integration, indbygget modal support, <a href="https://trongate.io/docs/trongate-mx/managing-ui-during-requests.html">"optimistisk" UI</a> og <a href="https://trongate.io/docs/trongate-mx/after-swap-operations.html">mere</a>.</div>', '2024-09-23 11:36:09', '2024-09-23 11:29:00'),
	(98, 'blog', 'da_DK', 'read_more', 'Læs mere', '2024-09-23 10:58:00', '2024-09-23 10:58:00'),
	(99, 'blog', 'en_US', 'read_more', 'Read more', '2024-09-23 10:58:00', '2024-09-23 10:58:00');

-- Dumping structure for tabel sasin91.trongate_administrators
CREATE TABLE IF NOT EXISTS `trongate_administrators` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(65) DEFAULT NULL,
  `password` varchar(60) DEFAULT NULL,
  `trongate_user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Dumping data for table sasin91.trongate_administrators: ~0 rows (tilnærmelsesvis)
DELETE FROM `trongate_administrators`;
INSERT INTO `trongate_administrators` (`id`, `username`, `password`, `trongate_user_id`) VALUES
	(1, 'admin', '$2y$11$SoHZDvbfLSRHAi3WiKIBiu.tAoi/GCBBO4HRxVX1I3qQkq3wCWfXi', 1);

-- Dumping structure for tabel sasin91.trongate_comments
CREATE TABLE IF NOT EXISTS `trongate_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment` text DEFAULT NULL,
  `date_created` int(11) DEFAULT 0,
  `user_id` int(11) DEFAULT NULL,
  `target_table` varchar(125) DEFAULT NULL,
  `update_id` int(11) DEFAULT NULL,
  `code` varchar(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Dumping data for table sasin91.trongate_comments: ~0 rows (tilnærmelsesvis)
DELETE FROM `trongate_comments`;

-- Dumping structure for tabel sasin91.trongate_pages
CREATE TABLE IF NOT EXISTS `trongate_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url_string` varchar(255) DEFAULT NULL,
  `page_title` varchar(255) DEFAULT NULL,
  `meta_keywords` text DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `page_body` text DEFAULT NULL,
  `date_created` int(11) DEFAULT NULL,
  `last_updated` int(11) DEFAULT NULL,
  `published` tinyint(1) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Dumping data for table sasin91.trongate_pages: ~0 rows (tilnærmelsesvis)
DELETE FROM `trongate_pages`;
INSERT INTO `trongate_pages` (`id`, `url_string`, `page_title`, `meta_keywords`, `meta_description`, `page_body`, `date_created`, `last_updated`, `published`, `created_by`) VALUES
	(1, 'homepage', 'Homepage', '', '', '<div style="text-align: center;">\n    <h1>It Totally Works!</h1>\n</div>\n<div class="text-div">\n    <p>\n        <i>Congratulations!</i> You have successfully installed Trongate.  <b>This is your homepage.</b>  Trongate was built with a focus on lightning-fast performance, while minimizing dependencies on third-party libraries. By adopting this approach, Trongate delivers not only exceptional speed but also rock-solid stability.\n    </p>\n    <p>\n        <b>You can change this page and start adding new content through the admin panel.</b>\n    </p>\n</div>\n<h2>Getting Started</h2>\n<div class="text-div">\n    <p>\n        To get started, log into the <a href="[website]tg-admin">admin panel</a>. From the admin panel, you\'ll be able to easily edit <i>this</i> page or create entirely <i>new</i> pages. The default login credentials for the admin panel are as follows:\n    </p>\n    <ul>\n        <li>Username: <b>admin</b></li>\n        <li>Password: <b>admin</b></li>\n    </ul>\n</div>\n<div class="button-div" style="cursor: pointer; font-size: 1.2em;">\n    <div style="text-align: center;">\n        <button onclick="window.location=\'[website]tg-admin\';">Admin Panel</button>\n        <button class="alt" onclick="window.location=\'https://trongate.io/docs\';">Documentation</button>\n    </div>\n</div>\n<h2 class="mt-2">About Trongate</h2>\n<div class="text-div">\n    <p>\n        <a href="https://trongate.io/" target="_blank">Trongate</a> is an open source project, written in PHP. The GitHub repository for Trongate is <a href="https://github.com/trongate/trongate-framework" target="_blank">here</a>. Contributions are welcome! If you\'re interested in learning how to build custom web applications with Trongate, a good place to start is The Learning Zone. The URL for the Learning Zone is: <a href="https://trongate.io/learning-zone" target="_blank">https://trongate.io/learning-zone</a>. <b>If you enjoy working with Trongate, all we ask is that you give Trongate a star on <a href="https://github.com/trongate/trongate-framework" target="_blank">GitHub</a>.</b> It really helps!\n    </p>\n    <p>\n        Finally, if you run into any issues or you require technical assistance, please do visit our free Help Bar, which is at: <a href="https://trongate.io/help_bar" target="_blank">https://trongate.io/help_bar</a>.\n    </p>\n</div>', 1723807486, 0, 1, 1);

-- Dumping structure for tabel sasin91.trongate_tokens
CREATE TABLE IF NOT EXISTS `trongate_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(125) DEFAULT NULL,
  `user_id` int(11) DEFAULT 0,
  `expiry_date` int(11) DEFAULT NULL,
  `code` varchar(3) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Dumping data for table sasin91.trongate_tokens: ~1 rows (tilnærmelsesvis)
DELETE FROM `trongate_tokens`;
INSERT INTO `trongate_tokens` (`id`, `token`, `user_id`, `expiry_date`, `code`) VALUES
	(4, 'Z7apeYdJKWqGZqpU6b2kQLXnbTpDhqdd', 1, 1727168522, '0'),
	(5, '7hufMZym2MCgrrTn4pdGg6sNmEyhQqNS', 1, 1727169894, '0'),
	(6, 'sLfqsSDFbsb4mMwVjJ7up5BDr3cp5vJs', 1, 1727775288, '0'),
	(7, 'AtvsNKq8SBg3EV3rtQG5apzgCuCUJueF', 1, 1727803888, '0');

-- Dumping structure for tabel sasin91.trongate_users
CREATE TABLE IF NOT EXISTS `trongate_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(32) DEFAULT NULL,
  `user_level_id` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Dumping data for table sasin91.trongate_users: ~0 rows (tilnærmelsesvis)
DELETE FROM `trongate_users`;
INSERT INTO `trongate_users` (`id`, `code`, `user_level_id`) VALUES
	(1, 'Tz8tehsWsTPUHEtzfbYjXzaKNqLmfAUz', 1);

-- Dumping structure for tabel sasin91.trongate_user_levels
CREATE TABLE IF NOT EXISTS `trongate_user_levels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level_title` varchar(125) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Dumping data for table sasin91.trongate_user_levels: ~0 rows (tilnærmelsesvis)
DELETE FROM `trongate_user_levels`;
INSERT INTO `trongate_user_levels` (`id`, `level_title`) VALUES
	(1, 'admin');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
