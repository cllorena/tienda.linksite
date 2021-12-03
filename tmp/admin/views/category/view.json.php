<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

class fwGalleryViewCategory extends JViewLegacy {
    function display($tmpl = null) {
        $model = $this->getModel();

        $data = new stdclass;
        switch ($this->getLayout()) {
            case 'upload' :
			$data = (object)$model->upload();
			break;
            case 'delete_image' :
            $data = (object)$model->deleteImage();
            break;
			case 'delete_video' :
            $data = (object)$model->deleteVideo();
            break;
            case 'quick_categories' :
            $data->result = $model->quickCategories();
            break;
        }
        $data->msg = implode("\n", $model->getErrors());
        die(json_encode($data));
    }
}
