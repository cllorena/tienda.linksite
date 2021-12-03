
CREATE TABLE IF NOT EXISTS #__fwsg_category (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`published` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
	`is_public` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
	`parent` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`ordering` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`access` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`created` DATETIME NULL DEFAULT NULL,
	`updated` DATETIME NULL DEFAULT NULL,
	`user_id` INT(10) UNSIGNED NOT NULL,
	`name` VARCHAR(200) NOT NULL,
	`sys_filename` VARCHAR(40) NULL DEFAULT NULL,
	`filename` VARCHAR(200) NULL DEFAULT NULL,
	`metadescr` VARCHAR(200) NULL DEFAULT NULL,
	`metakey` VARCHAR(200) NULL DEFAULT NULL,
	`descr` TEXT NULL,
	`params` TEXT NULL,
	`alias` VARCHAR(200) NULL DEFAULT NULL,
	`hits` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`media` VARCHAR(20) NULL DEFAULT NULL,
	`media_code` VARCHAR(200) NULL DEFAULT NULL,
	`latitude` FLOAT NOT NULL DEFAULT '0',
	`longitude` FLOAT NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	INDEX `published` (`published`),
	INDEX `ordering` (`ordering`),
	INDEX `parent` (`parent`),
	INDEX `access` (`access`),
	INDEX `updated` (`updated`),
	INDEX `name` (`name`)
);

CREATE TABLE IF NOT EXISTS `#__fwsg_category_tag` (
	`category_id` INT(10) UNSIGNED NOT NULL,
	`tag_id` INT(10) UNSIGNED NOT NULL,
	INDEX `category_id` (`category_id`),
	INDEX `tag_id` (`tag_id`)
);

CREATE TABLE IF NOT EXISTS #__fwsg_file (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`type` ENUM('image','video','audio','file') NOT NULL DEFAULT 'image',
	`published` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
	`ordering` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`access` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`created` DATETIME NULL DEFAULT NULL,
	`updated` DATETIME NULL DEFAULT NULL,
	`category_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`user_id` INT(10) UNSIGNED NOT NULL,
	`hits` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`downloads` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`name` VARCHAR(200) NOT NULL,
	`copyright` VARCHAR(200) NOT NULL,
	`metadescr` VARCHAR(200) NOT NULL,
	`metakey` VARCHAR(200) NOT NULL,
	`descr` TEXT NULL,
	`params` TEXT NULL,
	`alias` VARCHAR(200) NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	INDEX `type` (`type`),
	INDEX `published` (`published`),
	INDEX `ordering` (`ordering`),
	INDEX `access` (`access`),
	INDEX `updated` (`updated`),
	INDEX `name` (`name`)
);

CREATE TABLE IF NOT EXISTS #__fwsg_file_file (
	`file_id` int unsigned not null,
	`sys_filename` varchar(50) not null,
	`filename` varchar(200) not null,
	`size` int unsigned,
	primary key (`file_id`)
);

CREATE TABLE IF NOT EXISTS #__fwsg_file_image (
	`file_id` int unsigned not null,
	`sys_filename` varchar(50) not null,
	`filename` varchar(200) not null,
	`alt` varchar(200),
	`width` int unsigned,
	`height` int unsigned,
	`size` int unsigned,
	primary key (`file_id`)
);

CREATE TABLE IF NOT EXISTS `#__fwsg_file_location` (
	`file_id` INT(10) UNSIGNED NOT NULL,
	`address` VARCHAR(200) NOT NULL,
	`latitude` double,
	`longitude` double,
	PRIMARY KEY (`file_id`)
);

CREATE TABLE IF NOT EXISTS #__fwsg_file_tag (
	`file_id` int unsigned not null,
	`tag_id` int unsigned not null,
	key(`file_id`),
	key(tag_id)
);

CREATE TABLE IF NOT EXISTS `#__fwsg_file_video` (
	`file_id` INT(10) UNSIGNED NOT NULL,
	`sys_filename` VARCHAR(50) NOT NULL,
	`filename` VARCHAR(200) NOT NULL,
	`media` ENUM('youtube','vimeo','mp4') NULL DEFAULT NULL,
	`code` VARCHAR(200) NULL DEFAULT NULL,
	`size` INT(10) UNSIGNED NULL DEFAULT NULL,
	PRIMARY KEY (`file_id`)
);

CREATE TABLE IF NOT EXISTS `#__fwsg_file_vote` (
	`user_id` INT(10) UNSIGNED NOT NULL,
	`file_id` INT(10) UNSIGNED NOT NULL,
	`value` TINYINT(4) NOT NULL DEFAULT '0',
	`ipaddr` BIGINT(20) NULL DEFAULT NULL,
	KEY(`user_id`),
	KEY(`file_id`),
	KEY(`ipaddr`),
	KEY(`value`)
);

CREATE TABLE IF NOT EXISTS #__fwsg_tag (
	`id` int unsigned not null auto_increment,
	`published` tinyint unsigned not null default 0,
	`ordering` int unsigned not null default 0,
	`created` datetime,
	`user_id` int unsigned not null,
	`name` varchar(200) NOT NULL,
	`alias` varchar(200),
	`hits` int unsigned not null default 0,
	primary key (id),
	key(published),
	key(ordering),
	key(name)
);
