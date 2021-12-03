<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.html.html');
jimport('joomla.form.formfield');
class JFormFieldFWGFile extends JFormField {
	var	$type = 'fwgfile';

	function getInput() {
		JFactory::getLanguage()->load('com_fwgallery');
		if (!defined('FWMG_COMPONENT_SITE')) {
			define('FWMG_COMPONENT_SITE', JPATH_SITE.'/components/com_fwgallery');
		}
		if (!defined('FWMG_COMPONENT_ADMINISTRATOR')) {
			define('FWMG_COMPONENT_ADMINISTRATOR', JPATH_ADMINISTRATOR.'/components/com_fwgallery');
		}
		require_once(FWMG_COMPONENT_SITE.'/helpers/helper.php');
		JHTML::addIncludePath(FWMG_COMPONENT_ADMINISTRATOR.'/helpers');
		$db = JFactory::getDBO();
		$db->setQuery('SELECT id, name FROM #__fwsg_file ORDER BY ordering');
		if ($list = $db->loadObjectList()) {
			return JHTML::_(
				'select.genericlist',
				array_merge(array(
					JHTML::_('select.option', '0', JText::_('FWMG_SELECT_FILE'), 'id', 'name')
				), $list),
				$this->name,
				'class="inputbox form-control"',
				'id',
				'name',
				$this->value
			);
		}
	}
}
