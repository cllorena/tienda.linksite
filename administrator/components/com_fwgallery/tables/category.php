<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

class TableCategory extends JTable {
    var $id = null,
        $published = null,
		$is_public = null,
        $parent = null,
        $ordering = null,
        $access = null,
        $created = null,
        $updated = null,
        $user_id = null,
        $name = null,
        $sys_filename = null,
        $filename = null,
        $descr = null,
        $media = null,
        $media_code = null,
		$metadescr = null,
		$metakey = null,
        $params = null,
        $alias = null,
        $hits = null;

	var $_user_name = null,
		$_qty = null,
		$_images_qty = null,
        $_video_size = null;

    function __construct(&$db) {
		$this->params = new JRegistry();
        parent::__construct('#__fwsg_category', 'id', $db);
    }
    function load($id = null, $reset = true) {
        if ($id) {
            $db = JFactory::getDBO();
			$app = JFactory::getApplication();
			$extras = $app->triggerEvent('ongetCategoryExtraFields', array('com_fwgallery'));
			$qty_extras = $app->triggerEvent('ongetCategoryExtraCounterQuery', array('com_fwgallery', 'image'));

            $db->setQuery('
SELECT
    c.*,
	(SELECT u.name FROM `#__users` AS u WHERE u.id = c.user_id LIMIT 1) AS _user_name,
	(SELECT COUNT(*) FROM `#__fwsg_category` AS cc WHERE cc.parent = c.id AND cc.published = 1) AS _qty,
	(SELECT COUNT(*) FROM `#__fwsg_file` AS f WHERE (f.type = \'image\' AND f.published = 1 AND f.category_id = c.id) '.implode('', $qty_extras).') AS _images_qty
    '.implode('', $extras).'
FROM
    `#__fwsg_category` AS c
WHERE
    c.id = '.(int)$id);
//echo $db->getQuery(); die();
            if ($obj = $db->loadObject()) {
                foreach ($obj as $key=>$val) {
                    $this->$key = $val;
                }

				if ($this->media == 'mp4' and $this->media_code) {
					$path = fwgHelper::getImagePath($this->media_code);
					if (file_exists($path.$this->media_code)) {
						$this->_video_size = filesize($path.$this->media_code);
					}
                }

                if ($this->created == '0000-00-00 00:00:00') {
                    $this->created = JFactory::getDate()->toSql();
                    $db->setQuery('UPDATE `#__fwsg_category` SET created = '.$db->quote($this->created).' WHERE id = '.(int)$this->id);
                    $db->execute();
                }

				$this->params = new JRegistry($this->params);
//                $params = new fwgParams($this->params, fwgHelper::getParentGalleries($this->parent));

                $app->triggerEvent('oncalculateCategoryExtraFields', array('com_fwgallery', &$this));

                return true;
            }
        }
    }
    function check() {
        $db = JFactory::getDBO();
        $app = JFactory::getApplication();
        jimport('joomla.filesystem.file');
        if (!$this->alias) {
			$this->alias = $this->name;
		}
		$this->alias = JFilterOutput::stringURLSafe($this->alias);
		if (!$this->id) {
			$this->created = JFactory::getDate($this->created)->toSql();
			$this->user_id = JFactory::getUser()->id;
		}
		$this->updated = JFactory::getDate()->toSql();

        if (!$this->ordering) {
        	$db->setQuery('SELECT MAX(ordering) FROM #__fwsg_category WHERE parent ='.(int)$this->parent);
        	$this->ordering = (int)$db->loadResult() + 1;
        }

        if (is_array($this->params)) {
			$params = new JRegistry($this->params);
			$this->params = $params->toString();
		}

        if ($this->sys_filename and $app->input->getInt('delete_image')) {
            $path = fwgHelper::getImagePath($this->sys_filename);
            if (is_file($path.$this->sys_filename)) {
                JFile::delete($path.$this->sys_filename);
            }
            $this->sys_filename = '';
            $this->filename = '';
        }

		$filename = '';
		$path = '';
        if ($image = $app->input->files->get('image', null, 'raw') and !empty($image['tmp_name']) and empty($image['error'])) {
            $ext = strtolower(JFile::getExt($image['name']));
            do {
                $filename = md5($image['name'].rand()).'.'.$ext;
                $path = fwgHelper::getImagePath($filename);
            } while(file_exists($path.$filename));

            if (!file_exists($path)) {
                JFile::write($path.'index.html', $buff = '<html><head><title></title></head><body></body></html>');
            }
            if (JFile::copy($image['tmp_name'], $path.$filename)) {
                if ($this->sys_filename) {
                    $path = fwgHelper::getImagePath($this->sys_filename);
                    if (file_exists($path.$this->sys_filename)) {
                        JFile::delete($path.$this->sys_filename);
                    }
                }

                $this->sys_filename = $filename;
                $this->filename = $image['name'];
            }
        }
        if ($this->media_code and $app->input->getInt('delete_video')) {
			$path = fwgHelper::getImagePath($this->media_code);
            if (is_file($path.$this->media_code)) {
                JFile::delete($path.$this->media_code);
            }
            $this->media_code = '';
        }

        if (in_array($this->media, array('youtube', 'vimeo'))) {
            $this->media_code = $app->input->getString('remote_code');
            if ($this->media == 'youtube') {
                if (preg_match('#(\?|\&)v=([^&]+)#i', $this->media_code, $m)) $this->media_code = $m[2];
                elseif (preg_match('#youtu\.be/([^?]+)#i', $this->media_code, $m)) $this->media_code = $m[2];
            } else {
                if (preg_match('#vimeo.com/(\d+)#i', $this->media_code, $m)) $this->media_code = $m[1];
            }

            if (!$this->sys_filename and $this->media_code) {
                if ($this->media == 'youtube') {
                    if ($data = fwgHelper::request('http://img.youtube.com/vi/'.$this->media_code.'/0.jpg')) {
						do {
							$filename = md5($data.rand()).'.jpg';
							$path = fwgHelper::getImagePath($filename);
						} while(file_exists($path.$filename));

						if (JFile::write($path.$filename, $data)) {
                            $this->sys_filename = $filename;
						}
					}
                } else {
                    if ($data = fwgHelper::request('http://vimeo.com/api/v2/video/'.$this->media_code.'.xml') and preg_match('#<thumbnail_large>(.+)</thumbnail_large>#i', $data, $m)) {
                        if ($data = fwgHelper::request($m[1])) {
                            $ext = JFile::getExt($m[1]);
							do {
								$filename = md5($data.rand()).'.'.$ext;
								$path = fwgHelper::getImagePath($filename);
							} while(file_exists($path.$filename));

							if (JFile::write($path.$filename, $data)) {
								$this->sys_filename = $filename;
							}
                        }
                    }
                }
            }
        } elseif ($this->media == 'mp4' and $image = $app->input->files->get('file', null, 'raw') and !empty($image['tmp_name']) and empty($image['error'])) {
            $ext = strtolower(JFile::getExt($image['name']));
            do {
                $filename = md5($image['name'].rand()).'.'.$ext;
                $path = fwgHelper::getImagePath($filename);
            } while(file_exists($path.$filename));
            if (!file_exists($path)) {
                JFile::write($path.'index.html', $buff = '<html><head><title></title></head><body></body></html>');
            }
            if (JFile::copy($image['tmp_name'], $path.$filename)) {
				if ($this->media_code) {
					$path = fwgHelper::getImagePath($this->media_code);
					if (is_file($path.$this->media_code)) {
						JFile::delete($path.$this->media_code);
					}
				}
				$this->media_code = $filename;
			}
        }
        $app->triggerEvent('onCheckCategory', array('com_fwgallery', &$this));

		if (strlen($this->metakey) > 200) {
			$this->metakey = substr($this->metakey, 0, 200);
		}
		if (strlen($this->metadescr) > 200) {
			$this->metadescr = substr($this->metadescr, 0, 200);
		}

        return true;
    }
	function store($upd=null) {
		$isnew = !$this->id;
		if (parent::store($upd)) {
            JFactory::getApplication()->triggerEvent('onStoreCategory', array('com_fwgallery', &$this, $isnew));
			fwgHelper::clearImageCache('c'.$this->id);
			return true;
		}
	}
	function delete($id=null) {
		if (parent::delete($id)) {
			jimport('joomla.filesystem.file');

			if ($this->sys_filename) {
				$path = fwgHelper::getImagePath($this->sys_filename);
				if (is_file($path.$this->sys_filename)) {
					JFile::delete($path.$this->sys_filename);
				}
			}

			JFactory::getApplication()->triggerEvent('onDeleteCategory', array('com_fwgallery', $id));
			fwgHelper::clearImageCache('c'.$id);
			return true;
		}
	}
}
