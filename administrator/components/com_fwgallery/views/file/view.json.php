<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

class fwGalleryViewFile extends JViewLegacy {
    function display($tmpl = null) {
        $model = $this->getModel();

        $this->app = JFactory::getApplication();
        $data = new stdclass;
        switch ($this->getLayout()) {
            case 'batchupload' :
			$data->result = (object)$model->install();
			break;
            case 'upload' :
			$data = (object)$model->upload();
			break;
            case 'delete_image' :
            $data = (object)$model->deleteImage();
            break;
        }
        $data->msg = implode("\n", $model->getErrors());
        die(json_encode($data));
    }
}
