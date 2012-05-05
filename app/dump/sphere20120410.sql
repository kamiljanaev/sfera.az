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
(1, 1, 11, 1, 0, '2020-02-20 00:00:00', '2026-02-20 00:00:00', '', 'Требуется уборщик', '1500', 'уборщик', 'требуется уборщик територии', NULL),
(2, 6, 11, 1, 1, '2012-02-20 00:00:00', '2022-02-20 00:00:00', '/upload/untitled_hdr2.jpg', 'Требуется водитель', '2000', 'краткое', 'требуется водитель с личным авто', NULL),
(3, 1, 12, 1, 1, '2026-02-20 00:00:00', '2029-02-20 00:00:00', '/upload/preview.jpg', 'Вилла', '$ 70000', 'краткое поисание', 'пробается вилла', NULL),
(4, 1, 12, 1, 1, '2026-02-20 00:00:00', '2029-02-20 00:00:00', '/upload/kvartiry-v-astane_29_04.jpg', 'Квартира', '$ 50 000', 'описание краткое', 'продается квартира', NULL);

DROP TABLE IF EXISTS `site_awards`;
CREATE TABLE IF NOT EXISTS `site_awards` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `status` int(10) NOT NULL DEFAULT '1',
  `logo` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `deleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

INSERT INTO `site_awards` (`id`, `title`, `status`, `logo`, `alias`, `deleted`) VALUES
(1, 'За храбрость', 1, '/upload/award01.png', '', NULL),
(2, 'За смелость', 1, '/upload/award02.png', '', NULL),
(3, 'За мужество', 1, '/upload/award03.png', '', NULL),
(4, 'Просто так', 1, '/upload/award04.png', '', NULL),
(5, 'За отвагу', 1, '/upload/award05.png', '', NULL);

DROP TABLE IF EXISTS `site_awardslinks`;
CREATE TABLE IF NOT EXISTS `site_awardslinks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `award_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `site_awardslinks_unique_link` (`profile_id`,`award_id`),
  KEY `site_awardslinks_profile` (`profile_id`),
  KEY `site_awardslinks_award` (`award_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

INSERT INTO `site_awardslinks` (`id`, `profile_id`, `award_id`) VALUES
(18, 2, 4),
(16, 5, 3),
(17, 5, 4);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

INSERT INTO `site_bookmarks` (`id`, `profile_id`, `category_id`, `title`, `url`, `deleted`) VALUES
(1, 1, 1, 'Google', 'http://google.com', NULL),
(2, 1, 2, 'контакт', 'http://vk.com', NULL),
(3, 1, 3, 'Facebook', 'http://facebook.com', NULL),
(4, 1, 3, 'Youtube', 'http://youtube.com', '2012-03-18 02:37:14'),
(5, 1, 3, 'Yandex', 'http://ya.ru', NULL);

