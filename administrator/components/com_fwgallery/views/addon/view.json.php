<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

class fwGalleryViewAddon extends JViewLegacy {
    function display($tmpl = null) {
        $app = JFactory::getApplication();
        $model = $this->getModel();

        $data = new stdclass;
        switch ($this->getLayout()) {
			case 'get_cart' :
			$data->result = $model->loadCart();
			break;
			case 'clear_cart' :
			$data->result = $model->clearCart();
			break;
			case 'buy' :
			$data->result = $model->addCart();
			break;
            case 'install' :
			$data->result = $model->install();
			break;
            case 'update' :
			$data->result = $model->install(true);
			break;
            case 'install_all' :
			$data->result = $model->installAll();
			break;
            case 'update_all' :
			$data->result = $model->installAll(true);
			break;
            case 'enable' :
			$data->result = $model->enable();
			break;
            case 'disable' :
            $data->result = $model->disable();
            break;
            case 'load_addon_description' :
            $data->html = $model->loadAddonDescription();
            break;
            case 'load_deal_description' :
            $data->html = $model->loadDealDescription();
            break;
            case 'login' :
            $data->result = $model->login();
            break;
            case 'register' :
            $data->result = $model->register();
            break;
            case 'confirm' :
            $data->result = $model->confirm();
            break;
            case 'reset_password' :
            $data->result = $model->resetPassword();
            break;
        }
        $data->msgs = $model->getErrors();
        $data->msg = implode("\n", $data->msgs);
        die(json_encode($data));
    }
}
