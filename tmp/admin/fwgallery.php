<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

if (!JFactory::getUser()->authorise('core.manage', 'com_fwgallery')) {
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

JHTML::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.'/helpers');
JHTML::addIncludePath(JPATH_COMPONENT_SITE.'/helpers');
JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.'/tables');

require_once(JPATH_COMPONENT_SITE.'/helpers/helper.php');
require_once(JPATH_COMPONENT.'/controller.php');

fwgHelper::loadAdminStyles();
fwgHelper::checkClientContacts();

$input = JFactory::getApplication()->input;
$controller = JControllerLegacy :: getInstance('fwGallery');
$controller->execute($input->getCmd('task'));
$controller->redirect();
