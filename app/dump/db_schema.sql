SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT=0;
START TRANSACTION;

DROP TABLE IF EXISTS `sys_actions`;
CREATE TABLE IF NOT EXISTS `sys_actions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `controller_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_actions_unique_action_in_controller` (`name`,`controller_id`),
  KEY `sys_actions_controller` (`controller_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `sys_controllers`;
CREATE TABLE IF NOT EXISTS `sys_controllers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `module_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_controllers_unique_controller_in_module` (`name`,`module_id`),
  KEY `sys_controllers_module` (`module_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `sys_modules`;
CREATE TABLE IF NOT EXISTS `sys_modules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_modules_unique_module` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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

DROP TABLE IF EXISTS `sys_roles`;
CREATE TABLE IF NOT EXISTS `sys_roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `deleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `sys_roleslinks`;
CREATE TABLE IF NOT EXISTS `sys_roleslinks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sys_roleslinks_unique_link` (`user_id`,`role_id`),
  KEY `sys_roleslinks_user` (`user_id`),
  KEY `sys_roleslinks_role` (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `sys_users`;
CREATE TABLE IF NOT EXISTS `sys_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` char(32) NOT NULL,
  `status` int(10) unsigned NOT NULL DEFAULT 1,
  `activated` int(10) unsigned NOT NULL DEFAULT 0,
  `verify_code` varchar(10) NULL DEFAULT NULL,
  `is_online` int(10) unsigned NOT NULL DEFAULT 0,
  `fb_id` varchar(255) NULL DEFAULT NULL,
  `tw_id` varchar(255) NULL DEFAULT NULL,
  `changed` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `is_real` int(10) unsigned NOT NULL DEFAULT '0',
  `is_vip` int(10) unsigned NOT NULL DEFAULT '0',
  `account_number` varchar(12) NOT NULL DEFAULT '',
  `amount` decimal(10,0) NOT NULL DEFAULT '0',
  `sms_amount` int(11) unsigned NOT NULL DEFAULT '0',
  `awards` varchar(255) NOT NULL DEFAULT '',

  `login` varchar(255) NOT NULL DEFAULT '',
  `avatar` varchar(255) NOT NULL DEFAULT '',
  `hometown` varchar(255) NOT NULL DEFAULT '',
  `gender` int(10) unsigned NOT NULL DEFAULT '0',
  `about` text NOT NULL DEFAULT '',
  `phone` varchar(50) NOT NULL DEFAULT '',
  `icq` varchar(50) NOT NULL DEFAULT '',
  `skype` varchar(100) NOT NULL DEFAULT '',
  `facebook` varchar(255) NOT NULL DEFAULT '',
  `twitter` varchar(255) NOT NULL DEFAULT '',
  `googleplus` varchar(255) NOT NULL DEFAULT '',
  `www` varchar(255) NOT NULL DEFAULT '',
  `current_rating` varchar(100) NOT NULL DEFAULT '',

  `deleted` timestamp NULL DEFAULT NULL,
  KEY `site_profile_user` (  `user_id` ),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `site_scratch_cards`;
CREATE TABLE IF NOT EXISTS `site_scratch_cards` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `number` varchar(50) NOT NULL,
  `amount` decimal(10,0) NOT NULL DEFAULT '0',
  `is_used` int(10) DEFAULT 0,
  `deleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `site_scratch_cards_number` (`number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `site_news`;
CREATE TABLE IF NOT EXISTS `site_news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  `activated` int(10) unsigned NOT NULL DEFAULT '0',
  `is_hot` int(10) unsigned NOT NULL DEFAULT '0',
  `public_date` datetime DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `keywords` text,
  `description` text,
  `short_content` text NOT NULL,
  `content` mediumtext NOT NULL,
  `view_count` BIGINT UNSIGNED NOT NULL DEFAULT '0',
  `rating` BIGINT NOT NULL DEFAULT '0',
  `deleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `site_news_user` (`user_id`),
  KEY `site_news_category_id` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
  `value` VARCHAR( 255 ) NOT NULL DEFAULT  ''
  `short_content` text NOT NULL,
  `content` mediumtext NOT NULL,
  `deleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `site_ads_user` (`user_id`),
  KEY `site_ads_category_id` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

DROP TABLE IF EXISTS `site_awards`;
CREATE TABLE IF NOT EXISTS `site_awards` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `status` int(10) NOT NULL DEFAULT '1',
  `logo` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `deleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

DROP TABLE IF EXISTS `site_category_tree`;
CREATE TABLE IF NOT EXISTS `site_category_tree` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `left_id` int(10) DEFAULT NULL,
  `right_id` int(10) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `is_active` int(10) unsigned DEFAULT '1',
  `show_on_home` int(10) unsigned DEFAULT '0',
  `order` int(10) unsigned NOT NULL DEFAULT '0',
  `deleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `site_statuses`;
CREATE TABLE IF NOT EXISTS `site_statuses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `value` text NOT NULL,
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `site_statuses_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `site_awardslinks`;
CREATE TABLE IF NOT EXISTS `site_awardslinks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `award_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `site_awardslinks_unique_link` (`profile_id`,`award_id`),
  KEY `site_awardslinks_profile` (`profile_id`),
  KEY `site_awardslinks_award` (`award_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `site_friendslinks`;
CREATE TABLE IF NOT EXISTS `site_friendslinks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `friend_id` int(10) unsigned NOT NULL,
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `site_friendslinks_unique_link` (`profile_id`,`friend_id`),
  KEY `site_friendslinks_profile` (`profile_id`),
  KEY `site_friendslinks_friend` (`friend_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `site_subscribes`;
CREATE TABLE IF NOT EXISTS `site_subscribes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `site_subscribes_unique_link` (`profile_id`,`category_id`),
  KEY `site_subscribes_profile` (`profile_id`),
  KEY `site_subscribes_award` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `site_bookmarks_category`;
CREATE TABLE IF NOT EXISTS `site_bookmarks_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `title` int(10) unsigned NOT NULL,
  `deleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `site_bookmarks_category_profile` (`profile_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `site_bookmarks`;
CREATE TABLE IF NOT EXISTS `site_bookmarks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `deleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `site_bookmarks_profile` (`profile_id`),
  KEY `site_bookmarks_category` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `site_favorites`;
CREATE TABLE IF NOT EXISTS `site_favorites` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `site_favorites_profile` (`profile_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `site_story`;
CREATE TABLE IF NOT EXISTS `site_story` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `site_story_profile` (`profile_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `site_content_tags`;
CREATE TABLE IF NOT EXISTS `site_content_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `type_id` int(10) NOT NULL DEFAULT '1',
  `logo` varchar(255) NOT NULL DEFAULT '',
  `description` TEXT NOT NULL DEFAULT  '',
  `is_paid` INT( 10 ) UNSIGNED NOT NULL DEFAULT  '0',
  `cnt` BIGINT UNSIGNED NOT NULL DEFAULT  '0',
  `deleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `site_content_tags_type` (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

DROP TABLE IF EXISTS `site_content_tagslinks`;
CREATE TABLE IF NOT EXISTS `site_content_tagslinks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tag_id` int(10) unsigned NOT NULL,
  `type_id` int(10) unsigned NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `site_content_tagslinks_tag` (`tag_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `site_tags_subscribes`;
CREATE TABLE IF NOT EXISTS `site_tags_subscribes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `tag_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `site_tags_subscribes_unique_link` (`profile_id`,`tag_id`),
  KEY `site_tags_subscribes_profile` (`profile_id`),
  KEY `site_tags_subscribes_tag` (`tag_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


////////////////////////
DROP TABLE IF EXISTS `site_ratings`;
CREATE TABLE IF NOT EXISTS `site_ratings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_id` int(10) unsigned NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `profile_id` int(10) unsigned NULL DEFAULT NULL,
  `ip` varchar(15) NULL DEFAULT NULL,
  `value` int(10) NOT NULL DEFAULT 0,
  `added` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `site_ratings_profile` (`profile_id`),
  KEY `site_ratings_item` (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE OR REPLACE VIEW `view_ratings` AS select `site_ratings`.* from `site_ratings` where isnull(`site_ratings`.`deleted`);

CREATE OR REPLACE VIEW `view_ratings_values` AS
select
	`view_ratings`.`id` as `id`,
	`view_ratings`.`item_id` as `item_id`,
	`view_ratings`.`type_id` as `type_id`,
	SUM(`view_ratings`.`value`) as rating
from
	`view_ratings`
group by `view_ratings`.`type_id`, `view_ratings`.`item_id`;
///////////////////////

DROP TABLE IF EXISTS `site_comments_tree`;
CREATE TABLE IF NOT EXISTS `site_comments_tree` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `left_id` int(10) DEFAULT NULL,
  `right_id` int(10) DEFAULT NULL,
  `profile_id` int(10) unsigned DEFAULT NULL,
  `type_id` int(10) unsigned DEFAULT NULL,
  `item_id` int(10) unsigned DEFAULT NULL,
  `comment` varchar(255) NOT NULL,
  `added` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_active` int(10) unsigned DEFAULT '1',
  `order` int(10) unsigned NOT NULL DEFAULT '0',
  `deleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `site_complains`;
CREATE TABLE IF NOT EXISTS `site_complains` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `type_id` int(10) unsigned NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `complain` TEXT NOT NULL DEFAULT '',
  `deleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `site_complains_type_item_profile` (`type_id`, `item_id`, `profile_id`),
  KEY `site_complains_profile_id` (`profile_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE OR REPLACE VIEW `view_category_tree` AS select `site_category_tree`.* from `site_category_tree` where isnull(`site_category_tree`.`deleted`);

CREATE OR REPLACE VIEW `view_users` AS select `sys_users`.* from `sys_users` where isnull(`sys_users`.`deleted`);

CREATE OR REPLACE VIEW `view_roles` AS select `sys_roles`.* from `sys_roles` where isnull(`sys_roles`.`deleted`);

CREATE OR REPLACE VIEW `view_permissions` AS
select	`permissions`.`role_id` AS `role_id`,
		`roles`.`name` AS `role_name`,
		`permissions`.`module_id` AS `module_id`,
		`modules`.`name` AS `module_name`,
		`permissions`.`controller_id` AS `controller_id`,
		`controllers`.`name` AS `controller_name`,
		`permissions`.`action_id` AS `action_id`,
		`actions`.`name` AS `action_name`
from ((((`sys_permissions` `permissions` join `sys_roles` `roles` on `roles`.`id` = `permissions`.`role_id`) join `sys_modules` `modules` on `modules`.`id` = `permissions`.`module_id`) join `sys_controllers` `controllers` on `controllers`.`id` = `permissions`.`controller_id`) join `sys_actions` `actions` on `actions`.`id` = `permissions`.`action_id`);

CREATE OR REPLACE VIEW `view_profiles` AS select `site_profiles`.* from `site_profiles` where isnull(`site_profiles`.`deleted`);

CREATE OR REPLACE VIEW `view_scratch_cards` AS select `site_scratch_cards`.* from `site_scratch_cards` where isnull(`site_scratch_cards`.`deleted`);

CREATE OR REPLACE VIEW `view_news` AS select `site_news`.* from `site_news` where isnull(`site_news`.`deleted`);

CREATE OR REPLACE VIEW `view_ads` AS select `site_ads`.* from `site_ads` where isnull(`site_ads`.`deleted`);

CREATE OR REPLACE VIEW `view_documents` AS select `site_documents`.* from `site_documents` where isnull(`site_documents`.`deleted`);

CREATE OR REPLACE VIEW `view_subscribes` AS select `site_subscribes`.* from `site_subscribes` where isnull(`site_subscribes`.`deleted`);

CREATE OR REPLACE VIEW `view_statuses` AS select `site_statuses`.* from `site_statuses` where isnull(`site_statuses`.`deleted`);

CREATE OR REPLACE VIEW `view_awards` AS select `site_awards`.* from `site_awards` where isnull(`site_awards`.`deleted`);

CREATE OR REPLACE VIEW `view_bookmarks` AS select `site_bookmarks`.* from `site_bookmarks` where isnull(`site_bookmarks`.`deleted`);

CREATE OR REPLACE VIEW `view_bookmarks_category` AS select `site_bookmarks_category`.* from `site_bookmarks_category` where isnull(`site_bookmarks_category`.`deleted`);

CREATE OR REPLACE VIEW `view_content_tags` AS select `site_content_tags`.* from `site_content_tags` where isnull(`site_content_tags`.`deleted`);

CREATE OR REPLACE VIEW `view_comments_tree` AS select `site_comments_tree`.* from `site_comments_tree` where isnull(`site_comments_tree`.`deleted`);

CREATE OR REPLACE VIEW `view_complains` AS select `site_complains`.* from `site_complains` where isnull(`site_complains`.`deleted`);

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

ALTER TABLE  `site_profiles`
  ADD CONSTRAINT `site_profiles_ibfk_1` FOREIGN KEY (  `user_id` ) REFERENCES  `sys_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE ;

ALTER TABLE `site_news`
  ADD CONSTRAINT `site_news_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `sys_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `site_ads`
  ADD CONSTRAINT `site_ads_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `sys_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE  `site_documents`
  ADD CONSTRAINT `site_documents_ibfk_1` FOREIGN KEY (  `user_id` ) REFERENCES  `sys_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE ;

ALTER TABLE  `site_documents`
  ADD CONSTRAINT `site_documents_ibfk_2` FOREIGN KEY (  `profile_id` ) REFERENCES  `site_profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE ;

ALTER TABLE `site_statuses`
  ADD CONSTRAINT `site_statuses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `sys_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE  `site_awardslinks`
    ADD FOREIGN KEY (  `profile_id` ) REFERENCES  `site_profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE ,
    ADD FOREIGN KEY (  `award_id` ) REFERENCES  `site_awards` (`id`) ON DELETE CASCADE ON UPDATE CASCADE ;

ALTER TABLE  `site_subscribes`
    ADD FOREIGN KEY (  `profile_id` ) REFERENCES  `site_profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE ,
    ADD FOREIGN KEY (  `category_id` ) REFERENCES  `site_category_tree` (`id`) ON DELETE CASCADE ON UPDATE CASCADE ;

ALTER TABLE  `site_friendslinks`
    ADD FOREIGN KEY (  `profile_id` ) REFERENCES  `site_profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE ,
    ADD FOREIGN KEY (  `friend_id` ) REFERENCES  `site_profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE ;

ALTER TABLE  `site_bookmarks_category`
    ADD FOREIGN KEY (  `profile_id` ) REFERENCES  `site_profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE ;

ALTER TABLE  `site_bookmarks`
    ADD FOREIGN KEY (  `profile_id` ) REFERENCES  `site_profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE ,
    ADD FOREIGN KEY (  `category_id` ) REFERENCES  `site_bookmarks_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE ;

ALTER TABLE  `site_favorites`
    ADD FOREIGN KEY (  `profile_id` ) REFERENCES  `site_profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE ;

ALTER TABLE  `site_story`
    ADD FOREIGN KEY (  `profile_id` ) REFERENCES  `site_profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE ;

ALTER TABLE  `site_content_tagslinks`
    ADD FOREIGN KEY (  `tag_id` ) REFERENCES  `site_content_tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE ;

ALTER TABLE  `site_tags_subscribes`
    ADD FOREIGN KEY (  `profile_id` ) REFERENCES  `site_profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE ,
    ADD FOREIGN KEY (  `tag_id` ) REFERENCES  `site_content_tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE ;

ALTER TABLE  `site_comments_tree`
    ADD FOREIGN KEY (  `profile_id` ) REFERENCES  `site_profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE ;

ALTER TABLE  `site_complains`
    ADD FOREIGN KEY (  `profile_id` ) REFERENCES  `site_profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE ;

INSERT INTO `site_settings` (`id`, `name`, `value`, `description`, `type`, `position`) VALUES
(1, 'site_title', 'Сфера', 'Заголовок сайта', 'text', 1),
(2, 'site_title_separator', ' - ', 'Разделитель заголовка', 'text', 2),
(3, 'site_keywords', '', 'Ключевые слова сайта', 'textarea', 3),
(4, 'site_descriptions', '', 'Описание сайта', 'textarea', 4);


INSERT INTO `sys_roles` (`id`, `name`, `deleted`) VALUES
(1, 'admin', NULL),
(2, 'guest', NULL);

SET FOREIGN_KEY_CHECKS=1;
COMMIT;