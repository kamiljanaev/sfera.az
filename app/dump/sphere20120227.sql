SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT=0;
START TRANSACTION;
SET time_zone = "+00:00";

DROP TABLE IF EXISTS `site_ads`;
CREATE TABLE IF NOT EXISTS `site_ads` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  `activated` int(10) unsigned NOT NULL DEFAULT '0',
  `is_payed` int(10) unsigned NOT NULL DEFAULT '0',
  `public_date` datetime DEFAULT NULL,
  `to_date` datetime DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL DEFAULT '',
  `short_content` text NOT NULL,
  `content` mediumtext NOT NULL,
  `deleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `site_ads_user` (`user_id`),
  KEY `site_ads_category_id` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

INSERT INTO `site_ads` (`id`, `user_id`, `category_id`, `activated`, `is_payed`, `public_date`, `to_date`, `image`, `title`, `value`, `short_content`, `content`, `deleted`) VALUES
(1, 1, 11, 1, 0, '2020-02-20 00:00:00', '2026-02-20 00:00:00', '', 'Требуется уборщик', '1500', '', 'ываываываыва', NULL),
(2, 6, 11, 1, 1, '2012-02-20 00:00:00', '2022-02-20 00:00:00', '/upload/6a00d8354f1fb569e20147e02e809d970b.jpg', 'Требуется водитель', '2000', 'краткое', 'кило кокаина', NULL),
(3, 1, 12, 1, 1, '2026-02-20 00:00:00', '2029-02-20 00:00:00', '/upload/preview.jpg', 'Вилла', '$ 70000', 'краткое поисание', 'пробается вилла', NULL),
(4, 1, 12, 1, 1, '2026-02-20 00:00:00', '2029-02-20 00:00:00', '/upload/kvartiry-v-astane_29_04.jpg', 'Квартира', '$ 50 000', 'описание краткое', 'продается квартира', NULL);

DROP TABLE IF EXISTS `site_category_tree`;
CREATE TABLE IF NOT EXISTS `site_category_tree` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `left_id` int(10) DEFAULT NULL,
  `right_id` int(10) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `is_active` int(10) unsigned NOT NULL DEFAULT '1',
  `show_on_home` int(10) unsigned NOT NULL DEFAULT '0',
  `order` int(10) unsigned NOT NULL DEFAULT '0',
  `readonly` int(10) unsigned NOT NULL DEFAULT '0',
  `deleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

INSERT INTO `site_category_tree` (`id`, `parent_id`, `left_id`, `right_id`, `title`, `alias`, `is_active`, `show_on_home`, `order`, `readonly`, `deleted`) VALUES
(1, NULL, 1, 8, 'Новости', 'news-root-category', 1, 0, 1, 1, NULL),
(2, NULL, 9, 14, 'Объявления', 'ads-root-catgory', 1, 0, 1, 1, NULL),
(4, 1, 4, 5, 'werwerwer', '', 1, 0, 0, 0, '2012-02-19 23:24:01'),
(5, 2, 8, 9, 'sdfsdfsdf', '', 1, 0, 0, 0, '2012-02-19 23:23:56'),
(6, 1, 2, 3, 'sssssssss', '', 1, 0, 0, 0, '2012-02-19 23:24:04'),
(7, 1, 2, 3, 'sdfsdf', '', 1, 0, 0, 0, '2012-02-19 23:32:09'),
(8, 1, 6, 7, 'World', '', 1, 1, 0, 0, NULL),
(9, 1, 4, 5, 'Auto', '', 1, 1, 0, 0, NULL),
(10, 1, 2, 3, 'Politics', '', 1, 1, 0, 0, NULL),
(11, 2, 12, 13, 'Вакансии', 'ads-vacancy-category', 1, 0, 0, 0, NULL),
(12, 2, 10, 11, 'Недвижимость', 'ads-realty-category', 1, 0, 0, 0, NULL);

DROP TABLE IF EXISTS `site_documents`;
CREATE TABLE IF NOT EXISTS `site_documents` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `image` varchar(255) NOT NULL DEFAULT '',
  `comment` text NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `profile_id` int(10) unsigned NOT NULL,
  `status` int(10) NOT NULL DEFAULT '1',
  `deleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `profile_id` (`profile_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

INSERT INTO `site_documents` (`id`, `image`, `comment`, `user_id`, `profile_id`, `status`, `deleted`) VALUES
(1, '/upload/1328278915431746_156188321163426_100003167780590_210723_1607536174_n.jpg', 'sdasfasdfasdfasdf', 6, 1, 1, '2012-02-03 14:41:34'),
(2, '/upload/431082_155408604574731_100003167780590_209152_1205988547_n.jpg', 'sdfsdf', 6, 1, 0, NULL),
(3, '/upload/1328284167430740_156523421129916_100003167780590_211575_789600414_n.jpg', 'dfgdfgdfg', 6, 1, 0, NULL);

