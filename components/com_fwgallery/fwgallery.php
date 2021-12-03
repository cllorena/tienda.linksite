<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

JHTML::addIncludePath(JPATH_COMPONENT.'/helpers');
JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.'/tables');

require_once(JPATH_COMPONENT.'/helpers/helper.php');
require_once(JPATH_COMPONENT.'/controller.php');

$input = JFactory::getApplication()->input;
$controller = JControllerLegacy::getInstance('fwGallery');
$controller->execute($input->getCmd('task'));
$controller->redirect();
