<?php
/**
 * FW Gallery 6.7.2
 * @copyright C 2019 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

class fwGalleryViewTranslation extends JViewLegacy {
    function display($tmpl=null) {
        $model = $this->getModel();
        $data = new stdclass;
        switch ($this->getLayout()) {
            case 'copy_to_overrides' :
            $data->result = $model->copy();
            break;
            case 'request_translation' :
            $data->result = $model->request();
            break;
        }
        $data->msg = $model->getError();
        die(json_encode($data));
    }
}
