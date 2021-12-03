<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

class fwGalleryModelItem extends JModelLegacy {
    function getId() {
        $app = JFactory::getApplication();
        $id = $app->input->getInt('id');
        if (!$id) {
            $id = $app->getParams()->get('file_id');
        }
        return $id;
    }
    function getCategoryId($file) {
        $cid = $file->category_id;
        JFactory::getApplication()->triggerEvent('ongetFileCategoryId', array('com_fwgallery', &$cid, $file));
        return $cid;
    }
    function loadCategory($id = null) {
        $app = JFactory::getApplication();
        $gids = $app->getParams()->get('gids');
        if (/*is_null($id) and*/ is_array($gids) and count($gids) == 1) {
            if ($id) {
                $ids = fwgHelper::getChildGalleriesIDS(array($gids[0]));
                if (in_array($id, $ids)) {
                    $gids[0] = $id;
                }
            }
            $id = $gids[0];
        }
        $category = $this->getTable('category');
		if ($id) {
			$category->load($id);
		}

		$app->triggerEvent('onloadTopCategory', array('com_fwgallery', &$category));
		return $category;
	}
    function loadFile($id = null, $hit=true) {
        $app = JFactory::getApplication();

        $file = $this->getTable('file');
        if (!$id) {
            $id = $this->getId();
        }
        if ($id and $file->load($id) and $hit) {
            $file->hit();
        }
        return $file;
    }
    function _getFilesWhere($id, $params) {
        $db = JFactory::getDBO();
        $app = JFactory::getApplication();

        $where = array(
            'f.published = 1',
            'c.published = 1'
        );
        $types = array(
            'image'
        );
        $buff = $app->triggerEvent('ongetType', array('com_fwgallery'));
        foreach ($buff as $val) {
            if ($val) {
                $types[] = $val;
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

        $app->triggerEvent('ongetFileExtraSearch', array('com_fwgallery', $params, &$where));
//print_r($where); die();
        return $where;
    }
    function _getFilesOrderClause($params) {
        $model = JModelLegacy::getInstance('fwGallery', 'fwGalleryModel');
        return $model->_getFilesOrderClause($params);
    }
    function loadFiles($cid, $params) {
        $app = JFactory::getApplication();

        $extra_select = $app->triggerEvent('ongetFileExtraFields', array('com_fwgallery'));
        $extra_from = $app->triggerEvent('ongetFileExtraTables', array('com_fwgallery'));

        $db = JFactory::getDBO();
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
WHERE '.implode(' AND ', $this->_getFilesWhere($cid, $params)).'
'.$this->_getFilesOrderClause($params),
            $app->input->getInt('limitstart'),
            $app->input->getInt('limit')
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
    function loadFilesQty($cid, $params) {
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

WHERE '.implode(' AND ', $this->_getFilesWhere($cid, $params))
        );
        return $db->loadResult();
    }
    function getFilesPagination($cid, $params) {
        $app = JFactory::getApplication();
        jimport('joomla.html.pagination');
        return new JPagination(
        	$this->loadFilesQty($cid, $params),
    		$app->input->getInt('limitstart'),
    		$app->input->getInt('limit')
    	);
    }
    function getFilesOrder($params) {
        $order = JFactory::getApplication()->input->getCmd('files_order', $params->get('files_default_ordering'));
        if (!in_array($order, array('new', 'old', 'ordering', 'alpha', 'random'))) {
            $order = 'ordering';
        }
        return $order;
    }
    function getFilePosition($id, $cid, $params) {
        $app = JFactory::getApplication();
        $extra_from = $app->triggerEvent('ongetFileExtraTables', array('com_fwgallery'));
        $db = JFactory::getDBO();
        $db->setQuery('
SELECT
    f.id
FROM
    #__fwsg_file AS f
    LEFT JOIN #__viewlevels AS fa ON fa.id = f.`access`
    LEFT JOIN #__fwsg_category AS c ON c.id = f.category_id
    LEFT JOIN #__viewlevels AS ca ON ca.id = c.`access`
    LEFT JOIN #__users AS u ON u.id = f.user_id
    LEFT JOIN #__fwsg_file_image AS fi ON fi.file_id = f.id
    '.implode('', $extra_from).'
WHERE '.implode(' AND ', $this->_getFilesWhere($cid, $params)).'
'.$this->_getFilesOrderClause($params)
        );
        if ($ids = $db->loadColumn()) {
            return (object)array('pos' => (int)array_search($id, $ids), 'total_qty' => count($ids));
        }
    }
	function getDownloadFilename() {
		$file = $this->loadFile($id = null, $hit=false);
		if ($file->id) {
			$db = JFactory::getDBO();
			$db->setQuery('UPDATE #__fwsg_file SET downloads = '.((int)$file->downloads + 1).' WHERE id = '.(int)$file->id);
			$db->execute();
			if ($file->type == 'image') {
				return $this->getImage();
			} else {
				$buff = JFactory::getApplication()->triggerEvent('onfileDownloadPath', array('com_fwgallery.'.$file->type, $file));
				foreach ($buff as $link) {
					if ($link) return $link;
				}
			}
		}
	}
    function getImage() {
		$input = JFactory::getApplication()->input;
		$id = (int)$input->getInt('id');
		$js = (int)$input->getInt('js');
		$height = (int)$input->getInt('h');
		$width = (int)$input->getInt('w');
		$t_path = JPATH_SITE.'/cache/fwgallery/images/';
		$t_filename = 'f'.$id.'_'.md5($id.'h'.$height.'w'.$width.'j'.$js).'.jpg';
		if (!file_exists($t_path.$t_filename)) {
			ini_set('memory_limit', '512M');
            jimport('joomla.filesystem.file');
            $img_exts = array('gif', 'png', 'jpg', 'jpeg');

			$filename = '';
			$s_path = FWMG_STORAGE_PATH;

			$db = JFactory::getDBO();
			$db->setQuery('
SELECT
    c.parent AS _cat_parent,
    c.params AS _cat_params,
    ff.sys_filename
FROM
    `#__fwsg_file` AS f
    LEFT JOIN `#__fwsg_file_image` AS ff ON ff.file_id = f.id
    LEFT JOIN `#__fwsg_category` AS c ON c.id = f.category_id
WHERE
    f.id = '.$id);
            $file = $db->loadObject();
			if ($file) {
                $buff = $file->sys_filename;
				$ext = strtolower(JFile::getExt($buff));
				if (in_array($ext, $img_exts)) {
					$prefix = substr($buff, 0, 2);
					if (file_exists($s_path.$prefix.'/'.$buff)) {
						$s_path .= $prefix.'/';
						$filename = $buff;
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
                $params = $file?(new fwgParams($file->_cat_params, fwgHelper::getParentGalleries($file->_cat_parent))):$com_params;

				$use_wm = $params->get('use_watermark', $com_params->get('use_watermark'));
				$dw_with_wm = $com_params->get('download_with_watermark');
				$wmfile = $wmtext = $wmpos = '';
				if ($use_wm) {
					$wmfile = fwgHelper::getWatermarkFilename();
					$wmtext = $com_params->get('watermark_text');
					$wmpos = $com_params->get('watermark_position', 'left bottom');
				}

				$ext = strtolower(JFile::getExt($filename));
				if (!in_array($ext, array('gif', 'jpeg', 'jpg', 'png'))) {
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
	function getItemLink() {
		$input = JFactory::getApplication()->input;
		if ($link = $input->getString('link')) {
			return fwgHelper::route('index.php?option=com_fwgallery&view=item&id='.$link, false);
		}
	}
}
