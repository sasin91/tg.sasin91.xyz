-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-11-2021 a las 08:20:59
-- Versión del servidor: 10.4.18-MariaDB
-- Versión de PHP: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `blog`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `associated_blog_notices_and_blog_categories`
--

CREATE TABLE `associated_blog_notices_and_blog_categories` (
  `id` int(11) NOT NULL,
  `blog_notices_id` int(11) NOT NULL DEFAULT 0,
  `blog_categories_id` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `associated_blog_notices_and_blog_categories`
--

INSERT INTO `associated_blog_notices_and_blog_categories` (`id`, `blog_notices_id`, `blog_categories_id`) VALUES
(1, 1, 2),
(2, 2, 1),
(3, 1, 1),
(4, 3, 2),
(5, 4, 1),
(6, 5, 1),
(7, 3, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `blog_categories`
--

CREATE TABLE `blog_categories` (
  `id` int(11) NOT NULL,
  `url_string` varchar(255) DEFAULT NULL,
  `category_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `blog_categories`
--

INSERT INTO `blog_categories` (`id`, `url_string`, `category_name`) VALUES
(1, 'entertainment', 'Entertainment'),
(2, 'italian-food', 'Italian Food');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `blog_notices`
--

CREATE TABLE `blog_notices` (
  `id` int(11) NOT NULL,
  `url_string` varchar(255) DEFAULT NULL,
  `blog_title` varchar(255) DEFAULT NULL,
  `blog_sub_title` varchar(255) DEFAULT NULL,
  `notice` text DEFAULT NULL,
  `youtube` varchar(50) DEFAULT NULL,
  `uploaded_date` date DEFAULT NULL,
  `published_date` date DEFAULT NULL,
  `published` tinyint(1) DEFAULT NULL,
  `picture` varchar(255) DEFAULT '',
  `notice_sources_id` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `blog_notices`
--

INSERT INTO `blog_notices` (`id`, `url_string`, `blog_title`, `blog_sub_title`, `notice`, `youtube`, `uploaded_date`, `published_date`, `published`, `picture`, `notice_sources_id`) VALUES
(1, 'creamy-pumpkin--sausage-sage-pasta', 'CREAMY PUMPKIN & SAUSAGE SAGE PASTA', '', '<span style=\"color: rgb(62, 58, 59); font-family: \"Gentium Basic\", Georgia, \"Times New Roman\", Times, serif; font-size: 17px; background-color: rgb(255, 255, 255);\">Fall has arrived and brought with it a fantastic selection of new fruits and vegetables for us to enjoy. I am now buying apples, pears, greens, and pumpkin each and every shopping trip and am thoroughly enjoying creating new fall recipes with these ingredients. I love summer, but nothing inspires a cook more than having a whole unique selection of produce to play with! In Umbria, we do not have the large selection of winter squash that I am used to back in North America, but as soon as the cool weather is here, zucca (pumpkin) can be found in every grocery store or outdoor market. Since there is no such thing as canned pumpkin in Italy, I often buy it fresh and roast it, puree it, and then use it for my dessert recipes. When I want to include pumpkin in savory recipes, I often roast it until it is tender, then fold it into risotto or pasta dishes. In this recipe, I used my favorite skillet to cook the sauce from start to finish in the time it took to bring the pasta water to boil, then cook the pasta.</span><div><div class=\"mv-create-ingredients\" style=\"box-sizing: inherit; font-family: \"Gentium Basic\", Georgia, \"Times New Roman\", Times, serif; font-size: 17px; vertical-align: baseline; margin: 0px 0px 20px; padding: 0px 20px; border: 0px; outline: 0px; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; clear: left; color: rgb(62, 58, 59);\"><h2 class=\"mv-create-ingredients-title mv-create-title-secondary\" style=\"box-sizing: inherit; font-family: uniformextracondensed, \"Helvetica Neue\", Arial, Helvetica, Geneva, sans-serif; font-size: 24px; font-weight: normal; font-style: inherit; vertical-align: baseline; margin: 20px 0px 10px; padding: 0px; border: none; outline: 0px; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; text-transform: uppercase; line-height: 2.2em; float: none; clear: none;\">INGREDIENTS</h2><ul style=\"box-sizing: inherit; font-family: inherit; font-size: 16px; font-weight: inherit; font-style: inherit; vertical-align: baseline; margin: 10px 0px 10px 30px; padding: 0px; border: 0px; outline: 0px; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; list-style: none;\"><div id=\"AdThrive_Recipe_1_desktop\" class=\"adthrive-ad adthrive-recipe adthrive-recipe-1 adthrive-ad-cls\" data-google-query-id=\"CO2Vx6PXnfQCFZ4-AQodmY4OPQ\" style=\"box-sizing: inherit; font-family: inherit; font-weight: inherit; font-style: inherit; vertical-align: baseline; margin: 10px; padding: 0px; border: 0px; outline: 0px; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; text-align: center; overflow-x: visible; clear: both; line-height: 0; display: flex; justify-content: center; align-items: center; flex-wrap: wrap; float: right; min-height: 250px;\"><div id=\"google_ads_iframe_/18190176,22569836831/AdThrive_Recipe_1/5613decd5ed284b838a60247_0__container__\" style=\"box-sizing: inherit; font-family: inherit; font-weight: inherit; font-style: inherit; vertical-align: baseline; margin: 0px; padding: 0px; border: 0pt none; outline: 0px; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; flex-basis: 100%;\"><iframe id=\"google_ads_iframe_/18190176,22569836831/AdThrive_Recipe_1/5613decd5ed284b838a60247_0\" title=\"3rd party ad content\" name=\"google_ads_iframe_/18190176,22569836831/AdThrive_Recipe_1/5613decd5ed284b838a60247_0\" width=\"300\" height=\"250\" scrolling=\"no\" marginwidth=\"0\" marginheight=\"0\" frameborder=\"0\" sandbox=\"allow-forms allow-popups allow-popups-to-escape-sandbox allow-same-origin allow-scripts allow-top-navigation-by-user-activation\" allow=\"attribution-reporting\" data-google-container-id=\"7\" data-load-complete=\"true\" style=\"box-sizing: inherit; font-family: inherit; font-weight: inherit; font-style: inherit; vertical-align: bottom; margin: 0px; padding: 0px; border-width: 0px; border-style: initial; outline: 0px; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;\"></iframe></div></div><li style=\"box-sizing: inherit; font-family: inherit; font-weight: inherit; font-style: inherit; vertical-align: baseline; margin: 0px 0px 3px 10px; padding: 0px; border: 0px; outline: 0px; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; text-align: left; line-height: 1.4em; list-style-type: none;\">1 Pound Pasta of Choice</li><li style=\"box-sizing: inherit; font-family: inherit; font-weight: inherit; font-style: inherit; vertical-align: baseline; margin: 0px 0px 3px 10px; padding: 0px; border: 0px; outline: 0px; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; text-align: left; line-height: 1.4em; list-style-type: none;\">2 Tablespoons Olive OIl</li><li style=\"box-sizing: inherit; font-family: inherit; font-weight: inherit; font-style: inherit; vertical-align: baseline; margin: 0px 0px 3px 10px; padding: 0px; border: 0px; outline: 0px; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; text-align: left; line-height: 1.4em; list-style-type: none;\">1 Pound Fresh Pumpkin, Cut Into 3/4-inch Cubes</li><li style=\"box-sizing: inherit; font-family: inherit; font-weight: inherit; font-style: inherit; vertical-align: baseline; margin: 0px 0px 3px 10px; padding: 0px; border: 0px; outline: 0px; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; text-align: left; line-height: 1.4em; list-style-type: none;\">3/4 Pound Italian Sausage, Removed From The Casing</li><li style=\"box-sizing: inherit; font-family: inherit; font-weight: inherit; font-style: inherit; vertical-align: baseline; margin: 0px 0px 3px 10px; padding: 0px; border: 0px; outline: 0px; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; text-align: left; line-height: 1.4em; list-style-type: none;\">1 Medium Onion, Peeled & Chopped</li><li style=\"box-sizing: inherit; font-family: inherit; font-weight: inherit; font-style: inherit; vertical-align: baseline; margin: 0px 0px 3px 10px; padding: 0px; border: 0px; outline: 0px; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; text-align: left; line-height: 1.4em; list-style-type: none;\">3 Garlic Cloves, Peeled & Minced</li><li style=\"box-sizing: inherit; font-family: inherit; font-weight: inherit; font-style: inherit; vertical-align: baseline; margin: 0px 0px 3px 10px; padding: 0px; border: 0px; outline: 0px; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; text-align: left; line-height: 1.4em; list-style-type: none;\">4 Sage Leaves, Finely Chopped</li><li style=\"box-sizing: inherit; font-family: inherit; font-weight: inherit; font-style: inherit; vertical-align: baseline; margin: 0px 0px 3px 10px; padding: 0px; border: 0px; outline: 0px; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; text-align: left; line-height: 1.4em; list-style-type: none;\">1 Cup Heavy Cream</li><li style=\"box-sizing: inherit; font-family: inherit; font-weight: inherit; font-style: inherit; vertical-align: baseline; margin: 0px 0px 3px 10px; padding: 0px; border: 0px; outline: 0px; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; text-align: left; line-height: 1.4em; list-style-type: none;\">1/3 Cup Grated Parmesan Cheese</li><li style=\"box-sizing: inherit; font-family: inherit; font-weight: inherit; font-style: inherit; vertical-align: baseline; margin: 0px 0px 3px 10px; padding: 0px; border: 0px; outline: 0px; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; text-align: left; line-height: 1.4em; list-style-type: none;\">Salt & Pepper to Taste</li></ul><h3 style=\"box-sizing: inherit; font-family: uniformextracondensed, \"Helvetica Neue\", Arial, Helvetica, Geneva, sans-serif; font-size: 18px; font-weight: normal; font-style: inherit; vertical-align: baseline; margin: 20px 0px 10px; padding: 0px; border: none; outline: 0px; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; text-transform: uppercase; line-height: 1.4em; clear: none;\">TO SERVE:</h3><ul style=\"box-sizing: inherit; font-family: inherit; font-size: 16px; font-weight: inherit; font-style: inherit; vertical-align: baseline; margin: 10px 0px 10px 30px; padding: 0px; border: 0px; outline: 0px; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; list-style: none;\"><li style=\"box-sizing: inherit; font-family: inherit; font-weight: inherit; font-style: inherit; vertical-align: baseline; margin: 0px 0px 3px 10px; padding: 0px; border: 0px; outline: 0px; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; text-align: left; line-height: 1.4em; list-style-type: none;\">Shaved Parmesan</li><li style=\"box-sizing: inherit; font-family: inherit; font-weight: inherit; font-style: inherit; vertical-align: baseline; margin: 0px 0px 3px 10px; padding: 0px; border: 0px; outline: 0px; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; text-align: left; line-height: 1.4em; list-style-type: none;\">Cracked Black Pepper</li></ul></div><div class=\"mv-create-instructions\" style=\"box-sizing: inherit; font-family: \"Gentium Basic\", Georgia, \"Times New Roman\", Times, serif; font-size: 17px; vertical-align: baseline; margin: 0px 0px 20px; padding: 0px 20px; border: 0px; outline: 0px; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; clear: left; color: rgb(62, 58, 59);\"><h2 class=\"mv-create-instructions-title mv-create-title-secondary\" style=\"box-sizing: inherit; font-family: uniformextracondensed, \"Helvetica Neue\", Arial, Helvetica, Geneva, sans-serif; font-size: 24px; font-weight: normal; font-style: inherit; vertical-align: baseline; margin: 20px 0px 10px; padding: 0px; border: none; outline: 0px; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; text-transform: uppercase; line-height: 2.2em; float: none; clear: none;\">INSTRUCTIONS</h2><ol style=\"box-sizing: inherit; font-family: inherit; font-size: 16px; font-weight: inherit; font-style: inherit; vertical-align: baseline; margin: 10px 0px 10px 30px; padding: 0px; border: 0px; outline: 0px; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; list-style: none;\"><li id=\"mv_create_911_1\" style=\"box-sizing: inherit; font-family: inherit; font-weight: inherit; font-style: inherit; vertical-align: baseline; margin: 0px 0px 0px 20px; padding: 0px 0px 10px; border: 0px; outline: 0px; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; text-align: left; line-height: 1.4em; list-style-type: decimal;\">Add the pasta to a large pot of boiling salted water over high heat and cook, stirring occasionally, until al dente.</li><li id=\"mv_create_911_2\" style=\"box-sizing: inherit; font-family: inherit; font-weight: inherit; font-style: inherit; vertical-align: baseline; margin: 0px 0px 0px 20px; padding: 0px 0px 10px; border: 0px; outline: 0px; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; text-align: left; line-height: 1.4em; list-style-type: decimal;\">Drain, reserving about 1/4 cup of the pasta water.</li><li id=\"mv_create_911_3\" style=\"box-sizing: inherit; font-family: inherit; font-weight: inherit; font-style: inherit; vertical-align: baseline; margin: 0px 0px 0px 20px; padding: 0px 0px 10px; border: 0px; outline: 0px; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; text-align: left; line-height: 1.4em; list-style-type: decimal;\">While the pasta is cooking, heat the oil in a large skillet over high heat, then add the pumpkin and sausages.</li><li id=\"mv_create_911_4\" style=\"box-sizing: inherit; font-family: inherit; font-weight: inherit; font-style: inherit; vertical-align: baseline; margin: 0px 0px 0px 20px; padding: 0px 0px 10px; border: 0px; outline: 0px; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; text-align: left; line-height: 1.4em; list-style-type: decimal;\">Cook, breaking up the sausage into bite-size pieces, for 8 mins, or until the sausage and pumpkin are beginning to brown.</li><li id=\"mv_create_911_5\" style=\"box-sizing: inherit; font-family: inherit; font-weight: inherit; font-style: inherit; vertical-align: baseline; margin: 0px 0px 0px 20px; padding: 0px 0px 10px; border: 0px; outline: 0px; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; text-align: left; line-height: 1.4em; list-style-type: decimal;\">Add the onion, garlic, and sage and cook, stirring often, until the onion softens, about 5 minutes.</li><div id=\"AdThrive_Recipe_2_desktop\" class=\"adthrive-ad adthrive-recipe adthrive-recipe-1 adthrive-ad-cls\" data-google-query-id=\"CPrT3I3SnfQCFc5IAQodvOsKEA\" style=\"box-sizing: inherit; font-family: inherit; font-weight: inherit; font-style: inherit; vertical-align: baseline; margin: 10px; padding: 0px; border: 0px; outline: 0px; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; text-align: center; overflow-x: visible; clear: both; line-height: 0; display: flex; justify-content: center; align-items: center; flex-wrap: wrap; float: right; min-height: 250px;\"><div id=\"google_ads_iframe_/18190176,22569836831/AdThrive_Recipe_2/5613decd5ed284b838a60247_0__container__\" style=\"box-sizing: inherit; font-family: inherit; font-weight: inherit; font-style: inherit; vertical-align: baseline; margin: 0px; padding: 0px; border: 0pt none; outline: 0px; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; flex-basis: 100%; display: inline-block; width: 300px; height: 250px;\"><iframe frameborder=\"0\" src=\"https://5f7db864b9065fa1d427287311a6c086.safeframe.googlesyndication.com/safeframe/1-0-38/html/container.html\" id=\"google_ads_iframe_/18190176,22569836831/AdThrive_Recipe_2/5613decd5ed284b838a60247_0\" title=\"3rd party ad content\" name=\"\" scrolling=\"no\" marginwidth=\"0\" marginheight=\"0\" width=\"300\" height=\"250\" data-is-safeframe=\"true\" sandbox=\"allow-forms allow-popups allow-popups-to-escape-sandbox allow-same-origin allow-scripts allow-top-navigation-by-user-activation\" allow=\"attribution-reporting\" data-google-container-id=\"8\" data-load-complete=\"true\" style=\"box-sizing: inherit; font-family: inherit; font-weight: inherit; font-style: inherit; vertical-align: bottom; margin: 0px; padding: 0px; border-width: 0px; border-style: initial; outline: 0px; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;\"></iframe></div></div><li id=\"mv_create_911_6\" style=\"box-sizing: inherit; font-family: inherit; font-weight: inherit; font-style: inherit; vertical-align: baseline; margin: 0px 0px 0px 20px; padding: 0px 0px 10px; border: 0px; outline: 0px; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; text-align: left; line-height: 1.4em; list-style-type: decimal;\">Stir in the cream and grated cheese, cook for about 3 minutes until thickened, then remove from the heat.</li><li id=\"mv_create_911_7\" style=\"box-sizing: inherit; font-family: inherit; font-weight: inherit; font-style: inherit; vertical-align: baseline; margin: 0px 0px 0px 20px; padding: 0px 0px 10px; border: 0px; outline: 0px; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; text-align: left; line-height: 1.4em; list-style-type: decimal;\">Season with salt and pepper, then add to the pasta along with enough pasta water to loosen as needed.</li><li id=\"mv_create_911_8\" style=\"box-sizing: inherit; font-family: inherit; font-weight: inherit; font-style: inherit; vertical-align: baseline; margin: 0px 0px 0px 20px; padding: 0px 0px 10px; border: 0px; outline: 0px; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; text-align: left; line-height: 1.4em; list-style-type: decimal;\">Cook briefly, stirring constantly over high heat until piping hot.</li><li id=\"mv_create_911_9\" style=\"box-sizing: inherit; font-family: inherit; font-weight: inherit; font-style: inherit; vertical-align: baseline; margin: 0px 0px 0px 20px; padding: 0px 0px 10px; border: 0px; outline: 0px; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; text-align: left; line-height: 1.4em; list-style-type: decimal;\">Serve in individual pasta bowls topped with shavings of Parmesan cheese and cracked black pepper.</li></ol></div></div>', 'XE2_XsOfj6A', '1970-01-01', '2021-11-10', 1, '', 2),
(2, 'kale-barley--shredded-brussels-sprouts-salad', 'KALE, BARLEY, & SHREDDED BRUSSELS SPROUTS SALAD', '', '<h1 class=\"title\" style=\"box-sizing: border-box; font-family: uniformextracondensed, \" helvetica=\"\" neue\",=\"\" arial,=\"\" helvetica,=\"\" geneva,=\"\" sans-serif;=\"\" font-size:=\"\" 38px;=\"\" font-weight:=\"\" normal;=\"\" vertical-align:=\"\" baseline;=\"\" margin:=\"\" 0px=\"\" 5px;=\"\" padding:=\"\" 0px;=\"\" border:=\"\" outline:=\"\" background:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-transform:=\"\" uppercase;=\"\" line-height:=\"\" 1.1;=\"\" color:=\"\" rgb(62,=\"\" 58,=\"\" 59);\"=\"\"><p style=\"box-sizing: border-box; margin: 0px 0px 30px; padding: 0px; font-family: muli, sans-serif; font-weight: 400; font-size: 20px; line-height: 28px; text-rendering: optimizelegibility; color: rgb(51, 51, 51);\"><span style=\"background-color: rgb(255, 255, 255);\">Santa Fe\'s culinary arts scene is sizzling so when you pack for your trip, </span><span style=\"background-color: rgb(102, 255, 255);\">make sure to bring your appetiteǃ<br style=\"box-sizing: border-box;\"></span><br style=\"box-sizing: border-box;\"><span style=\"background-color: rgb(255, 255, 255);\">Consider yourself a foodie? Santa Fe is a food lover\'s paradise. Ask the locals to name their favorite Santa Fe restaurants and you\'ll end up with a lengthy list of worthy choices. Innovative Southwestern fare created by award-winning chefs and hearty New Mexico dishes such as breakfast burritos and green chile stew are reason enough for Santa Fe to have earned a prominent place on the world\'s culinary map. If you\'re hungry for creative contemporary cuisine or Asian, French, Italian, Indian or Middle Eastern cuisine, you\'ll find them all here as well.</span><br style=\"box-sizing: border-box;\"><br style=\"box-sizing: border-box;\"><span style=\"background-color: rgb(255, 255, 255);\">New Mexicans love their chile and it isn\'t the kind with beans that cowboys eatǃ New Mexican chile is spicy, delicious and unlike anything you\'ve ever tasted. When you come to Santa Fe and your server asks if you want \"red or green?\" this refers to the kind of chile you\'d like served over enchiladas, chile rellenos or other staples of New Mexican fare. When in doubt, you can always answer \"Christmas\" and you\'ll get to try bothǃ</span></p><p style=\"box-sizing: border-box; margin: 0px 0px 30px; padding: 0px; font-family: muli, sans-serif; font-weight: 400; font-size: 20px; line-height: 28px; text-rendering: optimizelegibility; color: rgb(51, 51, 51); background-color: rgb(255, 255, 255);\">Discover the legendary and innovative flavors of Santa Fe</p></h1>', '', '2021-11-16', '2021-11-16', 1, '2.jpg', 1),
(3, 'what-kind-of-website-do-i-need', 'What kind of website do I need?', '', '<font face=\"Arial, Verdana\"><span style=\"font-size: 13.3333px;\">What kind of website do I need?</span></font>', '', '2021-11-17', '2021-11-17', 1, 'squashsausagepasta5 (1).jpg', 1),
(4, 'apple-cobbler-gf-option', 'Apple Cobbler (GF Option)', 'How to do this?', '<ul class=\"recipe-directions__steps\" style=\"box-sizing: border-box; margin: 0px; list-style-type: none; padding: 0px; font-family: Gotham, &quot;sans serif&quot;, sans-serif, &quot;Helvetica Neue&quot;, Helvetica, arial; font-size: 14px; background-color: rgb(255, 255, 255);\"><li class=\"recipe-directions__step\" style=\"box-sizing: border-box; padding-bottom: 28px;\">Remove the turkey from its package, making sure to remove the neck and giblets from the cavity. Place on large cutting board and pat completely dry with a few paper towels.</li><li class=\"recipe-directions__step\" style=\"box-sizing: border-box; padding-bottom: 28px;\">Flip the turkey over so the breast side is down. Using large poultry shears or a very sharp large knife, cut through the bones on either side of the spine.</li></ul>', '', '2021-11-18', '2021-11-16', 1, 'category-1.png', 1),
(5, 'new-blog-notice', 'New Blog Notice', '', '<font face=\"Arial, Verdana\"><span style=\"font-size: 13.3333px;\">New<br></span></font><div><font face=\"Arial, Verdana\"><span style=\"font-size: 13.3333px;\"><br></span></font></div><div><font face=\"Arial, Verdana\"><span style=\"font-size: 13.3333px;\"><br></span></font></div><div><font face=\"Arial, Verdana\"><span style=\"font-size: 13.3333px;\">New Blog Notice</span></font></div><div><font face=\"Arial, Verdana\"><span style=\"font-size: 13.3333px;\"><br></span></font></div><div><font face=\"Arial, Verdana\"><span style=\"font-size: 13.3333px;\">New Blog Notice&nbsp;</span></font></div><div><font face=\"Arial, Verdana\"><span style=\"font-size: 13.3333px; background-color: rgb(255, 255, 102);\">Blog Notice</span></font></div>', '', '2021-11-18', '2021-11-04', 1, 'category-4.png', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notice_sources`
--

CREATE TABLE `notice_sources` (
  `id` int(11) NOT NULL,
  `source_name` varchar(255) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `source_link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `notice_sources`
--

INSERT INTO `notice_sources` (`id`, `source_name`, `author`, `source_link`) VALUES
(1, 'Website', 'Owner', ''),
(2, 'https://www.italianfoodforever.com', 'Deborah Mele', 'https://www.italianfoodforever.com/2021/11/creamy-pumpkin-sausage-sage-pasta/');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pictures`
--

CREATE TABLE `pictures` (
  `id` int(11) NOT NULL,
  `picture` varchar(255) NOT NULL,
  `priority` int(11) NOT NULL,
  `target_module` varchar(255) NOT NULL,
  `target_module_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pictures`
--

INSERT INTO `pictures` (`id`, `picture`, `priority`, `target_module`, `target_module_id`) VALUES
(4, 'chicasol568k.jpg', 1, 'blog_notices', 1),
(5, 'br22Qnx.jpg', 4, 'blog_notices', 1),
(6, 'br7qLV.jpg', 2, 'blog_notices', 1),
(7, 'br3vxK3.jpg', 3, 'blog_notices', 1),
(9, 'd8a6N.JPG', 5, 'blog_notices', 1),
(17, 'product7Gvzj.png', 1, 'blog_notices', 4),
(18, 'product9aYLW.png', 1, 'blog_notices', 4),
(19, 'product8mrva.png', 1, 'blog_notices', 4),
(20, 'product19LUR.png', 1, 'blog_notices', 5),
(21, 'product2jQBj.png', 1, 'blog_notices', 5),
(22, 'banner1YVD2.jpg', 1, 'blog_notices', 3),
(23, 'banner2cddR.jpg', 1, 'blog_notices', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trongate_administrators`
--

CREATE TABLE `trongate_administrators` (
  `id` int(11) NOT NULL,
  `username` varchar(65) DEFAULT NULL,
  `password` varchar(60) DEFAULT NULL,
  `trongate_user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `trongate_administrators`
--

INSERT INTO `trongate_administrators` (`id`, `username`, `password`, `trongate_user_id`) VALUES
(1, 'admin', '$2y$11$SoHZDvbfLSRHAi3WiKIBiu.tAoi/GCBBO4HRxVX1I3qQkq3wCWfXi', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trongate_comments`
--

CREATE TABLE `trongate_comments` (
  `id` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `date_created` int(11) DEFAULT 0,
  `user_id` int(11) DEFAULT NULL,
  `target_table` varchar(125) DEFAULT NULL,
  `update_id` int(11) DEFAULT NULL,
  `code` varchar(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trongate_tokens`
--

CREATE TABLE `trongate_tokens` (
  `id` int(11) NOT NULL,
  `token` varchar(125) DEFAULT NULL,
  `user_id` int(11) DEFAULT 0,
  `expiry_date` int(11) DEFAULT NULL,
  `code` varchar(3) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `trongate_tokens`
--

INSERT INTO `trongate_tokens` (`id`, `token`, `user_id`, `expiry_date`, `code`) VALUES
(7, 'PHaIs5uV0Ja7E62x9WSW83TP4MZGBKvP', 1, 1637275415, '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trongate_users`
--

CREATE TABLE `trongate_users` (
  `id` int(11) NOT NULL,
  `code` varchar(32) DEFAULT NULL,
  `user_level_id` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `trongate_users`
--

INSERT INTO `trongate_users` (`id`, `code`, `user_level_id`) VALUES
(1, 'UYW3LFRrMSbQLTYyD7tELmTgFrgwkhmw', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trongate_user_levels`
--

CREATE TABLE `trongate_user_levels` (
  `id` int(11) NOT NULL,
  `level_title` varchar(125) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `trongate_user_levels`
--

INSERT INTO `trongate_user_levels` (`id`, `level_title`) VALUES
(1, 'admin');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `associated_blog_notices_and_blog_categories`
--
ALTER TABLE `associated_blog_notices_and_blog_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `blog_categories`
--
ALTER TABLE `blog_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `blog_notices`
--
ALTER TABLE `blog_notices`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `notice_sources`
--
ALTER TABLE `notice_sources`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pictures`
--
ALTER TABLE `pictures`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `trongate_administrators`
--
ALTER TABLE `trongate_administrators`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `trongate_comments`
--
ALTER TABLE `trongate_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `trongate_tokens`
--
ALTER TABLE `trongate_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `trongate_users`
--
ALTER TABLE `trongate_users`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `trongate_user_levels`
--
ALTER TABLE `trongate_user_levels`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `associated_blog_notices_and_blog_categories`
--
ALTER TABLE `associated_blog_notices_and_blog_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `blog_categories`
--
ALTER TABLE `blog_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `blog_notices`
--
ALTER TABLE `blog_notices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `notice_sources`
--
ALTER TABLE `notice_sources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `pictures`
--
ALTER TABLE `pictures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `trongate_administrators`
--
ALTER TABLE `trongate_administrators`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `trongate_comments`
--
ALTER TABLE `trongate_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `trongate_tokens`
--
ALTER TABLE `trongate_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `trongate_users`
--
ALTER TABLE `trongate_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `trongate_user_levels`
--
ALTER TABLE `trongate_user_levels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
