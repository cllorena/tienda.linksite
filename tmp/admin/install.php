<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

class com_fwgalleryInstallerScript {
	function postflight($type, $adaptor) {
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');
		if (!defined('FWMG_COMPONENT_SITE')) {
			define('FWMG_COMPONENT_SITE', JPATH_SITE.'/components/com_fwgallery');
		}
		$db = JFactory :: getDBO();
		$tables = $db->getTableList();
		if (in_array($db->getPrefix().'fwsg_category', $tables)) {
			$db->setQuery('SHOW FIELDS FROM `#__fwsg_category`');
			$fields = $db->loadColumn();
			if (!in_array('is_public', $fields)) {
				$db->setQuery('ALTER TABLE `#__fwsg_category` ADD COLUMN is_public TINYINT(3) UNSIGNED NOT NULL DEFAULT 0');
				$db->execute();
			}
		} else {
			$db->setQuery("
CREATE TABLE IF NOT EXISTS `#__fwsg_category` (
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
)");
			$db->execute();
		}
		if (!in_array($db->getPrefix().'fwsg_category_tag', $tables)) {
			$db->setQuery("
CREATE TABLE IF NOT EXISTS `#__fwsg_category_tag` (
	`category_id` INT(10) UNSIGNED NOT NULL,
	`tag_id` INT(10) UNSIGNED NOT NULL,
	INDEX `category_id` (`category_id`),
	INDEX `tag_id` (`tag_id`)
)");
			$db->execute();
		}
		if (in_array($db->getPrefix().'fwsg_file', $tables)) {
			$db->setQuery('ALTER TABLE `#__fwsg_file` CHANGE COLUMN `copyright` `copyright` VARCHAR(200) NULL');
			$db->execute();
			$db->setQuery('ALTER TABLE `#__fwsg_file` CHANGE COLUMN `metadescr` `metadescr` VARCHAR(200) NULL');
			$db->execute();
			$db->setQuery('ALTER TABLE `#__fwsg_file` CHANGE COLUMN `metakey` `metakey` VARCHAR(200) NULL');
			$db->execute();

			$db->setQuery('SHOW FIELDS FROM `#__fwsg_file`');
			$fields = $db->loadColumn();
			if (!in_array('featured', $fields)) {
				$db->setQuery('ALTER TABLE `#__fwsg_file` ADD COLUMN featured TINYINT(3) UNSIGNED NOT NULL DEFAULT 0, ADD INDEX `featured` (`featured`)');
				$db->execute();
			}
		} else {
			$db->setQuery("
CREATE TABLE IF NOT EXISTS `#__fwsg_file` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`type` ENUM('image','video','audio','file') NOT NULL DEFAULT 'image',
	`published` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
	`featured` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
	`ordering` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`access` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`created` DATETIME NULL DEFAULT NULL,
	`updated` DATETIME NULL DEFAULT NULL,
	`category_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`user_id` INT(10) UNSIGNED NOT NULL,
	`hits` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`downloads` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`name` VARCHAR(200) NOT NULL,
	`copyright` VARCHAR(200) NULL,
	`metadescr` VARCHAR(200) NULL,
	`metakey` VARCHAR(200) NULL,
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
)");
			$db->execute();
		}
		if (!in_array($db->getPrefix().'fwsg_file_file', $tables)) {
			$db->setQuery("
CREATE TABLE IF NOT EXISTS `#__fwsg_file_file` (
	`file_id` int unsigned not null,
	`sys_filename` varchar(50) null,
	`filename` varchar(200) null,
	`size` int unsigned,
	primary key (`file_id`)
)");
			$db->execute();
		} else {
			$db->setQuery('ALTER TABLE `#__fwsg_file_file` CHANGE COLUMN `filename` `filename` VARCHAR(200) NULL');
			$db->execute();
			$db->setQuery('ALTER TABLE `#__fwsg_file_file` CHANGE COLUMN `sys_filename` `sys_filename` VARCHAR(50) NULL');
			$db->execute();
		}
		if (!in_array($db->getPrefix().'fwsg_file_image', $tables)) {
			$db->setQuery("
CREATE TABLE IF NOT EXISTS `#__fwsg_file_image` (
	`file_id` int unsigned not null,
	`sys_filename` varchar(50) null,
	`filename` varchar(200) null,
	`alt` varchar(200),
	`width` int unsigned,
	`height` int unsigned,
	`size` int unsigned,
	primary key (`file_id`)
)");
			$db->execute();
		} else {
			$db->setQuery('ALTER TABLE `#__fwsg_file_image` CHANGE COLUMN `filename` `filename` VARCHAR(200) NULL');
			$db->execute();
			$db->setQuery('ALTER TABLE `#__fwsg_file_image` CHANGE COLUMN `sys_filename` `sys_filename` VARCHAR(50) NULL');
			$db->execute();
		}
		if (!in_array($db->getPrefix().'fwsg_file_location', $tables)) {
			$db->setQuery("
CREATE TABLE IF NOT EXISTS `#__fwsg_file_location` (
	`file_id` INT(10) UNSIGNED NOT NULL,
	`address` VARCHAR(200) NOT NULL,
	`latitude` double,
	`longitude` double,
	PRIMARY KEY (`file_id`)
)");
			$db->execute();
		}
		if (!in_array($db->getPrefix().'fwsg_file_tag', $tables)) {
			$db->setQuery("
CREATE TABLE IF NOT EXISTS `#__fwsg_file_tag` (
	`file_id` int unsigned not null,
	`tag_id` int unsigned not null,
	key(`file_id`),
	key(tag_id)
)");
			$db->execute();
		}
		if (!in_array($db->getPrefix().'fwsg_file_video', $tables)) {
			$db->setQuery("
CREATE TABLE IF NOT EXISTS `#__fwsg_file_video` (
	`file_id` INT(10) UNSIGNED NOT NULL,
	`sys_filename` VARCHAR(50) NULL,
	`filename` VARCHAR(200) NULL,
	`media` ENUM('youtube','vimeo','mp4') NULL DEFAULT NULL,
	`code` VARCHAR(200) NULL DEFAULT NULL,
	`size` INT(10) UNSIGNED NULL DEFAULT NULL,
	`width` int unsigned,
	`height` int unsigned,
	`duration` TIME,
	PRIMARY KEY (`file_id`)
)");
			$db->execute();
		} else {
			$db->setQuery('ALTER TABLE `#__fwsg_file_video` CHANGE COLUMN `filename` `filename` VARCHAR(200) NULL');
			$db->execute();
			$db->setQuery('ALTER TABLE `#__fwsg_file_video` CHANGE COLUMN `sys_filename` `sys_filename` VARCHAR(50) NULL');
			$db->execute();
			$db->setQuery('SHOW FIELDS FROM `#__fwsg_file_video`');
			$fields = $db->loadColumn();
			if (!in_array('width', $fields)) {
				$db->setQuery('ALTER TABLE `#__fwsg_file_video` ADD COLUMN width INT UNSIGNED');
				$db->execute();
			}
			if (!in_array('height', $fields)) {
				$db->setQuery('ALTER TABLE `#__fwsg_file_video` ADD COLUMN height INT UNSIGNED');
				$db->execute();
			}
			if (!in_array('duration', $fields)) {
				$db->setQuery('ALTER TABLE `#__fwsg_file_video` ADD COLUMN duration TIME');
				$db->execute();
			}
		}
		if (!in_array($db->getPrefix().'fwsg_file_audio', $tables)) {
			$db->setQuery("
CREATE TABLE IF NOT EXISTS `#__fwsg_file_audio` (
	`file_id` INT(10) UNSIGNED NOT NULL,
	`sys_filename` VARCHAR(50) NULL,
	`filename` VARCHAR(200) NULL,
	`size` INT(10) UNSIGNED NULL DEFAULT NULL,
	`duration` TIME,
	PRIMARY KEY (`file_id`)
)");
			$db->execute();
		} else {
			$db->setQuery('ALTER TABLE `#__fwsg_file_audio` CHANGE COLUMN `filename` `filename` VARCHAR(200) NULL');
			$db->execute();
			$db->setQuery('ALTER TABLE `#__fwsg_file_audio` CHANGE COLUMN `sys_filename` `sys_filename` VARCHAR(50) NULL');
			$db->execute();
			$db->setQuery('SHOW FIELDS FROM `#__fwsg_file_audio`');
			$fields = $db->loadColumn();
			if (!in_array('duration', $fields)) {
				$db->setQuery('ALTER TABLE `#__fwsg_file_audio` ADD COLUMN duration TIME');
				$db->execute();
			}
		}
		if (!in_array($db->getPrefix().'fwsg_file_vote', $tables)) {
			$db->setQuery("
CREATE TABLE IF NOT EXISTS `#__fwsg_file_vote` (
	`user_id` INT(10) UNSIGNED NOT NULL,
	`file_id` INT(10) UNSIGNED NOT NULL,
	`value` TINYINT(4) NOT NULL DEFAULT '0',
	`ipaddr` BIGINT(20) NULL DEFAULT NULL,
	KEY(`user_id`),
	KEY(`file_id`),
	KEY(`ipaddr`),
	KEY(`value`)
)");
			$db->execute();
		}
		if (!in_array($db->getPrefix().'fwsg_tag', $tables)) {
			$db->setQuery("
CREATE TABLE IF NOT EXISTS `#__fwsg_tag` (
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
)");
			$db->execute();
		}

		$db->setQuery('SELECT extension_id FROM `#__extensions` WHERE `element` = \'com_fwgallery\'');
		$extension_id = (int)$db->loadResult();

		$db->setQuery("SELECT * FROM `#__menu` WHERE `client_id` = 0 AND (`link` = 'index.php?option=com_fwgallery&view=gallery' OR `link` = 'index.php?option=com_fwgallery&view=galleries' OR `link` = 'index.php?option=com_fwgallerylight&view=gallery' OR `link` = 'index.php?option=com_fwgallerylight&view=galleries')");
		if ($list = $db->loadObjectList()) {
			foreach ($list as $row) {
				$params = new JRegistry($row->params);
				if ($id = $params->get('id')) {
					$params->set('gid', $id);
				}
				$db->setQuery("UPDATE `#__menu` SET link = 'index.php?option=com_fwgallery&view=fwgallery', params = ".$db->quote($params->toString()).", component_id = $extension_id WHERE id = ".$row->id);
				$db->execute();
			}
		}
		$db->setQuery("SELECT * FROM `#__menu` WHERE `client_id` = 0 AND (`link` = 'index.php?option=com_fwgallery&view=image' OR `link` = 'index.php?option=com_fwgallerylight&view=image')");
		if ($list = $db->loadObjectList()) {
			foreach ($list as $row) {
				$params = new JRegistry($row->params);
				if ($id = $params->get('id')) {
					$params->set('file_id', $id);
				}
				$db->setQuery("UPDATE `#__menu` SET link = 'index.php?option=com_fwgallery&view=item', params = ".$db->quote($params->toString()).", component_id = $extension_id WHERE id = ".$row->id);
				$db->execute();
			}
		}

		$now = JFactory::getDate()->toSql();
		$db->setQuery('UPDATE `#__fwsg_category` SET created = '.$db->quote($now).' WHERE created = \'0000-00-00 00:00:00\'');
		$db->execute();
		$db->setQuery('UPDATE `#__fwsg_file` SET created = '.$db->quote($now).' WHERE created = \'0000-00-00 00:00:00\'');
		$db->execute();

		$params = JComponentHelper::getParams('com_fwgallery');
		if ($params) {
			if ($update_code = $params->get('verified_code')) {
				$db->setQuery('UPDATE `#__update_sites` SET extra_query = '.$db->quote('code='.urlencode(trim($update_code))).', last_check_timestamp = 0 WHERE name LIKE \'FW Gallery%\'');
				$db->execute();
			}
			if ($params->get('extensions_data_time')) {
				$params->set('extensions_data_time', '');
				$db->setQuery('UPDATE `#__extensions` SET params = '.$db->quote($params->toString()).' WHERE `element` = \'com_fwgallery\' AND `type` = \'component\'');
		    	$db->execute();
			}
		}

		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');
		$views = array('frontendmanager', 'galleries', 'gallery', 'image', 'tags', 'tag');
		foreach ($views as $view) {
			$path = FWMG_COMPONENT_SITE.'/views/'.$view;
			if (is_dir($path)) {
				JFolder::delete($path);
			}
			$path = FWMG_COMPONENT_SITE.'/models/'.$view.'.php';
			if (file_exists($path)) {
				JFile::delete($path);
			}
		}
	}
}
