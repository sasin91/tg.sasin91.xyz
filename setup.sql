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
CREATE DATABASE IF NOT EXISTS `sasin91` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `sasin91`;

-- Dumping structure for tabel sasin91.tenants
CREATE TABLE IF NOT EXISTS `tenants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain` varchar(255) NOT NULL,
  `database_config` text NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `domain_unique` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table sasin91.tenants
DELETE FROM `tenants`;
INSERT INTO `tenants` (`id`, `domain`, `database_config`) VALUES
  (1, 'localhost', 'eyJpdiI6IjNhR0JzUHBQSHNrbzBnQTBDc3A3bUE9PSIsInZhbHVlIjoia0ZVV0NYZjRCdWdqTjNyVEtzdnFRRDdBWXBRQjR0Z1YwNjQ1dUg4empQOD0iLCJtYWMiOiJCRVhhYTNIN3BlQXlrU0ZEMzVYd1FIcFhuZXlwbVVEZCtadG04Rk40M2U4PSIsInNlcmlhbGl6ZWQiOnRydWV9');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table sasin91.chats: ~0 rows (tilnærmelsesvis)
DELETE FROM `chats`;

-- Dumping structure for tabel sasin91.live_streams
CREATE TABLE IF NOT EXISTS `live_streams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url_string` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `summary` varchar(255) DEFAULT NULL,
  `live` tinyint(1) DEFAULT 0,
  `start_date_and_time` datetime DEFAULT NULL,
  `mux_stream_id` varchar(255) DEFAULT NULL,
  `mux_stream_key` varchar(255) DEFAULT NULL,
  `mux_playback_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table sasin91.live_streams: ~0 rows (tilnærmelsesvis)
DELETE FROM `live_streams`;

-- Dumping structure for tabel sasin91.trongate_administrators
CREATE TABLE IF NOT EXISTS `trongate_administrators` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(65) DEFAULT NULL,
  `password` varchar(60) DEFAULT NULL,
  `trongate_user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table sasin91.trongate_users: ~0 rows (tilnærmelsesvis)
DELETE FROM `trongate_users`;
INSERT INTO `trongate_users` (`id`, `code`, `user_level_id`) VALUES
	(1, 'Tz8tehsWsTPUHEtzfbYjXzaKNqLmfAUz', 1);

-- Dumping structure for tabel sasin91.trongate_user_levels
CREATE TABLE IF NOT EXISTS `trongate_user_levels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level_title` varchar(125) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table sasin91.trongate_user_levels: ~0 rows (tilnærmelsesvis)
DELETE FROM `trongate_user_levels`;
INSERT INTO `trongate_user_levels` (`id`, `level_title`) VALUES
	(1, 'admin');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
