<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

class fwGalleryModelFwGallery extends JModelLegacy {
    function _getSubcategoriesWhere($params = null) {
		$app = JFactory::getApplication();
		$id = 0;
		if (is_null($params)) {
			$id = $app->input->getInt('id');
			$params = $app->getParams();
		}

		$where = array('c.published = 1');

		$user = JFactory::getUser();
		if (!$user->authorise('core.login.admin')) {
			$where[] = '(c.`access` = 0 OR c.access IN ('.implode(',', $user->getAuthorisedViewLevels()).'))';
		}

		if ($id) {
			$where[] = 'c.parent = '.(int)$id;
		} elseif ($gids = (array)$params->get('gids') and !in_array(0, $gids)) {
			JArrayHelper::toInteger($gids, 0);
			$where[] = 'c.parent IN ('.implode(',', $gids).')';
		} else {
			$where[] = 'c.parent = 0';
		}

		$app->triggerEvent('ongetCategoryExtraSearch', array('com_fwgallery', &$where));
//print_r($where); die();
        return 'WHERE '.implode(' AND ', $where);
    }
    function getSubcategoriesOrder($params) {
        $order = JFactory::getApplication()->input->getCmd('subcategories_order', $params->get('gallery_default_ordering'));
        if (!in_array($order, array('new', 'old', 'ordering', 'alpha'))) {
            $order = 'ordering';
        }
        return $order;
    }
    function _getSubcategoriesOrderClause($params) {
        $order = $this->getSubcategoriesOrder($params);
        switch ($order) {
            case 'new' : $order = 'created DESC'; break;
            case 'old' : $order = 'created'; break;
            case 'alpha' : $order = 'name'; break;
            default : $order = 'ordering';
        }
        return 'ORDER BY c.parent, c.'.$order;
    }
    function loadCategory($params = null, $id = null) {
        $app = JFactory::getApplication();
		if (is_null($params)) {
			if (is_null($id)) {
                if ($buff = $app->input->getString('gids')) {
                    $gids = explode(',', $buff);
                    $id = $gids[0];
                } else {
		            $id = $app->input->getInt('id');
                }
			}
			if ($app->isClient('administrator')) {
				$params = JComponentHelper::getParams('com_fwgallery');
			} else {
				$params = $app->getParams();
			}
		}
        $category = $this->getTable('category');

		if (($id and $category->load($id)) or ($gids = $params->get('gids') and is_array($gids) and count($gids) == 1 and $category->load($gids[0]))) {
            $category->hit();
        }

        $app->triggerEvent('onloadTopCategory', array('com_fwgallery', &$category));
        return $category;
    }
    function loadSubcategories($params = null) {
        $app = JFactory::getApplication();
        $extra_select = $app->triggerEvent('ongetCategoryExtraFields', array('com_fwgallery'));
		$qty_extras = $app->triggerEvent('ongetCategoryExtraCounterQuery', array('com_fwgallery', 'image'));

		$loc_params = $params;
		if (is_null($loc_params)) {
			if ($app->isClient('administrator')) {
				$loc_params = JComponentHelper::getParams('com_fwgallery');
			} else {
				$loc_params = $app->getParams();
			}
		}
		$qty = $loc_params->get('gallery_columns', 4) * $loc_params->get('gallery_rows', 4);

        $db = JFactory::getDBO();
        $db->setQuery('
SELECT
    c.*,
    u.name AS _user_name,
    (SELECT COUNT(*) FROM #__fwsg_category AS cc WHERE cc.parent = c.id AND cc.published = 1) AS _qty,
    (SELECT COUNT(*) FROM #__fwsg_file AS f WHERE (f.category_id = c.id AND f.published = 1 AND f.`type` = \'image\') '.implode('', $qty_extras).') AS _images_qty
    '.implode('', $extra_select).'
FROM
    #__fwsg_category AS c
    LEFT JOIN #__users AS u ON u.id = c.user_id
    LEFT JOIN #__viewlevels AS ca ON ca.id = c.`access`
'.$this->_getSubcategoriesWhere($loc_params).'
'.$this->_getSubcategoriesOrderClause($loc_params),
            $app->input->getInt('subcategorieslimitstart'),
            $app->input->getInt('subcategorieslimit', $qty)
        );
        if ($list = $db->loadObjectList()) {
            $ids = array();
			foreach ($list as $i=>$row) {
                $ids[] = $row->id;
				$list[$i]->params = new JRegistry($row->params);
			}
            $app->triggerEvent('oncalculateCategoryListExtraFields', array('com_fwgallery', &$list, $ids));
			return $list;
		}
    }
    function loadSubcategoriesQty($params = null) {
		$app = JFactory::getApplication();

        $db = JFactory::getDBO();
        $db->setQuery('
SELECT
    COUNT(*)
FROM
    #__fwsg_category AS c
    LEFT JOIN #__users AS u ON u.id = c.user_id
    LEFT JOIN #__viewlevels AS ca ON ca.id = c.`access`
'.$this->_getSubcategoriesWhere($params));
		return $db->loadResult();
    }
    function getSubcategoriesPagination($params = null) {
        $app = JFactory::getApplication();
		$loc_params = $params;
		if (is_null($loc_params)) {
			if ($app->isClient('administrator')) {
				$loc_params = JComponentHelper::getParams('com_fwgallery');
			} else {
				$loc_params = $app->getParams();
			}
		}
		$qty = $loc_params->get('gallery_columns', 4) * $loc_params->get('gallery_rows', 4);

        jimport('joomla.html.pagination');
        return new JPagination(
        	$this->loadSubcategoriesQty($params),
    		$app->input->getInt('subcategorieslimitstart'),
    		$app->input->getInt('subcategorieslimit', $qty),
            'subcategories'
    	);
    }

    function _getFilesWhere($params = null) {
		$db = JFactory::getDBO();
		$app = JFactory::getApplication();
		$id = 0;
		if (is_null($params)) {
			$params = $app->getParams();
			$id = (int)$app->input->getInt('id');
		}
		$where = array(
			'f.published = 1',
			'c.published = 1',
		);
		if ($fid = (int)$params->get('fid')) {
			$where[] = 'f.id = '.$fid;
		} elseif ($fids = $app->input->getVar('ids2skip') and is_array($fids)) {
			$where[] = 'f.id NOT IN ('.implode(',', $fids).')';
		}
		$types = array(
			'image'
		);
        if ($buff = $app->triggerEvent('ongetType', array('com_fwgallery'))) {
    		foreach ($buff as $val) {
    			if ($val) {
    				$types[] = $val;
    			}
    		}
        }
		$where[] = "f.`type` IN ('".implode("','", $types)."')";

		$user = JFactory::getUser();
		if (!$user->authorise('core.login.admin')) {
			$where[] = '(c.`access` = 0 OR c.access IN ('.implode(',', $user->getAuthorisedViewLevels()).'))';
			$where[] = '(f.`access` = 0 OR f.access IN ('.implode(',', $user->getAuthorisedViewLevels()).'))';
		}

		$where2 = array();
		if ($id) {
			$where2[] = 'f.category_id = '.$id;
			$app->triggerEvent('ongetFileExtraCategorySearch', array('com_fwgallery', array($id), &$where2));
		} elseif ($gids = (array)$params->get('gids') and !in_array(0, $gids)) {
			JArrayHelper::toInteger($gids, 0);
			$where2[] = 'f.category_id IN ('.implode(',', $gids).')';
			$app->triggerEvent('ongetFileExtraCategorySearch', array('com_fwgallery', $gids, &$where2));
		} else {
			$where2[] = 'f.category_id = 0';
		}
		$where[] = '('.implode(' OR ', $where2).')';

        $search = fwgHelper::getSearchData();
        if (!empty($search['text'])) {
            $where[] = '(f.name LIKE \'%'.$db->escape($search['text']).'%\' OR f.descr LIKE \'%'.$db->escape($search['text']).'%\')';
        }
        if (!empty($search['type'])) {
            $subwhere = array();
            foreach ($search['type'] as $type) {
                if ($type) {
                    $subwhere[] = $db->quote($type);
                }
            }
            if ($subwhere) {
                $where[] = 'f.`type` IN ('.implode(',', $subwhere).')';
            }
        }

		$app->triggerEvent('ongetFileExtraSearch', array('com_fwgallery', $params, &$where, $search));
//print_r($where); die();
        return $where;
    }
    function getFilesOrder($params) {
        $order = JFactory::getApplication()->input->getCmd('files_order', $params->get('files_default_ordering'));
        if (!in_array($order, array('new', 'old', 'ordering', 'alpha', 'random'))) {
            $order = 'ordering';
        }
        return $order;
    }
    function _getFilesOrderClause($params) {
        $order = $this->getFilesOrder($params);
        switch ($order) {
            case 'new' : $order = 'f.updated DESC'; break;
            case 'old' : $order = 'f.updated'; break;
            case 'alpha' : $order = 'f.name'; break;
            case 'random' : $order = 'RAND()'; break;
            default : $order = 'f.ordering';
        }
		JFactory::getApplication()->triggerEvent('ongetFilesListExtraOrdering', array('com_fwgallery', $params, &$order));
        return 'ORDER BY '.$order;
    }
    function loadFiles($params = null) {
        $db = JFactory::getDBO();
        $user = JFactory::getUser();
        $app = JFactory::getApplication();

		$loc_params = $params;
		if (is_null($loc_params)) {
			if ($app->isClient('administrator')) {
				$loc_params = JComponentHelper::getParams('com_fwgallery');
			} else {
				$loc_params = $app->getParams();
			}
		}

        $extra_select = $app->triggerEvent('ongetFileExtraFields', array('com_fwgallery'));
        $extra_from = $app->triggerEvent('ongetFileExtraTables', array('com_fwgallery'));

		$qty = fwgHelper::getLimitQty(
			$loc_params->get('files_columns', 4),
			$loc_params->get('files_rows', 4),
			$loc_params->get('files_grid', 'standard')
		);

        $db->setQuery('
SELECT
    f.*,
    fi.alt AS _alt,
    fi.width AS _width,
    fi.height AS _height,
    fi.size AS _size,
	fi.sys_filename AS _sys_filename,
    c.name AS _category_name,
    u.name AS _user_name
    '.implode('', $extra_select).'
FROM
    #__fwsg_file AS f
    LEFT JOIN #__viewlevels AS fa ON fa.id = f.`access`

    LEFT JOIN #__fwsg_category AS c ON c.id = f.category_id
    LEFT JOIN #__viewlevels AS ca ON ca.id = c.`access`

    LEFT JOIN #__users AS u ON u.id = f.user_id
    LEFT JOIN #__fwsg_file_image AS fi ON fi.file_id = f.id
    '.implode('', $extra_from).'
WHERE '.implode(' AND ', $this->_getFilesWhere($loc_params)).'
'.$this->_getFilesOrderClause($loc_params),
            $app->input->getInt('fileslimitstart'),
            $app->input->getInt('fileslimit', $qty)
        );
        if ($list = $db->loadObjectList()) {
			$ids = array();
			foreach ($list as $i=>$row) {
				$ids[] = $row->id;
			}
            $app->triggerEvent('oncalculateFileListExtraFields', array('com_fwgallery', &$list, $ids));
			return $list;
        }
    }
    function loadFilesQty($params = null) {
        $app = JFactory::getApplication();
        $extra_from = $app->triggerEvent('ongetFileExtraTables', array('com_fwgallery'));

        $db = JFactory::getDBO();
        $db->setQuery('
SELECT
    COUNT(*)
FROM
    #__fwsg_file AS f
    LEFT JOIN #__viewlevels AS fa ON fa.id = f.`access`

    LEFT JOIN #__fwsg_category AS c ON c.id = f.category_id
    LEFT JOIN #__viewlevels AS ca ON ca.id = c.`access`

    LEFT JOIN #__users AS u ON u.id = f.user_id
    LEFT JOIN #__fwsg_file_image AS fi ON fi.file_id = f.id
    '.implode('', $extra_from).'

WHERE '.implode(' AND ', $this->_getFilesWhere($params))
        );
        return $db->loadResult();
    }
    function getFilesPagination($params = null) {
        $app = JFactory::getApplication();
		$loc_params = $params;
		if (is_null($loc_params)) {
			if ($app->isClient('administrator')) {
				$loc_params = JComponentHelper::getParams('com_fwgallery');
			} else {
				$loc_params = $app->getParams();
			}
		}
		$qty = fwgHelper::getLimitQty(
			$loc_params->get('files_columns', 4),
			$loc_params->get('files_rows', 4),
			$loc_params->get('files_grid', 'standard')
		);
        jimport('joomla.html.pagination');
        return new JPagination(
        	$this->loadFilesQty($loc_params),
    		$app->input->getInt('fileslimitstart'),
    		$app->input->getInt('fileslimit', $qty),
            'files'
    	);
    }
    function getImage() {
		$input = JFactory::getApplication()->input;
		$id = (int)$input->getInt('id');
		$js = (int)$input->getInt('js');
		$height = (int)$input->getInt('h');
		$width = (int)$input->getInt('w');
		$t_path = JPATH_SITE.'/cache/fwgallery/images/';
		$t_filename = 'c'.$id.'_'.md5($id.'h'.$height.'w'.$width.'j'.$js).'.jpg';
		if (!file_exists($t_path.$t_filename)) {
			ini_set('memory_limit', '512M');
            jimport('joomla.filesystem.file');
            $img_exts = array('gif', 'png', 'jpg', 'jpeg');

			$filename = '';
			$s_path = FWMG_STORAGE_PATH;

			$db = JFactory::getDBO();
			$db->setQuery('
SELECT
	parent,
	params,
	sys_filename,
	(SELECT fi.sys_filename FROM `#__fwsg_file` AS f, #__fwsg_file_image AS fi WHERE f.category_id = c.id AND fi.file_id = f.id ORDER BY f.ordering LIMIT 1) AS _image_sys_filename
FROM
	#__fwsg_category AS c
WHERE
	c.id = '.$id
			);
			$category = $db->loadObject();
			if ($category) {
				if ($category->sys_filename) {
					$buff = $category->sys_filename;
					$ext = strtolower(JFile::getExt($buff));
					if (in_array($ext, $img_exts)) {
						$prefix = substr($buff, 0, 2);
						if (file_exists($s_path.$prefix.'/'.$buff)) {
							$s_path .= $prefix.'/';
							$filename = $buff;
						}
					}
				}
				if (!$filename and $category->_image_sys_filename) {
					$buff = $category->_image_sys_filename;
					$ext = strtolower(JFile::getExt($buff));
					if (in_array($ext, $img_exts)) {
						$prefix = substr($buff, 0, 2);
						if (file_exists($s_path.$prefix.'/'.$buff)) {
							$s_path .= $prefix.'/';
							$filename = $buff;
						}
					}
				}
			}

			if (!$filename or ($filename and !file_exists($s_path.$filename))) {
				$s_path = FWMG_COMPONENT_SITE.'/assets/images/';
				$filename = 'no_image.jpg';
			}

			if ($filename and file_exists($s_path.$filename)) {
				if (!file_exists($t_path)) {
                    if (!JFile::write($t_path.'index.html', $buff = '<html><head><title></title></head><body></body></html>')) {
                        $this->setError(JText::_('FWMG_CACHE_FOLDER_NOT_WRITABLE'));
                    }
                }
                $com_params = JComponentHelper::getParams('com_fwgallery');
                $params = $category?(new fwgParams($category->params, fwgHelper::getParentGalleries($category->parent))):$com_params;

				$use_wm = $params->get('use_watermark', $com_params->get('use_watermark'));
				$dw_with_wm = $com_params->get('download_with_watermark');
				$wmfile = $wmtext = $wmpos = '';
				if ($use_wm) {
					$wmfile = fwgHelper::getWatermarkFilename();
					$wmtext = $com_params->get('watermark_text');
					$wmpos = $com_params->get('watermark_position', 'left bottom');
				}

				$ext = strtolower(JFile::getExt($filename));
				if (!in_array($ext, $img_exts)) {
					return $s_path.$filename;
				} else {
					if (!$height and !$width) {
						if ($use_wm and $dw_with_wm) {
							if (!JFile::copy($s_path.$filename, $t_path.$t_filename)) {
                                $this->setError(JText::_('FWMG_CANT_COPY_FILE_CO_CACHE_FOLDER'));
                            }
						} else {
							return $s_path.$filename;
						}
					}
					$image = JHTML::_('fwsgImage.get', $s_path, $filename);

					if ($image) {
						JHTML::_('fwsgImage.fit', $image, $t_path, $t_filename, $width, $height, $wmfile, $wmtext, $wmpos, $js);
					} else {
                        $this->setError(JText::_('FWMG_CANT_COPY_FILE_CO_CACHE_FOLDER'));
					}
				}
			}
		}
		if (file_exists($t_path.$t_filename)) return $t_path.$t_filename;
	}
    function loadModuleData($params) {
		$app = JFactory::getApplication();
    	$db = JFactory::getDBO();

		if ($params->get('display') == 'categories') {
/* collect filtering clause */
			$where = array(
				'c.published = 1'
			);
			if ($gallery_id = (array)$params->get('gallery_id')) {
				JArrayHelper::toInteger($gallery_id, 0);
				$gids = fwgHelper::getChildGalleriesIDS($gallery_id);
				$where[] = 'c.parent IN ('.implode(',', $gids).')';
			}
			$user = JFactory::getUser();
			if (!$user->authorise('core.login.admin')) {
				$where[] = '(c.access = 0 OR c.access IN ('.implode(',', $user->getAuthorisedViewLevels()).'))';
			}
			switch ($params->get('order')) {
				case 'alpha' : $order = 'c.name';
				break;
				case 'popular' : $order = 'c.hits DESC';
				break;
				case 'rand' : $order = 'RAND()';
				break;
				case 'order' : $order = 'c.ordering';
				break;
				default : $order = 'c.created DESC';
			}
            $extra_select = $app->triggerEvent('ongetCategoryExtraFields', array('com_fwgallery'));
            $qty_extras = $app->triggerEvent('ongetCategoryExtraCounterQuery', array('com_fwgallery', 'image'));

            $db->setQuery('
SELECT
    c.*,
    u.name AS _user_name,
    (SELECT COUNT(*) FROM #__fwsg_category AS cc WHERE cc.parent = c.id AND cc.published = 1) AS _qty,
    (SELECT COUNT(*) FROM #__fwsg_file AS f WHERE (f.category_id = c.id AND f.published = 1 AND f.`type` = \'image\') '.implode('', $qty_extras).') AS _images_qty
    '.implode('', $extra_select).'
FROM
    #__fwsg_category AS c
    LEFT JOIN #__users AS u ON u.id = c.user_id
    LEFT JOIN #__viewlevels AS ca ON ca.id = c.`access`
WHERE
	'.implode(' AND ', $where).'
ORDER BY
	'.$order,
				0,
				$params->get('limit', 10)
            );
			if ($list = $db->loadObjectList()) {
				$ids = array();
				foreach ($list as $i=>$row) {
					$ids[] = $row->id;
					$list[$i]->params = new JRegistry($row->params);
				}
				$app->triggerEvent('oncalculateCategoryListExtraFields', array('com_fwgallery', &$list, $ids));
				return $list;
			}
		} else {
/* collect filtering clause */
			$where = array(
				'f.published = 1',
				'c.published = 1'
			);

			$types = array(
				'image'
			);
			if ($buff = $app->triggerEvent('ongetType', array('com_fwgallery'))) {
				foreach ($buff as $val) {
					if ($val) {
						$types[] = $val;
					}
				}
			}
			$where[] = "f.`type` IN ('".implode("','", $types)."')";

			if ($gallery_id = (array)$params->get('gallery_id')) {
				JArrayHelper::toInteger($gallery_id, 0);
				$gids = fwgHelper::getChildGalleriesIDS($gallery_id);
				$where[] = 'f.category_id IN ('.implode(',', $gids).')';
			}

			$user = JFactory::getUser();
			if (!$user->authorise('core.login.admin')) {
				$where[] = '(c.access = 0 OR c.access IN ('.implode(',', $user->getAuthorisedViewLevels()).'))';
			}
/* collect ordering clause */
			switch ($params->get('order')) {
				case 'alpha' : $order = 'f.name';
				break;
				case 'popular' : $order = 'f.hits DESC';
				break;
				case 'rand' : $order = 'RAND()';
				break;
				case 'order' : $order = 'c.ordering, f.ordering';
				break;
				default : $order = 'c.created DESC, f.updated DESC';
			}
			$app->triggerEvent('ongetModuleDataExtraOrdering', array('com_fwgallery', $params, &$order));
			$app->triggerEvent('ongetModuleDataExtraSearch', array('com_fwgallery', $params, &$where));
			$extra_select = $app->triggerEvent('ongetFileExtraFields', array('com_fwgallery'));
			$extra_from = $app->triggerEvent('ongetFileExtraTables', array('com_fwgallery'));
/* load data */
    		$db->setQuery('
SELECT
    f.*,
    fi.alt AS _alt,
    fi.width AS _width,
    fi.height AS _height,
    fi.size AS _size,
	fi.sys_filename AS _sys_filename,
    c.name AS _category_name,
    u.name AS _user_name
    '.implode('', $extra_select).'
FROM
    #__fwsg_file AS f
    LEFT JOIN #__viewlevels AS fa ON fa.id = f.`access`

    LEFT JOIN #__fwsg_category AS c ON c.id = f.category_id
    LEFT JOIN #__viewlevels AS ca ON ca.id = c.`access`

    LEFT JOIN #__users AS u ON u.id = f.user_id
    LEFT JOIN #__fwsg_file_image AS fi ON fi.file_id = f.id
    '.implode('', $extra_from).'
WHERE
	'.implode(' AND ', $where).'
ORDER BY
	'.$order,
				0,
				$params->get('limit', 10)
			);
			if ($list = $db->loadObjectList()) {
				$ids = array();
				foreach ($list as $i=>$row) {
					$ids[] = $row->id;
				}
				$app->triggerEvent('oncalculateFileListExtraFields', array('com_fwgallery', &$list, $ids));
				return $list;
			}
		}
    }
    function _getFlatFilesWhere($params = null) {
		$db = JFactory::getDBO();
		$app = JFactory::getApplication();
		$id = 0;
		if (is_null($params)) {
			$params = $app->getParams();
			$id = (int)$app->input->getInt('id');
		}
		$where = array(
			'f.published = 1',
			'c.published = 1',
		);
		if ($fid = (int)$params->get('fid')) {
			$where[] = 'f.id = '.$fid;
		}
		$types = array(
			'image'
		);
        if ($buff = $app->triggerEvent('ongetType', array('com_fwgallery'))) {
    		foreach ($buff as $val) {
    			if ($val) {
    				$types[] = $val;
    			}
    		}
        }
		$where[] = "f.`type` IN ('".implode("','", $types)."')";

		$user = JFactory::getUser();
		if (!$user->authorise('core.login.admin')) {
			$where[] = '(c.`access` = 0 OR c.access IN ('.implode(',', $user->getAuthorisedViewLevels()).'))';
			$where[] = '(f.`access` = 0 OR f.access IN ('.implode(',', $user->getAuthorisedViewLevels()).'))';
		}

		$where2 = array();
		if ($id) {
            $gids = fwgHelper::getChildGalleriesIDS(array($id));
			$where2[] = 'f.category_id IN ('.implode(',', $gids).')';
			$app->triggerEvent('ongetFileExtraCategorySearch', array('com_fwgallery', $gids, &$where2));
		} elseif ($gids = (array)$params->get('gids') and !in_array(0, $gids)) {
			JArrayHelper::toInteger($gids, 0);
            $gids = fwgHelper::getChildGalleriesIDS($gids);
			$where2[] = 'f.category_id IN ('.implode(',', $gids).')';
			$app->triggerEvent('ongetFileExtraCategorySearch', array('com_fwgallery', $gids, &$where2));
		} else {
			$where2[] = 'f.category_id = 0';
		}
		$where[] = '('.implode(' OR ', $where2).')';

        $search = fwgHelper::getSearchData();
        if (!empty($search['text'])) {
            $where[] = '(f.name LIKE \'%'.$db->escape($search['text']).'%\' OR f.descr LIKE \'%'.$db->escape($search['text']).'%\')';
        }
        if (!empty($search['type'])) {
            $subwhere = array();
            foreach ($search['type'] as $type) {
                if ($type) {
                    $subwhere[] = $db->quote($type);
                }
            }
            if ($subwhere) {
                $where[] = 'f.`type` IN ('.implode(',', $subwhere).')';
            }
        }

		$app->triggerEvent('ongetFileExtraSearch', array('com_fwgallery', $params, &$where, $search));
//print_r($where); die();
        return $where;
    }
    function loadFlatFiles($params = null) {
        $db = JFactory::getDBO();
        $user = JFactory::getUser();
        $app = JFactory::getApplication();

		$loc_params = $params;
		if (is_null($loc_params)) {
			if ($app->isClient('administrator')) {
				$loc_params = JComponentHelper::getParams('com_fwgallery');
			} else {
				$loc_params = $app->getParams();
			}
		}

        $extra_select = $app->triggerEvent('ongetFileExtraFields', array('com_fwgallery'));
        $extra_from = $app->triggerEvent('ongetFileExtraTables', array('com_fwgallery'));

		$qty = fwgHelper::getLimitQty(
			$loc_params->get('files_columns', 4),
			$loc_params->get('files_rows', 4),
			$loc_params->get('files_grid', 'standard')
		);

        $order = $this->_getSubcategoriesOrderClause($loc_params);
        $order .= ', c.id, '.str_replace('ORDER BY', '', $this->_getFilesOrderClause($loc_params));

        $db->setQuery('
SELECT
    f.*,
    fi.alt AS _alt,
    fi.width AS _width,
    fi.height AS _height,
    fi.size AS _size,
	fi.sys_filename AS _sys_filename,
    c.name AS _category_name,
    u.name AS _user_name
    '.implode('', $extra_select).'
FROM
    #__fwsg_file AS f
    LEFT JOIN #__viewlevels AS fa ON fa.id = f.`access`

    LEFT JOIN #__fwsg_category AS c ON c.id = f.category_id
    LEFT JOIN #__viewlevels AS ca ON ca.id = c.`access`

    LEFT JOIN #__users AS u ON u.id = f.user_id
    LEFT JOIN #__fwsg_file_image AS fi ON fi.file_id = f.id
    '.implode('', $extra_from).'
WHERE '.implode(' AND ', $this->_getFlatFilesWhere($loc_params)).'
'.$order,
            $app->input->getInt('fileslimitstart'),
            $app->input->getInt('fileslimit', $qty)
        );
//echo $db->getQuery(); die();
        if ($list = $db->loadObjectList()) {
			$ids = array();
			foreach ($list as $i=>$row) {
				$ids[] = $row->id;
			}
            $app->triggerEvent('oncalculateFileListExtraFields', array('com_fwgallery', &$list, $ids));
			return $list;
        }
    }
    function loadFlatFilesQty($params = null) {
        $app = JFactory::getApplication();
        $extra_from = $app->triggerEvent('ongetFileExtraTables', array('com_fwgallery'));

        $db = JFactory::getDBO();
        $db->setQuery('
SELECT
    COUNT(*)
FROM
    #__fwsg_file AS f
    LEFT JOIN #__viewlevels AS fa ON fa.id = f.`access`

    LEFT JOIN #__fwsg_category AS c ON c.id = f.category_id
    LEFT JOIN #__viewlevels AS ca ON ca.id = c.`access`

    LEFT JOIN #__users AS u ON u.id = f.user_id
    LEFT JOIN #__fwsg_file_image AS fi ON fi.file_id = f.id
    '.implode('', $extra_from).'

WHERE '.implode(' AND ', $this->_getFlatFilesWhere($params))
        );
        return $db->loadResult();
    }
    function getFlatFilesPagination($params = null) {
        $app = JFactory::getApplication();
		$loc_params = $params;
		if (is_null($loc_params)) {
			if ($app->isClient('administrator')) {
				$loc_params = JComponentHelper::getParams('com_fwgallery');
			} else {
				$loc_params = $app->getParams();
			}
		}
		$qty = fwgHelper::getLimitQty(
			$loc_params->get('files_columns', 4),
			$loc_params->get('files_rows', 4),
			$loc_params->get('files_grid', 'standard')
		);
        jimport('joomla.html.pagination');
        return new JPagination(
        	$this->loadFlatFilesQty($loc_params),
    		$app->input->getInt('fileslimitstart'),
    		$app->input->getInt('fileslimit', $qty),
            'files'
    	);
    }
    function loadFlatSubcategories($files) {
        $cids = array();
        if ($files) {
            foreach ($files as $file) {
                if (!in_array($file->category_id, $cids)) {
                    $cids[] = (int)$file->category_id;
                }
            }
        }
        if ($cids) {
            $app = JFactory::getApplication();
            $extra_select = $app->triggerEvent('ongetCategoryExtraFields', array('com_fwgallery'));
    		$qty_extras = $app->triggerEvent('ongetCategoryExtraCounterQuery', array('com_fwgallery', 'image'));

            $db = JFactory::getDBO();
            $db->setQuery('
SELECT
    c.*,
    u.name AS _user_name,
    (SELECT COUNT(*) FROM #__fwsg_category AS cc WHERE cc.parent = c.id AND cc.published = 1) AS _qty,
    (SELECT COUNT(*) FROM #__fwsg_file AS f WHERE (f.category_id = c.id AND f.published = 1 AND f.`type` = \'image\') '.implode('', $qty_extras).') AS _images_qty
    '.implode('', $extra_select).'
FROM
    #__fwsg_category AS c
    LEFT JOIN #__users AS u ON u.id = c.user_id
    LEFT JOIN #__viewlevels AS ca ON ca.id = c.`access`
WHERE
    c.id IN ('.implode(',', $cids).')');
            if ($list = $db->loadObjectList()) {
                $ids = array();
    			foreach ($list as $i=>$row) {
                    $ids[] = $row->id;
    				$list[$i]->params = new JRegistry($row->params);
    			}
                $app->triggerEvent('oncalculateCategoryListExtraFields', array('com_fwgallery', &$list, $ids));
    			return $list;
    		}
        }
	}
	function loadAllCategories($params) {
		$gids = (array)$params->get('gids');
		if ($gids) {
			foreach ($gids as $id) {
				if (!$id) {
					$gids = null;
					break;
				} 
			}
		}
		$data = array();
		$cats = fwgHelper::loadCategories();
		if ($cats) {
			foreach ($cats as $cat) {
				if ($cat->published and (!$gids or ($gids and in_array($cat->id, $gids)))) {
					$data[] = $cat;
					if ($buff = $this->loadSubCats($cat)) {
						$data = array_merge($data, $buff);
					}
				}
			}
		}
		return $data;
	}
	function loadSubCats($pcat) {
		$data = array();
		$cats = fwgHelper::loadCategories();
		if ($cats) {
			foreach ($cats as $cat) {
				if ($cat->published and $cat->parent == $pcat->id) {
					$data[] = $cat;
					if ($buff = $this->loadSubCats($cat)) {
						$data = array_merge($data, $buff);
					}
				}
			}
		}
		return $data;
	}
}
