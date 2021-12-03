<?php
/**
 * FW Gallery 4.10.0
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

class fwGalleryViewcss extends JViewLegacy {
	function display($tmpl=null) {
		$this->menu = 'css';
		$model = $this->getModel();

		JHTML::_('behavior.core');
		$this->setLayout('default');
		$this->app = JFactory::getApplication();
		$this->editor = $model->getEditor();
		$this->object = $model->loadObject();
		$this->tmpls = $model->loadTemplatesCSS();
        $this->users = fwgHelper::loadUsers();
        $this->current_user = JFactory::getUser();

		$this->path = JPATH_COMPONENT_SITE.'/assets/css/fwmg-design-styles.css';
		$this->content = file_get_contents($this->path);

		parent::display($tmpl);
	}
}
