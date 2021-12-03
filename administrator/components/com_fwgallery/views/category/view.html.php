<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

class fwGalleryViewCategory extends JViewLegacy {
    function display($tmpl = null) {
        $this->menu = 'category';
        $model = $this->getModel();

        JHTML::_('behavior.core');
        $this->app = JFactory::getApplication();
		$this->current_user = JFactory::getUser();

		$this->fields = array('search', 'category', 'user', 'limit', 'limitstart', 'ordering');
        switch ($this->getLayout()) {
            case 'edit' :
            $this->object = $model->loadObject();
            if ((!$this->object->id and !$this->current_user->authorise('core.create', 'com_fwgallery')) or ($this->object->id and !$this->current_user->authorise('core.edit', 'com_fwgallery'))) {
				throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
			}
			$this->groups = fwgHelper::loadviewlevels();
			$this->users = $model->loadUsers($load_all = true);
			foreach ($this->fields as $field) {
				$this->$field = $this->app->input->getString($field);
			}
            break;
            default:
            $this->order = $model->getOrdering();
			$this->list = $model->loadList();
			$this->pagination = $model->getPagination();
			$this->users = $model->loadUsers();
			$this->extra_link = '';
			foreach ($this->fields as $field) {
				$this->$field = $this->app->input->getString($field);
				if ($this->$field) {
					$this->pagination->setAdditionalUrlParam($field, $this->$field);
					$this->extra_link .= '&'.$field.'='.urlencode($this->$field);
				}
			}
        }
        parent::display($tmpl);
    }
}
