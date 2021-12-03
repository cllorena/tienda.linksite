<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

class fwgalleryModelConfiguration extends JModelLegacy {
    function loadObject() {
    	$obj = new stdclass;
    	$obj->params = JComponentHelper::getParams('com_fwgallery');

        $app = JFactory::getApplication();
        $app->triggerEvent('ongetExtraConfigData', array('com_fwgallery', $obj->params));

        return $obj;
    }
    function save() {
    	$params = JComponentHelper::getParams('com_fwgallery');
        $app = JFactory::getApplication();

		$data = $app->input->getVar('config');
        $app->triggerEvent('onsetExtraConfigData', array('com_fwgallery', &$data));

	   	$params->loadArray($data);

		jimport('joomla.filesystem.file');
		$wmf = $params->get('watermark_file');
		if ($app->input->getInt('delete_watermark') and $wmf) {
			if (file_exists(FWMG_STORAGE_PATH.$wmf)) JFile::delete(FWMG_STORAGE_PATH.$wmf);
			$params->set('watermark_file', '');
			$wmf = '';
		}

    	if ($file = $app->input->files->get('watermark_file', null, 'raw')
    	 and $name = JArrayHelper::getValue($file, 'name')
    	  and empty($file['error']) and preg_match('/\.png$/i', $name)
    	   and JFile::copy(JArrayHelper::getValue($file, 'tmp_name'), FWMG_STORAGE_PATH.$name)) {
			if ($wmf and $name != $wmf and file_exists(FWMG_STORAGE_PATH.$wmf)) {
				JFile::delete(FWMG_STORAGE_PATH.$wmf);
			}
    		$params->set('watermark_file', $name);
    	}

        return fwgHelper::storeConfig($params);
    }
}
