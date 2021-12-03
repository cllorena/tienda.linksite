<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

class fwGalleryViewTag extends JViewLegacy {
    function display($tmpl = null) {
        $this->menu = 'tag';
        $model = $this->getModel();

        JHTML::_('behavior.core');
        $this->app = JFactory::getApplication();
        $this->current_user = JFactory::getUser();
        $this->users = fwgHelper::loadUsers();
        $this->current_user = JFactory::getUser();

        $this->plugin_installed = fwgHelper::pluginInstalled('tag');
        $this->plugin_enabled = fwgHelper::pluginEnabled('tag');

		$this->fields = array('order', 'search', 'status', 'type', 'limit', 'limitstart');

        switch ($this->getLayout()) {
            case 'edit' :
			$this->object = $model->loadObject();
            foreach ($this->fields as $field) {
                $this->$field = $this->app->input->getString($field);
            }
			fwgHelper::triggerEvent('onLoadMainMenu', array('com_fwgallery', 'tag', $this));
            break;
            default:
			$this->list = $model->loadList();
			$this->pagination = $model->getPagination();
            $this->types = $model->getFileTypes();
			$this->extra_link = '';
            foreach ($this->fields as $field) {
                $this->$field = $this->app->input->getString($field);
				if (is_string($this->$field)) {
					$this->extra_link .= '&amp;'.$field.'='.urlencode($this->$field);
    				$this->pagination->setAdditionalUrlParam($field, $this->$field);
    			}
            }
			fwgHelper::triggerEvent('onLoadMainMenu', array('com_fwgallery', 'tags', $this));
        }
        parent::display($tmpl);
    }
}
