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
class JFormFieldFWGallery extends JFormField {
	var	$type = 'fwgallery';

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
		return JHTML::_(
			'fwsgCategory.getCategories',
			$this->name.'[]',
			$this->value,
			'class="inputbox form-control" multiple="multiple" size="10"',
			true,
			'FWMG_SELECT_GALLERY',
			false,
			true
		);
	}
}
