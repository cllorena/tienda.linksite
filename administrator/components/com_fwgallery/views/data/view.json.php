<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

class fwGalleryViewData extends JViewLegacy {
    function display($tmpl=null) {
        $model = $this->getModel();
		$data = new stdclass;
		switch ($this->getLayout()) {
		case 'import_db' :
			$data->result = $model->importDB();
		break;
		case 'export_data' :
			$data->result = $model->exportData();
		break;
		case 'store_export_data' :
			$data->result = $model->storeExportData();
		break;
		case 'backup_delete' :
			$data->result = $model->backupDelete();
		break;
		case 'backup_upload' :
			$data->result = $model->backupUpload();
		break;
		case 'backup_restore' :
			$data->result = $model->backupRestore();
		break;
		}
		$data->msg = implode('\\n', $model->getErrors());
		die(json_encode($data));
    }
}
