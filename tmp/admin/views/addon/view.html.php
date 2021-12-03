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
        $this->menu = 'addon';
        $model = $this->getModel();

        JHTML::_('behavior.core');
        $this->app = JFactory::getApplication();
		$this->params = JComponentHelper::getParams('com_fwgallery');

        $this->users = fwgHelper::loadUsers();
        $this->current_user = JFactory::getUser();

		$this->list = $model->getPluginsData();
		$this->deals = $model->getDeals();
		$this->have_not_installed = $model->checkNotInstalled($this->list);
		$this->have_not_updated = $model->checkNotUpdated($this->list);
        parent::display();
    }
}
