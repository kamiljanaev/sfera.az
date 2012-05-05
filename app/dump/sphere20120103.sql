-- phpMyAdmin SQL Dump
-- version 3.4.3.2
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Янв 03 2012 г., 01:54
-- Версия сервера: 5.5.14
-- Версия PHP: 5.3.6

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT=0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `sphere`
--

-- --------------------------------------------------------

--
-- Структура таблицы `site_settings`
--

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

--
-- Дамп данных таблицы `site_settings`
--

INSERT INTO `site_settings` (`id`, `name`, `value`, `description`, `type`, `position`) VALUES
(1, 'site_title', 'Сфера', 'Заголовок сайта', 'text', 1),
(2, 'site_title_separator', ' - ', 'Разделитель заголовка', 'text', 2),
(3, 'site_keywords', '', 'Ключевые слова сайта', 'textarea', 3),
(4, 'site_descriptions', '', 'Описание сайта', 'textarea', 4);

-- --------------------------------------------------------

--
-- Структура таблицы `sys_actions`
--

DROP TABLE IF EXISTS `sys_actions`;
CREATE TABLE IF NOT EXISTS `sys_actions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `controller_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_actions_unique_action_in_controller` (`name`,`controller_id`),
  KEY `sys_actions_controller` (`controller_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Дамп данных таблицы `sys_actions`
--

INSERT INTO `sys_actions` (`id`, `name`, `controller_id`) VALUES
(10, 'denied', 6),
(1, 'index', 1),
(2, 'index', 2),
(3, 'index', 3),
(4, 'index', 4),
(6, 'index', 5),
(7, 'index', 6),
(8, 'login', 6),
(9, 'logout', 6),
(5, 'save', 4);

-- --------------------------------------------------------

--
-- Структура таблицы `sys_controllers`
--

DROP TABLE IF EXISTS `sys_controllers`;
CREATE TABLE IF NOT EXISTS `sys_controllers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `module_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_controllers_unique_controller_in_module` (`name`,`module_id`),
  KEY `sys_controllers_module` (`module_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `sys_controllers`
--

