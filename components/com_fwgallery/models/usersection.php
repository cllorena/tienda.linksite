<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

class fwGalleryModelUsersection extends JModelLegacy {
	function changePassword() {
		$user = JFactory::getUser();
		if (!$user->id) {
			$this->setError(JText::_('FWMG_LOGIN_FIRST'));
			return;
		}
		$input = JFactory::getApplication()->input;
		$password = $input->getRaw('password');
		$password1 = $input->getRaw('password1');
		$password2 = $input->getRaw('password2');
		if ($password and $password1 and $password2) {
			$db = JFactory::getDBO();
			$db->setQuery('SELECT password FROM #__users WHERE id = '.(int)$user->id);
			if ($sys_password = $db->loadResult()) {
				if (JUserHelper::verifyPassword($password, $sys_password)) {
					if ($password1 == $password2) {
						$db->setQuery('UPDATE #__users SET password = '.$db->quote(JUserHelper::hashPassword($password1)).' WHERE id = '.(int)$user->id);
						if ($db->execute()) {
							$this->setError(JText::_('FWMG_PASSWORD_CHANGED'));
							return true;
						} else {
							$this->setError(JText::_('FWMG_DB_ERROR', $db->getError()));
						}
					} else $this->setError('FWMG_NEW_PASSWORDS_NOT_MATCH');
				} else $this->setError('FWMG_CURRENT_PASSWORD_NOT_MATCH');
			} else $this->setError(JText::_('FWMG_DB_ERROR', $db->getError()));
		} else {
			$this->setError(JText::_('FWMG_ALL_FIELDS_REQUIRED'));
		}
	}
	function getActiveLayout($view) {
		$app = JFactory::getApplication();
		$layout = $app->input->getCmd('layout', $app->getParams()->get('layout', 'default'));

		$management = array();
		$app->triggerEvent('ongetUsersectionMenu', array('com_fwgallery', $view, &$management));
		$keys = array_keys($management);

		if ($management and ($layout == 'default' or ($layout != 'default' and !in_array(str_replace('_edit', '', $layout), $keys)))) {
			$keys = array_keys($management);
			$layout = $keys[0];
		}
		return $layout;
	}
}
