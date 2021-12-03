<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

class fwGalleryViewConfiguration extends JViewLegacy {
    function display($tmpl = null) {
        $this->menu = 'configuration';
        $model = $this->getModel();
        JHTML::_('behavior.core');
        $this->users = fwgHelper::loadUsers();
        $this->current_user = JFactory::getUser();
        $this->app = JFactory::getApplication();
        $this->object = $model->loadObject();
        parent::display($tmpl);
    }
}
