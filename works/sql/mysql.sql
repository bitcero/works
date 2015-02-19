CREATE TABLE `mod_works_categories` (
  `id_cat` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `position` int(11) NOT NULL DEFAULT '0',
  `status` varchar(10) NOT NULL DEFAULT 'active',
  `nameid` varchar(150) NOT NULL,
  `created` int(10) NOT NULL DEFAULT '0',
  `parent` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_cat`),
  KEY `status` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `mod_works_categories_rel` (
  `category` int(11) NOT NULL,
  `work` int(11) NOT NULL,
  KEY `category` (`category`,`work`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `mod_works_clients` (
  `id_client` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `business_name` varchar(200) NOT NULL,
  `desc` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_client`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `mod_works_images` (
  `title` varchar(100) NOT NULL,
  `image` varchar(200) NOT NULL,
  `work` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `mod_works_meta` (
  `id_meta` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `value` text NOT NULL,
  `work` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_meta`),
  KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `mod_works_types` (
  `id_type` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(100) NOT NULL,
  `created` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `mod_works_works` (
  `id_work` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `titleid` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `customer` varchar(150) NOT NULL,
  `comment` text NOT NULL,
  `web` varchar(150) NOT NULL,
  `url` varchar(255) NOT NULL DEFAULT '',
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `image` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'public',
  `groups` text NOT NULL,
  `schedule` datetime NOT NULL,
  `rating` int(11) NOT NULL DEFAULT '0',
  `views` int(11) NOT NULL DEFAULT '0',
  `comms` int(11) NOT NULL DEFAULT '0',
  `seo_title` varchar(150) NOT NULL,
  `seo_description` varchar(255) NOT NULL,
  `seo_keywords` varchar(255) NOT NULL,
  PRIMARY KEY (`id_work`),
  KEY `titleid` (`titleid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
