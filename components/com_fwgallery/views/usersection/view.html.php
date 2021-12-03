<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

class fwGalleryViewUsersection extends JViewLegacy {
	function display($tpl = null) {
		$model = $this->getModel();

		$this->app = JFactory::getApplication();
		$this->user = JFactory::getUser();
		$this->isadmin = $this->user->authorise('core.login.admin');
		$this->params = $this->app->getParams();
		$this->setLayout($model->getActiveLayout($this));

		$this->app->triggerEvent('onbeforeUsersectionDisplay', array('com_fwrealestate', &$this));

		parent::display($tpl);
	}
	function getPaginationLinks($pagination, $options = array()) {
		$list = array(
				'prefix'       => $pagination->prefix,
				'limit'        => $pagination->limit,
				'limitstart'   => $pagination->limitstart,
				'total'        => $pagination->total,
				'limitfield'   => $pagination->getLimitBox(),
				'pagescounter' => $pagination->getPagesCounter(),
				'pages'        => $pagination->getPaginationPages(),
				'pagesTotal'   => $pagination->pagesTotal,
		);
		return fwgHelper::loadTemplate('pagination.links', array('view' => $this, 'list' => $list, 'options' => $options));
	}
}