DROP TABLE IF EXISTS `site_news`;
CREATE TABLE IF NOT EXISTS `site_news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `category_id` int(10) unsigned NOT NULL DEFAULT '1',
  `activated` int(10) unsigned NOT NULL DEFAULT '0',
  `is_hot` int(10) unsigned NOT NULL DEFAULT '0',
  `public_date` datetime DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `keywords` text,
  `description` text,
  `short_content` text NOT NULL,
  `content` mediumtext NOT NULL,
  `deleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `site_news_user` (`user_id`),
  KEY `site_news_category_id` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

INSERT INTO `site_news` (`id`, `user_id`, `category_id`, `activated`, `is_hot`, `public_date`, `image`, `title`, `keywords`, `description`, `short_content`, `content`, `deleted`) VALUES
(1, 2, 0, 1, 0, '2012-02-01 00:00:00', '', 'Первая новость!!!', '', '', 'бла!!!', 'блаблабла', '2012-02-01 21:41:09'),
(2, 1, 9, 1, 1, '2012-01-31 00:00:00', '/upload/1328134134x_7de48d00.jpg', 'Новость 2', 'идфидфидф', 'фывыва', 'ываыв', 'аыва', NULL),
(3, 1, 9, 1, 1, '2012-01-20 00:00:00', '/upload/1328134134x_7de48d00.jpg', 'ываываыва', '', '', 'ываыв', 'аывавыа', NULL),
(4, 1, 0, 0, 0, '2012-01-20 00:00:00', '', 'вапвап', '', '', 'вапва', 'пвапвап', '2012-02-01 22:06:07'),
(5, 1, 0, 1, 0, '2012-01-20 00:00:00', '', 'ываыва', '', '', 'ываы', 'ваыва', NULL),
(6, 6, 0, 0, 0, '2001-02-20 00:00:00', '', 'новость бля', NULL, NULL, 'фывфыв', 'фывфывфывфывфыв', '2012-02-01 22:06:09'),
(7, 1, 0, 0, 0, '2001-02-20 00:00:00', '', 'qeqweqwe', '', '', 'qweqw', 'eqweqwe', '2012-02-01 21:53:16'),
(8, 6, 0, 0, 0, '2001-02-20 00:00:00', '', 'новость бля!!!', NULL, NULL, 'фывфыв', 'фывфывфывфывфыв', '2012-02-01 21:53:13'),
(9, 6, 0, 0, 0, '2012-02-01 00:00:00', '', '!!!!новость бля!!!', NULL, NULL, 'фывфыв', 'фывфывфывфывфыв', '2012-02-01 21:41:20'),
(10, 6, 0, 0, 0, '2012-02-01 00:00:00', '', '!!!!новость бля!!!', NULL, NULL, 'фывфыв', 'фывфывфывфывфыв', '2012-02-01 21:41:22'),
(11, 6, 0, 0, 0, '2012-02-01 00:00:00', '', '!!!!новость бля!!!', NULL, NULL, 'фывфыв', 'фывфывфывфывфыв', '2012-02-01 21:52:58'),
(12, 6, 0, 0, 0, '2012-02-01 00:00:00', '', '!!!!новость бля!!!', NULL, NULL, 'фывфыв', 'фывфывфывфывфыв', '2012-02-01 21:53:00'),
(13, 6, 0, 0, 0, '2012-02-01 00:00:00', '', '!!!!новость бля!!!', NULL, NULL, 'фывфыв', 'фывфывфывфывфыв', '2012-02-01 21:53:09'),
(14, 6, 0, 0, 0, '2012-02-01 00:00:00', '', 'фы', NULL, NULL, 'фывфыв', 'фывфывфывфывфыв', '2012-02-01 21:41:25'),
(15, 6, 0, 0, 0, '2012-02-01 00:00:00', '', 'фы', NULL, NULL, 'фывфыв', 'фывфывфывфывфыв', '2012-02-01 21:41:35'),
(16, 6, 0, 0, 0, '2012-02-01 00:00:00', '', '333333333', NULL, NULL, 'фывфыв', 'фывфывфывфывфыв', '2012-02-01 21:53:11'),
(17, 6, 0, 0, 0, '2012-02-01 00:00:00', '', 'Добавленая новость', NULL, NULL, 'фывфыв', 'фывфыв', '2012-02-01 21:41:32'),
(18, 6, 0, 0, 0, '2012-02-01 00:00:00', '', 'ываываыва', NULL, NULL, 'ыва', 'ываыва', '2012-02-01 22:06:03'),
(19, 6, 0, 0, 0, '2012-02-01 00:00:00', '', 'ываываыва', NULL, NULL, 'ыва', 'ываыва', '2012-02-01 21:41:30'),
(20, 6, 0, 0, 0, '2012-02-01 00:00:00', '/upload/r_46a22552.jpg', 'ываываыва', NULL, NULL, 'ыва', 'ываыва', '2012-02-01 21:53:03'),
(21, 6, 0, 0, 0, '2012-02-01 00:00:00', '/upload/фото21.jpg', 'ываываыва', NULL, NULL, 'ыва', 'ываыва', '2012-02-01 22:06:05'),
(22, 6, 0, 0, 0, '2012-02-02 00:00:00', '', 'ываываыва', NULL, NULL, 'ыва', 'ываыва', '2012-02-01 22:06:01'),
(23, 6, 10, 1, 1, '2012-02-02 00:00:00', '/upload/1328134134x_7de48d00.jpg', 'ываываыва', '', '', 'ыва', 'ываыва', NULL),
(24, 6, 10, 1, 1, '2012-02-02 00:00:00', '/upload/1328134134x_7de48d00.jpg', 'ываываыва', '', '', 'ыва', 'ываыва', NULL),
(25, 6, 8, 1, 1, '2012-02-02 00:00:00', '/upload/1328134134x_7de48d00.jpg', 'ываываыва', '', '', 'ыва', 'ываыва', NULL),
(26, 6, 8, 0, 0, '2012-02-03 00:00:00', '', 'ertwe', '', '', 'dfgsd', 'dfghdfgh', NULL);

