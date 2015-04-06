-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mar 31 Mars 2015 à 10:15
-- Version du serveur: 5.5.40
-- Version de PHP: 5.4.35-0+deb7u2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `c0symfony`
--

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

CREATE TABLE IF NOT EXISTS `article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `description` text NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_at` datetime NOT NULL,
  `validated_at` datetime DEFAULT NULL,
  `User_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_article_User_idx` (`User_id`),
  KEY `fk_article_article_category1_idx` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=37 ;

--
-- Contenu de la table `article`
--

INSERT INTO `article` (`id`, `name`, `brand`, `category_id`, `description`, `created_at`, `modified_at`, `validated_at`, `User_id`) VALUES
(27, 'Ordinateur XEZ234', 'Hp', 3, 'Simple description de l''ordinateur', '2015-01-01 00:00:00', '2015-01-01 00:00:00', NULL, 14),
(28, 'iMac', 'Apple', 3, 'Simple description', '2015-01-01 00:00:00', '2015-01-01 00:00:00', NULL, 14),
(29, 'Nouveau Macbook', 'Apple', 3, 'Simple description', '2015-01-01 00:00:00', '2015-01-01 00:00:00', NULL, 14),
(31, 'Iphone 6 Plus', 'Apple', 6, 'Simple description', '2015-01-01 00:00:00', '2015-01-01 00:00:00', NULL, 14),
(32, 'Iphone 6', 'Apple', 6, 'Simple description', '2015-01-01 00:00:00', '2015-01-01 00:00:00', NULL, 14),
(33, 'Galaxy S6', 'Samsung', 6, 'Simple description', '2015-01-01 00:00:00', '2015-01-01 00:00:00', NULL, 14);

-- --------------------------------------------------------

--
-- Structure de la table `article_category`
--

CREATE TABLE IF NOT EXISTS `article_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `url` varchar(256) NOT NULL,
  `order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `article_category`
--

INSERT INTO `article_category` (`id`, `name`, `url`, `order`) VALUES
(1, 'Audio', 'audio', 3),
(2, 'Vidéo', 'video', 2),
(3, 'Informatique', 'informatique', 1),
(4, 'Electroménager', 'electromenager', 4),
(5, 'Auto', 'auto', 5),
(6, 'Téléphone', 'telephone', 1);

-- --------------------------------------------------------

--
-- Structure de la table `article_has_Image`
--

