<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

class fwGalleryViewUsersection extends JViewLegacy {
	function display($tpl = null) {
		$model = $this->getModel();
		$buff = new stdclass;

		switch ($this->getLayout()) {
			case 'change_password' :
				$buff->result = $model->changePassword();
				break;
		}
		$buff->msg = implode('\\n', $model->getErrors());
		die(json_encode($buff));
	}
}
