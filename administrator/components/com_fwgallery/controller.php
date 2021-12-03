<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

class fwGalleryController extends JControllerLegacy {
	var $fields = null;
    function __construct($config = array())    {
        parent::__construct($config);
        $this->registerTask('add','edit');
		$this->fields = array('category', 'status', 'type', 'tab', 'search', 'limit', 'limitstart', 'user', 'ordering');
    }
    function edit() {
        $viewName = $this->input->getCmd('view', $this->getName());
        $view = $this->getView($viewName, 'html');
        $view->setModel($this->getModel($viewName), true);
        $view->setLayout('edit');
        parent::display();
    }
    function batch() {
        $viewName = $this->input->getCmd('view', $this->getName());
        $view = $this->getView($viewName, 'html');
        $view->setModel($this->getModel($viewName), true);
        $view->setLayout('batch');
        parent::display();
    }
    function apply() {
        $view_name = $this->input->getString('view');
        $model = $this->getModel($view_name);

        if (method_exists($model, 'save')) {
            $id = $model->save();
            $msg = $model->getError();
        } else {
            $id = JArrayHelper :: getValue($this->input->getVar('cid'), 0);
            $msg = JText::_('FWMG_METHOD_APPLY_DOES_NOT_EXISTS');
        }
		foreach ($this->fields as $field) {
			if ($data = $this->input->getString($field)) {
				$view_name .= '&'.$field.'='.urlencode($data);
			}
		}
        if ($view_name == 'config') {
            $view_name .= '&task=edit';
        }
        if ($msg) {
            fwgHelper::addMessage($msg);
        }
        $this->setRedirect('index.php?option=com_fwgallery&view='.$view_name.'&task=edit&cid[]='.$id);
    }
	function dobatch() {
        $view_name = $this->input->getString('view');
        $model = $this->getModel($view_name);

        if (method_exists($model, 'batch')) {
            $model->batch();
            $msg = $model->getError();
        } else {
            $msg = JText::_('FWMG_METHOD_BATCH_DOES_NOT_EXISTS');
        }
		foreach ($this->fields as $field) {
			if ($data = $this->input->getString($field)) {
				$view_name .= '&'.$field.'='.urlencode($data);
			}
		}
        if ($msg) {
            fwgHelper::addMessage($msg);
        }
        $this->setRedirect('index.php?option=com_fwgallery&view='.$view_name);
	}
	function saveall() {
        $view_name = $this->input->getString('view');
        $model = $this->getModel($view_name);

        if (method_exists($model, 'saveall')) {
            $model->saveall();
            $msg = $model->getError();
        } else {
            $msg = JText::_('FWMG_METHOD_SAVEALL_DOES_NOT_EXISTS');
        }
		foreach ($this->fields as $field) {
			if ($data = $this->input->getString($field)) {
				$view_name .= '&'.$field.'='.urlencode($data);
			}
		}
        if ($msg) {
            fwgHelper::addMessage($msg);
        }
        $this->setRedirect('index.php?option=com_fwgallery&view='.$view_name);
	}
    function install() {
        $view_name = $this->input->getString('view');
        $model = $this->getModel($view_name);

        if (method_exists($model, 'install')) {
            $model->install();
            $msg = $model->getError();
        } else {
            $msg = JText::_('FWMG_METHOD_INSTALL_DOES_NOT_EXISTS');
        }
		foreach ($this->fields as $field) {
			if ($data = $this->input->getString($field)) {
				$view_name .= '&'.$field.'='.urlencode($data);
			}
		}
        if ($msg) {
            fwgHelper::addMessage($msg);
        }
        $this->setRedirect('index.php?option=com_fwgallery&view='.$view_name);
    }
    function uninstall() {
        $view_name = $this->input->getString('view');
        $model = $this->getModel($view_name);

        if (method_exists($model, 'uninstall')) {
            $model->uninstall();
            $msg = $model->getError();
        } else {
            $msg = JText::_('FWMG_METHOD_UNINSTALL_DOES_NOT_EXISTS');
        }
		foreach ($this->fields as $field) {
			if ($data = $this->input->getString($field)) {
				$view_name .= '&'.$field.'='.urlencode($data);
			}
		}
        if ($msg) {
            fwgHelper::addMessage($msg);
        }
        $this->setRedirect('index.php?option=com_fwgallery&view='.$view_name);
    }
    function save() {
        $view_name = $this->input->getString('view');
        $model = $this->getModel($view_name);

        if (method_exists($model, 'save')) {
            $model->save();
            $msg = $model->getError();
        } else {
            $msg = JText::_('FWMG_METHOD_SAVE_DOES_NOT_EXISTS');
        }
		foreach ($this->fields as $field) {
			if ($data = $this->input->getString($field)) {
				$view_name .= '&'.$field.'='.urlencode($data);
			}
		}
        if ($msg) {
            fwgHelper::addMessage($msg);
        }
        $this->setRedirect('index.php?option=com_fwgallery&view='.$view_name);
    }
    function remove() {
        $view_name = $this->input->getString('view');
        $model = $this->getModel($view_name);

        if (method_exists($model, 'remove')) {
            $model->remove();
            $msg = $model->getError();
        } else {
            $msg = JText::_('FWMG_METHOD_REMOVE_DOES_NOT_EXISTS');
        }
		foreach ($this->fields as $field) {
			if ($data = $this->input->getString($field)) {
				$view_name .= '&'.$field.'='.urlencode($data);
			}
		}
        if ($msg) {
            fwgHelper::addMessage($msg);
        }
        $this->setRedirect('index.php?option=com_fwgallery&view='.$view_name);
    }
    function saveorder() {
        $view_name = $this->input->getString('view');
        $model = $this->getModel($view_name);

        if (method_exists($model, 'saveorder')) {
            $model->saveorder();
            $msg = $model->getError();
        } else {
            $msg = JText::_('FWMG_METHOD_SAVEORDER_DOES_NOT_EXISTS');
        }
		foreach ($this->fields as $field) {
			if ($data = $this->input->getString($field)) {
				$view_name .= '&'.$field.'='.urlencode($data);
			}
		}
        if ($msg) {
            fwgHelper::addMessage($msg);
        }
        $this->setRedirect('index.php?option=com_fwgallery&view='.$view_name);
    }
    function orderup() {
        $view_name = $this->input->getString('view');
        $model = $this->getModel($view_name);
        if (method_exists($model, 'orderup')) {
            $model->orderup();
            $msg = $model->getError();
        } else {
            $msg = JText::_('FWMG_METHOD_ORDERUP_DOES_NOT_EXISTS');
        }
		foreach ($this->fields as $field) {
			if ($data = $this->input->getString($field)) {
				$view_name .= '&'.$field.'='.urlencode($data);
			}
		}
        if ($msg) {
            fwgHelper::addMessage($msg);
        }
        $this->setRedirect('index.php?option=com_fwgallery&view='.$view_name);
    }
    function orderdown() {
        $view_name = $this->input->getString('view');
        $model = $this->getModel($view_name);
        if (method_exists($model, 'orderdown')) {
            $model->orderdown();
            $msg = $model->getError();
        } else {
            $msg = JText::_('FWMG_METHOD_ORDERDOWN_DOES_NOT_EXISTS');
        }
		foreach ($this->fields as $field) {
			if ($data = $this->input->getString($field)) {
				$view_name .= '&'.$field.'='.urlencode($data);
			}
		}
        if ($msg) {
            fwgHelper::addMessage($msg);
        }
        $this->setRedirect('index.php?option=com_fwgallery&view='.$view_name);
    }
    function publish() {
        $view_name = $this->input->getString('view');
        $model = $this->getModel($view_name);
        if (method_exists($model, 'publish')) {
            $model->publish();
            $msg = $model->getError();
        } else {
            $msg = JText::_('FWMG_METHOD_PUBLISH_DOES_NOT_EXISTS');
        }
		foreach ($this->fields as $field) {
			if ($data = $this->input->getString($field)) {
				$view_name .= '&'.$field.'='.urlencode($data);
			}
		}
        if ($msg) {
            fwgHelper::addMessage($msg);
        }
        $this->setRedirect('index.php?option=com_fwgallery&view='.$view_name);
    }
    function unpublish() {
        $view_name = $this->input->getString('view');
        $model = $this->getModel($view_name);
        if (method_exists($model, 'unpublish')) {
            $model->unpublish();
            $msg = $model->getError();
        } else {
            $msg = JText::_('FWMG_METHOD_UNPUBLISH_DOES_NOT_EXISTS');
        }
		foreach ($this->fields as $field) {
			if ($data = $this->input->getString($field)) {
				$view_name .= '&'.$field.'='.urlencode($data);
			}
		}
        if ($msg) {
            fwgHelper::addMessage($msg);
        }
        $this->setRedirect('index.php?option=com_fwgallery&view='.$view_name);
    }
    function select() {
        $view_name = $this->input->getString('view');
        $model = $this->getModel($view_name);
        if (method_exists($model, 'select')) {
            $model->select();
            $msg = $model->getError();
        } else {
            $msg = JText::_('FWMG_METHOD_SELECT_DOES_NOT_EXISTS');
        }
		foreach ($this->fields as $field) {
			if ($data = $this->input->getString($field)) {
				$view_name .= '&'.$field.'='.urlencode($data);
			}
		}
        if ($msg) {
            fwgHelper::addMessage($msg);
        }
        $this->setRedirect('index.php?option=com_fwgallery&view='.$view_name);
    }
    function unselect() {
        $view_name = $this->input->getString('view');
        $model = $this->getModel($view_name);
        if (method_exists($model, 'unselect')) {
            $model->unselect();
            $msg = $model->getError();
        } else {
            $msg = JText::_('FWMG_METHOD_UNSELECT_DOES_NOT_EXISTS');
        }
		foreach ($this->fields as $field) {
			if ($data = $this->input->getString($field)) {
				$view_name .= '&'.$field.'='.urlencode($data);
			}
		}
        if ($msg) {
            fwgHelper::addMessage($msg);
        }
        $this->setRedirect('index.php?option=com_fwgallery&view='.$view_name);
    }
    function clockwise() {
        $view_name = 'file';
        $model = $this->getModel($view_name);
    	$model->clockwise();
		$msg = $model->getError();
		foreach ($this->fields as $field) {
			if ($data = $this->input->getString($field)) {
				$view_name .= '&'.$field.'='.urlencode($data);
			}
		}
        if ($msg) {
            fwgHelper::addMessage($msg);
        }
        $this->setRedirect('index.php?option=com_fwgallery&view='.$view_name);
    }
    function counterclockwise() {
        $view_name = 'file';
        $model = $this->getModel($view_name);
    	$model->counterClockwise();
		$msg = $model->getError();
		foreach ($this->fields as $field) {
			if ($data = $this->input->getString($field)) {
				$view_name .= '&'.$field.'='.urlencode($data);
			}
		}
        if ($msg) {
            fwgHelper::addMessage($msg);
        }
        $this->setRedirect('index.php?option=com_fwgallery&view='.$view_name);
    }
}