DROP TABLE IF EXISTS `site_profiles`;
CREATE TABLE IF NOT EXISTS `site_profiles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `firstname` varchar(50) NOT NULL DEFAULT '',
  `middlename` varchar(50) NOT NULL DEFAULT '',
  `lastname` varchar(50) NOT NULL DEFAULT '',
  `alias` varchar(100) NOT NULL DEFAULT '',
  `birthdate` date DEFAULT NULL,
  `current_status` varchar(255) NOT NULL DEFAULT '',
  `is_real` int(10) NOT NULL DEFAULT '0',
  `is_vip` int(10) NOT NULL DEFAULT '0',
  `account_number` varchar(12) NOT NULL DEFAULT '',
  `amount` decimal(10,0) NOT NULL DEFAULT '0',
  `sms_amount` int(11) NOT NULL DEFAULT '0',
  `awards` varchar(255) NOT NULL DEFAULT '',
  `deleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `site_profile_user` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

INSERT INTO `site_profiles` (`id`, `user_id`, `firstname`, `middlename`, `lastname`, `alias`, `birthdate`, `current_status`, `is_real`, `is_vip`, `account_number`, `amount`, `sms_amount`, `awards`, `deleted`) VALUES
(1, 6, 'Дмитрий!', 'Николаевич!', 'Панченко!', 'kocmoc', '1982-02-06', 'sdkfjsdfgsadfastyjyrtu', 1, 1, '1222333', '400', 0, '', NULL),
(2, 8, 'орпопо', 'олрплорп', 'флорало', 'user5', '2012-02-10', 'sdjkfhs', 1, 1, '', '124', 0, '', NULL),
(3, 1, '', '', '', '', NULL, '', 0, 0, '', '0', 0, '', NULL);