DROP TABLE IF EXISTS `site_bookmarks_category`;
CREATE TABLE IF NOT EXISTS `site_bookmarks_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `deleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `site_bookmarks_category_profile` (`profile_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

INSERT INTO `site_bookmarks_category` (`id`, `profile_id`, `title`, `deleted`) VALUES
(1, 1, 'Первая категория11', NULL),
(2, 1, 'Вторая категория', '2012-03-18 02:35:48'),
(3, 1, 'Третья категория', NULL),
(4, 1, 'kjshdfjasdhfgasdf!!!!!!', NULL),
(5, 1, 'ertwertwert', '2012-03-27 21:13:41');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

INSERT INTO `site_category_tree` (`id`, `parent_id`, `left_id`, `right_id`, `title`, `alias`, `is_active`, `show_on_home`, `order`, `readonly`, `deleted`) VALUES
(1, NULL, 1, 14, 'Новости', 'news-root-category', 1, 0, 1, 1, NULL),
(2, NULL, 15, 20, 'Объявления', 'ads-root-catgory', 1, 0, 1, 1, NULL),
(4, 1, 4, 5, 'werwerwer', '', 1, 0, 0, 0, '2012-02-19 23:24:01'),
(5, 2, 8, 9, 'sdfsdfsdf', '', 1, 0, 0, 0, '2012-02-19 23:23:56'),
(6, 1, 2, 3, 'sssssssss', '', 1, 0, 0, 0, '2012-02-19 23:24:04'),
(7, 1, 2, 3, 'sdfsdf', '', 1, 0, 0, 0, '2012-02-19 23:32:09'),
(8, 1, 4, 7, 'World', 'news-world-category', 1, 1, 1, 0, NULL),
(9, 1, 10, 11, 'Auto', 'news-auto-category', 1, 1, 4, 0, NULL),
(10, 1, 12, 13, 'Politics', 'news-politics-category', 1, 0, 5, 0, NULL),
(11, 2, 16, 17, 'Вакансии', 'ads-vacancy-category', 1, 0, 0, 0, NULL),
(12, 2, 18, 19, 'Недвижимость', 'ads-realty-category', 1, 0, 0, 0, NULL),
(13, 1, 8, 9, 'Baku', 'news-baku-category', 1, 1, 3, 0, NULL),
(14, 1, 2, 3, 'Ajerbaijan', 'news-ajerbaijan-category', 1, 1, 0, 0, NULL),
(15, 8, 5, 6, 'sub world', 'news-sub-world-category', 1, 0, 0, 0, NULL);

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
  `is_active` int(10) unsigned DEFAULT '1',
  `order` int(10) unsigned NOT NULL DEFAULT '0',
  `deleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `profile_id` (`profile_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=34 ;

INSERT INTO `site_comments_tree` (`id`, `parent_id`, `left_id`, `right_id`, `profile_id`, `type_id`, `item_id`, `comment`, `is_active`, `order`, `deleted`) VALUES
(1, NULL, 1, 6, 1, 1, 27, 'sdfsdfsdfsdf\r\nsdfgsdfgdfg', 1, 0, NULL),
(2, 1, 2, 5, 1, 1, 27, 'blabla', 1, 0, NULL),
(3, 2, 3, 4, 1, 1, 27, 'blabla33333', 1, 0, NULL),
(4, NULL, 7, 8, 1, 1, 27, 'sdfsdfsdfsdf', 1, 0, NULL),
(5, NULL, 9, 26, 1, 1, 27, 'sdfsdfsdf', 1, 0, NULL),
(6, 5, 10, 25, 1, 1, 27, 'asdfasdf', 1, 0, NULL),
(7, 6, 11, 12, 1, 1, 27, 'vvvvvvvvv', 1, 0, NULL),
(8, 6, 13, 14, 1, 1, 27, 'vvvvvvvvv', 1, 0, NULL),
(9, 6, 15, 16, 1, 1, 27, 'vvvvvvvvv', 1, 0, NULL),
(10, 6, 17, 18, 1, 1, 27, 'vvvvvvvvv', 1, 0, NULL),
(11, 6, 19, 20, 1, 1, 27, 'vvvvvvvvv', 1, 0, NULL),
(12, 6, 21, 22, 1, 1, 27, 'vvvvvvvvv', 1, 0, NULL),
(13, 6, 23, 24, 1, 1, 27, 'vvvvvvvvv', 1, 0, NULL),
(14, NULL, 27, 32, 1, 1, 26, 'sdsdfgdfgsdfgdfg', 1, 0, NULL),
(15, NULL, 33, 46, 1, 1, 26, 'cvbxcvbxcvbxcvb', 1, 0, NULL),
(16, 14, 28, 29, 1, 1, 26, 'sadfasdfasdfxcvzxcvzx', 1, 0, NULL),
(17, 14, 30, 31, 1, 1, 26, 'sadfasdfasdfxcvzxcvzx', 1, 0, NULL),
(18, 19, 37, 38, 1, 1, 26, 'asdfasdfasdf', 1, 0, NULL),
(19, 15, 36, 39, 1, 1, 26, 'sdfgsdfgsdfgsdfg', 1, 0, NULL),
(20, 15, 40, 41, 1, 1, 26, 'uuuuuuuuuuuuuuuu', 1, 0, NULL),
(21, 15, 42, 43, 1, 1, 26, 'uuuuuuuuuuuuuuuu', 1, 0, NULL),
(22, 15, 44, 45, 1, 1, 26, 'uuuuuuuuuuuuuuuu', 1, 0, NULL),
(23, 15, 34, 35, 2, 1, 26, 'asdfasdfasdfasdf', 1, 0, NULL),
(24, 0, 47, 48, 2, 1, 26, 'sfsdfsdfsxcvxcvxcv', 1, 0, NULL),
(25, 0, 49, 50, 2, 1, 26, 'sdfsfsdxxxxxx', 1, 0, NULL),
(26, NULL, 51, 52, 2, 1, 26, 'ttttttt', 1, 0, NULL),
(27, 0, 53, 54, 1, 1, 26, 'sssssssdf', 1, 0, NULL),
(28, 0, 55, 56, 1, 1, 26, 'zzzzzzzzzzzzzzzzzzz', 1, 0, NULL),
(29, NULL, 57, 60, 1, 1, 26, 'tertert', 1, 0, NULL),
(30, NULL, 61, 62, 1, 1, 26, 'sdfgsdfgsdg', 1, 0, NULL),
(31, 0, 63, 66, 1, 1, 26, 'oooooooo', 1, 0, NULL),
(32, 31, 64, 65, 1, 1, 26, 'hhhhhhh', 1, 0, NULL),
(33, 29, 58, 59, 1, 1, 26, 'bbbbbb', 1, 0, NULL);

DROP TABLE IF EXISTS `site_content_tags`;
CREATE TABLE IF NOT EXISTS `site_content_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `type_id` int(10) NOT NULL DEFAULT '1',
  `logo` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `is_paid` int(10) unsigned NOT NULL DEFAULT '0',
  `cnt` bigint(20) unsigned NOT NULL DEFAULT '0',
  `deleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `site_content_tags_type` (`type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

INSERT INTO `site_content_tags` (`id`, `title`, `type_id`, `logo`, `description`, `is_paid`, `cnt`, `deleted`) VALUES
(1, 'тег 11111', 1, '/upload/1328134050x_7de48d00.jpg', 'тестовый тег для новостей. Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 1, 5, NULL),
(2, 'тег 2', 1, '/upload/1328284167430740_156523421129916_100003167780590_211575_789600414_n.jpg', '', 0, 2, NULL);

DROP TABLE IF EXISTS `site_content_tagslinks`;
CREATE TABLE IF NOT EXISTS `site_content_tagslinks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tag_id` int(10) unsigned NOT NULL,
  `type_id` int(10) unsigned NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `site_content_tagslinks_tag` (`tag_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

INSERT INTO `site_content_tagslinks` (`id`, `tag_id`, `type_id`, `item_id`) VALUES
(3, 1, 1, 32),
(4, 2, 1, 32),
(16, 1, 1, 31),
(17, 2, 1, 31),
(18, 1, 1, 2),
(19, 1, 1, 26),
(20, 1, 1, 23);

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

DROP TABLE IF EXISTS `site_favorites`;
CREATE TABLE IF NOT EXISTS `site_favorites` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `site_favorites_profile` (`profile_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=47 ;

INSERT INTO `site_favorites` (`id`, `profile_id`, `type`, `item_id`) VALUES
(43, 1, 1, 28),
(44, 1, 1, 26),
(45, 1, 1, 27),
(46, 1, 1, 25);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `site_friendslinks` (`id`, `profile_id`, `friend_id`, `added`) VALUES
(1, 1, 2, '2012-03-12 23:38:46');

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
  `view_count` bigint(20) unsigned NOT NULL DEFAULT '0',
  `rating` bigint(20) NOT NULL DEFAULT '0',
  `deleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `site_news_user` (`user_id`),
  KEY `site_news_category_id` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=38 ;

INSERT INTO `site_news` (`id`, `user_id`, `category_id`, `activated`, `is_hot`, `public_date`, `image`, `title`, `keywords`, `description`, `short_content`, `content`, `view_count`, `rating`, `deleted`) VALUES
(1, 2, 0, 1, 0, '2012-02-01 00:00:00', '', 'Первая новость!!!', '', '', 'бла!!!', 'блаблабла', 0, 0, '2012-02-01 21:41:09'),
(2, 1, 9, 1, 1, '2012-01-31 00:00:00', '/upload/top-posts-04.jpg', 'Lorem', '', '', 'Sed blandit, augue sit amet vulputate bibendum, massa est vulputate ipsum, sit amet ultrices diam enim a felis. In quam elit, pretium nec posuere ut, aliquam sed odio.', '<p>Quisque posuere, justo in porttitor rhoncus, mi purus semper nulla, nec sollicitudin massa risus quis mauris. Quisque nec tortor sit amet magna rutrum laoreet. Aliquam condimentum nisi eu ipsum hendrerit ultrices. Sed blandit, augue sit amet vulputate bibendum, massa est vulputate ipsum, sit amet ultrices diam enim a felis. In quam elit, pretium nec posuere ut, aliquam sed odio. Fusce nec fringilla ligula. Suspendisse imperdiet ligula nec erat pretium et bibendum lacus auctor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Mauris justo nisi, pulvinar non iaculis id, euismod eu massa. Integer nisi felis, euismod sit amet sagittis in, consequat ut neque. Nam ut tortor nisl. Nullam nec odio in metus mollis pharetra vitae at libero. Nunc sodales elit velit, sit amet ultricies dolor. Proin et sapien nisl, id egestas turpis. Nulla eget eros a enim aliquet consequat. Phasellus cursus ligula quis risus varius aliquet ultrices lorem semper. Nullam malesuada lectus at leo placerat non interdum dui interdum. Vivamus vitae ultrices tellus. Cras auctor sagittis lorem, ut bibendum metus sodales in. Vestibulum at urna enim, non condimentum magna. Praesent rhoncus ullamcorper volutpat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla scelerisque, arcu ut sagittis commodo, orci nunc facilisis tellus, convallis venenatis diam sapien sit amet dui. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur mollis dignissim velit nec molestie. Nulla facilisi. Quisque nisi est, adipiscing in condimentum eu, laoreet vel ipsum. Duis accumsan, ipsum sed euismod gravida, elit lorem porttitor lacus, in porttitor turpis magna nec dolor. Curabitur molestie sollicitudin cursus. Sed congue felis eget libero pellentesque varius. Pellentesque a lacus eget est malesuada laoreet. Vestibulum tincidunt ornare euismod.</p>', 3, 0, NULL),
(3, 1, 9, 1, 1, '2012-01-20 00:00:00', '/upload/top-posts-05.jpg', 'Lipsum', '', '', 'Sed blandit, augue sit amet vulputate bibendum, massa est vulputate ipsum, sit amet ultrices diam enim a felis. In quam elit, pretium nec posuere ut, aliquam sed odio.', 'Quisque posuere, justo in porttitor rhoncus, mi purus semper nulla, nec sollicitudin massa risus quis mauris. Quisque nec tortor sit amet magna rutrum laoreet. Aliquam condimentum nisi eu ipsum hendrerit ultrices. Sed blandit, augue sit amet vulputate bibendum, massa est vulputate ipsum, sit amet ultrices diam enim a felis. In quam elit, pretium nec posuere ut, aliquam sed odio. Fusce nec fringilla ligula. Suspendisse imperdiet ligula nec erat pretium et bibendum lacus auctor.\r\n\r\nVestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Mauris justo nisi, pulvinar non iaculis id, euismod eu massa. Integer nisi felis, euismod sit amet sagittis in, consequat ut neque. Nam ut tortor nisl. Nullam nec odio in metus mollis pharetra vitae at libero. Nunc sodales elit velit, sit amet ultricies dolor. Proin et sapien nisl, id egestas turpis. Nulla eget eros a enim aliquet consequat. Phasellus cursus ligula quis risus varius aliquet ultrices lorem semper. Nullam malesuada lectus at leo placerat non interdum dui interdum. Vivamus vitae ultrices tellus.\r\n\r\nCras auctor sagittis lorem, ut bibendum metus sodales in. Vestibulum at urna enim, non condimentum magna. Praesent rhoncus ullamcorper volutpat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla scelerisque, arcu ut sagittis commodo, orci nunc facilisis tellus, convallis venenatis diam sapien sit amet dui. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur mollis dignissim velit nec molestie. Nulla facilisi. Quisque nisi est, adipiscing in condimentum eu, laoreet vel ipsum. Duis accumsan, ipsum sed euismod gravida, elit lorem porttitor lacus, in porttitor turpis magna nec dolor. Curabitur molestie sollicitudin cursus. Sed congue felis eget libero pellentesque varius. Pellentesque a lacus eget est malesuada laoreet. Vestibulum tincidunt ornare euismod.', 0, 0, NULL),
(4, 1, 0, 0, 0, '2012-01-20 00:00:00', '', 'вапвап', '', '', 'вапва', 'пвапвап', 0, 0, '2012-02-01 22:06:07'),
(5, 1, 10, 1, 0, '2012-01-20 00:00:00', '/upload/news-post-image.jpg', 'Ipsum', '', '', 'Praesent rhoncus ullamcorper volutpat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla scelerisque, arcu ut sagittis commodo, orci nunc facilisis tellus, convallis venenatis diam sapien sit amet dui. Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'Quisque posuere, justo in porttitor rhoncus, mi purus semper nulla, nec sollicitudin massa risus quis mauris. Quisque nec tortor sit amet magna rutrum laoreet. Aliquam condimentum nisi eu ipsum hendrerit ultrices. Sed blandit, augue sit amet vulputate bibendum, massa est vulputate ipsum, sit amet ultrices diam enim a felis. In quam elit, pretium nec posuere ut, aliquam sed odio. Fusce nec fringilla ligula. Suspendisse imperdiet ligula nec erat pretium et bibendum lacus auctor.\r\n\r\nVestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Mauris justo nisi, pulvinar non iaculis id, euismod eu massa. Integer nisi felis, euismod sit amet sagittis in, consequat ut neque. Nam ut tortor nisl. Nullam nec odio in metus mollis pharetra vitae at libero. Nunc sodales elit velit, sit amet ultricies dolor. Proin et sapien nisl, id egestas turpis. Nulla eget eros a enim aliquet consequat. Phasellus cursus ligula quis risus varius aliquet ultrices lorem semper. Nullam malesuada lectus at leo placerat non interdum dui interdum. Vivamus vitae ultrices tellus.\r\n\r\nCras auctor sagittis lorem, ut bibendum metus sodales in. Vestibulum at urna enim, non condimentum magna. Praesent rhoncus ullamcorper volutpat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla scelerisque, arcu ut sagittis commodo, orci nunc facilisis tellus, convallis venenatis diam sapien sit amet dui. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur mollis dignissim velit nec molestie. Nulla facilisi. Quisque nisi est, adipiscing in condimentum eu, laoreet vel ipsum. Duis accumsan, ipsum sed euismod gravida, elit lorem porttitor lacus, in porttitor turpis magna nec dolor. Curabitur molestie sollicitudin cursus. Sed congue felis eget libero pellentesque varius. Pellentesque a lacus eget est malesuada laoreet. Vestibulum tincidunt ornare euismod.', 2, 1, NULL),
(6, 6, 0, 0, 0, '2001-02-20 00:00:00', '', 'новость бля', NULL, NULL, 'фывфыв', 'фывфывфывфывфыв', 0, 0, '2012-02-01 22:06:09'),
(7, 1, 0, 0, 0, '2001-02-20 00:00:00', '', 'qeqweqwe', '', '', 'qweqw', 'eqweqwe', 0, 0, '2012-02-01 21:53:16'),
(8, 6, 0, 0, 0, '2001-02-20 00:00:00', '', 'новость бля!!!', NULL, NULL, 'фывфыв', 'фывфывфывфывфыв', 0, 0, '2012-02-01 21:53:13'),
(9, 6, 0, 0, 0, '2012-02-01 00:00:00', '', '!!!!новость бля!!!', NULL, NULL, 'фывфыв', 'фывфывфывфывфыв', 0, 0, '2012-02-01 21:41:20'),
(10, 6, 0, 0, 0, '2012-02-01 00:00:00', '', '!!!!новость бля!!!', NULL, NULL, 'фывфыв', 'фывфывфывфывфыв', 0, 0, '2012-02-01 21:41:22'),
(11, 6, 0, 0, 0, '2012-02-01 00:00:00', '', '!!!!новость бля!!!', NULL, NULL, 'фывфыв', 'фывфывфывфывфыв', 0, 0, '2012-02-01 21:52:58'),
(12, 6, 0, 0, 0, '2012-02-01 00:00:00', '', '!!!!новость бля!!!', NULL, NULL, 'фывфыв', 'фывфывфывфывфыв', 0, 0, '2012-02-01 21:53:00'),
(13, 6, 0, 0, 0, '2012-02-01 00:00:00', '', '!!!!новость бля!!!', NULL, NULL, 'фывфыв', 'фывфывфывфывфыв', 0, 0, '2012-02-01 21:53:09'),
(14, 6, 0, 0, 0, '2012-02-01 00:00:00', '', 'фы', NULL, NULL, 'фывфыв', 'фывфывфывфывфыв', 0, 0, '2012-02-01 21:41:25'),
(15, 6, 0, 0, 0, '2012-02-01 00:00:00', '', 'фы', NULL, NULL, 'фывфыв', 'фывфывфывфывфыв', 0, 0, '2012-02-01 21:41:35'),
(16, 6, 0, 0, 0, '2012-02-01 00:00:00', '', '333333333', NULL, NULL, 'фывфыв', 'фывфывфывфывфыв', 0, 0, '2012-02-01 21:53:11'),
(17, 6, 0, 0, 0, '2012-02-01 00:00:00', '', 'Добавленая новость', NULL, NULL, 'фывфыв', 'фывфыв', 0, 0, '2012-02-01 21:41:32'),
(18, 6, 0, 0, 0, '2012-02-01 00:00:00', '', 'ываываыва', NULL, NULL, 'ыва', 'ываыва', 0, 0, '2012-02-01 22:06:03'),
(19, 6, 0, 0, 0, '2012-02-01 00:00:00', '', 'ываываыва', NULL, NULL, 'ыва', 'ываыва', 0, 0, '2012-02-01 21:41:30'),
(20, 6, 0, 0, 0, '2012-02-01 00:00:00', '/upload/r_46a22552.jpg', 'ываываыва', NULL, NULL, 'ыва', 'ываыва', 0, 0, '2012-02-01 21:53:03'),
(21, 6, 0, 0, 0, '2012-02-01 00:00:00', '/upload/фото21.jpg', 'ываываыва', NULL, NULL, 'ыва', 'ываыва', 0, 0, '2012-02-01 22:06:05'),
(22, 6, 0, 0, 0, '2012-02-02 00:00:00', '', 'ываываыва', NULL, NULL, 'ыва', 'ываыва', 0, 0, '2012-02-01 22:06:01'),
(23, 6, 10, 1, 1, '2012-02-02 00:00:00', '/upload/top-posts-01.jpg', 'Lorem ipsum', '', '', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec lectus orci, iaculis nec placerat nec, facilisis sed nisi. Pellentesque consequat est vitae neque dignissim at laoreet ante fringilla. Vestibulum condimentum ipsum eget velit consectetur vel varius sapien accumsan.', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec lectus orci, iaculis nec placerat nec, facilisis sed nisi. Pellentesque consequat est vitae neque dignissim at laoreet ante fringilla. Vestibulum condimentum ipsum eget velit consectetur vel varius sapien accumsan. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec at justo eu odio scelerisque pretium. Phasellus sed felis ac urna scelerisque rhoncus. Integer dolor nisi, cursus eu congue nec, pulvinar pellentesque sem. Duis varius luctus vestibulum. Nunc quis scelerisque ante. In hac habitasse platea dictumst. Nulla lobortis venenatis mollis. Donec in erat eget dui tempus pretium. Morbi purus nulla, pretium vel molestie vitae, laoreet ut velit. Quisque posuere, justo in porttitor rhoncus, mi purus semper nulla, nec sollicitudin massa risus quis mauris. Quisque nec tortor sit amet magna rutrum laoreet. Aliquam condimentum nisi eu ipsum hendrerit ultrices. Sed blandit, augue sit amet vulputate bibendum, massa est vulputate ipsum, sit amet ultrices diam enim a felis. In quam elit, pretium nec posuere ut, aliquam sed odio. Fusce nec fringilla ligula. Suspendisse imperdiet ligula nec erat pretium et bibendum lacus auctor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Mauris justo nisi, pulvinar non iaculis id, euismod eu massa. Integer nisi felis, euismod sit amet sagittis in, consequat ut neque. Nam ut tortor nisl. Nullam nec odio in metus mollis pharetra vitae at libero. Nunc sodales elit velit, sit amet ultricies dolor. Proin et sapien nisl, id egestas turpis. Nulla eget eros a enim aliquet consequat. Phasellus cursus ligula quis risus varius aliquet ultrices lorem semper. Nullam malesuada lectus at leo placerat non interdum dui interdum. Vivamus vitae ultrices tellus.</p>', 6, -1, NULL),
(24, 6, 10, 1, 1, '2012-02-02 00:00:00', '/upload/top-posts-02.jpg', 'Lorem ipsum', '', '', 'Quisque posuere, justo in porttitor rhoncus, mi purus semper nulla, nec sollicitudin massa risus quis mauris. Quisque nec tortor sit amet magna rutrum laoreet.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec lectus orci, iaculis nec placerat nec, facilisis sed nisi. Pellentesque consequat est vitae neque dignissim at laoreet ante fringilla. Vestibulum condimentum ipsum eget velit consectetur vel varius sapien accumsan. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec at justo eu odio scelerisque pretium. Phasellus sed felis ac urna scelerisque rhoncus. Integer dolor nisi, cursus eu congue nec, pulvinar pellentesque sem. Duis varius luctus vestibulum. Nunc quis scelerisque ante. In hac habitasse platea dictumst. Nulla lobortis venenatis mollis. Donec in erat eget dui tempus pretium. Morbi purus nulla, pretium vel molestie vitae, laoreet ut velit.\r\n\r\nQuisque posuere, justo in porttitor rhoncus, mi purus semper nulla, nec sollicitudin massa risus quis mauris. Quisque nec tortor sit amet magna rutrum laoreet. Aliquam condimentum nisi eu ipsum hendrerit ultrices. Sed blandit, augue sit amet vulputate bibendum, massa est vulputate ipsum, sit amet ultrices diam enim a felis. In quam elit, pretium nec posuere ut, aliquam sed odio. Fusce nec fringilla ligula. Suspendisse imperdiet ligula nec erat pretium et bibendum lacus auctor.\r\n\r\nVestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Mauris justo nisi, pulvinar non iaculis id, euismod eu massa. Integer nisi felis, euismod sit amet sagittis in, consequat ut neque. Nam ut tortor nisl. Nullam nec odio in metus mollis pharetra vitae at libero. Nunc sodales elit velit, sit amet ultricies dolor. Proin et sapien nisl, id egestas turpis. Nulla eget eros a enim aliquet consequat. Phasellus cursus ligula quis risus varius aliquet ultrices lorem semper. Nullam malesuada lectus at leo placerat non interdum dui interdum. Vivamus vitae ultrices tellus.', 1, 1, NULL),
(25, 6, 8, 1, 1, '2012-02-02 00:00:00', '/upload/top-posts-03.jpg', 'Lorem ipsum', '', '', 'Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Mauris justo nisi, pulvinar non iaculis id, euismod eu massa. Integer nisi felis, euismod sit amet sagittis in, consequat ut neque.', 'Quisque posuere, justo in porttitor rhoncus, mi purus semper nulla, nec sollicitudin massa risus quis mauris. Quisque nec tortor sit amet magna rutrum laoreet. Aliquam condimentum nisi eu ipsum hendrerit ultrices. Sed blandit, augue sit amet vulputate bibendum, massa est vulputate ipsum, sit amet ultrices diam enim a felis. In quam elit, pretium nec posuere ut, aliquam sed odio. Fusce nec fringilla ligula. Suspendisse imperdiet ligula nec erat pretium et bibendum lacus auctor.\r\n\r\nVestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Mauris justo nisi, pulvinar non iaculis id, euismod eu massa. Integer nisi felis, euismod sit amet sagittis in, consequat ut neque. Nam ut tortor nisl. Nullam nec odio in metus mollis pharetra vitae at libero. Nunc sodales elit velit, sit amet ultricies dolor. Proin et sapien nisl, id egestas turpis. Nulla eget eros a enim aliquet consequat. Phasellus cursus ligula quis risus varius aliquet ultrices lorem semper. Nullam malesuada lectus at leo placerat non interdum dui interdum. Vivamus vitae ultrices tellus.\r\n\r\nCras auctor sagittis lorem, ut bibendum metus sodales in. Vestibulum at urna enim, non condimentum magna. Praesent rhoncus ullamcorper volutpat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla scelerisque, arcu ut sagittis commodo, orci nunc facilisis tellus, convallis venenatis diam sapien sit amet dui. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur mollis dignissim velit nec molestie. Nulla facilisi. Quisque nisi est, adipiscing in condimentum eu, laoreet vel ipsum. Duis accumsan, ipsum sed euismod gravida, elit lorem porttitor lacus, in porttitor turpis magna nec dolor. Curabitur molestie sollicitudin cursus. Sed congue felis eget libero pellentesque varius. Pellentesque a lacus eget est malesuada laoreet. Vestibulum tincidunt ornare euismod.', 0, 1, NULL),
(26, 6, 8, 1, 0, '2012-02-03 00:00:00', '/upload/stars-slide.jpg', 'Star', '', '', 'Praesent rhoncus ullamcorper volutpat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla scelerisque, arcu ut sagittis commodo, orci nunc facilisis tellus, convallis venenatis diam sapien sit amet dui. Lorem ipsum dolor sit amet, consectetur adipiscing elit.', '<p>Quisque posuere, justo in porttitor rhoncus, mi purus semper nulla, nec sollicitudin massa risus quis mauris. Quisque nec tortor sit amet magna rutrum laoreet. Aliquam condimentum nisi eu ipsum hendrerit ultrices. Sed blandit, augue sit amet vulputate bibendum, massa est vulputate ipsum, sit amet ultrices diam enim a felis. In quam elit, pretium nec posuere ut, aliquam sed odio. Fusce nec fringilla ligula. Suspendisse imperdiet ligula nec erat pretium et bibendum lacus auctor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Mauris justo nisi, pulvinar non iaculis id, euismod eu massa. Integer nisi felis, euismod sit amet sagittis in, consequat ut neque. Nam ut tortor nisl. Nullam nec odio in metus mollis pharetra vitae at libero. Nunc sodales elit velit, sit amet ultricies dolor. Proin et sapien nisl, id egestas turpis. Nulla eget eros a enim aliquet consequat. Phasellus cursus ligula quis risus varius aliquet ultrices lorem semper. Nullam malesuada lectus at leo placerat non interdum dui interdum. Vivamus vitae ultrices tellus. Cras auctor sagittis lorem, ut bibendum metus sodales in. Vestibulum at urna enim, non condimentum magna. Praesent rhoncus ullamcorper volutpat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla scelerisque, arcu ut sagittis commodo, orci nunc facilisis tellus, convallis venenatis diam sapien sit amet dui. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur mollis dignissim velit nec molestie. Nulla facilisi. Quisque nisi est, adipiscing in condimentum eu, laoreet vel ipsum. Duis accumsan, ipsum sed euismod gravida, elit lorem porttitor lacus, in porttitor turpis magna nec dolor. Curabitur molestie sollicitudin cursus. <img src="../../../../../../upload/stars-slide.jpg" alt="" />Sed congue felis eget libero pellentesque varius. Pellentesque a lacus eget est malesuada laoreet. Vestibulum tincidunt ornare euismod.</p>', 50, 0, NULL),
(27, 1, 14, 1, 0, '2011-12-20 00:00:00', '/upload/preview.jpg', 'Holidays Around The World: Smashing Photo Contest', '', '', '2012 is around the corner and the year is (already!) coming to an end. We''d like to know how upcoming holidays are celebrated in your city. Just send us a photo of your kind of Christmas or New Year''s Eve.', 'Quisque posuere, justo in porttitor rhoncus, mi purus semper nulla, nec sollicitudin massa risus quis mauris. Quisque nec tortor sit amet magna rutrum laoreet. Aliquam condimentum nisi eu ipsum hendrerit ultrices. Sed blandit, augue sit amet vulputate bibendum, massa est vulputate ipsum, sit amet ultrices diam enim a felis. In quam elit, pretium nec posuere ut, aliquam sed odio. Fusce nec fringilla ligula. Suspendisse imperdiet ligula nec erat pretium et bibendum lacus auctor.\r\n\r\nVestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Mauris justo nisi, pulvinar non iaculis id, euismod eu massa. Integer nisi felis, euismod sit amet sagittis in, consequat ut neque. Nam ut tortor nisl. Nullam nec odio in metus mollis pharetra vitae at libero. Nunc sodales elit velit, sit amet ultricies dolor. Proin et sapien nisl, id egestas turpis. Nulla eget eros a enim aliquet consequat. Phasellus cursus ligula quis risus varius aliquet ultrices lorem semper. Nullam malesuada lectus at leo placerat non interdum dui interdum. Vivamus vitae ultrices tellus.\r\n\r\nCras auctor sagittis lorem, ut bibendum metus sodales in. Vestibulum at urna enim, non condimentum magna. Praesent rhoncus ullamcorper volutpat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla scelerisque, arcu ut sagittis commodo, orci nunc facilisis tellus, convallis venenatis diam sapien sit amet dui. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur mollis dignissim velit nec molestie. Nulla facilisi. Quisque nisi est, adipiscing in condimentum eu, laoreet vel ipsum. Duis accumsan, ipsum sed euismod gravida, elit lorem porttitor lacus, in porttitor turpis magna nec dolor. Curabitur molestie sollicitudin cursus. Sed congue felis eget libero pellentesque varius. Pellentesque a lacus eget est malesuada laoreet. Vestibulum tincidunt ornare euismod.', 30, 0, NULL),
(28, 1, 14, 1, 0, '2000-12-16 00:00:00', '/upload/kvartiry-v-astane_29_04.jpg', 'Bla blabla', '', '', 'Quisque nisi est, adipiscing in condimentum eu, laoreet vel ipsum. Duis accumsan, ipsum sed euismod gravida, elit lorem porttitor lacus, in porttitor turpis magna nec dolor. Curabitur molestie sollicitudin cursus.', 'Quisque posuere, justo in porttitor rhoncus, mi purus semper nulla, nec sollicitudin massa risus quis mauris. Quisque nec tortor sit amet magna rutrum laoreet. Aliquam condimentum nisi eu ipsum hendrerit ultrices. Sed blandit, augue sit amet vulputate bibendum, massa est vulputate ipsum, sit amet ultrices diam enim a felis. In quam elit, pretium nec posuere ut, aliquam sed odio. Fusce nec fringilla ligula. Suspendisse imperdiet ligula nec erat pretium et bibendum lacus auctor.\r\n\r\nVestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Mauris justo nisi, pulvinar non iaculis id, euismod eu massa. Integer nisi felis, euismod sit amet sagittis in, consequat ut neque. Nam ut tortor nisl. Nullam nec odio in metus mollis pharetra vitae at libero. Nunc sodales elit velit, sit amet ultricies dolor. Proin et sapien nisl, id egestas turpis. Nulla eget eros a enim aliquet consequat. Phasellus cursus ligula quis risus varius aliquet ultrices lorem semper. Nullam malesuada lectus at leo placerat non interdum dui interdum. Vivamus vitae ultrices tellus.\r\n\r\nCras auctor sagittis lorem, ut bibendum metus sodales in. Vestibulum at urna enim, non condimentum magna. Praesent rhoncus ullamcorper volutpat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla scelerisque, arcu ut sagittis commodo, orci nunc facilisis tellus, convallis venenatis diam sapien sit amet dui. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur mollis dignissim velit nec molestie. Nulla facilisi. Quisque nisi est, adipiscing in condimentum eu, laoreet vel ipsum. Duis accumsan, ipsum sed euismod gravida, elit lorem porttitor lacus, in porttitor turpis magna nec dolor. Curabitur molestie sollicitudin cursus. Sed congue felis eget libero pellentesque varius. Pellentesque a lacus eget est malesuada laoreet. Vestibulum tincidunt ornare euismod.', 1, 0, NULL),
(29, 1, 13, 1, 0, '2012-01-19 00:00:00', '/upload/social-groups.jpg', 'Baku news 1', '', '', 'Quisque nisi est, adipiscing in condimentum eu, laoreet vel ipsum. Duis accumsan, ipsum sed euismod gravida, elit lorem porttitor lacus, in porttitor turpis magna nec dolor. Curabitur molestie sollicitudin cursus.', 'Quisque nisi est, adipiscing in condimentum eu, laoreet vel ipsum. Duis accumsan, ipsum sed euismod gravida, elit lorem porttitor lacus, in porttitor turpis magna nec dolor. Curabitur molestie sollicitudin cursus.  Quisque nisi est, adipiscing in condimentum eu, laoreet vel ipsum. Duis accumsan, ipsum sed euismod gravida, elit lorem porttitor lacus, in porttitor turpis magna nec dolor. Curabitur molestie sollicitudin cursus.  Quisque nisi est, adipiscing in condimentum eu, laoreet vel ipsum. Duis accumsan, ipsum sed euismod gravida, elit lorem porttitor lacus, in porttitor turpis magna nec dolor. Curabitur molestie sollicitudin cursus.  Quisque nisi est, adipiscing in condimentum eu, laoreet vel ipsum. Duis accumsan, ipsum sed euismod gravida, elit lorem porttitor lacus, in porttitor turpis magna nec dolor. Curabitur molestie sollicitudin cursus.', 2, 0, NULL),
(30, 6, 1, 0, 0, '2012-03-13 19:08:11', '', 'sdfsdfsd', NULL, NULL, 'fsdfsdf', '<p>sdfsdf</p>', 0, 0, NULL),
(31, 6, 1, 0, 0, '2012-03-14 00:00:00', '', 'qwerqwer', '', '', 'qwerqwerqwr', '<p>qwerqwerqwer</p>', 2, 0, NULL),
(32, 6, 1, 0, 0, '2012-03-14 00:00:00', '', 'qwerqwer', '', '', 'qwerqwerqwr', '<p>qwerqwerqwer</p>', 0, 0, NULL),
(33, 6, 1, 0, 0, '2012-03-14 13:25:46', '', 'test', NULL, NULL, 'sfdssdf', '<p>sdfsdfsdf</p>', 0, 0, '2012-03-14 11:26:40'),
(34, 6, 1, 0, 0, '2012-03-14 00:00:00', '/upload/1331724386Untitled_HDR2.jpg', 'asdfasdf', '', '', 'asdfasdfasdf', '<p>asdfas<strong>dfasdfsdaf</strong></p>', 0, 0, NULL),
(35, 6, 1, 0, 0, '2012-03-27 23:53:06', '/upload/47177672.jpg', 'werwrwer', NULL, NULL, 'sdfsdf', '<p>sssssdfsfa asdf asdf asdf</p>', 0, 0, NULL),
(36, 6, 1, 0, 0, '2012-03-27 23:55:13', '/upload/133288171347177672.jpg', 'rrrrrrrrr', NULL, NULL, 'aas  asdf asdf sad', '<p>&nbsp;asdfas df asdf&nbsp;</p>', 0, 0, NULL),
(37, 6, 10, 1, 0, '2012-04-03 00:00:00', '', 'qwerqwerqwer', '', '', 'qwerqwerqwerqwer', '<p>qwrqwerqwerqwerqwer</p>', 0, 0, NULL);

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
  `login` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL DEFAULT '',
  `hometown` varchar(255) NOT NULL DEFAULT '',
  `gender` int(10) unsigned NOT NULL DEFAULT '0',
  `about` text NOT NULL,
  `phone` varchar(50) NOT NULL DEFAULT '',
  `icq` varchar(50) NOT NULL DEFAULT '',
  `skype` varchar(100) NOT NULL DEFAULT '',
  `facebook` varchar(255) NOT NULL DEFAULT '',
  `twitter` varchar(255) NOT NULL DEFAULT '',
  `googleplus` varchar(255) NOT NULL DEFAULT '',
  `www` varchar(255) NOT NULL DEFAULT '',
  `current_rating` varchar(100) NOT NULL DEFAULT '',
  `deleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `site_profile_user` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

INSERT INTO `site_profiles` (`id`, `user_id`, `firstname`, `middlename`, `lastname`, `alias`, `birthdate`, `current_status`, `is_real`, `is_vip`, `account_number`, `amount`, `sms_amount`, `awards`, `login`, `avatar`, `hometown`, `gender`, `about`, `phone`, `icq`, `skype`, `facebook`, `twitter`, `googleplus`, `www`, `current_rating`, `deleted`) VALUES
(1, 6, 'Дмитрий!11', 'Николаевич!', 'Панченко!11', 'kocmoc111', '1982-02-06', 'sdkfjsdfgsadfastyjyrtu', 1, 1, '1222333', '400', 0, '', '', '/upload/Untitled_HDR2.jpg', '', 1, '', '', '', '', '', '', '', '', '', NULL),
(2, 8, 'димка', 'олрплорп', 'космос', 'kocmoc', '1982-02-06', 'sdjkfhs', 1, 1, '', '124', 0, '', '', '/upload/1330560157acd2E2.jpg', 'Донецк', 1, 'Житель города Донецка', '+380505555555', '853235', 'kosmozilla', 'dimka.kosmos', '', '', '', '', NULL),
(3, 1, '', '', '', '', NULL, '', 0, 0, '', '0', 0, '', '', '', '', 0, '', '', '', '', '', '', '', '', '', NULL),
(4, 13, '', '', '', '', NULL, '', 0, 0, '', '0', 0, '', '', '', '', 0, '', '', '', '', '', '', '', '', '', NULL),
(5, 14, 'Dmitry', 'Nik', 'Panchenko', 'kosmozilla', '1950-11-30', '', 0, 0, '', '0', 0, '', '', '/upload/1330912881Untitled_HDR2.jpg', 'Donetsk', 1, '', '', '', '', '', '', '', '', '', NULL),
(6, 19, '', '', '', '', NULL, '', 0, 0, '', '0', 0, '', '', '', '', 0, '', '', '', '', '', '', '', '', '', NULL),
(7, 20, '', '', '', '', NULL, '', 0, 0, '', '0', 0, '', '', '', '', 0, '', '', '', '', '', '', '', '', '', NULL),
(8, 21, '', '', '', '', NULL, '', 0, 0, '', '0', 0, '', '', '', '', 0, '', '', '', '', '', '', '', '', '', NULL);

DROP TABLE IF EXISTS `site_ratings`;
CREATE TABLE IF NOT EXISTS `site_ratings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_id` int(10) unsigned NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `profile_id` int(10) unsigned DEFAULT NULL,
  `ip` varchar(15) DEFAULT NULL,
  `value` int(10) NOT NULL DEFAULT '0',
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `site_ratings_profile` (`profile_id`),
  KEY `site_ratings_item` (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

INSERT INTO `site_ratings` (`id`, `type_id`, `item_id`, `profile_id`, `ip`, `value`, `added`, `deleted`) VALUES
(1, 1, 27, 1, '127.0.0.1', 1, '2012-04-02 21:34:36', NULL),
(2, 1, 26, 1, '127.0.0.1', -1, '2012-04-02 21:36:07', NULL),
(3, 1, 25, 1, '127.0.0.1', 1, '2012-04-02 21:36:54', NULL),
(4, 1, 24, 1, '127.0.0.1', 1, '2012-04-02 21:50:37', NULL),
(5, 1, 23, 1, '127.0.0.1', -1, '2012-04-02 21:50:56', NULL),
(6, 1, 5, 1, '127.0.0.1', 1, '2012-04-02 21:52:14', NULL);

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

DROP TABLE IF EXISTS `site_story`;
CREATE TABLE IF NOT EXISTS `site_story` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `site_story_profile` (`profile_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `site_subscribes`;
CREATE TABLE IF NOT EXISTS `site_subscribes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `site_subscribes_unique_link` (`profile_id`,`category_id`),
  KEY `site_subscribes_profile` (`profile_id`),
  KEY `site_subscribes_award` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

INSERT INTO `site_subscribes` (`id`, `profile_id`, `category_id`) VALUES
(23, 1, 1),
(20, 1, 8),
(30, 1, 9),
(22, 1, 13),
(21, 1, 14),
(25, 2, 8),
(28, 2, 9),
(29, 2, 10),
(27, 2, 13),
(26, 2, 14),
(19, 5, 13),
(18, 5, 14);

DROP TABLE IF EXISTS `site_tags_subscribes`;
CREATE TABLE IF NOT EXISTS `site_tags_subscribes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `tag_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `site_tags_subscribes_unique_link` (`profile_id`,`tag_id`),
  KEY `site_tags_subscribes_profile` (`profile_id`),
  KEY `site_tags_subscribes_tag` (`tag_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `site_tags_subscribes` (`id`, `profile_id`, `tag_id`) VALUES
(1, 1, 1);

DROP TABLE IF EXISTS `sys_actions`;
CREATE TABLE IF NOT EXISTS `sys_actions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `controller_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_actions_unique_action_in_controller` (`name`,`controller_id`),
  KEY `sys_actions_controller` (`controller_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=149 ;

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
(113, 'add', 24),
(122, 'add', 26),
(125, 'add', 27),
(133, 'add', 29),
(145, 'add', 31),
(140, 'added', 17),
(86, 'approve', 19),
(87, 'cancel', 19),
(118, 'category', 17),
(123, 'category', 26),
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
(114, 'delete', 24),
(136, 'delete', 29),
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
(115, 'edit', 24),
(129, 'edit', 26),
(137, 'edit', 29),
(117, 'email', 25),
(47, 'fetch', 9),
(90, 'fetch', 20),
(119, 'friend', 13),
(127, 'friends', 28),
(147, 'getrating', 17),
(146, 'getrating', 31),
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
(110, 'index', 24),
(121, 'index', 26),
(124, 'index', 27),
(132, 'index', 29),
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
(112, 'list', 24),
(135, 'list', 29),
(142, 'list', 30),
(45, 'lista', 7),
(46, 'lista', 8),
(48, 'lista', 10),
(92, 'load', 20),
(8, 'login', 6),
(116, 'login', 25),
(9, 'logout', 6),
(25, 'module', 9),
(96, 'move', 20),
(68, 'ping', 5),
(144, 'rating', 17),
(148, 'rebuild', 31),
(50, 'registration', 13),
(130, 'remove', 26),
(143, 'remove', 30),
(131, 'removecat', 26),
(72, 'reset', 13),
(5, 'save', 4),
(93, 'save', 20),
(141, 'saved', 13),
(26, 'sections', 9),
(66, 'show', 17),
(108, 'show', 22),
(126, 'subscribe', 17),
(128, 'subscribers', 28),
(138, 'tags', 17),
(139, 'tagsubscribe', 17),
(120, 'unfriend', 13),
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
(105, 'view', 21),
(111, 'view', 24),
(134, 'view', 29);

DROP TABLE IF EXISTS `sys_controllers`;
CREATE TABLE IF NOT EXISTS `sys_controllers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `module_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_controllers_unique_controller_in_module` (`name`,`module_id`),
  KEY `sys_controllers_module` (`module_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=32 ;

INSERT INTO `sys_controllers` (`id`, `name`, `module_id`) VALUES
(7, 'admin_actions', 7),
(21, 'admin_ads', 11),
(24, 'admin_awards', 9),
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
(29, 'admin_tags', 11),
(22, 'ads', 11),
(6, 'auth', 4),
(26, 'bookmarks', 9),
(15, 'cards', 10),
(25, 'check', 9),
(31, 'comment', 11),
(23, 'elfinder', 12),
(27, 'favorites', 9),
(1, 'index', 1),
(5, 'index', 3),
(13, 'index', 9),
(2, 'install', 1),
(3, 'module', 1),
(17, 'news', 11),
(28, 'relations', 9),
(30, 'subscriptions', 9);

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
(1, 9, 24, 110),
(1, 9, 24, 112),
(1, 9, 24, 113),
(1, 9, 24, 114),
(1, 9, 24, 115),
(2, 9, 13, 49),
(2, 9, 13, 50),
(2, 9, 13, 51),
(2, 9, 13, 72),
(2, 9, 25, 116),
(2, 9, 25, 117),
(3, 9, 13, 49),
(3, 9, 13, 52),
(3, 9, 13, 79),
(3, 9, 13, 119),
(3, 9, 13, 120),
(3, 9, 13, 141),
(3, 9, 26, 121),
(3, 9, 26, 122),
(3, 9, 26, 123),
(3, 9, 26, 129),
(3, 9, 26, 130),
(3, 9, 26, 131),
(3, 9, 27, 124),
(3, 9, 27, 125),
(3, 9, 28, 127),
(3, 9, 28, 128),
(3, 9, 30, 142),
(3, 9, 30, 143),
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
(1, 11, 29, 132),
(1, 11, 29, 133),
(1, 11, 29, 134),
(1, 11, 29, 135),
(1, 11, 29, 136),
(1, 11, 29, 137),
(2, 11, 17, 66),
(2, 11, 17, 67),
(2, 11, 17, 118),
(2, 11, 17, 138),
(3, 11, 17, 66),
(3, 11, 17, 67),
(3, 11, 17, 71),
(3, 11, 17, 118),
(3, 11, 17, 126),
(3, 11, 17, 138),
(3, 11, 17, 139),
(3, 11, 17, 140),
(3, 11, 17, 144),
(3, 11, 22, 106),
(3, 11, 22, 107),
(3, 11, 22, 108),
(3, 11, 31, 145),
(3, 11, 31, 148),
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=40 ;

INSERT INTO `sys_roleslinks` (`id`, `user_id`, `role_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 2),
(4, 2, 3),
(10, 5, 2),
(11, 5, 3),
(12, 6, 2),
(13, 6, 3),
(14, 7, 2),
(15, 7, 3),
(16, 8, 2),
(17, 8, 3),
(18, 9, 2),
(19, 9, 3),
(24, 13, 2),
(25, 13, 3),
(26, 14, 2),
(27, 14, 3),
(28, 15, 2),
(29, 15, 3),
(32, 18, 2),
(33, 18, 3),
(34, 19, 2),
(35, 19, 3),
(36, 20, 2),
(37, 20, 3),
(38, 21, 2),
(39, 21, 3);

DROP TABLE IF EXISTS `sys_users`;
CREATE TABLE IF NOT EXISTS `sys_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` char(32) NOT NULL,
  `status` int(10) unsigned NOT NULL DEFAULT '1',
  `activated` int(10) unsigned NOT NULL DEFAULT '0',
  `verify_code` varchar(10) DEFAULT NULL,
  `is_online` int(10) NOT NULL DEFAULT '0',
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_login` (`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

INSERT INTO `sys_users` (`id`, `login`, `email`, `password`, `status`, `activated`, `verify_code`, `is_online`, `changed`, `deleted`) VALUES
(1, 'admin', 'admin@sphere.com', 'e10adc3949ba59abbe56e057f20f883e', 1, 1, NULL, 1, '2012-02-27 23:51:15', NULL),
(2, 'user1', 'user1@sphere.com', '25d55ad283aa400af464c76d713c07ad', 1, 1, NULL, 0, '2012-02-27 23:51:24', NULL),
(3, 'kosmozilla', 'kosmozilla@gmail.com', '25d55ad283aa400af464c76d713c07ad', 1, 0, NULL, 0, '2012-03-01 11:53:54', '2012-03-01 11:53:54'),
(5, 'user2', 'user2@sphere.com', '45256440bc446dcfc6685591a427e8ec', 1, 0, 'fauo', 0, '2012-02-27 23:51:38', NULL),
(6, 'dmitry', 'dmitry@panchenko.biz', 'e807f1fcf82d132f9bb018ca6738a19f', 1, 1, '', 0, '2012-02-27 23:51:43', NULL),
(7, 'user3', 'user3@sphere.com', 'e115451ca17e14fcb73a0667a16c53b9', 1, 1, '', 0, '2012-02-27 23:51:48', NULL),
(8, 'artgen', 'mail@artgen.com.ua', '25d55ad283aa400af464c76d713c07ad', 1, 1, '', 0, '2012-02-27 23:51:57', NULL),
(9, 'kosmoz', 'kosmoz@gmail.com', '25d55ad283aa400af464c76d713c07ad', 1, 0, NULL, 0, '2012-02-27 23:55:31', NULL),
(10, 'susanna85', 'dmitriy.panchenko@gmail.com', 'f4e84c8aea6256817fd88bb9e509ec94', 1, 0, 'b5q6', 0, '2012-03-01 11:53:38', '2012-03-01 11:53:38'),
(11, 'susanna', 'kosmozilla@gmail.com', 'ae12229e9be1e6935d2c4ad8d04a8e47', 1, 0, '3d3O', 0, '2012-03-01 11:59:31', '2012-03-01 11:59:31'),
(13, 'sus', 'kosmozilla@gmail.com', 'dbe1535f70d81a4bc708a716fc2a9157', 1, 1, '', 0, '2012-03-01 12:00:16', NULL),
(14, 'admin2', 'kosmozilla222@gmail.com', 'e807f1fcf82d132f9bb018ca6738a19f', 1, 1, '', 0, '2012-03-05 02:32:35', NULL),
(15, 'admin1', 'kosmozilla2221@gmail.com', '3c71af7280c0eac1040a3cfec6c412a2', 1, 1, '', 0, '2012-03-05 01:12:54', NULL),
(18, 'kosmozilla123', 'dmitriy.panchenko@gmail.com', 'e807f1fcf82d132f9bb018ca6738a19f', 1, 1, '', 0, '2012-03-18 02:59:58', NULL),
(19, 'kosmozilla1', 'dmitriy.panchenko.1@gmail.com', 'e807f1fcf82d132f9bb018ca6738a19f', 1, 1, '', 0, '2012-03-18 03:02:28', NULL),
(20, 'dmitro', 'dmitroooo@gmail.com', 'e807f1fcf82d132f9bb018ca6738a19f', 1, 1, '', 0, '2012-03-19 00:56:04', NULL),
(21, 'kosmozoo', 'kosmozilla123@gmail.com', 'ddf65770826a098d98003a76ddb3c50f', 1, 1, '', 0, '2012-03-26 22:51:35', NULL);
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
);DROP VIEW IF EXISTS `view_awards`;
CREATE TABLE IF NOT EXISTS `view_awards` (
`id` int(10) unsigned
,`title` varchar(255)
,`status` int(10)
,`logo` varchar(255)
,`alias` varchar(255)
,`deleted` timestamp
);DROP VIEW IF EXISTS `view_bookmarks`;
CREATE TABLE IF NOT EXISTS `view_bookmarks` (
`id` int(10) unsigned
,`profile_id` int(10) unsigned
,`category_id` int(10) unsigned
,`title` varchar(255)
,`url` varchar(255)
,`deleted` timestamp
);DROP VIEW IF EXISTS `view_bookmarks_category`;
CREATE TABLE IF NOT EXISTS `view_bookmarks_category` (
`id` int(10) unsigned
,`profile_id` int(10) unsigned
,`title` varchar(255)
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
);DROP VIEW IF EXISTS `view_comments_tree`;
CREATE TABLE IF NOT EXISTS `view_comments_tree` (
`id` int(10) unsigned
,`parent_id` int(10) unsigned
,`left_id` int(10)
,`right_id` int(10)
,`profile_id` int(10) unsigned
,`type_id` int(10) unsigned
,`item_id` int(10) unsigned
,`comment` varchar(255)
,`is_active` int(10) unsigned
,`order` int(10) unsigned
,`deleted` timestamp
);DROP VIEW IF EXISTS `view_content_tags`;
CREATE TABLE IF NOT EXISTS `view_content_tags` (
`id` int(10) unsigned
,`title` varchar(255)
,`type_id` int(10)
,`logo` varchar(255)
,`description` text
,`is_paid` int(10) unsigned
,`cnt` bigint(20) unsigned
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
,`view_count` bigint(20) unsigned
,`rating` bigint(20)
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
,`login` varchar(255)
,`avatar` varchar(255)
,`hometown` varchar(255)
,`gender` int(10) unsigned
,`about` text
,`phone` varchar(50)
,`icq` varchar(50)
,`skype` varchar(100)
,`facebook` varchar(255)
,`twitter` varchar(255)
,`googleplus` varchar(255)
,`www` varchar(255)
,`current_rating` varchar(100)
,`deleted` timestamp
);DROP VIEW IF EXISTS `view_ratings`;
CREATE TABLE IF NOT EXISTS `view_ratings` (
`id` int(10) unsigned
,`type_id` int(10) unsigned
,`item_id` int(10) unsigned
,`profile_id` int(10) unsigned
,`ip` varchar(15)
,`value` int(10)
,`added` timestamp
,`deleted` timestamp
);DROP VIEW IF EXISTS `view_ratings_values`;
CREATE TABLE IF NOT EXISTS `view_ratings_values` (
`item_id` int(10) unsigned
,`type_id` int(10) unsigned
,`rating` decimal(32,0)
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
);DROP VIEW IF EXISTS `view_users`;
CREATE TABLE IF NOT EXISTS `view_users` (
`id` int(10) unsigned
,`login` varchar(255)
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
DROP TABLE IF EXISTS `view_awards`;

CREATE OR REPLACE VIEW `view_awards` AS select `site_awards`.`id` AS `id`,`site_awards`.`title` AS `title`,`site_awards`.`status` AS `status`,`site_awards`.`logo` AS `logo`,`site_awards`.`alias` AS `alias`,`site_awards`.`deleted` AS `deleted` from `site_awards` where isnull(`site_awards`.`deleted`);
DROP TABLE IF EXISTS `view_bookmarks`;

CREATE OR REPLACE VIEW `view_bookmarks` AS select `site_bookmarks`.`id` AS `id`,`site_bookmarks`.`profile_id` AS `profile_id`,`site_bookmarks`.`category_id` AS `category_id`,`site_bookmarks`.`title` AS `title`,`site_bookmarks`.`url` AS `url`,`site_bookmarks`.`deleted` AS `deleted` from `site_bookmarks` where isnull(`site_bookmarks`.`deleted`);
DROP TABLE IF EXISTS `view_bookmarks_category`;

CREATE OR REPLACE VIEW `view_bookmarks_category` AS select `site_bookmarks_category`.`id` AS `id`,`site_bookmarks_category`.`profile_id` AS `profile_id`,`site_bookmarks_category`.`title` AS `title`,`site_bookmarks_category`.`deleted` AS `deleted` from `site_bookmarks_category` where isnull(`site_bookmarks_category`.`deleted`);
DROP TABLE IF EXISTS `view_category_tree`;

CREATE OR REPLACE VIEW `view_category_tree` AS select `site_category_tree`.`id` AS `id`,`site_category_tree`.`parent_id` AS `parent_id`,`site_category_tree`.`left_id` AS `left_id`,`site_category_tree`.`right_id` AS `right_id`,`site_category_tree`.`title` AS `title`,`site_category_tree`.`alias` AS `alias`,`site_category_tree`.`is_active` AS `is_active`,`site_category_tree`.`show_on_home` AS `show_on_home`,`site_category_tree`.`order` AS `order`,`site_category_tree`.`readonly` AS `readonly`,`site_category_tree`.`deleted` AS `deleted` from `site_category_tree` where isnull(`site_category_tree`.`deleted`);
DROP TABLE IF EXISTS `view_comments_tree`;

CREATE OR REPLACE VIEW `view_comments_tree` AS select `site_comments_tree`.`id` AS `id`,`site_comments_tree`.`parent_id` AS `parent_id`,`site_comments_tree`.`left_id` AS `left_id`,`site_comments_tree`.`right_id` AS `right_id`,`site_comments_tree`.`profile_id` AS `profile_id`,`site_comments_tree`.`type_id` AS `type_id`,`site_comments_tree`.`item_id` AS `item_id`,`site_comments_tree`.`comment` AS `comment`,`site_comments_tree`.`is_active` AS `is_active`,`site_comments_tree`.`order` AS `order`,`site_comments_tree`.`deleted` AS `deleted` from `site_comments_tree` where isnull(`site_comments_tree`.`deleted`);
DROP TABLE IF EXISTS `view_content_tags`;

CREATE OR REPLACE VIEW `view_content_tags` AS select `site_content_tags`.`id` AS `id`,`site_content_tags`.`title` AS `title`,`site_content_tags`.`type_id` AS `type_id`,`site_content_tags`.`logo` AS `logo`,`site_content_tags`.`description` AS `description`,`site_content_tags`.`is_paid` AS `is_paid`,`site_content_tags`.`cnt` AS `cnt`,`site_content_tags`.`deleted` AS `deleted` from `site_content_tags` where isnull(`site_content_tags`.`deleted`);
DROP TABLE IF EXISTS `view_documents`;

CREATE OR REPLACE VIEW `view_documents` AS select `site_documents`.`id` AS `id`,`site_documents`.`image` AS `image`,`site_documents`.`comment` AS `comment`,`site_documents`.`user_id` AS `user_id`,`site_documents`.`profile_id` AS `profile_id`,`site_documents`.`status` AS `status`,`site_documents`.`deleted` AS `deleted` from `site_documents` where isnull(`site_documents`.`deleted`);
DROP TABLE IF EXISTS `view_news`;

CREATE OR REPLACE VIEW `view_news` AS select `site_news`.`id` AS `id`,`site_news`.`user_id` AS `user_id`,`site_news`.`category_id` AS `category_id`,`site_news`.`activated` AS `activated`,`site_news`.`is_hot` AS `is_hot`,`site_news`.`public_date` AS `public_date`,`site_news`.`image` AS `image`,`site_news`.`title` AS `title`,`site_news`.`keywords` AS `keywords`,`site_news`.`description` AS `description`,`site_news`.`short_content` AS `short_content`,`site_news`.`content` AS `content`,`site_news`.`view_count` AS `view_count`,`site_news`.`rating` AS `rating`,`site_news`.`deleted` AS `deleted` from `site_news` where isnull(`site_news`.`deleted`);
DROP TABLE IF EXISTS `view_permissions`;

CREATE OR REPLACE VIEW `view_permissions` AS select `permissions`.`role_id` AS `role_id`,`roles`.`name` AS `role_name`,`permissions`.`module_id` AS `module_id`,`modules`.`name` AS `module_name`,`permissions`.`controller_id` AS `controller_id`,`controllers`.`name` AS `controller_name`,`permissions`.`action_id` AS `action_id`,`actions`.`name` AS `action_name` from ((((`sys_permissions` `permissions` join `sys_roles` `roles` on((`roles`.`id` = `permissions`.`role_id`))) join `sys_modules` `modules` on((`modules`.`id` = `permissions`.`module_id`))) join `sys_controllers` `controllers` on((`controllers`.`id` = `permissions`.`controller_id`))) join `sys_actions` `actions` on((`actions`.`id` = `permissions`.`action_id`)));
DROP TABLE IF EXISTS `view_profiles`;

CREATE OR REPLACE VIEW `view_profiles` AS select `site_profiles`.`id` AS `id`,`site_profiles`.`user_id` AS `user_id`,`site_profiles`.`firstname` AS `firstname`,`site_profiles`.`middlename` AS `middlename`,`site_profiles`.`lastname` AS `lastname`,`site_profiles`.`alias` AS `alias`,`site_profiles`.`birthdate` AS `birthdate`,`site_profiles`.`current_status` AS `current_status`,`site_profiles`.`is_real` AS `is_real`,`site_profiles`.`is_vip` AS `is_vip`,`site_profiles`.`account_number` AS `account_number`,`site_profiles`.`amount` AS `amount`,`site_profiles`.`sms_amount` AS `sms_amount`,`site_profiles`.`awards` AS `awards`,`site_profiles`.`login` AS `login`,`site_profiles`.`avatar` AS `avatar`,`site_profiles`.`hometown` AS `hometown`,`site_profiles`.`gender` AS `gender`,`site_profiles`.`about` AS `about`,`site_profiles`.`phone` AS `phone`,`site_profiles`.`icq` AS `icq`,`site_profiles`.`skype` AS `skype`,`site_profiles`.`facebook` AS `facebook`,`site_profiles`.`twitter` AS `twitter`,`site_profiles`.`googleplus` AS `googleplus`,`site_profiles`.`www` AS `www`,`site_profiles`.`current_rating` AS `current_rating`,`site_profiles`.`deleted` AS `deleted` from `site_profiles` where isnull(`site_profiles`.`deleted`);
DROP TABLE IF EXISTS `view_ratings`;

CREATE OR REPLACE VIEW `view_ratings` AS select `site_ratings`.`id` AS `id`,`site_ratings`.`type_id` AS `type_id`,`site_ratings`.`item_id` AS `item_id`,`site_ratings`.`profile_id` AS `profile_id`,`site_ratings`.`ip` AS `ip`,`site_ratings`.`value` AS `value`,`site_ratings`.`added` AS `added`,`site_ratings`.`deleted` AS `deleted` from `site_ratings` where isnull(`site_ratings`.`deleted`);
DROP TABLE IF EXISTS `view_ratings_values`;

CREATE OR REPLACE VIEW `view_ratings_values` AS select `view_ratings`.`item_id` AS `item_id`,`view_ratings`.`type_id` AS `type_id`,sum(`view_ratings`.`value`) AS `rating` from `view_ratings` group by `view_ratings`.`type_id`,`view_ratings`.`item_id`;
DROP TABLE IF EXISTS `view_roles`;

CREATE OR REPLACE VIEW `view_roles` AS select `sys_roles`.`id` AS `id`,`sys_roles`.`name` AS `name`,`sys_roles`.`deleted` AS `deleted` from `sys_roles` where isnull(`sys_roles`.`deleted`);
DROP TABLE IF EXISTS `view_scratch_cards`;

CREATE OR REPLACE VIEW `view_scratch_cards` AS select `site_scratch_cards`.`id` AS `id`,`site_scratch_cards`.`number` AS `number`,`site_scratch_cards`.`amount` AS `amount`,`site_scratch_cards`.`is_used` AS `is_used`,`site_scratch_cards`.`deleted` AS `deleted` from `site_scratch_cards` where isnull(`site_scratch_cards`.`deleted`);
DROP TABLE IF EXISTS `view_statuses`;

CREATE OR REPLACE VIEW `view_statuses` AS select `site_statuses`.`id` AS `id`,`site_statuses`.`user_id` AS `user_id`,`site_statuses`.`value` AS `value`,`site_statuses`.`added` AS `added`,`site_statuses`.`deleted` AS `deleted` from `site_statuses` where isnull(`site_statuses`.`deleted`);
DROP TABLE IF EXISTS `view_users`;

CREATE OR REPLACE VIEW `view_users` AS select `sys_users`.`id` AS `id`,`sys_users`.`login` AS `login`,`sys_users`.`email` AS `email`,`sys_users`.`password` AS `password`,`sys_users`.`status` AS `status`,`sys_users`.`activated` AS `activated`,`sys_users`.`verify_code` AS `verify_code`,`sys_users`.`is_online` AS `is_online`,`sys_users`.`changed` AS `changed`,`sys_users`.`deleted` AS `deleted` from `sys_users` where isnull(`sys_users`.`deleted`);


ALTER TABLE `site_ads`
  ADD CONSTRAINT `site_ads_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `sys_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `site_awardslinks`
  ADD CONSTRAINT `site_awardslinks_ibfk_1` FOREIGN KEY (`profile_id`) REFERENCES `site_profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `site_awardslinks_ibfk_2` FOREIGN KEY (`award_id`) REFERENCES `site_awards` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `site_bookmarks`
  ADD CONSTRAINT `site_bookmarks_ibfk_1` FOREIGN KEY (`profile_id`) REFERENCES `site_profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `site_bookmarks_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `site_bookmarks_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `site_bookmarks_category`
  ADD CONSTRAINT `site_bookmarks_category_ibfk_1` FOREIGN KEY (`profile_id`) REFERENCES `site_profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `site_comments_tree`
  ADD CONSTRAINT `site_comments_tree_ibfk_1` FOREIGN KEY (`profile_id`) REFERENCES `site_profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `site_content_tagslinks`
  ADD CONSTRAINT `site_content_tagslinks_ibfk_1` FOREIGN KEY (`tag_id`) REFERENCES `site_content_tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `site_documents`
  ADD CONSTRAINT `site_documents_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `sys_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `site_documents_ibfk_2` FOREIGN KEY (`profile_id`) REFERENCES `site_profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `site_favorites`
  ADD CONSTRAINT `site_favorites_ibfk_1` FOREIGN KEY (`profile_id`) REFERENCES `site_profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `site_friendslinks`
  ADD CONSTRAINT `site_friendslinks_ibfk_1` FOREIGN KEY (`profile_id`) REFERENCES `site_profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `site_friendslinks_ibfk_2` FOREIGN KEY (`friend_id`) REFERENCES `site_profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `site_news`
  ADD CONSTRAINT `site_news_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `sys_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `site_profiles`
  ADD CONSTRAINT `site_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `sys_users` (`id`);

ALTER TABLE `site_statuses`
  ADD CONSTRAINT `site_statuses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `sys_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `site_story`
  ADD CONSTRAINT `site_story_ibfk_1` FOREIGN KEY (`profile_id`) REFERENCES `site_profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `site_subscribes`
  ADD CONSTRAINT `site_subscribes_ibfk_1` FOREIGN KEY (`profile_id`) REFERENCES `site_profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `site_subscribes_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `site_category_tree` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `site_tags_subscribes`
  ADD CONSTRAINT `site_tags_subscribes_ibfk_1` FOREIGN KEY (`profile_id`) REFERENCES `site_profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `site_tags_subscribes_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `site_content_tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
