<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license https://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link https://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

class fwGalleryModelFwGallery extends JModelLegacy {
	function localStat() {
		$data = null;
		if (is_null($data)) {
			$db = JFactory::getDBO();
			$query = '
SELECT
	(SELECT COUNT(*) FROM #__fwsg_category) AS ct,
	(SELECT COUNT(*) FROM #__fwsg_category WHERE published = 1) AS cp,
	(SELECT COUNT(*) FROM #__fwsg_file) AS files_qty,
	'.(class_exists('JVersion')?'(SELECT COUNT(*) FROM #__menu WHERE `client_id` = 0 AND `link` LIKE \'%com_fwgallery&view=fwgallery%\')':'1').' AS menus_qty,
	(SELECT COUNT(*) FROM #__fwsg_file WHERE `type` = \'image\') AS it,
	(SELECT COUNT(*) FROM #__fwsg_file WHERE `type` = \'image\' AND published = 1) AS ip,
	(SELECT SUM(`size`) FROM #__fwsg_file_image WHERE `size` IS NOT NULL) AS `is`';
			$app = JFactory::getApplication();
			$app->triggerEvent('ongetLocalStatExtraFields', array('com_fwgallery', &$query));

			$db->setQuery($query);
			$data = $db->loadObject();
			$size = 0;
			foreach ($data as $key=>$val) {
				/* sum up all sizes */
				if ($key[strlen($key) - 1] == 's') {
					$size += $val;
					unset($data->$key);
				}
			}
			$data->fs = $size;
		}
		return $data;
	}
	function quickCheck($check_orphans=true) {
		$data = new stdclass;
		jimport('joomla.filesystem.file');
		$path = JPATH_SITE.'/media/';
		if (!file_exists($path)) {
			JFile::write($path.'index.html', $html='<html><body></body></html>');
		}
		$data->media_folder_exists = file_exists($path);
		$path .= 'com_fwgallery/';
		if (!file_exists($path)) {
			JFile::write($path.'index.html', $html='<html><body></body></html>');
		}
		$data->gallery_folder_exists = file_exists($path);
		$data->gallery_folder_writeable = is_writable($path);

		$path = JPATH_SITE.'/cache/fwgallery/images/';
		if (!file_exists($path)) {
			JFolder::create($path);
		}
		$data->cache_folder_exists = file_exists($path);
		$data->cache_folder_writeable = is_writable($path);

		$data->gd_installed = function_exists('gd_info');
		$data->exif_installed = function_exists('exif_read_data');
		$data->version = $this->getVersion();

		$data->test_passed = true;
		foreach ($data as $key=>$val) {
			if (!$val) {
				$data->test_passed = false;
				break;
			}
		}

		$data->orphans = 0;
		if ($check_orphans and $files = $this->getUnregisteredFiles()) {
			foreach ($files as $file) {
				$data->orphans += filesize($file);
			}
		}

		return $data;
	}
	function deleteUnregisteredFiles() {
		$deleted = $skipped = 0;
		$msg = JText::_('FWMG_NO_FILES_WERE_DELETED');
		if ($files = $this->getUnregisteredFiles()) {
			jimport('joomla.filesystem.file');
			foreach ($files as $file) {
				if (is_writable($file) and JFile::delete($file)) {
					$deleted++;
				} else {
					$skipped++;
				}
			}
			$msg = '';
			if ($deleted) {
				$msg = $deleted.' '.JText::_('FWMG_FILES_DELETED');
			}
			if ($skipped) {
				$msg = ($msg?', ':'').$skipped.' '.JText::_('FWMG_FILES_SKIPPED');
			}
		}
		$this->setError($msg);
		return $deleted;
	}
	function getUnregisteredFiles() {
		jimport('joomla.filesystem.folder');
		$path = JPATH_SITE.'/media/com_fwgallery';
		$files = JFolder::files($path, '.', true, true, array('.svn', 'CVS', '.DS_Store', '__MACOSX', 'index.html'));
		
		foreach ($files as $i=>$file) {
			if (strpos(str_replace('\\', '/', $file), '/backups/')) {
				unset($files[$i]);
			}
		}

		$params = JComponentHelper::getParams('com_fwgallery');
		if ($waremark = $params->get('watermark_file')) {
			foreach ($files as $i=>$file) {
				if (strpos($file, $waremark)) {
					unset($files[$i]);
					break;
				}
			}
		}

        $app = JFactory::getApplication();
		$db = JFactory::getDBO();

        $extra_select = $app->triggerEvent('ongetFileExtraFields', array('com_fwgallery'));
        $extra_from = $app->triggerEvent('ongetFileExtraTables', array('com_fwgallery'));

		$types = array(
			'image'
		);
        if ($buff = $app->triggerEvent('ongetType', array('com_fwgallery'))) {
    		foreach ($buff as $val) {
    			if ($val) {
    				$types[] = $val;
    			}
    		}
        }
		$where[] = "f.`type` IN ('".implode("','", $types)."')";

        $db->setQuery('
SELECT
    f.id,
    f.type,
	fi.sys_filename AS _sys_filename
    '.implode('', $extra_select).'
FROM
    #__fwsg_file AS f
    LEFT JOIN #__fwsg_file_image AS fi ON fi.file_id = f.id
    '.implode('', $extra_from).'
WHERE
	'.implode(' AND ', $where)
		);
		if ($list = $db->loadObjectList()) {
			foreach ($list as $row) {
				foreach ($files as $i=>$file) {
					if ($row->_sys_filename and strpos($file, $row->_sys_filename)) {
						unset($files[$i]);
					}
				}
				if ($row->type != 'image') {
					$app->triggerEvent('oncheckFileRegistration', array('com_fwgallery', $row, &$files));
				}
			}
		}

		$db->setQuery('SELECT DISTINCT media_code, sys_filename FROM `#__fwsg_category`');
		if ($list = $db->loadObjectList()) {
			foreach ($list as $row) {
				if ($row->sys_filename or $row->media_code) {
					foreach ($files as $i=>$file) {
						if ($row->media_code and strpos($file, $row->media_code)) {
							unset($files[$i]);
							break;
						} elseif ($row->sys_filename and strpos($file, $row->sys_filename)) {
							unset($files[$i]);
							break;
						}
					}
				}
			}
		}
		return $files;
	}
	function loadParamsObj() {
    	$obj = new stdclass;
    	$obj->params = JComponentHelper::getParams('com_fwgallery');
        return $obj;
	}
	function getDescription() {
		$buff = file_get_contents(JPATH_COMPONENT_ADMINISTRATOR.'/fwgallery.xml');
		if (preg_match('#<description><\!\[CDATA\[(.*?)\]\]></description>#msi', $buff, $match)) {
			return $match[1];
		}
	}
	function getVersion() {
		$buff = file_get_contents(JPATH_COMPONENT_ADMINISTRATOR.'/fwgallery.xml');
		if (preg_match('#<version>(.*?)</version>#msi', $buff, $match)) {
			return $match[1];
		}
	}
	function store($params) {
    	$db = JFactory::getDBO();

		$cache = JFactory::getCache('_system', 'callback');
    	$cache->clean();

		$db->setQuery('UPDATE `#__update_sites` SET extra_query = '.$db->quote('code='.urlencode(trim($params->get('update_code')))).' WHERE name LIKE \'FW Gallery%\'');
		$db->execute();

		$db->setQuery('TRUNCATE TABLE #__updates');
		$db->execute();
		$db->setQuery('UPDATE `#__update_sites` SET last_check_timestamp = 0');
		$db->execute();

    	$db->setQuery('UPDATE `#__extensions` SET params = '.$db->quote($params->toString()).' WHERE `element` = \'com_fwgallery\' AND `type` = \'component\'');
    	return $db->execute();
	}
	function checkUpdate() {
		$data = new stdclass;
		$data->loc_version = $this->getVersion();
		if ($buff = fwgHelper::request(FWMG_UPDATE_SERVER.'/index.php?option=com_fwsales&view=updates&layout=package&format=raw&package='.FWMG_UPDATE_NAME.'&dummy=extension.xml')) {
			if (preg_match('#<extension.*version="([^"]*)"#', $buff, $match)) {
				$data->rem_version = $match[1];
			}
		} else $this->setError(JText::_('FWMG_NO_RESPONSE_FROM_REMOTE_SERVER'));
		return $data;
	}
	function verifyCode() {
		$result = new stdclass;
		$input = JFactory::getApplication()->input;
		if ($code = $input->getString('code')) {
			$j_version = $langs_qty = '';
			if (class_exists('JVersion')) {
				$jv = new JVersion();
				$j_version = $jv->getShortVersion();
			} else {
				global $wp_version;
			}
			$lang = JFactory::getLanguage();
			$db = JFactory::getDBO();
			$db->setQuery('SELECT COUNT(*) FROM `#__languages`');
			$langs_qty = $db->loadResult();
			$db->setQuery('SELECT COUNT(*) FROM `#__fwsg_category` AS c WHERE EXISTS(SELECT * FROM `#__fwsg_file` AS p WHERE p.published=1 AND p.category_id = c.id)');
			$categories_qty = $db->loadResult();
			$db->setQuery('SELECT COUNT(*) FROM `#__fwsg_file` WHERE published=1');
			$files_qty = $db->loadResult();

			$data = (object)array(
				'code' => $code,
				'package' => FWMG_UPDATE_NAME,
				'host' => JURI::root(false),
				'params' => array(
					'joomla_version' => $j_version,
					'wp_version' => empty($wp_version)?'':$wp_version,
					'version' => $this->getVersion(),
					'language' => $lang->getTag(),
					'langs_qty' => $langs_qty,
					'categories_qty' => $categories_qty,
					'files_qty' => $files_qty,
					'exts' => array()
				)
			);
			$db->setQuery('
SELECT
	`name`,
	`element`,
	`type`,
	`type` AS subtype,
	`folder`,
	`enabled`
FROM
	#__extensions
WHERE
	manifest_cache LIKE \'%Fastw3b%\'
	AND
	NOT `type` IN (\'package\', \'template\')
ORDER BY
	`type`,
	`folder`,
	`name`'
			);
			if ($list = $db->loadObjectList()) {
				foreach ($list as $i=>$row) {
					$filename = "";
					if ($row->type == "component") {
						$filename = FWMG_COMPONENT_ADMINISTRATOR."/".str_replace("com_", "", $row->element).".xml";
					} elseif ($row->type == "plugin") {
						$filename = JPATH_SITE."/plugins/".$row->folder."/".$row->element."/".$row->element.".xml";
					} elseif ($row->type == "module") {
						$filename = JPATH_SITE."/modules/".$row->element."/".$row->element.".xml";
					}
					if ($filename and file_exists($filename)) {
						$buff = file_get_contents($filename);
						if (preg_match("#<version>(.*?)</version>#msi", $buff, $match)) {
							$list[$i]->loc_version = $match[1];
						}
					}
				}
				$data->params["exts"] = $list;
			}
			if ($buff = fwgHelper::request(FWMG_UPDATE_SERVER.'/index.php?option=com_fwsales&view=updates&layout=verify_code&format=raw', 'post', $data)) {
				$tmp = json_decode($buff);
				if ($tmp) {
					if (!empty($tmp->msg)) $this->setError($tmp->msg);
					$result->user_name = empty($tmp->user_name)?'':$tmp->user_name;
					$result->user_avatar = empty($tmp->user_avatar)?'':$tmp->user_avatar;
					$result->user_email = empty($tmp->user_email)?'':$tmp->user_email;
					if (!empty($tmp->verified)) {
						$result->verified = 1;
						$params = JComponentHelper::getParams('com_fwgallery');
						$params->set('update_code', $code);
						$params->set('verified_code', $code);
						$params->set('user_name', $result->user_name);
						$params->set('user_avatar', $result->user_avatar);
						$params->set('user_email', $result->user_email);
						$this->store($params);
					}
				} elseif (preg_match('#<title>([^<]+)<#msi', $buff, $match)) {
					$this->setError($match[1]);
				}
			} else $this->setError(JText::_('FWMG_NO_RESPONSE_FROM_REMOTE_SERVER'));
		} else $this->setError(JText::_('FWMG_NO_CODE_TO_VERIFY'));
		return $result;
	}
	function getChangelog() {
		if ($buff = fwgHelper::request(FWMG_UPDATE_SERVER.'/index.php?option=com_fwsales&view=updates&layout=changelog&format=raw&package='.FWMG_UPDATE_NAME)) {
			$tmp = json_decode($buff);
			if (!empty($tmp->msg)) $this->setError($tmp->msg);
			if ($tmp->result) {
				return $tmp->result;
			}
		}
	}
	function revokeCode() {
		$data = new stdclass;
		$params = JComponentHelper::getParams('com_fwgallery');
		$params->set('verified_code', '');
		$params->set('update_code', '');
		$data->result = $this->store($params);
		return $data;
	}
	function updatePackage() {
		$params = JComponentHelper::getParams('com_fwgallery');
		$code = $params->get('update_code');
		$result = false;
		if ($code) {
			if ($code == $params->get('verified_code')) {
				if ($buff = fwgHelper::request(FWMG_UPDATE_SERVER.'/index.php?option=com_fwsales&view=updates&layout=package&format=raw&package='.FWMG_UPDATE_NAME.'&dummy=extension.xml')) {
					if (preg_match('#detailsurl="([^"]*)"#', $buff, $match)) {
						$url = str_replace('&amp;', '&', $match[1]);
						if ($buff = fwgHelper::request($url)) {
							if (preg_match('#<downloadurl[^>]*>([^<]+)</downloadurl>#', $buff, $match)) {
								$url = str_replace('&amp;', '&', $match[1]).'&code='.urlencode($code);
								if ($buff = fwgHelper::request($url)) {
									jimport('joomla.filesystem.file');
									jimport('joomla.filesystem.folder');
									$path = JPATH_SITE.'/tmp/';

									$filename = '';
									do {
										$filename = 'inst'.rand().'.zip';
									} while (file_exists($path.$filename));

									if (JFile::write($path.$filename, $buff)) {
										$package = JInstallerHelper::unpack($path.$filename, true);
										if (!empty($package['dir'])) {
											$installer = JInstaller::getInstance();
											if ($result = $installer->install($package['dir'])) {
												$this->setError(JText::_('FWMG_UPDATED_SUCCESFULLY'));
											} else {
												$this->setError(JText::_('FWMG_NOT_UPDATED'));
											}
											if (file_exists($package['dir'])) JFolder::delete($package['dir']);
										}
										if (file_exists($path.$filename)) JFile::delete($path.$filename);
									} else $this->setError(JText::_('FWMG_CANT_WRITE_FILE'));
								} else $this->setError(JText::_('FWMG_SERVER_REFUSES_DOWNLOAD'));
							} else $this->setError(JText::_('FWMG_WRONG_RESPONSE_FORMAT_FROM_REMOTE_SERVER'));
						} else $this->setError(JText::_('FWMG_NO_RESPONSE_FROM_REMOTE_SERVER'));
					} else $this->setError(JText::_('FWMG_WRONG_RESPONSE_FORMAT_FROM_REMOTE_SERVER'));
				} else $this->setError(JText::_('FWMG_NO_RESPONSE_FROM_REMOTE_SERVER'));
			} else $this->setError(JText::_('FWMG_CODE_NOT_VERIFIED'));
		} else $this->setError(JText::_('FWMG_NO_UPDATE_CODE'));
		return $result;
	}

	/* modal support */
    function _collectFilesWhere() {
        $where = array(
            'f.type = \'image\''
        );
		$input = JFactory::getApplication()->input;
        if ($data = $input->getString('search')) {
        	$db = JFactory::getDBO();
            $where[] = '(f.name LIKE \'%'.$db->escape($data).'%\')';
        }
        if ($data = $input->getInt('category_id')) {
            $where[] = 'f.category_id = '.$data;
        }
        return $where?('WHERE '.implode(' AND ', $where)):'';
    }
    function loadFiles() {
        $app = JFactory::getApplication();
		$input = $app->input;
        $db = JFactory::getDBO();
        $db->setQuery('
SELECT
    f.*,
    u.name AS _user_name,
    p.name AS _category_name
FROM
    #__fwsg_file AS f
    LEFT JOIN #__fwsg_category AS p ON f.category_id = p.id
    LEFT JOIN #__users AS u ON u.id = f.user_id
'.$this->_collectFilesWhere().'
ORDER BY
    p.ordering,
    f.ordering',
    		$input->getUInt('limitstart', 0),
    		$input->getUInt('limit', $app->getCfg('list_limit'))
		);
		return $db->loadObjectList();
	}
    function getFilesQty() {
        $db = JFactory::getDBO();
        $db->setQuery('
SELECT
    COUNT(*)
FROM
    #__fwsg_file AS f
    LEFT JOIN #__fwsg_category AS p ON f.category_id = p.id
    '.$this->_collectFilesWhere());
        return $db->loadResult();
    }
	function getFilesPagination() {
        $app = JFactory::getApplication();
		$input = $app->input;
        jimport('joomla.html.pagination');
        return new JPagination(
        	$this->getFilesQty(),
    		$input->getUInt('limitstart', 0),
    		$input->getUInt('limit', $app->getCfg('list_limit'))
    	);
	}
    function _collectGalleriesWhere() {
        $where = array();
        $db = JFactory::getDBO();

		$input = JFactory::getApplication()->input;
        if ($data = $input->getString('search')) {
            $where[] = "p.name LIKE '%".$db->escape($data)."%'";
        }

        return $where?('WHERE '.implode(' AND ', $where)):'';
    }
    function getGalleries() {
        $db = JFactory::getDBO();
        $app = JFactory::getApplication();
		$input = $app->input;
        $limitstart = (int)$input->getInt('limitstart', 0);
        $limit = (int)$input->getInt('limit', $app->getCfg('list_limit'));
		$list = array();

    	if ($where = $this->_collectGalleriesWhere()) {
	        $db->setQuery('
SELECT
    p.*,
	p.name AS treename,
	p.user_id AS _user_id,
    u.name AS _user_name,
	(SELECT g.title FROM #__viewlevels AS g WHERE g.id = p.`access`) AS _group_name,
	(SELECT COUNT(*) FROM #__fwsg_file AS f WHERE f.category_id = p.id AND f.`type` = \'image\') AS _qty
FROM
    #__fwsg_category AS p
    LEFT JOIN #__users AS u ON u.id = p.user_id
'.$where.'
ORDER BY
	p.parent,
    p.ordering',
    			$limitstart,
    			$limit
			);
	        $list = $db->loadObjectList();
    	} else {
	        $db->setQuery('
SELECT
    p.*,
    u.name AS _user_name,
	(SELECT g.title FROM #__viewlevels AS g WHERE g.id = p.`access`) AS _group_name,
	(SELECT COUNT(*) FROM #__fwsg_file AS f WHERE f.category_id = p.id) AS _qty
FROM
    #__fwsg_category AS p
    LEFT JOIN #__users AS u ON u.id = p.user_id
ORDER BY
	p.parent,
    p.ordering'
			);
	        if ($rows = $db->loadObjectList()) {
	            $children = array();
	            foreach ($rows as $v) {
	                $pt = $v->parent;
	                $list = @$children[$pt] ? $children[$pt] : array();
	                array_push( $list, $v );
	                $children[$pt] = $list;
	            }
		        $levellimit = 10;
	            $list = JHTML::_('fwsgCategory.treerecurse', 0, '', array(), $children, max( 0, $levellimit-1 ) );
	            if ($limit) $list = array_slice($list, $limitstart, $limit);
	        }
    	}

        return $list;
	}
    function getGalleriesQty() {
        $db = JFactory::getDBO();
        $db->setQuery('SELECT COUNT(*) FROM `#__fwsg_category` AS p '.$this->_collectGalleriesWhere());
        return $db->loadResult();
    }
	function getGalleriesPagination() {
        $app = JFactory::getApplication();
		$input = $app->input;
        jimport('joomla.html.pagination');
        return new JPagination(
        	$this->getGalleriesQty(),
    		$input->getUInt('limitstart', 0),
    		$input->getUInt('limit', $app->getCfg('list_limit'))
    	);
	}
	function save() {
		$category = $this->getTable('category');
        $user = JFactory::getUser();
        if ($user->authorise('core.create', 'com_content')) {
			$input = JFactory::getApplication()->input;
            $id = $input->getInt('id', 0, 'post');
            if ($id and !$category->load($id)) $input->set('id', 0);

            if ($category->bind($input->getArray(array(), null, 'RAW'), array('user_id', 'created', 'ordering', 'color')) and $category->check() and $category->store()) {
    			$this->setError(JText::sprintf('FWMG_GALLERY_SUCCESSFULLY_ADDED', $category->name));
    			return true;
    		} else $this->setError($category->getError());
        } else $this->setError(JText::_('FWMG_CONTENT_ADMINS_ONLY_ACCESS'));
	}
	function checkPreviousVersion() {
		$db = JFactory::getDBO();
		$tables = $db->getTableList();
		if (in_array($db->getPrefix().'fwmg_projects', $tables)) {
			$db->setQuery('SELECT COUNT(*) FROM #__fwmg_projects');
			if ($db->loadResult()) {
				$size = 0;
				$path = JPATH_SITE.'/images/com_fwgallery';
				if (is_dir($path)) {
					jimport('joomla.filesystem.folder');

					$files = JFolder::files($path, '.*', true, true);
					if ($files) {
						foreach ($files as $file) {
							$size += filesize($file);
						}
					}
				}
				return max(1, ceil($size/1048576))*1048576;
			}
		}
	}
	function importPrevData() {
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');

		set_time_limit(1200);
        ini_set('memory_limit', '2G');

		fwgHelper::clearImageCache();

		$input = JFactory::getApplication()->input;
		$db = JFactory::getDBO();
		$tables = $db->getTableList();
		$errors = 0;
		$src_path = JPATH_SITE.'/images/com_fwgallery/files/';
		$trg_path = JPATH_SITE.'/media/com_fwgallery/';

		if (!file_exists($trg_path) and !JFile::write($trg_path.'index.html', $buff = '<html><head><title></title></head><body></body></html>')) {
			$this->setError(JText::_('FWMG_CANT_CREATE_FOLDER').' '.$trg_path);
			return false;
		}
		if (!is_writable($trg_path)) {
			$this->setError(JText::_('FWMG_TARGET_FOLDER_NOT_WRITEABLE').' '.$trg_path);
			return false;
		}

		/* check watermark */
		$lparams = JComponentHelper::getParams('com_fwgallerylight');
		$params = JComponentHelper::getParams('com_fwgallery');
		if ($wmf = $lparams->get('watermark_file')) {
			$path = JPATH_SITE.'/images/com_fwgallery/';
			if (file_exists($path.$wmf)) {
				$params->set('watermark_file', $wmf);
				fwgHelper::storeConfig($params);

				if ($input->getInt('remove_previous_data')) {
					JFile::move($path.$wmf, $trg_path.$wmf);
				} else {
					JFile::copy($path.$wmf, $trg_path.$wmf);
				}
			}
		} elseif ($wmf = $params->get('watermark_file')) {
			$path = JPATH_SITE.'/images/com_fwgallery/';
			if (file_exists($path.$wmf)) {
				if ($input->getInt('remove_previous_data')) {
					JFile::move($path.$wmf, $trg_path.$wmf);
				} else {
					JFile::copy($path.$wmf, $trg_path.$wmf);
				}
			}
		}

		if (in_array($db->getPrefix().'fwmg_projects', $tables)) {
			$db->setQuery('SELECT * FROM `#__fwmg_projects`');
			if ($list = $db->loadObjectList()) {
				foreach ($list as $row) {
					$table = JTable::getInstance('Category', 'Table');
					if ($table->load($row->id)) {
						$file_exists = (($table->media == 'none' and $table->sys_filename and $tmp_path = fwgHelper::getImagePath($table->sys_filename) and file_exists($tmp_path.$table->sys_filename)) or ($table->media == 'mp4' and $table->media_code and $tmp_path = fwgHelper::getImagePath($table->media_code) and file_exists($tmp_path.$table->media_code)));

						if ($table->name == $row->name and $file_exists) {
							continue;
						} else {
							$table->delete($row->id);
						}
					}

					$db->setQuery('INSERT INTO `#__fwsg_category` SET
	`id` = '.(int)$row->id.',
	`published` = '.(int)$row->published.',
	`parent` = '.(int)$row->parent.',
	`ordering` = '.(int)$row->ordering.',
	`access` = '.(int)$row->gid.',
	`created` = '.$db->quote($row->created).',
	`updated` = '.$db->quote($row->updated).',
	`user_id` = '.(int)$row->user_id.',
	`name` = '.$db->quote($row->name).',
	`sys_filename` = '.$db->quote($row->sys_filename).',
	`filename` = '.$db->quote($row->filename).',
	`descr` = '.$db->quote($row->descr).',
	`alias` = '.$db->quote(JFilterOutput::stringURLSafe($row->name)).',
	`media` = '.$db->quote(empty($row->media)?'':$row->media).',
	`media_code` = '.$db->quote(empty($row->media_code)?'':$row->media_code).',
	`latitude` = '.$db->quote(empty($row->latitude)?'':$row->latitude).',
	`longitude` = '.$db->quote(empty($row->longitude)?'':$row->longitude)
					);
					if ($db->execute()) {
						if ($row->sys_filename and file_exists($src_path.$row->sys_filename)) {
							$trg_path = fwgHelper::getImagePath($row->sys_filename);
							if (!file_exists($trg_path) and !JFile::write($trg_path.'index.html', $buff = '<html><head><title></title></head><body></body></html>')) {
								$this->setError(JText::_('FWMG_CANT_CREATE_FOLDER').' '.$trg_path);
								return false;
							}

							if ($input->getInt('remove_previous_data')) {
								if (!JFile::move($src_path.$row->sys_filename, $trg_path.$row->sys_filename)) {
									$this->setError(JText::sprintf('FWMG_CANT_MOVE_FILE_FROM_TO', $src_path.$row->sys_filename, $trg_path.$row->sys_filename));
									return false;
								}
							} else {
								if (!JFile::copy($src_path.$row->sys_filename, $trg_path.$row->sys_filename)) {
									$this->setError(JText::sprintf('FWMG_CANT_COPY_FILE_FROM_TO', $src_path.$row->sys_filename, $trg_path.$row->sys_filename));
									return false;
								}
							}
						}
						if (!empty($row->media) and $row->media == 'mp4' and $row->media_code and file_exists($src_path.$row->media_code)) {
							$trg_path = fwgHelper::getImagePath($row->media_code);
							if (!file_exists($trg_path)) {
								JFile::write($trg_path.'index.html', $buff = '<html><head><title></title></head><body></body></html>');
							}
							if ($input->getInt('remove_previous_data')) {
								if (!JFile::move($src_path.$row->media_code, $trg_path.$row->media_code)) {
									$this->setError(JText::sprintf('FWMG_CANT_MOVE_FILE_FROM_TO', $src_path.$row->media_code, $trg_path.$row->media_code));
									return false;
								}
							} else {
								if (!JFile::copy($src_path.$row->media_code, $trg_path.$row->media_code)) {
									$this->setError(JText::sprintf('FWMG_CANT_COPY_FILE_FROM_TO', $src_path.$row->media_code, $trg_path.$row->media_code));
									return false;
								}
							}
						}
					} else {
						$this->setError(JText::_('FWMG_DB_ERROR').' '.$db->getError());
						return false;
					}
				}
			}
		}

		if (in_array($db->getPrefix().'fwmg_files', $tables)) {
			$db->setQuery('SELECT f.*, (SELECT p.user_id FROM `#__fwmg_projects` AS p WHERE p.id = f.project_id LIMIT 1) AS _user_id FROM `#__fwmg_files` AS f');

			if ($list = $db->loadObjectList()) {
				foreach ($list as $row) {
					$table = JTable::getInstance('File', 'Table');
					if ($table->load($row->id)) {
						/* skip copying if the name is same and file(s) attached */
						if ($table->name == $row->name and fwgHelper::fileDownloadable($table)) {
							continue;
						} else {
							$table->delete($row->id);
						}
					}

					$db->setQuery('INSERT INTO `#__fwsg_file` SET
	`id` = '.(int)$row->id.',
	`type` = '.$db->quote($row->type_id == 1?'image':'video').',
	`published` = '.(int)$row->published.',
	`ordering` = '.(int)$row->ordering.',
	`access` = 0,
	`created` = '.$db->quote($row->created).',
	`updated` = '.$db->quote($row->created).',
	`category_id` = '.(int)$row->project_id.',
	`user_id` = '.(int)$row->user_id.',
	`hits` = '.(int)(empty($row->hits)?0:$row->hits).',
	`downloads` = '.(int)(empty($row->downloads)?0:$row->downloads).',
	`name` = '.$db->quote($row->name).',
	`copyright` = '.$db->quote($row->copyright).',
	`descr` = '.$db->quote($row->descr).',
	`alias` = '.$db->quote(JFilterOutput::stringURLSafe($row->name))
					);
					if ($db->execute()) {
						if ($row->latitude and $row->longitude) {
							$db->setQuery('INSERT INTO `#__fwsg_file_location` SET
	`file_id` = '.(int)$row->id.',
	`latitude` = '.$db->quote($row->latitude).',
	`longitude` = '.$db->quote($row->longitude)
							);
							if (!$db->execute()) {
								$this->setError(JText::_('FWMG_DB_ERROR').' '.$db->getError());
								return false;
							}
						}
						if ($row->original_filename and file_exists($src_path.$row->_user_id.'/'.$row->original_filename)) {
							$trg_path = fwgHelper::getImagePath($row->original_filename);
							if (!file_exists($trg_path)) {
								JFile::write($trg_path.'index.html', $buff = '<html><head><title></title></head><body></body></html>');
							}
							if ($input->getInt('remove_previous_data')) {
								if (!JFile::move($src_path.$row->_user_id.'/'.$row->original_filename, $trg_path.$row->original_filename)) {
									$this->setError(JText::sprintf('FWMG_CANT_MOVE_FILE_FROM_TO', $src_path.$row->_user_id.'/'.$row->original_filename, $trg_path.$row->original_filename));
									return false;
								}
							} else {
								if (!JFile::copy($src_path.$row->_user_id.'/'.$row->original_filename, $trg_path.$row->original_filename)) {
									$this->setError(JText::sprintf('FWMG_CANT_COPY_FILE_FROM_TO', $src_path.$row->_user_id.'/'.$row->original_filename, $trg_path.$row->original_filename));
									return false;
								}
							}

							$info = (array)getimagesize($trg_path.$row->original_filename);
							$width = (int)JArrayHelper::getValue($info, 0);
							$height = (int)JArrayHelper::getValue($info, 1);
							$size = (int)filesize($trg_path.$row->original_filename);

							$db->setQuery('INSERT INTO #__fwsg_file_image SET
	`file_id` = '.$row->id.',
	`sys_filename` = '.$db->quote($row->original_filename).',
	`filename` = '.$db->quote($row->filename).',
	`alt` = '.$db->quote($row->name).',
	`width` = '.(int)$width.',
	`height` = '.(int)$height.',
	`size` = '.(int)$size
							);
							if (!$db->execute()) {
								$this->setError(JText::_('FWMG_DB_ERROR').' '.$db->getError());
								return false;
							}
						}
						if ($row->type_id == 2) {
							if ($row->media == 'mp4') {
								if ($row->media_code and file_exists($src_path.$row->_user_id.'/'.$row->media_code)) {
									$trg_path = fwgHelper::getImagePath($row->media_code);
									if (!file_exists($trg_path)) {
										JFile::write($trg_path.'index.html', $buff = '<html><head><title></title></head><body></body></html>');
									}
									if ($input->getInt('remove_previous_data')) {
										if (!JFile::move($src_path.$row->_user_id.'/'.$row->media_code, $trg_path.$row->media_code)) {
											$this->setError(JText::sprintf('FWMG_CANT_MOVE_FILE_FROM_TO', $src_path.$row->_user_id.'/'.$row->media_code, $trg_path.$row->media_code));
											return false;
										}
									} else {
										if (!JFile::copy($src_path.$row->_user_id.'/'.$row->media_code, $trg_path.$row->media_code)) {
											$this->setError(JText::sprintf('FWMG_CANT_COPY_FILE_FROM_TO', $src_path.$row->_user_id.'/'.$row->media_code, $trg_path.$row->media_code));
											return false;
										}
									}

									$db->setQuery('INSERT INTO `#__fwsg_file_video` SET
	`file_id` = '.(int)$row->id.',
	`media` = \'mp4\',
	`sys_filename` = '.$db->quote($row->media_code).',
	`filename` = '.$db->quote($row->media_code).',
	`size` = '.(int)filesize($trg_path.$row->media_code)
									);
									if (!$db->execute()) {
										$this->setError(JText::_('FWMG_DB_ERROR').' '.$db->getError());
										return false;
									}
								}
							} else {
								$db->setQuery('INSERT INTO `#__fwsg_file_video` SET
	`file_id` = '.(int)$row->id.',
	`media` = '.$db->quote($row->media).',
	`code` = '.$db->quote($row->media_code)
								);
								if (!$db->execute()) {
									$this->setError(JText::_('FWMG_DB_ERROR').' '.$db->getError());
									return false;
								}
							}
						}
					} else {
						$errors++;
					}
				}
			}
		}

		if (in_array($db->getPrefix().'fwmg_files_vote', $tables)) {
			$db->setQuery('DELETE FROM `#__fwsg_file_vote`');
			$db->execute();
			$db->setQuery('SELECT * FROM `#__fwmg_files_vote`');
			if ($list = $db->loadObjectList()) {
				foreach ($list as $row) {
					$db->setQuery('INSERT INTO `#__fwsg_file_vote` SET
	`file_id` = '.(int)$row->file_id.',
	`user_id` = '.(int)$row->user_id.',
	`value` = '.(int)$row->value.',
	`ipaddr` = '.$db->quote($row->ipaddr)
					);
					if (!$db->execute()) {
						$this->setError(JText::_('FWMG_DB_ERROR').' '.$db->getError());
						return false;
					}
				}
			}
		}
		if (in_array($db->getPrefix().'fwmg_files_thumbs_vote', $tables)) {
			$db->setQuery('SELECT * FROM `#__fwmg_files_thumbs_vote`');
			if ($list = $db->loadObjectList()) {
				foreach ($list as $row) {
					$db->setQuery('INSERT INTO `#__fwsg_file_vote` SET
	`file_id` = '.(int)$row->file_id.',
	`user_id` = '.(int)$row->user_id.',
	`value` = '.(int)(($row->value > 0)?5:1).',
	`ipaddr` = '.$db->quote($row->ipaddr)
					);
					if (!$db->execute()) {
						$this->setError(JText::_('FWMG_DB_ERROR').' '.$db->getError());
						return false;
					}
				}
			}
		}
		if (in_array($db->getPrefix().'fwmg_tags', $tables)) {
			$db->setQuery('SELECT * FROM `#__fwmg_tags`');
			if ($list = $db->loadObjectList()) {
				foreach ($list as $i=>$row) {
					$db->setQuery('SELECT id, name FROM #__fwsg_tag WHERE id = '.$row->id);
					if ($obj = $db->loadObject()) {
						if ($obj->name == $row->name) {
							continue;
						} else {
							$table = JTable::getInstance('Tag', 'Table');
							if ($table->load($row->id)) {
								$table->delete($row->id);
							}
						}
					}

					$db->setQuery('INSERT INTO `#__fwsg_tag` SET
	`id` = '.(int)$row->id.',
	`published` = 1,
	`ordering` = '.($i + 1).',
	`name` = '.$db->quote($row->name).',
	`alias` = '.$db->quote(JFilterOutput::stringURLSafe($row->name))
					);
					if (!$db->execute()) {
						$this->setError(JText::_('FWMG_DB_ERROR').' '.$db->getError());
						return false;
					}
				}
			}
		}
		if (in_array($db->getPrefix().'fwmg_projects_tags', $tables)) {
			$db->setQuery('DELETE FROM `#__fwsg_category_tag`');
			$db->execute();

			$db->setQuery('INSERT INTO `#__fwsg_category_tag` (category_id, tag_id) SELECT project_id, tag_id FROM `#__fwmg_projects_tags`');
			if (!$db->execute()) {
				$this->setError(JText::_('FWMG_DB_ERROR').' '.$db->getError());
				return false;
			}
		}

		if (in_array($db->getPrefix().'fwmg_files_tags', $tables)) {
			$db->setQuery('DELETE FROM `#__fwsg_file_tag`');
			$db->execute();

			$db->setQuery('INSERT INTO `#__fwsg_file_tag` (file_id, tag_id) SELECT file_id, tag_id FROM `#__fwmg_files_tags`');
			if (!$db->execute()) {
				$this->setError(JText::_('FWMG_DB_ERROR').' '.$db->getError());
				return false;
			}
		}

		fwgHelper::clearImageCache('');

		if (!$errors) {
			if ($input->getInt('remove_previous_data')) {
				$list = array('files', 'files_tags', 'files_thumbs_vote', 'files_vote', 'file_prices', 'projects', 'projects_tags', 'tags', 'types');
				foreach ($list as $table) {
					if (in_array($db->getPrefix().'fwmg_'.$table, $tables)) {
						$db->setQuery('TRUNCATE TABLE #__fwmg_'.$table);
						$db->execute();
					}
				}
				JFolder::delete($src_path);
			}
			$this->setError(JText::_('FWMG_DATA_IMPORT_COMPLETED'));
			return true;
		} else {
			$this->setError(JText::_('FWMG_THERE_WERE_ERRORS_CHECK_WRITE_ACCESS'));
		}
	}
	function getAddons($verify=true) {
		$params = JComponentHelper::getParams('com_fwgallery');
		if ($verify and $code = $params->get('verified_code')) {
			JFactory::getApplication()->input->set('code', $code);
			$this->verifyCode();
		}
		$model = JModelLegacy::getInstance('addon', 'fwGalleryModel');
		return $model->getPluginsData();
	}
	function checkNotInstalled($list) {
		$model = JModelLegacy::getInstance('addon', 'fwGalleryModel');
		return $model->checkNotInstalled($list);
	}
	function checkNotUpdated($list) {
		$model = JModelLegacy::getInstance('addon', 'fwGalleryModel');
		return $model->checkNotUpdated($list);
	}
	function clearCachedImages() {
		fwgHelper::clearImageCache();
		$this->setError(JText::_('FWMG_ADMIN_DASHBOARD_CACHE_CLEARED'));
	}
	function loadWizards() {
		$data = array(
			'wizards'=>array(),
			'install'=>array(),
			'update'=>array()
		);
		$params = JComponentHelper::getParams('com_fwgallery');
		$update_code = $params->get('update_code');
		$input = JFactory::getApplication()->input;
		if (!$update_code) {
			$dismissed = $input->cookie->get('fwmg_dismissed_1');
			$data['wizards'][] = (object)array(
				'ordering'=>1+($dismissed?10:0),
				'active'=>$dismissed?0:1,
				'text'=>JText::_('FWMG_WIZARD_VERIFY'),
				'title'=>JText::_('FWMG_WIZARD_VERIFY_TITLE'),
				'icon'=>'fal fa-user-lock',
				'buttons'=>($dismissed?'':'<button type="button" class="btn btn-secondary" onclick="fwmg_dismiss_wizard(1);">'.JText::_('FWMG_DISMISS').'</button>').'<button type="button" class="btn btn-action" onclick="jQuery(\'.fwa-user-no-login\').click()">'.JText::_('FWMG_VERIFY_ACCOUNT').'</button>'
			);
		}
		$quickcheck = $this->quickCheck($check_orphans=false);
		if (!$quickcheck->test_passed) {
			$dismissed = $input->cookie->get('fwmg_dismissed_2');
			$data['wizards'][] = (object)array(
				'ordering'=>2+($dismissed?10:0),
				'active'=>$dismissed?0:1,
				'text'=>JText::_('FWMG_WIZARD_QUICKCHECK'),
				'title'=>JText::_('FWMG_WIZARD_QUICKCHECK_TITLE'),
				'icon'=>'fal fa-tasks',
				'buttons'=>($dismissed?'':'<button type="button" class="btn btn-secondary" onclick="fwmg_dismiss_wizard(2);">'.JText::_('FWMG_DISMISS').'</button>').'<a class="btn btn-action" href="index.php?option=com_fwgallery&view=fwgallery#quick-check">'.JText::_('FWMG_QUICK_CHECK').'</a>'
			);
		}
		$stat = $this->localStat();
		if (empty($stat->ct)) {
			$dismissed = $input->cookie->get('fwmg_dismissed_3');
			$data['wizards'][] = (object)array(
				'ordering'=>3+($dismissed?10:0),
				'active'=>$dismissed?0:1,
				'text'=>JText::_('FWMG_WIZARD_CATEGORIES'),
				'title'=>JText::_('FWMG_WIZARD_CATEGORIES_TITLE'),
				'icon'=>'fal fa-folders',
				'buttons'=>($dismissed?'':'<button type="button" class="btn btn-secondary" onclick="fwmg_dismiss_wizard(3);">'.JText::_('FWMG_DISMISS').'</button>').'<button type="button" class="btn btn-action" onclick="jQuery(\'#fwmg-quick-categories\').modal(\'show\');">'.JText::_('FWMG_QUICK_CATEGORIES').'</button>'
			);
		}
		if (empty($stat->files_qty)) {
			$dismissed = $input->cookie->get('fwmg_dismissed_4');
			$data['wizards'][] = (object)array(
				'ordering'=>4+($dismissed?10:0),
				'active'=>$dismissed?0:1,
				'text'=>JText::_('FWMG_WIZARD_FILES'),
				'title'=>JText::_('FWMG_WIZARD_FILES_TITLE'),
				'icon'=>'fal fa-photo-video',
				'buttons'=>($dismissed?'':'<button type="button" class="btn btn-secondary" onclick="fwmg_dismiss_wizard(4);">'.JText::_('FWMG_DISMISS').'</button>').'<button type="button" class="btn btn-action" onclick="jQuery(\'#fwmg-batch-upload\').modal(\'show\');">'.JText::_('FWMG_BATCH_UPLOAD').'</button>'
			);
		}
		if (empty($stat->menus_qty)) {
			$dismissed = $input->cookie->get('fwmg_dismissed_5');
			$data['wizards'][] = (object)array(
				'ordering'=>5+($dismissed?10:0),
				'active'=>$dismissed?0:1,
				'text'=>JText::_('FWMG_WIZARD_MENU'),
				'title'=>JText::_('FWMG_WIZARD_MENU_TITLE'),
				'icon'=>'fal fa-desktop',
				'buttons'=>($dismissed?'':'<button type="button" class="btn btn-secondary" onclick="fwmg_dismiss_wizard(5);">'.JText::_('FWMG_DISMISS').'</button>').'<a class="btn btn-action" href="index.php?option=com_menus&view=item&client_id=0&layout=edit">'.JText::_('FWMG_CREATE_MENU').'</a>'
			);
		}
		if ($update_code) {
			$addons = $this->getAddons(false);
			if ($addons) {
				foreach ($addons as $row) {
					if ($row->loc_version and $row->rem_version and $row->loc_version != 'x.x.x' and $row->loc_version != $row->rem_version) {
						$data['update'][] = (object)array('name'=>$row->name, 'update_name'=>$row->update_name);
					}
					if (empty($row->installed) and !empty($row->installable)) {
						$data['install'][] = (object)array('name'=>$row->name, 'update_name'=>$row->update_name);
					}
				}
			}
			$has_current_subscr = false;
			if ($subscr = json_decode($params->get('subscr_data')) and is_array($subscr)) {
				$now = time();
				foreach ($subscr as $row) {
					if ($row->time_end > $now) {
						$has_current_subscr = true;
						break;
					}
				}
			}
			if (!$has_current_subscr) {
				$dismissed = $input->cookie->get('fwmg_dismissed_6');
				$data['wizards'][] = (object)array(
					'ordering'=>6+($dismissed?10:0),
					'active'=>$dismissed?0:1,
					'text'=>JText::_('FWMG_WIZARD_ADDONS'),
					'title'=>JText::_('FWMG_WIZARD_ADDONS_TITLE'),
					'icon'=>'fal fa-puzzle-piece',
					'buttons'=>($dismissed?'':'<button type="button" class="btn btn-secondary" onclick="fwmg_dismiss_wizard(6);">'.JText::_('FWMG_DISMISS').'</button>').'<a class="btn btn-action" href="https://fastw3b.net/products/fw-gallery#deals" target="_blank">'.JText::_('FWMG_CHOOSE').'</a>'
				);
    		}
			$dismissed = $input->cookie->get('fwmg_dismissed_7');
			$data['wizards'][] = (object)array(
				'ordering'=>7+($dismissed?10:0),
				'active'=>$dismissed?0:1,
				'text'=>JText::_('FWMG_WIZARD_CUSTOM'),
				'title'=>JText::_('FWMG_WIZARD_CUSTOM_TITLE'),
				'icon'=>'fal fa-code',
				'buttons'=>($dismissed?'':'<button type="button" class="btn btn-secondary" onclick="fwmg_dismiss_wizard(7);">'.JText::_('FWMG_DISMISS').'</button>').'<a class="btn btn-action" href="https://fastw3b.net/services/hire-joomla-developer" target="_blank">'.JText::_('FWMG_ORDER').'</a>'
			);
			if ($data['update']) {
				$dismissed = $input->cookie->get('fwmg_dismissed_8');
				$data['wizards'][] = (object)array(
					'ordering'=>8+($dismissed?10:0),
					'active'=>$dismissed?0:1,
					'text'=>JText::_('FWMG_WIZARD_UPDATE'),
					'title'=>JText::_('FWMG_WIZARD_UPDATE_TITLE'),
					'icon'=>'fal fa-sync',
					'buttons'=>($dismissed?'':'<button type="button" class="btn btn-secondary" onclick="fwmg_dismiss_wizard(8);">'.JText::_('FWMG_DISMISS').'</button>').'<button type="button" class="btn btn-action" onclick="jQuery(\'#fwmg-update\').modal(\'show\');">'.JText::_('FWMG_UPDATE').'</button>'
				);
			}
			if ($data['install']) {
				$dismissed = $input->cookie->get('fwmg_dismissed_9');
				$data['wizards'][] = (object)array(
					'ordering'=>9+($dismissed?10:0),
					'active'=>$dismissed?0:1,
					'text'=>JText::_('FWMG_WIZARD_INSTALL'),
					'title'=>JText::_('FWMG_WIZARD_INSTALL_TITLE'),
					'icon'=>'fal fa-download',
					'buttons'=>($dismissed?'':'<button type="button" class="btn btn-secondary" onclick="fwmg_dismiss_wizard(9);">'.JText::_('FWMG_DISMISS').'</button>').'<button type="button" class="btn btn-action" onclick="jQuery(\'#fwmg-install\').modal(\'show\');">'.JText::_('FWMG_INSTALL').'</button>'
				);
		    }
		}
		JArrayHelper::sortObjects($data['wizards'], 'ordering');
		return $data;
	}
}
