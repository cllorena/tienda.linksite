<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

class fwGalleryViewData extends JViewLegacy {
    function display($tmpl=null) {
		$this->menu = 'data';
		$model = $this->getModel();

		$this->app = JFactory::getApplication();
		$this->params = JComponentHelper::getParams('com_fwgallery');
		$this->current_user = JFactory::getUser();
        $this->users = fwgHelper::loadUsers();
        $this->current_user = JFactory::getUser();
		if ($this->getLayout() == 'edit') {
			if ($plugin = $model->getPlugin()) {
				if (!headers_sent()) {
					header("Expires: Tue, 1 Jul 2003 05:00:00 GMT");
					header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
					header("Cache-Control: no-store, no-cache, must-revalidate");
					header("Pragma: no-cache");
				}
				ob_implicit_flush();
				$model->processPlugin($plugin);
			}
		} else {
			$this->backups = $model->getBackupsList();
			$this->plugins = $model->getPlugins($this);
			parent:: display($tmpl);
		}
	}
}
