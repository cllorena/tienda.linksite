<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

class fwGalleryModelFile extends JModelLegacy {
    function getUserState($name, $def='', $type='string') {
        $app = JFactory::getApplication();
        return $app->getUserStateFromRequest('com_fwgallery.image.'.$name, $name, $def, $type);
    }
    function _collectWhere() {
		static $where;
		if (!is_array($where)) {
			$db = JFactory :: getDBO();
			$app = JFactory::getApplication();
			$where = array();

			$types = array('image');
			$buff = $app->triggerEvent('ongetType', array('com_fwgallery'));
			if ($buff) {
				foreach ($buff as $type) {
					if ($type) {
						$types[] = $type;
					}
				}
			}
			if ($data = $this->getUserState('tab') and is_string($data)) {
				$where[] = 'f.`type` = '.$db->quote($data);
			} else {
				$where[] = 'f.`type` IN (\''.implode('\',\'', $types).'\')';
			}
			if ($data = $this->getUserState('search') and is_string($data)) {
				$where[] = '(f.id = '.(int)$data.' OR f.name LIKE \'%'.$db->escape($data).'%\')';
			}
			if ($data = $this->getUserState('user')) {
				$where[] = 'f.user_id = '.$data;
			}
			if ($data = $this->getUserState('category')) {
				$where[] = 'f.category_id = '.$data;
			}
			$app->triggerEvent('oncollectListWhere', array('com_fwgallery', $where));
		}

        return $where?('WHERE '.implode(' AND ', $where)):'';
    }
    function loadQty() {
		$db = JFactory::getDBO();
		$db->setQuery('
SELECT
    COUNT(*)
FROM
    #__fwsg_file AS f
    LEFT JOIN #__fwsg_category AS p ON f.category_id = p.id
'.$this->_collectWhere());
		return $db->loadResult();
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
	function getOrdering() {
		$app = JFactory::getApplication();
		$order = $app->input->getString('ordering', 'created');
		if (!$order or !in_array($order, array('ordering', 'name', 'created'))) $order = 'created';
		return $order;
	}
    function loadList() {
		$app = JFactory::getApplication();
		$order = $this->getOrdering();
		if ($order == 'created') {
			$order = 'created DESC';
		}

		$extras = $app->triggerEvent('ongetFileExtraFields', array('com_fwgallery'));

		$db = JFactory::getDBO();
		$db->setQuery('
SELECT
    f.*,
    fi.alt AS _alt,
    fi.width AS _width,
	fi.height AS _height,
	fi.sys_filename AS _sys_filename,
    u.name AS _user_name,
    p.name AS _category_name,
    p.parent AS _category_parent,
    p.params AS _category_params,
    (SELECT g.title FROM #__viewlevels AS g WHERE g.id = f.`access`) AS _group_name
    '.implode('', $extras).'
FROM
    #__fwsg_file AS f
    LEFT JOIN #__fwsg_file_image AS fi ON fi.file_id = f.id
    LEFT JOIN #__fwsg_category AS p ON f.category_id = p.id
    LEFT JOIN #__users AS u ON u.id = f.user_id
'.$this->_collectWhere().'
ORDER BY
'.(($order == 'ordering')?'    p.ordering,
    p.name,
    p.id,
':'').'
    f.'.$order,
			$app->input->getInt('limitstart'),
			$app->input->getInt('limit', $app->getCfg('list_limit'))
		);
//echo $db->getQuery(); die();
		if ($list = $db->loadObjectList()) {
			$ids = array();
			foreach ($list as $i=>$row) {
				$ids[] = $row->id;
				$list[$i]->_category_params = new JRegistry($row->_category_params);
			}
			$app->triggerEvent('oncalculateFileListExtraFields', array('com_fwgallery', &$list, $ids, true));
			return $list;
		}
    }

    function save() {
		$user = JFactory::getUser();
		$file = $this->getTable('file');
		$input = JFactory::getApplication()->input;
        if ($id = $input->getInt('id') and !$file->load($id)) $input->set('id', 0);
        if (($file->id and !$user->authorise('core.edit', 'com_fwgallery')) or (!$file->id and !$user->authorise('core.create', 'com_fwgallery'))) {
        	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        }

        if ($file->bind($input->getArray(array(), null, 'RAW'), array('ordering')) and $file->check() and $file->store()) {
            $this->setError(JText::_('FWMG_THE_FILE_DATA').' '.JText::_('FWMG_STORED_SUCCESSFULLY'));
            return $file->id;
        } else
        	$this->setError(JText::_('FWMG_THE_FILE_DATA').' '.JText::_('FWMG_STORING_ERROR').':'.$file->getError());
    }

    function remove() {
		$user = JFactory::getUser();
		if (!$user->authorise('core.delete', 'com_fwgallery')) {
			throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
		}
		$app = JFactory::getApplication();
        if ($cid = (array)$app->input->getVar('cid')) {
            $file = $this->getTable('file');
            foreach ($cid as $id) {
				if ($file->load($id)) {
					$file->delete($id);
				}
            }
            $this->setError(JText::_('FWMG_IMAGE_REMOVED'));
            return true;
        }
        $this->setError(JText::_('FWMG_NO_IMAGE_ID_PASSED_TO_REMOVE'));
    }
    function saveorder() {
		$user = JFactory::getUser();
		if (!$user->authorise('core.edit', 'com_fwgallery')) {
			throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
		}
		$app = JFactory::getApplication();

		$input = $app->input;
        $cid = (array)$input->getVar('cid');
        $order = (array)$input->getVar('order');

		$pids = array();
        if (is_array($cid) and is_array($order) and count($cid) and count($cid) == count($order)) {
            $db = JFactory::getDBO();
            $file = $this->getTable('file');
            foreach ($cid as $num=>$id) {
            	if ($file->load($id)) {
                    if (array_search($file->category_id, $pids) === false) {
                		$pids[] = $file->category_id;
                	}
                    $ordering = (int)JArrayHelper::getValue($order, $num);
                	if ($file->ordering != $ordering) {
                		$db->setQuery('UPDATE #__fwsg_file SET ordering = '.(int)$ordering.' WHERE id = '.(int)$file->id);
						$db->execute();
                	}
                }
            }

            if ($pids) {
            	foreach ($pids as $category_id) {
		            $file->reorder('category_id = '.$category_id);
            	}
            }
            return true;
        }
    }
    function orderdown() {
		$user = JFactory::getUser();
		if (!$user->authorise('core.edit', 'com_fwgallery')) {
			throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
		}
		$app = JFactory::getApplication();

		$input = JFactory::getApplication()->input;
        if ($cid = (array)$input->getVar('cid') and $id = JArrayHelper::getValue($cid, 0)) {
            $file = $this->getTable('file');
            $file->load($id);
            $file->move(1, 'category_id='.$file->category_id);
            return true;
        }
    }
    function orderup() {
		$user = JFactory::getUser();
		if (!$user->authorise('core.edit', 'com_fwgallery')) {
			throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
		}
		$app = JFactory::getApplication();

		$input = $app->input;
        if ($cid = (array)$input->getVar('cid') and $id = JArrayHelper::getValue($cid, 0)) {
            $file = $this->getTable('file');
            $file->load($id);
            $file->move(-1, 'category_id='.$file->category_id);
            return true;
        }
    }
    function publish() {
		$user = JFactory::getUser();
		if (!$user->authorise('core.edit', 'com_fwgallery')) {
			throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
		}
		$app = JFactory::getApplication();

		$input = $app->input;
        if ($cid = (array)$input->getVar('cid')) {
            $file = $this->getTable('file');
            $file->publish($cid, 1);
            $this->setError(JText::_('FWMG_IMAGE_PUBLISHED'));
            return true;
        }
        $this->setError(JText::_('FWMG_NO_IMAGE_ID_PASSED_TO_PUBLISH'));
    }
    function unpublish() {
		$user = JFactory::getUser();
		if (!$user->authorise('core.edit', 'com_fwgallery')) {
			throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
		}
		$app = JFactory::getApplication();

		$input = $app->input;
        if ($cid = (array)$input->getVar('cid')) {
            $file = $this->getTable('file');
            $file->publish($cid, 0);
            $this->setError(JText::_('FWMG_IMAGE_UNPUBLISHED'));
            return true;
        }
        $this->setError(JText::_('FWMG_NO_IMAGE_ID_PASSED_TO_UNPUBLISH'));
    }
    function clockwise() {
		$user = JFactory::getUser();
		if (!$user->authorise('core.edit', 'com_fwgallery')) {
			throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
		}
		$input = JFactory::getApplication()->input;
        if ($cid = (array)$input->getVar('cid')) {
            $file = $this->getTable('file');
            return $file->clockwise($cid);
        }
        return false;
    }
    function counterClockwise() {
		$user = JFactory::getUser();
		if (!$user->authorise('core.edit', 'com_fwgallery')) {
			throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
		}
		$input = JFactory::getApplication()->input;
        if ($cid = (array)$input->getVar('cid')) {
            $file = $this->getTable('file');
            return $file->counterClockwise($cid);
        }
        return false;
    }
	function install() {
		$user = JFactory::getUser();
		if (!$user->authorise('core.add', 'com_fwgallery')) {
			throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
		}
		$app = JFactory::getApplication();
		$input = $app->input;
		$success = $errors = 0;
		if ($images = $input->files->get('images', null, 'raw')) {
			jimport('joomla.filesystem.file');
			$user = JFactory::getUser();

			$category_id = (int)$input->get('category_id');
			$user_id = (int)$input->get('user_id', $user->id);
			$published = (int)$input->get('published');
			$category = $this->getTable('category');
			$category->load($category_id);

			$exts = array('gif', 'png', 'jpg', 'jpeg');
			foreach ($images as $image) {
				if (!empty($image['name']) and empty($image['error'])) {
					$ext = strtolower(JFile::getExt($image['name']));
					$type = in_array($ext, $exts)?'image':'';
					if (!$type) {
						$app->triggerEvent('ongetFileTypeByExt', array('com_fwgallery', $ext, &$type));
					}
					if ($type) {
						$file = $this->getTable('file');
						$file->id = 0;
						$file->type = $type;
						$file->published = $published;
						$file->user_id = $user_id;
						$file->category_id = $category_id;
						$file->access = 1;
						$file->user_id = $user->id;

						if ($file->check($image) and $file->store(null, $image)) {
/*							if ($type == 'image') {
								JHTML::_('fwSgImage.store', $file, $image);
							}
							$app->triggerEvent('onStoreFile', array('com_fwgallery.'.$type, $file, true, $image));*/
							$success++;
						} else {
							$this->setError($file->getError());
							$errors++;
						}
					}
				} else {
					$errors++;
				}
			}
		}
		$this->setError(JText::sprintf('FWMG_FILE_UPLOADING_RESULT', $success, $category->name));
		return $success;
	}
    function loadUsers($load_all = false) {
        $db = JFactory::getDBO();
        $db->setQuery('SELECT id, name FROM `#__users` AS u '.($load_all?'':'WHERE EXISTS(SELECT * FROM #__fwsg_file AS f WHERE f.`type` = \'image\' AND f.user_id = u.id) ').'ORDER BY name');
        return $db->loadObjectList();
    }
    function loadObject() {
        $file = $this->getTable('file');
        $cid = JFactory::getApplication()->input->getVar('cid');
        if (!empty($cid[0])) {
            $file->load($cid[0]);
        }
        return $file;
    }
	function upload() {
        $file = $this->getTable('file');
		$app = JFactory::getApplication();
		$input = $app->input;
        if ($id = $input->getInt('id')) {
			$file->load($id);
		}

		if ($file->bind($input->getArray(array(), null, 'RAW'), array('ordering')) and $file->check() and $file->store()) {
			$file->load($file->id);
			return (object)array(
				'id' => $file->id,
				'name' => $file->name,
				'alias' => $file->alias,
				'copyright' => $file->copyright,
				'metadescr' => $file->metadescr,
				'metakey' => $file->metakey,
				'updated' => $file->updated,
				'descr' => $file->descr,
				'image' => JURI::root(true).'/index.php?option=com_fwgallery&view=item&layout=img&format=raw&amp;w=491&h=300&id='.$file->id.'&rand='.rand()
			);
		} else $this->setError($file->getError());
	}
	function deleteImage() {
        $file = $this->getTable('file');
		$input = JFactory::getApplication()->input;
        if ($id = $input->getInt('id') and $file->load($id)) {
			if ($file->_sys_filename) {
				JHTML::_('fwSgImage.delete', $file);
				return (object)array(
					'id' => $file->id,
					'image' => JURI::root(true).'/index.php?option=com_fwgallery&view=item&layout=img&format=raw&amp;w=491&h=300&id='.$file->id.'&rand='.rand()
				);
			} else {
				$this->setError(JText::_('FWMG_NOTHING_TO_DELETE'));
			}
		} else $this->setError(JText::_('FWMG_FILE_NOT_FOUND'));
	}
	function batch() {
		$app = JFactory::getApplication();

		$input = JFactory::getApplication()->input;
        if ($cid = (array)$input->getVar('cid')) {
			$db = JFactory::getDBO();
			JArrayHelper::toInteger($cid, 0);
			$changes_requested = false;

			if ($data = $input->getInt('category_id')) {
				$changes_requested = true;
				$db->setQuery('UPDATE #__fwsg_file SET `category_id` = '.(int)$data.' WHERE id IN ('.implode(',', $cid).')');
				$db->execute();
			}

			$data = (int)$input->getInt('access');
			if ($data != -1) {
				$changes_requested = true;
				$db->setQuery('UPDATE #__fwsg_file SET `access` = '.(int)$data.' WHERE id IN ('.implode(',', $cid).')');
				$db->execute();
			}

			$data = (int)$input->getInt('user_id');
			if ($data != -1) {
				$changes_requested = true;
				$db->setQuery('UPDATE #__fwsg_file SET `user_id` = '.(int)$data.' WHERE id IN ('.implode(',', $cid).')');
				$db->execute();
			}

			if ($data = $input->getString('copyright')) {
				$changes_requested = true;
				$db->setQuery('UPDATE #__fwsg_file SET `copyright` = '.$db->quote($data).' WHERE id IN ('.implode(',', $cid).')');
				$db->execute();
			}

			$app->triggerEvent('ondoBatchProcessing', array('com_fwgallery', $cid, &$changes_requested, $this));

            return true;
        }
        $this->setError(JText::_('FWMG_NO_FILE_ID_PASSED_TO_PROCESS'));
	}
	function saveall() {
		$input = JFactory::getApplication()->input;
		if ($alts = $input->getVar('alt_texts')) {
			$db = JFactory::getDBO();
			foreach ($alts as $id=>$alt) {
				$db->setQuery('SELECT fi.file_id, fi.alt FROM #__fwsg_file_image AS fi WHERE file_id = '.(int)$id);
				if ($obj = $db->loadObject()) {
					if ($obj->alt != $alt) {
						$db->setQuery('UPDATE #__fwsg_file_image SET alt = '.$db->quote($alt).' WHERE file_id = '.(int)$id);
						$db->execute();
					}
				} else {
					$db->setQuery('INSERT INTO #__fwsg_file_image SET file_id = '.(int)$id.', alt = '.$db->quote($alt));
					$db->execute();
				}
			}
		}
		$this->saveorder();
	}
}
