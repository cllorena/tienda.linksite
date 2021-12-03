<?php
/**
 * FW Gallery 6.7.2
 * @copyright C 2019 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

class fwGalleryModelTranslation extends JModelLegacy {
    function getUserState($name, $def='', $type='cmd') {
        $app = JFactory::getApplication();
        $context = 'com_fwgallery.language.';
        return $app->getUserStateFromRequest($context.$name, $name, $def, $type);
    }
	function getLanguageData($ext) {
		jimport('joomla.filesystem.file');
		$langs = fwgHelper::getInstalledLanguages();
		$lang = JArrayHelper::getValue($langs, fwgHelper::getLanguage());

		$search = $this->getUserState('search', '', 'string');

		$path = '';
		$filename = '';

		switch ($ext->type) {
			case 'component' :
				$type = (int)$this->getUserState('type', 0, 'int');
				$path = $type?FWMG_LANGUAGE_SITE:FWMG_LANGUAGE_ADMINISTRATOR;
				$filename = $ext->_element;
			break;
			case 'module' :
			case 'template' :
				$path = FWMG_LANGUAGE_SITE;
				$filename = $ext->_element;
			break;
			case 'plugin' :
				$path = FWMG_LANGUAGE_ADMINISTRATOR;
				$filename = 'plg_'.$ext->folder.'_'.$ext->_element;
			break;
		}

		$src_file = $path.'/language/en-GB/en-GB.'.$filename.'.ini';
		$src_buff = file_exists($src_file)?explode("\n", file_get_contents($src_file)):array();
		$result = array();
		foreach ($src_buff as $i=>$row) if (trim($row)) {
			$row = explode("=", trim($row), 2);
			if (count($row) < 2) continue;
			$row[1] = trim($row[1], '"');
			if (!empty($row[1]) and (!$search or ($search and (mb_stripos($row[0], $search) !== false or mb_stripos($row[1], $search) !== false)))) {
				$result[$row[0]] = array(
					'src' => $row[1],
					'trg' => ''
				);
			}
		}

		$trg_file = $path.'/language/'.$lang->tag.'/'.$lang->tag.'.'.$filename.'.ini';
		$trg_buff = file_exists($trg_file)?explode("\n", file_get_contents($trg_file)):array();
		foreach ($trg_buff as $i=>$row) if (trim($row)) {
			$row = explode("=", trim($row), 2);
			if (!empty($row[1]) and (!$search or ($search and (mb_stripos($row[0], $search) !== false or mb_stripos($row[1], $search) !== false)))) {
				if (isset($result[$row[0]])) {
					$result[$row[0]]['trg'] = trim($row[1], '"');
				} else {
					$found = false;
					foreach ($src_buff as $srow) {
						$srow = explode('=', trim($srow), 2);
						if ($srow[0] == $row[0]) {
							$found = true;
							$result[$row[0]] = array(
								'src' => trim($srow[1], '"'),
								'trg' => trim($row[1], '"')
							);
							break;
						}
					}
					if (!$found) {
						$result[$row[0]] = array(
							'src' => '',
							'trg' => trim($row[1], '"')
						);
					}
				}
			}
		}

		/* load overrides */
		$trg_file = $path.'/language/overrides/'.$lang->tag.'.override.ini';
		$trg_buff = file_exists($trg_file)?explode("\n", file_get_contents($trg_file)):array();
		foreach ($trg_buff as $i=>$row) if (trim($row)) {
			$row = explode("=", trim($row), 2);
			if (!empty($row[1]) and (!$search or ($search and (mb_stripos($row[0], $search) !== false or mb_stripos($row[1], $search) !== false)))) {
				if (isset($result[$row[0]])) {
					$result[$row[0]]['trg'] = trim($row[1], '"');
					$result[$row[0]]['override'] = true;
				}
			}
		}

		return $result;
	}
	function getLanguageOverridesFilename() {
		$langs = fwgHelper::getInstalledLanguages();
		$lang = JArrayHelper::getValue($langs, fwgHelper::getLanguage());
		$exts = $this->loadExtensions();
		$ext = $this->getExtension($exts, $this->getUserState('extension', 'com_fwgallery', 'string'));
		$path = '';
		switch ($ext->type) {
			case 'component' :
				$type = (int)$this->getUserState('type', 0, 'int');
				$path = $type?FWMG_LANGUAGE_SITE:FWMG_LANGUAGE_ADMINISTRATOR;
			break;
			case 'module' :
			case 'template' :
				$path = FWMG_LANGUAGE_SITE;
			break;
			case 'plugin' :
				$path = FWMG_LANGUAGE_ADMINISTRATOR;
			break;
		}

		return $path.'/language/overrides/'.$lang->tag.'.override.ini';
	}
	function getLanguageFilename() {
		$langs = fwgHelper::getInstalledLanguages();
		$lang = JArrayHelper::getValue($langs, fwgHelper::getLanguage());
		$type = (int)$this->getUserState('type', 0, 'int');
		$exts = $this->loadExtensions();
		$ext = $this->getExtension($exts, $this->getUserState('extension', 'com_fwgallery', 'string'));

		$path = '';
		$filename = '';

		switch ($ext->type) {
			case 'component' :
				$type = (int)$this->getUserState('type', 0, 'int');
				$path = $type?FWMG_LANGUAGE_SITE:FWMG_LANGUAGE_ADMINISTRATOR;
				$filename = $ext->_element;
			break;
			case 'module' :
			case 'template' :
				$path = FWMG_LANGUAGE_SITE;
				$filename = $ext->_element;
			break;
			case 'plugin' :
				$path = FWMG_LANGUAGE_ADMINISTRATOR;
				$filename = 'plg_'.$ext->folder.'_'.$ext->_element;
			break;
		}

		return $path.'/language/'.$lang->tag.'/'.$lang->tag.'.'.$filename.'.ini';
	}
	function copy() {
		$input = JFactory::getApplication()->input;

		$cid = $input->getVar('cid');
		$lang_data = $input->getVar('lang_data');

		$tag = $input->getCmd('tag');
		$text = $input->gethtml('text');

		$langs = fwgHelper::getInstalledLanguages();
		$lang = JArrayHelper::getValue($langs, fwgHelper::getLanguage());

		$exts = $this->loadExtensions();
		$ext = $this->getExtension($exts, $this->getUserState('extension', 'com_fwgallery', 'string'));

		$path = '';
		switch ($ext->type) {
			case 'component' :
				$type = (int)$this->getUserState('type', 0, 'int');
				$path = $type?FWMG_LANGUAGE_SITE:FWMG_LANGUAGE_ADMINISTRATOR;
			break;
			case 'module' :
			case 'template' :
				$path = FWMG_LANGUAGE_SITE;
			break;
			case 'plugin' :
				$path = FWMG_LANGUAGE_ADMINISTRATOR;
			break;
		}

		$trg_file = $path.'/language/overrides/'.$lang->tag.'.override.ini';

		$result = array();
/* load existing values */
		$trg_buff = file_exists($trg_file)?explode("\n", file_get_contents($trg_file)):array();
		foreach ($trg_buff as $i=>$row) {
			if (trim($row)) {
				$row = explode("=", trim($row), 2);
				if (!empty($row[1])) {
					$result[$row[0]] = trim($row[1], '"');
				}
			}
		}

		if (is_array($cid) and is_array($lang_data)) {
			foreach ($cid as $tag) {
				if (isset($lang_data[$tag])) {
					$result[$tag] = $lang_data[$tag];
				}
			}
		}

		$buff = '';
		foreach ($result as $const=>$val) {
			$buff .= $const.'="'.str_replace(array("\r", "\n", "\t"), ' ', $val).'"'."\n";
		}

/* store updated language file */
		jimport('joomla.filesystem.file');
		if (JFile::write($trg_file, $buff)) {
			$this->setError(JText::_('FWMG_LANGUAGE_FILE_SUCCESFULLY_UPDATED'));
			return true;
		} else {
			$this->setError(JText::_('FWMG_LANGUAGE_CANT_WRITE_FILE'));
		}

	}
	function save() {
		$langs = fwgHelper::getInstalledLanguages();
		$lang = JArrayHelper::getValue($langs, fwgHelper::getLanguage());
		$input = JFactory::getApplication()->input;

		$exts = $this->loadExtensions();
		$ext = $this->getExtension($exts, $this->getUserState('extension', 'com_fwgallery', 'string'));

		$path = '';
		$filename = '';

		switch ($ext->type) {
			case 'component' :
				$type = (int)$this->getUserState('type', 0, 'int');
				$path = $type?FWMG_LANGUAGE_SITE:FWMG_LANGUAGE_ADMINISTRATOR;
				$filename = $ext->_element;
			break;
			case 'module' :
			case 'template' :
				$path = FWMG_LANGUAGE_SITE;
				$filename = $ext->_element;
			break;
			case 'plugin' :
				$path = FWMG_LANGUAGE_ADMINISTRATOR;
				$filename = 'plg_'.$ext->folder.'_'.$ext->_element;
			break;
		}

		$trg_file = $path.'/language/'.$lang->tag.'/'.$lang->tag.'.'.$filename.'.ini';

		$result = array();
/* load existing values */
		$trg_buff = file_exists($trg_file)?explode("\n", file_get_contents($trg_file)):array();
		foreach ($trg_buff as $i=>$row) {
			if (trim($row)) {
				$row = explode("=", trim($row), 2);
				if (!empty($row[1])) {
					$result[$row[0]] = trim($row[1], '"');
				}
			}
		}

/* update changes */
		if ($data = (array)$input->getRaw('lang_data')) {
			foreach ($data as $const => $val) {
				$result[$const] = $val;
			}

/* collect language file */
			$buff = "\n";
			foreach ($result as $const=>$val) {
				$buff .= $const.'="'.str_replace(array("\r", "\n", "\t"), ' ', $val).'"'."\n";
			}

/* store updated language file */
			jimport('joomla.filesystem.file');
			if (JFile::write($trg_file, $buff)) {
				$this->setError(JText::_('FWMG_LANGUAGE_FILE_SUCCESFULLY_UPDATED'));
				return true;
			} else {
				$this->setError(JText::_('FWMG_LANGUAGE_CANT_WRITE_FILE'));
			}
		}
	}
	function getExtension($exts, $ext_element) {
		foreach ($exts as $ext) {
			if ($ext->element == $ext_element) {
				return $ext;
			}
		}
		return $exts[0];
	}
	function loadExtensions() {
		$db = JFactory::getDBO();
		$db->setQuery('SELECT `element`, `folder`, type, name, client_id FROM `#__extensions` WHERE (`element` LIKE \'%fwg%\' OR `folder` = \'fwgallery\' OR `folder` = \'fwgallerytype\' OR `folder` = \'fwgallerytmpl\') ORDER BY `element`');
		if ($list = $db->loadObjectList()) {
			foreach ($list as $i => $row) {
				$list[$i]->_name = $row->name;
				$list[$i]->_element = $row->element;
/*				if ($row->folder) {
					$list[$i]->element .= '-'.$row->folder;
				}*/

				$path = '';
				if (($row->type == 'template' or $row->type == 'module') and $row->client_id == 0) {
					$path = FWMG_LANGUAGE_SITE.'/language/en-GB/en-GB.';
				} else {
					$path = FWMG_LANGUAGE_ADMINISTRATOR.'/language/en-GB/en-GB.';
				}
				$name = '';
				if ($row->type == 'plugin') {
					$filename = $path.'plg_'.strtolower($row->folder).'_'.strtolower($row->element).'.sys.ini';
				} else {
					$filename = $path.strtolower($row->element).'.sys.ini';
				}

				if (file_exists($filename)) {
					$buff = file_get_contents($filename);
					if (preg_match('#'.$row->name.'\="(.+)"#i', $buff, $match)) {
						$name = $match[1];
					} elseif (preg_match('#FW_REALESTATE_'.$row->element.'\="(.+)"#i', $buff, $match)) {
						$name = $match[1];
					}
				}
				if ($name) {
					$list[$i]->name = $name;
				}
				if ($row->type) {
					$list[$i]->name .= ' ['.JText::_('FWMG_'.$row->type).']';
				}
			}

			JArrayHelper::sortObjects($list, array('name'), $direction = 1, $caseSensitive = false);

			return $list;
		}
	}
	function getPagination(&$data) {
		jimport('joomla.html.pagination');
		$app = JFactory::getApplication();

		$limitstart = $app->input->getInt('limitstart');
		$limit = $app->input->getInt('limit', $app->getCfg('list_limit', 20));
		$qty = count($data);

		if ($qty > $limit) {
			$data = array_slice($data, $limitstart, $limit);
		}

		return new JPagination(
			$qty,
			$limitstart,
			$limit
		);
	}
	function request() {
		$langs = fwgHelper::getInstalledLanguages();
		$lang = JArrayHelper::getValue($langs, fwgHelper::getLanguage());
		$params = JComponentHelper::getParams('com_fwgallery');
		$exts = $this->loadExtensions();
		$ext = $this->getExtension($exts, $this->getUserState('extension', 'com_fwgallery', 'string'));
		$prefix = class_exists('JVersion')?'':'.wp';
		$version = $type = '';

		$path = array();
		$filename = '';
		$xml_filename = '';

		switch ($ext->type) {
			case 'component' :
				$type = (int)$this->getUserState('type', 0, 'int');
				$path['site'] = FWMG_LANGUAGE_SITE;
				$path['admin'] = FWMG_LANGUAGE_ADMINISTRATOR;
				$filename = $ext->_element;
				$xml_filename = '/administrator/components/'.$filename.'/'.str_replace('com_', '', $filename).'.xml';
			break;
			case 'module' :
				$path['site'] = FWMG_LANGUAGE_SITE;
				$filename = $ext->_element;
				$xml_filename = '/modules/'.$filename.'/'.$filename.'.xml';
			break;
			case 'template' :
				$path['site'] = FWMG_LANGUAGE_SITE;
				$filename = $ext->_element;
				$xml_filename = '/templates/'.$filename.'/templateDetails.xml';
			break;
			case 'plugin' :
				$path['admin'] = FWMG_LANGUAGE_ADMINISTRATOR;
				$filename = 'plg_'.$ext->folder.'_'.$ext->_element;
				$xml_filename = '/plugins/'.$ext->folder.'/'.$filename.'/'.$filename.'.xml';
			break;
		}
		if ($xml_filename) {
			$xml_filename = JPATH_SITE.$xml_filename;
			if (file_exists($xml_filename)) {
				$buff = file_get_contents($xml_filename);
				if (preg_match('#<version>(.*)</version>#i', $buff, $match)) {
					$version = $match[1];
				}
			}
		}
		$data = json_decode(fwgHelper::request('https://fastw3b.net/index.php?option=com_fwsales&view=fwsales&layout=translate&format=json&access_code='.$params->get('update_code').'&ext='.$filename.'&version='.$version.'&lang='.$lang->tag.'&cms='.(class_exists('JVersion')?'joomla':'wp')));

		if (!empty($data->result)) {
			$qty = 0;
			foreach ($data->result as $type=>$files) {
				foreach ($files as $lang_filename=>$text) {
					if (preg_match('#\.ini$#', $lang_filename) and !empty($path[$type])) {
						$trg_file = $path[$type].'/language/'.$lang->tag.'/'.$lang_filename;
						if (file_put_contents($trg_file, $text)) {
							$qty++;
						}
					}
				}
			}
			$this->setError(JText::sprintf('FWMG_RECEIVED_LANGUAGE_FILES_QTY', $qty));
			return true;
		} else {
			if (!empty($data->msg)) {
				$this->setError($data->msg);
			} else {
				$this->setError(JText::_('FWMG_NO_RESPONSE_FROM_TRANSLATION_SERVER'));
			}
		}
	}
}
