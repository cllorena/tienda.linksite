<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

class fwGalleryViewTranslation extends JViewLegacy {
    function display($tmpl = null) {
        $this->menu = 'translation';
        $model = $this->getModel();
        JHTML::_('behavior.core');
        $this->users = fwgHelper::loadUsers();
        $this->current_user = JFactory::getUser();

		$this->app = JFactory::getApplication();
		$this->type = $model->getUserState('type', 0, 'int');
        $this->search = $model->getUserState('search', '', 'string');
        $this->language = fwgHelper::getLanguage();
        $this->languages = fwgHelper::getInstalledLanguages();
		$this->extensions = $model->loadExtensions();
		$this->extension = $model->getUserState('extension', 'com_fwgallery', 'string');
		$this->obj = $model->getExtension($this->extensions, $this->extension);
        $this->data = $model->getLanguageData($this->obj);
        $this->pagination = $model->getPagination($this->data);
        $this->path = $model->getLanguageFilename();
        $this->path_overrides = $model->getLanguageOverridesFilename();
        parent::display($tmpl);
    }
}
