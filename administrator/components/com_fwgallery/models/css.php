<?php
/**
 * FW Gallery 4.10.0
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

class fwGalleryModelCss extends JModelLegacy {
	function getEditor() {
		$editor = '';
		$db = JFactory::getDbo();
		$db->setQuery('SELECT `element` FROM `#__extensions` WHERE `element` IN (\'codemirror\',\'none\') AND `folder` = \'editors\' AND enabled = 1');
		if ($list = $db->loadColumn()) {
			if (in_array('codemirror', $list)) {
				$editor = 'codemirror';
			} else {
				$editor = 'none';
			}
		} else {
			$editor = JFactory::getConfig()->get('editor');
		}
		return JEditor::getInstance($editor);
	}
	function loadObject() {
		return (object)array(
			'params' => JComponentHelper::getParams('com_fwgallery')
		);
	}
	function loadTemplatesCSS() {
		$db = JFactory::getDBO();
		$db->setQuery('SELECT name, element FROM `#__extensions` WHERE `folder` = \'fwgallerytmpl\' AND enabled = 1 ORDER BY name');
		if ($list = $db->loadObjectList()) {
			$lang = JFactory::getLanguage();
			foreach ($list as $i=>$row) {
				$name = 'plg_fwgallerytmpl_'.$row->element;
				$lang->load($name);
				$list[$i]->name = JText::_($row->name);
				$list[$i]->path = JPATH_SITE.'/plugins/fwgallerytmpl/'.$row->element.'/assets/css/fwmg-design-styles.css';
				if (file_exists($list[$i]->path)) {
					$list[$i]->css = file_get_contents($list[$i]->path);
				}
			}
			return $list;
		}
	}
    function save() {
    	$params = JComponentHelper::getParams('com_fwgallery');
		$input = JFactory::getApplication()->input;
		$data = (array)$input->getVar('config');

    	$fields = array(
			'additional_css'
		);
		foreach ($fields as $field) $data[$field] = $input->getVar($field);

	   	$params->loadArray($data);

		return $this->store($params);
	}
	function store($params) {
		$cache = JFactory::getCache('_system', 'callback');
    	$cache->clean();

    	$db = JFactory::getDBO();
    	$db->setQuery('UPDATE #__extensions SET params = '.$db->quote($params->toString()).' WHERE `element` = \'com_fwgallery\' AND `type` = \'component\'');
    	return $db->execute();
	}
}
