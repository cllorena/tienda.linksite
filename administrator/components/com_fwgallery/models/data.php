<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

class fwGalleryModelData extends JModelLegacy {
	function getPlugins($view) {
		$app = JFactory::getApplication();
		JPluginHelper::importPlugin('fwgallery');
		return $app->triggerEvent('onfwGetAdminForm', array('com_fwgallery', $view));
	}
	function getPlugin() {
		$input = JFactory::getApplication()->input;
		if ($plugin = $input->getString('plugin'))
			return JPluginHelper::getPlugin('fwgallery', $plugin);
	}
	function processPlugin(&$plugin) {
		if (JPluginHelper::importPlugin('fwgallery', $plugin->name)) {
			$app = JFactory::getApplication();
			$result = $app->triggerEvent('onfwProcess', array($plugin->name));
		}
	}
	function exportData() {
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');

		$db = JFactory::getDBO();
		$folder = '';
		$path = JPATH_SITE.'/tmp/';
		do {
			$folder = rand();
		} while (file_exists($path.$folder));
		JFolder::create($path.$folder);

		$tables = array(
			'category',
			'category_tag',
			'file',
			'file_audio',
			'file_file',
			'file_image',
			'file_location',
			'file_tag',
			'file_video',
			'file_vote',
			'tag'
		);

		$query = '';
		foreach ($tables as $table) {
			$table_name = '#__fwsg_'.$table;
			$table = $db->getPrefix().'fwsg_'.$table;
			$db->setQuery('SHOW CREATE TABLE `'.$table.'`');
			if ($obj = $db->loadAssoc()) {
				$query .= 'DROP TABLE IF EXISTS `'.$table_name."`;\n";
				$query .= str_replace('CREATE TABLE ', 'CREATE TABLE IF NOT EXISTS ', str_replace($table, $table_name, $obj['Create Table'])).";\n\n";
			}

			$db->setQuery('SHOW COLUMNS FROM `'.$table.'`');
			if ($cols = $db->loadAssocList()) {
				foreach ($cols as $i=>$col) $cols[$i]['is_numeric'] = preg_match("/^(\w*int|year)/", $col['Type'])?true:false;
				$db->setQuery('SELECT * FROM `'.$table.'`');
				if ($list = $db->loadAssocList()) {
					foreach ($list as $row) {
						$query .= 'INSERT INTO `'.$table_name.'` VALUES (';
						foreach ($cols as $i=>$col) {
							if ($i) $query .= ',';
							if ($col['Field'] and $col['is_numeric']) $query .= (int)$row[$col['Field']];
							elseif (is_null($row[$col['Field']])) $query .= 'NULL';
							else $query .= $db->quote($row[$col['Field']]);
						}
						$query .= ");\n";
					}
				}
			}
			$query .= "\n";
		}
		JFile::write($path.$folder.'/gallery.sql', $query);

		$db->setQuery('SELECT id, name, username, email, password, block, registerDate, params FROM #__users AS u WHERE EXISTS(SELECT * FROM #__fwsg_category AS p WHERE p.user_id = u.id) OR EXISTS(SELECT * FROM #__fwsg_file AS p WHERE p.user_id = u.id)');
		if ($list = $db->loadAssocList() and $fh = fopen($path.$folder.'/users.csv', 'w')) {
			foreach ($list as $row) {
				fputcsv($fh, $row);
			}
			fclose($fh);
		}
		
		$db->setQuery('SELECT params FROM `#__extensions` WHERE `type` = \'component\' AND `element` = \'com_fwgallery\'');
		if ($params = $db->loadResult()) {
			JFile::write($path.$folder.'/settings.txt', $params);
		}

		return $folder;
	}
	function storeExportData() {
		set_time_limit(600);
		$input = JFactory::getApplication()->input;
		if ($folder = $input->getString('folder')) {
			$src_path = str_replace('\\', '/', JPATH_SITE).'/tmp/'.$folder.'/';
			if (!is_dir($src_path)) {
				$this->setError(JText::_('FWMG_NO_DATA_FOLDER'));
				return;
			}
			$trg_path = str_replace('\\', '/', JPATH_SITE).'/media/com_fwgallery/backups/';
			if (!file_exists($trg_path)) {
				JFolder::create($trg_path);
			}
			if (!file_exists($trg_path)) {
				$this->setError(JText::_('FWMG_CANT_CREATE_EXPORT_FOLDER'));
				return;
			}

			$images = $input->getInt('images');
			jimport('joomla.filesystem.folder');
			jimport('joomla.filesystem.file');

			$files = JFolder::files($src_path, '.*', true, true);
			if ($files) {
				$filename = 'fwmg_data_'.($images?'files_':'').date('d_M_Y_His').'.zip';
				$zip = new ZipArchive;
				if ($zip->open($trg_path.$filename, ZipArchive::CREATE) === TRUE) {
					foreach ($files as $file) {
						$loc_path = str_replace($src_path, '', str_replace('\\', '/', $file));
						$zip->addFile($file, $loc_path);
					}
					if ($images) {
						$src_path = str_replace('\\', '/', JPATH_SITE).'/media/com_fwgallery/';
						$files = JFolder::files($src_path, '.*', true, true);
						if ($files) {
							foreach ($files as $file) {
								$loc_path = str_replace($src_path, '', str_replace('\\', '/', $file));
								if (preg_match('#fwmg_data.*zip$#', $loc_path)) {
									continue;
								}
								$zip->addFile($file, $loc_path);
							}
						}
					}
					$zip->close();
					JFolder::delete(JPATH_SITE.'/tmp/'.$folder);
					return $filename;
				}
			} else {
				$this->setError(JText::_('FWMG_NO_DATA_EXPORT'));
			}
		} else {
			$this->setError(JText::_('FWMG_NO_FOLDER_TO_EXPORT'));
		}
	}
	function getBackupsList() {
		jimport('joomla.filesystem.folder');
		$src_path = str_replace('\\', '/', JPATH_SITE).'/media/com_fwgallery/backups';
		return is_dir($src_path)?JFolder::files($src_path, '\.zip$'):array();
	}
	function backupRestore() {
		set_time_limit(600);
		$input = JFactory::getApplication()->input;
		if ($filename = str_replace(array('/', '\\'), '', $input->getString('filename'))) {
			$path = JPATH_SITE.'/media/com_fwgallery/backups/';
			if (file_exists($path.$filename)) {
				jimport('joomla.filesystem.folder');
				jimport('joomla.filesystem.file');
				$trg_path = JPATH_SITE.'/media/com_fwgallery/';
				if (strpos('_data_images_', $filename) !== false) {
					$dirs = JFolder::folders($trg_path);
					foreach ($dirs as $dir) {
						if (is_dir($trg_path.$dir)) {
							JFolder::delete($trg_path.$dir);
						}
					}
				}
				$zip = new ZipArchive;
				if ($zip->open($path.$filename) === TRUE) {
					$zip->extractTo($trg_path);
					$zip->close();
/*
					$files = JFolder::files($trg_path, '.', true, true);
					if ($files) {
						foreach ($files as $file) {
							$ext = strtolower(JFile::getExt($file));
							if (!in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'txt', 'csv', 'sql', 'pdf', 'zip', 'flv', 'swf'))) {
								JFile::delete($file);
							}
						}
					}
*/
					$db = JFactory::getDBO();
					if (file_exists($trg_path.'gallery.sql')) {
						$buff = file_get_contents($trg_path.'gallery.sql');
						$queries = fwgHelper::splitSql($buff);

						$err = $total = 0;
						foreach ($queries as $query) {
							if (trim($query)) {
								$db->setQuery($query);
								if (!$db->execute()) {
									$err++;
								}
								$total++;
							}
						}

						JFile::delete($trg_path.'gallery.sql');
					}
					if (file_exists($trg_path.'settings.txt')) {
						$params = JComponentHelper::getParams('com_fwgallery');
						$params->loadFile($trg_path.'settings.txt');
						$db->setQuery('UPDATE `#__extensions` SET params = '.$db->quote($params->toString()).' WHERE `element` = \'com_fwgallery\' AND `type` = \'component\'');
						$db->execute();
						JFile::delete($trg_path.'settings.txt');
					}
					if (file_exists($trg_path.'users.csv')) {
						if ($fh = fopen($trg_path.'users.csv', 'r')) {
							while ($row = fgetcsv($fh, 10000)) {
								/* id, name, username, email, password, block, registerDate, params */
								$data = array(
									'name' => $row[1],
									'username' => $row[2],
									'email' => $row[3],
									'password' => $row[4],
									'block' => $row[5],
									'registerDate' => $row[6],
									'params' => json_decode($row[7], true),
									'groups' => array(2)
								);
								$db->setQuery('SELECT id FROM `#__users` AS u WHERE u.email = '.$db->quote($data['email']).' OR u.username = '.$db->quote($data['email']));
								$uid = $db->loadResult();
								if (!$uid) {
									$user = clone(JFactory::getUser(0));
									if ($user->bind($data) and $user->save()) {
										$uid = $user->id;
									}
								}
								if ($uid and $uid != $row[0]) {
									$tables = array('category', 'file');
									foreach ($tables as $table) {
										$db->setQuery('UPDATE #__fwsg_'.$table.' SET user_id = '.(int)$uid.' WHERE user_id = '.(int)$row[0]);
										$db->execute();
									}
								}
							}
							fclose($fh);
						}
						JFile::delete($trg_path.'users.csv');
					}
					return true;
				}
			} else {
				$this->setError(JText::_('FWMG_FILE_NOT_FOUND_TO_RESTORE'));
			}
		} else {
			$this->setError(JText::_('FWMG_NO_FILENAME_TO_RESTORE'));
		}
	}
	function backupDelete() {
		$input = JFactory::getApplication()->input;
		if ($filename = str_replace(array('/', '\\'), '', $input->getString('filename'))) {
			$path = JPATH_SITE.'/media/com_fwgallery/backups/';
			if (file_exists($path.$filename)) {
				jimport('joomla.filesystem.file');
				if (JFile::delete($path.$filename)) {
					return true;
				} else {
					$this->setError(JText::_('FWMG_CANT_DELETE_FILE'));
				}
			} else {
				$this->setError(JText::_('FWMG_FILE_NOT_FOUND_TO_DELETE'));
			}
		} else {
			$this->setError(JText::_('FWMG_NO_FILENAME_TO_DELETE'));
		}
	}
	function backupUpload() {
		$input = JFactory::getApplication()->input;
		if ($file = $input->files->get('upload', array(), 'raw') and !empty($file['name']) and empty($file['error'])) {
			jimport('joomla.filesystem.folder');
			jimport('joomla.filesystem.file');
			$path = JPATH_SITE.'/media/com_fwgallery/backups/';
			if (!file_exists($path)) {
				JFolder::create($path);
			}
			if (JFile::copy($file['tmp_name'], $path.$file['name'])) {
				return $file['name'];
			} else {
				$this->setError(JText::_('FWMG_CANT_COPY_UPLOADED_FILE'));
			}
		} else {
			$this->setError(JText::_('FWMG_NO_FILE_TO_UPLOAD'));
		}
	}
}