INSERT INTO `sys_controllers` (`id`, `name`, `module_id`) VALUES
(4, 'admin_index', 2),
(6, 'auth', 4),
(1, 'index', 1),
(5, 'index', 3),
(2, 'install', 1),
(3, 'module', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `sys_modules`
--

DROP TABLE IF EXISTS `sys_modules`;
CREATE TABLE IF NOT EXISTS `sys_modules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_modules_unique_module` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `sys_modules`
--

INSERT INTO `sys_modules` (`id`, `name`) VALUES
(1, 'admin'),
(2, 'config'),
(3, 'default'),
(4, 'users');

-- --------------------------------------------------------

--
-- Структура таблицы `sys_permissions`
--

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

--
-- Дамп данных таблицы `sys_permissions`
--

INSERT INTO `sys_permissions` (`role_id`, `module_id`, `controller_id`, `action_id`) VALUES
(1, 1, 1, 1),
(1, 1, 2, 2),
(1, 1, 3, 3),
(1, 2, 4, 4),
(1, 2, 4, 5),
(2, 3, 5, 6),
(1, 4, 6, 9),
(2, 4, 6, 7),
(2, 4, 6, 8),
(2, 4, 6, 10);

-- --------------------------------------------------------

--
-- Структура таблицы `sys_roles`
--

DROP TABLE IF EXISTS `sys_roles`;
CREATE TABLE IF NOT EXISTS `sys_roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `deleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `sys_roles`
--

INSERT INTO `sys_roles` (`id`, `name`, `deleted`) VALUES
(1, 'admin', NULL),
(2, 'guest', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `sys_roleslinks`
--

DROP TABLE IF EXISTS `sys_roleslinks`;
CREATE TABLE IF NOT EXISTS `sys_roleslinks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sys_roleslinks_unique_link` (`user_id`,`role_id`),
  KEY `sys_roleslinks_user` (`user_id`),
  KEY `sys_roleslinks_role` (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `sys_roleslinks`
--

INSERT INTO `sys_roleslinks` (`id`, `user_id`, `role_id`) VALUES
(1, 1, 1),
(2, 1, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `sys_users`
--

DROP TABLE IF EXISTS `sys_users`;
CREATE TABLE IF NOT EXISTS `sys_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `password` char(32) NOT NULL,
  `deleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `sys_users`
--

INSERT INTO `sys_users` (`id`, `login`, `password`, `deleted`) VALUES
(1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', NULL);

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `view_permissions`
--
DROP VIEW IF EXISTS `view_permissions`;
CREATE TABLE IF NOT EXISTS `view_permissions` (
`role_id` int(10) unsigned
,`role_name` varchar(255)
,`module_id` int(10) unsigned
,`module_name` varchar(255)
,`controller_id` int(10) unsigned
,`controller_name` varchar(255)
,`action_id` int(10) unsigned
,`action_name` varchar(255)
);
-- --------------------------------------------------------

--
-- Дублирующая структура для представления `view_roles`
--
DROP VIEW IF EXISTS `view_roles`;
CREATE TABLE IF NOT EXISTS `view_roles` (
`id` int(10) unsigned
,`name` varchar(255)
,`deleted` timestamp
);
-- --------------------------------------------------------

--
-- Дублирующая структура для представления `view_users`
--
DROP VIEW IF EXISTS `view_users`;
CREATE TABLE IF NOT EXISTS `view_users` (
`id` int(10) unsigned
,`login` varchar(255)
,`password` char(32)
,`deleted` timestamp
);
-- --------------------------------------------------------

--
-- Структура для представления `view_permissions`
--
DROP TABLE IF EXISTS `view_permissions`;

CREATE OR REPLACE VIEW `view_permissions` AS select `permissions`.`role_id` AS `role_id`,`roles`.`name` AS `role_name`,`permissions`.`module_id` AS `module_id`,`modules`.`name` AS `module_name`,`permissions`.`controller_id` AS `controller_id`,`controllers`.`name` AS `controller_name`,`permissions`.`action_id` AS `action_id`,`actions`.`name` AS `action_name` from ((((`sys_permissions` `permissions` join `sys_roles` `roles` on((`roles`.`id` = `permissions`.`role_id`))) join `sys_modules` `modules` on((`modules`.`id` = `permissions`.`module_id`))) join `sys_controllers` `controllers` on((`controllers`.`id` = `permissions`.`controller_id`))) join `sys_actions` `actions` on((`actions`.`id` = `permissions`.`action_id`)));

-- --------------------------------------------------------

--
-- Структура для представления `view_roles`
--
DROP TABLE IF EXISTS `view_roles`;

CREATE OR REPLACE VIEW `view_roles` AS select `sys_roles`.`id` AS `id`,`sys_roles`.`name` AS `name`,`sys_roles`.`deleted` AS `deleted` from `sys_roles` where isnull(`sys_roles`.`deleted`);

-- --------------------------------------------------------

--
-- Структура для представления `view_users`
--
DROP TABLE IF EXISTS `view_users`;

CREATE OR REPLACE VIEW `view_users` AS select `sys_users`.`id` AS `id`,`sys_users`.`login` AS `login`,`sys_users`.`password` AS `password`,`sys_users`.`deleted` AS `deleted` from `sys_users` where isnull(`sys_users`.`deleted`);

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `sys_actions`
--
ALTER TABLE `sys_actions`
  ADD CONSTRAINT `sys_actions_ibfk_1` FOREIGN KEY (`controller_id`) REFERENCES `sys_controllers` (`id`);

--
-- Ограничения внешнего ключа таблицы `sys_controllers`
--
ALTER TABLE `sys_controllers`
  ADD CONSTRAINT `sys_controllers_ibfk_1` FOREIGN KEY (`module_id`) REFERENCES `sys_modules` (`id`);

--
-- Ограничения внешнего ключа таблицы `sys_permissions`
--
ALTER TABLE `sys_permissions`
  ADD CONSTRAINT `sys_permissions_ibfk_13` FOREIGN KEY (`role_id`) REFERENCES `sys_roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sys_permissions_ibfk_14` FOREIGN KEY (`module_id`) REFERENCES `sys_modules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sys_permissions_ibfk_15` FOREIGN KEY (`controller_id`) REFERENCES `sys_controllers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sys_permissions_ibfk_16` FOREIGN KEY (`action_id`) REFERENCES `sys_actions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `sys_roleslinks`
--
ALTER TABLE `sys_roleslinks`
  ADD CONSTRAINT `sys_roleslinks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `sys_users` (`id`),
  ADD CONSTRAINT `sys_roleslinks_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `sys_roles` (`id`);
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