CREATE TABLE IF NOT EXISTS `article_has_Image` (
  `article_id` int(11) NOT NULL,
  `Image_id` int(11) NOT NULL,
  PRIMARY KEY (`article_id`,`Image_id`),
  KEY `fk_article_has_Image_Image1_idx` (`Image_id`),
  KEY `fk_article_has_Image_article1_idx` (`article_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `article_has_Image`
--

INSERT INTO `article_has_Image` (`article_id`, `Image_id`) VALUES
(27, 1),
(28, 2),
(29, 3),
(31, 5),
(32, 6),
(33, 7);

-- --------------------------------------------------------

--
-- Structure de la table `article_model`
--

CREATE TABLE IF NOT EXISTS `article_model` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `price` double NOT NULL DEFAULT '0',
  `article_id` int(11) NOT NULL,
  `User_id` int(11) NOT NULL,
  `status_id` int(11) DEFAULT NULL,
  `weight` double NOT NULL DEFAULT '0',
  `width` double NOT NULL DEFAULT '0',
  `height` double NOT NULL DEFAULT '0',
  `depth` double NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_article_model_article1_idx` (`article_id`),
  KEY `fk_article_model_User1_idx` (`User_id`),
  KEY `fk_article_model_article_model_status1_idx` (`status_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Contenu de la table `article_model`
--

INSERT INTO `article_model` (`id`, `price`, `article_id`, `User_id`, `status_id`, `weight`, `width`, `height`, `depth`, `created_at`) VALUES
(1, 100, 27, 14, 1, 2, 100, 100, 50, '2015-01-01 00:00:00'),
(2, 1499, 28, 14, 1, 4, 200, 200, 200, '2015-01-01 00:00:00'),
(3, 1399, 29, 14, 1, 1.5, 50, 50, 10, '2015-01-01 00:00:00'),
(5, 799, 31, 14, 1, 1, 20, 25, 10, '2015-01-01 00:00:00'),
(6, 699, 32, 14, 1, 1, 20, 25, 10, '2015-01-01 00:00:00'),
(7, 699, 33, 14, 1, 1, 20, 25, 10, '2015-01-01 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `article_model_has_Image`
--

CREATE TABLE IF NOT EXISTS `article_model_has_Image` (
  `model_id` int(11) NOT NULL,
  `Image_id` int(11) NOT NULL,
  PRIMARY KEY (`model_id`,`Image_id`),
  KEY `fk_article_model_has_Image_Image1_idx` (`Image_id`),
  KEY `fk_article_model_has_Image_article_model1_idx` (`model_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `article_model_has_Image`
--

INSERT INTO `article_model_has_Image` (`model_id`, `Image_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `article_model_status`
--

CREATE TABLE IF NOT EXISTS `article_model_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `article_model_status`
--

INSERT INTO `article_model_status` (`id`, `name`) VALUES
(1, 'Neuf'),
(2, 'Comme neuf'),
(3, 'Bon'),
(4, 'Légèrement abimé'),
(5, 'Très abimé');

-- --------------------------------------------------------

--
-- Structure de la table `cart`
--

CREATE TABLE IF NOT EXISTS `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `User_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `is_visible` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_cart_User1_idx` (`User_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `cart`
--

INSERT INTO `cart` (`id`, `User_id`, `created_at`, `modified_at`, `name`, `is_visible`) VALUES
(1, 14, '2015-03-31 10:01:24', '2015-03-31 10:10:56', 'Mon premier panier', 1);

-- --------------------------------------------------------

--
-- Structure de la table `cart_has_article_model`
--

CREATE TABLE IF NOT EXISTS `cart_has_article_model` (
  `cart_id` int(11) NOT NULL,
  `model_id` int(11) NOT NULL,
  PRIMARY KEY (`cart_id`,`model_id`),
  KEY `fk_cart_has_article_model_article_model1_idx` (`model_id`),
  KEY `fk_cart_has_article_model_cart1_idx` (`cart_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `cart_has_article_model`
--

INSERT INTO `cart_has_article_model` (`cart_id`, `model_id`) VALUES
(1, 1),
(1, 2);

-- --------------------------------------------------------

--
-- Structure de la table `contact`
--

CREATE TABLE IF NOT EXISTS `contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` text NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime NOT NULL,
  `is_read` int(11) NOT NULL DEFAULT '0',
  `User_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_contact_User1_idx` (`User_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `country`
--

CREATE TABLE IF NOT EXISTS `country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(45) NOT NULL,
  `name` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=243 ;

--
-- Contenu de la table `country`
--

INSERT INTO `country` (`id`, `code`, `name`) VALUES
(1, 'US', 'United States'),
(2, 'CA', 'Canada'),
(3, 'AF', 'Afghanistan'),
(4, 'AL', 'Albania'),
(5, 'DZ', 'Algeria'),
(6, 'DS', 'American Samoa'),
(7, 'AD', 'Andorra'),
(8, 'AO', 'Angola'),
(9, 'AI', 'Anguilla'),
(10, 'AQ', 'Antarctica'),
(11, 'AG', 'Antigua and/or Barbuda'),
(12, 'AR', 'Argentina'),
(13, 'AM', 'Armenia'),
(14, 'AW', 'Aruba'),
(15, 'AU', 'Australia'),
(16, 'AT', 'Austria'),
(17, 'AZ', 'Azerbaijan'),
(18, 'BS', 'Bahamas'),
(19, 'BH', 'Bahrain'),
(20, 'BD', 'Bangladesh'),
(21, 'BB', 'Barbados'),
(22, 'BY', 'Belarus'),
(23, 'BE', 'Belgium'),
(24, 'BZ', 'Belize'),
(25, 'BJ', 'Benin'),
(26, 'BM', 'Bermuda'),
(27, 'BT', 'Bhutan'),
(28, 'BO', 'Bolivia'),
(29, 'BA', 'Bosnia and Herzegovina'),
(30, 'BW', 'Botswana'),
(31, 'BV', 'Bouvet Island'),
(32, 'BR', 'Brazil'),
(33, 'IO', 'British lndian Ocean Territory'),
(34, 'BN', 'Brunei Darussalam'),
(35, 'BG', 'Bulgaria'),
(36, 'BF', 'Burkina Faso'),
(37, 'BI', 'Burundi'),
(38, 'KH', 'Cambodia'),
(39, 'CM', 'Cameroon'),
(40, 'CV', 'Cape Verde'),
(41, 'KY', 'Cayman Islands'),
(42, 'CF', 'Central African Republic'),
(43, 'TD', 'Chad'),
(44, 'CL', 'Chile'),
(45, 'CN', 'China'),
(46, 'CX', 'Christmas Island'),
(47, 'CC', 'Cocos (Keeling) Islands'),
(48, 'CO', 'Colombia'),
(49, 'KM', 'Comoros'),
(50, 'CG', 'Congo'),
(51, 'CK', 'Cook Islands'),
(52, 'CR', 'Costa Rica'),
(53, 'HR', 'Croatia (Hrvatska)'),
(54, 'CU', 'Cuba'),
(55, 'CY', 'Cyprus'),
(56, 'CZ', 'Czech Republic'),
(57, 'DK', 'Denmark'),
(58, 'DJ', 'Djibouti'),
(59, 'DM', 'Dominica'),
(60, 'DO', 'Dominican Republic'),
(61, 'TP', 'East Timor'),
(62, 'EC', 'Ecuador'),
(63, 'EG', 'Egypt'),
(64, 'SV', 'El Salvador'),
(65, 'GQ', 'Equatorial Guinea'),
(66, 'ER', 'Eritrea'),
(67, 'EE', 'Estonia'),
(68, 'ET', 'Ethiopia'),
(69, 'FK', 'Falkland Islands (Malvinas)'),
(70, 'FO', 'Faroe Islands'),
(71, 'FJ', 'Fiji'),
(72, 'FI', 'Finland'),
(73, 'FR', 'France'),
(74, 'FX', 'France, Metropolitan'),
(75, 'GF', 'French Guiana'),
(76, 'PF', 'French Polynesia'),
(77, 'TF', 'French Southern Territories'),
(78, 'GA', 'Gabon'),
(79, 'GM', 'Gambia'),
(80, 'GE', 'Georgia'),
(81, 'DE', 'Germany'),
(82, 'GH', 'Ghana'),
(83, 'GI', 'Gibraltar'),
(84, 'GR', 'Greece'),
(85, 'GL', 'Greenland'),
(86, 'GD', 'Grenada'),
(87, 'GP', 'Guadeloupe'),
(88, 'GU', 'Guam'),
(89, 'GT', 'Guatemala'),
(90, 'GN', 'Guinea'),
(91, 'GW', 'Guinea-Bissau'),
(92, 'GY', 'Guyana'),
(93, 'HT', 'Haiti'),
(94, 'HM', 'Heard and Mc Donald Islands'),
(95, 'HN', 'Honduras'),
(96, 'HK', 'Hong Kong'),
(97, 'HU', 'Hungary'),
(98, 'IS', 'Iceland'),
(99, 'IN', 'India'),
(100, 'ID', 'Indonesia'),
(101, 'IR', 'Iran (Islamic Republic of)'),
(102, 'IQ', 'Iraq'),
(103, 'IE', 'Ireland'),
(104, 'IL', 'Israel'),
(105, 'IT', 'Italy'),
(106, 'CI', 'Ivory Coast'),
(107, 'JM', 'Jamaica'),
(108, 'JP', 'Japan'),
(109, 'JO', 'Jordan'),
(110, 'KZ', 'Kazakhstan'),
(111, 'KE', 'Kenya'),
(112, 'KI', 'Kiribati'),
(113, 'KP', 'Korea, Democratic People''s Republic of'),
(114, 'KR', 'Korea, Republic of'),
(115, 'XK', 'Kosovo'),
(116, 'KW', 'Kuwait'),
(117, 'KG', 'Kyrgyzstan'),
(118, 'LA', 'Lao People''s Democratic Republic'),
(119, 'LV', 'Latvia'),
(120, 'LB', 'Lebanon'),
(121, 'LS', 'Lesotho'),
(122, 'LR', 'Liberia'),
(123, 'LY', 'Libyan Arab Jamahiriya'),
(124, 'LI', 'Liechtenstein'),
(125, 'LT', 'Lithuania'),
(126, 'LU', 'Luxembourg'),
(127, 'MO', 'Macau'),
(128, 'MK', 'Macedonia'),
(129, 'MG', 'Madagascar'),
(130, 'MW', 'Malawi'),
(131, 'MY', 'Malaysia'),
(132, 'MV', 'Maldives'),
(133, 'ML', 'Mali'),
(134, 'MT', 'Malta'),
(135, 'MH', 'Marshall Islands'),
(136, 'MQ', 'Martinique'),
(137, 'MR', 'Mauritania'),
(138, 'MU', 'Mauritius'),
(139, 'TY', 'Mayotte'),
(140, 'MX', 'Mexico'),
(141, 'FM', 'Micronesia, Federated States of'),
(142, 'MD', 'Moldova, Republic of'),
(143, 'MC', 'Monaco'),
(144, 'MN', 'Mongolia'),
(145, 'ME', 'Montenegro'),
(146, 'MS', 'Montserrat'),
(147, 'MA', 'Morocco'),
(148, 'MZ', 'Mozambique'),
(149, 'MM', 'Myanmar'),
(150, 'NA', 'Namibia'),
(151, 'NR', 'Nauru'),
(152, 'NP', 'Nepal'),
(153, 'NL', 'Netherlands'),
(154, 'AN', 'Netherlands Antilles'),
(155, 'NC', 'New Caledonia'),
(156, 'NZ', 'New Zealand'),
(157, 'NI', 'Nicaragua'),
(158, 'NE', 'Niger'),
(159, 'NG', 'Nigeria'),
(160, 'NU', 'Niue'),
(161, 'NF', 'Norfork Island'),
(162, 'MP', 'Northern Mariana Islands'),
(163, 'NO', 'Norway'),
(164, 'OM', 'Oman'),
(165, 'PK', 'Pakistan'),
(166, 'PW', 'Palau'),
(167, 'PA', 'Panama'),
(168, 'PG', 'Papua New Guinea'),
(169, 'PY', 'Paraguay'),
(170, 'PE', 'Peru'),
(171, 'PH', 'Philippines'),
(172, 'PN', 'Pitcairn'),
(173, 'PL', 'Poland'),
(174, 'PT', 'Portugal'),
(175, 'PR', 'Puerto Rico'),
(176, 'QA', 'Qatar'),
(177, 'RE', 'Reunion'),
(178, 'RO', 'Romania'),
(179, 'RU', 'Russian Federation'),
(180, 'RW', 'Rwanda'),
(181, 'KN', 'Saint Kitts and Nevis'),
(182, 'LC', 'Saint Lucia'),
(183, 'VC', 'Saint Vincent and the Grenadines'),
(184, 'WS', 'Samoa'),
(185, 'SM', 'San Marino'),
(186, 'ST', 'Sao Tome and Principe'),
(187, 'SA', 'Saudi Arabia'),
(188, 'SN', 'Senegal'),
(189, 'RS', 'Serbia'),
(190, 'SC', 'Seychelles'),
(191, 'SL', 'Sierra Leone'),
(192, 'SG', 'Singapore'),
(193, 'SK', 'Slovakia'),
(194, 'SI', 'Slovenia'),
(195, 'SB', 'Solomon Islands'),
(196, 'SO', 'Somalia'),
(197, 'ZA', 'South Africa'),
(198, 'GS', 'South Georgia South Sandwich Islands'),
(199, 'ES', 'Spain'),
(200, 'LK', 'Sri Lanka'),
(201, 'SH', 'St. Helena'),
(202, 'PM', 'St. Pierre and Miquelon'),
(203, 'SD', 'Sudan'),
(204, 'SR', 'Suriname'),
(205, 'SJ', 'Svalbarn and Jan Mayen Islands'),
(206, 'SZ', 'Swaziland'),
(207, 'SE', 'Sweden'),
(208, 'CH', 'Switzerland'),
(209, 'SY', 'Syrian Arab Republic'),
(210, 'TW', 'Taiwan'),
(211, 'TJ', 'Tajikistan'),
(212, 'TZ', 'Tanzania, United Republic of'),
(213, 'TH', 'Thailand'),
(214, 'TG', 'Togo'),
(215, 'TK', 'Tokelau'),
(216, 'TO', 'Tonga'),
(217, 'TT', 'Trinidad and Tobago'),
(218, 'TN', 'Tunisia'),
(219, 'TR', 'Turkey'),
(220, 'TM', 'Turkmenistan'),
(221, 'TC', 'Turks and Caicos Islands'),
(222, 'TV', 'Tuvalu'),
(223, 'UG', 'Uganda'),
(224, 'UA', 'Ukraine'),
(225, 'AE', 'United Arab Emirates'),
(226, 'GB', 'United Kingdom'),
(227, 'UM', 'United States minor outlying islands'),
(228, 'UY', 'Uruguay'),
(229, 'UZ', 'Uzbekistan'),
(230, 'VU', 'Vanuatu'),
(231, 'VA', 'Vatican City State'),
(232, 'VE', 'Venezuela'),
(233, 'VN', 'Vietnam'),
(234, 'VG', 'Virgin Islands (British)'),
(235, 'VI', 'Virgin Islands (U.S.)'),
(236, 'WF', 'Wallis and Futuna Islands'),
(237, 'EH', 'Western Sahara'),
(238, 'YE', 'Yemen'),
(239, 'YU', 'Yugoslavia'),
(240, 'ZR', 'Zaire'),
(241, 'ZM', 'Zambia'),
(242, 'ZW', 'Zimbabwe');

-- --------------------------------------------------------

--
-- Structure de la table `Image`
--

CREATE TABLE IF NOT EXISTS `Image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) DEFAULT NULL,
  `url` text NOT NULL,
  `file` text NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Contenu de la table `Image`
--

INSERT INTO `Image` (`id`, `name`, `url`, `file`, `created_at`) VALUES
(1, 'hp-computer', 'http://images.amazon.com/images/G/01/electronics/detail-page/B00318CGIC-2.jpg', 'http://images.amazon.com/images/G/01/electronics/detail-page/B00318CGIC-2.jpg', '2015-01-01 00:00:00'),
(2, 'imac-computer', 'http://www.logosystems.co.uk/wp-content/uploads/2013/11/iMac-27%22.jpg', 'http://www.logosystems.co.uk/wp-content/uploads/2013/11/iMac-27%22.jpg', '2015-01-01 00:00:00'),
(3, 'macbook-laptop', 'http://consomac.fr/images/news/mba-15-hajek.jpg', 'http://consomac.fr/images/news/mba-15-hajek.jpg', '2015-01-01 00:00:00'),
(4, 'chromebook', 'http://cdn.bgr.com/2012/10/samsung-chromebook-12.jpeg', 'http://cdn.bgr.com/2012/10/samsung-chromebook-12.jpeg', '2015-01-01 00:00:00'),
(5, 'Iphone 6 plus', 'http://img-teknosa.cubecdn.net/TeknosaImg/productImages/1024x783/125071340-2-iphone_6_plus_16gb_gold_akilli_telefon.jpg', 'http://img-teknosa.cubecdn.net/TeknosaImg/productImages/1024x783/125071340-2-iphone_6_plus_16gb_gold_akilli_telefon.jpg', '2015-01-01 00:00:00'),
(6, 'Iphone 6', 'http://cnet1.cbsistatic.com/hub/i/r/2014/09/15/98b70517-3a22-4e39-b48b-9bb396a23407/thumbnail/770x433/b75c788b1c8f96233658bac84d2d10d9/plutus91503.jpg', 'http://cnet1.cbsistatic.com/hub/i/r/2014/09/15/98b70517-3a22-4e39-b48b-9bb396a23407/thumbnail/770x433/b75c788b1c8f96233658bac84d2d10d9/plutus91503.jpg', '2015-01-01 00:00:00'),
(7, 'Galaxy S6', 'http://droidsoft.fr/wordpress/wp-content/uploads/2015/03/galaxy-S6.jpg', 'http://droidsoft.fr/wordpress/wp-content/uploads/2015/03/galaxy-S6.jpg', '2015-01-01 00:00:00'),
(13, 'Desert.jpg', '/uploads/images//0c3ce789dd2712ecefb3b7299cdbfc2187d07f90f10c40ed8075f45c042fe6f6d5d42e952e05b7793f18ff8bdfb406bd.jpeg', '/var/www/clients/client0/web7/web/src/Odysseus/AdminBundle/Entity/../../../../web/uploads/images//55e39c0c41427b8a8aa043a7d7ae01e787d07f90f10c40ed8075f45c042fe6f6d5d42e952e05b7793f18ff8bdfb406bd.jpeg', '2015-03-30 14:08:00');

-- --------------------------------------------------------

--
-- Structure de la table `order`
--

CREATE TABLE IF NOT EXISTS `order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `User_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `validated_at` datetime DEFAULT NULL,
  `payment_method_id` int(11) DEFAULT NULL,
  `builling_id` int(11) NOT NULL,
  `shipping_id` int(11) NOT NULL,
  `shipping_method_id` int(11) DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_order_User1_idx` (`User_id`),
  KEY `fk_order_payment_method1_idx` (`payment_method_id`),
  KEY `fk_order_order_details1_idx` (`builling_id`),
  KEY `fk_order_order_details2_idx` (`shipping_id`),
  KEY `fk_order_shipping_method1_idx` (`shipping_method_id`),
  KEY `fk_order_order_status1_idx` (`status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `order_article`
--

CREATE TABLE IF NOT EXISTS `order_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `status_id` int(11) DEFAULT NULL,
  `model_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_order_article_order1_idx` (`order_id`),
  KEY `fk_order_article_order_article_status1_idx` (`status_id`),
  KEY `fk_order_article_article_model1_idx` (`model_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `order_article_status`
--

CREATE TABLE IF NOT EXISTS `order_article_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `order_details`
--

CREATE TABLE IF NOT EXISTS `order_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(256) NOT NULL,
  `last_name` varchar(256) NOT NULL,
  `address1` varchar(256) NOT NULL,
  `address2` varchar(256) DEFAULT NULL,
  `zip_code` varchar(45) NOT NULL,
  `city` varchar(45) NOT NULL,
  `telephone` varchar(45) NOT NULL,
  `country_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_order_details_country1_idx` (`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `order_status`
--

CREATE TABLE IF NOT EXISTS `order_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `page`
--

CREATE TABLE IF NOT EXISTS `page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(45) NOT NULL,
  `url` text NOT NULL,
  `content` text,
  `created_at` datetime NOT NULL,
  `modified_at` datetime NOT NULL,
  `status_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `template` varchar(256) DEFAULT 'OdysseusFrontBundle:Page:index.html.twig',
  PRIMARY KEY (`id`),
  KEY `fk_page_page_status1_idx` (`status_id`),
  KEY `fk_page_page1_idx` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Contenu de la table `page`
--

INSERT INTO `page` (`id`, `title`, `url`, `content`, `created_at`, `modified_at`, `status_id`, `parent_id`, `template`) VALUES
(2, 'Mentions légales', '/mentions-legales', 'Mentions légales', '2015-03-14 00:00:00', '2015-03-14 00:00:00', 1, NULL, 'OdysseusFrontBundle:Page:index.html;twig'),
(3, 'test', 'test', 'test', '2015-03-14 00:00:00', '2015-03-14 00:00:00', 1, 2, 'OdysseusFrontBundle:Page:index.html.twig'),
(4, 'zerzerzerzer', 'erzerzerzer', 'zfzerzerz', '2015-03-14 00:00:00', '2015-03-14 00:00:00', 2, 2, 'OdysseusFrontBundle:Page:index.html.twig'),
(5, 'zerzerzerzerzerzer', 'zerzerze', 'rzerzerzerzerzer', '2015-03-14 00:00:00', '2015-03-14 00:00:00', 2, 3, 'OdysseusFrontBundle:Page:index.html.twig'),
(6, 'bcvbfgbfndethsbfdbdfgdfg', 'zerdbfvcbcvb', 'cvbcvbcvbcvbcv', '2015-03-14 00:00:00', '2015-03-14 00:00:00', 2, 5, 'OdysseusFrontBundle:Page:index.html.twig'),
(7, 'sgdsfbdfj,ypouiokfj', 'dsdfvdfbfnjy,u;yfd', 'bvbt,yijtyhfgbfgh', '2015-03-14 00:00:00', '2015-03-14 00:00:00', 2, 5, 'OdysseusFrontBundle:Page:index.html.twig');

-- --------------------------------------------------------

--
-- Structure de la table `page_meta`
--

CREATE TABLE IF NOT EXISTS `page_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `value` text NOT NULL,
  `page_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_page_meta_page1_idx` (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `page_status`
--

CREATE TABLE IF NOT EXISTS `page_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `page_status`
--

INSERT INTO `page_status` (`id`, `name`) VALUES
(1, 'Brouillon'),
(2, 'Publié'),
(3, 'Supprimé');

-- --------------------------------------------------------

--
-- Structure de la table `payment_method`
--

CREATE TABLE IF NOT EXISTS `payment_method` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `seller_infos`
--

CREATE TABLE IF NOT EXISTS `seller_infos` (
  `id` int(11) NOT NULL,
  `first_name` varchar(256) NOT NULL,
  `last_name` varchar(256) NOT NULL,
  `iban` text NOT NULL,
  `bic` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `setting`
--

CREATE TABLE IF NOT EXISTS `setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `value` text,
  `created_at` datetime NOT NULL,
  `modified_at` datetime NOT NULL,
  `User_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_setting_User1_idx` (`User_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `shipping_method`
--

CREATE TABLE IF NOT EXISTS `shipping_method` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `price_per_weight` double NOT NULL DEFAULT '0',
  `price_per_m3` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `User`
--

CREATE TABLE IF NOT EXISTS `User` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `locked` tinyint(1) NOT NULL,
  `expired` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `confirmation_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `credentials_expired` tinyint(1) NOT NULL,
  `credentials_expire_at` datetime DEFAULT NULL,
  `seller_infos_id` int(11) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `receive_special_offers` tinyint(1) NOT NULL DEFAULT '0',
  `receive_partners_special_offers` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_2DA1797792FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_2DA17977A0D96FBF` (`email_canonical`),
  KEY `fk_User_seller_infos1_idx` (`seller_infos_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=17 ;

--
-- Contenu de la table `User`
--

INSERT INTO `User` (`id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `locked`, `expired`, `expires_at`, `confirmation_token`, `password_requested_at`, `roles`, `credentials_expired`, `credentials_expire_at`, `seller_infos_id`, `birthdate`, `receive_special_offers`, `receive_partners_special_offers`, `created_at`) VALUES
(14, '{{admuser}}', '{{admuser}}', '{{admmail}}', '{{admmail}}', 1, 'sudi2cadn2o84css8g04gg4so440sks', 'i8js5oOfrSpSeT56nrVipwDqt+goIYoC0zBH5mmmh+CiC9tzKmnYKhZlrqlIrbv7of1h6TbrKc5O7tg+wlA0iQ==', '2015-03-30 10:58:05', 0, 0, NULL, NULL, NULL, 'a:2:{i:0;s:16:"ROLE_SUPER_ADMIN";i:1;s:10:"ROLE_ADMIN";}', 0, NULL, NULL, '1991-10-25', 0, 0, '2015-01-01 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `user_infos`
--

CREATE TABLE IF NOT EXISTS `user_infos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `User_id` int(11) NOT NULL,
  `civility` varchar(45) NOT NULL,
  `first_name` varchar(256) NOT NULL,
  `last_name` varchar(256) NOT NULL,
  `company` varchar(100) DEFAULT NULL,
  `address1` varchar(256) NOT NULL,
  `address2` varchar(256) DEFAULT NULL,
  `zip_code` varchar(45) NOT NULL,
  `city` varchar(45) NOT NULL,
  `telephone` varchar(45) DEFAULT NULL,
  `mobile_phone` varchar(45) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_at` datetime NOT NULL,
  `is_default` int(11) NOT NULL DEFAULT '0',
  `is_builling` int(11) NOT NULL DEFAULT '0',
  `country_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user_infos_User1_idx` (`User_id`),
  KEY `fk_user_infos_country1_idx` (`country_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Contenu de la table `user_infos`
--

INSERT INTO `user_infos` (`id`, `User_id`, `civility`, `first_name`, `last_name`, `company`, `address1`, `address2`, `zip_code`, `city`, `telephone`, `mobile_phone`, `created_at`, `modified_at`, `is_default`, `is_builling`, `country_id`) VALUES
(16, 14, 'M', 'Wendy', 'JEAN-LOUIS', NULL, '53 Rue Moliere', NULL, '94200', 'IVRY-SUR-SEINE', '+33626071310', '+33626071310', '2015-03-28 19:22:51', '2015-03-28 19:22:51', 1, 0, 73),
(17, 14, 'M', 'Wendy', 'JEAN-LOUIS', NULL, '53 Rue Moliere', NULL, '94200', 'IVRY-SUR-SEINE', '+33626071310', '+33626071310', '2015-03-28 19:22:51', '2015-03-28 19:22:51', 0, 1, 73),
(18, 15, 'M', 'yoann', 'Pierre', 'HEre', '3 allée des charmes', 'paat3', '92500', 'Prout', '0147510451', '0147510451', '2015-03-29 17:52:20', '2015-03-29 17:52:20', 1, 0, 73),
(19, 15, 'M', 'yoann', 'Pierre', 'HEre', '3 allée des charmes', 'paat3', '92500', 'Prout', '0147510451', '0147510451', '2015-03-29 17:52:20', '2015-03-29 17:52:20', 0, 1, 73);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `fk_article_article_category1` FOREIGN KEY (`category_id`) REFERENCES `article_category` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_article_User` FOREIGN KEY (`User_id`) REFERENCES `User` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `article_has_Image`
--
ALTER TABLE `article_has_Image`
  ADD CONSTRAINT `fk_article_has_Image_article1` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_article_has_Image_Image1` FOREIGN KEY (`Image_id`) REFERENCES `Image` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `article_model`
--
ALTER TABLE `article_model`
  ADD CONSTRAINT `fk_article_model_article1` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_article_model_article_model_status1` FOREIGN KEY (`status_id`) REFERENCES `article_model_status` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_article_model_User1` FOREIGN KEY (`User_id`) REFERENCES `User` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `article_model_has_Image`
--
ALTER TABLE `article_model_has_Image`
  ADD CONSTRAINT `fk_article_model_has_Image_article_model1` FOREIGN KEY (`model_id`) REFERENCES `article_model` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_article_model_has_Image_Image1` FOREIGN KEY (`Image_id`) REFERENCES `Image` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `fk_cart_User1` FOREIGN KEY (`User_id`) REFERENCES `User` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `cart_has_article_model`
--
ALTER TABLE `cart_has_article_model`
  ADD CONSTRAINT `fk_cart_has_article_model_article_model1` FOREIGN KEY (`model_id`) REFERENCES `article_model` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cart_has_article_model_cart1` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `contact`
--
ALTER TABLE `contact`
  ADD CONSTRAINT `fk_contact_User1` FOREIGN KEY (`User_id`) REFERENCES `User` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `fk_order_User1` FOREIGN KEY (`User_id`) REFERENCES `User` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_order_payment_method1` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_method` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_order_order_details1` FOREIGN KEY (`builling_id`) REFERENCES `order_details` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_order_order_details2` FOREIGN KEY (`shipping_id`) REFERENCES `order_details` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_order_shipping_method1` FOREIGN KEY (`shipping_method_id`) REFERENCES `shipping_method` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_order_order_status1` FOREIGN KEY (`status_id`) REFERENCES `order_status` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `order_article`
--
ALTER TABLE `order_article`
  ADD CONSTRAINT `fk_order_article_article_model1` FOREIGN KEY (`model_id`) REFERENCES `article_model` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_order_article_order1` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_order_article_order_article_status1` FOREIGN KEY (`status_id`) REFERENCES `order_article_status` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `fk_order_details_country1` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `page`
--
ALTER TABLE `page`
  ADD CONSTRAINT `fk_page_page1` FOREIGN KEY (`parent_id`) REFERENCES `page` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_page_page_status1` FOREIGN KEY (`status_id`) REFERENCES `page_status` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `page_meta`
--
ALTER TABLE `page_meta`
  ADD CONSTRAINT `fk_page_meta_page1` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `setting`
--
ALTER TABLE `setting`
  ADD CONSTRAINT `fk_setting_User1` FOREIGN KEY (`User_id`) REFERENCES `User` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `User`
--
ALTER TABLE `User`
  ADD CONSTRAINT `fk_User_seller_infos1` FOREIGN KEY (`seller_infos_id`) REFERENCES `seller_infos` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `user_infos`
--
ALTER TABLE `user_infos`
  ADD CONSTRAINT `fk_user_infos_country1` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_infos_User1` FOREIGN KEY (`User_id`) REFERENCES `User` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
