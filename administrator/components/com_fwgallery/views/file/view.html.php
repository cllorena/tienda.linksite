<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

class fwGalleryViewFile extends JViewLegacy {
    function display($tmpl = null) {
        $this->menu = 'file';
        $model = $this->getModel();

        JHTML::_('behavior.core');
		$this->current_user = JFactory::getUser();
        $this->app = JFactory::getApplication();
        $this->params = JComponentHelper::getParams('com_fwgallery');
        
        $this->types = array(array(
            'active_class' => 'btn-success',
            'title' => JText::_('FWMG_ALL_TYPES'),
            'value' => ''
        ),array(
            'active_class' => 'btn-success',
            'title' => JText::_('FWMG_IMAGE'),
            'value' => 'image'
        ));
        $buff = fwgHelper::triggerEvent('ongetType', array('com_fwgallery'));
        if ($buff) {
            foreach ($buff as $type) {
                if ($type) {
                    $this->types[] = array(
                        'active_class' => 'btn-success',
                        'title' => JText::_('FWMG_'.$type),
                        'value' => $type
                    );
                }
            }
        }

		$this->fields = array('search', 'tab', 'user', 'category', 'limit', 'limitstart', 'ordering');
        switch ($this->getLayout()) {
            case 'edit' :
            $this->object = $model->loadObject();
            if ((!$this->object->id and !$this->current_user->authorise('core.create', 'com_fwgallery')) or ($this->object->id and !$this->current_user->authorise('core.edit', 'com_fwgallery'))) {
            	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
            }
			$this->users = $model->loadUsers($load_all = true);
            foreach ($this->fields as $field) {
                $this->$field = $model->getUserState($field);
            }
            $type = $this->object->type?$this->object->type:$this->app->input->getCmd('type', 'image');
            $this->setLayout('edit_'.$type);
			fwgHelper::triggerEvent('onLoadMainMenu', array('com_fwgallery', 'file.'.$type, $this));

            break;
            default:
			fwgHelper::triggerEvent('ondoCheckPluginsTasksBeforeFilesListingLoading', array('com_fwgallery', 'file.image'));

            $this->order = $model->getOrdering();
			$this->list = $model->loadList();
			$this->pagination = $model->getPagination();
			$this->users = $model->loadUsers();
			$this->allusers = $model->loadUsers($load_all = true);
			$this->extra_link = '';
            foreach ($this->fields as $field) {
                $this->$field = $model->getUserState($field);
				if ($this->$field) {
					$this->extra_link .= '&amp;'.$field.'='.urlencode($this->$field);
    				$this->pagination->setAdditionalUrlParam($field, $this->$field);
    			}
            }
        }
        parent::display();
    }
}
