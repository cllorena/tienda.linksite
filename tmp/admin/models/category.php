<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

class fwGalleryModelCategory extends JModelLegacy {
    function loadObject() {
        $category = $this->getTable('category');
		$input = JFactory::getApplication()->input;
        if (($ids = (array)$input->getVar('cid') and $id = JArrayHelper::getValue($ids, 0)) or $id = $input->getInt('id', 0)) {
            $category->load($id);
        }
        return $category;
    }

    function _collectWhere() {
        static $where;
        if (!is_array($where)) {
            $where = array();
            $db = JFactory::getDBO();

            $input = JFactory::getApplication()->input;
            if ($data = (int)$input->getInt('user')) {
                $where[] = 'c.user_id = '.$data;
            }
            if ($data = (int)$input->getInt('category')) {
                $where[] = 'c.parent = '.$data;
            }
            if ($data = $db->escape($input->getString('search'))) {
                $where[] = "(".(is_numeric($data)?("c.id = ".$data." OR "):'')."c.name LIKE '%".$data."%' OR c.descr LIKE '%".$data."%')";
            }
        }
        return $where?('WHERE '.implode(' AND ', $where)):'';
    }
    function getOrdering() {
        $app = JFactory::getApplication();

        $order = $app->input->getString('ordering');
        if (!$order or !in_array($order, array('ordering', 'name', 'created'))) $order = 'created';
		return $order;
    }
    function loadList() {
        $db = JFactory::getDBO();
        $app = JFactory::getApplication();
		$input = $app->input;

        $order = $this->getOrdering();
		if ($order == 'created') {
			$order = 'created DESC';
		}

        $limit = (int)$input->getInt('limit', $app->getCfg('list_limit'));
        $limitstart = (int)$input->getInt('limitstart', 0);

		$list = array();

        $extras = $app->triggerEvent('ongetCategoryExtraFields', array('com_fwgallery'));
        $qty_extras = $app->triggerEvent('ongetCategoryExtraCounterQuery', array('com_fwgallery', 'image'));

    	if ($where = $this->_collectWhere()) {
	        $db->setQuery('
SELECT
    c.*,
	c.name AS treename,
    u.name AS _user_name,
	(SELECT g.title FROM #__viewlevels AS g WHERE g.id = c.`access`) AS _group_name,
    (SELECT COUNT(*) FROM #__fwsg_category AS cc WHERE cc.parent = c.id) AS _qty,
    (SELECT COUNT(*) FROM #__fwsg_file AS f WHERE (f.category_id = c.id AND f.`type` = \'image\') '.implode('', $qty_extras).') AS _images_qty
    '.implode('', $extras).'
FROM
    #__fwsg_category AS c
    LEFT JOIN #__users AS u ON u.id = c.user_id
'.$where.'
ORDER BY
    c.'.$order,
    			$limitstart,
    			$limit
			);
	        $list = $db->loadObjectList();
    	} else {
	        $db->setQuery('
SELECT
    c.*,
    c.name AS treename,
    u.name AS _user_name,
    (SELECT g.title FROM #__viewlevels AS g WHERE g.id = c.`access`) AS _group_name,
    (SELECT COUNT(*) FROM #__fwsg_category AS cc WHERE cc.parent = c.id) AS _qty,
    (SELECT COUNT(*) FROM #__fwsg_file AS f WHERE (f.category_id = c.id AND f.`type` = \'image\') '.implode('', $qty_extras).') AS _images_qty
    '.implode('', $extras).'
FROM
    #__fwsg_category AS c
    LEFT JOIN #__users AS u ON u.id = c.user_id
ORDER BY
	c.parent,
    c.'.$order
            );
	        if ($rows = $db->loadObjectList()) {
	            $children = array();
	            foreach ($rows as $v) {
	                $pt = $v->parent;
	                $list = @$children[$pt] ? $children[$pt] : array();
	                array_push( $list, $v );
	                $children[$pt] = $list;
	            }
		        $levellimit = 10;
	            $list = JHTML::_('fwsgCategory.treerecurse', 0, '', array(), $children, max( 0, $levellimit-1 ) );
                if ($order == 'created') {
                    JArrayHelper::sortObjects($list, 'created', 0);
                }
	            if ($limit) $list = array_slice($list, $limitstart, $limit);
	        }
    	}
		if ($list) {
            $ids = array();
			foreach ($list as $i=>$row) {
                $ids[] = $row->id;
			}
            $app->triggerEvent('oncalculateCategoryListExtraFields', array('com_fwgallery', &$list, $ids, true));
		}
        return $list;
    }

    function loadQty() {
        $db = JFactory::getDBO();
        $db->setQuery('SELECT COUNT(*) FROM #__fwsg_category AS c '.$this->_collectWhere());
        return $db->loadResult();
    }

    function getPagination() {
        $app = JFactory::getApplication();
        jimport('joomla.html.pagination');
        $pagination = new JPagination(
        	$this->loadQty(),
        	$app->input->getInt('limitstart', 0),
        	$app->input->getInt('limit', $app->getCfg('list_limit'))
    	);
        return $pagination;
    }

    function saveorder() {
		$user = JFactory::getUser();
		if (!$user->authorise('core.edit', 'com_fwgallery')) {
			throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
		}
		$input = JFactory::getApplication()->input;
        $cid = (array)$input->getVar('cid');
        $order = (array)$input->getVar('order');

        if (count($cid) and count($cid) == count($order)) {
            $db = JFactory::getDBO();
            $category = $this->getTable('category');
            foreach ($cid as $num=>$id) {
                $db->setQuery('UPDATE #__fwsg_category SET ordering = '.(int)JArrayHelper::getValue($order, $num).' WHERE id = '.(int)$id);
                $db->execute();
            }
            JArrayHelper::toInteger($cid, 0);
            $db->setQuery('SELECT DISTINCT parent FROM  `#__fwsg_category` WHERE id IN ('.implode(',',$cid).')');
            if ($parents = $db->loadColumn()) {
                foreach ($parents as $parent) {
                    $category->reorder('parent='.(int)$parent);
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
		$input = JFactory::getApplication()->input;
        if ($cid = (array)$input->getVar('cid') and $id = JArrayHelper::getValue($cid, 0)) {
            $category = $this->getTable('category');
            if ($category->load($id)) $category->move(1, 'parent='.(int)$category->parent);
            return true;
        }
    }

    function orderup() {
		$user = JFactory::getUser();
		if (!$user->authorise('core.edit', 'com_fwgallery')) {
			throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
		}
		$input = JFactory::getApplication()->input;
        if ($cid = (array)$input->getVar('cid') and $id = JArrayHelper::getValue($cid, 0)) {
            $category = $this->getTable('category');
            if ($category->load($id)) $category->move(-1, 'parent='.(int)$category->parent);
            return true;
        }
    }

    function save() {
		$user = JFactory::getUser();
		$category = $this->getTable('category');
		$input = JFactory::getApplication()->input;
		if ($id = $input->getInt('id') and !$category->load($id)) $input->set('id', 0);
		if (($category->id and !$user->authorise('core.edit', 'com_fwgallery')) or (!$category->id and !$user->authorise('core.create', 'com_fwgallery'))) {
			throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
		}

        if ($category->bind($input->getArray(array(), null, 'RAW'), array('ordering')) and $category->check() and $category->store()) {
            $this->setError(JText::_('FWMG_GALLERY_STORED_SUCCESSFULLY'));
            fwgHelper::clearImageCache();
            return $category->id;
        } else {
            $this->setError(JText::_('FWMG_STORING_ERROR').':'.$category->getError());
        }
    }

    function remove() {
		$user = JFactory::getUser();
		if (!$user->authorise('core.delete', 'com_fwgallery')) {
			throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
		}
		$input = JFactory::getApplication()->input;
        if ($cid = (array)$input->getVar('cid')) {
            $category = $this->getTable('category');
            foreach ($cid as $id) $category->delete($id);
            $this->setError(JText::_('FWMG_GALLERY_WAS_REMOVED'));
            return true;
        } else $this->setError(JText::_('FWMG_NO_GALLERY_ID_PASSED_TO_REMOVE'));
    }

    function publish() {
		$user = JFactory::getUser();
		if (!$user->authorise('core.edit', 'com_fwgallery')) {
			throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
		}
		$input = JFactory::getApplication()->input;
        if ($cid = (array)$input->getVar('cid')) {
            $category = $this->getTable('category');
            $category->publish($cid, 1);
            $this->setError(JText::_('FWMG_GALLERY_PUBLISHED'));
            return true;
        } else $this->setError(JText::_('FWMG_NO_GALLERY_ID_PASSED_TO_PUBLISH'));
    }

    function unpublish() {
		$user = JFactory::getUser();
		if (!$user->authorise('core.edit', 'com_fwgallery')) {
			throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
		}
		$input = JFactory::getApplication()->input;
        if ($cid = (array)$input->getVar('cid')) {
            $category = $this->getTable('category');
            $category->publish($cid, 0);
            $this->setError(JText::_('FWMG_GALLERY_UNPUBLISHED'));
            return true;
        } else $this->setError(JText::_('FWMG_NO_GALLERY_ID_PASSED_TO_PUBLISH'));
    }

    function loadUsers($load_all = false) {
        $db = JFactory::getDBO();
        $db->setQuery('SELECT u.id, u.name FROM `#__users` AS u '.($load_all?'':('WHERE EXISTS(SELECT * FROM `#__fwsg_category` AS c WHERE c.user_id = u.id) ')).'ORDER BY u.name');
        return (array)$db->loadObjectList();
    }
    function upload() {
		$user = JFactory::getUser();
		if (!$user->authorise('core.edit', 'com_fwgallery')) {
			throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
		}
        $category = $this->getTable('category');
		$input = JFactory::getApplication()->input;
        if ($id = $input->getInt('id') and !$category->load($id)) $input->set('id', 0);

        if ($category->bind($input->getArray(array(), null, 'RAW'), array('ordering')) and $category->check() and $category->store()) {
			$category->load($category->id);
            return (object)array(
				'id' => $category->id,
				'media_code' => $category->media_code,
				'_video_size' => fwgHelper::humanFileSize($category->_video_size),
				'image' => JURI::root(true).'/index.php?option=com_fwgallery&view=fwgallery&layout=img&format=raw&amp;w=266&h=200&id='.$category->id.'&rand='.rand()
			);
        } else {
        	$this->setError($category->getError());
		}
	}
    function deleteImage() {
		$user = JFactory::getUser();
		if (!$user->authorise('core.edit', 'com_fwgallery')) {
			throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
		}
        $category = $this->getTable('category');
		$input = JFactory::getApplication()->input;
        if ($id = $input->getInt('id') and $category->load($id)) {
			$input->set('delete_image', 1);
			if ($category->check() and $category->store()) {
				return (object)array(
					'id' => $category->id,
					'image' => JURI::root(true).'/index.php?option=com_fwgallery&view=fwgallery&layout=img&format=raw&amp;w=266&h=200&id='.$category->id.'&rand='.rand()
				);
			} else {
				$this->setError($category->getError());
			}
		} else $this->setError(JText::_('FWMG_CATEGORY_NOT_FOUND'));
    }
    function deleteVideo() {
		$user = JFactory::getUser();
		if (!$user->authorise('core.edit', 'com_fwgallery')) {
			throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
		}
        $category = $this->getTable('category');
		$input = JFactory::getApplication()->input;
        if ($id = $input->getInt('id') and $category->load($id)) {
			$input->set('delete_video', 1);
			if ($category->check() and $category->store()) {
				return (object)array(
					'id' => $category->id
				);
			} else {
				$this->setError($category->getError());
			}
		} else $this->setError(JText::_('FWMG_CATEGORY_NOT_FOUND'));
    }
    function quickCategories() {
        $input = JFactory::getApplication()->input;
        if ($text = $input->getString('text')) {
            $db = JFactory::getDBO();
            $user = JFactory::getUser();
            $now = JFactory::getDate()->toSql();
            $data = explode("\n", str_replace("\r", '', $text));
            $parents = array(0);
            $parent = 0;
            $prev_qty = 0;
            $prev_id = 0;
            $updated = $added = 0;

            $parent = 0;
            foreach ($data as $name) {
                $name = trim($name);
                if (empty($name)) {
                    continue;
                }
                $qty = 0;
                if (preg_match('#^([-]+)#', $name, $match)) {
                    $qty = strlen($match[1]);
                    $name = ltrim($name, '- ');
                }
                if ($qty > $prev_qty) {
                    if ($prev_id) {
                        $parent = $prev_id;
                        $parents[] = $prev_id;
                        $prev_qty = $qty;
                    } else {
                        $this->setError(JText::_('FWMG_NO_TOP_LEVEL'));
                        return;
                    }
                } elseif ($qty < $prev_qty) {
                    for ($i = $qty; $i <= $prev_qty; $i++) {
                        if (count($parents) > 1) {
                            $parent = array_pop($parents);
                        } else {
                            $parent = 0;
                        }
                    }
                    $prev_qty = $qty;
                }
                $db->setQuery('
SELECT
    (SELECT id FROM `#__fwsg_category` WHERE name = '.$db->quote($name).' AND parent = '.(int)$parent.') AS prev_id,
    (SELECT MAX(ordering) FROM `#__fwsg_category` WHERE parent = '.(int)$parent.') AS ordering');
                if ($obj = $db->loadObject()) {
                    $prev_id = $obj->prev_id;
                    if ($prev_id) {
                        $updated++;
                    } else {
                        $db->setQuery('
INSERT INTO
    `#__fwsg_category`
SET
    published = 1,
    access = 1,
    created = '.$db->quote($now).',
    updated = '.$db->quote($now).',
    user_id = '.(int)$user->id.',
    name = '.$db->quote($name).',
    alias = '.$db->quote(JFilterOutput::stringURLSafe($name)).',
    parent = '.(int)$parent.',
    ordering = '.((int)$obj->ordering + 1));
                        if ($db->execute()) {
                            $added++;
                            $prev_id = $db->insertid();
                        }
                    }
                } else {
                    $this->setError($db->getError());
                }
            }
            if ($added and $updated) {
                $this->setError(JText::sprintf('FWMG_CATEGORY_QUICK_IMPORT_RESULT_ADDED_EXISTED', $added, $updated));
            } elseif ($added) {
                $this->setError(JText::sprintf('FWMG_CATEGORY_QUICK_IMPORT_RESULT_ADDED', $added));
            } elseif ($updated) {
                $this->setError(JText::sprintf('FWMG_CATEGORY_QUICK_IMPORT_RESULT_EXISTED', $updated));
            } else {
                $this->setError(JText::_('FWMG_CATEGORY_QUICK_IMPORT_RESULT'));
            }
            return true;
        } else $this->setError(JText::_('FWMG_NOTHING_TO_PROCESS'));
    }
}
