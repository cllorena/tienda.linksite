<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.html.html');
jimport('joomla.form.formfield');
class JFormFieldFWUsersectionLayout extends JFormField {
	var	$type = 'fwusersectionlayout';

	function getInput() {
		if (!defined('FWMG_COMPONENT_SITE')) {
			define('FWMG_COMPONENT_SITE', JPATH_SITE.'/components/com_fwgallery');
		}
		$path = FWMG_COMPONENT_SITE.'/helpers/helper.php';
		if (!file_exists($path)) return;
		require_once($path);
		JFactory::getLanguage()->load('com_fwgallery');

		$layouts = array(
			JHTML::_('select.option', 'default', JText::_('FWMG_DEFAULT'), 'id', 'name')
		);
		JFactory::getApplication()->triggerEvent('ongetUsersectionLayouts', array('com_fwgallery', &$layouts));
		return JHTML::_('select.genericlist', $layouts, $this->name, 'class="inputbox form-control"', 'id', 'name', $this->value);
	}
}
