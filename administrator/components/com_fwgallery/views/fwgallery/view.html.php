<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

class fwGalleryViewFwGallery extends JViewLegacy {
    function display($tmpl = null) {
        $this->menu = 'fwgallery';
        $model = $this->getModel();
		$this->app = JFactory::getApplication();
		$this->params = JComponentHelper::getParams('com_fwgallery');
		$input = $this->app->input;
        $this->users = fwgHelper::loadUsers();
        $this->current_user = JFactory::getUser();

        switch ($this->getLayout()) {
        case 'modal_add' :
        case 'modal_image_add' :
            JHTML::_('behavior.core');
            JHTML::_('behavior.formvalidator');
            JHTML::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.'/helpers');
            JFactory::getLanguage()->load('com_fwgallery', JPATH_ADMINISTRATOR);
            $this->category_id = $input->getInt('category_id');
            $this->function  = $input->getCmd('function', 'jSelectFwgallery');
            break;
        case 'modal' :
            JHTML::_('behavior.core');
            JHTML::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.'/helpers');
            JFactory::getLanguage()->load('com_fwgallery', JPATH_ADMINISTRATOR);
            $this->sublayout = $input->getCmd('sublayout', 'galleries');
            if (!in_array($this->sublayout, array('galleries', 'images'))) $this->sublayout = 'galleries';
            switch ($this->sublayout) {
                case 'images' :
                $this->files = $model->loadFiles();
                if ($this->files) {
                    foreach ($this->files as $i=>$file) {
                        if ($file->descr) {
                            $this->files[$i]->descr = fwgHelper::stripTags($file->descr, 40);
                        }
                    }
                }
                $this->pagination = $model->getFilesPagination();
                $this->search = $input->getString('search');
                if ($this->search) {
                    $this->pagination->setAdditionalUrlParam('search', $this->search);
                }
		        $this->category_id = $input->getInt('category_id');
                if ($this->category_id) {
                    $this->pagination->setAdditionalUrlParam('category_id', $this->category_id);
                }
                $this->function  = 'jSelectFwgalleryImage';
                break;
                case 'galleries' :
                $this->galleries = $model->getGalleries();
                if ($this->galleries) {
                    foreach ($this->galleries as $i=>$file) {
                        if ($file->descr) {
                            $this->galleries[$i]->descr = fwgHelper::stripTags($file->descr, $this->params->get('description_length'));
                        }
                    }
                }
                $this->pagination = $model->getGalleriesPagination();
                $this->search = $input->getString('search');
                if ($this->search) {
                    $this->pagination->setAdditionalUrlParam('search', $this->search);
                }
		        $this->category_id = $input->getInt('category_id');
                if ($this->category_id) {
                    $this->pagination->setAdditionalUrlParam('category_id', $this->category_id);
                }
                $this->function  = 'jSelectFwgallery';
                break;
            }
        break;
        default :
			$this->obj = $model->loadParamsObj();
			$this->qch = $model->quickCheck();
			$this->locstat = $model->localStat();
			$this->prev_data = $model->checkPreviousVersion();
			$this->addons = $model->getAddons();
		}

        parent::display($tmpl);
    }
}