DROP TABLE IF EXISTS `site_scratch_cards`;
CREATE TABLE IF NOT EXISTS `site_scratch_cards` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `number` varchar(50) NOT NULL,
  `amount` decimal(10,0) NOT NULL DEFAULT '0',
  `is_used` int(10) DEFAULT '0',
  `deleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `site_scratch_cards_number` (`number`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

INSERT INTO `site_scratch_cards` (`id`, `number`, `amount`, `is_used`, `deleted`) VALUES
(1, '1234567890', '100', 1, NULL),
(2, '888999000', '300', 1, '2012-01-31 21:30:58'),
(3, '4444444444', '24', 1, NULL),
(4, '666666666', '100', 1, NULL);

DROP TABLE IF EXISTS `site_settings`;
CREATE TABLE IF NOT EXISTS `site_settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `description` varchar(255) NOT NULL,
  `type` varchar(10) NOT NULL DEFAULT 'text',
  `position` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `site_settings_name` (`name`),
  KEY `site_settings_position` (`position`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

INSERT INTO `site_settings` (`id`, `name`, `value`, `description`, `type`, `position`) VALUES
(1, 'site_title', 'Сфера', 'Заголовок сайта', 'text', 1),
(2, 'site_title_separator', ' - ', 'Разделитель заголовка', 'text', 2),
(3, 'site_keywords', '', 'Ключевые слова сайта', 'textarea', 3),
(4, 'site_descriptions', '', 'Описание сайта', 'textarea', 4);

DROP TABLE IF EXISTS `site_statuses`;
CREATE TABLE IF NOT EXISTS `site_statuses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `value` text NOT NULL,
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `site_statuses_user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

INSERT INTO `site_statuses` (`id`, `user_id`, `value`, `added`, `deleted`) VALUES
(1, 6, 'Все пройдет!!!', '2012-02-20 00:54:38', NULL),
(2, 6, 'Все вообще пройдет!!!', '2012-02-20 00:54:44', NULL),
(3, 6, 'sdkfjsdfg', '2012-02-20 16:13:12', NULL),
(4, 6, 'sdkfjsdfg', '2012-02-20 16:13:15', NULL),
(5, 6, 'sdkfjsdfgsadfas', '2012-02-20 16:13:37', NULL),
(6, 6, 'sdkfjsdfgsadfastyjyrtu', '2012-02-20 16:13:41', NULL),
(7, 6, 'sdkfjsdfgsadfastyjyrtu', '2012-02-20 16:13:43', NULL);

DROP TABLE IF EXISTS `site_subscribes`;
CREATE TABLE IF NOT EXISTS `site_subscribes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `deleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `site_subscribes_type_id` (`type_id`),
  KEY `site_subscribes_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `sys_actions`;
CREATE TABLE IF NOT EXISTS `sys_actions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `controller_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_actions_unique_action_in_controller` (`name`,`controller_id`),
  KEY `sys_actions_controller` (`controller_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=110 ;

INSERT INTO `sys_actions` (`id`, `name`, `controller_id`) VALUES
(70, 'activate', 16),
(101, 'activate', 21),
(13, 'add', 7),
(19, 'add', 8),
(29, 'add', 10),
(36, 'add', 11),
(42, 'add', 12),
(55, 'add', 14),
(60, 'add', 16),
(71, 'add', 17),
(74, 'add', 18),
(81, 'add', 19),
(91, 'add', 20),
(100, 'add', 21),
(106, 'add', 22),
(86, 'approve', 19),
(87, 'cancel', 19),
(24, 'clearcache', 9),
(51, 'confirmation', 13),
(109, 'connector', 23),
(14, 'delete', 7),
(20, 'delete', 8),
(30, 'delete', 10),
(37, 'delete', 11),
(43, 'delete', 12),
(56, 'delete', 14),
(64, 'delete', 16),
(76, 'delete', 18),
(84, 'delete', 19),
(95, 'delete', 20),
(103, 'delete', 21),
(10, 'denied', 6),
(79, 'document', 13),
(15, 'edit', 7),
(21, 'edit', 8),
(31, 'edit', 10),
(33, 'edit', 11),
(44, 'edit', 12),
(52, 'edit', 13),
(57, 'edit', 14),
(61, 'edit', 16),
(77, 'edit', 18),
(82, 'edit', 19),
(94, 'edit', 20),
(104, 'edit', 21),
(47, 'fetch', 9),
(90, 'fetch', 20),
(1, 'index', 1),
(2, 'index', 2),
(3, 'index', 3),
(4, 'index', 4),
(6, 'index', 5),
(7, 'index', 6),
(12, 'index', 7),
(18, 'index', 8),
(23, 'index', 9),
(28, 'index', 10),
(34, 'index', 11),
(40, 'index', 12),
(49, 'index', 13),
(53, 'index', 14),
(62, 'index', 16),
(73, 'index', 18),
(80, 'index', 19),
(88, 'index', 20),
(99, 'index', 21),
(89, 'init', 20),
(11, 'list', 7),
(17, 'list', 8),
(27, 'list', 10),
(35, 'list', 11),
(41, 'list', 12),
(54, 'list', 14),
(63, 'list', 16),
(67, 'list', 17),
(75, 'list', 18),
(83, 'list', 19),
(97, 'list', 20),
(102, 'list', 21),
(107, 'list', 22),
(45, 'lista', 7),
(46, 'lista', 8),
(48, 'lista', 10),
(92, 'load', 20),
(8, 'login', 6),
(9, 'logout', 6),
(25, 'module', 9),
(96, 'move', 20),
(68, 'ping', 5),
(50, 'registration', 13),
(72, 'reset', 13),
(5, 'save', 4),
(93, 'save', 20),
(26, 'sections', 9),
(66, 'show', 17),
(108, 'show', 22),
(59, 'update', 15),
(69, 'usergrabber', 5),
(16, 'view', 7),
(22, 'view', 8),
(32, 'view', 10),
(38, 'view', 11),
(39, 'view', 12),
(58, 'view', 14),
(65, 'view', 16),
(78, 'view', 18),
(85, 'view', 19),
(98, 'view', 20),
(105, 'view', 21);

DROP TABLE IF EXISTS `sys_controllers`;
CREATE TABLE IF NOT EXISTS `sys_controllers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `module_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_controllers_unique_controller_in_module` (`name`,`module_id`),
  KEY `sys_controllers_module` (`module_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

INSERT INTO `sys_controllers` (`id`, `name`, `module_id`) VALUES
(7, 'admin_actions', 7),
(21, 'admin_ads', 11),
(14, 'admin_cards', 10),
(20, 'admin_category', 11),
(8, 'admin_controllers', 7),
(19, 'admin_document', 9),
(4, 'admin_index', 2),
(12, 'admin_index', 4),
(9, 'admin_index', 7),
(11, 'admin_index', 8),
(18, 'admin_index', 9),
(10, 'admin_modules', 7),
(16, 'admin_news', 11),
(22, 'ads', 11),
(6, 'auth', 4),
(15, 'cards', 10),
(23, 'elfinder', 12),
(1, 'index', 1),
(5, 'index', 3),
(13, 'index', 9),
(2, 'install', 1),
(3, 'module', 1),
(17, 'news', 11);

DROP TABLE IF EXISTS `sys_modules`;
CREATE TABLE IF NOT EXISTS `sys_modules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_modules_unique_module` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

INSERT INTO `sys_modules` (`id`, `name`) VALUES
(1, 'admin'),
(10, 'billing'),
(2, 'config'),
(11, 'content'),
(3, 'default'),
(12, 'files'),
(7, 'permissions'),
(9, 'profile'),
(8, 'roles'),
(4, 'users');

DROP TABLE IF EXISTS `sys_permissions`;
CREATE TABLE IF NOT EXISTS `sys_permissions` (
  `role_id` int(10) unsigned NOT NULL,
  `module_id` int(10) unsigned NOT NULL,
  `controller_id` int(10) unsigned NOT NULL,
  `action_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`module_id`,`controller_id`,`action_id`),
  KEY `sys_permissions_module` (`module_id`),
  KEY `sys_permissions_controller` (`controller_id`),
  KEY `sys_permissions_action` (`action_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `sys_permissions` (`role_id`, `module_id`, `controller_id`, `action_id`) VALUES
(1, 1, 1, 1),
(1, 1, 2, 2),
(1, 1, 3, 3),
(1, 2, 4, 4),
(1, 2, 4, 5),
(2, 3, 5, 6),
(2, 3, 5, 68),
(2, 3, 5, 69),
(3, 3, 5, 6),
(1, 4, 6, 9),
(1, 4, 12, 39),
(1, 4, 12, 40),
(1, 4, 12, 41),
(1, 4, 12, 42),
(1, 4, 12, 43),
(1, 4, 12, 44),
(2, 4, 6, 7),
(2, 4, 6, 8),
(2, 4, 6, 10),
(3, 4, 6, 7),
(3, 4, 6, 8),
(3, 4, 6, 9),
(3, 4, 6, 10),
(1, 7, 7, 11),
(1, 7, 7, 12),
(1, 7, 7, 13),
(1, 7, 7, 14),
(1, 7, 7, 15),
(1, 7, 7, 16),
(1, 7, 8, 17),
(1, 7, 8, 18),
(1, 7, 8, 19),
(1, 7, 8, 20),
(1, 7, 8, 21),
(1, 7, 8, 22),
(1, 7, 9, 23),
(1, 7, 9, 24),
(1, 7, 9, 25),
(1, 7, 9, 26),
(1, 7, 9, 47),
(1, 7, 10, 27),
(1, 7, 10, 28),
(1, 7, 10, 29),
(1, 7, 10, 30),
(1, 7, 10, 31),
(1, 7, 10, 32),
(1, 8, 11, 33),
(1, 8, 11, 34),
(1, 8, 11, 35),
(1, 8, 11, 36),
(1, 8, 11, 37),
(1, 8, 11, 38),
(1, 9, 18, 73),
(1, 9, 18, 75),
(1, 9, 18, 76),
(1, 9, 18, 77),
(1, 9, 19, 80),
(1, 9, 19, 83),
(1, 9, 19, 84),
(1, 9, 19, 85),
(1, 9, 19, 86),
(1, 9, 19, 87),
(2, 9, 13, 50),
(2, 9, 13, 51),
(2, 9, 13, 72),
(3, 9, 13, 49),
(3, 9, 13, 52),
(3, 9, 13, 79),
(1, 10, 14, 53),
(1, 10, 14, 54),
(1, 10, 14, 55),
(1, 10, 14, 56),
(1, 10, 14, 57),
(1, 10, 14, 58),
(3, 10, 15, 59),
(1, 11, 16, 60),
(1, 11, 16, 61),
(1, 11, 16, 62),
(1, 11, 16, 63),
(1, 11, 16, 64),
(1, 11, 16, 65),
(1, 11, 16, 70),
(1, 11, 17, 71),
(1, 11, 20, 88),
(1, 11, 20, 89),
(1, 11, 20, 90),
(1, 11, 20, 91),
(1, 11, 20, 92),
(1, 11, 20, 93),
(1, 11, 20, 94),
(1, 11, 20, 95),
(1, 11, 20, 96),
(1, 11, 20, 97),
(1, 11, 20, 98),
(1, 11, 21, 99),
(1, 11, 21, 100),
(1, 11, 21, 101),
(1, 11, 21, 102),
(1, 11, 21, 103),
(1, 11, 21, 104),
(1, 11, 21, 105),
(2, 11, 17, 66),
(2, 11, 17, 67),
(3, 11, 17, 66),
(3, 11, 17, 67),
(3, 11, 17, 71),
(3, 11, 22, 106),
(3, 11, 22, 107),
(3, 11, 22, 108),
(1, 12, 23, 109);

DROP TABLE IF EXISTS `sys_roles`;
CREATE TABLE IF NOT EXISTS `sys_roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `deleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

INSERT INTO `sys_roles` (`id`, `name`, `deleted`) VALUES
(1, 'admin', NULL),
(2, 'guest', NULL),
(3, 'member', NULL),
(4, 'test', '2012-01-08 22:57:03'),
(5, 'ljhkjhgkjh', '2012-01-08 22:56:57');

DROP TABLE IF EXISTS `sys_roleslinks`;
CREATE TABLE IF NOT EXISTS `sys_roleslinks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sys_roleslinks_unique_link` (`user_id`,`role_id`),
  KEY `sys_roleslinks_user` (`user_id`),
  KEY `sys_roleslinks_role` (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

INSERT INTO `sys_roleslinks` (`id`, `user_id`, `role_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 2),
(4, 2, 3),
(5, 3, 1),
(6, 3, 2),
(7, 3, 3),
(10, 5, 2),
(11, 5, 3),
(12, 6, 2),
(13, 6, 3),
(14, 7, 2),
(15, 7, 3),
(16, 8, 2),
(17, 8, 3);

DROP TABLE IF EXISTS `sys_users`;
CREATE TABLE IF NOT EXISTS `sys_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` char(32) NOT NULL,
  `status` int(10) unsigned NOT NULL DEFAULT '1',
  `activated` int(10) unsigned NOT NULL DEFAULT '0',
  `verify_code` varchar(10) DEFAULT NULL,
  `is_online` int(10) NOT NULL DEFAULT '0',
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

INSERT INTO `sys_users` (`id`, `email`, `password`, `status`, `activated`, `verify_code`, `is_online`, `changed`, `deleted`) VALUES
(1, 'admin@sphere.com', 'e10adc3949ba59abbe56e057f20f883e', 1, 1, NULL, 1, '2012-01-18 22:00:00', NULL),
(2, 'user1@sphere.com', '25d55ad283aa400af464c76d713c07ad', 1, 1, NULL, 0, '0000-00-00 00:00:00', NULL),
(3, 'kosmozilla@gmail.com', '25d55ad283aa400af464c76d713c07ad', 1, 0, NULL, 0, '0000-00-00 00:00:00', NULL),
(5, 'user2@sphere.com', '45256440bc446dcfc6685591a427e8ec', 1, 0, 'fauo', 0, '0000-00-00 00:00:00', NULL),
(6, 'dmitry@panchenko.biz', 'e807f1fcf82d132f9bb018ca6738a19f', 1, 1, '', 0, '2012-02-02 16:14:48', NULL),
(7, 'user3@sphere.com', 'e115451ca17e14fcb73a0667a16c53b9', 1, 1, '', 0, '0000-00-00 00:00:00', NULL),
(8, 'mail@artgen.com.ua', '25d55ad283aa400af464c76d713c07ad', 1, 1, '', 0, '2012-01-19 15:42:39', NULL);
DROP VIEW IF EXISTS `view_ads`;
CREATE TABLE IF NOT EXISTS `view_ads` (
`id` int(10) unsigned
,`user_id` int(10) unsigned
,`category_id` int(10) unsigned
,`activated` int(10) unsigned
,`is_payed` int(10) unsigned
,`public_date` datetime
,`to_date` datetime
,`image` varchar(255)
,`title` varchar(255)
,`value` varchar(255)
,`short_content` text
,`content` mediumtext
,`deleted` timestamp
);DROP VIEW IF EXISTS `view_category_tree`;
CREATE TABLE IF NOT EXISTS `view_category_tree` (
`id` int(10) unsigned
,`parent_id` int(10) unsigned
,`left_id` int(10)
,`right_id` int(10)
,`title` varchar(255)
,`alias` varchar(255)
,`is_active` int(10) unsigned
,`show_on_home` int(10) unsigned
,`order` int(10) unsigned
,`readonly` int(10) unsigned
,`deleted` timestamp
);DROP VIEW IF EXISTS `view_documents`;
CREATE TABLE IF NOT EXISTS `view_documents` (
`id` int(10)
,`image` varchar(255)
,`comment` text
,`user_id` int(10) unsigned
,`profile_id` int(10) unsigned
,`status` int(10)
,`deleted` timestamp
);DROP VIEW IF EXISTS `view_news`;
CREATE TABLE IF NOT EXISTS `view_news` (
`id` int(10) unsigned
,`user_id` int(10) unsigned
,`category_id` int(10) unsigned
,`activated` int(10) unsigned
,`is_hot` int(10) unsigned
,`public_date` datetime
,`image` varchar(255)
,`title` varchar(255)
,`keywords` text
,`description` text
,`short_content` text
,`content` mediumtext
,`deleted` timestamp
);DROP VIEW IF EXISTS `view_permissions`;
CREATE TABLE IF NOT EXISTS `view_permissions` (
`role_id` int(10) unsigned
,`role_name` varchar(255)
,`module_id` int(10) unsigned
,`module_name` varchar(255)
,`controller_id` int(10) unsigned
,`controller_name` varchar(255)
,`action_id` int(10) unsigned
,`action_name` varchar(255)
);DROP VIEW IF EXISTS `view_profiles`;
CREATE TABLE IF NOT EXISTS `view_profiles` (
`id` int(10) unsigned
,`user_id` int(10) unsigned
,`firstname` varchar(50)
,`middlename` varchar(50)
,`lastname` varchar(50)
,`alias` varchar(100)
,`birthdate` date
,`current_status` varchar(255)
,`is_real` int(10)
,`is_vip` int(10)
,`account_number` varchar(12)
,`amount` decimal(10,0)
,`sms_amount` int(11)
,`awards` varchar(255)
,`deleted` timestamp
);DROP VIEW IF EXISTS `view_roles`;
CREATE TABLE IF NOT EXISTS `view_roles` (
`id` int(10) unsigned
,`name` varchar(255)
,`deleted` timestamp
);DROP VIEW IF EXISTS `view_scratch_cards`;
CREATE TABLE IF NOT EXISTS `view_scratch_cards` (
`id` int(10) unsigned
,`number` varchar(50)
,`amount` decimal(10,0)
,`is_used` int(10)
,`deleted` timestamp
);DROP VIEW IF EXISTS `view_statuses`;
CREATE TABLE IF NOT EXISTS `view_statuses` (
`id` int(10) unsigned
,`user_id` int(10) unsigned
,`value` text
,`added` timestamp
,`deleted` timestamp
);DROP VIEW IF EXISTS `view_subscribes`;
CREATE TABLE IF NOT EXISTS `view_subscribes` (
`id` int(10) unsigned
,`type_id` int(10) unsigned
,`user_id` int(10) unsigned
,`item_id` int(10) unsigned
,`deleted` timestamp
);DROP VIEW IF EXISTS `view_users`;
CREATE TABLE IF NOT EXISTS `view_users` (
`id` int(10) unsigned
,`email` varchar(255)
,`password` char(32)
,`status` int(10) unsigned
,`activated` int(10) unsigned
,`verify_code` varchar(10)
,`is_online` int(10)
,`changed` timestamp
,`deleted` timestamp
);DROP TABLE IF EXISTS `view_ads`;

CREATE OR REPLACE VIEW `view_ads` AS select `site_ads`.`id` AS `id`,`site_ads`.`user_id` AS `user_id`,`site_ads`.`category_id` AS `category_id`,`site_ads`.`activated` AS `activated`,`site_ads`.`is_payed` AS `is_payed`,`site_ads`.`public_date` AS `public_date`,`site_ads`.`to_date` AS `to_date`,`site_ads`.`image` AS `image`,`site_ads`.`title` AS `title`,`site_ads`.`value` AS `value`,`site_ads`.`short_content` AS `short_content`,`site_ads`.`content` AS `content`,`site_ads`.`deleted` AS `deleted` from `site_ads` where isnull(`site_ads`.`deleted`);
DROP TABLE IF EXISTS `view_category_tree`;

CREATE OR REPLACE VIEW `view_category_tree` AS select `site_category_tree`.`id` AS `id`,`site_category_tree`.`parent_id` AS `parent_id`,`site_category_tree`.`left_id` AS `left_id`,`site_category_tree`.`right_id` AS `right_id`,`site_category_tree`.`title` AS `title`,`site_category_tree`.`alias` AS `alias`,`site_category_tree`.`is_active` AS `is_active`,`site_category_tree`.`show_on_home` AS `show_on_home`,`site_category_tree`.`order` AS `order`,`site_category_tree`.`readonly` AS `readonly`,`site_category_tree`.`deleted` AS `deleted` from `site_category_tree` where isnull(`site_category_tree`.`deleted`);
DROP TABLE IF EXISTS `view_documents`;

CREATE OR REPLACE VIEW `view_documents` AS select `site_documents`.`id` AS `id`,`site_documents`.`image` AS `image`,`site_documents`.`comment` AS `comment`,`site_documents`.`user_id` AS `user_id`,`site_documents`.`profile_id` AS `profile_id`,`site_documents`.`status` AS `status`,`site_documents`.`deleted` AS `deleted` from `site_documents` where isnull(`site_documents`.`deleted`);
DROP TABLE IF EXISTS `view_news`;

CREATE OR REPLACE VIEW `view_news` AS select `site_news`.`id` AS `id`,`site_news`.`user_id` AS `user_id`,`site_news`.`category_id` AS `category_id`,`site_news`.`activated` AS `activated`,`site_news`.`is_hot` AS `is_hot`,`site_news`.`public_date` AS `public_date`,`site_news`.`image` AS `image`,`site_news`.`title` AS `title`,`site_news`.`keywords` AS `keywords`,`site_news`.`description` AS `description`,`site_news`.`short_content` AS `short_content`,`site_news`.`content` AS `content`,`site_news`.`deleted` AS `deleted` from `site_news` where isnull(`site_news`.`deleted`);
DROP TABLE IF EXISTS `view_permissions`;

CREATE OR REPLACE VIEW `view_permissions` AS select `permissions`.`role_id` AS `role_id`,`roles`.`name` AS `role_name`,`permissions`.`module_id` AS `module_id`,`modules`.`name` AS `module_name`,`permissions`.`controller_id` AS `controller_id`,`controllers`.`name` AS `controller_name`,`permissions`.`action_id` AS `action_id`,`actions`.`name` AS `action_name` from ((((`sys_permissions` `permissions` join `sys_roles` `roles` on((`roles`.`id` = `permissions`.`role_id`))) join `sys_modules` `modules` on((`modules`.`id` = `permissions`.`module_id`))) join `sys_controllers` `controllers` on((`controllers`.`id` = `permissions`.`controller_id`))) join `sys_actions` `actions` on((`actions`.`id` = `permissions`.`action_id`)));
DROP TABLE IF EXISTS `view_profiles`;

CREATE OR REPLACE VIEW `view_profiles` AS select `site_profiles`.`id` AS `id`,`site_profiles`.`user_id` AS `user_id`,`site_profiles`.`firstname` AS `firstname`,`site_profiles`.`middlename` AS `middlename`,`site_profiles`.`lastname` AS `lastname`,`site_profiles`.`alias` AS `alias`,`site_profiles`.`birthdate` AS `birthdate`,`site_profiles`.`current_status` AS `current_status`,`site_profiles`.`is_real` AS `is_real`,`site_profiles`.`is_vip` AS `is_vip`,`site_profiles`.`account_number` AS `account_number`,`site_profiles`.`amount` AS `amount`,`site_profiles`.`sms_amount` AS `sms_amount`,`site_profiles`.`awards` AS `awards`,`site_profiles`.`deleted` AS `deleted` from `site_profiles` where isnull(`site_profiles`.`deleted`);
DROP TABLE IF EXISTS `view_roles`;

CREATE OR REPLACE VIEW `view_roles` AS select `sys_roles`.`id` AS `id`,`sys_roles`.`name` AS `name`,`sys_roles`.`deleted` AS `deleted` from `sys_roles` where isnull(`sys_roles`.`deleted`);
DROP TABLE IF EXISTS `view_scratch_cards`;

CREATE OR REPLACE VIEW `view_scratch_cards` AS select `site_scratch_cards`.`id` AS `id`,`site_scratch_cards`.`number` AS `number`,`site_scratch_cards`.`amount` AS `amount`,`site_scratch_cards`.`is_used` AS `is_used`,`site_scratch_cards`.`deleted` AS `deleted` from `site_scratch_cards` where isnull(`site_scratch_cards`.`deleted`);
DROP TABLE IF EXISTS `view_statuses`;

CREATE OR REPLACE VIEW `view_statuses` AS select `site_statuses`.`id` AS `id`,`site_statuses`.`user_id` AS `user_id`,`site_statuses`.`value` AS `value`,`site_statuses`.`added` AS `added`,`site_statuses`.`deleted` AS `deleted` from `site_statuses` where isnull(`site_statuses`.`deleted`);
DROP TABLE IF EXISTS `view_subscribes`;

CREATE OR REPLACE VIEW `view_subscribes` AS select `site_subscribes`.`id` AS `id`,`site_subscribes`.`type_id` AS `type_id`,`site_subscribes`.`user_id` AS `user_id`,`site_subscribes`.`item_id` AS `item_id`,`site_subscribes`.`deleted` AS `deleted` from `site_subscribes` where isnull(`site_subscribes`.`deleted`);
DROP TABLE IF EXISTS `view_users`;

CREATE OR REPLACE VIEW `view_users` AS select `sys_users`.`id` AS `id`,`sys_users`.`email` AS `email`,`sys_users`.`password` AS `password`,`sys_users`.`status` AS `status`,`sys_users`.`activated` AS `activated`,`sys_users`.`verify_code` AS `verify_code`,`sys_users`.`is_online` AS `is_online`,`sys_users`.`changed` AS `changed`,`sys_users`.`deleted` AS `deleted` from `sys_users` where isnull(`sys_users`.`deleted`);


ALTER TABLE `site_ads`
  ADD CONSTRAINT `site_ads_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `sys_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `site_documents`
  ADD CONSTRAINT `site_documents_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `sys_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `site_documents_ibfk_2` FOREIGN KEY (`profile_id`) REFERENCES `site_profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `site_news`
  ADD CONSTRAINT `site_news_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `sys_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `site_profiles`
  ADD CONSTRAINT `site_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `sys_users` (`id`);

ALTER TABLE `site_statuses`
  ADD CONSTRAINT `site_statuses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `sys_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `site_subscribes`
  ADD CONSTRAINT `site_subscribes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `sys_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `sys_actions`
  ADD CONSTRAINT `sys_actions_ibfk_1` FOREIGN KEY (`controller_id`) REFERENCES `sys_controllers` (`id`);

ALTER TABLE `sys_controllers`
  ADD CONSTRAINT `sys_controllers_ibfk_1` FOREIGN KEY (`module_id`) REFERENCES `sys_modules` (`id`);

ALTER TABLE `sys_permissions`
  ADD CONSTRAINT `sys_permissions_ibfk_13` FOREIGN KEY (`role_id`) REFERENCES `sys_roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sys_permissions_ibfk_14` FOREIGN KEY (`module_id`) REFERENCES `sys_modules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sys_permissions_ibfk_15` FOREIGN KEY (`controller_id`) REFERENCES `sys_controllers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sys_permissions_ibfk_16` FOREIGN KEY (`action_id`) REFERENCES `sys_actions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `sys_roleslinks`
  ADD CONSTRAINT `sys_roleslinks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `sys_users` (`id`),
  ADD CONSTRAINT `sys_roleslinks_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `sys_roles` (`id`);
SET FOREIGN_KEY_CHECKS=1;
COMMIT;
