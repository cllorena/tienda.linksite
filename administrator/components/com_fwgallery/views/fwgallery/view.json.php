<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

class fwGalleryViewFwGallery extends JViewLegacy {
    function display($tmpl=null) {
        $model = $this->getModel();
		$input = JFactory::getApplication()->input;
		$data = new stdclass;
    	switch ($this->getLayout()) {
			case 'get_changelog' :
			$this->list = $model->getChangelog();
			$data->result = $this->loadTemplate();
			break;
			case 'import_prev_data' :
			$data->result = $model->importPrevData();
			break;
			case 'check_update' :
			$data = (object)$model->checkUpdate();
			break;
			case 'verify_code' :
			$data = (object)$model->verifyCode();
			break;
			case 'revoke_code' :
			$data = (object)$model->revokeCode();
			break;
			case 'update_package' :
			$data->result = $model->updatePackage();
			break;
			case 'save' :
            $data->result = $model->save();
			break;
            case 'delete_unregistered_files' :
            $data->result = $model->deleteUnregisteredFiles();
			break;
			case 'clear_cached_files' :
			$model->clearCachedImages();
			break;
			case 'load_wizards' :
			$data->result = $model->loadWizards();
			break;
		}
        $data->msgs = $model->getErrors();
        $data->msg = implode("\n", $data->msgs);
    	die(json_encode($data));
    }
}
