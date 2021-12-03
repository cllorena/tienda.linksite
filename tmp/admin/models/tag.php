<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

class fwGalleryModelTag extends JModelLegacy {
    function getUserState($name, $def='', $type='cmd') {
        $app = JFactory::getApplication();
        return $app->getUserStateFromRequest('com_fwgallery.tag.'.$name, $name, $def, $type);
    }
    function getPagination() {
        $app = JFactory::getApplication();
		$input = $app->input;
        jimport('joomla.html.pagination');
        return new JPagination(
        	$this->loadQty(),
    		$input->getInt('limitstart'),
    		$input->getInt('limit', $app->getCfg('list_limit'))
    	);
    }
    function getFileTypes() {
        $types = array((object)array('id'=>'image', 'name'=>JText::_('FWMG_IMAGE')));
        $buff = JFactory::getApplication()->triggerEvent('ongetType', array('com_fwgallery'));
        if ($buff) {
            foreach ($buff as $type) {
                if ($type) {
                    $types[] = (object)array('id'=>$type, 'name'=>JText::_('FWMG_'.$type));
                }
            }
        }
        return $types;
    }
    function loadList() {
        $app = JFactory::getApplication();
        $list = array();
        $app->triggerEvent('onloadList', array('com_fwgallery.tag', $this, &$list));
        return $list;
    }
    function loadQty() {
        $app = JFactory::getApplication();
        $qty = 0;
        $app->triggerEvent('onloadQty', array('com_fwgallery.tag', $this, &$qty));
        return $qty;
    }
    function save() {
        $app = JFactory::getApplication();
        $data = $app->triggerEvent('onsave', array('com_fwgallery.tag', $this));
        if ($data) {
            foreach ($data as $row) {
                if ($row) {
                    return $row;
                }
            }
        }
    }
    function remove() {
        $app = JFactory::getApplication();
        $data = $app->triggerEvent('onremove', array('com_fwgallery.tag', $this));
        if ($data) {
            foreach ($data as $row) {
                if ($row) {
                    return $row;
                }
            }
        }
    }
    function saveorder() {
        $app = JFactory::getApplication();
        $data = $app->triggerEvent('onsaveorder', array('com_fwgallery.tag', $this));
        if ($data) {
            foreach ($data as $row) {
                if ($row) {
                    return $row;
                }
            }
        }
    }
    function orderdown() {
        $app = JFactory::getApplication();
        $data = $app->triggerEvent('onorderdown', array('com_fwgallery.tag', $this));
        if ($data) {
            foreach ($data as $row) {
                if ($row) {
                    return $row;
                }
            }
        }
    }
    function orderup() {
        $app = JFactory::getApplication();
        $data = $app->triggerEvent('onorderup', array('com_fwgallery.tag', $this));
        if ($data) {
            foreach ($data as $row) {
                if ($row) {
                    return $row;
                }
            }
        }
    }
    function publish() {
        $app = JFactory::getApplication();
        $data = $app->triggerEvent('onpublish', array('com_fwgallery.tag', $this));
        if ($data) {
            foreach ($data as $row) {
                if ($row) {
                    return $row;
                }
            }
        }
    }
    function unpublish() {
        $app = JFactory::getApplication();
        $data = $app->triggerEvent('onunpublish', array('com_fwgallery.tag', $this));
        if ($data) {
            foreach ($data as $row) {
                if ($row) {
                    return $row;
                }
            }
        }
    }
    function select() {
        $app = JFactory::getApplication();
        $data = $app->triggerEvent('onselect', array('com_fwgallery.tag', $this));
        if ($data) {
            foreach ($data as $row) {
                if ($row) {
                    return $row;
                }
            }
        }
    }
    function unselect() {
        $app = JFactory::getApplication();
        $data = $app->triggerEvent('onunselect', array('com_fwgallery.tag', $this));
        if ($data) {
            foreach ($data as $row) {
                if ($row) {
                    return $row;
                }
            }
        }
    }
    function install() {
        $app = JFactory::getApplication();
        $data = $app->triggerEvent('oninstall', array('com_fwgallery.tag', $this));
        if ($data) {
            foreach ($data as $row) {
                if ($row) {
                    return $row;
                }
            }
        }
    }
    function loadObject() {
        $app = JFactory::getApplication();
        $data = $app->triggerEvent('onloadObject', array('com_fwgallery.tag', $this));
        if ($data) {
            foreach ($data as $row) {
                if ($row) {
                    return $row;
                }
            }
        }
    }
}
