<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

class TableFile extends JTable {
    var $id = null,
		$category_id = null,
        $type = null,
        $published = null,
		$featured = null,
        $ordering = null,
        $created = null,
		$updated = null,
        $access = null,
        $user_id = null,
        $name = null,
		$copyright = null,
		$metadescr = null,
		$metakey = null,
        $descr = null,
        $params = null,
        $alias = null,
        $hits = null;

    var $_user_name = null,
		$_category_name = null,
		$_image_exists = null,
		$_filename = null,
		$_sys_filename = null,

        $_alt = null,
        $_size = null,
        $_width = null,
        $_height = null;

    function __construct(&$db) {
		$this->params = new JRegistry();
        parent::__construct('#__fwsg_file', 'id', $db);
    }
    function load($id = null, $reset = true) {
        if ($id) {
            $db = JFactory::getDBO();

			$app = JFactory::getApplication();
			$extras = $app->triggerEvent('ongetFileExtraFields', array('com_fwgallery'));

            $db->setQuery('
SELECT
    f.*,
	fi.file_id AS _image_exists,
	fi.filename AS `_filename`,
	fi.sys_filename AS _sys_filename,
    fi.alt AS _alt,
    fi.width AS _width,
    fi.height AS _height,
    fi.size AS _size,

    u.name AS _user_name,
    p.name AS _category_name,
	p.params AS params,
    (SELECT g.title FROM `#__viewlevels` AS g WHERE g.id = f.`access`) AS _group_name

    '.implode('', $extras).'
FROM
    `#__fwsg_file` AS f
    LEFT JOIN `#__fwsg_file_image` AS fi ON fi.file_id = f.id
    LEFT JOIN `#__fwsg_category` AS p ON f.category_id = p.id
    LEFT JOIN `#__users` AS u ON u.id = f.user_id
WHERE
    f.id = '.(int)$id);
//	echo $db->getQuery(); die();
            if ($obj = $db->loadObject()) {
                foreach ($obj as $key=>$val) {
                    $this->$key = $val;
                }

                if ($this->created == '0000-00-00 00:00:00') {
                    $this->created = JFactory::getDate()->toSql();
                    $db->setQuery('UPDATE `#__fwsg_category` SET created = '.$db->quote($this->created).' WHERE id = '.(int)$this->id);
                    $db->execute();
                }

				$this->params = new JRegistry($this->params);
				$app->triggerEvent('oncalculateFileExtraFields', array('com_fwgallery', $this));
                return true;
            }
        }
    }
    function check($image = null) {
		$app = JFactory::getApplication();
		jimport('joomla.filesystem.file');

		if (is_null($image)) {
			$image = $app->input->files->get('image');
		}

		$results = $app->triggerEvent('onCheckFile', array('com_fwgallery.'.$this->type, $this, $image));
		if ($results) {
			foreach ($results as $result) {
				if (!$result) return;
			}
		}

		if (!$this->name and $image and !empty($image['name']) and empty($image['error'])) {
			$this->name = JFile::stripExt($image['name']);
		}

		if (!$this->alias) {
			$this->alias = $this->name;
		}
		$this->alias = JFilterOutput::stringURLSafe($this->alias);

		if (!$this->ordering) {
			$db = JFactory::getDBO();
			$db->setQuery('SELECT MAX(ordering) FROM `#__fwsg_file` WHERE category_id ='.(int)$this->category_id);
			$this->ordering = (int)$db->loadResult() + 1;
		}

        if (!$this->created) {
            $this->created = JHTML::date('now', 'Y-m-d H:i:s');
        }
        $this->updated = JHTML::date('now', 'Y-m-d H:i:s');

        if (!$this->user_id) {
        	$this->user_id = JFactory::getUser()->id;
        }

        if (is_array($this->params)) {
			$params = new JRegistry($this->params);
			$this->params = $params->toString();
		}

		$this->access = (int)$app->input->getInt('access');

		if (strlen($this->metakey) > 200) {
			$this->metakey = substr($this->metakey, 0, 200);
		}
		if (strlen($this->metadescr) > 200) {
			$this->metadescr = substr($this->metadescr, 0, 200);
		}

        return true;
    }
	function store($upd=null, $image=null) {
		$isnew = !$this->id;
		if (parent::store($upd)) {
			$app = JFactory::getApplication();
			$input = $app->input;
			if ($input->getInt('delete_image')) {
				JHTML::_('fwSgImage.delete', $this);
			}
			if (is_null($image)) {
				$image = $input->files->get('image', array(), 'RAW');
			}
			if ($image and !empty($image['tmp_name']) and empty($image['error'])) {
				JHTML::_('fwSgImage.store', $this, $image);
			}

			$app->triggerEvent('onStoreFile', array('com_fwgallery.'.$this->type, $this, $isnew));

			return true;
		}
	}
	function delete($id=null) {
		if (parent::delete($id)) {
			JHTML::_('fwSgImage.delete', $this);
			$app = JFactory::getApplication();
			$app->triggerEvent('onDeleteFile', array('com_fwgallery', $this));
			return true;
		}
	}
    function clockwise($cid) {
    	if ($cid) {
			foreach ($cid as $id) {
				if ($this->load((int)$id) and fwgHelper::fileExists($this->_sys_filename)) {
					fwgHelper::clearImageCache('f'.$id);
					if ($this->category_id) fwgHelper::clearImageCache('c'.$this->category_id);
					$img_path = fwgHelper::getImagePath($this->_sys_filename);
					JHTML::_('fwSgImage.rotate', $img_path, $this->_sys_filename, 270);
				}
			}
    	}
    }

    function counterclockwise($cid) {
    	if ($cid) {
			foreach ($cid as $id) {
				if ($this->load((int)$id) and fwgHelper::fileExists($this->_sys_filename)) {
					fwgHelper::clearImageCache('f'.$id);
					if ($this->category_id) fwgHelper::clearImageCache('c'.$this->category_id);
					$img_path = fwgHelper::getImagePath($this->_sys_filename);
					JHTML::_('fwSgImage.rotate', $img_path, $this->_sys_filename, 90);
				}
			}
    	}
    }
}
